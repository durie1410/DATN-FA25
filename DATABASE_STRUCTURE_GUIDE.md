# HƯỚNG DẪN CHI TIẾT VỀ CƠ SỞ DỮ LIỆU HỆ THỐNG QUẢN LÝ THƯ VIỆN

## TỔNG QUAN HỆ THỐNG

Hệ thống quản lý thư viện được xây dựng trên nền tảng Laravel với cơ sở dữ liệu MySQL, bao gồm các chức năng chính:
- Quản lý sách và tài liệu
- Quản lý người dùng (độc giả, thủ thư, quản trị viên)
- Quản lý mượn trả sách
- Quản lý đặt trước và phạt
- Hệ thống đánh giá và bình luận
- Quản lý kho và tồn kho
- Hệ thống mua sách điện tử
- Quản lý email marketing
- Hệ thống phân quyền và vai trò

## CẤU TRÚC CÁC BẢNG DỮ LIỆU

**Tổng số bảng: 46 bảng**

## PHÂN NHÓM THEO CHỨC NĂNG

### 📋 NHÓM 1: QUẢN LÝ NGƯỜI DÙNG VÀ PHÂN QUYỀN (8 bảng)
**Chức năng**: Quản lý tài khoản, vai trò và quyền hạn trong hệ thống

**Danh sách bảng**:
- `users` - Người dùng hệ thống
- `readers` - Độc giả thư viện  
- `librarians` - Nhân viên thư viện
- `permissions` - Quyền hạn
- `roles` - Vai trò
- `model_has_permissions` - Quyền của model
- `model_has_roles` - Vai trò của model
- `role_has_permissions` - Quyền của vai trò

#### 1.1. Bảng `users` - Người dùng hệ thống
**Mục đích**: Lưu trữ thông tin tài khoản của tất cả người dùng trong hệ thống

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của người dùng
- `name` (VARCHAR): Tên đầy đủ của người dùng
- `email` (VARCHAR, UNIQUE): Email đăng nhập (duy nhất)
- `email_verified_at` (TIMESTAMP): Thời gian xác thực email
- `password` (VARCHAR): Mật khẩu đã mã hóa
- `remember_token` (VARCHAR): Token ghi nhớ đăng nhập
- `role` (ENUM): Vai trò người dùng (admin, librarian, reader)
- `google_id` (VARCHAR): ID Google OAuth (nếu có)
- `google_avatar` (VARCHAR): Ảnh đại diện từ Google
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

**Mối quan hệ**:
- Liên kết với bảng `readers` qua `user_id`
- Liên kết với bảng `librarians` qua `user_id`
- Liên kết với bảng `borrows` qua `librarian_id`

#### 1.2. Bảng `readers` - Độc giả
**Mục đích**: Lưu trữ thông tin chi tiết của độc giả thư viện

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của độc giả
- `user_id` (BIGINT, FOREIGN KEY): Liên kết với bảng users
- `ho_ten` (VARCHAR): Họ và tên đầy đủ
- `email` (VARCHAR, UNIQUE): Email liên hệ
- `so_dien_thoai` (VARCHAR): Số điện thoại
- `ngay_sinh` (DATE): Ngày sinh
- `gioi_tinh` (ENUM): Giới tính (Nam, Nu, Khac)
- `dia_chi` (TEXT): Địa chỉ thường trú
- `so_the_doc_gia` (VARCHAR, UNIQUE): Số thẻ độc giả
- `ngay_cap_the` (DATE): Ngày cấp thẻ
- `ngay_het_han` (DATE): Ngày hết hạn thẻ
- `trang_thai` (ENUM): Trạng thái thẻ (Hoat dong, Tam khoa, Het han)
- `faculty_id` (BIGINT, FOREIGN KEY): Liên kết với khoa
- `department_id` (BIGINT, FOREIGN KEY): Liên kết với ngành
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

**Mối quan hệ**:
- Liên kết với bảng `users` qua `user_id`
- Liên kết với bảng `faculties` qua `faculty_id`
- Liên kết với bảng `departments` qua `department_id`
- Liên kết với bảng `borrows` qua `reader_id`

#### 1.3. Bảng `librarians` - Thủ thư
**Mục đích**: Lưu trữ thông tin chi tiết của nhân viên thư viện

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của thủ thư
- `user_id` (BIGINT, FOREIGN KEY): Liên kết với bảng users
- `ho_ten` (VARCHAR): Họ và tên đầy đủ
- `ma_thu_thu` (VARCHAR, UNIQUE): Mã số thủ thư
- `email` (VARCHAR): Email liên hệ
- `so_dien_thoai` (VARCHAR): Số điện thoại
- `ngay_sinh` (DATE): Ngày sinh
- `gioi_tinh` (ENUM): Giới tính (male, female, other)
- `dia_chi` (TEXT): Địa chỉ thường trú
- `chuc_vu` (VARCHAR): Chức vụ hiện tại
- `phong_ban` (VARCHAR): Phòng ban làm việc
- `ngay_vao_lam` (DATE): Ngày bắt đầu làm việc
- `ngay_het_han_hop_dong` (DATE): Ngày hết hạn hợp đồng
- `luong_co_ban` (DECIMAL): Lương cơ bản
- `trang_thai` (ENUM): Trạng thái làm việc (active, inactive)
- `anh_dai_dien` (VARCHAR): Ảnh đại diện
- `bang_cap` (TEXT): Thông tin bằng cấp
- `kinh_nghiem` (TEXT): Kinh nghiệm làm việc
- `ghi_chu` (TEXT): Ghi chú thêm
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 📚 NHÓM 2: QUẢN LÝ SÁCH VÀ TÀI LIỆU (7 bảng)
**Chức năng**: Quản lý thông tin sách, tác giả, nhà xuất bản và phân loại

