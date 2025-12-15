<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InventoryFlowController extends Controller
{
	public function intake(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'book_id' => 'required|integer|exists:books,id',
			'quantity' => 'required|integer|min:1',
			'location_id' => 'required|integer|exists:locations,id',
			'barcodes' => 'array',
			'barcodes.*' => 'string',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}

		$data = $validator->validated();

		$createdItems = [];
		DB::transaction(function () use ($data, &$createdItems) {
			$now = Carbon::now();
			$barcodes = $data['barcodes'] ?? [];
			for ($i = 0; $i < $data['quantity']; $i++) {
				$barcode = $barcodes[$i] ?? ('BK' . Str::upper(Str::random(10)));
				$itemId = DB::table('book_items')->insertGetId([
					'book_id' => $data['book_id'],
					'barcode' => $barcode,
					'condition_grade' => 'good',
					'status' => 'in_stock',
					'location_id' => $data['location_id'],
					'created_at' => $now,
					'updated_at' => $now,
				]);
				$createdItems[] = $itemId;

				DB::table('stock_movements')->insert([
					'book_item_id' => $itemId,
					'book_id' => $data['book_id'],
					'type' => 'inbound',
					'qty' => 1,
					'from_location_id' => null,
					'to_location_id' => $data['location_id'],
					'reference_type' => 'intake',
					'reference_id' => null,
					'created_by' => auth()->id(),
					'created_at' => $now,
					'updated_at' => $now,
				]);
			}
		});

		return response()->json(['message' => 'intake_ok', 'book_item_ids' => $createdItems]);
	}

	public function move(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'book_item_ids' => 'required|array|min:1',
			'book_item_ids.*' => 'integer|exists:book_items,id',
			'from_location_id' => 'nullable|integer|exists:locations,id',
			'to_location_id' => 'required|integer|exists:locations,id',
			'type' => 'required|string|in:to_display,to_warehouse,adjust',
			'reason' => 'nullable|string',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}
		$data = $validator->validated();

		$statusMap = [
			'to_display' => 'displayed',
			'to_warehouse' => 'in_stock',
			'adjust' => null,
		];
		$newStatus = $statusMap[$data['type']];
		$now = Carbon::now();

		DB::transaction(function () use ($data, $newStatus, $now) {
			$items = DB::table('book_items')->whereIn('id', $data['book_item_ids'])->get(['id', 'location_id', 'status', 'book_id']);
			foreach ($items as $item) {
				DB::table('book_items')->where('id', $item->id)->update([
					'location_id' => $data['to_location_id'],
					'status' => $newStatus ? $newStatus : $item->status,
					'updated_at' => $now,
				]);
				DB::table('stock_movements')->insert([
					'book_item_id' => $item->id,
					'book_id' => $item->book_id,
					'type' => $data['type'],
					'qty' => 1,
					'from_location_id' => $item->location_id,
					'to_location_id' => $data->to_location_id ?? $data['to_location_id'],
					'reference_type' => 'move',
					'reference_id' => null,
					'created_by' => auth()->id(),
					'created_at' => $now,
					'updated_at' => $now,
				]);
			}
		});

		return response()->json(['message' => 'move_ok', 'moved' => count($data['book_item_ids'])]);
	}

	public function stock(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'book_id' => 'nullable|integer|exists:books,id',
			'location_id' => 'nullable|integer|exists:locations,id',
			'status' => 'nullable|string',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid filters', 'errors' => $validator->errors()], 422);
		}

		$q = DB::table('book_items');
		if ($request->filled('book_id')) $q->where('book_id', $request->book_id);
		if ($request->filled('location_id')) $q->where('location_id', $request->location_id);
		if ($request->filled('status')) $q->where('status', $request->status);

		$summary = $q->select('book_id', 'location_id', 'status', DB::raw('COUNT(*) as qty'))
			->groupBy('book_id', 'location_id', 'status')
			->orderBy('book_id')->get();

		return response()->json(['data' => $summary]);
	}
}
