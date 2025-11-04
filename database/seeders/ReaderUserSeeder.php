<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reader;
use Illuminate\Support\Facades\Hash;

class ReaderUserSeeder extends Seeder
{
    public function run()
    {
        // Tạo user admin nếu chưa có
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        // Tạo user độc giả mẫu
        $readerUser = User::firstOrCreate(
            ['email' => 'reader@library.com'],
            [
                'name' => 'Nguyễn Văn A',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        // Tạo reader profile cho user độc giả
        Reader::firstOrCreate(
            ['user_id' => $readerUser->id],
            [
                'ho_ten' => 'Nguyễn Văn A',
                'email' => 'reader@library.com',
                'so_dien_thoai' => '0123456789',
                'ngay_sinh' => '1990-01-01',
                'gioi_tinh' => 'Nam',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'so_the_doc_gia' => 'RD001',
                'ngay_cap_the' => now()->subDays(30),
                'ngay_het_han' => now()->addYear(),
                'trang_thai' => 'Hoat dong'
            ]
        );

        // Tạo thêm một vài user và reader khác
        $users = [
            [
                'name' => 'Trần Thị B',
                'email' => 'user2@library.com',
                'password' => Hash::make('password'),
                'role' => 'user'
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'user3@library.com',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            Reader::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'ho_ten' => $userData['name'],
                    'email' => $userData['email'],
                    'so_dien_thoai' => '012345678' . rand(0, 9),
                    'ngay_sinh' => '199' . rand(0, 9) . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
                    'gioi_tinh' => ['Nam', 'Nu'][rand(0, 1)],
                    'dia_chi' => rand(1, 999) . ' Đường XYZ, Quận ' . rand(1, 12) . ', TP.HCM',
                    'so_the_doc_gia' => 'RD' . sprintf('%03d', rand(2, 999)),
                    'ngay_cap_the' => now()->subDays(rand(1, 365)),
                    'ngay_het_han' => now()->addYear(),
                    'trang_thai' => 'Hoat dong'
                ]
            );
        }
    }
}