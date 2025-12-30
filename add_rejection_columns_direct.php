<?php

/**
 * Script trực tiếp để thêm các cột từ chối nhận sách
 * Chạy: php add_rejection_columns_direct.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "==========================================\n";
echo "  THÊM CÁC CỘT TỪ CHỐI NHẬN SÁCH\n";
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

    // Thêm cột customer_rejected_delivery
    if (in_array('customer_rejected_delivery', $columns)) {
        echo "✓ Cột 'customer_rejected_delivery' đã tồn tại\n";
    } else {
        echo "Đang thêm cột 'customer_rejected_delivery'...\n";
        try {
            DB::statement("
                ALTER TABLE borrows 
                ADD COLUMN customer_rejected_delivery TINYINT(1) NOT NULL DEFAULT 0 
                COMMENT 'Khách hàng đã từ chối nhận sách'
            ");
            echo "✓ Đã thêm cột 'customer_rejected_delivery' thành công!\n";
        } catch (\Exception $e) {
            echo "✗ Lỗi khi thêm cột 'customer_rejected_delivery': " . $e->getMessage() . "\n";
        }
    }

    echo "\n";

    // Thêm cột customer_rejected_delivery_at
    $columns = Schema::getColumnListing('borrows'); // Refresh lại danh sách
    if (in_array('customer_rejected_delivery_at', $columns)) {
        echo "✓ Cột 'customer_rejected_delivery_at' đã tồn tại\n";
    } else {
        echo "Đang thêm cột 'customer_rejected_delivery_at'...\n";
        try {
            DB::statement("
                ALTER TABLE borrows 
                ADD COLUMN customer_rejected_delivery_at TIMESTAMP NULL DEFAULT NULL 
                COMMENT 'Thời gian khách hàng từ chối nhận sách'
            ");
            echo "✓ Đã thêm cột 'customer_rejected_delivery_at' thành công!\n";
        } catch (\Exception $e) {
            echo "✗ Lỗi khi thêm cột 'customer_rejected_delivery_at': " . $e->getMessage() . "\n";
        }
    }

    echo "\n";

    // Thêm cột customer_rejection_reason
    $columns = Schema::getColumnListing('borrows'); // Refresh lại danh sách
    if (in_array('customer_rejection_reason', $columns)) {
        echo "✓ Cột 'customer_rejection_reason' đã tồn tại\n";
    } else {
        echo "Đang thêm cột 'customer_rejection_reason'...\n";
        try {
            DB::statement("
                ALTER TABLE borrows 
                ADD COLUMN customer_rejection_reason TEXT NULL DEFAULT NULL 
                COMMENT 'Lý do khách hàng từ chối nhận sách'
            ");
            echo "✓ Đã thêm cột 'customer_rejection_reason' thành công!\n";
        } catch (\Exception $e) {
            echo "✗ Lỗi khi thêm cột 'customer_rejection_reason': " . $e->getMessage() . "\n";
        }
    }

    echo "\n";
    echo "==========================================\n";
    echo "  HOÀN TẤT!\n";
    echo "==========================================\n";
    
    // Kiểm tra lại
    $finalColumns = Schema::getColumnListing('borrows');
    $allAdded = true;
    
    if (!in_array('customer_rejected_delivery', $finalColumns)) {
        echo "\n✗ Cột 'customer_rejected_delivery' CHƯA được thêm!\n";
        $allAdded = false;
    }
    if (!in_array('customer_rejected_delivery_at', $finalColumns)) {
        echo "✗ Cột 'customer_rejected_delivery_at' CHƯA được thêm!\n";
        $allAdded = false;
    }
    if (!in_array('customer_rejection_reason', $finalColumns)) {
        echo "✗ Cột 'customer_rejection_reason' CHƯA được thêm!\n";
        $allAdded = false;
    }
    
    if ($allAdded) {
        echo "\n✓ Cả ba cột đã được thêm thành công!\n";
        echo "  - customer_rejected_delivery\n";
        echo "  - customer_rejected_delivery_at\n";
        echo "  - customer_rejection_reason\n";
        echo "\n✅ Bạn có thể thử lại chức năng từ chối nhận sách!\n";
    } else {
        echo "\n⚠ Có vấn đề với một số cột. Vui lòng kiểm tra lại.\n";
        echo "\n💡 Gợi ý: Chạy SQL trực tiếp trong phpMyAdmin:\n";
        echo "   Xem file: FIX_REJECTION_COLUMNS.sql\n";
    }

} catch (\Exception $e) {
    echo "\n✗ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}

echo "\n";
