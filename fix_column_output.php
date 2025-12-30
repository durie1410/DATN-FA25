<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$output = [];
$output[] = "=== FIX COLUMN anh_hoan_tra ===";
$output[] = "";

try {
    // Check if column exists
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    
    if (empty($columns)) {
        $output[] = "Column does NOT exist. Adding...";
        
        $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
        
        if (!empty($checkTinhTrang)) {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            $output[] = "Column added AFTER tinh_trang_sach";
        } else {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            $output[] = "Column added at END of table";
        }
        
        // Verify
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            $output[] = "SUCCESS: Column now exists!";
            $output[] = "Field: " . $columns[0]->Field;
            $output[] = "Type: " . $columns[0]->Type;
        } else {
            $output[] = "ERROR: Failed to add column";
        }
    } else {
        $output[] = "Column already EXISTS";
        $output[] = "Field: " . $columns[0]->Field;
        $output[] = "Type: " . $columns[0]->Type;
    }
    
} catch (\Exception $e) {
    $output[] = "ERROR: " . $e->getMessage();
    $output[] = "File: " . $e->getFile() . ":" . $e->getLine();
}

$output[] = "";
$output[] = "=== END ===";

$result = implode("\n", $output);
file_put_contents('fix_column_result.txt', $result);
echo $result;
