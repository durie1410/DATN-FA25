# 📖 User Manual - Hệ thống Quản lý Thư viện

## 🎯 **Tổng quan hệ thống**

Hệ thống Quản lý Thư viện là một ứng dụng web toàn diện được xây dựng trên Laravel 10, cung cấp đầy đủ các chức năng quản lý thư viện hiện đại.

### **Tính năng chính:**
- 📚 Quản lý sách và thể loại
- 👥 Quản lý độc giả
- 📖 Quản lý mượn/trả sách
- ⭐ Hệ thống đánh giá và bình luận
- 💰 Quản lý phí phạt
- 📅 Hệ thống đặt trước sách
- 🔍 Tìm kiếm nâng cao
- 📊 Báo cáo và thống kê
- 📦 Quản lý kho
- 🔔 Thông báo đa kênh

---

## 🔐 **Đăng nhập và Phân quyền**

### **Các loại tài khoản:**

#### **1. Super Admin** 🔑
- **Quyền hạn**: Toàn quyền hệ thống
- **Tài khoản**: `admin@library.com` / `123456`
- **Chức năng**: Quản lý tất cả modules, users, roles, permissions

#### **2. Admin** 👨‍💼
- **Quyền hạn**: Quản lý thư viện đầy đủ
- **Tài khoản**: `admin2@library.com` / `123456`
- **Chức năng**: Quản lý sách, độc giả, mượn sách, báo cáo

#### **3. Librarian** 📚
- **Quyền hạn**: Quản lý thư viện cơ bản
- **Tài khoản**: `librarian@library.com` / `123456`
- **Chức năng**: Mượn/trả sách, quản lý độc giả, xem báo cáo

#### **4. Assistant** 🤝
- **Quyền hạn**: Hỗ trợ thư viện
- **Tài khoản**: `assistant@library.com` / `123456`
- **Chức năng**: Tạo độc giả, mượn sách, xem thông tin

#### **5. User** 👤
- **Quyền hạn**: Sử dụng dịch vụ thư viện
- **Tài khoản**: `user@library.com` / `123456`
- **Chức năng**: Xem sách, đánh giá, đặt trước

---

## 🏠 **Trang chủ và Dashboard**

### **Trang chủ công khai** (`/`)
- Hiển thị danh sách sách mới nhất
- Thống kê tổng quan thư viện
- Tìm kiếm sách nhanh
- Liên kết đăng nhập/đăng ký

### **Dashboard Admin** (`/admin/dashboard`)
- **Thống kê tổng quan:**
  - Tổng số sách, độc giả, lượt mượn
  - Sách quá hạn, phí phạt chưa thanh toán
  - Biểu đồ thống kê theo tháng
- **Thông báo gần đây**
- **Truy cập nhanh các chức năng**

---

## 📚 **Quản lý Sách**

### **Danh sách sách** (`/admin/books`)
- **Xem danh sách:** Tất cả sách với thông tin chi tiết
- **Tìm kiếm:** Theo tên sách, tác giả, thể loại
- **Lọc:** Theo thể loại, năm xuất bản, tình trạng
- **Sắp xếp:** Theo tên, tác giả, ngày tạo

### **Thêm sách mới** (`/admin/books/create`)
1. **Thông tin cơ bản:**
   - Tên sách (bắt buộc)
   - Thể loại (chọn từ dropdown)
   - Tác giả (bắt buộc)
   - Năm xuất bản (bắt buộc)
2. **Ảnh bìa:** Upload file JPG/PNG (tối đa 2MB)
3. **Mô tả:** Thông tin chi tiết về sách
4. **Lưu:** Click "Lưu" để thêm sách

### **Chỉnh sửa sách** (`/admin/books/{id}/edit`)
- Cập nhật thông tin sách
- Thay đổi ảnh bìa
- Xem preview ảnh hiện tại

### **Xóa sách**
- Click nút "Xóa" → Xác nhận
- ⚠️ **Lưu ý:** Sách đang được mượn không thể xóa

---

## 📂 **Quản lý Thể loại**

### **Danh sách thể loại** (`/admin/categories`)
- Xem tất cả thể loại
- Số lượng sách trong mỗi thể loại
- Tìm kiếm theo tên thể loại

### **Thêm thể loại mới** (`/admin/categories/create`)
1. Nhập tên thể loại
2. Click "Lưu"

### **Chỉnh sửa thể loại** (`/admin/categories/{id}/edit`)
- Cập nhật tên thể loại
- Xem số sách thuộc thể loại

---

## 👥 **Quản lý Độc giả**