**Danh sách bảng**:
- `categories` - Thể loại sách
- `books` - Sách trong thư viện
- `authors` - Tác giả
- `publishers` - Nhà xuất bản
- `faculties` - Khoa
- `departments` - Ngành
- `purchasable_books` - Sách điện tử có thể mua

#### 2.1. Bảng `categories` - Thể loại sách
**Mục đích**: Phân loại sách theo các thể loại khác nhau

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của thể loại
- `ten_the_loai` (VARCHAR): Tên thể loại sách
- `mo_ta` (TEXT): Mô tả chi tiết về thể loại
- `hinh_anh` (VARCHAR): Ảnh đại diện thể loại
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 2.2. Bảng `books` - Sách trong thư viện
**Mục đích**: Lưu trữ thông tin về các cuốn sách có trong thư viện

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của sách
- `ten_sach` (VARCHAR): Tên sách
- `category_id` (BIGINT, FOREIGN KEY): Liên kết với thể loại
- `tac_gia` (VARCHAR): Tác giả chính
- `nam_xuat_ban` (YEAR): Năm xuất bản
- `hinh_anh` (VARCHAR): Ảnh bìa sách
- `mo_ta` (TEXT): Mô tả nội dung sách
- `publisher_id` (BIGINT, FOREIGN KEY): Liên kết với nhà xuất bản
- `isbn` (VARCHAR): Mã ISBN
- `so_trang` (INTEGER): Số trang
- `ngon_ngu` (VARCHAR): Ngôn ngữ sách
- `gia_tri_sach` (DECIMAL): Giá trị sách
- `danh_gia_trung_binh` (DECIMAL): Điểm đánh giá trung bình
- `so_luot_danh_gia` (INTEGER): Số lượt đánh giá
- `so_luot_xem` (INTEGER): Số lượt xem
- `trang_thai` (ENUM): Trạng thái sách (active, inactive)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

**Mối quan hệ**:
- Liên kết với bảng `categories` qua `category_id`
- Liên kết với bảng `publishers` qua `publisher_id`
- Liên kết với bảng `borrows` qua `book_id`
- Liên kết với bảng `reviews` qua `book_id`

#### 2.3. Bảng `authors` - Tác giả
**Mục đích**: Quản lý thông tin về các tác giả

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của tác giả
- `ten_tac_gia` (VARCHAR): Tên đầy đủ của tác giả
- `email` (VARCHAR, UNIQUE): Email liên hệ
- `so_dien_thoai` (VARCHAR): Số điện thoại
- `dia_chi` (TEXT): Địa chỉ
- `ngay_sinh` (DATE): Ngày sinh
- `gioi_thieu` (TEXT): Tiểu sử tác giả
- `hinh_anh` (VARCHAR): Ảnh đại diện
- `trang_thai` (VARCHAR): Trạng thái (active, inactive)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 2.4. Bảng `publishers` - Nhà xuất bản
**Mục đích**: Quản lý thông tin về các nhà xuất bản

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của nhà xuất bản
- `ten_nha_xuat_ban` (VARCHAR): Tên nhà xuất bản
- `dia_chi` (TEXT): Địa chỉ trụ sở
- `so_dien_thoai` (VARCHAR): Số điện thoại liên hệ
- `email` (VARCHAR): Email liên hệ
- `website` (VARCHAR): Website chính thức
- `mo_ta` (TEXT): Mô tả về nhà xuất bản
- `ngay_thanh_lap` (DATE): Ngày thành lập
- `trang_thai` (ENUM): Trạng thái (active, inactive)
- `logo` (VARCHAR): Logo nhà xuất bản
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 🔄 NHÓM 3: QUẢN LÝ MƯỢN TRẢ SÁCH (3 bảng)
**Chức năng**: Quản lý việc mượn, trả và đặt trước sách

**Danh sách bảng**:
- `borrows` - Phiếu mượn sách
- `reservations` - Đặt trước sách
- `fines` - Phạt vi phạm

#### 3.1. Bảng `borrows` - Phiếu mượn sách
**Mục đích**: Quản lý các phiếu mượn sách của độc giả

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của phiếu mượn
- `reader_id` (BIGINT, FOREIGN KEY): Liên kết với độc giả
- `book_id` (BIGINT, FOREIGN KEY): Liên kết với sách
- `librarian_id` (BIGINT, FOREIGN KEY): Thủ thư cho mượn
- `ngay_muon` (DATE): Ngày mượn sách
- `ngay_hen_tra` (DATE): Ngày hẹn trả
- `ngay_tra_thuc_te` (DATE): Ngày trả thực tế
- `trang_thai` (ENUM): Trạng thái (Dang muon, Da tra, Qua han, Mat sach)
- `so_lan_gia_han` (INTEGER): Số lần gia hạn
- `ngay_gia_han_cuoi` (DATE): Ngày gia hạn cuối cùng
- `ghi_chu` (TEXT): Ghi chú thêm
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

