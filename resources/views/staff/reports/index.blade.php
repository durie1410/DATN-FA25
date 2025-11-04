@extends('layouts.staff')

@section('title', 'Báo Cáo - Nhân viên')

@section('content')
<div class="mb-4">
    <h3 class="mb-1">
        <i class="fas fa-chart-bar text-primary me-2"></i>
        Báo cáo thống kê
    </h3>
    <p class="text-muted mb-0">Xem các báo cáo thống kê của thư viện</p>
</div>

<!-- Thống kê tổng quan -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Tổng sách</h5>
                <h3 class="text-primary">{{ $totalBooks ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Tổng độc giả</h5>
                <h3 class="text-success">{{ $totalReaders ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Tổng lượt mượn</h5>
                <h3 class="text-info">{{ $totalBorrows ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Đang mượn</h5>
                <h3 class="text-warning">{{ $activeBorrows ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Quá hạn</h5>
                <h3 class="text-danger">{{ $overdueBorrows ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="text-muted mb-2">Thể loại</h5>
                <h3 class="text-secondary">{{ $categoryStats->count() ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Các báo cáo -->
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-hand-holding fa-3x text-primary mb-3"></i>
                <h5>Báo cáo mượn trả</h5>
                <p class="text-muted">Thống kê hoạt động mượn trả sách</p>
                <a href="{{ route('staff.reports.borrows') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-1"></i>Xem báo cáo
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-success mb-3"></i>
                <h5>Báo cáo độc giả</h5>
                <p class="text-muted">Thống kê về độc giả</p>
                <a href="{{ route('staff.reports.readers') }}" class="btn btn-success">
                    <i class="fas fa-eye me-1"></i>Xem báo cáo
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-book fa-3x text-info mb-3"></i>
                <h5>Báo cáo sách</h5>
                <p class="text-muted">Thống kê về sách</p>
                <a href="{{ route('staff.reports.books') }}" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i>Xem báo cáo
                </a>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Lưu ý:</strong> Nhân viên chỉ có thể xem báo cáo, không thể xuất file Excel hoặc PDF.
</div>
@endsection

