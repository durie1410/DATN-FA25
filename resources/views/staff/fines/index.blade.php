@extends('layouts.staff')

@section('title', 'Quản Lý Phạt - Nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
            Quản lý phí phạt
        </h3>
        <p class="text-muted mb-0">Quản lý và theo dõi các khoản phạt của độc giả</p>
    </div>
    <div>
        <a href="{{ route('staff.fines.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Tạo phạt mới
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
                <h5 class="text-muted mb-2">Chưa thanh toán</h5>
                <h2 class="text-warning">{{ $fines->where('status', 'pending')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Đã thanh toán</h5>
                <h2 class="text-success">{{ $fines->where('status', 'paid')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Tổng tiền phạt</h5>
                <h2 class="text-danger">{{ number_format($fines->where('status', 'pending')->sum('amount')) }} VNĐ</h2>
            </div>
        </div>
    </div>
</div>

<!-- Tìm kiếm -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('staff.fines.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Tìm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã phạt</th>
                        <th>Độc giả</th>
                        <th>Sách</th>
                        <th>Loại phạt</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th width="120">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fines as $fine)
                    <tr>
                        <td><strong>#{{ $fine->id }}</strong></td>
                        <td>{{ $fine->reader->ho_ten ?? 'N/A' }}</td>
                        <td>{{ $fine->book->ten_sach ?? 'N/A' }}</td>
                        <td>
                            @if($fine->type == 'late_return')
                                <span class="badge bg-warning">Trả muộn</span>
                            @elseif($fine->type == 'damaged_book')
                                <span class="badge bg-danger">Làm hỏng</span>
                            @elseif($fine->type == 'lost_book')
                                <span class="badge bg-danger">Mất sách</span>
                            @else
                                <span class="badge bg-secondary">{{ $fine->type }}</span>
                            @endif
                        </td>
                        <td><strong class="text-danger">{{ number_format($fine->amount) }} VNĐ</strong></td>
                        <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($fine->status == 'pending')
                                <span class="badge bg-warning">Chưa thanh toán</span>
                            @elseif($fine->status == 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @else
                                <span class="badge bg-secondary">{{ $fine->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staff.fines.show', $fine->id) }}" class="btn btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($fine->status == 'pending')
                                    <form action="{{ route('staff.fines.mark-paid', $fine->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Đánh dấu đã thanh toán" onclick="return confirm('Xác nhận đã thanh toán?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Không có phạt nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($fines, 'links'))
            <div class="mt-3">
                {{ $fines->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

