<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrow;
use Illuminate\Support\Facades\DB;

class SyncShippingFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrows:sync-shipping-fees 
                            {--dry-run : Chạy thử không cập nhật database}
                            {--force : Cập nhật tất cả, kể cả khi đã có giá trị}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ tiền ship từ borrow_items lên bảng borrows';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('🔄 Bắt đầu đồng bộ tiền ship từ items lên bảng borrows...');
        $this->newLine();

        // Lấy tất cả borrows có items
        $borrows = Borrow::with('items')->get();
        
        $totalBorrows = $borrows->count();
        $updated = 0;
        $skipped = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($totalBorrows);
        $bar->start();

        foreach ($borrows as $borrow) {
            try {
                if (!$borrow->items || $borrow->items->count() == 0) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Tính tổng tien_ship từ items
                $tienShipFromItems = $borrow->items->sum(function($item) {
                    return floatval($item->tien_ship ?? 0);
                });

                $currentTienShip = floatval($borrow->tien_ship ?? 0);

                // Chỉ cập nhật nếu:
                // 1. Force mode: cập nhật tất cả
                // 2. Hoặc borrow->tien_ship = 0 và items có tien_ship > 0
                $shouldUpdate = $force || ($currentTienShip == 0 && $tienShipFromItems > 0);

                if ($shouldUpdate && $tienShipFromItems != $currentTienShip) {
                    if (!$dryRun) {
                        // Sử dụng recalculateTotals để đồng bộ tất cả (tien_coc, tien_thue, tien_ship, tong_tien)
                        // Method này sẽ tự động tính tien_ship từ items và cập nhật tong_tien
                        $borrow->load('voucher');
                        $borrow->recalculateTotals();
                    }
                    $updated++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("❌ Lỗi khi xử lý borrow ID {$borrow->id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Hiển thị kết quả
        $this->info("📊 Kết quả:");
        $this->table(
            ['Thống kê', 'Số lượng'],
            [
                ['Tổng số phiếu mượn', number_format($totalBorrows)],
                ['Đã cập nhật', number_format($updated)],
                ['Đã bỏ qua', number_format($skipped)],
                ['Lỗi', number_format($errors)],
            ]
        );

        if ($dryRun) {
            $this->warn('⚠️  Chế độ DRY-RUN: Không có thay đổi nào được lưu vào database.');
            $this->info('💡 Chạy lại command không có --dry-run để thực hiện cập nhật.');
        } else {
            if ($updated > 0) {
                $this->info("✅ Đã đồng bộ thành công {$updated} phiếu mượn!");
            } else {
                $this->info("ℹ️  Không có phiếu mượn nào cần cập nhật.");
            }
        }

        return 0;
    }
}

