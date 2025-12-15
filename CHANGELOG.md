# Changelog

Tất cả các thay đổi đáng chú ý trong dự án này sẽ được ghi lại trong file này.

Format dựa trên [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
và dự án này tuân thủ [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- README.md với tài liệu đầy đủ về dự án
- LICENSE file (MIT License)
- CONTRIBUTING.md hướng dẫn đóng góp
- CHANGELOG.md để theo dõi thay đổi
- setup.bat script tự động setup dự án

## [1.0.0] - 2025-12-04

### Added
- Hệ thống quản lý thư viện cơ bản
- Quản lý sách, danh mục, tác giả, nhà xuất bản
- Quản lý mượn trả sách với trạng thái chi tiết
- Tích hợp thanh toán VNPay
- Quản lý đơn hàng và giao hàng
- Quản lý người dùng với phân quyền (Admin, Staff, Reader)
- Dashboard quản trị với thống kê
- Hệ thống thông báo
- Export báo cáo Excel
- Tìm kiếm sách nâng cao
- Quản lý kho và nhập kho
- Shipping logs và tracking
- Email marketing
- Google OAuth authentication

### Fixed
- Sửa lỗi xác thực chữ ký VNPay
- Cải thiện logging cho VNPay service
- Fix enum values cho borrow status
- Cải thiện UI/UX cho các trang quản lý

### Changed
- Cải thiện cấu trúc code và organization
- Tối ưu hóa database queries
- Cải thiện error handling

### Documentation
- Thêm hướng dẫn sửa lỗi VNPay (HUONG_DAN_SUA_LOI_VNPAY.md)
- Thêm tài liệu test VNPay (TEST_VNPAY.md)
- Thêm summary changes (SUMMARY_CHANGES.md)
- Thêm các file hướng dẫn nhanh (QUICK_START.txt, START_HERE.txt)

---

## Types of Changes

- `Added` - Tính năng mới
- `Changed` - Thay đổi trong tính năng hiện có
- `Deprecated` - Tính năng sắp bị loại bỏ
- `Removed` - Tính năng đã bị loại bỏ
- `Fixed` - Sửa lỗi
- `Security` - Sửa lỗi bảo mật

