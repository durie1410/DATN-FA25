@extends('layouts.admin')

@section('title', 'BC05 - Báo Cáo Tiền Phạt')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-money-bill-wave"></i> BC05 - Báo Cáo Tiền Phạt</h3>
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
            <form method="GET" action="{{ route('admin.inventory-reports.fine') }}">
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
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalFines, 0, ',', '.') }} VNĐ</h4>
                    <small>Tổng số tiền phạt</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalPaid, 0, ',', '.') }} VNĐ</h4>
                    <small>Đã thanh toán</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalPending, 0, ',', '.') }} VNĐ</h4>
                    <small>Chưa thanh toán</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($paymentRate, 2) }}%</h4>
                    <small>Tỷ lệ thanh toán</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo loại phạt -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-tags"></i> Phân tích theo loại phạt</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Loại phạt</th>
                                    <th>Số lượng</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($typeStats as $stat)
                                <tr>
                                    <td>{{ $stat->type_label }}</td>
                                    <td><span class="badge bg-primary">{{ $stat->count }}</span></td>
                                    <td><strong>{{ number_format($stat->total_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phân tích theo trạng thái thanh toán -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-check-circle"></i> Phân tích theo trạng thái thanh toán</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top độc giả bị phạt nhiều nhất -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-users"></i> Top độc giả bị phạt nhiều nhất</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Độc giả</th>
                                    <th>Số lần phạt</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReaders as $index => $reader)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $reader->reader->ho_ten ?? 'N/A' }}</td>
                                    <td><span class="badge bg-danger">{{ $reader->fine_count }}</span></td>
                                    <td><strong>{{ number_format($reader->total_amount, 0, ',', '.') }} VNĐ</strong></td>
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

    <!-- Chi tiết khoản phạt -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Chi tiết khoản phạt</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ngày tạo</th>
                            <th>Độc giả</th>
                            <th>Sách</th>
                            <th>Loại phạt</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày thanh toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fines as $fine)
                        <tr>
                            <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                            <td>{{ $fine->reader->ho_ten ?? 'N/A' }}</td>
                            <td>{{ $fine->borrow->book->ten_sach ?? 'N/A' }}</td>
                            <td>
                                @if($fine->type == 'late_return')
                                    <span class="badge bg-warning">Trả muộn</span>
                                @elseif($fine->type == 'damaged_book')
                                    <span class="badge bg-danger">Làm hỏng sách</span>
                                @elseif($fine->type == 'lost_book')
                                    <span class="badge bg-dark">Mất sách</span>
                                @else
                                    <span class="badge bg-secondary">Khác</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($fine->amount, 0, ',', '.') }} VNĐ</strong></td>
                            <td>
                                @if($fine->status == 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($fine->status == 'pending')
                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                @elseif($fine->status == 'waived')
                                    <span class="badge bg-info">Đã miễn giảm</span>
                                @else
                                    <span class="badge bg-secondary">Đã hủy</span>
                                @endif
                            </td>
                            <td>{{ $fine->paid_date ? $fine->paid_date->format('d/m/Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $fines->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ trạng thái thanh toán
    const statusData = @json($statusStats);
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.status_label),
            datasets: [{
                data: statusData.map(item => item.total_amount),
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
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
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_label),
            datasets: [{
                label: 'Tổng tiền phạt',
                data: monthlyData.map(item => item.total),
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Đã thanh toán',
                data: monthlyData.map(item => item.paid),
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Chưa thanh toán',
                data: monthlyData.map(item => item.pending),
                borderColor: 'rgb(255, 193, 7)',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
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

