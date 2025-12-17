<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
	public function stock()
	{
		$rows = DB::table('book_items')
			->select('book_id','status', DB::raw('COUNT(*) as qty'))
			->groupBy('book_id','status')
			->get();
		return response()->json(['data' => $rows]);
	}

	public function loans()
	{
		$rows = DB::table('loans')
			->select(DB::raw('status, COUNT(*) as count'))
			->groupBy('status')
			->get();
		return response()->json(['data' => $rows]);
	}

	public function utilization()
	{
		$rows = DB::table('seat_reservations')
			->select(DB::raw('DATE(start_time) as day, COUNT(*) as reservations'))
			->groupBy(DB::raw('DATE(start_time)'))
			->orderBy('day','desc')
			->limit(30)
			->get();
		return response()->json(['data' => $rows]);
	}
}


