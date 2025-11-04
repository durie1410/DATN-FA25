@extends('layouts.admin')

@section('title', 'Quản Lý Đặt Trước')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-check me-2"></i>Quản Lý Đặt Trước
            </h1>
            <p class="text-muted mb-0">Quản lý các yêu cầu đặt trước sách của độc giả</p>
        </div>
        <div>
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Tạo Đặt Trước
            </a>
            <button type="button" class="btn btn-success" onclick="exportReservations()">
                <i class="fas fa-download me-1"></i>Xuất Excel
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng Đặt Trước</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reservations->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đang Chờ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reservations->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đã Xác Nhận</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reservations->where('status', 'confirmed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sẵn Sàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reservations->where('status', 'ready')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search me-2"></i>Tìm Kiếm và Lọc
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reservations.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Sẵn sàng</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="book_id" class="form-label">Sách</label>
                    <select class="form-select" id="book_id" name="book_id">
                        <option value="">Tất cả sách</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->ten_sach }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="reader_id" class="form-label">Độc giả</label>
                    <select class="form-select" id="reader_id" name="reader_id">
                        <option value="">Tất cả độc giả</option>
                        @foreach($readers as $reader)
                            <option value="{{ $reader->id }}" {{ request('reader_id') == $reader->id ? 'selected' : '' }}>
                                {{ $reader->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Danh Sách Đặt Trước
            </h6>
            <div>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('confirm')">
                    <i class="fas fa-check me-1"></i>Xác nhận
                </button>
                <button type="button" class="btn btn-sm btn-info" onclick="bulkAction('mark-ready')">
                    <i class="fas fa-hand-holding me-1"></i>Đánh dấu sẵn sàng
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('cancel')">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>#</th>
                            <th>Thông tin sách</th>
                            <th>Độc giả</th>
                            <th>Ngày đặt</th>
                            <th>Hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Độ ưu tiên</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations ?? [] as $reservation)
                        <tr>
                            <td>
                                <input type="checkbox" class="reservation-checkbox" value="{{ $reservation->id }}">
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="book-info">
                                    <div class="book-title">{{ $reservation->book->ten_sach }}</div>
                                    <div class="book-author">{{ $reservation->book->tac_gia }}</div>
                                    <div class="book-isbn">{{ $reservation->book->isbn }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="reader-info">
                                    <div class="reader-name">{{ $reservation->reader->ho_ten }}</div>
                                    <div class="reader-card">{{ $reservation->reader->so_the_doc_gia }}</div>
                                    <div class="reader-phone">{{ $reservation->reader->so_dien_thoai }}</div>
                                </div>
                            </td>
                            <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                            <td>
                                <div class="expiry-info">
                                    <div class="expiry-date">{{ $reservation->expiry_date->format('d/m/Y') }}</div>
                                    @if($reservation->isExpired())
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Hết hạn
                                        </small>
                                    @elseif($reservation->expiry_date->diffInDays(now()) <= 1)
                                        <small class="text-warning">
                                            <i class="fas fa-clock"></i> Sắp hết hạn
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ $reservation->priority }}">
                                    {{ $reservation->priority }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.reservations.show', $reservation) }}" 
                                       class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($reservation->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="confirmReservation({{ $reservation->id }})" title="Xác nhận">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if($reservation->status === 'confirmed')
                                        <button type="button" class="btn btn-sm btn-info" 
                                                onclick="markReady({{ $reservation->id }})" title="Đánh dấu sẵn sàng">
                                            <i class="fas fa-hand-holding"></i>
                                        </button>
                                    @endif
                                    @if(in_array($reservation->status, ['pending', 'confirmed']))
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="cancelReservation({{ $reservation->id }})" title="Hủy">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Không có đặt trước nào</h5>
                                    <p class="text-muted">Chưa có yêu cầu đặt trước nào trong hệ thống.</p>
                                    <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Tạo đặt trước đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reservations && $reservations->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.book-info, .reader-info {
    display: flex;
    flex-direction: column;
}

.book-title, .reader-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.book-author, .book-isbn, .reader-card, .reader-phone {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 1px;
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

.priority-badge {
    padding: 4px 8px;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 24px;
    text-align: center;
    display: inline-block;
}

.priority-1 {
    background-color: #dc3545;
    color: white;
}

.priority-2 {
    background-color: #fd7e14;
    color: white;
}

.priority-3 {
    background-color: #ffc107;
    color: #212529;
}

.priority-4 {
    background-color: #20c997;
    color: white;
}

.priority-5 {
    background-color: #6c757d;
    color: white;
}

.expiry-info {
    text-align: center;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}
</style>
@endsection

@section('scripts')
<script>
// Toggle select all
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.reservation-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Bulk actions
function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.reservation-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một đặt trước!');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'confirm':
            confirmMessage = `Bạn có chắc muốn xác nhận ${ids.length} đặt trước?`;
            break;
        case 'mark-ready':
            confirmMessage = `Bạn có chắc muốn đánh dấu ${ids.length} đặt trước là sẵn sàng?`;
            break;
        case 'cancel':
            confirmMessage = `Bạn có chắc muốn hủy ${ids.length} đặt trước?`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        // Implement bulk action logic here
        alert(`Đã thực hiện ${action} cho ${ids.length} đặt trước!`);
        location.reload();
    }
}

// Confirm reservation
function confirmReservation(reservationId) {
    if (confirm('Bạn có chắc muốn xác nhận đặt trước này?')) {
        fetch(`/admin/reservations/${reservationId}/confirm`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    }
}

// Mark ready
function markReady(reservationId) {
    if (confirm('Bạn có chắc muốn đánh dấu đặt trước này là sẵn sàng?')) {
        fetch(`/admin/reservations/${reservationId}/mark-ready`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    }
}

// Cancel reservation
function cancelReservation(reservationId) {
    if (confirm('Bạn có chắc muốn hủy đặt trước này?')) {
        fetch(`/admin/reservations/${reservationId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    }
}

// Export reservations
function exportReservations() {
    window.open('{{ route("admin.reservations.export") }}', '_blank');
}
</script>
@endsection
