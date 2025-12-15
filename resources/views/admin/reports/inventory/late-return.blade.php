@extends('layouts.admin')

@section('title', 'BC06 - Báo Cáo Sách Trả Muộn')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-clock"></i> BC06 - Báo Cáo Sách Trả Muộn</h3>
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
            <form method="GET" action="{{ route('admin.inventory-reports.late-return') }}">
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
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalLateReturns) }}</h4>
                    <small>Tổng số sách trả muộn</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($currentOverdue) }}</h4>
                    <small>Sách đang quá hạn chưa trả</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo độc giả -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i> Phân tích theo độc giả (Top 20)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Độc giả</th>
                                    <th>Số lần trả muộn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($readerStats as $stat)
                                <tr>
                                    <td>{{ $stat->reader->ho_ten ?? 'N/A' }}</td>
                                    <td><span class="badge bg-danger">{{ $stat->late_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phân tích theo phòng mượn -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-building"></i> Phân tích theo phòng mượn</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Phòng mượn</th>
                                    <th>Số lần trả muộn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentStats as $stat)
                                <tr>
                                    <td>{{ $stat['department'] }}</td>
                                    <td><span class="badge bg-warning">{{ $stat['count'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo số ngày quá hạn -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt"></i> Phân tích theo số ngày quá hạn</h5>
                </div>
                <div class="card-body">
                    <canvas id="overdueDaysChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Thống kê theo tháng -->
        <div class="col-md-6">
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

    <!-- Chi tiết sách trả muộn -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Chi tiết sách trả muộn</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ngày mượn</th>
                            <th>Ngày hẹn trả</th>
                            <th>Ngày trả thực tế</th>
                            <th>Độc giả</th>
                            <th>Phòng mượn</th>
                            <th>Sách</th>
                            <th>Số ngày quá hạn</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lateReturns as $borrow)
                        @php
                            $dueDate = \Carbon\Carbon::parse($borrow->ngay_hen_tra);
                            $returnDate = $borrow->ngay_tra_thuc_te ? \Carbon\Carbon::parse($borrow->ngay_tra_thuc_te) : now();
                            $overdueDays = max(0, $dueDate->diffInDays($returnDate, false));
                        @endphp
                        <tr>
                            <td>{{ $borrow->ngay_muon->format('d/m/Y') }}</td>
                            <td>{{ $borrow->ngay_hen_tra->format('d/m/Y') }}</td>
                            <td>{{ $borrow->ngay_tra_thuc_te ? $borrow->ngay_tra_thuc_te->format('d/m/Y') : 'Chưa trả' }}</td>
                            <td>{{ $borrow->reader->ho_ten ?? 'N/A' }}</td>
                            <td>{{ $borrow->reader->department->ten_phong_ban ?? 'N/A' }}</td>
                            <td>{{ $borrow->book->ten_sach ?? 'N/A' }}</td>
                            <td>
                                @if($overdueDays > 0)
                                    <span class="badge bg-danger">{{ $overdueDays }} ngày</span>
                                @else
                                    <span class="badge bg-success">0 ngày</span>
                                @endif
                            </td>
                            <td>
                                @if($borrow->trang_thai == 'Qua han')
                                    <span class="badge bg-danger">Quá hạn</span>
                                @elseif($borrow->trang_thai == 'Da tra')
                                    <span class="badge bg-warning">Đã trả (muộn)</span>
                                @else
                                    <span class="badge bg-secondary">{{ $borrow->trang_thai }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $lateReturns->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ số ngày quá hạn
    const overdueDaysData = @json($overdueDaysStats);
    const overdueDaysCtx = document.getElementById('overdueDaysChart').getContext('2d');
    new Chart(overdueDaysCtx, {
        type: 'bar',
        data: {
            labels: overdueDaysData.map(item => item.range),
            datasets: [{
                label: 'Số lượng',
                data: overdueDaysData.map(item => item.count),
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(255, 152, 0, 0.8)',
                    'rgba(255, 87, 34, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ]
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

    // Biểu đồ theo tháng
    const monthlyData = @json($monthlyStats);
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_label),
            datasets: [{
                label: 'Số sách trả muộn',
                data: monthlyData.map(item => item.count),
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
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

