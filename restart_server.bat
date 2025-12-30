@echo off
chcp 65001 >nul
echo ==========================================
echo    RESTART WEB SERVER & CLEAR CACHE
echo ==========================================
echo.

cd /d D:\laragon\www\quanlythuviennn

echo [1/4] Clear all cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo.

echo [2/4] Rebuild config cache...
php artisan config:cache
echo.

echo [3/4] Check VNPay config...
php artisan tinker --execute="echo 'TMN: ' . config('services.vnpay.tmn_code') . PHP_EOL; echo 'Hash length: ' . strlen(config('services.vnpay.hash_secret')) . PHP_EOL;"
echo.

echo [4/4] DONE!
echo ==========================================
echo.
echo BAY GIO:
echo 1. Restart Laragon/Web Server
echo 2. Hoac: Ctrl+C de stop server, sau do chay lai
echo 3. Thu thanh toan lai
echo.
pause

