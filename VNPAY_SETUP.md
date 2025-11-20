# Hướng dẫn cấu hình VNPay Payment Gateway

## 1. Giới thiệu

Hệ thống đã tích hợp VNPay - cổng thanh toán phổ biến nhất tại Việt Nam, hỗ trợ:
- 💳 Thanh toán qua thẻ ATM nội địa
- 💳 Thanh toán qua thẻ Visa/MasterCard
- 🏦 Thanh toán qua Internet Banking
- 📱 Thanh toán qua QR Code

## 2. Cấu hình môi trường

### 2.1. Môi trường Sandbox (Testing)

Thêm các biến sau vào file `.env`:

```env
# VNPay Payment Gateway (Sandbox)
VNPAY_TMN_CODE=DEMO
VNPAY_HASH_SECRET=DEMO
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
```

**Lưu ý:** Đây là thông tin demo của VNPay, chỉ dùng để test. Không sử dụng trong môi trường production.

### 2.2. Môi trường Production

Để sử dụng trong môi trường production, bạn cần:

1. **Đăng ký tài khoản VNPay:**
   - Truy cập: https://vnpay.vn
   - Đăng ký tài khoản doanh nghiệp
   - Hoàn tất thủ tục xác minh

2. **Lấy thông tin kết nối:**
   - Sau khi đăng ký thành công, VNPay sẽ cung cấp:
     - `TMN_CODE`: Mã website/merchant
     - `HASH_SECRET`: Mã bảo mật để tạo chữ ký
   
3. **Cập nhật file `.env`:**

```env
# VNPay Payment Gateway (Production)
VNPAY_TMN_CODE=YOUR_TMN_CODE_HERE
VNPAY_HASH_SECRET=YOUR_HASH_SECRET_HERE
VNPAY_URL=https://pay.vnpay.vn/vpcpay.html
```

## 3. Cách sử dụng

### 3.1. Quy trình thanh toán

1. **Khách hàng đặt hàng:**
   - Vào trang checkout
   - Chọn phương thức thanh toán "VNPay"
   - Nhấn "Đặt hàng"

2. **Chuyển hướng đến VNPay:**
   - Hệ thống tự động tạo đơn hàng
   - Chuyển hướng khách hàng đến trang thanh toán VNPay

3. **Khách hàng thanh toán:**
   - Chọn ngân hàng/phương thức thanh toán
   - Nhập thông tin thẻ/tài khoản
   - Xác nhận thanh toán

4. **Xử lý kết quả:**
   - VNPay gửi kết quả về hệ thống
   - Hệ thống cập nhật trạng thái đơn hàng
   - Chuyển hướng khách hàng về trang chi tiết đơn hàng

### 3.2. Test thanh toán (Sandbox)

Với môi trường sandbox, bạn có thể test với thông tin sau:

**Thẻ ATM nội địa:**
- Số thẻ: `9704198526191432198`
- Tên chủ thẻ: `NGUYEN VAN A`
- Ngày phát hành: `07/15`
- Mật khẩu OTP: `123456`

**Thẻ quốc tế:**
- Số thẻ: `4111111111111111` (Visa)
- CVV: `123`
- Ngày hết hạn: `12/25`

## 4. Xử lý lỗi

### 4.1. Các mã lỗi thường gặp

| Mã lỗi | Ý nghĩa | Giải pháp |
|--------|---------|-----------|
| 00 | Giao dịch thành công | - |
| 07 | Trừ tiền thành công nhưng giao dịch bị nghi ngờ | Liên hệ VNPay |
| 09 | Thẻ chưa đăng ký Internet Banking | Yêu cầu khách hàng đăng ký |
| 10 | Xác thực sai quá 3 lần | Yêu cầu khách hàng thử lại |
| 11 | Hết hạn chờ thanh toán | Tạo đơn hàng mới |
| 12 | Thẻ bị khóa | Liên hệ ngân hàng |
| 13 | Sai mật khẩu OTP | Yêu cầu khách hàng nhập lại |
| 24 | Khách hàng hủy giao dịch | - |
| 51 | Tài khoản không đủ số dư | Yêu cầu khách hàng nạp tiền |
| 65 | Vượt quá hạn mức giao dịch | Liên hệ ngân hàng |
| 75 | Ngân hàng đang bảo trì | Thử lại sau |

### 4.2. Debug

Kiểm tra log tại `storage/logs/laravel.log` để xem chi tiết lỗi:

```bash
tail -f storage/logs/laravel.log
```

## 5. Bảo mật

### 5.1. Các biện pháp bảo mật đã áp dụng

- ✅ Xác thực chữ ký HMAC SHA-512
- ✅ Kiểm tra quyền sở hữu đơn hàng
- ✅ Kiểm tra trạng thái đơn hàng trước khi thanh toán
- ✅ Sử dụng HTTPS cho callback URL
- ✅ Log tất cả giao dịch

### 5.2. Khuyến nghị

- 🔒 **KHÔNG** commit file `.env` lên git
- 🔒 **KHÔNG** chia sẻ `HASH_SECRET` với bất kỳ ai
- 🔒 Sử dụng HTTPS cho production
- 🔒 Thường xuyên kiểm tra log giao dịch
- 🔒 Backup database định kỳ

## 6. Liên hệ hỗ trợ

- **VNPay Hotline:** 1900 55 55 77
- **Email:** support@vnpay.vn
- **Website:** https://vnpay.vn
- **Tài liệu API:** https://sandbox.vnpayment.vn/apis/docs/

## 7. Changelog

- **2025-11-20:** Tích hợp VNPay payment gateway
  - Hỗ trợ thanh toán ATM/Visa/MasterCard
  - Sandbox testing
  - Xử lý callback và cập nhật trạng thái đơn hàng

