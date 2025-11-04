@extends('layouts.frontend')

@section('title', 'Thư Viện Online 4.0 - Khám Phá Tri Thức Tương Lai')

@section('content')
<!-- Hero Section với Glass Morphism -->
<div class="hero-modern">
    <div class="hero-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-rocket"></i>
                        <span>Thư Viện 4.0</span>
                    </div>
                    <h1 class="hero-title">
                        <span class="text-gradient">Khám Phá</span><br>
                        Thế Giới Tri Thức<br>
                        <span class="highlight-text">Tương Lai</span>
                    </h1>
                    <p class="hero-description">
                        Trải nghiệm đọc sách thông minh với AI, tìm kiếm thông minh và giao diện hiện đại. 
                        Hàng nghìn cuốn sách đang chờ bạn khám phá.
                    </p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number" data-count="{{ $stats['total_books'] }}">0</div>
                            <div class="stat-label">Sách</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-count="{{ $stats['total_categories'] }}">0</div>
                            <div class="stat-label">Thể loại</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-count="{{ $stats['new_books'] }}">0</div>
                            <div class="stat-label">Mới</div>
                        </div>
                    </div>
                    <div class="hero-actions">
                        <button class="btn-primary-modern" onclick="scrollToSearch()">
                            <i class="fas fa-search"></i>
                            <span>Tìm kiếm thông minh</span>
                        </button>
                        <button class="btn-secondary-modern" onclick="showAIFeatures()">
                            <i class="fas fa-robot"></i>
                            <span>Tính năng AI</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="floating-books">
                        <div class="book-card-float book-1">
                            <div class="book-cover">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                        <div class="book-card-float book-2">
                            <div class="book-cover">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                        <div class="book-card-float book-3">
                            <div class="book-cover">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    <div class="ai-elements">
                        <div class="ai-chip chip-1">
                            <i class="fas fa-brain"></i>
                            <span>AI Search</span>
                        </div>
                        <div class="ai-chip chip-2">
                            <i class="fas fa-lightbulb"></i>
                            <span>Smart Recommend</span>
                        </div>
                        <div class="ai-chip chip-3">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI-Powered Search Section - Enhanced -->
<div class="search-modern" id="searchSection">
    <div class="container">
        <h2 class="search-title">
            <i class="fas fa-search-plus"></i>
            Tìm kiếm thông minh với AI
        </h2>
        <p class="search-subtitle">
            Sử dụng trí tuệ nhân tạo để tìm kiếm sách chính xác và nhanh chóng
        </p>
        
        <form action="{{ route('books.public') }}" method="GET" class="search-form">
            <div class="search-input-group">
                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                       class="search-input" placeholder="Nhập tên sách, tác giả hoặc chủ đề...">
                <i class="fas fa-search search-icon"></i>
                <i class="fas fa-microphone voice-icon" onclick="startVoiceSearch()"></i>
            </div>
            
            <select name="category_id" class="category-dropdown">
                <option value="">Tất cả thể loại</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->ten_the_loai }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
                <span>Tìm kiếm</span>
            </button>
        </form>
        
        <div class="category-tags">
            <span class="category-tag" onclick="searchByTag('Khoa học')">Khoa học</span>
            <span class="category-tag" onclick="searchByTag('Lịch sử')">Lịch sử</span>
            <span class="category-tag" onclick="searchByTag('Văn học')">Văn học</span>
            <span class="category-tag" onclick="searchByTag('Kinh tế')">Kinh tế</span>
            <span class="category-tag" onclick="searchByTag('Công nghệ')">Công nghệ</span>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Tính năng nổi bật
            </h2>
            <p class="section-subtitle">
                Trải nghiệm thư viện thông minh với các tính năng hiện đại
            </p>
        </div>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 class="feature-title">AI Tìm kiếm</h3>
                <p class="feature-description">
                    Sử dụng trí tuệ nhân tạo để tìm kiếm sách chính xác và gợi ý phù hợp với sở thích của bạn.
                </p>
                <div class="feature-badge">Mới</div>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">Đọc đa nền tảng</h3>
                <p class="feature-description">
                    Đọc sách trên mọi thiết bị với giao diện responsive và đồng bộ hóa tiến độ đọc.
                </p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Thống kê thông minh</h3>
                <p class="feature-description">
                    Theo dõi thói quen đọc sách và nhận báo cáo chi tiết về tiến độ học tập của bạn.
                </p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">Cộng đồng</h3>
                <p class="feature-description">
                    Tham gia thảo luận, chia sẻ đánh giá và kết nối với những người có cùng sở thích.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Books Showcase -->
