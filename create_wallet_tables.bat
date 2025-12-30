@echo off
chcp 65001 >nul
echo ==========================================
echo   TAO BANG WALLET CHO HE THONG VI
echo ==========================================
echo.

cd /d "%~dp0"

echo [1/3] Dang kiem tra ket noi database...
php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); try { Illuminate\Support\Facades\DB::connection()->getPdo(); echo '✓ Ket noi database thanh cong' . PHP_EOL; } catch (Exception \$e) { echo '✗ Loi ket noi: ' . \$e->getMessage() . PHP_EOL; exit(1); }"
if errorlevel 1 (
    echo.
    echo ✗ KHONG THE KET NOI DATABASE!
    echo Vui long kiem tra file .env
    echo.
    pause
    exit /b 1
)

echo.
echo [2/3] Dang chay migrations...
echo.

php artisan migrate --path=database/migrations/2025_12_07_000000_create_wallets_table.php --force
if errorlevel 1 (
    echo.
    echo ✗ LOI khi tao bang wallets!
    echo.
    pause
    exit /b 1
)

php artisan migrate --path=database/migrations/2025_12_07_000001_create_wallet_transactions_table.php --force
if errorlevel 1 (
    echo.
    echo ✗ LOI khi tao bang wallet_transactions!
    echo.
    pause
    exit /b 1
)

echo.
echo [3/3] Dang kiem tra ket qua...
php run_wallet_migration.php

echo.
echo ==========================================
echo   HOAN TAT!
echo ==========================================
echo.
echo Ban co the truy cap trang vi tai:
echo   http://quanlythuviennn.test/account/wallet
echo.
pause



