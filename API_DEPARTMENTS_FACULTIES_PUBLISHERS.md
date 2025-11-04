# API Quản lý Ngành, Khoa và Nhà Xuất Bản

## Tổng quan
Đã tạo thành công các API endpoints cho quản lý:
- **Departments (Ngành)**: Quản lý các ngành học
- **Faculties (Khoa)**: Quản lý các khoa
- **Publishers (Nhà xuất bản)**: Quản lý các nhà xuất bản

## API Endpoints

### 1. Departments API

#### Lấy danh sách ngành
```
GET /api/departments
```
**Tham số tùy chọn:**
- `keyword`: Tìm kiếm theo tên hoặc mã ngành
- `faculty_id`: Lọc theo khoa
- `trang_thai`: Lọc theo trạng thái (active/inactive)
- `sort_by`: Sắp xếp theo (ten_nganh, ma_nganh, faculty, created_at)
- `sort_order`: Thứ tự sắp xếp (asc/desc)
- `per_page`: Số bản ghi mỗi trang (mặc định: 15)

#### Lấy ngành theo ID
```
GET /api/departments/{id}
```

#### Lấy ngành đang hoạt động
```
GET /api/departments/active
```

#### Lấy ngành theo khoa
```
GET /api/departments/faculty/{facultyId}
```

### 2. Faculties API

#### Lấy danh sách khoa
```
GET /api/faculties
```
**Tham số tùy chọn:**
- `keyword`: Tìm kiếm theo tên hoặc mã khoa
- `trang_thai`: Lọc theo trạng thái (active/inactive)
- `sort_by`: Sắp xếp theo (ten_khoa, ma_khoa, departments_count, readers_count, created_at)
- `sort_order`: Thứ tự sắp xếp (asc/desc)
- `per_page`: Số bản ghi mỗi trang (mặc định: 15)

#### Lấy khoa theo ID
```
GET /api/faculties/{id}
```

#### Lấy khoa đang hoạt động
```
GET /api/faculties/active
```

#### Lấy khoa kèm ngành
```
GET /api/faculties/with-departments
```

### 3. Publishers API

#### Lấy danh sách nhà xuất bản
```
GET /api/publishers
```
**Tham số tùy chọn:**
- `keyword`: Tìm kiếm theo tên nhà xuất bản
- `trang_thai`: Lọc theo trạng thái (active/inactive)
- `sort_by`: Sắp xếp theo (ten_nha_xuat_ban, books_count, ngay_thanh_lap, created_at)
- `sort_order`: Thứ tự sắp xếp (asc/desc)
- `per_page`: Số bản ghi mỗi trang (mặc định: 15)

#### Lấy nhà xuất bản theo ID
```
GET /api/publishers/{id}
```

#### Lấy nhà xuất bản đang hoạt động
```
GET /api/publishers/active
```

#### Lấy nhà xuất bản có sách
```
GET /api/publishers/with-books
```

## Response Format

Tất cả API đều trả về JSON với format chuẩn:

### Thành công
```json
{
    "status": "success",
    "data": {
        // Dữ liệu trả về
    }
}
```

### Lỗi
```json
{
    "status": "error",
    "message": "Thông báo lỗi"
}
```

## Ví dụ sử dụng

### Lấy danh sách ngành đang hoạt động
```bash
curl -X GET "http://127.0.0.1:8000/api/departments/active"
```

### Tìm kiếm khoa theo từ khóa
```bash
curl -X GET "http://127.0.0.1:8000/api/faculties?keyword=CNTT"
```

### Lấy nhà xuất bản có sách
```bash
curl -X GET "http://127.0.0.1:8000/api/publishers/with-books"
```

## Dữ liệu mẫu

Hệ thống đã có sẵn dữ liệu:
- **7 khoa** (Khoa Công nghệ Thông tin, Khoa Kinh tế, Khoa Ngoại ngữ, v.v.)
- **17 ngành** (Khoa học Máy tính, Công nghệ Thông tin, An toàn Thông tin, v.v.)
- **8 nhà xuất bản** (Nhà xuất bản Giáo dục Việt Nam, Nhà xuất bản Chính trị Quốc gia, v.v.)

## Lưu ý

1. Tất cả API đều là public (không cần authentication)
2. Dữ liệu được phân trang mặc định 15 bản ghi/trang
3. Hỗ trợ tìm kiếm và sắp xếp linh hoạt
4. Có đầy đủ relationships giữa các bảng (khoa-ngành, nhà xuất bản-sách)
