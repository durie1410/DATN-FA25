<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibraryPricingSeeder extends Seeder
{
	public function run()
	{
		$now = Carbon::now();
		$rows = [
			[
				'rule_type' => 'rental',
				'scope' => 'global',
				'condition' => json_encode(['unit' => 'flat_vnd']),
				'amount_type' => 'flat',
				'amount_value' => config('library.rental_flat', 10000),
				'priority' => 10,
				'active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'rule_type' => 'deposit',
				'scope' => 'user_status',
				'condition' => json_encode(['kyc' => 'verified']),
				'amount_type' => 'flat',
				'amount_value' => config('library.deposit_verified', 50000),
				'priority' => 10,
				'active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'rule_type' => 'deposit',
				'scope' => 'user_status',
				'condition' => json_encode(['kyc' => 'guest_or_unverified']),
				'amount_type' => 'flat',
				'amount_value' => config('library.deposit_unverified', 100000),
				'priority' => 20,
				'active' => 1,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];

		foreach ($rows as $row) {
			DB::table('pricing_rules')->updateOrInsert(
				[
					'rule_type' => $row['rule_type'],
					'scope' => $row['scope'],
					'condition' => $row['condition'],
				],
				$row
			);
		}
	}
}


