<?php
/**
 * Script để thêm các cột từ chối nhận sách vào bảng borrows
 * Chạy: php add_customer_rejection_columns_direct.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Đang thêm các cột từ chối nhận sách vào bảng borrows...\n\n";

try {
    // Kiểm tra bảng borrows
    if (!Schema::hasTable('borrows')) {
        echo "✗ Bảng borrows không tồn tại!\n";
        exit(1);
    }

    echo "✓ Bảng borrows đã tồn tại\n";

    // Thêm cột customer_rejected_delivery
    if (Schema::hasColumn('borrows', 'customer_rejected_delivery')) {
        echo "✓ Cột customer_rejected_delivery đã tồn tại\n";
    } else {
        echo "Đang thêm cột customer_rejected_delivery...\n";
        DB::statement("
            ALTER TABLE borrows 
            ADD COLUMN customer_rejected_delivery TINYINT(1) NOT NULL DEFAULT 0 
            COMMENT 'Khách hàng đã từ chối nhận sách'
        ");
        echo "✓ Đã thêm cột customer_rejected_delivery thành công!\n";
    }

    // Thêm cột customer_rejected_delivery_at
    if (Schema::hasColumn('borrows', 'customer_rejected_delivery_at')) {
        echo "✓ Cột customer_rejected_delivery_at đã tồn tại\n";
    } else {
        echo "Đang thêm cột customer_rejected_delivery_at...\n";
        DB::statement("
            ALTER TABLE borrows 
            ADD COLUMN customer_rejected_delivery_at TIMESTAMP NULL DEFAULT NULL 
            COMMENT 'Thời gian khách hàng từ chối nhận sách'
        ");
        echo "✓ Đã thêm cột customer_rejected_delivery_at thành công!\n";
    }

    // Thêm cột customer_rejection_reason
    if (Schema::hasColumn('borrows', 'customer_rejection_reason')) {
        echo "✓ Cột customer_rejection_reason đã tồn tại\n";
    } else {
        echo "Đang thêm cột customer_rejection_reason...\n";
        DB::statement("
            ALTER TABLE borrows 
            ADD COLUMN customer_rejection_reason TEXT NULL DEFAULT NULL 
            COMMENT 'Lý do khách hàng từ chối nhận sách'
        ");
        echo "✓ Đã thêm cột customer_rejection_reason thành công!\n";
    }

    // Kiểm tra lại
    echo "\n=== KẾT QUẢ KIỂM TRA ===\n";
    $columns = ['customer_rejected_delivery', 'customer_rejected_delivery_at', 'customer_rejection_reason'];
    foreach ($columns as $column) {
        if (Schema::hasColumn('borrows', $column)) {
            echo "✓ $column: CÓ\n";
        } else {
            echo "✗ $column: CHƯA CÓ\n";
        }
    }

    echo "\n✅ Hoàn tất!\n";
} catch (\Exception $e) {
    echo "\n✗ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}
