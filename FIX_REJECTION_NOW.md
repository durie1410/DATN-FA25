# 🔧 SỬA LỖI NGAY - Thêm cột từ chối nhận sách

## ⚠️ LỖI HIỆN TẠI
Bạn đang gặp lỗi: `Unknown column 'customer_rejected_delivery' in 'field list'`

## ✅ GIẢI PHÁP NHANH NHẤT

### Cách 1: Chạy SQL trực tiếp trong phpMyAdmin (KHUYẾN NGHỊ)

1. Mở phpMyAdmin (thường là http://localhost/phpmyadmin)
2. Chọn database của bạn
3. Vào tab "SQL"
4. Copy và chạy các lệnh sau:

```sql
ALTER TABLE borrows 
ADD COLUMN customer_rejected_delivery TINYINT(1) NOT NULL DEFAULT 0 
COMMENT 'Khách hàng đã từ chối nhận sách';

ALTER TABLE borrows 
ADD COLUMN customer_rejected_delivery_at TIMESTAMP NULL DEFAULT NULL 
COMMENT 'Thời gian khách hàng từ chối nhận sách';

ALTER TABLE borrows 
ADD COLUMN customer_rejection_reason TEXT NULL DEFAULT NULL 
COMMENT 'Lý do khách hàng từ chối nhận sách';
```

5. Click "Go" hoặc nhấn Ctrl+Enter

**Lưu ý:** Nếu cột đã tồn tại, sẽ báo lỗi "Duplicate column name" - không sao, bỏ qua và chạy tiếp các lệnh khác.

### Cách 2: Chạy file SQL

1. Mở file `FIX_REJECTION_COLUMNS.sql` trong thư mục gốc
2. Copy nội dung
3. Chạy trong phpMyAdmin hoặc MySQL client

### Cách 3: Truy cập route tự động

Truy cập URL sau trong trình duyệt:
```
http://quanlythuviennn.test/fix-rejection-columns
```

Route này sẽ tự động thêm các cột còn thiếu.

### Cách 4: Chạy script PHP

```bash
php add_rejection_columns_direct.php
```

## ✅ SAU KHI SỬA

1. Làm mới trang web (F5)
2. Lỗi sẽ biến mất
3. Tính năng từ chối nhận sách sẽ hoạt động bình thường

## 📝 KIỂM TRA

Sau khi chạy SQL, kiểm tra lại bằng cách:

```sql
SHOW COLUMNS FROM borrows LIKE 'customer_rejected%';
SHOW COLUMNS FROM borrows LIKE 'customer_rejection%';
```

Bạn sẽ thấy 3 cột:
- customer_rejected_delivery
- customer_rejected_delivery_at
- customer_rejection_reason

## 🗑️ SAU KHI XONG

Nhớ xóa route tạm thời `/fix-rejection-columns` trong file `routes/web.php` để bảo mật!
