# Hướng dẫn Setup Tính Năng Hoàn Trả Sách với Ảnh

## ✅ Đã hoàn thành:

1. **Migration**: `database/migrations/2025_12_08_000000_add_return_image_to_borrows_table.php`
2. **Model**: `app/Models/Borrow.php` - đã thêm `anh_hoan_tra` vào fillable
3. **Controller**: `app/Http/Controllers/BorrowController.php` - đã xử lý upload ảnh
4. **View**: `resources/views/account/borrowed-books.blade.php` - đã có form với:
   - Dropdown tình trạng sách (bắt buộc)
   - Input upload ảnh (bắt buộc)
   - Preview ảnh
   - Ghi chú (tùy chọn)
5. **JavaScript**: Đã có hàm `previewReturnImage()` để preview ảnh

## 🔧 Cần thực hiện:

### Bước 1: Thêm cột vào database

Chạy một trong các cách sau:

**Cách 1: Chạy migration (khuyến nghị)**
```bash
php artisan migrate --path=database/migrations/2025_12_08_000001_ensure_return_image_column.php --force
```

**Cách 2: Chạy SQL trực tiếp trong phpMyAdmin**
```sql
ALTER TABLE `borrows` 
ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL 
AFTER `tinh_trang_sach` 
COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng';
```

**Cách 3: Chạy script PHP**
```bash
php setup_return_image_feature.php
```

### Bước 2: Tạo storage link (nếu chưa có)
```bash
php artisan storage:link
```

### Bước 3: Kiểm tra quyền thư mục
Đảm bảo thư mục `storage/app/public/return-books` có quyền ghi.

## 🧪 Test tính năng:

1. Đăng nhập với tài khoản khách hàng
2. Vào trang "Sách đang mượn"
3. Tìm sách có trạng thái "Chờ Trả sách"
4. Nhấn nút "Hoàn trả sách"
5. Điền form:
   - Chọn tình trạng sách
   - Upload ảnh minh chứng
   - Xem preview ảnh
   - Nhập ghi chú (nếu có)
6. Nhấn "Xác nhận hoàn trả"

## 📝 Lưu ý:

- Ảnh sẽ được lưu trong `storage/app/public/return-books/`
- Định dạng ảnh: JPG, PNG, GIF, WEBP
- Kích thước tối đa: 5MB
- Ảnh sẽ được resize tự động nếu quá lớn
