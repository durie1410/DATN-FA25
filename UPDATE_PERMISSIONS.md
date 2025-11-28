# Hướng dẫn Cập nhật Phân quyền

## Cách cập nhật Permissions và Roles

### 1. Chạy Seeder để cập nhật Permissions

```bash
php artisan db:seed --class=RolePermissionSeeder
```

Lệnh này sẽ:
- Tạo tất cả permissions mới (nếu chưa có)
- Cập nhật roles với permissions phù hợp
- Gán roles cho các user mẫu (admin, staff, user)

### 2. Reset và Seed lại toàn bộ (Nếu cần)

⚠️ **CẢNH BÁO:** Lệnh này sẽ xóa toàn bộ dữ liệu!

```bash
php artisan migrate:fresh --seed
```

### 3. Chỉ cập nhật Permissions (Không xóa dữ liệu)

Nếu bạn chỉ muốn cập nhật permissions mà không ảnh hưởng đến dữ liệu:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 4. Kiểm tra Permissions sau khi cập nhật

#### Trong Tinker:

```bash
php artisan tinker
```

```php
// Kiểm tra roles
\Spatie\Permission\Models\Role::all();

// Kiểm tra permissions
\Spatie\Permission\Models\Permission::all();

// Kiểm tra role của user
$user = \App\Models\User::find(1);
$user->getRoleNames();
$user->getAllPermissions();

// Kiểm tra user có permission không
$user->can('view-books');
```

### 5. Gán Role cho User mới

```php
$user = \App\Models\User::find($userId);
$user->assignRole('admin'); // hoặc 'staff', 'user'
$user->update(['role' => 'admin']); // Cập nhật role attribute
```

### 6. Xóa và gán lại Role

```php
$user = \App\Models\User::find($userId);
$user->removeRole('staff');
$user->assignRole('admin');
$user->update(['role' => 'admin']);
```

---

## Cấu trúc Permissions hiện tại

### Admin - Toàn quyền
- Tất cả permissions

### Staff - Quyền hạn chế
- view-dashboard
- view-books, create-books, edit-books
- view-categories, create-categories, edit-categories
- view-readers, create-readers, edit-readers
- view-borrows, create-borrows, edit-borrows, return-books
- view-reservations, create-reservations, edit-reservations, confirm-reservations
- view-reviews, approve-reviews
- view-fines, create-fines, edit-fines
- view-reports
- view-notifications, send-notifications
- view-inventory, create-inventory, edit-inventory

### User - Quyền tối thiểu
- view-books
- view-categories
- create-reviews
- view-reservations, create-reservations
- view-notifications

---

## Troubleshooting

### Lỗi: Permission không tồn tại
```bash
# Chạy lại seeder
php artisan db:seed --class=RolePermissionSeeder
```

### Lỗi: User không có quyền
```php
// Kiểm tra role
$user->role;
$user->getRoleNames();

// Gán lại role
$user->assignRole('admin');
$user->update(['role' => 'admin']);
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

**Lưu ý:** Luôn backup database trước khi chạy migrate:fresh!

