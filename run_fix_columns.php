<?php

/**
 * Script để thêm các cột xác nhận khách hàng - VERSION ĐƠN GIẢN
 * Chạy: php run_fix_columns.php
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
echo "  SỬA LỖI: Thêm cột xác nhận khách hàng\n";
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
    
    // Kiểm tra và thêm cột customer_confirmed_delivery
    echo "1. Kiểm tra cột 'customer_confirmed_delivery'...\n";
    $columns = DB::select("SHOW COLUMNS FROM borrows LIKE 'customer_confirmed_delivery'");
    
    if (empty($columns)) {
        echo "   → Cột chưa tồn tại, đang thêm...\n";
        DB::statement("ALTER TABLE borrows ADD COLUMN customer_confirmed_delivery TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Khách hàng đã xác nhận nhận sách'");
        echo "   ✓ Đã thêm cột 'customer_confirmed_delivery' thành công!\n";
    } else {
        echo "   ✓ Cột 'customer_confirmed_delivery' đã tồn tại\n";
    }
    
    echo "\n";
    
    // Kiểm tra và thêm cột customer_confirmed_delivery_at
    echo "2. Kiểm tra cột 'customer_confirmed_delivery_at'...\n";
    $columns = DB::select("SHOW COLUMNS FROM borrows LIKE 'customer_confirmed_delivery_at'");
    
    if (empty($columns)) {
        echo "   → Cột chưa tồn tại, đang thêm...\n";
        DB::statement("ALTER TABLE borrows ADD COLUMN customer_confirmed_delivery_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian khách hàng xác nhận nhận sách'");
        echo "   ✓ Đã thêm cột 'customer_confirmed_delivery_at' thành công!\n";
    } else {
        echo "   ✓ Cột 'customer_confirmed_delivery_at' đã tồn tại\n";
    }
    
    echo "\n";
    echo "==========================================\n";
    echo "  HOÀN TẤT!\n";
    echo "==========================================\n";
    echo "\n✓ Các cột đã được thêm thành công vào bảng borrows!\n";
    echo "  - customer_confirmed_delivery (TINYINT, default: 0)\n";
    echo "  - customer_confirmed_delivery_at (TIMESTAMP, nullable)\n";
    echo "\nBây giờ bạn có thể làm mới trang web và lỗi sẽ biến mất!\n\n";
    
} catch (Exception $e) {
    echo "\n";
    echo "==========================================\n";
    echo "  LỖI XẢY RA\n";
    echo "==========================================\n";
    echo "✗ " . $e->getMessage() . "\n";
    echo "\nFile: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\n";
    exit(1);
}
