@echo off
echo ========================================
echo   Hệ Thống Quản Lý Thư Viện
echo   Setup Script
echo ========================================
echo.

REM Check if .env exists
if not exist .env (
    echo [1/7] Creating .env file...
    copy .env.example .env
    echo .env file created!
) else (
    echo [1/7] .env file already exists, skipping...
)
echo.

REM Install Composer dependencies
echo [2/7] Installing Composer dependencies...
call composer install
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo Composer dependencies installed!
echo.

REM Generate application key
echo [3/7] Generating application key...
php artisan key:generate
echo Application key generated!
echo.

REM Create storage link
echo [4/7] Creating storage link...
php artisan storage:link
echo Storage link created!
echo.

REM Run migrations
echo [5/7] Running database migrations...
echo WARNING: This will run all migrations. Make sure your database is configured in .env
set /p confirm="Do you want to run migrations? (y/n): "
if /i "%confirm%"=="y" (
    php artisan migrate
    echo Migrations completed!
) else (
    echo Migrations skipped.
)
echo.

REM Run seeders
echo [6/7] Running database seeders...
set /p seed="Do you want to run seeders? (y/n): "
if /i "%seed%"=="y" (
    php artisan db:seed
    echo Seeders completed!
) else (
    echo Seeders skipped.
)
echo.

REM Install NPM dependencies
echo [7/7] Installing NPM dependencies...
call npm install
if errorlevel 1 (
    echo WARNING: NPM install failed! You may need to install Node.js.
) else (
    echo NPM dependencies installed!
    echo.
    echo Building assets...
    call npm run dev
    echo Assets built!
)
echo.

REM Clear cache
echo Clearing application cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
echo Cache cleared!
echo.

echo ========================================
echo   Setup Completed!
echo ========================================
echo.
echo Next steps:
echo 1. Configure your database in .env file
echo 2. Run: php artisan migrate (if not done)
echo 3. Run: php artisan serve
echo 4. Visit: http://localhost:8000
echo.
pause

