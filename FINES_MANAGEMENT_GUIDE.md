# Hướng dẫn sử dụng hệ thống quản lý phí phạt

## Tổng quan
Hệ thống quản lý phí phạt cho phép thủ thư tạo, quản lý và theo dõi các khoản phạt của độc giả trong thư viện.

## Các tính năng chính

### 1. Quản lý phạt
- **Tạo phạt mới**: Tạo phạt cho các vi phạm như trả muộn, làm hỏng sách, mất sách
- **Chỉnh sửa phạt**: Cập nhật thông tin phạt, thay đổi trạng thái
- **Xem chi tiết**: Xem đầy đủ thông tin phạt, độc giả và sách liên quan
- **Đánh dấu đã thanh toán**: Cập nhật trạng thái khi độc giả đã thanh toán
- **Miễn phạt**: Miễn phạt trong các trường hợp đặc biệt

### 2. Tự động hóa
- **Tự động tạo phạt trả muộn**: Hệ thống tự động tạo phạt cho sách trả muộn
- **Thông báo tự động**: Gửi email và thông báo database cho độc giả khi có phạt mới
- **Command hàng ngày**: Chạy tự động mỗi ngày lúc 8:00 để tạo phạt trả muộn

### 3. Báo cáo và thống kê
- **Báo cáo tổng quan**: Thống kê số lượng và tổng tiền phạt
- **Lọc theo thời gian**: Xem báo cáo theo khoảng thời gian cụ thể
- **Biểu đồ trực quan**: Biểu đồ phân bố theo trạng thái và loại phạt

## Các loại phạt

| Loại phạt | Mô tả | Mức phạt |
|-----------|-------|----------|
| Trả muộn | Trả sách sau hạn quy định | 5,000 VND/ngày |
| Làm hỏng sách | Sách bị hỏng trong quá trình mượn | Theo giá trị sách |
| Mất sách | Không thể trả sách | Theo giá trị sách |
| Khác | Các vi phạm khác | Tùy theo mức độ |

## Các trạng thái phạt

- **Chưa thanh toán (pending)**: Phạt chưa được thanh toán
- **Đã thanh toán (paid)**: Phạt đã được thanh toán
- **Đã miễn (waived)**: Phạt được miễn
- **Đã hủy (cancelled)**: Phạt bị hủy

## Hướng dẫn sử dụng

### Tạo phạt mới
1. Vào menu "Quản lý phí phạt" → "Tạo phạt mới"
2. Chọn phiếu mượn từ danh sách
3. Chọn loại phạt và nhập số tiền
4. Nhập mô tả lý do phạt
5. Chọn hạn thanh toán
6. Nhấn "Tạo phạt"

### Tự động tạo phạt trả muộn
1. Vào danh sách phạt
2. Nhấn nút "Tạo phạt trả muộn"
3. Hệ thống sẽ tự động tạo phạt cho tất cả sách quá hạn

### Quản lý phạt
- **Xem chi tiết**: Nhấn biểu tượng mắt để xem thông tin chi tiết
- **Chỉnh sửa**: Nhấn biểu tượng bút để chỉnh sửa
- **Đánh dấu đã thanh toán**: Nhấn biểu tượng check màu xanh
- **Miễn phạt**: Nhấn biểu tượng quà tặng màu xanh dương

### Báo cáo
1. Vào menu "Quản lý phí phạt" → "Báo cáo"
2. Chọn khoảng thời gian cần xem
3. Nhấn "Lọc báo cáo"
4. Xem thống kê và biểu đồ

## Command line

### Chạy thủ công
```bash
php artisan fines:create-late-returns
```

### Lên lịch tự động
Command đã được cấu hình để chạy tự động mỗi ngày lúc 8:00 sáng.

## Quyền hạn

- **view-fines**: Xem danh sách phạt
- **create-fines**: Tạo phạt mới
- **edit-fines**: Chỉnh sửa phạt
- **waive-fines**: Miễn phạt
- **view-reports**: Xem báo cáo

## Lưu ý quan trọng

1. **Email độc giả**: Đảm bảo độc giả có email để nhận thông báo
2. **Template thông báo**: Cần có template "fine_notification" trong hệ thống
3. **Scheduler**: Cần cấu hình cron job để chạy scheduler Laravel
4. **Backup**: Thường xuyên backup dữ liệu phạt quan trọng

## Troubleshooting

### Lỗi không gửi được thông báo
- Kiểm tra cấu hình email trong `.env`
- Kiểm tra template thông báo có tồn tại không
- Xem log để biết chi tiết lỗi

### Command không chạy tự động
- Kiểm tra cron job: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
- Kiểm tra log scheduler: `php artisan schedule:list`

### Phạt không được tạo tự động
- Kiểm tra trạng thái phiếu mượn phải là "Dang muon"
- Kiểm tra ngày hẹn trả phải nhỏ hơn ngày hiện tại
- Kiểm tra đã có phạt chưa thanh toán cho phiếu mượn đó chưa
