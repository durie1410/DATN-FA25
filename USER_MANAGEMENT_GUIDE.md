# 📋 Hướng Dẫn Quản Lý Người Dùng - Hệ Thống Thư Viện

## 🎯 Tổng Quan

Hệ thống quản lý người dùng bao gồm 3 loại tài khoản chính với các quyền hạn khác nhau:

### 👑 **Admin (Quản trị viên)**
- **Quyền hạn**: Toàn quyền quản lý hệ thống
- **Số lượng**: 3 tài khoản
- **Chức năng**: Quản lý tất cả modules, users, roles, permissions

### 📚 **Staff (Thủ thư/Nhân viên)**
- **Quyền hạn**: Quản lý hoạt động thư viện hàng ngày
- **Số lượng**: 3 tài khoản
- **Chức năng**: Mượn/trả sách, quản lý độc giả, xử lý đặt chỗ

### 👥 **User (Độc giả)**
- **Quyền hạn**: Sử dụng dịch vụ thư viện
- **Số lượng**: 17 tài khoản (16 readers)
- **Chức năng**: Xem sách, đánh giá, đặt trước

---

## 🔐 **Tài Khoản Mẫu**

### **Admin Accounts**

| Email | Mật khẩu | Tên | Vai trò |
|-------|----------|-----|---------|
| `admin@library.com` | `123456` | Super Admin | Admin |
| `admin2@library.com` | `123456` | Nguyễn Văn Admin | Admin |
| `manager@library.com` | `123456` | Trần Thị Quản Lý | Admin |

### **Staff Accounts (Librarians)**

| Email | Mật khẩu | Tên | Chức vụ | Mã thủ thư |
|-------|----------|-----|---------|------------|
| `librarian@library.com` | `123456` | Lê Văn Thủ Thư | Thủ thư trưởng | TT001 |
| `staff@library.com` | `123456` | Phạm Thị Nhân Viên | Nhân viên thư viện | TT002 |
| `assistant@library.com` | `123456` | Hoàng Văn Trợ Lý | Trợ lý thư viện | TT003 |

### **User Accounts (Readers)**

| Email | Mật khẩu | Tên | Loại | Mã thẻ |
|-------|----------|-----|------|--------|
| `student@library.com` | `123456` | Nguyễn Văn Sinh Viên | Sinh viên | RD001 |
| `teacher@library.com` | `123456` | Trần Thị Giảng Viên | Giảng viên | RD002 |
| `researcher@library.com` | `123456` | Lê Văn Nghiên Cứu | Nghiên cứu sinh | RD003 |
| `learner@library.com` | `123456` | Phạm Thị Học Viên | Học viên | RD004 |
| `master@library.com` | `123456` | Hoàng Văn Thạc Sĩ | Thạc sĩ | RD005 |
| `doctor@library.com` | `123456` | Võ Thị Tiến Sĩ | Tiến sĩ | RD006 |

---

## 🏢 **Cấu Trúc Tổ Chức**

### **Khoa (Faculties)**

| Mã khoa | Tên khoa | Trưởng khoa | Email |
|---------|----------|-------------|-------|
| CNTT | Khoa Công nghệ Thông tin | PGS.TS Nguyễn Văn A | cntt@university.edu.vn |
| KT | Khoa Kinh tế | TS Trần Thị B | kinhte@university.edu.vn |
| NN | Khoa Ngoại ngữ | TS Lê Văn C | ngoaingu@university.edu.vn |

### **Ngành (Departments)**

| Mã ngành | Tên ngành | Thuộc khoa | Trưởng ngành |
|----------|-----------|------------|--------------|
| CNTT | Ngành Công nghệ Thông tin | CNTT | ThS Phạm Văn D |
| MMT | Ngành Mạng máy tính | CNTT | ThS Hoàng Thị E |
| KTH | Ngành Kinh tế học | KT | TS Nguyễn Văn F |
| TA | Ngành Tiếng Anh | NN | ThS Trần Thị G |

---

## 🚀 **Cách Sử Dụng**

### **1. Đăng Nhập**

1. Truy cập `/login`
2. Nhập email và mật khẩu từ bảng trên
3. Hệ thống sẽ chuyển hướng đến dashboard phù hợp:
   - **Admin** → `/admin/dashboard`
   - **Staff** → `/staff/dashboard`
   - **User** → `/` (trang chủ)

### **2. Quản Lý Người Dùng (Admin)**

#### **Xem Danh Sách Người Dùng**
- Truy cập: **Admin Panel > Users**
- Xem tất cả người dùng với phân loại theo vai trò
- Tìm kiếm và lọc theo vai trò

#### **Tạo Người Dùng Mới**
- Nhấn nút **"Thêm Người Dùng"**
- Điền thông tin:
  - Tên đầy đủ
  - Email (duy nhất)
  - Mật khẩu
  - Vai trò (admin/staff/user)
- Xác nhận tạo

#### **Chỉnh Sửa Người Dùng**
- Nhấn nút **"Chỉnh sửa"** bên cạnh người dùng
- Cập nhật thông tin
- Lưu thay đổi

#### **Xóa Người Dùng**
- Nhấn nút **"Xóa"** (có xác nhận)
- ⚠️ **Cảnh báo**: Xóa người dùng sẽ xóa tất cả dữ liệu liên quan

### **3. Quản Lý Thủ Thư (Admin)**

#### **Xem Danh Sách Thủ Thư**
- Truy cập: **Admin Panel > Librarians**
- Xem thông tin chi tiết:
  - Thông tin cá nhân
  - Chức vụ và phòng ban
  - Ngày vào làm và hợp đồng
  - Lương cơ bản
  - Trạng thái hoạt động

