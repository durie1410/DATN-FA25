# 🤝 Hướng Dẫn Đóng Góp

Cảm ơn bạn đã quan tâm đến việc đóng góp cho dự án Hệ Thống Quản Lý Thư Viện! 

## 📋 Quy Trình Đóng Góp

### 1. Fork và Clone Repository

```bash
# Fork repository trên GitHub
# Sau đó clone về máy
git clone https://github.com/YOUR_USERNAME/DATN-FA25.git
cd DATN-FA25
```

### 2. Tạo Branch Mới

```bash
# Tạo branch từ main
git checkout -b feature/ten-tinh-nang-moi
# hoặc
git checkout -b fix/ten-bug-fix
```

**Quy ước đặt tên branch:**
- `feature/` - Tính năng mới
- `fix/` - Sửa lỗi
- `docs/` - Cập nhật tài liệu
- `refactor/` - Refactor code
- `test/` - Thêm test

### 3. Phát Triển

- Viết code rõ ràng, dễ đọc
- Tuân thủ coding standards của Laravel
- Thêm comments cho code phức tạp
- Đảm bảo không có lỗi syntax

### 4. Commit Changes

```bash
git add .
git commit -m "feat: Thêm tính năng XYZ"
```

**Quy ước commit message:**
- `feat:` - Tính năng mới
- `fix:` - Sửa lỗi
- `docs:` - Cập nhật tài liệu
- `style:` - Formatting, thiếu semicolon, etc
- `refactor:` - Refactor code
- `test:` - Thêm test
- `chore:` - Cập nhật build tasks, config, etc

### 5. Push và Tạo Pull Request

```bash
git push origin feature/ten-tinh-nang-moi
```

Sau đó tạo Pull Request trên GitHub với:
- Mô tả rõ ràng về thay đổi
- Screenshots (nếu có thay đổi UI)
- Reference đến issue (nếu có)

## 📝 Coding Standards

### PHP/Laravel

- Tuân thủ [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)
- Sử dụng type hints và return types
- Viết docblocks cho methods phức tạp
- Đặt tên biến và hàm rõ ràng, có ý nghĩa

**Ví dụ:**
```php
/**
 * Lấy danh sách sách đang mượn của độc giả
 *
 * @param int $readerId
 * @return Collection
 */
public function getBorrowedBooks(int $readerId): Collection
{
    return Borrow::where('reader_id', $readerId)
        ->where('trang_thai', 'dang_muon')
        ->with('borrowItems.book')
        ->get();
}
```

### Blade Templates

- Sử dụng indentation 4 spaces
- Tách logic phức tạp ra Controller hoặc Service
- Sử dụng components khi có thể

### JavaScript

- Sử dụng ES6+ syntax
- Comment cho logic phức tạp
- Tránh inline scripts trong Blade templates

## 🧪 Testing

Nếu thêm tính năng mới, hãy thêm test:

```bash
php artisan test
```

## 📚 Tài Liệu

- Cập nhật README.md nếu thêm tính năng lớn
- Thêm comments trong code
- Cập nhật CHANGELOG.md (nếu có)

## ✅ Checklist Trước Khi Submit PR

- [ ] Code đã được test và hoạt động đúng
- [ ] Không có lỗi syntax hoặc linter
- [ ] Tuân thủ coding standards
- [ ] Đã cập nhật tài liệu (nếu cần)
- [ ] Commit messages rõ ràng
- [ ] Không có conflict với main branch

## 🐛 Báo Cáo Bug

Khi báo cáo bug, vui lòng cung cấp:

1. **Mô tả bug**: Mô tả rõ ràng về vấn đề
2. **Các bước tái hiện**: Các bước để tái hiện bug
3. **Kết quả mong đợi**: Kết quả bạn mong đợi
4. **Kết quả thực tế**: Kết quả thực tế xảy ra
5. **Screenshots**: Nếu có thể
6. **Môi trường**: PHP version, Laravel version, OS

## 💡 Đề Xuất Tính Năng

Khi đề xuất tính năng mới:

1. Mô tả rõ ràng tính năng
2. Giải thích tại sao tính năng này hữu ích
3. Đề xuất cách implement (nếu có)
4. Cung cấp examples hoặc mockups (nếu có)

## 📞 Câu Hỏi?

Nếu có câu hỏi, vui lòng:
- Mở một [Issue](https://github.com/durie1410/DATN-FA25/issues)
- Hoặc liên hệ maintainers

---

Cảm ơn bạn đã đóng góp! 🎉

