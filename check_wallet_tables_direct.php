<?php

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "==========================================\n";
echo "  KIEM TRA BANG WALLET\n";
echo "==========================================\n\n";

try {
    // Kiểm tra bảng wallets
    $walletsExists = Schema::hasTable('wallets');
    echo "1. Bang 'wallets': ";
    if ($walletsExists) {
        echo "✓ DA TON TAI\n";
        
        // Đếm số ví
        $count = DB::table('wallets')->count();
        echo "   - So luong vi: " . $count . "\n";
    } else {
        echo "✗ CHUA TON TAI\n";
    }
    
    echo "\n";
    
    // Kiểm tra bảng wallet_transactions
    $transactionsExists = Schema::hasTable('wallet_transactions');
    echo "2. Bang 'wallet_transactions': ";
    if ($transactionsExists) {
        echo "✓ DA TON TAI\n";
        
        // Đếm số giao dịch
        $count = DB::table('wallet_transactions')->count();
        echo "   - So luong giao dich: " . $count . "\n";
    } else {
        echo "✗ CHUA TON TAI\n";
    }
    
    echo "\n";
    echo "==========================================\n";
    
    if ($walletsExists && $transactionsExists) {
        echo "  ✓ HAI BANG DA DUOC TAO THANH CONG!\n";
        echo "  Ban co the truy cap trang vi tai: /account/wallet\n";
    } else {
        echo "  ✗ CO BANG CHUA DUOC TAO!\n";
        echo "  Vui long chay: php artisan migrate\n";
    }
    
    echo "==========================================\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "LOI: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}



