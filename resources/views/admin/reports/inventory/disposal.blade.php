@extends('layouts.admin')

@section('title', 'BC04 - Báo Cáo Số Lượng Sách Hủy')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-trash-alt"></i> BC04 - Báo Cáo Số Lượng Sách Hủy</h3>
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
            <form method="GET" action="{{ route('admin.inventory-reports.disposal') }}">
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
        <div class="col-md-12">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalDisposed) }}</h4>
                    <small>Tổng số lượng sách hủy</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo lý do hủy -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-exclamation-triangle"></i> Phân tích theo lý do hủy</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Lý do</th>
                                    <th>Số lượng</th>
                                    <th>Số bản</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reasonStats as $stat)
                                <tr>
                                    <td>{{ $stat->reason ?? 'Không xác định' }}</td>
                                    <td><span class="badge bg-danger">{{ $stat->count }}</span></td>
                                    <td><span class="badge bg-warning">{{ $stat->total_quantity }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phân tích theo trạng thái trước khi hủy -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Phân tích theo trạng thái trước khi hủy</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusBeforeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo danh mục -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-folder"></i> Phân tích theo danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th>Số giao dịch</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $stat)
                                <tr>
                                    <td>{{ $stat['category'] }}</td>
                                    <td><span class="badge bg-danger">{{ $stat['count'] }}</span></td>
                                    <td><span class="badge bg-warning">{{ $stat['quantity'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo tháng -->
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

    <!-- Chi tiết giao dịch hủy -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Chi tiết giao dịch hủy</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ngày hủy</th>
                            <th>Tên sách</th>
                            <th>Lý do</th>
                            <th>Trạng thái trước</th>
                            <th>Số lượng</th>
                            <th>Người thực hiện</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaction->inventory->book->ten_sach ?? 'N/A' }}</td>
                            <td>{{ $transaction->reason ?? 'Không xác định' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $transaction->status_before ?? 'N/A' }}</span>
                            </td>
                            <td><span class="badge bg-danger">{{ $transaction->quantity }}</span></td>
                            <td>{{ $transaction->performer->name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ trạng thái trước khi hủy
    const statusBeforeData = @json($statusBeforeStats);
    const statusBeforeCtx = document.getElementById('statusBeforeChart').getContext('2d');
    new Chart(statusBeforeCtx, {
        type: 'doughnut',
        data: {
            labels: statusBeforeData.map(item => item.status_before ?? 'Không xác định'),
            datasets: [{
                data: statusBeforeData.map(item => item.count),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Biểu đồ theo tháng
    const monthlyData = @json($monthlyStats);
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month_label),
            datasets: [{
                label: 'Số lượng sách hủy',
                data: monthlyData.map(item => item.quantity),
                backgroundColor: 'rgba(220, 53, 69, 0.8)'
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

