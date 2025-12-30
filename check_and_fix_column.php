<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");

if (empty($columns)) {
    echo "Column does not exist. Adding...\n";
    
    $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
    
    if (!empty($checkTinhTrang)) {
        DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
        echo "Column added successfully after tinh_trang_sach\n";
    } else {
        DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
        echo "Column added successfully at end of table\n";
    }
    
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    if (!empty($columns)) {
        echo "SUCCESS: Column anh_hoan_tra now exists\n";
    } else {
        echo "ERROR: Failed to add column\n";
    }
} else {
    echo "Column already exists\n";
    print_r($columns[0]);
}
