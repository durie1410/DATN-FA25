<?php

/**
 * Script chạy migrations cho hệ thống ví
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
echo "  CHAY MIGRATIONS CHO HE THONG VI\n";
echo "==========================================\n\n";

try {
    // Kiểm tra kết nối database
    DB::connection()->getPdo();
    echo "✓ Ket noi database thanh cong\n\n";
    
    // Kiểm tra bảng wallets
    echo "1. Kiem tra bang 'wallets'...\n";
    $walletsExists = Schema::hasTable('wallets');
    
    if ($walletsExists) {
        echo "   ✓ Bang 'wallets' da ton tai\n";
    } else {
        echo "   → Bang 'wallets' chua ton tai, dang tao...\n";
        
        // Chạy migration cho wallets
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_12_07_000000_create_wallets_table.php',
            '--force' => true
        ]);
        
        $output = Artisan::output();
        if (!empty(trim($output))) {
            echo "   " . $output . "\n";
        }
        
        // Kiểm tra lại
        if (Schema::hasTable('wallets')) {
            echo "   ✓ Da tao bang 'wallets' thanh cong!\n";
        } else {
            echo "   ✗ Khong the tao bang 'wallets'\n";
        }
    }
    
    echo "\n";
    
    // Kiểm tra bảng wallet_transactions
    echo "2. Kiem tra bang 'wallet_transactions'...\n";
    $transactionsExists = Schema::hasTable('wallet_transactions');
    
    if ($transactionsExists) {
        echo "   ✓ Bang 'wallet_transactions' da ton tai\n";
    } else {
        echo "   → Bang 'wallet_transactions' chua ton tai, dang tao...\n";
        
        // Chạy migration cho wallet_transactions
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_12_07_000001_create_wallet_transactions_table.php',
            '--force' => true
        ]);
        
        $output = Artisan::output();
        if (!empty(trim($output))) {
            echo "   " . $output . "\n";
        }
        
        // Kiểm tra lại
        if (Schema::hasTable('wallet_transactions')) {
            echo "   ✓ Da tao bang 'wallet_transactions' thanh cong!\n";
        } else {
            echo "   ✗ Khong the tao bang 'wallet_transactions'\n";
        }
    }
    
    echo "\n";
    echo "==========================================\n";
    
    // Kiểm tra kết quả cuối cùng
    if (Schema::hasTable('wallets') && Schema::hasTable('wallet_transactions')) {
        echo "  ✓ HOAN TAT! HAI BANG DA DUOC TAO THANH CONG!\n";
        echo "==========================================\n";
        echo "\n";
        echo "Ban co the:\n";
        echo "  - Truy cap trang vi: http://quanlythuviennn.test/account/wallet\n";
        echo "  - He thong se tu dong tao vi cho khach hang khi can\n";
        echo "  - Tien hoan coc se tu dong chuyen vao vi khi hoan tat don muon\n";
        echo "\n";
    } else {
        echo "  ✗ CO LOI XAY RA!\n";
        echo "==========================================\n";
        echo "\n";
        echo "Vui long kiem tra:\n";
        echo "  - Ket noi database trong file .env\n";
        echo "  - Quyen cua user database\n";
        echo "  - Hoac chay SQL truc tiep trong phpMyAdmin\n";
        echo "\n";
    }
    
} catch (\Exception $e) {
    echo "\n";
    echo "==========================================\n";
    echo "  ✗ LOI: " . $e->getMessage() . "\n";
    echo "==========================================\n";
    echo "\n";
    echo "Chi tiet loi:\n";
    echo $e->getTraceAsString() . "\n";
    echo "\n";
    exit(1);
}



