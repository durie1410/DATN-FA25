# Hướng dẫn test Thống kê nâng cao

## Vấn đề đã được khắc phục:

1. ✅ **Sửa link menu**: Đã sửa link "Thống kê nâng cao" từ `href="#"` thành `href="{{ route('statistics.advanced.dashboard') }}"`

2. ✅ **Dữ liệu đã được tạo**: 
   - SearchLogs: 2,500 records
   - NotificationLogs: 900 records  
   - InventoryTransactions: 200 records
   - ReportTemplates: 30 records

3. ✅ **Routes đã được đăng ký**: Tất cả routes cho thống kê nâng cao đã hoạt động

## Cách test:

### 1. Test trực tiếp (không cần quyền):
Truy cập: `http://quanlythuvien.test/admin/test-stats`

### 2. Test qua menu (cần quyền):
1. Đăng nhập với tài khoản admin
2. Click vào "Thống kê nâng cao" trong menu bên trái
3. Nếu bị chặn bởi quyền, hãy chạy lệnh sau:

```bash
php artisan tinker --execute="
\$user = App\Models\User::where('role', 'admin')->first();
\$user->givePermissionTo('view-reports');
echo 'Permission granted to user: ' . \$user->name;
"
```

### 3. Nếu vẫn không hoạt động:

**Kiểm tra log lỗi:**
```bash
tail -f storage/logs/laravel.log
```

**Xóa cache:**
```bash
php artisan optimize:clear
```

**Kiểm tra routes:**
```bash
php artisan route:list --name=statistics.advanced
```

## Các tính năng có sẵn:

- 📊 Dashboard tổng quan với các chỉ số chính
- 📈 Biểu đồ xu hướng mượn sách theo thời gian  
- 📚 Thống kê sách phổ biến và độc giả tích cực
- 🔍 Phân tích hoạt động tìm kiếm
- 📧 Thống kê thông báo và giao dịch kho
- 📋 Template báo cáo có sẵn

## URL chính thức:
`http://quanlythuvien.test/admin/statistics/advanced`

## URL test (không cần quyền):
`http://quanlythuvien.test/admin/test-stats`
