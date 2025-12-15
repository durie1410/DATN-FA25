@extends('layouts.admin')

@section('title', 'Dashboard Mượn Sách - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-chart-line"></i> Dashboard Mượn Sách</h3>
        <div>
            <form action="{{ route('admin.borrows.dashboard') }}" method="GET" class="d-inline">
                <select name="date_range" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>3 tháng qua</option>
                    <option value="365" {{ $dateRange == '365' ? 'selected' : '' }}>1 năm qua</option>
                </select>
            </form>
            <a href="{{ route('admin.borrows.dashboard.export', ['date_range' => $dateRange]) }}" class="btn btn-success ms-2">
                <i class="fas fa-download"></i> Xuất báo cáo
            </a>
        </div>
    </div>

    {{-- Thống kê tổng quan --}}
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_borrows'] }}</h4>
                    <p class="mb-0">Tổng mượn</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['active_borrows'] }}</h4>
                    <p class="mb-0">Đang mượn</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['returned_books'] }}</h4>
                    <p class="mb-0">Đã trả</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['overdue_books'] }}</h4>
                    <p class="mb-0">Quá hạn</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_readers'] }}</h4>
                    <p class="mb-0">Độc giả</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total_books'] }}</h4>
                    <p class="mb-0">Tổng sách</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Biểu đồ thống kê theo thời gian --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-line"></i> Thống kê mượn/trả theo thời gian</h5>
                </div>
                <div class="card-body">
                    <canvas id="timeChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Thống kê theo trạng thái --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-pie-chart"></i> Phân bố theo trạng thái</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        {{-- Top sách được mượn nhiều nhất --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-book"></i> Top sách được mượn nhiều nhất</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Số lần mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBooks as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item['book']->ten_sach }}</td>
                                    <td>{{ $item['book']->tac_gia }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item['count'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top tác giả mượn nhiều nhất --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-users"></i> Top tác giả mượn nhiều nhất</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên tác giả</th>
                                    <th>Mã thẻ</th>
                                    <th>Số lần mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReaders as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item['reader']->ho_ten }}</td>
                                    <td>{{ $item['reader']->so_the_doc_gia }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $item['count'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Thống kê quá hạn --}}
    @if($overdueStats['count'] > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5><i class="fas fa-exclamation-triangle"></i> Sách quá hạn ({{ $overdueStats['count'] }} cuốn)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Độc giả</th>
                                    <th>Sách</th>
                                    <th>Hạn trả</th>
                                    <th>Số ngày quá hạn</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueStats['borrows'] as $borrow)
                                <tr>
                                    <td>{{ $borrow->id }}</td>
                                    <td>{{ $borrow->reader->ho_ten }}</td>
                                    <td>{{ $borrow->book->ten_sach }}</td>
                                    <td>{{ $borrow->ngay_hen_tra->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ now()->diffInDays($borrow->ngay_hen_tra) }} ngày
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.borrows.show', $borrow->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($overdueStats['count'] > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.borrows.index', ['trang_thai' => 'Dang muon']) }}" class="btn btn-outline-danger">
                            Xem tất cả sách quá hạn
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ thống kê theo thời gian
    const timeCtx = document.getElementById('timeChart').getContext('2d');
    const timeChart = new Chart(timeCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($timeStats['borrows']->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d/m'); })) !!},
            datasets: [{
                label: 'Mượn sách',
                data: {!! json_encode($timeStats['borrows']->pluck('count')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Trả sách',
                data: {!! json_encode($timeStats['returns']->pluck('count')) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1
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

    // Biểu đồ tròn trạng thái
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Đang mượn', 'Đã trả', 'Quá hạn', 'Mất sách'],
            datasets: [{
                data: [
                    {{ $statusStats['dang_muon'] }},
                    {{ $statusStats['da_tra'] }},
                    {{ $statusStats['qua_han'] }},
                    {{ $statusStats['mat_sach'] }}
                ],
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#dc3545',
                    '#ffc107'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush

