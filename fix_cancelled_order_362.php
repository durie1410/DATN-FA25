<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Borrow;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\BorrowPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== HOÀN TIỀN CHO ĐƠN #362 ===\n\n";

$borrow = Borrow::with(['reader', 'items', 'payments'])->find(362);

if (!$borrow) {
    echo "❌ Không tìm thấy đơn hàng #362\n";
    exit(1);
}

echo "--- Đơn hàng #{$borrow->id} ---\n";
echo "Trạng thái: {$borrow->trang_thai}\n";

// Kiểm tra các điều kiện
if (!$borrow->reader) {
    echo "❌ Không có thông tin độc giả\n";
    exit(1);
}

if (!$borrow->reader->user_id) {
    echo "❌ Độc giả chưa liên kết với user (user_id = null)\n";
    echo "Reader ID: {$borrow->reader_id}, User ID: null\n";
    exit(1);
}

// Tính lại tổng tiền từ items
$borrow->recalculateTotals();
$borrow->refresh();

echo "Tiền cọc: " . number_format($borrow->tien_coc) . " VNĐ\n";
echo "Tiền thuê: " . number_format($borrow->tien_thue) . " VNĐ\n";

if ($borrow->tien_coc <= 0) {
    echo "⚠️  Đơn hàng không có tiền cọc (tien_coc = {$borrow->tien_coc})\n";
    exit(1);
}

// Kiểm tra xem đã có transaction hoàn tiền chưa
$existingTransaction = WalletTransaction::where('reference_type', Borrow::class)
    ->where('reference_id', $borrow->id)
    ->where('type', 'refund')
    ->first();

if ($existingTransaction) {
    echo "✓ Đã có giao dịch hoàn tiền (ID: {$existingTransaction->id})\n";
    echo "Số tiền: " . number_format($existingTransaction->amount) . " VNĐ\n";
    exit(0);
}

// Chỉ hoàn tiền nếu khách đã thanh toán ONLINE (đã trả tiền rồi)
// COD/Offline không cần hoàn vì chưa thanh toán
$hasOnlinePayment = $borrow->payments()
    ->where('payment_method', 'online')
    ->where('payment_status', 'success')
    ->exists();

echo "Có payment online: " . ($hasOnlinePayment ? 'Có' : 'Không') . "\n";

// Xác định số tiền cần hoàn
$refundAmount = 0;
$refundDescription = '';

if ($hasOnlinePayment) {
    // Online: hoàn đầy đủ - tiền cọc + tiền thuê + tiền ship (nếu có)
    $refundAmount = $borrow->tien_coc + $borrow->tien_thue + ($borrow->tien_ship ?? 0);
    $refundDescription = 'Hoàn tiền cho đơn mượn #' . $borrow->id . ' (Đơn hàng đã hủy - thanh toán online)';
    
    $details = [];
    if ($borrow->tien_coc > 0) {
        $details[] = 'Tiền cọc: ' . number_format($borrow->tien_coc, 0, ',', '.') . ' VNĐ';
    }
    if ($borrow->tien_thue > 0) {
        $details[] = 'Phí thuê: ' . number_format($borrow->tien_thue, 0, ',', '.') . ' VNĐ';
    }
    if (($borrow->tien_ship ?? 0) > 0) {
        $details[] = 'Phí ship: ' . number_format($borrow->tien_ship, 0, ',', '.') . ' VNĐ';
    }
    
    if (!empty($details)) {
        $refundDescription .= ' - ' . implode(', ', $details);
    }
} else {
    echo "⚠️  Đơn hàng này sử dụng thanh toán khi nhận hàng (COD), chưa thanh toán nên không cần hoàn tiền.\n";
    exit(0);
}

if ($refundAmount <= 0) {
    echo "⚠️  Không có tiền cần hoàn (refundAmount = {$refundAmount})\n";
    exit(1);
}

echo "\nSố tiền sẽ hoàn: " . number_format($refundAmount) . " VNĐ\n";
echo "Mô tả: {$refundDescription}\n\n";

// Thử hoàn tiền
try {
    DB::beginTransaction();

    $wallet = Wallet::getOrCreateForUser($borrow->reader->user_id);
    $balanceBefore = $wallet->balance;

    echo "Số dư ví trước: " . number_format($balanceBefore) . " VNĐ\n";

    $wallet->refund(
        $refundAmount,
        $refundDescription,
        $borrow
    );

    $wallet->refresh();
    $balanceAfter = $wallet->balance;

    DB::commit();

    echo "✅ Đã hoàn tiền thành công!\n";
    echo "Số tiền: " . number_format($refundAmount) . " VNĐ\n";
    echo "Số dư ví sau: " . number_format($balanceAfter) . " VNĐ\n";
    echo "User ID: {$borrow->reader->user_id}\n";
    echo "Payment method: " . ($hasOnlinePayment ? 'Online' : ($hasOfflinePayment ? 'Offline' : 'Không xác định')) . "\n";

    Log::info('Fixed refund for cancelled order #362', [
        'borrow_id' => $borrow->id,
        'user_id' => $borrow->reader->user_id,
        'amount' => $refundAmount,
        'wallet_id' => $wallet->id,
        'payment_method' => $hasOnlinePayment ? 'online' : ($hasOfflinePayment ? 'offline' : 'unknown')
    ]);

} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Lỗi khi hoàn tiền: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\nHoàn tất!\n";