### **Danh sách độc giả** (`/admin/readers`)
- **Thông tin hiển thị:**
  - Họ tên, email, số điện thoại
  - Số thẻ độc giả
  - Trạng thái thẻ (Hoạt động/Tạm khóa/Hết hạn)
  - Ngày cấp thẻ, ngày hết hạn
- **Tìm kiếm:** Theo tên, email, số thẻ
- **Lọc:** Theo trạng thái, giới tính

### **Thêm độc giả mới** (`/admin/readers/create`)
1. **Thông tin cá nhân:**
   - Họ tên (bắt buộc)
   - Email (bắt buộc, unique)
   - Số điện thoại (bắt buộc)
   - Giới tính
   - Ngày sinh
   - Địa chỉ
2. **Thông tin thẻ:**
   - Số thẻ độc giả (tự động hoặc nhập thủ công)
   - Ngày cấp thẻ
   - Ngày hết hạn
3. **Lưu:** Click "Lưu" để tạo độc giả

### **Chỉnh sửa độc giả** (`/admin/readers/{id}/edit`)
- Cập nhật thông tin cá nhân
- Thay đổi trạng thái thẻ
- Gia hạn thẻ độc giả

---

## 📖 **Quản lý Mượn/Trả sách**

### **Danh sách mượn sách** (`/admin/borrows`)
- **Thông tin hiển thị:**
  - Tên sách, tác giả
  - Tên độc giả, số thẻ
  - Ngày mượn, ngày hẹn trả
  - Trạng thái (Đang mượn/Đã trả/Quá hạn)
- **Tìm kiếm:** Theo tên sách, tên độc giả
- **Lọc:** Theo trạng thái, khoảng thời gian

### **Tạo mượn sách mới** (`/admin/borrows/create`)
1. **Chọn độc giả:** Tìm kiếm theo tên hoặc số thẻ
2. **Chọn sách:** Tìm kiếm theo tên sách
3. **Thông tin mượn:**
   - Ngày mượn (mặc định: hôm nay)
   - Ngày hẹn trả (tự động tính theo quy định)
   - Ghi chú
4. **Lưu:** Click "Lưu" để tạo phiếu mượn

### **Trả sách** (`/admin/borrows/{id}/return`)
1. Tìm phiếu mượn cần trả
2. Click nút "Trả sách"
3. **Thông tin trả:**
   - Ngày trả thực tế (mặc định: hôm nay)
   - Ghi chú
4. **Xác nhận:** Click "Xác nhận trả sách"

### **Xử lý sách quá hạn:**
- Sách quá hạn được đánh dấu màu đỏ
- Tự động tạo phí phạt trả muộn
- Gửi thông báo nhắc nhở

---

## ⭐ **Hệ thống Đánh giá và Bình luận**

### **Xem đánh giá sách** (`/admin/reviews`)
- **Thông tin hiển thị:**
  - Tên sách, tác giả
  - Tên người đánh giá
  - Điểm đánh giá (1-5 sao)
  - Nội dung bình luận
  - Trạng thái (Chờ duyệt/Đã duyệt)
- **Lọc:** Theo điểm đánh giá, trạng thái

### **Duyệt đánh giá:**
1. Click vào đánh giá cần duyệt
2. Đọc nội dung bình luận
3. Click "Duyệt" hoặc "Từ chối"
4. Nhập lý do nếu từ chối

### **Quản lý bình luận:**
- Xem tất cả bình luận
- Duyệt/từ chối bình luận
- Xóa bình luận không phù hợp

---

## 💰 **Quản lý Phí phạt**

### **Danh sách phí phạt** (`/admin/fines`)
- **Thông tin hiển thị:**
  - Tên độc giả, số thẻ
  - Tên sách
  - Loại phạt (Trả muộn/Hỏng sách/Mất sách)
  - Số tiền phạt
  - Trạng thái (Chưa thanh toán/Đã thanh toán)
  - Ngày hết hạn thanh toán
- **Lọc:** Theo trạng thái, loại phạt, khoảng thời gian

### **Tạo phí phạt mới** (`/admin/fines/create`)
1. **Chọn phiếu mượn:** Từ dropdown
2. **Thông tin phạt:**
   - Loại phạt
   - Số tiền phạt
   - Mô tả lý do phạt
   - Ngày hết hạn thanh toán
3. **Lưu:** Click "Lưu" để tạo phạt

### **Xử lý phí phạt:**
- **Đánh dấu đã thanh toán:** Click "Đã thanh toán"
- **Miễn phạt:** Click "Miễn phạt" (cần lý do)
- **Tạo phạt trả muộn tự động:** Từ menu "Tạo phạt trả muộn"

---

## 📅 **Hệ thống Đặt trước sách**

