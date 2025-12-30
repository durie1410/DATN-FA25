-- Script tạo bảng wallets và wallet_transactions
-- Chạy file này trong phpMyAdmin hoặc MySQL client nếu migrations không hoạt động
-- HƯỚNG DẪN: Copy toàn bộ nội dung file này và paste vào phpMyAdmin > SQL > Go

-- Xóa bảng cũ nếu tồn tại (để tạo lại)
DROP TABLE IF EXISTS `wallet_transactions`;
DROP TABLE IF EXISTS `wallets`;

-- Tạo bảng wallets
CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Số dư ví',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Ví có đang hoạt động không',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallets_user_id_unique` (`user_id`),
  KEY `wallets_user_id_index` (`user_id`),
  CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng wallet_transactions
CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'Loại giao dịch: deposit (nạp), refund (hoàn cọc), withdraw (rút), payment (thanh toán)',
  `amount` decimal(15,2) NOT NULL COMMENT 'Số tiền',
  `balance_before` decimal(15,2) NOT NULL COMMENT 'Số dư trước giao dịch',
  `balance_after` decimal(15,2) NOT NULL COMMENT 'Số dư sau giao dịch',
  `description` varchar(255) DEFAULT NULL COMMENT 'Mô tả giao dịch',
  `reference_type` varchar(255) DEFAULT NULL COMMENT 'Loại tham chiếu: App\\\\Models\\\\Borrow, App\\\\Models\\\\Order, etc.',
  `reference_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID của đối tượng tham chiếu',
  `status` varchar(255) NOT NULL DEFAULT 'completed' COMMENT 'Trạng thái: pending, completed, failed, cancelled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  KEY `wallet_transactions_wallet_id_type_index` (`wallet_id`,`type`),
  KEY `wallet_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `wallet_transactions_created_at_index` (`created_at`),
  CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



