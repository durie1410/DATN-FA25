# Hướng dẫn khắc phục sự cố Thống kê nâng cao

## Vấn đề: Thống kê nâng cao không hiển thị dữ liệu

### Nguyên nhân có thể:

1. **Dữ liệu chưa được tạo**
2. **Lỗi trong controller**
3. **Lỗi trong view**
4. **Vấn đề với routes**
5. **Lỗi database connection**

## Các bước khắc phục:

### 1. Kiểm tra dữ liệu đã được tạo chưa

```bash
# Kiểm tra số lượng records trong các bảng
php artisan tinker --execute="
echo 'Books: ' . App\Models\Book::count() . PHP_EOL;
echo 'Readers: ' . App\Models\Reader::count() . PHP_EOL;
echo 'Borrows: ' . App\Models\Borrow::count() . PHP_EOL;
echo 'SearchLogs: ' . App\Models\SearchLog::count() . PHP_EOL;
echo 'NotificationLogs: ' . App\Models\NotificationLog::count() . PHP_EOL;
echo 'InventoryTransactions: ' . App\Models\InventoryTransaction::count() . PHP_EOL;
echo 'ReportTemplates: ' . App\Models\ReportTemplate::count() . PHP_EOL;
"
```

**Nếu các số liệu = 0, chạy seeder:**
```bash
php artisan db:seed --class=AdvancedStatisticsSeeder
```

### 2. Kiểm tra routes

```bash
php artisan route:list --name=statistics.advanced
```

**Kết quả mong đợi:** Các routes phải hiển thị với middleware `view-reports`

### 3. Kiểm tra quyền user

Đảm bảo user hiện tại có quyền `view-reports`:

```bash
php artisan tinker --execute="
\$user = App\Models\User::find(1); // Thay 1 bằng ID user của bạn
echo 'User role: ' . \$user->role . PHP_EOL;
echo 'Has permission: ' . (\$user->can('view-reports') ? 'Yes' : 'No') . PHP_EOL;
"
```

### 4. Kiểm tra log lỗi

```bash
tail -f storage/logs/laravel.log
```

Hoặc kiểm tra file log:
```bash
cat storage/logs/laravel.log | tail -20
```

### 5. Kiểm tra cấu trúc database

```bash
php artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
echo 'SearchLogs columns: ';
print_r(Schema::getColumnListing('search_logs'));
echo PHP_EOL;
echo 'NotificationLogs columns: ';
print_r(Schema::getColumnListing('notification_logs'));
echo PHP_EOL;
echo 'InventoryTransactions columns: ';
print_r(Schema::getColumnListing('inventory_transactions'));
echo PHP_EOL;
"
```

### 6. Test trực tiếp controller

Tạo file test đơn giản:

```php
// test_stats.php
<?php
require_once 'vendor/autoload.php';

use App\Models\Book;
use App\Models\Reader;
use App\Models\Borrow;

echo "Testing models...\n";
echo "Books: " . Book::count() . "\n";
echo "Readers: " . Reader::count() . "\n";
echo "Borrows: " . Borrow::count() . "\n";
echo "Test completed!\n";
```

Chạy:
```bash
php test_stats.php
```

### 7. Kiểm tra cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 8. Kiểm tra database connection

```bash
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'Database connection: OK' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database connection: ERROR - ' . \$e->getMessage() . PHP_EOL;
}
"
```

## Các lỗi thường gặp và cách khắc phục:

### Lỗi 1: "Call to a member function connection() on null"

**Nguyên nhân:** Laravel chưa được khởi tạo đúng cách
**Khắc phục:** Đảm bảo chạy trong môi trường Laravel (qua web browser hoặc artisan command)

### Lỗi 2: "Column not found"

**Nguyên nhân:** Cấu trúc database khác với code
**Khắc phục:** 
```bash
php artisan migrate:status
php artisan migrate
```

### Lỗi 3: "Data truncated for column"

**Nguyên nhân:** Giá trị không phù hợp với enum/constraint
**Khắc phục:** Kiểm tra migration và sửa seeder cho đúng

### Lỗi 4: "Foreign key constraint fails"

**Nguyên nhân:** Tham chiếu đến record không tồn tại
**Khắc phục:** Sửa seeder để sử dụng ID có sẵn

### Lỗi 5: "Permission denied"

**Nguyên nhân:** User không có quyền truy cập
**Khắc phục:** 
```bash
php artisan tinker --execute="
\$user = App\Models\User::find(1);
\$user->givePermissionTo('view-reports');
"
```

## Debug nâng cao:

### 1. Bật debug mode

Trong `.env`:
```
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

### 2. Thêm logging vào controller

```php
\Log::info('Dashboard method called');
\Log::info('Stats data: ', $stats);
```

### 3. Kiểm tra memory limit

```bash
php -i | grep memory_limit
```

### 4. Kiểm tra PHP errors

```bash
php -l app/Http/Controllers/AdvancedStatisticsController.php
```

## Liên hệ hỗ trợ:

Nếu vẫn gặp vấn đề, hãy cung cấp:
1. Thông báo lỗi đầy đủ
2. Kết quả của các lệnh kiểm tra trên
3. Phiên bản Laravel và PHP
4. Cấu hình database

## Các lệnh hữu ích:

```bash
# Reset toàn bộ database và chạy lại seeders
php artisan migrate:fresh --seed

# Chạy chỉ seeder thống kê nâng cao
php artisan db:seed --class=AdvancedStatisticsSeeder

# Kiểm tra tất cả routes
php artisan route:list

# Xóa tất cả cache
php artisan optimize:clear

# Kiểm tra cấu hình
php artisan config:show
```
