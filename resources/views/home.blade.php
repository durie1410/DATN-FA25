<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Waka - Đọc sách online - Library Management</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='0.9em' font-size='90'>📚</text></svg>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <style>
    body {
      background-color: #ffffff !important;
      color: #000000 !important;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">WAKA</div>
    <nav>
      <ul>
        <li><a href="#">Sách điện tử</a></li>
        <li><a href="#">Sách hội viên</a></li>
        <li><a href="#">Sách nói</a></li>
        <li><a href="#">Truyện tranh</a></li>
        <li><a href="#">Dịch vụ xuất bản</a></li>
      </ul>
    </nav>
    <div class="header-actions">
      <!-- Search Button -->
      <button class="search-btn" id="searchBtn" aria-label="Tìm kiếm">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/>
          <path d="m21 21-4.35-4.35"/>
        </svg>
      </button>

      <!-- Cart Button -->
      <a href="{{ route('cart.index') }}" class="cart-btn" aria-label="Giỏ hàng">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="21" r="1"/>
          <circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <span class="cart-badge" id="cartCount">0</span>
      </a>
      
      <!-- Auth Buttons -->
      <div class="auth-buttons">
        @guest
          <a href="{{ route('register') }}" class="btn btn-register">Đăng ký</a>
          <a href="{{ route('login') }}" class="btn btn-login">Đăng nhập</a>
        @else
          <div class="user-menu">
            <button class="btn btn-user" id="userMenuBtn">
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
              <a href="#" class="dropdown-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
                Hồ sơ của tôi
              </a>
             <a href="{{ route('nap-tien.form') }}" class="dropdown-item">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
    </svg>
    Nạp tiền
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
        @endauth
      </div>
    </div>
  </header>

  <!-- Search Modal -->
  <div class="search-modal" id="searchModal">
    <div class="search-modal-content">
      <div class="search-modal-header">
        <h3>Tìm kiếm sách</h3>
        <button class="search-close" id="searchClose">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <div class="search-input-wrapper">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/>
          <path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" class="search-input" id="searchInput" placeholder="Nhập tên sách, tác giả, thể loại..." autocomplete="off">
      </div>
      <div class="search-suggestions">
        <h4>Gợi ý tìm kiếm</h4>
        <div class="suggestion-tags">
          <span class="suggestion-tag">Đắc Nhân Tâm</span>
          <span class="suggestion-tag">Nhà Giả Kim</span>
          <span class="suggestion-tag">Tuổi Trẻ Đáng Giá</span>
          <span class="suggestion-tag">Tư duy ngược</span>
          <span class="suggestion-tag">Chủ nghĩa tối giản</span>
          <span class="suggestion-tag">Tiểu thuyết</span>
          <span class="suggestion-tag">Kinh doanh</span>
          <span class="suggestion-tag">Tâm lý học</span>
        </div>
      </div>
      <div class="search-results" id="searchResults">
        <!-- Search results will be populated here -->
      </div>
    </div>
    <div class="search-modal-overlay" id="searchOverlay"></div>
  </div>

  <!-- Banner Carousel -->
  <section class="banner-carousel">
    <div class="banner-slides">
      <!-- Slide 1 -->
      <div class="banner-slide active">
        <div class="banner-content">
          <h1>MUA 1 NĂM TẶNG 1 TÚI CANVAS</h1>
          <p>Chỉ 99K - Áp dụng cho mọi khách hàng</p>
          <button class="banner-btn">Mua ngay</button>
        </div>
        <div class="banner-image">
          @php
            $bannerDir = public_path('storage/banners');
            $banner1Extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $banner1Path = null;
            foreach($banner1Extensions as $ext) {
              $path = $bannerDir . '/banner1.' . $ext;
              if(file_exists($path)) {
                $banner1Path = asset('storage/banners/banner1.' . $ext);
                break;
              }
            }
          @endphp
          @if($banner1Path)
            <img src="{{ $banner1Path }}" alt="Banner 1" class="banner-img">
          @else
            <div class="banner-placeholder">
              <svg width="300" height="300" viewBox="0 0 300 300">
                <rect width="300" height="300" fill="#00ff99" opacity="0.1" rx="20"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#00ff99" font-size="24" font-family="Poppins">
                  Banner 1
                </text>
              </svg>
            </div>
          @endif
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="banner-slide">
        <div class="banner-content">
          <h1>ĐỌC SÁCH KHÔNG GIỚI HẠN</h1>
          <p>Hàng ngàn đầu sách chỉ với 99K/tháng</p>
          <button class="banner-btn">Khám phá ngay</button>
        </div>
        <div class="banner-image">
          @php
            $bannerDir = public_path('storage/banners');
            $banner2Extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $banner2Path = null;
            foreach($banner2Extensions as $ext) {
              $path = $bannerDir . '/banner2.' . $ext;
              if(file_exists($path)) {
                $banner2Path = asset('storage/banners/banner2.' . $ext);
                break;
              }
            }
          @endphp
          @if($banner2Path)
            <img src="{{ $banner2Path }}" alt="Banner 2" class="banner-img">
          @else
            <div class="banner-placeholder">
              <svg width="300" height="300" viewBox="0 0 300 300">
                <rect width="300" height="300" fill="#ffdd00" opacity="0.1" rx="20"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#ffdd00" font-size="24" font-family="Poppins">
                  Banner 2
                </text>
              </svg>
            </div>
          @endif
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="banner-slide">
        <div class="banner-content">
          <h1>SÁCH NÓI MIỄN PHÍ</h1>
          <p>Nghe sách mọi lúc mọi nơi - Hoàn toàn miễn phí</p>
          <button class="banner-btn">Nghe ngay</button>
        </div>
        <div class="banner-image">
          @php
            $bannerDir = public_path('storage/banners');
            $banner3Extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $banner3Path = null;
            foreach($banner3Extensions as $ext) {
              $path = $bannerDir . '/banner3.' . $ext;
              if(file_exists($path)) {
                $banner3Path = asset('storage/banners/banner3.' . $ext);
                break;
              }
            }
          @endphp
          @if($banner3Path)
            <img src="{{ $banner3Path }}" alt="Banner 3" class="banner-img">
          @else
            <div class="banner-placeholder">
              <svg width="300" height="300" viewBox="0 0 300 300">
                <rect width="300" height="300" fill="#ff6b9d" opacity="0.1" rx="20"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#ff6b9d" font-size="24" font-family="Poppins">
                  Banner 3
                </text>
              </svg>
            </div>
          @endif
        </div>
      </div>

      <!-- Slide 4 -->
      <div class="banner-slide">
        <div class="banner-content">
          <h1>TRUYỆN TRANH HOT NHẤT</h1>
          <p>Cập nhật mỗi ngày - Đọc trọn bộ không quảng cáo</p>
          <button class="banner-btn">Đọc ngay</button>
        </div>
        <div class="banner-image">
          @php
            $bannerDir = public_path('storage/banners');
            $banner4Extensions = ['jpg', 'jpeg', 'png', 'webp'];
            $banner4Path = null;
            foreach($banner4Extensions as $ext) {
              $path = $bannerDir . '/banner4.' . $ext;
              if(file_exists($path)) {
                $banner4Path = asset('storage/banners/banner4.' . $ext);
                break;
              }
            }
          @endphp
          @if($banner4Path)
            <img src="{{ $banner4Path }}" alt="Banner 4" class="banner-img">
          @else
            <div class="banner-placeholder">
              <svg width="300" height="300" viewBox="0 0 300 300">
                <rect width="300" height="300" fill="#00ddff" opacity="0.1" rx="20"/>
                <text x="50%" y="50%" text-anchor="middle" fill="#00ddff" font-size="24" font-family="Poppins">
                  Banner 4
                </text>
              </svg>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Navigation Arrows -->
    <button class="banner-nav banner-prev" aria-label="Previous slide">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15 18 9 12 15 6"></polyline>
      </svg>
    </button>
    <button class="banner-nav banner-next" aria-label="Next slide">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="9 18 15 12 9 6"></polyline>
      </svg>
    </button>

    <!-- Dots Navigation -->
    <div class="banner-dots">
      <button class="banner-dot active" aria-label="Slide 1"></button>
      <button class="banner-dot" aria-label="Slide 2"></button>
      <button class="banner-dot" aria-label="Slide 3"></button>
      <button class="banner-dot" aria-label="Slide 4"></button>
    </div>
  </section>

  <!-- Bảng xếp hạng -->
  <section class="books">
    <h2>Bảng xếp hạng</h2>
    
    <!-- Tabs Navigation -->
    <div class="ranking-tabs-inline">
      <div class="tabs-container">
        <button class="tab-btn active">Đọc nhiều</button>
        <button class="tab-btn">Nghe nhiều</button>
        <button class="tab-btn">Sách Hiệu Sỏi</button>
        <button class="tab-btn">Truyện tranh</button>
        <button class="tab-btn">Podcast</button>
        <button class="tab-btn">Cộng đồng viết</button>
      </div>
    </div>
    
    <div class="book-list">
      @if(isset($rankingBooks) && $rankingBooks->count() > 0)
        @foreach($rankingBooks as $book)
          <x-book-item :book="$book" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách trong bảng xếp hạng</p>
      @endif
    </div>
  </section>

  <section class="books waka-recommended">
    <h2>Waka đề xuất</h2>
    <div class="book-list">
      @if(isset($wakaRecommendedBooks) && $wakaRecommendedBooks->count() > 0)
        @foreach($wakaRecommendedBooks as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách đề xuất</p>
      @endif
    </div>
  </section>

  <section class="books minimalist-living">
    <h2>Sống Tối Giản Cùng Waka</h2>
    <div class="book-list">
      @if(isset($minimalistBooks) && $minimalistBooks->count() > 0)
        @foreach($minimalistBooks as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách về sống tối giản</p>
      @endif
    </div>
  </section>

  <section class="books spiritual-peace">
    <h2>Thiên Định - Tìm Bình An trong Tâm Hồn</h2>
    <div class="book-list">
      @if(isset($spiritualBooks) && $spiritualBooks->count() > 0)
        @foreach($spiritualBooks as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách về tâm linh</p>
      @endif
    </div>
  </section>

  <section class="books">
    <h2>Hành trình chữa lành, tìm lại chính mình</h2>
    <div class="book-list">
      @if(isset($healingBooks) && $healingBooks->count() > 0)
        @foreach($healingBooks as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách chữa lành</p>
      @endif
    </div>
  </section>

  <section class="books">
    <h2>Sách cho ngày muốn bỏ cuộc</h2>
    <div class="book-list">
      @if(isset($motivationalBooks) && $motivationalBooks->count() > 0)
        @foreach($motivationalBooks as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách động lực</p>
      @endif
    </div>
  </section>

  <section class="books">
    <h2>Cân bằng cảm xúc - Đón nhận hạnh phúc</h2>
    <div class="book-list">
      @if(isset($healingBooks) && $healingBooks->count() > 0)
        @foreach($healingBooks->take(8) as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách</p>
      @endif
    </div>
  </section>

  <section class="books">
    <h2>Hãy sống theo cách của bạn</h2>
    <div class="book-list">
      @if(isset($minimalistBooks) && $minimalistBooks->count() > 0)
        @foreach($minimalistBooks->take(8) as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách</p>
      @endif
    </div>
  </section>

  <section class="books">
    <h2>Nghĩ khác để sống khác</h2>
    <div class="book-list">
      @if(isset($motivationalBooks) && $motivationalBooks->count() > 0)
        @foreach($motivationalBooks->take(8) as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách</p>
      @endif
    </div>
  </section>

  <section class="books">
    <h2>Thấu hiểu người, thay đổi mình</h2>
    <div class="book-list">
      @if(isset($rankingBooks) && $rankingBooks->count() > 0)
        @foreach($rankingBooks->take(8) as $book)
          <x-book-item :book="$book" :premium="true" />
        @endforeach
      @else
        <p style="text-align: center; color: #666; padding: 40px;">Chưa có sách</p>
      @endif
    </div>
  </section>

  <!-- Thư viện sách - Dữ liệu thật từ Database -->
  <section class="books library-collection">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2>Thư viện sách của chúng tôi</h2>
      <a href="{{ route('books.public') }}" style="color: #27ae60; text-decoration: none; font-weight: 600;">Xem tất cả →</a>
    </div>
    
    @if(isset($books) && $books->count() > 0)
      <div class="book-list">
        @foreach($books as $book)
          <div class="book-item" 
               data-book-id="{{ $book->id }}"
               data-book-title="{{ $book->ten_sach }}"
               data-book-author="{{ $book->tac_gia }}"
               data-book-genre="{{ $book->category->ten_danh_muc ?? 'Chưa phân loại' }}"
               data-book-rating="4.5/5"
               data-book-year="{{ $book->nam_xuat_ban ?? 'N/A' }}"
               data-book-description="{{ Str::limit($book->mo_ta ?? 'Chưa có mô tả', 200) }}"
               data-book-premium="false">
            <div class="book-cover">
              @if($book->hinh_anh && file_exists(public_path('storage/' . $book->hinh_anh)))
                <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}" style="width: 160px; height: 240px; object-fit: cover; border-radius: 8px;">
              @else
                <svg width="160" height="240" viewBox="0 0 160 240">
                  <defs>
                    <linearGradient id="grad{{ $book->id }}" x1="0%" y1="0%" x2="0%" y2="100%">
                      <stop offset="0%" style="stop-color:{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};stop-opacity:0.8" />
                      <stop offset="100%" style="stop-color:{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }};stop-opacity:1" />
                    </linearGradient>
                  </defs>
                  <rect width="160" height="240" fill="url(#grad{{ $book->id }})" rx="8"/>
                  <text x="50%" y="50%" text-anchor="middle" fill="white" font-size="14" font-family="Poppins" font-weight="600" style="max-width: 140px;">
                    {{ Str::limit($book->ten_sach, 30) }}
                  </text>
                </svg>
              @endif
            </div>
            <p style="font-weight: 600;">{{ Str::limit($book->ten_sach, 40) }}</p>
            <p style="font-size: 0.85em; color: #666; margin-top: 4px;">{{ $book->tac_gia }}</p>
            <p style="font-size: 0.8em; color: #27ae60; margin-top: 4px;">
              @php
                $availableCopies = $book->inventories->where('status', 'Co san')->count();
              @endphp
              @if($availableCopies > 0)
                <span style="color: #27ae60;">✓ Còn {{ $availableCopies }} bản</span>
              @else
                <span style="color: #e74c3c;">✗ Hết sách</span>
              @endif
            </p>
            
            @auth
              @if($availableCopies > 0)
                <button onclick="borrowBook({{ $book->id }})" style="margin-top: 10px; padding: 8px 16px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9em; width: 100%;">
                  Mượn sách
                </button>
              @endif
            @else
              <a href="{{ route('login') }}" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 6px; font-size: 0.9em; text-align: center; width: 100%;">
                Đăng nhập để mượn
              </a>
            @endauth
          </div>
        @endforeach
      </div>
    @else
      <div style="text-align: center; padding: 40px; color: #666;">
        <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin: 0 auto 20px;">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
        </svg>
        <p style="font-size: 1.2em;">Chưa có sách nào trong thư viện</p>
      </div>
    @endif
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-main">
        <!-- Left Section -->
        <div class="footer-left">
          <div class="footer-logo">
            <h2>WAKA</h2>
            <p class="company-name">Công ty cổ phần sách điện tử Waka</p>
          </div>
          <div class="footer-contact">
            <div class="contact-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke="currentColor" stroke-width="2"/>
              </svg>
              <span>0877736289</span>
            </div>
            <div class="contact-item">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="currentColor" stroke-width="2"/>
              </svg>
              <span>Support@waka.vn</span>
            </div>
          </div>
          <div class="footer-badge">
            <div class="badge-box">
              <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <circle cx="20" cy="20" r="18" fill="#0066cc"/>
                <path d="M20 8l2 6h6l-5 4 2 6-5-4-5 4 2-6-5-4h6l2-6z" fill="white"/>
              </svg>
              <div class="badge-text">
                <p class="badge-title">ĐÃ THÔNG BÁO</p>
                <p class="badge-subtitle">BỘ CÔNG THƯƠNG</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Middle Sections -->
        <div class="footer-links">
          <div class="footer-column">
            <h4>Về chúng tôi</h4>
            <ul>
              <li><a href="#">Giới thiệu</a></li>
              <li><a href="#">Cơ cấu tổ chức</a></li>
              <li><a href="#">Lĩnh vực hoạt động</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h4>Cơ hội đầu tư</h4>
            <ul>
              <li><a href="#">Tuyển dụng</a></li>
              <li><a href="#">Liên hệ</a></li>
              <li><a href="#">Dịch vụ xuất bản sách</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h4>Thông tin hữu ích</h4>
            <ul>
              <li><a href="#">Thỏa thuận sử dụng dịch vụ</a></li>
              <li><a href="#">Quyền lợi</a></li>
              <li><a href="#">Quy định riêng tư</a></li>
              <li><a href="#">Quy chế hoạt động sàn TMĐT</a></li>
              <li><a href="#">Câu hỏi thường gặp</a></li>
            </ul>
          </div>

          <div class="footer-column">
            <h4>Tin tức</h4>
            <ul>
              <li><a href="#">Tin dịch vụ</a></li>
              <li><a href="#">Review sách</a></li>
              <li><a href="#">Lịch phát hành</a></li>
            </ul>
          </div>
        </div>

        <!-- Right Section - App Download -->
        <div class="footer-right">
          <div class="qr-code">
            <svg width="120" height="120" viewBox="0 0 120 120">
              <rect width="120" height="120" fill="white" rx="8"/>
              <!-- Simple QR Code Pattern -->
              <rect x="10" y="10" width="30" height="30" fill="black"/>
              <rect x="15" y="15" width="20" height="20" fill="white"/>
              <rect x="20" y="20" width="10" height="10" fill="black"/>
              
              <rect x="80" y="10" width="30" height="30" fill="black"/>
              <rect x="85" y="15" width="20" height="20" fill="white"/>
              <rect x="90" y="20" width="10" height="10" fill="black"/>
              
              <rect x="10" y="80" width="30" height="30" fill="black"/>
              <rect x="15" y="85" width="20" height="20" fill="white"/>
              <rect x="20" y="90" width="10" height="10" fill="black"/>
              
              <rect x="50" y="50" width="20" height="20" fill="black"/>
              <rect x="55" y="30" width="10" height="10" fill="black"/>
              <rect x="30" y="55" width="10" height="10" fill="black"/>
              <rect x="75" y="55" width="15" height="15" fill="black"/>
              <rect x="55" y="75" width="15" height="15" fill="black"/>
            </svg>
          </div>
          <div class="app-buttons">
            <a href="#" class="app-store-btn">
              <svg width="120" height="40" viewBox="0 0 120 40">
                <rect width="120" height="40" rx="5" fill="#000"/>
                <text x="60" y="15" text-anchor="middle" fill="white" font-size="8">Download on the</text>
                <text x="60" y="28" text-anchor="middle" fill="white" font-size="14" font-weight="600">App Store</text>
              </svg>
            </a>
            <a href="#" class="google-play-btn">
              <svg width="120" height="40" viewBox="0 0 120 40">
                <rect width="120" height="40" rx="5" fill="#000"/>
                <text x="60" y="15" text-anchor="middle" fill="white" font-size="8">GET IT ON</text>
                <text x="60" y="28" text-anchor="middle" fill="white" font-size="14" font-weight="600">Google Play</text>
              </svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Footer Bottom Info -->
      <div class="footer-bottom">
        <div class="company-info">
          <p><strong>Công ty Cổ phần Sách điện tử Waka</strong> – Tầng 6, Tháp văn phòng quốc tế Hòa Bình, số 106 đường Hoàng Quốc Việt, Phường Nghĩa Đô, Thành phố Hà Nội, Việt Nam.</p>
          <p>ĐKKD số 0108796796 do SKHĐT TP Hà Nội cấp lần đầu ngày 24/06/2019.</p>
          <p>Giấy xác nhận Đăng ký hoạt động phát hành xuất bản phẩm điện tử số 8132/XN-CXBIPH do Cục Xuất bản, In và Phát hành cấp ngày 31/12/2019.</p>
          <p>Giấy chứng nhận Đăng ký kết nối đề cung cấp dịch vụ nội dung thông tin trên mạng viễn thông di động số 91/GCN-CVT cấp ngày 24/03/2025.</p>
          <p>Người đại diện: (Bà) Phùng Thị Như Quỳnh (Theo Giấy ủy quyền số 2402/GUQ-WAKA/2025 ngày 24/02/2025).</p>
          <p>Người đại diện được ủy quyền phối hợp với CQNN giải quyết các vấn đề liên quan đến bảo vệ quyền lợi Khách hàng: (Bà) Phùng Thị Như Quỳnh – Số điện thoại: 0877756263 – Email: Support@waka.vn. – Địa chỉ liên hệ: Tháp văn phòng quốc tế Hòa Bình, số 106 đường Hoàng Quốc Việt, Phường Nghĩa Đô, Thành phố Hà Nội, Việt Nam.</p>
          <p>Số VPĐD: 024.73086566 | Số CSKH: 1900545482 nhấn 5 | Hotline: 0877736289</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Book Detail Modal -->
  <div id="bookDetailModal" class="book-detail-modal">
    <div class="book-detail-overlay"></div>
    <div class="book-detail-container">
      <button class="book-detail-close" aria-label="Đóng">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
      
      <div class="book-detail-content">
        <div class="book-detail-left">
          <div class="book-detail-cover" id="modalBookCover">
            <!-- Book cover will be inserted here -->
          </div>
        </div>
        
        <div class="book-detail-right">
          <div class="book-detail-badge" id="modalBookBadge" style="display: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" fill="#FF6B35"/>
              <path d="M12 7l1.545 3.13L17 10.635l-2.5 2.435L15 17l-3-1.575L9 17l.5-3.93L7 10.635l3.455-.505L12 7z" fill="white"/>
            </svg>
            <span>HỘI VIÊN</span>
          </div>
          
          <h2 class="book-detail-title" id="modalBookTitle"></h2>
          <p class="book-detail-author" id="modalBookAuthor"></p>
          
          <div class="book-detail-info">
            <div class="book-detail-info-item">
              <span class="book-detail-label">Thể loại:</span>
              <span class="book-detail-value" id="modalBookGenre"></span>
            </div>
            <div class="book-detail-info-item">
              <span class="book-detail-label">Đánh giá:</span>
              <span class="book-detail-value">
                <span id="modalBookRating"></span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#ffdd00">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </span>
            </div>
            <div class="book-detail-info-item">
              <span class="book-detail-label">Năm xuất bản:</span>
              <span class="book-detail-value" id="modalBookYear"></span>
            </div>
          </div>
          
          <div class="book-detail-actions">
            <button class="book-detail-read-btn" onclick="window.location.href='/books/' + document.getElementById('modalBookId').value">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
              </svg>
              Đọc sách
            </button>
            <button class="book-detail-favorite-btn" aria-label="Yêu thích">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </button>
          </div>
          
          <div class="book-detail-description">
            <p id="modalBookDescription"></p>
          </div>
          
          <button class="book-detail-more-btn" onclick="window.location.href='/books/' + document.getElementById('modalBookId').value">Chi tiết</button>
          <input type="hidden" id="modalBookId" value="">
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('script.js') }}"></script>
  
  <!-- User Menu Toggle Script -->
  <script>
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuBtn && userDropdown) {
      userMenuBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
      });
      
      document.addEventListener('click', function(e) {
        if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
          userDropdown.classList.remove('show');
        }
      });
    }
  </script>
  
  <!-- Borrow Book Function -->
  <script>
    function borrowBook(bookId) {
      if (!bookId) {
        alert('Không tìm thấy thông tin sách');
        return;
      }
      
      // Get CSRF token
      const token = document.querySelector('meta[name="csrf-token"]');
      if (!token) {
        // Add CSRF token meta tag if not exists
        const metaTag = document.createElement('meta');
        metaTag.setAttribute('name', 'csrf-token');
        metaTag.setAttribute('content', '{{ csrf_token() }}');
        document.head.appendChild(metaTag);
      }
      
      const csrfToken = token ? token.getAttribute('content') : '{{ csrf_token() }}';
      
      // Show loading
      const button = event.target;
      const originalText = button.textContent;
      button.textContent = 'Đang xử lý...';
      button.disabled = true;
      
      // Send AJAX request
      fetch('{{ route("borrow.book") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          book_id: bookId,
          borrow_days: 14
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('✓ ' + data.message);
          // Reload page to update available copies
          window.location.reload();
        } else {
          alert('✗ ' + data.message);
          button.textContent = originalText;
          button.disabled = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi mượn sách. Vui lòng thử lại.');
        button.textContent = originalText;
        button.disabled = false;
      });
    }
  </script>
  
  <!-- Custom JavaScript -->
  <script src="{{ asset('script.js') }}"></script>

  <!-- Cart Count Update Script -->
  <script>
    // Load cart count on page load
    function updateCartCount() {
      fetch('{{ route("cart.count") }}', {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        const cartBadge = document.getElementById('cartCount');
        if (cartBadge) {
          cartBadge.textContent = data.count;
          if (data.count === 0) {
            cartBadge.style.display = 'none';
          } else {
            cartBadge.style.display = 'flex';
          }
        }
      })
      .catch(error => {
        console.error('Error loading cart count:', error);
      });
    }

    // Update cart count on page load
    document.addEventListener('DOMContentLoaded', updateCartCount);

    // Update cart count every 30 seconds (optional)
    setInterval(updateCartCount, 30000);
  </script>
</body>
</html>
