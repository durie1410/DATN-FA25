<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WalletTransaction;
use App\Models\Borrow;
use Illuminate\Support\Facades\DB;

echo "=== KIỂM TRA VÀ XÓA GIAO DỊCH TRÙNG LẶP ===\n\n";

// Tìm tất cả các giao dịch hoàn tiền
$allRefunds = WalletTransaction::where('type', 'refund')
    ->whereNotNull('reference_type')
    ->whereNotNull('reference_id')
    ->orderBy('reference_id')
    ->orderBy('created_at')
    ->get();

echo "Tổng số giao dịch hoàn tiền: " . $allRefunds->count() . "\n\n";

// Nhóm theo reference
$grouped = $allRefunds->groupBy(function($transaction) {
    return $transaction->reference_type . '-' . $transaction->reference_id;
});

$duplicatesRemoved = 0;

foreach ($grouped as $key => $transactions) {
    if ($transactions->count() > 1) {
        echo "--- " . $key . " ---\n";
        echo "Tìm thấy " . $transactions->count() . " giao dịch\n";
        
        // Giữ lại giao dịch đầu tiên, xóa các giao dịch còn lại
        $first = $transactions->first();
        $duplicates = $transactions->where('id', '!=', $first->id);
        
        echo "Giữ lại giao dịch ID: " . $first->id . " (Amount: " . number_format($first->amount) . " VNĐ)\n";
        echo "Xóa " . $duplicates->count() . " giao dịch trùng lặp...\n";
        
        DB::beginTransaction();
        try {
            foreach ($duplicates as $dup) {
                $dup->delete();
                $duplicatesRemoved++;
            }
            DB::commit();
            echo "✅ Đã xóa các giao dịch trùng lặp\n\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ Lỗi: " . $e->getMessage() . "\n\n";
        }
    }
}

// Tính lại số dư cho tất cả ví
echo "=== TÍNH LẠI SỐ DƯ ===\n\n";
use App\Models\Wallet;

$wallets = Wallet::all();
foreach ($wallets as $wallet) {
    $transactions = WalletTransaction::where('wallet_id', $wallet->id)
        ->orderBy('created_at', 'asc')
        ->get();
    
    $calculatedBalance = 0;
    foreach ($transactions as $transaction) {
        if ($transaction->type === 'deposit' || $transaction->type === 'refund') {
            $calculatedBalance += $transaction->amount;
        } elseif ($transaction->type === 'withdraw' || $transaction->type === 'payment') {
            $calculatedBalance -= $transaction->amount;
        }
    }
    
    if (abs($wallet->balance - $calculatedBalance) > 0.01) {
        echo "Ví ID {$wallet->id} (User ID: {$wallet->user_id}):\n";
        echo "  Số dư cũ: " . number_format($wallet->balance) . " VNĐ\n";
        echo "  Số dư mới: " . number_format($calculatedBalance) . " VNĐ\n";
        
        DB::beginTransaction();
        try {
            $wallet->balance = $calculatedBalance;
            $wallet->save();
            DB::commit();
            echo "  ✅ Đã cập nhật\n\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "  ❌ Lỗi: " . $e->getMessage() . "\n\n";
        }
    }
}

echo "\n=== KẾT QUẢ ===\n";
echo "Đã xóa: {$duplicatesRemoved} giao dịch trùng lặp\n";
echo "Hoàn tất!\n";


