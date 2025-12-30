@echo off
echo ================================================
echo    FIX STORAGE SYMBOLIC LINK - LIBHUB
echo ================================================
echo.

echo [1/3] Xoa thu muc public\storage hien tai...
if exist "public\storage" (
    rmdir /s /q "public\storage"
    echo      + Da xoa thanh cong
) else (
    echo      ! Thu muc khong ton tai
)
echo.

echo [2/3] Tao symbolic link moi...
php artisan storage:link
echo.

echo [3/3] Kiem tra ket qua...
if exist "public\storage" (
    echo      + Symbolic link da duoc tao thanh cong!
    echo.
    echo ================================================
    echo    HOAN THANH! Anh da co the hien thi.
    echo ================================================
    echo.
    echo Luu y: 
    echo - Neu van khong hien, nhan Ctrl+Shift+R de xoa cache
    echo - Hoac vao trang quan ly sach va F5 de refresh
) else (
    echo      ! LOI: Khong the tao symbolic link
    echo.
    echo Vui long chay PowerShell voi quyen Administrator:
    echo    1. Nhan Windows + X
    echo    2. Chon "Windows PowerShell (Admin)"
    echo    3. Chay lenh: php artisan storage:link
)
echo.

pause



