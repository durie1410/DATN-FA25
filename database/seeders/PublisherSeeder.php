<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $publishers = [
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Giáo dục Việt Nam',
                'dia_chi' => '81 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội',
                'so_dien_thoai' => '024 3822 1234',
                'email' => 'info@nxbgd.vn',
                'website' => 'https://nxbgd.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách giáo khoa và tài liệu giáo dục',
                'ngay_thanh_lap' => '1957-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Trẻ',
                'dia_chi' => '161B Lý Chính Thắng, Phường 7, Quận 3, TP.HCM',
                'so_dien_thoai' => '028 3930 1234',
                'email' => 'info@nxbtre.com.vn',
                'website' => 'https://nxbtre.com.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách văn học và sách thiếu nhi',
                'ngay_thanh_lap' => '1981-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Kim Đồng',
                'dia_chi' => '55 Quang Trung, Hai Bà Trưng, Hà Nội',
                'so_dien_thoai' => '024 3822 5678',
                'email' => 'info@nxbkimdong.com.vn',
                'website' => 'https://nxbkimdong.com.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách thiếu nhi và thanh thiếu niên',
                'ngay_thanh_lap' => '1957-06-17',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Thế giới',
                'dia_chi' => '46 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội',
                'so_dien_thoai' => '024 3822 9012',
                'email' => 'info@nxbthegioi.com.vn',
                'website' => 'https://nxbthegioi.com.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách khoa học và công nghệ',
                'ngay_thanh_lap' => '1957-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Đại học Quốc gia Hà Nội',
                'dia_chi' => '144 Xuân Thủy, Cầu Giấy, Hà Nội',
                'so_dien_thoai' => '024 3754 1234',
                'email' => 'info@nxb.vnu.edu.vn',
                'website' => 'https://nxb.vnu.edu.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách đại học và nghiên cứu khoa học',
                'ngay_thanh_lap' => '1956-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Đại học Quốc gia TP.HCM',
                'dia_chi' => '268 Lý Thường Kiệt, Quận 10, TP.HCM',
                'so_dien_thoai' => '028 3865 1234',
                'email' => 'info@nxb.vnuhcm.edu.vn',
                'website' => 'https://nxb.vnuhcm.edu.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách đại học và nghiên cứu khoa học',
                'ngay_thanh_lap' => '1995-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Chính trị Quốc gia Sự thật',
                'dia_chi' => '24 Quang Trung, Hoàn Kiếm, Hà Nội',
                'so_dien_thoai' => '024 3822 3456',
                'email' => 'info@nxbctqg.vn',
                'website' => 'https://nxbctqg.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách chính trị và lý luận',
                'ngay_thanh_lap' => '1945-01-01',
                'trang_thai' => 'active',
            ],
            [
                'ten_nha_xuat_ban' => 'Nhà xuất bản Lao động',
                'dia_chi' => '175 Giảng Võ, Đống Đa, Hà Nội',
                'so_dien_thoai' => '024 3851 1234',
                'email' => 'info@nxblaodong.vn',
                'website' => 'https://nxblaodong.vn',
                'mo_ta' => 'Nhà xuất bản chuyên về sách lao động và xã hội',
                'ngay_thanh_lap' => '1954-01-01',
                'trang_thai' => 'active',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
