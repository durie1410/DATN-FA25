<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fine;
use App\Models\Borrow;
use App\Models\BorrowItem;
use App\Models\Reader;
use App\Models\User;
use Carbon\Carbon;

class CreateSampleFines extends Command
{
    protected $signature = 'fines:create-sample';
    protected $description = 'Tạo dữ liệu phạt mẫu để test';

    public function handle()
    {
        $this->info('=== TAO DU LIEU PHAT MAU ===');
        $this->newLine();

        // Kiểm tra dữ liệu có sẵn
        $readers = Reader::limit(5)->get();
        $borrows = Borrow::with('borrowItems')->limit(10)->get();
        $users = User::limit(3)->get();

        $this->info("Số lượng dữ liệu có sẵn:");
        $this->line("- Readers: " . $readers->count());
        $this->line("- Borrows: " . $borrows->count());
        $this->line("- Users: " . $users->count());
        $this->newLine();

        if ($readers->isEmpty()) {
            $this->error('⚠️ Không có Reader nào. Vui lòng tạo Reader trước.');
            return 1;
        }

        if ($borrows->isEmpty()) {
            $this->error('⚠️ Không có Borrow nào. Vui lòng tạo Borrow trước.');
            return 1;
        }

        if ($users->isEmpty()) {
            $this->error('⚠️ Không có User nào. Vui lòng tạo User trước.');
            return 1;
        }

        $created = 0;

        // 1. Phạt trả muộn
        if ($borrows->isNotEmpty()) {
            $borrow = $borrows->first();
            $borrowItem = $borrow->borrowItems->first();
            
            if ($borrowItem) {
                Fine::create([
                    'borrow_id' => $borrow->id,
                    'borrow_item_id' => $borrowItem->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => 50000,
                    'type' => 'late_return',
                    'description' => 'Trả sách muộn 10 ngày',
                    'status' => 'pending',
                    'due_date' => Carbon::now()->addDays(30),
                    'created_by' => $users->first()->id,
                ]);
                $created++;
                $this->info('✓ Đã tạo phạt trả muộn');
            }
        }

        // 2. Phạt làm hỏng sách (có thông tin hư hỏng)
        if ($borrows->count() > 1) {
            $borrow = $borrows->skip(1)->first();
            $borrowItem = $borrow->borrowItems->first();
            
            if ($borrowItem) {
                $conditionBefore = 'Tot';
                if ($borrowItem->inventory) {
                    $conditionBefore = $borrowItem->inventory->condition ?? 'Tot';
                }
                
                Fine::create([
                    'borrow_id' => $borrow->id,
                    'borrow_item_id' => $borrowItem->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => 150000,
                    'type' => 'damaged_book',
                    'description' => 'Sách bị hư hỏng khi trả',
                    'damage_description' => 'Sách bị rách nhiều trang, bìa bị cong và có vết bẩn. Trang 50-60 bị rách hoàn toàn.',
                    'damage_severity' => 'trung_binh',
                    'damage_type' => 'trang_bi_rach',
                    'condition_before' => $conditionBefore,
                    'condition_after' => 'Hong',
                    'inspection_notes' => 'Đã kiểm tra và xác nhận hư hỏng. Khách hàng đồng ý thanh toán phạt.',
                    'inspected_by' => $users->first()->id,
                    'inspected_at' => Carbon::now(),
                    'status' => 'pending',
                    'due_date' => Carbon::now()->addDays(15),
                    'created_by' => $users->first()->id,
                ]);
                $created++;
                $this->info('✓ Đã tạo phạt làm hỏng sách (có thông tin hư hỏng)');
            }
        }

        // 3. Phạt mất sách
        if ($borrows->count() > 2) {
            $borrow = $borrows->skip(2)->first();
            $borrowItem = $borrow->borrowItems->first();
            
            if ($borrowItem) {
                Fine::create([
                    'borrow_id' => $borrow->id,
                    'borrow_item_id' => $borrowItem->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => 200000,
                    'type' => 'lost_book',
                    'description' => 'Mất sách khi trả',
                    'damage_description' => 'Khách hàng báo mất sách, không thể trả lại.',
                    'damage_severity' => 'mat_sach',
                    'damage_type' => 'mat_sach',
                    'condition_before' => 'Tot',
                    'condition_after' => 'Mat',
                    'inspection_notes' => 'Đã xác nhận với khách hàng về việc mất sách. Khách đồng ý bồi thường.',
                    'inspected_by' => $users->first()->id,
                    'inspected_at' => Carbon::now()->subDays(2),
                    'status' => 'pending',
                    'due_date' => Carbon::now()->addDays(7),
                    'created_by' => $users->first()->id,
                ]);
                $created++;
                $this->info('✓ Đã tạo phạt mất sách');
            }
        }

        // 4. Phạt đã thanh toán
        if ($borrows->count() > 3) {
            $borrow = $borrows->skip(3)->first();
            $borrowItem = $borrow->borrowItems->first();
            
            if ($borrowItem) {
                Fine::create([
                    'borrow_id' => $borrow->id,
                    'borrow_item_id' => $borrowItem->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => 30000,
                    'type' => 'late_return',
                    'description' => 'Trả sách muộn 6 ngày',
                    'status' => 'paid',
                    'paid_date' => Carbon::now()->subDays(5),
                    'due_date' => Carbon::now()->subDays(10),
                    'created_by' => $users->first()->id,
                ]);
                $created++;
                $this->info('✓ Đã tạo phạt đã thanh toán');
            }
        }

        // 5. Phạt quá hạn
        if ($borrows->count() > 4) {
            $borrow = $borrows->skip(4)->first();
            $borrowItem = $borrow->borrowItems->first();
            
            if ($borrowItem) {
                Fine::create([
                    'borrow_id' => $borrow->id,
                    'borrow_item_id' => $borrowItem->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => 75000,
                    'type' => 'damaged_book',
                    'description' => 'Sách bị hư hỏng nhẹ',
                    'damage_description' => 'Bìa sách bị cong nhẹ, một số trang bị nhăn.',
                    'damage_severity' => 'nhe',
                    'damage_type' => 'bia_bi_hu',
                    'condition_before' => 'Moi',
                    'condition_after' => 'Trung binh',
                    'inspection_notes' => 'Hư hỏng nhẹ, có thể sửa chữa được.',
                    'inspected_by' => $users->first()->id,
                    'inspected_at' => Carbon::now()->subDays(20),
                    'status' => 'pending',
                    'due_date' => Carbon::now()->subDays(5), // Quá hạn 5 ngày
                    'created_by' => $users->first()->id,
                ]);
                $created++;
                $this->info('✓ Đã tạo phạt quá hạn');
            }
        }

        // 6. Phạt hư hỏng nặng
        if ($borrows->count() > 5) {
            $borrow = $borrows->skip(5)->first();
            $borrowItem = $borrow->borrowItems->first();
            
            if ($borrowItem) {
                $conditionBefore = 'Moi';
                if ($borrowItem->inventory) {
                    $conditionBefore = $borrowItem->inventory->condition ?? 'Moi';
                }
                
                Fine::create([
                    'borrow_id' => $borrow->id,
                    'borrow_item_id' => $borrowItem->id,
                    'reader_id' => $borrow->reader_id,
                    'amount' => 300000,
                    'type' => 'damaged_book',
                    'description' => 'Sách bị hư hỏng nghiêm trọng',
                    'damage_description' => 'Sách bị ướt, nhiều trang bị dính, bìa bị rách hoàn toàn, một số trang bị mất. Tình trạng rất nghiêm trọng, không thể sửa chữa.',
                    'damage_severity' => 'nang',
                    'damage_type' => 'bi_am_moc',
                    'condition_before' => $conditionBefore,
                    'condition_after' => 'Hong',
                    'inspection_notes' => 'Hư hỏng rất nghiêm trọng. Sách không thể sử dụng được nữa. Cần thay thế sách mới.',
                    'inspected_by' => $users->first()->id,
                    'inspected_at' => Carbon::now()->subDays(1),
                    'status' => 'pending',
                    'due_date' => Carbon::now()->addDays(20),
                    'created_by' => $users->first()->id,
                ]);
                $created++;
                $this->info('✓ Đã tạo phạt hư hỏng nặng');
            }
        }

        $total = Fine::count();
        $this->newLine();
        $this->info("=== HOÀN THÀNH ===");
        $this->line("Đã tạo: {$created} phạt mẫu");
        $this->line("Tổng số phạt trong hệ thống: {$total}");
        $this->newLine();
        $this->info('Bạn có thể vào trang quản lý phí phạt để xem dữ liệu!');

        return 0;
    }
}
