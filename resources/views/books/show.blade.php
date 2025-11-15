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
                <a href="{{ route('cart.index') }}" class="cart-link">
                    <span class="cart-icon">🛒</span>
                    <span>Giỏ sách</span>
                    <span class="cart-badge" id="cart-count">0</span>
                </a>
                @auth
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
                            <a href="{{ route('account.purchased-books') }}" class="dropdown-item">
                                <span>❤️</span> Sách đã mua
                            </a>
                            @if(auth()->user()->reader)
                            <a href="{{ route('account.borrowed-books') }}" class="dropdown-item">
                                <span>📚</span> Sách đang mượn
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
                            <a href="#" class="dropdown-item">
                                <span>💳</span> Lịch sử nạp tiền
                            </a>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
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
                                <!-- Hiển thị thông tin số lượng sách có sẵn -->
                                <div style="padding: 15px; background: #e8f5e9; border-radius: 4px; margin-bottom: 20px; border: 1px solid #4caf50;">
                                    <strong style="font-size: 1.1em;">📚 Sách có sẵn:</strong> 
                                    <span style="color: #2e7d32; font-weight: bold; font-size: 1.1em;">{{ $stats['available_copies'] ?? 0 }} cuốn</span>
                                    @if(isset($hasActiveReservation) && $hasActiveReservation)
                                        <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 4px; border: 1px solid #ffc107; color: #856404;">
                                            ⚠️ Bạn đã có yêu cầu mượn sách này. Vui lòng chờ duyệt.
                                        </div>
                                    @endif
                                </div>

                                <div class="action-buttons">
                                    @auth
                                        @if(isset($hasActiveReservation) && $hasActiveReservation)
                                            <button class="btn btn-buy" disabled style="opacity: 0.6; cursor: not-allowed; width: 100%;">
                                                <span style="font-size: 1.2em;">⏳</span> Đã gửi yêu cầu
                                            </button>
                                        @else
                                            <button class="btn btn-buy" onclick="borrowNow()" style="flex: 1;">
                                                <span style="font-size: 1.2em;">📖</span> Mượn ngay
                                            </button>
                                            <button class="btn btn-cart" onclick="reserveBook()" style="flex: 1;">
                                                <span style="font-size: 1.2em;">📋</span> Đặt trước
                                            </button>
                                        @endif
                                    @else
                                        <button class="btn btn-buy" onclick="alert('Vui lòng đăng nhập để mượn sách!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer; flex: 1;">
                                            <span style="font-size: 1.2em;">📖</span> Mượn ngay
                                        </button>
                                        <button class="btn btn-cart" onclick="alert('Vui lòng đăng nhập để đặt trước sách!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer; flex: 1;">
                                            <span style="font-size: 1.2em;">📋</span> Đặt trước
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
                                        <button class="btn btn-buy" onclick="buyNow()" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                                            <span style="font-size: 1.2em;">$</span> Mua ngay
                                        </button>
                                        <button class="btn btn-cart" onclick="addToCart()" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                                            <span style="font-size: 1.2em;">🛒</span> Thêm vào giỏ
                                        </button>
                                    @else
                                        <button class="btn btn-buy" onclick="alert('Vui lòng đăng nhập để mua hàng!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer;" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                                            <span style="font-size: 1.2em;">$</span> Mua ngay
                                        </button>
                                        <button class="btn btn-cart" onclick="alert('Vui lòng đăng nhập để thêm vào giỏ hàng!'); window.location.href='{{ route('login') }}';" style="opacity: 0.7; cursor: pointer;" {{ ($stats['stock_quantity'] ?? 0) == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
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

        function addToCart() {
            // Kiểm tra đăng nhập
            @guest
                alert('Vui lòng đăng nhập để thêm vào giỏ hàng!');
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

            const cartData = {
                book_id: {{ $book->id }},
                paper_quantity: quantity
            };
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(cartData)
            })
            .then(response => {
                // Kiểm tra nếu response là 401 (Unauthorized)
                if (response.status === 401) {
                    return response.json().then(data => {
                        alert(data.message || 'Vui lòng đăng nhập để thêm vào giỏ hàng!');
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.href = '{{ route("login") }}';
                        }
                        return;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (!data) return; // Đã xử lý redirect ở trên
                
                if (data.success) {
                    alert('Đã thêm vào giỏ hàng!');
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count || 0;
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                    // Nếu có redirect_url, chuyển hướng đến trang đăng nhập
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng!');
            });
        }

        // Khởi tạo giá khi trang load
        updateTotalPrice();

        // Load cart count on page load
        function loadCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        if (data.count > 0) {
                            cartCountElement.textContent = data.count;
                            cartCountElement.style.display = 'inline-block';
                        } else {
                            cartCountElement.textContent = '0';
                            cartCountElement.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }

        // Kiểm tra chế độ mượn sách
        const isBorrowMode = {{ isset($mode) && $mode === 'borrow' ? 'true' : 'false' }};

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

            const borrowDays = prompt('Nhập số ngày mượn (mặc định 14 ngày):', '14');
            if (borrowDays === null) {
                return; // Người dùng đã hủy
            }

            const days = parseInt(borrowDays) || 14;
            if (days < 1 || days > 30) {
                alert('Số ngày mượn phải từ 1 đến 30 ngày.');
                return;
            }

            const message = `Bạn có chắc chắn muốn mượn sách này?\n- Thời gian mượn: ${days} ngày\n\nYêu cầu sẽ được gửi và chờ quản trị viên duyệt.`;

            if (!confirm(message)) {
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
                    note: `Yêu cầu mượn sách - ${days} ngày`
                })
            })
            .then(response => {
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
                if (!data) return;
                
                if (data.success) {
                    alert(data.message || 'Đã gửi yêu cầu mượn sách thành công! Vui lòng chờ quản trị viên duyệt.');
                    // Reload trang để cập nhật trạng thái
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi gửi yêu cầu mượn sách!');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi yêu cầu mượn sách!');
            });
        }

        // Hàm đặt trước sách
        function reserveBook() {
            @guest
                alert('Vui lòng đăng nhập để đặt trước sách!');
                window.location.href = '{{ route("login") }}';
                return;
            @endguest

            const availableCopies = {{ $stats['available_copies'] ?? 0 }};
            
            if (availableCopies <= 0) {
                alert('Hiện tại không còn sách có sẵn để đặt trước. Vui lòng thử lại sau.');
                return;
            }

            const message = `Bạn có chắc chắn muốn đặt trước sách này?\n\nYêu cầu sẽ được gửi và chờ quản trị viên duyệt.`;

            if (!confirm(message)) {
                return;
            }

            // Gửi yêu cầu đặt trước sách (sử dụng borrow API với borrow_days = 14)
            fetch('{{ route("borrow.book") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    book_id: {{ $book->id }},
                    borrow_days: 14,
                    note: 'Đặt trước sách'
                })
            })
            .then(response => {
                if (response.status === 401) {
                    return response.json().then(data => {
                        alert(data.message || 'Vui lòng đăng nhập để đặt trước sách!');
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
                if (!data) return;
                
                if (data.success) {
                    alert(data.message || 'Đã gửi yêu cầu đặt trước sách thành công! Vui lòng chờ quản trị viên duyệt.');
                    // Reload trang để cập nhật trạng thái
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi gửi yêu cầu đặt trước sách!');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi gửi yêu cầu đặt trước sách!');
            });
        }


        // Load cart count when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadCartCount();
        });
    </script>
</body>
</html>
