<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchasableBook;

class PurchasableBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = [
            [
                'ten_sach' => 'Lập Trình PHP Cơ Bản',
                'tac_gia' => 'Nguyễn Văn A',
                'mo_ta' => 'Cuốn sách hướng dẫn lập trình PHP từ cơ bản đến nâng cao, phù hợp cho người mới bắt đầu.',
                'hinh_anh' => 'books/php-basic.jpg',
                'gia' => 150000,
                'nha_xuat_ban' => 'NXB Giáo Dục',
                'nam_xuat_ban' => 2023,
                'isbn' => '978-604-0-12345-6',
                'so_trang' => 350,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'PDF',
                'kich_thuoc_file' => 2500, // KB
                'trang_thai' => 'active',
                'so_luong_ban' => 45,
                'danh_gia_trung_binh' => 4.5,
                'so_luot_xem' => 1200,
            ],
            [
                'ten_sach' => 'Truyện Kiều - Nguyễn Du',
                'tac_gia' => 'Nguyễn Du',
                'mo_ta' => 'Tác phẩm kinh điển của văn học Việt Nam, được dịch và chú thích chi tiết.',
                'hinh_anh' => 'books/truyen-kieu.jpg',
                'gia' => 80000,
                'nha_xuat_ban' => 'NXB Văn Học',
                'nam_xuat_ban' => 2022,
                'isbn' => '978-604-0-23456-7',
                'so_trang' => 280,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'EPUB',
                'kich_thuoc_file' => 1800,
                'trang_thai' => 'active',
                'so_luong_ban' => 120,
                'danh_gia_trung_binh' => 4.8,
                'so_luot_xem' => 2500,
            ],
            [
                'ten_sach' => 'Lịch Sử Việt Nam',
                'tac_gia' => 'GS. Trần Văn Giàu',
                'mo_ta' => 'Tổng hợp lịch sử Việt Nam từ thời kỳ dựng nước đến hiện đại.',
                'hinh_anh' => 'books/vietnam-history.jpg',
                'gia' => 200000,
                'nha_xuat_ban' => 'NXB Chính Trị Quốc Gia',
                'nam_xuat_ban' => 2023,
                'isbn' => '978-604-0-34567-8',
                'so_trang' => 500,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'PDF',
                'kich_thuoc_file' => 3200,
                'trang_thai' => 'active',
                'so_luong_ban' => 78,
                'danh_gia_trung_binh' => 4.6,
                'so_luot_xem' => 1800,
            ],
            [
                'ten_sach' => 'Giáo Dục Thế Kỷ 21',
                'tac_gia' => 'TS. Lê Thị Lan',
                'mo_ta' => 'Phương pháp giáo dục hiện đại và xu hướng giáo dục trong thế kỷ 21.',
                'hinh_anh' => 'books/education-21st.jpg',
                'gia' => 180000,
                'nha_xuat_ban' => 'NXB Đại Học Quốc Gia',
                'nam_xuat_ban' => 2023,
                'isbn' => '978-604-0-45678-9',
                'so_trang' => 400,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'PDF',
                'kich_thuoc_file' => 2800,
                'trang_thai' => 'active',
                'so_luong_ban' => 32,
                'danh_gia_trung_binh' => 4.3,
                'so_luot_xem' => 950,
            ],
            [
                'ten_sach' => 'Khoa Học Dữ Liệu',
                'tac_gia' => 'PGS. Phạm Minh Tuấn',
                'mo_ta' => 'Hướng dẫn phân tích dữ liệu và machine learning từ cơ bản đến nâng cao.',
                'hinh_anh' => 'books/data-science.jpg',
                'gia' => 250000,
                'nha_xuat_ban' => 'NXB Khoa Học Kỹ Thuật',
                'nam_xuat_ban' => 2024,
                'isbn' => '978-604-0-56789-0',
                'so_trang' => 600,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'PDF',
                'kich_thuoc_file' => 4500,
                'trang_thai' => 'active',
                'so_luong_ban' => 25,
                'danh_gia_trung_binh' => 4.7,
                'so_luot_xem' => 1100,
            ],
            [
                'ten_sach' => 'Y Học Cơ Bản',
                'tac_gia' => 'BS. Nguyễn Thị Hoa',
                'mo_ta' => 'Kiến thức y học cơ bản cho sinh viên và những người quan tâm đến sức khỏe.',
                'hinh_anh' => 'books/basic-medicine.jpg',
                'gia' => 220000,
                'nha_xuat_ban' => 'NXB Y Học',
                'nam_xuat_ban' => 2023,
                'isbn' => '978-604-0-67890-1',
                'so_trang' => 450,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'PDF',
                'kich_thuoc_file' => 3800,
                'trang_thai' => 'active',
                'so_luong_ban' => 18,
                'danh_gia_trung_binh' => 4.4,
                'so_luot_xem' => 800,
            ],
            [
                'ten_sach' => 'Kinh Tế Học Vi Mô',
                'tac_gia' => 'GS. Vũ Thành Tự Anh',
                'mo_ta' => 'Giáo trình kinh tế học vi mô dành cho sinh viên đại học và cao đẳng.',
                'hinh_anh' => 'books/microeconomics.jpg',
                'gia' => 190000,
                'nha_xuat_ban' => 'NXB Đại Học Kinh Tế',
                'nam_xuat_ban' => 2023,
                'isbn' => '978-604-0-78901-2',
                'so_trang' => 420,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'PDF',
                'kich_thuoc_file' => 2900,
                'trang_thai' => 'active',
                'so_luong_ban' => 55,
                'danh_gia_trung_binh' => 4.2,
                'so_luot_xem' => 1300,
            ],
            [
                'ten_sach' => 'Tâm Lý Học Đại Cương',
                'tac_gia' => 'TS. Trần Thị Mai',
                'mo_ta' => 'Nhập môn tâm lý học với các khái niệm cơ bản và ứng dụng thực tế.',
                'hinh_anh' => 'books/psychology.jpg',
                'gia' => 160000,
                'nha_xuat_ban' => 'NXB Đại Học Sư Phạm',
                'nam_xuat_ban' => 2022,
                'isbn' => '978-604-0-89012-3',
                'so_trang' => 380,
                'ngon_ngu' => 'Tiếng Việt',
                'dinh_dang' => 'EPUB',
                'kich_thuoc_file' => 2200,
                'trang_thai' => 'active',
                'so_luong_ban' => 42,
                'danh_gia_trung_binh' => 4.1,
                'so_luot_xem' => 1050,
            ],
        ];

        foreach ($books as $book) {
            PurchasableBook::create($book);
        }
    }
}
