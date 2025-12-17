<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MotivationalBooksSeeder extends Seeder
{
    /**
     * Seed sách động lực và phát triển bản thân
     */
    public function run()
    {
        // Lấy hoặc tạo category phát triển bản thân
        $selfDevelopmentCategory = Category::firstOrCreate(
            ['ten_the_loai' => 'Phát triển bản thân'],
            ['mo_ta' => 'Sách về kỹ năng sống, động lực và phát triển cá nhân']
        );

        $businessCategory = Category::firstOrCreate(
            ['ten_the_loai' => 'Kinh doanh'],
            ['mo_ta' => 'Sách về kinh doanh, khởi nghiệp và làm giàu']
        );

        $books = [
            [
                'ten_sach' => 'Lựa chọn đúng quan trọng hơn nỗ lực',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Tống Văn Chiêu',
                'nam_xuat_ban' => 2022,
                'mo_ta' => 'Cuốn sách giúp bạn hiểu rằng việc lựa chọn đúng hướng đi quan trọng hơn việc chỉ chăm chỉ nỗ lực. Những quyết định sáng suốt sẽ dẫn đến thành công nhanh chóng hơn.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Bí quyết thay đổi cuộc đời',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Tony Robbins',
                'nam_xuat_ban' => 2021,
                'mo_ta' => 'Khám phá những bí mật và phương pháp đã được chứng minh để thay đổi cuộc sống của bạn theo hướng tích cực.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Giảm "xóc"... Hành trình cuộc đời',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Lê Quốc Vinh',
                'nam_xuat_ban' => 2023,
                'mo_ta' => 'Học cách giảm thiểu những rung động tiêu cực trong cuộc sống và tạo dựng hành trình ổn định, hạnh phúc.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Chúng ta cách nhau một bước chân',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Lý Hoàng Dũng',
                'nam_xuat_ban' => 2022,
                'mo_ta' => 'Khám phá rằng sự khác biệt giữa thành công và thất bại đôi khi chỉ là một bước chân nhỏ. Hãy dám bước tiếp.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Dám làm giàu',
                'category_id' => $businessCategory->id,
                'tac_gia' => 'Adam Khoo',
                'nam_xuat_ban' => 2020,
                'mo_ta' => 'Cuốn sách truyền cảm hứng về việc dám nghĩ lớn, dám hành động và dám theo đuổi giấc mơ làm giàu của mình.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Khởi nghiệp thành công',
                'category_id' => $businessCategory->id,
                'tac_gia' => 'Eric Ries',
                'nam_xuat_ban' => 2021,
                'mo_ta' => 'Hướng dẫn chi tiết về cách xây dựng startup từ ý tưởng đến thành công. Bao gồm các bài học từ những doanh nhân hàng đầu.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Đắc Nhân Tâm',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Dale Carnegie',
                'nam_xuat_ban' => 1936,
                'mo_ta' => 'Cuốn sách kinh điển về nghệ thuật giao tiếp và ứng xử. Giúp bạn hiểu và làm việc tốt hơn với mọi người.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Khởi Dậy Tiềm Thức',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Joseph Murphy',
                'nam_xuat_ban' => 1963,
                'mo_ta' => 'Khám phá sức mạnh vô tận của tiềm thức và cách sử dụng nó để thay đổi cuộc sống. Tác phẩm kinh điển về phát triển bản thân và sức mạnh của tâm trí.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Nhà Giả Kim',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Paulo Coelho',
                'nam_xuat_ban' => 1988,
                'mo_ta' => 'Hành trình tìm kiếm kho báu và ý nghĩa cuộc sống. Một tác phẩm triết lý sâu sắc về việc theo đuổi ước mơ và lắng nghe trái tim mình.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Cà Phê Cùng Tony',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Tony Buổi Sáng',
                'nam_xuat_ban' => 2019,
                'mo_ta' => 'Những câu chuyện truyền cảm hứng và bài học cuộc sống từ Tony Buổi Sáng. Thích hợp cho những buổi sáng đọc sách với tách cà phê.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Tuổi Trẻ Đáng Giá Bao Nhiêu',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Rosie Nguyễn',
                'nam_xuat_ban' => 2018,
                'mo_ta' => 'Câu chuyện về tuổi trẻ, ước mơ và cách tận dụng tuổi thanh xuân để xây dựng tương lai tươi sáng.',
                'hinh_anh' => null
            ],
            [
                'ten_sach' => 'Tư Duy Ngược',
                'category_id' => $selfDevelopmentCategory->id,
                'tac_gia' => 'Nguyễn Anh Dũng',
                'nam_xuat_ban' => 2020,
                'mo_ta' => 'Học cách suy nghĩ khác biệt, nhìn vấn đề từ nhiều góc độ và tìm ra giải pháp sáng tạo.',
                'hinh_anh' => null
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(
                [
                    'ten_sach' => $bookData['ten_sach'],
                    'tac_gia' => $bookData['tac_gia']
                ],
                $bookData
            );
        }

        $this->command->info('✅ Đã thêm ' . count($books) . ' sách động lực vào database!');
    }
}










