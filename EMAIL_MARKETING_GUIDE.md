# Hướng dẫn Email Marketing - Hệ thống Quản lý Thư viện

## Tổng quan

Hệ thống Email Marketing đã được tích hợp vào hệ thống quản lý thư viện, cho phép gửi email tiếp thị chuyên nghiệp đến độc giả với nhiều tính năng nâng cao.

## Tính năng chính

### 1. Template Email Chuyên nghiệp
- **Template Marketing**: Thiết kế đẹp với gradient, responsive, bao gồm sách nổi bật và thống kê
- **Template Simple**: Thiết kế đơn giản cho thông báo thường
- **Template Notification**: Thiết kế cho thông báo hệ thống

### 2. Phân đoạn người dùng
- **Tags**: Phân loại theo nhãn (student, teacher, researcher, etc.)
- **Nguồn đăng ký**: Website, Facebook, Email mời, Admin thêm thủ công
- **Tùy chọn nhận email**: Newsletter, thông báo sự kiện, khuyến nghị sách
- **Thời gian đăng ký**: Lọc theo ngày đăng ký

### 3. Quản lý Chiến dịch
- **Tạo chiến dịch**: Thiết kế nội dung với placeholder động
- **Lên lịch gửi**: Tự động gửi theo thời gian đã định
- **Theo dõi thống kê**: Tỷ lệ gửi, mở, click
- **Quản lý trạng thái**: Draft, Scheduled, Sending, Sent, Cancelled

### 4. Quản lý Subscribers
- **Đăng ký tự động**: Từ hệ thống thư viện
- **Thêm thủ công**: Admin có thể thêm subscribers
- **Hủy đăng ký**: Tự động và thủ công
- **Tags và Preferences**: Phân loại và tùy chỉnh

## Cách sử dụng

### 1. Truy cập Email Marketing
```
/admin/email-marketing
```

### 2. Tạo chiến dịch mới
1. Click "Tạo chiến dịch mới"
2. Điền thông tin cơ bản:
   - Tên chiến dịch
   - Tiêu đề email
   - Nội dung (sử dụng placeholder: {{name}}, {{email}}, {{library_name}})
   - Chọn template
3. Cài đặt nâng cao:
   - Lên lịch gửi
   - Chọn đối tượng nhận
   - Metadata bổ sung (sách nổi bật, thống kê, URL hành động)

### 3. Quản lý Subscribers
```
/admin/email-marketing/subscribers
```
- Xem danh sách subscribers
- Thêm subscribers mới
- Hủy đăng ký subscribers

### 4. Theo dõi thống kê
- Tỷ lệ gửi thành công
- Tỷ lệ mở email
- Tỷ lệ click
- Thống kê theo chiến dịch

## Placeholder có sẵn

### Cơ bản
- `{{name}}` - Tên người nhận
- `{{email}}` - Email người nhận
- `{{library_name}}` - Tên thư viện
- `{{current_date}}` - Ngày hiện tại (dd/mm/yyyy)
- `{{current_year}}` - Năm hiện tại

### Metadata động
- `{{book_title}}` - Tên sách (cho nhắc nhở mượn sách)
- `{{due_date}}` - Ngày hẹn trả
- `{{days_remaining}}` - Số ngày còn lại
- `{{days_overdue}}` - Số ngày quá hạn

## Cấu hình tự động

### 1. Command tự động gửi
```bash
php artisan email-marketing:send
```

### 2. Cron Job (thêm vào crontab)
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Kernel.php (đã cấu hình)
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('email-marketing:send')->everyMinute();
}
```

## Template mẫu

### Template Marketing
```html
Xin chào {{name}},

Chúng tôi muốn chia sẻ với bạn những tin tức mới nhất từ thư viện:

📚 Hơn 10,000 đầu sách mới
🖥️ Hệ thống tìm kiếm thông minh  
📱 App thư viện di động
🎯 Dịch vụ tư vấn học tập

Hãy đến thư viện để khám phá những điều thú vị!

Trân trọng,
Thư viện
```

### Template Simple
```html
Xin chào {{name}},

Thông báo quan trọng từ thư viện...

Trân trọng,
Thư viện
```

## Troubleshooting

### Lỗi không gửi được email
1. Kiểm tra cấu hình SMTP trong `.env`
2. Kiểm tra template có tồn tại không
3. Xem log: `storage/logs/laravel.log`

### Command không chạy tự động
1. Kiểm tra cron job
2. Kiểm tra scheduler: `php artisan schedule:list`
3. Chạy thủ công: `php artisan email-marketing:send`

### Template không hiển thị đúng
1. Kiểm tra file template trong `resources/views/emails/`
2. Kiểm tra placeholder có đúng không
3. Kiểm tra CSS responsive

## API Endpoints

### Chiến dịch
- `GET /admin/email-marketing` - Danh sách chiến dịch
- `POST /admin/email-marketing` - Tạo chiến dịch mới
- `GET /admin/email-marketing/{id}` - Chi tiết chiến dịch
- `PUT /admin/email-marketing/{id}` - Cập nhật chiến dịch
- `DELETE /admin/email-marketing/{id}` - Xóa chiến dịch
- `POST /admin/email-marketing/{id}/send` - Gửi ngay
- `POST /admin/email-marketing/{id}/schedule` - Lên lịch
- `POST /admin/email-marketing/{id}/cancel` - Hủy

### Subscribers
- `GET /admin/email-marketing/subscribers` - Danh sách subscribers
- `POST /admin/email-marketing/subscribers/add` - Thêm subscriber
- `POST /admin/email-marketing/subscribers/{id}/unsubscribe` - Hủy đăng ký

## Bảo mật

- Tất cả routes đều có middleware `auth` và `admin`
- Phân quyền chi tiết theo permission
- Validation đầy đủ cho tất cả input
- Log đầy đủ các hoạt động

## Mở rộng

### Thêm template mới
1. Tạo file trong `resources/views/emails/`
2. Thêm vào danh sách templates trong controller
3. Cập nhật validation

### Thêm metadata mới
1. Cập nhật migration `email_campaigns`
2. Thêm vào `EmailMarketingService`
3. Cập nhật view create/edit

### Tích hợp provider khác
1. Cập nhật `EmailMarketingService`
2. Thêm cấu hình trong `.env`
3. Cập nhật template nếu cần

---

**Lưu ý**: Hệ thống email marketing đã được tích hợp hoàn chỉnh và sẵn sàng sử dụng. Tất cả các tính năng đã được test và hoạt động ổn định.
















