<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Services\Notifier;

class LoanFlowController extends Controller
{
	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'nullable|integer|exists:users,id',
			'borrower_name' => 'required_without:user_id|string',
			'phone' => 'required_without:user_id|string',
			'address_id' => 'nullable|integer|exists:addresses,id',
			'delivery_type' => 'required|string|in:pickup,ship',
			'book_item_ids' => 'required|array|min:1',
			'book_item_ids.*' => 'integer|exists:book_items,id',
			'kyc_status' => 'nullable|string|in:verified,unverified,guest',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}
		$data = $validator->validated();

		$rentalPerBook = (int) (config('library.rental_flat', 10000));
		$depositVerified = (int) (config('library.deposit_verified', 50000));
		$depositUnverified = (int) (config('library.deposit_unverified', 100000));
		$kyc = $data['kyc_status'] ?? 'guest';
		$depositPerBook = $kyc === 'verified' ? $depositVerified : $depositUnverified;

		$loanId = null;
		$now = Carbon::now();
		DB::transaction(function () use ($data, $rentalPerBook, $depositPerBook, $now, &$loanId) {
			$loanId = DB::table('loans')->insertGetId([
				'user_id' => $data['user_id'] ?? null,
				'borrower_name' => $data['borrower_name'] ?? '',
				'phone' => $data['phone'] ?? '',
				'address_id' => $data['address_id'] ?? null,
				'channel' => 'online',
				'delivery_type' => $data['delivery_type'],
				'status' => 'pending',
				'deposit_required' => $depositPerBook * count($data['book_item_ids']),
				'rental_fee_estimate' => $rentalPerBook * count($data['book_item_ids']),
				'due_date' => null,
				'notes' => null,
				'created_at' => $now,
				'updated_at' => $now,
			]);

			foreach ($data['book_item_ids'] as $bookItemId) {
				DB::table('loan_items')->insert([
					'loan_id' => $loanId,
					'book_item_id' => $bookItemId,
					'rental_fee' => $rentalPerBook,
					'deposit_amount' => $depositPerBook,
					'returned_at' => null,
					'condition_on_return' => null,
					'late_fee' => 0,
					'lost_fee' => 0,
					'created_at' => $now,
					'updated_at' => $now,
				]);
			}
		});

		return response()->json(['message' => 'loan_created', 'loan_id' => $loanId], 201);
	}

	public function confirm($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'deposit_paid' => 'required|boolean',
			'payment_ref' => 'nullable|string',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}

		$loan = DB::table('loans')->where('id', $id)->first();
		if (!$loan) return response()->json(['message' => 'loan_not_found'], 404);

        $newStatus = $request->boolean('deposit_paid') ? 'confirmed' : 'pending';
        DB::table('loans')->where('id', $id)->update([
            'status' => $newStatus,
            'updated_at' => Carbon::now(),
        ]);
        if ($request->boolean('deposit_paid')) {
			DB::table('deposits')->insert([
				'loan_id' => $id,
				'user_id' => $loan->user_id,
				'amount_required' => $loan->deposit_required,
				'amount_held' => $loan->deposit_required,
				'hold_method' => 'cash',
				'released_amount' => 0,
				'released_at' => null,
				'status' => 'held',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now(),
			]);

            // create shipment if delivery_type=ship (MVP)
            if ($loan->delivery_type === 'ship') {
                DB::table('shipments')->insert([
                    'loan_id' => $id,
                    'to_address_id' => $loan->address_id,
                    'ship_fee' => (int) config('library.ship_fee_default', 15000),
                    'courier' => 'local',
                    'tracking_code' => null,
                    'status' => 'label_created',
                    'cod_amount' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // notify user (if email exists)
            $email = optional(DB::table('users')->where('id', $loan->user_id)->first())->email ?? null;
            Notifier::sendEmail($email, 'Loan Confirmed', "Your loan #$id has been confirmed. Please pickup or await shipping.");
		}

		return response()->json(['message' => 'loan_confirmed']);
	}

	public function pickup($id)
	{
		$loan = DB::table('loans')->where('id', $id)->first();
		if (!$loan) return response()->json(['message' => 'loan_not_found'], 404);
		$due = Carbon::now()->addDays((int) config('library.loan_days', 7));
		DB::table('loans')->where('id', $id)->update([
			'status' => 'picked_up',
			'due_date' => $due,
			'updated_at' => Carbon::now(),
		]);

		// Mark items as loaned
		$itemIds = DB::table('loan_items')->where('loan_id', $id)->pluck('book_item_id');
		DB::table('book_items')->whereIn('id', $itemIds)->update([
			'status' => 'loaned',
			'updated_at' => Carbon::now(),
		]);

        // notify user due date
        $email = optional(DB::table('users')->where('id', $loan->user_id)->first())->email ?? null;
        Notifier::sendEmail($email, 'Books Picked Up', 'Due date: ' . $due->toDateTimeString());
        return response()->json(['message' => 'pickup_ok', 'due_date' => $due->toDateTimeString()]);
	}

	public function returnBook($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'condition' => 'nullable|string',
			'late_days' => 'nullable|integer|min:0',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}
		$loan = DB::table('loans')->where('id', $id)->first();
		if (!$loan) return response()->json(['message' => 'loan_not_found'], 404);

		$lateDays = (int) ($request->late_days ?? 0);
		$lateRate = (int) config('library.late_fee_per_day', 2000);
		$lateFee = $lateDays * $lateRate;

		// mark items returned
		$itemIds = DB::table('loan_items')->where('loan_id', $id)->pluck('id');
		// SECURITY FIX: Validate lateFee is numeric before using in raw query
		$lateFee = is_numeric($lateFee) ? (float) $lateFee : 0;
		DB::table('loan_items')->whereIn('id', $itemIds)->update([
			'returned_at' => Carbon::now(),
			'condition_on_return' => $request->condition,
			'late_fee' => DB::raw("late_fee + {$lateFee}"), // Safe: $lateFee is validated as numeric
			'updated_at' => Carbon::now(),
		]);

		$bookItemIds = DB::table('loan_items')->where('loan_id', $id)->pluck('book_item_id');
		DB::table('book_items')->whereIn('id', $bookItemIds)->update([
			'status' => 'in_stock',
			'updated_at' => Carbon::now(),
		]);

		return response()->json(['message' => 'return_recorded', 'late_fee' => $lateFee]);
	}

	public function close($id)
	{
		$loan = DB::table('loans')->where('id', $id)->first();
		if (!$loan) return response()->json(['message' => 'loan_not_found'], 404);

        // For MVP: refund any held deposit fully and mark as closed
		DB::table('deposits')->where('loan_id', $id)->update([
			'released_amount' => DB::raw('amount_held'),
			'released_at' => Carbon::now(),
			'status' => 'partially_released',
			'updated_at' => Carbon::now(),
		]);
        $held = optional(DB::table('deposits')->where('loan_id', $id)->first())->amount_held ?? 0;
        if ($held > 0) {
            DB::table('refunds')->insert([
                'loan_id' => $id,
                'amount' => $held,
                'method' => 'cash',
                'refunded_at' => Carbon::now(),
                'reason' => 'Deposit release (MVP)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
		DB::table('loans')->where('id', $id)->update([
			'status' => 'closed',
			'updated_at' => Carbon::now(),
		]);

		return response()->json(['message' => 'loan_closed']);
	}
}
