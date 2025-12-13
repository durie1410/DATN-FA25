================================================================================
  SỬA LỖI "XÁC THỰC CHỮ KÝ THẤT BẠI" - VNPAY
================================================================================

LỖI: "Thanh toán thất bại - Xác thực chữ ký thất bại"

NGUYÊN NHÂN: Hash Secret trong .env không khớp với VNPay

================================================================================
  CÁCH SỬA NHANH (3 BƯỚC)
================================================================================

1. Double-click file: fix_vnpay_now.bat
   
2. Kiểm tra: http://quanlythuviennn.test/vnpay-debug
   
3. Thử thanh toán lại

XONG! ✅

================================================================================
  CÁCH SỬA THỦ CÔNG
================================================================================

1. Mở file .env
2. Thêm/sửa các dòng sau:

   VNPAY_TMN_CODE=E6I8Z7HX
   VNPAY_HASH_SECRET=LYS57TC0V5NARXASTFT3Y0D50NHNPWEZ
   VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html

3. Chạy lệnh:
   php artisan config:clear
   php artisan cache:clear

4. Thử lại

================================================================================
  KIỂM TRA
================================================================================

• Trang debug đẹp: http://quanlythuviennn.test/vnpay-debug
• API JSON: http://quanlythuviennn.test/test-vnpay-config  
• Log file: storage/logs/laravel.log

================================================================================
  GHI CHÚ
================================================================================

- Thông tin trên là cho SANDBOX (test)
- Nếu dùng tài khoản VNPay thật, lấy thông tin từ trang quản trị VNPay
- Luôn chạy "php artisan config:clear" sau khi sửa .env

Xem hướng dẫn chi tiết: HUONG_DAN_SUA_LOI_VNPAY.md

================================================================================

