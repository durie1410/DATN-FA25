<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Borrow;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

echo "=== KIỂM TRA VÀ SỬA HOÀN TIỀN CỌC ===\n\n";

// Tìm các đơn hàng đã hoàn thành
$completedBorrows = Borrow::where('trang_thai_chi_tiet', Borrow::STATUS_HOAN_TAT_DON_HANG)
    ->with(['reader', 'items'])
    ->get();

echo "Tìm thấy " . $completedBorrows->count() . " đơn hàng đã hoàn thành.\n\n";

$fixedCount = 0;
$skippedCount = 0;
$errorCount = 0;

foreach ($completedBorrows as $borrow) {
    echo "--- Đơn hàng #{$borrow->id} ---\n";
    
    if (!$borrow->reader) {
        echo "  ❌ Không có thông tin độc giả\n";
        $skippedCount++;
        continue;
    }
    
    if (!$borrow->reader->user_id) {
        echo "  ❌ Độc giả chưa liên kết với user (user_id = null)\n";
        echo "     Reader ID: {$borrow->reader_id}\n";
        $skippedCount++;
        continue;
    }
    
    // Tính lại tổng tiền từ items
    $borrow->recalculateTotals();
    $borrow->refresh();
    
    if ($borrow->tien_coc <= 0) {
        echo "  ⚠️  Đơn hàng không có tiền cọc (tien_coc = {$borrow->tien_coc})\n";
        $skippedCount++;
        continue;
    }
    
    // Tính lại tien_coc_hoan_tra
    $borrow->updateRefundAmount();
    $borrow->refresh();
    
    if ($borrow->tien_coc_hoan_tra <= 0) {
        echo "  ⚠️  Tiền cọc hoàn trả <= 0\n";
        echo "     Tiền cọc: " . number_format($borrow->tien_coc) . " VNĐ\n";
        echo "     Phí hỏng sách: " . number_format($borrow->phi_hong_sach ?? 0) . " VNĐ\n";
        echo "     Tiền cọc hoàn trả: " . number_format($borrow->tien_coc_hoan_tra) . " VNĐ\n";
        $skippedCount++;
        continue;
    }
    
    // Kiểm tra xem đã có transaction hoàn tiền chưa
    $existingTransaction = WalletTransaction::where('reference_type', Borrow::class)
        ->where('reference_id', $borrow->id)
        ->where('type', 'refund')
        ->first();
    
    if ($existingTransaction) {
        echo "  ✓ Đã có giao dịch hoàn tiền (ID: {$existingTransaction->id})\n";
        echo "     Số tiền: " . number_format($existingTransaction->amount) . " VNĐ\n";
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
        
        echo "  ✅ Đã hoàn tiền thành công!\n";
        echo "     Số tiền: " . number_format($borrow->tien_coc_hoan_tra) . " VNĐ\n";
        echo "     Số dư ví trước: " . number_format($balanceBefore) . " VNĐ\n";
        echo "     Số dư ví sau: " . number_format($balanceAfter) . " VNĐ\n";
        echo "     User ID: {$borrow->reader->user_id}\n";
        
        $fixedCount++;
        
    } catch (\Exception $e) {
        DB::rollBack();
        echo "  ❌ Lỗi khi hoàn tiền: " . $e->getMessage() . "\n";
        $errorCount++;
    }
    
    echo "\n";
}

echo "\n=== KẾT QUẢ ===\n";
echo "Đã sửa: {$fixedCount} đơn hàng\n";
echo "Đã bỏ qua: {$skippedCount} đơn hàng\n";
echo "Lỗi: {$errorCount} đơn hàng\n";
echo "\nHoàn tất!\n";


