# Hướng Dẫn Sử Dụng Hệ Thống Sao Lưu và Khôi Phục Dữ Liệu

## Tổng Quan

Hệ thống sao lưu và khôi phục dữ liệu của thư viện được thiết kế để đảm bảo an toàn dữ liệu với các tính năng:

- ✅ Sao lưu tự động hàng ngày
- ✅ Sao lưu thủ công theo yêu cầu
- ✅ Khôi phục dữ liệu từ file sao lưu
- ✅ Quản lý và theo dõi các bản sao lưu
- ✅ Dọn dẹp tự động các file cũ
- ✅ Giao diện web thân thiện

## Các Tính Năng Chính

### 1. Sao Lưu Tự Động
- **Tần suất**: Hàng ngày lúc 2:00 sáng
- **Loại**: Sao lưu tự động
- **Mô tả**: "Daily automatic backup"
- **Lưu trữ**: Tự động giữ lại 30 ngày

### 2. Sao Lưu Thủ Công
- Truy cập: **Admin Panel > Settings > Quản Lý Sao Lưu Dữ Liệu**
- Nhấn nút **"Tạo Sao Lưu Ngay"**
- Nhập mô tả cho bản sao lưu
- Xác nhận tạo sao lưu

### 3. Khôi Phục Dữ Liệu
- Nhấn nút **"Khôi Phục Dữ Liệu"**
- Chọn file sao lưu từ danh sách
- Xem thông tin chi tiết file
- Xác nhận khôi phục (⚠️ **CẢNH BÁO**: Sẽ thay thế toàn bộ dữ liệu hiện tại)

### 4. Quản Lý Sao Lưu
- **Xem danh sách**: Tất cả các bản sao lưu
- **Lọc theo loại**: Thủ công, Tự động, Định kỳ
- **Tải về**: Download file sao lưu
- **Xóa**: Xóa file sao lưu không cần thiết

## Sử Dụng Giao Diện Web

### Dashboard Sao Lưu
```
┌─────────────────────────────────────────────────────────────┐
│ 📊 Thống Kê Sao Lưu                                        │
├─────────────────────────────────────────────────────────────┤
│ 📁 Tổng Sao Lưu    ✅ Thành Công    💾 Dung Lượng    ⏰ 7 Ngày Qua │
│     15              14              245.6 MB         3      │
└─────────────────────────────────────────────────────────────┘
```

### Các Nút Thao Tác
- **🔵 Tạo Sao Lưu Ngay**: Tạo bản sao lưu mới ngay lập tức
- **🔵 Khôi Phục Dữ Liệu**: Khôi phục từ file sao lưu có sẵn
- **🟢 Làm Mới Danh Sách**: Cập nhật danh sách sao lưu
- **🟡 Cài Đặt Sao Lưu**: Cấu hình các tùy chọn sao lưu

### Bảng Danh Sách Sao Lưu
| Tên File | Loại | Ngày Tạo | Kích Thước | Mô Tả | Thao Tác |
|----------|------|----------|------------|-------|----------|
| backup_2024_01_20_14_30_15.sql | Thủ Công | 20/01/2024 14:30 | 45.2 MB | Sao lưu trước khi cập nhật | 📥 🔄 🗑️ |

## Sử Dụng Command Line

### 1. Tạo Sao Lưu
```bash
# Sao lưu thủ công
php artisan backup:create --type=manual --description="Sao lưu trước khi cập nhật hệ thống"

# Sao lưu tự động
php artisan backup:create --type=automatic --description="Sao lưu tự động"

# Sao lưu định kỳ
php artisan backup:create --type=scheduled --description="Sao lưu định kỳ hàng tuần"
```

### 2. Khôi Phục Sao Lưu
```bash
# Khôi phục với xác nhận
php artisan backup:restore backup_2024_01_20_14_30_15.sql

# Khôi phục không cần xác nhận (cẩn thận!)
php artisan backup:restore backup_2024_01_20_14_30_15.sql --force
```

### 3. Xem Danh Sách Sao Lưu
```bash
# Xem tất cả sao lưu
php artisan backup:list

# Xem sao lưu thủ công
php artisan backup:list --type=manual

# Giới hạn số lượng hiển thị
php artisan backup:list --limit=5
```

### 4. Dọn Dẹp Sao Lưu Cũ
```bash
# Xem trước những file sẽ bị xóa (dry-run)
php artisan backup:cleanup --days=30 --dry-run

# Xóa các file cũ hơn 30 ngày
php artisan backup:cleanup --days=30

# Xóa các file cũ hơn 7 ngày
php artisan backup:cleanup --days=7
```

## Cấu Hình Tự Động