**Mối quan hệ**:
- Liên kết với bảng `readers` qua `reader_id`
- Liên kết với bảng `books` qua `book_id`
- Liên kết với bảng `users` qua `librarian_id`
- Liên kết với bảng `fines` qua `borrow_id`

#### 3.2. Bảng `reservations` - Đặt trước sách
**Mục đích**: Quản lý việc đặt trước sách khi chưa có sẵn

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của đặt trước
- `book_id` (BIGINT, FOREIGN KEY): Liên kết với sách
- `reader_id` (BIGINT, FOREIGN KEY): Liên kết với độc giả
- `user_id` (BIGINT, FOREIGN KEY): Người đặt trước
- `status` (ENUM): Trạng thái (pending, confirmed, ready, cancelled, expired)
- `priority` (INTEGER): Độ ưu tiên trong hàng đợi
- `reservation_date` (DATE): Ngày đặt trước
- `expiry_date` (DATE): Ngày hết hạn đặt trước
- `ready_date` (DATE): Ngày sách sẵn sàng
- `pickup_date` (DATE): Ngày nhận sách
- `notes` (TEXT): Ghi chú
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

**Ràng buộc đặc biệt**:
- Mỗi user chỉ có thể đặt trước 1 lần/sách (unique constraint)

#### 3.3. Bảng `fines` - Phạt vi phạm
**Mục đích**: Quản lý các khoản phạt do vi phạm quy định thư viện

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của phạt
- `borrow_id` (BIGINT, FOREIGN KEY): Liên kết với phiếu mượn
- `reader_id` (BIGINT, FOREIGN KEY): Liên kết với độc giả
- `amount` (DECIMAL): Số tiền phạt
- `type` (ENUM): Loại phạt (late_return, damaged_book, lost_book, other)
- `description` (TEXT): Mô tả lý do phạt
- `status` (ENUM): Trạng thái (pending, paid, waived, cancelled)
- `due_date` (DATE): Ngày hết hạn thanh toán
- `paid_date` (DATE): Ngày thanh toán
- `notes` (TEXT): Ghi chú
- `created_by` (BIGINT, FOREIGN KEY): Người tạo phạt
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 📦 NHÓM 4: QUẢN LÝ KHO VÀ TỒN KHO (2 bảng)
**Chức năng**: Quản lý kho sách và theo dõi giao dịch kho

**Danh sách bảng**:
- `inventories` - Quản lý kho sách
- `inventory_transactions` - Giao dịch kho

#### 4.1. Bảng `inventories` - Quản lý kho sách
**Mục đích**: Quản lý từng cuốn sách cụ thể trong kho

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của sách trong kho
- `book_id` (BIGINT, FOREIGN KEY): Liên kết với thông tin sách
- `barcode` (VARCHAR, UNIQUE): Mã vạch sách
- `location` (VARCHAR): Vị trí trong kho (kệ, tầng, vị trí)
- `condition` (ENUM): Tình trạng sách (Moi, Tot, Trung binh, Cu, Hong)
- `status` (ENUM): Trạng thái (Co san, Dang muon, Mat, Hong, Thanh ly)
- `purchase_price` (DECIMAL): Giá mua
- `purchase_date` (DATE): Ngày mua
- `notes` (TEXT): Ghi chú
- `created_by` (BIGINT, FOREIGN KEY): Người tạo
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 4.2. Bảng `inventory_transactions` - Giao dịch kho
**Mục đích**: Theo dõi các giao dịch nhập/xuất kho

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của giao dịch
- `inventory_id` (BIGINT, FOREIGN KEY): Liên kết với sách trong kho
- `transaction_type` (ENUM): Loại giao dịch (in, out, transfer, adjustment)
- `quantity` (INTEGER): Số lượng
- `reference_type` (VARCHAR): Loại tham chiếu (borrow, return, purchase, etc.)
- `reference_id` (BIGINT): ID của tham chiếu
- `notes` (TEXT): Ghi chú
- `created_by` (BIGINT, FOREIGN KEY): Người thực hiện
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### ⭐ NHÓM 5: HỆ THỐNG ĐÁNH GIÁ VÀ TƯƠNG TÁC (4 bảng)
**Chức năng**: Quản lý đánh giá, bình luận và tương tác của người dùng

**Danh sách bảng**:
- `reviews` - Đánh giá sách
- `review_likes` - Thích đánh giá
- `review_reports` - Báo cáo đánh giá
- `comments` - Bình luận

#### 5.1. Bảng `reviews` - Đánh giá sách
**Mục đích**: Lưu trữ đánh giá và bình luận của độc giả về sách

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất của đánh giá
- `book_id` (BIGINT, FOREIGN KEY): Liên kết với sách
- `user_id` (BIGINT, FOREIGN KEY): Liên kết với người đánh giá
- `rating` (INTEGER): Điểm đánh giá (1-5 sao)
- `comment` (TEXT): Bình luận chi tiết
- `title` (VARCHAR): Tiêu đề đánh giá
- `is_verified` (BOOLEAN): Đã mượn sách chưa
- `status` (ENUM): Trạng thái (pending, approved, rejected)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

