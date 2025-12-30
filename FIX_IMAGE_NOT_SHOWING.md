# Hướng Dẫn Sửa Lỗi Ảnh Không Hiển Thị

## Vấn đề
Khi upload hoặc cập nhật ảnh sách, hệ thống báo "Cập nhật thành công" nhưng ảnh không hiển thị trên trang web.

## Nguyên nhân
Trên Windows, thư mục `public/storage` không phải là **symbolic link** mà là một **thư mục thật**. 
Khi upload ảnh, file được lưu vào `storage/app/public/` nhưng không tự động sync sang `public/storage/`.

## Giải pháp

### Cách 1: Chạy lệnh khắc phục (KHUYẾN NGHỊ)
Mở PowerShell/Terminal tại thư mục gốc project và chạy:

```powershell
# Xóa thư mục public/storage hiện tại
Remove-Item -Recurse -Force public\storage

# Tạo symbolic link đúng cách
php artisan storage:link
```

### Cách 2: Sử dụng file batch (Windows)
Chạy file `fix_storage_link.bat` đã được tạo sẵn:

```batch
fix_storage_link.bat
```

## Kiểm tra sau khi sửa

### 1. Kiểm tra symbolic link
```powershell
Get-Item public\storage | Select-Object LinkType, Target
```

Kết quả mong đợi:
```
LinkType Target
-------- ------
Junction {D:\laragon\www\quanlythuviennn\storage\app\public}
```

### 2. Kiểm tra file ảnh
```powershell
# Kiểm tra số lượng file
(Get-ChildItem public\storage\books).Count
(Get-ChildItem storage\app\public\books).Count
# Hai số này phải BẰNG NHAU
```

### 3. Test trên trình duyệt
1. Vào trang quản lý sách: http://quanlythuviennn.test/admin/books
2. Nhấn F12 để mở DevTools
3. Kiểm tra Network tab - các ảnh phải load thành công (status 200)
4. Nếu vẫn không thấy, nhấn Ctrl+Shift+R để xóa cache và reload

## Lưu ý quan trọng

### Trên Windows
- Phải chạy PowerShell/CMD **với quyền Administrator** để tạo symbolic link
- Nếu không có quyền, Windows sẽ tạo thư mục thường thay vì symbolic link

### Sau khi deploy hoặc clone project
- **LUÔN CHẠY** `php artisan storage:link` sau khi:
  - Clone project từ git
  - Deploy lên server mới
  - Cài đặt lại Windows
  - Di chuyển project sang máy khác

### Nếu vẫn không hiển thị
1. Xóa cache Laravel:
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

2. Xóa cache trình duyệt:
- Chrome/Edge: Ctrl + Shift + Delete
- Chọn "Cached images and files"
- Hoặc nhấn Ctrl + Shift + R để hard refresh

3. Kiểm tra quyền thư mục:
```powershell
# Đảm bảo storage có quyền ghi
icacls storage /grant Everyone:F /T
icacls public\storage /grant Everyone:F /T
```

## Cách hoạt động

### Đúng (Symbolic Link)
```
storage/app/public/books/image.jpg
                ↓ (symbolic link)
public/storage/books/image.jpg
                ↓
URL: http://site.test/storage/books/image.jpg ✅
```

### Sai (Thư mục thường)
```
storage/app/public/books/image.jpg  ← File ở đây
public/storage/books/               ← Thư mục rỗng/khác
                ↓
URL: http://site.test/storage/books/image.jpg ❌ (404 Not Found)
```

## File liên quan
- `app/Services/FileUploadService.php` - Service xử lý upload
- `app/Http/Controllers/BookController.php` - Controller cập nhật sách
- `config/filesystems.php` - Cấu hình storage
- `public/storage/` - Phải là symbolic link
- `storage/app/public/` - Thư mục thực chứa file

## Tạo lại lần đầu (Chỉ 1 lần)
Nếu chưa có symbolic link bao giờ:

```bash
php artisan storage:link
```

Nếu đã có nhưng bị lỗi:

```bash
# Windows PowerShell
Remove-Item -Recurse -Force public\storage
php artisan storage:link

# Linux/Mac
rm -rf public/storage
php artisan storage:link
```

---

**Ngày tạo:** 5/12/2025  
**Người tạo:** AI Assistant  
**Trạng thái:** ✅ Đã kiểm tra và hoạt động



