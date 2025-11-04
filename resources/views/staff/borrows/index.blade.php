@extends('layouts.staff')

@section('title', 'Quản Lý Mượn/Trả Sách - Nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-hand-holding text-primary me-2"></i>
            Quản lý mượn/trả sách
        </h3>
        <p class="text-muted mb-0">Theo dõi và quản lý hoạt động mượn trả sách</p>
    </div>
    <div>
        <a href="{{ route('staff.borrows.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Cho mượn sách mới
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Thống kê nhanh -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Đang mượn</h5>
                <h2 class="text-primary">{{ $borrows->where('trang_thai', 'Dang muon')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Quá hạn</h5>
                <h2 class="text-danger">{{ $borrows->where('trang_thai', 'Qua han')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Đã trả</h5>
                <h2 class="text-success">{{ $borrows->where('trang_thai', 'Da tra')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Tổng số</h5>
                <h2 class="text-info">{{ $borrows->total() }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Tìm kiếm -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('staff.borrows.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên sách, độc giả...">
            </div>
            <div class="col-md-2">
                <select name="trang_thai" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="Dang muon" {{ request('trang_thai') == 'Dang muon' ? 'selected' : '' }}>Đang mượn</option>
                    <option value="Da tra" {{ request('trang_thai') == 'Da tra' ? 'selected' : '' }}>Đã trả</option>
                    <option value="Qua han" {{ request('trang_thai') == 'Qua han' ? 'selected' : '' }}>Quá hạn</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Tìm
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('staff.borrows.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-times me-1"></i>Xóa
                </a>
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
                        <th>Mã mượn</th>
                        <th>Sách</th>
                        <th>Độc giả</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Trạng thái</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrows as $borrow)
                    <tr>
                        <td><strong>#{{ $borrow->id }}</strong></td>
                        <td>
                            <strong>{{ $borrow->book->ten_sach ?? 'N/A' }}</strong>
                            <br>
                            <small class="text-muted">Tác giả: {{ $borrow->book->tac_gia ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $borrow->reader->ho_ten ?? 'N/A' }}</td>
                        <td>{{ $borrow->ngay_muon ? \Carbon\Carbon::parse($borrow->ngay_muon)->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            @if($borrow->ngay_hen_tra)
                                <span class="{{ \Carbon\Carbon::parse($borrow->ngay_hen_tra)->isPast() && $borrow->trang_thai != 'Da tra' ? 'text-danger' : '' }}">
                                    {{ \Carbon\Carbon::parse($borrow->ngay_hen_tra)->format('d/m/Y') }}
                                </span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($borrow->trang_thai == 'Dang muon')
                                <span class="badge bg-primary">Đang mượn</span>
                            @elseif($borrow->trang_thai == 'Da tra')
                                <span class="badge bg-success">Đã trả</span>
                            @elseif($borrow->trang_thai == 'Qua han')
                                <span class="badge bg-danger">Quá hạn</span>
                            @else
                                <span class="badge bg-secondary">{{ $borrow->trang_thai }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staff.borrows.show', $borrow->id) }}" class="btn btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($borrow->trang_thai == 'Dang muon')
                                    <form action="{{ route('staff.borrows.return', $borrow->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Trả sách" onclick="return confirm('Xác nhận trả sách?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Không có phiếu mượn nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($borrows, 'links'))
            <div class="mt-3">
                {{ $borrows->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

