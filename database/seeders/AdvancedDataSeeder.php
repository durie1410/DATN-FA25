<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AdvancedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        // Tạo dữ liệu mở rộng cho các model hiện có
        $this->createExtendedBooks($faker);
        $this->createExtendedReaders($faker);
        $this->createExtendedBorrows($faker);
        $this->createExtendedFines($faker);
        $this->createExtendedReservations($faker);
        $this->createExtendedReviews($faker);
    }

    private function createExtendedBooks($faker)
    {
        // Tạo thêm sách với dữ liệu phong phú hơn
        $categories = Category::all();
        $publishers = Publisher::all();
        
        for ($i = 0; $i < 50; $i++) {
            Book::create([
                'ten_sach' => $faker->sentence(3),
                'category_id' => $faker->randomElement($categories)->id,
                'nha_xuat_ban_id' => $faker->randomElement($publishers)->id,
                'tac_gia' => $faker->name(),
                'nam_xuat_ban' => $faker->numberBetween(1990, 2024),
                'hinh_anh' => $faker->optional(0.7)->imageUrl(300, 400, 'books'),
                'mo_ta' => $faker->paragraph(3),
                'gia' => $faker->randomFloat(2, 50000, 500000),
                'trang_thai' => $faker->randomElement(['active', 'inactive']),
                'danh_gia_trung_binh' => $faker->randomFloat(1, 1, 5),
                'so_luong_ban' => $faker->numberBetween(100, 10000),
                'so_luot_xem' => $faker->numberBetween(0, 1000),
            ]);
        }
    }

    private function createExtendedReaders($faker)
    {
        $faculties = Faculty::all();
        $departments = Department::all();
        $users = User::where('role', 'reader')->get();
        
        foreach ($users as $user) {
            Reader::create([
                'user_id' => $user->id,
                'faculty_id' => $faker->randomElement($faculties)->id,
                'department_id' => $faker->randomElement($departments)->id,
                'ho_ten' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'so_dien_thoai' => $faker->phoneNumber(),
                'ngay_sinh' => $faker->date('Y-m-d', '2000-01-01'),
                'gioi_tinh' => $faker->randomElement(['Nam', 'Nữ']),
                'dia_chi' => $faker->address(),
                'so_the_doc_gia' => 'TDG' . $faker->unique()->numberBetween(100000, 999999),
                'ngay_cap_the' => $faker->dateTimeBetween('-2 years', 'now'),
                'ngay_het_han' => $faker->dateTimeBetween('now', '+2 years'),
                'trang_thai' => $faker->randomElement(['Hoat dong', 'Tam khoa', 'Het han']),
            ]);
        }
    }

    private function createExtendedBorrows($faker)
    {
        $readers = Reader::all();
        $books = Book::all();
        $librarians = User::whereIn('role', ['staff', 'admin'])->get();
        
        for ($i = 0; $i < 200; $i++) {
            $borrowDate = $faker->dateTimeBetween('-1 year', 'now');
            $dueDate = Carbon::parse($borrowDate)->addDays($faker->numberBetween(7, 30));
            
            Borrow::create([
                'reader_id' => $faker->randomElement($readers)->id,
                'book_id' => $faker->randomElement($books)->id,
                'librarian_id' => $faker->randomElement($librarians)->id,
                'ngay_muon' => $borrowDate,
                'ngay_hen_tra' => $dueDate,
                'ngay_tra_thuc_te' => $faker->optional(0.7)->dateTimeBetween($borrowDate, 'now'),
                'trang_thai' => $faker->randomElement(['Dang muon', 'Da tra', 'Qua han']),
                'so_lan_gia_han' => $faker->numberBetween(0, 2),
                'ngay_gia_han_cuoi' => $faker->optional(0.3)->dateTimeBetween($borrowDate, 'now'),
                'ghi_chu' => $faker->optional(0.2)->sentence(),
            ]);
        }
    }

    private function createExtendedFines($faker)
    {
        $borrows = Borrow::all();
        $readers = Reader::all();
        $librarians = User::whereIn('role', ['staff', 'admin'])->get();
        
        for ($i = 0; $i < 100; $i++) {
            $fine = Fine::create([
                'borrow_id' => $faker->randomElement($borrows)->id,
                'reader_id' => $faker->randomElement($readers)->id,
                'amount' => $faker->randomFloat(2, 10000, 100000),
                'type' => $faker->randomElement(['late_return', 'damaged_book', 'lost_book', 'other']),
                'description' => $faker->sentence(),
                'status' => $faker->randomElement(['pending', 'paid', 'cancelled']),
                'due_date' => $faker->dateTimeBetween('now', '+30 days'),
                'paid_date' => $faker->optional(0.6)->dateTimeBetween('-1 month', 'now'),
                'notes' => $faker->optional(0.3)->sentence(),
                'created_by' => $faker->randomElement($librarians)->id,
            ]);
        }
    }

    private function createExtendedReservations($faker)
    {
        $readers = Reader::all();
        $books = Book::all();
        $users = User::all();
        
        for ($i = 0; $i < 150; $i++) {
            $bookId = $faker->randomElement($books)->id;
            $userId = $faker->randomElement($users)->id;
            
            Reservation::firstOrCreate(
                [
                    'book_id' => $bookId,
                    'user_id' => $userId,
                ],
                [
                    'reader_id' => $faker->randomElement($readers)->id,
                    'reservation_date' => $faker->dateTimeBetween('-6 months', 'now'),
                    'expiry_date' => $faker->dateTimeBetween('now', '+7 days'),
                    'status' => $faker->randomElement(['pending', 'confirmed', 'ready', 'cancelled', 'expired']),
                    'notes' => $faker->optional(0.2)->sentence(),
                ]
            );
        }
    }

    private function createExtendedReviews($faker)
    {
        $books = Book::all();
        $users = User::all();
        
        for ($i = 0; $i < 300; $i++) {
            $bookId = $faker->randomElement($books)->id;
            $userId = $faker->randomElement($users)->id;
            
            Review::firstOrCreate(
                [
                    'book_id' => $bookId,
                    'user_id' => $userId,
                ],
                [
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->paragraph(2),
                    'is_verified' => $faker->boolean(0.8),
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                ]
            );
        }
    }
}