**Ràng buộc đặc biệt**:
- Mỗi user chỉ đánh giá 1 lần/sách (unique constraint)

#### 5.2. Bảng `review_likes` - Thích đánh giá
**Mục đích**: Quản lý lượt thích cho các đánh giá

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `review_id` (BIGINT, FOREIGN KEY): Liên kết với đánh giá
- `user_id` (BIGINT, FOREIGN KEY): Người thích
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 5.3. Bảng `review_reports` - Báo cáo đánh giá
**Mục đích**: Quản lý các báo cáo về đánh giá không phù hợp

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `review_id` (BIGINT, FOREIGN KEY): Liên kết với đánh giá
- `user_id` (BIGINT, FOREIGN KEY): Người báo cáo
- `reason` (ENUM): Lý do báo cáo (spam, inappropriate, fake, other)
- `description` (TEXT): Mô tả chi tiết
- `status` (ENUM): Trạng thái xử lý (pending, reviewed, resolved, dismissed)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 5.4. Bảng `comments` - Bình luận
**Mục đích**: Quản lý bình luận trên các bài viết/tin tức

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người bình luận
- `content` (TEXT): Nội dung bình luận
- `parent_id` (BIGINT, FOREIGN KEY): Bình luận cha (cho reply)
- `status` (ENUM): Trạng thái (pending, approved, rejected)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 💳 NHÓM 6: HỆ THỐNG MUA SÁCH ĐIỆN TỬ (5 bảng)
**Chức năng**: Quản lý giỏ hàng, đơn hàng và thanh toán sách điện tử

**Danh sách bảng**:
- `purchasable_books` - Sách điện tử có thể mua
- `carts` - Giỏ hàng
- `cart_items` - Chi tiết giỏ hàng
- `orders` - Đơn hàng
- `order_items` - Chi tiết đơn hàng

#### 6.1. Bảng `purchasable_books` - Sách điện tử có thể mua
**Mục đích**: Quản lý các sách điện tử có thể mua trực tuyến

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `ten_sach` (VARCHAR): Tên sách
- `tac_gia` (VARCHAR): Tác giả
- `mo_ta` (TEXT): Mô tả sách
- `hinh_anh` (VARCHAR): Ảnh bìa
- `gia` (DECIMAL): Giá bán
- `nha_xuat_ban` (VARCHAR): Nhà xuất bản
- `nam_xuat_ban` (INTEGER): Năm xuất bản
- `isbn` (VARCHAR): Mã ISBN
- `so_trang` (INTEGER): Số trang
- `ngon_ngu` (VARCHAR): Ngôn ngữ
- `dinh_dang` (VARCHAR): Định dạng file (PDF, EPUB, MOBI)
- `kich_thuoc_file` (INTEGER): Kích thước file (KB)
- `trang_thai` (VARCHAR): Trạng thái (active, inactive)
- `so_luong_ban` (INTEGER): Số lượng đã bán
- `danh_gia_trung_binh` (DECIMAL): Điểm đánh giá trung bình
- `so_luot_xem` (INTEGER): Số lượt xem
- `so_luong_ton` (INTEGER): Số lượng tồn kho
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 6.2. Bảng `carts` - Giỏ hàng
**Mục đích**: Quản lý giỏ hàng của người dùng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người dùng (có thể null cho guest)
- `session_id` (VARCHAR): Session ID cho guest cart
- `total_amount` (DECIMAL): Tổng tiền
- `total_items` (INTEGER): Tổng số sản phẩm
- `status` (ENUM): Trạng thái (active, abandoned, converted)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 6.3. Bảng `cart_items` - Chi tiết giỏ hàng
**Mục đích**: Lưu trữ các sản phẩm trong giỏ hàng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `cart_id` (BIGINT, FOREIGN KEY): Liên kết với giỏ hàng
- `purchasable_book_id` (BIGINT, FOREIGN KEY): Liên kết với sách điện tử
- `quantity` (INTEGER): Số lượng
- `price` (DECIMAL): Giá tại thời điểm thêm vào giỏ
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 6.4. Bảng `orders` - Đơn hàng
**Mục đích**: Quản lý các đơn hàng mua sách điện tử

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `order_number` (VARCHAR, UNIQUE): Mã đơn hàng
- `user_id` (BIGINT, FOREIGN KEY): Người mua
- `session_id` (VARCHAR): Session ID cho guest
- `customer_name` (VARCHAR): Tên khách hàng
- `customer_email` (VARCHAR): Email khách hàng
- `customer_phone` (VARCHAR): Số điện thoại
- `customer_address` (TEXT): Địa chỉ
- `subtotal` (DECIMAL): Tổng tiền hàng
- `tax_amount` (DECIMAL): Thuế
- `shipping_amount` (DECIMAL): Phí vận chuyển
- `total_amount` (DECIMAL): Tổng tiền thanh toán
- `status` (ENUM): Trạng thái đơn hàng (pending, processing, shipped, delivered, cancelled)
- `payment_status` (ENUM): Trạng thái thanh toán (pending, paid, failed, refunded)
- `payment_method` (VARCHAR): Phương thức thanh toán
- `notes` (TEXT): Ghi chú
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 6.5. Bảng `order_items` - Chi tiết đơn hàng
**Mục đích**: Lưu trữ chi tiết các sản phẩm trong đơn hàng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `order_id` (BIGINT, FOREIGN KEY): Liên kết với đơn hàng
- `purchasable_book_id` (BIGINT, FOREIGN KEY): Liên kết với sách điện tử
- `quantity` (INTEGER): Số lượng
- `price` (DECIMAL): Giá tại thời điểm mua
- `total_price` (DECIMAL): Tổng tiền cho sản phẩm này
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 🏛️ NHÓM 8: QUẢN LÝ TỔ CHỨC (2 bảng)
**Chức năng**: Quản lý cấu trúc tổ chức khoa và ngành

