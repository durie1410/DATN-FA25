@echo off
chcp 65001 >nul
echo ==========================================
echo   TAO BANG WALLET - FIX LOI
echo ==========================================
echo.

cd /d "%~dp0"

echo Dang chay script tao bang...
echo.

php fix_create_wallet_tables.php

echo.
echo ==========================================
echo   HOAN TAT!
echo ==========================================
echo.
echo Vui long kiem tra ket qua o phia tren.
echo.
echo Neu van gap loi, vui long:
echo   1. Mo phpMyAdmin
echo   2. Chon database quanlythuvien
echo   3. Vao tab SQL
echo   4. Mo file create_wallet_tables.sql va chay
echo.
pause



