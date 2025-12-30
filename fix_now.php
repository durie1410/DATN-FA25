<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fix Column anh_hoan_tra</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Column anh_hoan_tra</h1>
        
        <?php
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        use Illuminate\Support\Facades\DB;
        
        try {
            echo '<div class="info">[1/3] Đang kiểm tra kết nối database...</div>';
            DB::connection()->getPdo();
            echo '<div class="success">✓ Kết nối database thành công!</div>';
            
            echo '<div class="info">[2/3] Đang kiểm tra cột anh_hoan_tra...</div>';
            $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
            
            if (empty($columns)) {
                echo '<div class="error">✗ Cột chưa tồn tại. Đang thêm cột...</div>';
                
                // Kiểm tra cột tinh_trang_sach
                $checkTinhTrang = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'tinh_trang_sach'");
                
                if (!empty($checkTinhTrang)) {
                    echo '<div class="info">→ Thêm sau cột tinh_trang_sach...</div>';
                    // FIX: COMMENT phải đứng trước AFTER trong MySQL
                    DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng' AFTER `tinh_trang_sach`");
                } else {
                    echo '<div class="info">→ Thêm vào cuối bảng...</div>';
                    DB::statement("ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'");
                }
                
                // Kiểm tra lại
                $columns = DB::select("SHOW COLUMNS FROM `borrows` WHERE Field = 'anh_hoan_tra'");
                if (!empty($columns)) {
                    echo '<div class="success">[3/3] ✓ ĐÃ THÊM CỘT THÀNH CÔNG!</div>';
                    echo '<div class="success"><strong>✅ Hoàn tất! Bạn có thể test lại ngay bây giờ.</strong></div>';
                    
                    $col = $columns[0];
                    echo '<div class="info"><strong>Thông tin cột:</strong><pre>';
                    echo "Field: {$col->Field}\n";
                    echo "Type: {$col->Type}\n";
                    echo "Null: {$col->Null}\n";
                    echo "Default: " . ($col->Default ?? 'NULL') . "\n";
                    echo '</pre></div>';
                } else {
                    echo '<div class="error">✗ Không thể thêm cột! Vui lòng kiểm tra quyền database.</div>';
                }
            } else {
                echo '<div class="success">[2/3] ✓ Cột đã tồn tại.</div>';
                echo '<div class="info">[3/3] Không cần thêm.</div>';
                
                $col = $columns[0];
                echo '<div class="info"><strong>Thông tin cột hiện tại:</strong><pre>';
                echo "Field: {$col->Field}\n";
                echo "Type: {$col->Type}\n";
                echo "Null: {$col->Null}\n";
                echo '</pre></div>';
            }
            
        } catch (\Exception $e) {
            echo '<div class="error"><strong>❌ Lỗi:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<div class="error">File: ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</div>';
            echo '<div class="info"><strong>Hướng dẫn:</strong><br>';
            echo '1. Mở phpMyAdmin<br>';
            echo '2. Chọn database của bạn<br>';
            echo '3. Vào tab SQL<br>';
            echo '4. Chạy lệnh: <pre>ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT \'Ảnh minh chứng hoàn trả sách từ khách hàng\';</pre>';
            echo '</div>';
        }
        ?>
        
        <hr>
        <p><a href="/account/borrowed-books">← Quay lại trang Sách đang mượn</a></p>
    </div>
</body>
</html>