**Danh sách bảng**:
- `faculties` - Khoa
- `departments` - Ngành

#### 8.1. Bảng `faculties` - Khoa
**Mục đích**: Quản lý thông tin các khoa trong trường

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `ten_khoa` (VARCHAR): Tên khoa
- `ma_khoa` (VARCHAR, UNIQUE): Mã khoa
- `mo_ta` (TEXT): Mô tả về khoa
- `truong_khoa` (VARCHAR): Trưởng khoa
- `so_dien_thoai` (VARCHAR): Số điện thoại
- `email` (VARCHAR): Email liên hệ
- `dia_chi` (TEXT): Địa chỉ
- `website` (VARCHAR): Website
- `ngay_thanh_lap` (DATE): Ngày thành lập
- `trang_thai` (ENUM): Trạng thái (active, inactive)
- `logo` (VARCHAR): Logo khoa
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 7.2. Bảng `departments` - Ngành
**Mục đích**: Quản lý thông tin các ngành học

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `ten_nganh` (VARCHAR): Tên ngành
- `ma_nganh` (VARCHAR, UNIQUE): Mã ngành
- `faculty_id` (BIGINT, FOREIGN KEY): Liên kết với khoa
- `mo_ta` (TEXT): Mô tả về ngành
- `truong_nganh` (VARCHAR): Trưởng ngành
- `so_dien_thoai` (VARCHAR): Số điện thoại
- `email` (VARCHAR): Email liên hệ
- `dia_chi` (TEXT): Địa chỉ
- `website` (VARCHAR): Website
- `ngay_thanh_lap` (DATE): Ngày thành lập
- `trang_thai` (ENUM): Trạng thái (active, inactive)
- `logo` (VARCHAR): Logo ngành
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 📧 NHÓM 7: HỆ THỐNG THÔNG BÁO VÀ EMAIL MARKETING (6 bảng)
**Chức năng**: Quản lý thông báo hệ thống và chiến dịch email marketing

**Danh sách bảng**:
- `notifications` - Thông báo hệ thống
- `notification_templates` - Mẫu thông báo
- `notification_logs` - Log thông báo
- `email_campaigns` - Chiến dịch email
- `email_subscribers` - Người đăng ký email
- `email_logs` - Log email

#### 7.1. Bảng `notifications` - Thông báo hệ thống
**Mục đích**: Quản lý các thông báo trong hệ thống

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người nhận thông báo
- `title` (VARCHAR): Tiêu đề thông báo
- `message` (TEXT): Nội dung thông báo
- `type` (ENUM): Loại thông báo (info, warning, error, success)
- `is_read` (BOOLEAN): Đã đọc chưa
- `read_at` (TIMESTAMP): Thời gian đọc
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 8.2. Bảng `notification_templates` - Mẫu thông báo
**Mục đích**: Quản lý các mẫu thông báo có thể tái sử dụng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `name` (VARCHAR): Tên mẫu
- `subject` (VARCHAR): Tiêu đề
- `body` (TEXT): Nội dung mẫu
- `type` (ENUM): Loại mẫu (email, sms, push)
- `variables` (JSON): Các biến có thể thay thế
- `is_active` (BOOLEAN): Trạng thái hoạt động
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 8.3. Bảng `notification_logs` - Log thông báo
**Mục đích**: Ghi lại lịch sử gửi thông báo

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `notification_id` (BIGINT, FOREIGN KEY): Liên kết với thông báo
- `user_id` (BIGINT, FOREIGN KEY): Người nhận
- `status` (ENUM): Trạng thái gửi (sent, failed, pending)
- `sent_at` (TIMESTAMP): Thời gian gửi
- `error_message` (TEXT): Thông báo lỗi (nếu có)
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 8.4. Bảng `email_campaigns` - Chiến dịch email marketing
**Mục đích**: Quản lý các chiến dịch email marketing

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `name` (VARCHAR): Tên chiến dịch
- `subject` (VARCHAR): Tiêu đề email
- `content` (TEXT): Nội dung email
- `target_audience` (ENUM): Đối tượng mục tiêu (all, readers, librarians, specific)
- `status` (ENUM): Trạng thái (draft, scheduled, sending, sent, cancelled)
- `scheduled_at` (TIMESTAMP): Thời gian lên lịch gửi
- `sent_at` (TIMESTAMP): Thời gian gửi thực tế
- `total_recipients` (INTEGER): Tổng số người nhận
- `sent_count` (INTEGER): Số email đã gửi
- `opened_count` (INTEGER): Số email đã mở
- `clicked_count` (INTEGER): Số lượt click
- `created_by` (BIGINT, FOREIGN KEY): Người tạo
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 8.5. Bảng `email_subscribers` - Người đăng ký nhận email
**Mục đích**: Quản lý danh sách người đăng ký nhận email marketing

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `email` (VARCHAR, UNIQUE): Email đăng ký
- `name` (VARCHAR): Tên người đăng ký
- `status` (ENUM): Trạng thái (active, unsubscribed, bounced)
- `subscribed_at` (TIMESTAMP): Thời gian đăng ký
- `unsubscribed_at` (TIMESTAMP): Thời gian hủy đăng ký
- `source` (VARCHAR): Nguồn đăng ký
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 8.6. Bảng `email_logs` - Log email
**Mục đích**: Ghi lại lịch sử gửi email

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `campaign_id` (BIGINT, FOREIGN KEY): Liên kết với chiến dịch
- `subscriber_id` (BIGINT, FOREIGN KEY): Liên kết với người đăng ký
- `email` (VARCHAR): Email người nhận
- `status` (ENUM): Trạng thái (sent, delivered, opened, clicked, bounced, failed)
- `sent_at` (TIMESTAMP): Thời gian gửi
- `delivered_at` (TIMESTAMP): Thời gian gửi thành công
- `opened_at` (TIMESTAMP): Thời gian mở email
- `clicked_at` (TIMESTAMP): Thời gian click
- `error_message` (TEXT): Thông báo lỗi
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### 🔧 NHÓM 9: HỆ THỐNG HỖ TRỢ VÀ LOG (8 bảng)
**Chức năng**: Quản lý các tính năng hỗ trợ, log và báo cáo

