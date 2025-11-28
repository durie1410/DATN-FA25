@extends('layouts.admin')

@section('title', 'BC02 - Báo Cáo Số Lượng Sách Trả Và Mượn')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-exchange-alt"></i> BC02 - Báo Cáo Số Lượng Sách Trả Và Mượn</h3>
        <a href="{{ route('admin.inventory-reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter"></i> Bộ lọc thời gian</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory-reports.borrow-return') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label>Từ ngày</label>
                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                    </div>
                    <div class="col-md-4">
                        <label>Đến ngày</label>
                        <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalBorrowed) }}</h4>
                    <small>Tổng số sách đã mượn</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalReturned) }}</h4>
                    <small>Tổng số sách đã trả</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($borrowReturnRatio, 2) }}%</h4>
                    <small>Tỷ lệ trả/mượn</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ theo tháng -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line"></i> Thống kê theo tháng</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ theo tuần -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Thống kê theo tuần</h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ theo ngày -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-area"></i> Thống kê theo ngày</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top sách được mượn nhiều nhất -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-book"></i> Top sách được mượn nhiều nhất</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sách</th>
                            <th>Số lượt mượn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topBorrowedBooks as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->book->ten_sach ?? 'N/A' }}</td>
                            <td><span class="badge bg-primary">{{ $item->borrow_count }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ theo tháng
    const monthlyData = @json($monthlyStats);
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_label),
            datasets: [{
                label: 'Số sách mượn',
                data: monthlyData.map(item => item.borrowed),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Số sách trả',
                data: monthlyData.map(item => item.returned),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ theo tuần
    const weeklyData = @json($weeklyStats);
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: weeklyData.map(item => item.week_label),
            datasets: [{
                label: 'Số sách mượn',
                data: weeklyData.map(item => item.borrowed),
                backgroundColor: 'rgba(54, 162, 235, 0.8)'
            }, {
                label: 'Số sách trả',
                data: weeklyData.map(item => item.returned),
                backgroundColor: 'rgba(255, 99, 132, 0.8)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ theo ngày (chỉ hiển thị 30 ngày gần nhất để tránh quá dài)
    const dailyData = @json($dailyStats);
    const recentDailyData = dailyData.slice(-30); // Lấy 30 ngày gần nhất
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: recentDailyData.map(item => item.date_label),
            datasets: [{
                label: 'Số sách mượn',
                data: recentDailyData.map(item => item.borrowed),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Số sách trả',
                data: recentDailyData.map(item => item.returned),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

