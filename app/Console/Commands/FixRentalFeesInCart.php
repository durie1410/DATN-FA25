<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowCartItem;
use App\Models\Book;
use App\Models\Inventory;
use App\Models\Reader;
use App\Services\PricingService;

class FixRentalFeesInCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:fix-rental-fees {--dry-run : Chỉ hiển thị những gì sẽ được sửa, không thực sự sửa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tính lại phí thuê cho các item trong giỏ hàng có phí thuê = 0';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 CHẾ ĐỘ XEM TRƯỚC (Dry Run) - Không thực sự cập nhật dữ liệu');
            $this->newLine();
        }

        // Lấy tất cả các item có phí thuê = 0 hoặc null và có sách hợp lệ
        $items = BorrowCartItem::with(['book', 'cart.user'])
            ->where(function($query) {
                $query->where('tien_thue', 0)
                      ->orWhereNull('tien_thue');
            })
            ->whereHas('book', function($query) {
                $query->where('gia', '>', 0);
            })
            ->get();

        if ($items->isEmpty()) {
            $this->info('✅ Không có item nào cần sửa. Tất cả item đã có phí thuê hợp lệ.');
            return 0;
        }

        $this->info("📦 Tìm thấy {$items->count()} item cần cập nhật phí thuê:");
        $this->newLine();

        $updated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($items as $item) {
            if (!$item->book) {
                $this->warn("   ⚠️  Item #{$item->id}: Không tìm thấy sách, bỏ qua");
                $skipped++;
                continue;
            }

            $book = $item->book;
            $borrowDays = $item->borrow_days ?? 14;
            
            // Lấy thông tin reader nếu có
            $hasCard = false;
            if ($item->cart && $item->cart->user) {
                $reader = Reader::where('user_id', $item->cart->user->id)->first();
                $hasCard = $reader ? true : false;
            }

            // Lấy inventory để tính phí
            $inventory = Inventory::where('book_id', $book->id)
                ->where('status', 'Co san')
                ->first();

            if (!$inventory) {
                $inventory = new Inventory();
                $inventory->condition = 'Trung binh';
                $inventory->status = 'Co san';
            }

            try {
                // Tính lại phí
                $fees = PricingService::calculateFees(
                    $book,
                    $inventory,
                    now(),
                    now()->addDays($borrowDays),
                    $hasCard
                );

                $oldTienThue = $item->tien_thue ?? 0;
                $newTienThue = $fees['tien_thue'];
                $newTienCoc = $fees['tien_coc'];

                if ($dryRun) {
                    $this->line("   📄 Item #{$item->id}:");
                    $this->line("      Sách: {$book->ten_sach} (ID: {$book->id})");
                    $this->line("      Số ngày mượn: {$borrowDays}");
                    $this->line("      Phí thuê cũ: " . number_format($oldTienThue, 0, ',', '.') . "₫");
                    $this->line("      Phí thuê mới: " . number_format($newTienThue, 0, ',', '.') . "₫");
                    $this->line("      Tiền cọc mới: " . number_format($newTienCoc, 0, ',', '.') . "₫");
                    $this->newLine();
                } else {
                    // Cập nhật item
                    $item->tien_coc = $newTienCoc;
                    $item->tien_thue = $newTienThue;
                    $item->save();

                    $this->info("   ✅ Item #{$item->id}: {$book->ten_sach}");
                    $this->line("      Phí thuê: " . number_format($oldTienThue, 0, ',', '.') . "₫ → " . number_format($newTienThue, 0, ',', '.') . "₫");
                }

                $updated++;
            } catch (\Exception $e) {
                $this->error("   ❌ Item #{$item->id}: Lỗi - {$e->getMessage()}");
                $errors++;
            }
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════════');
        
        if ($dryRun) {
            $this->info("📊 KẾT QUẢ XEM TRƯỚC:");
            $this->info("   • Sẽ cập nhật: {$updated} item");
            $this->info("   • Bỏ qua: {$skipped} item");
            if ($errors > 0) {
                $this->warn("   • Lỗi: {$errors} item");
            }
            $this->newLine();
            $this->comment('💡 Chạy lại lệnh không có --dry-run để thực sự cập nhật dữ liệu');
        } else {
            $this->info("✅ HOÀN THÀNH:");
            $this->info("   • Đã cập nhật: {$updated} item");
            if ($skipped > 0) {
                $this->warn("   • Bỏ qua: {$skipped} item");
            }
            if ($errors > 0) {
                $this->error("   • Lỗi: {$errors} item");
            }
        }

        $this->info('═══════════════════════════════════════════════════════════');

        return 0;
    }
}


