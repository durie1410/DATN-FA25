# 🚨 FIX NHANH LỖI "invalid_client"

## ❌ **Lỗi hiện tại:**
```
400. That's an error.
invalid_client The OAuth client was not found.
```

## ⚡ **FIX NHANH TRONG 5 PHÚT:**

### **Bước 1: Tạo Google OAuth Client ID (3 phút)**

1. **Mở:** https://console.cloud.google.com/
2. **Tạo project:** Nhấn "Select a project" → "NEW PROJECT" → "Thư viện OAuth" → "CREATE"
3. **Enable API:** APIs & Services → Library → Tìm "Google+ API" → "ENABLE"
4. **Tạo OAuth Client:**
   - APIs & Services → Credentials
   - "+ CREATE CREDENTIALS" → "OAuth client ID"
   - Application type: **Web application**
   - Name: **Thư viện OAuth**
   - Authorized JavaScript origins: `http://localhost:8000`
   - Authorized redirect URIs: `http://localhost:8000/auth/google/callback`
   - **CREATE**

5. **Copy thông tin:**
   - Client ID: `123456789-abcdefghijklmnop.apps.googleusercontent.com`
   - Client Secret: `GOCSPX-abcdefghijklmnopqrstuvwxyz`

### **Bước 2: Cập nhật file .env (1 phút)**

**Cách 1: Sử dụng script tự động**
```bash
php update_google_client.php YOUR_CLIENT_ID YOUR_CLIENT_SECRET
```

**Cách 2: Sửa thủ công**
Mở file `.env` và thay thế:
```env
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
```

### **Bước 3: Clear cache và test (1 phút)**

```bash
php artisan config:clear
php artisan serve
```

Truy cập: http://localhost:8000/register

## 🎯 **Kết quả:**

✅ Không còn lỗi "invalid_client"  
✅ Redirect đến Google OAuth thành công  
✅ Đăng ký với Google hoạt động bình thường  

## 🚨 **Nếu vẫn còn lỗi:**

1. **Kiểm tra OAuth consent screen:**
   - APIs & Services → OAuth consent screen
   - Chọn "External" → Điền thông tin → "SAVE AND CONTINUE"

2. **Kiểm tra redirect URI:**
   - Phải chính xác: `http://localhost:8000/auth/google/callback`
   - Không có dấu `/` cuối

3. **Kiểm tra Client ID:**
   - Không có khoảng trắng thừa
   - Copy đầy đủ từ Google Console

## 📞 **Cần hỗ trợ?**

Nếu gặp khó khăn, hãy:
1. Chạy: `php update_google_client.php` để xem hướng dẫn chi tiết
2. Đọc file: `FIX_INVALID_CLIENT_ERROR.md`
3. Kiểm tra file: `GOOGLE_OAUTH_FIX_COMPLETE.md`

**Chúc bạn thành công! 🚀**





