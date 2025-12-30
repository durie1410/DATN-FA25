<?php

/**
 * Script để thêm các cột từ chối nhận sách - VERSION ĐƠN GIẢN
 * Chạy: php run_fix_rejection.php
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
echo "  SỬA LỖI: Thêm cột từ chối nhận sách\n";
echo "==========================================\n\n";

try {
    // Kết nối database
    DB::connection()->getPdo();
    echo "✓ Đã kết nối database thành công\n\n";
    
    // Kiểm tra bảng borrows
    $tableExists = DB::select("SHOW TABLES LIKE 'borrows'");
    if (empty($tableExists)) {
        echo "✗ LỖI: Bảng 'borrows' không tồn tại!\n";
        exit(1);
    }
    echo "✓ Bảng 'borrows' đã tồn tại\n\n";
    
    // Kiểm tra và thêm cột customer_rejected_delivery
    echo "1. Kiểm tra cột 'customer_rejected_delivery'...\n";
    $columns = DB::select("SHOW COLUMNS FROM borrows LIKE 'customer_rejected_delivery'");
    
    if (empty($columns)) {
        echo "   → Cột chưa tồn tại, đang thêm...\n";
        DB::statement("ALTER TABLE borrows ADD COLUMN customer_rejected_delivery TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Khách hàng đã từ chối nhận sách'");
        echo "   ✓ Đã thêm cột 'customer_rejected_delivery' thành công!\n";
    } else {
        echo "   ✓ Cột 'customer_rejected_delivery' đã tồn tại\n";
    }
    
    echo "\n";
    
    // Kiểm tra và thêm cột customer_rejected_delivery_at
    echo "2. Kiểm tra cột 'customer_rejected_delivery_at'...\n";
    $columns = DB::select("SHOW COLUMNS FROM borrows LIKE 'customer_rejected_delivery_at'");
    
    if (empty($columns)) {
        echo "   → Cột chưa tồn tại, đang thêm...\n";
        DB::statement("ALTER TABLE borrows ADD COLUMN customer_rejected_delivery_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian khách hàng từ chối nhận sách'");
        echo "   ✓ Đã thêm cột 'customer_rejected_delivery_at' thành công!\n";
    } else {
        echo "   ✓ Cột 'customer_rejected_delivery_at' đã tồn tại\n";
    }
    
    echo "\n";
    
    // Kiểm tra và thêm cột customer_rejection_reason
    echo "3. Kiểm tra cột 'customer_rejection_reason'...\n";
    $columns = DB::select("SHOW COLUMNS FROM borrows LIKE 'customer_rejection_reason'");
    
    if (empty($columns)) {
        echo "   → Cột chưa tồn tại, đang thêm...\n";
        DB::statement("ALTER TABLE borrows ADD COLUMN customer_rejection_reason TEXT NULL DEFAULT NULL COMMENT 'Lý do khách hàng từ chối nhận sách'");
        echo "   ✓ Đã thêm cột 'customer_rejection_reason' thành công!\n";
    } else {
        echo "   ✓ Cột 'customer_rejection_reason' đã tồn tại\n";
    }
    
    echo "\n";
    echo "==========================================\n";
    echo "  KIỂM TRA KẾT QUẢ\n";
    echo "==========================================\n\n";
    
    // Kiểm tra lại tất cả các cột
    $finalCheck = DB::select("SHOW COLUMNS FROM borrows WHERE Field LIKE 'customer_rejected%' OR Field LIKE 'customer_rejection%'");
    
    if (count($finalCheck) >= 3) {
        echo "✅ HOÀN TẤT! Tất cả các cột đã được thêm thành công:\n\n";
        foreach ($finalCheck as $col) {
            echo "   ✓ " . $col->Field . "\n";
        }
        echo "\n✅ Bạn có thể thử lại chức năng từ chối nhận sách!\n";
    } else {
        echo "⚠ Có vấn đề! Chỉ tìm thấy " . count($finalCheck) . " cột.\n";
        echo "Các cột tìm thấy:\n";
        foreach ($finalCheck as $col) {
            echo "   - " . $col->Field . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "\n✗ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

echo "\n";
