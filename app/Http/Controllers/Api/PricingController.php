<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Inventory;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
		$days = isset($data['days']) ? (int) $data['days'] : 1;
		
		// Kiểm tra xem user có thẻ độc giả không
		$hasCard = false;
		if (isset($data['user_id'])) {
			$reader = Reader::where('user_id', $data['user_id'])->first();
			$hasCard = $reader ? true : false;
		}

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
		$totalRental = 0;
		$totalDeposit = 0;
		
		// Tính ngày mượn và ngày hẹn trả
		$ngayMuon = now();
		$ngayHenTra = now()->addDays($days);

		foreach ($data['book_ids'] as $bookId) {
			$book = Book::find($bookId);
			if (!$book) {
				continue;
			}

			// Lấy inventory đầu tiên có sẵn để tính tiền cọc (dựa trên tình trạng)
			$inventory = Inventory::where('book_id', $bookId)
				->where('status', 'Co san')
				->first();
			
			// Nếu không có inventory, sử dụng tình trạng mặc định
			if (!$inventory) {
				$inventory = new Inventory();
				$inventory->condition = 'Trung binh';
				$inventory->status = 'Co san';
			}

			// Sử dụng PricingService để tính phí
			$fees = \App\Services\PricingService::calculateFees(
				$book,
				$inventory,
				$ngayMuon,
				$ngayHenTra,
				$hasCard
			);

			$items[] = [
				'book_id' => $bookId,
				'rental_fee' => $fees['tien_thue'],
				'rental_fee_per_day' => $days > 0 ? round($fees['tien_thue'] / $days) : 0,
				'deposit' => $fees['tien_coc'],
			];
			
			$totalRental += $fees['tien_thue'];
			$totalDeposit += $fees['tien_coc'];
		}

		return response()->json([
			'items' => $items,
			'total_rental_fee' => $totalRental,
			'total_deposit' => $totalDeposit,
			'shipping_fee' => $shipFee,
			'payable_now' => $totalDeposit + $shipFee,
		]);
	}
}
