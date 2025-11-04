# 📚 Hướng dẫn sử dụng các chức năng quản lý độc giả

## 🎯 **Tổng quan các chức năng mới**

Hệ thống quản lý độc giả đã được nâng cấp với nhiều tính năng mạnh mẽ:

### ✅ **Các chức năng chính:**
1. **Xem chi tiết độc giả** - Thông tin đầy đủ và lịch sử hoạt động
2. **Tìm kiếm và lọc nâng cao** - Tìm kiếm theo nhiều tiêu chí
3. **Thao tác hàng loạt** - Xử lý nhiều độc giả cùng lúc
4. **Gia hạn thẻ** - Tự động gia hạn thẻ độc giả
5. **Tạm khóa/Kích hoạt** - Quản lý trạng thái độc giả
6. **Xuất Excel** - Xuất danh sách ra file Excel
7. **In danh sách** - In danh sách độc giả
8. **Thống kê** - Báo cáo thống kê chi tiết

---

## 🔍 **1. Tìm kiếm và lọc nâng cao**

### **Tìm kiếm theo từ khóa:**
- **Tên độc giả**: Nhập tên hoặc một phần tên
- **Email**: Tìm theo địa chỉ email
- **Số thẻ độc giả**: Tìm theo mã thẻ (VD: DG_001)
- **Số điện thoại**: Tìm theo số điện thoại

### **Lọc theo tiêu chí:**
- **Trạng thái**: Hoạt động, Tạm khóa, Hết hạn
- **Giới tính**: Nam, Nữ, Khác
- **Năm sinh**: Lọc theo năm sinh cụ thể

### **Sắp xếp:**
- **Ngày tạo**: Mới nhất trước
- **Tên**: A-Z hoặc Z-A
- **Ngày hết hạn**: Sắp hết hạn trước

---

## 👥 **2. Thao tác hàng loạt**

### **Cách sử dụng:**
1. **Chọn độc giả**: Tick vào checkbox của các độc giả muốn xử lý
2. **Chọn hành động**: 
   - **Kích hoạt**: Mở khóa các độc giả bị tạm khóa
   - **Tạm khóa**: Khóa tạm thời các độc giả
   - **Xóa**: Xóa vĩnh viễn (có kiểm tra ràng buộc)
3. **Thực hiện**: Nhấn nút "Thực hiện"

### **Lưu ý khi xóa hàng loạt:**
- ❌ Không thể xóa độc giả đang có sách mượn
- ❌ Không thể xóa độc giả có phạt chưa thanh toán
- ✅ Hệ thống sẽ kiểm tra tự động trước khi xóa

---

## 🔐 **3. Quản lý trạng thái độc giả**

### **Các trạng thái:**
- **🟢 Hoạt động**: Độc giả có thể mượn sách bình thường
- **🟡 Tạm khóa**: Tạm thời không cho mượn sách
- **🔴 Hết hạn**: Thẻ độc giả đã hết hạn

### **Thao tác:**
- **Tạm khóa**: Nhấn nút 🔒 trên từng độc giả
- **Kích hoạt**: Nhấn nút 🔓 để mở khóa
- **Gia hạn thẻ**: Nhấn nút 📅 để gia hạn thêm 1 năm

---

## 📊 **4. Xem chi tiết độc giả**

### **Thông tin hiển thị:**
- **Thông tin cơ bản**: Tên, email, SĐT, địa chỉ, ngày sinh
- **Thông tin thẻ**: Mã thẻ, ngày cấp, ngày hết hạn
- **Thống kê hoạt động**:
  - Tổng lượt mượn
  - Sách đang mượn
  - Phạt chưa thanh toán
  - Đặt chỗ đang chờ

### **Lịch sử hoạt động:**
- **Lịch sử mượn sách**: Tất cả lượt mượn/trả
- **Lịch sử phạt**: Các khoản phạt và trạng thái thanh toán

---

## 📈 **5. Thống kê độc giả**

### **Thống kê tổng quan:**
- Tổng số độc giả
- Số độc giả hoạt động
- Số độc giả tạm khóa
- Số độc giả hết hạn

### **Thống kê chi tiết:**
- **Theo giới tính**: Nam/Nữ
- **Theo trạng thái**: Biểu đồ tròn
- **Theo năm sinh**: Biểu đồ cột
- **Top 10 độc giả tích cực**: Số lượt mượn nhiều nhất
- **Độc giả có phạt**: Danh sách có phạt chưa thanh toán

---

## 📤 **6. Xuất Excel**

### **Tính năng:**
- Xuất danh sách độc giả ra file Excel
- Áp dụng các bộ lọc hiện tại
- Định dạng đẹp với header và style

### **Cách sử dụng:**
1. Áp dụng bộ lọc mong muốn
2. Nhấn nút "Xuất Excel"
3. File sẽ tự động tải về

### **Thông tin xuất:**
- Mã độc giả, Họ tên, Email
- Số điện thoại, Giới tính, Ngày sinh
- Trạng thái, Ngày hết hạn, Ngày tạo

---

## 🖨️ **7. In danh sách**

### **Tính năng:**
- In danh sách độc giả ra giấy
- Định dạng chuyên nghiệp
- Áp dụng các bộ lọc hiện tại

### **Cách sử dụng:**
1. Áp dụng bộ lọc mong muốn
2. Nhấn nút "In danh sách"
3. Cửa sổ mới mở ra với bản in
4. Nhấn Ctrl+P để in

### **Thông tin in:**
- Header với tên thư viện
- Thông tin ngày in và tổng số độc giả
- Bảng danh sách chi tiết
- Footer với thông tin hệ thống

---

## ⚠️ **Lưu ý quan trọng**

### **Bảo mật:**
- Chỉ Admin và Staff mới có quyền truy cập
- Staff không thể xóa độc giả
- Tất cả thao tác đều được ghi log

### **Ràng buộc dữ liệu:**
- Không thể xóa độc giả đang mượn sách
- Không thể xóa độc giả có phạt chưa thanh toán
- Email và số thẻ độc giả phải duy nhất

### **Hiệu suất:**
- Phân trang tự động (15 độc giả/trang)
- Tìm kiếm được tối ưu hóa
- Cache dữ liệu thống kê

---

## 🚀 **Cách sử dụng nhanh**

### **Thêm độc giả mới:**
1. Nhấn "Thêm mới"
2. Điền thông tin (mã thẻ tự động tạo nếu để trống)
3. Chọn trạng thái
4. Lưu

### **Tìm độc giả:**
1. Nhập từ khóa vào ô tìm kiếm
2. Chọn bộ lọc nếu cần
3. Nhấn nút tìm kiếm

### **Xử lý hàng loạt:**
1. Tick chọn các độc giả
2. Chọn hành động từ dropdown
3. Nhấn "Thực hiện"
4. Xác nhận

### **Xem thống kê:**
1. Nhấn nút "Thống kê"
2. Xem các biểu đồ và số liệu
3. Phân tích xu hướng

---

## 🎉 **Kết luận**

Hệ thống quản lý độc giả mới cung cấp đầy đủ các công cụ cần thiết để:
- ✅ Quản lý độc giả hiệu quả
- ✅ Tìm kiếm và lọc nhanh chóng  
- ✅ Xử lý hàng loạt tiết kiệm thời gian
- ✅ Thống kê và báo cáo chi tiết
- ✅ Xuất dữ liệu và in ấn

**Hệ thống đã sẵn sàng để sử dụng!** 🚀

