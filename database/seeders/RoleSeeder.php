<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
	public function run()
	{
		$roles = [
			['name' => 'admin', 'description' => 'Administrator'],
			['name' => 'librarian', 'description' => 'Librarian / front desk'],
			['name' => 'warehouse', 'description' => 'Warehouse staff'],
			['name' => 'user', 'description' => 'Member / patron'],
		];
		foreach ($roles as $r) {
			DB::table('roles')->updateOrInsert(['name' => $r['name']], $r + ['created_at' => now(), 'updated_at' => now()]);
		}

		// assign role to first user if exists
		$adminId = optional(DB::table('users')->orderBy('id')->first())->id ?? null;
		if ($adminId) {
			$roleId = optional(DB::table('roles')->where('name', 'admin')->first())->id ?? null;
			if ($roleId) {
				DB::table('user_roles')->updateOrInsert(['user_id' => $adminId, 'role_id' => $roleId], ['created_at' => now(), 'updated_at' => now()]);
			}
		}
	}
}


