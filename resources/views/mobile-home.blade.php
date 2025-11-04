@extends('layouts.app')

@section('title', 'Trang Chủ - Thư Viện Online')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white py-5 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold mb-3">📚 Thư Viện Online</h1>
                <p class="lead mb-4">Khám phá thế giới tri thức với hàng nghìn cuốn sách hay</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark fs-6">📖 {{ $stats['total_books'] ?? 0 }} Sách</span>
                    <span class="badge bg-light text-dark fs-6">👥 {{ $stats['total_readers'] ?? 0 }} Độc giả</span>
                    <span class="badge bg-light text-dark fs-6">📚 {{ $stats['total_categories'] ?? 0 }} Thể loại</span>
                </div>
            </div>
            <div class="col-md-4 text-center d-none d-md-block">
                <i class="fas fa-book-open" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Search Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Tìm kiếm sách</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('books.public') }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" 
                                   class="form-control" placeholder="Tìm theo tên sách hoặc tác giả...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-tags"></i>
                            </span>
                            <select name="category_id" class="form-select">
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Tìm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('books.public') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-book text-primary mb-2" style="font-size: 2rem;"></i>
                    <h6 class="card-title">Xem sách</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('categories.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-list text-success mb-2" style="font-size: 2rem;"></i>
                    <h6 class="card-title">Thể loại</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('cart.index') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart text-warning mb-2" style="font-size: 2rem;"></i>
                    <h6 class="card-title">Giỏ hàng</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3 mb-3">
            @auth
            <a href="{{ route('profile') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user text-info mb-2" style="font-size: 2rem;"></i>
                    <h6 class="card-title">Tài khoản</h6>
                </div>
            </a>
            @else
            <a href="{{ route('login') }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-sign-in-alt text-info mb-2" style="font-size: 2rem;"></i>
                    <h6 class="card-title">Đăng nhập</h6>
                </div>
            </a>
            @endauth
        </div>
    </div>

    <!-- Featured Books -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Sách nổi bật</h5>
            <a href="{{ route('books.public') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="card-body">
            <div class="row" id="featuredBooks">
                @forelse($featuredBooks as $book)
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100 book-card">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                            @if($book->hinh_anh)
                                <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}" class="img-fluid" style="max-height: 140px;">
                            @else
                                <i class="fas fa-book text-muted" style="font-size: 3rem;"></i>
                            @endif
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title text-truncate" title="{{ $book->ten_sach }}">{{ $book->ten_sach }}</h6>
                            <p class="card-text text-muted small">{{ $book->tac_gia }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $book->category->ten_the_loai ?? 'N/A' }}</small>
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <i class="fas fa-book text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">Chưa có sách nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Thể loại sách</h5>
            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($categories as $category)
                <div class="col-6 col-md-3 mb-3">
                    <a href="{{ route('books.public', ['category_id' => $category->id]) }}" class="card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-folder text-primary mb-2" style="font-size: 2rem;"></i>
                            <h6 class="card-title">{{ $category->ten_the_loai }}</h6>
                            <small class="text-muted">{{ $category->books_count ?? 0 }} sách</small>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <i class="fas fa-tags text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">Chưa có thể loại nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    @auth
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Hoạt động gần đây</h5>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                @forelse($recentActivity as $activity)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">{{ $activity->title }}</h6>
                        <small class="text-muted">{{ $activity->description }}</small>
                    </div>
                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-history text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">Chưa có hoạt động nào</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endauth
</div>

<!-- Floating Action Button for Mobile -->
<div class="d-md-none">
    <button class="btn btn-primary rounded-circle position-fixed" style="bottom: 80px; right: 20px; width: 60px; height: 60px; z-index: 1000;" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>
@endsection

@section('scripts')
<script>
    // Mobile-specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll to top
        window.scrollToTop = function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };

        // Add pull-to-refresh functionality for mobile
        let startY = 0;
        let currentY = 0;
        let isRefreshing = false;

        document.addEventListener('touchstart', function(e) {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
            }
        });

        document.addEventListener('touchmove', function(e) {
            if (window.scrollY === 0 && !isRefreshing) {
                currentY = e.touches[0].clientY;
                if (currentY - startY > 100) {
                    isRefreshing = true;
                    MobileUtils.showLoading();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }
        });

        // Auto-hide mobile nav on scroll
        let lastScrollTop = 0;
        const mobileNav = document.querySelector('.mobile-nav');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scrolling down
                mobileNav.style.transform = 'translateY(100%)';
            } else {
                // Scrolling up
                mobileNav.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });

        // Add transition to mobile nav
        mobileNav.style.transition = 'transform 0.3s ease';
    });
</script>
@endsection
















