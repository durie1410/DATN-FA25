# Hướng dẫn truy cập Email Marketing

## Các bước để truy cập Email Marketing:

### 1. Đảm bảo server đang chạy
```bash
php artisan serve
```
Hoặc nếu dùng Laragon, đảm bảo Apache/Nginx đang chạy.

### 2. Đăng nhập với tài khoản Admin
- Truy cập: `http://localhost:8000/login` (hoặc domain của bạn)
- Đăng nhập với tài khoản có role = 'admin'

### 3. Truy cập Email Marketing
Sau khi đăng nhập, truy cập một trong các URL sau:

#### Test Routes (để kiểm tra):
- `http://localhost:8000/admin/email-marketing-test` - Test route đơn giản
- `http://localhost:8000/admin/email-marketing-simple` - Test với dữ liệu

#### Routes chính:
- `http://localhost:8000/admin/email-marketing` - Trang chính Email Marketing
- `http://localhost:8000/admin/email-marketing/create` - Tạo chiến dịch mới
- `http://localhost:8000/admin/email-marketing/subscribers` - Quản lý subscribers

### 4. Nếu vẫn không truy cập được:

#### Kiểm tra routes:
```bash
php artisan route:list --name=email-marketing
```

#### Kiểm tra cache:
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

#### Kiểm tra database:
```bash
php artisan migrate:status
```

### 5. Troubleshooting:

#### Lỗi 404:
- Kiểm tra routes đã được đăng ký chưa
- Clear cache routes

#### Lỗi 403 (Forbidden):
- Đảm bảo đã đăng nhập với tài khoản admin
- Kiểm tra middleware admin

#### Lỗi 500 (Server Error):
- Kiểm tra log: `storage/logs/laravel.log`
- Kiểm tra database connection
- Kiểm tra các model có tồn tại không

### 6. Test nhanh:

#### Test route đơn giản:
```
http://localhost:8000/admin/email-marketing-test
```
Nếu thấy "Email Marketing Admin Test - Routes are working!" thì routes đã hoạt động.

#### Test với dữ liệu:
```
http://localhost:8000/admin/email-marketing-simple
```
Nếu thấy trang với dữ liệu campaigns và stats thì controller đã hoạt động.

### 7. Dữ liệu mẫu:
Nếu chưa có dữ liệu, chạy:
```bash
php artisan db:seed --class=EmailMarketingSeeder
```

### 8. Cấu trúc URL:
- `/admin/email-marketing` - Danh sách chiến dịch
- `/admin/email-marketing/create` - Tạo mới
- `/admin/email-marketing/{id}` - Chi tiết
- `/admin/email-marketing/{id}/edit` - Chỉnh sửa
- `/admin/email-marketing/subscribers` - Quản lý subscribers

### 9. Nếu vẫn có vấn đề:
1. Kiểm tra file `.env` có đúng database không
2. Kiểm tra `APP_URL` trong `.env`
3. Kiểm tra middleware `admin` có hoạt động không
4. Kiểm tra user có role = 'admin' không

### 10. Test cuối cùng:
Nếu tất cả đều ổn, bạn sẽ thấy:
- Trang Email Marketing với thống kê tổng quan
- Danh sách chiến dịch (nếu có)
- Nút "Tạo chiến dịch mới"
- Các thống kê: tổng chiến dịch, subscribers, email đã gửi, tỷ lệ mở

---

**Lưu ý**: Tất cả routes email marketing đã được tạo và không cần permission đặc biệt, chỉ cần đăng nhập với tài khoản admin.
















