<?php
/**
 * CHẠY FILE NÀY ĐỂ FIX NGAY: php FIX_NGAY_BAY_GIO.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════╗\n";
echo "║   FIX CỘT: anh_hoan_tra                ║\n";
echo "╚════════════════════════════════════════╝\n\n";

try {
    // Kiểm tra cột
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    
    if (empty($columns)) {
        echo "⚠️  Cột chưa tồn tại → Đang thêm...\n\n";
        
        // Kiểm tra vị trí để thêm
        $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
        
        if (!empty($checkTinhTrang)) {
            // FIX: COMMENT phải đứng trước AFTER trong MySQL
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng' AFTER `tinh_trang_sach`");
            echo "✓ Đã thêm sau cột 'tinh_trang_sach'\n";
        } else {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            echo "✓ Đã thêm vào cuối bảng\n";
        }
        
        // Xác minh
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            echo "\n";
            echo "╔════════════════════════════════════════╗\n";
            echo "║   ✅ THÀNH CÔNG!                       ║\n";
            echo "╚════════════════════════════════════════╝\n";
            echo "\n";
            echo "Cột đã được thêm:\n";
            echo "  - Field: {$columns[0]->Field}\n";
            echo "  - Type: {$columns[0]->Type}\n";
            echo "  - Null: {$columns[0]->Null}\n";
            echo "\n";
            echo "🎉 Bạn có thể quay lại trang web và thử lại!\n";
            echo "   URL: http://quanlythuviennn.test/account/borrowed-books\n";
        } else {
            echo "\n❌ LỖI: Không thể thêm cột\n";
            exit(1);
        }
    } else {
        echo "✅ Cột 'anh_hoan_tra' đã tồn tại!\n";
        echo "Type: {$columns[0]->Type}\n";
        echo "\n✓ Không cần fix.\n";
    }
    
} catch (\Exception $e) {
    echo "\n";
    echo "╔════════════════════════════════════════╗\n";
    echo "║   ❌ LỖI                               ║\n";
    echo "╚════════════════════════════════════════╝\n";
    echo "\n";
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\n";
    echo "Hướng dẫn thủ công:\n";
    echo "1. Mở phpMyAdmin\n";
    echo "2. Chọn database\n";
    echo "3. Chạy SQL:\n";
    echo "   ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng';\n";
    exit(1);
}

echo "\n";
