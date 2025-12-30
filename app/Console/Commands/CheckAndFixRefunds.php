<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckAndFixRefunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refunds:check-and-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và sửa các đơn hàng đã hoàn thành nhưng chưa được hoàn tiền cọc';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== KIỂM TRA VÀ SỬA HOÀN TIỀN CỌC ===');
        $this->newLine();

        // Tìm các đơn hàng đã hoàn thành
        $completedBorrows = Borrow::where('trang_thai_chi_tiet', Borrow::STATUS_HOAN_TAT_DON_HANG)
            ->with(['reader', 'items'])
            ->get();

        $this->info("Tìm thấy {$completedBorrows->count()} đơn hàng đã hoàn thành.");
        $this->newLine();

        $fixedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        foreach ($completedBorrows as $borrow) {
            $this->line("--- Đơn hàng #{$borrow->id} ---");

            // Kiểm tra các điều kiện
            if (!$borrow->reader) {
                $this->error("  ❌ Không có thông tin độc giả");
                $skippedCount++;
                continue;
            }

            if (!$borrow->reader->user_id) {
                $this->error("  ❌ Độc giả chưa liên kết với user (user_id = null)");
                $this->line("     Reader ID: {$borrow->reader_id}, User ID: null");
                $skippedCount++;
                continue;
            }

            // Tính lại tổng tiền từ items
            $borrow->recalculateTotals();
            $borrow->refresh();

            if ($borrow->tien_coc <= 0) {
                $this->warn("  ⚠️  Đơn hàng không có tiền cọc (tien_coc = {$borrow->tien_coc})");
                $skippedCount++;
                continue;
            }

            // Tính lại tien_coc_hoan_tra
            $borrow->updateRefundAmount();
            $borrow->refresh();

            if ($borrow->tien_coc_hoan_tra <= 0) {
                $this->warn("  ⚠️  Tiền cọc hoàn trả <= 0");
                $this->line("     Tiền cọc: " . number_format($borrow->tien_coc) . " VNĐ");
                $this->line("     Phí hỏng sách: " . number_format($borrow->phi_hong_sach ?? 0) . " VNĐ");
                $this->line("     Tiền cọc hoàn trả: " . number_format($borrow->tien_coc_hoan_tra) . " VNĐ");
                $skippedCount++;
                continue;
            }

            // Kiểm tra xem đã có transaction hoàn tiền chưa
            $existingTransaction = WalletTransaction::where('reference_type', Borrow::class)
                ->where('reference_id', $borrow->id)
                ->where('type', 'refund')
                ->first();

            if ($existingTransaction) {
                $this->info("  ✓ Đã có giao dịch hoàn tiền (ID: {$existingTransaction->id})");
                $this->line("     Số tiền: " . number_format($existingTransaction->amount) . " VNĐ");
                $skippedCount++;
                continue;
            }

            // Thử hoàn tiền
            try {
                DB::beginTransaction();

                $wallet = Wallet::getOrCreateForUser($borrow->reader->user_id);
                $balanceBefore = $wallet->balance;

                $description = 'Hoàn tiền cọc cho đơn mượn #' . $borrow->id;
                if ($borrow->phi_hong_sach > 0) {
                    $description .= ' (Đã trừ phí hỏng sách: ' . number_format($borrow->phi_hong_sach, 0, ',', '.') . ' VNĐ)';
                }

                $wallet->refund(
                    $borrow->tien_coc_hoan_tra,
                    $description,
                    $borrow
                );

                $wallet->refresh();
                $balanceAfter = $wallet->balance;

                DB::commit();

                $this->info("  ✅ Đã hoàn tiền thành công!");
                $this->line("     Số tiền: " . number_format($borrow->tien_coc_hoan_tra) . " VNĐ");
                $this->line("     Số dư ví trước: " . number_format($balanceBefore) . " VNĐ");
                $this->line("     Số dư ví sau: " . number_format($balanceAfter) . " VNĐ");
                $this->line("     User ID: {$borrow->reader->user_id}");

                $fixedCount++;

                Log::info('Fixed refund for completed order', [
                    'borrow_id' => $borrow->id,
                    'user_id' => $borrow->reader->user_id,
                    'amount' => $borrow->tien_coc_hoan_tra,
                    'wallet_id' => $wallet->id
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("  ❌ Lỗi khi hoàn tiền: " . $e->getMessage());
                $errorCount++;

                Log::error('Error fixing refund for order', [
                    'borrow_id' => $borrow->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $this->newLine();
        }

        $this->newLine();
        $this->info('=== KẾT QUẢ ===');
        $this->info("Đã sửa: {$fixedCount} đơn hàng");
        $this->info("Đã bỏ qua: {$skippedCount} đơn hàng");
        $this->info("Lỗi: {$errorCount} đơn hàng");
        $this->newLine();
        $this->info('Hoàn tất!');

        return 0;
    }
}


