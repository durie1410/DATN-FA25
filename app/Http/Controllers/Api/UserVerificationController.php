<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserVerificationController extends Controller
{
	public function submit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'nullable|integer|exists:users,id',
			'id_type' => 'required|string',
			'id_number' => 'required|string',
			'id_images' => 'nullable|array',
			'id_images.*' => 'string',
			'notes' => 'nullable|string',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}

		$data = $validator->validated();
		$userId = $data['user_id'] ?? ($request->user()->id ?? null);
		if (!$userId) return response()->json(['message' => 'user_required'], 422);

		DB::table('user_verifications')->updateOrInsert(
			['user_id' => $userId],
			[
				'id_type' => $data['id_type'],
				'id_number' => $data['id_number'],
				'id_images' => isset($data['id_images']) ? json_encode($data['id_images']) : null,
				'verified_status' => 'pending',
				'notes' => $data['notes'] ?? null,
				'updated_at' => Carbon::now(),
				'created_at' => Carbon::now(),
			]
		);

		return response()->json(['message' => 'kyc_submitted', 'user_id' => (int) $userId]);
	}

	public function myStatus(Request $request)
	{
		$user = $request->user();
		if (!$user) return response()->json(['message' => 'Unauthenticated'], 401);
		$ver = DB::table('user_verifications')->where('user_id', $user->id)->first();
		return response()->json(['data' => $ver]);
	}

	public function getStatus($userId)
	{
		$ver = DB::table('user_verifications')->where('user_id', $userId)->first();
		if (!$ver) return response()->json(['message' => 'not_found'], 404);
		return response()->json(['data' => $ver]);
	}

	public function review($userId, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'status' => 'required|string|in:approved,rejected,pending',
			'notes' => 'nullable|string',
		]);
		if ($validator->fails()) {
			return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
		}
		$exists = DB::table('user_verifications')->where('user_id', $userId)->exists();
		if (!$exists) return response()->json(['message' => 'not_found'], 404);
		DB::table('user_verifications')->where('user_id', $userId)->update([
			'verified_status' => $request->status,
			'notes' => $request->notes,
			'updated_at' => Carbon::now(),
		]);
		return response()->json(['message' => 'kyc_updated']);
	}
}


