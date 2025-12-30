-- ============================================
-- SCRIPT SỬA LỖI: Thêm cột ngay_sinh và gioi_tinh vào bảng users
-- ============================================
-- Hướng dẫn:
-- 1. Mở phpMyAdmin
-- 2. Chọn database: quanlythuviennn
-- 3. Vào tab SQL
-- 4. Copy và paste toàn bộ nội dung file này
-- 5. Nhấn "Thực thi" (Go)
-- ============================================

-- Kiểm tra và thêm cột ngay_sinh
-- Nếu cột chưa tồn tại, sẽ thêm vào sau cột so_cccd (hoặc address nếu so_cccd không có)

SET @column_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'users' 
    AND COLUMN_NAME = 'ngay_sinh'
);

SET @so_cccd_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'users' 
    AND COLUMN_NAME = 'so_cccd'
);

SET @after_column = IF(@so_cccd_exists > 0, 'so_cccd', 'address');

SET @sql_ngay_sinh = IF(
    @column_exists = 0,
    CONCAT('ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `', @after_column, '`'),
    'SELECT "Cột ngay_sinh đã tồn tại" AS message'
);

PREPARE stmt FROM @sql_ngay_sinh;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Kiểm tra và thêm cột gioi_tinh
SET @column_exists_gioi_tinh = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'users' 
    AND COLUMN_NAME = 'gioi_tinh'
);

SET @sql_gioi_tinh = IF(
    @column_exists_gioi_tinh = 0,
    'ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM(\'Nam\', \'Nu\', \'Khac\') NULL AFTER `ngay_sinh`',
    'SELECT "Cột gioi_tinh đã tồn tại" AS message'
);

PREPARE stmt2 FROM @sql_gioi_tinh;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;

-- Kiểm tra kết quả
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'users'
AND COLUMN_NAME IN ('ngay_sinh', 'gioi_tinh')
ORDER BY ORDINAL_POSITION;

-- ============================================
-- Nếu script trên không hoạt động, chạy các lệnh đơn giản sau:
-- ============================================

-- ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `so_cccd`;
-- ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`;

-- Nếu cột so_cccd không tồn tại, dùng:
-- ALTER TABLE `users` ADD COLUMN `ngay_sinh` DATE NULL AFTER `address`;
-- ALTER TABLE `users` ADD COLUMN `gioi_tinh` ENUM('Nam', 'Nu', 'Khac') NULL AFTER `ngay_sinh`;

