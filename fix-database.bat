@echo off
echo ================================
echo FIX DATABASE - BORROW ITEMS
echo ================================
echo.

echo [1/5] Clear cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo Done!
echo.

echo [2/5] Run migration...
php artisan migrate --force
echo Done!
echo.

echo [3/5] Check database structure...
php artisan tinker --execute="dd(DB::select('SHOW COLUMNS FROM borrow_items WHERE Field = \'trang_thai\''));"
echo Done!
echo.

echo [4/5] Show sample borrow items...
php artisan tinker --execute="dd(DB::table('borrow_items')->select('id', 'trang_thai')->limit(5)->get());"
echo Done!
echo.

echo [5/5] Check log file...
echo Last 30 lines of log:
powershell -Command "Get-Content storage\logs\laravel.log -Tail 30"
echo.

echo ================================
echo HOAN TAT! Hay thu duyet lai
echo ================================
pause


