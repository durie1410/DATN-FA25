<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
	public function quote(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'book_ids' => 'required|array|min:1',
			'book_ids.*' => 'integer',
			'user_id' => 'nullable|integer',
			'kyc_status' => 'nullable|string|in:verified,unverified,guest',
			'delivery_type' => 'nullable|string|in:pickup,ship',
			'distance' => 'nullable|numeric|min:0',
			'days' => 'nullable|integer|min:1|max:30',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}

		$data = $validator->validated();
		$kyc = $data['kyc_status'] ?? 'guest';

		// Try pricing_rules first
		$rentalPerBook = null;
		$depositPerBook = null;
		try {
			$ruleRental = DB::table('pricing_rules')
				->where('rule_type', 'rental')
				->where('scope', 'global')
				->where('active', 1)
				->orderByDesc('priority')
				->first();
			if ($ruleRental && $ruleRental->amount_type === 'flat') {
				$rentalPerBook = (int) $ruleRental->amount_value;
			}

			$rulesDeposit = DB::table('pricing_rules')
				->where('rule_type', 'deposit')
				->where('scope', 'user_status')
				->where('active', 1)
				->orderByDesc('priority')
				->get();
			foreach ($rulesDeposit as $r) {
				$cond = json_decode($r->condition, true) ?? [];
				if (($cond['kyc'] ?? '') === 'verified' && $kyc === 'verified' && $r->amount_type === 'flat') {
					$depositPerBook = (int) $r->amount_value;
					break;
				}
				if (($cond['kyc'] ?? '') === 'guest_or_unverified' && in_array($kyc, ['guest','unverified'], true) && $r->amount_type === 'flat') {
					$depositPerBook = (int) $r->amount_value;
					break;
				}
			}
		} catch (\Throwable $e) {
			// fall back silently
		}

		// Fallback to config
		if ($rentalPerBook === null) $rentalPerBook = (int) config('library.rental_flat', 10000);
		if ($depositPerBook === null) {
			$depositPerBook = $kyc === 'verified'
				? (int) config('library.deposit_verified', 50000)
				: (int) config('library.deposit_unverified', 100000);
		}

		// Tính phí thuê theo số ngày (nếu có tham số days)
		$days = isset($data['days']) ? (int) $data['days'] : 1;
		$rentalPerBookPerDay = $rentalPerBook; // Phí thuê mỗi ngày
		$rentalPerBookTotal = $rentalPerBookPerDay * $days; // Tổng phí thuê = phí mỗi ngày * số ngày

		// Tính tiền ship dựa trên khoảng cách
		$shipFee = 0;
		if (($data['delivery_type'] ?? 'pickup') === 'ship') {
			$distance = floatval($data['distance'] ?? 0);
			if ($distance > 5) {
				// Nếu > 5km, mỗi km trên 5km thêm 5.000₫
				$extraKm = $distance - 5;
				$shipFee = (int) ($extraKm * 5000);
			}
		}

		$items = [];
		foreach ($data['book_ids'] as $bookId) {
			$items[] = [
				'book_id' => $bookId,
				'rental_fee' => $rentalPerBookTotal, // Phí thuê đã tính theo số ngày
				'rental_fee_per_day' => $rentalPerBookPerDay, // Phí thuê mỗi ngày
				'deposit' => $depositPerBook,
			];
		}
		$totalRental = $rentalPerBookTotal * count($items);
		$totalDeposit = $depositPerBook * count($items);

		return response()->json([
			'items' => $items,
			'total_rental_fee' => $totalRental,
			'total_deposit' => $totalDeposit,
			'shipping_fee' => $shipFee,
			'payable_now' => $totalDeposit + $shipFee,
		]);
	}
}
