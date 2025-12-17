@echo off
chcp 65001 >nul
echo ==========================================
echo    SỬA LỖI VNPAY SIGNATURE FAILED
echo ==========================================
echo.

cd /d D:\laragon\www\quanlythuviennn

echo [Bước 1/4] Cập nhật file .env với thông tin VNPay...
php check_and_fix_vnpay.php
echo.

echo [Bước 2/4] Xóa cache config...
php artisan config:clear
echo.

echo [Bước 3/4] Xóa cache...
php artisan cache:clear
echo.

echo [Bước 4/4] Kiểm tra config đã áp dụng...
php artisan tinker --execute="echo 'TMN_CODE: ' . config('services.vnpay.tmn_code') . PHP_EOL; echo 'HASH_SECRET length: ' . strlen(config('services.vnpay.hash_secret')) . ' chars' . PHP_EOL;"
echo.

echo ==========================================
echo    HOÀN THÀNH!
echo ==========================================
echo.
echo ✅ Đã cập nhật cấu hình VNPay
echo.
echo 📝 Tiếp theo:
echo    1. Mở trình duyệt
echo    2. Thử thanh toán lại
echo    3. Nếu vẫn lỗi, kiểm tra log tại: storage/logs/laravel.log
echo.
pause

