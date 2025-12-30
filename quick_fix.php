<?php
// Quick fix script - Chạy trực tiếp từ browser hoặc CLI
// Truy cập: http://quanlythuviennn.test/quick_fix.php

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quick Fix - Thêm cột users table</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Quick Fix - Thêm cột vào bảng users</h1>
        
        <?php
        try {
            require __DIR__.'/vendor/autoload.php';
            $app = require_once __DIR__.'/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            
            use Illuminate\Support\Facades\DB;
            
            echo "<div class='info'><strong>Đang kiểm tra và thêm các cột...</strong></div>";
            
            $results = [];
            
            // Kiểm tra cột so_cccd để xác định vị trí thêm cột
            $soCccdColumn = DB::select("SHOW COLUMNS FROM `users` WHERE Field = 'so_cccd'");
            $afterColumn = !empty($soCccdColumn) ? 'so_cccd' : 'address';
            
            // Kiểm tra cột ngay_sinh
            echo "<h3>1. Kiểm tra cột ngay_sinh</h3>";
            $result = DB::select("SHOW COLUMNS FROM `users` WHERE Field = 'ngay_sinh'");
            
            if (empty($result)) {
                echo "<div class='info'>→ Cột ngay_sinh chưa tồn tại. Đang thêm sau cột `{$afterColumn}`...</div>";
                try {
                    DB::statement("ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `{$afterColumn}`");
                    echo "<div class='success'>✓ Đã thêm cột ngay_sinh thành công!</div>";
                    $results[] = "Đã thêm ngay_sinh";
                } catch (\Exception $e) {
                    echo "<div class='error'>✗ Lỗi: " . htmlspecialchars($e->getMessage()) . "</div>";
                    $results[] = "Lỗi khi thêm ngay_sinh: " . $e->getMessage();
                }
            } else {
                echo "<div class='success'>✓ Cột ngay_sinh đã tồn tại.</div>";
                $results[] = "ngay_sinh đã tồn tại";
            }
            
            // Kiểm tra cột gioi_tinh
            echo "<h3>2. Kiểm tra cột gioi_tinh</h3>";
            $result = DB::select("SHOW COLUMNS FROM `users` WHERE Field = 'gioi_tinh'");
            
            if (empty($result)) {
                echo "<div class='info'>→ Cột gioi_tinh chưa tồn tại. Đang thêm...</div>";
                DB::statement("ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`");
                echo "<div class='success'>✓ Đã thêm cột gioi_tinh thành công!</div>";
                $results[] = "Đã thêm gioi_tinh";
            } else {
                echo "<div class='success'>✓ Cột gioi_tinh đã tồn tại.</div>";
                $results[] = "gioi_tinh đã tồn tại";
            }
            
            // Kiểm tra lại
            echo "<h3>3. Kiểm tra lại các cột</h3>";
            $columns = DB::select("SHOW COLUMNS FROM `users` WHERE Field IN ('ngay_sinh', 'gioi_tinh')");
            
            if (count($columns) == 2) {
                echo "<div class='success'><strong>✓ Cả hai cột đã tồn tại!</strong></div>";
                echo "<pre>";
                foreach ($columns as $col) {
                    echo "Cột: {$col->Field}\n";
                    echo "  Type: {$col->Type}\n";
                    echo "  Null: {$col->Null}\n";
                    echo "  Default: " . ($col->Default ?? 'NULL') . "\n";
                    echo "\n";
                }
                echo "</pre>";
            } else {
                echo "<div class='error'>⚠ Có vấn đề! Chỉ tìm thấy " . count($columns) . " cột.</div>";
            }
            
            echo "<div class='success' style='margin-top: 20px;'>";
            echo "<h2>✅ HOÀN THÀNH!</h2>";
            echo "<p><strong>Bây giờ bạn có thể:</strong></p>";
            echo "<ol>";
            echo "<li>Mở lại trang: <a href='/account'>/account</a></li>";
            echo "<li>Điền đầy đủ thông tin và nhấn 'Cập nhật'</li>";
            echo "<li>Lỗi sẽ không còn xuất hiện nữa!</li>";
            echo "</ol>";
            echo "</div>";
            
        } catch (\Exception $e) {
            echo "<div class='error'>";
            echo "<h2>❌ LỖI</h2>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "<p><strong>Vui lòng kiểm tra:</strong></p>";
            echo "<ul>";
            echo "<li>Database connection trong file .env</li>";
            echo "<li>Quyền truy cập database</li>";
            echo "<li>Tên bảng 'users' có tồn tại không</li>";
            echo "</ul>";
            echo "</div>";
        }
        ?>
        
        <hr style="margin: 30px 0;">
        <p><a href="/account">← Quay lại trang tài khoản</a></p>
    </div>
</body>
</html>

