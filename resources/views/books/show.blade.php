<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sách: {{ $book->ten_sach }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* --- Thiết lập chung --- */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5; 
            color: #333;
        }

        h1, h2, h3 {
            margin-top: 0;
        }

        .content-wrapper {
            display: flex;
            width: 90%; 
            max-width: 1300px;
            margin: 20px auto;
            gap: 20px; 
        }

        /* Header sẽ sử dụng style từ style.css */

        /* --- MAIN CONTENT & SIDEBAR LAYOUT --- */
        .main-content {
            flex: 3; 
            background-color: white;
            padding: 20px 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar {
            flex: 1; 
            padding-top: 10px;
        }

        /* --- BORROW ORDER SUMMARY --- */
        .borrow-summary-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #333;
            font-size: 14px;
        }

        .summary-value {
            font-weight: 600;
            color: #2196F3;
            font-size: 14px;
        }

        .summary-value.discount {
            color: #333;
        }

        .discount-input-section {
            margin: 15px 0;
            padding: 15px 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .discount-input-wrapper {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .discount-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .discount-input::placeholder {
            color: #999;
        }

        .apply-discount-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }

        .apply-discount-btn:hover {
            background-color: #45a049;
        }

        .summary-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            margin-top: 10px;
        }

        .summary-total-label {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .summary-total-value {
            font-weight: bold;
            color: #FF6B35;
            font-size: 18px;
        }

        .btn-borrow-now {
            width: 100%;
            padding: 15px;
            background-color: #FF6B35;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        .btn-borrow-now:hover {
            background-color: #e55a2b;
        }

        .btn-borrow-now:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .terms-text {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 12px;
            line-height: 1.5;
        }

        .terms-text strong {
            color: #333;
            font-weight: 600;
        }

        .summary-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }

        .summary-detail-label {
            color: #666;
        }

        .summary-detail-value {
            color: #333;
            font-weight: 500;
        }

        .breadcrumb {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #d9534f;
        }

        /* --- BOOK DETAILS --- */
        .book-detail-section {
            padding: 20px 0;
        }

        .book-summary {
            display: flex;
            gap: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .book-cover {
            width: 200px;
            height: auto;
            flex-shrink: 0;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .info-and-buy {
            flex: 1;
        }

        .info-and-buy h1 {
            font-size: 1.5em;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .info-and-buy p {
            margin: 5px 0;
            color: #666;
        }

        .rating {
            font-size: 0.9em;
            color: #666;
            margin: 10px 0;
        }

        .stars {
            color: orange;
            letter-spacing: 2px;
        }

        /* --- BUY OPTIONS & BUTTONS --- */
        .buy-options {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #fcfcfc;
        }

        .buy-options label {
            font-weight: bold;
            display: block;
            margin-bottom: 15px;
        }

        .option-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .option-row .type {
            font-weight: bold;
            font-size: 1.1em;
        }

        .option-row .duration {
            color: #666;
        }

        .option-row input[type="checkbox"] {
            cursor: pointer;
            accent-color: #4CAF50;
        }

        .option-row input[type="checkbox"]:checked {
            accent-color: #4CAF50;
        }

        .price, .final-price {
            font-weight: bold;
            color: #cc0000;
            font-size: 1.1em;
        }

        .total-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-top: 1px solid #eee;
            margin-top: 15px;
        }

        .total-price span:first-child {
            font-weight: bold;
        }

        .action-buttons {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            border: none;
            transition: opacity 0.2s;
            font-size: 1em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-buy {
            background-color: #cc0000;
            color: white;
            flex: 1;
        }

        .btn-cart {
            background-color: white; 
            color: #cc0000;
            border: 1px solid #cc0000;
            flex: 1;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* --- MODAL PHIẾU MƯỢN --- */
        .borrow-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .borrow-modal-overlay.active {
            display: flex;
        }

        .borrow-modal {
            background: white;
            border-radius: 12px;
            padding: 20px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .borrow-modal-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .borrow-modal-header h2 {
            margin: 0;
            color: #cc0000;
            font-size: 1.5em;
        }

        .borrow-modal-header .subtitle {
            color: #666;
            font-size: 0.85em;
            margin-top: 3px;
        }

        .borrow-info-section {
            margin-bottom: 12px;
        }

        .borrow-info-section h3 {
            color: #333;
            font-size: 1em;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #eee;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
            font-size: 0.95em;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #333;
            font-weight: bold;
            text-align: right;
        }

        .price-breakdown {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 0.95em;
        }

        .price-row.total {
            border-top: 2px solid #cc0000;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 1.1em;
            font-weight: bold;
            color: #cc0000;
        }

        .borrow-modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .btn-modal {
            flex: 1;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }

        .btn-modal-cancel {
            background: #f5f5f5;
            color: #333;
        }

        .btn-modal-cancel:hover {
            background: #e0e0e0;
        }

        .btn-modal-confirm {
            background: #cc0000;
            color: white;
        }

        .btn-modal-confirm:hover {
            background: #aa0000;
        }

        .btn-modal-confirm:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .close-modal:hover {
            background: #f5f5f5;
            color: #333;
        }

        .loading-spinner {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        /* --- TABS --- */
        .tab-section {
            display: flex;
            gap: 20px;
            margin: 30px 0;
            border-bottom: 2px solid #eee;
        }

        .tab-link {
            padding: 15px 0;
            text-decoration: none;
            color: #666;
            font-weight: 500;
            position: relative;
            transition: color 0.3s;
        }

        .tab-link.active {
            color: #333;
            font-weight: bold;
        }

        .tab-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #ffcc00;
        }

        .description-section {
            padding: 20px 0;
            line-height: 1.8;
            color: #555;
        }

        /* --- METADATA TABLE --- */
        .metadata-table {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .metadata-table h2 {
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .book-metadata {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 0.9em;
        }

        .book-metadata tr {
            border-bottom: 1px dashed #ddd;
        }

        .book-metadata td {
            padding: 10px 5px;
            vertical-align: top;
            width: 25%;
        }

        .book-metadata .label {
            font-weight: bold;
            color: #333;
        }

        /* --- COMMENTS --- */
        .comment-section {
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }

        .comment-section h2 {
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 5px;
            min-height: 100px;
            font-family: inherit;
            resize: vertical;
        }

        .char-count {
            font-size: 0.8em;
            color: #999;
            text-align: right;
            margin-bottom: 10px;
        }

        .btn-comment {
            background-color: #f0f0f0;
            color: #666;
            border: 1px solid #ccc;
            padding: 8px 15px;
        }

        /* --- RELATED BOOKS & AUDIOBOOKS SECTIONS --- */
        .full-width-section {
            width: 100%;
            background-color: #f5f5f5;
            padding: 40px 0;
            margin-top: 40px;
        }

        .full-width-section .section-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 60px;
        }

        .related-books-section,
        .audiobooks-section {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .audiobooks-section {
            margin-top: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .view-all-link {
            color: #cc0000;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9em;
        }

        .view-all-link:hover {
            text-decoration: underline;
        }

        .book-carousel-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .book-carousel-wrapper .book-list {
            display: flex;
            flex-direction: row;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            flex: 1;
            padding: 10px 0;
        }

        .book-carousel-wrapper .book-list::-webkit-scrollbar {
            display: none;
        }

        .book-carousel-wrapper .book-item {
            flex: 0 0 180px;
            min-width: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0;
            gap: 8px;
        }

        .book-carousel-wrapper .book-link {
            text-decoration: none;
            color: inherit;
            width: 100%;
        }

        .book-carousel-wrapper .book-cover {
            width: 100%;
            height: 240px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .book-carousel-wrapper .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-carousel-wrapper .book-title {
            font-size: 0.9em;
            font-weight: 600;
            color: #333;
            margin: 0;
            line-height: 1.3;
            height: 2.6em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .book-carousel-wrapper .book-author {
            font-size: 0.85em;
            color: #666;
            margin: 0;
        }

        .book-carousel-wrapper .book-rating {
            margin: 5px 0;
        }

        .book-carousel-wrapper .book-rating .stars {
            color: #ffdd00;
            font-size: 0.9em;
        }

        .book-carousel-wrapper .book-price {
            font-size: 0.85em;
            color: #cc0000;
            font-weight: 600;
            margin: 5px 0 0 0;
        }

        .carousel-nav {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 24px;
            color: #333;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .carousel-nav:hover {
            background: #f5f5f5;
            border-color: #cc0000;
            color: #cc0000;
        }

        .carousel-nav:active {
            transform: scale(0.95);
        }

        /* --- SIDEBAR --- */
        .sidebar-block {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-block h3 {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin: 0 0 15px 0;
            padding: 0;
            border-bottom: none;
        }

        .book-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .book-item {
            display: flex;
            align-items: flex-start;
            padding: 12px 0;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .book-item:not(:last-child) {
            border-bottom: 1px solid #f0f0f0;
        }

        .sidebar-thumb {
            width: 60px;
            height: 85px;
            object-fit: cover;
            flex-shrink: 0;
            border-radius: 4px;
        }

        .item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 5px;
        }

        .item-details a {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-decoration: none;
            line-height: 1.4;
            display: block;
            margin: 0;
        }

        .item-details a:hover {
            color: #cc0000;
        }

        .item-details .stats {
            font-size: 13px;
            color: #666;
            margin: 0;
            font-weight: normal;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
            }

            .book-summary {
                flex-direction: column;
            }

            .book-cover {
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <div class="logo-section">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-part1">THƯ VIỆN</span>
                    <span class="logo-part2">LIBHUB</span>
                </div>
            </div>
            <div class="hotline-section">
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách lẻ:</span>
                    <a href="tel:0327888669" class="hotline-number">0327888669</a>
                </div>
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách sỉ:</span>
                    <a href="tel:02439741791" class="hotline-number">02439741791 - 0327888669</a>
                </div>
            </div>
            <div class="user-actions">
                @auth
                    <a href="{{ route('borrow-cart.index') }}" class="cart-link" id="borrow-cart-link" title="Giỏ sách">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ sách</span>
                        <span class="cart-badge" id="borrow-cart-count" style="display: none;">0</span>
                    </a>
                    <div class="user-menu-dropdown" style="position: relative;">
                        <a href="#" class="auth-link user-menu-toggle">
                            <span class="user-icon">👤</span>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="user-dropdown-menu">
                            <div class="dropdown-header" style="padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: 600; color: #333;">
                                <span class="user-icon">👤</span>
                                {{ auth()->user()->name }}
                            </div>
                            @if(auth()->user()->reader)
                            <a href="{{ route('account.borrowed-books') }}" class="dropdown-item">
                                <span>📚</span> Sách đang mượn
                            </a>
                            <a href="{{ route('account.reader-info') }}" class="dropdown-item">
                                <span>👥</span> Thông tin độc giả
                            </a>
                            @endif
                            <a href="{{ route('account') }}" class="dropdown-item">
                                <span>👤</span> Thông tin tài khoản
                            </a>
                            <a href="{{ route('account.change-password') }}" class="dropdown-item">
                                <span>🔒</span> Đổi mật khẩu
                            </a>
                            <a href="{{ route('orders.index') }}" class="dropdown-item">
                                <span>⏰</span> Lịch sử mua hàng
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <div style="border-top: 1px solid #eee; margin-top: 5px;"></div>
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <span>📊</span> Dashboard
                            </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <span>➡️</span> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                    <style>
                        .user-menu-dropdown {
                            position: relative;
                        }
                        .user-menu-dropdown .user-dropdown-menu {
                            display: none;
                            position: absolute;
                            top: calc(100% + 5px);
                            right: 0;
                            background: white;
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                            min-width: 220px;
                            z-index: 1000;
                            overflow: hidden;
                        }
                        .user-menu-dropdown:hover .user-dropdown-menu {
                            display: block;
                        }
                        .user-menu-dropdown .dropdown-item {
                            display: block;
                            padding: 10px 15px;
                            color: #333;
                            text-decoration: none;
                            border-bottom: 1px solid #eee;
                            transition: background-color 0.2s;
                            cursor: pointer;
                        }
                        .user-menu-dropdown .dropdown-item:hover {
                            background-color: #f5f5f5;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn {
                            border: none;
                            background: none;
                            width: 100%;
                            text-align: left;
                            color: #d32f2f;
                            border-top: 1px solid #eee;
                            margin-top: 5px;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn:hover {
                            background-color: #ffebee;
                        }
                        .user-menu-dropdown .dropdown-item span {
                            margin-right: 8px;
                        }
                    </style>
                @else
                    <a href="{{ route('borrow-cart.index') }}" class="cart-link" id="borrow-cart-link" title="Giỏ sách">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ sách</span>
                        <span class="cart-badge" id="borrow-cart-count" style="display: none;">0</span>
                    </a>
                    <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
                @endauth
            </div>
        </div>
        <div class="header-nav">
            <div class="search-bar">
                <form action="{{ route('books.public') }}" method="GET" class="search-form">
                    <input type="text" name="keyword" placeholder="Tìm sách, tác giả, sản phẩm mong muốn..." value="{{ request('keyword') }}" class="search-input">
                    <button type="submit" class="search-button">🔍 Tìm kiếm</button>
                </form>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <main class="main-content">
            <p class="breadcrumb">
                <a href="{{ route('home') }}">🏠</a> / 
                <span>{{ Str::limit($book->ten_sach, 50) }}</span>
            </p>

            <section class="book-detail-section">
                <div class="book-summary">
                    <img src="{{ $book->hinh_anh && file_exists(public_path('storage/'.$book->hinh_anh)) ? asset('storage/'.$book->hinh_anh) : 'https://via.placeholder.com/200x300?text=Book+Cover' }}" 
                         alt="Bìa sách" 
                         class="book-cover">

                    <div class="info-and-buy">
                        <h1>{{ $book->ten_sach }}</h1>
                        <p>Tác giả: <strong>{{ $book->tac_gia }}</strong></p>
                        
                        <div class="rating">
                            @php
                                $rating = $stats['average_rating'] ?? 4.5;
                            @endphp
                            {{ number_format($rating, 1) }} 
                            <span class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($rating))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </span> 
                            | {{ number_format($book->so_luot_xem ?? 0, 0, ',', '.') }} Lượt xem | 
                            {{ number_format($book->so_luong_ban ?? 0, 0, ',', '.') }} Đã bán
                        </div>

                        <div class="buy-options">
                            @php
                                $isBorrowMode = isset($mode) && $mode === 'borrow';
                            @endphp
                            
                            @if($isBorrowMode)
                                <!-- Hiển thị thông tin giá sách -->
                                @if($book->gia && $book->gia > 0)
                                <div style="padding: 15px; background: #fff3e0; border-radius: 4px; margin-bottom: 15px; border: 1px solid #ff9800;">
                                    <strong style="font-size: 1.1em;">💰 Giá sách:</strong> 
                                    <span style="color: #e65100; font-weight: bold; font-size: 1.2em;">{{ number_format($book->gia, 0, ',', '.') }}₫</span>
                                </div>
                                @endif

                                <!-- Hiển thị thông tin số lượng sách có sẵn -->
                                <div style="padding: 15px; background: #e8f5e9; border-radius: 4px; margin-bottom: 20px; border: 1px solid #4caf50;">
                                    <strong style="font-size: 1.1em;">📚 Sách có sẵn:</strong> 
                                    <span style="color: #2e7d32; font-weight: bold; font-size: 1.1em;">{{ $stats['available_copies'] ?? 0 }} cuốn</span>
                                </div>

                                <!-- Chọn số lượng mượn -->
                                <div style="padding: 15px; background: #f5f5f5; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ddd;">
                                    <label style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 1em;">Số lượng mượn:</label>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <button type="button" onclick="changeBorrowQuantity(-1)" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer; font-size: 1.2em; font-weight: bold;">-</button>
                                        <input type="number" id="borrow-quantity" value="1" min="1" max="{{ $stats['available_copies'] ?? 1 }}" style="width: 80px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; text-align: center; font-size: 1.1em; font-weight: bold;" onchange="validateBorrowQuantity()">
                                        <button type="button" onclick="changeBorrowQuantity(1)" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer; font-size: 1.2em; font-weight: bold;">+</button>
                                        <span style="color: #666; font-size: 0.9em;">cuốn</span>
                                    </div>
                                </div>

                                <div class="action-buttons" style="display: flex; gap: 10px;">
                                    @auth
                                        <button class="btn btn-buy" onclick="addToCart()" style="flex: 1; background: #6C63FF;">
                                            <span style="font-size: 1.2em;">🛒</span> Thêm vào giỏ sách
                                        </button>
                                        <button class="btn btn-buy" onclick="borrowNow()" style="flex: 1;">
                                            <span style="font-size: 1.2em;">📖</span> Mượn ngay
                                        </button>
                                    @else
                                        <button class="btn btn-buy" onclick="alert('Vui lòng đăng nhập để mượn sách!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer; width: 100%;">
                                            <span style="font-size: 1.2em;">📖</span> Mượn sách
                                        </button>
                                    @endauth
                                </div>
                            @else
                                <label>Chọn sản phẩm</label>
                                
                                <!-- Sách giấy -->
                                <div class="option-row">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span class="type">📚 Sách giấy</span>
                                        <span style="font-size: 0.9em; color: #666; font-weight: normal;">
                                            (Còn {{ $stats['stock_quantity'] ?? 0 }} cuốn trong kho)
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <button type="button" onclick="changeQuantity('paper', -1)" style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer;">-</button>
                                        <input type="number" id="paper-quantity" value="1" min="1" max="{{ $stats['stock_quantity'] ?? 999 }}" style="width: 50px; padding: 5px; border: 1px solid #ddd; border-radius: 4px; text-align: center;" onchange="updateTotalPrice()">
                                        <button type="button" onclick="changeQuantity('paper', 1)" style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; background: white; cursor: pointer;">+</button>
                                    </div>
                                    <span class="price" id="paper-price">{{ number_format($book->gia ?? 111000, 0, ',', '.') }}₫</span>
                                    <input type="checkbox" id="paper-checkbox" checked onchange="updateTotalPrice()" style="width: 20px; height: 20px; cursor: pointer;">
                                </div>
                                
                                @if(($stats['stock_quantity'] ?? 0) == 0)
                                    <div style="padding: 15px; background: #fff3cd; border-radius: 4px; margin: 15px 0; border: 1px solid #ffc107; color: #856404;">
                                        <strong>⚠️ Hết hàng:</strong> Sách này hiện đã hết hàng. Vui lòng quay lại sau!
                                    </div>
                                @endif
                                
                                <div class="total-price">
                                    <span>Thành tiền</span>
                                    <span class="final-price" id="total-price">{{ number_format($book->gia ?? 111000, 0, ',', '.') }}₫</span>
                                </div>

                                <div class="action-buttons">
                                    @auth
                                        <button class="btn btn-buy" onclick="buyNow()" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }} style="width: 100%;">
                                            <span style="font-size: 1.2em;">$</span> Mua ngay
                                        </button>
                                    @else
                                        <button class="btn btn-buy" onclick="alert('Vui lòng đăng nhập để mua hàng!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer;" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                                            <span style="font-size: 1.2em;">$</span> Mua ngay
                                        </button>
                                        <button class="btn btn-cart" onclick="alert('Vui lòng đăng nhập để thêm vào giỏ sách!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer;" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                                            <span style="font-size: 1.2em;">🛒</span> Thêm vào giỏ
                                        </button>
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="tab-section">
                    <a href="#" class="tab-link active" onclick="switchTab('intro'); return false;">Giới thiệu</a>
                    <a href="#" class="tab-link" onclick="switchTab('contents'); return false;">Mục lục</a>
                </div>

                <div class="description-section" id="intro-content">
                    {{ $book->mo_ta ?? 'Nội dung giới thiệu về sách đang được cập nhật...' }}
                </div>

                <div class="description-section" id="contents-content" style="display: none;">
                    <p>Mục lục đang được cập nhật...</p>
                </div>

                <div class="metadata-table">
                    <h2>Thông tin xuất bản</h2>
                    <table class="book-metadata">
                        <tr>
                            <td class="label">Tác giả:</td>
                            <td>{{ $book->tac_gia }}</td>
                            <td class="label">Nhà xuất bản:</td>
                            <td>{{ $book->publisher->ten_nha_xuat_ban ?? 'Nhà xuất bản Xây dựng' }}</td>
                        </tr>
                        <tr>
                            <td class="label">📖 Khổ sách:</td>
                            <td>17 x 24 (cm)</td>
                            <td class="label">Số trang:</td>
                            <td>{{ $book->so_trang ?? '260' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Mã ISBN:</td>
                            <td>{{ $book->isbn ?? '' }}</td>
                            <td class="label">Ngôn ngữ:</td>
                            <td>vi</td>
                        </tr>
                    </table>
                </div>

                <div class="comment-section">
                    <h2>Bình luận</h2>
                    @auth
                        <form class="comment-form" action="{{ route('books.comments.store', $book->id) }}" method="POST">
                            @csrf
                            <textarea 
                                name="content" 
                                placeholder="Để lại bình luận của bạn..." 
                                maxlength="1500"
                                oninput="updateCharCount(this)"
                                required
                            ></textarea>
                            <p class="char-count">
                                <span id="char-count">0</span>/1500
                            </p>
                            <button type="submit" class="btn btn-comment">Gửi bình luận</button>
                        </form>
                    @else
                        <div style="padding: 20px; background: #f9f9f9; border-radius: 8px; text-align: center;">
                            <p>Vui lòng <a href="{{ route('login') }}" style="color: #cc0000; font-weight: bold;">đăng nhập</a> để bình luận.</p>
                        </div>
                    @endauth

                    @if($book->reviews && $book->reviews->count() > 0)
                        <div style="margin-top: 30px;">
                            <h3 style="margin-bottom: 15px;">Bình luận ({{ $book->reviews->count() }})</h3>
                            @foreach($book->reviews->take(5) as $review)
                                @if($review->comments && $review->comments->count() > 0)
                                    @foreach($review->comments->whereNull('parent_id') as $comment)
                                        <div style="padding: 15px; background: #f9f9f9; border-radius: 8px; margin-bottom: 15px;">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <strong>{{ $comment->user->name ?? 'Người dùng' }}</strong>
                                                <span style="color: #666; font-size: 12px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <p style="margin: 0; line-height: 1.6;">{{ $comment->content }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </main>

    </div>

    <!-- Cùng chủ đề -->
    @if($same_topic_books && $same_topic_books->count() > 0)
    <div class="related-books-section full-width-section">
        <div class="section-container">
            <div class="section-header">
                <h2>Cùng chủ đề</h2>
                <a href="{{ route('books.public', ['category_id' => $book->category_id]) }}" class="view-all-link">Xem toàn bộ →</a>
            </div>
            <div class="book-carousel-wrapper">
                <button class="carousel-nav carousel-prev" onclick="scrollCarousel('same-topic-carousel', -1)">‹</button>
                <div class="book-list" id="same-topic-carousel">
                    @foreach($same_topic_books as $relatedBook)
                        <div class="book-item">
                            <a href="{{ route('books.show', $relatedBook->id) }}" class="book-link">
                                <div class="book-cover">
                                    @if($relatedBook->hinh_anh && file_exists(public_path('storage/'.$relatedBook->hinh_anh)))
                                        <img src="{{ asset('storage/'.$relatedBook->hinh_anh) }}" alt="{{ $relatedBook->ten_sach }}">
                                    @else
                                        <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="210" height="297" fill="#f0f0f0"/>
                                            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                        </svg>
                                    @endif
                                </div>
                                <p class="book-title">{{ Str::limit($relatedBook->ten_sach, 50) }}</p>
                                @if($relatedBook->tac_gia)
                                    <p class="book-author">{{ $relatedBook->tac_gia }}</p>
                                @endif
                                <div class="book-rating">
                                    <span class="stars">★★★★★</span>
                                </div>
                                @if($relatedBook->gia && $relatedBook->gia > 0)
                                    <p class="book-price">Chỉ từ {{ number_format($relatedBook->gia, 0, ',', '.') }}₫</p>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-nav carousel-next" onclick="scrollCarousel('same-topic-carousel', 1)">›</button>
            </div>
        </div>
    </div>
    @endif


    @include('components.footer')

    <script>
        function switchTab(tab) {
            document.getElementById('intro-content').style.display = tab === 'intro' ? 'block' : 'none';
            document.getElementById('contents-content').style.display = tab === 'contents' ? 'block' : 'none';
            
            document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
            event.target.classList.add('active');
        }

        function updateCharCount(textarea) {
            document.getElementById('char-count').textContent = textarea.value.length;
        }

        // Hàm thay đổi số lượng sách giấy
        function changeQuantity(type, change) {
            const quantityInput = document.getElementById('paper-quantity');
            if (!quantityInput) return;
            let currentQuantity = parseInt(quantityInput.value) || 1;
            currentQuantity += change;
            if (currentQuantity < 1) currentQuantity = 1;
            
            // Kiểm tra giới hạn số lượng tồn kho
            const isBorrowMode = {{ isset($mode) && $mode === 'borrow' ? 'true' : 'false' }};
            const maxQuantity = parseInt(quantityInput.getAttribute('max')) || 999;
            
            if (isBorrowMode) {
                // Chế độ mượn: sử dụng available_copies
                const availableCopies = {{ $stats['available_copies'] ?? 0 }};
                const maxBorrowQuantity = availableCopies;
                if (currentQuantity > maxBorrowQuantity) {
                    currentQuantity = maxBorrowQuantity;
                    alert(`Chỉ còn ${maxBorrowQuantity} cuốn sách có sẵn.`);
                }
            } else {
                // Chế độ mua: sử dụng stock_quantity
                const stockQuantity = {{ $stats['stock_quantity'] ?? 0 }};
                if (currentQuantity > stockQuantity) {
                    currentQuantity = stockQuantity;
                    alert(`Chỉ còn ${stockQuantity} cuốn sách trong kho.`);
                }
            }
            
            quantityInput.value = currentQuantity;
            updateTotalPrice();
        }

        // Hàm cập nhật giá tổng
        function updateTotalPrice() {
            // Kiểm tra chế độ mượn sách
            const isBorrowMode = {{ isset($mode) && $mode === 'borrow' ? 'true' : 'false' }};
            if (isBorrowMode) {
                // Ở chế độ mượn, không cần tính giá
                return;
            }

            const basePrice = {{ $book->gia ?? 111000 }};
            let totalPrice = 0;

            // Tính và cập nhật giá sách giấy
            const paperCheckbox = document.getElementById('paper-checkbox');
            if (paperCheckbox && paperCheckbox.checked) {
                const paperQuantity = parseInt(document.getElementById('paper-quantity')?.value) || 1;
                const paperTotal = basePrice * paperQuantity;
                totalPrice += paperTotal;
                const paperPriceElement = document.getElementById('paper-price');
                if (paperPriceElement) {
                    paperPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(paperTotal) + '₫';
                }
            } else {
                const paperPriceElement = document.getElementById('paper-price');
                if (paperPriceElement) {
                    paperPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(basePrice) + '₫';
                }
            }

            // Cập nhật giá tổng
            const totalPriceElement = document.getElementById('total-price');
            if (totalPriceElement) {
                totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(Math.round(totalPrice)) + '₫';
            }
        }

        function buyNow() {
            // Kiểm tra đăng nhập
            @guest
                alert('Vui lòng đăng nhập để mua hàng!');
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            const paperCheckbox = document.getElementById('paper-checkbox');
            const paperChecked = paperCheckbox ? paperCheckbox.checked : false;
            
            if (!paperChecked) {
                alert('Vui lòng chọn sản phẩm!');
                return;
            }

            const quantity = parseInt(document.getElementById('paper-quantity')?.value) || 1;
            const stockQuantity = {{ $stats['stock_quantity'] ?? 0 }};
            
            // Kiểm tra số lượng tồn kho
            if (quantity > stockQuantity) {
                alert(`Số lượng bạn chọn (${quantity} cuốn) vượt quá số lượng tồn kho (${stockQuantity} cuốn). Vui lòng chọn lại!`);
                return;
            }
            
            if (stockQuantity === 0) {
                alert('Sách này hiện đã hết hàng. Vui lòng quay lại sau!');
                return;
            }

            const message = `Bạn có chắc chắn muốn mua:\n- Sách giấy: ${quantity} cuốn\n`;

            if (!confirm(message)) {
                return;
            }

            // Tạo URL với các tham số
            const params = new URLSearchParams();
            params.append('book_id', {{ $book->id }});
            params.append('paper_quantity', document.getElementById('paper-quantity').value);
            
            window.location.href = '{{ route("checkout") }}?' + params.toString();
        }

        function scrollCarousel(carouselId, direction) {
            const carousel = document.getElementById(carouselId);
            if (carousel) {
                const scrollAmount = 200; // Số pixel scroll mỗi lần
                carousel.scrollBy({
                    left: direction * scrollAmount,
                    behavior: 'smooth'
                });
            }
        }


        // Khởi tạo giá khi trang load
        updateTotalPrice();

        // Cập nhật tóm tắt đơn hàng mượn sách
        function updateBorrowSummary() {
            if (!isBorrowMode) return;
            
            const quantity = parseInt(document.getElementById('borrow-quantity')?.value) || 1;
            const bookPrice = {{ $book->gia ?? 0 }};
            const hasCard = {{ auth()->check() && auth()->user()->reader ? 'true' : 'false' }};
            
            // Số ngày mượn mặc định (có thể thay đổi khi người dùng mở modal)
            const defaultDays = 14;
            
            // Tính phí thuê (1% giá sách mỗi ngày, hoặc 0.5% nếu có thẻ)
            const dailyRate = hasCard ? 0.005 : 0.01;
            const rentalFeePerBook = Math.round((bookPrice * dailyRate * defaultDays) / 1000) * 1000;
            const totalRentalFee = rentalFeePerBook * quantity;
            
            // Tính tiền cọc (30% giá sách)
            const depositRate = 0.3;
            const depositPerBook = Math.round(bookPrice * depositRate / 1000) * 1000;
            const totalDeposit = depositPerBook * quantity;
            
            // Phí ship mặc định 0 (có thể thay đổi khi người dùng nhập khoảng cách)
            const shippingFee = 0;
            
            // Giảm giá
            const productDiscount = 0;
            const orderDiscount = 0;
            
            // Tính tổng
            const totalBasic = totalDeposit + shippingFee;
            const subtotal = totalBasic - productDiscount;
            const totalPayment = subtotal - orderDiscount;
            
            // Cập nhật UI
            updateSummaryDisplay('rental-fee-display', totalRentalFee);
            updateSummaryDisplay('deposit-fee-display', totalDeposit);
            updateSummaryDisplay('shipping-fee-display', shippingFee);
            updateSummaryDisplay('total-basic-display', totalBasic);
            updateSummaryDisplay('product-discount-display', productDiscount, true);
            updateSummaryDisplay('subtotal-display', subtotal);
            updateSummaryDisplay('order-discount-display', orderDiscount, true);
            updateSummaryDisplay('total-payment-display', totalPayment);
        }

        function updateSummaryDisplay(elementId, amount, isDiscount = false) {
            const element = document.getElementById(elementId);
            if (element) {
                const prefix = isDiscount && amount > 0 ? '-' : '';
                element.textContent = prefix + new Intl.NumberFormat('vi-VN').format(amount) + '₫';
            }
        }

        function applyDiscountCode() {
            const discountInput = document.getElementById('discount-code-input');
            const code = discountInput?.value.trim();
            
            if (!code) {
                alert('Vui lòng nhập mã giảm giá!');
                return;
            }
            
            // Hiển thị loading
            const btn = event.target;
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Đang kiểm tra...';
            
            // Gọi API kiểm tra mã giảm giá (tạm thời giả lập)
            setTimeout(() => {
                // Giả lập kiểm tra mã giảm giá
                const validCodes = ['LIBHUB2024', 'FREESHIP', 'DISCOUNT10'];
                
                if (validCodes.includes(code.toUpperCase())) {
                    alert('Áp dụng mã giảm giá thành công!\n\nLưu ý: Chức năng giảm giá đang được phát triển.');
                    discountInput.value = '';
                } else {
                    alert('Mã giảm giá không hợp lệ hoặc đã hết hạn!');
                }
                
                btn.disabled = false;
                btn.textContent = originalText;
            }, 500);
        }

        function borrowNowFromSummary() {
            borrowNow();
        }

        // Khởi tạo tóm tắt đơn hàng khi trang load (nếu ở chế độ mượn)
        if (isBorrowMode) {
            document.addEventListener('DOMContentLoaded', function() {
                updateBorrowSummary();
            });
            
            // Cập nhật ngay lập tức nếu DOM đã load
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                updateBorrowSummary();
            }
        }


        // Kiểm tra chế độ mượn sách
        const isBorrowMode = {{ isset($mode) && $mode === 'borrow' ? 'true' : 'false' }};

        // Hàm thay đổi số lượng mượn
        function changeBorrowQuantity(change) {
            const quantityInput = document.getElementById('borrow-quantity');
            if (!quantityInput) return;
            
            let currentQuantity = parseInt(quantityInput.value) || 1;
            currentQuantity += change;
            
            const maxQuantity = parseInt(quantityInput.getAttribute('max')) || 1;
            const availableCopies = {{ $stats['available_copies'] ?? 0 }};
            
            if (currentQuantity < 1) {
                currentQuantity = 1;
            } else if (currentQuantity > availableCopies) {
                currentQuantity = availableCopies;
                alert(`Chỉ còn ${availableCopies} cuốn sách có sẵn.`);
            }
            
            quantityInput.value = currentQuantity;
            
            // Cập nhật tóm tắt đơn hàng
            if (isBorrowMode) {
                updateBorrowSummary();
            }
        }

        // Hàm kiểm tra số lượng mượn hợp lệ
        function validateBorrowQuantity() {
            const quantityInput = document.getElementById('borrow-quantity');
            if (!quantityInput) return;
            
            let quantity = parseInt(quantityInput.value) || 1;
            const availableCopies = {{ $stats['available_copies'] ?? 0 }};
            
            if (quantity < 1) {
                quantity = 1;
                quantityInput.value = 1;
            } else if (quantity > availableCopies) {
                quantity = availableCopies;
                quantityInput.value = availableCopies;
                alert(`Chỉ còn ${availableCopies} cuốn sách có sẵn.`);
            }
            
            // Cập nhật tóm tắt đơn hàng
            if (isBorrowMode) {
                updateBorrowSummary();
            }
        }

        // Hàm mượn sách ngay
        function borrowNow() {
            @guest
                alert('Vui lòng đăng nhập để mượn sách!');
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            const availableCopies = {{ $stats['available_copies'] ?? 0 }};
            
            if (availableCopies <= 0) {
                alert('Hiện tại không còn sách có sẵn để mượn. Vui lòng thử lại sau.');
                return;
            }

            // Hiển thị modal để nhập số ngày mượn
            showBorrowModal();
        }

        // Thêm sách vào giỏ sách
        function addToCart() {
            @guest
                alert('Vui lòng đăng nhập để thêm sách vào giỏ sách!');
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            const availableCopies = {{ $stats['available_copies'] ?? 0 }};
            
            if (availableCopies <= 0) {
                alert('Hiện tại không còn sách có sẵn để mượn. Vui lòng thử lại sau.');
                return;
            }

            const quantity = parseInt(document.getElementById('borrow-quantity')?.value) || 1;
            const borrowDays = 14; // Mặc định 14 ngày
            const distance = 0; // Mặc định 0 km

            if (quantity > availableCopies) {
                alert(`Chỉ còn ${availableCopies} cuốn sách có sẵn. Vui lòng chọn lại số lượng.`);
                return;
            }

            // Hiển thị loading
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span style="font-size: 1.2em;">⏳</span> Đang thêm...';

            fetch('{{ route("borrow-cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    book_id: {{ $book->id }},
                    quantity: quantity,
                    borrow_days: borrowDays,
                    distance: distance,
                    note: ''
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng trong giỏ sách nếu có icon giỏ sách
                    updateCartCount(data.cart_count);
                    
                    // Hiển thị thông báo thành công
                    if (confirm('Đã thêm sách vào giỏ sách!\n\nBạn có muốn xem giỏ sách không?')) {
                        window.location.href = '{{ route("borrow-cart.index") }}';
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi thêm sách vào giỏ sách');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
                btn.disabled = false;
                btn.innerHTML = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm sách vào giỏ sách');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        // Cập nhật số lượng trong giỏ sách (nếu có icon giỏ sách)
        function updateCartCount(count) {
            // Cập nhật cả cart-count và borrow-cart-count
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'inline-block' : 'none';
            }
            
            // Cập nhật borrow-cart-count (icon giỏ sách)
            const borrowCartCountElement = document.getElementById('borrow-cart-count');
            if (borrowCartCountElement) {
                borrowCartCountElement.textContent = count;
                borrowCartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
            
            // Hoặc gọi hàm global nếu có
            if (typeof updateBorrowCartCount === 'function') {
                updateBorrowCartCount(count);
            }
        }

        // Hiển thị modal phiếu mượn
        function showBorrowModal() {
            const modal = document.getElementById('borrowModal');
            const borrowQuantity = parseInt(document.getElementById('borrow-quantity')?.value) || 1;
            
            // Tạo danh sách items với input riêng cho mỗi quyển
            let itemsHtml = '';
            for (let i = 0; i < borrowQuantity; i++) {
                itemsHtml += `
                    <div class="borrow-item-card" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 2px solid #e0e0e0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0; color: #333;">📚 Quyển sách ${i + 1}</h4>
                            <span style="color: #666; font-size: 0.9em;">{{ $book->ten_sach }}</span>
                        </div>
                        
                        <div style="display: flex; gap: 15px;">
                            <div style="flex: 1;">
                                <label style="display: block; margin-bottom: 6px; font-weight: bold; font-size: 0.9em;">Số ngày mượn:</label>
                                <input type="number" 
                                       class="item-days-input" 
                                       data-index="${i}"
                                       min="1" 
                                       max="30" 
                                       value="14" 
                                       onchange="updateBorrowQuoteMultiple()" 
                                       oninput="updateBorrowQuoteMultiple()"
                                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.95em;">
                                <small style="color: #666; display: block; margin-top: 3px; font-size: 0.85em;">1 - 30 ngày</small>
                            </div>
                            
                            <div style="flex: 1;">
                                <label style="display: block; margin-bottom: 6px; font-weight: bold; font-size: 0.9em;">Khoảng cách (km):</label>
                                <input type="number" 
                                       class="item-distance-input" 
                                       data-index="${i}"
                                       min="0" 
                                       step="0.1"
                                       value="0" 
                                       onchange="updateBorrowQuoteMultiple()" 
                                       oninput="updateBorrowQuoteMultiple()"
                                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.95em;">
                                <small style="color: #666; display: block; margin-top: 3px; font-size: 0.85em;">> 5km: +5.000₫/km</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            modal.classList.add('active');
            
            // Hiển thị danh sách items và loading
            document.getElementById('borrowModalInputs').innerHTML = itemsHtml;
            document.getElementById('borrowModalContent').innerHTML = '<div class="loading-spinner">Đang tải thông tin...</div>';
            
            // Load thông tin giá
            updateBorrowQuoteMultiple();
        }

        // Đóng modal
        function closeBorrowModal() {
            document.getElementById('borrowModal').classList.remove('active');
        }

        // Hàm mới: Cập nhật thông tin giá cho nhiều items với thông số khác nhau
        function updateBorrowQuoteMultiple() {
            const daysInputs = document.querySelectorAll('.item-days-input');
            const distanceInputs = document.querySelectorAll('.item-distance-input');
            
            if (daysInputs.length === 0) {
                return;
            }
            
            // Tính tổng phí cho tất cả items
            const bookPrice = {{ $book->gia ?? 0 }};
            const hasCard = {{ auth()->check() && auth()->user()->reader ? 'true' : 'false' }};
            const dailyRate = hasCard ? 0.005 : 0.01;
            
            let totalRentalFee = 0;
            let totalDeposit = 0;
            let totalShippingFee = 0;
            let itemsDetails = '';
            
            daysInputs.forEach((daysInput, index) => {
                const days = parseInt(daysInput.value) || 14;
                const distance = parseFloat(distanceInputs[index].value) || 0;
                
                // Tính phí thuê cho item này
                const rentalFeePerBook = Math.round((bookPrice * dailyRate * days) / 1000) * 1000;
                totalRentalFee += rentalFeePerBook;
                
                // Tính tiền cọc cho item này (30% giá sách)
                const depositRate = 0.3;
                const depositPerCopy = Math.round(bookPrice * depositRate / 1000) * 1000;
                totalDeposit += depositPerCopy;
                
                // Tính phí ship cho item này (chỉ tính nếu > 5km)
                let shippingFee = 0;
                if (distance > 5) {
                    const extraKm = distance - 5;
                    shippingFee = Math.round(extraKm * 5000);
                }
                totalShippingFee += shippingFee;
                
                // Tạo chi tiết item
                const today = new Date();
                const returnDate = new Date(today);
                returnDate.setDate(today.getDate() + days);
                
                itemsDetails += `
                    <div style="padding: 12px; background: white; border-radius: 6px; margin-bottom: 10px; border: 1px solid #e0e0e0;">
                        <div style="font-weight: bold; color: #333; margin-bottom: 8px;">📚 Quyển ${index + 1}</div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9em;">
                            <span style="color: #666;">Số ngày mượn:</span>
                            <span style="font-weight: 500;">${days} ngày</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9em;">
                            <span style="color: #666;">Ngày trả dự kiến:</span>
                            <span style="font-weight: 500; color: #cc0000;">${returnDate.toLocaleDateString('vi-VN')}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9em;">
                            <span style="color: #666;">Khoảng cách:</span>
                            <span style="font-weight: 500;">${distance} km</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9em; border-top: 1px dashed #ddd; margin-top: 6px; padding-top: 8px;">
                            <span style="color: #666;">Phí thuê:</span>
                            <span style="font-weight: 500;">${new Intl.NumberFormat('vi-VN').format(rentalFeePerBook)}₫</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9em;">
                            <span style="color: #666;">Tiền cọc:</span>
                            <span style="font-weight: 500;">${new Intl.NumberFormat('vi-VN').format(depositPerCopy)}₫</span>
                        </div>
                        ${shippingFee > 0 ? `
                        <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 0.9em;">
                            <span style="color: #666;">Phí ship:</span>
                            <span style="font-weight: 500;">${new Intl.NumberFormat('vi-VN').format(shippingFee)}₫</span>
                        </div>
                        ` : ''}
                    </div>
                `;
            });
            
            const payableNow = totalDeposit + totalShippingFee+ totalRentalFee;
            
            const content = `
                <div class="borrow-info-section">
                    <h3>📚 Thông tin sách</h3>
                    <div class="info-row">
                        <span class="info-label">Tên sách:</span>
                        <span class="info-value">{{ $book->ten_sach }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số lượng mượn:</span>
                        <span class="info-value">${daysInputs.length} cuốn</span>
                    </div>
                </div>

                <div class="borrow-info-section">
                    <h3>📋 Chi tiết từng quyển</h3>
                    ${itemsDetails}
                </div>

                <div class="borrow-info-section">
                    <h3>💰 Tổng chi phí</h3>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Tổng phí thuê (${daysInputs.length} cuốn):</span>
                            <span>${new Intl.NumberFormat('vi-VN').format(totalRentalFee)}₫</span>
                        </div>
                        <div class="price-row">
                            <span>Tổng tiền cọc (${daysInputs.length} cuốn):</span>
                            <span>${new Intl.NumberFormat('vi-VN').format(totalDeposit)}₫</span>
                        </div>
                        ${totalShippingFee > 0 ? `
                        <div class="price-row">
                            <span>Tổng phí vận chuyển:</span>
                            <span>${new Intl.NumberFormat('vi-VN').format(totalShippingFee)}₫</span>
                        </div>
                        ` : ''}
                        <div class="price-row total">
                            <span>Tổng tiền phải trả ngay:</span>
                            <span>${new Intl.NumberFormat('vi-VN').format(payableNow)}₫</span>
                        </div>
                    </div>
                    <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 4px; border: 1px solid #ffc107; color: #856404; font-size: 0.9em;">
                        <strong>Lưu ý:</strong> Tiền cọc sẽ được hoàn lại khi bạn trả sách đúng hạn và sách không bị hư hỏng. Phí thuê sẽ được tính khi bạn nhận sách.
                    </div>
                </div>

                <div class="borrow-modal-actions">
                    <button class="btn-modal btn-modal-cancel" onclick="closeBorrowModal()">Hủy</button>
                    <button class="btn-modal btn-modal-confirm" onclick="confirmBorrowMultiple()">Xác nhận mượn sách</button>
                </div>
            `;

            document.getElementById('borrowModalContent').innerHTML = content;
        }
        
        // Hàm cũ: Cập nhật thông tin giá khi thay đổi số ngày hoặc khoảng cách (giữ lại cho tương thích)
        function updateBorrowQuote() {
            const days = parseInt(document.getElementById('borrowDaysInput')?.value) || 14;
            const distance = parseFloat(document.getElementById('distanceInput')?.value) || 0;
            const quantity = parseInt(document.getElementById('borrow-quantity')?.value) || 1;
            
            if (days < 1 || days > 30) {
                document.getElementById('borrowModalContent').innerHTML = 
                    '<div style="text-align: center; padding: 20px; color: #cc0000;">Số ngày mượn phải từ 1 đến 30 ngày.</div>';
                return;
            }

            // Sử dụng KYC status từ server
            const kycStatus = '{{ $kycStatus ?? "unverified" }}';
            const userId = {{ auth()->id() ?? 'null' }};
            
            // Xác định delivery_type: nếu có khoảng cách > 0 thì là ship, ngược lại là pickup
            const deliveryType = distance > 0 ? 'ship' : 'pickup';
            
            // Gọi API để lấy thông tin giá (truyền tham số days để tính phí thuê theo số ngày)
            // Lưu ý: API có thể không hỗ trợ số lượng, nên sẽ tính nhân sau
            const apiUrl = `/api/pricing/quote?book_ids[]={{ $book->id }}&kyc_status=${kycStatus}&delivery_type=${deliveryType}&distance=${distance}&days=${days}`;
            const finalUrl = userId ? `${apiUrl}&user_id=${userId}` : apiUrl;
            
            fetch(finalUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.items && data.items.length > 0) {
                        displayBorrowQuote(data, days, quantity);
                    } else {
                        // Fallback nếu API không trả về đúng format
                        displayBorrowQuoteFallback(days, kycStatus, quantity);
                    }
                })
                .catch(error => {
                    console.error('Error fetching pricing:', error);
                    // Fallback nếu API lỗi
                    displayBorrowQuoteFallback(days, kycStatus, quantity);
                });
        }

        // Hiển thị phiếu mượn với thông tin từ API
        function displayBorrowQuote(pricingData, days, quantity = 1) {
            const item = pricingData.items[0];
            const rentalFee = item.rental_fee || 10000;
            const deposit = item.deposit || 50000;
            const shippingFee = pricingData.shipping_fee || 0;
            // Nhân với số lượng
            const totalRental = (pricingData.total_rental_fee || rentalFee) * quantity;
            const totalDeposit = (pricingData.total_deposit || deposit) * quantity;
            const payableNow = totalDeposit + shippingFee;

            const today = new Date();
            const returnDate = new Date(today);
            returnDate.setDate(today.getDate() + days);

            const formatDate = (date) => {
                return date.toLocaleDateString('vi-VN', { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric' 
                });
            };

            const formatCurrency = (amount) => {
                return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
            };

            const content = `
                <div class="borrow-info-section">
                    <h3>📚 Thông tin sách</h3>
                    <div class="info-row">
                        <span class="info-label">Tên sách:</span>
                        <span class="info-value">{{ $book->ten_sach }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tác giả:</span>
                        <span class="info-value">{{ $book->tac_gia ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nhà xuất bản:</span>
                        <span class="info-value">{{ $book->publisher->ten_nha_xuat_ban ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Năm xuất bản:</span>
                        <span class="info-value">{{ $book->nam_xuat_ban ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="borrow-info-section">
                    <h3>📅 Thông tin mượn</h3>
                    <div class="info-row">
                        <span class="info-label">Ngày mượn:</span>
                        <span class="info-value">${formatDate(today)}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số ngày mượn:</span>
                        <span class="info-value">${days} ngày</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày trả dự kiến:</span>
                        <span class="info-value" style="color: #cc0000;">${formatDate(returnDate)}</span>
                    </div>
                </div>

                <div class="borrow-info-section">
                    <h3>💰 Chi phí mượn sách</h3>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Phí thuê sách (${quantity} cuốn × ${days} ngày):</span>
                            <span>${formatCurrency(totalRental)}</span>
                        </div>
                        <div class="price-row">
                            <span>Tiền cọc (${quantity} cuốn):</span>
                            <span>${formatCurrency(totalDeposit)}</span>
                        </div>
                        ${shippingFee > 0 ? `
                        <div class="price-row">
                            <span>Phí vận chuyển:</span>
                            <span>${formatCurrency(shippingFee)}</span>
                        </div>
                        ` : ''}
                        <div class="price-row total">
                            <span>Tổng tiền phải trả ngay:</span>
                            <span>${formatCurrency(payableNow)}</span>
                        </div>
                    </div>
                    <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 4px; border: 1px solid #ffc107; color: #856404; font-size: 0.9em;">
                        <strong>Lưu ý:</strong> Tiền cọc sẽ được hoàn lại khi bạn trả sách đúng hạn và sách không bị hư hỏng. Phí thuê sẽ được tính khi bạn nhận sách.
                    </div>
                </div>

                <div class="borrow-modal-actions">
                    <button class="btn-modal btn-modal-cancel" onclick="closeBorrowModal()">Hủy</button>
                    <button class="btn-modal btn-modal-confirm" onclick="confirmBorrow(${days}, ${quantity})">Xác nhận mượn sách</button>
                </div>
            `;

            document.getElementById('borrowModalContent').innerHTML = content;
        }

        // Fallback nếu API không hoạt động - tính dựa trên giá sách
        function displayBorrowQuoteFallback(days, kycStatus = 'unverified', quantity = 1) {
            // Lấy giá sách từ server
            const bookPrice = {{ $book->gia ?? 0 }};
            const hasCard = {{ auth()->check() && auth()->user()->reader ? 'true' : 'false' }};
            
            // Tỷ lệ phí thuê mỗi ngày (1% giá sách mỗi ngày, hoặc 0.5% nếu có thẻ)
            const dailyRate = hasCard ? 0.005 : 0.01;
            
            // Tính phí thuê = giá sách * tỷ lệ mỗi ngày * số ngày
            const rentalFeePerBook = Math.round((bookPrice * dailyRate * days) / 1000) * 1000;
            const rentalFee = rentalFeePerBook * quantity;
            
            // Tính tiền cọc dựa trên giá sách (30% giá sách mặc định)
            const depositRate = 0.3; // 30% giá sách
            const depositPerCopy = Math.round(bookPrice * depositRate);
            const deposit = depositPerCopy * quantity;
            
            const shippingFee = 0;
            const total = deposit + shippingFee;

            const today = new Date();
            const returnDate = new Date(today);
            returnDate.setDate(today.getDate() + days);

            const formatDate = (date) => {
                return date.toLocaleDateString('vi-VN', { 
                    day: '2-digit', 
                    month: '2-digit', 
                    year: 'numeric' 
                });
            };

            const formatCurrency = (amount) => {
                return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
            };

            const content = `
                <div class="borrow-info-section">
                    <h3>📚 Thông tin sách</h3>
                    <div class="info-row">
                        <span class="info-label">Tên sách:</span>
                        <span class="info-value">{{ $book->ten_sach }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tác giả:</span>
                        <span class="info-value">{{ $book->tac_gia ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="borrow-info-section">
                    <h3>📅 Thông tin mượn</h3>
                    <div class="info-row">
                        <span class="info-label">Ngày mượn:</span>
                        <span class="info-value">${formatDate(today)}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số lượng mượn:</span>
                        <span class="info-value">${quantity} cuốn</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số ngày mượn:</span>
                        <span class="info-value">${days} ngày</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày trả dự kiến:</span>
                        <span class="info-value" style="color: #cc0000;">${formatDate(returnDate)}</span>
                    </div>
                </div>

                <div class="borrow-info-section">
                    <h3>💰 Chi phí mượn sách</h3>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Phí thuê sách (${quantity} cuốn × ${days} ngày):</span>
                            <span>${formatCurrency(rentalFee)}</span>
                        </div>
                        <div class="price-row">
                            <span>Tiền cọc (${quantity} cuốn):</span>
                            <span>${formatCurrency(deposit)}</span>
                        </div>
                        <div class="price-row total">
                            <span>Tổng tiền phải trả ngay:</span>
                            <span>${formatCurrency(total)}</span>
                        </div>
                    </div>
                    <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 4px; border: 1px solid #ffc107; color: #856404; font-size: 0.9em;">
                        <strong>Lưu ý:</strong> Tiền cọc sẽ được hoàn lại khi bạn trả sách đúng hạn và sách không bị hư hỏng. Phí thuê sẽ được tính khi bạn nhận sách.
                    </div>
                </div>

                <div class="borrow-modal-actions">
                    <button class="btn-modal btn-modal-cancel" onclick="closeBorrowModal()">Hủy</button>
                    <button class="btn-modal btn-modal-confirm" onclick="confirmBorrow(${days}, ${quantity})">Xác nhận mượn sách</button>
                </div>
            `;

            document.getElementById('borrowModalContent').innerHTML = content;
        }

        // Hàm mới: Xác nhận mượn nhiều quyển với thông số khác nhau
       // Thay thế hàm confirmBorrowMultiple (line ~1350) thành:
function confirmBorrowMultiple() {
    const daysInputs = document.querySelectorAll('.item-days-input');
    const distanceInputs = document.querySelectorAll('.item-distance-input');
    const availableCopies = {{ $stats['available_copies'] ?? 0 }};
    
    if (daysInputs.length === 0) {
        alert('Không có thông tin mượn sách!');
        return;
    }
    
    if (daysInputs.length > availableCopies) {
        alert(`Số lượng mượn vượt quá số lượng có sẵn. Chỉ còn ${availableCopies} cuốn.`);
        return;
    }
    
    // ✅ KHÔNG GỘI API, CHỈ REDIRECT CHECKOUT VỚI THÔNG SỐ
    const items = [];
    daysInputs.forEach((daysInput, index) => {
        const days = parseInt(daysInput.value) || 14;
        const distance = parseFloat(distanceInputs[index].value) || 0;
        
        if (days < 1 || days > 30) {
            alert(`Quyển ${index + 1}: Số ngày mượn phải từ 1 đến 30 ngày!`);
            return;
        }
        
        items.push({
            book_id: {{ $book->id }},
            borrow_days: days,
            distance: distance
        });
    });
    
    if (items.length === 0) {
        alert('Không có thông tin mượn sách hợp lệ!');
        return;
    }

    closeBorrowModal();
    
    // ✅ Redirect đến checkout với thông tin items
    const params = new URLSearchParams();
    params.append('book_id', {{ $book->id }});
    params.append('quantity', items.length);
    params.append('items', JSON.stringify(items));
    
    window.location.href = '{{ route("borrow-cart.checkout") }}?' + params.toString();
}
        
        // Hàm cũ: Xác nhận mượn sách (giữ lại cho tương thích)
        function confirmBorrow(days, quantityFromModal = null) {
            const confirmBtn = event.target;
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Đang xử lý...';

            // Lấy khoảng cách từ input
            const distance = parseFloat(document.getElementById('distanceInput')?.value) || 0;
            
            // Lấy số lượng mượn (ưu tiên từ tham số, nếu không có thì lấy từ input)
            const quantity = quantityFromModal !== null ? quantityFromModal : (parseInt(document.getElementById('borrow-quantity')?.value) || 1);
            const availableCopies = {{ $stats['available_copies'] ?? 0 }};
            
            // Kiểm tra số lượng hợp lệ
            if (quantity < 1 || quantity > availableCopies) {
                alert(`Số lượng mượn không hợp lệ. Vui lòng chọn từ 1 đến ${availableCopies} cuốn.`);
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Xác nhận mượn sách';
                return;
            }

            // Gửi yêu cầu mượn sách
            fetch('{{ route("borrow.book") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    book_id: {{ $book->id }},
                    borrow_days: days,
                    distance: distance,
                    quantity: quantity,
                    note: `Yêu cầu mượn sách - ${quantity} cuốn - ${days} ngày`
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.status === 401) {
                    return response.json().then(data => {
                        alert(data.message || 'Vui lòng đăng nhập để mượn sách!');
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = '{{ route("login") }}';
                        }
                        return;
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (!data) {
                    console.error('No data returned from server');
                    alert('Không nhận được phản hồi từ server!');
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Xác nhận mượn sách';
                    return;
                }
                
                if (data.success) {
                    console.log('Borrow created successfully:', data.data);
                    closeBorrowModal();
                    
                    // Hiển thị thông báo thành công với thông tin chi tiết
                    const quantity = data.data?.quantity || 1;
                    const message = (data.message || 'Đã gửi yêu cầu mượn sách thành công!') + 
                        '\n\nSố lượng mượn: ' + quantity + ' cuốn' +
                        '\nMã phiếu mượn: ' + (data.data?.borrow_id || 'N/A') +
                        '\nMã chi tiết: ' + (data.data?.borrow_item_id || 'N/A') +
                        '\n\nYêu cầu đã được gửi và sẽ hiển thị trong trang "Quản lý mượn sách" của admin.';
                    
                    alert(message);
                    
                    // Redirect đến trang sách đang mượn để xem yêu cầu vừa tạo
window.location.href = '{{ route("borrow-cart.checkout") }}';                } else {
                    console.error('Borrow creation failed:', data.message);
                    alert(data.message || 'Có lỗi xảy ra khi gửi yêu cầu mượn sách!');
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Xác nhận mượn sách';
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('Có lỗi xảy ra khi gửi yêu cầu mượn sách: ' + error.message);
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Xác nhận mượn sách';
            });
        }


        // Các hàm reservation đã được xóa (thay bằng chức năng thêm vào giỏ sách)



        // Đóng modal khi click bên ngoài
        document.getElementById('borrowModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBorrowModal();
            }
        });

    </script>

    @auth
    <script>
    // Load số lượng giỏ sách khi trang load
    document.addEventListener('DOMContentLoaded', function() {
        loadBorrowCartCount();
    });

    function loadBorrowCartCount() {
        fetch('{{ route('borrow-cart.count') }}')
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.getElementById('borrow-cart-count');
                if (cartCountElement) {
                    const count = data.count || 0;
                    cartCountElement.textContent = count;
                    cartCountElement.style.display = count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => {
                console.error('Error loading cart count:', error);
            });
    }
    </script>
    @endauth

    <!-- Modal Phiếu Mượn -->
    <div id="borrowModal" class="borrow-modal-overlay">
        <div class="borrow-modal">
            <button class="close-modal" onclick="closeBorrowModal()">&times;</button>
            <div class="borrow-modal-header">
                <h2>📖 PHIẾU MƯỢN SÁCH</h2>
                <div class="subtitle">Vui lòng nhập thông tin cho từng quyển sách</div>
            </div>
            
            <!-- Container cho danh sách items (sẽ được tạo động bằng JavaScript) -->
            <div id="borrowModalInputs" style="margin-bottom: 20px; max-height: 400px; overflow-y: auto;">
                <!-- Các input cho từng quyển sách sẽ được thêm vào đây -->
            </div>

            <!-- Container cho thông tin tóm tắt và nút xác nhận -->
            <div id="borrowModalContent">
                <div class="loading-spinner">Đang tải thông tin...</div>
            </div>
        </div>
    </div>

    <!-- Modal Đặt Trước đã được xóa (thay bằng chức năng thêm vào giỏ sách) -->
</body>
</html>