<div class="books-showcase">
    <div class="container">
        <h2 class="books-showcase-title">
            <i class="fas fa-book-open"></i>
            Sách nổi bật
        </h2>
        <p class="books-showcase-subtitle">
            Khám phá những cuốn sách hay nhất được độc giả yêu thích
        </p>
        <div class="books-grid">
            @forelse($books->take(6) as $index => $book)
            <div class="book-card-modern" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="book-image-container">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                    @else
                        <div class="book-placeholder" style="height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); color: #6c757d; font-size: 3rem;">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                    <div class="book-overlay">
                        <button class="quick-view-btn" onclick="quickViewBook({{ $book->id }})">
                            <i class="fas fa-eye"></i>
                            <span>Xem nhanh</span>
                        </button>
                        @auth
                            @if($currentReader)
                                @if($book->user_borrowed)
                                    <button class="borrow-btn borrowed" disabled>
                                        <i class="fas fa-check"></i>
                                        <span>Đã mượn</span>
                                    </button>
                                @elseif($book->available_copies > 0)
                                    <button class="borrow-btn available" onclick="borrowBook({{ $book->id }})">
                                        <i class="fas fa-book"></i>
                                        <span>Gửi yêu cầu mượn</span>
                                    </button>
                                @else
                                    <button class="borrow-btn unavailable" disabled>
                                        <i class="fas fa-times"></i>
                                        <span>Hết sách</span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="borrow-btn login-required">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Đăng nhập</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="borrow-btn login-required">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Đăng nhập</span>
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="book-info-modern">
                    <h3 class="book-title-modern">{{ $book->ten_sach }}</h3>
                    <p class="book-author-modern">
                        <i class="fas fa-user"></i>
                        {{ $book->tac_gia }}
                    </p>
                    <div class="book-meta">
                        <span class="book-category">
                            <i class="fas fa-tag"></i>
                            {{ $book->category->ten_the_loai ?? 'Chưa phân loại' }}
                        </span>
                        <span class="book-availability">
                            <i class="fas fa-book"></i>
                            {{ $book->available_copies }} có sẵn
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Chưa có sách nào</h3>
                <p>Hãy thêm sách vào hệ thống để bắt đầu</p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center">
            <button class="view-all-btn" onclick="showAllBooks()">
                <span>Xem tất cả sách</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="categories-section">
    <div class="container">
        <h2 class="categories-title">
            <i class="fas fa-th-large"></i>
            Khám phá theo thể loại
        </h2>
        <p class="categories-subtitle">
            Tìm hiểu các thể loại sách đa dạng và phong phú trong thư viện
        </p>
        <div class="categories-grid">
            @forelse($categories as $index => $category)
            <div class="category-card-modern" data-aos="zoom-in" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="category-icon-modern">
                    <i class="fas fa-folder"></i>
                </div>
                <h3 class="category-name-modern">{{ $category->ten_the_loai }}</h3>
                <p class="category-description">
                    Khám phá {{ $category->books_count ?? 0 }} cuốn sách thuộc thể loại này
                </p>
                <div class="category-stats-modern">
                    <span class="book-count-modern">{{ $category->books_count ?? 0 }} sách</span>
                    <button class="explore-category-btn" onclick="exploreCategory({{ $category->id }})">
                        <span>Khám phá</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state-modern">
                <div class="empty-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>Chưa có thể loại nào</h3>
                <p>Hãy thêm thể loại vào hệ thống</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- AI Features Modal -->
