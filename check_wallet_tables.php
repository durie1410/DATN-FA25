<?php

/**
 * Script kiểm tra và tạo bảng wallets nếu chưa có
 */

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Đang kiểm tra bảng wallets...\n";
    
    // Kiểm tra bảng wallets
    $walletsExists = Schema::hasTable('wallets');
    echo "Bảng wallets: " . ($walletsExists ? "Đã tồn tại" : "Chưa tồn tại") . "\n";
    
    // Kiểm tra bảng wallet_transactions
    $transactionsExists = Schema::hasTable('wallet_transactions');
    echo "Bảng wallet_transactions: " . ($transactionsExists ? "Đã tồn tại" : "Chưa tồn tại") . "\n";
    
    if (!$walletsExists) {
        echo "\nĐang tạo bảng wallets...\n";
        Schema::create('wallets', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });
        echo "✓ Đã tạo bảng wallets\n";
    }
    
    if (!$transactionsExists) {
        echo "\nĐang tạo bảng wallet_transactions...\n";
        Schema::create('wallet_transactions', function ($table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->string('type');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
            
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->index(['wallet_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
        });
        echo "✓ Đã tạo bảng wallet_transactions\n";
    }
    
    echo "\n✓ Hoàn tất!\n";
    
} catch (\Exception $e) {
    echo "LỖI: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}



