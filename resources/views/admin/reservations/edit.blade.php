@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Đặt Trước')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Chỉnh Sửa Đặt Trước
            </h1>
            <p class="text-muted mb-0">Cập nhật thông tin đặt trước sách</p>
        </div>
        <div>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i>Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-edit me-2"></i>Thông Tin Đặt Trước
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                    <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="ready" {{ old('status', $reservation->status) == 'ready' ? 'selected' : '' }}>Sẵn sàng</option>
                                    <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    <option value="expired" {{ old('status', $reservation->status) == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Độ ưu tiên</label>
                                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority">
                                    <option value="1" {{ old('priority', $reservation->priority) == 1 ? 'selected' : '' }}>1 - Thấp nhất</option>
                                    <option value="2" {{ old('priority', $reservation->priority) == 2 ? 'selected' : '' }}>2 - Thấp</option>
                                    <option value="3" {{ old('priority', $reservation->priority) == 3 ? 'selected' : '' }}>3 - Trung bình</option>
                                    <option value="4" {{ old('priority', $reservation->priority) == 4 ? 'selected' : '' }}>4 - Cao</option>
                                    <option value="5" {{ old('priority', $reservation->priority) == 5 ? 'selected' : '' }}>5 - Cao nhất</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">Ngày hết hạn</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" 
                                       value="{{ old('expiry_date', $reservation->expiry_date->format('Y-m-d')) }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ready_date" class="form-label">Ngày sẵn sàng</label>
                                <input type="date" class="form-control @error('ready_date') is-invalid @enderror" 
                                       id="ready_date" name="ready_date" 
                                       value="{{ old('ready_date', $reservation->ready_date ? $reservation->ready_date->format('Y-m-d') : '') }}">
                                @error('ready_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pickup_date" class="form-label">Ngày nhận sách</label>
                                <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" 
                                       id="pickup_date" name="pickup_date" 
                                       value="{{ old('pickup_date', $reservation->pickup_date ? $reservation->pickup_date->format('Y-m-d') : '') }}">
                                @error('pickup_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Nhập ghi chú về đặt trước...">{{ old('notes', $reservation->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Reservation Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông Tin Hiện Tại
                    </h6>
                </div>
                <div class="card-body">
                    <div class="reservation-info">
                        <p><strong>Mã đặt trước:</strong> #{{ $reservation->id }}</p>
                        <p><strong>Sách:</strong> {{ $reservation->book->ten_sach }}</p>
                        <p><strong>Độc giả:</strong> {{ $reservation->reader->ho_ten }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $reservation->reservation_date->format('d/m/Y') }}</p>
                        <p><strong>Ngày hết hạn:</strong> {{ $reservation->expiry_date->format('d/m/Y') }}</p>
                        <p><strong>Trạng thái hiện tại:</strong> 
                            <span class="status-badge status-{{ $reservation->status }}">
                                @switch($reservation->status)
                                    @case('pending')
                                        <i class="fas fa-clock me-1"></i>Đang chờ
                                        @break
                                    @case('confirmed')
                                        <i class="fas fa-check-circle me-1"></i>Đã xác nhận
                                        @break
                                    @case('ready')
                                        <i class="fas fa-hand-holding me-1"></i>Sẵn sàng
                                        @break
                                    @case('cancelled')
                                        <i class="fas fa-times-circle me-1"></i>Đã hủy
                                        @break
                                    @case('expired')
                                        <i class="fas fa-exclamation-triangle me-1"></i>Hết hạn
                                        @break
                                @endswitch
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Hướng Dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <div class="help-content">
                        <h6>Trạng thái đặt trước:</h6>
                        <ul class="small">
                            <li><strong>Đang chờ:</strong> Đặt trước mới được tạo</li>
                            <li><strong>Đã xác nhận:</strong> Thủ thư đã xác nhận</li>
                            <li><strong>Sẵn sàng:</strong> Sách đã sẵn sàng để nhận</li>
                            <li><strong>Đã hủy:</strong> Đặt trước bị hủy</li>
                            <li><strong>Hết hạn:</strong> Đặt trước hết hạn</li>
                        </ul>
                        
                        <div class="alert alert-warning mt-3">
                            <small>
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Lưu ý:</strong> Thay đổi trạng thái sẽ ảnh hưởng đến quy trình xử lý đặt trước.
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
.reservation-info p {
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background-color: #d1ecf1;
    color: #0c5460;
}

.status-ready {
    background-color: #d4edda;
    color: #155724;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.status-expired {
    background-color: #f5c6cb;
    color: #721c24;
}

.help-content h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.help-content ul {
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

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
</style>
@endsection

@section('scripts')
<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const status = document.getElementById('status').value;
    const expiryDate = document.getElementById('expiry_date').value;
    
    if (!status) {
        e.preventDefault();
        alert('Vui lòng chọn trạng thái!');
        return false;
    }
    
    if (expiryDate && new Date(expiryDate) <= new Date()) {
        e.preventDefault();
        alert('Ngày hết hạn phải sau ngày hiện tại!');
        return false;
    }
});

// Auto-set ready date when status changes to ready
document.getElementById('status').addEventListener('change', function() {
    if (this.value === 'ready' && !document.getElementById('ready_date').value) {
        document.getElementById('ready_date').value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection
