<?php

/**
 * Script để thêm cột anh_hoan_tra vào bảng borrows
 * Chạy: php fix_anh_hoan_tra_column.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n";
echo "==========================================\n";
echo "  THÊM CỘT anh_hoan_tra VÀO BẢNG borrows\n";
echo "==========================================\n\n";

try {
    // Kiểm tra kết nối database
    DB::connection()->getPdo();
    echo "✓ Đã kết nối database thành công\n\n";
    
    // Kiểm tra bảng borrows
    $tableExists = DB::select("SHOW TABLES LIKE 'borrows'");
    if (empty($tableExists)) {
        echo "✗ LỖI: Bảng 'borrows' không tồn tại!\n";
        exit(1);
    }
    echo "✓ Bảng 'borrows' đã tồn tại\n\n";
    
    // Kiểm tra cột anh_hoan_tra
    echo "Đang kiểm tra cột 'anh_hoan_tra'...\n";
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    
    if (empty($columns)) {
        echo "→ Cột chưa tồn tại, đang thêm...\n";
        
        // Kiểm tra cột tinh_trang_sach để xác định vị trí thêm cột
        $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
        
        if (!empty($checkTinhTrang)) {
            echo "→ Thêm sau cột 'tinh_trang_sach'...\n";
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
        } else {
            echo "→ Thêm vào cuối bảng...\n";
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
        }
        
        // Kiểm tra lại
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            echo "✓ ĐÃ THÊM CỘT 'anh_hoan_tra' THÀNH CÔNG!\n\n";
            
            $col = $columns[0];
            echo "Thông tin cột:\n";
            echo "  - Field: {$col->Field}\n";
            echo "  - Type: {$col->Type}\n";
            echo "  - Null: {$col->Null}\n";
            echo "  - Default: " . ($col->Default ?? 'NULL') . "\n";
            echo "\n";
        } else {
            echo "✗ LỖI: Không thể thêm cột! Vui lòng kiểm tra quyền database.\n";
            exit(1);
        }
    } else {
        echo "✓ Cột 'anh_hoan_tra' đã tồn tại.\n\n";
        
        $col = $columns[0];
        echo "Thông tin cột hiện tại:\n";
        echo "  - Field: {$col->Field}\n";
        echo "  - Type: {$col->Type}\n";
        echo "  - Null: {$col->Null}\n";
        echo "  - Default: " . ($col->Default ?? 'NULL') . "\n";
        echo "\n";
    }
    
    // Kiểm tra bằng Schema facade
    if (Schema::hasColumn('borrows', 'anh_hoan_tra')) {
        echo "✅ Xác nhận: Schema::hasColumn() cũng cho thấy cột đã tồn tại!\n";
        echo "\n🎉 HOÀN TẤT! Bạn có thể test lại ứng dụng ngay bây giờ.\n\n";
    } else {
        echo "⚠️  Cảnh báo: Schema::hasColumn() chưa nhận diện cột. Có thể cần clear cache.\n";
    }
    
} catch (\Exception $e) {
    echo "\n✗ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\n";
    echo "Hướng dẫn thủ công:\n";
    echo "1. Mở phpMyAdmin\n";
    echo "2. Chọn database của bạn\n";
    echo "3. Vào tab SQL\n";
    echo "4. Chạy lệnh:\n";
    echo "   ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng';\n";
    echo "\n";
    exit(1);
}
