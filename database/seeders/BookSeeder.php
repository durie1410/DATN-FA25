<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        $books = [
            [
                'ten_sach' => 'Lập trình Laravel từ A-Z',
                'category_id' => 1,
                'tac_gia' => 'Nguyễn Văn A',
                'nam_xuat_ban' => 2024,
                'mo_ta' => 'Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Khoa học dữ liệu',
                'category_id' => 2,
                'tac_gia' => 'Trần Thị B',
                'nam_xuat_ban' => 2023,
                'mo_ta' => 'Giới thiệu về khoa học dữ liệu và machine learning',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Lịch sử Việt Nam hiện đại',
                'category_id' => 3,
                'tac_gia' => 'Lê Văn C',
                'nam_xuat_ban' => 2022,
                'mo_ta' => 'Lịch sử Việt Nam từ thế kỷ 20 đến nay',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Lập trình PHP cơ bản',
                'category_id' => 4,
                'tac_gia' => 'Nguyễn Văn A',
                'nam_xuat_ban' => 2023,
                'mo_ta' => 'Sách học PHP từ cơ bản',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Kinh tế học vi mô',
                'category_id' => 5,
                'tac_gia' => 'Phạm Thị D',
                'nam_xuat_ban' => 2023,
                'mo_ta' => 'Giáo trình kinh tế học vi mô cho sinh viên',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Truyện Kiều',
                'category_id' => 6,
                'tac_gia' => 'Nguyễn Du',
                'nam_xuat_ban' => 1950,
                'mo_ta' => 'Tác phẩm kinh điển của văn học Việt Nam',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Giáo dục thế kỷ 21',
                'category_id' => 7,
                'tac_gia' => 'Hoàng Văn E',
                'nam_xuat_ban' => 2024,
                'mo_ta' => 'Phương pháp giáo dục hiện đại',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Y học cơ bản',
                'category_id' => 8,
                'tac_gia' => 'Bác sĩ Nguyễn F',
                'nam_xuat_ban' => 2023,
                'mo_ta' => 'Kiến thức y học cơ bản cho mọi người',
                'hinh_anh' => null
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}