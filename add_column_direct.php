<?php
/**
 * Script trực tiếp để thêm cột anh_hoan_tra
 * Chạy: php add_column_direct.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n==========================================\n";
echo "  FIX: Thêm cột anh_hoan_tra\n";
echo "==========================================\n\n";

try {
    // Kiểm tra cột
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    
    if (!empty($columns)) {
        echo "✅ Cột 'anh_hoan_tra' đã tồn tại!\n";
        echo "Type: " . $columns[0]->Type . "\n";
        echo "Null: " . $columns[0]->Null . "\n\n";
    } else {
        echo "⚠️  Cột chưa tồn tại. Đang thêm...\n\n";
        
        // Kiểm tra cột tinh_trang_sach
        $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
        
        if (!empty($checkTinhTrang)) {
            echo "→ Thêm sau cột 'tinh_trang_sach'...\n";
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
        } else {
            echo "→ Thêm vào cuối bảng...\n";
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
        }
        
        // Xác minh
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            echo "✅ THÀNH CÔNG! Cột đã được thêm.\n";
            echo "Field: " . $columns[0]->Field . "\n";
            echo "Type: " . $columns[0]->Type . "\n";
            echo "Null: " . $columns[0]->Null . "\n\n";
            echo "🎉 Bạn có thể test lại ứng dụng ngay bây giờ!\n\n";
        } else {
            echo "❌ LỖI: Không thể thêm cột.\n";
            exit(1);
        }
    }
    
} catch (\Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    exit(1);
}
