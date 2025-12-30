<?php
/**
 * Script để fix cột anh_hoan_tra ngay lập tức
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Đang kiểm tra và fix cột anh_hoan_tra...\n\n";

try {
    // Kiểm tra cột
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    
    if (empty($columns)) {
        echo "⚠️  Cột chưa tồn tại. Đang thêm...\n";
        
        // Kiểm tra cột tinh_trang_sach để xác định vị trí
        $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
        
        if (!empty($checkTinhTrang)) {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            echo "✓ Đã thêm cột sau 'tinh_trang_sach'\n";
        } else {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            echo "✓ Đã thêm cột vào cuối bảng\n";
        }
        
        // Xác minh
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            echo "\n✅ THÀNH CÔNG! Cột đã được thêm.\n";
            echo "Field: " . $columns[0]->Field . "\n";
            echo "Type: " . $columns[0]->Type . "\n";
            echo "\n🎉 Bạn có thể quay lại trang web và thử lại!\n";
        } else {
            echo "\n❌ LỖI: Không thể thêm cột.\n";
            exit(1);
        }
    } else {
        echo "✅ Cột 'anh_hoan_tra' đã tồn tại!\n";
        echo "Type: " . $columns[0]->Type . "\n";
        echo "\n✓ Không cần fix.\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
