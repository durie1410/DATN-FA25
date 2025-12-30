<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "========================================\n";
echo "KIỂM TRA VÀ THÊM CỘT VÀO BẢNG USERS\n";
echo "========================================\n\n";

try {
    // Kiểm tra kết nối database
    DB::connection()->getPdo();
    echo "✓ Kết nối database thành công\n\n";
    
    // Kiểm tra các cột hiện có trong bảng users
    echo "Đang kiểm tra cấu trúc bảng users...\n";
    $columns = DB::select("SHOW COLUMNS FROM `users`");
    
    $existingColumns = array_map(function($col) {
        return $col->Field;
    }, $columns);
    
    echo "Các cột hiện có: " . implode(', ', $existingColumns) . "\n\n";
    
    // Kiểm tra cột ngay_sinh
    $hasNgaySinh = in_array('ngay_sinh', $existingColumns);
    echo "Cột 'ngay_sinh': " . ($hasNgaySinh ? "✓ Đã tồn tại" : "✗ Chưa có") . "\n";
    
    // Kiểm tra cột gioi_tinh
    $hasGioiTinh = in_array('gioi_tinh', $existingColumns);
    echo "Cột 'gioi_tinh': " . ($hasGioiTinh ? "✓ Đã tồn tại" : "✗ Chưa có") . "\n\n";
    
    // Thêm cột ngay_sinh nếu chưa có
    if (!$hasNgaySinh) {
        echo "Đang thêm cột 'ngay_sinh'...\n";
        $afterColumn = in_array('so_cccd', $existingColumns) ? 'so_cccd' : 'address';
        DB::statement("ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `{$afterColumn}`");
        echo "✓ Đã thêm cột 'ngay_sinh' thành công\n\n";
    }
    
    // Thêm cột gioi_tinh nếu chưa có
    if (!$hasGioiTinh) {
        echo "Đang thêm cột 'gioi_tinh'...\n";
        // Kiểm tra lại xem ngay_sinh đã có chưa (có thể vừa thêm)
        $columnsAfter = DB::select("SHOW COLUMNS FROM `users`");
        $columnsAfterNames = array_map(function($col) {
            return $col->Field;
        }, $columnsAfter);
        
        $afterColumn = in_array('ngay_sinh', $columnsAfterNames) ? 'ngay_sinh' : 'so_cccd';
        DB::statement("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `{$afterColumn}`");
        echo "✓ Đã thêm cột 'gioi_tinh' thành công\n\n";
    }
    
    // Kiểm tra lại sau khi thêm
    echo "Kiểm tra lại cấu trúc bảng...\n";
    $finalColumns = DB::select("SHOW COLUMNS FROM `users`");
    $finalColumnNames = array_map(function($col) {
        return $col->Field;
    }, $finalColumns);
    
    $hasNgaySinhFinal = in_array('ngay_sinh', $finalColumnNames);
    $hasGioiTinhFinal = in_array('gioi_tinh', $finalColumnNames);
    
    echo "\n========================================\n";
    echo "KẾT QUẢ CUỐI CÙNG:\n";
    echo "========================================\n";
    echo "Cột 'ngay_sinh': " . ($hasNgaySinhFinal ? "✓ Có" : "✗ Không có") . "\n";
    echo "Cột 'gioi_tinh': " . ($hasGioiTinhFinal ? "✓ Có" : "✗ Không có") . "\n";
    
    if ($hasNgaySinhFinal && $hasGioiTinhFinal) {
        echo "\n✅ HOÀN TẤT! Các cột đã được thêm thành công.\n";
        echo "Bạn có thể quay lại trang cập nhật thông tin tài khoản.\n";
    } else {
        echo "\n⚠️ Có vấn đề khi thêm cột. Vui lòng kiểm tra lại.\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}


