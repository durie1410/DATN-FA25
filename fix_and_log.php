<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$log = [];
$log[] = "=== FIX COLUMN anh_hoan_tra ===";
$log[] = "Time: " . date('Y-m-d H:i:s');
$log[] = "";

try {
    $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
    
    if (empty($columns)) {
        $log[] = "❌ Column does NOT exist";
        $log[] = "→ Adding column...";
        
        $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
        
        if (!empty($checkTinhTrang)) {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            $log[] = "✓ Added column AFTER tinh_trang_sach";
        } else {
            DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
            $log[] = "✓ Added column at END of table";
        }
        
        $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
        if (!empty($columns)) {
            $log[] = "✅ SUCCESS! Column added successfully";
            $log[] = "Field: " . $columns[0]->Field;
            $log[] = "Type: " . $columns[0]->Type;
            $log[] = "";
            $log[] = "🎉 Fix completed! You can now refresh the website.";
            $success = true;
        } else {
            $log[] = "❌ FAILED: Could not add column";
            $success = false;
        }
    } else {
        $log[] = "✅ Column already EXISTS";
        $log[] = "Field: " . $columns[0]->Field;
        $log[] = "Type: " . $columns[0]->Type;
        $success = true;
    }
    
} catch (\Exception $e) {
    $log[] = "❌ ERROR: " . $e->getMessage();
    $log[] = "File: " . $e->getFile() . ":" . $e->getLine();
    $success = false;
}

$log[] = "";
$log[] = "=== END ===";

$logContent = implode("\n", $log);
file_put_contents('fix_column_log.txt', $logContent);

// Also output to console
echo $logContent . "\n";

exit($success ? 0 : 1);
