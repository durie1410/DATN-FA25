# 📚 Hệ Thống Quản Lý Thư Viện

[![Laravel](https://img.shields.io/badge/Laravel-8.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.3%7C8.0-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Hệ thống quản lý thư viện hiện đại được xây dựng bằng Laravel 8, hỗ trợ quản lý sách, mượn trả, thanh toán VNPay, và nhiều tính năng khác.

## ✨ Tính Năng Chính

### 📖 Quản Lý Sách
- Quản lý danh mục sách, tác giả, nhà xuất bản
- Tìm kiếm và lọc sách nâng cao
- Quản lý kho sách và nhập kho
- Hiển thị sách công khai với đánh giá và bình luận

### 🔄 Quản Lý Mượn Trả
- Quản lý phiếu mượn sách
- Theo dõi trạng thái mượn trả chi tiết
- Quản lý giỏ hàng mượn sách
- Tự động tính phí quá hạn

### 💳 Thanh Toán
- Tích hợp VNPay (sandbox & production)
- Thanh toán đơn hàng mua sách
- Quản lý đơn hàng và giao hàng
- Theo dõi lịch sử thanh toán

### 👥 Quản Lý Người Dùng
- Phân quyền người dùng (Admin, Staff, Reader)
- Quản lý độc giả và thông tin cá nhân
- Xác thực Google OAuth
- Quản lý tài khoản người dùng

### 📊 Báo Cáo & Thống Kê
- Dashboard quản trị với thống kê tổng quan
- Báo cáo xuất Excel
- Lịch sử hoạt động (Audit Log)
- Theo dõi tìm kiếm và hành vi người dùng

### 🔔 Thông Báo
- Thông báo mượn sách, nhắc nhở trả sách
- Thông báo quá hạn
- Email marketing và đăng ký nhận thông báo
- Real-time notifications

### 🚚 Quản Lý Giao Hàng
- Theo dõi đơn hàng và giao hàng
- Quản lý shipping logs chi tiết
- Upload chứng từ giao hàng

## 🚀 Cài Đặt

### Yêu Cầu Hệ Thống
- PHP >= 7.3 hoặc >= 8.0
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Web server (Apache/Nginx)

### Các Bước Cài Đặt

1. **Clone repository**
```bash
git clone https://github.com/durie1410/DATN-FA25.git
cd DATN-FA25
```

2. **Cài đặt dependencies**
```bash
composer install
npm install
```

3. **Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database trong `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quanlythuvien
DB_USERNAME=root
DB_PASSWORD=
```

5. **Chạy migrations và seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Tạo storage link**
```bash
php artisan storage:link
```

7. **Build assets**
```bash
npm run dev
# hoặc cho production
npm run production
```

8. **Chạy server**
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## ⚙️ Cấu Hình VNPay

### Cấu hình trong `.env`
```env
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
```

### Sửa lỗi VNPay nhanh
Nếu gặp lỗi "Xác thực chữ ký thất bại":
- Chạy script: `fix_vnpay_now.bat` (Windows)
- Hoặc xem hướng dẫn: `HUONG_DAN_SUA_LOI_VNPAY.md`
- Kiểm tra config: Truy cập `/vnpay-debug`

## 📁 Cấu Trúc Dự Án

```
DATN-FA25/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Business Logic Services
│   └── Notifications/        # Notification Classes
├── database/
│   ├── migrations/           # Database Migrations
│   └── seeders/              # Database Seeders
├── resources/
│   ├── views/                # Blade Templates
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript
├── routes/
│   ├── web.php               # Web Routes
│   └── api.php               # API Routes
└── public/                   # Public Assets
```

## 🔐 Phân Quyền

Hệ thống sử dụng [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) để quản lý phân quyền:

- **Admin**: Toàn quyền quản trị hệ thống
- **Staff**: Quản lý sách, mượn trả, đơn hàng
- **Reader**: Mượn sách, xem sách, đặt hàng

## 🧪 Testing

```bash
php artisan test
```

> Gợi ý nhanh:
> - Tạo file `.env.testing` (copy từ `.env`) và trỏ tới database riêng cho test.
> - Chạy `php artisan migrate --env=testing` trước lần test đầu tiên.
> - Khi cần chạy 1 test cụ thể: `php artisan test tests/Feature/UserTest.php`.

## 📝 Tài Liệu

- [Hướng dẫn sửa lỗi VNPay](HUONG_DAN_SUA_LOI_VNPAY.md)
- [Tóm tắt thay đổi](SUMMARY_CHANGES.md)
- [Hướng dẫn test VNPay](TEST_VNPAY.md)

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: Laravel 8.x
- **Frontend**: Blade Templates, Bootstrap, jQuery
- **Database**: MySQL
- **Payment**: VNPay Integration
- **Permissions**: Spatie Laravel Permission
- **Export**: Maatwebsite Excel
- **Authentication**: Laravel Sanctum, Google OAuth

## 📦 Packages Chính

- `laravel/framework`: ^8.75
- `spatie/laravel-permission`: ^6.21
- `maatwebsite/excel`: ^3.1
- `laravel/sanctum`: ^2.11
- `laravel/socialite`: ^5.23

## 🤝 Đóng Góp

Mọi đóng góp đều được chào đón! Vui lòng:

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📄 License

Dự án này được phân phối dưới giấy phép MIT. Xem file `LICENSE` để biết thêm chi tiết.

## 👥 Contributors

- [@durie1410](https://github.com/durie1410)
- [@KQHoang](https://github.com/KQHoang)
- [@hoangdvph402399](https://github.com/hoangdvph402399)

## 📞 Liên Hệ

Nếu có câu hỏi hoặc cần hỗ trợ, vui lòng mở một [Issue](https://github.com/durie1410/DATN-FA25/issues).

---

⭐ Nếu dự án này hữu ích, hãy cho một star!

