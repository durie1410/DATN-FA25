@extends('layouts.admin')

@section('title', 'Báo cáo phí phạt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        Báo cáo phí phạt
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Bộ lọc báo cáo -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Từ ngày:</label>
                                    <input type="date" name="from_date" class="form-control" 
                                           value="{{ request('from_date', date('Y-m-01')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Đến ngày:</label>
                                    <input type="date" name="to_date" class="form-control" 
                                           value="{{ request('to_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Lọc báo cáo
                                        </button>
                                        <a href="{{ route('admin.fines.report') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Xóa lọc
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng số phạt</span>
                                    <span class="info-box-number">{{ $stats['total_fines'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng tiền</span>
                                    <span class="info-box-number">{{ number_format($stats['total_amount'], 0, ',', '.') }} VND</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Chưa thu</span>
                                    <span class="info-box-number">{{ number_format($stats['pending_amount'], 0, ',', '.') }} VND</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Quá hạn</span>
                                    <span class="info-box-number">{{ $stats['overdue_count'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ thống kê -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Phân bố theo trạng thái</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="statusChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Phân bố theo loại phạt</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="typeChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng chi tiết -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chi tiết phạt</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Độc giả</th>
                                            <th>Sách</th>
                                            <th>Loại phạt</th>
                                            <th>Số tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Hạn thanh toán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($fines as $fine)
                                            <tr class="{{ $fine->isOverdue() ? 'table-danger' : '' }}">
                                                <td>{{ $fine->id }}</td>
                                                <td>
                                                    <strong>{{ $fine->reader->ho_ten }}</strong><br>
                                                    <small class="text-muted">{{ $fine->reader->ma_so_the }}</small>
                                                </td>
                                                <td>
                                                    @if($fine->borrow && $fine->borrow->book)
                                                        <strong>{{ $fine->borrow->book->ten_sach }}</strong><br>
                                                        <small class="text-muted">{{ $fine->borrow->book->ma_sach }}</small>
                                                    @else
                                                        <span class="text-muted">Không có thông tin</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($fine->type)
                                                        @case('late_return')
                                                            <span class="badge badge-warning">Trả muộn</span>
                                                            @break
                                                        @case('damaged_book')
                                                            <span class="badge badge-danger">Làm hỏng</span>
                                                            @break
                                                        @case('lost_book')
                                                            <span class="badge badge-dark">Mất sách</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary">Khác</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <strong class="text-danger">{{ number_format($fine->amount, 0, ',', '.') }} VND</strong>
                                                </td>
                                                <td>
                                                    @switch($fine->status)
                                                        @case('pending')
                                                            <span class="badge badge-warning">Chưa thanh toán</span>
                                                            @if($fine->isOverdue())
                                                                <br><small class="text-danger">Quá hạn {{ $fine->days_overdue }} ngày</small>
                                                            @endif
                                                            @break
                                                        @case('paid')
                                                            <span class="badge badge-success">Đã thanh toán</span>
                                                            @if($fine->paid_date)
                                                                <br><small class="text-muted">{{ $fine->paid_date->format('d/m/Y') }}</small>
                                                            @endif
                                                            @break
                                                        @case('waived')
                                                            <span class="badge badge-info">Đã miễn</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge badge-secondary">Đã hủy</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    {{ $fine->due_date->format('d/m/Y') }}
                                                    @if($fine->isOverdue())
                                                        <br><small class="text-danger">Quá hạn</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                    Không có dữ liệu phạt trong khoảng thời gian này
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ trạng thái
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Chưa thanh toán', 'Đã thanh toán', 'Đã miễn', 'Đã hủy'],
            datasets: [{
                data: [
                    {{ $fines->where('status', 'pending')->count() }},
                    {{ $fines->where('status', 'paid')->count() }},
                    {{ $fines->where('status', 'waived')->count() }},
                    {{ $fines->where('status', 'cancelled')->count() }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#28a745',
                    '#17a2b8',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Biểu đồ loại phạt
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: ['Trả muộn', 'Làm hỏng sách', 'Mất sách', 'Khác'],
            datasets: [{
                label: 'Số lượng',
                data: [
                    {{ $fines->where('type', 'late_return')->count() }},
                    {{ $fines->where('type', 'damaged_book')->count() }},
                    {{ $fines->where('type', 'lost_book')->count() }},
                    {{ $fines->where('type', 'other')->count() }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#dc3545',
                    '#343a40',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