**Danh sách bảng**:
- `favorites` - Yêu thích
- `wishlists` - Danh sách mong muốn
- `wishlist_items` - Chi tiết danh sách mong muốn
- `violations` - Vi phạm
- `search_logs` - Log tìm kiếm
- `audit_logs` - Log kiểm toán
- `report_templates` - Mẫu báo cáo
- `backups` - Sao lưu dữ liệu

#### 9.1. Bảng `favorites` - Yêu thích
**Mục đích**: Quản lý danh sách sách yêu thích của người dùng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người dùng
- `book_id` (BIGINT, FOREIGN KEY): Sách yêu thích
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 9.2. Bảng `wishlists` - Danh sách mong muốn
**Mục đích**: Quản lý danh sách sách mong muốn của người dùng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người dùng
- `name` (VARCHAR): Tên danh sách
- `description` (TEXT): Mô tả
- `is_public` (BOOLEAN): Công khai hay không
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 10.3. Bảng `wishlist_items` - Chi tiết danh sách mong muốn
**Mục đích**: Lưu trữ các sách trong danh sách mong muốn

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `wishlist_id` (BIGINT, FOREIGN KEY): Liên kết với danh sách
- `book_id` (BIGINT, FOREIGN KEY): Liên kết với sách
- `notes` (TEXT): Ghi chú
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 10.4. Bảng `violations` - Vi phạm
**Mục đích**: Quản lý các vi phạm của độc giả

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `reader_id` (BIGINT, FOREIGN KEY): Độc giả vi phạm
- `type` (ENUM): Loại vi phạm (late_return, damaged_book, lost_book, noise, other)
- `description` (TEXT): Mô tả vi phạm
- `penalty` (DECIMAL): Mức phạt
- `status` (ENUM): Trạng thái (pending, resolved, waived)
- `reported_by` (BIGINT, FOREIGN KEY): Người báo cáo
- `resolved_by` (BIGINT, FOREIGN KEY): Người xử lý
- `resolved_at` (TIMESTAMP): Thời gian xử lý
- `notes` (TEXT): Ghi chú
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 10.5. Bảng `search_logs` - Log tìm kiếm
**Mục đích**: Ghi lại lịch sử tìm kiếm của người dùng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người tìm kiếm
- `search_term` (VARCHAR): Từ khóa tìm kiếm
- `search_type` (ENUM): Loại tìm kiếm (book, author, category)
- `results_count` (INTEGER): Số kết quả tìm được
- `ip_address` (VARCHAR): Địa chỉ IP
- `user_agent` (TEXT): User agent
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 10.6. Bảng `audit_logs` - Log kiểm toán
**Mục đích**: Ghi lại các hoạt động quan trọng trong hệ thống

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `user_id` (BIGINT, FOREIGN KEY): Người thực hiện
- `action` (VARCHAR): Hành động thực hiện
- `model_type` (VARCHAR): Loại model
- `model_id` (BIGINT): ID của model
- `old_values` (JSON): Giá trị cũ
- `new_values` (JSON): Giá trị mới
- `ip_address` (VARCHAR): Địa chỉ IP
- `user_agent` (TEXT): User agent
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 10.7. Bảng `report_templates` - Mẫu báo cáo
**Mục đích**: Quản lý các mẫu báo cáo có thể tái sử dụng

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `name` (VARCHAR): Tên mẫu báo cáo
- `description` (TEXT): Mô tả
- `template_type` (ENUM): Loại báo cáo (borrow, return, fine, inventory, etc.)
- `query` (TEXT): Câu truy vấn SQL
- `parameters` (JSON): Các tham số
- `is_active` (BOOLEAN): Trạng thái hoạt động
- `created_by` (BIGINT, FOREIGN KEY): Người tạo
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

