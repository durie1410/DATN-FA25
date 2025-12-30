@echo off
chcp 65001 >nul
echo ========================================
echo   FIX COLUMN: anh_hoan_tra
echo ========================================
echo.

php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); $cols = Illuminate\Support\Facades\DB::select(\"SHOW COLUMNS FROM borrows WHERE Field = 'anh_hoan_tra'\"); if(empty($cols)) { $check = Illuminate\Support\Facades\DB::select(\"SHOW COLUMNS FROM borrows WHERE Field = 'tinh_trang_sach'\"); if(!empty($check)) { Illuminate\Support\Facades\DB::statement(\"ALTER TABLE borrows ADD COLUMN anh_hoan_tra VARCHAR(255) NULL AFTER tinh_trang_sach COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'\"); echo '✅ Đã thêm cột sau tinh_trang_sach'; } else { Illuminate\Support\Facades\DB::statement(\"ALTER TABLE borrows ADD COLUMN anh_hoan_tra VARCHAR(255) NULL COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng'\"); echo '✅ Đã thêm cột vào cuối bảng'; } $verify = Illuminate\Support\Facades\DB::select(\"SHOW COLUMNS FROM borrows WHERE Field = 'anh_hoan_tra'\"); if(!empty($verify)) { echo '✅ XÁC MINH: Cột đã được thêm thành công!'; echo '🎉 Bạn có thể quay lại trang web và thử lại!'; } else { echo '❌ LỖI: Không thể thêm cột'; } } else { echo 'ℹ️  Cột anh_hoan_tra đã tồn tại!'; }"

echo.
echo ========================================
pause
