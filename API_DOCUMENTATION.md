# 📚 API Documentation - Hệ thống Quản lý Thư viện

## 🔗 Base URL
```
http://localhost:8000/api
```

## 🔐 Authentication
Hệ thống sử dụng **Laravel Sanctum** để xác thực API.

### Đăng nhập để lấy token:
```http
POST /api/login
Content-Type: application/json

{
    "email": "user@library.com",
    "password": "123456"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Đăng nhập thành công",
    "data": {
        "user": {
            "id": 1,
            "name": "User Name",
            "email": "user@library.com",
            "role": "user"
        },
        "token": "1|abc123def456..."
    }
}
```

### Sử dụng token trong header:
```http
Authorization: Bearer 1|abc123def456...
```

---

## 📖 **Books API**

### 1. Lấy danh sách sách (Public)
```http
GET /api/books
```

**Query Parameters:**
- `page` (int): Trang (default: 1)
- `per_page` (int): Số sách/trang (default: 20)
- `search` (string): Tìm kiếm theo tên sách, tác giả
- `category_id` (int): Lọc theo thể loại
- `year_from` (int): Năm xuất bản từ
- `year_to` (int): Năm xuất bản đến
- `sort_by` (string): Sắp xếp (title, author, year, rating, popularity)

**Response:**
```json
{
    "status": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "ten_sach": "Lập trình PHP",
                "tac_gia": "Nguyễn Văn A",
                "nam_xuat_ban": 2023,
                "hinh_anh": "books/book1.jpg",
                "mo_ta": "Sách học PHP cơ bản",
                "category": {
                    "id": 1,
                    "ten_the_loai": "Lập trình"
                },
                "average_rating": 4.5,
                "reviews_count": 10,
                "total_copies": 3,
                "available_copies": 2,
                "borrowed_copies": 1
            }
        ],
        "total": 50,
        "per_page": 20,
        "last_page": 3
    }
}
```

### 2. Lấy chi tiết sách (Public)
```http
GET /api/books/{id}
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "ten_sach": "Lập trình PHP",
        "tac_gia": "Nguyễn Văn A",
        "nam_xuat_ban": 2023,
        "hinh_anh": "books/book1.jpg",
        "mo_ta": "Sách học PHP cơ bản",
        "category": {
            "id": 1,
            "ten_the_loai": "Lập trình"
        },
        "reviews": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Sách rất hay!",
                "user": {
                    "name": "Nguyễn Văn B"
                },
                "created_at": "2024-01-15T10:30:00Z"
            }
        ],
        "inventories": [
            {
                "id": 1,
                "barcode": "BK000001",
                "location": "Kệ A1",
                "condition": "Moi",
                "status": "Co san"
            }
        ]
    }
}
```

### 3. Tạo sách mới (Admin)
```http
POST /api/books
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "ten_sach": "Lập trình Laravel",
    "category_id": 1,
    "tac_gia": "Nguyễn Văn C",
    "nam_xuat_ban": 2024,
    "hinh_anh": [file],
    "mo_ta": "Sách học Laravel framework"
}
```

### 4. Cập nhật sách (Admin)
```http
PUT /api/books/{id}
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

### 5. Xóa sách (Admin)
```http
DELETE /api/books/{id}
Authorization: Bearer {token}
```

---

## 📚 **Categories API**

### 1. Lấy danh sách thể loại (Public)
```http
GET /api/categories
```

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "ten_the_loai": "Lập trình",
            "books_count": 15
        },
        {
            "id": 2,
            "ten_the_loai": "Khoa học",
            "books_count": 8
        }
    ]
}
```

### 2. Tạo thể loại mới (Admin)
```http
POST /api/categories
Authorization: Bearer {token}
Content-Type: application/json

{
    "ten_the_loai": "Thể loại mới"
}
```

---

## 👥 **Readers API**

### 1. Lấy danh sách độc giả (Admin)
```http
GET /api/readers
Authorization: Bearer {token}
```

