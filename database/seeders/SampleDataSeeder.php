<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Book;
use App\Models\Reader;
use App\Models\User;
use App\Models\Borrow;
use App\Models\Inventory;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo danh mục mẫu
        $categories = [
            ['ten_the_loai' => 'Khoa học'],
            ['ten_the_loai' => 'Văn học'],
            ['ten_the_loai' => 'Lịch sử'],
            ['ten_the_loai' => 'Kinh tế'],
            ['ten_the_loai' => 'Công nghệ'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate($categoryData);
        }

        // Tạo sách mẫu
        $books = [
            [
                'ten_sach' => 'Lập trình PHP',
                'tac_gia' => 'Nguyễn Văn A',
                'nam_xuat_ban' => 2023,
                'category_id' => Category::where('ten_the_loai', 'Công nghệ')->first()->id,
                'mo_ta' => 'Sách hướng dẫn lập trình PHP từ cơ bản đến nâng cao',
                'gia' => 150000,
                'danh_gia_trung_binh' => 4.5,
                'so_luong_ban' => 45,
                'so_luot_xem' => 1200,
            ],
            [
                'ten_sach' => 'Laravel Framework',
                'tac_gia' => 'Trần Thị B',
                'nam_xuat_ban' => 2024,
                'category_id' => Category::where('ten_the_loai', 'Công nghệ')->first()->id,
                'mo_ta' => 'Tài liệu chi tiết về Laravel Framework',
                'gia' => 200000,
                'danh_gia_trung_binh' => 4.8,
                'so_luong_ban' => 32,
                'so_luot_xem' => 950,
            ],
            [
                'ten_sach' => 'Văn học Việt Nam',
                'tac_gia' => 'Lê Văn C',
                'nam_xuat_ban' => 2022,
                'category_id' => Category::where('ten_the_loai', 'Văn học')->first()->id,
                'mo_ta' => 'Tuyển tập các tác phẩm văn học Việt Nam',
                'gia' => 120000,
                'danh_gia_trung_binh' => 4.2,
                'so_luong_ban' => 67,
                'so_luot_xem' => 1800,
            ],
            [
                'ten_sach' => 'Lịch sử Việt Nam',
                'tac_gia' => 'Phạm Thị D',
                'nam_xuat_ban' => 2021,
                'category_id' => Category::where('ten_the_loai', 'Lịch sử')->first()->id,
                'mo_ta' => 'Lịch sử Việt Nam từ thời kỳ cổ đại đến hiện đại',
                'gia' => 180000,
                'danh_gia_trung_binh' => 4.6,
                'so_luong_ban' => 78,
                'so_luot_xem' => 2100,
            ],
            [
                'ten_sach' => 'Kinh tế học',
                'tac_gia' => 'Hoàng Văn E',
                'nam_xuat_ban' => 2023,
                'category_id' => Category::where('ten_the_loai', 'Kinh tế')->first()->id,
                'mo_ta' => 'Giáo trình kinh tế học cơ bản',
                'gia' => 160000,
                'danh_gia_trung_binh' => 4.3,
                'so_luong_ban' => 54,
                'so_luot_xem' => 1400,
            ],
        ];

        foreach ($books as $bookData) {
            $book = Book::firstOrCreate([
                'ten_sach' => $bookData['ten_sach'],
                'tac_gia' => $bookData['tac_gia'],
            ], $bookData);

            // Tạo inventory cho mỗi sách
            Inventory::firstOrCreate([
                'book_id' => $book->id,
                'barcode' => 'BK' . str_pad($book->id, 6, '0', STR_PAD_LEFT),
            ], [
                'location' => 'Kệ ' . rand(1, 10) . ', Tầng ' . rand(1, 3),
                'condition' => ['Moi', 'Tot', 'Trung binh'][rand(0, 2)],
                'status' => 'Co san',
                'purchase_price' => rand(50000, 200000),
                'purchase_date' => now()->subDays(rand(1, 365)),
                'created_by' => User::where('role', 'admin')->first()->id ?? 1,
            ]);
        }

        // Tạo độc giả mẫu
        $readers = [
            [
                'ho_ten' => 'Nguyễn Văn Độc giả 1',
                'email' => 'reader1@example.com',
                'so_dien_thoai' => '0123456789',
                'ngay_sinh' => '1990-01-01',
                'gioi_tinh' => 'Nam',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'so_the_doc_gia' => 'RD001',
                'ngay_cap_the' => now()->subMonths(6),
                'ngay_het_han' => now()->addMonths(6),
                'trang_thai' => 'Hoat dong',
            ],
            [
                'ho_ten' => 'Trần Thị Độc giả 2',
                'email' => 'reader2@example.com',
                'so_dien_thoai' => '0987654321',
                'ngay_sinh' => '1995-05-15',
                'gioi_tinh' => 'Nữ',
                'dia_chi' => '456 Đường XYZ, Quận 2, TP.HCM',
                'so_the_doc_gia' => 'RD002',
                'ngay_cap_the' => now()->subMonths(3),
                'ngay_het_han' => now()->addMonths(9),
                'trang_thai' => 'Hoat dong',
            ],
            [
                'ho_ten' => 'Lê Văn Độc giả 3',
                'email' => 'reader3@example.com',
                'so_dien_thoai' => '0369258147',
                'ngay_sinh' => '1988-12-20',
                'gioi_tinh' => 'Nam',
                'dia_chi' => '789 Đường DEF, Quận 3, TP.HCM',
                'so_the_doc_gia' => 'RD003',
                'ngay_cap_the' => now()->subMonths(1),
                'ngay_het_han' => now()->addMonths(11),
                'trang_thai' => 'Hoat dong',
            ],
        ];

        foreach ($readers as $readerData) {
            Reader::firstOrCreate([
                'so_the_doc_gia' => $readerData['so_the_doc_gia'],
            ], $readerData);
        }

        // Tạo một số mượn sách mẫu
        $books = Book::limit(3)->get();
        $readers = Reader::limit(3)->get();
        $admin = User::where('role', 'admin')->first();

        foreach ($books as $index => $book) {
            if (isset($readers[$index])) {
                Borrow::firstOrCreate([
                    'reader_id' => $readers[$index]->id,
                    'book_id' => $book->id,
                ], [
                    'librarian_id' => $admin->id,
                    'ngay_muon' => now()->subDays(rand(1, 30)),
                    'ngay_hen_tra' => now()->addDays(rand(1, 14)),
                    'trang_thai' => 'Dang muon',
                    'so_lan_gia_han' => 0,
                ]);
            }
        }

        $this->command->info('Sample data created successfully!');
    }
}
