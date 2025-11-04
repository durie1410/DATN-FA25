@extends('layouts.admin')

@section('title', 'Tạo Đặt Trước')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>Tạo Đặt Trước Mới
            </h1>
            <p class="text-muted mb-0">Tạo yêu cầu đặt trước sách cho độc giả</p>
        </div>
        <div>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Thông Tin Đặt Trước
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reservations.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="book_id" class="form-label">Sách <span class="text-danger">*</span></label>
                                <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                                    <option value="">Chọn sách</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" 
                                                {{ (old('book_id') == $book->id || (isset($book) && $book->id == $book->id)) ? 'selected' : '' }}>
                                            {{ $book->ten_sach }} - {{ $book->tac_gia }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('book_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="reader_id" class="form-label">Độc giả <span class="text-danger">*</span></label>
                                <select class="form-select @error('reader_id') is-invalid @enderror" id="reader_id" name="reader_id" required>
                                    <option value="">Chọn độc giả</option>
                                    @foreach($readers as $reader)
                                        <option value="{{ $reader->id }}" {{ old('reader_id') == $reader->id ? 'selected' : '' }}>
                                            {{ $reader->ho_ten }} ({{ $reader->so_the_doc_gia }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('reader_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Độ ưu tiên</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                    <option value="1" {{ old('priority', 1) == 1 ? 'selected' : '' }}>1 - Thấp nhất</option>
                                    <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>2 - Thấp</option>
                                    <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>3 - Trung bình</option>
                                    <option value="4" {{ old('priority') == 4 ? 'selected' : '' }}>4 - Cao</option>
                                    <option value="5" {{ old('priority') == 5 ? 'selected' : '' }}>5 - Cao nhất</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="reservation_date" class="form-label">Ngày đặt trước</label>
                                <input type="date" class="form-control @error('reservation_date') is-invalid @enderror" 
                                       id="reservation_date" name="reservation_date" 
                                       value="{{ old('reservation_date', date('Y-m-d')) }}" readonly>
                                @error('reservation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Nhập ghi chú về đặt trước...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Tạo Đặt Trước
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Book Info Card -->
            @if(isset($book))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book me-2"></i>Thông Tin Sách
                    </h6>
                </div>
                <div class="card-body">
                    <div class="book-details">
                        <h5 class="book-title">{{ $book->ten_sach }}</h5>
                        <p class="book-author"><strong>Tác giả:</strong> {{ $book->tac_gia }}</p>
                        <p class="book-isbn"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p class="book-category"><strong>Thể loại:</strong> {{ $book->category->ten_the_loai ?? 'N/A' }}</p>
                        <p class="book-publisher"><strong>Nhà xuất bản:</strong> {{ $book->publisher->ten_nha_xuat_ban ?? 'N/A' }}</p>
                        <p class="book-year"><strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban ?? 'N/A' }}</p>
                        <p class="book-pages"><strong>Số trang:</strong> {{ $book->so_trang ?? 'N/A' }}</p>
                        <p class="book-status">
                            <strong>Trạng thái:</strong> 
                            <span class="badge bg-{{ $book->trang_thai == 'available' ? 'success' : 'warning' }}">
                                {{ $book->trang_thai == 'available' ? 'Có sẵn' : 'Không có sẵn' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Help Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Hướng Dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <div class="help-content">
                        <h6>Quy trình đặt trước:</h6>
                        <ol class="small">
                            <li><strong>Đang chờ:</strong> Đặt trước được tạo và chờ xác nhận</li>
                            <li><strong>Đã xác nhận:</strong> Thủ thư xác nhận đặt trước</li>
                            <li><strong>Sẵn sàng:</strong> Sách đã sẵn sàng để độc giả nhận</li>
                            <li><strong>Hết hạn:</strong> Đặt trước hết hạn sau 7 ngày</li>
                        </ol>
                        
                        <h6 class="mt-3">Độ ưu tiên:</h6>
                        <ul class="small">
                            <li><strong>1-2:</strong> Độ ưu tiên thấp</li>
                            <li><strong>3:</strong> Độ ưu tiên trung bình</li>
                            <li><strong>4-5:</strong> Độ ưu tiên cao</li>
                        </ul>
                        
                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Lưu ý:</strong> Đặt trước sẽ tự động hết hạn sau 7 ngày nếu không được xác nhận.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.book-details h5 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.book-details p {
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.book-title {
    font-weight: 600;
    color: #2c3e50;
}

.help-content h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.help-content ol, .help-content ul {
    margin-bottom: 15px;
}

.help-content li {
    margin-bottom: 5px;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}
</style>
@endsection

@section('scripts')
<script>
// Auto-select book if passed in URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('book_id');
    if (bookId) {
        document.getElementById('book_id').value = bookId;
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const bookId = document.getElementById('book_id').value;
    const readerId = document.getElementById('reader_id').value;
    
    if (!bookId || !readerId) {
        e.preventDefault();
        alert('Vui lòng chọn sách và độc giả!');
        return false;
    }
});

// Update expiry date when reservation date changes
document.getElementById('reservation_date').addEventListener('change', function() {
    const reservationDate = new Date(this.value);
    const expiryDate = new Date(reservationDate);
    expiryDate.setDate(expiryDate.getDate() + 7);
    
    // You can add expiry date display if needed
    console.log('Expiry date will be:', expiryDate.toISOString().split('T')[0]);
});
</script>
@endsection
