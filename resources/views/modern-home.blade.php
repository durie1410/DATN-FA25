@extends('layouts.app')

@section('title', 'Trang Chủ - Thư Viện Online')

@section('styles')
<style>
    /* Modern UI/UX Enhancements */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --shadow-soft: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        --shadow-hover: 0 15px 35px rgba(31, 38, 135, 0.2);
    }

    /* Glass morphism effect */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border-radius: 20px;
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }

    /* Animated gradient backgrounds */
    .gradient-bg {
        background: var(--primary-gradient);
        position: relative;
        overflow: hidden;
    }

    .gradient-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Floating elements */
    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    /* Pulse animation */
    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Slide in animations */
    .slide-in-left {
        animation: slideInLeft 0.8s ease-out;
    }

    .slide-in-right {
        animation: slideInRight 0.8s ease-out;
    }

    .slide-in-up {
        animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInLeft {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideInUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Staggered animations */
    .stagger-item {
        opacity: 0;
        transform: translateY(30px);
        animation: staggerIn 0.6s ease-out forwards;
    }

    .stagger-item:nth-child(1) { animation-delay: 0.1s; }
    .stagger-item:nth-child(2) { animation-delay: 0.2s; }
    .stagger-item:nth-child(3) { animation-delay: 0.3s; }
    .stagger-item:nth-child(4) { animation-delay: 0.4s; }
    .stagger-item:nth-child(5) { animation-delay: 0.5s; }
    .stagger-item:nth-child(6) { animation-delay: 0.6s; }

    @keyframes staggerIn {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Modern button styles */
    .btn-modern {
        position: relative;
        overflow: hidden;
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        background: var(--primary-gradient);
        color: white;
    }

    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-modern:hover::before {
        left: 100%;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    /* Card hover effects */
    .card-modern {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .card-modern:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .card-modern .card-img-top {
        transition: transform 0.3s ease;
    }

    .card-modern:hover .card-img-top {
        transform: scale(1.1);
    }

    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Progress bars */
    .progress-modern {
        height: 8px;
        border-radius: 10px;
        background: rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .progress-modern .progress-bar {
        background: var(--primary-gradient);
        border-radius: 10px;
        transition: width 0.8s ease;
    }

    /* Interactive elements */
    .interactive {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .interactive:hover {
        transform: scale(1.05);
    }

    .interactive:active {
        transform: scale(0.95);
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .glass-card {
            border-radius: 15px;
        }
        
        .btn-modern {
            padding: 10px 25px;
            font-size: 0.9rem;
        }
        
        .card-modern {
            border-radius: 15px;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        :root {
            --glass-bg: rgba(0, 0, 0, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
        }
        
        .card-modern {
            background: #1a1a1a;
            color: white;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section with Modern Design -->
<div class="gradient-bg text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 slide-in-left">
                <h1 class="display-3 fw-bold mb-4 floating">
                    📚 Thư Viện Online
                </h1>
                <p class="lead mb-4 fs-4">
                    Khám phá thế giới tri thức với hàng nghìn cuốn sách hay
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <span class="badge bg-white text-dark fs-6 px-3 py-2 pulse">
                        📖 {{ $stats['total_books'] ?? 0 }} Sách
                    </span>
                    <span class="badge bg-white text-dark fs-6 px-3 py-2 pulse">
                        👥 {{ $stats['total_readers'] ?? 0 }} Độc giả
                    </span>
                    <span class="badge bg-white text-dark fs-6 px-3 py-2 pulse">
                        📚 {{ $stats['total_categories'] ?? 0 }} Thể loại
                    </span>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('books.public') }}" class="btn-modern btn-lg">
                        <i class="fas fa-search me-2"></i>Khám phá sách
                    </a>
                    @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i>Giỏ hàng
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4 text-center slide-in-right">
                <div class="floating">
                    <i class="fas fa-book-open" style="font-size: 12rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Search Section with Glass Effect -->
    <div class="glass-card p-4 mb-5 slide-in-up">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-3">
                    <i class="fas fa-search me-2"></i>Tìm kiếm sách
                </h3>
                <form action="{{ route('books.public') }}" method="GET" id="searchForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                       class="form-control border-start-0" placeholder="Tìm theo tên sách hoặc tác giả...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-tags text-primary"></i>
                                </span>
                                <select name="category_id" class="form-select border-start-0">
                                    <option value="">-- Tất cả thể loại --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->ten_the_loai }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-modern w-100">
                                <i class="fas fa-search me-1"></i>Tìm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Actions with Staggered Animation -->
    <div class="row mb-5">
        <div class="col-6 col-md-3 mb-4">
            <a href="{{ route('books.public') }}" class="card-modern text-decoration-none h-100 stagger-item interactive">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-book text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Xem sách</h5>
                    <p class="text-muted small">Khám phá thư viện sách</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <a href="{{ route('categories.index') }}" class="card-modern text-decoration-none h-100 stagger-item interactive">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-list text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Thể loại</h5>
                    <p class="text-muted small">Duyệt theo danh mục</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-4">
            <a href="{{ route('cart.index') }}" class="card-modern text-decoration-none h-100 stagger-item interactive">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shopping-cart text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Giỏ hàng</h5>
                    <p class="text-muted small">Sách đã chọn</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-4">
            @auth
            <a href="{{ route('profile') }}" class="card-modern text-decoration-none h-100 stagger-item interactive">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-user text-info" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Tài khoản</h5>
                    <p class="text-muted small">Quản lý cá nhân</p>
                </div>
            </a>
            @else
            <a href="{{ route('login') }}" class="card-modern text-decoration-none h-100 stagger-item interactive">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-sign-in-alt text-info" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Đăng nhập</h5>
                    <p class="text-muted small">Truy cập hệ thống</p>
                </div>
            </a>
            @endauth
        </div>
    </div>

    <!-- Featured Books with Modern Cards -->
    <div class="card-modern mb-5">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>Sách nổi bật
                </h3>
                <a href="{{ route('books.public') }}" class="btn-modern btn-sm">
                    Xem tất cả
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row" id="featuredBooks">
                @forelse($featuredBooks as $index => $book)
                <div class="col-6 col-md-3 mb-4">
                    <div class="card-modern h-100 stagger-item" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="height: 200px;">
                            @if($book->hinh_anh)
                                <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}" class="img-fluid" style="max-height: 190px;">
                            @else
                                <i class="fas fa-book text-muted" style="font-size: 4rem;"></i>
                            @endif
                            
                            <!-- Book Status Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($book->so_luong > 0)
                                    <span class="badge bg-success pulse">Có sẵn</span>
                                @else
                                    <span class="badge bg-danger">Hết sách</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title text-truncate" title="{{ $book->ten_sach }}">
                                {{ $book->ten_sach }}
                            </h6>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-user me-1"></i>{{ $book->tac_gia }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $book->category->ten_the_loai ?? 'N/A' }}</small>
                                <a href="{{ route('books.show', $book->id) }}" class="btn-modern btn-sm">
                                    Xem
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-book text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">Chưa có sách nào</h4>
                    <p class="text-muted">Hãy thêm sách vào hệ thống</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Categories with Progress Bars -->
    <div class="card-modern mb-5">
        <div class="card-header bg-transparent border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-tags me-2 text-primary"></i>Thể loại sách
                </h3>
                <a href="{{ route('categories.index') }}" class="btn-modern btn-sm">
                    Xem tất cả
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row">
                @forelse($categories as $index => $category)
                <div class="col-6 col-md-3 mb-4">
                    <a href="{{ route('books.public', ['category_id' => $category->id]) }}" class="card-modern text-decoration-none h-100 stagger-item interactive" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-folder text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="card-title">{{ $category->ten_the_loai }}</h6>
                            <div class="progress-modern mb-2">
                                <div class="progress-bar" style="width: {{ min(($category->books_count ?? 0) * 10, 100) }}%"></div>
                            </div>
                            <small class="text-muted">{{ $category->books_count ?? 0 }} sách</small>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-tags text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">Chưa có thể loại nào</h4>
                    <p class="text-muted">Hãy thêm thể loại vào hệ thống</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="d-md-none">
    <button class="btn-modern rounded-circle position-fixed" style="bottom: 80px; right: 20px; width: 60px; height: 60px; z-index: 1000;" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>
@endsection

@section('scripts')
<script>
    // Enhanced mobile functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll to top
        window.scrollToTop = function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        // Observe all stagger items
        document.querySelectorAll('.stagger-item').forEach(item => {
            item.style.animationPlayState = 'paused';
            observer.observe(item);
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.gradient-bg');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-modern').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });
</script>

<style>
    /* Ripple effect */
    .btn-modern {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>
@endsection
















