-- CHẠY FILE NÀY TRỰC TIẾP TRONG PHPMYADMIN HOẶC MYSQL CLIENT
-- Để thêm các cột từ chối nhận sách vào bảng borrows
-- LƯU Ý: Nếu cột đã tồn tại, sẽ báo lỗi nhưng không sao, bỏ qua và chạy tiếp

-- Thêm cột customer_rejected_delivery
ALTER TABLE borrows 
ADD COLUMN customer_rejected_delivery TINYINT(1) NOT NULL DEFAULT 0 
COMMENT 'Khách hàng đã từ chối nhận sách';

-- Thêm cột customer_rejected_delivery_at  
ALTER TABLE borrows 
ADD COLUMN customer_rejected_delivery_at TIMESTAMP NULL DEFAULT NULL 
COMMENT 'Thời gian khách hàng từ chối nhận sách';

-- Thêm cột customer_rejection_reason
ALTER TABLE borrows 
ADD COLUMN customer_rejection_reason TEXT NULL DEFAULT NULL 
COMMENT 'Lý do khách hàng từ chối nhận sách';

-- Kiểm tra kết quả
SHOW COLUMNS FROM borrows LIKE 'customer_rejected%';
SHOW COLUMNS FROM borrows LIKE 'customer_rejection%';