**Query Parameters:**
- `page`, `per_page`, `search`, `status`, `gender`

### 2. Tạo độc giả mới (Admin)
```http
POST /api/readers
Authorization: Bearer {token}
Content-Type: application/json

{
    "ho_ten": "Nguyễn Văn D",
    "email": "reader@example.com",
    "so_dien_thoai": "0123456789",
    "so_the_doc_gia": "RD001",
    "gioi_tinh": "Nam",
    "ngay_sinh": "1990-01-01",
    "dia_chi": "123 Đường ABC",
    "ngay_cap_the": "2024-01-01",
    "ngay_het_han": "2025-01-01"
}
```

---

## 📖 **Borrows API**

### 1. Lấy danh sách mượn sách (Admin)
```http
GET /api/borrows
Authorization: Bearer {token}
```

### 2. Tạo mượn sách mới (Admin)
```http
POST /api/borrows
Authorization: Bearer {token}
Content-Type: application/json

{
    "reader_id": 1,
    "book_id": 1,
    "ngay_muon": "2024-01-15",
    "ngay_hen_tra": "2024-01-29",
    "ghi_chu": "Mượn sách học tập"
}
```

### 3. Trả sách (Admin)
```http
POST /api/borrows/{id}/return
Authorization: Bearer {token}
Content-Type: application/json

{
    "ngay_tra_thuc_te": "2024-01-25",
    "ghi_chu": "Trả sách đúng hạn"
}
```

---

## ⭐ **Reviews API**

### 1. Lấy đánh giá sách (Public)
```http
GET /api/books/{book_id}/reviews
```

### 2. Tạo đánh giá mới (User)
```http
POST /api/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
    "book_id": 1,
    "rating": 5,
    "comment": "Sách rất hay và bổ ích!"
}
```

### 3. Cập nhật đánh giá (User)
```http
PUT /api/reviews/{id}
Authorization: Bearer {token}
```

### 4. Xóa đánh giá (User/Admin)
```http
DELETE /api/reviews/{id}
Authorization: Bearer {token}
```

---

## 💰 **Fines API**

### 1. Lấy danh sách phạt (Admin)
```http
GET /api/fines
Authorization: Bearer {token}
```

### 2. Tạo phạt mới (Admin)
```http
POST /api/fines
Authorization: Bearer {token}
Content-Type: application/json

{
    "borrow_id": 1,
    "reader_id": 1,
    "amount": 50000,
    "type": "late_return",
    "description": "Trả sách muộn 3 ngày",
    "due_date": "2024-02-01"
}
```

### 3. Đánh dấu đã thanh toán (Admin)
```http
POST /api/fines/{id}/mark-paid
Authorization: Bearer {token}
```

---

## 📅 **Reservations API**

### 1. Lấy danh sách đặt trước (User/Admin)
```http
GET /api/reservations
Authorization: Bearer {token}
```

### 2. Tạo đặt trước mới (User)
```http
POST /api/reservations
Authorization: Bearer {token}
Content-Type: application/json

{
    "book_id": 1,
    "reader_id": 1,
    "notes": "Cần sách để học tập"
}
```

### 3. Xác nhận đặt trước (Admin)
```http
POST /api/reservations/{id}/confirm
Authorization: Bearer {token}
```

---

## 🔍 **Advanced Search API**

### 1. Tìm kiếm toàn cục
```http
GET /api/search/global?q={query}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "type": "book",
            "title": "Lập trình PHP",
            "subtitle": "Nguyễn Văn A",
            "url": "/admin/books/1",
            "icon": "fas fa-book"
        },
        {
            "type": "reader",
            "title": "Nguyễn Văn B",
            "subtitle": "reader@example.com",
            "url": "/admin/readers/1",
            "icon": "fas fa-user"
        }
    ]
}
```

### 2. Gợi ý tìm kiếm
```http
GET /api/search/suggestions?q={query}&type={type}
Authorization: Bearer {token}
```

**Parameters:**
- `type`: books, readers, borrows

---

