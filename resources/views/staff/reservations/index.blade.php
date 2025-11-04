@extends('layouts.staff')

@section('title', 'Quản Lý Đặt Trước - Nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-calendar-check text-primary me-2"></i>
            Quản lý đặt trước
        </h3>
        <p class="text-muted mb-0">Quản lý các yêu cầu đặt trước sách của độc giả</p>
    </div>
    <div>
        <a href="{{ route('staff.reservations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tạo đặt trước
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Thống kê -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Tổng đặt trước</h5>
                <h2 class="text-primary">{{ $reservations->total() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Đang chờ</h5>
                <h2 class="text-warning">{{ $reservations->where('status', 'pending')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Đã xác nhận</h5>
                <h2 class="text-success">{{ $reservations->where('status', 'confirmed')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Sách</th>
                        <th>Độc giả</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th width="200">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td><strong>#{{ $reservation->id }}</strong></td>
                        <td>
                            <strong>{{ $reservation->book->ten_sach ?? 'N/A' }}</strong>
                        </td>
                        <td>{{ $reservation->reader->ho_ten ?? 'N/A' }}</td>
                        <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($reservation->status == 'pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                            @elseif($reservation->status == 'confirmed')
                                <span class="badge bg-success">Đã xác nhận</span>
                            @elseif($reservation->status == 'ready')
                                <span class="badge bg-info">Sẵn sàng</span>
                            @elseif($reservation->status == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">{{ $reservation->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staff.reservations.show', $reservation->id) }}" class="btn btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($reservation->status == 'pending')
                                    <form action="{{ route('staff.reservations.confirm', $reservation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Xác nhận" onclick="return confirm('Xác nhận đặt trước này?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($reservation->status == 'confirmed')
                                    <form action="{{ route('staff.reservations.mark-ready', $reservation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" title="Đánh dấu sẵn sàng">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Không có đặt trước nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($reservations, 'links'))
            <div class="mt-3">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

