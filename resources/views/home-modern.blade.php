@extends('layouts.frontend')

@section('title', 'Thư Viện Online - Đọc Sách & Mua Sách Online')

@section('content')
<!-- Hero Section -->
<div class="hero-ultra-modern">
    <div class="hero-background-ultra">
        <div class="gradient-orb-ultra orb-1"></div>
        <div class="gradient-orb-ultra orb-2"></div>
        <div class="gradient-orb-ultra orb-3"></div>
        <div class="gradient-orb-ultra orb-4"></div>
    </div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content-ultra">
                    <div class="hero-badge-ultra">
                        <i class="fas fa-sparkles"></i>
                        <span>Thư Viện 4.0</span>
                    </div>
                    <h1 class="hero-title-ultra">
                        <span class="gradient-text">Khám Phá</span><br>
                        Thế Giới Tri Thức<br>
                        <span class="highlight-text">Không Giới Hạn</span>
                    </h1>
                    <p class="hero-description-ultra">
                        Mượn sách miễn phí hoặc mua sách điện tử với giá ưu đãi. 
                        Hàng nghìn đầu sách đang chờ bạn khám phá.
                    </p>
                    
                    <div class="hero-stats-ultra">
                        <div class="stat-card-ultra">
                            <div class="stat-number-ultra" data-count="{{ $totalBooks }}">0</div>
                            <div class="stat-label-ultra">Sách có sẵn</div>
                        </div>
                        <div class="stat-card-ultra">
                            <div class="stat-number-ultra" data-count="{{ $totalCategories }}">0</div>
                            <div class="stat-label-ultra">Thể loại</div>
                        </div>
                        <div class="stat-card-ultra">
                            <div class="stat-number-ultra" data-count="{{ $newBooks }}">0</div>
                            <div class="stat-label-ultra">Mới nhất</div>
                        </div>
                    </div>
                    
                    <div class="hero-actions-ultra">
                        <a href="#featured-books" class="btn-primary-ultra">
                            <i class="fas fa-book-reader"></i>
                            <span>Mượn sách ngay</span>
                        </a>
                        <a href="#purchasable-books" class="btn-secondary-ultra">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Mua sách</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-visual-ultra">
                    <div class="floating-elements-ultra">
                        <div class="floating-book book-1">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="floating-book book-2">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="floating-book book-3">
                            <i class="fas fa-bookmark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Books - Sách Mượn Nổi Bật -->
<section id="featured-books" class="books-section-ultra">
    <div class="container">
        <div class="section-header-ultra">
            <div>
                <div class="section-badge-ultra">
                    <i class="fas fa-book-reader"></i>
                    <span>Mượn sách miễn phí</span>
                </div>
                <h2 class="section-title-ultra">Sách Mượn Nổi Bật</h2>
                <p class="section-subtitle-ultra">Mượn miễn phí - Đọc không giới hạn</p>
            </div>
            <a href="{{ route('books.public') }}" class="view-all-btn-ultra">
                <span>Xem tất cả</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="books-grid-ultra">
            @forelse($featuredBooks as $index => $book)
            <div class="book-card-ultra" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="book-image-ultra">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                    @else
                        <div class="book-placeholder-ultra">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                    
                    <div class="book-overlay-ultra">
                        <div class="book-actions-ultra">
                            <button class="action-btn-ultra" onclick="quickView({{ $book->id }}, 'borrow')" title="Xem nhanh">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('books.show', $book->id) }}" class="action-btn-ultra" title="Chi tiết">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                        
                        @auth
                            @if($currentReader)
                                @if($book->user_borrowed)
                                    <button class="borrow-btn-ultra borrowed" disabled>
                                        <i class="fas fa-check-circle"></i>
                                        <span>Đã mượn</span>
                                    </button>
                                @elseif($book->available_copies > 0)
                                    <button class="borrow-btn-ultra available" onclick="borrowBook({{ $book->id }})">
                                        <i class="fas fa-book-reader"></i>
                                        <span>Gửi yêu cầu mượn</span>
                                    </button>
                                @else
                                    <button class="borrow-btn-ultra unavailable" disabled>
                                        <i class="fas fa-times-circle"></i>
                                        <span>Hết sách</span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="borrow-btn-ultra login-required">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Đăng nhập</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="borrow-btn-ultra login-required">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Đăng nhập</span>
                            </a>
                        @endauth
                    </div>
                    
                    <div class="book-badge-ultra free">
                        <i class="fas fa-gift"></i>
                        <span>Miễn phí</span>
                    </div>
                </div>
                
                <div class="book-info-ultra">
                    <h3 class="book-title-ultra">{{ Str::limit($book->ten_sach, 40) }}</h3>
                    <p class="book-author-ultra">
                        <i class="fas fa-user-edit"></i>
                        {{ $book->tac_gia }}
                    </p>
                    <div class="book-meta-ultra">
                        <span class="book-category-ultra">
                            <i class="fas fa-tag"></i>
                            {{ $book->category->ten_the_loai ?? 'Chưa phân loại' }}
                        </span>
                        <span class="book-availability-ultra {{ $book->available_copies > 0 ? 'available' : 'unavailable' }}">
                            <i class="fas fa-circle"></i>
                            {{ $book->available_copies }} có sẵn
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state-ultra">
                <i class="fas fa-book-open"></i>
                <h3>Chưa có sách nào</h3>
                <p>Hãy thêm sách vào thư viện để bắt đầu</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Purchasable Books - Sách Có Thể Mua -->