## 📊 **Reports API**

### 1. Tạo báo cáo từ template
```http
POST /api/reports/generate
Authorization: Bearer {token}
Content-Type: application/json

{
    "template_id": 1,
    "filters": {
        "from_date": "2024-01-01",
        "to_date": "2024-01-31",
        "status": "Dang muon"
    },
    "export_format": "excel"
}
```

### 2. Lấy thống kê tổng quan
```http
GET /api/stats
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "total_books": 150,
        "total_readers": 200,
        "total_borrows": 500,
        "total_fines": 2500000,
        "overdue_borrows": 5,
        "pending_fines": 100000
    }
}
```

---

## 📦 **Inventory API**

### 1. Lấy danh sách kho
```http
GET /api/inventory
Authorization: Bearer {token}
```

### 2. Quét mã vạch
```http
POST /api/inventory/scan-barcode
Authorization: Bearer {token}
Content-Type: application/json

{
    "barcode": "BK000001"
}
```

### 3. Chuyển kho
```http
POST /api/inventory/{id}/transfer
Authorization: Bearer {token}
Content-Type: application/json

{
    "to_location": "Kệ B2",
    "reason": "Sắp xếp lại kho"
}
```

---

## 🔔 **Notifications API**

### 1. Lấy thông báo của user
```http
GET /api/notifications
Authorization: Bearer {token}
```

### 2. Đánh dấu đã đọc
```http
POST /api/notifications/{id}/mark-read
Authorization: Bearer {token}
```

---

## ❌ **Error Responses**

### 400 Bad Request
```json
{
    "status": "error",
    "message": "Dữ liệu không hợp lệ",
    "errors": {
        "email": ["Email không đúng định dạng"],
        "password": ["Mật khẩu phải có ít nhất 6 ký tự"]
    }
}
```

### 401 Unauthorized
```json
{
    "status": "error",
    "message": "Token không hợp lệ hoặc đã hết hạn"
}
```

### 403 Forbidden
```json
{
    "status": "error",
    "message": "Bạn không có quyền thực hiện hành động này"
}
```

### 404 Not Found
```json
{
    "status": "error",
    "message": "Không tìm thấy dữ liệu"
}
```

### 500 Internal Server Error
```json
{
    "status": "error",
    "message": "Lỗi máy chủ nội bộ"
}
```

---

## 📝 **Rate Limiting**

API có giới hạn số lượng request:
- **Public endpoints**: 60 requests/minute
- **Authenticated endpoints**: 100 requests/minute
- **Admin endpoints**: 200 requests/minute

---

## 🔧 **Testing với Postman**

### Import Collection:
1. Tạo collection mới trong Postman
2. Import các endpoints từ documentation này
3. Set base URL: `http://localhost:8000/api`
4. Tạo environment với variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: `{your_token}`

### Test Flow:
1. **Login** → Lấy token
2. **Set token** vào Authorization header
3. **Test các endpoints** theo role của user

---

## 📱 **Mobile App Integration**

### React Native Example:
```javascript
// Login
const login = async (email, password) => {
  const response = await fetch('http://localhost:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email, password }),
  });
  return response.json();
};

// Get books
const getBooks = async (token, page = 1) => {
  const response = await fetch(`http://localhost:8000/api/books?page=${page}`, {
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
  return response.json();
};
```

---

## 🚀 **Deployment Notes**

### Production Setup:
1. **HTTPS**: Luôn sử dụng HTTPS trong production
2. **CORS**: Cấu hình CORS cho domain frontend
3. **Rate Limiting**: Điều chỉnh rate limits phù hợp
4. **Logging**: Bật logging cho API requests
5. **Monitoring**: Sử dụng tools như Laravel Telescope

### Security Best Practices:
- Validate tất cả input
- Sử dụng HTTPS
- Implement proper CORS
- Rate limiting
- Token expiration
- Input sanitization
- SQL injection prevention

---

**📞 Support**: Liên hệ admin để được hỗ trợ API integration.