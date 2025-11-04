@extends('layouts.admin')

@section('title', 'Thống kê độc giả - Quản Lý Thư Viện')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Thống kê độc giả</h4>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary text-white font-size-24">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['total_readers'] }}</h5>
                            <p class="text-muted mb-0">Tổng độc giả</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success text-white font-size-24">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['active_readers'] }}</h5>
                            <p class="text-muted mb-0">Hoạt động</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning text-white font-size-24">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['suspended_readers'] }}</h5>
                            <p class="text-muted mb-0">Tạm khóa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger text-white font-size-24">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['expired_readers'] }}</h5>
                            <p class="text-muted mb-0">Hết hạn</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo giới tính -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thống kê theo giới tính</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <h3 class="text-primary">{{ $stats['male_readers'] }}</h3>
                                <p class="text-muted">Nam</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <h3 class="text-pink">{{ $stats['female_readers'] }}</h3>
                                <p class="text-muted">Nữ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thống kê theo trạng thái</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo năm sinh -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thống kê theo năm sinh</h5>
                </div>
                <div class="card-body">
                    <canvas id="yearChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng chi tiết -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Top 10 độc giả tích cực</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tên độc giả</th>
                                    <th>Số lượt mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $topReaders = \App\Models\Reader::withCount('borrows')
                                        ->orderBy('borrows_count', 'asc')
                                        ->limit(10)
                                        ->get();
                                @endphp
                                @foreach($topReaders as $reader)
                                <tr>
                                    <td>{{ $reader->ho_ten }}</td>
                                    <td><span class="badge bg-primary">{{ $reader->borrows_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Độc giả có phạt chưa thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tên độc giả</th>
                                    <th>Số tiền phạt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $readersWithFines = \App\Models\Reader::withSum('fines', 'amount')
                                        ->having('fines_sum_amount', '>', 0)
                                        ->orderBy('fines_sum_amount', 'asc')
                                        ->limit(10)
                                        ->get();
                                @endphp
                                @foreach($readersWithFines as $reader)
                                <tr>
                                    <td>{{ $reader->ho_ten }}</td>
                                    <td><span class="badge bg-danger">{{ number_format($reader->fines_sum_amount) }} VNĐ</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart theo trạng thái
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($stats['readers_by_status']);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.trang_thai),
            datasets: [{
                data: statusData.map(item => item.count),
                backgroundColor: [
                    '#28a745', // Hoạt động
                    '#ffc107', // Tạm khóa
                    '#dc3545'  // Hết hạn
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Chart theo năm sinh
    const yearCtx = document.getElementById('yearChart').getContext('2d');
    const yearData = @json($stats['readers_by_year']);
    
    new Chart(yearCtx, {
        type: 'bar',
        data: {
            labels: yearData.map(item => item.year),
            datasets: [{
                label: 'Số độc giả',
                data: yearData.map(item => item.count),
                backgroundColor: '#17a2b8',
                borderColor: '#17a2b8',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

