<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fine;
use App\Models\Borrow;
use App\Services\NotificationService;
use Carbon\Carbon;

class CreateLateReturnFines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fines:create-late-returns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động tạo phạt cho sách trả muộn';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Bắt đầu tạo phạt cho sách trả muộn...');

        $overdueBorrows = Borrow::with(['book', 'reader'])
            ->where('trang_thai', 'Dang muon')
            ->where('ngay_hen_tra', '<', Carbon::today())
            ->get();

        $this->info("Tìm thấy {$overdueBorrows->count()} phiếu mượn quá hạn");

        $createdCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($overdueBorrows as $borrow) {
            try {
                // Kiểm tra đã có phạt chưa
                $existingFine = Fine::where('borrow_id', $borrow->id)
                    ->where('type', 'late_return')
                    ->where('status', 'pending')
                    ->first();

                if ($existingFine) {
                    $skippedCount++;
                    $this->line("Bỏ qua phiếu mượn #{$borrow->id} - đã có phạt");
                    continue;
                }

                $daysOverdue = Carbon::today()->diffInDays($borrow->ngay_hen_tra);
                $fineAmount = $daysOverdue * 5000; // 5000 VND/ngày

                $fine = Fine::create([
                    'borrow_id' => $borrow->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => $fineAmount,
                    'type' => 'late_return',
                    'description' => "Trả sách muộn {$daysOverdue} ngày (hạn trả: {$borrow->ngay_hen_tra->format('d/m/Y')})",
                    'due_date' => Carbon::today()->addDays(30),
                    'created_by' => 1, // System user
                    'notes' => 'Tự động tạo bởi hệ thống'
                ]);

                // Gửi thông báo cho độc giả
                try {
                    $notificationService = new NotificationService();
                    $data = [
                        'reader_name' => $borrow->reader->ho_ten,
                        'book_title' => $borrow->book->ten_sach,
                        'fine_amount' => number_format($fineAmount, 0, ',', '.') . ' VND',
                        'due_date' => Carbon::today()->addDays(30)->format('d/m/Y'),
                        'fine_type' => 'Trả sách muộn',
                    ];

                    $notificationService->sendNotification(
                        'fine_notification',
                        $borrow->reader->email,
                        $data,
                        ['email', 'database']
                    );

                    $this->line("✓ Tạo phạt #{$fine->id} cho phiếu mượn #{$borrow->id} - {$borrow->reader->ho_ten}");
                } catch (\Exception $e) {
                    $this->error("Lỗi gửi thông báo cho phiếu mượn #{$borrow->id}: " . $e->getMessage());
                }

                $createdCount++;

            } catch (\Exception $e) {
                $errors[] = "Lỗi khi tạo phạt cho phiếu mượn #{$borrow->id}: " . $e->getMessage();
                $this->error("Lỗi tạo phạt cho phiếu mượn #{$borrow->id}: " . $e->getMessage());
            }
        }

        $this->info("\n=== KẾT QUẢ ===");
        $this->info("✓ Đã tạo: {$createdCount} phạt mới");
        $this->info("⚠ Bỏ qua: {$skippedCount} phiếu mượn (đã có phạt)");
        
        if (!empty($errors)) {
            $this->error("❌ Lỗi: " . count($errors) . " lỗi");
            foreach ($errors as $error) {
                $this->error("  - {$error}");
            }
        }

        $this->info("\nHoàn thành!");
        return 0;
    }
}