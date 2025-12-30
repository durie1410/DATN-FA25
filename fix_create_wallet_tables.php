<?php

/**
 * Script tạo bảng wallets và wallet_transactions TRỰC TIẾP
 * Chạy: php fix_create_wallet_tables.php
 */

define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "==========================================\n";
echo "  TAO BANG WALLET TRUC TIEP\n";
echo "==========================================\n\n";

try {
    // Kiểm tra kết nối
    DB::connection()->getPdo();
    echo "✓ Ket noi database thanh cong\n\n";
    
    // Tắt kiểm tra foreign key tạm thời
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // Kiểm tra và tạo bảng wallets
    echo "1. Kiem tra bang 'wallets'...\n";
    if (Schema::hasTable('wallets')) {
        echo "   ✓ Bang 'wallets' da ton tai\n";
    } else {
        echo "   → Dang tao bang 'wallets'...\n";
        
        // Tạo bảng bằng SQL trực tiếp
        DB::statement("
            CREATE TABLE IF NOT EXISTS `wallets` (
                `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` bigint UNSIGNED NOT NULL,
                `balance` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Số dư ví',
                `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Ví có đang hoạt động không',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `wallets_user_id_unique` (`user_id`),
                KEY `wallets_user_id_index` (`user_id`),
                CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        if (Schema::hasTable('wallets')) {
            echo "   ✓ Da tao bang 'wallets' thanh cong!\n";
        } else {
            echo "   ✗ Khong the tao bang 'wallets'\n";
        }
    }
    
    echo "\n";
    
    // Kiểm tra và tạo bảng wallet_transactions
    echo "2. Kiem tra bang 'wallet_transactions'...\n";
    if (Schema::hasTable('wallet_transactions')) {
        echo "   ✓ Bang 'wallet_transactions' da ton tai\n";
    } else {
        echo "   → Dang tao bang 'wallet_transactions'...\n";
        
        // Tạo bảng bằng SQL trực tiếp
        DB::statement("
            CREATE TABLE IF NOT EXISTS `wallet_transactions` (
                `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
                `wallet_id` bigint UNSIGNED NOT NULL,
                `type` varchar(255) NOT NULL COMMENT 'Loại giao dịch: deposit (nạp), refund (hoàn cọc), withdraw (rút), payment (thanh toán)',
                `amount` decimal(15,2) NOT NULL COMMENT 'Số tiền',
                `balance_before` decimal(15,2) NOT NULL COMMENT 'Số dư trước giao dịch',
                `balance_after` decimal(15,2) NOT NULL COMMENT 'Số dư sau giao dịch',
                `description` varchar(255) DEFAULT NULL COMMENT 'Mô tả giao dịch',
                `reference_type` varchar(255) DEFAULT NULL COMMENT 'Loại tham chiếu',
                `reference_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID của đối tượng tham chiếu',
                `status` varchar(255) NOT NULL DEFAULT 'completed' COMMENT 'Trạng thái: pending, completed, failed, cancelled',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
                KEY `wallet_transactions_wallet_id_type_index` (`wallet_id`,`type`),
                KEY `wallet_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`),
                KEY `wallet_transactions_created_at_index` (`created_at`),
                CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        if (Schema::hasTable('wallet_transactions')) {
            echo "   ✓ Da tao bang 'wallet_transactions' thanh cong!\n";
        } else {
            echo "   ✗ Khong the tao bang 'wallet_transactions'\n";
        }
    }
    
    // Bật lại kiểm tra foreign key
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
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



