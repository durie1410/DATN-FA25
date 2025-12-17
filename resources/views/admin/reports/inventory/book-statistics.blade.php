@extends('layouts.admin')

@section('title', 'BC01 - Thống Kê Số Lượng Sách')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-chart-pie"></i> BC01 - Thống Kê Số Lượng Sách</h3>
        <a href="{{ route('admin.inventory-reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-filter"></i> Bộ lọc</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory-reports.book-statistics') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>Danh mục</label>
                        <select name="category_id" class="form-control">
                            <option value="">Tất cả</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->ten_the_loai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tác giả</label>
                        <input type="text" name="tac_gia" class="form-control" value="{{ request('tac_gia') }}" placeholder="Nhập tên tác giả">
                    </div>
                    <div class="col-md-2">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="Co san" {{ request('status') == 'Co san' ? 'selected' : '' }}>Có sẵn</option>
                            <option value="Dang muon" {{ request('status') == 'Dang muon' ? 'selected' : '' }}>Đang mượn</option>
                            <option value="Hong" {{ request('status') == 'Hong' ? 'selected' : '' }}>Hỏng</option>
                            <option value="Mat" {{ request('status') == 'Mat' ? 'selected' : '' }}>Mất</option>
                            <option value="Thanh ly" {{ request('status') == 'Thanh ly' ? 'selected' : '' }}>Thanh lý</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Tình trạng</label>
                        <select name="condition" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="Moi" {{ request('condition') == 'Moi' ? 'selected' : '' }}>Mới</option>
                            <option value="Tot" {{ request('condition') == 'Tot' ? 'selected' : '' }}>Tốt</option>
                            <option value="Trung binh" {{ request('condition') == 'Trung binh' ? 'selected' : '' }}>Trung bình</option>
                            <option value="Cu" {{ request('condition') == 'Cu' ? 'selected' : '' }}>Cũ</option>
                            <option value="Hong" {{ request('condition') == 'Hong' ? 'selected' : '' }}>Hỏng</option>
                        </select>
                    </div>
                    <div class="col-md-2">
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
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($totalBooks) }}</h4>
                    <small>Tổng số lượng sách</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($availableBooks) }}</h4>
                    <small>Sách có sẵn</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($borrowedBooks) }}</h4>
                    <small>Sách đang mượn</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($damagedBooks) }}</h4>
                    <small>Sách hỏng</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($lostBooks) }}</h4>
                    <small>Sách mất</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4>{{ number_format($disposedBooks) }}</h4>
                    <small>Sách thanh lý</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo danh mục -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-folder"></i> Thống kê theo danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th>Tổng số</th>
                                    <th>Có sẵn</th>
                                    <th>Đang mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $stat)
                                <tr>
                                    <td>{{ $stat->ten_the_loai }}</td>
                                    <td><span class="badge bg-primary">{{ $stat->books_count }}</span></td>
                                    <td><span class="badge bg-success">{{ $stat->available_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $stat->borrowed_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo tác giả -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user"></i> Thống kê theo tác giả (Top 20)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tác giả</th>
                                    <th>Tổng số</th>
                                    <th>Có sẵn</th>
                                    <th>Đang mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($authorStats as $stat)
                                <tr>
                                    <td>{{ $stat->tac_gia }}</td>
                                    <td><span class="badge bg-primary">{{ $stat->total }}</span></td>
                                    <td><span class="badge bg-success">{{ $stat->available_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $stat->borrowed_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo trạng thái và tình trạng -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Thống kê theo trạng thái</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-tags"></i> Thống kê theo tình trạng</h5>
                </div>
                <div class="card-body">
                    <canvas id="conditionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết sách -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Chi tiết sách trong kho</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mã vạch</th>
                            <th>Tên sách</th>
                            <th>Danh mục</th>
                            <th>Tác giả</th>
                            <th>Vị trí</th>
                            <th>Tình trạng</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->barcode }}</td>
                            <td>{{ $inventory->book->ten_sach }}</td>
                            <td>{{ $inventory->book->category->ten_the_loai ?? 'N/A' }}</td>
                            <td>{{ $inventory->book->tac_gia }}</td>
                            <td>{{ $inventory->location }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $inventory->condition }}</span>
                            </td>
                            <td>
                                @if($inventory->status == 'Co san')
                                    <span class="badge bg-success">{{ $inventory->status }}</span>
                                @elseif($inventory->status == 'Dang muon')
                                    <span class="badge bg-info">{{ $inventory->status }}</span>
                                @elseif($inventory->status == 'Hong')
                                    <span class="badge bg-warning">{{ $inventory->status }}</span>
                                @elseif($inventory->status == 'Mat')
                                    <span class="badge bg-danger">{{ $inventory->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $inventory->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $inventories->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ trạng thái
    const statusData = @json($statusStats);
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.status),
            datasets: [{
                data: statusData.map(item => item.count),
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Biểu đồ tình trạng
    const conditionData = @json($conditionStats);
    const conditionCtx = document.getElementById('conditionChart').getContext('2d');
    new Chart(conditionCtx, {
        type: 'bar',
        data: {
            labels: conditionData.map(item => item.condition),
            datasets: [{
                label: 'Số lượng',
                data: conditionData.map(item => item.count),
                backgroundColor: 'rgba(54, 162, 235, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection

