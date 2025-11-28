@extends('layouts.admin')

@section('title', 'Quản Lý Đặt Trước - WAKA Admin')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-calendar-check"></i>
            Quản lý đặt trước
        </h1>
        <p class="page-subtitle">Theo dõi và quản lý tất cả yêu cầu đặt trước sách</p>
    </div>
    <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tạo đặt trước
    </a>
</div>

<!-- Quick Stats -->
<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đang chờ</div>
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value">{{ $reservations->where('status', 'pending')->count() }}</div>
        <div class="stat-label">Chờ xử lý</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đã xác nhận</div>
            <div class="stat-icon primary">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $reservations->where('status', 'confirmed')->count() }}</div>
        <div class="stat-label">Đã được xác nhận</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Sẵn sàng</div>
            <div class="stat-icon success">
                <i class="fas fa-hand-holding"></i>
            </div>
        </div>
        <div class="stat-value">{{ $reservations->where('status', 'ready')->count() }}</div>
        <div class="stat-label">Có thể nhận sách</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Tổng số</div>
            <div class="stat-icon info">
                <i class="fas fa-list"></i>
            </div>
        </div>
        <div class="stat-value">{{ $reservations->total() }}</div>
        <div class="stat-label">Tất cả đặt trước</div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter"></i>
            Tìm kiếm & Lọc
        </h3>
    </div>
    <form action="{{ route('admin.reservations.index') }}" method="GET" style="padding: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" 
                   name="keyword" 
                   value="{{ request('keyword') }}" 
                   class="form-control" 
                   placeholder="Tìm theo tên độc giả hoặc tên sách...">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <select name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Sẵn sàng</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
            </select>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <select name="book_id" class="form-select">
                <option value="">-- Tất cả sách --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->ten_sach }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
            Lọc
        </button>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i>
            Reset
        </a>
    </form>
</div>

<!-- Reservations List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Danh sách đặt trước
        </h3>
        <span class="badge badge-info">Tổng: {{ $reservations->total() }} đặt trước</span>
    </div>
    
    @if($reservations->count() > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã đặt trước</th>
                    <th>Độc giả</th>
                    <th>Sách</th>
                    <th>Ngày đặt</th>
                    <th>Hết hạn</th>
                    <th>Trạng thái</th>
                    <th>Độ ưu tiên</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr style="{{ $reservation->isExpired() && $reservation->status != 'cancelled' ? 'border-left: 3px solid #ff6b6b;' : '' }}">
                    <td>
                        <span class="badge badge-info">{{ $reservation->id }}</span>
                    </td>
                    <td>
                        @if($reservation->reader)
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: 600;">
                                    {{ strtoupper(substr($reservation->reader->ho_ten, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500; color: var(--text-primary);">
                                        {{ $reservation->reader->ho_ten }}
                                    </div>
                                    <div style="font-size: 12px; color: #888;">
                                        ID: {{ $reservation->reader->id }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <span>Không có thông tin</span>
                        @endif
                    </td>
                    <td>
                        @if($reservation->book)
                            <div>
                                <div style="font-weight: 500; color: var(--text-primary);">
                                    {{ $reservation->book->ten_sach }}
                                </div>
                                <div style="font-size: 12px; color: #888;">
                                    {{ $reservation->book->tac_gia }}
                                </div>
                            </div>
                        @else
                            <span>Không có thông tin</span>
                        @endif
                    </td>
                    <td>
                        {{ $reservation->reservation_date->format('d/m/Y') }}
                    </td>
                    <td>
                        <div>
                            <div>{{ $reservation->expiry_date->format('d/m/Y') }}</div>
                            @if($reservation->isExpired())
                                <small style="color: #ff6b6b;">
                                    <i class="fas fa-exclamation-triangle"></i> Hết hạn
                                </small>
                            @elseif($reservation->expiry_date->diffInDays(now()) <= 1)
                                <small style="color: #ffc107;">
                                    <i class="fas fa-clock"></i> Sắp hết hạn
                                </small>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($reservation->status == 'ready')
                            <span class="badge badge-success">
                                <i class="fas fa-hand-holding"></i> Sẵn sàng
                            </span>
                        @elseif($reservation->status == 'confirmed')
                            <span class="badge" style="background: rgba(0, 123, 255, 0.2); color: #007bff;">
                                <i class="fas fa-check-circle"></i> Đã xác nhận
                            </span>
                        @elseif($reservation->status == 'pending')
                            <span class="badge" style="background: rgba(255, 193, 7, 0.2); color: #ffc107;">
                                <i class="fas fa-clock"></i> Đang chờ
                            </span>
                        @elseif($reservation->status == 'cancelled')
                            <span class="badge badge-secondary">
                                <i class="fas fa-times-circle"></i> Đã hủy
                            </span>
                        @elseif($reservation->status == 'expired')
                            <span class="badge badge-danger">
                                <i class="fas fa-exclamation-triangle"></i> Hết hạn
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background: {{ $reservation->priority <= 2 ? '#dc3545' : ($reservation->priority == 3 ? '#ffc107' : '#6c757d') }}; color: white;">
                            {{ $reservation->priority }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                               class="btn btn-sm btn-secondary"
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.reservations.edit', $reservation->id) }}" 
                               class="btn btn-sm btn-warning"
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($reservation->status === 'pending')
                                <button type="button" 
                                        class="btn btn-sm btn-success" 
                                        onclick="confirmReservation({{ $reservation->id }})"
                                        title="Xác nhận">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            @if($reservation->status === 'confirmed')
                                <button type="button" 
                                        class="btn btn-sm btn-info" 
                                        onclick="markReady({{ $reservation->id }})"
                                        title="Đánh dấu sẵn sàng">
                                    <i class="fas fa-hand-holding"></i>
                                </button>
                            @endif
                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="cancelReservation({{ $reservation->id }})"
                                        title="Hủy">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                            <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" 
                                  method="POST" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Xóa đặt trước này?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger"
                                        title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px;">
        {{ $reservations->appends(request()->query())->links('vendor.pagination.admin') }}
    </div>
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0, 255, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-calendar-check" style="font-size: 36px; color: var(--primary-color);"></i>
            </div>
            <h3 style="color: var(--text-primary); margin-bottom: 10px;">Chưa có đặt trước nào</h3>
            <p style="color: #888; margin-bottom: 25px;">Hãy tạo đặt trước đầu tiên để bắt đầu quản lý.</p>
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Tạo đặt trước đầu tiên
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .modal.fade {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
    }
    
    .modal-dialog {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Get CSRF token from meta tag or input
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '{{ csrf_token() }}';
}

// Confirm reservation
function confirmReservation(reservationId) {
    if (confirm('Bạn có chắc muốn xác nhận đặt trước này?')) {
        const formData = new FormData();
        formData.append('_token', getCsrfToken());
        
        fetch(`/admin/reservations/${reservationId}/confirm`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            return response.text().then(text => {
                throw new Error(text || 'Có lỗi xảy ra');
            });
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Đặt trước đã được xác nhận!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Nếu là lỗi permission hoặc validation, thử submit form thông thường
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/reservations/${reservationId}/confirm`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = getCsrfToken();
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// Mark ready
function markReady(reservationId) {
    if (confirm('Bạn có chắc muốn đánh dấu đặt trước này là sẵn sàng?')) {
        const formData = new FormData();
        formData.append('_token', getCsrfToken());
        
        fetch(`/admin/reservations/${reservationId}/mark-ready`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            return response.text().then(text => {
                throw new Error(text || 'Có lỗi xảy ra');
            });
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Sách đã sẵn sàng để nhận!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Nếu là lỗi permission hoặc validation, thử submit form thông thường
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/reservations/${reservationId}/mark-ready`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = getCsrfToken();
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// Cancel reservation
function cancelReservation(reservationId) {
    if (confirm('Bạn có chắc muốn hủy đặt trước này?')) {
        const formData = new FormData();
        formData.append('_token', getCsrfToken());
        
        fetch(`/admin/reservations/${reservationId}/cancel`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            return response.text().then(text => {
                throw new Error(text || 'Có lỗi xảy ra');
            });
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Đặt trước đã được hủy!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Nếu là lỗi permission hoặc validation, thử submit form thông thường
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/reservations/${reservationId}/cancel`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = getCsrfToken();
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// Export reservations
function exportReservations() {
    window.open('{{ route("admin.reservations.export") }}', '_blank');
}
</script>
@endpush