<section id="purchasable-books" class="books-section-ultra alt-bg">
    <div class="container">
        <div class="section-header-ultra">
            <div>
                <div class="section-badge-ultra shop">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Cửa hàng sách</span>
                </div>
                <h2 class="section-title-ultra">Sách Bán Chạy</h2>
                <p class="section-subtitle-ultra">Sở hữu vĩnh viễn - Giá ưu đãi</p>
            </div>
            <a href="{{ route('purchasable-books.index') }}" class="view-all-btn-ultra">
                <span>Xem tất cả</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="books-grid-ultra">
            @forelse($purchasableBooks as $index => $book)
            <div class="book-card-ultra purchasable" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="book-image-ultra">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                    @else
                        <div class="book-placeholder-ultra">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                    
                    <div class="book-overlay-ultra">
                        <div class="book-actions-ultra">
                            <button class="action-btn-ultra" onclick="quickView({{ $book->id }}, 'purchase')" title="Xem nhanh">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('purchasable-books.show', $book->id) }}" class="action-btn-ultra" title="Chi tiết">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                        
                        @auth
                            @if(in_array($book->id, $purchasedBookIds))
                                <button class="buy-btn-ultra purchased" disabled>
                                    <i class="fas fa-check-circle"></i>
                                    <span>Đã mua</span>
                                </button>
                            @elseif($book->so_luong_ton > 0)
                                <button class="buy-btn-ultra available" onclick="addToCart({{ $book->id }})">
                                    <i class="fas fa-cart-plus"></i>
                                    <span>Thêm giỏ hàng</span>
                                </button>
                            @else
                                <button class="buy-btn-ultra unavailable" disabled>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Hết hàng</span>
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="buy-btn-ultra login-required">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Đăng nhập</span>
                            </a>
                        @endauth
                    </div>
                    
                    <div class="book-badge-ultra price">
                        <i class="fas fa-tag"></i>
                        <span>{{ number_format($book->gia, 0, ',', '.') }}đ</span>
                    </div>
                    
                    @if($book->so_luong_ban > 100)
                    <div class="book-badge-ultra bestseller">
                        <i class="fas fa-fire"></i>
                        <span>Bán chạy</span>
                    </div>
                    @endif
                </div>
                
                <div class="book-info-ultra">
                    <h3 class="book-title-ultra">{{ Str::limit($book->ten_sach, 40) }}</h3>
                    <p class="book-author-ultra">
                        <i class="fas fa-user-edit"></i>
                        {{ $book->tac_gia }}
                    </p>
                    <div class="book-meta-ultra">
                        <span class="book-price-ultra">
                            <i class="fas fa-tag"></i>
                            {{ number_format($book->gia, 0, ',', '.') }}đ
                        </span>
                        <span class="book-rating-ultra">
                            <i class="fas fa-star"></i>
                            {{ number_format($book->danh_gia_trung_binh, 1) }}/5
                        </span>
                    </div>
                    <div class="book-stats-ultra">
                        <span>
                            <i class="fas fa-shopping-cart"></i>
                            {{ $book->so_luong_ban }} đã bán
                        </span>
                        <span>
                            <i class="fas fa-eye"></i>
                            {{ $book->so_luot_xem }} lượt xem
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state-ultra">
                <i class="fas fa-shopping-bag"></i>
                <h3>Chưa có sách để bán</h3>
                <p>Hãy thêm sách vào cửa hàng</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- All Books with Pagination -->
