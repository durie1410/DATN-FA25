@extends('layouts.admin')

@section('title', 'Thống kê nâng cao')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-chart-line me-2"></i>Thống kê nâng cao
                </h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="exportStats()">
                        <i class="fas fa-download me-1"></i>Xuất báo cáo
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="refreshStats()">
                        <i class="fas fa-sync-alt me-1"></i>Làm mới
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="period" class="form-label">Chu kỳ thống kê</label>
                            <select class="form-select" id="period" onchange="updateStats()">
                                <option value="week">Tuần</option>
                                <option value="month" selected>Tháng</option>
                                <option value="quarter">Quý</option>
                                <option value="year">Năm</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fromDate" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="fromDate" onchange="updateStats()">
                        </div>
                        <div class="col-md-3">
                            <label for="toDate" class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" id="toDate" onchange="updateStats()">
                        </div>
                        <div class="col-md-3">
                            <label for="months" class="form-label">Số tháng hiển thị</label>
                            <select class="form-select" id="months" onchange="updateTrends()">
                                <option value="6">6 tháng</option>
                                <option value="12" selected>12 tháng</option>
                                <option value="24">24 tháng</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng số sách
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalBooks">
                                {{ $stats['overview']['total_books'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng độc giả
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalReaders">
                                {{ $stats['overview']['total_readers'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đang mượn
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeBorrows">
                                {{ $stats['overview']['active_borrows'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Quá hạn
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="overdueBorrows">
                                {{ $stats['overview']['overdue_borrows'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Borrow Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Xu hướng mượn sách</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Tùy chọn:</div>
                            <a class="dropdown-item" href="#" onclick="updateTrends()">Làm mới</a>
                            <a class="dropdown-item" href="#" onclick="exportChart('borrowTrends')">Xuất biểu đồ</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="borrowTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Phân bố theo thể loại</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row mb-4">
        <!-- Popular Books -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sách được mượn nhiều nhất</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Lượt mượn</th>
                                    <th>Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody id="popularBooksTable">
                                @foreach($stats['popular_books'] as $book)
                                <tr>
                                    <td>{{ $book->ten_sach }}</td>
                                    <td>{{ $book->tac_gia }}</td>
                                    <td>{{ $book->borrows_count }}</td>
                                    <td>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $book->danh_gia_trung_binh)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Readers -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Độc giả tích cực</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tên độc giả</th>
                                    <th>Email</th>
                                    <th>Số thẻ</th>
                                    <th>Lượt mượn</th>
                                </tr>
                            </thead>
                            <tbody id="activeReadersTable">
                                @foreach($stats['active_readers'] as $reader)
                                <tr>
                                    <td>{{ $reader->ho_ten }}</td>
                                    <td>{{ $reader->email }}</td>
                                    <td>{{ $reader->so_the_doc_gia }}</td>
                                    <td>{{ $reader->borrows_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Analytics -->
    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê tìm kiếm</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="h3 text-primary">{{ $stats['search_analytics']['total_searches'] }}</div>
                                <div class="text-muted">Tổng lượt tìm kiếm</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="h3 text-success">{{ $stats['search_analytics']['popular_queries']->count() }}</div>
                                <div class="text-muted">Từ khóa phổ biến</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6>Từ khóa tìm kiếm phổ biến:</h6>
                    <div class="list-group">
                        @foreach($stats['search_analytics']['popular_queries'] as $query)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $query->query }}
                            <span class="badge bg-primary rounded-pill">{{ $query->search_count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Stats -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thống kê thông báo</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h3 text-info">{{ $stats['notification_stats']['total_sent'] }}</div>
                                <div class="text-muted">Đã gửi</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h3 text-success">{{ $stats['notification_stats']['delivery_rate'] }}%</div>
                                <div class="text-muted">Tỷ lệ gửi</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h3 text-warning">{{ $stats['notification_stats']['read_rate'] }}%</div>
                                <div class="text-muted">Tỷ lệ đọc</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <div class="mt-2">Đang tải dữ liệu...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let borrowTrendsChart, categoryChart;

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    setDefaultDates();
});

function initializeCharts() {
    // Borrow Trends Chart
    const borrowTrendsCtx = document.getElementById('borrowTrendsChart').getContext('2d');
    borrowTrendsChart = new Chart(borrowTrendsCtx, {
        type: 'line',
        data: {
            labels: @json($stats['trends']->pluck('month')),
            datasets: [{
                label: 'Lượt mượn',
                data: @json($stats['trends']->pluck('borrows')),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Khoa học', 'Văn học', 'Lịch sử', 'Kinh tế', 'Khác'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function setDefaultDates() {
    const today = new Date();
    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
    
    document.getElementById('fromDate').value = lastMonth.toISOString().split('T')[0];
    document.getElementById('toDate').value = today.toISOString().split('T')[0];
}

function updateStats() {
    showLoading();
    
    const period = document.getElementById('period').value;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    fetch(`/admin/statistics/advanced/overview?period=${period}&from_date=${fromDate}&to_date=${toDate}`)
        .then(response => response.json())
        .then(data => {
            updateOverviewCards(data);
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
        });
}

function updateTrends() {
    showLoading();
    
    const period = document.getElementById('period').value;
    const months = document.getElementById('months').value;
    
    fetch(`/admin/statistics/advanced/trends?period=${period}&months=${months}`)
        .then(response => response.json())
        .then(data => {
            updateTrendsChart(data.borrows);
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
        });
}

function updateOverviewCards(data) {
    document.getElementById('totalBooks').textContent = data.books.total;
    document.getElementById('totalReaders').textContent = data.readers.total;
    document.getElementById('activeBorrows').textContent = data.borrows.active;
    document.getElementById('overdueBorrows').textContent = data.borrows.overdue;
}

function updateTrendsChart(trendsData) {
    borrowTrendsChart.data.labels = trendsData.map(item => item.period);
    borrowTrendsChart.data.datasets[0].data = trendsData.map(item => item.count);
    borrowTrendsChart.update();
}

function refreshStats() {
    showLoading();
    
    // Refresh all statistics
    Promise.all([
        fetch('/admin/statistics/advanced/overview').then(r => r.json()),
        fetch('/admin/statistics/advanced/trends').then(r => r.json()),
        fetch('/admin/statistics/advanced/category-stats').then(r => r.json())
    ]).then(([overview, trends, categories]) => {
        updateOverviewCards(overview);
        updateTrendsChart(trends.borrows);
        updateCategoryChart(categories);
        hideLoading();
    }).catch(error => {
        console.error('Error:', error);
        hideLoading();
    });
}

function updateCategoryChart(categories) {
    const labels = categories.map(cat => cat.name);
    const data = categories.map(cat => cat.borrow_count);
    
    categoryChart.data.labels = labels;
    categoryChart.data.datasets[0].data = data;
    categoryChart.update();
}

function exportStats() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    window.open(`/admin/statistics/advanced/export?from_date=${fromDate}&to_date=${toDate}`, '_blank');
}

function exportChart(chartType) {
    // Export chart functionality
    console.log('Exporting chart:', chartType);
}

function showLoading() {
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();
}

function hideLoading() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
    if (modal) {
        modal.hide();
    }
}
</script>
@endsection

@section('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.chart-area {
    position: relative;
    height: 10rem;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 15rem;
    width: 100%;
}

.rating i {
    font-size: 0.8rem;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection
