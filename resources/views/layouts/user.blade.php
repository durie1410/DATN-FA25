<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WAKA - Thư Viện Online')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>📚</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00ff99;
            --primary-dark: #00b894;
            --secondary-color: #ffdd00;
            --background-dark: #0a0a0a;
            --background-card: #1c1c1c;
            --header-bg: #052d23;
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --text-muted: #aaa;
            --transition-fast: 0.2s;
            --transition-normal: 0.3s;
            --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-dark);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: var(--header-bg);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 28px;
            color: var(--primary-color);
            font-weight: bold;
            letter-spacing: 2px;
            cursor: pointer;
            transition: transform var(--transition-normal) var(--ease-smooth);
            text-decoration: none;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 10px;
            margin: 0;
        }

        nav li {
            margin: 0 15px;
        }

        nav a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color var(--transition-normal) var(--ease-smooth);
            position: relative;
        }

        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--primary-color);
            transition: width var(--transition-normal) var(--ease-smooth);
        }

        nav a:hover,
        nav a.active {
            color: var(--primary-color);
        }

        nav a:hover::after,
        nav a.active::after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-primary);
            transition: all var(--transition-normal) var(--ease-smooth);
        }

        .search-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .user-menu {
            position: relative;
        }

        .btn-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background-color: var(--primary-dark);
            border: none;
            color: var(--text-primary);
            border-radius: 25px;
            cursor: pointer;
            transition: all var(--transition-normal) var(--ease-smooth);
            font-weight: 500;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-user:hover {
            background-color: #019c80;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 240px;
            background: var(--background-card);
            border: 1px solid rgba(0, 255, 153, 0.2);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-normal) var(--ease-smooth);
            z-index: 1000;
            overflow: hidden;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-fast) var(--ease-smooth);
            font-size: 14px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background: rgba(0, 255, 153, 0.1);
            color: var(--primary-color);
        }

        .dropdown-item svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        .dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 8px 0;
        }

        .logout-item {
            color: #ff6b6b;
        }

        .logout-item:hover {
            background: rgba(255, 107, 107, 0.1);
            color: #ff5252;
        }

        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 60px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title i {
            color: var(--primary-color);
        }

        .page-subtitle {
            font-size: 16px;
            color: #888;
            font-weight: 400;
        }

        /* Cards */
        .card {
            background: var(--background-card);
            border: 1px solid rgba(0, 255, 153, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            transition: all var(--transition-normal) var(--ease-smooth);
        }

        .card:hover {
            border-color: rgba(0, 255, 153, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary-color);
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all var(--transition-normal) var(--ease-smooth);
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #000;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 153, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-color);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--background-card), #0f0f0f);
            border: 1px solid rgba(0, 255, 153, 0.1);
            border-radius: 15px;
            padding: 25px;
            transition: all var(--transition-normal) var(--ease-smooth);
        }

        .stat-card:hover {
            border-color: rgba(0, 255, 153, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 13px;
            color: #888;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-icon.primary {
            background: rgba(0, 255, 153, 0.15);
            color: var(--primary-color);
        }

        .stat-icon.warning {
            background: rgba(255, 221, 0, 0.15);
            color: var(--secondary-color);
        }

        .stat-icon.danger {
            background: rgba(255, 107, 107, 0.15);
            color: #ff6b6b;
        }

        .stat-icon.success {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 13px;
            color: #888;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
        }

        .alert-danger {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff6b6b;
        }

        .alert-warning {
            background: rgba(255, 221, 0, 0.15);
            border: 1px solid rgba(255, 221, 0, 0.3);
            color: var(--secondary-color);
        }

        .alert-info {
            background: rgba(0, 255, 153, 0.15);
            border: 1px solid rgba(0, 255, 153, 0.3);
            color: var(--primary-color);
        }

        /* Footer */
        footer {
            background-color: #052d23;
            padding: 60px 60px 30px;
            color: #aaa;
            font-size: 14px;
            margin-top: 80px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #bbb;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-text {
            color: #888;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            header {
                padding: 20px 30px;
            }

            .main-container {
                padding: 30px 30px;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-wrap: wrap;
                padding: 15px 20px;
            }

            nav {
                display: none;
            }

            .main-container {
                padding: 20px 20px;
            }

            .page-title {
                font-size: 28px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            footer {
                padding: 40px 20px 20px;
            }

            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--background-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 255, 153, 0.3);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 255, 153, 0.5);
        }

        /* Text Selection */
        ::selection {
            background-color: var(--primary-color);
            color: #000;
        }

        ::-moz-selection {
            background-color: var(--primary-color);
            color: #000;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header>
        <a href="{{ route('home') }}" class="logo">WAKA</a>
        
        <nav>
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="{{ route('books.public') }}" class="{{ request()->routeIs('books.public') ? 'active' : '' }}">Khám phá</a></li>
                @auth
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="#" class="{{ request()->routeIs('user.history') ? 'active' : '' }}">Sách của tôi</a></li>
                @endauth
            </ul>
        </nav>

        <div class="header-actions">
            <button class="search-btn" aria-label="Tìm kiếm">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>

            @guest
                <a href="{{ route('register') }}" class="btn btn-secondary" style="padding: 10px 20px; font-size: 14px;">Đăng ký</a>
                <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 10px 20px; font-size: 14px;">Đăng nhập</a>
            @else
                <div class="user-menu">
                    <button class="btn-user" onclick="toggleUserMenu()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ auth()->user()->name }}
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        @if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7"/>
                                    <rect x="14" y="3" width="7" height="7"/>
                                    <rect x="14" y="14" width="7" height="7"/>
                                    <rect x="3" y="14" width="7" height="7"/>
                                </svg>
                                Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="dropdown-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="9"/>
                                <rect x="14" y="3" width="7" height="5"/>
                                <rect x="14" y="12" width="7" height="9"/>
                                <rect x="3" y="16" width="7" height="5"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="#" class="dropdown-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Hồ sơ của tôi
                        </a>
                        <a href="#" class="dropdown-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                            Sách của tôi
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul style="margin: 8px 0 0 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Giới thiệu</a>
                <a href="#">Liên hệ</a>
                <a href="#">Chính sách</a>
                <a href="#">Điều khoản</a>
                <a href="#">Trợ giúp</a>
            </div>
            <div class="footer-text">
                © {{ date('Y') }} WAKA - Thư Viện Online. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Toggle user menu
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            if (userMenu && !userMenu.contains(event.target)) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) {
                    dropdown.classList.remove('show');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>










