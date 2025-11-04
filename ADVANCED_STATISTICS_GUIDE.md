# Hướng dẫn sử dụng Thống kê nâng cao

## Tổng quan

Hệ thống thống kê nâng cao cung cấp các công cụ phân tích dữ liệu toàn diện cho thư viện, bao gồm:

- Dashboard thống kê trực quan với biểu đồ và báo cáo
- Phân tích xu hướng theo thời gian
- Thống kê chi tiết theo thể loại, khoa/bộ môn
- Phân tích hoạt động tìm kiếm và thông báo
- Quản lý giao dịch kho

## Cài đặt và chạy dữ liệu mẫu

### 1. Chạy Migration
```bash
php artisan migrate
```

### 2. Chạy Seeder để tạo dữ liệu mẫu
```bash
php artisan db:seed --class=AdvancedStatisticsSeeder
php artisan db:seed --class=AdvancedDataSeeder
```

Hoặc chạy tất cả seeders:
```bash
php artisan db:seed
```

## Các tính năng chính

### 1. Dashboard Thống kê nâng cao

**Truy cập:** `/admin/statistics/advanced`

**Tính năng:**
- Tổng quan các chỉ số chính (sách, độc giả, mượn sách, phạt)
- Biểu đồ xu hướng mượn sách theo thời gian
- Phân bố sách theo thể loại
- Danh sách sách được mượn nhiều nhất
- Danh sách độc giả tích cực
- Thống kê tìm kiếm và thông báo

### 2. API Endpoints

#### Thống kê tổng quan
```
GET /admin/statistics/advanced/overview
```
**Tham số:**
- `period`: chu kỳ (week, month, quarter, year)
- `from_date`: từ ngày (YYYY-MM-DD)
- `to_date`: đến ngày (YYYY-MM-DD)

#### Xu hướng
```
GET /admin/statistics/advanced/trends
```
**Tham số:**
- `period`: chu kỳ
- `months`: số tháng hiển thị (6, 12, 24)

#### Thống kê theo thể loại
```
GET /admin/statistics/advanced/category-stats
```

#### Thống kê theo khoa/bộ môn
```
GET /admin/statistics/advanced/faculty-stats
```

#### Thống kê tìm kiếm
```
GET /admin/statistics/advanced/search-stats
```

#### Thống kê thông báo
```
GET /admin/statistics/advanced/notification-stats
```

#### Thống kê kho
```
GET /admin/statistics/advanced/inventory-stats
```

### 3. Template Báo cáo nâng cao

Hệ thống đã tạo sẵn các template báo cáo:

1. **Báo cáo mượn sách theo tháng**
   - Thống kê chi tiết về việc mượn sách
   - Lọc theo khoảng thời gian và trạng thái
   - Nhóm theo ngày mượn

2. **Thống kê độc giả tích cực**
   - Danh sách độc giả có hoạt động cao
   - Lọc theo khoa/bộ môn
   - Sắp xếp theo số lượt mượn

3. **Báo cáo sách phổ biến**
   - Sách được mượn nhiều nhất
   - Lọc theo thể loại và năm xuất bản
   - Bao gồm đánh giá trung bình

4. **Báo cáo phạt và vi phạm**
   - Thống kê chi tiết về các khoản phạt
   - Lọc theo loại phạt và trạng thái
   - Nhóm theo loại và trạng thái

5. **Thống kê tổng quan hệ thống**
   - Các chỉ số tổng quan
   - So sánh theo chu kỳ
   - Xu hướng phát triển

## Cấu trúc dữ liệu

### 1. SearchLog Model
Lưu trữ log các hoạt động tìm kiếm:
- `query`: từ khóa tìm kiếm
- `type`: loại tìm kiếm (books, readers, borrows, fines)
- `filters`: bộ lọc được sử dụng
- `results_count`: số kết quả trả về
- `user_id`: người thực hiện tìm kiếm
- `ip_address`: địa chỉ IP
- `user_agent`: thông tin trình duyệt

### 2. NotificationLog Model
Lưu trữ log các thông báo đã gửi:
- `user_id`: người nhận
- `type`: loại thông báo
- `title`: tiêu đề
- `message`: nội dung
- `status`: trạng thái (sent, delivered, read, failed)
- `sent_at`: thời gian gửi
- `read_at`: thời gian đọc
- `metadata`: dữ liệu bổ sung

