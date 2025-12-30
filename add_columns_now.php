<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Kiểm tra và thêm cột vào bảng users ===\n\n";

try {
    // Kiểm tra cột ngay_sinh
    $columns = DB::select("SHOW COLUMNS FROM `users` LIKE 'ngay_sinh'");
    if (empty($columns)) {
        echo "Đang thêm cột ngay_sinh...\n";
        DB::statement("ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `so_cccd`");
        echo "✓ Đã thêm cột ngay_sinh thành công!\n\n";
    } else {
        echo "✓ Cột ngay_sinh đã tồn tại\n\n";
    }
    
    // Kiểm tra cột gioi_tinh
    $columns = DB::select("SHOW COLUMNS FROM `users` LIKE 'gioi_tinh'");
    if (empty($columns)) {
        echo "Đang thêm cột gioi_tinh...\n";
        DB::statement("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`");
        echo "✓ Đã thêm cột gioi_tinh thành công!\n\n";
    } else {
        echo "✓ Cột gioi_tinh đã tồn tại\n\n";
    }
    
    echo "=== Hoàn thành! ===\n";
    echo "Bây giờ bạn có thể cập nhật thông tin tài khoản mà không gặp lỗi.\n";
    
} catch (\Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

