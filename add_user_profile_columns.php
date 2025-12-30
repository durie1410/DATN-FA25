<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "Đang kiểm tra và thêm các cột vào bảng users...\n";
    
    // Kiểm tra và thêm cột ngay_sinh
    if (!Schema::hasColumn('users', 'ngay_sinh')) {
        echo "Thêm cột ngay_sinh...\n";
        DB::statement('ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `so_cccd`');
        echo "✓ Đã thêm cột ngay_sinh\n";
    } else {
        echo "✓ Cột ngay_sinh đã tồn tại\n";
    }
    
    // Kiểm tra và thêm cột gioi_tinh
    if (!Schema::hasColumn('users', 'gioi_tinh')) {
        echo "Thêm cột gioi_tinh...\n";
        DB::statement("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`");
        echo "✓ Đã thêm cột gioi_tinh\n";
    } else {
        echo "✓ Cột gioi_tinh đã tồn tại\n";
    }
    
    echo "\nHoàn thành! Các cột đã được thêm vào bảng users.\n";
    
} catch (\Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

