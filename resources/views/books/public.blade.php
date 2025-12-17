<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sách mới - Nhà xuất bản Xây dựng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Biến CSS (Tùy chọn, để dễ quản lý màu sắc) */
        :root {
            --primary-color: #e51d2e; /* Màu đỏ chủ đạo cho logo, nút, liên kết */
            --secondary-color: #f7f7f7; /* Màu nền xám nhạt cho các khu vực lọc/phân trang */
            --text-dark: #333;
            --text-light: #666;
            --border-color: #ddd;
            --header-bg: #fff;
            --search-bg: #f8f8f8; /* Màu nền cho thanh tìm kiếm */
            --button-primary: #e51d2e;
        }

        /* Thiết lập cơ bản */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Nền trang chính */
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
            color: var(--text-dark);
        }

        /* --- HEADER (Phần đầu trang) --- */
        .header {
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .logo-text {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo-text .text-red {
            color: var(--primary-color);
        }

        .logo-text .text-black {
            color: #000;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 14px;
        }

        .contact-info .phone {
            color: #0066cc;
            font-weight: bold;
            font-size: 18px;
        }

        .user-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .action-button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .cart-button {
            background-color: var(--primary-color);
            color: white;
            position: relative;
        }

        .cart-button .cart-count {
            background-color: white;
            color: var(--primary-color);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }

        .login-button {
            background-color: white;
            color: #333;
            border: 1px solid var(--border-color);
        }

        .header-search {
            display: flex;
            align-items: center;
            background-color: #ffd700; /* Màu vàng */
            padding: 15px 0;
        }

        .search-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding: 0 20px;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            flex-grow: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .search-button {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .search-button:hover {
            background-color: #c41e2f;
        }

        .search-button i {
            font-size: 18px;
        }

        /* --- MAIN CONTENT (Nội dung chính) --- */
        .main-content {
        display: flex;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            gap: 20px;
        }

        /* --- SIDEBAR (Thanh bên trái) --- */
        .sidebar {
            width: 280px;
            flex-shrink: 0;
        }

        .sidebar h3 {
            background-color: #f0f0f0; /* Nền cho tiêu đề danh mục/lọc */
            padding: 10px;
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            color: var(--text-dark);
        }

        .sidebar h4 {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 12px 15px;
            margin: 0 0 15px 0;
            font-size: 15px;
            font-weight: 600;
            border-radius: 6px;
            color: var(--text-dark);
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        gap: 8px;
        }

        .sidebar h4 i {
            color: var(--primary-color);
            font-size: 16px;
        }

        .sidebar-section {
            margin-bottom: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .sidebar-section:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .sidebar-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
        .sidebar-list li {
            padding: 0;
            margin-bottom: 5px;
        }

        .sidebar-list li:last-child {
            margin-bottom: 0;
        }

        .sidebar-list a {
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        text-decoration: none;
            position: relative;
            font-size: 14px;
        }

        .sidebar-list a::before {
            content: "▸";
            color: var(--primary-color);
            font-size: 12px;
            opacity: 0;
            transition: all 0.3s ease;
            margin-right: -10px;
        }

        .sidebar-list a:hover {
        color: var(--primary-color);
            background-color: #fff5f5;
            padding-left: 20px;
            transform: translateX(5px);
        }

        .sidebar-list a:hover::before {
            opacity: 1;
            margin-right: 0;
        }
    
        .sidebar-list .active-category {
        font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), #c41e2f);
            box-shadow: 0 2px 6px rgba(229, 29, 46, 0.3);
            padding-left: 20px;
        }

        .sidebar-list .active-category::before {
            opacity: 1;
            margin-right: 0;
            color: white;
        }

        .sidebar-list .active-category:hover {
            background: linear-gradient(135deg, #c41e2f, var(--primary-color));
            transform: translateX(5px);
        }

        /* Lọc theo Tác giả */
        .author-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .author-list li {
            padding: 8px 0;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            border-radius: 4px;
            padding-left: 8px;
        }

        .author-list li:hover {
            background-color: #fff5f5;
            color: var(--primary-color);
        }

        .author-list input[type="checkbox"] {
            margin-right: 0;
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        /* Price filter section */
        .price-filter {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }

        .price-filter input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .price-filter input[type="number"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 29, 46, 0.1);
        }

        .price-filter-button {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary-color), #c41e2f);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(229, 29, 46, 0.3);
        }

        .price-filter-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 29, 46, 0.4);
        }

        /* --- CONTENT (Khu vực danh sách sách) --- */
        .content {
            flex-grow: 1;
        }

        .breadcrumbs {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .breadcrumbs a {
            color: var(--text-light);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .breadcrumbs a:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .breadcrumbs .home-icon {
            font-size: 16px;
        }

        .content h2 {
            font-size: 24px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        /* Bộ lọc định dạng và Sắp xếp */
        .filter-sort-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--secondary-color);
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .filter-format button {
            background: white;
            border: 1px solid var(--border-color);
            padding: 8px 15px;
            margin-right: 5px;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-format .active-filter {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .sort-dropdown select {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        /* Danh sách sách */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); /* 5 cột */
            gap: 20px;
        }

        .book-item {
            text-align: center;
            border: 1px solid var(--border-color);
            padding: 10px;
            border-radius: 4px;
            transition: box-shadow 0.3s;
        }

        .book-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            height: 220px; /* Chiều cao cố định cho ảnh bìa */
            overflow: hidden;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f5f5f5;
        }

        .book-cover img {
            max-width: 100%;
            max-height: 100%;
            height: auto;
            display: block;
            object-fit: contain;
        }

        .book-title {
            font-size: 14px;
            font-weight: bold;
            color: var(--text-dark);
            margin-bottom: 5px;
            min-height: 3em; /* Đảm bảo chiều cao cho tiêu đề 2 dòng */
            line-height: 1.5;
        }

        .book-author, .book-publisher {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 3px;
        }

        .book-price {
            font-size: 16px;
            color: var(--primary-color);
            font-weight: bold;
            margin-top: 10px;
        }

        /* --- PHÂN TRANG (Pagination) --- */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 5px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            color: var(--text-dark);
            text-align: center;
            transition: background-color 0.2s;
        }

        .pagination a:hover {
            background-color: var(--secondary-color);
        }

        .pagination .current-page {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            font-weight: bold;
        }

        .pagination .page-item {
            list-style: none;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-top">
            <div class="logo">
                <div class="logo-icon">📚</div>
                <a href="{{ route('home') }}" class="logo-text">
                    <span class="text-red">THƯ VIỆN</span>
                    <span class="text-black">LIBHUB</span>
                </a>
            </div>
            <div class="contact-info">
                <div>
                    <span>Hotline khách lẻ: </span>
                    <span class="phone">0327888669</span>
                </div>
                <div>
                    <span>Hotline khách sỉ: </span>
                    <span class="phone">02439741791 - 0327888669</span>
                </div>
            </div>
            <div class="user-actions">
                @auth
                    <a href="{{ route('borrow-cart.index') }}" class="action-button cart-button" id="borrow-cart-link" title="Giỏ sách">
                        <i class="fas fa-book"></i>
                        <span>Giỏ sách</span>
                        <span class="cart-count" id="borrow-cart-count" style="display: none;">0</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="action-button login-button">{{ auth()->user()->name }}</a>
                @else
                    <a href="{{ route('borrow-cart.index') }}" class="action-button cart-button" title="Giỏ sách">
                        <i class="fas fa-book"></i>
                        <span>Giỏ sách</span>
                        <span class="cart-count">0</span>
                    </a>
                    <a href="{{ route('login') }}" class="action-button login-button">Đăng nhập</a>
                @endauth
            </div>
        </div>
        <div class="header-search">
            <form action="{{ route('books.public') }}" method="GET" class="search-container">
                <input type="text" 
                       name="keyword" 
                       value="{{ request('keyword') }}" 
                       class="search-input" 
                       placeholder="Tìm sách, tác giả, sản phẩm mong muốn...">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                    <span>Tìm kiếm</span>
                </button>
            </form>
        </div>
    </header>

    <div class="main-content">

        <aside class="sidebar">
            <h3>DANH MỤC SÁCH</h3>
            <div class="sidebar-section">
                <h4><i class="fas fa-tags"></i> CHỦ ĐỀ TIÊU BIỂU</h4>
                <ul class="sidebar-list">
                    @php
                        $categories = \App\Models\Category::all();
                        $activeCategoryId = request('category_id');
                    @endphp
                    <li>
                        <a href="{{ route('books.public') }}" class="{{ !$activeCategoryId ? 'active-category' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Tất cả</span>
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('books.public', ['category_id' => $category->id]) }}" 
                               class="{{ $activeCategoryId == $category->id ? 'active-category' : '' }}">
                                <i class="fas fa-book"></i>
                                <span>{{ $category->ten_the_loai }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-section">
                <h4><i class="fas fa-user-edit"></i> TÁC GIẢ NHIỀU SÁCH NHẤT</h4>
                <ul class="author-list">
                    @php
                        $topAuthors = \App\Models\Book::selectRaw('tac_gia, COUNT(*) as count')
                            ->whereNotNull('tac_gia')
                            ->where('tac_gia', '!=', '')
                            ->groupBy('tac_gia')
                            ->orderBy('count', 'desc')
                            ->limit(4)
                            ->get();
                    @endphp
                    @foreach($topAuthors as $author)
                        <li>
                            <input type="checkbox" name="author[]" value="{{ $author->tac_gia }}">
                            <span>{{ $author->tac_gia }} <strong>({{ $author->count }})</strong></span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-section">
                <h4><i class="fas fa-language"></i> THEO NGÔN NGỮ</h4>
                <ul class="author-list">
                    <li>
                        <input type="checkbox" checked>
                        <span>Tiếng Việt</span>
                    </li>
                    <li>
                        <input type="checkbox">
                        <span>Tiếng Anh</span>
                    </li>
                    <li>
                        <input type="checkbox">
                        <span>Tiếng Nhật</span>
                    </li>
                    <li>
                        <input type="checkbox">
                        <span>Tiếng Trung Quốc</span>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section">
                <h4><i class="fas fa-dollar-sign"></i> THEO GIÁ SÁCH GIẤY</h4>
                <form method="GET" action="{{ route('books.public') }}">
                    @if(request('keyword'))
                        <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    @endif
                    @if(request('category_id'))
                        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    @endif
                    <div class="price-filter">
                        <input type="number" 
                               name="price_min" 
                               placeholder="Giá tối thiểu" 
                               value="{{ request('price_min') }}">
                        <input type="number" 
                               name="price_max" 
                               placeholder="Giá tối đa" 
                               value="{{ request('price_max') }}">
                    </div>
                    <button type="submit" class="price-filter-button">
                        <i class="fas fa-filter"></i>
                        Lọc giá
                    </button>
                </form>
            </div>
        </aside>

        <main class="content">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}">
                    <i class="fas fa-home home-icon"></i>
                    <span>Trang chủ</span>
                </a>
                <span>/</span>
                <span>Sách mới</span>
            </div>

            <h2>Sách mới</h2>

            <div class="filter-sort-bar">
                <div class="filter-format">
                    <button class="active-filter">Tất cả</button>
                    <button>Sách giấy</button>
                </div>
                <div class="sort-dropdown">
                    <label for="sort-by">Sắp xếp theo:</label>
                    <select id="sort-by" onchange="
                        var url = new URL(window.location.href);
                        url.searchParams.set('sort', this.value);
                        window.location.href = url.toString();
                    ">
                        <option value="new" {{ request('sort') == 'new' || !request('sort') ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>
                </div>
            </div>

            @if($books->count() > 0)
                <div class="book-grid">
                    @foreach($books as $book)
                        <div class="book-item">
                            <a href="{{ route('books.show', $book->id) }}">
                                <div class="book-cover">
                                    @if($book->hinh_anh)
                                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999;">
                                            <span>Không có ảnh</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="book-title">
                                    {{ $book->ten_sach }}
                                </div>
                                <div class="book-author">{{ $book->tac_gia }}</div>
                                <div class="book-price">
                                    @php
                                        $price = $book->gia_ban ?? $book->gia ?? 0;
                                    @endphp
                                    @if($price > 0)
                                        Chỉ từ {{ number_format($price) }}₫
                                    @else
                                        0₫
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px;">
                    <p>Không tìm thấy sách nào.</p>
                </div>
            @endif

            @if($books->hasPages())
                <div class="pagination">
                    @if($books->onFirstPage())
                        <span>&lt;&lt;</span>
                        <span>&lt;</span>
                    @else
                        <a href="{{ $books->url(1) }}">&lt;&lt;</a>
                        <a href="{{ $books->previousPageUrl() }}">&lt;</a>
                    @endif

                    @foreach($books->getUrlRange(max(1, $books->currentPage() - 2), min($books->lastPage(), $books->currentPage() + 2)) as $page => $url)
                        @if($page == $books->currentPage())
                            <span class="current-page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}">&gt;</a>
                        <a href="{{ $books->url($books->lastPage()) }}">&gt;&gt;</a>
                    @else
                        <span>&gt;</span>
                        <span>&gt;&gt;</span>
                    @endif
                </div>
            @endif

        </main>
    </div>

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
                cartCountElement.style.display = count > 0 ? 'inline-block' : 'none';
            }
        })
        .catch(error => {
            console.error('Error loading cart count:', error);
        });
}
</script>
@endauth
</body>
</html>
