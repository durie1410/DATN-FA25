<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Librarian;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo user cho thủ thư trưởng
        $user1 = User::create([
            'name' => 'Nguyễn Thị Lan',
            'email' => 'lan.nguyen@library.vn',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // Tạo user cho thủ thư phó
        $user2 = User::create([
            'name' => 'Trần Văn Minh',
            'email' => 'minh.tran@library.vn',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        // Tạo user cho thủ thư
        $user3 = User::create([
            'name' => 'Lê Thị Hoa',
            'email' => 'hoa.le@library.vn',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $librarians = [
            [
                'user_id' => $user1->id,
                'ho_ten' => 'Nguyễn Thị Lan',
                'ma_thu_thu' => 'TT001',
                'email' => 'lan.nguyen@library.vn',
                'so_dien_thoai' => '0987654321',
                'ngay_sinh' => '1980-05-15',
                'gioi_tinh' => 'female',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'chuc_vu' => 'Thủ thư trưởng',
                'phong_ban' => 'Phòng Quản lý Thư viện',
                'ngay_vao_lam' => '2010-01-15',
                'ngay_het_han_hop_dong' => '2025-01-15',
                'luong_co_ban' => 15000000,
                'trang_thai' => 'active',
                'bang_cap' => 'Thạc sĩ Thư viện học',
                'kinh_nghiem' => '15 năm kinh nghiệm trong lĩnh vực thư viện',
                'ghi_chu' => 'Thủ thư có kinh nghiệm lâu năm',
            ],
            [
                'user_id' => $user2->id,
                'ho_ten' => 'Trần Văn Minh',
                'ma_thu_thu' => 'TT002',
                'email' => 'minh.tran@library.vn',
                'so_dien_thoai' => '0987654322',
                'ngay_sinh' => '1985-08-20',
                'gioi_tinh' => 'male',
                'dia_chi' => '456 Đường DEF, Quận 2, TP.HCM',
                'chuc_vu' => 'Thủ thư phó',
                'phong_ban' => 'Phòng Quản lý Thư viện',
                'ngay_vao_lam' => '2012-03-01',
                'ngay_het_han_hop_dong' => '2024-03-01',
                'luong_co_ban' => 12000000,
                'trang_thai' => 'active',
                'bang_cap' => 'Cử nhân Thông tin - Thư viện',
                'kinh_nghiem' => '12 năm kinh nghiệm trong lĩnh vực thư viện',
                'ghi_chu' => 'Thủ thư chuyên về công nghệ thông tin',
            ],
            [
                'user_id' => $user3->id,
                'ho_ten' => 'Lê Thị Hoa',
                'ma_thu_thu' => 'TT003',
                'email' => 'hoa.le@library.vn',
                'so_dien_thoai' => '0987654323',
                'ngay_sinh' => '1990-12-10',
                'gioi_tinh' => 'female',
                'dia_chi' => '789 Đường GHI, Quận 3, TP.HCM',
                'chuc_vu' => 'Thủ thư',
                'phong_ban' => 'Phòng Phục vụ Bạn đọc',
                'ngay_vao_lam' => '2015-06-01',
                'ngay_het_han_hop_dong' => '2025-06-01',
                'luong_co_ban' => 10000000,
                'trang_thai' => 'active',
                'bang_cap' => 'Cử nhân Văn học',
                'kinh_nghiem' => '8 năm kinh nghiệm trong lĩnh vực thư viện',
                'ghi_chu' => 'Thủ thư chuyên về phục vụ bạn đọc',
            ],
        ];

        foreach ($librarians as $librarian) {
            Librarian::create($librarian);
        }
    }
}