<div class="modal fade" id="aiFeaturesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-robot"></i>
                    Tính năng AI
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="ai-features-grid">
                    <div class="ai-feature">
                        <div class="ai-feature-icon">
                            <i class="fas fa-search-plus"></i>
                        </div>
                        <h4>Tìm kiếm thông minh</h4>
                        <p>Sử dụng AI để tìm kiếm sách chính xác dựa trên ngữ cảnh và ý định của bạn.</p>
                    </div>
                    <div class="ai-feature">
                        <div class="ai-feature-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4>Gợi ý cá nhân hóa</h4>
                        <p>AI phân tích sở thích đọc của bạn để đưa ra gợi ý sách phù hợp.</p>
                    </div>
                    <div class="ai-feature">
                        <div class="ai-feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Phân tích thói quen</h4>
                        <p>Theo dõi và phân tích thói quen đọc sách để cải thiện trải nghiệm.</p>
                    </div>
                    <div class="ai-feature">
                        <div class="ai-feature-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4>Chatbot hỗ trợ</h4>
                        <p>Trợ lý AI 24/7 để hỗ trợ bạn tìm sách và giải đáp thắc mắc.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye"></i>
                    Xem nhanh
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-actions">
    <button class="fab-main" onclick="scrollToTop()" data-tooltip="Lên đầu trang">
        <i class="fas fa-arrow-up"></i>
    </button>
    <button class="fab-secondary" onclick="toggleDarkMode()" data-tooltip="Chế độ tối">
        <i class="fas fa-moon"></i>
    </button>
    <button class="fab-secondary" onclick="showAIFeatures()" data-tooltip="AI Assistant">
        <i class="fas fa-robot"></i>
    </button>
</div>

<!-- Borrow Request Modal -->
<div class="modal fade" id="borrowRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-book-reader"></i>
                    Gửi yêu cầu mượn sách
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="borrowRequestForm">
                    <input type="hidden" name="borrow_book_id" id="borrow_book_id">
                    <div class="mb-3">
                        <label for="borrow_days" class="form-label">Số ngày mượn</label>
                        <input type="number" class="form-control" id="borrow_days" name="borrow_days" min="1" max="30" value="14" required>
                    </div>
                    <div class="mb-3">
                        <label for="borrow_note" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea class="form-control" id="borrow_note" name="note" rows="2" maxlength="1000" placeholder="Ghi chú cho thư viện hoặc thủ thư..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Gửi yêu cầu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
/* Modern Homepage Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow-soft: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    --shadow-hover: 0 15px 35px rgba(31, 38, 135, 0.2);
    --text-gradient: linear-gradient(135deg, #667eea, #764ba2);
}

/* Hero Section */
.hero-modern {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(40px);
    animation: float 6s ease-in-out infinite;
}

.orb-1 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.orb-2 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03));
    top: 60%;
    right: 20%;
    animation-delay: 2s;
}

.orb-3 {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02));
    bottom: 20%;
    left: 30%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.hero-content {
    position: relative;
    z-index: 2;
    color: white;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 8px 16px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 24px;
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 24px;
}

.text-gradient {
    background: linear-gradient(135deg, #fff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.highlight-text {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 32px;
    line-height: 1.6;
}

.hero-stats {
    display: flex;
    gap: 32px;
    margin-bottom: 40px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #ffd700;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.btn-primary-modern, .btn-secondary-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-primary-modern {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
}

.btn-primary-modern:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.btn-secondary-modern {
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.5);
    color: white;
}

.btn-secondary-modern:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: white;
    transform: translateY(-2px);
}

/* Hero Visual */
.hero-visual {
    position: relative;
    z-index: 2;
    height: 500px;
}

.floating-books {
    position: relative;
    width: 100%;
    height: 100%;
}

.book-card-float {
    position: absolute;
    width: 80px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: floatBook 4s ease-in-out infinite;
}

.book-1 {
    top: 20%;
    left: 20%;
    animation-delay: 0s;
}

.book-2 {
    top: 50%;
    right: 30%;
    animation-delay: 1s;
}

.book-3 {
    bottom: 20%;
    left: 50%;
    animation-delay: 2s;
}

@keyframes floatBook {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(5deg); }
}

.book-cover {
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.8);
}

.ai-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.ai-chip {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    display: flex;
    align-items: center;
    gap: 6px;
    animation: pulse 2s infinite;
}

.chip-1 {
    top: 10%;
    right: 10%;
}

.chip-2 {
    top: 60%;
    left: 10%;
}

.chip-3 {
    bottom: 10%;
    right: 20%;
}

@keyframes pulse {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 1; }
}