### **Danh sách đặt trước** (`/admin/reservations`)
- **Thông tin hiển thị:**
  - Tên sách, tác giả
  - Tên người đặt trước
  - Ngày đặt trước
  - Trạng thái (Chờ xác nhận/Đã xác nhận/Sẵn sàng/Hết hạn)
  - Độ ưu tiên
- **Lọc:** Theo trạng thái, khoảng thời gian

### **Xử lý đặt trước:**
1. **Xác nhận đặt trước:** Click "Xác nhận"
2. **Đánh dấu sẵn sàng:** Khi sách được trả về
3. **Hủy đặt trước:** Click "Hủy" (cần lý do)

### **Tạo đặt trước mới** (`/admin/reservations/create`)
1. Chọn sách và độc giả
2. Nhập ghi chú
3. Lưu đặt trước

---

## 🔍 **Tìm kiếm nâng cao**

### **Tìm kiếm sách** (`/admin/search/books`)
- **Tìm kiếm full-text:** Tên sách, tác giả, mô tả
- **Lọc nâng cao:**
  - Thể loại
  - Năm xuất bản (từ - đến)
  - Chỉ sách có sẵn
  - Điểm đánh giá tối thiểu
- **Sắp xếp:** Theo tên, tác giả, năm, điểm đánh giá, độ phổ biến

### **Tìm kiếm độc giả** (`/admin/search/readers`)
- **Tìm kiếm:** Tên, email, số thẻ, địa chỉ
- **Lọc:** Trạng thái, giới tính, độ tuổi
- **Sắp xếp:** Theo tên, email, ngày đăng ký

### **Tìm kiếm mượn sách** (`/admin/search/borrows`)
- **Tìm kiếm:** Tên sách, tên độc giả
- **Lọc:** Trạng thái, khoảng thời gian, sách quá hạn
- **Sắp xếp:** Theo ngày mượn, ngày hẹn trả

### **Tìm kiếm toàn cục:**
- Gõ từ khóa → Hiển thị kết quả từ tất cả modules
- Auto-complete suggestions
- Truy cập nhanh đến chi tiết

---

## 📊 **Báo cáo và Thống kê**

### **Báo cáo cơ bản** (`/admin/reports`)
- **Báo cáo mượn sách:** Theo thời gian, độc giả, sách
- **Báo cáo độc giả:** Thống kê theo giới tính, độ tuổi
- **Báo cáo sách:** Thống kê theo thể loại, năm xuất bản
- **Xuất Excel/PDF:** Click "Xuất báo cáo"

### **Báo cáo nâng cao** (`/admin/advanced-reports`)
- **Tạo template tùy chỉnh:**
  1. Chọn loại báo cáo
  2. Chọn cột hiển thị
  3. Thiết lập bộ lọc
  4. Cấu hình nhóm và sắp xếp
- **Generate báo cáo:** Từ template với filters
- **Export:** Excel, PDF với định dạng tùy chỉnh

---

## 📦 **Quản lý Kho**

### **Danh sách kho** (`/admin/inventory`)
- **Thông tin hiển thị:**
  - Tên sách, tác giả
  - Mã vạch
  - Vị trí trong kho
  - Tình trạng sách (Mới/Tốt/Trung bình/Cũ/Hỏng)
  - Trạng thái (Có sẵn/Đang mượn/Mất/Hỏng/Thanh lý)
- **Tìm kiếm:** Theo mã vạch, tên sách
- **Lọc:** Theo trạng thái, tình trạng, vị trí

### **Thêm sách vào kho** (`/admin/inventory/create`)
1. **Chọn sách:** Từ dropdown
2. **Mã vạch:** Tự động hoặc nhập thủ công
3. **Vị trí:** Kệ, tầng, vị trí cụ thể
4. **Tình trạng:** Mới/Tốt/Trung bình/Cũ/Hỏng
5. **Thông tin mua:** Giá mua, ngày mua (nếu có)
6. **Ghi chú:** Thông tin bổ sung

### **Quản lý giao dịch kho** (`/admin/inventory-transactions`)
- **Các loại giao dịch:**
  - **Nhập kho:** Thêm sách mới
  - **Xuất kho:** Cho mượn sách
  - **Chuyển kho:** Thay đổi vị trí
  - **Kiểm kê:** Cập nhật thông tin
  - **Thanh lý:** Xóa sách hỏng/mất
  - **Sửa chữa:** Cập nhật tình trạng
- **Theo dõi:** Người thực hiện, thời gian, lý do

### **Chuyển kho:**
1. Tìm sách cần chuyển
2. Click "Chuyển kho"
3. Nhập vị trí mới và lý do
4. Xác nhận chuyển kho

### **Sửa chữa sách:**
1. Tìm sách cần sửa chữa
2. Click "Sửa chữa"
3. Cập nhật tình trạng mới
4. Nhập lý do và ghi chú

