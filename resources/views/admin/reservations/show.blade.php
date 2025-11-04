@extends('layouts.admin')

@section('title', 'Chi Tiết Đặt Trước')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-check me-2"></i>Chi Tiết Đặt Trước
            </h1>
            <p class="text-muted mb-0">Thông tin chi tiết về đặt trước sách</p>
        </div>
        <div>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <a href="{{ route('admin.reservations.edit', $reservation) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Reservation Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Thông Tin Đặt Trước
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Mã đặt trước:</strong></td>
                                    <td>#{{ $reservation->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
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
                                </tr>
                                <tr>
                                    <td><strong>Độ ưu tiên:</strong></td>
                                    <td>
                                        <span class="priority-badge priority-{{ $reservation->priority }}">
                                            {{ $reservation->priority }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày đặt trước:</strong></td>
                                    <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày hết hạn:</strong></td>
                                    <td>
                                        {{ $reservation->expiry_date->format('d/m/Y') }}
                                        @if($reservation->isExpired())
                                            <small class="text-danger ms-2">
                                                <i class="fas fa-exclamation-triangle"></i> Hết hạn
                                            </small>
                                        @elseif($reservation->expiry_date->diffInDays(now()) <= 1)
                                            <small class="text-warning ms-2">
                                                <i class="fas fa-clock"></i> Sắp hết hạn
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Ngày sẵn sàng:</strong></td>
                                    <td>{{ $reservation->ready_date ? $reservation->ready_date->format('d/m/Y') : 'Chưa có' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày nhận sách:</strong></td>
                                    <td>{{ $reservation->pickup_date ? $reservation->pickup_date->format('d/m/Y') : 'Chưa nhận' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cập nhật cuối:</strong></td>
                                    <td>{{ $reservation->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($reservation->notes)
                    <div class="mt-3">
                        <h6><strong>Ghi chú:</strong></h6>
                        <div class="alert alert-light">
                            {{ $reservation->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Thao Tác
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @if($reservation->status === 'pending')
                            <button type="button" class="btn btn-success" onclick="confirmReservation({{ $reservation->id }})">
                                <i class="fas fa-check me-1"></i>Xác nhận
                            </button>
                        @endif
                        
                        @if($reservation->status === 'confirmed')
                            <button type="button" class="btn btn-info" onclick="markReady({{ $reservation->id }})">
                                <i class="fas fa-hand-holding me-1"></i>Đánh dấu sẵn sàng
                            </button>
                        @endif
                        
                        @if(in_array($reservation->status, ['pending', 'confirmed']))
                            <button type="button" class="btn btn-danger" onclick="cancelReservation({{ $reservation->id }})">
                                <i class="fas fa-times me-1"></i>Hủy đặt trước
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-warning" onclick="printReservation()">
                            <i class="fas fa-print me-1"></i>In đặt trước
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book me-2"></i>Thông Tin Sách
                    </h6>
                </div>
                <div class="card-body">
                    <div class="book-details">
                        <h5 class="book-title">{{ $reservation->book->ten_sach }}</h5>
                        <p class="book-author"><strong>Tác giả:</strong> {{ $reservation->book->tac_gia }}</p>
                        <p class="book-isbn"><strong>ISBN:</strong> {{ $reservation->book->isbn }}</p>
                        <p class="book-category"><strong>Thể loại:</strong> {{ $reservation->book->category->ten_the_loai ?? 'N/A' }}</p>
                        <p class="book-publisher"><strong>Nhà xuất bản:</strong> {{ $reservation->book->publisher->ten_nha_xuat_ban ?? 'N/A' }}</p>
                        <p class="book-year"><strong>Năm xuất bản:</strong> {{ $reservation->book->nam_xuat_ban ?? 'N/A' }}</p>
                        <p class="book-pages"><strong>Số trang:</strong> {{ $reservation->book->so_trang ?? 'N/A' }}</p>
                        <p class="book-status">
                            <strong>Trạng thái:</strong> 
                            <span class="badge bg-{{ $reservation->book->trang_thai == 'available' ? 'success' : 'warning' }}">
                                {{ $reservation->book->trang_thai == 'available' ? 'Có sẵn' : 'Không có sẵn' }}
                            </span>
                        </p>
                        <p class="book-location"><strong>Vị trí:</strong> {{ $reservation->book->vi_tri ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Reader Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Thông Tin Độc Giả
                    </h6>
                </div>
                <div class="card-body">
                    <div class="reader-details">
                        <h5 class="reader-name">{{ $reservation->reader->ho_ten }}</h5>
                        <p class="reader-card"><strong>Mã thẻ:</strong> {{ $reservation->reader->so_the_doc_gia }}</p>
                        <p class="reader-phone"><strong>Số điện thoại:</strong> {{ $reservation->reader->so_dien_thoai }}</p>
                        <p class="reader-email"><strong>Email:</strong> {{ $reservation->reader->email }}</p>
                        <p class="reader-address"><strong>Địa chỉ:</strong> {{ $reservation->reader->dia_chi }}</p>
                        <p class="reader-status">
                            <strong>Trạng thái thẻ:</strong> 
                            <span class="badge bg-{{ $reservation->reader->trang_thai == 'Hoat dong' ? 'success' : 'warning' }}">
                                {{ $reservation->reader->trang_thai }}
                            </span>
                        </p>
                        <p class="reader-expiry"><strong>Hết hạn thẻ:</strong> {{ $reservation->reader->ngay_het_han->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-shield me-2"></i>Người Tạo Đặt Trước
                    </h6>
                </div>
                <div class="card-body">
                    <div class="user-details">
                        <h5 class="user-name">{{ $reservation->user->name }}</h5>
                        <p class="user-email"><strong>Email:</strong> {{ $reservation->user->email }}</p>
                        <p class="user-role">
                            <strong>Vai trò:</strong> 
                            <span class="badge bg-primary">
                                @switch($reservation->user->role)
                                    @case('admin')
                                        Quản trị viên
                                        @break
                                    @case('staff')
                                        Nhân viên
                                        @break
                                    @default
                                        Người dùng
                                @endswitch
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.book-details h5, .reader-details h5, .user-details h5 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.book-details p, .reader-details p, .user-details p {
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.book-title, .reader-name, .user-name {
    font-weight: 600;
    color: #2c3e50;
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

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.gap-2 {
    gap: 0.5rem;
}
</style>
@endsection

@section('scripts')
<script>
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

// Print reservation
function printReservation() {
    window.print();
}
</script>
@endsection
