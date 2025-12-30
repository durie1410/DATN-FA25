<?php

/**
 * Script trực tiếp để thêm các cột xác nhận khách hàng
 * Chạy: php add_customer_confirmation_columns_direct.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "==========================================\n";
echo "  THÊM CÁC CỘT XÁC NHẬN KHÁCH HÀNG\n";
echo "==========================================\n\n";

try {
    // Kiểm tra xem bảng borrows có tồn tại không
    if (!Schema::hasTable('borrows')) {
        echo "✗ LỖI: Bảng 'borrows' không tồn tại!\n";
        exit(1);
    }

    echo "✓ Bảng 'borrows' đã tồn tại\n\n";

    // Lấy danh sách cột hiện tại
    $columns = Schema::getColumnListing('borrows');
    echo "Số cột hiện tại trong bảng: " . count($columns) . "\n\n";

    // Thêm cột customer_confirmed_delivery
    if (in_array('customer_confirmed_delivery', $columns)) {
        echo "✓ Cột 'customer_confirmed_delivery' đã tồn tại\n";
    } else {
        echo "Đang thêm cột 'customer_confirmed_delivery'...\n";
        try {
            DB::statement("
                ALTER TABLE borrows 
                ADD COLUMN customer_confirmed_delivery TINYINT(1) NOT NULL DEFAULT 0 
                COMMENT 'Khách hàng đã xác nhận nhận sách'
            ");
            echo "✓ Đã thêm cột 'customer_confirmed_delivery' thành công!\n";
        } catch (\Exception $e) {
            echo "✗ Lỗi khi thêm cột 'customer_confirmed_delivery': " . $e->getMessage() . "\n";
        }
    }

    echo "\n";

    // Thêm cột customer_confirmed_delivery_at
    $columns = Schema::getColumnListing('borrows'); // Refresh lại danh sách
    if (in_array('customer_confirmed_delivery_at', $columns)) {
        echo "✓ Cột 'customer_confirmed_delivery_at' đã tồn tại\n";
    } else {
        echo "Đang thêm cột 'customer_confirmed_delivery_at'...\n";
        try {
            DB::statement("
                ALTER TABLE borrows 
                ADD COLUMN customer_confirmed_delivery_at TIMESTAMP NULL DEFAULT NULL 
                COMMENT 'Thời gian khách hàng xác nhận nhận sách'
            ");
            echo "✓ Đã thêm cột 'customer_confirmed_delivery_at' thành công!\n";
        } catch (\Exception $e) {
            echo "✗ Lỗi khi thêm cột 'customer_confirmed_delivery_at': " . $e->getMessage() . "\n";
        }
    }

    echo "\n";
    echo "==========================================\n";
    echo "  HOÀN TẤT!\n";
    echo "==========================================\n";
    
    // Kiểm tra lại
    $finalColumns = Schema::getColumnListing('borrows');
    if (in_array('customer_confirmed_delivery', $finalColumns) && 
        in_array('customer_confirmed_delivery_at', $finalColumns)) {
        echo "\n✓ Cả hai cột đã được thêm thành công!\n";
        echo "  - customer_confirmed_delivery\n";
        echo "  - customer_confirmed_delivery_at\n";
    } else {
        echo "\n⚠ Có vấn đề với một số cột. Vui lòng kiểm tra lại.\n";
    }

} catch (\Exception $e) {
    echo "\n✗ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

echo "\n";
