@extends('layouts.user')

@section('title', 'Khám phá sách - WAKA')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-compass"></i>
            Khám phá sách
        </h1>
        <p class="page-subtitle">Tổng cộng {{ $books->total() }} cuốn sách đang chờ bạn khám phá</p>
    </div>
</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 30px;">
    <form action="{{ route('books.public') }}" method="GET" style="padding: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 300px;">
            <input type="text" 
                   name="keyword" 
                   value="{{ request('keyword') }}" 
                   class="form-control" 
                   placeholder="Tìm theo tên sách hoặc tác giả..."
                   style="padding: 12px 16px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 14px;">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <select name="category_id" 
                    class="form-control"
                    style="padding: 12px 16px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 14px;">
                <option value="">-- Tất cả thể loại --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->ten_the_loai }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
            Tìm kiếm
        </button>
        <a href="{{ route('books.public') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i>
            Reset
        </a>
    </form>
</div>

<!-- Books Grid -->
@if($books->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; margin-bottom: 40px;">
        @foreach($books as $book)
            <div class="book-card book-item-public" 
                 data-book-id="{{ $book->id }}"
                 data-book-title="{{ $book->ten_sach }}"
                 data-book-author="{{ $book->tac_gia }}"
                 data-book-genre="{{ $book->category->ten_the_loai ?? 'Chưa phân loại' }}"
                 data-book-rating="4.5/5"
                 data-book-year="{{ $book->nam_xuat_ban ?? 'N/A' }}"
                 data-book-description="{{ Str::limit($book->mo_ta ?? 'Chưa có mô tả', 200) }}"
                 data-book-premium="false"
                 style="background: var(--background-card); border: 1px solid rgba(0, 255, 153, 0.1); border-radius: 15px; padding: 15px; transition: all 0.3s; cursor: pointer; position: relative;">
                <!-- Member/Price Badge -->
                @if($book->is_member_only)
                    <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #FF6B35, #FF4757); color: white; padding: 5px 10px; border-radius: 15px; font-size: 10px; font-weight: 700; z-index: 10; box-shadow: 0 3px 10px rgba(255, 107, 53, 0.5);">
                        <i class="fas fa-crown"></i> VIP
                    </div>
                @endif
                
                <!-- Book Cover -->
                <div class="book-cover" style="margin-bottom: 15px; border-radius: 10px; overflow: hidden; aspect-ratio: 2/3; position: relative;">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                             style="width: 100%; height: 100%; object-fit: cover;"
                             alt="{{ $book->ten_sach }}">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1a5f4d, #2d6e5a); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-book" style="font-size: 48px; color: rgba(255, 255, 255, 0.3);"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Book Info -->
                <div>
                    <h3 style="font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 5px; line-height: 1.4; height: 42px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $book->ten_sach }}
                    </h3>
                    <div style="font-size: 13px; color: var(--primary-color); margin-bottom: 8px;">
                        {{ $book->tac_gia }}
                    </div>
                    
                    <!-- Book Meta -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                        <div style="font-size: 12px; color: #888;">
                            <i class="fas fa-tags"></i>
                            {{ $book->category->ten_the_loai ?? 'N/A' }}
                        </div>
                        <div style="font-size: 12px; color: #888;">
                            <i class="fas fa-calendar"></i>
                            {{ $book->nam_xuat_ban }}
                        </div>
                    </div>
                    
                    @if($book->gia_ban)
                        <div style="margin-top: 10px; padding: 8px; background: rgba(0, 255, 153, 0.1); border-radius: 8px; text-align: center;">
                            <span style="color: var(--primary-color); font-weight: 600; font-size: 14px;">
                                {{ number_format($book->gia_ban) }} VNĐ
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="display: flex; justify-content: center; margin-top: 40px;">
        {{ $books->appends(request()->query())->links() }}
    </div>
@else
    <div style="text-align: center; padding: 80px 20px;">
        <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(0, 255, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
            <i class="fas fa-book-open" style="font-size: 48px; color: var(--primary-color);"></i>
        </div>
        <h3 style="color: var(--text-primary); margin-bottom: 10px; font-size: 24px;">Không tìm thấy sách</h3>
        <p style="color: #888; margin-bottom: 25px; font-size: 16px;">Thử điều chỉnh bộ lọc hoặc từ khóa tìm kiếm của bạn</p>
        <a href="{{ route('books.public') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-redo"></i>
            Xem tất cả sách
        </a>
    </div>
@endif
@endsection

@push('styles')
<style>
    .book-card:hover {
        border-color: rgba(0, 255, 153, 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transform: translateY(-5px);
    }
    
    .book-card:hover .book-overlay {
        opacity: 1;
    }
    
    /* Pagination Styles */
    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination .page-item {
        list-style: none;
    }
    
    .pagination .page-link {
        padding: 10px 15px;
        background: var(--background-card);
        border: 1px solid rgba(0, 255, 153, 0.1);
        border-radius: 8px;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
    }
    
    .pagination .page-link:hover {
        background: rgba(0, 255, 153, 0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .pagination .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #000;
        font-weight: 600;
    }
    
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
<link rel="stylesheet" href="{{ asset('style.css') }}">
@endpush

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

@push('scripts')
<script src="{{ asset('script.js') }}"></script>
<script>
// Initialize modal hover for public books page
function initializePublicBookModal() {
  const bookDetailModal = document.getElementById('bookDetailModal');
  const bookDetailClose = document.querySelector('.book-detail-close');
  const bookDetailOverlay = document.querySelector('.book-detail-overlay');
  
  if (!bookDetailModal) {
    console.warn('BookDetailModal not found in DOM');
    return;
  }
  
  let hoverTimeout = null;
  let isHoveringModal = false;
  let currentModalBookData = null;
  
  const openBookDetailModal = (bookData) => {
    if (currentModalBookData && currentModalBookData.element === bookData.element && bookDetailModal.classList.contains('active')) {
      return;
    }
    
    currentModalBookData = bookData;
    
    const modalCover = document.getElementById('modalBookCover');
    const modalTitle = document.getElementById('modalBookTitle');
    const modalAuthor = document.getElementById('modalBookAuthor');
    const modalGenre = document.getElementById('modalBookGenre');
    const modalRating = document.getElementById('modalBookRating');
    const modalYear = document.getElementById('modalBookYear');
    const modalDescription = document.getElementById('modalBookDescription');
    const modalBadge = document.getElementById('modalBookBadge');
    const modalBookId = document.getElementById('modalBookId');
    
    const bookCover = bookData.element.querySelector('.book-cover');
    if (bookCover && modalCover) {
      modalCover.innerHTML = bookCover.innerHTML;
    }
    
    if (modalTitle) modalTitle.textContent = bookData.title;
    if (modalAuthor) modalAuthor.textContent = bookData.author;
    if (modalGenre) modalGenre.textContent = bookData.genre;
    if (modalRating) modalRating.textContent = bookData.rating;
    if (modalYear) modalYear.textContent = bookData.year;
    if (modalDescription) modalDescription.textContent = bookData.description;
    if (modalBookId) modalBookId.value = bookData.id || '';
    
    if (modalBadge) {
      if (bookData.premium === 'true') {
        modalBadge.style.display = 'inline-flex';
      } else {
        modalBadge.style.display = 'none';
      }
    }
    
    bookDetailModal.classList.add('active');
  };
  
  const closeBookDetailModal = () => {
    bookDetailModal.classList.remove('active');
    currentModalBookData = null;
  };
  
  const bookItems = document.querySelectorAll('.book-item-public');
  
  bookItems.forEach((book, index) => {
    const bookData = {
      element: book,
      id: book.dataset.bookId,
      title: book.dataset.bookTitle,
      author: book.dataset.bookAuthor,
      genre: book.dataset.bookGenre,
      rating: book.dataset.bookRating,
      year: book.dataset.bookYear,
      description: book.dataset.bookDescription,
      premium: book.dataset.bookPremium
    };
    
    if (bookData.title) {
      book.addEventListener('mouseenter', function(e) {
        if (hoverTimeout) {
          clearTimeout(hoverTimeout);
          hoverTimeout = null;
        }
        isHoveringModal = false;
        openBookDetailModal(bookData);
      });
      
      book.addEventListener('mouseleave', function(e) {
        const relatedTarget = e.relatedTarget;
        const modal = document.getElementById('bookDetailModal');
        if (relatedTarget && modal && (modal.contains(relatedTarget) || relatedTarget === modal)) {
          isHoveringModal = true;
          return;
        }
        
        if (!isHoveringModal) {
          hoverTimeout = setTimeout(() => {
            if (!isHoveringModal) {
              closeBookDetailModal();
            }
          }, 300);
        }
      });
    }
  });
  
  const bookDetailContainer = document.querySelector('.book-detail-container');
  if (bookDetailContainer) {
    bookDetailContainer.addEventListener('mouseenter', () => {
      isHoveringModal = true;
      if (hoverTimeout) {
        clearTimeout(hoverTimeout);
        hoverTimeout = null;
      }
    });
    
    bookDetailContainer.addEventListener('mouseleave', () => {
      isHoveringModal = false;
      hoverTimeout = setTimeout(() => {
        closeBookDetailModal();
      }, 300);
    });
    
    bookDetailContainer.addEventListener('click', (e) => {
      e.stopPropagation();
    });
  }
  
  if (bookDetailClose) {
    bookDetailClose.addEventListener('click', closeBookDetailModal);
  }
  
  if (bookDetailOverlay) {
    bookDetailOverlay.addEventListener('click', closeBookDetailModal);
  }
  
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && bookDetailModal.classList.contains('active')) {
      closeBookDetailModal();
    }
  });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(initializePublicBookModal, 100);
  });
} else {
  setTimeout(initializePublicBookModal, 100);
}
</script>
@endpush