#### 10.8. Bảng `backups` - Sao lưu dữ liệu
**Mục đích**: Quản lý các bản sao lưu dữ liệu

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `filename` (VARCHAR): Tên file sao lưu
- `file_path` (VARCHAR): Đường dẫn file
- `file_size` (BIGINT): Kích thước file
- `backup_type` (ENUM): Loại sao lưu (full, incremental, differential)
- `status` (ENUM): Trạng thái (in_progress, completed, failed)
- `created_by` (BIGINT, FOREIGN KEY): Người tạo
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

### ⚙️ NHÓM 10: BẢNG HỆ THỐNG LARAVEL (4 bảng)
**Chức năng**: Các bảng mặc định của Laravel framework

**Danh sách bảng**:
- `migrations` - Migration Laravel
- `password_resets` - Reset mật khẩu
- `failed_jobs` - Job thất bại
- `personal_access_tokens` - Token truy cập cá nhân

#### 10.1. Bảng `migrations` - Migration Laravel
**Mục đích**: Theo dõi các migration đã chạy

**Cấu trúc bảng**:
- `id` (INTEGER, PRIMARY KEY): ID duy nhất
- `migration` (VARCHAR): Tên file migration
- `batch` (INTEGER): Số batch chạy migration

#### 10.10. Bảng `password_resets` - Reset mật khẩu
**Mục đích**: Lưu trữ token reset mật khẩu

**Cấu trúc bảng**:
- `email` (VARCHAR, PRIMARY KEY): Email người dùng
- `token` (VARCHAR): Token reset
- `created_at` (TIMESTAMP): Thời gian tạo

#### 10.11. Bảng `failed_jobs` - Job thất bại
**Mục đích**: Lưu trữ các job queue thất bại

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `uuid` (VARCHAR, UNIQUE): UUID của job
- `connection` (TEXT): Connection queue
- `queue` (TEXT): Tên queue
- `payload` (LONGTEXT): Dữ liệu job
- `exception` (LONGTEXT): Thông tin lỗi
- `failed_at` (TIMESTAMP): Thời gian thất bại

#### 10.12. Bảng `personal_access_tokens` - Token truy cập cá nhân
**Mục đích**: Quản lý token API cho Laravel Sanctum

**Cấu trúc bảng**:
- `id` (BIGINT, PRIMARY KEY): ID duy nhất
- `tokenable_type` (VARCHAR): Loại model
- `tokenable_id` (BIGINT): ID của model
- `name` (VARCHAR): Tên token
- `token` (VARCHAR, UNIQUE): Token hash
- `abilities` (TEXT): Quyền hạn
- `last_used_at` (TIMESTAMP): Lần sử dụng cuối
- `expires_at` (TIMESTAMP): Thời gian hết hạn
- `created_at`, `updated_at` (TIMESTAMP): Thời gian tạo và cập nhật

## TỔNG KẾT PHÂN NHÓM CHỨC NĂNG

Hệ thống quản lý thư viện được tổ chức thành **10 nhóm chức năng** với **46 bảng**:

### 📊 BẢNG TỔNG KẾT THEO NHÓM

| Nhóm | Số bảng | Chức năng chính | Các bảng quan trọng |
|------|---------|-----------------|-------------------|
| **📋 NHÓM 1** | 8 bảng | Quản lý người dùng và phân quyền | `users`, `readers`, `librarians`, `permissions`, `roles` |
| **📚 NHÓM 2** | 7 bảng | Quản lý sách và tài liệu | `books`, `categories`, `authors`, `publishers` |
| **🔄 NHÓM 3** | 3 bảng | Quản lý mượn trả sách | `borrows`, `reservations`, `fines` |
| **📦 NHÓM 4** | 2 bảng | Quản lý kho và tồn kho | `inventories`, `inventory_transactions` |
| **⭐ NHÓM 5** | 4 bảng | Hệ thống đánh giá và tương tác | `reviews`, `review_likes`, `comments` |
| **💳 NHÓM 6** | 5 bảng | Hệ thống mua sách điện tử | `carts`, `orders`, `purchasable_books` |
| **📧 NHÓM 7** | 6 bảng | Thông báo và email marketing | `notifications`, `email_campaigns` |
| **🏛️ NHÓM 8** | 2 bảng | Quản lý tổ chức | `faculties`, `departments` |
| **🔧 NHÓM 9** | 8 bảng | Hệ thống hỗ trợ và log | `favorites`, `violations`, `audit_logs` |
| **⚙️ NHÓM 10** | 4 bảng | Bảng hệ thống Laravel | `migrations`, `failed_jobs` |

### 🎯 LỢI ÍCH CỦA VIỆC PHÂN NHÓM

1. **Dễ quản lý**: Mỗi nhóm có chức năng rõ ràng, dễ bảo trì
2. **Phát triển độc lập**: Có thể phát triển từng nhóm riêng biệt
3. **Bảo mật**: Phân quyền theo từng nhóm chức năng
4. **Mở rộng**: Dễ dàng thêm tính năng mới vào nhóm phù hợp
5. **Tối ưu**: Có thể tối ưu hóa từng nhóm riêng biệt

## QUAN HỆ GIỮA CÁC BẢNG

