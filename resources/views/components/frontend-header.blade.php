<header class="site-header">
    <div class="header-container">
        <div class="header-logo">
            <a href="{{ route('home') }}">
                <i class="fas fa-book-reader"></i>
                <span>Thư viện</span>
            </a>
        </div>
        
        <nav class="header-nav">
            <ul>
                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                @auth
                    <li><a href="{{ route('account') }}"><i class="fas fa-user"></i> Tài khoản</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f5f5f5;
    }

    .site-header {
        background: linear-gradient(135deg, #0066cc, #0052a3);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    .header-logo a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
        text-decoration: none;
        font-size: 24px;
        font-weight: 700;
        transition: opacity 0.3s;
    }

    .header-logo a:hover {
        opacity: 0.9;
    }

    .header-logo i {
        font-size: 32px;
    }

    .header-nav ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 30px;
        align-items: center;
    }

    .header-nav a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: opacity 0.3s;
    }

    .header-nav a:hover {
        opacity: 0.8;
    }

    .logout-btn {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0;
        transition: opacity 0.3s;
    }

    .logout-btn:hover {
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            height: auto;
            padding: 15px 20px;
        }

        .header-nav ul {
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .header-nav a,
        .logout-btn {
            font-size: 14px;
        }
    }
</style>