/* Search Section */
.search-modern-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.search-container {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    padding: 48px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.search-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 16px;
    background: var(--text-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 0;
}

.search-form-modern {
    max-width: 800px;
    margin: 0 auto;
}

.search-input-group {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
}

.search-input-wrapper {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 20px;
    color: #6c757d;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 20px 60px 20px 50px;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    font-size: 1.1rem;
    background: white;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.voice-search-btn {
    position: absolute;
    right: 20px;
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.voice-search-btn:hover {
    background: #f8f9fa;
    color: #667eea;
}

.search-filters {
    display: flex;
    gap: 12px;
    align-items: center;
}

.filter-select {
    padding: 20px 24px;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    background: white;
    font-size: 1rem;
    min-width: 200px;
}

.search-submit-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 20px 32px;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.search-suggestions {
    text-align: center;
}

.suggestion-tags {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.suggestion-tag {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.suggestion-tag:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

/* Features Section */
.features-section {
    padding: 80px 0;
    background: white;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
    margin-top: 48px;
}

.feature-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    opacity: 0;
    transition: all 0.3s ease;
}

.feature-card:hover::before {
    opacity: 1;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 2rem;
    color: white;
}

.feature-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 16px;
    color: #2d3748;
}

.feature-description {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 0;
}

.feature-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: var(--success-gradient);
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Books Showcase */
.books-showcase {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 48px;
}

.section-actions {
    display: flex;
    gap: 16px;
}

.view-all-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
}

.book-card-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
}

.book-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.book-image-modern {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.book-image-modern img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.book-card-modern:hover .book-image-modern img {
    transform: scale(1.1);
}

.book-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #6c757d;
}

.book-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    opacity: 0;
    transition: all 0.3s ease;
}

.book-card-modern:hover .book-overlay {
    opacity: 1;
}

.quick-view-btn, .borrow-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-view-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
}

.borrow-btn.available {
    background: var(--success-gradient);
    color: white;
}

.borrow-btn.borrowed {
    background: #6c757d;
    color: white;
    cursor: not-allowed;
}

.borrow-btn.unavailable {
    background: #dc3545;
    color: white;
    cursor: not-allowed;
}

.book-info-modern {
    padding: 24px;
}

.book-title-modern {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
    line-height: 1.4;
}

.book-author-modern {
    color: #6c757d;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.book-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: #6c757d;
}

.book-category, .book-availability {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Categories Section */
.categories-section {
    padding: 80px 0;
    background: white;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.category-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 1.5rem;
    color: white;
}

.category-name {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #2d3748;
}

.category-stats {
    margin-bottom: 20px;
}

.book-count {
    color: #6c757d;
    font-size: 0.9rem;
}

.explore-category-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0 auto;
}

.explore-category-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Empty State */
.empty-state-modern {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 24px;
}

.empty-state-modern h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
}

.empty-state-modern p {
    color: #6c757d;
    font-size: 1.1rem;
}

/* Modal Styles */
.modern-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modern-modal .modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 20px 20px 0 0;
    padding: 24px 32px;
}

.modern-modal .modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modern-modal .btn-close {
    filter: invert(1);
}

.modern-modal .modal-body {
    padding: 32px;
}

.ai-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.ai-feature {
    text-align: center;
    padding: 24px;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 16px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.ai-feature-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 1.5rem;
    color: white;
}

.ai-feature h4 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
}

