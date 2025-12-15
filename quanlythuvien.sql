-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th12 03, 2025 lúc 05:34 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlythuvien`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `url`, `method`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-30 04:18:35', '2025-10-30 04:18:35'),
(2, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-30 07:01:59', '2025-10-30 07:01:59'),
(3, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-10-30 08:08:27', '2025-10-30 08:08:27'),
(4, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-30 08:08:40', '2025-10-30 08:08:40'),
(5, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-10-30 08:08:48', '2025-10-30 08:08:48'),
(6, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-30 08:09:01', '2025-10-30 08:09:01'),
(7, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-10-30 08:09:35', '2025-10-30 08:09:35'),
(8, NULL, 'created', 'App\\Models\\User', 24, '[]', '{\"id\": 24, \"name\": \"hoang\", \"role\": \"user\", \"email\": \"librariann@library.com\", \"password\": \"$2y$10$n0T2FSYy4QGuxM7RDbKK0uzSEMQ1PAFYvt5Lh0WB3CVvnrlzxzZG.\", \"created_at\": \"2025-10-30 15:10:10\", \"updated_at\": \"2025-10-30 15:10:10\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/register', 'POST', 'New user registered', '2025-10-30 08:10:10', '2025-10-30 08:10:10'),
(9, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-31 02:41:07', '2025-10-31 02:41:07'),
(10, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-10-31 02:58:58', '2025-10-31 02:58:58'),
(11, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-31 03:18:02', '2025-10-31 03:18:02'),
(12, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-31 06:10:07', '2025-10-31 06:10:07'),
(13, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-10-31 06:56:37', '2025-10-31 06:56:37'),
(14, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-31 06:57:04', '2025-10-31 06:57:04'),
(15, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-10-31 06:57:28', '2025-10-31 06:57:28'),
(16, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-10-31 07:02:44', '2025-10-31 07:02:44'),
(17, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-03 08:07:50', '2025-11-03 08:07:50'),
(18, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-03 10:01:53', '2025-11-03 10:01:53'),
(19, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-03 10:01:59', '2025-11-03 10:01:59'),
(20, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-03 23:09:42', '2025-11-03 23:09:42'),
(21, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-03 23:10:59', '2025-11-03 23:10:59'),
(22, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-03 23:11:05', '2025-11-03 23:11:05'),
(23, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-04 06:47:04', '2025-11-04 06:47:04'),
(24, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-04 06:47:12', '2025-11-04 06:47:12'),
(25, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-04 06:47:20', '2025-11-04 06:47:20'),
(26, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-04 06:47:27', '2025-11-04 06:47:27'),
(27, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-04 06:47:35', '2025-11-04 06:47:35'),
(28, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-04 07:37:33', '2025-11-04 07:37:33'),
(29, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-04 07:38:12', '2025-11-04 07:38:12'),
(30, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 00:22:33', '2025-11-05 00:22:33'),
(31, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 00:26:56', '2025-11-05 00:26:56'),
(32, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 00:27:06', '2025-11-05 00:27:06'),
(33, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 00:27:15', '2025-11-05 00:27:15'),
(34, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 00:30:00', '2025-11-05 00:30:00'),
(35, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 02:27:26', '2025-11-05 02:27:26'),
(36, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 02:37:44', '2025-11-05 02:37:44'),
(37, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 02:37:51', '2025-11-05 02:37:51'),
(38, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 05:40:54', '2025-11-05 05:40:54'),
(39, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 05:41:00', '2025-11-05 05:41:00'),
(40, 25, 'created', 'App\\Models\\User', 25, '[]', '{\"id\": 25, \"name\": \"hoang\", \"role\": \"user\", \"email\": \"hoangproxz123@gmail.com\", \"password\": \"$2y$10$HMegyZQfb3h/tX2eqbHz8eyi1fBlarm3dlKtLMx7wRHCDZctaZsby\", \"created_at\": \"2025-11-05 12:41:26\", \"updated_at\": \"2025-11-05 12:41:26\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/register', 'POST', 'New user registered', '2025-11-05 05:41:26', '2025-11-05 05:41:26'),
(41, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 05:42:41', '2025-11-05 05:42:41'),
(42, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 05:42:47', '2025-11-05 05:42:47'),
(43, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 05:54:51', '2025-11-05 05:54:51'),
(44, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 06:15:49', '2025-11-05 06:15:49'),
(45, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 06:16:36', '2025-11-05 06:16:36'),
(46, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 06:16:41', '2025-11-05 06:16:41'),
(47, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 08:14:29', '2025-11-05 08:14:29'),
(48, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 08:14:37', '2025-11-05 08:14:37'),
(49, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 08:34:13', '2025-11-05 08:34:13'),
(50, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 08:34:19', '2025-11-05 08:34:19'),
(51, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:07:52', '2025-11-05 09:07:52'),
(52, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:07:58', '2025-11-05 09:07:58'),
(53, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:11:53', '2025-11-05 09:11:53'),
(54, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:12:02', '2025-11-05 09:12:02'),
(55, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:12:51', '2025-11-05 09:12:51'),
(56, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:13:00', '2025-11-05 09:13:00'),
(57, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:22:48', '2025-11-05 09:22:48'),
(58, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:22:53', '2025-11-05 09:22:53'),
(59, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:28:33', '2025-11-05 09:28:33'),
(60, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:28:40', '2025-11-05 09:28:40'),
(61, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:35:28', '2025-11-05 09:35:28'),
(62, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:35:33', '2025-11-05 09:35:33'),
(63, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 09:39:19', '2025-11-05 09:39:19'),
(64, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 09:39:25', '2025-11-05 09:39:25'),
(65, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 10:31:38', '2025-11-05 10:31:38'),
(66, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 10:31:44', '2025-11-05 10:31:44'),
(67, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 10:52:49', '2025-11-05 10:52:49'),
(68, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 10:52:55', '2025-11-05 10:52:55'),
(69, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 10:54:47', '2025-11-05 10:54:47'),
(70, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 10:54:53', '2025-11-05 10:54:53'),
(71, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 20:39:13', '2025-11-05 20:39:13'),
(72, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 20:41:14', '2025-11-05 20:41:14'),
(73, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 20:41:19', '2025-11-05 20:41:19'),
(74, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-05 20:43:44', '2025-11-05 20:43:44'),
(75, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-05 20:43:50', '2025-11-05 20:43:50'),
(76, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-06 12:02:14', '2025-11-06 12:02:14'),
(77, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-06 12:02:30', '2025-11-06 12:02:30'),
(78, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-06 12:05:54', '2025-11-06 12:05:54'),
(79, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-06 12:06:02', '2025-11-06 12:06:02'),
(80, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-06 12:08:55', '2025-11-06 12:08:55'),
(81, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-06 12:14:27', '2025-11-06 12:14:27'),
(82, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-06 19:37:55', '2025-11-06 19:37:55'),
(83, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-06 19:38:06', '2025-11-06 19:38:06'),
(84, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-06 19:38:11', '2025-11-06 19:38:11'),
(85, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 08:35:33', '2025-11-07 08:35:33'),
(86, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 08:37:59', '2025-11-07 08:37:59'),
(87, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 08:38:18', '2025-11-07 08:38:18'),
(88, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 09:13:25', '2025-11-07 09:13:25'),
(89, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 09:14:28', '2025-11-07 09:14:28'),
(90, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 09:14:36', '2025-11-07 09:14:36'),
(91, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 09:24:39', '2025-11-07 09:24:39'),
(92, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 09:24:47', '2025-11-07 09:24:47'),
(93, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 09:30:34', '2025-11-07 09:30:34'),
(94, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 09:33:19', '2025-11-07 09:33:19'),
(95, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:06:43', '2025-11-07 10:06:43'),
(96, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:06:52', '2025-11-07 10:06:52'),
(97, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:10:20', '2025-11-07 10:10:20'),
(98, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:11:11', '2025-11-07 10:11:11'),
(99, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:12:56', '2025-11-07 10:12:56'),
(100, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:13:21', '2025-11-07 10:13:21'),
(101, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:15:03', '2025-11-07 10:15:03'),
(102, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:18:04', '2025-11-07 10:18:04'),
(103, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:20:41', '2025-11-07 10:20:41'),
(104, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:20:58', '2025-11-07 10:20:58'),
(105, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:29:39', '2025-11-07 10:29:39'),
(106, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:29:55', '2025-11-07 10:29:55'),
(107, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:30:37', '2025-11-07 10:30:37'),
(108, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:30:48', '2025-11-07 10:30:48'),
(109, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 10:42:32', '2025-11-07 10:42:32'),
(110, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 10:42:38', '2025-11-07 10:42:38'),
(111, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 11:15:19', '2025-11-07 11:15:19'),
(112, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 11:15:24', '2025-11-07 11:15:24'),
(113, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 11:20:35', '2025-11-07 11:20:35'),
(114, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 11:20:52', '2025-11-07 11:20:52'),
(115, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 16:26:12', '2025-11-07 16:26:12'),
(116, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-07 16:27:53', '2025-11-07 16:27:53'),
(117, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-07 16:28:11', '2025-11-07 16:28:11'),
(118, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-08 06:27:42', '2025-11-08 06:27:42'),
(119, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-08 07:00:08', '2025-11-08 07:00:08'),
(120, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-08 07:00:16', '2025-11-08 07:00:16'),
(121, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-10 17:45:11', '2025-11-10 17:45:11'),
(122, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-11 08:59:43', '2025-11-11 08:59:43'),
(123, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-11 13:45:37', '2025-11-11 13:45:37'),
(124, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-11 13:46:15', '2025-11-11 13:46:15'),
(125, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-11 13:46:22', '2025-11-11 13:46:22'),
(126, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 05:38:31', '2025-11-12 05:38:31'),
(127, 1, 'updated', 'App\\Models\\Book', 83, '{\"id\": 83, \"gia\": \"2334000.00\", \"mo_ta\": \"f\", \"tac_gia\": \"Phạm Thị D\", \"hinh_anh\": \"books/ba30ab37-362e-43f9-902b-37ff3f0002cc.jpg\", \"ten_sach\": \"Kinh tế học\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-11-11 16:53:21\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 12:54:44\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 7, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/9c4e8806-6cd5-440c-b64e-b96ebd131810.jpg\", \"updated_at\": \"2025-11-12 12:56:16\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/83', 'PUT', 'Book \'Kinh tế học\' updated', '2025-11-12 05:56:16', '2025-11-12 05:56:16'),
(128, 1, 'updated', 'App\\Models\\Book', 83, '{\"id\": 83, \"gia\": \"2334000.00\", \"mo_ta\": \"f\", \"tac_gia\": \"Phạm Thị D\", \"hinh_anh\": \"books/9c4e8806-6cd5-440c-b64e-b96ebd131810.jpg\", \"ten_sach\": \"Kinh tế học\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-11-11 16:53:21\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 12:56:16\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 7, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/90fde21b-41da-47ed-bf7c-d86b06d8e06d.jpg\", \"updated_at\": \"2025-11-12 12:56:31\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/83', 'PUT', 'Book \'Kinh tế học\' updated', '2025-11-12 05:56:31', '2025-11-12 05:56:31'),
(129, 1, 'updated', 'App\\Models\\Book', 83, '{\"id\": 83, \"gia\": \"2334000.00\", \"mo_ta\": \"f\", \"tac_gia\": \"Phạm Thị D\", \"hinh_anh\": \"books/90fde21b-41da-47ed-bf7c-d86b06d8e06d.jpg\", \"ten_sach\": \"Kinh tế học\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-11-11 16:53:21\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 12:56:31\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 7, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/59438c08-02d4-4fb6-a2b5-2fa343fddc8a.png\", \"updated_at\": \"2025-11-12 12:58:48\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/83', 'PUT', 'Book \'Kinh tế học\' updated', '2025-11-12 05:58:48', '2025-11-12 05:58:48'),
(130, 1, 'updated', 'App\\Models\\Book', 83, '{\"id\": 83, \"gia\": \"2334000.00\", \"mo_ta\": \"f\", \"tac_gia\": \"Phạm Thị D\", \"hinh_anh\": \"books/59438c08-02d4-4fb6-a2b5-2fa343fddc8a.png\", \"ten_sach\": \"Kinh tế học\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-11-11 16:53:21\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 12:58:48\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 7, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/887e2de2-aa36-4aed-91c4-feffb02f0be9.jpg\", \"updated_at\": \"2025-11-12 12:59:06\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/83', 'PUT', 'Book \'Kinh tế học\' updated', '2025-11-12 05:59:06', '2025-11-12 05:59:06'),
(131, 1, 'updated', 'App\\Models\\Book', 82, '{\"id\": 82, \"gia\": \"26000.00\", \"mo_ta\": \"mới\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"ten_sach\": \"Văn học Việt Nam\", \"dinh_dang\": \"Paperback\", \"created_at\": \"2025-11-11 16:41:23\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-11 16:41:23\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/bb1f2f79-d9a9-4dd2-8cb2-a5c2db2ba362.png\", \"dinh_dang\": \"Sách giấy\", \"updated_at\": \"2025-11-12 13:03:22\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/82', 'PUT', 'Book \'Văn học Việt Nam\' updated', '2025-11-12 06:03:22', '2025-11-12 06:03:22'),
(132, 1, 'updated', 'App\\Models\\Book', 84, '{\"id\": 84, \"gia\": \"6000000.00\", \"mo_ta\": \"gg\", \"tac_gia\": \"Trần Thị B\", \"hinh_anh\": null, \"ten_sach\": \"Văn học Việt Nam\", \"dinh_dang\": \"Paperback\", \"created_at\": \"2025-11-12 13:05:05\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 13:05:05\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/04977b84-42b8-499e-80ef-8c3a2fd5020f.png\", \"dinh_dang\": \"Sách giấy\", \"updated_at\": \"2025-11-12 13:05:45\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/84', 'PUT', 'Book \'Văn học Việt Nam\' updated', '2025-11-12 06:05:45', '2025-11-12 06:05:45'),
(133, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/1762236678_1402.jpg\", \"so_luong\": 0, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-04 06:11:18\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.70\"}', '{\"so_luong\": \"8\", \"updated_at\": \"2025-11-12 14:29:47\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-12 07:29:47', '2025-11-12 07:29:47'),
(134, 1, 'updated', 'App\\Models\\Book', 2, '{\"id\": 2, \"gia\": \"220000.00\", \"mo_ta\": \"Giới thiệu về khoa học dữ liệu và machine learning\", \"tac_gia\": \"TS. Trần Thị E\", \"hinh_anh\": \"books/1762236687_1402.jpg\", \"so_luong\": 0, \"ten_sach\": \"Khoa học dữ liệu\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-04 06:11:27\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 650, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 180, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.40\"}', '{\"so_luong\": \"14\", \"updated_at\": \"2025-11-12 14:37:03\", \"nha_xuat_ban_id\": \"5\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/2', 'PUT', 'Book \'Khoa học dữ liệu\' updated', '2025-11-12 07:37:03', '2025-11-12 07:37:03'),
(135, 1, 'updated', 'App\\Models\\Book', 2, '{\"id\": 2, \"gia\": \"220000.00\", \"mo_ta\": \"Giới thiệu về khoa học dữ liệu và machine learning\", \"tac_gia\": \"TS. Trần Thị E\", \"hinh_anh\": \"books/1762236687_1402.jpg\", \"so_luong\": 14, \"ten_sach\": \"Khoa học dữ liệu\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 14:37:03\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 650, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 180, \"nha_xuat_ban_id\": 5, \"danh_gia_trung_binh\": \"4.40\"}', '{\"so_luong\": \"12\", \"updated_at\": \"2025-11-12 14:40:33\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/2', 'PUT', 'Book \'Khoa học dữ liệu\' updated', '2025-11-12 07:40:34', '2025-11-12 07:40:34'),
(136, 1, 'updated', 'App\\Models\\Book', 6, '{\"id\": 6, \"gia\": \"60000.00\", \"mo_ta\": \"Tác phẩm kinh điển của văn học Việt Nam\", \"tac_gia\": \"Nguyễn Du\", \"hinh_anh\": \"books/1762236717_1249.jpg\", \"so_luong\": 0, \"ten_sach\": \"Truyện Kiều\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-04 09:06:30\", \"category_id\": 5, \"is_featured\": 1, \"so_luot_xem\": 5001, \"nam_xuat_ban\": \"1950\", \"so_luong_ban\": 2500, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.90\"}', '{\"so_luong\": \"5\", \"updated_at\": \"2025-11-12 14:47:26\", \"nha_xuat_ban_id\": \"7\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/6', 'PUT', 'Book \'Truyện Kiều\' updated', '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(137, 1, 'updated', 'App\\Models\\Book', 3, '{\"id\": 3, \"gia\": \"95000.00\", \"mo_ta\": \"Lịch sử Việt Nam từ thế kỷ 20 đến nay\", \"tac_gia\": \"Lê Văn F\", \"hinh_anh\": \"books/1762236696_2389.png\", \"so_luong\": 0, \"ten_sach\": \"Lịch sử Việt Nam hiện đại\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-04 14:33:02\", \"category_id\": 3, \"is_featured\": 0, \"so_luot_xem\": 452, \"nam_xuat_ban\": \"2022\", \"so_luong_ban\": 120, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.20\"}', '{\"so_luong\": \"2\", \"updated_at\": \"2025-11-12 15:21:11\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/3', 'PUT', 'Book \'Lịch sử Việt Nam hiện đại\' updated', '2025-11-12 08:21:11', '2025-11-12 08:21:11'),
(138, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/1762236678_1402.jpg\", \"so_luong\": 8, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 14:29:47\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.70\"}', '{\"updated_at\": \"2025-11-12 15:26:14\", \"nha_xuat_ban_id\": \"8\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-12 08:26:14', '2025-11-12 08:26:14'),
(139, 1, 'updated', 'App\\Models\\Book', 4, '{\"id\": 4, \"gia\": \"0.00\", \"mo_ta\": \"Sách học PHP từ cơ bản\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": \"books/1761919414_1345.jpg\", \"so_luong\": 0, \"ten_sach\": \"Lập trình PHP cơ bản\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-10-31 14:03:34\", \"category_id\": 4, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"0.00\"}', '{\"gia\": \"60000\", \"updated_at\": \"2025-11-12 15:26:43\", \"nha_xuat_ban_id\": \"7\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/4', 'PUT', 'Book \'Lập trình PHP cơ bản\' updated', '2025-11-12 08:26:43', '2025-11-12 08:26:43'),
(140, 1, 'updated', 'App\\Models\\Book', 85, '{\"id\": 85, \"gia\": \"4000000.00\", \"mo_ta\": \"k\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"so_luong\": 6, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"dinh_dang\": \"Paperback\", \"created_at\": \"2025-11-12 14:45:03\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 14:45:07\", \"category_id\": 6, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 3, \"danh_gia_trung_binh\": \"0.00\"}', '{\"gia\": \"30000\", \"hinh_anh\": \"books/b0826e7b-862d-4c9d-a4de-1062695f2779.jpg\", \"dinh_dang\": \"Sách giấy\", \"updated_at\": \"2025-11-12 15:37:26\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/85', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-12 08:37:26', '2025-11-12 08:37:26'),
(141, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 08:57:45', '2025-11-12 08:57:45');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `url`, `method`, `description`, `created_at`, `updated_at`) VALUES
(142, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 08:57:52', '2025-11-12 08:57:52'),
(143, 23, 'borrow', 'App\\Models\\Reservation', 162, '[]', '{\"id\": 162, \"notes\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"status\": \"pending\", \"book_id\": 83, \"user_id\": 23, \"priority\": 1, \"reader_id\": 17, \"created_at\": \"2025-11-12 16:03:28\", \"updated_at\": \"2025-11-12 16:03:28\", \"expiry_date\": \"2025-11-19 00:00:00\", \"reservation_date\": \"2025-11-12 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Kinh tế học\' created by Người dùng', '2025-11-12 09:03:28', '2025-11-12 09:03:28'),
(144, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:07:21', '2025-11-12 09:07:21'),
(145, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:07:26', '2025-11-12 09:07:26'),
(146, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:13:43', '2025-11-12 09:13:43'),
(147, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:13:48', '2025-11-12 09:13:48'),
(148, 23, 'borrow', 'App\\Models\\Reservation', 163, '[]', '{\"id\": 163, \"notes\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"status\": \"pending\", \"book_id\": 84, \"user_id\": 23, \"priority\": 1, \"reader_id\": 17, \"created_at\": \"2025-11-12 16:18:51\", \"updated_at\": \"2025-11-12 16:18:51\", \"expiry_date\": \"2025-11-19 00:00:00\", \"reservation_date\": \"2025-11-12 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Văn học Việt Nam\' created by Người dùng', '2025-11-12 09:18:51', '2025-11-12 09:18:51'),
(149, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:19:21', '2025-11-12 09:19:21'),
(150, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:19:26', '2025-11-12 09:19:26'),
(151, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:32:34', '2025-11-12 09:32:34'),
(152, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:32:38', '2025-11-12 09:32:38'),
(153, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:32:45', '2025-11-12 09:32:45'),
(154, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:32:49', '2025-11-12 09:32:49'),
(155, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:33:56', '2025-11-12 09:33:56'),
(156, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:34:01', '2025-11-12 09:34:01'),
(157, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:35:10', '2025-11-12 09:35:10'),
(158, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-12 09:35:14', '2025-11-12 09:35:14'),
(159, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-12 09:36:03', '2025-11-12 09:36:03'),
(160, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-13 01:43:16', '2025-11-13 01:43:16'),
(161, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-13 01:50:08', '2025-11-13 01:50:08'),
(162, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-13 01:50:18', '2025-11-13 01:50:18'),
(163, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-13 14:24:41', '2025-11-13 14:24:41'),
(164, 1, 'borrow', 'App\\Models\\Reservation', 165, '[]', '{\"id\": 165, \"notes\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"status\": \"pending\", \"book_id\": 84, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-13 21:26:43\", \"updated_at\": \"2025-11-13 21:26:43\", \"expiry_date\": \"2025-11-20 00:00:00\", \"reservation_date\": \"2025-11-13 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Văn học Việt Nam\' created by Super Admin', '2025-11-13 14:26:43', '2025-11-13 14:26:43'),
(165, 1, 'updated', 'App\\Models\\Book', 9, '{\"id\": 9, \"gia\": \"150000.00\", \"mo_ta\": \"Sách hướng dẫn lập trình PHP từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": \"books/1761919483_28879.png\", \"so_luong\": 0, \"ten_sach\": \"Lập trình PHP\", \"dinh_dang\": \"Sách giấy\", \"created_at\": \"2025-10-26 13:13:37\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-07 17:10:38\", \"category_id\": 4, \"is_featured\": 0, \"so_luot_xem\": 1201, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 45, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.50\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/9', 'PUT', 'Book \'Lập trình PHP\' updated', '2025-11-13 14:30:07', '2025-11-13 14:30:07'),
(166, 1, 'updated', 'App\\Models\\Book', 86, '{\"id\": 86, \"gia\": \"20000.00\", \"mo_ta\": \"k\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"so_luong\": 5, \"ten_sach\": \"Văn học Việt Nam\", \"dinh_dang\": \"Paperback\", \"created_at\": \"2025-11-13 21:27:59\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-13 21:28:17\", \"category_id\": 6, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/caf33ca3-5549-480f-b38a-b3f01f46b2eb.jpg\", \"dinh_dang\": \"Sách giấy\", \"updated_at\": \"2025-11-13 21:31:35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/86', 'PUT', 'Book \'Văn học Việt Nam\' updated', '2025-11-13 14:31:35', '2025-11-13 14:31:35'),
(167, 1, 'borrow', 'App\\Models\\Reservation', 166, '[]', '{\"id\": 166, \"notes\": \"Đặt trước sách (Yêu cầu mượn 14 ngày)\", \"status\": \"pending\", \"book_id\": 85, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-13 21:36:57\", \"updated_at\": \"2025-11-13 21:36:57\", \"expiry_date\": \"2025-11-20 00:00:00\", \"reservation_date\": \"2025-11-13 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Lập trình Laravel từ A-Z\' created by Super Admin', '2025-11-13 14:36:57', '2025-11-13 14:36:57'),
(168, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-14 06:27:49', '2025-11-14 06:27:49'),
(169, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/1762236678_1402.jpg\", \"so_luong\": 0, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-12 15:26:14\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"4.70\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-14 09:55:47', '2025-11-14 09:55:47'),
(170, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-15 06:26:21', '2025-11-15 06:26:21'),
(171, 1, 'borrow', 'App\\Models\\Reservation', 167, '[]', '{\"id\": 167, \"notes\": \"Yêu cầu mượn sách - 1 ngày (Yêu cầu mượn 1 ngày)\", \"status\": \"pending\", \"book_id\": 89, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-15 21:02:06\", \"updated_at\": \"2025-11-15 21:02:06\", \"expiry_date\": \"2025-11-22 00:00:00\", \"reservation_date\": \"2025-11-15 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/borrow-book', 'POST', 'Borrow request for \'sách C\' created by Super Admin', '2025-11-15 14:02:06', '2025-11-15 14:02:06'),
(172, 1, 'updated', 'App\\Models\\Book', 10, '{\"id\": 10, \"gia\": \"200000.00\", \"mo_ta\": \"Tài liệu chi tiết về Laravel Framework\", \"tac_gia\": \"Trần Thị B\", \"hinh_anh\": \"books/1761919496_1756.jpg\", \"so_luong\": 0, \"ten_sach\": \"Laravel Framework\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:37\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-07 17:17:29\", \"category_id\": 4, \"is_featured\": 0, \"so_luot_xem\": 957, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 32, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.80\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/admin/books/10', 'PUT', 'Book \'Laravel Framework\' updated', '2025-11-15 14:17:08', '2025-11-15 14:17:08'),
(173, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-16 04:44:53', '2025-11-16 04:44:53'),
(174, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 08:22:35', '2025-11-16 08:22:35'),
(175, 1, 'updated', 'App\\Models\\Book', 87, '{\"id\": 87, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"so_luong\": 20, \"ten_sach\": \"sách A\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-14 16:57:55\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-15 21:21:12\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 2, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/a61a1df4-5354-4f72-b32d-f1045cf3bebe.jpg\", \"updated_at\": \"2025-11-16 15:22:57\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/87', 'PUT', 'Book \'sách A\' updated', '2025-11-16 08:22:57', '2025-11-16 08:22:57'),
(176, 1, 'borrow', 'App\\Models\\Reservation', 168, '[]', '{\"id\": 168, \"notes\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"status\": \"pending\", \"book_id\": 38, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-16 16:05:58\", \"updated_at\": \"2025-11-16 16:05:58\", \"expiry_date\": \"2025-11-23 00:00:00\", \"reservation_date\": \"2025-11-16 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Dolor eaque mollitia.\' created by Super Admin', '2025-11-16 09:05:58', '2025-11-16 09:05:58'),
(177, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-16 09:39:36', '2025-11-16 09:39:36'),
(178, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 09:39:54', '2025-11-16 09:39:54'),
(179, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-16 09:41:34', '2025-11-16 09:41:34'),
(180, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 09:41:40', '2025-11-16 09:41:40'),
(181, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-16 09:42:14', '2025-11-16 09:42:14'),
(182, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 09:42:22', '2025-11-16 09:42:22'),
(183, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-16 09:44:30', '2025-11-16 09:44:30'),
(184, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 09:44:35', '2025-11-16 09:44:35'),
(185, 1, 'borrow', 'App\\Models\\Reservation', 169, '[]', '{\"id\": 169, \"notes\": \"Yêu cầu mượn sách - 22 ngày (Yêu cầu mượn 22 ngày)\", \"status\": \"pending\", \"book_id\": 12, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-16 17:16:32\", \"updated_at\": \"2025-11-16 17:16:32\", \"expiry_date\": \"2025-11-23 00:00:00\", \"reservation_date\": \"2025-11-16 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Lịch sử Việt Nam\' created by Super Admin', '2025-11-16 10:16:32', '2025-11-16 10:16:32'),
(186, 1, 'borrow', 'App\\Models\\Reservation', 170, '[]', '{\"id\": 170, \"notes\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"status\": \"pending\", \"book_id\": 44, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-16 17:50:57\", \"updated_at\": \"2025-11-16 17:50:57\", \"expiry_date\": \"2025-11-23 00:00:00\", \"reservation_date\": \"2025-11-16 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Sit aliquid omnis ut.\' created by Super Admin', '2025-11-16 10:50:57', '2025-11-16 10:50:57'),
(187, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-16 11:04:13', '2025-11-16 11:04:13'),
(188, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 11:04:17', '2025-11-16 11:04:17'),
(189, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-16 11:05:25', '2025-11-16 11:05:25'),
(190, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-16 11:05:30', '2025-11-16 11:05:30'),
(191, 1, 'borrow', 'App\\Models\\Borrow', 211, '[]', '{\"id\": 211, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-16 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-16 18:27:12\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-16 18:27:12\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Truyện Kiều\' created by Super Admin', '2025-11-16 11:27:12', '2025-11-16 11:27:12'),
(192, 1, 'borrow', 'App\\Models\\Borrow', 212, '[]', '{\"id\": 212, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-16 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-16 18:29:17\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-16 18:29:17\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Truyện Kiều\' created by Super Admin', '2025-11-16 11:29:17', '2025-11-16 11:29:17'),
(193, 1, 'borrow', 'App\\Models\\Borrow', 213, '[]', '{\"id\": 213, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-16 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"25000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 25000, \"created_at\": \"2025-11-16 18:36:20\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-16 18:36:20\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Giáo trình Revit Structure theo kết cấu\' created by Super Admin', '2025-11-16 11:36:20', '2025-11-16 11:36:20'),
(194, 1, 'borrow', 'App\\Models\\Borrow', 214, '[]', '{\"id\": 214, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-16 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"125000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 125000, \"created_at\": \"2025-11-16 18:39:23\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-16 18:39:23\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Giáo trình Revit Structure theo kết cấu\' created by Super Admin', '2025-11-16 11:39:23', '2025-11-16 11:39:23'),
(195, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-17 09:39:05', '2025-11-17 09:39:05'),
(196, 1, 'borrow', 'App\\Models\\Borrow', 215, '[]', '{\"id\": 215, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-17 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-17 16:41:03\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-17 16:41:03\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Truyện Kiều\' created by Super Admin', '2025-11-17 09:41:03', '2025-11-17 09:41:03'),
(197, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-17 09:49:51', '2025-11-17 09:49:51'),
(198, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-17 09:49:56', '2025-11-17 09:49:56'),
(199, 1, 'borrow', 'App\\Models\\Borrow', 216, '[]', '{\"id\": 216, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-17 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"25000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 25000, \"created_at\": \"2025-11-17 16:52:27\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-17 16:52:27\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Giáo trình Revit Structure theo kết cấu\' created by Super Admin', '2025-11-17 09:52:27', '2025-11-17 09:52:27'),
(200, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-17 13:34:25', '2025-11-17 13:34:25'),
(201, 1, 'updated', 'App\\Models\\Book', 2, '{\"id\": 2, \"gia\": \"220000.00\", \"mo_ta\": \"Giới thiệu về khoa học dữ liệu và machine learning\", \"tac_gia\": \"TS. Trần Thị E\", \"hinh_anh\": \"books/1762236687_1402.jpg\", \"so_luong\": 0, \"ten_sach\": \"Khoa học dữ liệu\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-16 17:51:44\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 651, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 180, \"nha_xuat_ban_id\": 5, \"danh_gia_trung_binh\": \"4.40\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/2', 'PUT', 'Book \'Khoa học dữ liệu\' updated', '2025-11-17 14:40:59', '2025-11-17 14:40:59'),
(202, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-17 14:59:34', '2025-11-17 14:59:34'),
(203, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-17 15:00:48', '2025-11-17 15:00:48'),
(204, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-17 15:00:53', '2025-11-17 15:00:53'),
(205, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-17 15:01:14', '2025-11-17 15:01:14'),
(206, 1, 'updated', 'App\\Models\\Book', 10, '{\"id\": 10, \"gia\": \"200000.00\", \"mo_ta\": \"Tài liệu chi tiết về Laravel Framework\", \"tac_gia\": \"Trần Thị B\", \"hinh_anh\": \"books/1761919496_1756.jpg\", \"so_luong\": 0, \"ten_sach\": \"Laravel Framework\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:37\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-07 17:17:29\", \"category_id\": 4, \"is_featured\": 0, \"so_luot_xem\": 957, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 32, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"4.80\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/10', 'PUT', 'Book \'Laravel Framework\' updated', '2025-11-17 15:03:36', '2025-11-17 15:03:36'),
(207, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-20 17:03:45', '2025-11-20 17:03:45'),
(208, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-20 17:04:26', '2025-11-20 17:04:26'),
(209, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-20 17:04:35', '2025-11-20 17:04:35'),
(210, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-20 17:11:57', '2025-11-20 17:11:57'),
(211, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-20 17:12:02', '2025-11-20 17:12:02'),
(212, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-20 17:12:24', '2025-11-20 17:12:24'),
(213, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-20 17:12:30', '2025-11-20 17:12:30'),
(214, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-20 17:15:54', '2025-11-20 17:15:54'),
(215, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-20 17:15:58', '2025-11-20 17:15:58'),
(216, 1, 'borrow', 'App\\Models\\Reservation', 1, '[]', '{\"id\": 1, \"notes\": \"Đặt trước sách (Đặt trước 14 ngày)\", \"status\": \"pending\", \"book_id\": 52, \"user_id\": 1, \"priority\": 1, \"reader_id\": 18, \"created_at\": \"2025-11-21 01:17:27\", \"updated_at\": \"2025-11-21 01:17:27\", \"expiry_date\": \"2025-11-28 00:00:00\", \"reservation_date\": \"2025-11-21 00:00:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Reservation request for \'Itaque minima consequatur quo.\' created by Super Admin', '2025-11-20 18:17:27', '2025-11-20 18:17:27'),
(217, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-20 18:40:43', '2025-11-20 18:40:43'),
(218, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-20 18:40:53', '2025-11-20 18:40:53'),
(219, 1, 'borrow', 'App\\Models\\Borrow', 217, '[]', '{\"id\": 217, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 15 ngày (Yêu cầu mượn 15 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-21 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"15000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 15000, \"created_at\": \"2025-11-21 02:40:29\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-21 02:40:29\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Truyện Kiều\' created by Super Admin', '2025-11-20 19:40:29', '2025-11-20 19:40:29'),
(220, 1, 'borrow', 'App\\Models\\Borrow', 218, '[]', '{\"id\": 218, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-21 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-21 02:48:45\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-21 02:48:45\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Lịch sử Việt Nam\' created by Super Admin', '2025-11-20 19:48:45', '2025-11-20 19:48:45'),
(221, 1, 'borrow', 'App\\Models\\Borrow', 219, '[]', '{\"id\": 219, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-21 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-21 02:49:15\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-21 02:49:15\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Lịch sử Việt Nam\' created by Super Admin', '2025-11-20 19:49:15', '2025-11-20 19:49:15'),
(222, 1, 'borrow', 'App\\Models\\Borrow', 220, '[]', '{\"id\": 220, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-21 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-21 02:55:57\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-21 02:55:57\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Voluptatem asperiores neque.\' created by Super Admin', '2025-11-20 19:55:57', '2025-11-20 19:55:57'),
(223, 1, 'borrow', 'App\\Models\\Borrow', 221, '[]', '{\"id\": 221, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-21 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-21 02:58:06\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-21 02:58:06\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Lịch sử Việt Nam\' created by Super Admin', '2025-11-20 19:58:06', '2025-11-20 19:58:06'),
(224, 1, 'borrow', 'App\\Models\\Borrow', 222, '[]', '{\"id\": 222, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-21 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"25000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 25000, \"created_at\": \"2025-11-21 03:02:20\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-21 03:02:20\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Truyện Kiều\' created by Super Admin', '2025-11-20 20:02:20', '2025-11-20 20:02:20'),
(225, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 03:26:22', '2025-11-21 03:26:22'),
(226, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/1762236678_1402.jpg\", \"so_luong\": 1, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 03:27:05', '2025-11-21 03:27:05'),
(227, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 03:31:39', '2025-11-21 03:31:39'),
(228, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 03:31:49', '2025-11-21 03:31:49'),
(229, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 03:31:56', '2025-11-21 03:31:56'),
(230, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 03:32:05', '2025-11-21 03:32:05'),
(231, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 03:32:12', '2025-11-21 03:32:12'),
(232, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 03:32:20', '2025-11-21 03:32:20'),
(233, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 03:32:55', '2025-11-21 03:32:55'),
(234, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 03:33:03', '2025-11-21 03:33:03'),
(235, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 15:22:15', '2025-11-21 15:22:15'),
(236, 1, 'updated', 'App\\Models\\Book', 4, '{\"id\": 4, \"gia\": \"60000.00\", \"mo_ta\": \"Sách học PHP từ cơ bản\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": \"books/1761919414_1345.jpg\", \"so_luong\": 0, \"ten_sach\": \"Lập trình PHP cơ bản\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 4, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 7, \"danh_gia_trung_binh\": \"0.00\"}', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/4', 'PUT', 'Book \'Lập trình PHP cơ bản\' updated', '2025-11-21 16:00:28', '2025-11-21 16:00:28'),
(237, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 16:47:52', '2025-11-21 16:47:52'),
(238, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 16:48:00', '2025-11-21 16:48:00'),
(239, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 16:54:13', '2025-11-21 16:54:13'),
(240, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 16:54:18', '2025-11-21 16:54:18'),
(241, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 16:57:40', '2025-11-21 16:57:40'),
(242, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 16:57:45', '2025-11-21 16:57:45'),
(243, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 17:01:49', '2025-11-21 17:01:49'),
(244, 25, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 17:01:59', '2025-11-21 17:01:59'),
(245, 25, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 17:02:03', '2025-11-21 17:02:03'),
(246, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 17:02:08', '2025-11-21 17:02:08'),
(247, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 17:05:56', '2025-11-21 17:05:56'),
(248, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 17:06:02', '2025-11-21 17:06:02'),
(249, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 17:10:19', '2025-11-21 17:10:19'),
(250, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 17:10:26', '2025-11-21 17:10:26'),
(251, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 17:10:39', '2025-11-21 17:10:39'),
(252, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 17:10:43', '2025-11-21 17:10:43'),
(253, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 17:17:29', '2025-11-21 17:17:29'),
(254, 1, 'borrow', 'App\\Models\\Borrow', 224, '[]', '{\"id\": 224, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-22 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"125000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 125000, \"created_at\": \"2025-11-22 00:31:51\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-22 00:31:51\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/borrow-book', 'POST', 'Borrow request for \'Văn học Việt Nam\' created by Super Admin', '2025-11-21 17:31:51', '2025-11-21 17:31:51'),
(255, 1, 'updated', 'App\\Models\\Book', 6, '{\"id\": 6, \"gia\": \"60000.00\", \"mo_ta\": \"Tác phẩm kinh điển của văn học Việt Nam\", \"tac_gia\": \"Nguyễn Du\", \"hinh_anh\": \"books/1762236717_1249.jpg\", \"so_luong\": 0, \"ten_sach\": \"Truyện Kiều\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 10:34:48\", \"category_id\": 5, \"is_featured\": 1, \"so_luot_xem\": 5016, \"nam_xuat_ban\": \"1950\", \"so_luong_ban\": 2500, \"nha_xuat_ban_id\": 7, \"danh_gia_trung_binh\": \"0.00\"}', '{\"so_luong\": \"1\", \"updated_at\": \"2025-11-22 00:45:10\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/6', 'PUT', 'Book \'Truyện Kiều\' updated', '2025-11-21 17:45:10', '2025-11-21 17:45:10');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `url`, `method`, `description`, `created_at`, `updated_at`) VALUES
(256, 1, 'updated', 'App\\Models\\Book', 9, '{\"id\": 9, \"gia\": \"150000.00\", \"mo_ta\": \"Sách hướng dẫn lập trình PHP từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": \"books/1761919483_28879.png\", \"so_luong\": 0, \"ten_sach\": \"Lập trình PHP\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:37\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 4, \"is_featured\": 0, \"so_luot_xem\": 1202, \"nam_xuat_ban\": \"2023\", \"so_luong_ban\": 45, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"0.00\"}', '{\"so_luong\": \"2\", \"updated_at\": \"2025-11-22 00:47:57\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/9', 'PUT', 'Book \'Lập trình PHP\' updated', '2025-11-21 17:47:57', '2025-11-21 17:47:57'),
(257, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 18:25:34', '2025-11-21 18:25:34'),
(258, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 18:25:59', '2025-11-21 18:25:59'),
(259, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 19:04:22', '2025-11-21 19:04:22'),
(260, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 19:04:28', '2025-11-21 19:04:28'),
(261, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 19:11:02', '2025-11-21 19:11:02'),
(262, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 19:11:07', '2025-11-21 19:11:07'),
(263, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 19:11:45', '2025-11-21 19:11:45'),
(264, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 19:11:50', '2025-11-21 19:11:50'),
(265, 1, 'updated', 'App\\Models\\Book', 101, '{\"id\": 101, \"gia\": \"30000.00\", \"mo_ta\": \"f\", \"tac_gia\": \"Lê Văn C\", \"hinh_anh\": \"books/a096288a-f6bf-4395-9457-00fd77705624.png\", \"so_luong\": 0, \"ten_sach\": \"Kinh tế học\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-22 02:11:33\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 02:11:33\", \"category_id\": 7, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": null, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/23edae36-20a4-490b-a9c8-44790ba442cd.jpg\", \"updated_at\": \"2025-11-22 02:12:42\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/101', 'PUT', 'Book \'Kinh tế học\' updated', '2025-11-21 19:12:42', '2025-11-21 19:12:42'),
(266, 1, 'updated', 'App\\Models\\Book', 93, '{\"id\": 93, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"so_luong\": 5, \"ten_sach\": \"sách  loại 2\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-16 13:31:52\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 10, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/b6c952d6-7b65-49e8-9d12-6bb0e77614a7.jpg\", \"updated_at\": \"2025-11-22 02:12:59\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/93', 'PUT', 'Book \'sách  loại 2\' updated', '2025-11-21 19:12:59', '2025-11-21 19:12:59'),
(267, 1, 'updated', 'App\\Models\\Book', 88, '{\"id\": 88, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"sách B\", \"loai_sach\": \"quy\", \"created_at\": \"2025-11-14 17:12:09\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 2, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/aa11c93c-ca3b-4261-9c5c-2af1481266b0.jpg\", \"updated_at\": \"2025-11-22 03:14:05\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/88', 'PUT', 'Book \'sách B\' updated', '2025-11-21 20:14:05', '2025-11-21 20:14:05'),
(268, 1, 'updated', 'App\\Models\\Book', 88, '{\"id\": 88, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/aa11c93c-ca3b-4261-9c5c-2af1481266b0.jpg\", \"so_luong\": 0, \"ten_sach\": \"sách B\", \"loai_sach\": \"quy\", \"created_at\": \"2025-11-14 17:12:09\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 03:14:05\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 2, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/7f173450-c872-4bbb-83f0-266f125bf2f3.png\", \"updated_at\": \"2025-11-22 03:14:19\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/88', 'PUT', 'Book \'sách B\' updated', '2025-11-21 20:14:19', '2025-11-21 20:14:19'),
(269, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/1762236678_1402.jpg\", \"so_luong\": 6, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 10:27:28\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/1ce88bf6-538e-404e-b9b7-3f2d7f72a44c.png\", \"updated_at\": \"2025-11-22 03:14:36\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 20:14:36', '2025-11-21 20:14:36'),
(270, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/1ce88bf6-538e-404e-b9b7-3f2d7f72a44c.png\", \"so_luong\": 6, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 03:14:36\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/d7f898fc-ef36-4c6f-9e94-c570ca16f08e.jpg\", \"updated_at\": \"2025-11-22 03:17:35\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 20:17:35', '2025-11-21 20:17:35'),
(271, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/d7f898fc-ef36-4c6f-9e94-c570ca16f08e.jpg\", \"so_luong\": 6, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 03:17:35\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/c9d0bf28-2257-4fbc-9b17-75c04ae7461e.jpg\", \"updated_at\": \"2025-11-22 03:17:48\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 20:17:48', '2025-11-21 20:17:48'),
(272, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/c9d0bf28-2257-4fbc-9b17-75c04ae7461e.jpg\", \"so_luong\": 6, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 03:17:48\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/5f7f2e48-549b-474c-add1-e04769541faa.png\", \"updated_at\": \"2025-11-22 03:18:47\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 20:18:47', '2025-11-21 20:18:47'),
(273, 1, 'updated', 'App\\Models\\Book', 1, '{\"id\": 1, \"gia\": \"250000.00\", \"mo_ta\": \"Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": \"books/5f7f2e48-549b-474c-add1-e04769541faa.png\", \"so_luong\": 6, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-10-26 13:13:36\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 03:18:47\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 800, \"nam_xuat_ban\": \"2024\", \"so_luong_ban\": 200, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/29595ac3-1ba3-461b-a542-3c2ffc8e3e29.jpg\", \"updated_at\": \"2025-11-22 03:21:46\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/1', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 20:21:46', '2025-11-21 20:21:46'),
(274, 1, 'updated', 'App\\Models\\Book', 89, '{\"id\": 89, \"gia\": \"300000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"sách C\", \"loai_sach\": \"tham_khao\", \"created_at\": \"2025-11-14 17:13:27\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 9, \"is_featured\": 0, \"so_luot_xem\": 3, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/ef07e6d9-1c5c-4e89-9d47-602ad509cfb0.jpg\", \"updated_at\": \"2025-11-22 03:25:01\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/89', 'PUT', 'Book \'sách C\' updated', '2025-11-21 20:25:01', '2025-11-21 20:25:01'),
(275, 1, 'updated', 'App\\Models\\Book', 90, '{\"id\": 90, \"gia\": \"20000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": null, \"so_luong\": 1, \"ten_sach\": \"sách A1\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-16 11:45:51\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/509db91e-e03f-4ff3-9f63-38b31ec0aeae.png\", \"so_luong\": \"5\", \"updated_at\": \"2025-11-22 03:25:17\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/90', 'PUT', 'Book \'sách A1\' updated', '2025-11-21 20:25:17', '2025-11-21 20:25:17'),
(276, 1, 'updated', 'App\\Models\\Book', 91, '{\"id\": 91, \"gia\": \"10000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"sách D\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-16 11:51:02\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 02:23:31\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 4, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 1, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/f5c5b4a2-0b00-461f-9024-b3883c93edb8.jpg\", \"updated_at\": \"2025-11-22 03:25:32\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/91', 'PUT', 'Book \'sách D\' updated', '2025-11-21 20:25:32', '2025-11-21 20:25:32'),
(277, 1, 'updated', 'App\\Models\\Book', 92, '{\"id\": 92, \"gia\": \"150000.00\", \"mo_ta\": null, \"tac_gia\": \"Trần Thị B\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"sách loại 1\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-16 13:28:55\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 00:29:14\", \"category_id\": 8, \"is_featured\": 0, \"so_luot_xem\": 2, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/93881653-bcda-47e3-a327-cf3f402a8a75.png\", \"updated_at\": \"2025-11-22 03:25:52\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/92', 'PUT', 'Book \'sách loại 1\' updated', '2025-11-21 20:25:52', '2025-11-21 20:25:52'),
(278, 1, 'updated', 'App\\Models\\Book', 97, '{\"id\": 97, \"gia\": \"30000.00\", \"mo_ta\": \"k\", \"tac_gia\": \"Nguyễn Văn D\", \"hinh_anh\": null, \"so_luong\": 1, \"ten_sach\": \"Kinh tế học\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-21 23:06:32\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 23:06:40\", \"category_id\": 1, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 2, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/5fa13720-97b2-470b-bd4d-07edff47a717.png\", \"updated_at\": \"2025-11-22 03:26:05\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/97', 'PUT', 'Book \'Kinh tế học\' updated', '2025-11-21 20:26:05', '2025-11-21 20:26:05'),
(279, 1, 'updated', 'App\\Models\\Book', 96, '{\"id\": 96, \"gia\": \"400000.00\", \"mo_ta\": \"j\", \"tac_gia\": \"Trần Thị B\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"Lập trình Laravel từ A-Z\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-21 23:02:03\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 23:02:03\", \"category_id\": 8, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 6, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/148acbde-d904-4905-8e2f-e217c1cee586.jpg\", \"updated_at\": \"2025-11-22 03:26:39\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/96', 'PUT', 'Book \'Lập trình Laravel từ A-Z\' updated', '2025-11-21 20:26:39', '2025-11-21 20:26:39'),
(280, 1, 'updated', 'App\\Models\\Book', 95, '{\"id\": 95, \"gia\": \"455000.00\", \"mo_ta\": \"gg\", \"tac_gia\": \"Hoàng Văn E\", \"hinh_anh\": null, \"so_luong\": 1, \"ten_sach\": \"Lập trình PHP\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-21 23:01:14\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-22 00:18:01\", \"category_id\": 9, \"is_featured\": 0, \"so_luot_xem\": 1, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/7fa6f3b2-8d72-43ec-8174-3bbb25ff62c5.jpg\", \"updated_at\": \"2025-11-22 03:26:51\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/95', 'PUT', 'Book \'Lập trình PHP\' updated', '2025-11-21 20:26:51', '2025-11-21 20:26:51'),
(281, 1, 'updated', 'App\\Models\\Book', 94, '{\"id\": 94, \"gia\": \"30000.00\", \"mo_ta\": \"k\", \"tac_gia\": \"Hoàng Văn E\", \"hinh_anh\": null, \"so_luong\": 5, \"ten_sach\": \"Khoa học dữ liệu\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-21 22:58:53\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-21 22:59:03\", \"category_id\": 5, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 4, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/dfcf019c-ade5-4dd6-adef-927afd657b25.jpg\", \"updated_at\": \"2025-11-22 03:27:04\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/admin/books/94', 'PUT', 'Book \'Khoa học dữ liệu\' updated', '2025-11-21 20:27:04', '2025-11-21 20:27:04'),
(282, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 21:16:34', '2025-11-21 21:16:34'),
(283, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 21:16:40', '2025-11-21 21:16:40'),
(284, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 21:30:41', '2025-11-21 21:30:41'),
(285, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 21:30:47', '2025-11-21 21:30:47'),
(286, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 21:31:07', '2025-11-21 21:31:07'),
(287, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 21:31:17', '2025-11-21 21:31:17'),
(288, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/logout', 'POST', 'User logged out', '2025-11-21 21:47:35', '2025-11-21 21:47:35'),
(289, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-11-21 21:47:41', '2025-11-21 21:47:41'),
(290, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-24 02:30:32', '2025-11-24 02:30:32'),
(291, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'User logged out', '2025-11-24 02:30:41', '2025-11-24 02:30:41'),
(292, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-24 02:30:44', '2025-11-24 02:30:44'),
(293, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'User logged out', '2025-11-24 02:34:23', '2025-11-24 02:34:23'),
(294, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-24 02:34:32', '2025-11-24 02:34:32'),
(295, 1, 'borrow', 'App\\Models\\Borrow', 225, '[]', '{\"id\": 225, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-24 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"25000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 25000, \"created_at\": \"2025-11-24 09:35:03\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-24 09:35:03\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/borrow-book', 'POST', 'Borrow request for \'Lập trình PHP\' created by Super Admin', '2025-11-24 02:35:03', '2025-11-24 02:35:03'),
(296, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-25 01:16:52', '2025-11-25 01:16:52'),
(297, 1, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'User logged out', '2025-11-25 01:30:28', '2025-11-25 01:30:28'),
(298, NULL, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-25 01:30:56', '2025-11-25 01:30:56'),
(299, NULL, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'User logged out', '2025-11-25 01:31:05', '2025-11-25 01:31:05'),
(300, 23, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-25 01:31:12', '2025-11-25 01:31:12'),
(301, 23, 'borrow', 'App\\Models\\Borrow', 227, '[]', '{\"id\": 227, \"xa\": \"hà nội\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-25 00:00:00\", \"reader_id\": 17, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-25 08:32:30\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-25 08:32:30\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Người dùng\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/borrow-book', 'POST', 'Borrow request for \'Lập trình Laravel từ A-Z\' created by Người dùng', '2025-11-25 01:32:30', '2025-11-25 01:32:30'),
(302, 23, 'logout', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/logout', 'POST', 'User logged out', '2025-11-25 01:32:38', '2025-11-25 01:32:38'),
(303, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-25 01:32:44', '2025-11-25 01:32:44'),
(304, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-25 08:25:43', '2025-11-25 08:25:43'),
(305, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-26 00:54:28', '2025-11-26 00:54:28'),
(306, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-26 08:15:38', '2025-11-26 08:15:38'),
(307, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-26 17:07:45', '2025-11-26 17:07:45'),
(308, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-27 15:58:21', '2025-11-27 15:58:21'),
(309, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-28 00:35:36', '2025-11-28 00:35:36'),
(310, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-28 11:18:39', '2025-11-28 11:18:39'),
(311, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-29 11:45:18', '2025-11-29 11:45:18'),
(312, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-29 18:00:55', '2025-11-29 18:00:55'),
(313, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-30 03:22:55', '2025-11-30 03:22:55'),
(314, 1, 'borrow', 'App\\Models\\Borrow', 230, '[]', '{\"id\": 230, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-30 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-30 10:51:18\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-30 10:51:18\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/borrow-book', 'POST', 'Borrow request for \'Lập trình Laravel từ A-Z\' created by Super Admin', '2025-11-30 03:51:18', '2025-11-30 03:51:18'),
(315, 1, 'borrow', 'App\\Models\\Borrow', 231, '[]', '{\"id\": 231, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"0.00\", \"ngay_muon\": \"2025-11-30 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 0, \"created_at\": \"2025-11-30 10:51:18\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-11-30 10:51:18\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/borrow-book', 'POST', 'Borrow request for \'Lập trình Laravel từ A-Z\' created by Super Admin', '2025-11-30 03:51:18', '2025-11-30 03:51:18'),
(316, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-11-30 10:17:46', '2025-11-30 10:17:46'),
(317, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/login', 'POST', 'User logged in successfully', '2025-12-01 01:21:44', '2025-12-01 01:21:44'),
(318, 1, 'login', NULL, NULL, '[]', '[]', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuvien/public/login', 'POST', 'User logged in successfully', '2025-12-01 04:28:29', '2025-12-01 04:28:29'),
(319, 1, 'borrow', 'App\\Models\\Borrow', 232, '[]', '{\"id\": 232, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 16 ngày (Yêu cầu mượn 16 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"30000.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 130000, \"created_at\": \"2025-12-01 13:05:49\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 13:05:49\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuvien/public/borrow-book', 'POST', 'Borrow request for \'Est eos.\' created by Super Admin', '2025-12-01 06:05:49', '2025-12-01 06:05:49'),
(320, 1, 'updated', 'App\\Models\\Book', 102, '{\"id\": 102, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"sách cấp 1\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-25 10:30:30\", \"trang_thai\": \"active\", \"updated_at\": \"2025-11-27 00:08:53\", \"category_id\": 9, \"is_featured\": 0, \"so_luot_xem\": 1, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/c34a0974-0ade-4647-9ca1-af80425c73ed.png\", \"updated_at\": \"2025-12-01 14:02:38\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuvien/public/admin/books/102', 'PUT', 'Book \'sách cấp 1\' updated', '2025-12-01 07:02:38', '2025-12-01 07:02:38'),
(321, 1, 'borrow', 'App\\Models\\Borrow', 234, '[]', '{\"id\": 234, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 14:16:03\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 14:16:03\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuvien/public/borrow-book', 'POST', 'Borrow request for \'Kinh tế học\' created by Super Admin', '2025-12-01 07:16:03', '2025-12-01 07:16:03'),
(322, 1, 'borrow', 'App\\Models\\Borrow', 236, '[]', '{\"id\": 236, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 15:58:27\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 15:58:27\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuvien/public/borrow-book', 'POST', 'Borrow request for \'Lập trình Laravel từ A-Z\' created by Super Admin', '2025-12-01 08:58:27', '2025-12-01 08:58:27'),
(323, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-12-01 14:48:56', '2025-12-01 14:48:56'),
(324, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuvien.test/login', 'POST', 'User logged in successfully', '2025-12-01 15:22:19', '2025-12-01 15:22:19'),
(325, 1, 'login', NULL, NULL, '[]', '[]', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/login', 'POST', 'User logged in successfully', '2025-12-01 15:28:01', '2025-12-01 15:28:01'),
(326, 1, 'borrow', 'App\\Models\\Borrow', 237, '[]', '{\"id\": 237, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 22:49:59\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 22:49:59\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/borrow-book', 'POST', 'Borrow request for \'Kinh tế học\' created by Super Admin', '2025-12-01 15:49:59', '2025-12-01 15:49:59'),
(327, 1, 'borrow', 'App\\Models\\Borrow', 238, '[]', '{\"id\": 238, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 23:04:52\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 23:04:52\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/borrow-book', 'POST', 'Borrow request for \'Est eos.\' created by Super Admin', '2025-12-01 16:04:52', '2025-12-01 16:04:52'),
(328, 1, 'borrow', 'App\\Models\\Borrow', 239, '[]', '{\"id\": 239, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 23:13:04\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 23:13:04\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/borrow-book', 'POST', 'Borrow request for \'Lập trình Laravel từ A-Z\' created by Super Admin', '2025-12-01 16:13:04', '2025-12-01 16:13:04'),
(329, 1, 'borrow', 'App\\Models\\Borrow', 240, '[]', '{\"id\": 240, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 23:13:20\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 23:13:20\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/borrow-book', 'POST', 'Borrow request for \'Khoa học dữ liệu\' created by Super Admin', '2025-12-01 16:13:20', '2025-12-01 16:13:20'),
(330, 1, 'borrow', 'App\\Models\\Borrow', 241, '[]', '{\"id\": 241, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 23:20:32\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 23:20:32\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/borrow-book', 'POST', 'Borrow request for \'Kinh tế học\' created by Super Admin', '2025-12-01 16:20:32', '2025-12-01 16:20:32'),
(331, 1, 'borrow', 'App\\Models\\Borrow', 242, '[]', '{\"id\": 242, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)\", \"tien_coc\": \"100000.00\", \"ngay_muon\": \"2025-12-01 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"0.00\", \"tong_tien\": 100000, \"created_at\": \"2025-12-01 23:20:48\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-01 23:20:48\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://localhost/quanlythuviennn/public/borrow-book', 'POST', 'Borrow request for \'Assumenda esse sed distinctio.\' created by Super Admin', '2025-12-01 16:20:48', '2025-12-01 16:20:48'),
(332, 1, 'borrow', 'App\\Models\\Borrow', 243, '[]', '{\"id\": 243, \"xa\": \"hn\", \"huyen\": \"\", \"so_nha\": \"\", \"ghi_chu\": \"Yêu cầu mượn sách - 1 cuốn (Yêu cầu mượn 1 cuốn với thông số khác nhau)\", \"tien_coc\": \"12000.00\", \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": \"0.00\", \"tien_thue\": \"2000.00\", \"tong_tien\": 14000, \"created_at\": \"2025-12-02 19:42:18\", \"tinh_thanh\": \"\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 19:42:18\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-book', 'POST', 'Borrow request for \'Kinh tế học\' (1 copies with different params) created by Super Admin', '2025-12-02 12:42:18', '2025-12-02 12:42:18'),
(333, 1, 'borrow', 'App\\Models\\Borrow', 244, '[]', '{\"id\": 244, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": \"số 52\", \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 12000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 2000, \"tong_tien\": 14000, \"created_at\": \"2025-12-02 19:45:19\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 19:45:19\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 12:45:19', '2025-12-02 12:45:19'),
(334, 1, 'borrow', 'App\\Models\\Borrow', 245, '[]', '{\"id\": 245, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 80000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 20000, \"tien_thue\": 24000, \"tong_tien\": 124000, \"created_at\": \"2025-12-02 19:49:35\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 19:49:35\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 12:49:35', '2025-12-02 12:49:35'),
(335, 1, 'borrow', 'App\\Models\\Borrow', 246, '[]', '{\"id\": 246, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 120000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 140000, \"tien_thue\": 21000, \"tong_tien\": 281000, \"created_at\": \"2025-12-02 19:58:11\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 19:58:11\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 12:58:11', '2025-12-02 12:58:11'),
(336, 1, 'borrow', 'App\\Models\\Borrow', 247, '[]', '{\"id\": 247, \"xa\": \"hn\", \"huyen\": \"hậu lộc\", \"so_nha\": \"số 52\", \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 40000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 7000, \"tong_tien\": 47000, \"created_at\": \"2025-12-02 19:58:35\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 19:58:36\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 12:58:36', '2025-12-02 12:58:36'),
(337, 1, 'borrow', 'App\\Models\\Borrow', 248, '[]', '{\"id\": 248, \"xa\": \"hn\", \"huyen\": \"hoài đức\", \"so_nha\": \"46\", \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 144800, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 25000, \"tong_tien\": 169800, \"created_at\": \"2025-12-02 20:11:50\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 20:11:50\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 13:11:50', '2025-12-02 13:11:50'),
(338, 1, 'borrow', 'App\\Models\\Borrow', 249, '[]', '{\"id\": 249, \"xa\": \"hn\", \"huyen\": \"hoài đức\", \"so_nha\": \"46\", \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 40000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 7000, \"tong_tien\": 47000, \"created_at\": \"2025-12-02 20:16:07\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 20:16:07\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 13:16:07', '2025-12-02 13:16:07'),
(339, 1, 'borrow', 'App\\Models\\Borrow', 250, '[]', '{\"id\": 250, \"xa\": \"hn\", \"huyen\": \"hậu lộc\", \"so_nha\": \"46\", \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 70000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 0, \"tong_tien\": 70000, \"created_at\": \"2025-12-02 20:25:41\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 20:25:41\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 13:25:41', '2025-12-02 13:25:41'),
(340, 1, 'borrow', 'App\\Models\\Borrow', 251, '[]', '{\"id\": 251, \"xa\": \"hn\", \"huyen\": \"hậu lộc\", \"so_nha\": \"số 52\", \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 70000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 0, \"tong_tien\": 70000, \"created_at\": \"2025-12-02 20:55:44\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 20:55:44\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 13:55:44', '2025-12-02 13:55:44'),
(341, 1, 'borrow', 'App\\Models\\Borrow', 252, '[]', '{\"id\": 252, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 160000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 28000, \"tong_tien\": 188000, \"created_at\": \"2025-12-02 21:04:00\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 21:04:00\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 14:04:00', '2025-12-02 14:04:00');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `url`, `method`, `description`, `created_at`, `updated_at`) VALUES
(342, 1, 'borrow', 'App\\Models\\Borrow', 253, '[]', '{\"id\": 253, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 160000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 28000, \"tong_tien\": 188000, \"created_at\": \"2025-12-02 21:11:11\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 21:11:11\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 14:11:11', '2025-12-02 14:11:11'),
(343, 1, 'borrow', 'App\\Models\\Borrow', 254, '[]', '{\"id\": 254, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 160000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 28000, \"tong_tien\": 188000, \"created_at\": \"2025-12-02 21:16:25\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 21:16:25\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 14:16:25', '2025-12-02 14:16:25'),
(344, 1, 'borrow', 'App\\Models\\Borrow', 255, '[]', '{\"id\": 255, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 182000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 32000, \"tong_tien\": 214000, \"created_at\": \"2025-12-02 21:26:40\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 21:26:40\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 14:26:40', '2025-12-02 14:26:40'),
(345, 1, 'borrow', 'App\\Models\\Borrow', 256, '[]', '{\"id\": 256, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 194000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 34000, \"tong_tien\": 228000, \"created_at\": \"2025-12-02 21:39:42\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 21:39:42\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 14:39:42', '2025-12-02 14:39:42'),
(346, 1, 'borrow', 'App\\Models\\Borrow', 257, '[]', '{\"id\": 257, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 160000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 5000, \"tien_thue\": 28000, \"tong_tien\": 193000, \"created_at\": \"2025-12-02 22:04:16\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 22:04:16\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 15:04:16', '2025-12-02 15:04:16'),
(347, 1, 'borrow', 'App\\Models\\Borrow', 258, '[]', '{\"id\": 258, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 160000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 28000, \"tong_tien\": 188000, \"created_at\": \"2025-12-02 22:05:25\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 22:05:25\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 15:05:25', '2025-12-02 15:05:25'),
(348, 1, 'updated', 'App\\Models\\Book', 102, '{\"id\": 102, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": \"books/c34a0974-0ade-4647-9ca1-af80425c73ed.png\", \"so_luong\": 0, \"ten_sach\": \"sách cấp 1\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-25 10:30:30\", \"trang_thai\": \"active\", \"updated_at\": \"2025-12-01 14:02:38\", \"category_id\": 9, \"is_featured\": 0, \"so_luot_xem\": 1, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/739cd0cb-c6c0-4c07-884b-bc77efc660b4.jpg\", \"updated_at\": \"2025-12-02 22:07:05\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/admin/books/102', 'PUT', 'Book \'sách cấp 1\' updated', '2025-12-02 15:07:05', '2025-12-02 15:07:05'),
(349, 1, 'updated', 'App\\Models\\Book', 102, '{\"id\": 102, \"gia\": \"100000.00\", \"mo_ta\": null, \"tac_gia\": \"Nguyễn Văn A\", \"hinh_anh\": \"books/739cd0cb-c6c0-4c07-884b-bc77efc660b4.jpg\", \"so_luong\": 0, \"ten_sach\": \"sách cấp 1\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-11-25 10:30:30\", \"trang_thai\": \"active\", \"updated_at\": \"2025-12-02 22:07:05\", \"category_id\": 9, \"is_featured\": 0, \"so_luot_xem\": 1, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 8, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/4c6a8bc9-a9f1-401e-8ac2-9d308fb52a48.jpg\", \"updated_at\": \"2025-12-02 22:12:19\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/admin/books/102', 'PUT', 'Book \'sách cấp 1\' updated', '2025-12-02 15:12:19', '2025-12-02 15:12:19'),
(350, 1, 'updated', 'App\\Models\\Book', 104, '{\"id\": 104, \"gia\": \"60000.00\", \"mo_ta\": null, \"tac_gia\": \"Lê Văn C\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"Khoa học dữ liệu\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-12-02 22:15:16\", \"trang_thai\": \"active\", \"updated_at\": \"2025-12-02 22:15:16\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 2, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/b1f5e7cf-1ac2-45bb-b821-c29f5e91fb90.jpg\", \"updated_at\": \"2025-12-02 22:18:21\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/admin/books/104', 'PUT', 'Book \'Khoa học dữ liệu\' updated', '2025-12-02 15:18:21', '2025-12-02 15:18:21'),
(351, 1, 'updated', 'App\\Models\\Book', 105, '{\"id\": 105, \"gia\": \"50000.00\", \"mo_ta\": null, \"tac_gia\": \"Trần Thị B\", \"hinh_anh\": null, \"so_luong\": 4, \"ten_sach\": \"Lập trình PHP\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-12-02 22:16:14\", \"trang_thai\": \"active\", \"updated_at\": \"2025-12-02 22:16:55\", \"category_id\": 8, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 2, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/050fb72a-c13c-46ea-a754-490b24d6cb4b.jpg\", \"updated_at\": \"2025-12-02 22:21:52\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/admin/books/105', 'PUT', 'Book \'Lập trình PHP\' updated', '2025-12-02 15:21:52', '2025-12-02 15:21:52'),
(352, 1, 'updated', 'App\\Models\\Book', 106, '{\"id\": 106, \"gia\": \"600000.00\", \"mo_ta\": \"j\", \"tac_gia\": \"Hoàng Văn E\", \"hinh_anh\": null, \"so_luong\": 0, \"ten_sach\": \"Lập trình PHP\", \"loai_sach\": \"binh_thuong\", \"created_at\": \"2025-12-02 22:17:40\", \"trang_thai\": \"active\", \"updated_at\": \"2025-12-02 22:17:40\", \"category_id\": 2, \"is_featured\": 0, \"so_luot_xem\": 0, \"nam_xuat_ban\": \"2025\", \"so_luong_ban\": 0, \"nha_xuat_ban_id\": 3, \"danh_gia_trung_binh\": \"0.00\"}', '{\"hinh_anh\": \"books/ff00726d-d5c1-422d-a128-229b1f0ac094.png\", \"updated_at\": \"2025-12-02 22:22:04\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/admin/books/106', 'PUT', 'Book \'Lập trình PHP\' updated', '2025-12-02 15:22:04', '2025-12-02 15:22:04'),
(353, 1, 'borrow', 'App\\Models\\Borrow', 259, '[]', '{\"id\": 259, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 24000, \"ngay_muon\": \"2025-12-02 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 4000, \"tong_tien\": 28000, \"created_at\": \"2025-12-02 22:22:25\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-02 22:22:25\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-02 15:22:25', '2025-12-02 15:22:25'),
(354, 1, 'login', NULL, NULL, '[]', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/login', 'POST', 'User logged in successfully', '2025-12-03 05:03:59', '2025-12-03 05:03:59'),
(355, 1, 'borrow', 'App\\Models\\Borrow', 260, '[]', '{\"id\": 260, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 40400, \"ngay_muon\": \"2025-12-03 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 7000, \"tong_tien\": 47400, \"created_at\": \"2025-12-03 12:04:24\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-03 12:04:24\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-03 05:04:24', '2025-12-03 05:04:24'),
(356, 1, 'borrow', 'App\\Models\\Borrow', 261, '[]', '{\"id\": 261, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 40400, \"ngay_muon\": \"2025-12-03 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 7000, \"tong_tien\": 47400, \"created_at\": \"2025-12-03 12:08:08\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-03 12:08:08\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-03 05:08:08', '2025-12-03 05:08:08'),
(357, 1, 'borrow', 'App\\Models\\Borrow', 262, '[]', '{\"id\": 262, \"xa\": \"hn\", \"huyen\": null, \"so_nha\": null, \"ghi_chu\": \"Đặt mượn từ giỏ sách\", \"tien_coc\": 70000, \"ngay_muon\": \"2025-12-03 00:00:00\", \"reader_id\": 18, \"tien_ship\": 0, \"tien_thue\": 12000, \"tong_tien\": 82000, \"created_at\": \"2025-12-03 12:28:22\", \"tinh_thanh\": \"Hà Nội\", \"trang_thai\": \"Dang muon\", \"updated_at\": \"2025-12-03 12:28:23\", \"so_dien_thoai\": \"0987654323\", \"ten_nguoi_muon\": \"Super Admin\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'http://quanlythuviennn.test/borrow-cart/process-checkout', 'POST', 'Borrow request from cart created by Super Admin', '2025-12-03 05:28:23', '2025-12-03 05:28:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `authors`
--

CREATE TABLE `authors` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_tac_gia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_dien_thoai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_thieu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hinh_anh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trang_thai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `backups`
--

CREATE TABLE `backups` (
  `id` bigint UNSIGNED NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('manual','automatic','scheduled','full','incremental') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `last_restored_at` timestamp NULL DEFAULT NULL,
  `restored_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

CREATE TABLE `books` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_sach` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `tac_gia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nam_xuat_ban` year NOT NULL,
  `hinh_anh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gia` decimal(15,2) NOT NULL DEFAULT '0.00',
  `trang_thai` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `loai_sach` enum('binh_thuong','quy','tham_khao') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'binh_thuong',
  `danh_gia_trung_binh` decimal(3,2) NOT NULL DEFAULT '0.00',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `so_luong_ban` int NOT NULL DEFAULT '0',
  `so_luot_xem` int NOT NULL DEFAULT '0',
  `so_luong` int NOT NULL DEFAULT '0' COMMENT 'Số lượng sách',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nha_xuat_ban_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`id`, `ten_sach`, `category_id`, `tac_gia`, `nam_xuat_ban`, `hinh_anh`, `mo_ta`, `gia`, `trang_thai`, `loai_sach`, `danh_gia_trung_binh`, `is_featured`, `so_luong_ban`, `so_luot_xem`, `so_luong`, `created_at`, `updated_at`, `nha_xuat_ban_id`) VALUES
(1, 'Lập trình Laravel từ A-Z', 2, 'Nguyễn Văn D', '2024', 'books/29595ac3-1ba3-461b-a542-3c2ffc8e3e29.jpg', 'Cuốn sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao', 250000.00, 'active', 'binh_thuong', 0.00, 0, 200, 800, 6, '2025-10-26 06:13:36', '2025-11-21 20:21:46', 8),
(2, 'Khoa học dữ liệu', 2, 'TS. Trần Thị E', '2023', 'books/1762236687_1402.jpg', 'Giới thiệu về khoa học dữ liệu và machine learning', 220000.00, 'active', 'binh_thuong', 0.00, 0, 180, 651, 7, '2025-10-26 06:13:36', '2025-11-20 17:29:14', 5),
(3, 'Lịch sử Việt Nam hiện đại', 3, 'Lê Văn F', '2022', 'books/1762236696_2389.png', 'Lịch sử Việt Nam từ thế kỷ 20 đến nay', 95000.00, 'active', 'binh_thuong', 0.00, 0, 120, 452, 0, '2025-10-26 06:13:36', '2025-11-20 17:29:14', NULL),
(4, 'Lập trình PHP cơ bản', 4, 'Nguyễn Văn A', '2023', 'books/1761919414_1345.jpg', 'Sách học PHP từ cơ bản', 60000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 8, '2025-10-26 06:13:36', '2025-11-21 16:00:32', 7),
(5, 'Kinh tế học vi mô', 4, 'PGS.TS. Phạm Thị G', '2023', 'books/1762236707_1249.jpg', 'Giáo trình kinh tế học vi mô cho sinh viên', 110000.00, 'active', 'binh_thuong', 0.00, 0, 300, 900, 0, '2025-10-26 06:13:36', '2025-11-20 17:29:14', NULL),
(6, 'Truyện Kiều', 5, 'Nguyễn Du', '1950', 'books/1762236717_1249.jpg', 'Tác phẩm kinh điển của văn học Việt Nam', 60000.00, 'active', 'binh_thuong', 0.00, 1, 2500, 5016, 1, '2025-10-26 06:13:36', '2025-11-21 17:45:10', 7),
(7, 'Giáo dục thế kỷ 21', 6, 'TS. Hoàng Văn H', '2024', 'books/1762236727_360.jpg', 'Phương pháp giáo dục hiện đại', 140000.00, 'active', 'binh_thuong', 0.00, 0, 150, 503, 0, '2025-10-26 06:13:36', '2025-11-20 17:29:14', NULL),
(8, 'Y học cơ bản', 8, 'Bác sĩ Nguyễn F', '2023', 'books/1761919467_317.jpg', 'Kiến thức y học cơ bản cho mọi người', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:13:36', '2025-11-20 17:29:14', NULL),
(9, 'Lập trình PHP', 4, 'Nguyễn Văn A', '2023', 'books/1761919483_28879.png', 'Sách hướng dẫn lập trình PHP từ cơ bản đến nâng cao', 150000.00, 'active', 'binh_thuong', 0.00, 0, 45, 1202, 9, '2025-10-26 06:13:37', '2025-11-21 17:48:05', NULL),
(10, 'Laravel Framework', 4, 'Trần Thị B', '2024', 'books/1761919496_1756.jpg', 'Tài liệu chi tiết về Laravel Framework', 200000.00, 'active', 'binh_thuong', 0.00, 0, 32, 957, 4, '2025-10-26 06:13:37', '2025-11-20 17:29:14', NULL),
(11, 'Văn học Việt Nam', 6, 'Lê Văn C', '2022', 'books/1761919510_1756.jpg', 'Tuyển tập các tác phẩm văn học Việt Nam', 120000.00, 'active', 'binh_thuong', 0.00, 0, 67, 1830, 1, '2025-10-26 06:13:37', '2025-11-21 17:31:42', NULL),
(12, 'Lịch sử Việt Nam', 3, 'Phạm Thị D', '2021', 'books/1761919527_2389.png', 'Lịch sử Việt Nam từ thời kỳ cổ đại đến hiện đại', 180000.00, 'active', 'binh_thuong', 0.00, 0, 78, 2122, 0, '2025-10-26 06:13:37', '2025-11-20 19:58:00', NULL),
(13, 'Kinh tế học', 5, 'Hoàng Văn E', '2023', 'books/1762236757_2344.png', 'Giáo trình kinh tế học cơ bản', 160000.00, 'active', 'binh_thuong', 0.00, 0, 54, 1401, 1, '2025-10-26 06:13:37', '2025-11-21 15:42:46', NULL),
(14, 'Eum asperiores qui.', 3, 'Cô. Đào Duy Thiện', '2012', 'books/1762236743_2389.png', 'Fugit quidem molestias qui qui aut ut aut mollitia. Enim at facilis error ea vitae beatae voluptatibus. Minima quo aut voluptatem deleniti.', 113000.00, 'active', 'binh_thuong', 0.00, 0, 8477, 463, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 4),
(15, 'Et architecto vel tempore.', 8, 'Chị. Trưng An Đan', '2004', 'books/1761484610_tải xuống (3).jpg', 'Iusto unde saepe expedita voluptatibus libero sapiente. Quod magni quia ab adipisci hic et magnam. Non sit et nesciunt. Id inventore asperiores nulla molestias officiis possimus.', 87000.00, 'active', 'binh_thuong', 0.00, 0, 8186, 125, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 8),
(16, 'Quas voluptatem.', 2, 'Chú. Chế Quân Phúc', '2012', 'books/1761919553_1345.jpg', 'Voluptatem expedita assumenda delectus et quo veniam nihil aut. Inventore inventore quia occaecati. Quibusdam esse pariatur illum porro.', 306000.00, 'active', 'binh_thuong', 0.00, 0, 9629, 624, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 1),
(17, 'Harum minima voluptatem.', 1, 'Bùi Bá Minh', '1995', 'books/1762236773_2389.png', 'Aperiam doloremque dolor ad natus iure beatae quasi. Delectus praesentium sit qui laudantium velit. Maiores a harum aut et. Dolorem ullam sit ipsum optio et aut laudantium numquam.', 199000.00, 'active', 'binh_thuong', 0.00, 0, 1651, 6, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 5),
(18, 'Natus sapiente est.', 1, 'Anh. Lỡ Đồng', '1995', 'books/1762237020_1345.jpg', 'Quo deserunt esse sit vero voluptates eum. Nisi facilis in delectus repellat quod. Cum repudiandae qui est modi. Rerum error veritatis iure et rem ut architecto tempore.', 336000.00, 'active', 'binh_thuong', 0.00, 0, 101, 171, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 4),
(19, 'Placeat aut cum labore exercitationem.', 7, 'Anh. Trịnh Tấn', '2017', 'books/1762237037_1249.jpg', 'Hic et quasi expedita animi et sed praesentium. Iusto quia qui dolores ipsa harum rem aut.', 175000.00, 'active', 'binh_thuong', 0.00, 0, 7498, 701, 0, '2025-10-26 06:13:39', '2025-12-03 05:28:11', 5),
(20, 'Amet eos sapiente.', 4, 'Giao Uyên', '2008', 'books/1762236792_317.jpg', 'Voluptatum et autem eum eaque asperiores consequatur soluta error. Numquam quo et asperiores rerum eaque voluptate. Officiis cumque exercitationem quis laudantium vero ex architecto ducimus. Quia magnam facilis quia alias cumque qui id.', 410000.00, 'active', 'binh_thuong', 0.00, 0, 1084, 71, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 5),
(21, 'Non aspernatur voluptatem.', 4, 'Mẫn Nhật Thuận', '2022', 'books/1762237058_1756.jpg', 'Et aut aut sequi consequatur. Rerum tenetur quidem consequatur voluptas repudiandae. Id amet nostrum temporibus nihil iure ut.', 155000.00, 'active', 'binh_thuong', 0.00, 0, 7776, 185, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 2),
(22, 'Non repudiandae similique.', 4, 'Cụ. Trịnh Hiệp', '2012', 'books/1762237072_2389.png', 'Totam omnis et pariatur ea ab dolore. Cum dicta aut velit accusantium sequi ea dolor ducimus. Reiciendis quo quae vel.', 191000.00, 'active', 'binh_thuong', 0.00, 0, 481, 959, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 4),
(23, 'Voluptatem tempora sit praesentium.', 7, 'Vương Loan', '2020', 'books/1762237108_317.jpg', 'Minus hic rerum accusamus nemo esse laudantium inventore. Incidunt accusamus cupiditate velit quis aut non nihil. Est praesentium ullam eveniet doloribus exercitationem sed aut fugit. Dolorem eius nihil voluptas porro tempore.', 211000.00, 'active', 'binh_thuong', 5.00, 0, 7139, 165, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 3),
(24, 'Tempora harum quia reiciendis.', 1, 'Cụ. Khúc Hải Bình', '2000', 'books/1762237089_2389.png', 'Et amet nihil pariatur earum. Vero nesciunt voluptas voluptatem voluptatibus id et voluptatum est. Itaque doloremque sed ipsum et eum.', 105000.00, 'active', 'binh_thuong', 0.00, 0, 7118, 382, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 6),
(25, 'Similique assumenda repudiandae.', 8, 'Lương Phụng Thy', '2001', 'books/1762237152_2344.png', 'Delectus voluptatibus sed eum laborum. Sed voluptas illum cumque quaerat culpa delectus sed dignissimos. Illo id aut ut et nihil.', 146000.00, 'active', 'binh_thuong', 0.00, 0, 7907, 814, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 3),
(26, 'Recusandae possimus.', 8, 'Trương Lạc', '2012', 'books/1762237138_360.jpg', 'Vel qui quidem et est rem neque iste. Esse ducimus odio mollitia architecto. Consequatur quo consequatur natus voluptate ut quo repellendus nemo. Minima accusamus deserunt architecto quia voluptatem et voluptatem.', 380000.00, 'active', 'binh_thuong', 0.00, 0, 5673, 688, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 4),
(27, 'Expedita error sit enim.', 8, 'Anh. Đồng Nhiên', '2020', 'books/1762237123_1345.jpg', 'Libero dolor voluptates ut omnis ex et est. Voluptates vero quia rem rerum iusto recusandae. Dolorem at incidunt eveniet consequatur debitis.', 176000.00, 'active', 'binh_thuong', 0.00, 0, 3784, 612, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 8),
(28, 'Et deserunt nostrum adipisci.', 6, 'Lê Trang Thùy', '1992', 'books/1762237201_2389.png', 'Quis ut dolorem suscipit voluptates ex in at. Maiores ut atque provident est eveniet et et.', 293000.00, 'active', 'binh_thuong', 0.00, 0, 1661, 832, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 4),
(29, 'Et mollitia eos natus.', 4, 'Kha Hồ Diễm', '2024', 'books/1762237188_1249.jpg', 'Ut reprehenderit officiis autem beatae quis culpa cumque eos. Voluptatem et omnis nemo quisquam voluptatum est. Nulla dicta laboriosam omnis occaecati omnis illum et ut.', 456000.00, 'active', 'binh_thuong', 0.00, 0, 7548, 743, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 6),
(30, 'Impedit tempora tempore officia.', 7, 'Thào Anh', '2011', 'books/1762237167_1402.jpg', 'In nihil minima est suscipit qui qui tempora. Magnam consectetur quo et voluptatibus ut voluptas non. Consequatur consectetur quo non et est voluptatem eius provident.', 439000.00, 'active', 'binh_thuong', 0.00, 0, 5540, 481, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 6),
(31, 'Debitis ut nemo.', 2, 'Cô. Phi Lộc', '1996', 'books/1762237253_2344.png', 'A doloribus voluptate vero nihil molestiae amet dolore animi. Dolorum molestiae molestias est aut id. Et facere necessitatibus et laudantium iste.', 165000.00, 'active', 'binh_thuong', 0.00, 0, 6311, 886, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 3),
(33, 'Vero quibusdam et.', 6, 'Cô. Cát Thơ Vũ', '2006', 'books/1762237697_2344.png', 'Inventore nesciunt distinctio voluptatem ex exercitationem at a. Quisquam in non unde quia. Perspiciatis similique molestiae sed ipsa.', 205000.00, 'active', 'binh_thuong', 0.00, 0, 8062, 852, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 7),
(34, 'Porro dolores id.', 4, 'Chu Khắc Sâm', '1993', 'books/1762237711_2389.png', 'Porro nobis atque tempore. Omnis enim voluptatem id et. Quibusdam occaecati ex necessitatibus.', 473000.00, 'active', 'binh_thuong', 0.00, 0, 8109, 644, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 8),
(36, 'Excepturi asperiores non.', 1, 'Lô Toại', '2020', 'books/1762237779_1345.jpg', 'Ut et voluptate eligendi consectetur autem a. Qui fugiat quam eos optio. Amet dolorem sed sit sit blanditiis possimus.', 148000.00, 'active', 'binh_thuong', 0.00, 0, 6052, 604, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 1),
(37, 'Modi totam.', 2, 'Tạ Giao', '2018', 'books/1762237732_1249.jpg', 'Cupiditate quod est alias qui explicabo ea. Aut autem hic facilis aut. Numquam est doloremque doloremque voluptas. Molestias ea et unde delectus est libero.', 198000.00, 'active', 'binh_thuong', 0.00, 0, 1084, 172, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 7),
(38, 'Dolor eaque mollitia.', 3, 'Lâm Trân', '2003', 'books/1762237821_1249.jpg', 'Consequatur illum minus veritatis ut eaque tenetur ratione. Ex in quis dolores facilis. Molestiae ut et est explicabo laudantium fugit aut nihil. Sed dolor aut cum alias qui nesciunt.', 259000.00, 'active', 'binh_thuong', 0.00, 0, 5018, 331, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 8),
(39, 'Vel nostrum qui.', 1, 'Chung Cát Phi', '2011', 'books/1762237856_1345.jpg', 'Aut quidem et officia eos quos saepe. Reiciendis sunt quis consequatur quibusdam et ipsam iste. Sit ducimus quae sed est doloremque et. Sint laborum et ullam et incidunt quo perferendis quia.', 390000.00, 'active', 'binh_thuong', 0.00, 0, 4457, 295, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 6),
(40, 'Dolore ipsam enim.', 2, 'Mâu Lợi', '2007', 'books/1762237837_1402.jpg', 'Aut sint atque similique odit eum voluptas vitae. Laborum inventore accusamus doloremque. Perspiciatis voluptates corporis eaque sunt sed pariatur. Sint eligendi nesciunt ut in animi iusto. Maxime libero qui corporis quas accusamus est asperiores.', 320000.00, 'active', 'binh_thuong', 0.00, 0, 595, 854, 0, '2025-10-26 06:13:39', '2025-11-20 19:14:55', 2),
(41, 'Qui ut et ut.', 6, 'Chú. Khâu Trung', '1998', 'books/1762237918_1345.jpg', 'Eos delectus aut sunt quia aliquid culpa adipisci. Ut occaecati corrupti harum aut est. Dolorem hic eos mollitia quia quae.', 108000.00, 'active', 'binh_thuong', 0.00, 0, 5369, 15, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 3),
(42, 'Velit iusto vitae a totam.', 6, 'Yên Kỳ', '1990', 'books/1762237938_1756.jpg', 'Tempora et esse voluptatem suscipit distinctio qui omnis. In odio similique repudiandae et. Ducimus consequatur numquam est ut quisquam unde.', 192000.00, 'active', 'binh_thuong', 0.00, 0, 7137, 636, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 7),
(43, 'Ipsa soluta quia.', 3, 'Chị. Giao Thuần Thường', '1992', 'books/1762237962_317.jpg', 'Sed numquam cum consequuntur ut tempore. Repellat sequi sapiente dolores eaque beatae. Qui nihil autem sed tenetur eaque aut voluptatem. Est aspernatur est hic optio temporibus.', 458000.00, 'active', 'binh_thuong', 0.00, 0, 7919, 654, 0, '2025-10-26 06:13:39', '2025-11-20 19:24:25', 2),
(44, 'Sit aliquid omnis ut.', 7, 'Phương Văn', '2005', 'books/1762238012_1345.jpg', 'Nulla cumque reprehenderit voluptatum iure consequuntur asperiores qui. Commodi dolores modi quo velit excepturi. Assumenda quia sint ullam aut molestiae quia voluptates.', 258000.00, 'active', 'binh_thuong', 0.00, 0, 942, 541, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 3),
(45, 'Dolore ut ut id.', 7, 'Ông. Vừ Sĩ Nhu', '2014', 'books/1762238031_1249.jpg', 'Consequatur libero ipsa neque consequuntur. Soluta architecto molestias velit est. Molestias officia dolorem et dignissimos maiores veritatis. Atque dolorem suscipit esse ex sint qui.', 456000.00, 'active', 'binh_thuong', 0.00, 0, 9711, 579, 1, '2025-10-26 06:13:39', '2025-11-21 15:43:50', 8),
(46, 'Est eos.', 1, 'Chú. Cái Vịnh', '2024', 'books/1762238108_1756.jpg', 'Enim molestias est id velit natus eveniet autem. Quia sed reiciendis est voluptatibus consectetur inventore quam. Occaecati architecto laudantium voluptatibus veritatis. Sed unde voluptatem rerum expedita dolorem necessitatibus commodi.', 495000.00, 'active', 'binh_thuong', 0.00, 0, 3912, 535, 0, '2025-10-26 06:13:39', '2025-12-01 16:04:48', 4),
(47, 'Adipisci odit veritatis.', 7, 'Điền Lệ Hạnh', '1992', 'books/1762238046_1402.jpg', 'Et nam quod optio quam nisi. Harum vel molestias accusamus. Ut sed non laboriosam neque eum esse sit exercitationem.', 101000.00, 'active', 'binh_thuong', 0.00, 0, 3978, 841, 0, '2025-10-26 06:13:39', '2025-12-03 05:14:12', 3),
(48, 'Voluptatem asperiores neque.', 8, 'Bác. Thân Ban Nhân', '2004', 'books/1762238093_2389.png', 'Dolor aliquam repellendus molestias perspiciatis. Qui voluptatem quo eveniet pariatur in. Rerum dolores vero nihil odit quibusdam assumenda vero. Officia quo dicta assumenda.', 279000.00, 'active', 'binh_thuong', 0.00, 0, 7211, 162, 0, '2025-10-26 06:13:39', '2025-11-20 19:55:52', 7),
(49, 'Quam iste.', 5, 'Doãn Đoan', '2024', 'books/1762238078_360.jpg', 'Et accusamus adipisci minus voluptatem. Aut mollitia officiis iusto rerum. Nihil animi alias molestiae dicta qui in qui.', 493000.00, 'active', 'binh_thuong', 0.00, 0, 6137, 692, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 8),
(50, 'Voluptas temporibus occaecati in.', 5, 'Em. Cung Hữu Vương', '2008', 'books/1762238064_1402.jpg', 'Sint magnam dicta et eum. Dolor aut ut temporibus magnam consequuntur. Dolore nostrum illo fugit impedit itaque sapiente quo. Fugit dolor aliquam natus minima quo beatae.', 444000.00, 'active', 'binh_thuong', 0.00, 0, 640, 640, 1, '2025-10-26 06:13:39', '2025-11-21 15:42:46', 8),
(52, 'Itaque minima consequatur quo.', 7, 'Em. Đậu Duyên Nhân', '2001', 'books/1761823271_tải xuống (5).jpg', 'Qui adipisci consequuntur odit ut est consequatur. Et cum enim suscipit mollitia. Sed dolor impedit possimus consequatur ratione et. Possimus ut laboriosam necessitatibus hic. Voluptates non deleniti maxime voluptatem enim dignissimos.', 174000.00, 'active', 'binh_thuong', 0.00, 0, 6845, 478, 0, '2025-10-26 06:13:39', '2025-11-20 18:17:17', 1),
(53, 'Consequatur et incidunt ut.', 3, 'Bác. Tạ Thanh Ân', '2010', 'books/1762238175_1402.jpg', 'Temporibus et corporis id delectus rem. Distinctio possimus ea explicabo ipsam enim molestiae. Eum exercitationem sunt ut corrupti quam pariatur velit.', 485000.00, 'active', 'binh_thuong', 0.00, 0, 1418, 412, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 7),
(56, 'Tempora modi mollitia et.', 7, 'Bác. Uông Dũng', '1991', 'books/1762238272_1402.jpg', 'Culpa voluptas asperiores ea exercitationem et ut nulla fugit. Iusto quia blanditiis provident placeat. A illo velit quo laudantium perspiciatis earum. Occaecati vel voluptatem rerum quia corporis alias.', 353000.00, 'active', 'binh_thuong', 0.00, 0, 4122, 783, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 5),
(57, 'Ea sit ipsum qui.', 5, 'Vừ Nghi', '2001', 'books/1762238254_2389.png', 'Sunt iure vel impedit. Delectus tempore laboriosam excepturi consequatur. Ullam aut cupiditate iure illum pariatur facere dicta. Illum ut vero et autem amet perspiciatis autem.', 155000.00, 'active', 'binh_thuong', 0.00, 0, 8679, 877, 0, '2025-10-26 06:13:39', '2025-11-24 02:34:28', 5),
(58, 'Quidem ut deserunt.', 1, 'Khúc Kỳ An', '2009', 'books/1762238224_1345.jpg', 'Minus rerum amet sit sed. Sunt numquam modi quia ratione voluptates suscipit fugit velit. Sit quas et et. Ad eos et ducimus illo optio.', 362000.00, 'active', 'binh_thuong', 0.00, 0, 4104, 183, 0, '2025-10-26 06:13:39', '2025-12-02 13:11:38', 4),
(59, 'Ducimus asperiores nostrum sit.', 5, 'Tạ Nhàn', '2012', 'books/1762238241_1756.jpg', 'Ducimus quis eaque reprehenderit rerum fugit aut. At quasi ratione facere voluptas voluptatibus ea. Quo corrupti nam blanditiis doloremque qui. Sint eum quis illum.', 113000.00, 'active', 'binh_thuong', 0.00, 0, 7173, 122, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 5),
(60, 'Assumenda esse sed distinctio.', 1, 'Vi Uyển Vi', '2008', 'books/1762238145_2344.png', 'Consequatur consequuntur itaque dicta ipsum amet facilis. Ipsa quaerat asperiores doloribus. Quis culpa deleniti voluptatem. Ab eum qui nobis nihil dolores assumenda aut.', 377000.00, 'active', 'binh_thuong', 0.00, 0, 2184, 48, 0, '2025-10-26 06:13:39', '2025-12-01 16:20:43', 7),
(61, 'Veniam quod in.', 3, 'Bà. Thạch Đồng Thanh', '1992', 'books/1762238312_2389.png', 'Expedita esse voluptatem quia sed quia. Aspernatur doloremque mollitia ipsum minima. Sint eligendi temporibus sit illum. Atque laudantium veritatis qui. Rem maiores cum ut voluptas numquam cupiditate.', 106000.00, 'active', 'binh_thuong', 0.00, 0, 9456, 972, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 1),
(62, 'Doloremque molestias.', 7, 'Mã Thoa', '2011', 'books/1762238325_1345.jpg', 'Quae facere ducimus est dolores. Perferendis autem sit dolore eligendi rerum enim perspiciatis. Est est officia dicta consequuntur voluptatem deserunt rerum. Voluptatem nostrum repellat totam laudantium voluptatum quos voluptatem.', 382000.00, 'active', 'binh_thuong', 0.00, 0, 2372, 720, 1, '2025-10-26 06:13:39', '2025-11-21 15:43:50', 5),
(63, 'Quaerat fugit fugit cum aspernatur.', 7, 'Cô. Kiều Thiện Cát', '1994', 'books/1762238338_1402.jpg', 'Rerum soluta possimus aut mollitia commodi necessitatibus est aliquid. Vitae ut corporis eum dolore harum. Voluptas sint optio vitae provident qui et possimus. At perspiciatis tempore nemo ad eos reprehenderit iste.', 499000.00, 'active', 'binh_thuong', 0.00, 0, 2948, 10, 0, '2025-10-26 06:13:39', '2025-11-20 17:29:14', 4),
(64, 'Lựa chọn đúng quan trọng hơn nỗ lực', 9, 'Tống Văn Chiêu', '2022', 'books/1762238361_2389.png', 'Cuốn sách giúp bạn hiểu rằng việc lựa chọn đúng hướng đi quan trọng hơn việc chỉ chăm chỉ nỗ lực. Những quyết định sáng suốt sẽ dẫn đến thành công nhanh chóng hơn.', 1000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(65, 'Bí quyết thay đổi cuộc đời', 9, 'Tony Robbins', '2021', 'books/1762238389_317.jpg', 'Khám phá những bí mật và phương pháp đã được chứng minh để thay đổi cuộc sống của bạn theo hướng tích cực.', 1000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(66, 'Giảm \"xóc\"... Hành trình cuộc đời', 9, 'Lê Quốc Vinh', '2023', 'books/1762238375_1756.jpg', 'Học cách giảm thiểu những rung động tiêu cực trong cuộc sống và tạo dựng hành trình ổn định, hạnh phúc.', 2000.00, 'active', 'binh_thuong', 0.00, 0, 0, 3, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(67, 'Chúng ta cách nhau một bước chân', 9, 'Lý Hoàng Dũng', '2022', 'books/1761486189_tải xuống (2).jpg', 'Khám phá rằng sự khác biệt giữa thành công và thất bại đôi khi chỉ là một bước chân nhỏ. Hãy dám bước tiếp.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 1, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(68, 'Dám làm giàu', 10, 'Adam Khoo', '2020', 'books/1762238405_1249.jpg', 'Cuốn sách truyền cảm hứng về việc dám nghĩ lớn, dám hành động và dám theo đuổi giấc mơ làm giàu của mình.', 1000.00, 'active', 'binh_thuong', 0.00, 0, 0, 35, 1, '2025-10-26 06:41:57', '2025-11-21 15:43:50', NULL),
(69, 'Khởi nghiệp thành công', 10, 'Eric Ries', '2021', 'books/1762238421_2344.png', 'Hướng dẫn chi tiết về cách xây dựng startup từ ý tưởng đến thành công. Bao gồm các bài học từ những doanh nhân hàng đầu.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 13, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(70, 'Đắc Nhân Tâm', 9, 'Dale Carnegie', '1936', 'books/1762238434_360.jpg', 'Cuốn sách kinh điển về nghệ thuật giao tiếp và ứng xử. Giúp bạn hiểu và làm việc tốt hơn với mọi người.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(71, 'Khởi Dậy Tiềm Thức', 9, 'Joseph Murphy', '1963', 'books/1762238446_1345.jpg', 'Khám phá sức mạnh vô tận của tiềm thức và cách sử dụng nó để thay đổi cuộc sống. Tác phẩm kinh điển về phát triển bản thân và sức mạnh của tâm trí.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(72, 'Nhà Giả Kim', 9, 'Paulo Coelho', '1988', 'books/1761906405_1756.jpg', 'Hành trình tìm kiếm kho báu và ý nghĩa cuộc sống. Một tác phẩm triết lý sâu sắc về việc theo đuổi ước mơ và lắng nghe trái tim mình.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(73, 'Cà Phê Cùng Tony', 9, 'Tony Buổi Sáng', '2019', 'books/1762238460_1402.jpg', 'Những câu chuyện truyền cảm hứng và bài học cuộc sống từ Tony Buổi Sáng. Thích hợp cho những buổi sáng đọc sách với tách cà phê.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(74, 'Tuổi Trẻ Đáng Giá Bao Nhiêu', 9, 'Rosie Nguyễn', '2018', 'books/1762238471_2389.png', 'Câu chuyện về tuổi trẻ, ước mơ và cách tận dụng tuổi thanh xuân để xây dựng tương lai tươi sáng.', 0.00, 'active', 'binh_thuong', 0.00, 0, 0, 1, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(75, 'Tư Duy Ngược', 9, 'Nguyễn Anh Dũng', '2020', 'books/1762238537_1249.jpg', 'Học cách suy nghĩ khác biệt, nhìn vấn đề từ nhiều góc độ và tìm ra giải pháp sáng tạo.', 0.00, 'active', 'binh_thuong', 5.00, 0, 0, 6, 0, '2025-10-26 06:41:57', '2025-11-20 17:29:14', NULL),
(76, 'Kinh tế xây dựng công nghiệp 4.0', 1, 'PGS.TS. Bùi Mạnh Hùng', '2024', 'books/1762238486_1756.jpg', 'Cuốn sách về kinh tế xây dựng trong thời đại công nghiệp 4.0', 28000.00, 'active', 'binh_thuong', 5.00, 1, 150, 506, 0, '2025-11-03 09:31:36', '2025-12-03 05:03:47', NULL),
(77, 'Giáo trình Revit Structure theo kết cấu', 1, 'TS. Nguyễn Văn A', '2023', 'books/1762238524_1249.jpg', 'Hướng dẫn sử dụng Revit Structure trong thiết kế kết cấu', 150000.00, 'active', 'binh_thuong', 0.00, 1, 1571, 3532, 0, '2025-11-03 09:31:36', '2025-12-01 16:04:44', NULL),
(78, 'Hướng dẫn đồ án tổ chức và quản lý', 1, 'ThS. Trần Thị B', '2023', 'books/1762238496_360.jpg', 'Tài liệu hướng dẫn thực hiện đồ án về tổ chức và quản lý xây dựng', 120000.00, 'active', 'binh_thuong', 0.00, 1, 490, 1205, 1, '2025-11-03 09:31:36', '2025-12-03 05:03:39', NULL),
(79, 'Thiết kế kết cấu bê tông cốt thép', 1, 'PGS.TS. Lê Văn C', '2022', 'books/1762238510_1756.jpg', 'Giáo trình về thiết kế kết cấu bê tông cốt thép cho công trình xây dựng', 180000.00, 'active', 'binh_thuong', 0.00, 1, 361, 996, 0, '2025-11-03 09:31:36', '2025-11-20 17:29:14', NULL),
(82, 'Văn học Việt Nam', 1, 'Nguyễn Văn A', '2025', 'books/bb1f2f79-d9a9-4dd2-8cb2-a5c2db2ba362.png', 'mới', 26000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-11-11 09:41:23', '2025-11-20 17:29:14', NULL),
(83, 'Kinh tế học', 1, 'Phạm Thị D', '2025', 'books/887e2de2-aa36-4aed-91c4-feffb02f0be9.jpg', 'f', 2334000.00, 'active', 'binh_thuong', 0.00, 0, 0, 11, 0, '2025-11-11 09:53:21', '2025-11-20 17:29:14', 7),
(84, 'Văn học Việt Nam', 1, 'Trần Thị B', '2025', 'books/04977b84-42b8-499e-80ef-8c3a2fd5020f.png', 'gg', 6000000.00, 'active', 'binh_thuong', 0.00, 0, 0, 17, 0, '2025-11-12 06:05:05', '2025-11-20 17:29:14', 1),
(85, 'Lập trình Laravel từ A-Z', 6, 'Nguyễn Văn A', '2025', 'books/b0826e7b-862d-4c9d-a4de-1062695f2779.jpg', 'k', 30000.00, 'active', 'binh_thuong', 0.00, 0, 0, 3, 0, '2025-11-12 07:45:03', '2025-11-20 17:29:14', 3),
(86, 'Văn học Việt Nam', 6, 'Nguyễn Văn A', '2025', 'books/caf33ca3-5549-480f-b38a-b3f01f46b2eb.jpg', 'k', 20000.00, 'active', 'binh_thuong', 0.00, 0, 0, 1, 0, '2025-11-13 14:27:59', '2025-11-20 17:29:14', 1),
(87, 'sách A', 1, 'Nguyễn Văn A', '2025', 'books/a61a1df4-5354-4f72-b32d-f1045cf3bebe.jpg', NULL, 100000.00, 'active', 'binh_thuong', 0.00, 0, 0, 2, 20, '2025-11-14 09:57:55', '2025-11-20 17:29:14', 1),
(88, 'sách B', 1, 'Nguyễn Văn D', '2025', 'books/7f173450-c872-4bbb-83f0-266f125bf2f3.png', NULL, 100000.00, 'active', 'quy', 0.00, 0, 0, 4, 0, '2025-11-14 10:12:09', '2025-12-02 13:54:54', 1),
(89, 'sách C', 9, 'Nguyễn Văn A', '2025', 'books/ef07e6d9-1c5c-4e89-9d47-602ad509cfb0.jpg', NULL, 300000.00, 'active', 'tham_khao', 0.00, 0, 0, 4, 0, '2025-11-14 10:13:27', '2025-12-02 12:53:14', 1),
(90, 'sách A1', 1, 'Nguyễn Văn D', '2025', 'books/509db91e-e03f-4ff3-9f63-38b31ec0aeae.png', NULL, 20000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 5, '2025-11-16 04:45:51', '2025-11-21 20:25:17', 1),
(91, 'sách D', 1, 'Nguyễn Văn D', '2025', 'books/f5c5b4a2-0b00-461f-9024-b3883c93edb8.jpg', NULL, 10000.00, 'active', 'binh_thuong', 0.00, 0, 0, 5, 0, '2025-11-16 04:51:02', '2025-12-02 13:11:33', 1),
(92, 'sách loại 1', 8, 'Trần Thị B', '2025', 'books/93881653-bcda-47e3-a327-cf3f402a8a75.png', NULL, 150000.00, 'active', 'binh_thuong', 0.00, 0, 0, 2, 0, '2025-11-16 06:28:55', '2025-11-21 20:25:52', 8),
(93, 'sách  loại 2', 10, 'Nguyễn Văn A', '2025', 'books/b6c952d6-7b65-49e8-9d12-6bb0e77614a7.jpg', NULL, 100000.00, 'active', 'binh_thuong', 0.00, 0, 0, 5, 5, '2025-11-16 06:31:52', '2025-12-02 13:15:52', 1),
(94, 'Khoa học dữ liệu', 5, 'Hoàng Văn E', '2025', 'books/dfcf019c-ade5-4dd6-adef-927afd657b25.jpg', 'k', 30000.00, 'active', 'binh_thuong', 0.00, 0, 0, 2, 5, '2025-11-21 15:58:53', '2025-12-01 16:13:17', 4),
(95, 'Lập trình PHP', 9, 'Hoàng Văn E', '2025', 'books/7fa6f3b2-8d72-43ec-8174-3bbb25ff62c5.jpg', 'gg', 455000.00, 'active', 'binh_thuong', 0.00, 0, 0, 13, 1, '2025-11-21 16:01:14', '2025-12-02 14:39:21', 8),
(96, 'Lập trình Laravel từ A-Z', 8, 'Trần Thị B', '2025', 'books/148acbde-d904-4905-8e2f-e217c1cee586.jpg', 'j', 400000.00, 'active', 'binh_thuong', 0.00, 0, 0, 19, 0, '2025-11-21 16:02:03', '2025-12-02 15:05:16', 6),
(97, 'Kinh tế học', 1, 'Nguyễn Văn D', '2025', 'books/5fa13720-97b2-470b-bd4d-07edff47a717.png', 'k', 30000.00, 'active', 'binh_thuong', 0.00, 0, 0, 8, 1, '2025-11-21 16:06:32', '2025-12-02 14:44:20', 2),
(102, 'sách cấp 1', 9, 'Nguyễn Văn A', '2025', 'books/4c6a8bc9-a9f1-401e-8ac2-9d308fb52a48.jpg', NULL, 100000.00, 'active', 'binh_thuong', 0.00, 0, 0, 1, 0, '2025-11-25 03:30:30', '2025-12-02 15:12:19', 8),
(104, 'Khoa học dữ liệu', 2, 'Lê Văn C', '2025', 'books/b1f5e7cf-1ac2-45bb-b821-c29f5e91fb90.jpg', NULL, 60000.00, 'active', 'binh_thuong', 0.00, 0, 0, 1, 0, '2025-12-02 15:15:16', '2025-12-02 15:22:17', 2),
(105, 'Lập trình PHP', 8, 'Trần Thị B', '2025', 'books/050fb72a-c13c-46ea-a754-490b24d6cb4b.jpg', NULL, 50000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 4, '2025-12-02 15:16:14', '2025-12-02 15:21:52', 2),
(106, 'Lập trình PHP', 2, 'Hoàng Văn E', '2025', 'books/ff00726d-d5c1-422d-a128-229b1f0ac094.png', 'j', 600000.00, 'active', 'binh_thuong', 0.00, 0, 0, 0, 0, '2025-12-02 15:17:40', '2025-12-02 15:22:04', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrows`
--

CREATE TABLE `borrows` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_nguoi_muon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tinh_thanh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `huyen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_nha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reader_id` bigint UNSIGNED DEFAULT NULL,
  `librarian_id` bigint UNSIGNED DEFAULT NULL,
  `ngay_muon` date NOT NULL,
  `trang_thai` enum('chua_hoan_tat','Dang muon','Da tra','Qua han','Mat sach') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dang muon',
  `ghi_chu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tien_coc` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tien_ship` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tien_thue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `tong_tien` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `borrows`
--

INSERT INTO `borrows` (`id`, `ten_nguoi_muon`, `tinh_thanh`, `huyen`, `xa`, `so_nha`, `so_dien_thoai`, `reader_id`, `librarian_id`, `ngay_muon`, `trang_thai`, `ghi_chu`, `tien_coc`, `tien_ship`, `tien_thue`, `voucher_id`, `tong_tien`, `created_at`, `updated_at`) VALUES
(229, 'minh', 'hà nội', 'hoài đức', 'vân canh', '46', '0987654323', NULL, 1, '2025-11-28', 'Dang muon', NULL, 40000.00, 20000.00, 30000.00, NULL, 90000.00, '2025-11-28 03:55:26', '2025-12-01 06:13:55'),
(232, 'Super Admin', 'hà nội', 'hậu lộc', 'hn', 'số 52', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 16 ngày (Yêu cầu mượn 16 ngày)', 0.00, 30000.00, 0.00, NULL, 30000.00, '2025-12-01 06:05:49', '2025-12-02 12:42:19'),
(236, 'Super Admin', 'hà nội', 'hoài đức', 'hn', '46', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', 160000.00, 0.00, 28000.00, NULL, 188000.00, '2025-12-01 08:58:27', '2025-12-02 12:39:29'),
(237, 'Super Admin', 'hà nội', 'hoài đức', 'hn', '46', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', 12000.00, 0.00, 2000.00, NULL, 14000.00, '2025-12-01 15:49:59', '2025-12-02 12:39:29'),
(239, 'Super Admin', '', '', 'hn', '', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', 160000.00, 0.00, 28000.00, NULL, 188000.00, '2025-12-01 16:13:04', '2025-12-02 12:39:29'),
(240, 'Super Admin', '', '', 'hn', '', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', 12000.00, 0.00, 2000.00, NULL, 14000.00, '2025-12-01 16:13:20', '2025-12-02 12:39:29'),
(241, 'Super Admin', '', '', 'hn', '', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', 12000.00, 0.00, 2000.00, NULL, 14000.00, '2025-12-01 16:20:32', '2025-12-02 12:39:29'),
(242, 'Super Admin', '', '', 'hn', '', '0987654323', 18, NULL, '2025-12-01', 'Dang muon', 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', 150800.00, 0.00, 26000.00, NULL, 176800.00, '2025-12-01 16:20:48', '2025-12-02 12:39:29'),
(243, 'Super Admin', '', '', 'hn', '', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Yêu cầu mượn sách - 1 cuốn (Yêu cầu mượn 1 cuốn với thông số khác nhau)', 12000.00, 0.00, 2000.00, NULL, 14000.00, '2025-12-02 12:42:18', '2025-12-02 12:42:18'),
(244, 'Super Admin', 'Hà Nội', NULL, 'hn', 'số 52', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 12000.00, 0.00, 2000.00, NULL, 14000.00, '2025-12-02 12:45:19', '2025-12-02 12:45:19'),
(245, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 80000.00, 20000.00, 24000.00, NULL, 124000.00, '2025-12-02 12:49:35', '2025-12-02 12:49:35'),
(246, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 120000.00, 140000.00, 21000.00, NULL, 281000.00, '2025-12-02 12:58:11', '2025-12-02 12:58:11'),
(247, 'Super Admin', 'Hà Nội', 'hậu lộc', 'hn', 'số 52', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 40000.00, 0.00, 7000.00, NULL, 47000.00, '2025-12-02 12:58:35', '2025-12-02 12:58:36'),
(248, 'Super Admin', 'Hà Nội', 'hoài đức', 'hn', '46', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 144800.00, 0.00, 25000.00, NULL, 169800.00, '2025-12-02 13:11:50', '2025-12-02 13:11:50'),
(249, 'Super Admin', 'Hà Nội', 'hoài đức', 'hn', '46', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 40000.00, 0.00, 7000.00, NULL, 47000.00, '2025-12-02 13:16:07', '2025-12-02 13:16:07'),
(250, 'Super Admin', 'Hà Nội', 'hậu lộc', 'hn', '46', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 70000.00, 0.00, 0.00, NULL, 70000.00, '2025-12-02 13:25:41', '2025-12-02 13:25:41'),
(251, 'Super Admin', 'Hà Nội', 'hậu lộc', 'hn', 'số 52', '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 70000.00, 0.00, 0.00, NULL, 70000.00, '2025-12-02 13:55:44', '2025-12-02 13:55:44'),
(252, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 160000.00, 0.00, 28000.00, NULL, 188000.00, '2025-12-02 14:04:00', '2025-12-02 14:04:00'),
(253, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 160000.00, 0.00, 28000.00, NULL, 188000.00, '2025-12-02 14:11:11', '2025-12-02 14:11:11'),
(254, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 160000.00, 0.00, 28000.00, NULL, 188000.00, '2025-12-02 14:16:25', '2025-12-02 14:16:25'),
(255, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 182000.00, 0.00, 32000.00, NULL, 214000.00, '2025-12-02 14:26:40', '2025-12-02 14:26:40'),
(256, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 194000.00, 0.00, 34000.00, NULL, 228000.00, '2025-12-02 14:39:42', '2025-12-02 14:39:42'),
(257, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 160000.00, 5000.00, 28000.00, NULL, 193000.00, '2025-12-02 15:04:16', '2025-12-02 15:04:16'),
(258, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 160000.00, 0.00, 28000.00, NULL, 188000.00, '2025-12-02 15:05:25', '2025-12-02 15:05:25'),
(259, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-02', 'Dang muon', 'Đặt mượn từ giỏ sách', 24000.00, 0.00, 4000.00, NULL, 28000.00, '2025-12-02 15:22:25', '2025-12-02 15:22:25'),
(260, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-03', 'Dang muon', 'Đặt mượn từ giỏ sách', 40400.00, 0.00, 7000.00, NULL, 47400.00, '2025-12-03 05:04:24', '2025-12-03 05:04:24'),
(261, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-03', 'Dang muon', 'Đặt mượn từ giỏ sách', 40400.00, 0.00, 7000.00, NULL, 47400.00, '2025-12-03 05:08:08', '2025-12-03 05:08:08'),
(262, 'Super Admin', 'Hà Nội', NULL, 'hn', NULL, '0987654323', 18, NULL, '2025-12-03', 'Dang muon', 'Đặt mượn từ giỏ sách', 70000.00, 0.00, 12000.00, NULL, 82000.00, '2025-12-03 05:28:22', '2025-12-03 05:28:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrow_carts`
--

CREATE TABLE `borrow_carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reader_id` bigint UNSIGNED DEFAULT NULL,
  `total_items` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `borrow_carts`
--

INSERT INTO `borrow_carts` (`id`, `user_id`, `reader_id`, `total_items`, `created_at`, `updated_at`) VALUES
(1, 1, 18, 0, '2025-12-02 12:44:53', '2025-12-03 05:28:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrow_cart_items`
--

CREATE TABLE `borrow_cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `borrow_cart_id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `borrow_days` int NOT NULL DEFAULT '14',
  `distance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `tien_coc` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Tiền cọc đã tính',
  `tien_thue` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Tiền thuê đã tính',
  `is_selected` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrow_items`
--

CREATE TABLE `borrow_items` (
  `id` bigint UNSIGNED NOT NULL,
  `borrow_id` bigint UNSIGNED NOT NULL,
  `ngay_muon` date DEFAULT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `inventorie_id` bigint UNSIGNED DEFAULT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `tien_coc` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tien_coc_da_thu` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Số tiền cọc đã thu (có thể thu từng phần)',
  `tien_coc_da_hoan` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Số tiền cọc đã hoàn',
  `phuong_thuc_thu_coc` enum('tien_mat','chuyen_khoan','online','khac') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phương thức thu tiền cọc',
  `phuong_thuc_hoan_coc` enum('tien_mat','chuyen_khoan','online','tru_vao_phat','khac') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phương thức hoàn tiền cọc',
  `ghi_chu_coc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú về tiền cọc',
  `trang_thai_coc` enum('cho_xu_ly','da_thu','da_hoan','tru_vao_phat') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cho_xu_ly' COMMENT 'Trạng thái tiền cọc: chờ xử lý, đã thu, đã hoàn, trừ vào phạt',
  `ngay_thu_coc` date DEFAULT NULL COMMENT 'Ngày thu tiền cọc',
  `ngay_hoan_coc` date DEFAULT NULL COMMENT 'Ngày hoàn tiền cọc',
  `tien_thue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tien_ship` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ngay_hen_tra` date NOT NULL,
  `ngay_tra_thuc_te` date DEFAULT NULL,
  `trang_thai` enum('Cho duyet','Chua nhan','Dang muon','Da tra','Qua han','Mat sach','Hong') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cho duyet',
  `so_lan_gia_han` int NOT NULL DEFAULT '0',
  `ngay_gia_han_cuoi` date DEFAULT NULL,
  `ghi_chu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `borrow_items`
--

INSERT INTO `borrow_items` (`id`, `borrow_id`, `ngay_muon`, `book_id`, `inventorie_id`, `voucher_id`, `tien_coc`, `tien_coc_da_thu`, `tien_coc_da_hoan`, `phuong_thuc_thu_coc`, `phuong_thuc_hoan_coc`, `ghi_chu_coc`, `trang_thai_coc`, `ngay_thu_coc`, `ngay_hoan_coc`, `tien_thue`, `tien_ship`, `ngay_hen_tra`, `ngay_tra_thuc_te`, `trang_thai`, `so_lan_gia_han`, `ngay_gia_han_cuoi`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(43, 228, '2025-11-20', 102, 196, NULL, 40000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 20000.00, '2025-11-26', NULL, 'Qua han', 0, NULL, NULL, '2025-11-23 03:37:29', '2025-11-26 08:57:00'),
(46, 235, '2025-11-27', 88, 149, NULL, 100000.00, 100000.00, 100000.00, 'tien_mat', 'tien_mat', NULL, 'da_hoan', '2025-12-01', '2025-12-01', 0.00, 20000.00, '2025-11-28', NULL, 'Mat sach', 0, NULL, NULL, '2025-11-26 09:30:44', '2025-12-01 08:56:06'),
(48, 233, '2025-11-28', 1, 176, NULL, 100000.00, 100000.00, 100000.00, 'tien_mat', 'tien_mat', NULL, 'da_hoan', '2025-12-01', '2025-12-01', 0.00, 20000.00, '2025-12-11', NULL, 'Hong', 0, NULL, NULL, '2025-11-26 19:10:45', '2025-12-01 06:44:24'),
(49, 228, '2025-11-26', 102, 200, NULL, 40000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 20000.00, '2025-11-27', NULL, 'Dang muon', 0, NULL, NULL, '2025-11-27 15:59:19', '2025-11-27 16:00:08'),
(51, 229, '2025-11-27', 102, 197, NULL, 40000.00, 40000.00, 40000.00, 'tien_mat', 'tien_mat', NULL, 'da_hoan', '2025-12-01', '2025-12-01', 30000.00, 20000.00, '2025-11-28', NULL, 'Dang muon', 0, NULL, NULL, '2025-11-28 04:00:52', '2025-12-01 06:13:55'),
(52, 230, '2025-11-30', 96, 190, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 0.00, '2025-12-14', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày) - Khoảng cách: 1km', '2025-11-30 03:51:18', '2025-11-30 03:51:18'),
(53, 231, '2025-11-30', 96, 190, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 0.00, '2025-12-14', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày) - Khoảng cách: 1km', '2025-11-30 03:51:18', '2025-11-30 03:51:18'),
(54, 232, '2025-12-01', 46, 52, NULL, 0.00, 100000.00, 100000.00, 'chuyen_khoan', 'online', NULL, 'da_hoan', '2025-12-01', '2025-12-01', 0.00, 30000.00, '2025-12-17', NULL, 'Dang muon', 0, NULL, 'Yêu cầu mượn sách - 16 ngày (Yêu cầu mượn 16 ngày) - Khoảng cách: 11km', '2025-12-01 06:05:49', '2025-12-02 12:42:19'),
(55, 234, '2025-12-01', 97, 191, NULL, 100000.00, 100000.00, 0.00, 'tien_mat', NULL, NULL, 'da_thu', '2025-12-01', NULL, 0.00, 0.00, '2025-12-15', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 07:16:03', '2025-12-01 09:15:43'),
(56, 236, '2025-12-01', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-15', NULL, 'Dang muon', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 08:58:27', '2025-12-02 12:39:29'),
(57, 237, '2025-12-01', 97, 191, NULL, 12000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 2000.00, 0.00, '2025-12-15', NULL, 'Dang muon', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 15:49:59', '2025-12-02 12:39:29'),
(58, 238, '2025-12-01', 46, 52, NULL, 100000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 0.00, '2025-12-15', NULL, 'Mat sach', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 16:04:52', '2025-12-01 16:08:35'),
(59, 239, '2025-12-01', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-15', NULL, 'Dang muon', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 16:13:04', '2025-12-02 12:39:29'),
(60, 240, '2025-12-01', 94, 181, NULL, 12000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 2000.00, 0.00, '2025-12-15', NULL, 'Dang muon', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 16:13:20', '2025-12-02 12:39:29'),
(61, 241, '2025-12-01', 97, 191, NULL, 12000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 2000.00, 0.00, '2025-12-15', NULL, 'Dang muon', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 16:20:32', '2025-12-02 12:39:29'),
(62, 242, '2025-12-01', 60, 66, NULL, 150800.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 26000.00, 0.00, '2025-12-15', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn sách - 14 ngày (Yêu cầu mượn 14 ngày)', '2025-12-01 16:20:48', '2025-12-02 12:39:29'),
(63, 243, '2025-12-02', 97, 191, NULL, 12000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 2000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn sách - 1 cuốn (Quyển 1 - 14 ngày)', '2025-12-02 12:42:18', '2025-12-02 12:42:18'),
(64, 244, '2025-12-02', 97, 191, NULL, 12000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 2000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 12:45:19', '2025-12-02 12:45:19'),
(65, 245, '2025-12-02', 93, 159, NULL, 40000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 12000.00, 0.00, '2025-12-26', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 2 cuốn - 24 ngày - Khoảng cách: 9.00km', '2025-12-02 12:49:35', '2025-12-02 12:49:35'),
(66, 245, '2025-12-02', 93, 160, NULL, 40000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 12000.00, 0.00, '2025-12-26', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 2 cuốn - 24 ngày - Khoảng cách: 9.00km', '2025-12-02 12:49:35', '2025-12-02 12:49:35'),
(67, 246, '2025-12-02', 89, 151, NULL, 120000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 21000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày - Khoảng cách: 33.00km', '2025-12-02 12:58:11', '2025-12-02 12:58:11'),
(68, 247, '2025-12-02', 93, 159, NULL, 40000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 7000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 12:58:35', '2025-12-02 12:58:35'),
(69, 248, '2025-12-02', 58, 64, NULL, 144800.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 25000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 13:11:50', '2025-12-02 13:11:50'),
(70, 249, '2025-12-02', 93, 159, NULL, 40000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 7000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 13:16:07', '2025-12-02 13:16:07'),
(71, 250, '2025-12-02', 88, 147, NULL, 70000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 13:25:41', '2025-12-02 13:25:41'),
(72, 251, '2025-12-02', 88, 147, NULL, 70000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 0.00, 0.00, '2025-12-18', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 16 ngày', '2025-12-02 13:55:44', '2025-12-02 13:55:44'),
(73, 252, '2025-12-02', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 14:04:00', '2025-12-02 14:04:00'),
(74, 253, '2025-12-02', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 14:11:11', '2025-12-02 14:11:11'),
(75, 254, '2025-12-02', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 14:16:25', '2025-12-02 14:16:25'),
(76, 255, '2025-12-02', 95, 189, NULL, 182000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 32000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 14:26:40', '2025-12-02 14:26:40'),
(77, 256, '2025-12-02', 95, 189, NULL, 182000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 32000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 14:39:42', '2025-12-02 14:39:42'),
(78, 256, '2025-12-02', 97, 191, NULL, 12000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 2000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 14:39:42', '2025-12-02 14:39:42'),
(79, 257, '2025-12-02', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày - Khoảng cách: 6.00km', '2025-12-02 15:04:16', '2025-12-02 15:04:16'),
(80, 258, '2025-12-02', 96, 190, NULL, 160000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 28000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 15:05:25', '2025-12-02 15:05:25'),
(81, 259, '2025-12-02', 104, 207, NULL, 24000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 4000.00, 0.00, '2025-12-16', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-02 15:22:25', '2025-12-02 15:22:25'),
(82, 260, '2025-12-03', 47, 53, NULL, 40400.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 7000.00, 0.00, '2025-12-17', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-03 05:04:24', '2025-12-03 05:04:24'),
(83, 261, '2025-12-03', 47, 53, NULL, 40400.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 7000.00, 0.00, '2025-12-17', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-03 05:08:08', '2025-12-03 05:08:08'),
(84, 262, '2025-12-03', 19, 25, NULL, 70000.00, 0.00, 0.00, NULL, NULL, NULL, 'cho_xu_ly', NULL, NULL, 12000.00, 0.00, '2025-12-17', NULL, 'Cho duyet', 0, NULL, 'Yêu cầu mượn từ giỏ sách - 1 cuốn - 14 ngày', '2025-12-03 05:28:23', '2025-12-03 05:28:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrow_payments`
--

CREATE TABLE `borrow_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `borrow_id` bigint UNSIGNED NOT NULL,
  `borrow_item_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('deposit','borrow_fee','shipping_fee','damage_fee','refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('online','offline') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','success','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `borrow_payments`
--

INSERT INTO `borrow_payments` (`id`, `borrow_id`, `borrow_item_id`, `amount`, `payment_type`, `payment_method`, `payment_status`, `transaction_code`, `note`, `created_at`, `updated_at`) VALUES
(14, 229, NULL, 180000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 03:18:14', '2025-12-01 03:18:14'),
(15, 232, NULL, 130000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 06:06:26', '2025-12-01 06:06:26'),
(16, 229, 51, 40000.00, 'deposit', 'offline', 'success', NULL, 'Thu tiền cọc: 40,000đ', '2025-12-01 06:13:25', '2025-12-01 06:13:25'),
(17, 229, 51, 40000.00, 'refund', 'offline', 'success', NULL, 'Hoàn tiền cọc: 40,000đ', '2025-12-01 06:13:55', '2025-12-01 06:13:55'),
(18, 232, 54, 100000.00, 'deposit', 'offline', 'success', NULL, 'Thu tiền cọc: 100,000đ', '2025-12-01 06:14:43', '2025-12-01 06:14:43'),
(19, 232, 54, 100000.00, 'refund', 'online', 'success', '01234', 'Hoàn tiền cọc: 100,000đ', '2025-12-01 06:28:43', '2025-12-01 06:28:43'),
(27, 236, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:22:43', '2025-12-01 15:22:43'),
(28, 236, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:22:49', '2025-12-01 15:22:49'),
(29, 236, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:23:15', '2025-12-01 15:23:15'),
(30, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:50:52', '2025-12-01 15:50:52'),
(31, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:50:57', '2025-12-01 15:50:57'),
(32, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:51:08', '2025-12-01 15:51:08'),
(33, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:51:28', '2025-12-01 15:51:28'),
(34, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:56:42', '2025-12-01 15:56:42'),
(35, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:56:45', '2025-12-01 15:56:45'),
(36, 237, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 15:58:44', '2025-12-01 15:58:44'),
(38, 239, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 16:17:33', '2025-12-01 16:17:33'),
(39, 240, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 16:18:11', '2025-12-01 16:18:11'),
(40, 241, NULL, 100000.00, 'deposit', 'online', 'pending', NULL, NULL, '2025-12-01 16:21:07', '2025-12-01 16:21:07'),
(41, 244, NULL, 14000.00, 'deposit', 'online', 'pending', 'BRW244_1764679519', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 12:45:19', '2025-12-02 12:45:19'),
(42, 245, NULL, 124000.00, 'deposit', 'online', 'pending', 'BRW245_1764679775', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 12:49:35', '2025-12-02 12:49:35'),
(43, 246, NULL, 281000.00, 'deposit', 'online', 'pending', 'BRW246_1764680291', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - bank_transfer', '2025-12-02 12:58:11', '2025-12-02 12:58:11'),
(44, 247, NULL, 47000.00, 'deposit', 'online', 'pending', 'BRW247_1764680316', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 12:58:36', '2025-12-02 12:58:36'),
(45, 248, NULL, 169800.00, 'deposit', 'online', 'pending', 'BRW248_1764681110', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 13:11:50', '2025-12-02 13:11:50'),
(46, 249, NULL, 47000.00, 'deposit', 'online', 'pending', 'BRW249_1764681367', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 13:16:07', '2025-12-02 13:16:07'),
(47, 250, NULL, 70000.00, 'deposit', 'online', 'pending', 'BRW250_1764681941', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 13:25:41', '2025-12-02 13:25:41'),
(48, 251, NULL, 70000.00, 'deposit', 'online', 'pending', 'BRW251_1764683744', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 13:55:44', '2025-12-02 13:55:44'),
(49, 252, NULL, 188000.00, 'deposit', 'online', 'pending', 'BRW252_1764684240', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 14:04:00', '2025-12-02 14:04:00'),
(50, 253, NULL, 188000.00, 'deposit', 'online', 'pending', 'BRW253_1764684671', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 14:11:11', '2025-12-02 14:11:11'),
(51, 254, NULL, 188000.00, 'deposit', 'online', 'pending', 'BRW254_1764684985', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 14:16:25', '2025-12-02 14:16:25'),
(52, 255, NULL, 214000.00, 'deposit', 'online', 'pending', 'BRW255_1764685600', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 14:26:40', '2025-12-02 14:26:40'),
(53, 256, NULL, 228000.00, 'deposit', 'online', 'pending', 'BRW256_1764686382', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 14:39:42', '2025-12-02 14:39:42'),
(54, 257, NULL, 193000.00, 'deposit', 'online', 'pending', 'BRW257_1764687856', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 15:04:16', '2025-12-02 15:04:16'),
(55, 258, NULL, 188000.00, 'deposit', 'online', 'pending', 'BRW258_1764687925', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 15:05:25', '2025-12-02 15:05:25'),
(56, 259, NULL, 28000.00, 'deposit', 'online', 'pending', 'BRW259_1764688945', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-02 15:22:25', '2025-12-02 15:22:25'),
(57, 260, NULL, 47400.00, 'deposit', 'online', 'pending', 'BRW260_1764738264', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-03 05:04:24', '2025-12-03 05:04:24'),
(58, 261, NULL, 47400.00, 'deposit', 'online', 'pending', 'BRW261_1764738488', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-03 05:08:08', '2025-12-03 05:08:08'),
(59, 262, NULL, 82000.00, 'deposit', 'online', 'pending', 'BRW262_1764739703', 'Thanh toán tiền cọc, phí thuê và phí vận chuyển - vnpay', '2025-12-03 05:28:23', '2025-12-03 05:28:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_the_loai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trang_thai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `mau_sac` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `ten_the_loai`, `mo_ta`, `trang_thai`, `mau_sac`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Tiểu thuyết', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(2, 'Khoa học', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(3, 'Lịch sử', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(4, 'Công nghệ', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(5, 'Kinh tế', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(6, 'Văn học', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(7, 'Giáo dục', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(8, 'Y học', NULL, 'active', NULL, NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(9, 'Phát triển bản thân', 'Sách về kỹ năng sống, động lực và phát triển cá nhân', 'active', NULL, NULL, '2025-10-26 06:41:57', '2025-10-26 06:41:57'),
(10, 'Kinh doanh', 'Sách về kinh doanh, khởi nghiệp và làm giàu', 'active', NULL, NULL, '2025-10-26 06:41:57', '2025-10-26 06:41:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `review_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `likes_count` int NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `review_id`, `user_id`, `content`, `parent_id`, `likes_count`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 264, 23, 'h', NULL, 0, 1, '2025-11-04 06:48:21', '2025-11-04 06:48:21'),
(2, 265, 1, 'h', NULL, 0, 1, '2025-11-05 00:44:11', '2025-11-05 00:44:11'),
(3, 266, 1, 'gfg', NULL, 0, 1, '2025-11-07 16:36:50', '2025-11-07 16:36:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_nganh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_nganh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty_id` bigint UNSIGNED NOT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `truong_nganh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_thanh_lap` date DEFAULT NULL,
  `trang_thai` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `ten_nganh`, `ma_nganh`, `faculty_id`, `mo_ta`, `truong_nganh`, `so_dien_thoai`, `email`, `dia_chi`, `website`, `ngay_thanh_lap`, `trang_thai`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Ngành Công nghệ Thông tin', 'CNTT', 1, 'Ngành chuyên về công nghệ thông tin và phát triển phần mềm', 'ThS Phạm Văn D', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(2, 'Ngành Mạng máy tính', 'MMT', 1, 'Ngành chuyên về mạng máy tính và bảo mật', 'ThS Hoàng Thị E', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(3, 'Ngành Kinh tế học', 'KTH', 2, 'Ngành chuyên về kinh tế học cơ bản', 'TS Nguyễn Văn F', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(4, 'Ngành Tiếng Anh', 'TA', 3, 'Ngành chuyên về tiếng Anh', 'ThS Trần Thị G', NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `display_allocations`
--

CREATE TABLE `display_allocations` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED DEFAULT NULL,
  `quantity_on_display` int NOT NULL DEFAULT '0',
  `quantity_in_stock` int NOT NULL DEFAULT '0',
  `display_area` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_start_date` date DEFAULT NULL,
  `display_end_date` date DEFAULT NULL,
  `allocated_by` bigint UNSIGNED NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `published_date` date NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `documents`
--

INSERT INTO `documents` (`id`, `title`, `description`, `published_date`, `link_url`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Luật quản lý thuế và các văn bản hướng dẫn thi hành', 'Luật quản lý thuế số 78/2006/QH11 được Quốc hội nước Cộng hòa xã hội chủ nghĩa Việt Nam thông qua ngày 29 tháng 11 năm 2006, có hiệu lực từ ngày 01 tháng 7 năm 2007.', '2022-10-06', '#', NULL, '2025-11-03 09:31:35', '2025-11-03 09:31:35'),
(2, 'Nghị định 123/2020/NĐ-CP về hóa đơn điện tử', 'Nghị định quy định về hóa đơn điện tử và các vấn đề liên quan đến việc sử dụng hóa đơn điện tử trong hoạt động kinh doanh.', '2020-10-19', '#', NULL, '2025-11-03 09:31:35', '2025-11-03 09:31:35'),
(3, 'Thông tư 78/2021/TT-BTC về kế toán', 'Thông tư hướng dẫn chế độ kế toán doanh nghiệp, áp dụng cho các doanh nghiệp thuộc mọi lĩnh vực, mọi thành phần kinh tế.', '2021-09-14', '#', NULL, '2025-11-03 09:31:35', '2025-11-03 09:31:35'),
(4, 'Luật Doanh nghiệp 2020', 'Luật Doanh nghiệp số 59/2020/QH14 quy định về việc thành lập, quản lý, tổ chức lại, giải thể và hoạt động có liên quan của doanh nghiệp.', '2020-06-17', '#', NULL, '2025-11-03 09:31:35', '2025-11-03 09:31:35'),
(5, 'Luật Xây dựng 2014', 'Luật Xây dựng số 50/2014/QH13 quy định về hoạt động đầu tư xây dựng; quyền và nghĩa vụ của các cơ quan, tổ chức, cá nhân tham gia hoạt động đầu tư xây dựng.', '2014-06-18', '#', NULL, '2025-11-03 09:31:35', '2025-11-03 09:31:35'),
(6, 'Quy chuẩn kỹ thuật quốc gia về an toàn lao động trong xây dựng', 'Quy chuẩn quy định các yêu cầu về an toàn lao động trong thi công xây dựng công trình, áp dụng cho tất cả các hoạt động xây dựng.', '2021-03-15', '#', NULL, '2025-11-03 09:31:35', '2025-11-03 09:31:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_campaigns`
--

CREATE TABLE `email_campaigns` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'marketing',
  `target_criteria` json DEFAULT NULL,
  `status` enum('draft','scheduled','sending','sent','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `total_recipients` int NOT NULL DEFAULT '0',
  `sent_count` int NOT NULL DEFAULT '0',
  `opened_count` int NOT NULL DEFAULT '0',
  `clicked_count` int NOT NULL DEFAULT '0',
  `metadata` json DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_logs`
--

CREATE TABLE `email_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `campaign_id` bigint UNSIGNED DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('sent','delivered','opened','clicked','bounced','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NOT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `error_message` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_subscribers`
--

CREATE TABLE `email_subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','unsubscribed','bounced') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `preferences` json DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `subscribed_at` timestamp NOT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faculties`
--

CREATE TABLE `faculties` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_khoa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_khoa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `truong_khoa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_thanh_lap` date DEFAULT NULL,
  `trang_thai` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `faculties`
--

INSERT INTO `faculties` (`id`, `ten_khoa`, `ma_khoa`, `mo_ta`, `truong_khoa`, `so_dien_thoai`, `email`, `dia_chi`, `website`, `ngay_thanh_lap`, `trang_thai`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Khoa Công nghệ Thông tin', 'CNTT', 'Khoa Công nghệ Thông tin - Đào tạo các chuyên ngành về CNTT', 'PGS.TS Nguyễn Văn A', '028-1234567', 'cntt@university.edu.vn', NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(2, 'Khoa Kinh tế', 'KT', 'Khoa Kinh tế - Đào tạo các chuyên ngành về kinh tế', 'TS Trần Thị B', '028-1234568', 'kinhte@university.edu.vn', NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(3, 'Khoa Ngoại ngữ', 'NN', 'Khoa Ngoại ngữ - Đào tạo các chuyên ngành về ngôn ngữ', 'TS Lê Văn C', '028-1234569', 'ngoaingu@university.edu.vn', NULL, NULL, NULL, 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fines`
--

CREATE TABLE `fines` (
  `id` bigint UNSIGNED NOT NULL,
  `borrow_id` bigint UNSIGNED NOT NULL,
  `reader_id` bigint UNSIGNED NOT NULL,
  `borrow_item_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('late_return','damaged_book','lost_book','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','paid','waived','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_type` enum('Kho','Trung bay') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kho',
  `condition` enum('Moi','Tot','Trung binh','Cu','Hong') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Moi',
  `status` enum('Co san','Dang muon','Mat','Hong','Thanh ly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Co san',
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hinh_anh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `receipt_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventories`
--

INSERT INTO `inventories` (`id`, `book_id`, `barcode`, `location`, `storage_type`, `condition`, `status`, `purchase_price`, `purchase_date`, `notes`, `hinh_anh`, `created_by`, `receipt_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'BK000101', 'Kệ 4, Tầng 1, Vị trí 17', 'Kho', 'Tot', 'Dang muon', 191806.00, '2024-05-14', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-15 20:45:47'),
(2, 2, 'BK000201', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 194406.00, '2023-12-21', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-21 20:50:03'),
(3, 3, 'BK000301', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Co san', 170984.00, '2023-11-01', 'Corporis dolor ab quibusdam ipsa.', NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-16 10:50:13'),
(4, 3, 'BK000302', 'Kệ 2, Tầng 1, Vị trí 5', 'Kho', 'Trung binh', 'Dang muon', 186164.00, '2024-08-02', 'Sed qui sequi velit sit vel quia ut.', NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-16 09:23:33'),
(5, 3, 'BK000303', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Trung binh', 'Co san', 118378.00, '2024-12-17', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(6, 4, 'BK000401', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 77570.00, '2025-04-05', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(7, 4, 'BK000402', 'Kệ 10, Tầng 4, Vị trí 5', 'Kho', 'Cu', 'Dang muon', 179488.00, '2024-08-27', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-16 11:37:40'),
(8, 5, 'BK000501', 'Kệ 4, Tầng 4, Vị trí 6', 'Kho', 'Moi', 'Co san', 131601.00, '2025-02-06', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-16 10:50:13'),
(9, 6, 'BK000601', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Cu', 'Dang muon', 101330.00, '2024-03-15', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-16 11:27:46'),
(10, 6, 'BK000602', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Dang muon', 115284.00, '2024-05-11', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-20 17:46:34'),
(11, 6, 'BK000603', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Cu', 'Co san', 64894.00, '2024-05-27', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-25 01:26:29'),
(12, 7, 'BK000701', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Tot', 'Co san', 84384.00, '2025-08-30', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(13, 8, 'BK000801', 'Kệ 5, Tầng 3, Vị trí 12', 'Kho', 'Moi', 'Co san', 120374.00, '2025-10-06', 'Aut blanditiis iusto voluptate velit.', NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(14, 8, 'BK000802', 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 'Trung binh', 'Co san', 119522.00, '2023-11-20', 'Adipisci veniam qui adipisci ratione quas.', NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(15, 9, 'BK000009', 'Kệ 8, Tầng 3', 'Kho', 'Tot', 'Thanh ly', 77000.00, '2025-04-27', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-21 20:50:35'),
(16, 10, 'BK000010', 'Kệ 10, Tầng 1', 'Kho', 'Trung binh', 'Dang muon', 78070.00, '2025-05-12', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-16 05:30:02'),
(17, 11, 'BK000011', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 55207.00, '2025-09-02', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-11-21 15:42:17'),
(18, 12, 'BK000012', 'Kệ 9, Tầng 2', 'Kho', 'Trung binh', 'Co san', 126997.00, '2024-10-30', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(19, 13, 'BK000013', 'Kệ 9, Tầng 1', 'Kho', 'Moi', 'Co san', 73412.00, '2025-07-23', NULL, NULL, 1, NULL, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(20, 14, 'BK000020', 'Kho chính', 'Kho', 'Moi', 'Co san', 113000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(21, 15, 'BK000022', 'Kho chính', 'Kho', 'Moi', 'Co san', 87000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(22, 16, 'BK000024', 'Kho chính', 'Kho', 'Moi', 'Co san', 306000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(23, 17, 'BK000026', 'Kho chính', 'Kho', 'Moi', 'Co san', 199000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(24, 18, 'BK000028', 'Kho chính', 'Kho', 'Moi', 'Dang muon', 336000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-16 04:56:57'),
(25, 19, 'BK000030', 'Kho chính', 'Kho', 'Moi', 'Co san', 175000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(26, 20, 'BK000032', 'Kho chính', 'Kho', 'Moi', 'Dang muon', 410000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-15 18:11:44'),
(27, 21, 'BK000034', 'Kho chính', 'Kho', 'Moi', 'Co san', 155000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(28, 22, 'BK000036', 'Kho chính', 'Kho', 'Moi', 'Co san', 191000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(29, 23, 'BK000038', 'Kho chính', 'Kho', 'Moi', 'Co san', 211000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(30, 24, 'BK000040', 'Kho chính', 'Kho', 'Moi', 'Co san', 105000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(31, 25, 'BK000042', 'Kho chính', 'Kho', 'Moi', 'Co san', 146000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(32, 26, 'BK000044', 'Kho chính', 'Kho', 'Moi', 'Co san', 380000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(33, 27, 'BK000046', 'Kho chính', 'Kho', 'Moi', 'Co san', 176000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(34, 28, 'BK000048', 'Kho chính', 'Kho', 'Moi', 'Thanh ly', 293000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-20 17:29:13'),
(35, 29, 'BK000050', 'Kho chính', 'Kho', 'Moi', 'Co san', 456000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(36, 30, 'BK000052', 'Kho chính', 'Kho', 'Moi', 'Co san', 439000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(37, 31, 'BK000054', 'Kho chính', 'Kho', 'Moi', 'Co san', 165000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(39, 33, 'BK000058', 'Kho chính', 'Kho', 'Moi', 'Co san', 205000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(40, 34, 'BK000060', 'Kho chính', 'Kho', 'Moi', 'Co san', 473000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(42, 36, 'BK000064', 'Kho chính', 'Kho', 'Moi', 'Co san', 148000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(43, 37, 'BK000066', 'Kho chính', 'Kho', 'Moi', 'Co san', 198000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(44, 38, 'BK000068', 'Kho chính', 'Kho', 'Moi', 'Co san', 259000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(45, 39, 'BK000070', 'Kho chính', 'Kho', 'Moi', 'Co san', 390000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(46, 40, 'BK000072', 'Kho chính', 'Kho', 'Moi', 'Co san', 320000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(47, 41, 'BK000074', 'Kho chính', 'Kho', 'Moi', 'Co san', 108000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(48, 42, 'BK000076', 'Kho chính', 'Kho', 'Moi', 'Co san', 192000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(49, 43, 'BK000078', 'Kho chính', 'Kho', 'Moi', 'Co san', 458000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(50, 44, 'BK000080', 'Kho chính', 'Kho', 'Moi', 'Co san', 258000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(51, 45, 'BK000082', 'Kho chính', 'Kho', 'Moi', 'Co san', 456000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(52, 46, 'BK000084', 'Kho chính', 'Kho', 'Moi', 'Mat', 495000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-12-01 16:08:35'),
(53, 47, 'BK000086', 'Kho chính', 'Kho', 'Moi', 'Co san', 101000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(54, 48, 'BK000088', 'Kho chính', 'Kho', 'Moi', 'Co san', 279000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(55, 49, 'BK000090', 'Kho chính', 'Kho', 'Moi', 'Co san', 493000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(56, 50, 'BK000092', 'Kho chính', 'Kho', 'Moi', 'Co san', 444000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(58, 52, 'BK000096', 'Kho chính', 'Kho', 'Moi', 'Co san', 174000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(59, 53, 'BK000098', 'Kho chính', 'Kho', 'Moi', 'Co san', 485000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(62, 56, 'BK000104', 'Kho chính', 'Kho', 'Moi', 'Co san', 353000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(63, 57, 'BK000106', 'Kho chính', 'Kho', 'Moi', 'Co san', 155000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(64, 58, 'BK000108', 'Kho chính', 'Kho', 'Moi', 'Co san', 362000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(65, 59, 'BK000110', 'Kho chính', 'Kho', 'Moi', 'Co san', 113000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(66, 60, 'BK000112', 'Kho chính', 'Kho', 'Moi', 'Co san', 377000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(67, 61, 'BK000114', 'Kho chính', 'Kho', 'Moi', 'Co san', 106000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(68, 62, 'BK000116', 'Kho chính', 'Kho', 'Moi', 'Co san', 382000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(69, 63, 'BK000118', 'Kho chính', 'Kho', 'Moi', 'Co san', 499000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(70, 64, 'BK000120', 'Kho chính', 'Kho', 'Moi', 'Co san', 1000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(71, 65, 'BK000122', 'Kho chính', 'Kho', 'Moi', 'Co san', 1000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(72, 66, 'BK000124', 'Kho chính', 'Kho', 'Moi', 'Co san', 2000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(73, 67, 'BK000126', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(74, 68, 'BK000128', 'Kho chính', 'Kho', 'Moi', 'Co san', 1000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(75, 69, 'BK000130', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(76, 70, 'BK000132', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(77, 71, 'BK000134', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(78, 72, 'BK000136', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(79, 73, 'BK000138', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(80, 74, 'BK000140', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(81, 75, 'BK000142', 'Kho chính', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(82, 76, 'BK000144', 'Kho chính', 'Kho', 'Moi', 'Co san', 28000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(83, 77, 'BK000146', 'Kho chính', 'Kho', 'Moi', 'Dang muon', 150000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-17 09:53:01'),
(84, 78, 'BK000148', 'Kho chính', 'Kho', 'Moi', 'Co san', 120000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(85, 79, 'BK000150', 'Kho chính', 'Kho', 'Moi', 'Co san', 180000.00, '2025-11-11', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(86, 82, 'BK000087', 'Kệ A1', 'Kho', 'Moi', 'Co san', 11111.00, '2025-11-11', NULL, NULL, 1, 3, '2025-11-11 09:41:23', '2025-11-11 09:41:23'),
(87, 83, 'fdaaf', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Co san', 344000.00, '2025-11-11', 'hii', NULL, 1, NULL, '2025-11-11 09:53:21', '2025-11-11 14:28:50'),
(88, 12, 'BK000089', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Co san', 50000000.00, '2025-11-12', NULL, NULL, 1, 4, '2025-11-12 05:41:05', '2025-11-12 05:41:05'),
(89, 84, 'BK000091', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-12', NULL, NULL, 1, 5, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(90, 84, 'BK000093', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-12', NULL, NULL, 1, 5, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(91, 84, 'BK000095', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-12', NULL, NULL, 1, 5, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(92, 84, 'BK000097', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-12', NULL, NULL, 1, 5, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(93, 84, 'BK000099', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-12', NULL, NULL, 1, 5, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(94, 84, 'BK000103', 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-12', NULL, NULL, 1, 5, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(95, 85, 'BK000105', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-12', NULL, NULL, 1, 6, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(96, 85, 'BK000107', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-12', NULL, NULL, 1, 6, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(97, 85, 'BK000109', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-12', NULL, NULL, 1, 6, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(98, 85, 'BK000111', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-12', NULL, NULL, 1, 6, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(99, 85, 'BK000113', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-12', NULL, NULL, 1, 6, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(100, 85, 'BK000115', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-12', NULL, NULL, 1, 6, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(101, 6, 'BK000117', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 7, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(102, 6, 'BK000119', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 7, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(103, 6, 'BK000121', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 7, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(104, 6, 'BK000123', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 7, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(105, 6, 'BK000125', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 7, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(106, 3, 'BK000127', 'Kệ 2, Tầng 1, Vị trí 5', 'Kho', 'Moi', 'Co san', 95000.00, '2025-11-12', NULL, NULL, 1, 8, '2025-11-12 08:21:11', '2025-11-12 08:21:11'),
(107, 3, 'BK000129', 'Kệ 2, Tầng 1, Vị trí 5', 'Kho', 'Moi', 'Co san', 95000.00, '2025-11-12', NULL, NULL, 1, 8, '2025-11-12 08:21:11', '2025-11-12 08:21:11'),
(108, 4, 'BK000131', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Dang muon', 60000.00, '2025-11-12', NULL, NULL, 1, 9, '2025-11-12 08:27:02', '2025-11-16 11:37:45'),
(109, 4, 'BK000133', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 9, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(110, 4, 'BK000135', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 9, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(111, 4, 'BK000137', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 9, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(112, 4, 'BK000139', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-12', NULL, NULL, 1, 9, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(113, 86, 'BK000141', 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Co san', 2000000.00, '2025-11-13', NULL, NULL, 1, 10, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(114, 86, 'BK000143', 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Co san', 2000000.00, '2025-11-13', NULL, NULL, 1, 10, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(115, 86, 'BK000145', 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Co san', 2000000.00, '2025-11-13', NULL, NULL, 1, 10, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(116, 86, 'BK000147', 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Co san', 2000000.00, '2025-11-13', NULL, NULL, 1, 10, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(117, 86, 'BK000149', 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Co san', 2000000.00, '2025-11-13', NULL, NULL, 1, 10, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(118, 9, 'BK000151', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(119, 9, 'BK000152', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(120, 9, 'BK000153', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(121, 9, 'BK000154', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(122, 9, 'BK000155', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(123, 9, 'BK000156', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(124, 9, 'BK000157', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-13', NULL, NULL, 1, 11, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(125, 87, 'BK000158', 'Kệ 10, Tầng 1', 'Kho', 'Cu', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-16 10:50:13'),
(126, 87, 'BK000159', 'Kệ 10, Tầng 1', 'Kho', 'Cu', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-16 10:50:13'),
(127, 87, 'BK000160', 'Kệ 10, Tầng 1', 'Kho', 'Hong', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(128, 87, 'BK000161', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Mat', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(129, 87, 'BK000162', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-16 10:50:13'),
(130, 87, 'BK000163', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-16 10:50:13'),
(131, 87, 'BK000164', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-16 10:50:13'),
(132, 87, 'BK000165', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(133, 87, 'BK000166', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(134, 87, 'BK000167', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(135, 87, 'BK000168', 'Kệ 10, Tầng 1', 'Kho', 'Cu', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(136, 87, 'BK000169', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(137, 87, 'BK000170', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(138, 87, 'BK000171', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(139, 87, 'BK000172', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(140, 87, 'BK000173', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Hong', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-26 19:01:52'),
(141, 87, 'BK000174', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(142, 87, 'BK000175', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(143, 87, 'BK000176', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Mat', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(144, 87, 'BK000177', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 13, '2025-11-14 09:57:55', '2025-11-16 10:50:13'),
(145, 88, 'BK000178', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Cu', 'Mat', 0.00, '2025-11-14', NULL, NULL, 1, 14, '2025-11-14 10:12:09', '2025-11-26 09:27:09'),
(146, 88, 'BK000179', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Hong', 0.00, '2025-11-14', NULL, NULL, 1, 14, '2025-11-14 10:12:09', '2025-11-14 10:12:09'),
(147, 88, 'BK000180', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Trung binh', 'Co san', 0.00, '2025-11-14', NULL, NULL, 1, 14, '2025-11-14 10:12:09', '2025-11-16 10:50:13'),
(148, 88, 'BK000181', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Dang muon', 0.00, '2025-11-14', NULL, NULL, 1, 14, '2025-11-14 10:12:09', '2025-11-15 19:28:10'),
(149, 88, 'BK000182', 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 'Moi', 'Mat', 0.00, '2025-11-14', NULL, NULL, 1, 14, '2025-11-14 10:12:09', '2025-11-26 09:30:54'),
(150, 89, 'BK000183', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Thanh ly', 500000.00, '2025-11-14', NULL, NULL, 1, 15, '2025-11-14 10:13:27', '2025-11-21 15:30:04'),
(151, 89, 'BK000184', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Co san', 500000.00, '2025-11-14', NULL, NULL, 1, 15, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(152, 89, 'BK000185', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Co san', 500000.00, '2025-11-14', NULL, NULL, 1, 15, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(153, 89, 'BK000186', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Co san', 500000.00, '2025-11-14', NULL, NULL, 1, 15, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(154, 89, 'BK000187', 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 'Moi', 'Co san', 500000.00, '2025-11-14', NULL, NULL, 1, 15, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(156, 91, 'BK000189', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Dang muon', 0.00, '2025-11-16', NULL, NULL, 1, 18, '2025-11-16 04:51:02', '2025-11-16 04:51:36'),
(158, 92, 'BK000190', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Dang muon', 100000.00, '2025-11-16', NULL, NULL, 1, 20, '2025-11-16 06:28:55', '2025-11-16 06:32:53'),
(159, 93, 'BK000191', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-16', NULL, NULL, 1, 21, '2025-11-16 06:31:52', '2025-11-21 20:44:25'),
(160, 93, 'BK000192', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-16', NULL, NULL, 1, 21, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(161, 93, 'BK000193', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-16', NULL, NULL, 1, 21, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(162, 93, 'BK000194', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-16', NULL, NULL, 1, 21, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(163, 93, 'BK000195', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 0.00, '2025-11-16', NULL, NULL, 1, 21, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(164, 90, 'BK000196', 'Kho chính', 'Kho', 'Moi', 'Co san', 20000.00, '2025-11-16', 'Tự động import từ quản lý sách', NULL, 1, NULL, '2025-11-16 09:19:52', '2025-11-24 04:14:16'),
(165, 2, 'BK000197', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(166, 2, 'BK000198', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(167, 2, 'BK000199', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(168, 2, 'BK000200', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(169, 2, 'BK000202', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(170, 2, 'BK000203', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(171, 2, 'BK000204', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 220000.00, '2025-11-17', NULL, NULL, 1, 22, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(172, 10, 'BK000205', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 200000.00, '2025-11-17', NULL, NULL, 1, 23, '2025-11-17 15:04:30', '2025-11-17 15:04:30'),
(173, 10, 'BK000206', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 200000.00, '2025-11-17', NULL, NULL, 1, 23, '2025-11-17 15:04:30', '2025-11-17 15:04:30'),
(174, 10, 'BK000207', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 200000.00, '2025-11-17', NULL, NULL, 1, 23, '2025-11-17 15:04:31', '2025-11-17 15:04:31'),
(175, 10, 'BK000208', 'Kệ 10, Tầng 1', 'Kho', 'Moi', 'Co san', 200000.00, '2025-11-17', NULL, NULL, 1, 23, '2025-11-17 15:04:31', '2025-11-17 15:04:31'),
(176, 1, 'BK000209', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Hong', 250000.00, '2025-11-21', NULL, NULL, 1, 24, '2025-11-21 03:27:43', '2025-11-26 19:10:58'),
(177, 1, 'BK000210', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 250000.00, '2025-11-21', NULL, NULL, 1, 24, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(178, 1, 'BK000211', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 250000.00, '2025-11-21', NULL, NULL, 1, 24, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(179, 1, 'BK000212', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 250000.00, '2025-11-21', NULL, NULL, 1, 24, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(180, 1, 'BK000213', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 250000.00, '2025-11-21', NULL, NULL, 1, 24, '2025-11-21 03:27:44', '2025-11-21 03:27:44'),
(181, 94, 'BK000214', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-21', NULL, NULL, 1, 25, '2025-11-21 15:58:53', '2025-11-21 15:58:53'),
(182, 94, 'BK000215', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-21', NULL, NULL, 1, 25, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(183, 94, 'BK000216', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-21', NULL, NULL, 1, 25, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(184, 94, 'BK000217', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-21', NULL, NULL, 1, 25, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(185, 94, 'BK000218', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Moi', 'Co san', 300000.00, '2025-11-21', NULL, NULL, 1, 25, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(186, 4, 'BK000219', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-21', NULL, NULL, 1, 26, '2025-11-21 16:00:32', '2025-11-21 16:00:32'),
(187, 4, 'BK000220', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-21', NULL, NULL, 1, 26, '2025-11-21 16:00:32', '2025-11-21 16:00:32'),
(188, 4, 'BK000221', 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-21', NULL, NULL, 1, 26, '2025-11-21 16:00:32', '2025-11-21 16:00:32'),
(189, 95, 'BK000222', 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 'Moi', 'Co san', 5000000.00, '2025-11-21', NULL, NULL, 1, 27, '2025-11-21 16:01:14', '2025-11-21 16:01:14'),
(190, 96, 'BK000223', 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 'Moi', 'Co san', 600000.00, '2025-11-21', NULL, NULL, 1, 28, '2025-11-21 16:02:03', '2025-11-21 16:02:03'),
(191, 97, 'BK000224', 'Kệ 9, Tầng 1', 'Kho', 'Moi', 'Co san', 600000.00, '2025-11-21', 'k', NULL, 1, 29, '2025-11-21 16:06:40', '2025-11-21 19:54:50'),
(193, 6, 'BK000226', 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 'Moi', 'Co san', 60000.00, '2025-11-22', NULL, NULL, 1, 31, '2025-11-21 17:45:10', '2025-11-21 17:45:10'),
(194, 9, 'BK000227', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-22', NULL, NULL, 1, 32, '2025-11-21 17:48:05', '2025-11-21 17:48:05'),
(195, 9, 'BK000228', 'Kệ 8, Tầng 3', 'Kho', 'Moi', 'Co san', 150000.00, '2025-11-22', NULL, NULL, 1, 32, '2025-11-21 17:48:05', '2025-11-21 17:48:05'),
(196, 102, 'BK000188', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Dang muon', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:37:34'),
(197, 102, 'BK000225', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Dang muon', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-28 04:00:59'),
(198, 102, 'BK000229', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Co san', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(199, 102, 'BK000230', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Co san', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(200, 102, 'BK000231', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Dang muon', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-27 16:00:03'),
(201, 102, 'BK000232', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Co san', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(202, 102, 'BK000233', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Co san', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(203, 102, 'BK000234', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Co san', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(204, 102, 'BK000235', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Co san', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(205, 102, 'BK000236', 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 'Moi', 'Mat', 0.00, '2025-11-25', NULL, NULL, 1, 34, '2025-11-25 03:30:36', '2025-11-26 01:10:08'),
(207, 104, 'BK000237', 'Kệ 4, Tầng 1', 'Kho', 'Moi', 'Co san', 500000.00, '2025-12-02', NULL, NULL, 1, NULL, '2025-12-02 15:15:16', '2025-12-02 15:15:16'),
(208, 105, 'BK000238', 'Kệ 5, Tầng 3, Vị trí 12', 'Kho', 'Moi', 'Co san', 700000.00, '2025-12-02', NULL, NULL, 1, 35, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(209, 105, 'BK000239', 'Kệ 5, Tầng 3, Vị trí 12', 'Kho', 'Moi', 'Co san', 700000.00, '2025-12-02', NULL, NULL, 1, 35, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(210, 105, 'BK000240', 'Kệ 5, Tầng 3, Vị trí 12', 'Kho', 'Moi', 'Co san', 700000.00, '2025-12-02', NULL, NULL, 1, 35, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(211, 105, 'BK000241', 'Kệ 5, Tầng 3, Vị trí 12', 'Kho', 'Moi', 'Co san', 700000.00, '2025-12-02', NULL, NULL, 1, 35, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(212, 106, 'BK000242', 'Kệ 4, Tầng 4, Vị trí 6', 'Kho', 'Moi', 'Co san', 600000.00, '2025-12-02', NULL, NULL, 1, NULL, '2025-12-02 15:17:40', '2025-12-02 15:17:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_receipts`
--

CREATE TABLE `inventory_receipts` (
  `id` bigint UNSIGNED NOT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `storage_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_type` enum('Kho','Trung bay') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kho',
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_receipts`
--

INSERT INTO `inventory_receipts` (`id`, `receipt_number`, `receipt_date`, `book_id`, `quantity`, `storage_location`, `storage_type`, `unit_price`, `total_price`, `supplier`, `received_by`, `approved_by`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(3, 'PNK202511110001', '2025-11-11', 82, 1, 'Kệ A1', 'Kho', 11111.00, 11111.00, 'cty', 1, 1, 'approved', 'không', '2025-11-11 09:41:23', '2025-11-11 09:41:57'),
(4, 'PNK202511120001', '2025-11-12', 12, 1, 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 50000000.00, 50000000.00, 'f', 1, 1, 'rejected', 'f\nLý do từ chối: không nhập nữa', '2025-11-12 05:41:05', '2025-11-21 16:20:50'),
(5, 'PNK202511120002', '2025-11-12', 84, 6, 'Kệ 5, Tầng 5, Vị trí 4', 'Kho', 5000000.00, 30000000.00, 'cty', 1, 1, 'approved', 'k', '2025-11-12 06:05:05', '2025-11-12 06:05:12'),
(6, 'PNK202511120003', '2025-11-12', 85, 6, 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 300000.00, 1800000.00, 'k', 1, 1, 'approved', 'k', '2025-11-12 07:45:03', '2025-11-12 07:45:07'),
(7, 'PNK202511120004', '2025-11-12', 6, 5, 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 60000.00, 300000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5', '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(8, 'PNK202511120005', '2025-11-12', 3, 2, 'Kệ 2, Tầng 1, Vị trí 5', 'Kho', 95000.00, 190000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 2', '2025-11-12 08:21:11', '2025-11-12 08:21:11'),
(9, 'PNK202511120006', '2025-11-12', 4, 5, 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 60000.00, 300000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', '2025-11-12 08:26:43', '2025-11-12 08:27:02'),
(10, 'PNK202511130001', '2025-11-13', 86, 5, 'Kệ 10, Tầng 1, Vị trí 19', 'Kho', 2000000.00, 10000000.00, 'l', 1, 1, 'approved', 'k', '2025-11-13 14:27:59', '2025-11-13 14:28:17'),
(11, 'PNK202511130002', '2025-11-13', 9, 7, 'Kệ 8, Tầng 3', 'Kho', 150000.00, 1050000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', '2025-11-13 14:30:07', '2025-11-13 14:30:23'),
(12, 'PNK202511140001', '2025-11-14', 1, 10, 'Kệ 4, Tầng 1, Vị trí 17', 'Kho', 250000.00, 2500000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'rejected', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 10. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.\nLý do từ chối: không nhập nữa', '2025-11-14 09:55:47', '2025-11-21 16:20:04'),
(13, 'PNK202511140002', '2025-11-14', 87, 20, 'Kệ 10, Tầng 1', 'Kho', 0.00, 0.00, NULL, 1, 1, 'approved', NULL, '2025-11-14 09:57:55', '2025-11-14 10:05:32'),
(14, 'PNK202511140003', '2025-11-14', 88, 5, 'Kệ 1, Tầng 1, Vị trí 19', 'Kho', 0.00, 0.00, NULL, 1, 1, 'rejected', '\nLý do từ chối: không nhập nưaz', '2025-11-14 10:12:09', '2025-11-21 16:20:31'),
(15, 'PNK202511140004', '2025-11-14', 89, 5, 'Kệ 1, Tầng 5, Vị trí 13', 'Kho', 500000.00, 2500000.00, NULL, 1, 1, 'rejected', '\nLý do từ chối: không muốn nhập nữa', '2025-11-14 10:13:27', '2025-11-21 17:35:38'),
(16, 'PNK202511150001', '2025-11-15', 10, 5, 'Kệ 10, Tầng 1', 'Kho', 200000.00, 1000000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'rejected', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.\nLý do từ chối: không nhập nữa', '2025-11-15 14:17:08', '2025-11-21 16:20:20'),
(17, 'PNK202511160001', '2025-11-16', 90, 1, 'Kệ 4, Tầng 1', 'Kho', 0.00, 0.00, NULL, 1, 1, 'approved', NULL, '2025-11-16 04:45:51', '2025-11-16 04:46:36'),
(18, 'PNK202511160002', '2025-11-16', 91, 1, 'Kệ 4, Tầng 1', 'Kho', 0.00, 0.00, NULL, 1, 1, 'rejected', '\nLý do từ chối: không nhập nữa', '2025-11-16 04:51:02', '2025-11-21 16:19:53'),
(19, 'PNK202511160003', '2025-11-16', 1, 1, 'Kệ 4, Tầng 1', 'Kho', 0.00, 0.00, NULL, 1, 1, 'approved', NULL, '2025-11-16 05:35:46', '2025-11-16 05:36:01'),
(20, 'PNK202511160004', '2025-11-16', 92, 1, 'Kệ 8, Tầng 3', 'Kho', 100000.00, 100000.00, NULL, 1, 1, 'approved', NULL, '2025-11-16 06:28:55', '2025-11-16 06:29:16'),
(21, 'PNK202511160005', '2025-11-16', 93, 5, 'Kệ 4, Tầng 1', 'Kho', 0.00, 0.00, NULL, 1, 1, 'rejected', '\nLý do từ chối: không nhập nữa', '2025-11-16 06:31:52', '2025-11-21 16:19:18'),
(22, 'PNK202511170001', '2025-11-17', 2, 7, 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 220000.00, 1540000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', '2025-11-17 14:40:59', '2025-11-17 14:41:13'),
(23, 'PNK202511170002', '2025-11-17', 10, 4, 'Kệ 10, Tầng 1', 'Kho', 200000.00, 800000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 4. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', '2025-11-17 15:03:36', '2025-11-17 15:04:07'),
(24, 'PNK202511210001', '2025-11-21', 1, 5, 'Kệ 4, Tầng 1', 'Kho', 250000.00, 1250000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 6. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', '2025-11-21 03:27:05', '2025-11-21 03:27:28'),
(25, 'PNK202511210002', '2025-11-21', 94, 5, 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 300000.00, 1500000.00, 'h', 1, 1, 'approved', 'h', '2025-11-21 15:58:53', '2025-11-21 15:59:03'),
(26, 'PNK202511210003', '2025-11-21', 4, 3, 'Kệ 10, Tầng 2, Vị trí 14', 'Kho', 60000.00, 180000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 3. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', '2025-11-21 16:00:28', '2025-11-21 16:00:32'),
(27, 'PNK202511210004', '2025-11-21', 95, 1, 'Kệ 9, Tầng 1, Vị trí 12', 'Kho', 5000000.00, 5000000.00, 'h', 1, 1, 'approved', 'h', '2025-11-21 16:01:14', '2025-11-21 16:01:26'),
(28, 'PNK202511210005', '2025-11-21', 96, 1, 'Kệ 3, Tầng 3, Vị trí 13', 'Kho', 600000.00, 600000.00, 'j', 1, 1, 'rejected', 'h\nLý do từ chối: không nhập nữa', '2025-11-21 16:02:03', '2025-11-21 16:19:39'),
(29, 'PNK202511210006', '2025-11-21', 97, 1, 'Kệ 9, Tầng 1', 'Kho', 600000.00, 600000.00, 'j', 1, 1, 'approved', 'j', '2025-11-21 16:06:32', '2025-11-21 16:06:40'),
(31, 'PNK202511220002', '2025-11-22', 6, 1, 'Kệ 3, Tầng 5, Vị trí 16', 'Kho', 60000.00, 60000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo và duyệt khi cập nhật số lượng sách từ 0 lên 1.', '2025-11-21 17:45:10', '2025-11-21 17:45:10'),
(32, 'PNK202511220003', '2025-11-22', 9, 2, 'Kệ 8, Tầng 3', 'Kho', 150000.00, 300000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, 1, 'approved', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 2. Vui lòng duyệt phiếu để sách được nhập vào kho.', '2025-11-21 17:47:57', '2025-11-21 17:48:05'),
(33, 'PNK202511220004', '2025-11-22', 90, 4, 'Kho chính', 'Kho', 20000.00, 80000.00, 'Cập nhật trực tiếp từ quản lý sách', 1, NULL, 'pending', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 5. Vui lòng duyệt phiếu để sách được nhập vào kho.', '2025-11-21 20:25:17', '2025-11-21 20:25:17'),
(34, 'PNK202511250001', '2025-11-25', 102, 10, 'Kệ 5, Tầng 5, Vị trí 10', 'Trung bay', 0.00, 0.00, NULL, 1, 1, 'approved', NULL, '2025-11-25 03:30:30', '2025-11-25 03:30:36'),
(35, 'PNK202512020001', '2025-12-02', 105, 4, 'Kệ 5, Tầng 3, Vị trí 12', 'Kho', 700000.00, 2800000.00, 'cty', 1, 1, 'approved', 'k', '2025-12-02 15:16:14', '2025-12-02 15:16:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `type` enum('Nhap kho','Xuat kho','Chuyen kho','Kiem ke','Thanh ly','Sua chua') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `from_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_before` enum('Moi','Tot','Trung binh','Cu','Hong') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_after` enum('Moi','Tot','Trung binh','Cu','Hong') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_before` enum('Co san','Dang muon','Mat','Hong','Thanh ly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_after` enum('Co san','Dang muon','Mat','Hong','Thanh ly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `performed_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `inventory_id`, `type`, `quantity`, `from_location`, `to_location`, `condition_before`, `condition_after`, `status_before`, `status_after`, `reason`, `notes`, `performed_by`, `created_at`, `updated_at`) VALUES
(42, 8, 'Xuat kho', 8, 'Ke A', NULL, 'Trung binh', 'Cu', 'Co san', 'Thanh ly', 'Voluptatem cupiditate enim quis consequatur sequi nisi.', NULL, 1, '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(76, 18, 'Thanh ly', 6, NULL, 'Ke A', 'Tot', 'Moi', 'Thanh ly', 'Dang muon', 'Fuga nostrum ab eum maxime ipsa ex.', NULL, 23, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(84, 9, 'Chuyen kho', 5, 'Kho', 'Kho', 'Cu', 'Trung binh', 'Mat', 'Dang muon', 'Vel blanditiis ex repellendus fugit.', NULL, 1, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(87, 6, 'Chuyen kho', 7, NULL, NULL, 'Moi', 'Moi', 'Dang muon', 'Co san', 'Qui aut sit labore ut officiis velit consequatur.', NULL, 23, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(95, 17, 'Thanh ly', 10, NULL, 'Ke A', 'Tot', 'Moi', 'Co san', 'Mat', 'Et voluptates laudantium quia earum est praesentium repudiandae numquam.', 'Maiores recusandae rerum repellendus eos eius porro.', 23, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(114, 18, 'Chuyen kho', 2, 'Ke A', 'Ke A', 'Cu', 'Cu', 'Mat', 'Mat', 'Deleniti voluptate et excepturi harum.', NULL, 1, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(168, 14, 'Sua chua', 9, NULL, 'Ke A', 'Moi', 'Hong', 'Co san', 'Co san', 'Amet tempora quibusdam magni.', NULL, 23, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(173, 17, 'Thanh ly', 3, 'Kho', NULL, 'Trung binh', 'Cu', 'Co san', 'Co san', NULL, 'Officia in perferendis molestias id.', 23, '2025-10-26 06:13:57', '2025-10-26 06:13:57'),
(201, 20, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Eum asperiores qui.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(202, 21, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Et architecto vel tempore.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(203, 22, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Quas voluptatem.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(204, 23, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Harum minima voluptatem.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(205, 24, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Natus sapiente est.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(206, 25, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Placeat aut cum labore exercitationem.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(207, 26, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Amet eos sapiente.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(208, 27, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Non aspernatur voluptatem.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(209, 28, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Non repudiandae similique.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(210, 29, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Voluptatem tempora sit praesentium.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(211, 30, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Tempora harum quia reiciendis.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(212, 31, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Similique assumenda repudiandae.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(213, 32, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Recusandae possimus.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(214, 33, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Expedita error sit enim.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(215, 34, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Et deserunt nostrum adipisci.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(216, 35, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Et mollitia eos natus.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(217, 36, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Impedit tempora tempore officia.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(218, 37, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Debitis ut nemo.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(220, 39, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Vero quibusdam et.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(221, 40, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Porro dolores id.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(223, 42, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Excepturi asperiores non.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(224, 43, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Modi totam.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(225, 44, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Dolor eaque mollitia.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(226, 45, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Vel nostrum qui.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(227, 46, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Dolore ipsam enim.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(228, 47, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Qui ut et ut.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(229, 48, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Velit iusto vitae a totam.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(230, 49, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Ipsa soluta quia.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(231, 50, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Sit aliquid omnis ut.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(232, 51, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Dolore ut ut id.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(233, 52, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Est eos.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(234, 53, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Adipisci odit veritatis.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(235, 54, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Voluptatem asperiores neque.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(236, 55, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Quam iste.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(237, 56, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Voluptas temporibus occaecati in.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(239, 58, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Itaque minima consequatur quo.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(240, 59, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Consequatur et incidunt ut.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(243, 62, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Tempora modi mollitia et.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(244, 63, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Ea sit ipsum qui.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(245, 64, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Quidem ut deserunt.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(246, 65, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Ducimus asperiores nostrum sit.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(247, 66, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Assumenda esse sed distinctio.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(248, 67, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Veniam quod in.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(249, 68, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Doloremque molestias.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(250, 69, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Quaerat fugit fugit cum aspernatur.', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(251, 70, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Lựa chọn đúng quan trọng hơn nỗ lực', 1, '2025-11-11 09:30:09', '2025-11-11 09:30:09'),
(252, 71, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Bí quyết thay đổi cuộc đời', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(253, 72, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Giảm \"xóc\"... Hành trình cuộc đời', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(254, 73, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Chúng ta cách nhau một bước chân', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(255, 74, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Dám làm giàu', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(256, 75, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Khởi nghiệp thành công', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(257, 76, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Đắc Nhân Tâm', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(258, 77, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Khởi Dậy Tiềm Thức', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(259, 78, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Nhà Giả Kim', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(260, 79, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Cà Phê Cùng Tony', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(261, 80, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Tuổi Trẻ Đáng Giá Bao Nhiêu', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(262, 81, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Tư Duy Ngược', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(263, 82, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Kinh tế xây dựng công nghiệp 4.0', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(264, 83, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Giáo trình Revit Structure theo kết cấu', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(265, 84, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Hướng dẫn đồ án tổ chức và quản lý', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(266, 85, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: Thiết kế kết cấu bê tông cốt thép', 1, '2025-11-11 09:30:10', '2025-11-11 09:30:10'),
(267, 86, 'Nhap kho', 1, NULL, 'Kệ A1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511110001', 'không', 1, '2025-11-11 09:41:23', '2025-11-11 09:41:23'),
(268, 87, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 5, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho sách mới', 'f', 1, '2025-11-11 09:53:21', '2025-11-11 09:53:21'),
(269, 88, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120001', 'f', 1, '2025-11-12 05:41:05', '2025-11-12 05:41:05'),
(270, 89, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 4', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120002', 'k', 1, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(271, 90, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 4', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120002', 'k', 1, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(272, 91, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 4', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120002', 'k', 1, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(273, 92, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 4', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120002', 'k', 1, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(274, 93, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 4', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120002', 'k', 1, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(275, 94, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 4', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120002', 'k', 1, '2025-11-12 06:05:05', '2025-11-12 06:05:05'),
(276, 95, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120003', 'k', 1, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(277, 96, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120003', 'k', 1, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(278, 97, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120003', 'k', 1, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(279, 98, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120003', 'k', 1, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(280, 99, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120003', 'k', 1, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(281, 100, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120003', 'k', 1, '2025-11-12 07:45:03', '2025-11-12 07:45:03'),
(282, 101, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 5, Vị trí 16', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120004', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(283, 102, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 5, Vị trí 16', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120004', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(284, 103, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 5, Vị trí 16', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120004', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(285, 104, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 5, Vị trí 16', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120004', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(286, 105, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 5, Vị trí 16', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120004', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 07:47:26', '2025-11-12 07:47:26'),
(287, 106, 'Nhap kho', 1, NULL, 'Kệ 2, Tầng 1, Vị trí 5', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120005', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 08:21:11', '2025-11-12 08:21:11'),
(288, 107, 'Nhap kho', 1, NULL, 'Kệ 2, Tầng 1, Vị trí 5', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu tự động: PNK202511120005', 'Tự động tạo khi cập nhật số lượng sách', 1, '2025-11-12 08:21:11', '2025-11-12 08:21:11'),
(289, 108, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120006', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(290, 109, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120006', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(291, 110, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120006', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(292, 111, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120006', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(293, 112, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511120006', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 5. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-12 08:27:02', '2025-11-12 08:27:02'),
(294, 113, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130001', 'k', 1, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(295, 114, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130001', 'k', 1, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(296, 115, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130001', 'k', 1, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(297, 116, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130001', 'k', 1, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(298, 117, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130001', 'k', 1, '2025-11-13 14:27:59', '2025-11-13 14:27:59'),
(299, 118, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(300, 119, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(301, 120, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(302, 121, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(303, 122, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(304, 123, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(305, 124, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511130002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-13 14:30:23', '2025-11-13 14:30:23'),
(306, 125, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(307, 126, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(308, 127, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(309, 128, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(310, 129, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(311, 130, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(312, 131, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(313, 132, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(314, 133, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(315, 134, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(316, 135, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(317, 136, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(318, 137, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(319, 138, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(320, 139, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(321, 140, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(322, 141, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(323, 142, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(324, 143, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(325, 144, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140002', NULL, 1, '2025-11-14 09:57:55', '2025-11-14 09:57:55'),
(326, 145, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140003', NULL, 1, '2025-11-14 10:12:09', '2025-11-14 10:12:09'),
(327, 146, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140003', NULL, 1, '2025-11-14 10:12:09', '2025-11-14 10:12:09'),
(328, 147, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140003', NULL, 1, '2025-11-14 10:12:09', '2025-11-14 10:12:09'),
(329, 148, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140003', NULL, 1, '2025-11-14 10:12:09', '2025-11-14 10:12:09'),
(330, 149, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 1, Vị trí 19', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140003', NULL, 1, '2025-11-14 10:12:09', '2025-11-14 10:12:09'),
(331, 150, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 5, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140004', NULL, 1, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(332, 151, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 5, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140004', NULL, 1, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(333, 152, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 5, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140004', NULL, 1, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(334, 153, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 5, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140004', NULL, 1, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(335, 154, 'Nhap kho', 1, NULL, 'Kệ 1, Tầng 5, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511140004', NULL, 1, '2025-11-14 10:13:27', '2025-11-14 10:13:27'),
(337, 156, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160002', NULL, 1, '2025-11-16 04:51:02', '2025-11-16 04:51:02'),
(340, 158, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160004', NULL, 1, '2025-11-16 06:28:55', '2025-11-16 06:28:55'),
(341, 159, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160005', NULL, 1, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(342, 160, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160005', NULL, 1, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(343, 161, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160005', NULL, 1, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(344, 162, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160005', NULL, 1, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(345, 163, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511160005', NULL, 1, '2025-11-16 06:31:52', '2025-11-16 06:31:52'),
(346, 164, 'Nhap kho', 1, NULL, 'Kho chính', NULL, 'Moi', NULL, 'Co san', 'Tự động import tất cả sách từ quản lý sách', 'Import tự động cho sách: sách A1', 1, '2025-11-16 09:19:52', '2025-11-16 09:19:52'),
(347, 165, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(348, 166, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(349, 167, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(350, 168, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(351, 169, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(352, 170, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(353, 171, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 7. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 14:41:22', '2025-11-17 14:41:22'),
(354, 172, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 4. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 15:04:30', '2025-11-17 15:04:30'),
(355, 173, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 4. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 15:04:30', '2025-11-17 15:04:30'),
(356, 174, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 4. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 15:04:31', '2025-11-17 15:04:31'),
(357, 175, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511170002', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 4. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-17 15:04:31', '2025-11-17 15:04:31'),
(358, 176, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 6. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(359, 177, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 6. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(360, 178, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 6. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(361, 179, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 6. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-21 03:27:43', '2025-11-21 03:27:43'),
(362, 180, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210001', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 1 lên 6. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho. (Được tạo khi import từ quản lý kho)', 1, '2025-11-21 03:27:44', '2025-11-21 03:27:44'),
(363, 181, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 3, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210002', 'h', 1, '2025-11-21 15:58:53', '2025-11-21 15:58:53'),
(364, 182, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 3, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210002', 'h', 1, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(365, 183, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 3, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210002', 'h', 1, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(366, 184, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 3, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210002', 'h', 1, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(367, 185, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 3, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210002', 'h', 1, '2025-11-21 15:58:54', '2025-11-21 15:58:54'),
(368, 186, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210003', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 3. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-21 16:00:32', '2025-11-21 16:00:32'),
(369, 187, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210003', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 3. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-21 16:00:32', '2025-11-21 16:00:32'),
(370, 188, 'Nhap kho', 1, NULL, 'Kệ 10, Tầng 2, Vị trí 14', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210003', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 3. Vui lòng duyệt phiếu để số lượng sách được cập nhật vào kho.', 1, '2025-11-21 16:00:32', '2025-11-21 16:00:32'),
(371, 189, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210004', 'h', 1, '2025-11-21 16:01:14', '2025-11-21 16:01:14'),
(372, 190, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 3, Vị trí 13', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210005', 'h', 1, '2025-11-21 16:02:03', '2025-11-21 16:02:03'),
(373, 191, 'Nhap kho', 1, NULL, 'Kệ 9, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511210006', 'j', 1, '2025-11-21 16:06:40', '2025-11-21 16:06:40'),
(375, 193, 'Nhap kho', 1, NULL, 'Kệ 3, Tầng 5, Vị trí 16', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511220002', 'Phiếu nhập kho tự động được tạo và duyệt khi cập nhật số lượng sách từ 0 lên 1.', 1, '2025-11-21 17:45:10', '2025-11-21 17:45:10'),
(376, 194, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511220003', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 2. Vui lòng duyệt phiếu để sách được nhập vào kho.', 1, '2025-11-21 17:48:05', '2025-11-21 17:48:05'),
(377, 195, 'Nhap kho', 1, NULL, 'Kệ 8, Tầng 3', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511220003', 'Phiếu nhập kho tự động được tạo khi cập nhật số lượng sách từ 0 lên 2. Vui lòng duyệt phiếu để sách được nhập vào kho.', 1, '2025-11-21 17:48:05', '2025-11-21 17:48:05'),
(385, 196, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(386, 197, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(387, 198, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(388, 199, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(389, 200, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(390, 201, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(391, 202, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(392, 203, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(393, 204, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(394, 205, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 5, Vị trí 10', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202511250001', NULL, 1, '2025-11-25 03:30:36', '2025-11-25 03:30:36'),
(395, 207, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 1', NULL, 'Moi', NULL, 'Co san', 'Nhập kho sách mới', NULL, 1, '2025-12-02 15:15:16', '2025-12-02 15:15:16'),
(396, 208, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 3, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202512020001', 'k', 1, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(397, 209, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 3, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202512020001', 'k', 1, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(398, 210, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 3, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202512020001', 'k', 1, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(399, 211, 'Nhap kho', 1, NULL, 'Kệ 5, Tầng 3, Vị trí 12', NULL, 'Moi', NULL, 'Co san', 'Nhập kho theo phiếu: PNK202512020001', 'k', 1, '2025-12-02 15:16:55', '2025-12-02 15:16:55'),
(400, 212, 'Nhap kho', 1, NULL, 'Kệ 4, Tầng 4, Vị trí 6', NULL, 'Moi', NULL, 'Co san', 'Nhập kho sách mới', NULL, 1, '2025-12-02 15:17:40', '2025-12-02 15:17:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `librarians`
--

CREATE TABLE `librarians` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ho_ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ma_thu_thu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_tinh` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `chuc_vu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phong_ban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_vao_lam` date DEFAULT NULL,
  `ngay_het_han_hop_dong` date DEFAULT NULL,
  `luong_co_ban` decimal(10,2) DEFAULT NULL,
  `trang_thai` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `anh_dai_dien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bang_cap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kinh_nghiem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ghi_chu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_20_000000_create_backups_table', 1),
(6, '2025_10_09_165505_create_categories_table', 1),
(7, '2025_10_09_170205_create_books_table', 1),
(8, '2025_10_09_174138_add_role_to_users_table', 1),
(9, '2025_10_09_174328_create_readers_table', 1),
(10, '2025_10_09_174812_create_borrows_table', 1),
(11, '2025_10_09_175502_create_notifications_table', 1),
(12, '2025_10_09_181124_create_reviews_table', 1),
(13, '2025_10_09_181140_create_comments_table', 1),
(14, '2025_10_09_181603_create_favorites_table', 1),
(15, '2025_10_09_181616_create_wishlists_table', 1),
(16, '2025_10_09_181643_create_wishlist_items_table', 1),
(17, '2025_10_09_181754_create_fines_table', 1),
(18, '2025_10_09_182146_create_reservations_table', 1),
(19, '2025_10_09_182312_create_notification_templates_table', 1),
(20, '2025_10_09_182332_create_notification_logs_table', 1),
(21, '2025_10_09_182543_create_permission_tables', 1),
(22, '2025_10_09_182949_create_report_templates_table', 1),
(23, '2025_10_09_183145_create_search_logs_table', 1),
(24, '2025_10_09_183336_create_inventories_table', 1),
(25, '2025_10_09_183405_create_inventory_transactions_table', 1),
(26, '2025_10_10_073501_add_user_id_to_readers_table', 1),
(27, '2025_10_11_083029_update_users_role_enum', 1),
(28, '2025_10_11_102707_create_purchasable_books_table', 1),
(29, '2025_10_11_103719_add_fields_to_categories_table', 1),
(30, '2025_10_11_105023_create_carts_table', 1),
(31, '2025_10_11_105032_create_cart_items_table', 1),
(32, '2025_10_11_124646_add_rating_fields_to_books_table', 1),
(33, '2025_10_11_143212_create_authors_table', 1),
(34, '2025_10_11_144128_add_missing_fields_to_books_table', 1),
(35, '2025_10_11_151514_create_publishers_table', 1),
(36, '2025_10_11_151527_create_faculties_table', 1),
(37, '2025_10_11_151535_create_departments_table', 1),
(38, '2025_10_11_151550_create_librarians_table', 1),
(39, '2025_10_11_151622_create_violations_table', 1),
(40, '2025_10_11_151726_add_publisher_id_to_books_table', 1),
(41, '2025_10_11_151748_add_faculty_department_to_readers_table', 1),
(42, '2025_10_12_180620_add_so_luong_ton_to_purchasable_books_table', 1),
(43, '2025_10_12_180725_create_orders_table', 1),
(44, '2025_10_12_180738_create_order_items_table', 1),
(45, '2025_10_13_050733_create_audit_logs_table', 1),
(46, '2025_10_13_063554_create_review_likes_table', 1),
(47, '2025_10_13_063824_create_review_reports_table', 1),
(48, '2025_10_13_064216_add_title_and_status_to_reviews_table', 1),
(49, '2025_10_13_072029_create_email_campaigns_table', 1),
(50, '2025_10_13_072048_create_email_subscribers_table', 1),
(51, '2025_10_13_072101_create_email_logs_table', 1),
(52, '2025_10_13_073451_add_google_fields_to_users_table', 1),
(53, '2025_11_03_162639_create_documents_table', 2),
(54, '2025_11_03_162653_add_is_featured_to_books_table', 3),
(55, '2025_11_04_050605_add_image_to_documents_table', 4),
(56, '2025_11_03_142506_create_user_verifications_table', 5),
(57, '2025_11_03_142517_create_addresses_table', 5),
(58, '2025_11_03_142531_create_locations_table', 5),
(59, '2025_11_03_142543_create_book_items_table', 5),
(60, '2025_11_03_142554_create_stock_movements_table', 5),
(61, '2025_11_03_142606_create_display_allocations_table', 5),
(62, '2025_11_03_142621_create_loans_table', 5),
(63, '2025_11_03_142631_create_loan_items_table', 5),
(64, '2025_11_03_142641_create_deposits_table', 5),
(65, '2025_11_03_142650_create_payments_table', 5),
(66, '2025_11_03_142700_create_refunds_table', 5),
(67, '2025_11_03_142710_create_pricing_rules_table', 5),
(68, '2025_11_03_142721_create_shipments_table', 5),
(69, '2025_11_03_142731_create_spaces_table', 5),
(70, '2025_11_03_142741_create_tables_table', 5),
(71, '2025_11_03_142755_create_capacity_snapshots_table', 5),
(72, '2025_11_03_142806_create_reports_cache_table', 5),
(73, '2025_11_03_145252_create_seat_reservations_table', 5),
(74, '2025_11_05_130048_drop_unused_tables', 5),
(75, '2025_11_05_131830_add_storage_type_to_inventories_table', 6),
(76, '2025_11_05_131843_create_display_allocations_table', 6),
(77, '2025_11_05_131854_create_inventory_receipts_table', 6),
(78, '2025_11_05_131947_add_receipt_foreign_key_to_inventories_table', 6),
(79, '2025_11_07_020000_add_performance_indexes', 7),
(80, '2025_11_07_175726_update_orders_status_to_string', 7),
(96, '2025_11_08_134249_add_cancellation_reason_to_orders_table', 8),
(97, '2025_11_11_212208_add_hinh_anh_to_inventories_table', 8),
(98, '2025_11_12_141845_add_so_luong_to_books_table', 8),
(99, '2025_11_13_093557_fix_users_auto_increment', 8),
(100, '2025_11_13_220433_remove_dinh_dang_from_books_table', 8),
(101, '2025_11_14_135654_update_borrows_table', 9),
(102, '2025_11_14_140632_create_borrow_items_table', 10),
(103, '2025_11_14_142833_create_vouchers_table', 11),
(104, '2025_11_14_145936_add_loai_sach_to_books_table', 12),
(105, '2025_11_16_005448_add_inventorie_id_to_borrow_items_table', 13),
(106, '2025_11_16_014609_update_trang_thai_enum_in_borrows_table', 13),
(107, '2025_11_16_015018_update_trang_thai_enum_in_borrow_items_table', 14),
(108, '2025_11_16_031847_modify_trang_thai_column_in_borrows_table', 15),
(109, '2025_11_16_032300_modify_trang_thai_column_in_borrows_table', 16),
(110, '2025_11_16_153148_add_ngay_tra_thuc_te_to_borrow_items_table', 17),
(111, '2025_11_16_181533_fix_borrow_items_enum_trang_thai', 18),
(112, '2025_11_16_182357_make_inventorie_id_nullable_in_borrow_items', 19),
(113, '2025_11_21_000654_add_so_cccd_to_readers_table', 20),
(114, '2025_11_21_022618_add_user_info_fields_to_users_table', 21),
(115, '2025_11_21_031150_add_voucher_id_to_borrow_items_table', 22),
(116, '2025_11_28_114340_add_deleted_at_to_fines_table', 23),
(117, '2025_11_30_005517_create_borrow_payments_table', 24),
(118, '2025_11_30_005524_create_shipping_logs_table', 24),
(119, '2025_12_01_122318_add_deposit_management_fields_to_borrow_items_table', 25),
(121, '2025_12_01_150000_drop_unused_book_related_tables', 26),
(122, '2025_12_01_221454_verify_borrow_items_enum_trang_thai', 27),
(135, '2025_12_02_121008_drop_unused_api_tables', 28),
(136, '2025_12_02_121518_drop_reservations_table', 28),
(137, '2025_12_02_122651_drop_carts_and_cart_items_tables', 28),
(138, '2025_12_02_135650_create_borrow_carts_table', 28),
(139, '2025_12_02_135725_create_borrow_cart_items_table', 28),
(140, '2025_12_02_150000_final_check_and_drop_unused_tables', 28),
(141, '2025_12_02_165510_add_is_selected_to_borrow_cart_items_table', 28),
(142, '2025_12_02_172541_add_fee_columns_to_borrow_cart_items_table', 28);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 23),
(3, 'App\\Models\\User', 25);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `template_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sent','failed','delivered') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notification_logs`
--

INSERT INTO `notification_logs` (`id`, `template_id`, `type`, `channel`, `recipient`, `subject`, `content`, `status`, `error_message`, `metadata`, `sent_at`, `created_at`, `updated_at`) VALUES
(1, 4, 'fine_notice', 'sms', 'vnham@chau.health.vn', 'Quia quo quo.', 'Et aut autem nihil quam voluptates. Dicta qui veniam rerum quo provident. Quisquam vero iusto labore quasi velit nihil architecto.', 'failed', NULL, '{\"book_id\": 8, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-05 22:56:50', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(2, 2, 'system_maintenance', 'sms', 'lnguyen@khu.org', 'Dolores non.', 'Non exercitationem ut vero. Reiciendis magnam omnis sit doloribus qui dolorem.', 'pending', NULL, '{\"book_id\": 42, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-02 13:23:44', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(3, 1, 'reservation_ready', 'push', 'lyen@yahoo.com', 'Aut est culpa sunt.', 'Quod facilis ut quas dolor ullam nihil. Rerum non sapiente saepe recusandae ut. Et quia fuga mollitia nemo consequatur ratione eligendi.', 'pending', NULL, '{\"book_id\": 54, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-29 11:56:56', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(4, 1, 'fine_notice', 'push', 'hinh.ngon@ly.org.vn', 'Omnis iste praesentium iste eum.', 'Sit perferendis libero culpa ipsum veritatis molestiae nemo. Veniam sit rerum repudiandae laboriosam. Sit hic omnis excepturi.', 'pending', NULL, '{\"book_id\": 2, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-16 02:10:40', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(5, 2, 'reservation_ready', 'sms', 'mdam@gmail.com', 'Doloremque hic perferendis.', 'Rerum voluptates voluptas excepturi inventore. Impedit impedit non cumque omnis quasi modi asperiores. Ipsum qui accusantium dolorum laboriosam aut delectus non tempore.', 'delivered', NULL, '{\"book_id\": 44, \"borrow_id\": 196, \"fine_amount\": 99190.59}', '2025-08-03 18:53:59', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(6, NULL, 'new_book_available', 'push', 'tuyen75@gmail.com', 'Incidunt reprehenderit quidem.', 'Porro ipsa dolores laboriosam maiores at maxime. Dolor quia vel necessitatibus et reprehenderit.', 'delivered', NULL, '{\"book_id\": 60, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-11 07:55:33', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(7, 1, 'system_maintenance', 'sms', 'lai.nga@hotmail.com', 'Voluptatibus a maiores eligendi.', 'Voluptates maxime earum aut esse distinctio minima ea. Et eveniet quibusdam voluptatum quia.', 'failed', NULL, '{\"book_id\": 8, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-17 03:31:28', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(8, 2, 'borrow_reminder', 'sms', 'diep80@yahoo.com', 'Nihil libero.', 'Qui voluptatum iusto totam a omnis. Fuga reprehenderit sequi iure.', 'sent', NULL, '{\"book_id\": 9, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-29 19:12:36', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(9, 1, 'borrow_reminder', 'in_app', 'phuong35@co.int.vn', 'Voluptates exercitationem labore.', 'Incidunt et eos et. Placeat sequi sint possimus et illo. Dolorem autem aut possimus at.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 14547.62}', '2025-09-12 23:41:57', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(10, 2, 'reservation_ready', 'in_app', 'buu.giao@gmail.com', 'Nihil laborum enim hic.', 'Quae vel eum ex laudantium. Animi dolorum deserunt non nihil mollitia minima eos.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 143, \"fine_amount\": null}', '2025-10-20 11:48:02', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(11, 1, 'overdue_notice', 'in_app', 'dinh.xuyen@kha.gov.vn', 'Dolores ut qui voluptatibus.', 'Provident reiciendis nihil deserunt dolor. Sequi et id quis. At est maiores totam quia.', 'pending', NULL, '{\"book_id\": 63, \"borrow_id\": 174, \"fine_amount\": null}', '2025-10-11 12:06:17', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(12, 2, 'overdue_notice', 'sms', 'thoi.nghi@hotmail.com', 'Amet nulla cum.', 'Illo suscipit dolore velit. Dignissimos quo id id.', 'failed', NULL, '{\"book_id\": 58, \"borrow_id\": 151, \"fine_amount\": null}', '2025-10-11 18:43:50', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(13, NULL, 'reservation_ready', 'email', 'bthai@yahoo.com', 'Laudantium laborum qui.', 'Dolore eius ipsa quo. Assumenda ut vel blanditiis sint non ad deserunt. Distinctio et est iste laborum necessitatibus amet tempore.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 27, \"fine_amount\": null}', '2025-07-29 22:22:18', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(14, 3, 'system_maintenance', 'sms', 'cao.ty@yahoo.com', 'Distinctio est minus.', 'Sit culpa et magni pariatur eius quo consectetur architecto. Et impedit totam nulla.', 'failed', NULL, '{\"book_id\": 63, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-01 14:55:50', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(15, 3, 'reservation_ready', 'email', 'khong.thuy@lo.info.vn', 'Sit sapiente et.', 'Odit et ea ut. Fuga nam dolores omnis. Magni odit libero exercitationem odit est nemo sunt.', 'delivered', NULL, '{\"book_id\": 1, \"borrow_id\": 161, \"fine_amount\": null}', '2025-10-24 04:00:26', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(16, NULL, 'reservation_ready', 'email', 'vien.mang@hotmail.com', 'Voluptates animi ut fuga.', 'Error enim ratione quibusdam quo laudantium possimus molestiae error. Corrupti et vero ut illo.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 152, \"fine_amount\": 79863.34}', '2025-08-30 00:01:14', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(17, NULL, 'reservation_ready', 'push', 'aton@hotmail.com', 'Aut et fuga unde.', 'Nihil harum facilis minus sit id. Totam minima voluptatem sit omnis qui. Ut quia est et inventore qui dolorem.', 'sent', NULL, '{\"book_id\": 5, \"borrow_id\": 75, \"fine_amount\": null}', '2025-10-14 13:02:22', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(18, 1, 'fine_notice', 'sms', 'qma@hotmail.com', 'Et velit architecto.', 'Et ab officiis in rerum ipsa nihil. Rerum maiores consequatur fuga voluptas possimus nihil eveniet.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 82400.99}', '2025-10-22 03:47:45', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(19, NULL, 'system_maintenance', 'push', 'ztra@anh.com.vn', 'Rerum placeat necessitatibus voluptatem.', 'Consequatur tenetur totam ratione voluptates rerum. Molestias mollitia maxime qui ipsum ea repudiandae est corporis.', 'sent', NULL, '{\"book_id\": 3, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-23 05:38:54', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(20, NULL, 'fine_notice', 'in_app', 'pdon@ha.net', 'Ex harum ex nam.', 'Aut ipsa est omnis blanditiis. Nobis ex eius veritatis rerum. Aut ut dolores et neque ea aliquid.', 'failed', NULL, '{\"book_id\": 42, \"borrow_id\": 80, \"fine_amount\": null}', '2025-07-26 10:18:49', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(21, 1, 'new_book_available', 'sms', 'hao.cao@kim.mil.vn', 'Reprehenderit velit reprehenderit.', 'Itaque sed hic eligendi sint consectetur culpa eius. Quibusdam perferendis totam qui.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 65313.7}', '2025-08-26 14:40:16', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(22, NULL, 'reservation_ready', 'email', 'lam26@hotmail.com', 'Dolore harum.', 'Quia ipsum deleniti est aut. Magni illo quasi facilis occaecati hic doloremque et. Iusto voluptatem autem non placeat assumenda.', 'sent', NULL, '{\"book_id\": 50, \"borrow_id\": 158, \"fine_amount\": null}', '2025-08-14 19:56:28', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(23, 4, 'fine_notice', 'in_app', 'cam26@lu.int.vn', 'Incidunt quia excepturi.', 'Molestiae ut et est dolor voluptatem mollitia quae. Ipsam dolorem earum sed quidem hic labore. Exercitationem non nihil vel laborum et et quae.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-16 09:05:03', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(24, 1, 'system_maintenance', 'email', 'tham.dao@hotmail.com', 'Maxime quos et.', 'Qui quae eveniet non aut. Atque aliquid et possimus iusto. Ut minus error harum optio repudiandae.', 'delivered', NULL, '{\"book_id\": 11, \"borrow_id\": 138, \"fine_amount\": null}', '2025-08-06 09:48:48', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(25, 2, 'overdue_notice', 'sms', 'thuy.khuu@hotmail.com', 'Officia accusamus atque consequatur.', 'Saepe ut placeat necessitatibus quia. Eligendi placeat ut nam tempore quo voluptatibus voluptas. Pariatur ab consectetur culpa nihil.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 127, \"fine_amount\": 92309.77}', '2025-08-01 20:12:35', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(26, 1, 'system_maintenance', 'email', 'achung@hotmail.com', 'Est facere impedit.', 'Est minima illo magnam quidem culpa ratione. Aperiam aperiam mollitia qui sequi est.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 175, \"fine_amount\": null}', '2025-08-17 13:13:58', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(27, NULL, 'borrow_reminder', 'push', 'cam.nhien@hung.com', 'Dolor aut.', 'Consequatur autem natus fugiat maxime sit. Error fuga aperiam enim voluptatibus et velit consectetur. Ut voluptas tempore aut reiciendis qui sed.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-28 12:15:39', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(28, 2, 'overdue_notice', 'email', 'hang.do@yahoo.com', 'Illum ea voluptates.', 'Modi ut doloribus culpa commodi in autem sit. Ut aut qui laudantium. Explicabo eaque perferendis commodi ut.', 'failed', NULL, '{\"book_id\": 44, \"borrow_id\": 158, \"fine_amount\": null}', '2025-10-12 22:34:55', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(29, NULL, 'borrow_reminder', 'email', 'pman@yahoo.com', 'Quibusdam ut sint consequatur.', 'Eaque earum aperiam consequatur quisquam recusandae fugiat odit aspernatur. Et possimus et corporis totam sint omnis.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 13, \"fine_amount\": null}', '2025-10-19 18:03:36', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(30, 1, 'new_book_available', 'sms', 'trieu.hinh@gmail.com', 'Rerum eaque totam magnam.', 'Est officiis sunt sapiente autem. Ratione aperiam non fuga sunt. Dolor sit sed ea cumque officiis minus.', 'pending', 'Tenetur at et tempore.', '{\"book_id\": 20, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-21 18:44:50', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(31, 4, 'reservation_ready', 'sms', 'chu.thuong@cam.com', 'Impedit illum dolor.', 'Repudiandae quaerat nihil qui consequatur. Soluta et et iusto sunt quae.', 'delivered', 'Impedit dolorum occaecati necessitatibus et commodi.', '{\"book_id\": 52, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-15 20:12:12', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(32, 3, 'system_maintenance', 'email', 'dan.ho@dien.net.vn', 'Voluptatem rerum consequuntur ducimus.', 'Molestiae voluptas officia quas et id quas. Vero sequi optio quae voluptatum odit incidunt et impedit. Et nulla deserunt quia velit unde nihil.', 'pending', NULL, '{\"book_id\": 43, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-26 18:55:05', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(33, NULL, 'fine_notice', 'sms', 'ly.bien@gmail.com', 'Aut illo atque.', 'Accusantium doloribus inventore voluptatem alias. Omnis impedit non nobis.', 'failed', NULL, '{\"book_id\": 12, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-09 19:08:35', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(34, 1, 'overdue_notice', 'email', 'sam.trinh@da.mil.vn', 'Quisquam ipsum et.', 'Dolore ut rerum laboriosam culpa ratione quis voluptas. Soluta facere hic at dolorem eveniet eius ut. Voluptatem numquam maxime enim dolor.', 'failed', NULL, '{\"book_id\": 50, \"borrow_id\": 85, \"fine_amount\": 85112.48}', '2025-09-23 21:34:12', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(35, 2, 'overdue_notice', 'push', 'yen.chinh@hinh.org', 'Minima neque quidem.', 'Id ea debitis assumenda. Eum eos dolorum et ut ipsam.', 'sent', NULL, '{\"book_id\": 21, \"borrow_id\": null, \"fine_amount\": 71016.16}', '2025-09-23 15:38:28', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(36, 2, 'borrow_reminder', 'sms', 'trac.hanh@chung.edu.vn', 'Et aut natus.', 'Quasi corporis quae ut animi. Dignissimos ab dignissimos ut quidem quidem blanditiis.', 'delivered', NULL, '{\"book_id\": 34, \"borrow_id\": null, \"fine_amount\": 42960.34}', '2025-10-06 15:10:56', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(37, 2, 'fine_notice', 'push', 'tin46@gmail.com', 'Soluta facilis quas enim.', 'Debitis perferendis dolorem voluptatem iste voluptatem suscipit. Dolorum exercitationem qui qui ut provident placeat vitae. Delectus itaque id est est.', 'delivered', NULL, '{\"book_id\": 54, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-08 07:26:39', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(38, 4, 'reservation_ready', 'in_app', 'bang83@xa.gov.vn', 'Maxime fugiat occaecati.', 'Ut debitis cum in eaque. Voluptates commodi et ea doloremque quis dolorum.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-09 00:34:03', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(39, NULL, 'borrow_reminder', 'push', 'hang.tho@yahoo.com', 'Voluptatibus voluptatem maxime.', 'Non nesciunt qui qui et molestias quidem. Inventore nihil nihil a quibusdam. Aperiam voluptas eius nam modi officiis aut fugiat.', 'pending', NULL, '{\"book_id\": 61, \"borrow_id\": 17, \"fine_amount\": null}', '2025-08-04 21:31:24', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(40, 4, 'overdue_notice', 'in_app', 'unham@hotmail.com', 'Excepturi aspernatur eveniet.', 'Porro qui sint eligendi. Aut ipsum nesciunt non nam enim ea.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-02 05:22:14', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(41, 1, 'overdue_notice', 'push', 'vinh05@tong.biz', 'Vel numquam eum.', 'Libero eos eligendi inventore et aliquid aut corporis. A ipsum et voluptatibus et rerum. Sint et et sapiente vel.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-12 23:21:38', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(42, NULL, 'borrow_reminder', 'in_app', 'diep.nguy@khuu.biz', 'Soluta quia.', 'A fugit ut tempora ut sint et. Laudantium et ut sed eum sed molestias sunt. Quae tempore debitis officiis quis.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 53, \"fine_amount\": null}', '2025-09-12 08:30:28', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(43, 3, 'reservation_ready', 'in_app', 'khue.tiep@hotmail.com', 'Voluptas expedita totam facere.', 'Voluptatem occaecati iusto voluptatem iusto quae perferendis. Dolorem veniam possimus accusantium et quod.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 38, \"fine_amount\": 53287.4}', '2025-07-28 08:45:11', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(44, NULL, 'borrow_reminder', 'email', 'wthinh@hoang.biz', 'Et dolor distinctio et.', 'Soluta recusandae maiores asperiores natus. Facere nobis natus inventore eligendi. Rerum quae harum nam.', 'pending', 'Et eligendi eum et illum reprehenderit.', '{\"book_id\": 45, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-16 20:56:42', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(45, NULL, 'borrow_reminder', 'in_app', 'nhu95@au.com', 'Labore nesciunt ex suscipit.', 'Reiciendis quo corrupti natus est. Perspiciatis voluptates sit ad.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 92, \"fine_amount\": null}', '2025-10-02 07:43:28', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(46, 1, 'new_book_available', 'email', 'ngo.thac@dao.info.vn', 'Cumque similique magnam aut.', 'In deleniti perferendis ipsam molestias accusamus. Nihil et voluptatum doloribus quaerat assumenda.', 'failed', NULL, '{\"book_id\": 47, \"borrow_id\": 151, \"fine_amount\": 57677.89}', '2025-09-01 16:54:55', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(47, 1, 'fine_notice', 'in_app', 'huynh.ky@tu.ac.vn', 'Cupiditate suscipit harum esse.', 'Voluptate animi et quia voluptatem molestiae id harum. Dignissimos autem iste qui repellat.', 'sent', NULL, '{\"book_id\": 53, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-19 23:57:27', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(48, 3, 'overdue_notice', 'email', 'dao22@cam.com', 'Labore sunt aut distinctio.', 'Delectus voluptatum id dolores deleniti. Quo blanditiis et facere consequatur quos et non. Earum dolorem est autem qui.', 'sent', NULL, '{\"book_id\": 24, \"borrow_id\": 148, \"fine_amount\": null}', '2025-09-18 03:05:16', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(49, 3, 'reservation_ready', 'push', 'duc.thai@hotmail.com', 'Porro architecto quo ullam.', 'Provident magnam nisi suscipit dolorem. Consequuntur qui maxime neque. Nisi saepe non ipsa ab recusandae vel.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-17 11:14:31', '2025-10-26 06:13:53', '2025-10-26 06:13:53'),
(50, 1, 'overdue_notice', 'push', 'lo.thuy@mac.com', 'Necessitatibus consequatur hic.', 'Molestiae architecto illo quae. Error molestiae consequuntur accusantium dolorem neque dolorem. Voluptas rerum asperiores itaque quis odit id.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 154, \"fine_amount\": null}', '2025-10-13 17:41:41', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(51, 3, 'borrow_reminder', 'push', 'chau.khau@gmail.com', 'Ab molestias officia quis.', 'Molestias et quo eos qui asperiores ipsum laborum. Sint illo deleniti voluptatem eum dolores autem quis ut. Eos ab fugit nobis dicta dolorem.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 19359.41}', '2025-08-15 09:37:25', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(52, NULL, 'system_maintenance', 'sms', 'dinh.ngoc@khuu.info', 'Qui atque velit.', 'Quam doloremque quo et debitis accusantium sunt omnis. Expedita necessitatibus et perferendis laborum.', 'pending', 'Rem excepturi officiis nesciunt.', '{\"book_id\": 43, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-22 22:28:34', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(53, 1, 'new_book_available', 'email', 'truong92@yahoo.com', 'Doloribus dolorem aspernatur qui.', 'Aut nobis voluptas aperiam et distinctio et cupiditate. Quia a assumenda dolores quia qui velit.', 'sent', NULL, '{\"book_id\": 46, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-14 23:47:19', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(54, 4, 'reservation_ready', 'sms', 'jhan@hotmail.com', 'Voluptatem saepe voluptatibus.', 'Et totam et qui id in est. Labore nesciunt tenetur repellendus ullam.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 37, \"fine_amount\": 60519.62}', '2025-09-27 08:52:01', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(55, 1, 'new_book_available', 'in_app', 'vu.trung@yahoo.com', 'Perferendis dolorem rerum.', 'Voluptates et omnis voluptatem possimus. Eum assumenda et voluptatum est labore aliquam labore eum. Non fugit quasi nisi perspiciatis sit modi.', 'delivered', NULL, '{\"book_id\": 13, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-30 15:26:37', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(56, 2, 'borrow_reminder', 'in_app', 'ha23@vu.health.vn', 'Molestiae voluptatibus.', 'Dolor est sint iusto ullam in quod. Autem ut quidem omnis a.', 'sent', NULL, '{\"book_id\": 7, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-08 03:06:52', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(57, NULL, 'overdue_notice', 'in_app', 'adinh@gmail.com', 'Aut sed quia porro.', 'Dignissimos ipsum cum aliquid et. Unde impedit totam expedita.', 'sent', NULL, '{\"book_id\": 57, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-13 00:34:32', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(58, 2, 'new_book_available', 'push', 'ly.hoan@chau.com', 'Fugit vel ut.', 'Eaque laboriosam voluptatibus error aut id nesciunt. Est corrupti voluptatum possimus assumenda. Inventore nihil aut ut rerum tenetur.', 'sent', NULL, '{\"book_id\": 63, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-14 11:29:54', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(59, 2, 'overdue_notice', 'in_app', 'vu.thoi@thinh.com', 'Dolore et sed et.', 'Quasi id labore cum voluptates inventore doloribus impedit. Vel praesentium perferendis inventore qui officia ut. Et deserunt explicabo neque.', 'failed', 'Exercitationem voluptate vero non quam.', '{\"book_id\": 34, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-13 14:23:07', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(60, 4, 'reservation_ready', 'email', 'phung54@man.edu.vn', 'Sed voluptas qui.', 'Doloribus asperiores est reiciendis est tempora aut. Neque veritatis ut est quisquam omnis et aliquam.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 45, \"fine_amount\": null}', '2025-09-17 14:03:32', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(61, 3, 'system_maintenance', 'sms', 'olu@yahoo.com', 'Maxime velit iusto quis.', 'Voluptatem eum sequi ipsa sunt. Beatae qui eum totam odit velit voluptatem.', 'pending', 'Neque error exercitationem nobis quis blanditiis.', '{\"book_id\": 51, \"borrow_id\": null, \"fine_amount\": 71767.35}', '2025-07-27 05:49:20', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(62, 2, 'overdue_notice', 'sms', 'mau.dan@bang.net', 'Explicabo molestiae omnis corrupti.', 'Quod adipisci ut veniam. Quam qui fugiat quas qui culpa quidem eius. Sunt sit libero qui atque consectetur.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-12 22:33:46', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(63, 3, 'new_book_available', 'in_app', 'thanh41@luu.net', 'Quis sed ea et accusantium.', 'Nihil similique nam saepe placeat nihil laboriosam exercitationem. Reprehenderit omnis autem nemo asperiores non.', 'failed', NULL, '{\"book_id\": 23, \"borrow_id\": 25, \"fine_amount\": 92020.37}', '2025-09-13 19:29:12', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(64, 4, 'reservation_ready', 'sms', 'ai.cai@trung.com', 'Autem voluptatem provident eos aspernatur.', 'Mollitia est enim illo beatae. Id architecto deleniti perspiciatis libero autem eius dignissimos.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 62672.12}', '2025-08-15 15:56:38', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(65, 1, 'reservation_ready', 'in_app', 'jthinh@gmail.com', 'Molestias fuga error reiciendis.', 'Enim sunt illo quasi dolor. Perspiciatis praesentium et qui et odio voluptas ducimus qui.', 'pending', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-11 16:45:46', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(66, NULL, 'system_maintenance', 'sms', 'kha.do@ta.com.vn', 'Omnis dolorem repellat.', 'Iusto quia repellendus ut. Numquam quo nihil sint ut maxime animi porro. Quis ullam consequatur optio consequatur fugiat atque sint et.', 'delivered', NULL, '{\"book_id\": 16, \"borrow_id\": 144, \"fine_amount\": null}', '2025-07-27 22:15:30', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(67, 4, 'overdue_notice', 'email', 'uong.thao@doan.net.vn', 'Repudiandae et velit.', 'Voluptatem est placeat qui provident reprehenderit occaecati. Maiores expedita et eum neque corrupti. Incidunt velit adipisci blanditiis eum rem.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 130, \"fine_amount\": 50678.67}', '2025-09-28 00:15:38', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(68, 3, 'borrow_reminder', 'email', 'tuyen.au@khuong.com', 'Odit nobis sit.', 'Earum reiciendis consequuntur vitae dolor voluptas optio maxime maxime. Neque consequatur quia illum dignissimos excepturi. Consequatur nam consequatur a saepe omnis.', 'failed', NULL, '{\"book_id\": 35, \"borrow_id\": 18, \"fine_amount\": 78683.14}', '2025-09-30 04:34:57', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(69, 4, 'new_book_available', 'email', 'luat.dang@van.net.vn', 'Voluptatem inventore doloremque omnis.', 'Harum occaecati molestiae quis. Est libero vel mollitia non.', 'failed', NULL, '{\"book_id\": 43, \"borrow_id\": 3, \"fine_amount\": 58044.21}', '2025-09-26 03:28:10', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(70, 2, 'system_maintenance', 'sms', 'pyen@hotmail.com', 'Accusamus sed.', 'Corporis ut sequi quaerat dignissimos. Nihil qui est et ex eum cumque beatae. Voluptatem nam ut reprehenderit labore amet.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 45540.66}', '2025-09-27 03:00:27', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(71, NULL, 'borrow_reminder', 'sms', 'son.han@ha.edu.vn', 'Facilis mollitia illum.', 'Et sapiente qui quis molestiae debitis impedit itaque. Debitis quia et iste consequuntur. Ut sed quia dolor fuga necessitatibus.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-22 07:10:46', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(72, 4, 'system_maintenance', 'push', 'vthap@hotmail.com', 'Nulla optio consequatur et.', 'Nostrum at velit sit quam natus et. Labore iste consequatur autem iusto in eum. Totam praesentium aut voluptate eligendi.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-05 16:23:06', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(73, NULL, 'system_maintenance', 'email', 'kien53@yahoo.com', 'Vero qui magnam enim.', 'Itaque commodi qui earum unde qui modi quo. Quia quia necessitatibus optio quia et.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 94, \"fine_amount\": 92289.31}', '2025-07-31 03:56:43', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(74, NULL, 'fine_notice', 'in_app', 'ky20@hotmail.com', 'Voluptas facilis libero dolores.', 'Porro tempore soluta deleniti. Expedita eum consequatur omnis perferendis voluptatibus debitis error quidem. Dolor ullam amet illo iure sunt deserunt.', 'failed', NULL, '{\"book_id\": 52, \"borrow_id\": 176, \"fine_amount\": 12420.94}', '2025-08-31 21:20:26', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(75, 3, 'overdue_notice', 'email', 'ohua@cam.info.vn', 'Sunt voluptate ducimus.', 'Occaecati omnis cupiditate iste dicta porro aut eligendi. Dolorem odio accusantium aspernatur. Facilis est laborum vel.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-13 14:17:11', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(76, 1, 'new_book_available', 'sms', 'btong@ong.biz.vn', 'Reprehenderit non tempora repellendus.', 'Ad itaque eos voluptatum minus maiores. Neque quo commodi reprehenderit.', 'pending', NULL, '{\"book_id\": 38, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-22 02:16:15', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(77, 1, 'reservation_ready', 'sms', 'tien33@nghiem.com', 'Aut et tenetur quia.', 'Dolor ipsum enim omnis ex recusandae ipsum. Pariatur velit vel et officiis et. Quibusdam veritatis sit vitae qui.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-09 21:59:19', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(78, NULL, 'system_maintenance', 'in_app', 'dong95@bui.ac.vn', 'Porro pariatur odio qui nostrum.', 'Inventore voluptas dolorem in omnis quae. Corporis amet voluptatem quis aliquam dolorem.', 'sent', NULL, '{\"book_id\": 40, \"borrow_id\": 27, \"fine_amount\": null}', '2025-07-27 13:47:19', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(79, 3, 'reservation_ready', 'sms', 'loc69@khoa.com', 'Quia est eos labore.', 'Voluptatem rerum omnis voluptas animi est accusantium. Eligendi dignissimos voluptatibus nihil voluptatibus in voluptas molestiae aut.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 185, \"fine_amount\": null}', '2025-10-02 07:03:06', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(80, 3, 'fine_notice', 'email', 'nhan87@yahoo.com', 'Maxime culpa odit.', 'Quis aut asperiores et quos aliquam vel minima. Incidunt sed officiis libero repudiandae voluptatum aut aliquam.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 5, \"fine_amount\": null}', '2025-07-31 14:06:07', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(81, 3, 'overdue_notice', 'push', 'nhi98@hotmail.com', 'Architecto et natus consequuntur.', 'Itaque autem quidem accusamus fugit veritatis. Ea beatae aperiam assumenda dolores rerum ipsum.', 'delivered', NULL, '{\"book_id\": 17, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-09 19:15:37', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(82, 3, 'borrow_reminder', 'email', 'dang.cuong@can.ac.vn', 'Accusantium velit.', 'Voluptas alias voluptate nihil aut quidem. Expedita quod autem adipisci dolorum quidem voluptates.', 'delivered', NULL, '{\"book_id\": 11, \"borrow_id\": 84, \"fine_amount\": null}', '2025-07-27 12:47:24', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(83, 2, 'system_maintenance', 'push', 'lo.hao@gmail.com', 'Neque quo qui.', 'Et odit sed nihil et laboriosam adipisci animi voluptate. Culpa similique voluptates beatae voluptatem sapiente.', 'delivered', NULL, '{\"book_id\": 41, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-23 12:21:30', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(84, NULL, 'system_maintenance', 'email', 'gia.phuoc@vu.com', 'Alias eum rerum.', 'Est sunt occaecati quis est. Maiores illum quidem numquam velit quae ut dicta.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 12798.94}', '2025-07-31 15:49:35', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(85, NULL, 'borrow_reminder', 'sms', 'ngan.pho@gmail.com', 'Facere nemo dolores officia.', 'Consequatur commodi tempora voluptatibus doloremque sit quia eius. Cum illo quae aut earum exercitationem. Eum facilis expedita ex eum reiciendis nisi et.', 'failed', NULL, '{\"book_id\": 56, \"borrow_id\": 29, \"fine_amount\": 29394.55}', '2025-10-21 03:21:31', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(86, 3, 'system_maintenance', 'push', 'duong.thong@yahoo.com', 'Et optio at.', 'Et animi illo nihil sed error. Nemo quam laborum odio corporis consequuntur molestiae nam. Alias aut rerum quas iste.', 'failed', NULL, '{\"book_id\": 2, \"borrow_id\": 95, \"fine_amount\": null}', '2025-08-24 02:19:12', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(87, 1, 'borrow_reminder', 'email', 'do.lam@banh.org', 'Et expedita perferendis.', 'Repudiandae repellendus fugiat earum et rerum molestiae vel. Harum aperiam dignissimos debitis qui corrupti.', 'sent', NULL, '{\"book_id\": 33, \"borrow_id\": 136, \"fine_amount\": null}', '2025-09-24 21:33:52', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(88, NULL, 'overdue_notice', 'push', 'hton@hotmail.com', 'Aliquam aliquid aliquid.', 'Harum amet quia quo qui ea. Dicta non necessitatibus blanditiis sed minima repellat placeat.', 'sent', NULL, '{\"book_id\": 14, \"borrow_id\": 52, \"fine_amount\": 72073.35}', '2025-10-11 09:42:48', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(89, 2, 'fine_notice', 'in_app', 'zbo@cao.com', 'Dolorem consequatur facilis dignissimos.', 'Facilis eius culpa doloribus ut laudantium sit. Eveniet inventore reiciendis eos magnam maiores maxime autem recusandae. Et quia quaerat excepturi molestias aliquid dolor.', 'delivered', NULL, '{\"book_id\": 21, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-21 02:23:43', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(90, 4, 'fine_notice', 'sms', 'cam74@vuong.biz.vn', 'Soluta tenetur quis.', 'Quam quia aut nisi natus assumenda voluptatibus. Quos veniam reiciendis dolorem debitis reprehenderit suscipit ea. Saepe earum est explicabo laborum sunt.', 'pending', NULL, '{\"book_id\": 23, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-26 07:45:37', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(91, 4, 'fine_notice', 'email', 'phan.hy@yahoo.com', 'Iusto aut consequuntur et.', 'Sit doloribus sunt repellat et et laborum sit. Modi illo quia et non.', 'failed', NULL, '{\"book_id\": 13, \"borrow_id\": null, \"fine_amount\": 11320.3}', '2025-08-18 03:52:36', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(92, 4, 'reservation_ready', 'sms', 'jha@ho.net', 'Molestiae beatae quo.', 'Velit in dolores id. Et et iusto iste et ut dolore dolorum. Et aperiam possimus veritatis minus tempore aut similique.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 124, \"fine_amount\": 62476.2}', '2025-09-26 00:26:13', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(93, 2, 'system_maintenance', 'sms', 'du.yen@kha.org', 'Voluptatem ad et.', 'Quasi nam nulla nam et a sunt est. Temporibus pariatur sit nam nesciunt id. Ea vel corporis mollitia nesciunt qui.', 'failed', NULL, '{\"book_id\": 15, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-06 05:18:02', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(94, 1, 'new_book_available', 'email', 'dan.ca@lu.biz', 'Perferendis quo facere eaque.', 'Id consequuntur voluptatem quasi tenetur repellendus vel. Id animi voluptatem esse modi qui ducimus sint.', 'failed', NULL, '{\"book_id\": 9, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-30 03:19:57', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(95, NULL, 'reservation_ready', 'push', 'nu43@doan.info', 'Deleniti vero beatae.', 'Repellendus voluptates ex sit voluptate nihil. Doloribus asperiores odit harum recusandae. Id odit temporibus blanditiis aut beatae.', 'failed', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": 71177.62}', '2025-10-08 10:38:45', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(96, NULL, 'system_maintenance', 'push', 'zquan@doi.info', 'Voluptatibus deleniti.', 'Molestiae veritatis officiis veniam non et dolor velit accusantium. Nesciunt non qui pariatur aut vero.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 148, \"fine_amount\": null}', '2025-09-01 20:27:17', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(97, 2, 'system_maintenance', 'push', 'fdai@vuong.vn', 'Quia velit dignissimos id.', 'Temporibus voluptatem laudantium voluptatem. Aliquam nobis voluptate assumenda aperiam. Quidem adipisci quia error dolores dignissimos iusto asperiores.', 'pending', NULL, '{\"book_id\": 45, \"borrow_id\": null, \"fine_amount\": 51894.69}', '2025-10-17 13:11:35', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(98, NULL, 'new_book_available', 'push', 'lam.linh@doi.gov.vn', 'Ipsam ut accusantium aut iste.', 'Qui ipsam rerum et dolores deserunt exercitationem. Ut sed enim esse quis. Voluptatem aspernatur minima necessitatibus.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 60, \"fine_amount\": 70712.3}', '2025-08-11 14:25:29', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(99, 1, 'fine_notice', 'push', 'lai.thi@cu.org.vn', 'Suscipit quisquam ab.', 'Est autem nihil vero aliquam. Facere nesciunt omnis iure sit molestiae sed ratione aliquam.', 'pending', NULL, '{\"book_id\": 43, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-02 20:02:38', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(100, 3, 'fine_notice', 'sms', 'wdan@gmail.com', 'Pariatur sint molestiae.', 'Consequatur consequatur quam enim ullam recusandae aspernatur repellat. Omnis rerum cum eaque sit nam occaecati fugit non.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 41311.78}', '2025-09-29 16:02:24', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(101, 4, 'borrow_reminder', 'email', 'vnguy@gmail.com', 'Nobis aliquid sed.', 'Quae quod velit minus saepe itaque similique sapiente. Illo non voluptate quo quae.', 'pending', 'Nostrum qui dicta dolorem blanditiis et accusamus.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 62008.3}', '2025-09-03 12:41:40', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(102, 1, 'borrow_reminder', 'sms', 'quang.doan@gmail.com', 'Quia non quia.', 'Est ab dolorem corporis impedit omnis et et. Consequatur ullam asperiores in molestiae tempora. Repellendus soluta sit voluptas dolorum deserunt tempora dolorem labore.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 133, \"fine_amount\": null}', '2025-10-11 23:49:13', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(103, NULL, 'system_maintenance', 'in_app', 'tu08@ly.org', 'Iure aut vero sint.', 'Qui dignissimos dolorem et quia. Qui aliquid reprehenderit sed.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 86752.96}', '2025-08-08 14:25:56', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(104, 3, 'reservation_ready', 'push', 'wton@bui.com', 'Cupiditate dolores culpa molestiae.', 'Odit laboriosam aperiam in molestiae accusamus ullam. Mollitia consequatur ut nulla.', 'failed', NULL, '{\"book_id\": 26, \"borrow_id\": 65, \"fine_amount\": null}', '2025-09-07 12:03:36', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(105, NULL, 'new_book_available', 'in_app', 'wlai@gmail.com', 'Eius culpa.', 'Autem non occaecati iste qui. Sed quod voluptatem consequatur ex dolor delectus. Ipsa exercitationem et mollitia et officiis.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 164, \"fine_amount\": 15751.76}', '2025-08-23 14:52:21', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(106, 1, 'reservation_ready', 'push', 'pnguy@hotmail.com', 'Voluptate et quos ut.', 'Tempore sunt illum nemo mollitia totam tenetur iste. Laborum accusamus architecto quia voluptatum nemo.', 'sent', NULL, '{\"book_id\": 52, \"borrow_id\": null, \"fine_amount\": 48431.92}', '2025-08-03 20:03:48', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(107, 2, 'new_book_available', 'in_app', 'thien.dam@yahoo.com', 'Et corrupti facilis.', 'Occaecati nulla consequuntur velit aspernatur nesciunt magnam est. Ut ipsa alias voluptatem rerum totam soluta cumque id.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-27 18:01:02', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(108, 2, 'system_maintenance', 'sms', 'lo.trach@gmail.com', 'Nihil architecto et beatae.', 'Doloribus sint explicabo ad. Esse occaecati velit sunt veniam suscipit dolore.', 'sent', NULL, '{\"book_id\": 52, \"borrow_id\": 57, \"fine_amount\": 45580.66}', '2025-10-19 19:30:21', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(109, 1, 'fine_notice', 'push', 'wlo@hotmail.com', 'Eum reprehenderit rerum qui.', 'Fugit aut eveniet et cupiditate molestias voluptatum optio. Ea voluptates nulla nihil ut animi dolores doloribus.', 'failed', NULL, '{\"book_id\": 17, \"borrow_id\": 28, \"fine_amount\": null}', '2025-09-17 03:43:25', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(110, NULL, 'overdue_notice', 'email', 'ltiep@huynh.com', 'Atque totam alias.', 'Harum eum maxime illo voluptatem sapiente repellendus. Maxime cumque optio molestiae. Dolor incidunt et perspiciatis dolorem.', 'delivered', NULL, '{\"book_id\": 28, \"borrow_id\": 73, \"fine_amount\": 36429.94}', '2025-09-26 08:47:08', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(111, 1, 'borrow_reminder', 'sms', 'onghiem@gmail.com', 'Et temporibus nostrum.', 'Soluta et inventore quibusdam sit eum numquam aliquam voluptatibus. Odit nemo incidunt laboriosam cumque maxime nisi.', 'delivered', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-31 20:23:16', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(112, NULL, 'overdue_notice', 'in_app', 'phi.tram@yahoo.com', 'Ducimus rerum quam velit.', 'Sit excepturi repudiandae repudiandae nobis vel asperiores. Quia quas aut quisquam.', 'pending', 'Ratione quam est velit eaque necessitatibus eveniet veniam.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-23 08:47:14', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(113, NULL, 'borrow_reminder', 'email', 'ma.vu@to.info.vn', 'Architecto eum nesciunt.', 'Saepe necessitatibus quia cupiditate id sapiente quaerat vel pariatur. Sit minus ut neque itaque itaque minus non amet.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-19 10:41:06', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(114, 1, 'new_book_available', 'email', 'btrieu@hotmail.com', 'Molestiae consequuntur repellat sit perspiciatis.', 'Voluptas quia et porro expedita libero optio et. Qui ea deleniti et.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 45, \"fine_amount\": null}', '2025-08-28 14:48:14', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(115, NULL, 'overdue_notice', 'in_app', 'thuc40@gmail.com', 'At enim quasi illum.', 'Nobis dolorem distinctio assumenda asperiores possimus natus. Nobis itaque sed pariatur ratione.', 'delivered', NULL, '{\"book_id\": 43, \"borrow_id\": 145, \"fine_amount\": 86218.68}', '2025-10-05 07:52:36', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(116, 1, 'reservation_ready', 'email', 'an55@yahoo.com', 'Nobis doloremque quia dolor.', 'Et iste iure molestiae fugiat voluptas a. Odit illum labore vitae nisi rerum. Sapiente eum culpa quia ullam natus occaecati inventore similique.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 97798.96}', '2025-10-16 08:34:05', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(117, 3, 'overdue_notice', 'sms', 'toan32@gmail.com', 'Facere rerum dolor explicabo.', 'Pariatur laborum asperiores qui incidunt assumenda dolore porro. Consectetur maxime sequi vel.', 'sent', NULL, '{\"book_id\": 31, \"borrow_id\": 92, \"fine_amount\": null}', '2025-08-23 10:25:07', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(118, 4, 'fine_notice', 'in_app', 'ninh04@hotmail.com', 'Repellat ipsam exercitationem ea.', 'Placeat est error et iusto sapiente occaecati ut. Voluptas sapiente deserunt totam amet. Omnis et ut nisi atque.', 'failed', NULL, '{\"book_id\": 8, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-19 23:37:50', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(119, 1, 'reservation_ready', 'in_app', 'chieu93@trung.info.vn', 'Tempore commodi sit rerum.', 'Quibusdam eligendi ducimus molestiae et voluptatibus voluptas et. Quibusdam modi ut illo fugiat vero aut cupiditate. Ipsa sit impedit commodi alias pariatur consequatur.', 'sent', 'Nisi cupiditate corrupti sint sit dolores.', '{\"book_id\": null, \"borrow_id\": 123, \"fine_amount\": 64383.11}', '2025-09-21 04:07:38', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(120, 4, 'borrow_reminder', 'in_app', 'toan.dau@hotmail.com', 'Doloremque corrupti at.', 'Aspernatur quisquam mollitia hic vero sint aliquid. Rem qui iure non consequatur saepe. Magni et veritatis iusto et recusandae pariatur.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-30 19:51:53', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(121, NULL, 'system_maintenance', 'sms', 'sta@yahoo.com', 'Consequatur ex sit soluta dignissimos.', 'Sunt maxime minus omnis repellendus. Alias sit dolores quis est pariatur nobis.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-09 14:45:54', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(122, 3, 'system_maintenance', 'in_app', 'ca.dien@yahoo.com', 'Facere aperiam optio.', 'Est ad et illo. Velit consectetur est ab vel.', 'delivered', 'Omnis provident dignissimos ipsa nulla.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 76101.14}', '2025-09-05 20:04:09', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(123, 2, 'fine_notice', 'in_app', 'nuong.dong@yahoo.com', 'Quos voluptatem deleniti voluptatem.', 'Non totam id voluptate recusandae incidunt pariatur autem asperiores. Vel aut aut asperiores debitis voluptatem assumenda quasi ut. Odit quo non officia.', 'failed', 'Aut dolores rerum minima non aliquam.', '{\"book_id\": 5, \"borrow_id\": 62, \"fine_amount\": null}', '2025-09-29 03:48:18', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(124, 2, 'borrow_reminder', 'email', 'ong.mai@hotmail.com', 'Consequatur cumque sapiente unde.', 'Consectetur saepe officia dolores nobis assumenda eos. Qui ipsum deleniti aut eveniet. Nam consequuntur cum est rerum dolorem accusantium illum.', 'pending', NULL, '{\"book_id\": 17, \"borrow_id\": 203, \"fine_amount\": 70181.18}', '2025-09-13 16:57:37', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(125, 1, 'fine_notice', 'in_app', 'hao.chung@chuong.com', 'Et ut quos est.', 'In quasi dolorem et porro autem quam. Dicta ullam quo soluta occaecati.', 'failed', NULL, '{\"book_id\": 6, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-23 21:16:27', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(126, NULL, 'reservation_ready', 'push', 'yen03@gmail.com', 'Cupiditate omnis facere quam.', 'Provident at sapiente cumque illum sunt eaque omnis. Consequatur sequi reprehenderit non quasi. Ipsum blanditiis reprehenderit nobis provident quia eaque.', 'delivered', NULL, '{\"book_id\": 11, \"borrow_id\": 6, \"fine_amount\": 54742.02}', '2025-10-08 08:54:37', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(127, 4, 'borrow_reminder', 'sms', 'kkim@tao.vn', 'Exercitationem vel totam non ea.', 'Aliquid inventore velit consequatur ratione magni. Quae tempore corrupti voluptas fugit. Eligendi recusandae nobis placeat et sapiente.', 'sent', NULL, '{\"book_id\": 39, \"borrow_id\": 165, \"fine_amount\": null}', '2025-10-13 03:37:30', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(128, NULL, 'system_maintenance', 'email', 'duong.su@ton.vn', 'Delectus eos animi temporibus.', 'Unde tempore id quidem eaque tempora dolorem distinctio. Iste eaque est eos rerum ratione facilis veniam.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-26 07:03:40', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(129, 3, 'overdue_notice', 'in_app', 'hang.dieu@nguyen.ac.vn', 'Dolor commodi deserunt.', 'Voluptas aut porro ullam est. Dolore voluptates a est enim. Sint veritatis aperiam hic voluptatibus.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-02 08:14:14', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(130, 2, 'reservation_ready', 'sms', 'uha@yahoo.com', 'Nostrum dignissimos et rerum.', 'Adipisci et sapiente fuga quibusdam assumenda et numquam. Ex sit sint reprehenderit.', 'pending', NULL, '{\"book_id\": 6, \"borrow_id\": 102, \"fine_amount\": 46759.44}', '2025-08-18 23:40:09', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(131, 3, 'borrow_reminder', 'email', 'dbi@hotmail.com', 'Eum corrupti.', 'Sed dolor nam ex aut quas eos consequatur. Optio unde vel qui veritatis sunt possimus. Totam est eveniet temporibus non.', 'delivered', NULL, '{\"book_id\": 11, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-03 12:29:02', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(132, 2, 'new_book_available', 'email', 'tu.chung@yahoo.com', 'Aut et voluptas ea.', 'Sed neque qui voluptatem temporibus. Dolorem vel et perferendis quod omnis.', 'failed', 'Asperiores repellat sint possimus.', '{\"book_id\": 13, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-09 08:20:35', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(133, NULL, 'overdue_notice', 'in_app', 'tu.thanh@gmail.com', 'Voluptatem quo ab qui cumque.', 'Quaerat doloremque alias velit voluptatem consectetur placeat sed. Quia ut sit laudantium tempore iste corporis eveniet.', 'sent', NULL, '{\"book_id\": 56, \"borrow_id\": 6, \"fine_amount\": null}', '2025-09-22 15:58:23', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(134, 4, 'system_maintenance', 'in_app', 'khuyen03@leu.ac.vn', 'Accusantium est ipsam fugiat.', 'Voluptatem mollitia occaecati sed possimus enim. Sed dolorem iusto voluptatem quasi. Possimus est saepe veritatis reiciendis minima alias eaque expedita.', 'failed', 'Consequatur itaque est et enim vitae voluptatem.', '{\"book_id\": 50, \"borrow_id\": null, \"fine_amount\": 77688.19}', '2025-09-28 07:45:45', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(135, 1, 'fine_notice', 'in_app', 'othach@vuong.info', 'Corporis aut minima est.', 'Sit natus veniam maxime expedita harum praesentium dolores qui. Corrupti voluptas quod nam nesciunt quia.', 'sent', NULL, '{\"book_id\": 37, \"borrow_id\": null, \"fine_amount\": 34371.1}', '2025-10-18 15:33:31', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(136, 4, 'overdue_notice', 'email', 'khanh.huynh@tra.vn', 'Autem incidunt voluptates vitae.', 'Id ex rerum aut non. Ducimus beatae assumenda aut ut labore rem nam.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 150, \"fine_amount\": 56801.14}', '2025-08-11 05:43:08', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(137, 3, 'reservation_ready', 'sms', 'lieu.vuong@quan.org', 'Assumenda aut unde ea aut.', 'Voluptatem possimus quidem doloremque debitis qui alias sit voluptatibus. Voluptatem laborum nostrum et dolores.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 42, \"fine_amount\": null}', '2025-10-02 23:37:25', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(138, 2, 'reservation_ready', 'push', 'zcan@gmail.com', 'Placeat dolorum aut asperiores.', 'Id non et est sint doloremque sint rerum. Commodi odit laudantium soluta voluptatem deserunt quia.', 'sent', NULL, '{\"book_id\": 35, \"borrow_id\": 61, \"fine_amount\": null}', '2025-08-30 13:43:55', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(139, NULL, 'overdue_notice', 'email', 'yen.bang@hotmail.com', 'Dolores eaque totam.', 'Voluptas qui corrupti sunt est et nesciunt iste at. Omnis non deleniti nostrum et harum.', 'sent', NULL, '{\"book_id\": 23, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-22 22:56:23', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(140, 1, 'overdue_notice', 'email', 'xuan.hua@hotmail.com', 'Explicabo dolores aspernatur.', 'Ipsum molestiae repudiandae voluptatibus necessitatibus neque dolorem neque. In quo et quis est facere sed ad aut.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-04 07:32:06', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(141, 3, 'system_maintenance', 'push', 'vong.mai@ung.com', 'Et at voluptas.', 'Saepe ex unde cupiditate voluptatem consequatur qui. Tenetur quia quo recusandae tempora. Similique aut ad accusamus.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-11 21:54:14', '2025-10-26 06:13:54', '2025-10-26 06:13:54');
INSERT INTO `notification_logs` (`id`, `template_id`, `type`, `channel`, `recipient`, `subject`, `content`, `status`, `error_message`, `metadata`, `sent_at`, `created_at`, `updated_at`) VALUES
(142, 3, 'new_book_available', 'in_app', 'truong.tru@gmail.com', 'Temporibus enim voluptatum eaque.', 'Accusantium qui in ut commodi aut alias dolores. Cum quis asperiores asperiores nam et at quam. Consequatur aut velit quasi molestiae.', 'failed', NULL, '{\"book_id\": 28, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-10 21:49:05', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(143, 2, 'overdue_notice', 'push', 'han.vi@yahoo.com', 'Laudantium voluptatem praesentium est et.', 'Error voluptates et debitis corporis praesentium libero. Dolorum expedita dolorem eaque corporis sed omnis sint velit. Dolores commodi id non sunt aliquam vel.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 7, \"fine_amount\": null}', '2025-10-20 21:59:37', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(144, 2, 'overdue_notice', 'sms', 'hanh93@mac.org', 'Mollitia rerum consequatur.', 'Eaque reprehenderit est voluptatem facilis in. Ipsam odio est minus sit. Ipsum voluptatum a consequatur blanditiis enim consequatur facere.', 'pending', NULL, '{\"book_id\": 35, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-02 16:12:05', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(145, 3, 'new_book_available', 'email', 'tran.dieu@uong.com', 'Ea omnis quisquam.', 'Dolores iste quia in officia omnis accusantium. Quae voluptates neque modi deleniti. Necessitatibus aliquid qui rerum ut ipsa ullam.', 'sent', 'Reprehenderit laudantium dolorum sed voluptatem.', '{\"book_id\": 61, \"borrow_id\": 133, \"fine_amount\": 79607.04}', '2025-09-10 11:33:51', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(146, 2, 'system_maintenance', 'in_app', 'dien.thanh@hotmail.com', 'Molestias omnis sit dolore.', 'A ab tempora id itaque voluptatem nobis officiis. Eos culpa at vel quia at voluptatibus.', 'failed', NULL, '{\"book_id\": 32, \"borrow_id\": 109, \"fine_amount\": null}', '2025-09-04 03:47:06', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(147, 2, 'system_maintenance', 'sms', 'nha10@yahoo.com', 'Temporibus veniam et veniam.', 'Laborum aut praesentium et dolorem. Et repudiandae nihil at omnis pariatur tempora. Velit id dolor expedita esse.', 'delivered', NULL, '{\"book_id\": 19, \"borrow_id\": 32, \"fine_amount\": null}', '2025-09-04 23:01:03', '2025-10-26 06:13:54', '2025-10-26 06:13:54'),
(148, 3, 'borrow_reminder', 'sms', 'wla@yahoo.com', 'Amet quaerat.', 'Expedita reiciendis accusantium suscipit natus deserunt consequatur. Facilis dicta et fugit est repellat.', 'failed', NULL, '{\"book_id\": 48, \"borrow_id\": 137, \"fine_amount\": null}', '2025-09-03 23:58:24', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(149, 2, 'borrow_reminder', 'push', 'hung.hop@ba.com.vn', 'Aut sed sit magnam.', 'Eum ex cumque expedita placeat pariatur. Nulla omnis consequuntur voluptas aut beatae sint aspernatur. Reprehenderit vel distinctio numquam saepe rem velit.', 'pending', NULL, '{\"book_id\": 42, \"borrow_id\": 50, \"fine_amount\": 83239.91}', '2025-08-24 16:18:56', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(150, 4, 'new_book_available', 'in_app', 'svo@yahoo.com', 'Molestiae minima asperiores.', 'Corporis quo consequatur magni. Nihil et nihil quidem vitae. Quia non qui sed.', 'pending', NULL, '{\"book_id\": 2, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-18 05:47:08', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(151, NULL, 'new_book_available', 'sms', 'tram16@chung.biz.vn', 'Vitae ut nihil sunt.', 'Molestias explicabo commodi consequuntur. Perspiciatis rerum expedita voluptas iusto labore adipisci eius.', 'sent', NULL, '{\"book_id\": 28, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-07 08:47:53', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(152, 2, 'borrow_reminder', 'in_app', 'dan63@hotmail.com', 'Maiores vero incidunt.', 'Esse molestiae sit veritatis dolorem. Voluptatum doloremque distinctio commodi qui esse dicta.', 'failed', NULL, '{\"book_id\": 24, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-19 05:22:17', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(153, 4, 'system_maintenance', 'in_app', 'sinh.dong@yahoo.com', 'Beatae saepe deserunt dolor.', 'Qui necessitatibus molestiae error amet non autem. Iste qui quod a provident optio ducimus ut. Laudantium nihil qui ab nulla perferendis minus.', 'failed', NULL, '{\"book_id\": 17, \"borrow_id\": 191, \"fine_amount\": 13253.38}', '2025-09-25 15:50:28', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(154, 2, 'new_book_available', 'email', 'nuong.doan@han.biz', 'Sapiente laborum molestias.', 'Quia esse ullam minus repellendus. Voluptas quis eaque eos officiis et beatae occaecati.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-13 23:42:59', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(155, 1, 'reservation_ready', 'email', 'tvi@yahoo.com', 'Quisquam ut optio voluptatem.', 'Qui rerum delectus perferendis alias ad. Deserunt modi qui veritatis vel. Reiciendis labore doloremque laboriosam vel nobis.', 'delivered', NULL, '{\"book_id\": 6, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-11 10:58:36', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(156, 1, 'new_book_available', 'push', 'khong.ai@yahoo.com', 'Maiores nostrum laborum similique.', 'Ipsum nisi assumenda voluptatem eos quo non. Voluptas officiis et consequatur et doloremque omnis quae.', 'pending', NULL, '{\"book_id\": 53, \"borrow_id\": 24, \"fine_amount\": null}', '2025-09-20 08:21:59', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(157, 3, 'fine_notice', 'email', 'khau.bich@yahoo.com', 'Officia omnis.', 'Dolor fugiat perspiciatis nesciunt neque labore expedita. Explicabo eligendi deserunt dolorum sit sunt. Sint et porro ab neque aperiam.', 'sent', 'Repellat totam in doloribus necessitatibus.', '{\"book_id\": 7, \"borrow_id\": null, \"fine_amount\": 25023.32}', '2025-08-09 16:31:18', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(158, NULL, 'borrow_reminder', 'sms', 'ukhuu@gmail.com', 'Ducimus assumenda minima fuga.', 'Fugiat nam officia odit. Laborum ut velit velit sed dicta blanditiis iure. Sunt molestias ipsum aspernatur maiores qui molestias.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 95, \"fine_amount\": null}', '2025-08-25 21:15:44', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(159, NULL, 'borrow_reminder', 'in_app', 'truc22@yahoo.com', 'Cupiditate iusto et autem.', 'Modi incidunt dolor ipsa. Et quos possimus minima sint deleniti. Veritatis quos est aut dignissimos molestias modi.', 'pending', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-16 02:52:28', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(160, 2, 'system_maintenance', 'email', 'wdoi@yahoo.com', 'Earum cupiditate debitis animi.', 'Voluptatibus cupiditate qui soluta ea eius sit. Ex praesentium reiciendis magni laudantium ab. Aut dolorum non pariatur sint accusantium.', 'failed', NULL, '{\"book_id\": 46, \"borrow_id\": 154, \"fine_amount\": 32227.68}', '2025-10-13 20:48:07', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(161, NULL, 'overdue_notice', 'push', 'tran.vu@hoa.com', 'Est ab omnis autem.', 'Magni non omnis nulla necessitatibus natus sunt. Aut in sequi porro voluptatem perferendis velit. Atque pariatur ut recusandae non officiis sint praesentium.', 'delivered', NULL, '{\"book_id\": 62, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-10 19:56:20', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(162, 2, 'reservation_ready', 'push', 'chinh.thai@gmail.com', 'Et quaerat non.', 'Totam libero illo velit ipsa nostrum. Odio esse corporis magnam quisquam laboriosam. Dolor modi illum voluptatem ex odio sequi beatae.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 126, \"fine_amount\": null}', '2025-08-25 08:54:38', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(163, NULL, 'borrow_reminder', 'email', 'ebach@hotmail.com', 'Deserunt ducimus est eum.', 'Laboriosam quas deserunt ut tempora. Dolorem vel hic amet. Velit aut repellat officiis reprehenderit voluptate dignissimos ut.', 'delivered', 'Vel ratione voluptatem veritatis recusandae accusantium sunt nemo.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-19 16:00:13', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(164, NULL, 'fine_notice', 'email', 'don.van@yahoo.com', 'Sunt voluptas.', 'Quos inventore dolor eos ab dicta magnam. Maxime iure aspernatur maxime velit dolor minus. Enim corrupti eius id itaque consequatur quia quia non.', 'pending', NULL, '{\"book_id\": 48, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-04 22:25:53', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(165, 1, 'new_book_available', 'push', 'trinh.dinh@bach.health.vn', 'Quia sunt minima.', 'Voluptatibus accusantium eveniet alias doloremque consequatur amet. Minima dolores voluptate et rerum id.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-24 15:32:42', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(166, 3, 'system_maintenance', 'sms', 'phua@truong.edu.vn', 'Dolores nobis omnis.', 'Aut qui odio fugit quo. Omnis ipsa eum quisquam harum molestiae distinctio consequatur. Eos porro animi omnis sed officiis fugit officiis.', 'pending', NULL, '{\"book_id\": 51, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-03 01:15:39', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(167, 1, 'reservation_ready', 'push', 'tang.khoat@trinh.biz.vn', 'Delectus optio perferendis.', 'Temporibus adipisci sequi laboriosam non a omnis. Aliquid est ducimus eaque ex cumque ullam. Pariatur ut modi et sed nostrum.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-23 12:21:41', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(168, 1, 'fine_notice', 'in_app', 'cuc88@tran.health.vn', 'Omnis veritatis non autem.', 'Natus iusto occaecati voluptatem nihil. Possimus quasi quia non consequatur quis fuga.', 'failed', NULL, '{\"book_id\": 15, \"borrow_id\": 93, \"fine_amount\": null}', '2025-09-04 15:10:23', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(169, 2, 'new_book_available', 'in_app', 'quyen.diep@nong.net.vn', 'Impedit corrupti modi.', 'Illo et inventore neque. Quod molestiae qui voluptas maiores eaque. Fuga voluptas pariatur reiciendis.', 'delivered', 'Aut quo ad fugit voluptas sint adipisci et cumque.', '{\"book_id\": 4, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-03 03:57:01', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(170, 1, 'reservation_ready', 'in_app', 'an.hai@doan.mil.vn', 'Nisi eos quisquam fuga.', 'Sunt qui debitis mollitia occaecati. Eius non deserunt eum.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-10 00:40:46', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(171, NULL, 'reservation_ready', 'sms', 'ngo.hoa@le.mil.vn', 'Inventore qui molestiae.', 'Quia voluptatem soluta quo nihil labore inventore. Est quas inventore minima dolore.', 'failed', NULL, '{\"book_id\": 19, \"borrow_id\": null, \"fine_amount\": 26231.18}', '2025-09-11 20:12:58', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(172, 4, 'reservation_ready', 'email', 'banh.nghia@hotmail.com', 'Quos exercitationem quia.', 'Aut et est sunt ipsam officia. Sit veritatis sit excepturi iste. Sint iusto odit distinctio aliquid voluptatem molestiae.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-01 05:24:12', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(173, 4, 'borrow_reminder', 'sms', 'ngan58@mai.int.vn', 'Id possimus vel sit modi.', 'Id enim quis iste impedit. Est soluta dolores sed cumque rem.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 45610.93}', '2025-09-08 09:38:33', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(174, NULL, 'fine_notice', 'in_app', 'hien.leu@gmail.com', 'Omnis architecto laudantium vero.', 'Velit dicta hic libero iure impedit ut aut sit. Enim aut ea blanditiis voluptatem.', 'sent', NULL, '{\"book_id\": 37, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-14 00:57:25', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(175, 4, 'borrow_reminder', 'sms', 'phuc.an@gmail.com', 'Dignissimos illum repudiandae.', 'Consequatur voluptatem voluptates quis nihil. Earum laborum omnis illum. Officia cupiditate numquam quis incidunt.', 'delivered', NULL, '{\"book_id\": 11, \"borrow_id\": 13, \"fine_amount\": null}', '2025-08-11 15:46:04', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(176, 3, 'overdue_notice', 'in_app', 'trieu.an@quach.ac.vn', 'Accusamus ducimus ut.', 'Dolorum voluptatem perspiciatis quia aut magni beatae. Beatae vitae molestias dolores ut odio explicabo nisi. Non iusto nemo ea esse repellat itaque quasi recusandae.', 'delivered', NULL, '{\"book_id\": 7, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-08 21:48:41', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(177, 3, 'system_maintenance', 'sms', 'elo@le.info', 'Aut dolor voluptatum qui officiis.', 'Ut est nesciunt exercitationem molestiae possimus quas. Laboriosam ducimus inventore at sit voluptatem.', 'failed', 'Non quas magni qui dicta quia delectus possimus.', '{\"book_id\": 9, \"borrow_id\": 117, \"fine_amount\": 87948.2}', '2025-07-31 10:59:52', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(178, NULL, 'new_book_available', 'sms', 'lai.hop@cao.vn', 'A velit aut.', 'Voluptas deleniti consequatur qui. Voluptatibus necessitatibus quia unde ducimus et et.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 61381.08}', '2025-10-12 23:47:50', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(179, 4, 'borrow_reminder', 'sms', 'giang.ly@hotmail.com', 'Ratione temporibus praesentium consectetur.', 'Iure odit illo officia iste. Aut quidem facere amet earum et debitis.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-07 21:43:21', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(180, 4, 'system_maintenance', 'in_app', 'nga.ty@ly.vn', 'Ab est iste aut consectetur.', 'Ut illo sint ipsam consectetur quis quia impedit at. Dolor a velit atque sed. Provident repudiandae est amet esse nihil occaecati nulla.', 'pending', NULL, '{\"book_id\": 63, \"borrow_id\": null, \"fine_amount\": 18589.73}', '2025-10-05 00:57:34', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(181, NULL, 'reservation_ready', 'in_app', 'ung.hoai@yahoo.com', 'Vel ut deleniti.', 'Qui voluptatem et tenetur unde. Veniam iusto corporis rerum qui. Eius consequatur possimus tenetur voluptas et.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 39421.81}', '2025-08-30 07:48:05', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(182, 4, 'reservation_ready', 'email', 'hao.dong@hotmail.com', 'Suscipit consequatur hic.', 'In rerum excepturi hic rem fuga maxime. Autem hic est nisi consequatur rem ipsum. Reiciendis eos dolorem possimus aut illo consectetur consequatur.', 'pending', 'Harum laborum corrupti odit non rem non aut.', '{\"book_id\": 61, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-03 10:24:42', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(183, 1, 'system_maintenance', 'sms', 'khuu.nhuong@hotmail.com', 'Nisi fugiat libero.', 'Ut recusandae labore assumenda libero voluptatem quo. Unde vero ad aliquid rerum velit.', 'failed', NULL, '{\"book_id\": 58, \"borrow_id\": null, \"fine_amount\": 43680.15}', '2025-10-13 12:05:43', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(184, 4, 'overdue_notice', 'in_app', 'mac.thuan@hotmail.com', 'Ut id sit.', 'Amet cumque harum amet voluptas rerum ut. Sit et sunt quae sunt eum aliquam. Repellendus illo inventore sit quo.', 'failed', NULL, '{\"book_id\": 22, \"borrow_id\": 151, \"fine_amount\": null}', '2025-10-13 10:25:38', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(185, 3, 'fine_notice', 'email', 'trinh.dai@hinh.com', 'Tempora eum nam dignissimos.', 'A magnam et quibusdam quis. Aut magni officia aut vel et. Provident ducimus omnis temporibus.', 'delivered', NULL, '{\"book_id\": 19, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-07 13:10:38', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(186, 2, 'overdue_notice', 'sms', 'khuyen.dinh@yahoo.com', 'Voluptatem et hic.', 'Impedit quod est assumenda vitae ullam voluptates id. Sint harum ut aut sit non. Modi ipsam placeat aut.', 'failed', 'Possimus ut porro sed qui est sed.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-20 09:38:24', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(187, 3, 'system_maintenance', 'push', 'huynh.thieu@gmail.com', 'Quos voluptatem et.', 'Distinctio vel quas saepe adipisci mollitia blanditiis. Est deserunt est quia et provident aut. Est quasi ipsa quis voluptatem perferendis earum possimus.', 'delivered', NULL, '{\"book_id\": 47, \"borrow_id\": 38, \"fine_amount\": null}', '2025-08-24 03:33:57', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(188, NULL, 'reservation_ready', 'sms', 'nga.leu@hotmail.com', 'Labore qui modi.', 'Voluptatem autem dolorem quia consequuntur omnis. Rerum laborum ut quas vitae eos necessitatibus. Consequatur vero et hic mollitia eos soluta esse.', 'delivered', NULL, '{\"book_id\": 51, \"borrow_id\": null, \"fine_amount\": 48827.16}', '2025-10-15 01:01:44', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(189, NULL, 'overdue_notice', 'in_app', 'nghiem23@cam.gov.vn', 'Culpa necessitatibus qui veritatis.', 'Omnis laborum officiis qui modi eos. Et earum dolor iusto porro ut facilis voluptates.', 'pending', NULL, '{\"book_id\": 63, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-08 00:48:32', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(190, 3, 'fine_notice', 'push', 'pdao@mang.org', 'Odio et similique facilis.', 'Asperiores possimus qui et neque neque impedit dolorem. Blanditiis accusantium quis quis ipsam. Placeat aliquam quae ex provident iusto.', 'delivered', NULL, '{\"book_id\": 40, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-03 13:00:14', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(191, NULL, 'reservation_ready', 'push', 'le04@gmail.com', 'Cumque sint minus.', 'Optio at sint eos qui placeat non quis. Doloribus libero eligendi temporibus sit amet. Sunt dignissimos nisi placeat.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-05 14:58:57', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(192, NULL, 'reservation_ready', 'sms', 'mdai@yahoo.com', 'Maiores velit possimus voluptate.', 'Et dicta et et temporibus. Et aliquid dicta pariatur et vero.', 'pending', NULL, '{\"book_id\": 48, \"borrow_id\": null, \"fine_amount\": 38985.92}', '2025-09-14 16:25:39', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(193, 1, 'borrow_reminder', 'email', 'bui.hoan@gmail.com', 'Quos possimus.', 'Neque eveniet voluptas vel natus. Qui error suscipit id illum mollitia.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-07 03:34:38', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(194, 2, 'fine_notice', 'email', 'luat62@bac.com', 'Ullam soluta eum.', 'Mollitia quae eius a non odit eum qui. Quasi numquam et voluptatem sit. Perferendis nam et pariatur qui exercitationem consequatur.', 'failed', NULL, '{\"book_id\": 16, \"borrow_id\": null, \"fine_amount\": 60535.63}', '2025-10-09 15:14:05', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(195, 4, 'overdue_notice', 'push', 'hoang.bach@gmail.com', 'Laborum odio dolor.', 'Consequuntur quidem soluta excepturi voluptatibus voluptatum omnis veritatis voluptatibus. Et beatae qui nulla delectus consequatur inventore.', 'sent', NULL, '{\"book_id\": 14, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-23 17:35:02', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(196, NULL, 'new_book_available', 'in_app', 'hanh33@dam.com', 'Quasi ipsam adipisci.', 'Est voluptas assumenda atque mollitia. Quia consequatur qui aliquam. Perferendis dolorum ad itaque quam voluptas.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-11 13:18:05', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(197, 3, 'fine_notice', 'sms', 'hoa.quan@chu.com', 'Iste in quia amet.', 'Aliquid non aut molestiae voluptatem aperiam exercitationem numquam. Dolore et ut veritatis fugiat quidem eum.', 'delivered', NULL, '{\"book_id\": 6, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-08 10:49:25', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(198, 1, 'fine_notice', 'push', 'tu92@hotmail.com', 'Aut sequi neque quia.', 'Libero eaque delectus similique magni. Et voluptatem nostrum aliquid qui pariatur.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-28 12:55:32', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(199, NULL, 'fine_notice', 'email', 'thao29@la.mil.vn', 'Voluptatem quibusdam tempora ut debitis.', 'Et et voluptatibus unde voluptatem architecto necessitatibus. Voluptatem fuga atque ea.', 'sent', NULL, '{\"book_id\": 2, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-15 07:30:04', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(200, 1, 'overdue_notice', 'sms', 'sinh38@yahoo.com', 'Est reiciendis enim aut.', 'Rerum aut neque ut assumenda dolores voluptate nihil. Fugit et perferendis eius eum et delectus.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 117, \"fine_amount\": null}', '2025-10-08 12:46:30', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(201, NULL, 'new_book_available', 'email', 'mnham@tong.biz', 'Dolor provident est ad.', 'Et illo dolor minima voluptatem eos. Non eos nobis ipsum accusantium vitae et distinctio. Totam molestiae voluptas harum dolores ab ex.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 93, \"fine_amount\": 19303.04}', '2025-09-24 20:14:53', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(202, 1, 'new_book_available', 'in_app', 'van.lo@gmail.com', 'Veritatis animi error.', 'Voluptates voluptas odit odio quis beatae doloremque sunt. Consectetur velit et debitis aperiam ut et.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 66450.26}', '2025-10-06 09:38:40', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(203, NULL, 'system_maintenance', 'email', 'kim07@vuong.health.vn', 'Eligendi tenetur autem.', 'Similique doloremque quasi error in dolorem optio. Quibusdam provident dicta qui et hic. Iste quaerat pariatur similique qui et consequatur.', 'failed', 'At doloribus voluptas reprehenderit tempore non quod quae.', '{\"book_id\": 60, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-13 07:56:40', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(204, 3, 'overdue_notice', 'email', 'diem.nguyen@kim.edu.vn', 'Rerum quis et quod.', 'Magnam laboriosam explicabo et ut. Iusto vel ut animi. Doloribus itaque et excepturi hic illo nostrum magnam.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-17 02:47:10', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(205, NULL, 'reservation_ready', 'sms', 'thao.giang@hotmail.com', 'Et vel repellat.', 'Aliquam rerum dolorem laborum qui doloribus. Consequatur et facilis dolores incidunt similique at eius. Voluptates quos incidunt accusamus voluptas tenetur voluptatem.', 'failed', NULL, '{\"book_id\": 9, \"borrow_id\": null, \"fine_amount\": 39280.97}', '2025-10-12 23:02:38', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(206, 1, 'system_maintenance', 'email', 'giap.dai@gmail.com', 'Id similique accusamus.', 'Architecto illo est saepe ea sit atque. Laudantium iusto omnis aut vero modi.', 'sent', 'Non placeat quas facere asperiores repellat.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-20 22:07:22', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(207, 1, 'new_book_available', 'push', 'ca.ly@tong.vn', 'Maxime doloremque.', 'Eius aut aut et cum. Soluta alias voluptates qui cum quis dicta.', 'sent', NULL, '{\"book_id\": 54, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-15 00:08:29', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(208, 2, 'reservation_ready', 'sms', 'an.lu@dai.health.vn', 'Et ullam ut.', 'Officiis sunt consequatur dicta saepe ea dolor. Eum quis aut ducimus dolorem.', 'sent', NULL, '{\"book_id\": 4, \"borrow_id\": null, \"fine_amount\": 81763.06}', '2025-10-12 08:24:13', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(209, NULL, 'borrow_reminder', 'in_app', 'tiep.bien@ta.com', 'Libero in fugiat.', 'Et qui commodi molestiae dolor ut odio molestiae. Aliquid qui fuga amet ipsum est.', 'delivered', NULL, '{\"book_id\": 18, \"borrow_id\": 21, \"fine_amount\": 44011.35}', '2025-09-03 23:59:35', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(210, 2, 'reservation_ready', 'push', 'pba@yahoo.com', 'Enim omnis possimus pariatur et.', 'Nisi soluta perspiciatis unde molestias vel sed. Et molestias velit dolor et veritatis accusamus est.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 79, \"fine_amount\": null}', '2025-08-09 14:34:54', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(211, 3, 'overdue_notice', 'push', 'nong.ky@nhiem.vn', 'Qui fuga possimus.', 'Magnam voluptatem eum eveniet cumque porro. In quod hic dolorem qui repudiandae saepe et.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-18 09:05:29', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(212, NULL, 'overdue_notice', 'in_app', 'xdau@gmail.com', 'Quaerat corporis itaque provident a.', 'Nihil tenetur nisi doloremque ut non unde cum. Hic accusamus quasi veritatis sed minus quia.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 63, \"fine_amount\": 30248.83}', '2025-09-21 17:16:13', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(213, NULL, 'borrow_reminder', 'email', 'thach.dang@pham.info.vn', 'Maxime cupiditate officia libero.', 'Ut quia officiis nemo numquam. Minima doloremque sunt illo non et a ut.', 'pending', NULL, '{\"book_id\": 44, \"borrow_id\": null, \"fine_amount\": 97047.33}', '2025-08-25 13:56:37', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(214, 2, 'new_book_available', 'sms', 'ntao@yahoo.com', 'Omnis doloremque totam.', 'Beatae tempore suscipit perferendis voluptatem. Tenetur eveniet dolorem provident dolor quia.', 'delivered', NULL, '{\"book_id\": 23, \"borrow_id\": null, \"fine_amount\": 67402.71}', '2025-08-22 11:48:10', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(215, NULL, 'overdue_notice', 'in_app', 'ela@danh.gov.vn', 'Similique ea architecto deserunt.', 'Corporis mollitia atque perferendis veniam sunt sint. Laboriosam deserunt illum deleniti hic. Quos voluptas aut aut eius.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-31 21:12:22', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(216, NULL, 'system_maintenance', 'sms', 'wau@uong.health.vn', 'Ipsa esse dolore.', 'Voluptatibus minima voluptatum quisquam est ex. Adipisci placeat corporis fugiat aut.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 52419.85}', '2025-08-16 23:37:04', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(217, 1, 'overdue_notice', 'push', 'lam.lam@lu.mil.vn', 'Sunt voluptatem.', 'Quia harum architecto omnis. Ratione impedit aliquam molestiae.', 'delivered', NULL, '{\"book_id\": 46, \"borrow_id\": 129, \"fine_amount\": 85365.89}', '2025-09-03 21:08:44', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(218, NULL, 'overdue_notice', 'push', 'man.my@gmail.com', 'Culpa repellendus repellendus quidem.', 'Temporibus repudiandae nobis et voluptatem aliquid. Sit quas qui quos et voluptatem ut fugit quia. Cupiditate eos voluptas voluptatem rem occaecati et dolores.', 'failed', NULL, '{\"book_id\": 52, \"borrow_id\": 177, \"fine_amount\": null}', '2025-09-02 04:22:17', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(219, 1, 'borrow_reminder', 'push', 'tuan69@gmail.com', 'Totam velit ut.', 'Et alias aut voluptatem assumenda soluta. Voluptas necessitatibus voluptatem omnis iusto excepturi. Ipsum unde officiis et explicabo recusandae.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 38614.78}', '2025-10-14 15:40:56', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(220, NULL, 'reservation_ready', 'sms', 'ong.hien@yahoo.com', 'Sit a eaque animi.', 'Quae et quae id dolorum recusandae animi. Explicabo qui et hic voluptates itaque illo.', 'delivered', NULL, '{\"book_id\": 20, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-04 21:33:57', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(221, 3, 'overdue_notice', 'push', 'thai.doan@gmail.com', 'Doloremque rem eaque reiciendis.', 'Quia corporis libero pariatur inventore earum. Repellendus rerum facilis voluptatum. Assumenda molestiae voluptates odio reiciendis in officiis.', 'pending', NULL, '{\"book_id\": 16, \"borrow_id\": null, \"fine_amount\": 45854.41}', '2025-09-22 21:19:45', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(222, NULL, 'overdue_notice', 'in_app', 'lhoa@gmail.com', 'Culpa repellat incidunt voluptatibus.', 'Dignissimos iusto dolores assumenda est dolorum vel ad et. Molestias ut occaecati numquam rem.', 'failed', NULL, '{\"book_id\": 10, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-19 02:48:55', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(223, 1, 'borrow_reminder', 'push', 'toan90@gmail.com', 'Iste fugit at praesentium.', 'Officiis sint aspernatur tenetur vel inventore. Voluptatem veniam earum soluta rerum voluptas repellat magnam nostrum.', 'pending', 'Quibusdam autem esse non molestiae et.', '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-11 05:40:58', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(224, 4, 'system_maintenance', 'email', 'jlo@lieu.health.vn', 'Et adipisci quia.', 'Sapiente ut occaecati accusantium tenetur occaecati et illum deleniti. Cum excepturi illo qui quaerat qui dolores. Voluptatem alias molestias sed nihil sint.', 'pending', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-02 10:07:57', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(225, NULL, 'reservation_ready', 'email', 'dtieu@hotmail.com', 'Ex in praesentium rerum.', 'Alias ipsum non mollitia et et id ut a. Rerum quam qui fuga eos.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 102, \"fine_amount\": null}', '2025-09-03 08:12:02', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(226, 4, 'reservation_ready', 'in_app', 'truc41@yahoo.com', 'Molestiae dolor illum aliquam.', 'Ut autem dolorem sed perferendis. Similique laudantium et quam eaque temporibus.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 32444.25}', '2025-10-08 22:20:54', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(227, 4, 'new_book_available', 'in_app', 'nhan.tra@trac.org.vn', 'Tempore sint saepe.', 'Quia ratione voluptatibus tempore soluta. Velit ipsum doloremque amet molestiae. Id voluptate cumque deserunt rerum quos.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-11 14:20:37', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(228, NULL, 'system_maintenance', 'push', 'xngan@giao.name.vn', 'Iste est odit sequi.', 'Qui neque dolorem corrupti ipsam dolores. Molestiae similique tempore odit eaque et fugiat. Qui perspiciatis enim reprehenderit possimus sed delectus nisi optio.', 'delivered', NULL, '{\"book_id\": 15, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-02 05:44:09', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(229, 4, 'reservation_ready', 'in_app', 'dat98@yahoo.com', 'Culpa laboriosam culpa.', 'Iste deleniti est commodi atque quia. Aut adipisci et veniam repudiandae aut nemo. Recusandae sed corrupti omnis quidem aut laborum voluptates.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-21 04:40:49', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(230, 4, 'fine_notice', 'push', 'la.khanh@yahoo.com', 'Soluta sed velit.', 'Et eligendi possimus ea et nemo. Ducimus deserunt qui modi sint. At corporis dignissimos quia omnis.', 'delivered', NULL, '{\"book_id\": 24, \"borrow_id\": 60, \"fine_amount\": null}', '2025-10-07 08:30:46', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(231, 2, 'new_book_available', 'in_app', 'due44@tao.com', 'Omnis voluptatem quia molestias.', 'Placeat quam omnis alias ut rerum cum. Et eum ea est et quod.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-04 22:44:33', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(232, NULL, 'reservation_ready', 'in_app', 'kiem.hung@moc.net.vn', 'Eaque veniam commodi libero.', 'Fugit culpa a laborum alias ut quibusdam adipisci. Vitae quasi dolorem id quod et consectetur.', 'pending', NULL, '{\"book_id\": 17, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-20 05:57:59', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(233, 3, 'fine_notice', 'in_app', 'wdoan@dao.com', 'Qui quia laborum temporibus.', 'Sunt impedit rerum minima dolore. Enim error perferendis unde vel temporibus fugit aspernatur quasi. Necessitatibus quidem inventore dolorum hic.', 'delivered', NULL, '{\"book_id\": 34, \"borrow_id\": 79, \"fine_amount\": null}', '2025-08-01 17:07:45', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(234, 1, 'reservation_ready', 'email', 'ky.han@hotmail.com', 'Non veniam aspernatur iusto.', 'Ut maxime adipisci quia. Reiciendis accusamus deserunt fugit distinctio quae.', 'sent', 'Ad blanditiis dolorem itaque blanditiis.', '{\"book_id\": null, \"borrow_id\": 76, \"fine_amount\": null}', '2025-09-24 12:01:26', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(235, 3, 'reservation_ready', 'email', 'diem.khue@dien.gov.vn', 'Quos beatae incidunt reprehenderit.', 'Doloribus laboriosam voluptas dicta ducimus occaecati est alias ex. Rerum sit impedit quia ipsa praesentium.', 'failed', NULL, '{\"book_id\": 44, \"borrow_id\": 83, \"fine_amount\": null}', '2025-09-21 19:14:37', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(236, NULL, 'new_book_available', 'in_app', 'quach.ha@yahoo.com', 'Magnam delectus inventore est.', 'Debitis culpa repudiandae et unde enim voluptatem. Fugiat velit tempora commodi numquam ut unde amet. Ea quia id exercitationem reprehenderit cum.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-21 00:17:09', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(237, NULL, 'reservation_ready', 'email', 'an.sy@doan.mil.vn', 'Consectetur nobis labore aut.', 'Ipsam alias iure aut accusamus. Quia est ad assumenda sint.', 'pending', NULL, '{\"book_id\": 22, \"borrow_id\": null, \"fine_amount\": 67496.64}', '2025-08-13 19:12:48', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(238, 4, 'overdue_notice', 'push', 'ha.thinh@gmail.com', 'Nam minima consequatur.', 'Vitae voluptatem iure voluptas est optio asperiores. Esse laboriosam voluptatum ab deserunt iste fugiat. Hic quos quidem sit aspernatur sit rerum.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 55, \"fine_amount\": null}', '2025-08-30 01:05:30', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(239, 3, 'fine_notice', 'email', 'bac.yen@trang.health.vn', 'Pariatur necessitatibus quis.', 'Cum qui vero ipsam vitae quo. Iure unde beatae quisquam dolorem. Inventore pariatur voluptatem quod.', 'failed', NULL, '{\"book_id\": 27, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-23 10:54:40', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(240, NULL, 'system_maintenance', 'sms', 'cdiem@yahoo.com', 'Ratione animi est.', 'Aut facilis alias voluptatem aut doloremque placeat quas. Quod sed rem quis ad quia iste culpa voluptatem.', 'sent', 'Distinctio ipsam aut dolorem aut.', '{\"book_id\": 39, \"borrow_id\": null, \"fine_amount\": 76955.41}', '2025-10-03 22:33:50', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(241, 1, 'fine_notice', 'push', 'tuyet.phi@pho.ac.vn', 'Et commodi commodi amet.', 'Qui tenetur qui molestiae eum voluptatum sint. Iste molestiae omnis suscipit fugit. Officia harum quae et sed.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-24 08:42:34', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(242, 1, 'borrow_reminder', 'email', 'bty@khong.net.vn', 'Consequatur voluptatem qui error.', 'Sint quae et optio vitae dolore voluptas blanditiis eos. Veniam nihil voluptates tempora aut qui quo modi.', 'failed', NULL, '{\"book_id\": 15, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-24 02:37:01', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(243, 3, 'system_maintenance', 'push', 'uy.la@bac.edu.vn', 'Eaque sit optio.', 'Odit laborum aut quisquam et est omnis aut. Mollitia et autem fugiat optio labore autem est.', 'sent', NULL, '{\"book_id\": 37, \"borrow_id\": 153, \"fine_amount\": null}', '2025-09-17 07:30:40', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(244, 4, 'reservation_ready', 'email', 'bich.la@hotmail.com', 'Repellendus similique dignissimos suscipit.', 'Qui a perferendis qui debitis. Pariatur vero iusto in.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-01 08:37:41', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(245, 1, 'overdue_notice', 'push', 'pthao@nham.info', 'Omnis minima.', 'Quisquam ducimus quos adipisci. Ut mollitia dolores doloremque voluptatem. Consequatur a adipisci quaerat possimus quae quasi.', 'failed', NULL, '{\"book_id\": 46, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-27 11:33:12', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(246, NULL, 'reservation_ready', 'in_app', 'phan.thac@ngan.info', 'Beatae necessitatibus eum nam tempora.', 'Esse sit vel autem molestias facere nihil. Doloribus ad natus occaecati quos.', 'sent', 'Et dolorum necessitatibus ut veritatis aut id.', '{\"book_id\": 57, \"borrow_id\": null, \"fine_amount\": 34444.71}', '2025-09-03 06:37:03', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(247, 3, 'borrow_reminder', 'push', 'hson@nham.mil.vn', 'Et corporis pariatur.', 'Aut est possimus dignissimos suscipit quis qui. Illum qui voluptatem doloremque provident aut ea. Adipisci aut odio voluptas eos qui.', 'delivered', NULL, '{\"book_id\": 28, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-25 15:41:16', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(248, 4, 'overdue_notice', 'email', 'vo.thinh@gmail.com', 'Voluptatem et omnis quae soluta.', 'Omnis ea expedita architecto totam quia. Rerum est corrupti maiores a autem.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 89177.71}', '2025-10-23 19:21:41', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(249, 2, 'overdue_notice', 'sms', 'rphi@gmail.com', 'Aperiam similique officiis aut.', 'Qui et culpa dolor sequi accusantium eum. Molestias beatae mollitia commodi. Optio enim ut qui dolores possimus ad tempore.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-12 02:03:03', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(250, 3, 'new_book_available', 'push', 'tran.tong@ba.com', 'Et quo assumenda.', 'Dolore qui vitae sunt velit ut quisquam sit. Minima nam sequi explicabo iusto.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-29 19:47:26', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(251, 1, 'system_maintenance', 'push', 'bac.don@yahoo.com', 'Sit itaque atque aut.', 'Eos consequatur qui totam sed itaque. Ratione repudiandae numquam et. Totam quia fuga distinctio totam ea.', 'delivered', NULL, '{\"book_id\": 26, \"borrow_id\": 88, \"fine_amount\": null}', '2025-09-09 03:27:53', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(252, 2, 'overdue_notice', 'in_app', 'bang.cai@hotmail.com', 'Omnis laboriosam debitis cumque.', 'Aut aut et deleniti fugit qui quia. Natus tempora iure aut aut qui repellendus aliquid quia. Eum omnis et aut aut consequuntur reiciendis.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 92225.79}', '2025-08-04 20:20:17', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(253, 3, 'new_book_available', 'in_app', 'wtang@lac.com', 'Dolore iure velit consequuntur.', 'Rem quidem atque quas consequatur a tenetur. Ut amet dolor quos consequatur aut. Distinctio nesciunt qui et soluta accusantium.', 'pending', NULL, '{\"book_id\": 6, \"borrow_id\": 51, \"fine_amount\": null}', '2025-08-04 19:39:17', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(254, 2, 'borrow_reminder', 'email', 'thoai.lai@dan.int.vn', 'Ipsa fugiat sint expedita.', 'Accusantium commodi eos maiores quia facilis tempora est a. Porro porro tempora enim.', 'delivered', 'Voluptates officia quibusdam est et ut tenetur aperiam molestias.', '{\"book_id\": null, \"borrow_id\": 22, \"fine_amount\": null}', '2025-10-18 09:50:13', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(255, 2, 'reservation_ready', 'sms', 'ylai@lo.info.vn', 'Nisi vero tenetur consequatur.', 'Debitis voluptate laudantium neque fugit ut officiis dolores. Cumque reiciendis impedit alias minus tempora. Voluptas a magnam ab aut accusamus et.', 'sent', NULL, '{\"book_id\": 50, \"borrow_id\": 137, \"fine_amount\": null}', '2025-10-15 22:04:06', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(256, NULL, 'fine_notice', 'push', 'nga62@yahoo.com', 'Iste debitis qui.', 'Tempore praesentium explicabo molestiae perspiciatis velit culpa asperiores. Blanditiis qui explicabo eos ut.', 'failed', NULL, '{\"book_id\": 9, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-27 01:06:40', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(257, 1, 'new_book_available', 'push', 'dung.dong@ba.net.vn', 'Doloremque nesciunt officia.', 'Quibusdam non molestiae occaecati adipisci occaecati exercitationem doloribus. At aut non iure expedita animi non. Non vel ipsam dicta.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-30 07:48:48', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(258, 4, 'overdue_notice', 'in_app', 'thy78@giap.com', 'Ipsa dolorum.', 'Nesciunt velit et magni aut magni id. Quis magni cupiditate ratione occaecati. Accusamus suscipit molestiae possimus doloribus.', 'pending', NULL, '{\"book_id\": 41, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-12 16:21:45', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(259, 1, 'new_book_available', 'push', 'ohan@gmail.com', 'Sed aperiam et et.', 'Esse optio et odit ex voluptas quod voluptate nihil. Asperiores qui dignissimos quo sit eaque. Hic perspiciatis et nulla quisquam corrupti voluptatem.', 'sent', 'Vel sed eos eaque corporis.', '{\"book_id\": 42, \"borrow_id\": 80, \"fine_amount\": null}', '2025-10-04 14:26:47', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(260, NULL, 'new_book_available', 'push', 'nga95@danh.biz.vn', 'Quia quos explicabo aut.', 'Sint quisquam itaque excepturi id in. Nesciunt a autem dolorum dolorum excepturi fugiat. Quibusdam impedit quis doloremque aliquam.', 'sent', NULL, '{\"book_id\": 30, \"borrow_id\": 13, \"fine_amount\": null}', '2025-10-25 19:06:21', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(261, 1, 'borrow_reminder', 'in_app', 'phu92@yahoo.com', 'Accusantium in.', 'Ut in ea neque alias voluptas. Alias nihil vel et tempora praesentium porro.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 78199.56}', '2025-08-15 11:17:22', '2025-10-26 06:13:55', '2025-10-26 06:13:55'),
(262, 2, 'reservation_ready', 'email', 'truc.han@danh.org', 'Sed architecto et ipsam.', 'Dolorem sed cupiditate commodi quae quibusdam. Exercitationem repellat ut numquam dolorem nam.', 'failed', NULL, '{\"book_id\": 58, \"borrow_id\": 145, \"fine_amount\": 41280.68}', '2025-09-22 05:40:42', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(263, 2, 'borrow_reminder', 'sms', 'dong.hua@yahoo.com', 'Dicta similique vitae.', 'Consequuntur sed illo dolores voluptatem. Aliquid dolores soluta quasi exercitationem id. Sed mollitia libero iste laborum eos est debitis.', 'delivered', NULL, '{\"book_id\": 51, \"borrow_id\": 51, \"fine_amount\": null}', '2025-08-30 11:01:53', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(264, 1, 'borrow_reminder', 'in_app', 'ha.du@lam.int.vn', 'Et sunt impedit.', 'Voluptatem eligendi deleniti at error saepe debitis ut. Blanditiis omnis et ab optio vero voluptas accusantium et. Repellendus tempora est sunt nobis hic laboriosam hic.', 'failed', NULL, '{\"book_id\": 18, \"borrow_id\": 92, \"fine_amount\": 45175.06}', '2025-09-09 03:28:56', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(265, NULL, 'fine_notice', 'sms', 'hoang.thu@han.com', 'Doloremque provident suscipit illo vel.', 'Nesciunt quisquam non sunt. Neque omnis doloribus provident qui.', 'pending', NULL, '{\"book_id\": 7, \"borrow_id\": 15, \"fine_amount\": 83147.69}', '2025-08-21 17:11:30', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(266, 1, 'fine_notice', 'in_app', 'don.anh@thao.com', 'Voluptate nostrum amet possimus.', 'Dolores aliquid officiis odit et quibusdam autem placeat rem. Quo provident molestiae odio.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-15 23:49:51', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(267, 4, 'fine_notice', 'sms', 'di.dan@luc.org.vn', 'Nesciunt qui aut dolor.', 'Sequi quam et id cum eaque. Blanditiis voluptate ullam accusamus sit aut alias deleniti.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 9, \"fine_amount\": null}', '2025-09-06 14:24:13', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(268, 4, 'system_maintenance', 'in_app', 'phuoc.han@hotmail.com', 'Rerum ea et aut.', 'Voluptatem corrupti architecto deleniti facilis enim neque. Quaerat voluptatibus ut ea perspiciatis fuga. Amet eveniet voluptate velit voluptatem et accusantium.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-03 09:02:44', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(269, NULL, 'borrow_reminder', 'in_app', 'bao08@cam.biz.vn', 'Ut sit maiores.', 'Cum voluptatem odio rerum magnam necessitatibus. Molestias eos et molestiae vel neque omnis omnis qui.', 'pending', NULL, '{\"book_id\": 40, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-02 23:45:29', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(270, 3, 'fine_notice', 'email', 'doan.trong@diem.pro.vn', 'Ad voluptas autem.', 'Delectus aperiam a ut. Perferendis quia occaecati praesentium ex quia.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 12, \"fine_amount\": 25285.86}', '2025-07-29 19:51:40', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(271, 4, 'fine_notice', 'push', 'thanh41@nong.biz.vn', 'Iusto provident quo.', 'Eaque magni quis sed dolores et. Nam ullam asperiores ut. Est natus quia ut dolorem saepe.', 'sent', NULL, '{\"book_id\": 26, \"borrow_id\": 103, \"fine_amount\": 92842.82}', '2025-08-22 03:15:20', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(272, 1, 'overdue_notice', 'email', 'khanh.khau@yahoo.com', 'Voluptatem est eum.', 'Asperiores ipsa error voluptatem deleniti itaque. Molestiae velit et illum et architecto. Possimus quod suscipit laboriosam ea iusto.', 'failed', NULL, '{\"book_id\": 6, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-11 14:13:20', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(273, NULL, 'overdue_notice', 'in_app', 'zta@gmail.com', 'Qui error qui.', 'Inventore quia expedita nisi in. Ab incidunt quos id voluptatum.', 'failed', NULL, '{\"book_id\": 21, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-26 02:20:18', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(274, NULL, 'fine_notice', 'sms', 'co.loan@yahoo.com', 'Numquam eos nisi hic.', 'Voluptatem eos numquam dolores molestiae autem. Earum maiores eius dolor ab eligendi minima.', 'sent', NULL, '{\"book_id\": 37, \"borrow_id\": null, \"fine_amount\": 80959.99}', '2025-08-11 18:56:07', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(275, 4, 'fine_notice', 'in_app', 'hoai78@vien.ac.vn', 'Qui ratione.', 'Officiis iusto eos adipisci quia sed. Aut voluptatem sunt omnis omnis pariatur quia. Ut sit dolores eos optio aut.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": 48, \"fine_amount\": 25145.29}', '2025-09-25 09:40:10', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(276, 3, 'borrow_reminder', 'in_app', 'be.thoa@hotmail.com', 'Soluta tenetur dolorem.', 'Iure voluptatum suscipit in soluta temporibus voluptatem autem. Illo quasi harum voluptatem rerum odio voluptatum. Eaque eos a autem illum.', 'pending', NULL, '{\"book_id\": 36, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-05 09:40:25', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(277, 2, 'overdue_notice', 'sms', 'dau.dung@hotmail.com', 'Dolorum quod nihil.', 'Nihil nisi fugit doloribus et corporis voluptates. Dolorem repellendus blanditiis sed quo sit. Eaque explicabo quisquam ad et.', 'failed', 'Voluptatem omnis ducimus corporis.', '{\"book_id\": null, \"borrow_id\": 100, \"fine_amount\": 31119.91}', '2025-09-06 06:39:03', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(278, 1, 'system_maintenance', 'email', 'su.phong@hotmail.com', 'Ipsa in quo adipisci.', 'Sunt quia tempora sint voluptatem porro unde in similique. Debitis voluptas rerum quisquam quos cumque.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": 161, \"fine_amount\": null}', '2025-10-23 06:25:03', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(279, 2, 'system_maintenance', 'email', 'stu@hotmail.com', 'Impedit quaerat quo est assumenda.', 'Necessitatibus aut nulla facere ab nostrum atque ad. Odit voluptas rerum accusamus dolore.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 173, \"fine_amount\": 36559.13}', '2025-09-07 20:17:58', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(280, 4, 'reservation_ready', 'in_app', 'phuoc93@bao.pro.vn', 'Occaecati ut placeat consequatur.', 'Mollitia et a voluptas ad. Odit veniam voluptatem commodi omnis vero. Et libero alias veniam consectetur magnam.', 'pending', NULL, '{\"book_id\": 15, \"borrow_id\": 53, \"fine_amount\": null}', '2025-09-30 20:27:01', '2025-10-26 06:13:56', '2025-10-26 06:13:56');
INSERT INTO `notification_logs` (`id`, `template_id`, `type`, `channel`, `recipient`, `subject`, `content`, `status`, `error_message`, `metadata`, `sent_at`, `created_at`, `updated_at`) VALUES
(281, NULL, 'new_book_available', 'sms', 'bao65@lieu.org', 'Sed rerum accusamus.', 'Assumenda in tempore ipsam minima quia labore ut. Deleniti aspernatur sint aut tempore veniam vero et nihil. Ipsa et eligendi sapiente et voluptatem.', 'pending', NULL, '{\"book_id\": 25, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-02 01:10:40', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(282, 4, 'borrow_reminder', 'push', 'huynh.thong@hinh.com', 'Excepturi omnis explicabo eum.', 'Placeat facilis reiciendis ex distinctio illo vitae sint. Ipsa in dolores dolorum vel aut.', 'failed', NULL, '{\"book_id\": 11, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-22 01:55:10', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(283, 2, 'reservation_ready', 'push', 'znhiem@dam.gov.vn', 'Laudantium ab repudiandae.', 'Voluptate veritatis ea amet porro. Occaecati et harum at neque temporibus.', 'sent', NULL, '{\"book_id\": 5, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-15 18:46:37', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(284, 2, 'borrow_reminder', 'sms', 'kim.pham@ty.net', 'In nisi saepe.', 'Quasi numquam voluptatum debitis molestias alias enim libero. Iusto deserunt ut vitae harum aut tenetur. Error labore aut dolor ex delectus quaerat rerum.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-08 10:16:30', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(285, 3, 'reservation_ready', 'in_app', 'suong68@su.name.vn', 'Et voluptatem suscipit aliquam.', 'Velit sunt ut voluptas ipsa omnis quia. Possimus aut quia aut non minus. Asperiores molestiae et modi rem qui repellat.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 47103.73}', '2025-08-22 16:46:53', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(286, NULL, 'fine_notice', 'email', 'nhien.nong@phuong.info', 'Aut ut quam amet.', 'Fuga voluptatibus quas quia. Maiores voluptatem iusto voluptate dolore accusamus ut non. Consectetur ipsa aut ea mollitia quia.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-09 08:25:44', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(287, 1, 'overdue_notice', 'email', 'mau.cuc@yahoo.com', 'Consequatur reprehenderit similique quia.', 'Praesentium voluptate delectus esse vel dignissimos. Dignissimos aut occaecati doloremque porro est. Similique fugit beatae sit neque voluptas et.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 59, \"fine_amount\": null}', '2025-08-21 00:24:16', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(288, 1, 'fine_notice', 'sms', 'linh.leu@mau.gov.vn', 'Rerum blanditiis omnis laudantium.', 'Incidunt quis non neque eum adipisci consequuntur recusandae veniam. Voluptates est ad ea odio. Sit dolor officia atque vel aut.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": 66729.55}', '2025-10-12 12:09:38', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(289, 3, 'borrow_reminder', 'in_app', 'oanh.nham@yahoo.com', 'Quas animi quia doloribus.', 'Totam recusandae error excepturi enim ut. Iure quasi cupiditate esse non et.', 'sent', NULL, '{\"book_id\": 25, \"borrow_id\": null, \"fine_amount\": null}', '2025-09-01 20:00:26', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(290, 3, 'fine_notice', 'push', 'hly@gmail.com', 'Necessitatibus qui vitae et voluptatem.', 'Sunt quo sapiente nemo mollitia molestias sapiente voluptatem assumenda. Ullam est corrupti debitis nostrum modi soluta ut.', 'failed', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": null}', '2025-08-13 19:19:55', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(291, 4, 'new_book_available', 'sms', 'thuc.cat@an.int.vn', 'Fugiat minus ratione sit.', 'Quidem blanditiis ad nobis ducimus eum. Et et recusandae velit non quia. Quia illo vel inventore quam est.', 'delivered', NULL, '{\"book_id\": 56, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-31 19:49:23', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(292, NULL, 'reservation_ready', 'push', 'lam.han@ma.gov.vn', 'Eos in molestias.', 'Et est consequatur temporibus et ipsa doloremque ut. Dicta qui nulla eos aut odit possimus animi. Aspernatur aliquam excepturi distinctio molestiae officiis ipsum.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 182, \"fine_amount\": null}', '2025-10-16 15:19:50', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(293, 2, 'reservation_ready', 'in_app', 'trong.tra@yahoo.com', 'Corrupti temporibus sit soluta.', 'Aut neque aliquam non distinctio fuga. Fugiat aut perferendis sit in animi animi qui. Nobis a vero odio error architecto deleniti velit.', 'failed', NULL, '{\"book_id\": 14, \"borrow_id\": 33, \"fine_amount\": null}', '2025-09-17 11:19:31', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(294, NULL, 'new_book_available', 'in_app', 'phuong.thi@thap.info', 'Error sed et.', 'In ad ut animi enim. Dolorem et maiores voluptates nesciunt aut et. Ut error sit omnis sint consequuntur sit.', 'delivered', NULL, '{\"book_id\": 16, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-03 12:43:41', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(295, NULL, 'system_maintenance', 'email', 'nhuong75@hua.mil.vn', 'Sit deserunt sint earum.', 'Minima aperiam cupiditate placeat cum adipisci. Quas dolores sed in molestiae esse rerum. Dolores autem recusandae qui quaerat et.', 'sent', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-10-04 11:52:28', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(296, 3, 'system_maintenance', 'sms', 'duong53@hotmail.com', 'Necessitatibus non ipsum.', 'Itaque consequuntur fuga voluptas quod et ut. Consequatur et quia eaque veritatis. Perferendis facere ex odit ratione molestiae cupiditate architecto.', 'delivered', NULL, '{\"book_id\": null, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-26 10:52:05', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(297, NULL, 'reservation_ready', 'email', 'can89@to.info.vn', 'Nulla distinctio.', 'Reprehenderit quis corrupti similique non doloremque error labore. Doloremque debitis ea deleniti nam qui soluta.', 'pending', NULL, '{\"book_id\": 40, \"borrow_id\": null, \"fine_amount\": null}', '2025-07-29 23:22:58', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(298, 3, 'reservation_ready', 'sms', 'tuyen12@yahoo.com', 'Rerum possimus voluptas.', 'Et similique quisquam omnis aliquid ratione ut. Aut suscipit delectus dolor. Rerum id quo nulla ipsam qui.', 'pending', NULL, '{\"book_id\": null, \"borrow_id\": 127, \"fine_amount\": 42578.98}', '2025-07-28 01:20:55', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(299, 3, 'overdue_notice', 'in_app', 'dang21@yahoo.com', 'Debitis voluptatem est fugiat.', 'Et nemo consequatur dolores ut laudantium impedit nemo. Sit temporibus totam beatae veniam perspiciatis sed. Alias quas minima sapiente et delectus.', 'sent', NULL, '{\"book_id\": 32, \"borrow_id\": 50, \"fine_amount\": 15740.74}', '2025-08-27 15:51:19', '2025-10-26 06:13:56', '2025-10-26 06:13:56'),
(300, 1, 'fine_notice', 'email', 'tang.chi@gmail.com', 'Quia commodi eveniet.', 'Possimus repellat sapiente est dolorum. Doloribus aliquam quis ipsam dolore. Neque voluptas quia assumenda sed veniam.', 'failed', NULL, '{\"book_id\": null, \"borrow_id\": 108, \"fine_amount\": null}', '2025-08-05 01:46:00', '2025-10-26 06:13:56', '2025-10-26 06:13:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variables` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `name`, `type`, `channel`, `subject`, `content`, `variables`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Nhắc nhở trả sách sắp đến hạn', 'borrow_reminder', 'email', 'Nhắc nhở trả sách - {{book_title}}', 'Xin chào {{reader_name}},\n\nSách \'{{book_title}}\' của bạn sắp đến hạn trả vào ngày {{due_date}}.\nCòn {{days_remaining}} ngày nữa.\n\nVui lòng trả sách đúng hạn để tránh phí phạt.\n\nTrân trọng,\nThư viện', '[\"reader_name\", \"book_title\", \"due_date\", \"days_remaining\"]', 1, '2025-10-26 06:13:39', '2025-10-26 06:13:39'),
(2, 'Cảnh báo sách quá hạn', 'overdue_notification', 'email', 'Cảnh báo: Sách quá hạn - {{book_title}}', 'Xin chào {{reader_name}},\n\nSách \'{{book_title}}\' của bạn đã quá hạn trả {{days_overdue}} ngày.\nHạn trả: {{due_date}}\n\nVui lòng trả sách ngay để tránh phí phạt tăng cao.\n\nTrân trọng,\nThư viện', '[\"reader_name\", \"book_title\", \"due_date\", \"days_overdue\"]', 1, '2025-10-26 06:13:39', '2025-10-26 06:13:39'),
(3, 'Thông báo sách đặt trước sẵn sàng', 'reservation_ready', 'email', 'Sách đặt trước sẵn sàng - {{book_title}}', 'Xin chào {{reader_name}},\n\nSách \'{{book_title}}\' mà bạn đã đặt trước đã sẵn sàng để nhận.\nNgày sẵn sàng: {{ready_date}}\nHạn nhận: {{expiry_date}}\n\nVui lòng đến thư viện để nhận sách trong thời gian quy định.\n\nTrân trọng,\nThư viện', '[\"reader_name\", \"book_title\", \"ready_date\", \"expiry_date\"]', 1, '2025-10-26 06:13:39', '2025-10-26 06:13:39'),
(4, 'Thông báo phạt', 'fine_notification', 'email', 'Thông báo phạt - {{fine_type}}', 'Xin chào {{reader_name}},\n\nBạn có phạt {{fine_type}} cho sách \'{{book_title}}\'.\nSố tiền phạt: {{fine_amount}}\nHạn thanh toán: {{due_date}}\n\nVui lòng thanh toán phạt để tiếp tục sử dụng dịch vụ thư viện.\n\nTrân trọng,\nThư viện', '[\"reader_name\", \"book_title\", \"fine_amount\", \"due_date\", \"fine_type\"]', 1, '2025-10-26 06:13:39', '2025-10-26 06:13:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `session_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `subtotal`, `tax_amount`, `shipping_amount`, `total_amount`, `status`, `payment_status`, `payment_method`, `notes`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(1, 'ORD202511052369', 23, 'BaY14WS1FieKI7WClh1pl79TCEzz5pbpL5tRTMru', 'Người dùng', 'user@library.com', '0583388961', 'g', 32130.00, 0.00, 0.00, 32130.00, 'delivery_failed', 'pending', 'cash_on_delivery', 'g', NULL, '2025-11-05 09:57:31', '2025-11-08 06:39:28'),
(2, 'ORD202511055616', 23, 'gxMobJfEAYX1pveuAZXqCqmUKT35qR121ZlOP0Cl', 'Người dùng', 'user@library.com', '0583388961', 'f', 190000.00, 0.00, 0.00, 190000.00, 'delivered', 'paid', 'cash_on_delivery', 'f', NULL, '2025-11-05 10:31:59', '2025-11-21 20:39:33'),
(3, 'ORD202511058595', 23, 'gxMobJfEAYX1pveuAZXqCqmUKT35qR121ZlOP0Cl', 'Người dùng', 'user@library.com', '0583388961', 'h', 31500.00, 0.00, 0.00, 31500.00, 'cancelled', 'pending', 'cash_on_delivery', 'h', NULL, '2025-11-05 10:52:33', '2025-11-08 07:12:12'),
(4, 'ORD202511068624', 23, 'puHPo7xxqHicMF5R6UBNoiT6Ld2PYjYmnAj2jEa7', 'Người dùng', 'user@library.com', '0583388961', 'f', 61530.00, 0.00, 0.00, 61530.00, 'delivered', 'paid', 'cash_on_delivery', 'f', NULL, '2025-11-05 20:41:07', '2025-11-08 06:38:57'),
(5, 'ORD202511077205', 23, 'L40mKQyAJbtL5fw2TEG8GpwuR1bvNFfdxEhgwXYS', 'Người dùng', 'user@library.com', '0583388961', 'gg', 95760.00, 0.00, 0.00, 95760.00, 'delivered', 'pending', 'cash_on_delivery', 'đ', NULL, '2025-11-07 08:36:58', '2025-11-07 11:20:17'),
(6, 'ORD202511082665', 1, 'ieKbXEKMvBDOUvi0Yq5hujMCkaPugiD1fKTafcR7', 'Super Admin', 'admin@library.com', '0583388961', 'h', 176190.00, 0.00, 0.00, 176190.00, 'cancelled', 'pending', 'bank_transfer', 'h', 'không muốn mua nữa', '2025-11-08 06:40:37', '2025-11-21 15:43:50'),
(7, 'ORD202511084312', 23, 'ztPVfZdBKw5lX0I8CPJImn7DaccArV8IaW69KOvR', 'Người dùng', 'user@library.com', '0583388961', 'd', 307440.00, 0.00, 0.00, 307440.00, 'delivered', 'paid', 'cash_on_delivery', 'd', NULL, '2025-11-08 07:00:39', '2025-11-21 17:31:06'),
(8, 'ORD202511129549', 1, 'CaLKUz6prJURZLdW4S4UsfwD91ZfdciAZChZyb3W', 'Super Admin', 'admin@library.com', '0583388961', 'k', 2778000.00, 0.00, 0.00, 2778000.00, 'cancelled', 'pending', 'cash_on_delivery', 'k', 'không muốn mua nữa', '2025-11-12 08:38:52', '2025-11-21 15:42:46'),
(9, 'ORD202511128272', 1, 'rLaCfLv1y1pY7LjaJ5IEj9Dh0t7U6WVzLq31h4dp', 'Super Admin', 'admin@library.com', '0583388961', 'f', 6120000.00, 0.00, 0.00, 6120000.00, 'cancelled', 'pending', 'cash_on_delivery', 'f', 'không muốn mua nữa', '2025-11-12 09:25:46', '2025-11-21 15:42:34'),
(10, 'ORD202511126174', 23, 'u4ODtRDJF9nU55vDWLagNrYqAJFar7h2A1qJBiE9', 'Người dùng', 'user@library.com', '0583388961', 'k', 6000000.00, 0.00, 0.00, 6000000.00, 'delivered', 'paid', 'cash_on_delivery', 'k', NULL, '2025-11-12 09:33:36', '2025-11-12 09:34:53'),
(11, 'ORD202511133633', 1, '9Jhvs61lvaYSQszL0yATc7SXzauV9iuqoEU7v6rE', 'Super Admin', 'admin@library.com', '0583388961', 'k', 6000000.00, 0.00, 0.00, 6000000.00, 'cancelled', 'pending', 'cash_on_delivery', 'k', 'không muốn mua', '2025-11-13 14:33:06', '2025-11-21 15:42:17'),
(12, 'ORD202511214158', 1, 'XHjDKLKi0T3OhWrQzzOqY07vNxeF3JpEtnsgqJoy', 'Super Admin', 'admin@library.com', '0583388961', 'd', 450000.00, 0.00, 0.00, 450000.00, 'delivered', 'paid', 'cash_on_delivery', 'd', NULL, '2025-11-21 15:30:04', '2025-11-21 15:30:43'),
(13, 'ORD202511222514', 1, 'hfTjOs9k0YouJRNZuzLNFLsvJJKvlgJ9oYXm4Wz6', 'Super Admin', 'admin@library.com', '0583388961', 'g', 100000.00, 0.00, 0.00, 100000.00, 'cancelled', 'pending', 'cash_on_delivery', 'f', 'không muốn mua nữa', '2025-11-21 20:43:42', '2025-11-21 20:44:25'),
(14, 'ORD202511222583', 1, 'hfTjOs9k0YouJRNZuzLNFLsvJJKvlgJ9oYXm4Wz6', 'Super Admin', 'admin@library.com', '0583388961', 'd', 400000.00, 0.00, 0.00, 400000.00, 'cancelled', 'pending', 'cash_on_delivery', 'd', 'không muốn mua nữa', '2025-11-21 20:48:31', '2025-11-21 20:49:26'),
(15, 'ORD202511222335', 1, 'hfTjOs9k0YouJRNZuzLNFLsvJJKvlgJ9oYXm4Wz6', 'Super Admin', 'admin@library.com', '0583388961', 'g', 250000.00, 0.00, 0.00, 250000.00, 'cancelled', 'pending', 'cash_on_delivery', 'g', 'không muốn mua nữa', '2025-11-21 20:49:11', '2025-11-21 20:50:03'),
(16, 'ORD202511228705', 1, 'hfTjOs9k0YouJRNZuzLNFLsvJJKvlgJ9oYXm4Wz6', 'Super Admin', 'admin@library.com', '0583388961', 'd', 455000.00, 0.00, 0.00, 455000.00, 'delivered', 'paid', 'cash_on_delivery', 'd', NULL, '2025-11-21 20:50:35', '2025-11-21 20:51:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `purchasable_book_id` bigint UNSIGNED NOT NULL,
  `book_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `purchasable_book_id`, `book_title`, `book_author`, `price`, `quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 17, 'Dám làm giàu', 'Adam Khoo', 210.00, 1, 210.00, '2025-11-05 09:57:31', '2025-11-05 09:57:31'),
(2, 1, 20, 'Giáo trình Revit Structure theo kết cấu', 'TS. Nguyễn Văn A', 31500.00, 1, 31500.00, '2025-11-05 09:57:31', '2025-11-05 09:57:31'),
(3, 1, 23, 'Giảm \"xóc\"... Hành trình cuộc đời', 'Lê Quốc Vinh', 420.00, 1, 420.00, '2025-11-05 09:57:31', '2025-11-05 09:57:31'),
(4, 2, 7, 'Kinh Tế Học Vi Mô', 'GS. Vũ Thành Tự Anh', 190000.00, 1, 190000.00, '2025-11-05 10:31:59', '2025-11-05 10:31:59'),
(5, 3, 20, 'Giáo trình Revit Structure theo kết cấu', 'TS. Nguyễn Văn A', 31500.00, 1, 31500.00, '2025-11-05 10:52:33', '2025-11-05 10:52:33'),
(6, 4, 24, 'Et deserunt nostrum adipisci.', 'Lê Trang Thùy', 61530.00, 1, 61530.00, '2025-11-05 20:41:07', '2025-11-05 20:41:07'),
(7, 5, 25, 'Dolore ut ut id.', 'Ông. Vừ Sĩ Nhu', 95760.00, 1, 95760.00, '2025-11-07 08:36:58', '2025-11-07 08:36:58'),
(8, 6, 17, 'Dám làm giàu', 'Adam Khoo', 210.00, 1, 210.00, '2025-11-08 06:40:37', '2025-11-08 06:40:37'),
(9, 6, 25, 'Dolore ut ut id.', 'Ông. Vừ Sĩ Nhu', 95760.00, 1, 95760.00, '2025-11-08 06:40:37', '2025-11-08 06:40:37'),
(10, 6, 30, 'Doloremque molestias.', 'Mã Thoa', 80220.00, 1, 80220.00, '2025-11-08 06:40:37', '2025-11-08 06:40:37'),
(11, 7, 13, 'Dolor eaque mollitia.', 'Lâm Trân', 54390.00, 1, 54390.00, '2025-11-08 07:00:39', '2025-11-08 07:00:39'),
(12, 7, 24, 'Et deserunt nostrum adipisci.', 'Lê Trang Thùy', 61530.00, 1, 61530.00, '2025-11-08 07:00:39', '2025-11-08 07:00:39'),
(13, 7, 25, 'Dolore ut ut id.', 'Ông. Vừ Sĩ Nhu', 95760.00, 2, 191520.00, '2025-11-08 07:00:39', '2025-11-08 07:00:39'),
(14, 8, 31, 'Voluptas temporibus occaecati in.', 'Em. Cung Hữu Vương', 444000.00, 1, 444000.00, '2025-11-12 08:38:52', '2025-11-12 08:38:52'),
(15, 8, 32, 'Kinh tế học', 'Phạm Thị D', 2334000.00, 1, 2334000.00, '2025-11-12 08:38:52', '2025-11-12 08:38:52'),
(16, 9, 33, 'Hướng dẫn đồ án tổ chức và quản lý', 'ThS. Trần Thị B', 120000.00, 1, 120000.00, '2025-11-12 09:25:46', '2025-11-12 09:25:46'),
(17, 9, 34, 'Văn học Việt Nam', 'Trần Thị B', 6000000.00, 1, 6000000.00, '2025-11-12 09:25:46', '2025-11-12 09:25:46'),
(18, 10, 34, 'Văn học Việt Nam', 'Trần Thị B', 6000000.00, 1, 6000000.00, '2025-11-12 09:33:36', '2025-11-12 09:33:36'),
(19, 11, 34, 'Văn học Việt Nam', 'Trần Thị B', 6000000.00, 1, 6000000.00, '2025-11-13 14:33:06', '2025-11-13 14:33:06'),
(20, 12, 19, 'Giáo trình Revit Structure theo kết cấu', 'TS. Nguyễn Văn A', 150000.00, 1, 150000.00, '2025-11-21 15:30:04', '2025-11-21 15:30:04'),
(21, 12, 35, 'sách C', 'Nguyễn Văn A', 300000.00, 1, 300000.00, '2025-11-21 15:30:04', '2025-11-21 15:30:04'),
(22, 13, 36, 'sách  loại 2', 'Nguyễn Văn A', 100000.00, 1, 100000.00, '2025-11-21 20:43:42', '2025-11-21 20:43:42'),
(23, 14, 37, 'Lập trình Laravel từ A-Z', 'Trần Thị B', 400000.00, 1, 400000.00, '2025-11-21 20:48:31', '2025-11-21 20:48:31'),
(24, 15, 5, 'Khoa Học Dữ Liệu', 'PGS. Phạm Minh Tuấn', 250000.00, 1, 250000.00, '2025-11-21 20:49:11', '2025-11-21 20:49:11'),
(25, 16, 38, 'Lập trình PHP', 'Hoàng Văn E', 455000.00, 1, 455000.00, '2025-11-21 20:50:35', '2025-11-21 20:50:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-dashboard', 'web', '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(2, 'view-books', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(3, 'create-books', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(4, 'edit-books', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(5, 'delete-books', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(6, 'view-categories', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(7, 'create-categories', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(8, 'edit-categories', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(9, 'delete-categories', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(10, 'view-readers', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(11, 'create-readers', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(12, 'edit-readers', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(13, 'delete-readers', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(14, 'view-borrows', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(15, 'create-borrows', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(16, 'edit-borrows', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(17, 'delete-borrows', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(18, 'return-books', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(19, 'view-reservations', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(20, 'create-reservations', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(21, 'edit-reservations', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(22, 'delete-reservations', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(23, 'confirm-reservations', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(24, 'view-reviews', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(25, 'create-reviews', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(26, 'edit-reviews', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(27, 'delete-reviews', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(28, 'approve-reviews', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(29, 'view-fines', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(30, 'create-fines', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(31, 'edit-fines', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(32, 'delete-fines', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(33, 'waive-fines', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(34, 'view-reports', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(35, 'export-reports', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(36, 'view-notifications', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(37, 'send-notifications', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(38, 'manage-templates', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(39, 'view-users', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(40, 'create-users', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(41, 'edit-users', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(42, 'delete-users', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(43, 'manage-roles', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(44, 'manage-permissions', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(45, 'view-settings', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(46, 'edit-settings', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(47, 'view-inventory', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(48, 'create-inventory', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(49, 'edit-inventory', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(50, 'delete-inventory', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(51, 'import-inventory', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(52, 'export-inventory', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(53, 'manage-settings', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(54, 'view-backup', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(55, 'create-backup', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(56, 'restore-backup', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(57, 'delete-backup', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(58, 'manage-backup', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(59, 'view-logs', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(60, 'manage-logs', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(61, 'manage-bulk-operations', 'web', '2025-11-21 19:09:29', '2025-11-21 19:09:29'),
(62, 'view-email-marketing', 'web', '2025-11-21 19:09:30', '2025-11-21 19:09:30'),
(63, 'create-email-marketing', 'web', '2025-11-21 19:09:30', '2025-11-21 19:09:30'),
(64, 'edit-email-marketing', 'web', '2025-11-21 19:09:30', '2025-11-21 19:09:30'),
(65, 'send-email-marketing', 'web', '2025-11-21 19:09:30', '2025-11-21 19:09:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `publishers`
--

CREATE TABLE `publishers` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_nha_xuat_ban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `so_dien_thoai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ngay_thanh_lap` date DEFAULT NULL,
  `trang_thai` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `publishers`
--

INSERT INTO `publishers` (`id`, `ten_nha_xuat_ban`, `dia_chi`, `so_dien_thoai`, `email`, `website`, `mo_ta`, `ngay_thanh_lap`, `trang_thai`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Nhà xuất bản Giáo dục Việt Nam', '81 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội', '024 3822 1234', 'info@nxbgd.vn', 'https://nxbgd.vn', 'Nhà xuất bản chuyên về sách giáo khoa và tài liệu giáo dục', '1957-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(2, 'Nhà xuất bản Trẻ', '161B Lý Chính Thắng, Phường 7, Quận 3, TP.HCM', '028 3930 1234', 'info@nxbtre.com.vn', 'https://nxbtre.com.vn', 'Nhà xuất bản chuyên về sách văn học và sách thiếu nhi', '1981-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(3, 'Nhà xuất bản Kim Đồng', '55 Quang Trung, Hai Bà Trưng, Hà Nội', '024 3822 5678', 'info@nxbkimdong.com.vn', 'https://nxbkimdong.com.vn', 'Nhà xuất bản chuyên về sách thiếu nhi và thanh thiếu niên', '1957-06-17', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(4, 'Nhà xuất bản Thế giới', '46 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội', '024 3822 9012', 'info@nxbthegioi.com.vn', 'https://nxbthegioi.com.vn', 'Nhà xuất bản chuyên về sách khoa học và công nghệ', '1957-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(5, 'Nhà xuất bản Đại học Quốc gia Hà Nội', '144 Xuân Thủy, Cầu Giấy, Hà Nội', '024 3754 1234', 'info@nxb.vnu.edu.vn', 'https://nxb.vnu.edu.vn', 'Nhà xuất bản chuyên về sách đại học và nghiên cứu khoa học', '1956-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(6, 'Nhà xuất bản Đại học Quốc gia TP.HCM', '268 Lý Thường Kiệt, Quận 10, TP.HCM', '028 3865 1234', 'info@nxb.vnuhcm.edu.vn', 'https://nxb.vnuhcm.edu.vn', 'Nhà xuất bản chuyên về sách đại học và nghiên cứu khoa học', '1995-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(7, 'Nhà xuất bản Chính trị Quốc gia Sự thật', '24 Quang Trung, Hoàn Kiếm, Hà Nội', '024 3822 3456', 'info@nxbctqg.vn', 'https://nxbctqg.vn', 'Nhà xuất bản chuyên về sách chính trị và lý luận', '1945-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31'),
(8, 'Nhà xuất bản Lao động', '175 Giảng Võ, Đống Đa, Hà Nội', '024 3851 1234', 'info@nxblaodong.vn', 'https://nxblaodong.vn', 'Nhà xuất bản chuyên về sách lao động và xã hội', '1954-01-01', 'active', NULL, '2025-10-26 06:13:31', '2025-10-26 06:13:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchasable_books`
--

CREATE TABLE `purchasable_books` (
  `id` bigint UNSIGNED NOT NULL,
  `ten_sach` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tac_gia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hinh_anh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `nha_xuat_ban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nam_xuat_ban` int DEFAULT NULL,
  `isbn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_trang` int DEFAULT NULL,
  `ngon_ngu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tiếng Việt',
  `dinh_dang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PDF',
  `kich_thuoc_file` int DEFAULT NULL,
  `trang_thai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `so_luong_ban` int NOT NULL DEFAULT '0',
  `so_luong_ton` int NOT NULL DEFAULT '999' COMMENT 'Số lượng tồn kho',
  `danh_gia_trung_binh` decimal(3,2) NOT NULL DEFAULT '0.00',
  `so_luot_xem` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `purchasable_books`
--

INSERT INTO `purchasable_books` (`id`, `ten_sach`, `tac_gia`, `mo_ta`, `hinh_anh`, `gia`, `nha_xuat_ban`, `nam_xuat_ban`, `isbn`, `so_trang`, `ngon_ngu`, `dinh_dang`, `kich_thuoc_file`, `trang_thai`, `so_luong_ban`, `so_luong_ton`, `danh_gia_trung_binh`, `so_luot_xem`, `created_at`, `updated_at`) VALUES
(1, 'Lập Trình PHP Cơ Bản', 'Nguyễn Văn A', 'Cuốn sách hướng dẫn lập trình PHP từ cơ bản đến nâng cao, phù hợp cho người mới bắt đầu.', 'books/php-basic.jpg', 150000.00, 'NXB Giáo Dục', 2023, '978-604-0-12345-6', 350, 'Tiếng Việt', 'PDF', 2500, 'active', 45, 999, 0.00, 1200, '2025-10-26 06:13:37', '2025-11-20 17:29:14'),
(2, 'Truyện Kiều - Nguyễn Du', 'Nguyễn Du', 'Tác phẩm kinh điển của văn học Việt Nam, được dịch và chú thích chi tiết.', 'books/truyen-kieu.jpg', 80000.00, 'NXB Văn Học', 2022, '978-604-0-23456-7', 280, 'Tiếng Việt', 'EPUB', 1800, 'active', 120, 999, 4.80, 2500, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(3, 'Lịch Sử Việt Nam', 'GS. Trần Văn Giàu', 'Tổng hợp lịch sử Việt Nam từ thời kỳ dựng nước đến hiện đại.', 'books/vietnam-history.jpg', 200000.00, 'NXB Chính Trị Quốc Gia', 2023, '978-604-0-34567-8', 500, 'Tiếng Việt', 'PDF', 3200, 'active', 78, 999, 0.00, 1800, '2025-10-26 06:13:37', '2025-11-20 17:29:14'),
(4, 'Giáo Dục Thế Kỷ 21', 'TS. Lê Thị Lan', 'Phương pháp giáo dục hiện đại và xu hướng giáo dục trong thế kỷ 21.', 'books/education-21st.jpg', 180000.00, 'NXB Đại Học Quốc Gia', 2023, '978-604-0-45678-9', 400, 'Tiếng Việt', 'PDF', 2800, 'active', 32, 999, 0.00, 950, '2025-10-26 06:13:37', '2025-11-20 17:29:14'),
(5, 'Khoa Học Dữ Liệu', 'PGS. Phạm Minh Tuấn', 'Hướng dẫn phân tích dữ liệu và machine learning từ cơ bản đến nâng cao.', 'books/data-science.jpg', 250000.00, 'NXB Khoa Học Kỹ Thuật', 2024, '978-604-0-56789-0', 600, 'Tiếng Việt', 'PDF', 4500, 'active', 25, 5, 0.00, 1100, '2025-10-26 06:13:37', '2025-11-21 20:50:03'),
(6, 'Y Học Cơ Bản', 'BS. Nguyễn Thị Hoa', 'Kiến thức y học cơ bản cho sinh viên và những người quan tâm đến sức khỏe.', 'books/basic-medicine.jpg', 220000.00, 'NXB Y Học', 2023, '978-604-0-67890-1', 450, 'Tiếng Việt', 'PDF', 3800, 'active', 18, 999, 0.00, 800, '2025-10-26 06:13:37', '2025-11-20 17:29:14'),
(7, 'Kinh Tế Học Vi Mô', 'GS. Vũ Thành Tự Anh', 'Giáo trình kinh tế học vi mô dành cho sinh viên đại học và cao đẳng.', 'books/microeconomics.jpg', 190000.00, 'NXB Đại Học Kinh Tế', 2023, '978-604-0-78901-2', 420, 'Tiếng Việt', 'PDF', 2900, 'active', 56, 998, 0.00, 1300, '2025-10-26 06:13:37', '2025-11-20 17:29:14'),
(8, 'Tâm Lý Học Đại Cương', 'TS. Trần Thị Mai', 'Nhập môn tâm lý học với các khái niệm cơ bản và ứng dụng thực tế.', 'books/psychology.jpg', 160000.00, 'NXB Đại Học Sư Phạm', 2022, '978-604-0-89012-3', 380, 'Tiếng Việt', 'EPUB', 2200, 'active', 42, 999, 4.10, 1050, '2025-10-26 06:13:37', '2025-10-26 06:13:37'),
(9, 'Veniam quod in.', 'Bà. Thạch Đồng Thanh', 'Expedita esse voluptatem quia sed quia. Aspernatur doloremque mollitia ipsum minima. Sint eligendi temporibus sit illum. Atque laudantium veritatis qui. Rem maiores cum ut voluptas numquam cupiditate.', 'books/1762238312_2389.png', 106000.00, 'Nhà xuất bản Giáo dục Việt Nam', 1992, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-04 07:28:28', '2025-11-04 07:28:28'),
(10, 'Consequatur et incidunt ut.', 'Bác. Tạ Thanh Ân', 'Temporibus et corporis id delectus rem. Distinctio possimus ea explicabo ipsam enim molestiae. Eum exercitationem sunt ut corrupti quam pariatur velit.', 'books/1762238175_1402.jpg', 485000.00, 'Nhà xuất bản Chính trị Quốc gia Sự thật', 2010, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-04 07:32:54', '2025-11-04 07:32:54'),
(11, 'Dolore ut ut id.', 'Ông. Vừ Sĩ Nhu', 'Consequatur libero ipsa neque consequuntur. Soluta architecto molestias velit est. Molestias officia dolorem et dignissimos maiores veritatis. Atque dolorem suscipit esse ex sint qui.', 'books/1762238031_1249.jpg', 456000.00, 'Nhà xuất bản Lao động', 2014, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-04 07:33:18', '2025-11-04 07:33:18'),
(12, 'Dolor eaque mollitia.', 'Lâm Trân', 'Consequatur illum minus veritatis ut eaque tenetur ratione. Ex in quis dolores facilis. Molestiae ut et est explicabo laudantium fugit aut nihil. Sed dolor aut cum alias qui nesciunt.', 'books/1762237821_1249.jpg', 259000.00, 'Nhà xuất bản Lao động', 2003, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-04 07:33:30', '2025-11-04 07:33:30'),
(13, 'Dolor eaque mollitia.', 'Lâm Trân', 'Consequatur illum minus veritatis ut eaque tenetur ratione. Ex in quis dolores facilis. Molestiae ut et est explicabo laudantium fugit aut nihil. Sed dolor aut cum alias qui nesciunt.', 'books/1762237821_1249.jpg', 54390.00, 'Nhà xuất bản Lao động', 2003, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 1, 998, 0.00, 0, '2025-11-04 07:33:44', '2025-11-08 07:00:39'),
(14, 'Ipsa soluta quia.', 'Chị. Giao Thuần Thường', 'Sed numquam cum consequuntur ut tempore. Repellat sequi sapiente dolores eaque beatae. Qui nihil autem sed tenetur eaque aut voluptatem. Est aspernatur est hic optio temporibus.', 'books/1762237962_317.jpg', 458000.00, 'Nhà xuất bản Trẻ', 1992, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-04 07:34:15', '2025-11-04 07:34:15'),
(15, 'Ipsa soluta quia.', 'Chị. Giao Thuần Thường', 'Sed numquam cum consequuntur ut tempore. Repellat sequi sapiente dolores eaque beatae. Qui nihil autem sed tenetur eaque aut voluptatem. Est aspernatur est hic optio temporibus.', 'books/1762237962_317.jpg', 96180.00, 'Nhà xuất bản Trẻ', 1992, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 0, 999, 0.00, 0, '2025-11-04 07:34:20', '2025-11-04 07:34:20'),
(16, 'Dám làm giàu', 'Adam Khoo', 'Cuốn sách truyền cảm hứng về việc dám nghĩ lớn, dám hành động và dám theo đuổi giấc mơ làm giàu của mình.', 'books/1762238405_1249.jpg', 1000.00, NULL, 2020, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-04 07:43:41', '2025-11-04 07:43:41'),
(17, 'Dám làm giàu', 'Adam Khoo', 'Cuốn sách truyền cảm hứng về việc dám nghĩ lớn, dám hành động và dám theo đuổi giấc mơ làm giàu của mình.', 'books/1762238405_1249.jpg', 210.00, NULL, 2020, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 2, 998, 0.00, 0, '2025-11-04 07:43:50', '2025-11-21 15:43:50'),
(18, 'Veniam quod in.', 'Bà. Thạch Đồng Thanh', 'Expedita esse voluptatem quia sed quia. Aspernatur doloremque mollitia ipsum minima. Sint eligendi temporibus sit illum. Atque laudantium veritatis qui. Rem maiores cum ut voluptas numquam cupiditate.', 'books/1762238312_2389.png', 22260.00, 'Nhà xuất bản Giáo dục Việt Nam', 1992, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 0, 999, 0.00, 0, '2025-11-05 00:45:23', '2025-11-05 00:45:23'),
(19, 'Giáo trình Revit Structure theo kết cấu', 'TS. Nguyễn Văn A', 'Hướng dẫn sử dụng Revit Structure trong thiết kế kết cấu', 'books/1762238524_1249.jpg', 150000.00, NULL, 2023, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 0, 0.00, 0, '2025-11-05 08:33:58', '2025-11-21 15:30:04'),
(20, 'Giáo trình Revit Structure theo kết cấu', 'TS. Nguyễn Văn A', 'Hướng dẫn sử dụng Revit Structure trong thiết kế kết cấu', 'books/1762238524_1249.jpg', 31500.00, NULL, 2023, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 2, 998, 0.00, 0, '2025-11-05 08:34:02', '2025-11-08 07:12:12'),
(21, 'Tuổi Trẻ Đáng Giá Bao Nhiêu', 'Rosie Nguyễn', 'Câu chuyện về tuổi trẻ, ước mơ và cách tận dụng tuổi thanh xuân để xây dựng tương lai tươi sáng.', 'books/1762238471_2389.png', 0.00, NULL, 2018, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-05 08:34:39', '2025-11-05 08:34:39'),
(22, 'Giảm \"xóc\"... Hành trình cuộc đời', 'Lê Quốc Vinh', 'Học cách giảm thiểu những rung động tiêu cực trong cuộc sống và tạo dựng hành trình ổn định, hạnh phúc.', 'books/1762238375_1756.jpg', 2000.00, NULL, 2023, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-05 08:34:44', '2025-11-05 08:34:44'),
(23, 'Giảm \"xóc\"... Hành trình cuộc đời', 'Lê Quốc Vinh', 'Học cách giảm thiểu những rung động tiêu cực trong cuộc sống và tạo dựng hành trình ổn định, hạnh phúc.', 'books/1762238375_1756.jpg', 420.00, NULL, 2023, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 1, 998, 0.00, 0, '2025-11-05 08:34:48', '2025-11-05 09:57:31'),
(24, 'Et deserunt nostrum adipisci.', 'Lê Trang Thùy', 'Quis ut dolorem suscipit voluptates ex in at. Maiores ut atque provident est eveniet et et.', 'books/1762237201_2389.png', 61530.00, 'Nhà xuất bản Thế giới', 1992, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 1, 997, 0.00, 0, '2025-11-05 20:40:52', '2025-11-20 17:29:13'),
(25, 'Dolore ut ut id.', 'Ông. Vừ Sĩ Nhu', 'Consequatur libero ipsa neque consequuntur. Soluta architecto molestias velit est. Molestias officia dolorem et dignissimos maiores veritatis. Atque dolorem suscipit esse ex sint qui.', 'books/1762238031_1249.jpg', 95760.00, 'Nhà xuất bản Lao động', 2014, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 3, 996, 0.00, 0, '2025-11-07 08:36:10', '2025-11-21 15:43:50'),
(26, 'Et mollitia eos natus.', 'Kha Hồ Diễm', 'Ut reprehenderit officiis autem beatae quis culpa cumque eos. Voluptatem et omnis nemo quisquam voluptatum est. Nulla dicta laboriosam omnis occaecati omnis illum et ut.', 'books/1762237188_1249.jpg', 456000.00, 'Nhà xuất bản Đại học Quốc gia TP.HCM', 2024, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-07 10:10:47', '2025-11-07 10:10:47'),
(27, 'Et mollitia eos natus.', 'Kha Hồ Diễm', 'Ut reprehenderit officiis autem beatae quis culpa cumque eos. Voluptatem et omnis nemo quisquam voluptatum est. Nulla dicta laboriosam omnis occaecati omnis illum et ut.', 'books/1762237188_1249.jpg', 95760.00, 'Nhà xuất bản Đại học Quốc gia TP.HCM', 2024, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 0, 999, 0.00, 0, '2025-11-07 10:10:51', '2025-11-07 10:10:51'),
(28, 'Et deserunt nostrum adipisci.', 'Lê Trang Thùy', 'Quis ut dolorem suscipit voluptates ex in at. Maiores ut atque provident est eveniet et et.', 'books/1762237201_2389.png', 293000.00, 'Nhà xuất bản Thế giới', 1992, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-07 10:26:15', '2025-11-07 10:26:15'),
(29, 'Doloremque molestias.', 'Mã Thoa', 'Quae facere ducimus est dolores. Perferendis autem sit dolore eligendi rerum enim perspiciatis. Est est officia dicta consequuntur voluptatem deserunt rerum. Voluptatem nostrum repellat totam laudantium voluptatum quos voluptatem.', 'books/1762238325_1345.jpg', 382000.00, 'Nhà xuất bản Đại học Quốc gia Hà Nội', 2011, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 0, 0.00, 0, '2025-11-07 16:37:02', '2025-11-07 16:37:02'),
(30, 'Doloremque molestias.', 'Mã Thoa', 'Quae facere ducimus est dolores. Perferendis autem sit dolore eligendi rerum enim perspiciatis. Est est officia dicta consequuntur voluptatem deserunt rerum. Voluptatem nostrum repellat totam laudantium voluptatum quos voluptatem.', 'books/1762238325_1345.jpg', 80220.00, 'Nhà xuất bản Đại học Quốc gia Hà Nội', 2011, NULL, NULL, 'Tiếng Việt', 'PDF', NULL, 'active', 1, 999, 0.00, 0, '2025-11-07 16:37:06', '2025-11-21 15:43:50'),
(31, 'Voluptas temporibus occaecati in.', 'Em. Cung Hữu Vương', 'Sint magnam dicta et eum. Dolor aut ut temporibus magnam consequuntur. Dolore nostrum illo fugit impedit itaque sapiente quo. Fugit dolor aliquam natus minima quo beatae.', 'books/1762238064_1402.jpg', 444000.00, 'Nhà xuất bản Lao động', 2008, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 1, 0.00, 0, '2025-11-11 14:52:47', '2025-11-21 15:42:46'),
(32, 'Kinh tế học', 'Phạm Thị D', 'f', 'books/887e2de2-aa36-4aed-91c4-feffb02f0be9.jpg', 2334000.00, 'Nhà xuất bản Chính trị Quốc gia Sự thật', 2025, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 1, 0.00, 0, '2025-11-12 08:38:33', '2025-11-21 15:42:46'),
(33, 'Hướng dẫn đồ án tổ chức và quản lý', 'ThS. Trần Thị B', 'Tài liệu hướng dẫn thực hiện đồ án về tổ chức và quản lý xây dựng', 'books/1762238496_360.jpg', 120000.00, NULL, 2023, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 1, 0.00, 0, '2025-11-12 09:25:15', '2025-11-21 15:42:34'),
(34, 'Văn học Việt Nam', 'Trần Thị B', 'gg', 'books/04977b84-42b8-499e-80ef-8c3a2fd5020f.png', 6000000.00, 'Nhà xuất bản Giáo dục Việt Nam', 2025, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 6, 0.00, 0, '2025-11-12 09:25:27', '2025-11-21 15:42:34'),
(35, 'sách C', 'Nguyễn Văn A', NULL, NULL, 300000.00, 'Nhà xuất bản Giáo dục Việt Nam', 2025, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 4, 0.00, 0, '2025-11-15 14:07:14', '2025-11-21 15:30:04'),
(36, 'sách  loại 2', 'Nguyễn Văn A', NULL, 'books/b6c952d6-7b65-49e8-9d12-6bb0e77614a7.jpg', 100000.00, 'Nhà xuất bản Giáo dục Việt Nam', 2025, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 5, 0.00, 0, '2025-11-21 20:43:28', '2025-11-21 20:44:25'),
(37, 'Lập trình Laravel từ A-Z', 'Trần Thị B', 'j', 'books/148acbde-d904-4905-8e2f-e217c1cee586.jpg', 400000.00, 'Nhà xuất bản Đại học Quốc gia TP.HCM', 2025, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 0, 1, 0.00, 0, '2025-11-21 20:48:14', '2025-11-21 20:49:26'),
(38, 'Lập trình PHP', 'Hoàng Văn E', 'gg', 'books/7fa6f3b2-8d72-43ec-8174-3bbb25ff62c5.jpg', 50000.00, 'Nhà xuất bản Lao động', 2025, NULL, NULL, 'Tiếng Việt', 'PAPER', NULL, 'active', 1, 4, 0.00, 0, '2025-11-21 20:50:24', '2025-12-02 15:16:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `readers`
--

CREATE TABLE `readers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ho_ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_dien_thoai` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_cccd` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_sinh` date NOT NULL,
  `gioi_tinh` enum('Nam','Nu','Khac') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_the_doc_gia` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ngay_cap_the` date NOT NULL,
  `ngay_het_han` date NOT NULL,
  `trang_thai` enum('Hoat dong','Tam khoa','Het han') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hoat dong',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `faculty_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `readers`
--

INSERT INTO `readers` (`id`, `user_id`, `ho_ten`, `email`, `so_dien_thoai`, `so_cccd`, `ngay_sinh`, `gioi_tinh`, `dia_chi`, `so_the_doc_gia`, `ngay_cap_the`, `ngay_het_han`, `trang_thai`, `created_at`, `updated_at`, `faculty_id`, `department_id`) VALUES
(17, 23, 'Người dùng', 'user@library.com', '0987654323', NULL, '2004-04-06', 'Nam', 'hà nội', 'RD3JQFPY', '2025-11-12', '2026-11-12', 'Hoat dong', '2025-11-12 09:02:46', '2025-11-12 09:02:46', NULL, NULL),
(18, 1, 'Super Admin', 'admin@library.com', '0987654323', NULL, '2004-06-04', 'Nam', 'hn', 'RDWYVCX9', '2025-11-13', '2026-11-13', 'Hoat dong', '2025-11-13 14:26:27', '2025-11-13 14:26:27', NULL, NULL),
(19, 25, 'hoang123', 'hoangproxz123@gmail.com', '0123456780', NULL, '2006-04-06', 'Nam', 'hà nội', 'RDHD7ET6', '2025-11-16', '2026-11-16', 'Hoat dong', '2025-11-16 11:04:55', '2025-11-16 11:04:55', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `book_id`, `user_id`, `rating`, `comment`, `title`, `status`, `is_verified`, `created_at`, `updated_at`) VALUES
(40, 16, 23, 4, 'Sint qui illo eveniet quia eum non. Quia magni nihil maiores illo explicabo.', NULL, 'pending', 0, '2025-07-15 09:49:51', '2025-10-26 06:13:45'),
(41, 63, 1, 5, 'Porro et aut voluptas doloremque dolores quis. Reprehenderit voluptas vitae officiis est deleniti voluptatem tempore. Sit nostrum in repudiandae nulla ut reiciendis sed.', NULL, 'pending', 0, '2025-02-06 21:22:02', '2025-10-26 06:13:45'),
(43, 6, 23, 1, 'Atque modi sit quos error in vitae. Voluptas et qui dolores cupiditate nesciunt fugit natus. Modi fugit sit doloribus consequuntur.', NULL, 'pending', 0, '2025-02-16 20:08:48', '2025-10-26 06:13:45'),
(46, 46, 1, 2, 'Sit vitae et omnis explicabo similique molestias. Omnis asperiores voluptatem fugit quae repellat. Quam voluptatibus nihil quia voluptatem deserunt consectetur ea incidunt.', NULL, 'pending', 0, '2025-05-21 19:56:32', '2025-10-26 06:13:45'),
(47, 37, 23, 4, 'Cumque earum perferendis ipsum dolorem tempora explicabo at. Velit voluptas dolorem accusamus illo eius. Reiciendis id aut recusandae unde illum sint.', NULL, 'pending', 0, '2025-09-27 18:17:14', '2025-10-26 06:13:45'),
(50, 49, 23, 1, 'Tempora deleniti pariatur perferendis inventore. Maxime id sed dolores eius similique. Ipsam cupiditate tenetur vitae iure unde qui exercitationem.', NULL, 'pending', 0, '2025-08-25 06:44:34', '2025-10-26 06:13:46'),
(55, 48, 1, 3, 'Esse esse eligendi molestias minima et dicta natus neque. Perferendis aut reprehenderit et dolores sint sit. Rerum sequi et quis et et.', NULL, 'pending', 0, '2024-12-23 23:53:26', '2025-10-26 06:13:46'),
(81, 30, 23, 2, 'Ea ducimus omnis distinctio quidem. Harum accusamus et ipsum velit.', NULL, 'pending', 0, '2025-09-30 15:09:57', '2025-10-26 06:13:46'),
(82, 50, 1, 1, 'Nostrum adipisci occaecati id similique. Provident et aut consequuntur voluptate sit sunt nihil. Velit aut et eveniet ut sit a sint.', NULL, 'pending', 0, '2025-06-01 13:59:09', '2025-10-26 06:13:46'),
(94, 44, 23, 2, 'Eveniet impedit voluptas ratione officia et aut dignissimos. Consequatur magnam optio voluptate nobis cum. Eius ut laborum sed est eum.', NULL, 'pending', 0, '2025-04-12 22:01:56', '2025-10-26 06:13:46'),
(100, 59, 23, 5, 'Illum voluptatem qui molestiae debitis ullam. Laudantium quis fuga et fugiat dicta et.', NULL, 'pending', 0, '2025-07-29 08:22:11', '2025-10-26 06:13:46'),
(106, 44, 1, 4, 'Modi repudiandae ut veniam. Iste consequatur qui ea. Dolore sit veritatis sint incidunt sint quo ut.', NULL, 'pending', 0, '2025-09-24 05:23:35', '2025-10-26 06:13:46'),
(109, 2, 23, 1, 'Pariatur et doloribus pariatur et temporibus. Eos eveniet repellat minima quia aut numquam ea nesciunt. Eligendi aspernatur ducimus ratione dolores at quam est cumque.', NULL, 'pending', 0, '2025-03-06 10:18:26', '2025-10-26 06:13:46'),
(116, 11, 1, 3, 'Aliquam asperiores delectus id qui culpa distinctio aut. Repellat velit voluptas quo corrupti earum et.', NULL, 'pending', 0, '2024-12-31 07:55:18', '2025-10-26 06:13:46'),
(121, 29, 23, 2, 'Dolores vel atque molestiae et dolorum ipsa ea. Et in quod corrupti ut aperiam tempora. Eos nobis sapiente deserunt ut rerum.', NULL, 'pending', 0, '2025-02-01 19:21:29', '2025-10-26 06:13:47'),
(138, 3, 1, 5, 'Vero ratione earum a iste sed. Autem consequatur consequuntur quibusdam eos fuga. In sed unde amet.', NULL, 'pending', 0, '2025-02-02 20:50:30', '2025-10-26 06:13:47'),
(148, 57, 23, 3, 'Qui voluptatem ducimus nihil vitae distinctio. Et eaque ut officiis maiores sed.', NULL, 'pending', 0, '2025-07-03 14:33:13', '2025-10-26 06:13:47'),
(162, 24, 1, 2, 'Reprehenderit excepturi non laborum cum. Nemo consequatur alias atque non. Consequatur facere eligendi inventore.', NULL, 'pending', 0, '2025-05-20 02:28:18', '2025-10-26 06:13:47'),
(182, 60, 23, 4, 'Rerum incidunt rerum aperiam error culpa molestiae veniam. Rerum enim voluptate aut tenetur. Sapiente repellendus autem similique officiis quam soluta praesentium.', NULL, 'pending', 0, '2025-06-14 20:50:44', '2025-10-26 06:13:48'),
(211, 40, 23, 5, 'Dignissimos suscipit maxime quis nostrum est incidunt ipsam. Dolore est odio alias ut non.', NULL, 'pending', 0, '2024-11-17 17:42:25', '2025-10-26 06:13:48'),
(223, 22, 23, 3, 'Assumenda saepe officia qui et doloremque sequi aut eligendi. Sint ea qui ex incidunt autem saepe hic. Aperiam ut quia ex voluptate iusto vel.', NULL, 'pending', 0, '2024-11-01 00:00:02', '2025-10-26 06:13:48'),
(227, 42, 1, 1, 'Ut sequi consequatur excepturi repudiandae quo laudantium. Ea harum ad quas cumque consectetur et nihil.', NULL, 'pending', 0, '2025-02-24 21:36:35', '2025-10-26 06:13:48'),
(228, 7, 23, 5, 'Rerum delectus omnis architecto eaque architecto. Id labore nemo architecto natus officia.', NULL, 'pending', 0, '2025-08-07 20:46:02', '2025-10-26 06:13:48'),
(264, 76, 23, 5, '', NULL, 'pending', 1, '2025-11-04 06:48:21', '2025-11-04 06:48:21'),
(265, 75, 1, 5, '', NULL, 'pending', 1, '2025-11-05 00:44:11', '2025-11-05 00:44:11'),
(266, 23, 1, 5, '', NULL, 'pending', 1, '2025-11-07 16:36:50', '2025-11-07 16:36:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(2, 'staff', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38'),
(3, 'user', 'web', '2025-10-26 06:13:38', '2025-10-26 06:13:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(6, 2),
(7, 2),
(8, 2),
(10, 2),
(11, 2),
(12, 2),
(14, 2),
(15, 2),
(16, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(23, 2),
(24, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(34, 2),
(36, 2),
(37, 2),
(47, 2),
(48, 2),
(49, 2),
(2, 3),
(6, 3),
(19, 3),
(20, 3),
(25, 3),
(36, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `search_logs`
--

CREATE TABLE `search_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `query` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filters` json DEFAULT NULL,
  `results_count` int NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `search_logs`
--

INSERT INTO `search_logs` (`id`, `query`, `type`, `filters`, `results_count`, `user_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'khoa học', 'books', NULL, 28, NULL, '182.10.216.38', 'Opera/9.14 (X11; Linux i686; en-US) Presto/2.12.209 Version/12.00', '2025-09-02 20:17:26', '2025-10-26 06:13:49'),
(2, 'sinh học', 'readers', NULL, 48, NULL, '121.247.44.240', 'Mozilla/5.0 (Windows; U; Windows 95) AppleWebKit/534.20.2 (KHTML, like Gecko) Version/4.1 Safari/534.20.2', '2025-10-02 05:16:43', '2025-10-26 06:13:49'),
(3, 'hóa học', 'borrows', NULL, 45, NULL, '132.213.126.9', 'Opera/8.17 (X11; Linux x86_64; sl-SI) Presto/2.11.259 Version/11.00', '2025-07-19 03:37:28', '2025-10-26 06:13:49'),
(4, 'sách', 'fines', NULL, 20, NULL, '107.102.211.164', 'Mozilla/5.0 (Windows; U; Windows NT 5.1) AppleWebKit/532.13.5 (KHTML, like Gecko) Version/4.1 Safari/532.13.5', '2025-10-08 20:12:35', '2025-10-26 06:13:49'),
(5, 'triết học', 'fines', NULL, 22, NULL, '100.170.52.200', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20141226 Firefox/35.0', '2025-08-01 01:38:40', '2025-10-26 06:13:49'),
(6, 'trả sách', 'fines', NULL, 48, NULL, '93.109.174.67', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7) AppleWebKit/5362 (KHTML, like Gecko) Chrome/38.0.829.0 Mobile Safari/5362', '2025-09-11 06:16:19', '2025-10-26 06:13:49'),
(7, 'triết học', 'borrows', '[\"category\", \"status\", \"year\"]', 47, NULL, '17.83.38.22', 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20240303 Firefox/35.0', '2025-07-06 11:23:02', '2025-10-26 06:13:49'),
(8, 'Hoàng Văn E', 'books', '[\"category\"]', 40, NULL, '19.87.149.30', 'Mozilla/5.0 (Windows; U; Windows NT 4.0) AppleWebKit/535.48.2 (KHTML, like Gecko) Version/5.0 Safari/535.48.2', '2025-07-25 06:55:12', '2025-10-26 06:13:49'),
(9, 'Trần Thị B', 'fines', NULL, 32, NULL, '4.174.161.238', 'Mozilla/5.0 (Windows CE) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/99.0.4678.56 Safari/535.1 Edg/99.01041.93', '2025-05-17 11:37:01', '2025-10-26 06:13:49'),
(10, 'vật lý', 'books', NULL, 23, NULL, '208.22.216.95', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/536.1 (KHTML, like Gecko) Chrome/82.0.4587.68 Safari/536.1 EdgA/82.01041.61', '2025-10-16 21:59:58', '2025-10-26 06:13:49'),
(11, 'mượn sách', 'fines', NULL, 40, NULL, '202.97.46.107', 'Mozilla/5.0 (Windows; U; Windows NT 4.0) AppleWebKit/534.30.7 (KHTML, like Gecko) Version/4.0.1 Safari/534.30.7', '2025-06-15 17:59:41', '2025-10-26 06:13:49'),
(12, 'đặt trước', 'fines', NULL, 21, NULL, '253.101.63.103', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_7 rv:3.0) Gecko/20140925 Firefox/37.0', '2025-09-06 12:52:31', '2025-10-26 06:13:49'),
(13, 'gia hạn', 'books', NULL, 20, NULL, '194.240.166.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_1 like Mac OS X; sl-SI) AppleWebKit/533.8.3 (KHTML, like Gecko) Version/3.0.5 Mobile/8B116 Safari/6533.8.3', '2025-10-19 23:18:04', '2025-10-26 06:13:49'),
(14, 'văn học', 'books', '[\"category\"]', 24, NULL, '16.44.176.2', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.2; Trident/5.0)', '2025-10-01 05:18:57', '2025-10-26 06:13:49'),
(15, 'quá hạn', 'borrows', NULL, 29, NULL, '211.48.29.229', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.0 (KHTML, like Gecko) Chrome/97.0.4663.12 Safari/536.0 EdgA/97.01054.99', '2025-05-31 17:09:36', '2025-10-26 06:13:49'),
(16, 'Phạm Thị D', 'borrows', NULL, 50, NULL, '221.2.178.184', 'Mozilla/5.0 (Windows CE; sl-SI; rv:1.9.1.20) Gecko/20120224 Firefox/35.0', '2025-07-11 21:03:54', '2025-10-26 06:13:49'),
(17, 'gia hạn', 'readers', NULL, 31, NULL, '80.86.18.246', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows 95; Trident/5.0)', '2025-05-08 05:26:09', '2025-10-26 06:13:49'),
(18, 'phạt', 'readers', NULL, 4, NULL, '77.8.18.100', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows 98; Trident/5.1)', '2025-09-04 00:56:07', '2025-10-26 06:13:49'),
(19, 'đặt trước', 'fines', NULL, 32, NULL, '72.173.160.135', 'Mozilla/5.0 (Windows 98; sl-SI; rv:1.9.2.20) Gecko/20110228 Firefox/37.0', '2025-07-12 03:01:51', '2025-10-26 06:13:49'),
(20, 'văn học', 'books', NULL, 7, NULL, '228.236.128.72', 'Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20250422 Firefox/37.0', '2025-10-22 13:04:44', '2025-10-26 06:13:49'),
(21, 'Nguyễn Văn A', 'borrows', NULL, 11, NULL, '73.254.14.191', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_2) AppleWebKit/5362 (KHTML, like Gecko) Chrome/38.0.890.0 Mobile Safari/5362', '2025-05-29 13:50:31', '2025-10-26 06:13:49'),
(22, 'vật lý', 'books', NULL, 24, NULL, '244.127.117.54', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows CE; Trident/3.0)', '2025-06-13 12:07:49', '2025-10-26 06:13:49'),
(23, 'Nguyễn Văn A', 'books', '[\"category\", \"status\"]', 11, NULL, '200.112.234.60', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_7 rv:5.0; sl-SI) AppleWebKit/534.24.4 (KHTML, like Gecko) Version/5.0 Safari/534.24.4', '2025-09-24 00:41:32', '2025-10-26 06:13:49'),
(24, 'chính trị', 'readers', '[\"year\", \"category\"]', 27, NULL, '10.125.253.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0_2 like Mac OS X; sl-SI) AppleWebKit/534.19.6 (KHTML, like Gecko) Version/4.0.5 Mobile/8B114 Safari/6534.19.6', '2025-06-30 02:07:41', '2025-10-26 06:13:49'),
(25, 'lịch sử', 'books', '[\"category\"]', 39, NULL, '73.172.87.243', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_7) AppleWebKit/5362 (KHTML, like Gecko) Chrome/39.0.800.0 Mobile Safari/5362', '2025-05-13 08:47:13', '2025-10-26 06:13:49'),
(26, 'toán học', 'fines', NULL, 34, NULL, '199.203.66.2', 'Mozilla/5.0 (Windows 95) AppleWebKit/533.1 (KHTML, like Gecko) Chrome/79.0.4502.99 Safari/533.1 Edg/79.01142.10', '2025-08-24 08:36:12', '2025-10-26 06:13:49'),
(27, 'chính trị', 'borrows', '[\"category\", \"year\", \"status\"]', 35, NULL, '205.68.136.184', 'Opera/9.38 (X11; Linux x86_64; en-US) Presto/2.12.335 Version/11.00', '2025-08-04 05:08:05', '2025-10-26 06:13:49'),
(28, 'phạt', 'books', NULL, 45, NULL, '81.155.231.162', 'Opera/9.21 (Windows NT 5.01; en-US) Presto/2.10.223 Version/10.00', '2025-10-05 01:06:01', '2025-10-26 06:13:49'),
(29, 'hóa học', 'readers', '[\"year\", \"status\", \"category\"]', 48, NULL, '72.88.80.105', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_7 rv:5.0) Gecko/20130215 Firefox/37.0', '2025-05-04 16:28:29', '2025-10-26 06:13:49'),
(30, 'Trần Thị B', 'borrows', '[\"year\", \"status\"]', 6, NULL, '167.242.102.245', 'Mozilla/5.0 (Windows; U; Windows CE) AppleWebKit/532.11.4 (KHTML, like Gecko) Version/5.1 Safari/532.11.4', '2025-10-05 03:49:11', '2025-10-26 06:13:49'),
(31, 'quá hạn', 'readers', NULL, 34, 23, '146.130.23.108', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_6 rv:5.0) Gecko/20191017 Firefox/36.0', '2025-04-27 06:05:27', '2025-10-26 06:13:49'),
(32, 'lịch sử', 'books', NULL, 30, NULL, '154.130.2.218', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.1; Trident/5.0)', '2025-08-22 00:53:50', '2025-10-26 06:13:49'),
(33, 'chính trị', 'borrows', NULL, 36, NULL, '207.254.246.177', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.0; Trident/4.0)', '2025-06-21 14:22:39', '2025-10-26 06:13:49'),
(34, 'Nguyễn Văn A', 'fines', NULL, 12, NULL, '234.64.36.114', 'Opera/9.99 (Windows NT 6.1; en-US) Presto/2.10.216 Version/11.00', '2025-09-11 22:18:47', '2025-10-26 06:13:49'),
(35, 'sách', 'borrows', NULL, 41, NULL, '219.195.140.88', 'Mozilla/5.0 (X11; Linux x86_64; rv:7.0) Gecko/20200920 Firefox/36.0', '2025-10-26 00:51:12', '2025-10-26 06:13:49'),
(36, 'triết học', 'borrows', '[\"year\"]', 29, NULL, '108.100.240.132', 'Mozilla/5.0 (Windows 95; sl-SI; rv:1.9.1.20) Gecko/20180828 Firefox/35.0', '2025-09-29 20:20:29', '2025-10-26 06:13:49'),
(37, 'tiểu thuyết', 'readers', '[\"year\", \"status\", \"category\"]', 17, NULL, '162.200.51.84', 'Opera/8.49 (X11; Linux x86_64; sl-SI) Presto/2.12.314 Version/11.00', '2025-07-24 13:55:49', '2025-10-26 06:13:49'),
(38, 'kinh tế', 'books', '[\"year\", \"category\", \"status\"]', 7, NULL, '5.235.79.121', 'Mozilla/5.0 (Windows; U; Windows NT 5.1) AppleWebKit/531.28.3 (KHTML, like Gecko) Version/5.0.4 Safari/531.28.3', '2025-08-27 03:05:09', '2025-10-26 06:13:49'),
(39, 'sinh học', 'readers', NULL, 38, NULL, '106.23.77.188', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows 98; Trident/4.1)', '2025-10-07 18:47:09', '2025-10-26 06:13:49'),
(40, 'quá hạn', 'borrows', NULL, 17, NULL, '189.239.29.75', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4 rv:5.0; sl-SI) AppleWebKit/534.18.6 (KHTML, like Gecko) Version/4.0.1 Safari/534.18.6', '2025-06-21 14:33:19', '2025-10-26 06:13:49'),
(41, 'đặt trước', 'borrows', NULL, 16, NULL, '203.115.73.58', 'Mozilla/5.0 (Windows NT 5.1; sl-SI; rv:1.9.1.20) Gecko/20170312 Firefox/37.0', '2025-06-07 18:20:26', '2025-10-26 06:13:49'),
(42, 'đặt trước', 'books', NULL, 0, NULL, '97.168.154.91', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_2_1 like Mac OS X; nl-NL) AppleWebKit/532.14.2 (KHTML, like Gecko) Version/3.0.5 Mobile/8B117 Safari/6532.14.2', '2025-07-07 11:22:17', '2025-10-26 06:13:49'),
(43, 'sinh học', 'books', '[\"category\", \"status\"]', 25, NULL, '146.66.118.202', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/5342 (KHTML, like Gecko) Chrome/37.0.814.0 Mobile Safari/5342', '2025-07-21 20:56:55', '2025-10-26 06:13:49'),
(44, 'phạt', 'fines', '[\"category\", \"status\"]', 4, NULL, '194.253.217.68', 'Opera/8.30 (Windows CE; nl-NL) Presto/2.8.285 Version/12.00', '2025-05-14 01:39:38', '2025-10-26 06:13:49'),
(45, 'phạt', 'readers', NULL, 15, NULL, '248.228.138.42', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_2_1 like Mac OS X; en-US) AppleWebKit/534.46.3 (KHTML, like Gecko) Version/3.0.5 Mobile/8B118 Safari/6534.46.3', '2025-10-08 08:23:23', '2025-10-26 06:13:49'),
(46, 'mượn sách', 'borrows', NULL, 15, NULL, '233.185.239.164', 'Opera/9.96 (Windows 95; en-US) Presto/2.10.338 Version/11.00', '2025-07-26 04:06:11', '2025-10-26 06:13:49'),
(47, 'gia hạn', 'readers', NULL, 13, NULL, '195.226.237.110', 'Mozilla/5.0 (Windows NT 4.0) AppleWebKit/5340 (KHTML, like Gecko) Chrome/39.0.887.0 Mobile Safari/5340', '2025-06-28 05:40:37', '2025-10-26 06:13:49'),
(48, 'văn học', 'borrows', '[\"category\", \"status\", \"year\"]', 42, NULL, '54.90.25.241', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_8 rv:4.0) Gecko/20190810 Firefox/36.0', '2025-06-13 09:25:08', '2025-10-26 06:13:49'),
(49, 'Phạm Thị D', 'fines', NULL, 35, NULL, '116.38.158.49', 'Opera/9.15 (Windows CE; sl-SI) Presto/2.8.250 Version/10.00', '2025-06-10 19:58:18', '2025-10-26 06:13:49'),
(50, 'toán học', 'books', NULL, 11, NULL, '8.163.243.139', 'Opera/9.79 (X11; Linux i686; nl-NL) Presto/2.11.286 Version/12.00', '2025-07-22 01:37:23', '2025-10-26 06:13:49'),
(51, 'phạt', 'fines', '[\"category\", \"status\"]', 15, NULL, '17.177.56.226', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_5 rv:6.0) Gecko/20221224 Firefox/37.0', '2025-09-24 10:28:22', '2025-10-26 06:13:49'),
(52, 'toán học', 'readers', '[\"status\", \"category\", \"year\"]', 36, NULL, '89.47.116.65', 'Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20210419 Firefox/35.0', '2025-09-05 08:22:36', '2025-10-26 06:13:49'),
(53, 'hóa học', 'readers', NULL, 42, NULL, '21.230.138.110', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/533.1 (KHTML, like Gecko) Version/15.0 EdgiOS/93.01035.9 Mobile/15E148 Safari/533.1', '2025-09-05 03:19:05', '2025-10-26 06:13:49'),
(54, 'Lê Văn C', 'books', NULL, 36, NULL, '165.14.11.20', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_0) AppleWebKit/5331 (KHTML, like Gecko) Chrome/36.0.824.0 Mobile Safari/5331', '2025-08-21 11:24:23', '2025-10-26 06:13:49'),
(55, 'văn học', 'readers', NULL, 48, NULL, '242.36.127.164', 'Mozilla/5.0 (Windows 98; Win 9x 4.90; sl-SI; rv:1.9.0.20) Gecko/20180915 Firefox/37.0', '2025-07-10 10:43:22', '2025-10-26 06:13:49'),
(56, 'lịch sử', 'readers', NULL, 16, NULL, '250.181.32.173', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_7) AppleWebKit/5361 (KHTML, like Gecko) Chrome/37.0.827.0 Mobile Safari/5361', '2025-10-14 16:09:06', '2025-10-26 06:13:49'),
(57, 'gia hạn', 'readers', '[\"status\"]', 19, NULL, '95.35.99.116', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4) AppleWebKit/5361 (KHTML, like Gecko) Chrome/38.0.848.0 Mobile Safari/5361', '2025-05-06 15:32:41', '2025-10-26 06:13:49'),
(58, 'Lê Văn C', 'readers', NULL, 7, NULL, '113.202.65.230', 'Opera/8.21 (X11; Linux i686; sl-SI) Presto/2.11.285 Version/11.00', '2025-07-30 08:37:56', '2025-10-26 06:13:49'),
(59, 'gia hạn', 'books', NULL, 4, NULL, '35.135.176.253', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows 98; Trident/4.0)', '2025-05-03 05:50:22', '2025-10-26 06:13:49'),
(60, 'đặt trước', 'readers', '[\"year\"]', 40, NULL, '209.187.111.35', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_3) AppleWebKit/5330 (KHTML, like Gecko) Chrome/39.0.830.0 Mobile Safari/5330', '2025-05-30 00:13:36', '2025-10-26 06:13:49'),
(61, 'toán học', 'fines', '[\"status\", \"category\", \"year\"]', 0, NULL, '180.77.179.25', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.0 (KHTML, like Gecko) Chrome/83.0.4028.45 Safari/536.0 EdgA/83.01074.60', '2025-06-06 11:35:35', '2025-10-26 06:13:49'),
(62, 'phạt', 'readers', NULL, 7, NULL, '116.175.155.254', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/5310 (KHTML, like Gecko) Chrome/40.0.878.0 Mobile Safari/5310', '2025-08-29 22:09:27', '2025-10-26 06:13:49'),
(63, 'Phạm Thị D', 'borrows', '[\"category\"]', 42, NULL, '22.185.14.215', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/5312 (KHTML, like Gecko) Chrome/36.0.845.0 Mobile Safari/5312', '2025-08-01 23:34:04', '2025-10-26 06:13:49'),
(64, 'văn học', 'fines', NULL, 29, NULL, '30.243.27.94', 'Mozilla/5.0 (Windows NT 6.2; sl-SI; rv:1.9.0.20) Gecko/20110426 Firefox/36.0', '2025-07-15 22:39:58', '2025-10-26 06:13:49'),
(65, 'phạt', 'books', NULL, 16, NULL, '112.81.57.71', 'Opera/9.12 (Windows 95; nl-NL) Presto/2.11.313 Version/10.00', '2025-05-28 17:49:52', '2025-10-26 06:13:49'),
(66, 'toán học', 'borrows', '[\"status\", \"category\", \"year\"]', 22, NULL, '167.116.232.210', 'Mozilla/5.0 (Windows 98; Win 9x 4.90) AppleWebKit/5320 (KHTML, like Gecko) Chrome/39.0.825.0 Mobile Safari/5320', '2025-05-30 00:39:26', '2025-10-26 06:13:49'),
(67, 'Trần Thị B', 'borrows', NULL, 41, NULL, '233.241.146.136', 'Opera/8.69 (X11; Linux x86_64; nl-NL) Presto/2.8.198 Version/11.00', '2025-06-18 01:21:11', '2025-10-26 06:13:49'),
(68, 'sách', 'fines', '[\"year\", \"category\", \"status\"]', 25, NULL, '206.95.17.65', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.2; Trident/4.1)', '2025-08-20 11:44:23', '2025-10-26 06:13:49'),
(69, 'chính trị', 'readers', NULL, 23, NULL, '139.103.23.114', 'Opera/9.47 (X11; Linux i686; en-US) Presto/2.12.311 Version/10.00', '2025-07-07 20:29:07', '2025-10-26 06:13:49'),
(70, 'sách', 'fines', NULL, 12, NULL, '218.189.220.92', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/3.1)', '2025-08-25 00:22:33', '2025-10-26 06:13:49'),
(71, 'Hoàng Văn E', 'borrows', NULL, 46, 23, '248.247.161.35', 'Mozilla/5.0 (Windows; U; Windows NT 5.1) AppleWebKit/534.44.4 (KHTML, like Gecko) Version/4.0 Safari/534.44.4', '2025-09-15 00:44:03', '2025-10-26 06:13:49'),
(72, 'tiểu thuyết', 'fines', NULL, 29, NULL, '241.84.183.193', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.1; Trident/3.0)', '2025-09-26 17:50:32', '2025-10-26 06:13:49'),
(73, 'Phạm Thị D', 'readers', NULL, 21, NULL, '93.0.34.61', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.01; Trident/4.1)', '2025-06-21 00:10:26', '2025-10-26 06:13:49'),
(74, 'Nguyễn Văn A', 'borrows', NULL, 28, NULL, '208.22.55.62', 'Mozilla/5.0 (Windows; U; Windows NT 4.0) AppleWebKit/535.45.5 (KHTML, like Gecko) Version/4.0.3 Safari/535.45.5', '2025-06-26 19:53:49', '2025-10-26 06:13:49'),
(75, 'mượn sách', 'readers', NULL, 24, NULL, '1.227.30.51', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows CE; Trident/5.0)', '2025-06-10 22:13:13', '2025-10-26 06:13:49'),
(76, 'hóa học', 'readers', NULL, 49, NULL, '193.196.123.215', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_0 rv:4.0) Gecko/20160907 Firefox/35.0', '2025-05-28 06:03:54', '2025-10-26 06:13:49'),
(77, 'toán học', 'fines', '[\"category\", \"year\"]', 12, NULL, '116.101.92.129', 'Mozilla/5.0 (Windows 95; en-US; rv:1.9.0.20) Gecko/20190317 Firefox/36.0', '2025-10-13 11:48:51', '2025-10-26 06:13:49'),
(78, 'Nguyễn Văn A', 'readers', NULL, 8, NULL, '101.75.87.62', 'Mozilla/5.0 (Windows NT 4.0) AppleWebKit/5351 (KHTML, like Gecko) Chrome/36.0.830.0 Mobile Safari/5351', '2025-07-08 17:21:23', '2025-10-26 06:13:49'),
(79, 'phạt', 'readers', NULL, 48, NULL, '77.111.21.232', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.2; Trident/3.1)', '2025-06-22 15:48:39', '2025-10-26 06:13:49'),
(80, 'văn học', 'readers', NULL, 44, NULL, '208.34.246.42', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows CE; Trident/3.1)', '2025-05-24 15:17:22', '2025-10-26 06:13:49'),
(81, 'công nghệ', 'readers', '[\"year\", \"category\", \"status\"]', 21, NULL, '3.185.241.108', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_7 rv:2.0; nl-NL) AppleWebKit/533.8.6 (KHTML, like Gecko) Version/4.1 Safari/533.8.6', '2025-07-22 18:45:45', '2025-10-26 06:13:50'),
(82, 'sinh học', 'readers', NULL, 6, NULL, '225.229.9.136', 'Mozilla/5.0 (Windows 98; nl-NL; rv:1.9.0.20) Gecko/20220924 Firefox/37.0', '2025-08-27 01:25:48', '2025-10-26 06:13:50'),
(83, 'văn học', 'fines', NULL, 20, NULL, '147.137.16.202', 'Opera/9.17 (Windows 98; en-US) Presto/2.11.216 Version/11.00', '2025-07-01 00:51:02', '2025-10-26 06:13:50'),
(84, 'khoa học', 'readers', NULL, 25, NULL, '10.52.68.33', 'Mozilla/5.0 (Windows; U; Windows 98; Win 9x 4.90) AppleWebKit/532.17.6 (KHTML, like Gecko) Version/4.0.2 Safari/532.17.6', '2025-09-27 09:21:06', '2025-10-26 06:13:50'),
(85, 'phạt', 'borrows', NULL, 44, NULL, '165.254.8.34', 'Mozilla/5.0 (Windows CE) AppleWebKit/534.0 (KHTML, like Gecko) Chrome/82.0.4810.96 Safari/534.0 Edg/82.01081.83', '2025-09-14 14:45:48', '2025-10-26 06:13:50'),
(86, 'lịch sử', 'books', '[\"status\", \"year\"]', 34, NULL, '75.139.101.9', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows CE; Trident/3.0)', '2025-07-26 20:43:30', '2025-10-26 06:13:50'),
(87, 'mượn sách', 'borrows', '[\"category\", \"year\", \"status\"]', 0, 1, '139.17.75.96', 'Mozilla/5.0 (Windows; U; Windows NT 5.0) AppleWebKit/535.13.5 (KHTML, like Gecko) Version/4.0.5 Safari/535.13.5', '2025-05-21 12:17:12', '2025-10-26 06:13:50'),
(88, 'Trần Thị B', 'readers', NULL, 41, NULL, '30.148.12.102', 'Mozilla/5.0 (iPad; CPU OS 7_0_1 like Mac OS X; sl-SI) AppleWebKit/534.10.3 (KHTML, like Gecko) Version/3.0.5 Mobile/8B117 Safari/6534.10.3', '2025-07-15 05:54:19', '2025-10-26 06:13:50'),
(89, 'chính trị', 'readers', NULL, 25, NULL, '40.166.71.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5322 (KHTML, like Gecko) Chrome/38.0.834.0 Mobile Safari/5322', '2025-10-07 21:21:49', '2025-10-26 06:13:50'),
(90, 'vật lý', 'fines', NULL, 6, NULL, '111.67.205.60', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_1 rv:2.0) Gecko/20100616 Firefox/37.0', '2025-08-18 01:54:38', '2025-10-26 06:13:50'),
(91, 'trả sách', 'fines', '[\"year\", \"status\"]', 34, NULL, '234.206.221.228', 'Mozilla/5.0 (Windows 95; en-US; rv:1.9.0.20) Gecko/20130909 Firefox/35.0', '2025-06-06 02:25:27', '2025-10-26 06:13:50'),
(92, 'toán học', 'books', '[\"status\", \"year\", \"category\"]', 39, NULL, '152.23.53.1', 'Opera/8.36 (Windows NT 5.1; sl-SI) Presto/2.9.230 Version/11.00', '2025-07-02 21:49:22', '2025-10-26 06:13:50'),
(93, 'văn học', 'borrows', '[\"status\", \"category\", \"year\"]', 12, NULL, '247.106.160.228', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_9) AppleWebKit/5312 (KHTML, like Gecko) Chrome/40.0.811.0 Mobile Safari/5312', '2025-07-16 00:16:16', '2025-10-26 06:13:50'),
(94, 'sinh học', 'borrows', NULL, 40, NULL, '36.119.37.73', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20171210 Firefox/37.0', '2025-08-21 09:55:58', '2025-10-26 06:13:50'),
(95, 'lịch sử', 'borrows', '[\"category\", \"status\", \"year\"]', 26, NULL, '11.12.60.147', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_9 rv:5.0; en-US) AppleWebKit/535.48.5 (KHTML, like Gecko) Version/5.0 Safari/535.48.5', '2025-09-04 13:51:50', '2025-10-26 06:13:50'),
(96, 'công nghệ', 'fines', NULL, 26, NULL, '118.218.204.203', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.0; Trident/3.0)', '2025-10-14 16:01:58', '2025-10-26 06:13:50'),
(97, 'trả sách', 'borrows', NULL, 5, NULL, '114.161.195.112', 'Mozilla/5.0 (iPad; CPU OS 7_1_2 like Mac OS X; sl-SI) AppleWebKit/534.19.7 (KHTML, like Gecko) Version/4.0.5 Mobile/8B118 Safari/6534.19.7', '2025-09-30 12:54:36', '2025-10-26 06:13:50'),
(98, 'khoa học', 'fines', NULL, 12, NULL, '219.50.136.234', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3 rv:3.0) Gecko/20250922 Firefox/35.0', '2025-09-01 12:37:39', '2025-10-26 06:13:50'),
(99, 'Lê Văn C', 'readers', NULL, 39, NULL, '3.126.124.157', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 5.01; Trident/3.1)', '2025-07-14 08:20:09', '2025-10-26 06:13:50'),
(100, 'văn học', 'readers', NULL, 34, NULL, '81.179.64.136', 'Opera/8.74 (X11; Linux i686; en-US) Presto/2.10.316 Version/11.00', '2025-09-14 16:15:37', '2025-10-26 06:13:50'),
(101, 'quá hạn', 'fines', NULL, 5, NULL, '212.25.253.152', 'Mozilla/5.0 (Windows 98; Win 9x 4.90) AppleWebKit/5332 (KHTML, like Gecko) Chrome/36.0.809.0 Mobile Safari/5332', '2025-04-26 17:19:46', '2025-10-26 06:13:50'),
(102, 'trả sách', 'books', NULL, 23, NULL, '175.143.6.237', 'Mozilla/5.0 (Windows NT 6.0; nl-NL; rv:1.9.1.20) Gecko/20120827 Firefox/36.0', '2025-08-07 22:10:16', '2025-10-26 06:13:50'),
(103, 'Phạm Thị D', 'borrows', NULL, 34, NULL, '65.155.167.220', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_9 rv:3.0) Gecko/20161107 Firefox/37.0', '2025-05-11 23:05:03', '2025-10-26 06:13:50'),
(104, 'Lê Văn C', 'books', NULL, 22, NULL, '98.195.105.86', 'Opera/8.44 (X11; Linux i686; nl-NL) Presto/2.10.269 Version/12.00', '2025-07-08 22:10:13', '2025-10-26 06:13:50'),
(105, 'kinh tế', 'borrows', NULL, 43, NULL, '179.115.153.219', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_4) AppleWebKit/5352 (KHTML, like Gecko) Chrome/39.0.880.0 Mobile Safari/5352', '2025-10-22 19:04:39', '2025-10-26 06:13:50'),
(106, 'khoa học', 'borrows', NULL, 37, NULL, '3.157.70.8', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_2_1 like Mac OS X; en-US) AppleWebKit/534.33.7 (KHTML, like Gecko) Version/4.0.5 Mobile/8B111 Safari/6534.33.7', '2025-06-24 13:27:44', '2025-10-26 06:13:50'),
(107, 'phạt', 'books', NULL, 9, NULL, '7.201.228.172', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_8 rv:6.0; sl-SI) AppleWebKit/531.6.7 (KHTML, like Gecko) Version/4.0 Safari/531.6.7', '2025-07-06 04:31:45', '2025-10-26 06:13:50'),
(108, 'toán học', 'fines', '[\"status\", \"year\"]', 12, NULL, '88.239.32.131', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/5321 (KHTML, like Gecko) Chrome/38.0.814.0 Mobile Safari/5321', '2025-07-17 05:41:22', '2025-10-26 06:13:50'),
(109, 'văn học', 'fines', NULL, 34, NULL, '29.80.23.146', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/532.2 (KHTML, like Gecko) Version/15.0 EdgiOS/84.01121.83 Mobile/15E148 Safari/532.2', '2025-06-08 03:44:28', '2025-10-26 06:13:50'),
(110, 'toán học', 'fines', NULL, 23, NULL, '30.124.24.22', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_7 rv:2.0; nl-NL) AppleWebKit/533.27.4 (KHTML, like Gecko) Version/4.0.5 Safari/533.27.4', '2025-08-20 21:45:33', '2025-10-26 06:13:50'),
(111, 'lịch sử', 'books', NULL, 5, NULL, '126.89.90.81', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows CE; Trident/5.0)', '2025-10-21 00:36:09', '2025-10-26 06:13:50'),
(112, 'Trần Thị B', 'fines', NULL, 16, NULL, '121.99.34.201', 'Mozilla/5.0 (Windows NT 5.01; en-US; rv:1.9.1.20) Gecko/20120802 Firefox/36.0', '2025-05-17 08:22:18', '2025-10-26 06:13:50'),
(113, 'trả sách', 'borrows', NULL, 46, NULL, '129.45.9.83', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_9 rv:4.0; en-US) AppleWebKit/534.25.2 (KHTML, like Gecko) Version/5.0.4 Safari/534.25.2', '2025-06-20 08:47:33', '2025-10-26 06:13:50'),
(114, 'Trần Thị B', 'books', NULL, 4, NULL, '65.158.228.61', 'Opera/9.24 (X11; Linux i686; sl-SI) Presto/2.9.272 Version/10.00', '2025-05-03 14:45:49', '2025-10-26 06:13:50'),
(115, 'trả sách', 'borrows', NULL, 23, NULL, '190.61.232.49', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_3 rv:3.0; sl-SI) AppleWebKit/532.1.3 (KHTML, like Gecko) Version/5.0 Safari/532.1.3', '2025-10-12 01:21:56', '2025-10-26 06:13:50'),
(116, 'phạt', 'readers', NULL, 36, NULL, '190.17.238.231', 'Mozilla/5.0 (Windows NT 4.0; sl-SI; rv:1.9.1.20) Gecko/20110430 Firefox/35.0', '2025-05-03 02:46:14', '2025-10-26 06:13:50'),
(117, 'đặt trước', 'borrows', '[\"category\", \"year\"]', 26, NULL, '15.118.87.247', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows CE; Trident/5.1)', '2025-10-15 22:12:28', '2025-10-26 06:13:50'),
(118, 'toán học', 'readers', NULL, 36, NULL, '122.104.92.129', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_0) AppleWebKit/5350 (KHTML, like Gecko) Chrome/37.0.842.0 Mobile Safari/5350', '2025-08-03 22:20:43', '2025-10-26 06:13:50'),
(119, 'hóa học', 'fines', NULL, 2, NULL, '251.182.128.71', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 5.2; Trident/3.1)', '2025-06-27 01:28:59', '2025-10-26 06:13:50'),
(120, 'gia hạn', 'books', '[\"status\"]', 12, NULL, '4.153.168.25', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/5312 (KHTML, like Gecko) Chrome/37.0.820.0 Mobile Safari/5312', '2025-06-05 16:34:31', '2025-10-26 06:13:50'),
(121, 'sinh học', 'borrows', NULL, 19, NULL, '161.206.137.214', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_7 rv:3.0) Gecko/20120725 Firefox/37.0', '2025-08-02 19:20:45', '2025-10-26 06:13:50'),
(122, 'trả sách', 'borrows', '[\"year\", \"status\", \"category\"]', 40, NULL, '64.94.93.175', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/5310 (KHTML, like Gecko) Chrome/40.0.833.0 Mobile Safari/5310', '2025-07-10 02:48:34', '2025-10-26 06:13:50'),
(123, 'Trần Thị B', 'fines', NULL, 22, NULL, '74.220.140.234', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_9 rv:6.0) Gecko/20180921 Firefox/36.0', '2025-05-18 13:48:17', '2025-10-26 06:13:50'),
(124, 'sinh học', 'books', NULL, 30, NULL, '138.96.55.83', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_5 rv:2.0) Gecko/20170411 Firefox/36.0', '2025-09-02 20:00:30', '2025-10-26 06:13:50'),
(125, 'gia hạn', 'borrows', NULL, 35, NULL, '133.181.128.9', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/79.0.4380.76 Safari/532.1 Edg/79.01145.61', '2025-06-01 23:53:18', '2025-10-26 06:13:50'),
(126, 'tiểu thuyết', 'borrows', '[\"category\"]', 7, NULL, '246.230.201.89', 'Mozilla/5.0 (iPad; CPU OS 8_0_1 like Mac OS X; nl-NL) AppleWebKit/531.38.4 (KHTML, like Gecko) Version/3.0.5 Mobile/8B112 Safari/6531.38.4', '2025-05-27 04:06:48', '2025-10-26 06:13:50'),
(127, 'chính trị', 'borrows', NULL, 47, NULL, '136.184.217.223', 'Opera/8.78 (Windows NT 5.1; sl-SI) Presto/2.9.231 Version/12.00', '2025-06-09 22:43:28', '2025-10-26 06:13:50'),
(128, 'toán học', 'borrows', NULL, 23, NULL, '217.185.173.89', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 6.1; Trident/5.1)', '2025-09-16 17:35:27', '2025-10-26 06:13:50'),
(129, 'khoa học', 'borrows', NULL, 43, NULL, '241.150.180.41', 'Opera/8.95 (Windows NT 6.1; nl-NL) Presto/2.8.342 Version/11.00', '2025-06-16 09:48:54', '2025-10-26 06:13:50'),
(130, 'Trần Thị B', 'borrows', '[\"category\"]', 26, NULL, '3.50.16.190', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_6 rv:6.0; en-US) AppleWebKit/531.10.6 (KHTML, like Gecko) Version/5.0 Safari/531.10.6', '2025-08-02 22:12:08', '2025-10-26 06:13:50'),
(131, 'vật lý', 'readers', '[\"status\", \"category\", \"year\"]', 47, NULL, '101.151.200.36', 'Mozilla/5.0 (Windows 95; sl-SI; rv:1.9.0.20) Gecko/20111201 Firefox/37.0', '2025-05-02 03:02:00', '2025-10-26 06:13:50'),
(132, 'Hoàng Văn E', 'borrows', NULL, 4, NULL, '40.94.70.160', 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0_1 like Mac OS X; nl-NL) AppleWebKit/531.42.6 (KHTML, like Gecko) Version/3.0.5 Mobile/8B116 Safari/6531.42.6', '2025-08-02 17:32:56', '2025-10-26 06:13:50'),
(133, 'gia hạn', 'fines', NULL, 29, NULL, '136.182.186.230', 'Opera/9.31 (X11; Linux i686; sl-SI) Presto/2.11.344 Version/12.00', '2025-05-20 17:24:52', '2025-10-26 06:13:50'),
(134, 'khoa học', 'books', '[\"category\", \"status\"]', 29, NULL, '251.153.250.215', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_1 like Mac OS X) AppleWebKit/536.0 (KHTML, like Gecko) Version/15.0 EdgiOS/98.01070.84 Mobile/15E148 Safari/536.0', '2025-10-03 03:38:58', '2025-10-26 06:13:50'),
(135, 'sách', 'books', NULL, 50, NULL, '141.73.224.15', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_1) AppleWebKit/5312 (KHTML, like Gecko) Chrome/38.0.887.0 Mobile Safari/5312', '2025-07-04 06:34:57', '2025-10-26 06:13:50'),
(136, 'sách', 'fines', NULL, 21, NULL, '68.60.102.158', 'Mozilla/5.0 (iPad; CPU OS 8_0_2 like Mac OS X; nl-NL) AppleWebKit/534.34.3 (KHTML, like Gecko) Version/4.0.5 Mobile/8B118 Safari/6534.34.3', '2025-08-24 19:05:02', '2025-10-26 06:13:50'),
(137, 'toán học', 'readers', NULL, 2, NULL, '230.93.1.28', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/84.0.4728.43 Safari/537.2 EdgA/84.01127.1', '2025-07-31 00:06:27', '2025-10-26 06:13:50'),
(138, 'triết học', 'borrows', NULL, 41, NULL, '148.84.204.35', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5361 (KHTML, like Gecko) Chrome/38.0.883.0 Mobile Safari/5361', '2025-06-25 22:43:41', '2025-10-26 06:13:50'),
(139, 'quá hạn', 'books', '[\"category\"]', 48, NULL, '146.63.188.32', 'Mozilla/5.0 (Windows; U; Windows CE) AppleWebKit/531.39.3 (KHTML, like Gecko) Version/5.1 Safari/531.39.3', '2025-07-03 22:52:52', '2025-10-26 06:13:50'),
(140, 'phạt', 'fines', NULL, 6, 1, '54.195.4.134', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.2; Trident/4.1)', '2025-09-02 13:10:09', '2025-10-26 06:13:50'),
(141, 'mượn sách', 'fines', NULL, 0, NULL, '99.137.99.247', 'Mozilla/5.0 (Windows NT 5.2) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/86.0.4660.20 Safari/534.1 Edg/86.01069.53', '2025-10-09 00:30:47', '2025-10-26 06:13:50'),
(142, 'công nghệ', 'fines', NULL, 33, 1, '155.82.87.143', 'Mozilla/5.0 (Windows NT 5.2) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/95.0.4718.61 Safari/531.0 Edg/95.01129.29', '2025-08-02 14:56:41', '2025-10-26 06:13:50'),
(143, 'mượn sách', 'readers', NULL, 38, NULL, '129.115.77.7', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3 rv:2.0; nl-NL) AppleWebKit/533.47.1 (KHTML, like Gecko) Version/5.0 Safari/533.47.1', '2025-10-12 14:18:19', '2025-10-26 06:13:50'),
(144, 'tiểu thuyết', 'fines', '[\"status\", \"year\", \"category\"]', 28, NULL, '29.129.70.111', 'Mozilla/5.0 (Windows NT 4.0; en-US; rv:1.9.0.20) Gecko/20240617 Firefox/37.0', '2025-05-10 19:15:54', '2025-10-26 06:13:50'),
(145, 'đặt trước', 'borrows', '[\"category\", \"status\"]', 5, NULL, '89.104.251.178', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5350 (KHTML, like Gecko) Chrome/40.0.861.0 Mobile Safari/5350', '2025-08-03 22:13:53', '2025-10-26 06:13:50'),
(146, 'hóa học', 'readers', '[\"year\", \"category\", \"status\"]', 34, NULL, '163.69.231.106', 'Mozilla/5.0 (Windows; U; Windows NT 4.0) AppleWebKit/531.10.7 (KHTML, like Gecko) Version/5.0.1 Safari/531.10.7', '2025-10-26 05:41:32', '2025-10-26 06:13:50'),
(147, 'gia hạn', 'readers', '[\"status\"]', 31, NULL, '120.104.37.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_0 like Mac OS X) AppleWebKit/531.1 (KHTML, like Gecko) Version/15.0 EdgiOS/88.01124.76 Mobile/15E148 Safari/531.1', '2025-10-20 02:22:09', '2025-10-26 06:13:50'),
(148, 'văn học', 'books', NULL, 1, NULL, '168.70.157.169', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/94.0.4174.14 Safari/537.1 Edg/94.01075.75', '2025-06-26 21:33:25', '2025-10-26 06:13:50'),
(149, 'mượn sách', 'readers', NULL, 10, NULL, '48.215.242.188', 'Opera/9.76 (X11; Linux i686; sl-SI) Presto/2.10.190 Version/10.00', '2025-10-04 05:26:03', '2025-10-26 06:13:50'),
(150, 'tiểu thuyết', 'readers', '[\"status\", \"category\", \"year\"]', 17, NULL, '136.209.144.117', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows 95; Trident/4.1)', '2025-07-07 18:32:25', '2025-10-26 06:13:50'),
(151, 'vật lý', 'borrows', NULL, 0, 1, '204.102.17.249', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/531.1 (KHTML, like Gecko) Chrome/81.0.4036.88 Safari/531.1 Edg/81.01091.80', '2025-09-22 07:33:31', '2025-10-26 06:13:50'),
(152, 'vật lý', 'fines', '[\"status\", \"category\", \"year\"]', 12, NULL, '39.28.78.183', 'Opera/8.77 (Windows 95; nl-NL) Presto/2.11.245 Version/11.00', '2025-04-26 19:17:52', '2025-10-26 06:13:50'),
(153, 'gia hạn', 'borrows', '[\"status\", \"category\"]', 31, NULL, '178.207.238.225', 'Mozilla/5.0 (Windows; U; Windows NT 6.0) AppleWebKit/531.43.6 (KHTML, like Gecko) Version/4.1 Safari/531.43.6', '2025-07-03 13:52:32', '2025-10-26 06:13:50'),
(154, 'công nghệ', 'readers', NULL, 45, NULL, '21.113.120.109', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.1 (KHTML, like Gecko) Chrome/92.0.4604.48 Safari/536.1 Edg/92.01062.65', '2025-09-07 15:31:55', '2025-10-26 06:13:50'),
(155, 'đặt trước', 'borrows', NULL, 30, NULL, '241.105.125.47', 'Opera/8.52 (X11; Linux i686; nl-NL) Presto/2.12.193 Version/12.00', '2025-06-04 00:49:21', '2025-10-26 06:13:50'),
(156, 'sách', 'readers', NULL, 18, NULL, '175.134.216.105', 'Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20171130 Firefox/37.0', '2025-09-28 11:59:04', '2025-10-26 06:13:50'),
(157, 'công nghệ', 'books', '[\"year\", \"category\", \"status\"]', 39, NULL, '250.186.212.143', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_8) AppleWebKit/5321 (KHTML, like Gecko) Chrome/37.0.816.0 Mobile Safari/5321', '2025-05-24 04:35:27', '2025-10-26 06:13:50'),
(158, 'sinh học', 'readers', NULL, 34, NULL, '199.202.154.187', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/5340 (KHTML, like Gecko) Chrome/36.0.894.0 Mobile Safari/5340', '2025-07-05 06:20:52', '2025-10-26 06:13:50'),
(159, 'lịch sử', 'borrows', NULL, 45, NULL, '50.169.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:7.0) Gecko/20190621 Firefox/35.0', '2025-06-03 22:06:39', '2025-10-26 06:13:50'),
(160, 'khoa học', 'readers', '[\"status\"]', 2, 1, '102.101.183.18', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0_1 like Mac OS X; nl-NL) AppleWebKit/531.50.1 (KHTML, like Gecko) Version/4.0.5 Mobile/8B118 Safari/6531.50.1', '2025-08-20 06:18:43', '2025-10-26 06:13:50'),
(161, 'mượn sách', 'books', NULL, 0, NULL, '46.160.118.94', 'Mozilla/5.0 (Windows; U; Windows 98; Win 9x 4.90) AppleWebKit/533.13.4 (KHTML, like Gecko) Version/5.1 Safari/533.13.4', '2025-09-15 00:29:50', '2025-10-26 06:13:50'),
(162, 'sách', 'books', '[\"year\", \"status\", \"category\"]', 50, NULL, '129.229.15.210', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_7) AppleWebKit/5341 (KHTML, like Gecko) Chrome/36.0.864.0 Mobile Safari/5341', '2025-07-20 03:51:42', '2025-10-26 06:13:50'),
(163, 'triết học', 'borrows', NULL, 31, NULL, '62.129.33.7', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/536.2 (KHTML, like Gecko) Version/15.0 EdgiOS/87.01026.60 Mobile/15E148 Safari/536.2', '2025-05-23 19:21:39', '2025-10-26 06:13:50'),
(164, 'Phạm Thị D', 'borrows', NULL, 44, NULL, '235.158.209.62', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_1 rv:2.0; en-US) AppleWebKit/532.19.7 (KHTML, like Gecko) Version/4.0 Safari/532.19.7', '2025-09-11 19:16:31', '2025-10-26 06:13:50'),
(165, 'gia hạn', 'books', NULL, 23, NULL, '147.203.83.117', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows NT 6.0; Trident/3.0)', '2025-10-21 04:59:17', '2025-10-26 06:13:50'),
(166, 'Trần Thị B', 'borrows', NULL, 23, NULL, '230.130.150.183', 'Mozilla/5.0 (Windows; U; Windows NT 5.1) AppleWebKit/535.47.4 (KHTML, like Gecko) Version/5.0.1 Safari/535.47.4', '2025-08-12 23:35:42', '2025-10-26 06:13:50'),
(167, 'toán học', 'books', NULL, 23, NULL, '101.237.167.101', 'Mozilla/5.0 (Windows 98; Win 9x 4.90) AppleWebKit/5330 (KHTML, like Gecko) Chrome/39.0.835.0 Mobile Safari/5330', '2025-08-15 14:57:43', '2025-10-26 06:13:50'),
(168, 'triết học', 'readers', '[\"year\", \"category\", \"status\"]', 39, NULL, '15.0.252.105', 'Mozilla/5.0 (Windows NT 6.0; nl-NL; rv:1.9.2.20) Gecko/20230110 Firefox/35.0', '2025-07-12 07:35:26', '2025-10-26 06:13:50'),
(169, 'gia hạn', 'books', NULL, 43, NULL, '123.132.148.204', 'Opera/8.98 (Windows NT 5.01; nl-NL) Presto/2.11.223 Version/11.00', '2025-07-24 16:34:52', '2025-10-26 06:13:50'),
(170, 'đặt trước', 'fines', NULL, 39, NULL, '223.222.51.10', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows 98; Trident/4.0)', '2025-07-01 15:23:40', '2025-10-26 06:13:50'),
(171, 'quá hạn', 'borrows', '[\"status\"]', 45, NULL, '201.129.197.96', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.0; Trident/3.1)', '2025-09-08 14:07:23', '2025-10-26 06:13:50'),
(172, 'lịch sử', 'readers', '[\"status\", \"year\", \"category\"]', 16, NULL, '214.63.150.207', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_9) AppleWebKit/536.1 (KHTML, like Gecko) Chrome/83.0.4614.57 Safari/536.1 Edg/83.01049.58', '2025-05-09 20:14:53', '2025-10-26 06:13:50'),
(173, 'chính trị', 'fines', '[\"year\", \"category\", \"status\"]', 42, NULL, '19.251.226.244', 'Mozilla/5.0 (Windows; U; Windows 98) AppleWebKit/534.17.3 (KHTML, like Gecko) Version/5.0.1 Safari/534.17.3', '2025-08-31 03:14:38', '2025-10-26 06:13:50'),
(174, 'toán học', 'books', NULL, 31, NULL, '154.164.214.50', 'Mozilla/5.0 (Windows; U; Windows CE) AppleWebKit/534.10.3 (KHTML, like Gecko) Version/5.0 Safari/534.10.3', '2025-08-16 23:19:00', '2025-10-26 06:13:50'),
(175, 'phạt', 'borrows', NULL, 19, NULL, '180.113.114.115', 'Opera/9.93 (Windows NT 5.2; en-US) Presto/2.11.285 Version/11.00', '2025-09-10 12:10:19', '2025-10-26 06:13:50'),
(176, 'Hoàng Văn E', 'fines', '[\"category\", \"status\", \"year\"]', 25, NULL, '253.153.10.186', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7) AppleWebKit/536.0 (KHTML, like Gecko) Chrome/92.0.4824.53 Safari/536.0 Edg/92.01004.52', '2025-05-23 11:05:50', '2025-10-26 06:13:50'),
(177, 'Lê Văn C', 'borrows', NULL, 7, NULL, '61.51.183.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_0 like Mac OS X) AppleWebKit/536.0 (KHTML, like Gecko) Version/15.0 EdgiOS/98.01118.62 Mobile/15E148 Safari/536.0', '2025-10-04 05:12:47', '2025-10-26 06:13:50'),
(178, 'hóa học', 'fines', NULL, 13, NULL, '108.109.33.15', 'Opera/9.36 (Windows NT 5.01; sl-SI) Presto/2.9.194 Version/10.00', '2025-05-26 09:35:04', '2025-10-26 06:13:50'),
(179, 'triết học', 'readers', NULL, 44, NULL, '255.221.29.62', 'Mozilla/5.0 (iPad; CPU OS 7_1_2 like Mac OS X; sl-SI) AppleWebKit/531.37.7 (KHTML, like Gecko) Version/4.0.5 Mobile/8B114 Safari/6531.37.7', '2025-08-10 18:29:17', '2025-10-26 06:13:50'),
(180, 'vật lý', 'readers', NULL, 15, NULL, '54.242.0.175', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_1 like Mac OS X) AppleWebKit/531.2 (KHTML, like Gecko) Version/15.0 EdgiOS/93.01114.39 Mobile/15E148 Safari/531.2', '2025-10-17 11:03:02', '2025-10-26 06:13:50'),
(181, 'chính trị', 'borrows', NULL, 6, NULL, '103.111.201.124', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_1) AppleWebKit/5361 (KHTML, like Gecko) Chrome/38.0.834.0 Mobile Safari/5361', '2025-06-17 09:56:29', '2025-10-26 06:13:50'),
(182, 'khoa học', 'borrows', NULL, 41, NULL, '82.2.147.32', 'Opera/8.25 (Windows NT 5.0; en-US) Presto/2.9.181 Version/10.00', '2025-07-26 07:48:24', '2025-10-26 06:13:50'),
(183, 'toán học', 'fines', NULL, 14, NULL, '7.231.29.78', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/5341 (KHTML, like Gecko) Chrome/40.0.825.0 Mobile Safari/5341', '2025-10-17 15:26:59', '2025-10-26 06:13:50'),
(184, 'kinh tế', 'books', '[\"status\", \"year\", \"category\"]', 1, NULL, '23.89.109.142', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5342 (KHTML, like Gecko) Chrome/40.0.820.0 Mobile Safari/5342', '2025-07-08 08:39:15', '2025-10-26 06:13:50'),
(185, 'mượn sách', 'fines', '[\"year\", \"status\", \"category\"]', 44, NULL, '228.63.210.95', 'Mozilla/5.0 (Windows NT 4.0; nl-NL; rv:1.9.0.20) Gecko/20230605 Firefox/35.0', '2025-09-04 00:45:48', '2025-10-26 06:13:50'),
(186, 'Hoàng Văn E', 'borrows', NULL, 45, NULL, '166.0.46.223', 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20180824 Firefox/37.0', '2025-05-18 05:27:02', '2025-10-26 06:13:50'),
(187, 'triết học', 'books', NULL, 20, NULL, '165.46.92.254', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_5 rv:6.0) Gecko/20191124 Firefox/35.0', '2025-08-15 18:12:02', '2025-10-26 06:13:50'),
(188, 'lịch sử', 'borrows', NULL, 15, NULL, '132.73.67.25', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_4 rv:6.0) Gecko/20101102 Firefox/37.0', '2025-05-28 16:28:45', '2025-10-26 06:13:50'),
(189, 'trả sách', 'readers', '[\"year\", \"category\"]', 15, NULL, '242.195.16.238', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5350 (KHTML, like Gecko) Chrome/39.0.859.0 Mobile Safari/5350', '2025-10-24 11:25:07', '2025-10-26 06:13:50'),
(190, 'khoa học', 'borrows', NULL, 47, NULL, '114.230.19.127', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/4.0)', '2025-08-10 02:04:02', '2025-10-26 06:13:50'),
(191, 'quá hạn', 'books', NULL, 25, NULL, '219.34.204.251', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_3 rv:3.0; sl-SI) AppleWebKit/532.12.5 (KHTML, like Gecko) Version/4.0 Safari/532.12.5', '2025-06-11 19:11:34', '2025-10-26 06:13:50'),
(192, 'Lê Văn C', 'borrows', NULL, 2, NULL, '101.171.22.164', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.2; Trident/4.1)', '2025-05-07 23:50:55', '2025-10-26 06:13:50'),
(193, 'Trần Thị B', 'readers', '[\"year\", \"category\"]', 48, NULL, '173.157.25.77', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows 98; Win 9x 4.90; Trident/3.1)', '2025-07-18 16:05:46', '2025-10-26 06:13:50'),
(194, 'kinh tế', 'readers', NULL, 41, NULL, '66.175.122.216', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows 95; Trident/5.1)', '2025-10-20 19:27:13', '2025-10-26 06:13:50'),
(195, 'toán học', 'readers', NULL, 47, NULL, '164.19.142.182', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.2; Trident/4.0)', '2025-10-18 04:55:05', '2025-10-26 06:13:50'),
(196, 'triết học', 'fines', '[\"status\", \"year\", \"category\"]', 11, NULL, '91.69.22.222', 'Mozilla/5.0 (Windows NT 5.2; sl-SI; rv:1.9.0.20) Gecko/20230219 Firefox/36.0', '2025-06-11 19:16:34', '2025-10-26 06:13:50'),
(197, 'triết học', 'fines', NULL, 50, NULL, '190.125.173.90', 'Mozilla/5.0 (iPad; CPU OS 7_1_1 like Mac OS X; en-US) AppleWebKit/532.44.6 (KHTML, like Gecko) Version/3.0.5 Mobile/8B116 Safari/6532.44.6', '2025-07-21 13:52:35', '2025-10-26 06:13:50'),
(198, 'Lê Văn C', 'readers', NULL, 26, NULL, '74.223.92.27', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows NT 6.2; Trident/3.1)', '2025-07-01 05:13:08', '2025-10-26 06:13:50'),
(199, 'Lê Văn C', 'readers', '[\"status\", \"year\"]', 49, NULL, '20.196.205.169', 'Mozilla/5.0 (Windows; U; Windows 98; Win 9x 4.90) AppleWebKit/531.14.7 (KHTML, like Gecko) Version/5.0.4 Safari/531.14.7', '2025-07-26 13:19:16', '2025-10-26 06:13:50'),
(200, 'Phạm Thị D', 'readers', NULL, 39, NULL, '209.123.35.95', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_0 rv:2.0; sl-SI) AppleWebKit/533.5.7 (KHTML, like Gecko) Version/5.0.4 Safari/533.5.7', '2025-07-10 04:58:46', '2025-10-26 06:13:50'),
(201, 'kinh tế', 'books', NULL, 20, NULL, '24.76.20.32', 'Opera/9.81 (Windows CE; en-US) Presto/2.9.221 Version/11.00', '2025-07-08 12:31:59', '2025-10-26 06:13:50'),
(202, 'vật lý', 'fines', NULL, 42, NULL, '64.71.5.9', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.0; Trident/3.0)', '2025-10-05 12:15:24', '2025-10-26 06:13:50'),
(203, 'quá hạn', 'borrows', NULL, 33, NULL, '110.129.37.72', 'Mozilla/5.0 (Windows 98) AppleWebKit/536.1 (KHTML, like Gecko) Chrome/97.0.4816.33 Safari/536.1 Edg/97.01029.28', '2025-06-15 09:19:57', '2025-10-26 06:13:50'),
(204, 'phạt', 'borrows', NULL, 7, NULL, '169.124.120.13', 'Opera/8.56 (X11; Linux x86_64; sl-SI) Presto/2.9.240 Version/12.00', '2025-05-08 13:34:33', '2025-10-26 06:13:50'),
(205, 'tiểu thuyết', 'fines', NULL, 46, NULL, '42.98.188.162', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 5.01; Trident/3.0)', '2025-09-19 10:34:00', '2025-10-26 06:13:50'),
(206, 'chính trị', 'readers', NULL, 37, NULL, '112.96.91.206', 'Opera/8.76 (X11; Linux i686; nl-NL) Presto/2.8.331 Version/10.00', '2025-07-09 22:37:27', '2025-10-26 06:13:50'),
(207, 'sinh học', 'borrows', '[\"status\"]', 30, 23, '80.186.135.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_7 rv:6.0; nl-NL) AppleWebKit/534.44.1 (KHTML, like Gecko) Version/5.0.3 Safari/534.44.1', '2025-08-25 08:50:35', '2025-10-26 06:13:50'),
(208, 'Nguyễn Văn A', 'fines', NULL, 18, NULL, '142.219.113.236', 'Mozilla/5.0 (Windows; U; Windows NT 5.0) AppleWebKit/534.19.4 (KHTML, like Gecko) Version/4.0.2 Safari/534.19.4', '2025-08-29 09:21:08', '2025-10-26 06:13:50'),
(209, 'Trần Thị B', 'books', NULL, 33, NULL, '26.24.107.37', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_2) AppleWebKit/5340 (KHTML, like Gecko) Chrome/37.0.811.0 Mobile Safari/5340', '2025-06-10 01:31:58', '2025-10-26 06:13:50'),
(210, 'lịch sử', 'readers', '[\"category\", \"year\", \"status\"]', 48, NULL, '24.148.47.137', 'Opera/9.10 (Windows 98; nl-NL) Presto/2.10.316 Version/10.00', '2025-07-25 15:45:12', '2025-10-26 06:13:50'),
(211, 'Trần Thị B', 'fines', NULL, 37, NULL, '90.168.56.195', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/5321 (KHTML, like Gecko) Chrome/40.0.850.0 Mobile Safari/5321', '2025-05-14 11:12:15', '2025-10-26 06:13:50'),
(212, 'kinh tế', 'books', NULL, 40, NULL, '7.98.156.3', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/5322 (KHTML, like Gecko) Chrome/36.0.801.0 Mobile Safari/5322', '2025-10-23 05:57:05', '2025-10-26 06:13:50'),
(213, 'triết học', 'books', '[\"year\"]', 4, NULL, '14.201.171.192', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows 98; Trident/5.0)', '2025-09-02 04:25:39', '2025-10-26 06:13:50'),
(214, 'phạt', 'fines', NULL, 39, NULL, '209.136.215.27', 'Mozilla/5.0 (Windows NT 5.0; en-US; rv:1.9.2.20) Gecko/20121108 Firefox/37.0', '2025-05-01 12:25:28', '2025-10-26 06:13:50'),
(215, 'Phạm Thị D', 'books', NULL, 42, NULL, '60.88.72.166', 'Mozilla/5.0 (iPad; CPU OS 8_0_2 like Mac OS X; nl-NL) AppleWebKit/535.10.7 (KHTML, like Gecko) Version/4.0.5 Mobile/8B118 Safari/6535.10.7', '2025-05-21 07:39:47', '2025-10-26 06:13:50'),
(216, 'lịch sử', 'readers', NULL, 43, NULL, '108.221.218.11', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_3 rv:5.0; sl-SI) AppleWebKit/531.42.4 (KHTML, like Gecko) Version/5.1 Safari/531.42.4', '2025-05-31 06:32:01', '2025-10-26 06:13:50'),
(217, 'văn học', 'fines', NULL, 43, NULL, '160.24.210.204', 'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/532.9.5 (KHTML, like Gecko) Version/4.1 Safari/532.9.5', '2025-09-07 16:33:36', '2025-10-26 06:13:50'),
(218, 'toán học', 'borrows', NULL, 28, NULL, '86.76.9.108', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.2; Trident/4.1)', '2025-07-15 19:24:45', '2025-10-26 06:13:50'),
(219, 'hóa học', 'readers', NULL, 10, NULL, '119.200.0.26', 'Opera/9.87 (Windows NT 5.0; en-US) Presto/2.12.335 Version/11.00', '2025-06-20 18:09:03', '2025-10-26 06:13:50'),
(220, 'đặt trước', 'fines', NULL, 11, NULL, '241.16.34.224', 'Opera/9.51 (X11; Linux x86_64; nl-NL) Presto/2.9.230 Version/10.00', '2025-09-10 09:12:01', '2025-10-26 06:13:51'),
(221, 'Nguyễn Văn A', 'borrows', NULL, 21, NULL, '206.120.161.123', 'Opera/8.83 (X11; Linux x86_64; en-US) Presto/2.11.257 Version/12.00', '2025-06-24 03:09:18', '2025-10-26 06:13:51'),
(222, 'đặt trước', 'fines', NULL, 8, NULL, '210.124.69.66', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 6.1; Trident/3.1)', '2025-08-02 12:45:35', '2025-10-26 06:13:51'),
(223, 'Lê Văn C', 'books', NULL, 31, NULL, '250.154.138.102', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 6.2; Trident/4.0)', '2025-06-21 15:09:29', '2025-10-26 06:13:51'),
(224, 'mượn sách', 'readers', '[\"year\", \"status\", \"category\"]', 5, NULL, '221.129.30.131', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6 rv:2.0) Gecko/20151105 Firefox/37.0', '2025-07-22 05:33:33', '2025-10-26 06:13:51'),
(225, 'gia hạn', 'books', NULL, 19, NULL, '135.188.159.196', 'Mozilla/5.0 (X11; Linux x86_64; rv:7.0) Gecko/20131114 Firefox/36.0', '2025-06-14 12:18:40', '2025-10-26 06:13:51'),
(226, 'khoa học', 'borrows', NULL, 39, NULL, '100.40.226.155', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_5 rv:6.0) Gecko/20131107 Firefox/37.0', '2025-06-15 23:18:55', '2025-10-26 06:13:51'),
(227, 'tiểu thuyết', 'books', NULL, 2, 23, '245.4.27.150', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_6 rv:3.0) Gecko/20110313 Firefox/37.0', '2025-10-14 12:33:39', '2025-10-26 06:13:51'),
(228, 'đặt trước', 'fines', NULL, 19, NULL, '255.91.203.144', 'Opera/9.79 (X11; Linux i686; nl-NL) Presto/2.11.264 Version/10.00', '2025-05-26 19:20:42', '2025-10-26 06:13:51'),
(229, 'hóa học', 'books', NULL, 46, NULL, '39.164.121.212', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 6.1; Trident/5.0)', '2025-10-18 10:25:26', '2025-10-26 06:13:51'),
(230, 'Phạm Thị D', 'readers', NULL, 43, NULL, '16.153.87.18', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20130124 Firefox/37.0', '2025-06-14 04:32:41', '2025-10-26 06:13:51'),
(231, 'lịch sử', 'borrows', '[\"status\"]', 46, NULL, '143.100.187.98', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0_1 like Mac OS X; en-US) AppleWebKit/532.1.4 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6532.1.4', '2025-06-05 03:43:49', '2025-10-26 06:13:51'),
(232, 'tiểu thuyết', 'borrows', NULL, 12, NULL, '211.49.193.203', 'Mozilla/5.0 (X11; Linux i686; rv:7.0) Gecko/20220214 Firefox/37.0', '2025-05-29 09:47:44', '2025-10-26 06:13:51'),
(233, 'Hoàng Văn E', 'books', '[\"category\", \"year\", \"status\"]', 25, NULL, '28.144.58.250', 'Opera/8.41 (Windows 98; sl-SI) Presto/2.9.182 Version/11.00', '2025-06-13 02:04:02', '2025-10-26 06:13:51'),
(234, 'trả sách', 'fines', NULL, 27, 1, '92.2.105.84', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.0; Trident/3.1)', '2025-08-24 21:06:48', '2025-10-26 06:13:51'),
(235, 'chính trị', 'books', NULL, 8, NULL, '5.7.91.228', 'Mozilla/5.0 (Windows NT 5.01) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/94.0.4594.27 Safari/532.0 Edg/94.01018.83', '2025-09-22 14:23:59', '2025-10-26 06:13:51'),
(236, 'Hoàng Văn E', 'books', NULL, 6, NULL, '55.225.224.178', 'Mozilla/5.0 (iPad; CPU OS 7_0_1 like Mac OS X; sl-SI) AppleWebKit/531.11.3 (KHTML, like Gecko) Version/3.0.5 Mobile/8B112 Safari/6531.11.3', '2025-10-14 23:07:05', '2025-10-26 06:13:51'),
(237, 'gia hạn', 'books', NULL, 39, NULL, '34.101.160.51', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_3) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/79.0.4210.18 Safari/535.2 Edg/79.01104.34', '2025-08-04 06:51:12', '2025-10-26 06:13:51'),
(238, 'hóa học', 'readers', NULL, 22, NULL, '231.116.32.238', 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20191103 Firefox/36.0', '2025-05-11 12:45:21', '2025-10-26 06:13:51');
INSERT INTO `search_logs` (`id`, `query`, `type`, `filters`, `results_count`, `user_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(239, 'sinh học', 'readers', NULL, 32, NULL, '229.223.144.110', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/534.1 (KHTML, like Gecko) Version/15.0 EdgiOS/95.01114.91 Mobile/15E148 Safari/534.1', '2025-10-10 18:09:44', '2025-10-26 06:13:51'),
(240, 'Nguyễn Văn A', 'fines', '[\"year\", \"category\", \"status\"]', 49, NULL, '163.210.124.113', 'Mozilla/5.0 (iPad; CPU OS 7_2_1 like Mac OS X; en-US) AppleWebKit/533.24.5 (KHTML, like Gecko) Version/3.0.5 Mobile/8B116 Safari/6533.24.5', '2025-07-26 08:39:15', '2025-10-26 06:13:51'),
(241, 'trả sách', 'fines', NULL, 19, NULL, '106.11.1.203', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_7) AppleWebKit/534.0 (KHTML, like Gecko) Chrome/82.0.4055.87 Safari/534.0 Edg/82.01026.79', '2025-06-20 15:32:26', '2025-10-26 06:13:51'),
(242, 'triết học', 'books', NULL, 38, NULL, '56.143.164.54', 'Opera/9.16 (X11; Linux x86_64; en-US) Presto/2.8.324 Version/11.00', '2025-06-16 16:20:31', '2025-10-26 06:13:51'),
(243, 'quá hạn', 'readers', '[\"status\"]', 9, NULL, '188.170.144.153', 'Mozilla/5.0 (Windows NT 6.2; nl-NL; rv:1.9.2.20) Gecko/20110127 Firefox/37.0', '2025-08-20 18:42:12', '2025-10-26 06:13:51'),
(244, 'chính trị', 'readers', NULL, 10, NULL, '71.232.40.39', 'Mozilla/5.0 (Windows NT 4.0; en-US; rv:1.9.1.20) Gecko/20240211 Firefox/35.0', '2025-06-18 11:38:38', '2025-10-26 06:13:51'),
(245, 'tiểu thuyết', 'books', NULL, 9, NULL, '101.49.209.234', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows 95; Trident/3.1)', '2025-05-20 09:49:51', '2025-10-26 06:13:51'),
(246, 'triết học', 'borrows', NULL, 24, NULL, '177.153.99.166', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_1 like Mac OS X) AppleWebKit/536.2 (KHTML, like Gecko) Version/15.0 EdgiOS/84.01060.82 Mobile/15E148 Safari/536.2', '2025-06-07 04:56:50', '2025-10-26 06:13:51'),
(247, 'gia hạn', 'readers', '[\"category\", \"year\", \"status\"]', 34, NULL, '187.224.13.169', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5350 (KHTML, like Gecko) Chrome/37.0.826.0 Mobile Safari/5350', '2025-10-12 12:09:36', '2025-10-26 06:13:51'),
(248, 'sinh học', 'borrows', NULL, 3, NULL, '38.203.151.32', 'Opera/8.34 (Windows 98; nl-NL) Presto/2.9.342 Version/10.00', '2025-07-04 13:44:22', '2025-10-26 06:13:51'),
(249, 'Lê Văn C', 'readers', NULL, 38, NULL, '39.35.49.118', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_2) AppleWebKit/5351 (KHTML, like Gecko) Chrome/39.0.879.0 Mobile Safari/5351', '2025-05-20 22:36:10', '2025-10-26 06:13:51'),
(250, 'vật lý', 'fines', '[\"year\", \"status\"]', 3, NULL, '200.40.3.129', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 6.1; Trident/4.1)', '2025-08-14 00:53:15', '2025-10-26 06:13:51'),
(251, 'lịch sử', 'fines', '[\"status\", \"category\", \"year\"]', 11, NULL, '124.132.203.146', 'Mozilla/5.0 (iPad; CPU OS 8_1_1 like Mac OS X; nl-NL) AppleWebKit/531.8.6 (KHTML, like Gecko) Version/3.0.5 Mobile/8B113 Safari/6531.8.6', '2025-08-31 20:41:07', '2025-10-26 06:13:51'),
(252, 'Nguyễn Văn A', 'books', NULL, 26, NULL, '64.36.83.9', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows NT 4.0; Trident/5.0)', '2025-09-26 11:47:15', '2025-10-26 06:13:51'),
(253, 'tiểu thuyết', 'borrows', NULL, 29, NULL, '10.185.54.187', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/5340 (KHTML, like Gecko) Chrome/40.0.864.0 Mobile Safari/5340', '2025-07-20 12:18:53', '2025-10-26 06:13:51'),
(254, 'triết học', 'borrows', NULL, 32, NULL, '200.51.197.217', 'Mozilla/5.0 (Windows NT 5.2; en-US; rv:1.9.2.20) Gecko/20181105 Firefox/35.0', '2025-07-06 04:26:27', '2025-10-26 06:13:51'),
(255, 'công nghệ', 'readers', NULL, 17, NULL, '191.199.158.34', 'Opera/8.85 (Windows 98; nl-NL) Presto/2.11.213 Version/11.00', '2025-07-13 12:40:43', '2025-10-26 06:13:51'),
(256, 'lịch sử', 'borrows', NULL, 19, NULL, '95.213.22.150', 'Mozilla/5.0 (Windows CE) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/81.0.4467.69 Safari/535.1 Edg/81.01132.26', '2025-10-20 13:46:54', '2025-10-26 06:13:51'),
(257, 'đặt trước', 'books', NULL, 33, NULL, '45.121.36.234', 'Mozilla/5.0 (Windows 95) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/91.0.4220.69 Safari/532.0 Edg/91.01093.83', '2025-06-09 03:59:24', '2025-10-26 06:13:51'),
(258, 'chính trị', 'borrows', NULL, 14, NULL, '192.15.105.179', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1_2 like Mac OS X; sl-SI) AppleWebKit/535.23.1 (KHTML, like Gecko) Version/4.0.5 Mobile/8B114 Safari/6535.23.1', '2025-05-18 19:56:20', '2025-10-26 06:13:51'),
(259, 'chính trị', 'readers', NULL, 44, NULL, '147.71.76.16', 'Mozilla/5.0 (Windows 98; sl-SI; rv:1.9.2.20) Gecko/20180506 Firefox/37.0', '2025-06-05 17:15:38', '2025-10-26 06:13:51'),
(260, 'mượn sách', 'fines', '[\"category\", \"year\", \"status\"]', 11, NULL, '144.213.142.179', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 6.2; Trident/4.1)', '2025-09-04 21:46:15', '2025-10-26 06:13:51'),
(261, 'khoa học', 'borrows', NULL, 34, NULL, '100.82.247.80', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_9 rv:4.0; sl-SI) AppleWebKit/532.1.6 (KHTML, like Gecko) Version/5.0.3 Safari/532.1.6', '2025-07-23 15:54:06', '2025-10-26 06:13:51'),
(262, 'mượn sách', 'fines', NULL, 12, 23, '63.193.199.151', 'Mozilla/5.0 (Windows CE; sl-SI; rv:1.9.1.20) Gecko/20120227 Firefox/35.0', '2025-10-10 19:53:12', '2025-10-26 06:13:51'),
(263, 'Nguyễn Văn A', 'borrows', '[\"status\"]', 15, NULL, '182.150.2.210', 'Opera/9.11 (X11; Linux x86_64; sl-SI) Presto/2.10.263 Version/11.00', '2025-09-14 04:20:19', '2025-10-26 06:13:51'),
(264, 'trả sách', 'borrows', NULL, 35, NULL, '217.254.240.213', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/5320 (KHTML, like Gecko) Chrome/40.0.874.0 Mobile Safari/5320', '2025-07-04 08:13:46', '2025-10-26 06:13:51'),
(265, 'hóa học', 'fines', '[\"status\"]', 39, NULL, '237.13.207.125', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 6.2; Trident/5.1)', '2025-08-18 03:54:11', '2025-10-26 06:13:51'),
(266, 'phạt', 'readers', NULL, 20, NULL, '126.97.147.146', 'Opera/8.95 (Windows NT 6.2; en-US) Presto/2.8.266 Version/11.00', '2025-07-17 22:49:41', '2025-10-26 06:13:51'),
(267, 'hóa học', 'borrows', NULL, 38, NULL, '7.241.90.34', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.0 (KHTML, like Gecko) Chrome/87.0.4557.44 Safari/536.0 EdgA/87.01039.55', '2025-10-09 19:56:53', '2025-10-26 06:13:51'),
(268, 'hóa học', 'readers', '[\"status\", \"year\", \"category\"]', 38, NULL, '103.244.16.205', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20210709 Firefox/37.0', '2025-07-27 14:41:28', '2025-10-26 06:13:51'),
(269, 'đặt trước', 'fines', '[\"status\"]', 42, 1, '199.65.161.74', 'Mozilla/5.0 (Windows NT 5.2; nl-NL; rv:1.9.1.20) Gecko/20120413 Firefox/37.0', '2025-05-29 06:22:50', '2025-10-26 06:13:51'),
(270, 'lịch sử', 'fines', '[\"category\", \"year\"]', 27, NULL, '181.136.193.114', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/82.0.4488.22 Safari/537.2 EdgA/82.01008.74', '2025-08-17 09:45:36', '2025-10-26 06:13:51'),
(271, 'toán học', 'books', '[\"category\", \"year\", \"status\"]', 14, NULL, '89.218.125.78', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20110709 Firefox/35.0', '2025-05-23 09:48:16', '2025-10-26 06:13:51'),
(272, 'lịch sử', 'books', NULL, 24, NULL, '80.119.248.161', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.2; Trident/3.0)', '2025-04-30 02:29:30', '2025-10-26 06:13:51'),
(273, 'đặt trước', 'fines', NULL, 31, NULL, '234.103.15.109', 'Opera/9.51 (X11; Linux x86_64; sl-SI) Presto/2.11.335 Version/10.00', '2025-08-29 23:07:59', '2025-10-26 06:13:51'),
(274, 'Phạm Thị D', 'borrows', '[\"status\"]', 38, NULL, '196.205.127.177', 'Opera/8.87 (Windows NT 5.0; sl-SI) Presto/2.12.260 Version/10.00', '2025-08-15 16:01:29', '2025-10-26 06:13:51'),
(275, 'công nghệ', 'fines', NULL, 25, NULL, '39.37.9.111', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_4 rv:5.0) Gecko/20210526 Firefox/37.0', '2025-08-22 19:55:00', '2025-10-26 06:13:51'),
(276, 'công nghệ', 'readers', NULL, 30, NULL, '72.210.74.11', 'Mozilla/5.0 (Windows NT 5.1; sl-SI; rv:1.9.0.20) Gecko/20230305 Firefox/36.0', '2025-07-25 22:52:52', '2025-10-26 06:13:51'),
(277, 'kinh tế', 'books', NULL, 50, NULL, '6.139.124.4', 'Mozilla/5.0 (Windows; U; Windows NT 4.0) AppleWebKit/532.49.4 (KHTML, like Gecko) Version/5.1 Safari/532.49.4', '2025-05-27 12:27:42', '2025-10-26 06:13:51'),
(278, 'triết học', 'fines', NULL, 28, NULL, '243.20.186.177', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_0 rv:3.0; nl-NL) AppleWebKit/533.39.4 (KHTML, like Gecko) Version/4.1 Safari/533.39.4', '2025-09-03 12:07:02', '2025-10-26 06:13:51'),
(279, 'sinh học', 'borrows', NULL, 21, NULL, '18.47.172.206', 'Mozilla/5.0 (Windows; U; Windows NT 6.1) AppleWebKit/532.9.6 (KHTML, like Gecko) Version/5.1 Safari/532.9.6', '2025-07-29 22:06:36', '2025-10-26 06:13:51'),
(280, 'sinh học', 'readers', NULL, 27, NULL, '68.67.244.37', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 6.2; Trident/3.0)', '2025-06-12 00:00:30', '2025-10-26 06:13:51'),
(281, 'hóa học', 'fines', NULL, 12, NULL, '29.219.149.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_1 like Mac OS X) AppleWebKit/536.2 (KHTML, like Gecko) Version/15.0 EdgiOS/83.01088.11 Mobile/15E148 Safari/536.2', '2025-08-17 20:02:52', '2025-10-26 06:13:51'),
(282, 'kinh tế', 'readers', '[\"status\", \"category\"]', 9, NULL, '200.179.251.199', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/3.1)', '2025-10-13 05:08:43', '2025-10-26 06:13:51'),
(283, 'chính trị', 'borrows', NULL, 8, NULL, '54.68.153.49', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/5360 (KHTML, like Gecko) Chrome/36.0.806.0 Mobile Safari/5360', '2025-07-10 19:42:31', '2025-10-26 06:13:51'),
(284, 'văn học', 'books', NULL, 50, NULL, '234.13.176.65', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_9 rv:4.0; sl-SI) AppleWebKit/533.28.7 (KHTML, like Gecko) Version/4.1 Safari/533.28.7', '2025-10-03 17:56:59', '2025-10-26 06:13:51'),
(285, 'Hoàng Văn E', 'borrows', NULL, 39, NULL, '227.99.125.228', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_7 rv:4.0) Gecko/20220621 Firefox/36.0', '2025-05-21 12:03:07', '2025-10-26 06:13:51'),
(286, 'Trần Thị B', 'borrows', NULL, 45, NULL, '103.227.86.34', 'Mozilla/5.0 (Windows; U; Windows NT 5.01) AppleWebKit/533.46.2 (KHTML, like Gecko) Version/5.0 Safari/533.46.2', '2025-05-18 11:48:53', '2025-10-26 06:13:51'),
(287, 'sinh học', 'borrows', '[\"category\", \"status\"]', 40, NULL, '169.177.60.150', 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_2_2 like Mac OS X; en-US) AppleWebKit/535.5.7 (KHTML, like Gecko) Version/3.0.5 Mobile/8B118 Safari/6535.5.7', '2025-05-05 04:12:30', '2025-10-26 06:13:51'),
(288, 'Nguyễn Văn A', 'readers', '[\"status\"]', 21, NULL, '103.70.241.26', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_2 rv:6.0) Gecko/20130617 Firefox/35.0', '2025-05-06 18:28:49', '2025-10-26 06:13:51'),
(289, 'chính trị', 'borrows', NULL, 7, NULL, '31.66.199.239', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows 95; Trident/4.0)', '2025-08-16 20:23:52', '2025-10-26 06:13:51'),
(290, 'mượn sách', 'fines', NULL, 25, NULL, '204.135.164.76', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7) AppleWebKit/533.1 (KHTML, like Gecko) Chrome/84.0.4130.70 Safari/533.1 Edg/84.01132.14', '2025-08-09 10:31:57', '2025-10-26 06:13:51'),
(291, 'văn học', 'readers', NULL, 8, NULL, '129.117.98.39', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows CE; Trident/4.0)', '2025-09-02 15:00:58', '2025-10-26 06:13:51'),
(292, 'tiểu thuyết', 'readers', '[\"category\"]', 21, NULL, '73.53.92.171', 'Mozilla/5.0 (Windows; U; Windows NT 5.01) AppleWebKit/535.40.5 (KHTML, like Gecko) Version/5.0.1 Safari/535.40.5', '2025-06-20 20:35:58', '2025-10-26 06:13:51'),
(293, 'đặt trước', 'fines', NULL, 15, NULL, '223.194.206.178', 'Mozilla/5.0 (Windows; U; Windows NT 6.0) AppleWebKit/532.24.1 (KHTML, like Gecko) Version/5.0.5 Safari/532.24.1', '2025-09-05 15:10:02', '2025-10-26 06:13:51'),
(294, 'sách', 'books', NULL, 24, NULL, '207.104.58.20', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_7 rv:6.0; nl-NL) AppleWebKit/532.29.2 (KHTML, like Gecko) Version/4.0.3 Safari/532.29.2', '2025-08-07 20:39:18', '2025-10-26 06:13:51'),
(295, 'Trần Thị B', 'books', NULL, 7, NULL, '184.78.22.244', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_4 rv:6.0) Gecko/20181202 Firefox/37.0', '2025-06-21 10:06:21', '2025-10-26 06:13:51'),
(296, 'kinh tế', 'borrows', NULL, 32, NULL, '155.56.207.22', 'Opera/9.94 (Windows NT 6.2; nl-NL) Presto/2.12.228 Version/12.00', '2025-10-19 10:27:06', '2025-10-26 06:13:51'),
(297, 'Lê Văn C', 'borrows', NULL, 12, NULL, '41.117.62.188', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows CE; Trident/3.1)', '2025-06-27 00:45:01', '2025-10-26 06:13:51'),
(298, 'Hoàng Văn E', 'fines', NULL, 19, NULL, '131.216.224.42', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_7 rv:5.0; nl-NL) AppleWebKit/531.10.3 (KHTML, like Gecko) Version/4.0 Safari/531.10.3', '2025-06-19 13:36:24', '2025-10-26 06:13:51'),
(299, 'Trần Thị B', 'books', '[\"category\", \"year\"]', 37, NULL, '68.232.5.24', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/536.2 (KHTML, like Gecko) Chrome/84.0.4841.75 Safari/536.2 EdgA/84.01086.51', '2025-06-02 13:32:42', '2025-10-26 06:13:51'),
(300, 'toán học', 'borrows', '[\"status\", \"year\"]', 19, NULL, '63.162.199.149', 'Opera/8.76 (X11; Linux i686; en-US) Presto/2.9.213 Version/12.00', '2025-05-10 07:51:03', '2025-10-26 06:13:51'),
(301, 'Phạm Thị D', 'readers', '[\"category\", \"year\", \"status\"]', 41, NULL, '146.163.23.174', 'Mozilla/5.0 (Windows 95) AppleWebKit/5312 (KHTML, like Gecko) Chrome/36.0.870.0 Mobile Safari/5312', '2025-09-09 17:22:07', '2025-10-26 06:13:51'),
(302, 'Lê Văn C', 'fines', NULL, 38, NULL, '201.211.191.194', 'Mozilla/5.0 (Windows NT 5.01; en-US; rv:1.9.2.20) Gecko/20220324 Firefox/36.0', '2025-07-31 11:02:13', '2025-10-26 06:13:51'),
(303, 'hóa học', 'borrows', '[\"year\"]', 33, NULL, '194.168.124.18', 'Mozilla/5.0 (Windows 95; nl-NL; rv:1.9.1.20) Gecko/20111012 Firefox/36.0', '2025-08-11 08:23:06', '2025-10-26 06:13:51'),
(304, 'Lê Văn C', 'borrows', '[\"year\", \"category\", \"status\"]', 12, NULL, '143.252.77.168', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.01; Trident/5.0)', '2025-09-28 08:59:38', '2025-10-26 06:13:51'),
(305, 'tiểu thuyết', 'borrows', NULL, 2, NULL, '61.227.206.145', 'Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20170803 Firefox/35.0', '2025-08-03 13:14:50', '2025-10-26 06:13:51'),
(306, 'tiểu thuyết', 'borrows', NULL, 16, 23, '54.231.33.105', 'Mozilla/5.0 (iPad; CPU OS 8_1_2 like Mac OS X; sl-SI) AppleWebKit/533.17.6 (KHTML, like Gecko) Version/3.0.5 Mobile/8B115 Safari/6533.17.6', '2025-08-08 16:31:30', '2025-10-26 06:13:51'),
(307, 'Phạm Thị D', 'fines', NULL, 46, NULL, '100.37.182.107', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows CE; Trident/5.0)', '2025-05-14 08:06:48', '2025-10-26 06:13:51'),
(308, 'trả sách', 'fines', NULL, 27, NULL, '246.118.43.223', 'Opera/9.51 (Windows NT 4.0; en-US) Presto/2.12.231 Version/10.00', '2025-06-29 02:40:51', '2025-10-26 06:13:51'),
(309, 'văn học', 'borrows', NULL, 29, NULL, '120.221.235.211', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/86.0.4624.73 Safari/532.1 Edg/86.01067.83', '2025-05-01 12:55:54', '2025-10-26 06:13:51'),
(310, 'khoa học', 'borrows', '[\"year\"]', 20, NULL, '117.165.10.213', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/5362 (KHTML, like Gecko) Chrome/37.0.804.0 Mobile Safari/5362', '2025-08-02 03:56:04', '2025-10-26 06:13:51'),
(311, 'Lê Văn C', 'fines', NULL, 13, NULL, '122.255.191.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_2_1 like Mac OS X; en-US) AppleWebKit/534.31.6 (KHTML, like Gecko) Version/4.0.5 Mobile/8B111 Safari/6534.31.6', '2025-08-27 19:59:55', '2025-10-26 06:13:51'),
(312, 'toán học', 'readers', NULL, 8, NULL, '4.105.99.202', 'Mozilla/5.0 (Windows CE) AppleWebKit/5360 (KHTML, like Gecko) Chrome/36.0.833.0 Mobile Safari/5360', '2025-10-10 02:17:21', '2025-10-26 06:13:51'),
(313, 'Trần Thị B', 'fines', '[\"year\", \"status\"]', 36, NULL, '113.92.56.186', 'Opera/8.80 (X11; Linux i686; nl-NL) Presto/2.10.293 Version/11.00', '2025-07-24 09:25:03', '2025-10-26 06:13:51'),
(314, 'sinh học', 'fines', NULL, 16, NULL, '10.12.102.106', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/91.0.4617.28 Safari/537.1 Edg/91.01027.36', '2025-08-22 19:51:01', '2025-10-26 06:13:51'),
(315, 'triết học', 'readers', NULL, 30, NULL, '74.212.179.21', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_9) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/96.0.4458.16 Safari/531.0 Edg/96.01012.31', '2025-06-03 12:59:53', '2025-10-26 06:13:51'),
(316, 'mượn sách', 'fines', NULL, 16, NULL, '233.144.224.234', 'Opera/9.72 (X11; Linux x86_64; en-US) Presto/2.10.292 Version/11.00', '2025-08-26 17:28:54', '2025-10-26 06:13:51'),
(317, 'mượn sách', 'books', NULL, 43, 23, '83.112.220.79', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.0 (KHTML, like Gecko) Chrome/93.0.4663.63 Safari/534.0 EdgA/93.01094.9', '2025-10-24 06:01:01', '2025-10-26 06:13:51'),
(318, 'sách', 'readers', '[\"year\"]', 24, NULL, '62.118.129.188', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/531.0 (KHTML, like Gecko) Version/15.0 EdgiOS/83.01044.19 Mobile/15E148 Safari/531.0', '2025-09-14 12:29:53', '2025-10-26 06:13:51'),
(319, 'phạt', 'borrows', '[\"status\"]', 41, NULL, '7.164.194.104', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5322 (KHTML, like Gecko) Chrome/40.0.890.0 Mobile Safari/5322', '2025-09-17 02:45:01', '2025-10-26 06:13:51'),
(320, 'vật lý', 'fines', NULL, 14, NULL, '109.129.58.87', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_7 rv:5.0) Gecko/20110828 Firefox/37.0', '2025-08-30 08:18:42', '2025-10-26 06:13:51'),
(321, 'trả sách', 'fines', NULL, 33, 23, '73.190.161.93', 'Mozilla/5.0 (Windows 95; sl-SI; rv:1.9.2.20) Gecko/20120412 Firefox/36.0', '2025-05-27 06:08:29', '2025-10-26 06:13:51'),
(322, 'vật lý', 'borrows', NULL, 13, NULL, '33.229.203.223', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_2 rv:5.0) Gecko/20230216 Firefox/35.0', '2025-08-17 09:00:00', '2025-10-26 06:13:51'),
(323, 'đặt trước', 'books', NULL, 3, NULL, '28.149.178.82', 'Opera/9.69 (X11; Linux x86_64; en-US) Presto/2.9.301 Version/10.00', '2025-09-11 11:17:41', '2025-10-26 06:13:51'),
(324, 'hóa học', 'readers', NULL, 5, NULL, '94.140.10.14', 'Mozilla/5.0 (Windows NT 5.01) AppleWebKit/537.0 (KHTML, like Gecko) Chrome/81.0.4088.11 Safari/537.0 Edg/81.01113.38', '2025-10-13 09:32:02', '2025-10-26 06:13:52'),
(325, 'Lê Văn C', 'readers', '[\"status\", \"category\", \"year\"]', 48, NULL, '40.26.148.82', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows CE; Trident/3.1)', '2025-05-08 11:25:38', '2025-10-26 06:13:52'),
(326, 'toán học', 'fines', NULL, 14, NULL, '201.236.36.44', 'Mozilla/5.0 (iPad; CPU OS 8_0_1 like Mac OS X; en-US) AppleWebKit/533.41.7 (KHTML, like Gecko) Version/3.0.5 Mobile/8B117 Safari/6533.41.7', '2025-05-19 13:48:12', '2025-10-26 06:13:52'),
(327, 'Trần Thị B', 'readers', NULL, 40, NULL, '11.166.49.40', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_1 rv:3.0; nl-NL) AppleWebKit/534.49.2 (KHTML, like Gecko) Version/4.0 Safari/534.49.2', '2025-07-23 14:18:03', '2025-10-26 06:13:52'),
(328, 'gia hạn', 'readers', '[\"status\", \"category\", \"year\"]', 39, NULL, '209.1.59.31', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows 98; Trident/4.0)', '2025-05-23 11:31:26', '2025-10-26 06:13:52'),
(329, 'Lê Văn C', 'fines', NULL, 26, NULL, '204.13.232.219', 'Mozilla/5.0 (Windows NT 4.0) AppleWebKit/5332 (KHTML, like Gecko) Chrome/36.0.871.0 Mobile Safari/5332', '2025-08-06 20:47:44', '2025-10-26 06:13:52'),
(330, 'lịch sử', 'borrows', NULL, 45, NULL, '96.58.77.19', 'Opera/8.10 (Windows NT 6.0; nl-NL) Presto/2.8.318 Version/10.00', '2025-04-27 00:57:53', '2025-10-26 06:13:52'),
(331, 'sinh học', 'borrows', '[\"category\"]', 38, NULL, '132.59.221.213', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_2 rv:5.0) Gecko/20161009 Firefox/35.0', '2025-06-16 08:20:48', '2025-10-26 06:13:52'),
(332, 'chính trị', 'readers', '[\"category\", \"year\", \"status\"]', 17, NULL, '245.2.138.180', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows CE; Trident/4.1)', '2025-07-03 20:07:50', '2025-10-26 06:13:52'),
(333, 'trả sách', 'books', NULL, 5, NULL, '208.166.80.75', 'Mozilla/5.0 (Windows; U; Windows NT 5.1) AppleWebKit/533.23.7 (KHTML, like Gecko) Version/4.0 Safari/533.23.7', '2025-10-09 04:31:27', '2025-10-26 06:13:52'),
(334, 'Trần Thị B', 'borrows', NULL, 1, 1, '12.153.89.183', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5341 (KHTML, like Gecko) Chrome/36.0.887.0 Mobile Safari/5341', '2025-06-01 01:22:07', '2025-10-26 06:13:52'),
(335, 'sách', 'readers', '[\"status\", \"year\"]', 10, NULL, '198.50.238.115', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/534.2 (KHTML, like Gecko) Chrome/86.0.4175.89 Safari/534.2 Edg/86.01143.88', '2025-08-25 16:23:22', '2025-10-26 06:13:52'),
(336, 'gia hạn', 'readers', NULL, 27, 23, '141.131.228.116', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_0 rv:5.0) Gecko/20201118 Firefox/36.0', '2025-06-20 02:43:54', '2025-10-26 06:13:52'),
(337, 'sinh học', 'books', NULL, 17, 23, '165.215.142.202', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_9 rv:5.0) Gecko/20131009 Firefox/35.0', '2025-07-10 04:47:01', '2025-10-26 06:13:52'),
(338, 'Hoàng Văn E', 'fines', '[\"year\", \"status\"]', 26, NULL, '18.176.168.62', 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0_2 like Mac OS X; nl-NL) AppleWebKit/534.4.1 (KHTML, like Gecko) Version/3.0.5 Mobile/8B117 Safari/6534.4.1', '2025-07-26 05:33:33', '2025-10-26 06:13:52'),
(339, 'toán học', 'borrows', '[\"year\", \"category\", \"status\"]', 24, NULL, '19.204.240.50', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20240819 Firefox/36.0', '2025-08-29 17:17:08', '2025-10-26 06:13:52'),
(340, 'công nghệ', 'borrows', NULL, 20, NULL, '106.22.83.252', 'Opera/9.65 (X11; Linux x86_64; nl-NL) Presto/2.11.199 Version/12.00', '2025-08-30 17:27:20', '2025-10-26 06:13:52'),
(341, 'Phạm Thị D', 'borrows', '[\"year\", \"category\", \"status\"]', 6, NULL, '219.73.49.167', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/533.0 (KHTML, like Gecko) Chrome/82.0.4788.86 Safari/533.0 Edg/82.01132.29', '2025-09-09 17:55:47', '2025-10-26 06:13:52'),
(342, 'Lê Văn C', 'readers', NULL, 48, NULL, '223.63.177.31', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2 rv:4.0; en-US) AppleWebKit/533.42.6 (KHTML, like Gecko) Version/5.0.1 Safari/533.42.6', '2025-07-27 13:04:15', '2025-10-26 06:13:52'),
(343, 'văn học', 'books', NULL, 39, NULL, '135.37.237.119', 'Mozilla/5.0 (Windows; U; Windows NT 6.2) AppleWebKit/532.44.3 (KHTML, like Gecko) Version/4.0.3 Safari/532.44.3', '2025-07-31 23:48:03', '2025-10-26 06:13:52'),
(344, 'chính trị', 'borrows', NULL, 10, NULL, '187.154.115.239', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows 98; Win 9x 4.90; Trident/3.0)', '2025-05-06 06:39:26', '2025-10-26 06:13:52'),
(345, 'toán học', 'readers', NULL, 19, NULL, '140.100.182.109', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_2_1 like Mac OS X; nl-NL) AppleWebKit/534.34.1 (KHTML, like Gecko) Version/3.0.5 Mobile/8B116 Safari/6534.34.1', '2025-08-28 06:00:47', '2025-10-26 06:13:52'),
(346, 'phạt', 'borrows', '[\"status\", \"year\"]', 42, NULL, '207.112.223.174', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5341 (KHTML, like Gecko) Chrome/37.0.858.0 Mobile Safari/5341', '2025-10-10 11:36:16', '2025-10-26 06:13:52'),
(347, 'sách', 'readers', NULL, 20, NULL, '54.227.128.127', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_5 rv:6.0; nl-NL) AppleWebKit/534.29.5 (KHTML, like Gecko) Version/4.0.1 Safari/534.29.5', '2025-04-30 01:24:58', '2025-10-26 06:13:52'),
(348, 'tiểu thuyết', 'fines', NULL, 39, NULL, '8.109.42.58', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows 98; Win 9x 4.90; Trident/4.0)', '2025-09-27 06:03:40', '2025-10-26 06:13:52'),
(349, 'đặt trước', 'fines', NULL, 1, NULL, '234.247.29.216', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5351 (KHTML, like Gecko) Chrome/40.0.870.0 Mobile Safari/5351', '2025-10-09 06:02:07', '2025-10-26 06:13:52'),
(350, 'vật lý', 'borrows', NULL, 32, NULL, '252.116.78.171', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20250329 Firefox/37.0', '2025-07-03 20:20:56', '2025-10-26 06:13:52'),
(351, 'triết học', 'borrows', NULL, 43, NULL, '115.24.126.100', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/5342 (KHTML, like Gecko) Chrome/36.0.892.0 Mobile Safari/5342', '2025-08-18 12:13:33', '2025-10-26 06:13:52'),
(352, 'khoa học', 'borrows', NULL, 25, NULL, '164.238.33.71', 'Opera/9.94 (Windows 98; Win 9x 4.90; sl-SI) Presto/2.10.241 Version/11.00', '2025-05-30 21:48:47', '2025-10-26 06:13:52'),
(353, 'Hoàng Văn E', 'readers', NULL, 29, NULL, '7.250.177.50', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_2) AppleWebKit/5330 (KHTML, like Gecko) Chrome/39.0.859.0 Mobile Safari/5330', '2025-07-01 08:50:18', '2025-10-26 06:13:52'),
(354, 'chính trị', 'fines', '[\"status\"]', 13, NULL, '97.199.178.227', 'Mozilla/5.0 (Windows 95) AppleWebKit/5330 (KHTML, like Gecko) Chrome/36.0.838.0 Mobile Safari/5330', '2025-10-20 16:44:13', '2025-10-26 06:13:52'),
(355, 'chính trị', 'books', NULL, 30, NULL, '123.64.232.4', 'Mozilla/5.0 (iPad; CPU OS 8_0_1 like Mac OS X; nl-NL) AppleWebKit/533.17.2 (KHTML, like Gecko) Version/4.0.5 Mobile/8B119 Safari/6533.17.2', '2025-08-25 10:52:59', '2025-10-26 06:13:52'),
(356, 'khoa học', 'readers', NULL, 26, NULL, '82.145.75.152', 'Mozilla/5.0 (X11; Linux x86_64; rv:6.0) Gecko/20140227 Firefox/35.0', '2025-07-01 07:16:33', '2025-10-26 06:13:52'),
(357, 'vật lý', 'readers', NULL, 31, NULL, '53.212.40.190', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_2 rv:4.0) Gecko/20180906 Firefox/37.0', '2025-06-24 04:33:21', '2025-10-26 06:13:52'),
(358, 'văn học', 'fines', NULL, 40, NULL, '30.121.251.188', 'Opera/8.15 (Windows 98; nl-NL) Presto/2.10.208 Version/12.00', '2025-09-14 07:40:58', '2025-10-26 06:13:52'),
(359, 'lịch sử', 'borrows', NULL, 0, NULL, '210.75.251.182', 'Opera/8.48 (X11; Linux i686; sl-SI) Presto/2.10.258 Version/12.00', '2025-08-10 02:26:17', '2025-10-26 06:13:52'),
(360, 'triết học', 'readers', '[\"year\"]', 45, NULL, '244.92.148.229', 'Opera/9.68 (Windows NT 4.0; nl-NL) Presto/2.11.329 Version/12.00', '2025-08-20 16:33:08', '2025-10-26 06:13:52'),
(361, 'trả sách', 'readers', NULL, 44, NULL, '106.131.66.6', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/535.2 (KHTML, like Gecko) Version/15.0 EdgiOS/84.01052.20 Mobile/15E148 Safari/535.2', '2025-05-20 20:00:06', '2025-10-26 06:13:52'),
(362, 'đặt trước', 'fines', NULL, 1, NULL, '75.103.57.8', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_3) AppleWebKit/5332 (KHTML, like Gecko) Chrome/40.0.888.0 Mobile Safari/5332', '2025-10-03 05:41:46', '2025-10-26 06:13:52'),
(363, 'trả sách', 'books', NULL, 20, NULL, '171.35.160.205', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/531.0 (KHTML, like Gecko) Chrome/82.0.4793.42 Safari/531.0 Edg/82.01095.20', '2025-08-31 13:43:04', '2025-10-26 06:13:52'),
(364, 'vật lý', 'borrows', '[\"year\", \"status\"]', 5, NULL, '60.130.246.165', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows 98; Trident/5.1)', '2025-05-23 09:31:47', '2025-10-26 06:13:52'),
(365, 'phạt', 'books', '[\"status\"]', 32, NULL, '63.89.188.71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2 like Mac OS X) AppleWebKit/533.2 (KHTML, like Gecko) Version/15.0 EdgiOS/90.01095.89 Mobile/15E148 Safari/533.2', '2025-07-14 08:16:12', '2025-10-26 06:13:52'),
(366, 'sinh học', 'readers', NULL, 0, NULL, '146.83.176.248', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_7 rv:3.0) Gecko/20240222 Firefox/35.0', '2025-07-24 17:05:26', '2025-10-26 06:13:52'),
(367, 'triết học', 'borrows', '[\"status\", \"year\", \"category\"]', 48, NULL, '246.161.26.187', 'Mozilla/5.0 (Windows NT 4.0; en-US; rv:1.9.1.20) Gecko/20120603 Firefox/37.0', '2025-07-14 15:30:41', '2025-10-26 06:13:52'),
(368, 'Lê Văn C', 'borrows', NULL, 4, NULL, '74.227.54.58', 'Mozilla/5.0 (Windows NT 5.0; nl-NL; rv:1.9.1.20) Gecko/20200421 Firefox/36.0', '2025-06-11 00:03:28', '2025-10-26 06:13:52'),
(369, 'Hoàng Văn E', 'readers', NULL, 0, NULL, '5.125.19.197', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/5332 (KHTML, like Gecko) Chrome/37.0.864.0 Mobile Safari/5332', '2025-09-05 04:19:21', '2025-10-26 06:13:52'),
(370, 'sách', 'books', NULL, 43, NULL, '144.227.81.162', 'Mozilla/5.0 (Windows; U; Windows NT 6.0) AppleWebKit/531.1.5 (KHTML, like Gecko) Version/5.1 Safari/531.1.5', '2025-09-01 15:44:35', '2025-10-26 06:13:52'),
(371, 'văn học', 'borrows', NULL, 18, NULL, '5.197.93.88', 'Mozilla/5.0 (Windows 95; en-US; rv:1.9.2.20) Gecko/20100127 Firefox/37.0', '2025-05-20 13:50:43', '2025-10-26 06:13:52'),
(372, 'vật lý', 'fines', NULL, 49, NULL, '132.5.225.197', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/5311 (KHTML, like Gecko) Chrome/38.0.826.0 Mobile Safari/5311', '2025-06-15 19:37:35', '2025-10-26 06:13:52'),
(373, 'chính trị', 'fines', NULL, 37, NULL, '100.125.165.31', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_1 rv:2.0) Gecko/20141019 Firefox/35.0', '2025-06-12 10:23:20', '2025-10-26 06:13:52'),
(374, 'khoa học', 'fines', NULL, 34, NULL, '3.35.74.154', 'Opera/9.53 (X11; Linux i686; en-US) Presto/2.8.169 Version/11.00', '2025-07-22 20:46:33', '2025-10-26 06:13:52'),
(375, 'mượn sách', 'fines', NULL, 33, NULL, '67.161.197.127', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.0 (KHTML, like Gecko) Chrome/86.0.4402.77 Safari/534.0 Edg/86.01016.8', '2025-05-23 02:04:04', '2025-10-26 06:13:52'),
(376, 'Phạm Thị D', 'readers', NULL, 30, NULL, '230.3.142.42', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/5350 (KHTML, like Gecko) Chrome/38.0.841.0 Mobile Safari/5350', '2025-09-21 17:20:39', '2025-10-26 06:13:52'),
(377, 'triết học', 'readers', '[\"year\"]', 14, NULL, '7.92.93.253', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_4) AppleWebKit/5361 (KHTML, like Gecko) Chrome/36.0.802.0 Mobile Safari/5361', '2025-06-21 02:38:16', '2025-10-26 06:13:52'),
(378, 'triết học', 'readers', NULL, 41, NULL, '249.52.13.232', 'Mozilla/5.0 (Windows CE; sl-SI; rv:1.9.0.20) Gecko/20150117 Firefox/36.0', '2025-07-21 12:49:04', '2025-10-26 06:13:52'),
(379, 'Trần Thị B', 'books', NULL, 26, NULL, '168.248.179.60', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 4.0; Trident/3.0)', '2025-10-12 20:08:15', '2025-10-26 06:13:52'),
(380, 'tiểu thuyết', 'books', NULL, 33, NULL, '83.3.108.40', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_0 rv:4.0) Gecko/20240715 Firefox/36.0', '2025-06-10 13:14:34', '2025-10-26 06:13:52'),
(381, 'hóa học', 'books', NULL, 22, NULL, '175.82.28.28', 'Mozilla/5.0 (Windows NT 6.1; sl-SI; rv:1.9.2.20) Gecko/20231110 Firefox/37.0', '2025-10-21 20:51:13', '2025-10-26 06:13:52'),
(382, 'sinh học', 'borrows', NULL, 3, 23, '8.193.147.133', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_1 rv:5.0) Gecko/20250829 Firefox/35.0', '2025-08-01 15:34:09', '2025-10-26 06:13:52'),
(383, 'vật lý', 'borrows', '[\"category\", \"year\", \"status\"]', 19, NULL, '64.107.79.51', 'Mozilla/5.0 (Windows; U; Windows NT 5.0) AppleWebKit/531.26.1 (KHTML, like Gecko) Version/5.0.4 Safari/531.26.1', '2025-10-06 04:09:25', '2025-10-26 06:13:52'),
(384, 'trả sách', 'books', NULL, 25, NULL, '9.84.225.85', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_6_6) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/94.0.4466.29 Safari/532.0 Edg/94.01065.90', '2025-10-06 00:03:17', '2025-10-26 06:13:52'),
(385, 'Nguyễn Văn A', 'borrows', NULL, 50, NULL, '115.122.123.109', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_2_2 like Mac OS X; sl-SI) AppleWebKit/532.6.3 (KHTML, like Gecko) Version/4.0.5 Mobile/8B115 Safari/6532.6.3', '2025-10-25 23:49:11', '2025-10-26 06:13:52'),
(386, 'lịch sử', 'fines', '[\"year\", \"category\"]', 9, NULL, '129.116.112.80', 'Opera/8.25 (Windows NT 6.0; en-US) Presto/2.9.162 Version/10.00', '2025-07-01 10:24:43', '2025-10-26 06:13:52'),
(387, 'sinh học', 'readers', NULL, 44, NULL, '247.106.7.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_2_2 like Mac OS X; nl-NL) AppleWebKit/534.26.3 (KHTML, like Gecko) Version/3.0.5 Mobile/8B115 Safari/6534.26.3', '2025-10-13 13:24:20', '2025-10-26 06:13:52'),
(388, 'trả sách', 'borrows', '[\"status\", \"year\"]', 4, NULL, '62.106.225.111', 'Opera/8.21 (Windows NT 6.1; sl-SI) Presto/2.11.252 Version/11.00', '2025-09-16 04:08:27', '2025-10-26 06:13:52'),
(389, 'đặt trước', 'borrows', NULL, 50, NULL, '200.166.70.69', 'Mozilla/5.0 (Windows 95) AppleWebKit/5331 (KHTML, like Gecko) Chrome/36.0.824.0 Mobile Safari/5331', '2025-08-20 01:09:28', '2025-10-26 06:13:52'),
(390, 'Lê Văn C', 'borrows', NULL, 32, NULL, '235.233.138.88', 'Mozilla/5.0 (Windows; U; Windows NT 5.01) AppleWebKit/535.32.2 (KHTML, like Gecko) Version/4.0.2 Safari/535.32.2', '2025-07-27 08:02:54', '2025-10-26 06:13:52'),
(391, 'đặt trước', 'borrows', '[\"status\", \"year\"]', 23, NULL, '98.153.45.222', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows 98; Win 9x 4.90; Trident/3.1)', '2025-07-13 02:15:10', '2025-10-26 06:13:52'),
(392, 'khoa học', 'books', '[\"status\", \"category\"]', 11, NULL, '195.180.107.236', 'Opera/9.85 (Windows NT 6.1; nl-NL) Presto/2.8.249 Version/10.00', '2025-05-12 10:52:14', '2025-10-26 06:13:52'),
(393, 'chính trị', 'readers', NULL, 49, NULL, '36.163.128.242', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 4.0; Trident/4.1)', '2025-06-20 06:26:45', '2025-10-26 06:13:52'),
(394, 'quá hạn', 'books', NULL, 32, NULL, '64.79.159.50', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5340 (KHTML, like Gecko) Chrome/38.0.817.0 Mobile Safari/5340', '2025-05-15 14:44:53', '2025-10-26 06:13:52'),
(395, 'văn học', 'readers', NULL, 23, NULL, '157.228.62.52', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.1; Trident/4.1)', '2025-05-03 11:49:55', '2025-10-26 06:13:52'),
(396, 'Lê Văn C', 'borrows', NULL, 49, NULL, '99.100.40.229', 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/5331 (KHTML, like Gecko) Chrome/40.0.802.0 Mobile Safari/5331', '2025-07-25 15:29:38', '2025-10-26 06:13:52'),
(397, 'Hoàng Văn E', 'readers', NULL, 27, NULL, '122.68.153.71', 'Mozilla/5.0 (Windows NT 5.0; en-US; rv:1.9.1.20) Gecko/20211128 Firefox/36.0', '2025-09-20 18:36:15', '2025-10-26 06:13:52'),
(398, 'Trần Thị B', 'readers', NULL, 6, NULL, '233.225.106.95', 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1_1 like Mac OS X; sl-SI) AppleWebKit/534.39.3 (KHTML, like Gecko) Version/4.0.5 Mobile/8B117 Safari/6534.39.3', '2025-06-11 07:03:57', '2025-10-26 06:13:52'),
(399, 'sinh học', 'books', NULL, 26, NULL, '72.73.169.183', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5341 (KHTML, like Gecko) Chrome/37.0.879.0 Mobile Safari/5341', '2025-08-04 22:30:41', '2025-10-26 06:13:52'),
(400, 'kinh tế', 'books', '[\"category\", \"status\", \"year\"]', 11, NULL, '77.21.42.227', 'Opera/8.55 (X11; Linux x86_64; en-US) Presto/2.9.198 Version/10.00', '2025-05-30 18:25:37', '2025-10-26 06:13:52'),
(401, 'công nghệ', 'borrows', '[\"category\", \"status\", \"year\"]', 18, NULL, '54.154.239.123', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)', '2025-06-11 16:11:23', '2025-10-26 06:13:52'),
(402, 'triết học', 'books', '[\"category\", \"year\"]', 1, NULL, '218.37.183.100', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/533.0 (KHTML, like Gecko) Chrome/83.0.4029.16 Safari/533.0 EdgA/83.01135.85', '2025-05-18 07:24:55', '2025-10-26 06:13:52'),
(403, 'tiểu thuyết', 'books', NULL, 46, NULL, '196.179.165.252', 'Mozilla/5.0 (Windows; U; Windows 98; Win 9x 4.90) AppleWebKit/535.34.4 (KHTML, like Gecko) Version/5.0 Safari/535.34.4', '2025-07-11 12:48:15', '2025-10-26 06:13:52'),
(404, 'trả sách', 'readers', NULL, 47, NULL, '166.122.91.89', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.2; Trident/5.0)', '2025-04-26 18:23:35', '2025-10-26 06:13:52'),
(405, 'chính trị', 'readers', NULL, 45, NULL, '51.32.253.9', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows 98; Trident/4.1)', '2025-07-07 19:50:39', '2025-10-26 06:13:52'),
(406, 'văn học', 'fines', '[\"year\", \"category\", \"status\"]', 48, NULL, '122.154.253.42', 'Opera/8.85 (Windows NT 6.2; sl-SI) Presto/2.9.350 Version/11.00', '2025-09-12 05:39:57', '2025-10-26 06:13:52'),
(407, 'toán học', 'fines', '[\"year\", \"status\"]', 14, NULL, '248.185.146.180', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.2; Trident/3.0)', '2025-10-11 18:46:42', '2025-10-26 06:13:52'),
(408, 'mượn sách', 'readers', NULL, 20, NULL, '156.8.195.211', 'Opera/8.69 (Windows NT 5.1; nl-NL) Presto/2.8.223 Version/12.00', '2025-07-26 07:37:01', '2025-10-26 06:13:52'),
(409, 'Trần Thị B', 'books', '[\"status\", \"year\", \"category\"]', 12, NULL, '9.50.223.58', 'Mozilla/5.0 (Windows 98; Win 9x 4.90) AppleWebKit/5321 (KHTML, like Gecko) Chrome/38.0.836.0 Mobile Safari/5321', '2025-05-06 14:53:37', '2025-10-26 06:13:52'),
(410, 'kinh tế', 'fines', NULL, 0, NULL, '229.147.14.55', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5351 (KHTML, like Gecko) Chrome/39.0.857.0 Mobile Safari/5351', '2025-07-15 15:52:26', '2025-10-26 06:13:52'),
(411, 'Hoàng Văn E', 'books', '[\"year\", \"status\", \"category\"]', 21, NULL, '237.218.78.105', 'Mozilla/5.0 (X11; Linux x86_64; rv:6.0) Gecko/20181005 Firefox/36.0', '2025-10-04 01:07:39', '2025-10-26 06:13:52'),
(412, 'công nghệ', 'readers', '[\"category\", \"year\", \"status\"]', 35, NULL, '222.139.252.245', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_2) AppleWebKit/5312 (KHTML, like Gecko) Chrome/39.0.888.0 Mobile Safari/5312', '2025-09-19 23:19:31', '2025-10-26 06:13:52'),
(413, 'hóa học', 'fines', NULL, 1, NULL, '182.10.206.125', 'Mozilla/5.0 (Windows 95; nl-NL; rv:1.9.2.20) Gecko/20220818 Firefox/37.0', '2025-08-20 00:44:35', '2025-10-26 06:13:52'),
(414, 'triết học', 'borrows', NULL, 49, NULL, '142.11.76.177', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.1)', '2025-05-17 15:34:52', '2025-10-26 06:13:52'),
(415, 'vật lý', 'fines', NULL, 26, NULL, '171.239.224.132', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows CE; Trident/4.0)', '2025-09-11 02:04:36', '2025-10-26 06:13:52'),
(416, 'kinh tế', 'borrows', NULL, 28, NULL, '217.107.167.59', 'Mozilla/5.0 (Windows 98; Win 9x 4.90) AppleWebKit/5331 (KHTML, like Gecko) Chrome/40.0.817.0 Mobile Safari/5331', '2025-05-25 12:14:35', '2025-10-26 06:13:52'),
(417, 'sách', 'borrows', NULL, 29, 1, '37.156.115.171', 'Mozilla/5.0 (Windows 95) AppleWebKit/5322 (KHTML, like Gecko) Chrome/36.0.861.0 Mobile Safari/5322', '2025-07-25 09:31:46', '2025-10-26 06:13:52'),
(418, 'tiểu thuyết', 'books', NULL, 18, NULL, '168.144.195.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_1 like Mac OS X) AppleWebKit/533.0 (KHTML, like Gecko) Version/15.0 EdgiOS/96.01024.84 Mobile/15E148 Safari/533.0', '2025-09-04 03:25:58', '2025-10-26 06:13:52'),
(419, 'văn học', 'borrows', '[\"category\", \"status\", \"year\"]', 27, NULL, '132.146.215.25', 'Opera/8.97 (X11; Linux x86_64; sl-SI) Presto/2.9.325 Version/11.00', '2025-08-13 13:12:57', '2025-10-26 06:13:52'),
(420, 'Phạm Thị D', 'fines', NULL, 15, NULL, '39.58.227.254', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_2 rv:2.0) Gecko/20101214 Firefox/36.0', '2025-09-04 20:10:44', '2025-10-26 06:13:52'),
(421, 'kinh tế', 'borrows', NULL, 18, NULL, '99.66.227.106', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows 95; Trident/5.1)', '2025-07-10 05:01:12', '2025-10-26 06:13:52'),
(422, 'quá hạn', 'books', NULL, 21, NULL, '157.94.31.35', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_3) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/98.0.4784.57 Safari/533.2 Edg/98.01135.67', '2025-10-17 07:00:57', '2025-10-26 06:13:52'),
(423, 'đặt trước', 'books', '[\"year\", \"category\"]', 3, NULL, '20.34.151.73', 'Mozilla/5.0 (compatible; MSIE 11.0; Windows NT 5.1; Trident/4.1)', '2025-07-12 08:51:53', '2025-10-26 06:13:52'),
(424, 'sinh học', 'fines', NULL, 48, NULL, '51.241.69.142', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_2) AppleWebKit/5342 (KHTML, like Gecko) Chrome/40.0.865.0 Mobile Safari/5342', '2025-07-11 14:50:36', '2025-10-26 06:13:52'),
(425, 'mượn sách', 'readers', '[\"status\", \"category\"]', 43, NULL, '221.246.48.173', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 4.0; Trident/3.0)', '2025-09-04 00:37:20', '2025-10-26 06:13:52'),
(426, 'Trần Thị B', 'books', NULL, 21, NULL, '125.79.255.45', 'Mozilla/5.0 (X11; Linux i686; rv:7.0) Gecko/20161016 Firefox/37.0', '2025-09-04 14:45:12', '2025-10-26 06:13:52'),
(427, 'Trần Thị B', 'readers', NULL, 1, NULL, '236.243.92.28', 'Mozilla/5.0 (iPad; CPU OS 7_1_2 like Mac OS X; nl-NL) AppleWebKit/532.3.3 (KHTML, like Gecko) Version/4.0.5 Mobile/8B118 Safari/6532.3.3', '2025-06-28 18:59:46', '2025-10-26 06:13:52'),
(428, 'công nghệ', 'fines', NULL, 42, NULL, '155.242.200.25', 'Opera/8.99 (X11; Linux i686; nl-NL) Presto/2.9.160 Version/12.00', '2025-07-21 09:13:56', '2025-10-26 06:13:52'),
(429, 'Hoàng Văn E', 'borrows', NULL, 15, 23, '222.68.246.101', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/3.0)', '2025-06-07 12:29:23', '2025-10-26 06:13:52'),
(430, 'quá hạn', 'books', NULL, 16, NULL, '48.194.218.247', 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20240905 Firefox/36.0', '2025-05-17 10:39:39', '2025-10-26 06:13:52'),
(431, 'sinh học', 'fines', NULL, 44, NULL, '133.10.103.119', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.01; Trident/5.1)', '2025-05-13 21:39:39', '2025-10-26 06:13:52'),
(432, 'phạt', 'borrows', '[\"status\", \"category\"]', 17, NULL, '183.198.87.7', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_7_9 rv:3.0) Gecko/20250228 Firefox/36.0', '2025-09-15 20:47:02', '2025-10-26 06:13:52'),
(433, 'khoa học', 'fines', NULL, 39, NULL, '78.61.145.183', 'Mozilla/5.0 (iPad; CPU OS 8_1_1 like Mac OS X; en-US) AppleWebKit/534.15.4 (KHTML, like Gecko) Version/3.0.5 Mobile/8B114 Safari/6534.15.4', '2025-06-23 03:53:18', '2025-10-26 06:13:52'),
(434, 'sinh học', 'fines', NULL, 47, NULL, '166.114.198.173', 'Opera/9.12 (Windows 98; nl-NL) Presto/2.10.192 Version/10.00', '2025-10-09 10:10:08', '2025-10-26 06:13:52'),
(435, 'trả sách', 'books', '[\"year\", \"category\"]', 39, NULL, '65.189.17.220', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5321 (KHTML, like Gecko) Chrome/40.0.854.0 Mobile Safari/5321', '2025-05-23 15:38:16', '2025-10-26 06:13:52'),
(436, 'hóa học', 'borrows', NULL, 30, NULL, '62.54.248.235', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_5) AppleWebKit/532.1 (KHTML, like Gecko) Chrome/85.0.4149.22 Safari/532.1 Edg/85.01076.1', '2025-09-27 23:22:57', '2025-10-26 06:13:52'),
(437, 'triết học', 'readers', '[\"year\"]', 44, NULL, '166.252.113.59', 'Mozilla/5.0 (Windows NT 4.0; sl-SI; rv:1.9.1.20) Gecko/20190807 Firefox/36.0', '2025-10-16 11:11:58', '2025-10-26 06:13:52'),
(438, 'gia hạn', 'readers', '[\"status\", \"year\"]', 38, NULL, '194.225.26.26', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.01; Trident/3.0)', '2025-05-18 23:35:38', '2025-10-26 06:13:52'),
(439, 'chính trị', 'borrows', '[\"category\", \"year\", \"status\"]', 5, NULL, '255.191.139.145', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/531.1 (KHTML, like Gecko) Chrome/86.0.4488.68 Safari/531.1 EdgA/86.01019.74', '2025-09-19 11:19:08', '2025-10-26 06:13:52'),
(440, 'sách', 'fines', NULL, 1, NULL, '152.177.115.58', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_7 rv:6.0) Gecko/20241207 Firefox/35.0', '2025-06-20 02:35:32', '2025-10-26 06:13:52'),
(441, 'lịch sử', 'fines', NULL, 6, NULL, '239.34.31.14', 'Mozilla/5.0 (Windows NT 5.2) AppleWebKit/5330 (KHTML, like Gecko) Chrome/38.0.822.0 Mobile Safari/5330', '2025-09-16 17:09:43', '2025-10-26 06:13:52'),
(442, 'Lê Văn C', 'readers', NULL, 18, 23, '109.148.228.149', 'Mozilla/5.0 (Windows; U; Windows NT 6.1) AppleWebKit/535.13.6 (KHTML, like Gecko) Version/4.0.1 Safari/535.13.6', '2025-08-07 15:46:58', '2025-10-26 06:13:52'),
(443, 'khoa học', 'books', NULL, 42, NULL, '248.5.114.92', 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/5341 (KHTML, like Gecko) Chrome/39.0.816.0 Mobile Safari/5341', '2025-08-01 01:18:39', '2025-10-26 06:13:52'),
(444, 'phạt', 'fines', NULL, 5, NULL, '183.189.255.250', 'Mozilla/5.0 (X11; Linux i686; rv:7.0) Gecko/20121219 Firefox/35.0', '2025-06-01 00:05:51', '2025-10-26 06:13:52'),
(445, 'văn học', 'readers', NULL, 39, NULL, '218.121.226.26', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_2) AppleWebKit/536.2 (KHTML, like Gecko) Chrome/98.0.4468.15 Safari/536.2 Edg/98.01109.33', '2025-05-25 13:22:12', '2025-10-26 06:13:52'),
(446, 'Trần Thị B', 'books', NULL, 34, NULL, '39.121.175.144', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/537.2 (KHTML, like Gecko) Version/15.0 EdgiOS/82.01005.24 Mobile/15E148 Safari/537.2', '2025-10-22 22:50:53', '2025-10-26 06:13:52'),
(447, 'Phạm Thị D', 'fines', NULL, 29, NULL, '177.55.180.216', 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_8_0 rv:2.0) Gecko/20170107 Firefox/36.0', '2025-08-20 01:03:59', '2025-10-26 06:13:52'),
(448, 'triết học', 'books', NULL, 27, NULL, '141.150.219.226', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.2; Trident/3.1)', '2025-06-18 07:06:58', '2025-10-26 06:13:52'),
(449, 'gia hạn', 'fines', NULL, 42, NULL, '180.198.212.231', 'Opera/8.20 (X11; Linux x86_64; nl-NL) Presto/2.10.333 Version/12.00', '2025-09-11 03:38:46', '2025-10-26 06:13:52'),
(450, 'chính trị', 'fines', NULL, 21, 1, '35.8.203.79', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.0; Trident/5.1)', '2025-08-31 21:56:49', '2025-10-26 06:13:52'),
(451, 'văn học', 'borrows', NULL, 50, NULL, '141.21.132.64', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_6 rv:6.0) Gecko/20170712 Firefox/37.0', '2025-09-09 19:05:22', '2025-10-26 06:13:52'),
(452, 'tiểu thuyết', 'readers', NULL, 28, NULL, '91.89.254.94', 'Mozilla/5.0 (Windows NT 5.2) AppleWebKit/5320 (KHTML, like Gecko) Chrome/36.0.846.0 Mobile Safari/5320', '2025-08-30 00:37:20', '2025-10-26 06:13:52'),
(453, 'chính trị', 'books', NULL, 22, NULL, '19.41.143.227', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5331 (KHTML, like Gecko) Chrome/40.0.829.0 Mobile Safari/5331', '2025-05-07 08:12:03', '2025-10-26 06:13:52'),
(454, 'trả sách', 'fines', NULL, 12, NULL, '108.186.195.119', 'Mozilla/5.0 (Windows NT 4.0; sl-SI; rv:1.9.1.20) Gecko/20120701 Firefox/36.0', '2025-07-12 22:01:37', '2025-10-26 06:13:52'),
(455, 'sách', 'fines', '[\"year\", \"category\"]', 6, NULL, '121.7.206.44', 'Opera/8.37 (Windows 98; en-US) Presto/2.10.293 Version/10.00', '2025-10-26 06:13:11', '2025-10-26 06:13:53'),
(456, 'gia hạn', 'fines', '[\"year\", \"status\", \"category\"]', 39, NULL, '175.31.8.104', 'Mozilla/5.0 (Windows NT 6.0; nl-NL; rv:1.9.0.20) Gecko/20240227 Firefox/37.0', '2025-04-26 09:11:13', '2025-10-26 06:13:53'),
(457, 'toán học', 'books', NULL, 7, NULL, '144.20.252.197', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3 rv:5.0) Gecko/20161104 Firefox/35.0', '2025-08-15 22:20:21', '2025-10-26 06:13:53'),
(458, 'Phạm Thị D', 'borrows', '[\"year\", \"status\", \"category\"]', 21, NULL, '90.168.51.114', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_4) AppleWebKit/536.2 (KHTML, like Gecko) Chrome/95.0.4524.93 Safari/536.2 Edg/95.01146.81', '2025-06-25 00:22:32', '2025-10-26 06:13:53'),
(459, 'Lê Văn C', 'fines', NULL, 13, 23, '56.129.135.214', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_3) AppleWebKit/5352 (KHTML, like Gecko) Chrome/39.0.816.0 Mobile Safari/5352', '2025-10-13 08:40:16', '2025-10-26 06:13:53'),
(460, 'sinh học', 'borrows', '[\"status\", \"category\"]', 43, NULL, '176.86.123.42', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_7) AppleWebKit/5352 (KHTML, like Gecko) Chrome/39.0.888.0 Mobile Safari/5352', '2025-10-18 01:54:01', '2025-10-26 06:13:53'),
(461, 'đặt trước', 'borrows', NULL, 13, NULL, '86.227.2.241', 'Opera/9.53 (X11; Linux x86_64; nl-NL) Presto/2.10.308 Version/11.00', '2025-09-16 01:57:27', '2025-10-26 06:13:53'),
(462, 'gia hạn', 'borrows', NULL, 9, 1, '214.144.76.112', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows 98; Trident/4.0)', '2025-06-30 19:44:09', '2025-10-26 06:13:53'),
(463, 'trả sách', 'fines', NULL, 17, NULL, '22.160.206.52', 'Opera/9.62 (X11; Linux i686; sl-SI) Presto/2.9.184 Version/11.00', '2025-08-27 17:36:37', '2025-10-26 06:13:53'),
(464, 'phạt', 'fines', '[\"category\"]', 42, NULL, '177.102.109.134', 'Mozilla/5.0 (Windows NT 5.0; en-US; rv:1.9.2.20) Gecko/20240502 Firefox/35.0', '2025-08-26 07:22:31', '2025-10-26 06:13:53'),
(465, 'trả sách', 'borrows', '[\"category\"]', 6, NULL, '165.60.240.156', 'Mozilla/5.0 (Windows; U; Windows 98) AppleWebKit/532.5.7 (KHTML, like Gecko) Version/4.0.1 Safari/532.5.7', '2025-09-20 20:13:33', '2025-10-26 06:13:53'),
(466, 'khoa học', 'fines', '[\"year\"]', 5, NULL, '133.38.17.14', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_5_6 rv:3.0; sl-SI) AppleWebKit/531.19.1 (KHTML, like Gecko) Version/4.0 Safari/531.19.1', '2025-06-25 22:16:47', '2025-10-26 06:13:53'),
(467, 'mượn sách', 'readers', NULL, 25, NULL, '250.45.7.2', 'Opera/8.90 (Windows NT 5.01; nl-NL) Presto/2.9.164 Version/11.00', '2025-05-17 13:11:59', '2025-10-26 06:13:53'),
(468, 'đặt trước', 'books', '[\"category\"]', 12, NULL, '171.88.81.20', 'Mozilla/5.0 (Windows 98; nl-NL; rv:1.9.1.20) Gecko/20200506 Firefox/35.0', '2025-05-02 05:23:54', '2025-10-26 06:13:53'),
(469, 'Trần Thị B', 'readers', NULL, 44, NULL, '160.35.209.92', 'Mozilla/5.0 (X11; Linux i686; rv:5.0) Gecko/20241129 Firefox/35.0', '2025-08-04 07:24:01', '2025-10-26 06:13:53'),
(470, 'khoa học', 'books', NULL, 5, NULL, '109.10.2.144', 'Mozilla/5.0 (Windows NT 5.01; nl-NL; rv:1.9.0.20) Gecko/20130220 Firefox/35.0', '2025-06-03 03:14:58', '2025-10-26 06:13:53'),
(471, 'Nguyễn Văn A', 'readers', NULL, 24, NULL, '220.211.196.139', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/79.0.4730.29 Safari/537.1 EdgA/79.01099.39', '2025-07-22 15:59:31', '2025-10-26 06:13:53'),
(472, 'tiểu thuyết', 'books', NULL, 27, NULL, '19.28.197.235', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_1 rv:2.0; sl-SI) AppleWebKit/535.36.3 (KHTML, like Gecko) Version/4.0.4 Safari/535.36.3', '2025-08-18 18:47:24', '2025-10-26 06:13:53'),
(473, 'quá hạn', 'readers', NULL, 7, NULL, '117.46.136.233', 'Mozilla/5.0 (iPad; CPU OS 8_0_1 like Mac OS X; sl-SI) AppleWebKit/535.11.7 (KHTML, like Gecko) Version/3.0.5 Mobile/8B117 Safari/6535.11.7', '2025-09-28 08:12:54', '2025-10-26 06:13:53'),
(474, 'công nghệ', 'books', NULL, 19, NULL, '37.107.81.206', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_5_7 rv:2.0) Gecko/20210506 Firefox/36.0', '2025-06-22 06:16:35', '2025-10-26 06:13:53'),
(475, 'sách', 'borrows', NULL, 32, NULL, '123.168.218.2', 'Mozilla/5.0 (Windows; U; Windows NT 5.1) AppleWebKit/534.28.5 (KHTML, like Gecko) Version/5.1 Safari/534.28.5', '2025-05-27 03:55:25', '2025-10-26 06:13:53');
INSERT INTO `search_logs` (`id`, `query`, `type`, `filters`, `results_count`, `user_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(476, 'chính trị', 'readers', NULL, 21, NULL, '212.39.147.97', 'Opera/9.28 (X11; Linux x86_64; nl-NL) Presto/2.12.301 Version/10.00', '2025-09-15 16:15:57', '2025-10-26 06:13:53'),
(477, 'khoa học', 'books', NULL, 43, NULL, '240.62.128.39', 'Mozilla/5.0 (compatible; MSIE 7.0; Windows 98; Trident/4.0)', '2025-06-01 08:50:48', '2025-10-26 06:13:53'),
(478, 'văn học', 'borrows', '[\"status\"]', 42, NULL, '15.133.244.39', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/531.0 (KHTML, like Gecko) Version/15.0 EdgiOS/83.01015.12 Mobile/15E148 Safari/531.0', '2025-07-25 17:59:39', '2025-10-26 06:13:53'),
(479, 'Lê Văn C', 'borrows', NULL, 40, NULL, '197.76.44.161', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5350 (KHTML, like Gecko) Chrome/40.0.889.0 Mobile Safari/5350', '2025-06-02 13:03:50', '2025-10-26 06:13:53'),
(480, 'Lê Văn C', 'books', NULL, 19, NULL, '195.56.180.203', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/5322 (KHTML, like Gecko) Chrome/40.0.840.0 Mobile Safari/5322', '2025-06-15 20:33:10', '2025-10-26 06:13:53'),
(481, 'công nghệ', 'fines', NULL, 25, NULL, '178.201.148.82', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows 98; Win 9x 4.90; Trident/5.1)', '2025-10-11 18:05:47', '2025-10-26 06:13:53'),
(482, 'Nguyễn Văn A', 'readers', '[\"category\", \"year\", \"status\"]', 20, NULL, '162.55.179.168', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_8) AppleWebKit/532.2 (KHTML, like Gecko) Chrome/86.0.4282.76 Safari/532.2 Edg/86.01090.95', '2025-05-06 16:48:58', '2025-10-26 06:13:53'),
(483, 'sách', 'books', '[\"status\", \"category\", \"year\"]', 22, NULL, '37.123.248.192', 'Opera/8.57 (Windows NT 5.01; nl-NL) Presto/2.9.193 Version/12.00', '2025-07-29 13:02:35', '2025-10-26 06:13:53'),
(484, 'sách', 'fines', NULL, 6, NULL, '59.50.9.30', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 5.1; Trident/3.1)', '2025-06-04 12:30:32', '2025-10-26 06:13:53'),
(485, 'mượn sách', 'books', NULL, 35, NULL, '219.11.95.210', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 6.2; Trident/4.0)', '2025-10-04 07:23:05', '2025-10-26 06:13:53'),
(486, 'lịch sử', 'borrows', NULL, 11, NULL, '167.13.22.188', 'Mozilla/5.0 (compatible; MSIE 5.0; Windows 95; Trident/3.1)', '2025-09-05 21:46:29', '2025-10-26 06:13:53'),
(487, 'vật lý', 'fines', NULL, 4, NULL, '243.126.230.74', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/531.0 (KHTML, like Gecko) Version/15.0 EdgiOS/98.01020.99 Mobile/15E148 Safari/531.0', '2025-05-11 02:10:26', '2025-10-26 06:13:53'),
(488, 'sách', 'books', NULL, 21, NULL, '30.161.228.67', 'Mozilla/5.0 (Windows 95) AppleWebKit/5341 (KHTML, like Gecko) Chrome/37.0.859.0 Mobile Safari/5341', '2025-04-27 22:24:02', '2025-10-26 06:13:53'),
(489, 'hóa học', 'borrows', '[\"category\", \"status\"]', 45, NULL, '51.148.219.11', 'Opera/8.58 (Windows NT 6.0; en-US) Presto/2.12.333 Version/12.00', '2025-07-12 20:54:48', '2025-10-26 06:13:53'),
(490, 'khoa học', 'fines', NULL, 8, NULL, '90.159.177.26', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_5 rv:6.0) Gecko/20221028 Firefox/36.0', '2025-05-10 08:08:23', '2025-10-26 06:13:53'),
(491, 'Nguyễn Văn A', 'books', NULL, 37, NULL, '20.244.92.165', 'Opera/8.31 (Windows 95; nl-NL) Presto/2.10.333 Version/11.00', '2025-06-30 20:36:48', '2025-10-26 06:13:53'),
(492, 'sách', 'fines', NULL, 48, NULL, '109.35.250.88', 'Mozilla/5.0 (iPad; CPU OS 8_2_1 like Mac OS X; nl-NL) AppleWebKit/535.47.5 (KHTML, like Gecko) Version/3.0.5 Mobile/8B113 Safari/6535.47.5', '2025-08-23 12:43:21', '2025-10-26 06:13:53'),
(493, 'trả sách', 'borrows', NULL, 34, 1, '167.90.184.58', 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 4.0; Trident/5.1)', '2025-05-27 10:56:04', '2025-10-26 06:13:53'),
(494, 'chính trị', 'readers', '[\"status\", \"year\"]', 34, NULL, '36.176.165.201', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_7 rv:2.0; nl-NL) AppleWebKit/531.5.5 (KHTML, like Gecko) Version/4.0.4 Safari/531.5.5', '2025-07-31 09:40:03', '2025-10-26 06:13:53'),
(495, 'Phạm Thị D', 'fines', NULL, 43, NULL, '16.119.89.67', 'Opera/8.67 (X11; Linux x86_64; en-US) Presto/2.9.337 Version/12.00', '2025-10-03 09:50:07', '2025-10-26 06:13:53'),
(496, 'hóa học', 'borrows', '[\"status\", \"year\"]', 11, NULL, '53.213.238.196', 'Opera/8.28 (Windows NT 6.1; nl-NL) Presto/2.8.219 Version/12.00', '2025-08-29 12:00:00', '2025-10-26 06:13:53'),
(497, 'quá hạn', 'borrows', NULL, 3, NULL, '108.82.243.53', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/531.0 (KHTML, like Gecko) Version/15.0 EdgiOS/83.01015.47 Mobile/15E148 Safari/531.0', '2025-08-15 20:06:07', '2025-10-26 06:13:53'),
(498, 'sách', 'fines', NULL, 8, NULL, '49.69.141.188', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_7) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/98.0.4101.76 Safari/535.1 Edg/98.01065.72', '2025-07-19 01:35:21', '2025-10-26 06:13:53'),
(499, 'vật lý', 'borrows', NULL, 9, NULL, '19.235.215.148', 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 4.0; Trident/3.0)', '2025-05-15 09:34:38', '2025-10-26 06:13:53'),
(500, 'Trần Thị B', 'books', NULL, 14, NULL, '53.36.211.231', 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_8_4) AppleWebKit/5332 (KHTML, like Gecko) Chrome/40.0.838.0 Mobile Safari/5332', '2025-10-07 17:15:52', '2025-10-26 06:13:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping_logs`
--

CREATE TABLE `shipping_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `borrow_id` bigint UNSIGNED NOT NULL,
  `shipper_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipper_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('cho_xu_ly','dang_giao','da_giao','khong_nhan','hoan_hang') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dang_giao',
  `receiver_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipper_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `proof_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `shipping_logs`
--

INSERT INTO `shipping_logs` (`id`, `borrow_id`, `shipper_name`, `shipper_phone`, `status`, `receiver_note`, `shipper_note`, `proof_image`, `delivered_at`, `created_at`, `updated_at`) VALUES
(12, 229, NULL, NULL, 'dang_giao', NULL, '', 'shipping_proofs/gW5YNo1ULpUmhY3GIZW61N9no6C58qI42Gsm0RYX.jpg', NULL, '2025-12-01 03:18:14', '2025-12-01 04:03:31'),
(13, 232, NULL, NULL, 'cho_xu_ly', NULL, 'Phiếu mượn đã được xác nhận và đang xử lý giao', NULL, NULL, '2025-12-01 06:06:26', '2025-12-01 06:06:26'),
(16, 236, NULL, NULL, 'dang_giao', NULL, 'Phiếu mượn đã được xác nhận và đang xử lý giao', NULL, NULL, '2025-12-01 15:23:15', '2025-12-01 15:25:29'),
(17, 237, NULL, NULL, 'cho_xu_ly', NULL, 'Phiếu mượn đã xác nhận.', NULL, NULL, '2025-12-01 15:50:52', '2025-12-01 15:50:52'),
(18, 237, NULL, NULL, 'cho_xu_ly', NULL, 'Phiếu mượn đã xác nhận.', NULL, NULL, '2025-12-01 15:50:57', '2025-12-01 15:50:57'),
(22, 237, NULL, NULL, 'cho_xu_ly', NULL, 'Phiếu mượn đã xác nhận.', NULL, NULL, '2025-12-01 15:56:45', '2025-12-01 15:56:45'),
(23, 237, NULL, NULL, 'hoan_hang', NULL, 'Phiếu mượn đã xác nhận.', 'shipping_proofs/d831567a-1385-4c60-acd2-ec64b5d23048.png', NULL, '2025-12-01 15:58:44', '2025-12-01 16:04:11'),
(25, 239, NULL, NULL, 'da_giao', NULL, 'Phiếu mượn đã xác nhận.', 'shipping_proofs/3cf5365c-6517-481a-9ef1-edaa817aad2a.jpg', NULL, '2025-12-01 16:17:33', '2025-12-01 16:21:43'),
(26, 240, NULL, NULL, 'cho_xu_ly', NULL, 'Phiếu mượn đã xác nhận.', NULL, NULL, '2025-12-01 16:18:11', '2025-12-01 16:18:11'),
(27, 241, NULL, NULL, 'cho_xu_ly', NULL, 'Phiếu mượn đã được duyệt và chuyển sang đang mượn.', NULL, NULL, '2025-12-01 16:21:07', '2025-12-01 16:21:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `address` text,
  `so_cccd` varchar(20) DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `province`, `district`, `address`, `so_cccd`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`, `avatar`) VALUES
(1, 'Super Admin', 'admin@library.com', '0583388961', NULL, NULL, 'k', '07534375699', 'admin', '2025-11-21 17:05:04', '$2y$10$xaIcE5lWRjXCTv5qdCip/e3CrlfvyvJ0qRdCdojjzwFjGfousS0CK', NULL, '2025-10-26 06:13:32', '2025-11-21 17:05:04', NULL, NULL),
(23, 'Người dùng', 'user@library.com', NULL, NULL, NULL, NULL, NULL, 'user', '2025-11-21 17:05:04', '$2y$10$V/KClNt0oMrW3rFKAHNdde20YXPhUP/B8QU2AwtZ1sPQTL40p2LDG', NULL, '2025-10-26 06:13:39', '2025-11-21 17:05:04', NULL, NULL),
(25, 'hoang123', 'hoangproxz123@gmail.com', NULL, NULL, NULL, NULL, NULL, 'user', '2025-11-21 17:05:04', '$2y$10$HMegyZQfb3h/tX2eqbHz8eyi1fBlarm3dlKtLMx7wRHCDZctaZsby', NULL, '2025-11-05 05:41:26', '2025-11-21 17:05:04', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_verifications`
--

CREATE TABLE `user_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `id_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_images` json DEFAULT NULL,
  `verified_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `reader_id` bigint UNSIGNED DEFAULT NULL,
  `ma` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã giảm giá',
  `loai` enum('percentage','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại giảm giá: phần trăm hoặc cố định',
  `gia_tri` decimal(10,2) NOT NULL COMMENT 'Giá trị giảm',
  `so_luong` int NOT NULL DEFAULT '0' COMMENT 'Số lượng mã khả dụng',
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả chi tiết mã giảm',
  `don_toi_thieu` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Giá trị đơn hàng tối thiểu để áp dụng',
  `ngay_bat_dau` date DEFAULT NULL COMMENT 'Ngày bắt đầu hiệu lực',
  `ngay_ket_thuc` date DEFAULT NULL COMMENT 'Ngày hết hạn',
  `kich_hoat` tinyint NOT NULL DEFAULT '1' COMMENT '1: hoạt động, 0: ngưng',
  `trang_thai` enum('active','inactive','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Trạng thái mã',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `reader_id`, `ma`, `loai`, `gia_tri`, `so_luong`, `mo_ta`, `don_toi_thieu`, `ngay_bat_dau`, `ngay_ket_thuc`, `kich_hoat`, `trang_thai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'abc123', 'fixed', 20000.00, 1, NULL, 1.00, '2025-11-17', '2025-12-25', 1, 'active', '2025-11-17 09:48:44', '2025-11-25 01:35:52', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  ADD KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `audit_logs_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `authors_email_unique` (`email`);

--
-- Chỉ mục cho bảng `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backups_type_status_index` (`type`,`status`),
  ADD KEY `backups_created_at_index` (`created_at`),
  ADD KEY `backups_created_by_index` (`created_by`),
  ADD KEY `backups_restored_by_foreign` (`restored_by`);

--
-- Chỉ mục cho bảng `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `books_category_id_foreign` (`category_id`),
  ADD KEY `books_nha_xuat_ban_id_foreign` (`nha_xuat_ban_id`),
  ADD KEY `books_category_id_index` (`category_id`),
  ADD KEY `books_nha_xuat_ban_id_index` (`nha_xuat_ban_id`),
  ADD KEY `books_trang_thai_index` (`trang_thai`),
  ADD KEY `books_trang_thai_category_id_index` (`trang_thai`,`category_id`),
  ADD KEY `books_is_featured_index` (`is_featured`),
  ADD KEY `books_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrows_reader_id_foreign` (`reader_id`),
  ADD KEY `borrows_librarian_id_foreign` (`librarian_id`),
  ADD KEY `borrows_reader_id_index` (`reader_id`),
  ADD KEY `borrows_trang_thai_index` (`trang_thai`),
  ADD KEY `borrows_trang_thai_ngay_hen_tra_index` (`trang_thai`),
  ADD KEY `borrows_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `borrow_carts`
--
ALTER TABLE `borrow_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_carts_reader_id_foreign` (`reader_id`),
  ADD KEY `borrow_carts_user_id_index` (`user_id`);

--
-- Chỉ mục cho bảng `borrow_cart_items`
--
ALTER TABLE `borrow_cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_cart_items_borrow_cart_id_index` (`borrow_cart_id`),
  ADD KEY `borrow_cart_items_book_id_index` (`book_id`);

--
-- Chỉ mục cho bảng `borrow_items`
--
ALTER TABLE `borrow_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_items_borrow_id_index` (`borrow_id`),
  ADD KEY `borrow_items_book_id_index` (`book_id`);

--
-- Chỉ mục cho bảng `borrow_payments`
--
ALTER TABLE `borrow_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `borrow_payments_borrow_id_foreign` (`borrow_id`),
  ADD KEY `borrow_payments_borrow_item_id_foreign` (`borrow_item_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_trang_thai_index` (`trang_thai`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_review_id_foreign` (`review_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_ma_nganh_unique` (`ma_nganh`),
  ADD KEY `departments_faculty_id_foreign` (`faculty_id`);

--
-- Chỉ mục cho bảng `display_allocations`
--
ALTER TABLE `display_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `display_allocations_inventory_id_foreign` (`inventory_id`),
  ADD KEY `display_allocations_allocated_by_foreign` (`allocated_by`),
  ADD KEY `display_allocations_book_id_display_start_date_index` (`book_id`,`display_start_date`),
  ADD KEY `display_allocations_display_area_index` (`display_area`);

--
-- Chỉ mục cho bảng `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `email_campaigns`
--
ALTER TABLE `email_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_campaigns_created_by_foreign` (`created_by`),
  ADD KEY `email_campaigns_status_scheduled_at_index` (`status`,`scheduled_at`);

--
-- Chỉ mục cho bảng `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_logs_email_sent_at_index` (`email`,`sent_at`),
  ADD KEY `email_logs_campaign_id_status_index` (`campaign_id`,`status`);

--
-- Chỉ mục cho bảng `email_subscribers`
--
ALTER TABLE `email_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_subscribers_email_unique` (`email`),
  ADD KEY `email_subscribers_user_id_foreign` (`user_id`),
  ADD KEY `email_subscribers_status_subscribed_at_index` (`status`,`subscribed_at`),
  ADD KEY `email_subscribers_email_index` (`email`);

--
-- Chỉ mục cho bảng `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculties_ma_khoa_unique` (`ma_khoa`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_book_id_unique` (`user_id`,`book_id`),
  ADD KEY `favorites_book_id_foreign` (`book_id`);

--
-- Chỉ mục cho bảng `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fines_borrow_id_foreign` (`borrow_id`),
  ADD KEY `fines_reader_id_foreign` (`reader_id`),
  ADD KEY `fines_created_by_foreign` (`created_by`),
  ADD KEY `borrow_item_id` (`borrow_item_id`);

--
-- Chỉ mục cho bảng `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventories_barcode_unique` (`barcode`),
  ADD KEY `inventories_created_by_foreign` (`created_by`),
  ADD KEY `inventories_book_id_status_index` (`book_id`,`status`),
  ADD KEY `inventories_barcode_index` (`barcode`),
  ADD KEY `inventories_storage_type_index` (`storage_type`),
  ADD KEY `inventories_receipt_id_foreign` (`receipt_id`);

--
-- Chỉ mục cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_receipts_receipt_number_unique` (`receipt_number`),
  ADD KEY `inventory_receipts_book_id_foreign` (`book_id`),
  ADD KEY `inventory_receipts_received_by_foreign` (`received_by`),
  ADD KEY `inventory_receipts_approved_by_foreign` (`approved_by`),
  ADD KEY `inventory_receipts_receipt_date_status_index` (`receipt_date`,`status`),
  ADD KEY `inventory_receipts_receipt_number_index` (`receipt_number`);

--
-- Chỉ mục cho bảng `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_transactions_performed_by_foreign` (`performed_by`),
  ADD KEY `inventory_transactions_inventory_id_type_index` (`inventory_id`,`type`),
  ADD KEY `inventory_transactions_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `librarians`
--
ALTER TABLE `librarians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `librarians_ma_thu_thu_unique` (`ma_thu_thu`),
  ADD KEY `librarians_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Chỉ mục cho bảng `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_logs_template_id_foreign` (`template_id`);

--
-- Chỉ mục cho bảng `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_status_index` (`user_id`,`status`),
  ADD KEY `orders_order_number_index` (`order_number`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_purchasable_book_id_foreign` (`purchasable_book_id`),
  ADD KEY `order_items_order_id_index` (`order_id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `purchasable_books`
--
ALTER TABLE `purchasable_books`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `readers`
--
ALTER TABLE `readers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `readers_email_unique` (`email`),
  ADD UNIQUE KEY `readers_so_the_doc_gia_unique` (`so_the_doc_gia`),
  ADD KEY `readers_user_id_foreign` (`user_id`),
  ADD KEY `readers_faculty_id_foreign` (`faculty_id`),
  ADD KEY `readers_department_id_foreign` (`department_id`),
  ADD KEY `readers_email_index` (`email`),
  ADD KEY `readers_so_the_doc_gia_index` (`so_the_doc_gia`),
  ADD KEY `readers_trang_thai_index` (`trang_thai`),
  ADD KEY `readers_user_id_index` (`user_id`),
  ADD KEY `readers_faculty_id_index` (`faculty_id`),
  ADD KEY `readers_department_id_index` (`department_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_book_id_user_id_unique` (`book_id`,`user_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Chỉ mục cho bảng `search_logs`
--
ALTER TABLE `search_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_logs_user_id_foreign` (`user_id`),
  ADD KEY `search_logs_query_type_index` (`query`,`type`),
  ADD KEY `search_logs_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `shipping_logs`
--
ALTER TABLE `shipping_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_logs_borrow_id_foreign` (`borrow_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- Chỉ mục cho bảng `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_verifications_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vouchers_reader_id_foreign` (`reader_id`),
  ADD KEY `vouchers_ma_index` (`ma`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=358;

--
-- AUTO_INCREMENT cho bảng `authors`
--
ALTER TABLE `authors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `backups`
--
ALTER TABLE `backups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT cho bảng `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT cho bảng `borrow_carts`
--
ALTER TABLE `borrow_carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `borrow_cart_items`
--
ALTER TABLE `borrow_cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `borrow_items`
--
ALTER TABLE `borrow_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT cho bảng `borrow_payments`
--
ALTER TABLE `borrow_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `display_allocations`
--
ALTER TABLE `display_allocations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `email_campaigns`
--
ALTER TABLE `email_campaigns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `email_subscribers`
--
ALTER TABLE `email_subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `fines`
--
ALTER TABLE `fines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT cho bảng `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=401;

--
-- AUTO_INCREMENT cho bảng `librarians`
--
ALTER TABLE `librarians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT cho bảng `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT cho bảng `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `publishers`
--
ALTER TABLE `publishers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `purchasable_books`
--
ALTER TABLE `purchasable_books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `readers`
--
ALTER TABLE `readers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `search_logs`
--
ALTER TABLE `search_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;

--
-- AUTO_INCREMENT cho bảng `shipping_logs`
--
ALTER TABLE `shipping_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `user_verifications`
--
ALTER TABLE `user_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `backups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `backups_restored_by_foreign` FOREIGN KEY (`restored_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `books_nha_xuat_ban_id_foreign` FOREIGN KEY (`nha_xuat_ban_id`) REFERENCES `publishers` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `borrows_librarian_id_foreign` FOREIGN KEY (`librarian_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrows_reader_id_foreign` FOREIGN KEY (`reader_id`) REFERENCES `readers` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `borrow_carts`
--
ALTER TABLE `borrow_carts`
  ADD CONSTRAINT `borrow_carts_reader_id_foreign` FOREIGN KEY (`reader_id`) REFERENCES `readers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `borrow_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `borrow_cart_items`
--
ALTER TABLE `borrow_cart_items`
  ADD CONSTRAINT `borrow_cart_items_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_cart_items_borrow_cart_id_foreign` FOREIGN KEY (`borrow_cart_id`) REFERENCES `borrow_carts` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `borrow_payments`
--
ALTER TABLE `borrow_payments`
  ADD CONSTRAINT `borrow_payments_borrow_id_foreign` FOREIGN KEY (`borrow_id`) REFERENCES `borrows` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_payments_borrow_item_id_foreign` FOREIGN KEY (`borrow_item_id`) REFERENCES `borrow_items` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `display_allocations`
--
ALTER TABLE `display_allocations`
  ADD CONSTRAINT `display_allocations_allocated_by_foreign` FOREIGN KEY (`allocated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `display_allocations_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `display_allocations_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `email_campaigns`
--
ALTER TABLE `email_campaigns`
  ADD CONSTRAINT `email_campaigns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `email_logs`
--
ALTER TABLE `email_logs`
  ADD CONSTRAINT `email_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `email_campaigns` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `email_subscribers`
--
ALTER TABLE `email_subscribers`
  ADD CONSTRAINT `email_subscribers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_borrow_id_foreign` FOREIGN KEY (`borrow_id`) REFERENCES `borrows` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fines_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventories_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `inventory_receipts` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  ADD CONSTRAINT `inventory_receipts_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_receipts_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_transactions_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `librarians`
--
ALTER TABLE `librarians`
  ADD CONSTRAINT `librarians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD CONSTRAINT `notification_logs_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `notification_templates` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_purchasable_book_id_foreign` FOREIGN KEY (`purchasable_book_id`) REFERENCES `purchasable_books` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `readers`
--
ALTER TABLE `readers`
  ADD CONSTRAINT `readers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `readers_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `readers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `search_logs`
--
ALTER TABLE `search_logs`
  ADD CONSTRAINT `search_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `shipping_logs`
--
ALTER TABLE `shipping_logs`
  ADD CONSTRAINT `shipping_logs_borrow_id_foreign` FOREIGN KEY (`borrow_id`) REFERENCES `borrows` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD CONSTRAINT `user_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_reader_id_foreign` FOREIGN KEY (`reader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