#### **Thêm Thủ Thư Mới**
1. Tạo User với role = 'staff'
2. Tạo Librarian profile với thông tin chi tiết
3. Gán mã thủ thư duy nhất

#### **Quản Lý Hợp Đồng**
- Theo dõi ngày hết hạn hợp đồng
- Cảnh báo hợp đồng sắp hết hạn (30 ngày)
- Gia hạn hợp đồng

### **4. Quản Lý Độc Giả (Admin/Staff)**

#### **Xem Danh Sách Độc Giả**
- Truy cập: **Admin Panel > Readers** hoặc **Staff Panel > Readers**
- Xem thông tin:
  - Thông tin cá nhân
  - Khoa và ngành
  - Số thẻ độc giả
  - Ngày cấp và hết hạn thẻ
  - Trạng thái thẻ

#### **Tạo Độc Giả Mới**
1. Tạo User với role = 'user'
2. Tạo Reader profile
3. Gán số thẻ độc giả duy nhất
4. Chọn khoa và ngành

#### **Gia Hạn Thẻ Độc Giả**
- Nhấn nút **"Gia hạn thẻ"**
- Chọn thời gian gia hạn
- Cập nhật ngày hết hạn

#### **Khóa/Mở Khóa Thẻ**
- **Khóa thẻ**: Khi độc giả vi phạm
- **Mở khóa thẻ**: Sau khi giải quyết vi phạm

---

## 📊 **Thống Kê và Báo Cáo**

### **Dashboard Thống Kê**

#### **Admin Dashboard**
- Tổng số người dùng theo vai trò
- Số lượng thủ thư đang hoạt động
- Số lượng độc giả theo khoa
- Thống kê gia hạn thẻ
- Cảnh báo hợp đồng sắp hết hạn

#### **Staff Dashboard**
- Số lượng độc giả phục vụ
- Giao dịch mượn/trả trong ngày
- Độc giả có thẻ sắp hết hạn
- Vi phạm cần xử lý

### **Báo Cáo Xuất**

#### **Báo Cáo Người Dùng**
- Danh sách tất cả người dùng
- Phân loại theo vai trò
- Thống kê theo thời gian tạo

#### **Báo Cáo Thủ Thư**
- Danh sách thủ thư
- Thông tin hợp đồng
- Hiệu suất làm việc

#### **Báo Cáo Độc Giả**
- Danh sách độc giả theo khoa
- Thống kê thẻ hết hạn
- Lịch sử gia hạn thẻ

---

## 🔧 **Cấu Hình và Tùy Chỉnh**

### **Phân Quyền**

#### **Admin Permissions**
- `view-users`: Xem danh sách người dùng
- `create-users`: Tạo người dùng mới
- `edit-users`: Chỉnh sửa người dùng
- `delete-users`: Xóa người dùng
- `manage-roles`: Quản lý vai trò và quyền

#### **Staff Permissions**
- `view-readers`: Xem danh sách độc giả
- `create-readers`: Tạo độc giả mới
- `edit-readers`: Chỉnh sửa thông tin độc giả
- `renew-cards`: Gia hạn thẻ độc giả

### **Cài Đặt Hệ Thống**

#### **Thời Gian Gia Hạn**
- Thẻ sinh viên: 1 năm
- Thẻ giảng viên: 2 năm
- Thẻ nghiên cứu sinh: 3 năm
- Thẻ tiến sĩ: 5 năm

#### **Cảnh Báo**
- Thẻ hết hạn trong 30 ngày
- Hợp đồng hết hạn trong 30 ngày
- Vi phạm chưa xử lý

---

## 🚨 **Xử Lý Sự Cố**

### **Lỗi Thường Gặp**

#### **1. Không thể đăng nhập**
- Kiểm tra email và mật khẩu
- Kiểm tra trạng thái tài khoản
- Reset mật khẩu nếu cần

#### **2. Không có quyền truy cập**
- Kiểm tra vai trò người dùng
- Kiểm tra permissions
- Liên hệ admin để cấp quyền

#### **3. Thẻ độc giả hết hạn**
- Gia hạn thẻ từ admin/staff panel
- Cập nhật thông tin nếu cần
- Thông báo cho độc giả

#### **4. Hợp đồng thủ thư hết hạn**
- Gia hạn hợp đồng
- Cập nhật thông tin lương
- Thông báo cho thủ thư

### **Liên Hệ Hỗ Trợ**

- **Email**: support@library.com
- **Hotline**: (028) 1234-5678
- **Admin**: admin@library.com

---

## 📝 **Ghi Chú Quan Trọng**

### **Bảo Mật**
- Mật khẩu mặc định: `123456` (nên thay đổi)
- Sử dụng mật khẩu mạnh
- Không chia sẻ thông tin đăng nhập

### **Backup**
- Sao lưu dữ liệu người dùng định kỳ
- Lưu trữ thông tin quan trọng
- Test khôi phục dữ liệu

### **Tuân Thủ**
- Tuân thủ quy định về bảo mật thông tin
- Không tiết lộ thông tin cá nhân
- Xử lý vi phạm theo quy định

---

## 🎉 **Kết Luận**

Hệ thống quản lý người dùng đã được thiết lập hoàn chỉnh với:

- ✅ **3 Admin accounts** với quyền toàn hệ thống
- ✅ **3 Staff accounts** (thủ thư) với quyền quản lý thư viện
- ✅ **17 User accounts** (độc giả) với đầy đủ thông tin
- ✅ **3 Khoa** và **4 Ngành** được tổ chức rõ ràng
- ✅ **Phân quyền** chi tiết theo vai trò
- ✅ **Dashboard** thống kê đầy đủ
- ✅ **Báo cáo** xuất dữ liệu

Hệ thống sẵn sàng để sử dụng và có thể mở rộng thêm người dùng mới theo nhu cầu!
