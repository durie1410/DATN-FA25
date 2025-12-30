<?php

/**
 * Script sửa lỗi số dư ví và xóa giao dịch trùng lặp
 * 
 * Chạy script này bằng: php fix_wallet_balance.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Borrow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== SỬA LỖI SỐ DƯ VÍ VÀ XÓA GIAO DỊCH TRÙNG LẶP ===\n\n";

// Tìm tất cả các ví
$wallets = Wallet::with('transactions')->get();

echo "Tìm thấy " . $wallets->count() . " ví.\n\n";

$fixedWallets = 0;
$duplicateTransactionsRemoved = 0;

foreach ($wallets as $wallet) {
    echo "--- Ví ID: {$wallet->id}, User ID: {$wallet->user_id} ---\n";
    echo "Số dư hiện tại: " . number_format($wallet->balance, 0, ',', '.') . " VNĐ\n";
    
    // Tìm các giao dịch trùng lặp (cùng reference_type, reference_id, type, amount trong cùng 1 giây)
    $transactions = WalletTransaction::where('wallet_id', $wallet->id)
        ->orderBy('created_at', 'asc')
        ->get();
    
    echo "Tổng số giao dịch: " . $transactions->count() . "\n";
    
    // Tìm và xóa giao dịch trùng lặp
    $seen = [];
    $duplicates = [];
    
    foreach ($transactions as $transaction) {
        // Tạo key để kiểm tra trùng lặp
        $key = sprintf(
            '%s-%s-%s-%s-%s',
            $transaction->reference_type,
            $transaction->reference_id,
            $transaction->type,
            $transaction->amount,
            $transaction->created_at->format('Y-m-d H:i:s')
        );
        
        if (isset($seen[$key])) {
            // Đây là giao dịch trùng lặp
            $duplicates[] = $transaction;
            echo "  ⚠️  Tìm thấy giao dịch trùng lặp: ID {$transaction->id}, Amount: " . number_format($transaction->amount, 0, ',', '.') . " VNĐ\n";
        } else {
            $seen[$key] = $transaction;
        }
    }
    
    // Xóa các giao dịch trùng lặp (giữ lại giao dịch đầu tiên)
    if (count($duplicates) > 0) {
        echo "  Xóa " . count($duplicates) . " giao dịch trùng lặp...\n";
        
        DB::beginTransaction();
        try {
            foreach ($duplicates as $duplicate) {
                $duplicate->delete();
                $duplicateTransactionsRemoved++;
            }
            DB::commit();
            echo "  ✅ Đã xóa các giao dịch trùng lặp\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "  ❌ Lỗi khi xóa: " . $e->getMessage() . "\n";
            continue;
        }
    }
    
    // Tính lại số dư từ các giao dịch
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
    
    echo "Số dư tính từ giao dịch: " . number_format($calculatedBalance, 0, ',', '.') . " VNĐ\n";
    
    // So sánh với số dư hiện tại
    if (abs($wallet->balance - $calculatedBalance) > 0.01) {
        echo "  ⚠️  Số dư không khớp! Đang cập nhật...\n";
        
        DB::beginTransaction();
        try {
            $wallet->balance = $calculatedBalance;
            $wallet->save();
            DB::commit();
            
            echo "  ✅ Đã cập nhật số dư: " . number_format($calculatedBalance, 0, ',', '.') . " VNĐ\n";
            $fixedWallets++;
            
            Log::info('Fixed wallet balance', [
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'old_balance' => $wallet->getOriginal('balance'),
                'new_balance' => $calculatedBalance
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            echo "  ❌ Lỗi khi cập nhật: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✓ Số dư đúng\n";
    }
    
    echo "\n";
}

echo "\n=== KẾT QUẢ ===\n";
echo "Đã sửa số dư: {$fixedWallets} ví\n";
echo "Đã xóa giao dịch trùng lặp: {$duplicateTransactionsRemoved} giao dịch\n";
echo "\nHoàn tất!\n";

