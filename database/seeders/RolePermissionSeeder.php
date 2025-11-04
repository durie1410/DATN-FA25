<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Books
            'view-books',
            'create-books',
            'edit-books',
            'delete-books',
            
            // Categories
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            
            // Readers
            'view-readers',
            'create-readers',
            'edit-readers',
            'delete-readers',
            
            // Borrows
            'view-borrows',
            'create-borrows',
            'edit-borrows',
            'delete-borrows',
            'return-books',
            
            // Reservations
            'view-reservations',
            'create-reservations',
            'edit-reservations',
            'delete-reservations',
            'confirm-reservations',
            
            // Reviews
            'view-reviews',
            'create-reviews',
            'edit-reviews',
            'delete-reviews',
            'approve-reviews',
            
            // Fines
            'view-fines',
            'create-fines',
            'edit-fines',
            'delete-fines',
            'waive-fines',
            
            // Reports
            'view-reports',
            'export-reports',
            
            // Notifications
            'view-notifications',
            'send-notifications',
            'manage-templates',
            
            // Users & Roles
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-roles',
            'manage-permissions',
            
            // Settings
            'view-settings',
            'edit-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Tạo roles với 3 vai trò chính: admin, staff, user
        $roles = [
            'admin' => [
                'view-dashboard',
                'view-books', 'create-books', 'edit-books', 'delete-books',
                'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
                'view-readers', 'create-readers', 'edit-readers', 'delete-readers',
                'view-borrows', 'create-borrows', 'edit-borrows', 'delete-borrows', 'return-books',
                'view-reservations', 'create-reservations', 'edit-reservations', 'delete-reservations', 'confirm-reservations',
                'view-reviews', 'create-reviews', 'edit-reviews', 'delete-reviews', 'approve-reviews',
                'view-fines', 'create-fines', 'edit-fines', 'delete-fines', 'waive-fines',
                'view-reports', 'export-reports',
                'view-notifications', 'send-notifications', 'manage-templates',
                'view-users', 'create-users', 'edit-users', 'delete-users', 'manage-roles', 'manage-permissions',
                'view-settings', 'edit-settings',
            ],
            'staff' => [
                'view-dashboard',
                'view-books', 'create-books', 'edit-books',
                'view-categories', 'create-categories', 'edit-categories',
                'view-readers', 'create-readers', 'edit-readers',
                'view-borrows', 'create-borrows', 'edit-borrows', 'return-books',
                'view-reservations', 'create-reservations', 'edit-reservations', 'confirm-reservations',
                'view-reviews', 'approve-reviews',
                'view-fines', 'create-fines', 'edit-fines',
                'view-reports',
                'view-notifications', 'send-notifications',
            ],
            'user' => [
                'view-books',
                'view-categories',
                'create-reviews',
                'view-reservations', 'create-reservations',
                'view-notifications',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Gán role cho admin user
        $adminUser = User::where('email', 'admin@library.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        // Tạo thêm một số user mẫu với các role khác nhau
        $staff = User::firstOrCreate([
            'email' => 'staff@library.com'
        ], [
            'name' => 'Nhân viên thư viện',
            'password' => bcrypt('123456'),
            'role' => 'staff',
        ]);
        $staff->assignRole('staff');

        $user = User::firstOrCreate([
            'email' => 'user@library.com'
        ], [
            'name' => 'Người dùng',
            'password' => bcrypt('123456'),
            'role' => 'user',
        ]);
        $user->assignRole('user');

        $this->command->info('Roles and permissions created successfully!');
    }
}