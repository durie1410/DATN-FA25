# 🔧 HƯỚNG DẪN CẤU HÌNH FILE .ENV CHO GOOGLE OAUTH

## ❌ **Vấn đề hiện tại:**
File `.env` của bạn chưa có cấu hình Google OAuth, dẫn đến lỗi "Missing required parameter: redirect_uri"

## ✅ **Giải pháp:**

### **Bước 1: Thêm cấu hình Google OAuth vào file .env**

Mở file `.env` và thêm các dòng sau vào cuối file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### **Bước 2: Lấy thông tin từ Google Cloud Console**

1. **Truy cập:** [Google Cloud Console](https://console.cloud.google.com/)
2. **Chọn project** của bạn
3. **Vào:** APIs & Services → Credentials
4. **Tạo OAuth 2.0 Client ID** (nếu chưa có):
   - Application type: Web application
   - Name: Thư viện OAuth
   - Authorized JavaScript origins: `http://localhost:8000`
   - Authorized redirect URIs: `http://localhost:8000/auth/google/callback`
5. **Copy Client ID và Client Secret**

### **Bước 3: Cập nhật file .env**

Thay thế các giá trị trong file `.env`:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### **Bước 4: Cấu hình Mail (tùy chọn)**

Để gửi email thông báo, thêm cấu hình mail:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Lưu ý:** Nếu dùng Gmail, cần tạo App Password:
1. Google Account → Security → 2-Step Verification
2. App passwords → Generate password
3. Sử dụng password này thay vì mật khẩu thường

### **Bước 5: Clear cache và restart**

```bash
# Clear cache
php artisan config:clear
php artisan route:clear

# Restart server
php artisan serve
```

### **Bước 6: Test**

1. Truy cập: `http://localhost:8000/register`
2. Nhấn nút "Đăng ký với Google"
3. Kiểm tra có redirect đến Google không

## 🚨 **Các lỗi thường gặp:**

### **1. "Invalid client"**
- Kiểm tra GOOGLE_CLIENT_ID có đúng không
- Kiểm tra GOOGLE_CLIENT_SECRET có đúng không

### **2. "Redirect URI mismatch"**
- Kiểm tra GOOGLE_REDIRECT_URI có khớp với Google Cloud Console không
- URL phải chính xác: `http://localhost:8000/auth/google/callback`

### **3. "Access blocked"**
- Kiểm tra OAuth consent screen đã được cấu hình chưa
- Thêm email test trong OAuth consent screen

## 📋 **Template file .env hoàn chỉnh:**

```env
APP_NAME="Thư Viện"
APP_ENV=local
APP_KEY=base64:your_app_key_here
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quanlythuvien
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## ✅ **Sau khi cấu hình xong:**

1. **Kiểm tra:** `php artisan config:clear`
2. **Restart:** `php artisan serve`
3. **Test:** Truy cập `http://localhost:8000/register`
4. **Nhấn:** "Đăng ký với Google"

Nếu vẫn còn lỗi, hãy kiểm tra lại Google Cloud Console và đảm bảo redirect URI khớp chính xác!