.ai-feature p {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Floating Actions */
/* Enhanced Floating Action Buttons */
.floating-actions {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.fab-main, .fab-secondary {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(20px);
}

.fab-main {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.fab-secondary {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    color: #667eea;
    border: 2px solid rgba(102, 126, 234, 0.2);
}

.fab-main:hover, .fab-secondary:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
}

.fab-main:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.fab-secondary:hover {
    background: rgba(255, 255, 255, 1);
    color: #5a6fd8;
    border-color: rgba(90, 111, 216, 0.3);
}

/* Ripple Effect */
.fab-main::before, .fab-secondary::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.fab-main:active::before, .fab-secondary:active::before {
    width: 300px;
    height: 300px;
}

/* Tooltip */
.fab-main::after, .fab-secondary::after {
    content: attr(data-tooltip);
    position: absolute;
    right: 80px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.fab-main:hover::after, .fab-secondary:hover::after {
    opacity: 1;
}

/* Dark Mode Specific Styles */
.dark-mode .fab-secondary {
    background: rgba(45, 55, 72, 0.9);
    color: #e2e8f0;
    border-color: rgba(226, 232, 240, 0.2);
}

.dark-mode .fab-secondary:hover {
    background: rgba(45, 55, 72, 1);
    color: #f7fafc;
    border-color: rgba(247, 250, 252, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .floating-actions {
        bottom: 20px;
        right: 20px;
        gap: 12px;
    }
    
    .fab-main, .fab-secondary {
        width: 56px;
        height: 56px;
        font-size: 1.2rem;
    }
    
    .fab-main::after, .fab-secondary::after {
        display: none;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        gap: 16px;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .hero-actions {
        flex-direction: column;
    }
    
    .btn-primary-modern, .btn-secondary-modern {
        width: 100%;
        justify-content: center;
    }
    
    .search-input-group {
        flex-direction: column;
    }
    
    .search-filters {
        flex-direction: column;
        width: 100%;
    }
    
    .filter-select {
        width: 100%;
    }
    
    .search-submit-btn {
        width: 100%;
        justify-content: center;
    }
    
    .section-header {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .books-grid {
        grid-template-columns: 1fr;
    }
    
    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .floating-actions {
        bottom: 20px;
        right: 20px;
    }
    
    .fab-main, .fab-secondary {
        width: 48px;
        height: 48px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .search-container {
        padding: 24px;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .feature-card, .category-card {
        padding: 24px;
    }
    
    .book-info-modern {
        padding: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    // Counter animation
    animateCounters();
    
    // Initialize other features
    initializeModernFeatures();
});

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

function initializeModernFeatures() {
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add parallax effect to hero
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-modern');
        if (hero) {
            hero.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
}

function scrollToSearch() {
    document.getElementById('searchSection').scrollIntoView({
        behavior: 'smooth'
    });
}

function showAIFeatures() {
    const modal = new bootstrap.Modal(document.getElementById('aiFeaturesModal'));
    modal.show();
}

function startVoiceSearch() {
    // Voice search implementation
    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'vi-VN';
        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            document.querySelector('.search-input').value = transcript;
        };
        recognition.start();
    } else {
        alert('Trình duyệt không hỗ trợ nhận dạng giọng nói');
    }
}

function searchByTag(tag) {
    document.querySelector('.search-input').value = tag;
    document.querySelector('.search-form-modern').submit();
}

function quickViewBook(bookId) {
    // Load book details and show modal
    fetch(`/api/books/${bookId}`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="row">
                    <div class="col-md-4">
                        <img src="${data.image || '/images/book-placeholder.jpg'}" 
                             alt="${data.title}" class="img-fluid rounded">
                    </div>
                    <div class="col-md-8">
                        <h4>${data.title}</h4>
                        <p><strong>Tác giả:</strong> ${data.author}</p>
                        <p><strong>Thể loại:</strong> ${data.category}</p>
                        <p><strong>Mô tả:</strong> ${data.description}</p>
                    </div>
                </div>
            `;
            document.getElementById('quickViewContent').innerHTML = content;
            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading book details:', error);
        });
}

function borrowBook(bookId) {
    document.getElementById('borrow_book_id').value = bookId;
    document.getElementById('borrowRequestForm').reset();
    var modal = new bootstrap.Modal(document.getElementById('borrowRequestModal'));
    modal.show();
}

function exploreCategory(categoryId) {
    window.location.href = `/books?category_id=${categoryId}`;
}

function showAllBooks() {
    window.location.href = '/books';
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Load dark mode preference
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}
</script>

<style>
/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    padding: 16px 20px;
    transform: translateX(400px);
    transition: all 0.3s ease;
    border-left: 4px solid #28a745;
}

.notification.show {
    transform: translateX(0);
}

.notification-error {
    border-left-color: #dc3545;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
}

.notification-success .notification-content i {
    color: #28a745;
}

.notification-error .notification-content i {
    color: #dc3545;
}

/* Dark Mode Styles */
.dark-mode {
    --glass-bg: rgba(0, 0, 0, 0.2);
    --glass-border: rgba(255, 255, 255, 0.1);
}

.dark-mode .search-modern-section,
.dark-mode .books-showcase {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
}

.dark-mode .features-section,
.dark-mode .categories-section {
    background: #1a1a1a;
}

.dark-mode .search-container,
.dark-mode .feature-card,
.dark-mode .category-card,
.dark-mode .book-card-modern {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
}

.dark-mode .section-title,
.dark-mode .feature-title,
.dark-mode .category-name,
.dark-mode .book-title-modern {
    color: white;
}

.dark-mode .section-subtitle,
.dark-mode .feature-description,
.dark-mode .book-author-modern,
.dark-mode .book-meta {
    color: #b0b0b0;
}

.dark-mode .search-input,
.dark-mode .filter-select {
    background: #2d2d2d;
    border-color: #444;
    color: white;
}

.dark-mode .notification {
    background: #2d2d2d;
    color: white;
}
</style>
@endpush
