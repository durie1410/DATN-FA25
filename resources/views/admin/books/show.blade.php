@extends('layouts.admin')

@section('title', 'Chi tiết sách - ' . $book->ten_sach)

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.books.index') }}">Quản lý sách</a></li>
            <li class="breadcrumb-item active">{{ $book->ten_sach }}</li>
        </ol>
    </nav>

    <!-- Header với thông tin cơ bản -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Ảnh bìa sách -->
                        <div class="col-md-3">
                            <div class="text-center">
                                @if($book->hinh_anh)
                                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                                         alt="{{ $book->ten_sach }}" 
                                         class="img-fluid rounded shadow"
                                         style="max-height: 300px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 300px;">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Thông tin sách -->
                        <div class="col-md-6">
                            <h1 class="h3 mb-3">{{ $book->ten_sach }}</h1>
                            
                            <div class="mb-3">
                                <strong>Tác giả:</strong> {{ $book->tac_gia }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Thể loại:</strong> 
                                <span class="badge bg-primary">{{ $book->category->ten_the_loai }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban }}
                            </div>

                            <!-- Đánh giá sao -->
                            <div class="mb-3">
                                <strong>Đánh giá:</strong>
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($stats['average_rating']))
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2">
                                        {{ number_format($stats['average_rating'], 1) }}/5 
                                        ({{ $stats['total_reviews'] }} đánh giá)
                                    </span>
                                </div>
                            </div>

                            <!-- Mô tả -->
                            @if($book->mo_ta)
                            <div class="mb-3">
                                <strong>Mô tả:</strong>
                                <p class="text-muted">{{ $book->mo_ta }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Thống kê và hành động -->
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Thống kê</h5>
                                    
                                    <div class="row text-center">
                                        <div class="col-6 mb-2">
                                            <div class="border-end">
                                                <div class="h4 text-primary">{{ $stats['total_copies'] }}</div>
                                                <small class="text-muted">Tổng bản</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="h4 text-success">{{ $stats['available_copies'] }}</div>
                                            <small class="text-muted">Có sẵn</small>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="h4 text-warning">{{ $stats['borrowed_copies'] }}</div>
                                            <small class="text-muted">Đang mượn</small>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="h4 text-info">{{ $stats['total_borrows'] }}</div>
                                            <small class="text-muted">Lượt mượn</small>
                                        </div>
                                    </div>

                                    <!-- Hành động -->
                                    <div class="mt-3">
                                        @can('edit-books')
                                        <a href="{{ route('admin.books.edit', $book->id) }}" 
                                           class="btn btn-warning btn-sm w-100 mb-2">
                                            <i class="fas fa-edit"></i> Chỉnh sửa
                                        </a>
                                        @endcan

                                        @if(auth()->check())
                                        <button class="btn btn-outline-danger btn-sm w-100 mb-2" 
                                                onclick="toggleFavorite({{ $book->id }})">
                                            <i class="fas fa-heart {{ $isFavorited ? 'text-danger' : 'text-muted' }}"></i>
                                            {{ $isFavorited ? 'Bỏ yêu thích' : 'Yêu thích' }}
                                        </button>
                                        @endif

                                        @can('create-borrows')
                                        @if($stats['available_copies'] > 0)
                                        <button class="btn btn-success btn-sm w-100 mb-2" 
                                                onclick="showBorrowModal()">
                                            <i class="fas fa-book-open"></i> Cho mượn
                                        </button>
                                        @endif
                                        @endcan

                                        @can('create-reservations')
                                        @if($stats['available_copies'] == 0)
                                        <button class="btn btn-info btn-sm w-100 mb-2" 
                                                onclick="showReservationModal()">
                                            <i class="fas fa-bookmark"></i> Đặt trước
                                        </button>
                                        @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs cho các chức năng chi tiết -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="bookTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" 
                                    data-bs-target="#inventory" type="button" role="tab">
                                <i class="fas fa-boxes"></i> Quản lý kho
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                                    data-bs-target="#reviews" type="button" role="tab">
                                <i class="fas fa-star"></i> Đánh giá ({{ $stats['total_reviews'] }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="borrows-tab" data-bs-toggle="tab" 
                                    data-bs-target="#borrows" type="button" role="tab">
                                <i class="fas fa-exchange-alt"></i> Lịch sử mượn
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="related-tab" data-bs-toggle="tab" 
                                    data-bs-target="#related" type="button" role="tab">
                                <i class="fas fa-book"></i> Sách liên quan
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="bookTabsContent">
                        <!-- Tab Quản lý kho -->
                        <div class="tab-pane fade show active" id="inventory" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Danh sách bản copy trong kho</h5>
                                @can('create-books')
                                <button class="btn btn-primary btn-sm" onclick="showAddInventoryModal()">
                                    <i class="fas fa-plus"></i> Thêm bản copy
                                </button>
                                @endcan
                            </div>

                            @if($book->inventories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Mã vạch</th>
                                            <th>Vị trí</th>
                                            <th>Tình trạng</th>
                                            <th>Trạng thái</th>
                                            <th>Giá mua</th>
                                            <th>Ngày mua</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($book->inventories as $inventory)
                                        <tr>
                                            <td>
                                                <code>{{ $inventory->barcode }}</code>
                                            </td>
                                            <td>{{ $inventory->location }}</td>
                                            <td>
                                                @php
                                                    $conditionColors = [
                                                        'Moi' => 'success',
                                                        'Tot' => 'primary',
                                                        'Trung binh' => 'warning',
                                                        'Cu' => 'secondary',
                                                        'Hong' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $conditionColors[$inventory->condition] ?? 'secondary' }}">
                                                    {{ $inventory->condition }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'Co san' => 'success',
                                                        'Dang muon' => 'warning',
                                                        'Mat' => 'danger',
                                                        'Hong' => 'danger',
                                                        'Thanh ly' => 'secondary'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$inventory->status] ?? 'secondary' }}">
                                                    {{ $inventory->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($inventory->purchase_price)
                                                    {{ number_format($inventory->purchase_price) }} VNĐ
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($inventory->purchase_date)
                                                    {{ \Carbon\Carbon::parse($inventory->purchase_date)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @can('edit-books')
                                                <a href="{{ route('admin.inventory.edit', $inventory->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có bản copy nào trong kho</p>
                                @can('create-books')
                                <button class="btn btn-primary" onclick="showAddInventoryModal()">
                                    <i class="fas fa-plus"></i> Thêm bản copy đầu tiên
                                </button>
                                @endcan
                            </div>
                            @endif
                        </div>

                        <!-- Tab Đánh giá -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <!-- Form đánh giá (nếu user chưa đánh giá) -->
                            @if(auth()->check() && !$userReview)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-star"></i> Đánh giá sách này</h6>
                                </div>
                                <div class="card-body">
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
                            </div>
                            @endif

                            <!-- Danh sách đánh giá -->
                            @if($book->reviews->count() > 0)
                            <div class="row">
                                @foreach($book->reviews as $review)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
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
                                            
                                            @if($review->comment)
                                            <p class="mb-2">{{ $review->comment }}</p>
                                            @endif
                                            
                                            @if($review->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Đã xác minh
                                            </span>
                                            @endif
                                        </div>
                                    </div>
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

                        <!-- Tab Lịch sử mượn -->
                        <div class="tab-pane fade" id="borrows" role="tabpanel">
                            @if($book->borrows->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Độc giả</th>
                                            <th>Ngày mượn</th>
                                            <th>Ngày hẹn trả</th>
                                            <th>Ngày trả thực tế</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($book->borrows as $borrow)
                                        <tr>
                                            <td>
                                                <strong>{{ $borrow->reader->ho_ten }}</strong><br>
                                                <small class="text-muted">{{ $borrow->reader->so_the_doc_gia }}</small>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($borrow->ngay_muon)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($borrow->ngay_hen_tra)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($borrow->ngay_tra_thuc_te)
                                                    {{ \Carbon\Carbon::parse($borrow->ngay_tra_thuc_te)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'Dang muon' => 'warning',
                                                        'Da tra' => 'success',
                                                        'Qua han' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$borrow->trang_thai] ?? 'secondary' }}">
                                                    {{ $borrow->trang_thai }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có lượt mượn nào</p>
                            </div>
                            @endif
                        </div>

                        <!-- Tab Sách liên quan -->
                        <div class="tab-pane fade" id="related" role="tabpanel">
                            @if($relatedBooks->count() > 0)
                            <div class="row">
                                @foreach($relatedBooks as $relatedBook)
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100">
                                        @if($relatedBook->hinh_anh)
                                        <img src="{{ asset('storage/' . $relatedBook->hinh_anh) }}" 
                                             class="card-img-top" 
                                             style="height: 200px; object-fit: cover;"
                                             alt="{{ $relatedBook->ten_sach }}">
                                        @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-book fa-2x text-muted"></i>
                                        </div>
                                        @endif
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title">{{ Str::limit($relatedBook->ten_sach, 50) }}</h6>
                                            <p class="card-text text-muted small">{{ $relatedBook->tac_gia }}</p>
                                            <p class="card-text">
                                                <span class="badge bg-primary">{{ $relatedBook->category->ten_the_loai }}</span>
                                            </p>
                                            
                                            <div class="mt-auto">
                                                <a href="{{ route('admin.books.show', $relatedBook->id) }}" 
                                                   class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="fas fa-eye"></i> Xem chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Không có sách liên quan</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal cho mượn sách -->
<div class="modal fade" id="borrowModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cho mượn sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="borrowForm">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Chọn tác giả:</label>
                        <select class="form-select" name="reader_id" required>
                            <option value="">-- Chọn tác giả --</option>
                            <!-- Sẽ được load bằng AJAX -->
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ngày mượn:</label>
                        <input type="date" class="form-control" name="ngay_muon" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ngày hẹn trả:</label>
                        <input type="date" class="form-control" name="ngay_hen_tra" 
                               value="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú:</label>
                        <textarea class="form-control" name="ghi_chu" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitBorrow()">Cho mượn</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cho đặt trước -->
<div class="modal fade" id="reservationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đặt trước sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reservationForm">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Chọn tác giả:</label>
                        <select class="form-select" name="reader_id" required>
                            <option value="">-- Chọn tác giả --</option>
                            <!-- Sẽ được load bằng AJAX -->
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú:</label>
                        <textarea class="form-control" name="notes" rows="3" 
                                  placeholder="Lý do đặt trước sách này..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitReservation()">Đặt trước</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm bản copy -->
<div class="modal fade" id="addInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm bản copy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addInventoryForm">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Mã vạch:</label>
                        <input type="text" class="form-control" name="barcode" 
                               placeholder="Để trống để tự động tạo">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Vị trí trong kho:</label>
                        <input type="text" class="form-control" name="location" 
                               placeholder="VD: Kệ A1, Tầng 2" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tình trạng sách:</label>
                        <select class="form-select" name="condition" required>
                            <option value="Moi">Mới</option>
                            <option value="Tot">Tốt</option>
                            <option value="Trung binh">Trung bình</option>
                            <option value="Cu">Cũ</option>
                            <option value="Hong">Hỏng</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Giá mua (VNĐ):</label>
                        <input type="number" class="form-control" name="purchase_price" 
                               placeholder="Nhập giá mua nếu có">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ngày mua:</label>
                        <input type="date" class="form-control" name="purchase_date">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú:</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitAddInventory()">Thêm</button>
            </div>
        </div>
    </div>
</div>

<style>
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

.card-img-top {
    transition: transform 0.3s;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}
</style>

<script>
// Toggle favorite
function toggleFavorite(bookId) {
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

// Show borrow modal
function showBorrowModal() {
    loadReaders('borrowForm select[name="reader_id"]');
    new bootstrap.Modal(document.getElementById('borrowModal')).show();
}

// Show reservation modal
function showReservationModal() {
    loadReaders('reservationForm select[name="reader_id"]');
    new bootstrap.Modal(document.getElementById('reservationModal')).show();
}

// Show add inventory modal
function showAddInventoryModal() {
    new bootstrap.Modal(document.getElementById('addInventoryModal')).show();
}

// Load readers for dropdown
function loadReaders(selector) {
    fetch('/api/readers')
    .then(response => response.json())
    .then(data => {
        const select = document.querySelector(selector);
        select.innerHTML = '<option value="">-- Chọn tác giả --</option>';
        data.data.forEach(reader => {
            const option = document.createElement('option');
            option.value = reader.id;
            option.textContent = `${reader.ho_ten} (${reader.so_the_doc_gia})`;
            select.appendChild(option);
        });
    });
}

// Submit borrow
function submitBorrow() {
    const form = document.getElementById('borrowForm');
    const formData = new FormData(form);
    
    fetch('/admin/borrows', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('borrowModal')).hide();
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    });
}

// Submit reservation
function submitReservation() {
    const form = document.getElementById('reservationForm');
    const formData = new FormData(form);
    
    fetch('/admin/reservations', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('reservationModal')).hide();
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    });
}

// Submit add inventory
function submitAddInventory() {
    const form = document.getElementById('addInventoryForm');
    const formData = new FormData(form);
    
    fetch('/admin/inventory', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addInventoryModal')).hide();
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    });
}

// Submit review
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/api/reviews', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    });
});
</script>
@endsection


