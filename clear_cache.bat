@echo off
chcp 65001 >nul
echo ==========================================
echo    XÓA CACHE LARAVEL
echo ==========================================
echo.

cd /d D:\laragon\www\quanlythuviennn

echo [1/3] Xóa config cache...
php artisan config:clear
if %errorlevel% neq 0 (
    echo ❌ Lỗi khi xóa config cache
    pause
    exit /b 1
)
echo ✅ Đã xóa config cache
echo.

echo [2/3] Xóa application cache...
php artisan cache:clear
if %errorlevel% neq 0 (
    echo ❌ Lỗi khi xóa application cache
    pause
    exit /b 1
)
echo ✅ Đã xóa application cache
echo.

echo [3/3] Xóa view cache...
php artisan view:clear
if %errorlevel% neq 0 (
    echo ⚠️ Lỗi khi xóa view cache (có thể bỏ qua)
) else (
    echo ✅ Đã xóa view cache
)
echo.

echo ==========================================
echo    HOÀN THÀNH!
echo ==========================================
echo.
echo ✅ Đã xóa tất cả cache Laravel
echo 💡 Config mới đã được áp dụng
echo.
pause