### Scheduler (app/Console/Kernel.php)
```php
protected function schedule(Schedule $schedule)
{
    // Sao lưu tự động hàng ngày lúc 2:00
    $schedule->command('backup:create --type=automatic --description="Daily automatic backup"')
             ->dailyAt('02:00')
             ->withoutOverlapping()
             ->runInBackground();
    
    // Dọn dẹp sao lưu cũ hàng tuần (giữ lại 30 ngày)
    $schedule->command('backup:cleanup --days=30')
             ->weekly()
             ->sundays()
             ->at('03:00')
             ->withoutOverlapping()
             ->runInBackground();
}
```

### Cấu Hình Database
Đảm bảo file `.env` có cấu hình database đúng:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quanlythuvien
DB_USERNAME=root
DB_PASSWORD=
```

## Cấu Trúc File Sao Lưu

### Vị Trí Lưu Trữ
- **Thư mục**: `storage/app/backups/`
- **Định dạng tên**: `backup_YYYY_MM_DD_HH_MM_SS.sql`
- **Ví dụ**: `backup_2024_01_20_14_30_15.sql`

### Thông Tin Metadata
Mỗi bản sao lưu lưu trữ thông tin:
```json
{
    "table_count": 25,
    "total_records": 15420,
    "tables": {
        "users": 150,
        "books": 5000,
        "borrows": 3200,
        "readers": 800
    },
    "database_size": 45.2
}
```

## Bảo Mật và An Toàn

### Quyền Truy Cập
- **Xem sao lưu**: `view-settings`
- **Tạo sao lưu**: `manage-backup`
- **Khôi phục**: `manage-backup`
- **Xóa sao lưu**: `manage-backup`

### Khuyến Nghị Bảo Mật
1. **Sao lưu định kỳ**: Ít nhất hàng ngày
2. **Lưu trữ ngoài**: Copy file sao lưu ra ổ cứng khác
3. **Kiểm tra tính toàn vẹn**: Validate file sao lưu trước khi khôi phục
4. **Test khôi phục**: Thử nghiệm khôi phục trên môi trường test

## Xử Lý Sự Cố

### Lỗi Thường Gặp

#### 1. Lỗi "mysqldump command not found"
```bash
# Cài đặt MySQL client tools
# Ubuntu/Debian:
sudo apt-get install mysql-client

# CentOS/RHEL:
sudo yum install mysql
```

#### 2. Lỗi Permission Denied
```bash
# Cấp quyền cho thư mục storage
chmod -R 755 storage/
chown -R www-data:www-data storage/
```

#### 3. Lỗi Database Connection
- Kiểm tra cấu hình database trong `.env`
- Đảm bảo MySQL service đang chạy
- Kiểm tra username/password

#### 4. File Sao Lưu Bị Hỏng
```bash
# Kiểm tra tính toàn vẹn file
php artisan backup:validate backup_2024_01_20_14_30_15.sql

# Tạo sao lưu mới
php artisan backup:create --type=manual --description="Emergency backup"
```

### Log Files
- **Laravel Log**: `storage/logs/laravel.log`
- **Backup Log**: Tìm kiếm "Backup" trong log file

## API Endpoints

### REST API
```http
# Tạo sao lưu
POST /admin/settings/backup/create
Content-Type: application/json
{
    "description": "Manual backup"
}

# Khôi phục sao lưu
POST /admin/settings/backup/restore
Content-Type: application/json
{
    "backup_file": "backup_2024_01_20_14_30_15.sql"
}

# Lấy danh sách sao lưu
GET /admin/settings/backup/list

# Tải file sao lưu
GET /admin/settings/backup/download/{filename}

# Xóa file sao lưu
DELETE /admin/settings/backup/{filename}

# Thống kê sao lưu
GET /admin/settings/backup/stats
```

## Monitoring và Báo Cáo

### Thống Kê Hiển Thị
- **Tổng số sao lưu**: Tổng số file sao lưu đã tạo
- **Sao lưu thành công**: Số file sao lưu hoàn thành
- **Sao lưu thất bại**: Số file sao lưu bị lỗi
- **Tổng dung lượng**: Tổng kích thước tất cả file sao lưu
- **Sao lưu gần đây**: Số sao lưu trong 7 ngày qua

### Cảnh Báo
- ⚠️ Không có sao lưu trong 24 giờ
- ⚠️ Dung lượng ổ cứng sắp đầy
- ⚠️ Sao lưu thất bại liên tiếp
- ⚠️ File sao lưu bị hỏng

## Kết Luận

Hệ thống sao lưu và khôi phục dữ liệu được thiết kế để đảm bảo:
- **Tính khả dụng cao**: Sao lưu tự động, không cần can thiệp
- **Dễ sử dụng**: Giao diện web trực quan, command line đơn giản
- **An toàn**: Xác nhận trước khi khôi phục, kiểm tra tính toàn vẹn
- **Hiệu quả**: Dọn dẹp tự động, quản lý dung lượng

Để đảm bảo an toàn dữ liệu, hãy:
1. Kiểm tra sao lưu tự động hoạt động đúng
2. Test khôi phục định kỳ
3. Lưu trữ file sao lưu ở nhiều nơi
4. Theo dõi log và thống kê thường xuyên
