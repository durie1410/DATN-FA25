<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_user_can_access_staff_dashboard()
    {
        // Tạo user với role staff
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);
        
        $staff->assignRole('staff');

        // Đăng nhập với staff user
        $response = $this->actingAs($staff)->get('/staff');
        
        // Kiểm tra có thể truy cập được không
        $response->assertStatus(200);
    }

    public function test_admin_user_can_access_staff_dashboard()
    {
        // Tạo user với role admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        
        $admin->assignRole('admin');

        // Đăng nhập với admin user
        $response = $this->actingAs($admin)->get('/staff');
        
        // Admin cũng có thể truy cập staff dashboard
        $response->assertStatus(200);
    }

    public function test_user_cannot_access_staff_dashboard()
    {
        // Tạo user với role user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
        
        $user->assignRole('user');

        // Đăng nhập với regular user
        $response = $this->actingAs($user)->get('/staff');
        
        // User thường không thể truy cập staff dashboard
        $response->assertStatus(403);
    }
}

