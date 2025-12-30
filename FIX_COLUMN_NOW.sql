-- Chạy file này trong phpMyAdmin để thêm cột anh_hoan_tra
-- Hoặc copy và paste vào tab SQL trong phpMyAdmin

-- Kiểm tra và thêm cột nếu chưa có
SET @column_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'borrows'
    AND COLUMN_NAME = 'anh_hoan_tra'
);

-- Nếu cột chưa tồn tại, thêm vào
SET @sql = IF(@column_exists = 0,
    -- Kiểm tra xem có cột tinh_trang_sach không
    IF(EXISTS(
        SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'borrows'
        AND COLUMN_NAME = 'tinh_trang_sach'
    ),
    -- Có tinh_trang_sach, thêm sau nó
    'ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT ''Ảnh minh chứng hoàn trả sách từ khách hàng''',
    -- Không có tinh_trang_sach, thêm vào cuối
    'ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL COMMENT ''Ảnh minh chứng hoàn trả sách từ khách hàng'''
    ),
    'SELECT ''Column already exists'' AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Hoặc đơn giản hơn, chỉ cần chạy lệnh này:
-- ALTER TABLE `borrows` ADD COLUMN `anh_hoan_tra` VARCHAR(255) NULL AFTER `tinh_trang_sach` COMMENT 'Ảnh minh chứng hoàn trả sách từ khách hàng';
