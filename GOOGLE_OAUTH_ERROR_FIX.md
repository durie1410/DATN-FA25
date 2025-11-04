# 🔧 Khắc phục lỗi Google OAuth: "Missing required parameter: redirect_uri"

## ❌ **Lỗi hiện tại:**
```
Access blocked: Authorization error
Missing required parameter: redirect_uri
Error 400: invalid_request
```

## ✅ **Các bước khắc phục:**

### **Bước 1: Kiểm tra file .env**

Đảm bảo file `.env` có các dòng sau:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**⚠️ Lưu ý quan trọng:**
- Thay `your_google_client_id_here` bằng Client ID thực từ Google Cloud Console
- Thay `your_google_client_secret_here` bằng Client Secret thực từ Google Cloud Console
- URL redirect phải **chính xác** và **khớp hoàn toàn** với cấu hình trong Google Cloud Console

### **Bước 2: Cấu hình Google Cloud Console**

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Chọn project của bạn
3. Vào **APIs & Services** → **Credentials**
4. Chọn OAuth 2.0 Client ID của bạn
5. Trong phần **Authorized redirect URIs**, thêm:
   ```
   http://localhost:8000/auth/google/callback
   ```
6. **Lưu** cấu hình

### **Bước 3: Clear cache và restart**

```bash
# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Restart server (nếu đang chạy)
# Ctrl+C để dừng server, sau đó:
php artisan serve
```

### **Bước 4: Kiểm tra URL hiện tại**

Đảm bảo bạn đang truy cập đúng URL:
- **Development:** `http://localhost:8000`
- **Production:** `https://yourdomain.com`

### **Bước 5: Test lại**

1. Truy cập: `http://localhost:8000/register`
2. Nhấn nút "Đăng ký với Google"
3. Kiểm tra URL redirect trong browser

## 🔍 **Debug thêm:**

### **Kiểm tra cấu hình hiện tại:**
```bash
php artisan tinker
```
Trong tinker, chạy:
```php
config('services.google')
```

### **Kiểm tra URL callback:**
```bash
php artisan route:list | findstr google
```

## 🚨 **Các lỗi thường gặp:**

### **1. URL không khớp:**
- **Sai:** `http://localhost:8000/auth/google/callback/`
- **Đúng:** `http://localhost:8000/auth/google/callback`

### **2. Protocol không khớp:**
- **Sai:** `https://localhost:8000/auth/google/callback`
- **Đúng:** `http://localhost:8000/auth/google/callback`

### **3. Port không khớp:**
- **Sai:** `http://localhost:3000/auth/google/callback`
- **Đúng:** `http://localhost:8000/auth/google/callback`

### **4. Chưa cấu hình biến môi trường:**
- Kiểm tra file `.env` có tồn tại không
- Kiểm tra các biến `GOOGLE_*` có được set không

## 📝 **Template file .env:**

```env
APP_NAME="Thư Viện"
APP_ENV=local
APP_KEY=base64:your_app_key_here
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quanlythuvien
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Mail (để gửi email thông báo)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## ✅ **Sau khi sửa xong:**

1. Restart server: `php artisan serve`
2. Test đăng ký: `http://localhost:8000/register`
3. Test đăng nhập: `http://localhost:8000/login`

Nếu vẫn còn lỗi, hãy kiểm tra lại:
- Google Cloud Console có đúng redirect URI không
- File .env có đúng format không
- Server có restart không





