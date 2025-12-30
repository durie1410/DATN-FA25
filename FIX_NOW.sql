-- CHẠY FILE NÀY TRỰC TIẾP TRONG PHPMYADMIN HOẶC MYSQL CLIENT
-- Để thêm các cột xác nhận khách hàng vào bảng borrows

-- Thêm cột customer_confirmed_delivery
ALTER TABLE borrows 
ADD COLUMN IF NOT EXISTS customer_confirmed_delivery TINYINT(1) NOT NULL DEFAULT 0 
COMMENT 'Khách hàng đã xác nhận nhận sách';

-- Thêm cột customer_confirmed_delivery_at  
ALTER TABLE borrows 
ADD COLUMN IF NOT EXISTS customer_confirmed_delivery_at TIMESTAMP NULL DEFAULT NULL 
COMMENT 'Thời gian khách hàng xác nhận nhận sách';

-- Kiểm tra kết quả
SHOW COLUMNS FROM borrows LIKE 'customer_confirmed%';