### 3. InventoryTransaction Model
Lưu trữ các giao dịch kho:
- `book_id`: ID sách
- `type`: loại giao dịch (import, export, transfer, adjustment, damage, loss)
- `quantity`: số lượng
- `unit_price`: giá đơn vị
- `total_amount`: tổng tiền
- `status`: trạng thái (pending, completed, cancelled)
- `notes`: ghi chú
- `created_by`: người tạo

### 4. ReportTemplate Model
Lưu trữ template báo cáo:
- `name`: tên template
- `type`: loại báo cáo
- `description`: mô tả
- `columns`: các cột hiển thị
- `filters`: bộ lọc có sẵn
- `group_by`: nhóm theo cột
- `order_by`: sắp xếp
- `is_active`: trạng thái hoạt động
- `is_public`: công khai
- `created_by`: người tạo

## Sử dụng trong Code

### 1. Tạo thống kê tùy chỉnh

```php
use App\Http\Controllers\AdvancedStatisticsController;

$controller = new AdvancedStatisticsController();

// Lấy thống kê tổng quan
$overview = $controller->overview(request());

// Lấy xu hướng
$trends = $controller->trends(request());

// Lấy thống kê theo thể loại
$categoryStats = $controller->categoryStats(request());
```

### 2. Log tìm kiếm

```php
use App\Models\SearchLog;

SearchLog::create([
    'query' => 'từ khóa tìm kiếm',
    'type' => 'books',
    'filters' => ['category' => 'khoa học'],
    'results_count' => 15,
    'user_id' => auth()->id(),
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

### 3. Log thông báo

```php
use App\Models\NotificationLog;

NotificationLog::create([
    'user_id' => $userId,
    'type' => 'borrow_reminder',
    'title' => 'Nhắc nhở trả sách',
    'message' => 'Sách của bạn sắp đến hạn trả',
    'status' => 'sent',
    'sent_at' => now(),
    'metadata' => [
        'book_id' => $bookId,
        'borrow_id' => $borrowId,
    ],
]);
```

### 4. Tạo template báo cáo mới

```php
use App\Models\ReportTemplate;

ReportTemplate::create([
    'name' => 'Báo cáo tùy chỉnh',
    'type' => 'custom',
    'description' => 'Mô tả báo cáo',
    'columns' => [
        ['key' => 'field1', 'label' => 'Trường 1', 'type' => 'text'],
        ['key' => 'field2', 'label' => 'Trường 2', 'type' => 'number'],
    ],
    'filters' => [
        ['key' => 'date_range', 'label' => 'Khoảng thời gian', 'type' => 'date_range'],
    ],
    'group_by' => ['field1'],
    'order_by' => [['column' => 'field2', 'direction' => 'desc']],
    'is_active' => true,
    'is_public' => false,
    'created_by' => auth()->id(),
]);
```

## Tùy chỉnh và mở rộng

### 1. Thêm loại thống kê mới

1. Thêm method mới vào `AdvancedStatisticsController`
2. Thêm route tương ứng
3. Tạo view nếu cần
4. Cập nhật dashboard

### 2. Tạo biểu đồ tùy chỉnh

Sử dụng Chart.js để tạo biểu đồ mới:

```javascript
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Label 1', 'Label 2'],
        datasets: [{
            label: 'Dataset',
            data: [12, 19],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
```

### 3. Thêm bộ lọc mới

Thêm vào template báo cáo:

```php
'filters' => [
    ['key' => 'new_filter', 'label' => 'Bộ lọc mới', 'type' => 'select', 'options' => ['option1', 'option2']],
]
```

## Lưu ý quan trọng

1. **Hiệu suất**: Các truy vấn thống kê có thể chậm với dữ liệu lớn. Nên sử dụng index và cache khi cần thiết.

2. **Bảo mật**: Tất cả API đều có middleware kiểm tra quyền `view-reports`.

3. **Dữ liệu mẫu**: Seeder tạo dữ liệu mẫu phong phú để test các tính năng.

4. **Responsive**: Dashboard được thiết kế responsive cho mobile và desktop.

5. **Export**: Có thể xuất báo cáo ra Excel/PDF (cần implement thêm).

## Troubleshooting

### Lỗi thường gặp:

1. **Chart không hiển thị**: Kiểm tra Chart.js đã load chưa
2. **API trả về 403**: Kiểm tra quyền user
3. **Dữ liệu không cập nhật**: Kiểm tra cache và làm mới trang
4. **Seeder lỗi**: Kiểm tra foreign key constraints

### Debug:

```php
// Bật debug mode
config(['app.debug' => true]);

// Log query
DB::enableQueryLog();
// ... thực hiện query
dd(DB::getQueryLog());
```
