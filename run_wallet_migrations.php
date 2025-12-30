<?php

/**
 * Script để chạy migrations cho hệ thống ví
 * Chạy: php run_wallet_migrations.php
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

echo "\n";
echo "==========================================\n";
echo "  CHẠY MIGRATIONS CHO HỆ THỐNG VÍ\n";
echo "==========================================\n\n";

try {
    // Kết nối database
    DB::connection()->getPdo();
    echo "✓ Đã kết nối database thành công\n\n";
    
    // Kiểm tra xem bảng wallets đã tồn tại chưa
    echo "1. Kiểm tra bảng 'wallets'...\n";
    $walletsExists = Schema::hasTable('wallets');
    
    if ($walletsExists) {
        echo "   ✓ Bảng 'wallets' đã tồn tại\n";
    } else {
        echo "   → Bảng 'wallets' chưa tồn tại, đang tạo...\n";
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_12_07_000000_create_wallets_table.php',
            '--force' => true
        ]);
        echo "   " . Artisan::output();
        echo "   ✓ Đã tạo bảng 'wallets'\n";
    }
    
    echo "\n";
    
    // Kiểm tra xem bảng wallet_transactions đã tồn tại chưa
    echo "2. Kiểm tra bảng 'wallet_transactions'...\n";
    $transactionsExists = Schema::hasTable('wallet_transactions');
    
    if ($transactionsExists) {
        echo "   ✓ Bảng 'wallet_transactions' đã tồn tại\n";
    } else {
        echo "   → Bảng 'wallet_transactions' chưa tồn tại, đang tạo...\n";
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_12_07_000001_create_wallet_transactions_table.php',
            '--force' => true
        ]);
        echo "   " . Artisan::output();
        echo "   ✓ Đã tạo bảng 'wallet_transactions'\n";
    }
    
    echo "\n";
    
    // Kiểm tra lại
    echo "3. Kiểm tra lại...\n";
    if (Schema::hasTable('wallets') && Schema::hasTable('wallet_transactions')) {
        echo "   ✓ Cả hai bảng đã được tạo thành công!\n";
        echo "\n";
        echo "==========================================\n";
        echo "  HOÀN TẤT!\n";
        echo "==========================================\n";
        echo "\n";
        echo "Bạn có thể truy cập trang ví tại: /account/wallet\n";
        echo "\n";
    } else {
        echo "   ✗ Có lỗi xảy ra. Vui lòng kiểm tra lại.\n";
    }
    
} catch (\Exception $e) {
    echo "\n";
    echo "✗ LỖI: " . $e->getMessage() . "\n";
    echo "\n";
    echo "Chi tiết:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}



