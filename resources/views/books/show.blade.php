@extends('layouts.frontend')

@section('title', $book->ten_sach . ' - Chi tiết sách')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.public') }}">Danh sách sách</a></li>
            <li class="breadcrumb-item active">{{ $book->ten_sach }}</li>
        </ol>
    </nav>

    <!-- Book Details -->
    <div class="row">
        <!-- Book Image -->
        <div class="col-md-4">
            <div class="book-detail-image">
                @if($book->hinh_anh)
                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                         alt="{{ $book->ten_sach }}" 
                         class="img-fluid rounded shadow-lg">
                @else
                    <div class="no-image-placeholder">
                        <i class="fas fa-book fa-5x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Book Info -->
        <div class="col-md-8">
            <div class="book-detail-info">
                <h1 class="book-title">{{ $book->ten_sach }}</h1>
                
                <div class="book-meta mb-3">
                    <p class="book-author">
                        <i class="fas fa-user"></i> 
                        <strong>Tác giả:</strong> {{ $book->tac_gia }}
                    </p>
                    <p class="book-category">
                        <i class="fas fa-tag"></i> 
                        <strong>Thể loại:</strong> 
                        <span class="badge bg-primary">{{ $book->category->ten_the_loai }}</span>
                    </p>
                    <p class="book-year">
                        <i class="fas fa-calendar"></i> 
                        <strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban }}
                    </p>
                </div>

                <!-- Rating -->
                <div class="book-rating mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rating-stars me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($stats['average_rating']))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">
                            {{ number_format($stats['average_rating'], 1) }}/5 
                            ({{ $stats['total_reviews'] }} đánh giá)
                        </span>
                    </div>
                </div>

                <!-- Availability -->
                <div class="book-availability mb-3">
                    <div class="row">
                        <div class="col-4">
                            <div class="availability-item text-center">
                                <div class="availability-number text-primary">{{ $stats['total_copies'] }}</div>
                                <div class="availability-label">Tổng bản</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="availability-item text-center">
                                <div class="availability-number text-success">{{ $stats['available_copies'] }}</div>
                                <div class="availability-label">Có sẵn</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="availability-item text-center">
                                <div class="availability-number text-warning">{{ $stats['borrowed_copies'] }}</div>
                                <div class="availability-label">Đang mượn</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="book-actions mb-4">
                    @if(auth()->check())
                    <button class="btn btn-outline-danger me-2" onclick="toggleFavorite({{ $book->id }})">
                        <i class="fas fa-heart {{ $isFavorited ? 'text-danger' : 'text-muted' }}"></i>
                        {{ $isFavorited ? 'Bỏ yêu thích' : 'Yêu thích' }}
                    </button>
                    @endif

                    @if($stats['available_copies'] > 0)
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check"></i> Có sẵn để mượn
                        </span>
                    @else
                        <span class="badge bg-warning fs-6">
                            <i class="fas fa-clock"></i> Đã được mượn hết
                        </span>
                    @endif
                </div>

                <!-- Borrow Section -->
                @auth
                    @php
                        $currentReader = \App\Models\Reader::where('user_id', auth()->id())->first();
                        $isBorrowed = \App\Models\Borrow::where('book_id', $book->id)
                            ->where('trang_thai', 'Dang muon')
                            ->exists();
                        
                        $userBorrowed = $currentReader ? \App\Models\Borrow::where('book_id', $book->id)
                            ->where('reader_id', $currentReader->id)
                            ->where('trang_thai', 'Dang muon')
                            ->first() : null;
                    @endphp

                    @if($currentReader)
                        <div class="borrow-section">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-book-reader"></i> Đặt mượn sách
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($userBorrowed)
                                        <div class="alert alert-warning">
                                            <h6><i class="fas fa-info-circle"></i> Bạn đang mượn sách này</h6>
                                            <p class="mb-2">
                                                <strong>Ngày mượn:</strong> {{ $userBorrowed->ngay_muon->format('d/m/Y') }}<br>
                                                <strong>Hạn trả:</strong> {{ $userBorrowed->ngay_hen_tra->format('d/m/Y') }}<br>
                                                <strong>Số lần gia hạn:</strong> {{ $userBorrowed->so_lan_gia_han }}/2
                                            </p>
                                            @if($userBorrowed->isOverdue())
                                                <div class="alert alert-danger mt-2">
                                                    <i class="fas fa-exclamation-triangle"></i> 
                                                    <strong>Cảnh báo:</strong> Sách đã quá hạn {{ $userBorrowed->days_overdue }} ngày!
                                                </div>
                                            @endif
                                            <div class="mt-3">
                                                @if($userBorrowed->canExtend())
                                                    <button class="btn btn-info me-2" onclick="showExtendModal({{ $userBorrowed->id }})">
                                                        <i class="fas fa-clock"></i> Gia hạn mượn
                                                    </button>
                                                @endif
                                                <button class="btn btn-success" onclick="showReturnModal({{ $userBorrowed->id }})">
                                                    <i class="fas fa-undo"></i> Trả sách
                                                </button>
                                            </div>
                                        </div>
                                    @elseif($isBorrowed)
                                        <div class="alert alert-secondary">
                                            <h6><i class="fas fa-times-circle"></i> Sách đang được mượn</h6>
                                            <p>Sách này hiện đang được mượn bởi độc giả khác. Vui lòng quay lại sau.</p>
                                            <button class="btn btn-outline-primary" onclick="showReservationModal()">
                                                <i class="fas fa-bookmark"></i> Đặt chỗ trước
                                            </button>
                                        </div>
                                    @elseif($stats['available_copies'] > 0)
                                        <form id="borrowForm">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Thời gian mượn:</label>
                                                        <select name="borrow_days" class="form-control" required>
                                                            <option value="7">7 ngày</option>
                                                            <option value="14" selected>14 ngày (mặc định)</option>
                                                            <option value="21">21 ngày</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Ghi chú (tùy chọn):</label>
                                                        <textarea name="note" class="form-control" rows="2" 
                                                                  placeholder="Ghi chú thêm về việc mượn sách..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle"></i> Thông tin mượn sách:</h6>
                                                <ul class="mb-0">
                                                    <li>Thời gian mượn: <span id="selectedDays">14</span> ngày</li>
                                                    <li>Hạn trả: <span id="dueDate">{{ now()->addDays(14)->format('d/m/Y') }}</span></li>
                                                    <li>Có thể gia hạn tối đa 2 lần</li>
                                                    <li>Trả sách muộn sẽ bị phạt</li>
                                                </ul>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-success btn-lg">
                                                    <i class="fas fa-book"></i> Đặt mượn sách
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-warning">
                                            <h6><i class="fas fa-exclamation-triangle"></i> Không có sách để mượn</h6>
                                            <p>Tất cả bản sao của sách này đều đang được mượn.</p>
                                            <button class="btn btn-outline-primary" onclick="showReservationModal()">
                                                <i class="fas fa-bookmark"></i> Đặt chỗ trước
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Cần đăng ký độc giả</h6>
                            <p>Để mượn sách, bạn cần đăng ký làm độc giả trước.</p>
                            <a href="{{ route('register.reader.form') }}" class="btn btn-primary">
                                Đăng ký độc giả 
                            </a>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-sign-in-alt"></i> Cần đăng nhập</h6>
                        <p>Để mượn sách, bạn cần đăng nhập vào hệ thống.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Book Description -->
    @if($book->mo_ta)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Mô tả sách</h5>
                </div>
                <div class="card-body">
                    <p class="book-description">{{ $book->mo_ta }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Reviews Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-star"></i> Đánh giá và bình luận</h5>
                </div>
                <div class="card-body">
                    <!-- Review Form (if user is logged in and hasn't reviewed) -->
                    @if(auth()->check() && !$userReview)
                    <div class="review-form mb-4">
                        <h6>Đánh giá sách này</h6>
                        <form id="reviewForm">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Đánh giá của bạn:</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}">
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comment" class="form-label">Bình luận:</label>
                                <textarea class="form-control" name="comment" id="comment" rows="3" 
                                          placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi đánh giá
                            </button>
                        </form>
                    </div>
                    <hr>
                    @endif

                    <!-- Reviews List -->
                    @if($book->reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($book->reviews as $review)
                        <div class="review-item mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            
                            @if($review->comment && trim($review->comment) !== '')
                            <p class="mt-2">{{ $review->comment }}</p>
                            @else
                            <p class="mt-2 text-muted"><em>Không có bình luận</em></p>
                            @endif
                            
                            @if($review->is_verified)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Đã xác minh
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có đánh giá nào cho cuốn sách này</p>
                        <p class="text-muted">Hãy là người đầu tiên đánh giá!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Books -->
    @if($relatedBooks->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-book"></i> Sách liên quan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($relatedBooks as $relatedBook)
                        <div class="col-md-3 mb-3">
                            <div class="related-book-card">
                                <div class="related-book-image">
                                    @if($relatedBook->hinh_anh)
                                    <img src="{{ asset('storage/' . $relatedBook->hinh_anh) }}" 
                                         alt="{{ $relatedBook->ten_sach }}" 
                                         class="img-fluid">
                                    @else
                                    <div class="no-image-placeholder">
                                        <i class="fas fa-book fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="related-book-info">
                                    <h6 class="related-book-title">{{ Str::limit($relatedBook->ten_sach, 40) }}</h6>
                                    <p class="related-book-author">{{ $relatedBook->tac_gia }}</p>
                                    <a href="{{ route('books.show', $relatedBook->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal gia hạn mượn sách -->
<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gia hạn mượn sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn gia hạn mượn sách <strong>{{ $book->ten_sach }}</strong>?</p>
                <div class="mb-3">
                    <label class="form-label">Số ngày gia hạn:</label>
                    <select id="extendDays" class="form-control">
                        <option value="7">7 ngày</option>
                        <option value="14">14 ngày</option>
                    </select>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Lưu ý:</strong> Bạn có thể gia hạn tối đa 2 lần cho mỗi cuốn sách.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-info" id="confirmExtend">
                    <i class="fas fa-clock"></i> Gia hạn
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal trả sách -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trả sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn trả sách <strong>{{ $book->ten_sach }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Lưu ý:</strong> Sau khi trả sách, bạn sẽ không thể mượn lại cho đến khi có sách mới.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="confirmReturn">
                    <i class="fas fa-undo"></i> Xác nhận trả sách
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal đặt chỗ trước -->
<div class="modal fade" id="reservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đặt chỗ trước</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn muốn đặt chỗ trước cho sách <strong>{{ $book->ten_sach }}</strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Thông tin đặt chỗ:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Khi có sách trả về, bạn sẽ được thông báo</li>
                        <li>Bạn có 24 giờ để đến mượn sách</li>
                        <li>Nếu không đến, chỗ đặt sẽ bị hủy</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmReservation">
                    <i class="fas fa-bookmark"></i> Đặt chỗ trước
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast thông báo -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="borrowToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="fas fa-book text-success me-2"></i>
            <strong class="me-auto">Thông báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Nội dung thông báo sẽ được thêm vào đây -->
        </div>
    </div>
</div>

<style>
.book-detail-image img {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
}

.no-image-placeholder {
    width: 100%;
    height: 400px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-title {
    color: #333;
    margin-bottom: 1rem;
}

.book-meta p {
    margin-bottom: 0.5rem;
    color: #666;
}

.rating-stars {
    font-size: 1.2rem;
}

.availability-item {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.availability-number {
    font-size: 2rem;
    font-weight: bold;
}

.availability-label {
    font-size: 0.9rem;
    color: #666;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star-label {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #ffc107;
}

.review-item {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
}

.related-book-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s;
}

.related-book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.related-book-image {
    height: 200px;
    overflow: hidden;
}

.related-book-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-book-info {
    padding: 1rem;
}

.related-book-title {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.related-book-author {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 1rem;
}

.book-description {
    line-height: 1.6;
    color: #555;
}
</style>

<script>
let currentBorrowId = null;
const extendModal = new bootstrap.Modal(document.getElementById('extendModal'));
const returnModal = new bootstrap.Modal(document.getElementById('returnModal'));
const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
const borrowToast = new bootstrap.Toast(document.getElementById('borrowToast'));

// Toggle favorite
function toggleFavorite(bookId) {
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        alert('Vui lòng đăng nhập để sử dụng tính năng này');
        return;
    }

    fetch(`/api/favorites/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    });
}

// Show extend modal
function showExtendModal(borrowId) {
    currentBorrowId = borrowId;
    extendModal.show();
}

// Show return modal
function showReturnModal(borrowId) {
    currentBorrowId = borrowId;
    returnModal.show();
}

// Show reservation modal
function showReservationModal() {
    reservationModal.show();
}

// Update borrow days info
document.addEventListener('DOMContentLoaded', function() {
    const borrowDaysSelect = document.querySelector('select[name="borrow_days"]');
    const selectedDaysSpan = document.getElementById('selectedDays');
    const dueDateSpan = document.getElementById('dueDate');
    
    if (borrowDaysSelect && selectedDaysSpan && dueDateSpan) {
        borrowDaysSelect.addEventListener('change', function() {
            const days = parseInt(this.value);
            selectedDaysSpan.textContent = days;
            
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + days);
            dueDateSpan.textContent = dueDate.toLocaleDateString('vi-VN');
        });
    }
});

// Submit review
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!{{ auth()->check() ? 'true' : 'false' }}) {
        alert('Vui lòng đăng nhập để đánh giá sách');
        return;
    }
    
    const formData = new FormData(this);
    
    // Debug: Log form data
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    fetch('/api/reviews', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Không thể gửi đánh giá'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi gửi đánh giá');
    });
});

// Submit borrow form
document.getElementById('borrowForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    // Show loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch('/borrow-book', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Confirm extend
document.getElementById('confirmExtend')?.addEventListener('click', function() {
    if (!currentBorrowId) return;
    
    const button = this;
    const originalText = button.innerHTML;
    const days = document.getElementById('extendDays').value;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch(`/api/borrows/${currentBorrowId}/extend`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ days: parseInt(days) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            extendModal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Confirm return
document.getElementById('confirmReturn')?.addEventListener('click', function() {
    if (!currentBorrowId) return;
    
    const button = this;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch(`/api/borrows/${currentBorrowId}/return`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            returnModal.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Confirm reservation
document.getElementById('confirmReservation')?.addEventListener('click', function() {
    const button = this;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    // Giả lập đặt chỗ trước
    setTimeout(() => {
        showToast('success', 'Đặt chỗ trước thành công! Bạn sẽ được thông báo khi có sách.');
        reservationModal.hide();
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
});

// Show toast function
function showToast(type, message) {
    const toastElement = document.getElementById('borrowToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    
    const toastHeader = toastElement.querySelector('.toast-header');
    const icon = toastHeader.querySelector('i');
    
    if (type === 'success') {
        icon.className = 'fas fa-check-circle text-success me-2';
        toastElement.classList.remove('bg-danger');
    } else {
        icon.className = 'fas fa-exclamation-circle text-danger me-2';
        toastElement.classList.add('bg-danger');
    }
    
    borrowToast.show();
}
</script>
@endsection
