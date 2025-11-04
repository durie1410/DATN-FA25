# Hướng dẫn cấu hình Google OAuth cho đăng ký/đăng nhập

## Bước 1: Tạo Google OAuth App

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project hiện có
3. Kích hoạt Google+ API hoặc Google Identity API
4. Vào **Credentials** → **Create Credentials** → **OAuth client ID**
5. Chọn **Web application**
6. Thêm các URI sau:
   - **Authorized JavaScript origins**: `http://localhost:8000` (hoặc domain của bạn)
   - **Authorized redirect URIs**: `http://localhost:8000/auth/google/callback`

## Bước 2: Cấu hình file .env

Thêm các dòng sau vào file `.env`:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## Bước 3: Cấu hình Mail (để gửi email thông báo)

Thêm cấu hình mail vào file `.env`:

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

**Lưu ý**: Nếu sử dụng Gmail, bạn cần tạo App Password:
1. Vào Google Account Settings
2. Security → 2-Step Verification → App passwords
3. Tạo password mới cho ứng dụng

## Bước 4: Chạy Migration

```bash
php artisan migrate
```

## Bước 5: Cấu hình Queue (tùy chọn)

Để gửi email bất đồng bộ, cấu hình queue trong `.env`:

```env
QUEUE_CONNECTION=database
```

Sau đó chạy:
```bash
php artisan queue:table
php artisan migrate
```

## Tính năng đã được thêm:

### 1. Đăng ký với Google OAuth
- Nút "Đăng ký với Google" trên form đăng ký
- Tự động tạo tài khoản từ thông tin Google
- Lưu avatar và Google ID

### 2. Đăng nhập với Google OAuth  
- Nút "Đăng nhập với Google" trên form đăng nhập
- Liên kết tài khoản hiện có với Google ID

### 3. Email thông báo chào mừng
- Gửi email tự động sau khi đăng ký thành công
- Nội dung email bằng tiếng Việt
- Hướng dẫn sử dụng hệ thống

### 4. Bảo mật
- Rate limiting cho đăng nhập
- Audit log cho các hoạt động đăng ký/đăng nhập
- Validation dữ liệu đầu vào

## Cách sử dụng:

1. Người dùng truy cập `/register` hoặc `/login`
2. Chọn "Đăng ký/Đăng nhập với Google"
3. Xác thực với Google
4. Hệ thống tự động tạo tài khoản hoặc đăng nhập
5. Gửi email chào mừng (nếu đăng ký mới)

## Troubleshooting:

- **Lỗi "Invalid client"**: Kiểm tra GOOGLE_CLIENT_ID và GOOGLE_CLIENT_SECRET
- **Lỗi redirect**: Kiểm tra GOOGLE_REDIRECT_URI có khớp với cấu hình trong Google Console
- **Email không gửi được**: Kiểm tra cấu hình MAIL_* trong .env
- **Lỗi database**: Chạy `php artisan migrate` để cập nhật cơ sở dữ liệu





