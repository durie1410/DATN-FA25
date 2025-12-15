<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Book;
use App\Models\Reader;
use App\Models\User;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('📅 Bắt đầu tạo dữ liệu đặt trước...');

        // Lấy một số sách và độc giả để tạo đặt trước
        $books = Book::take(10)->get();
        $readers = Reader::where('trang_thai', 'Hoat dong')->take(10)->get();
        $users = User::whereIn('role', ['admin', 'staff'])->take(5)->get();

        if ($books->isEmpty() || $readers->isEmpty()) {
            $this->command->warn('⚠️ Không có đủ sách hoặc độc giả để tạo đặt trước. Vui lòng chạy BookSeeder và UserManagementSeeder trước.');
            return;
        }

        $reservations = [
            [
                'book_id' => $books->random()->id,
                'reader_id' => $readers->random()->id,
                'user_id' => $users->random()->id,
                'status' => 'pending',
                'priority' => rand(1, 5),
                'reservation_date' => Carbon::today()->subDays(rand(1, 5)),
                'expiry_date' => Carbon::today()->addDays(rand(1, 7)),
                'notes' => 'Đặt trước sách cho nghiên cứu',
            ],
            [
                'book_id' => $books->random()->id,
                'reader_id' => $readers->random()->id,
                'user_id' => $users->random()->id,
                'status' => 'confirmed',
                'priority' => rand(1, 5),
                'reservation_date' => Carbon::today()->subDays(rand(1, 3)),
                'expiry_date' => Carbon::today()->addDays(rand(2, 7)),
                'ready_date' => Carbon::today()->addDays(rand(1, 2)),
                'notes' => 'Sách đã được xác nhận',
            ],
            [
                'book_id' => $books->random()->id,
                'reader_id' => $readers->random()->id,
                'user_id' => $users->random()->id,
                'status' => 'ready',
                'priority' => rand(1, 5),
                'reservation_date' => Carbon::today()->subDays(rand(2, 5)),
                'expiry_date' => Carbon::today()->addDays(rand(1, 5)),
                'ready_date' => Carbon::today()->subDays(rand(1, 2)),
                'notes' => 'Sách đã sẵn sàng để nhận',
            ],
            [
                'book_id' => $books->random()->id,
                'reader_id' => $readers->random()->id,
                'user_id' => $users->random()->id,
                'status' => 'cancelled',
                'priority' => rand(1, 5),
                'reservation_date' => Carbon::today()->subDays(rand(3, 7)),
                'expiry_date' => Carbon::today()->subDays(rand(1, 3)),
                'notes' => 'Đặt trước bị hủy do không cần thiết',
            ],
            [
                'book_id' => $books->random()->id,
                'reader_id' => $readers->random()->id,
                'user_id' => $users->random()->id,
                'status' => 'expired',
                'priority' => rand(1, 5),
                'reservation_date' => Carbon::today()->subDays(rand(8, 10)),
                'expiry_date' => Carbon::today()->subDays(rand(1, 3)),
                'notes' => 'Đặt trước hết hạn',
            ],
        ];

        foreach ($reservations as $reservationData) {
            Reservation::firstOrCreate(
                [
                    'book_id' => $reservationData['book_id'],
                    'user_id' => $reservationData['user_id'],
                ],
                $reservationData
            );
        }

        // Tạo thêm một số đặt trước ngẫu nhiên
        for ($i = 0; $i < 15; $i++) {
            $statuses = ['pending', 'confirmed', 'ready', 'cancelled', 'expired'];
            $status = $statuses[array_rand($statuses)];
            
            $reservationDate = Carbon::today()->subDays(rand(1, 10));
            $expiryDate = $reservationDate->copy()->addDays(7);
            
            $reservationData = [
                'book_id' => $books->random()->id,
                'reader_id' => $readers->random()->id,
                'user_id' => $users->random()->id,
                'status' => $status,
                'priority' => rand(1, 5),
                'reservation_date' => $reservationDate,
                'expiry_date' => $expiryDate,
                'notes' => $this->getRandomNote(),
            ];

            // Thêm ready_date nếu status là ready
            if ($status === 'ready') {
                $reservationData['ready_date'] = $reservationDate->copy()->addDays(rand(1, 3));
            }

            // Thêm pickup_date nếu status là ready và có ready_date
            if ($status === 'ready' && isset($reservationData['ready_date'])) {
                $reservationData['pickup_date'] = $reservationData['ready_date']->copy()->addDays(rand(0, 2));
            }

            Reservation::firstOrCreate(
                [
                    'book_id' => $reservationData['book_id'],
                    'user_id' => $reservationData['user_id'],
                ],
                $reservationData
            );
        }

        $this->command->info('✅ Hoàn thành tạo dữ liệu đặt trước!');
    }

    /**
     * Lấy ghi chú ngẫu nhiên
     */
    private function getRandomNote()
    {
        $notes = [
            'Đặt trước cho nghiên cứu',
            'Sách cần thiết cho bài tập',
            'Đặt trước cho luận văn',
            'Sách tham khảo cho môn học',
            'Đặt trước cho dự án',
            'Sách cần cho công việc',
            'Đặt trước cho học tập',
            'Sách quan trọng',
            'Đặt trước khẩn cấp',
            'Sách cho nghiên cứu khoa học',
        ];

        return $notes[array_rand($notes)];
    }
}
