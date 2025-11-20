<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - Nhà Xuất Bản Xây Dựng</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <a href="{{ route('home') }}" class="logo-section" style="text-decoration: none; color: inherit; cursor: pointer;">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-part1">THƯ VIỆN</span>
                    <span class="logo-part2">LIBHUB</span>
                </div>
            </a>
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

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb-nav">
        <div class="breadcrumb-container">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Thông tin tài khoản</span>
        </div>
    </nav>

    <main class="account-container">
        <aside class="account-sidebar">
            <div class="user-profile">
                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="username">{{ $user->name }}</div>
            </div>
            <nav class="account-nav">
                <ul>
                    <li><a href="{{ route('account.purchased-books') }}"><span class="icon">📖</span> Sách đã mua</a></li>
                    <li class="active"><a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin khách hàng</a></li>
                    <li><a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a></li>
                    <li><a href="{{ route('orders.index') }}"><span class="icon">🛒</span> Lịch sử mua hàng</a></li>
                    @if(!$user->reader)
                    <li><a href="{{ route('account.register-reader') }}"><span class="icon">📝</span> Đăng kí độc giả</a></li>
                    @endif
                    <li><a href="#"><span class="icon">💳</span> Lịch sử nạp tiền</a></li>
                    <li><a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon">➡️</span> Đăng xuất</a></li>
                </ul>
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        <section class="account-content">
            <div class="coin-balance-section">
                <div class="coin-card">
                    <div>
                        <p class="coin-title">Số dư Book coin</p>
                        <p class="coin-value">0 coin</p>
                    </div>
                    <button class="coin-action-btn">Nạp thêm</button>
                </div>
                <div class="coin-card">
                    <div>
                        <p class="coin-title">Số dư Book coin khuyến mãi</p>
                        <p class="coin-value">0 coin</p>
                    </div>
                </div>
            </div>
            
            <div class="account-details-form">
                <h2 class="form-title">Thông tin tài khoản</h2>
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="fullName">Tên đầy đủ của bạn</label>
                        <div class="input-with-icon">
                            <input type="text" id="fullName" name="name" value="{{ $user->name }}" readonly>
                            <span class="input-icon">📋</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại của bạn</label>
                        <div class="input-with-icon">
                            <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" value="{{ $user->phone ?? '' }}">
                            <span class="input-icon">📞</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email của bạn</label>
                        <div class="input-with-icon">
                            <input type="email" id="email" name="email" value="{{ $user->email }}" readonly>
                            <span class="input-icon">✉️</span>
                        </div>
                    </div>
                    <div class="form-group half-width">
                        <label for="province">Tỉnh/Thành phố</label>
                        <div class="input-with-icon">
                            <select id="province" name="province">
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                <!-- Có thể thêm danh sách tỉnh/thành phố ở đây -->
                            </select>
                            <span class="input-icon arrow-down">▼</span>
                        </div>
                    </div>
                    <div class="form-group half-width">
                        <label for="district">Quận/Huyện</label>
                        <div class="input-with-icon">
                            <select id="district" name="district">
                                <option value="">Chọn Quận/Huyện</option>
                                <!-- Có thể thêm danh sách quận/huyện ở đây -->
                            </select>
                            <span class="input-icon arrow-down">▼</span>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address">Địa chỉ nhận hàng</label>
                        <div class="input-with-icon">
                            <input type="text" id="address" name="address" placeholder="Địa chỉ" value="{{ $user->address ?? '' }}">
                            <span class="input-icon">🏠</span>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-update">Cập nhật</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    @include('components.footer')
</body>
</html>