<section class="books-section-ultra">
    <div class="container">
        <div class="section-header-ultra">
            <div>
                <h2 class="section-title-ultra">Tất Cả Sách Mượn</h2>
                <p class="section-subtitle-ultra">Khám phá toàn bộ thư viện</p>
            </div>
        </div>
        
        <div class="books-grid-ultra">
            @forelse($books as $index => $book)
            <div class="book-card-ultra">
                <div class="book-image-ultra">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                    @else
                        <div class="book-placeholder-ultra">
                            <i class="fas fa-book"></i>
                        </div>
                    @endif
                    
                    <div class="book-overlay-ultra">
                        <div class="book-actions-ultra">
                            <button class="action-btn-ultra" onclick="quickView({{ $book->id }}, 'borrow')" title="Xem nhanh">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('books.show', $book->id) }}" class="action-btn-ultra" title="Chi tiết">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                        
                        @auth
                            @if($currentReader)
                                @if($book->user_borrowed)
                                    <button class="borrow-btn-ultra borrowed" disabled>
                                        <i class="fas fa-check-circle"></i>
                                        <span>Đã mượn</span>
                                    </button>
                                @elseif($book->available_copies > 0)
                                    <button class="borrow-btn-ultra available" onclick="borrowBook({{ $book->id }})">
                                        <i class="fas fa-book-reader"></i>
                                        <span>Gửi yêu cầu mượn</span>
                                    </button>
                                @else
                                    <button class="borrow-btn-ultra unavailable" disabled>
                                        <i class="fas fa-times-circle"></i>
                                        <span>Hết sách</span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="borrow-btn-ultra login-required">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Đăng nhập</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="borrow-btn-ultra login-required">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Đăng nhập</span>
                            </a>
                        @endauth
                    </div>
                    
                    <div class="book-badge-ultra free">
                        <i class="fas fa-gift"></i>
                        <span>Miễn phí</span>
                    </div>
                </div>
                
                <div class="book-info-ultra">
                    <h3 class="book-title-ultra">{{ Str::limit($book->ten_sach, 40) }}</h3>
                    <p class="book-author-ultra">
                        <i class="fas fa-user-edit"></i>
                        {{ $book->tac_gia }}
                    </p>
                    <div class="book-meta-ultra">
                        <span class="book-category-ultra">
                            <i class="fas fa-tag"></i>
                            {{ $book->category->ten_the_loai ?? 'Chưa phân loại' }}
                        </span>
                        <span class="book-availability-ultra {{ $book->available_copies > 0 ? 'available' : 'unavailable' }}">
                            <i class="fas fa-circle"></i>
                            {{ $book->available_copies }} có sẵn
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state-ultra">
                <i class="fas fa-book-open"></i>
                <h3>Chưa có sách nào</h3>
                <p>Hãy thêm sách vào thư viện để bắt đầu</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($books->hasPages())
        <div class="pagination-ultra">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section-ultra alt-bg">
    <div class="container">
        <div class="section-header-ultra center">
            <div>
                <h2 class="section-title-ultra">Khám Phá Theo Thể Loại</h2>
                <p class="section-subtitle-ultra">Tìm sách theo sở thích của bạn</p>
            </div>
        </div>
        
        <div class="categories-grid-ultra">
            @forelse($categories->take(8) as $index => $category)
            <div class="category-card-ultra" data-aos="zoom-in" data-aos-delay="{{ ($index + 1) * 100 }}" onclick="exploreCategory({{ $category->id }})">
                <div class="category-icon-ultra">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="category-name-ultra">{{ $category->ten_the_loai }}</h3>
                <p class="category-count-ultra">{{ $category->books_count ?? 0 }} cuốn sách</p>
                <div class="category-arrow-ultra">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            @empty
            <div class="empty-state-ultra">
                <i class="fas fa-tags"></i>
                <h3>Chưa có thể loại nào</h3>
                <p>Hãy thêm thể loại vào hệ thống</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-ultra">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye"></i>
                    Xem nhanh
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Cart Button -->
@auth
<a href="{{ route('cart.index') }}" class="floating-cart-btn">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count" id="cartCount">0</span>
</a>
@endauth

