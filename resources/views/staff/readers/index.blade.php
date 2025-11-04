@extends('layouts.staff')

@section('title', 'Quản Lý Độc Giả - Nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-users text-primary me-2"></i>
            Quản lý độc giả
        </h3>
        <p class="text-muted mb-0">Quản lý thông tin độc giả thư viện</p>
    </div>
    <div>
        <a href="{{ route('staff.readers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Thêm mới
        </a>
        <a href="{{ route('staff.readers.statistics') }}" class="btn btn-info">
            <i class="fas fa-chart-bar me-1"></i>Thống kê
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Form tìm kiếm -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('staff.readers.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên, email, số thẻ...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="Hoat dong" {{ request('trang_thai') == 'Hoat dong' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="Tam khoa" {{ request('trang_thai') == 'Tam khoa' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Tìm
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <a href="{{ route('staff.readers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Xóa
                    </a>
                </div>
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
                        <th>Mã thẻ</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Ngày hết hạn</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($readers as $reader)
                    <tr>
                        <td>
                            <strong>{{ $reader->so_the_doc_gia }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-primary text-white" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        {{ substr($reader->ho_ten, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $reader->ho_ten }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>{{ $reader->email }}</td>
                        <td>{{ $reader->so_dien_thoai }}</td>
                        <td>
                            @if($reader->trang_thai == 'Hoat dong')
                                <span class="badge bg-success">Hoạt động</span>
                            @elseif($reader->trang_thai == 'Tam khoa')
                                <span class="badge bg-danger">Tạm khóa</span>
                            @else
                                <span class="badge bg-secondary">{{ $reader->trang_thai }}</span>
                            @endif
                        </td>
                        <td>{{ $reader->ngay_het_han ? \Carbon\Carbon::parse($reader->ngay_het_han)->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staff.readers.show', $reader->id) }}" class="btn btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.readers.edit', $reader->id) }}" class="btn btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Không có độc giả nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($readers, 'links'))
            <div class="mt-3">
                {{ $readers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

