<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculties = [
            [
                'ten_khoa' => 'Khoa Công nghệ Thông tin',
                'ma_khoa' => 'CNTT',
                'mo_ta' => 'Khoa chuyên đào tạo về công nghệ thông tin, phần mềm và hệ thống',
                'truong_khoa' => 'PGS.TS. Nguyễn Văn A',
                'so_dien_thoai' => '024 3754 1001',
                'email' => 'cntt@vnu.edu.vn',
                'dia_chi' => 'Tầng 2, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://cntt.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_khoa' => 'Khoa Kinh tế',
                'ma_khoa' => 'KT',
                'mo_ta' => 'Khoa chuyên đào tạo về kinh tế học, quản trị kinh doanh và tài chính',
                'truong_khoa' => 'GS.TS. Trần Thị B',
                'so_dien_thoai' => '024 3754 1002',
                'email' => 'kt@vnu.edu.vn',
                'dia_chi' => 'Tầng 3, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://kt.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_khoa' => 'Khoa Ngoại ngữ',
                'ma_khoa' => 'NN',
                'mo_ta' => 'Khoa chuyên đào tạo về ngôn ngữ học và dịch thuật',
                'truong_khoa' => 'TS. Lê Văn C',
                'so_dien_thoai' => '024 3754 1003',
                'email' => 'nn@vnu.edu.vn',
                'dia_chi' => 'Tầng 4, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://nn.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_khoa' => 'Khoa Luật',
                'ma_khoa' => 'LUAT',
                'mo_ta' => 'Khoa chuyên đào tạo về luật học và pháp lý',
                'truong_khoa' => 'PGS.TS. Phạm Thị D',
                'so_dien_thoai' => '024 3754 1004',
                'email' => 'luat@vnu.edu.vn',
                'dia_chi' => 'Tầng 5, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://luat.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_khoa' => 'Khoa Sư phạm',
                'ma_khoa' => 'SP',
                'mo_ta' => 'Khoa chuyên đào tạo về giáo dục và sư phạm',
                'truong_khoa' => 'GS.TS. Hoàng Văn E',
                'so_dien_thoai' => '024 3754 1005',
                'email' => 'sp@vnu.edu.vn',
                'dia_chi' => 'Tầng 6, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://sp.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_khoa' => 'Khoa Khoa học Tự nhiên',
                'ma_khoa' => 'KHTN',
                'mo_ta' => 'Khoa chuyên đào tạo về toán học, vật lý, hóa học và sinh học',
                'truong_khoa' => 'PGS.TS. Vũ Thị F',
                'so_dien_thoai' => '024 3754 1006',
                'email' => 'khtn@vnu.edu.vn',
                'dia_chi' => 'Tầng 7, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://khtn.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_khoa' => 'Khoa Khoa học Xã hội và Nhân văn',
                'ma_khoa' => 'KHXHNV',
                'mo_ta' => 'Khoa chuyên đào tạo về lịch sử, địa lý, văn học và triết học',
                'truong_khoa' => 'GS.TS. Đặng Văn G',
                'so_dien_thoai' => '024 3754 1007',
                'email' => 'khxhnv@vnu.edu.vn',
                'dia_chi' => 'Tầng 8, Nhà E1, 144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'website' => 'https://khxhnv.vnu.edu.vn',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