### 1. Quan hệ chính (Primary Relationships)

**Users ↔ Readers**: Một người dùng có thể có một hồ sơ độc giả
**Users ↔ Librarians**: Một người dùng có thể có một hồ sơ thủ thư
**Books ↔ Categories**: Một sách thuộc một thể loại
**Books ↔ Publishers**: Một sách thuộc một nhà xuất bản
**Readers ↔ Borrows**: Một độc giả có thể có nhiều phiếu mượn
**Books ↔ Borrows**: Một sách có thể được mượn nhiều lần
**Borrows ↔ Fines**: Một phiếu mượn có thể có nhiều phạt

### 2. Quan hệ phức tạp (Complex Relationships)

**Faculties ↔ Departments**: Một khoa có nhiều ngành
**Readers ↔ Faculties/Departments**: Độc giả thuộc về một khoa và ngành
**Books ↔ Reviews**: Một sách có nhiều đánh giá
**Users ↔ Reviews**: Một người dùng có thể đánh giá nhiều sách
**Books ↔ Inventories**: Một sách có nhiều bản trong kho
**Orders ↔ Order Items**: Một đơn hàng có nhiều sản phẩm

### 3. Quan hệ tự tham chiếu (Self-referencing)

**Comments**: Bình luận có thể reply cho bình luận khác
**Categories**: Thể loại có thể có thể loại con

## CÁC RÀNG BUỘC VÀ QUY TẮC NGHIỆP VỤ

### 1. Ràng buộc duy nhất (Unique Constraints)
- Email trong bảng users phải duy nhất
- Số thẻ độc giả phải duy nhất
- Mã vạch sách trong kho phải duy nhất
- Mỗi user chỉ đánh giá một lần cho một sách
- Mỗi user chỉ đặt trước một lần cho một sách

### 2. Ràng buộc khóa ngoại (Foreign Key Constraints)
- Tất cả các khóa ngoại đều có ràng buộc cascade hoặc set null
- Khi xóa user, các bản ghi liên quan sẽ được xử lý phù hợp
- Khi xóa sách, các phiếu mượn và đánh giá liên quan sẽ bị xóa

### 3. Quy tắc nghiệp vụ (Business Rules)
- Độc giả chỉ có thể mượn tối đa 5 cuốn sách cùng lúc
- Thời gian mượn sách tối đa 30 ngày
- Có thể gia hạn tối đa 2 lần
- Phạt trễ hạn: 5,000 VND/ngày
- Phạt mất sách: 150% giá trị sách
- Độc giả có phạt chưa thanh toán không được mượn sách mới

## CHIẾN LƯỢC SAO LƯU VÀ BẢO MẬT

### 1. Sao lưu dữ liệu
- Sao lưu đầy đủ hàng ngày vào 2:00 AM
- Sao lưu tăng dần mỗi 6 giờ
- Lưu trữ sao lưu trong 30 ngày
- Test restore định kỳ hàng tháng

### 2. Bảo mật dữ liệu
- Mã hóa mật khẩu bằng bcrypt
- Sử dụng HTTPS cho tất cả giao tiếp
- Log tất cả hoạt động quan trọng
- Phân quyền chi tiết theo vai trò
- Backup mã hóa và lưu trữ an toàn

## TỐI ƯU HÓA HIỆU SUẤT

### 1. Indexing
- Index trên các trường thường xuyên tìm kiếm
- Composite index cho các truy vấn phức tạp
- Index trên foreign keys
- Index trên các trường datetime

### 2. Query Optimization
- Sử dụng eager loading để tránh N+1 queries
- Cache các truy vấn thường xuyên
- Pagination cho danh sách lớn
- Sử dụng database views cho báo cáo phức tạp

## KẾT LUẬN

Hệ thống quản lý thư viện được thiết kế với cấu trúc database toàn diện và có tổ chức:

### 🏗️ **CẤU TRÚC TỔNG QUAN**
- **46 bảng** được phân thành **10 nhóm chức năng** rõ ràng
- **Quan hệ chặt chẽ** giữa các bảng trong cùng nhóm và khác nhóm
- **Ràng buộc đầy đủ** đảm bảo tính toàn vẹn dữ liệu
- **Bảo mật cao** với hệ thống phân quyền Spatie Permission

### 🎯 **ĐIỂM MẠNH CỦA THIẾT KẾ**

1. **Modular Design**: Mỗi nhóm chức năng độc lập, dễ bảo trì
2. **Scalable**: Dễ dàng mở rộng thêm tính năng mới
3. **Secure**: Phân quyền chi tiết từng chức năng
4. **User-friendly**: Hỗ trợ đầy đủ các tính năng hiện đại
5. **Professional**: Tuân thủ chuẩn Laravel và best practices

### 📈 **KHẢ NĂNG MỞ RỘNG**

- **API Integration**: Sẵn sàng tích hợp với các hệ thống khác
- **Mobile Support**: Hỗ trợ ứng dụng di động
- **Analytics**: Có sẵn hệ thống log và báo cáo
- **Multi-language**: Có thể mở rộng đa ngôn ngữ
- **Cloud Ready**: Sẵn sàng triển khai trên cloud

Cấu trúc này đảm bảo hệ thống có thể hoạt động ổn định, bảo mật và dễ dàng bảo trì trong tương lai.