@endsection

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
/* Ultra Modern Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.books-section-ultra {
    padding: 80px 0;
    background: #ffffff;
}

.books-section-ultra.alt-bg {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.section-header-ultra {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 48px;
}

.section-header-ultra.center {
    justify-content: center;
    text-align: center;
}

.section-badge-ultra {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--primary-gradient);
    color: white;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 16px;
}

.section-badge-ultra.shop {
    background: var(--warning-gradient);
}

.section-title-ultra {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2d3748;
    margin-bottom: 8px;
}

.section-subtitle-ultra {
    font-size: 1.1rem;
    color: #6c757d;
}

.view-all-btn-ultra {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--primary-gradient);
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-all-btn-ultra:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.books-grid-ultra {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 32px;
}

.book-card-ultra {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.book-card-ultra:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.book-image-ultra {
    position: relative;
    height: 400px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.book-image-ultra img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.book-card-ultra:hover .book-image-ultra img {
    transform: scale(1.1);
}

.book-placeholder-ultra {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: #6c757d;
}

.book-overlay-ultra {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    opacity: 0;
    transition: all 0.3s ease;
}

.book-card-ultra:hover .book-overlay-ultra {
    opacity: 1;
}

.book-actions-ultra {
    display: flex;
    gap: 12px;
}

.action-btn-ultra {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.action-btn-ultra:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.borrow-btn-ultra,
.buy-btn-ultra {
    padding: 14px 28px;
    border-radius: 50px;
    border: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.borrow-btn-ultra.available,
.buy-btn-ultra.available {
    background: var(--success-gradient);
    color: white;
}

.borrow-btn-ultra.borrowed,
.buy-btn-ultra.purchased {
    background: #6c757d;
    color: white;
    cursor: not-allowed;
}

.borrow-btn-ultra.unavailable,
.buy-btn-ultra.unavailable {
    background: #dc3545;
    color: white;
    cursor: not-allowed;
}

.borrow-btn-ultra.login-required,
.buy-btn-ultra.login-required {
    background: var(--primary-gradient);
    color: white;
}

.book-badge-ultra {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    backdrop-filter: blur(10px);
    z-index: 1;
}

.book-badge-ultra.free {
    background: rgba(76, 175, 80, 0.9);
    color: white;
}

.book-badge-ultra.price {
    background: rgba(255, 152, 0, 0.9);
    color: white;
}

.book-badge-ultra.bestseller {
    top: 60px;
    background: rgba(244, 67, 54, 0.9);
    color: white;
}

.book-info-ultra {
    padding: 24px;
}

.book-title-ultra {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
    line-height: 1.4;
}

.book-author-ultra {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.book-meta-ultra {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: #6c757d;
    padding-top: 16px;
    border-top: 1px solid #e9ecef;
}

.book-category-ultra,
.book-availability-ultra,
.book-price-ultra,
.book-rating-ultra {
    display: flex;
    align-items: center;
    gap: 6px;
}

.book-availability-ultra.available {
    color: #28a745;
}

.book-availability-ultra.unavailable {
    color: #dc3545;
}

.book-stats-ultra {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 12px;
}

.book-stats-ultra span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.categories-grid-ultra {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 24px;
}

.category-card-ultra {
    background: white;
    border-radius: 20px;
    padding: 32px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.category-card-ultra::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    transition: all 0.3s ease;
}

.category-card-ultra:hover::before {
    opacity: 0.1;
}

.category-card-ultra:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.category-icon-ultra {
    width: 80px;
    height: 80px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: white;
}

.category-name-ultra {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.category-count-ultra {
    color: #6c757d;
    font-size: 0.95rem;
}

.category-arrow-ultra {
    margin-top: 16px;
    font-size: 1.2rem;
    color: #667eea;
    opacity: 0;
    transition: all 0.3s ease;
}

.category-card-ultra:hover .category-arrow-ultra {
    opacity: 1;
}

.empty-state-ultra {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    color: #6c757d;
}

.empty-state-ultra i {
    font-size: 4rem;
    margin-bottom: 20px;
}

.empty-state-ultra h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.pagination-ultra {
    margin-top: 48px;
    display: flex;
    justify-content: center;
}

.modal-ultra .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-ultra .modal-header {
    background: var(--primary-gradient);
    color: white;
    border-radius: 20px 20px 0 0;
    border: none;
    padding: 24px;
}

.modal-ultra .modal-title {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-ultra .btn-close {
    filter: invert(1);
}

.floating-cart-btn {
    position: fixed;
    bottom: 32px;
    right: 32px;
    width: 64px;
    height: 64px;
    background: var(--warning-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    transition: all 0.3s ease;
    text-decoration: none;
}

.floating-cart-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    color: white;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 28px;
    height: 28px;
    background: #dc3545;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
}

@media (max-width: 768px) {
    .books-grid-ultra {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .section-title-ultra {
        font-size: 2rem;
    }
    
    .section-header-ultra {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
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
    
    // Update cart count
    updateCartCount();
});

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number-ultra');
    
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

function quickView(bookId, type) {
    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    const content = document.getElementById('quickViewContent');
    
    content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    modal.show();
    
    const url = type === 'purchase' 
        ? `/purchasable-books/${bookId}` 
        : `/books/${bookId}`;
    
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Extract book info from response
            content.innerHTML = '<div class="p-4">Đang tải thông tin sách...</div>';
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger">Không thể tải thông tin sách</div>';
        });
}

function borrowBook(bookId) {
    if (!confirm('Bạn muốn mượn sách này?')) return;
    
    fetch('/borrow-book', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        showNotification('error', 'Có lỗi xảy ra, vui lòng thử lại');
    });
}

function addToCart(bookId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ purchasable_book_id: bookId, quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Đã thêm vào giỏ hàng');
            updateCartCount();
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        showNotification('error', 'Có lỗi xảy ra, vui lòng thử lại');
    });
}

function exploreCategory(categoryId) {
    window.location.href = `/books?category_id=${categoryId}`;
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                cartCount.textContent = data.count || 0;
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush



