<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\BorrowPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixCancelledOrderRefunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refunds:fix-cancelled {--borrow-id= : ID của đơn cụ thể cần xử lý}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hoàn tiền cho các đơn hàng đã hủy nhưng chưa được hoàn tiền vào ví';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== HOÀN TIỀN CHO CÁC ĐƠN ĐÃ HỦY ===');
        $this->newLine();

        $borrowId = $this->option('borrow-id');
        
        if ($borrowId) {
            // Xử lý đơn cụ thể
            $borrows = Borrow::where('id', $borrowId)
                ->where('trang_thai', 'Huy')
                ->with(['reader', 'items', 'payments'])
                ->get();
        } else {
            // Tìm tất cả các đơn đã hủy
            $borrows = Borrow::where('trang_thai', 'Huy')
                ->with(['reader', 'items', 'payments'])
                ->get();
        }

        if ($borrows->isEmpty()) {
            $this->warn('Không tìm thấy đơn hàng nào đã hủy.');
            return 0;
        }

        $this->info("Tìm thấy {$borrows->count()} đơn hàng đã hủy.");
        $this->newLine();

        $fixedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        foreach ($borrows as $borrow) {
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

            // Kiểm tra payment method
            $hasOnlinePayment = $borrow->payments()
                ->where('payment_method', 'online')
                ->where('payment_status', 'success')
                ->exists();

            $hasOfflinePayment = $borrow->payments()
                ->where('payment_method', 'offline')
                ->where('payment_status', 'success')
                ->exists();

            // Xác định số tiền cần hoàn
            $refundAmount = 0;
            $refundDescription = '';

            if ($hasOnlinePayment) {
                // Online: hoàn cả tiền cọc và tiền thuê (vì đã thanh toán cả 2)
                $refundAmount = $borrow->tien_coc + $borrow->tien_thue;
                $refundDescription = 'Hoàn tiền cọc và tiền thuê cho đơn mượn #' . $borrow->id . ' (Đơn hàng đã hủy - thanh toán online)';
            } elseif ($hasOfflinePayment) {
                // Offline: chỉ hoàn tiền cọc (vì chưa thanh toán tiền thuê)
                $refundAmount = $borrow->tien_coc;
                $refundDescription = 'Hoàn tiền cọc cho đơn mượn #' . $borrow->id . ' (Đơn hàng đã hủy - thanh toán offline)';
            } else {
                // Nếu không có payment record, kiểm tra xem có phải là chuyển khoản không
                // Dựa vào ghi chú hoặc thông tin khác
                // Tạm thời hoàn tiền cọc nếu có tiền cọc
                if ($borrow->tien_coc > 0) {
                    $refundAmount = $borrow->tien_coc;
                    $refundDescription = 'Hoàn tiền cọc cho đơn mượn #' . $borrow->id . ' (Đơn hàng đã hủy)';
                }
            }

            if ($refundAmount <= 0) {
                $this->warn("  ⚠️  Không có tiền cần hoàn (refundAmount = {$refundAmount})");
                $this->line("     Tiền cọc: " . number_format($borrow->tien_coc) . " VNĐ");
                $this->line("     Tiền thuê: " . number_format($borrow->tien_thue) . " VNĐ");
                $this->line("     Có payment online: " . ($hasOnlinePayment ? 'Có' : 'Không'));
                $this->line("     Có payment offline: " . ($hasOfflinePayment ? 'Có' : 'Không'));
                $skippedCount++;
                continue;
            }

            // Thử hoàn tiền
            try {
                DB::beginTransaction();

                $wallet = Wallet::getOrCreateForUser($borrow->reader->user_id);
                $balanceBefore = $wallet->balance;

                $wallet->refund(
                    $refundAmount,
                    $refundDescription,
                    $borrow
                );

                $wallet->refresh();
                $balanceAfter = $wallet->balance;

                DB::commit();

                $this->info("  ✅ Đã hoàn tiền thành công!");
                $this->line("     Số tiền: " . number_format($refundAmount) . " VNĐ");
                $this->line("     Số dư ví trước: " . number_format($balanceBefore) . " VNĐ");
                $this->line("     Số dư ví sau: " . number_format($balanceAfter) . " VNĐ");
                $this->line("     User ID: {$borrow->reader->user_id}");
                $this->line("     Payment method: " . ($hasOnlinePayment ? 'Online' : ($hasOfflinePayment ? 'Offline' : 'Không xác định')));

                $fixedCount++;

                Log::info('Fixed refund for cancelled order', [
                    'borrow_id' => $borrow->id,
                    'user_id' => $borrow->reader->user_id,
                    'amount' => $refundAmount,
                    'wallet_id' => $wallet->id,
                    'payment_method' => $hasOnlinePayment ? 'online' : ($hasOfflinePayment ? 'offline' : 'unknown')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("  ❌ Lỗi khi hoàn tiền: " . $e->getMessage());
                $errorCount++;

                Log::error('Error fixing refund for cancelled order', [
                    'borrow_id' => $borrow->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $this->newLine();
        }

        $this->newLine();
        $this->info('=== KẾT QUẢ ===');
        $this->info("Đã hoàn tiền: {$fixedCount} đơn hàng");
        $this->info("Đã bỏ qua: {$skippedCount} đơn hàng");
        $this->info("Lỗi: {$errorCount} đơn hàng");
        $this->newLine();
        $this->info('Hoàn tất!');

        return 0;
    }
}