### **Quét mã vạch:**
- Sử dụng máy quét mã vạch
- Nhập mã vạch thủ công
- Tìm kiếm nhanh thông tin sách

---

## 🔔 **Hệ thống Thông báo**

### **Thông báo trong hệ thống** (`/admin/notifications`)
- **Các loại thông báo:**
  - Nhắc nhở trả sách
  - Cảnh báo quá hạn
  - Sách đặt trước sẵn sàng
  - Phí phạt mới
- **Quản lý:** Đánh dấu đã đọc, xóa thông báo

### **Thông báo email:**
- Tự động gửi khi:
  - Sắp đến hạn trả sách
  - Sách quá hạn
  - Sách đặt trước sẵn sàng
  - Phí phạt mới

---

## 🎨 **Giao diện và Trải nghiệm**

### **Responsive Design:**
- Tương thích với desktop, tablet, mobile
- Menu sidebar có thể thu gọn
- Bảng dữ liệu responsive

### **Tính năng UX:**
- **Auto-complete:** Gợi ý khi nhập
- **Pagination:** Phân trang cho danh sách dài
- **Search & Filter:** Tìm kiếm và lọc nhanh
- **Breadcrumb:** Điều hướng rõ ràng
- **Loading states:** Hiển thị trạng thái tải

### **Màu sắc và Icon:**
- **Màu chủ đạo:** Xanh dương (#007bff)
- **Màu cảnh báo:** Đỏ (#dc3545)
- **Màu thành công:** Xanh lá (#28a745)
- **Icon:** Font Awesome 5

---

## ⚙️ **Cài đặt và Cấu hình**

### **Cài đặt hệ thống:**
1. **Clone repository**
2. **Cài đặt dependencies:** `composer install`
3. **Cấu hình database:** `.env`
4. **Chạy migrations:** `php artisan migrate`
5. **Seed dữ liệu:** `php artisan db:seed`
6. **Tạo storage link:** `php artisan storage:link`
7. **Chạy server:** `php artisan serve`

### **Cấu hình email:**
- Cập nhật SMTP settings trong `.env`
- Test gửi email từ admin panel

### **Cấu hình permissions:**
- Chạy `php artisan db:seed --class=RolePermissionSeeder`
- Phân quyền cho từng user

---

## 🚨 **Xử lý sự cố**

### **Lỗi thường gặp:**

#### **1. Không thể đăng nhập:**
- Kiểm tra email/password
- Kiểm tra tài khoản có bị khóa không
- Clear cache: `php artisan cache:clear`

#### **2. Không hiển thị ảnh sách:**
- Chạy: `php artisan storage:link`
- Kiểm tra quyền thư mục `storage/app/public`

#### **3. Lỗi database:**
- Kiểm tra kết nối database
- Chạy: `php artisan migrate:fresh --seed`

#### **4. Lỗi permission:**
- Chạy: `php artisan db:seed --class=RolePermissionSeeder`
- Kiểm tra user có role không

### **Log và Debug:**
- **Log file:** `storage/logs/laravel.log`
- **Debug mode:** `APP_DEBUG=true` trong `.env`
- **Clear cache:** `php artisan optimize:clear`

---

## 📞 **Hỗ trợ và Liên hệ**

### **Tài khoản hỗ trợ:**
- **Super Admin:** `admin@library.com`
- **Hotline:** 0123-456-789
- **Email:** support@library.com

### **Tài liệu bổ sung:**
- **API Documentation:** `API_DOCUMENTATION.md`
- **Database Schema:** `database/migrations/`
- **Code Documentation:** Inline comments

### **Cập nhật hệ thống:**
- **Version:** 1.0.0
- **Last Update:** 2024-01-15
- **Next Update:** Theo yêu cầu

---

## 🎯 **Tips và Best Practices**

### **Quản lý hiệu quả:**
1. **Thường xuyên backup database**
2. **Kiểm tra sách quá hạn hàng ngày**
3. **Cập nhật thông tin độc giả định kỳ**
4. **Theo dõi thống kê để ra quyết định**

### **Bảo mật:**
1. **Đổi password định kỳ**
2. **Không chia sẻ tài khoản**
3. **Logout khi không sử dụng**
4. **Báo cáo sự cố bảo mật ngay lập tức**

### **Performance:**
1. **Sử dụng pagination cho danh sách dài**
2. **Tối ưu hóa hình ảnh trước khi upload**
3. **Clear cache định kỳ**
4. **Monitor server resources**

---

**🎉 Chúc bạn sử dụng hệ thống hiệu quả!**

*Hệ thống được phát triển với Laravel 10, Bootstrap 5, và các công nghệ hiện đại khác.*



