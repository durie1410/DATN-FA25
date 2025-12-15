<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReservationFlowController extends Controller
{
	public function availability(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'space_id' => 'nullable|integer|exists:spaces,id',
			'start_time' => 'required|date',
			'end_time' => 'required|date|after:start_time',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}

		$spaceId = $request->space_id;
		$start = new Carbon($request->start_time);
		$end = new Carbon($request->end_time);
		$capacity = DB::table('spaces')->when($spaceId, fn($q)=>$q->where('id', $spaceId))->sum('capacity');

		$busy = DB::table('seat_reservations')
			->whereIn('status', ['pending','confirmed','seated'])
			->when($spaceId, fn($q)=>$q->where('space_id', $spaceId))
			->where(function($q) use ($start, $end) {
				$q->whereBetween('start_time', [$start, $end])
					->orWhereBetween('end_time', [$start, $end])
					->orWhere(function($q2) use ($start, $end) {
						$q2->where('start_time', '<=', $start)->where('end_time', '>=', $end);
					});
			})
			->count();

		return response()->json([
			'capacity' => (int) $capacity,
			'busy' => (int) $busy,
			'available' => max(0, (int)$capacity - (int)$busy),
		]);
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'nullable|integer|exists:users,id',
			'full_name' => 'required_without:user_id|string',
			'phone' => 'required_without:user_id|string',
			'space_id' => 'nullable|integer|exists:spaces,id',
			'table_id' => 'nullable|integer|exists:tables,id',
			'start_time' => 'required|date',
			'end_time' => 'required|date|after:start_time',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}

		$holdMinutes = (int) config('library.reservation_hold_minutes', 3);
		$start = new Carbon($request->start_time);
		$holdUntil = (clone $start)->addMinutes($holdMinutes);

		$id = DB::table('seat_reservations')->insertGetId([
			'user_id' => $request->user_id,
			'full_name' => $request->full_name ?? '',
			'phone' => $request->phone ?? '',
			'space_id' => $request->space_id,
			'table_id' => $request->table_id,
			'start_time' => $request->start_time,
			'end_time' => $request->end_time,
			'hold_until' => $holdUntil,
			'status' => 'pending',
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);

		return response()->json(['reservation_id' => $id, 'hold_until' => $holdUntil->toDateTimeString()]);
	}

	public function checkIn($id)
	{
		$res = DB::table('seat_reservations')->where('id', $id)->first();
		if (!$res) return response()->json(['message' => 'not_found'], 404);

		$now = Carbon::now();
		if ($res->hold_until && $now->gt(new Carbon($res->hold_until))) {
			DB::table('seat_reservations')->where('id', $id)->update([
				'status' => 'no_show',
				'updated_at' => $now,
			]);
			return response()->json(['message' => 'no_show']);
		}

		DB::table('seat_reservations')->where('id', $id)->update([
			'status' => 'seated',
			'check_in_at' => $now,
			'updated_at' => $now,
		]);

		return response()->json(['message' => 'checked_in']);
	}
}
