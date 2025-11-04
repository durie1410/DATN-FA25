# 🚨 FIX LỖI "invalid_client" - Client ID chưa được cấu hình

## ❌ **Lỗi hiện tại:**
```
400. That's an error.
invalid_client The OAuth client was not found.
```

## 🔍 **Nguyên nhân:**
File `.env` vẫn đang sử dụng placeholder:
```
GOOGLE_CLIENT_ID=your_google_client_id_here
```

## ✅ **Cách fix:**

### **Bước 1: Tạo Google OAuth Client ID**

1. **Truy cập:** https://console.cloud.google.com/
2. **Đăng nhập** bằng tài khoản Google của bạn
3. **Chọn project** hoặc **tạo project mới:**
   - Nhấn "Select a project" ở góc trên
   - Nhấn "NEW PROJECT"
   - Tên project: "Thư viện OAuth"
   - Nhấn "CREATE"

4. **Kích hoạt Google+ API:**
   - Vào "APIs & Services" → "Library"
   - Tìm "Google+ API" hoặc "Google Identity"
   - Nhấn "ENABLE"

5. **Tạo OAuth Client ID:**
   - Vào "APIs & Services" → "Credentials"
   - Nhấn "+ CREATE CREDENTIALS" → "OAuth client ID"
   - Nếu chưa có OAuth consent screen, chọn "CONFIGURE CONSENT SCREEN"
   - Chọn "External" → "CREATE"
   - Điền thông tin:
     - **App name**: Thư viện Online
     - **User support email**: Email của bạn
     - **Developer contact information**: Email của bạn
   - Nhấn "SAVE AND CONTINUE"
   - Nhấn "SAVE AND CONTINUE" (không cần thêm scopes)
   - Nhấn "SAVE AND CONTINUE" (không cần thêm test users)
   - Nhấn "BACK TO DASHBOARD"

6. **Tạo OAuth Client ID:**
   - Vào "APIs & Services" → "Credentials"
   - Nhấn "+ CREATE CREDENTIALS" → "OAuth client ID"
   - **Application type**: Web application
   - **Name**: Thư viện OAuth
   - **Authorized JavaScript origins**: `http://localhost:8000`
   - **Authorized redirect URIs**: `http://localhost:8000/auth/google/callback`
   - Nhấn "CREATE"

7. **Copy thông tin:**
   - **Client ID**: `123456789-abcdefghijklmnop.apps.googleusercontent.com`
   - **Client Secret**: `GOCSPX-abcdefghijklmnopqrstuvwxyz`

### **Bước 2: Cập nhật file .env**

Thay thế các dòng trong file `.env`:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### **Bước 3: Clear cache và test**

```bash
php artisan config:clear
php artisan serve
```

Sau đó truy cập: http://localhost:8000/register

## 🎯 **Kết quả mong đợi:**

Sau khi hoàn thành:
1. Truy cập http://localhost:8000/register
2. Nhấn "Đăng ký với Google"
3. Redirect đến Google OAuth (không còn lỗi 400)
4. Xác thực thành công
5. Quay lại ứng dụng và tạo tài khoản

## 🚨 **Lưu ý quan trọng:**

1. **Client ID phải chính xác** - không có khoảng trắng thừa
2. **Redirect URI phải khớp** với Google Cloud Console
3. **OAuth consent screen** phải được cấu hình
4. **Cache phải được clear** sau khi cập nhật .env

## 📋 **Checklist:**

- [ ] Đã tạo project trong Google Cloud Console
- [ ] Đã kích hoạt Google+ API
- [ ] Đã cấu hình OAuth consent screen
- [ ] Đã tạo OAuth Client ID
- [ ] Đã copy Client ID và Secret
- [ ] Đã cập nhật file .env
- [ ] Đã clear cache
- [ ] Đã test đăng ký với Google

**Sau khi làm theo hướng dẫn này, lỗi "invalid_client" sẽ được fix! 🚀**





