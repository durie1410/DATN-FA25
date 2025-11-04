# Hệ thống phân quyền 3 vai trò - Thư viện Online

## Tổng quan

Hệ thống đã được cập nhật để hỗ trợ 3 vai trò riêng biệt với các quyền truy cập khác nhau:

### 1. Admin (Quản trị viên)
- **Quyền hạn**: Toàn quyền quản lý hệ thống
- **Dashboard**: `/admin/dashboard`
- **Chức năng**: 
  - Quản lý tất cả sách, danh mục, độc giả
  - Xem và xuất báo cáo
  - Quản lý người dùng và phân quyền
  - Cấu hình hệ thống

### 2. Staff (Nhân viên)
- **Quyền hạn**: Quản lý hoạt động thư viện hàng ngày
- **Dashboard**: `/staff/dashboard`
- **Chức năng**:
  - Quản lý sách, độc giả, mượn trả sách
  - Xử lý đặt chỗ và phê duyệt đánh giá
  - Quản lý phạt (không thể miễn phạt)
  - Xem báo cáo (không thể xuất)
  - Gửi thông báo

### 3. User (Người dùng)
- **Quyền hạn**: Sử dụng dịch vụ thư viện
- **Dashboard**: Trang chủ (`/`)
- **Chức năng**:
  - Xem sách và danh mục
  - Tạo đánh giá và đặt chỗ
  - Xem thông báo

## Cách sử dụng

### Đăng ký tài khoản mới
1. Truy cập `/register`
2. Chọn loại tài khoản: Admin, Staff, hoặc User
3. Điền thông tin và đăng ký
4. Hệ thống sẽ tự động chuyển hướng đến dashboard phù hợp

### Đăng nhập
1. Truy cập `/login`
2. Nhập email và mật khẩu
3. Hệ thống sẽ chuyển hướng đến dashboard theo vai trò:
   - Admin → `/admin/dashboard`
   - Staff → `/staff/dashboard`
   - User → `/` (trang chủ)

## Tài khoản mẫu

Sau khi chạy seeder, bạn có thể sử dụng các tài khoản mẫu:

### Admin
- **Email**: admin@library.com
- **Mật khẩu**: 123456
- **Vai trò**: Admin

### Staff
- **Email**: staff@library.com
- **Mật khẩu**: 123456
- **Vai trò**: Staff

### User
- **Email**: user@library.com
- **Mật khẩu**: 123456
- **Vai trò**: User

## Cấu trúc Routes

### Admin Routes (`/admin/*`)
- Dashboard: `/admin/dashboard`
- Quản lý sách: `/admin/books`
- Quản lý độc giả: `/admin/readers`
- Quản lý mượn trả: `/admin/borrows`
- Báo cáo: `/admin/reports`
- Cài đặt: `/admin/settings`

### Staff Routes (`/staff/*`)
- Dashboard: `/staff/dashboard`
- Quản lý sách: `/staff/books`
- Quản lý độc giả: `/staff/readers`
- Quản lý mượn trả: `/staff/borrows`
- Đặt chỗ: `/staff/reservations`
- Đánh giá: `/staff/reviews`
- Phạt: `/staff/fines`
- Báo cáo: `/staff/reports`
- Thông báo: `/staff/notifications`

### User Routes
- Trang chủ: `/`
- Sách: `/books`
- Đăng nhập: `/login`
- Đăng ký: `/register`

## Middleware

Hệ thống sử dụng các middleware để kiểm tra quyền truy cập:

- `admin`: Chỉ cho phép admin
- `staff`: Cho phép admin và staff
- `user`: Chỉ cho phép user thường
- `permission`: Kiểm tra quyền cụ thể

## Database

### Bảng users
- Cột `role` hỗ trợ 3 giá trị: 'admin', 'staff', 'user'
- Constraint check đảm bảo chỉ có các giá trị hợp lệ

### Bảng roles và permissions
- Sử dụng Spatie Permission package
- 3 roles chính: admin, staff, user
- Mỗi role có các permissions riêng biệt

## Cài đặt và chạy

1. **Chạy migration**:
   ```bash
   php artisan migrate
   ```

2. **Chạy seeder**:
   ```bash
   php artisan db:seed --class=RolePermissionSeeder
   ```

3. **Khởi động server**:
   ```bash
   php artisan serve
   ```

4. **Truy cập ứng dụng**:
   - URL: http://localhost:8000
   - Đăng ký tài khoản mới hoặc sử dụng tài khoản mẫu

## Lưu ý

- Staff không thể xóa dữ liệu (chỉ admin mới có quyền này)
- Staff không thể xuất báo cáo (chỉ admin mới có quyền này)
- Staff không thể miễn phạt (chỉ admin mới có quyền này)
- User chỉ có thể xem và tương tác với dữ liệu công khai

