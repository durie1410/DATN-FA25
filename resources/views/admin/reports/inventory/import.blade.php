@extends('layouts.admin')

@section('title', 'BC03 - Báo Cáo Số Lượng Sách Nhập')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-boxes" style="color: #22c55e;"></i>
            Báo Cáo Nhập Kho
        </h1>
        <p class="page-subtitle">Thống kê và báo cáo số lượng sách nhập kho</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('admin.inventory.receipts') }}" class="btn btn-secondary" style="background: white; color: #1f2937; border: 1px solid #e5e7eb; padding: 12px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Quay lại
        </a>
        <a href="{{ route('admin.inventory-reports.index') }}" class="btn btn-info" style="background: #06b6d4; color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-list"></i>
            Danh sách báo cáo
        </a>
    </div>
</div>

<div class="admin-table">
    <!-- Search and Filter -->
    <div class="card" style="margin-bottom: 25px; background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
        <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
            <h3 class="card-title" style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                <i class="fas fa-search" style="color: #22c55e; margin-right: 8px;"></i>
                Tìm kiếm & Lọc
            </h3>
        </div>
        <form method="GET" action="{{ route('admin.inventory-reports.import') }}" style="padding: 0 25px 25px 25px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}" style="width: 100%;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate }}" style="width: 100%;">
                </div>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-start;">
                <button type="submit" class="btn btn-primary" style="background: #22c55e; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-filter"></i>
                    Lọc
                </button>
                <a href="{{ route('admin.inventory-reports.import') }}" class="btn btn-secondary" style="background: white; color: #1f2937; border: 1px solid #e5e7eb; padding: 10px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-redo"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Thống kê tổng quan -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 25px;">
        <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; position: relative; min-height: 180px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <h6 style="font-size: 13px; font-weight: 700; color: #374151; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; line-height: 1.4;">TỔNG SỐ LƯỢNG</h6>
                <div style="width: 44px; height: 44px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-book" style="font-size: 22px; color: #3b82f6;"></i>
                </div>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <h3 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; line-height: 1.2;">{{ number_format($totalImported) }}</h3>
                <p style="font-size: 13px; color: #6b7280; margin: 0 0 12px 0; line-height: 1.4;">Sách đã nhập vào kho</p>
            </div>
            <div style="display: flex; align-items: center; gap: 6px; color: #22c55e; font-size: 12px; margin-top: auto;">
                <i class="fas fa-arrow-up"></i>
                <span>Hoạt động bình thường</span>
            </div>
        </div>
        <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; position: relative; min-height: 180px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <h6 style="font-size: 13px; font-weight: 700; color: #374151; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; line-height: 1.4;">TỔNG PHIẾU NHẬP</h6>
                <div style="width: 44px; height: 44px; background: #d1fae5; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-file-invoice" style="font-size: 22px; color: #22c55e;"></i>
                </div>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <h3 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; line-height: 1.2;">{{ number_format($totalReceipts) }}</h3>
                <p style="font-size: 13px; color: #6b7280; margin: 0 0 12px 0; line-height: 1.4;">Phiếu nhập trong hệ thống</p>
            </div>
            <div style="display: flex; align-items: center; gap: 6px; color: #22c55e; font-size: 12px; margin-top: auto;">
                <i class="fas fa-check"></i>
                <span>Hoạt động bình thường</span>
            </div>
        </div>
        <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; position: relative; min-height: 180px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <h6 style="font-size: 13px; font-weight: 700; color: #374151; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; line-height: 1.4;">TỔNG GIÁ TRỊ</h6>
                <div style="width: 44px; height: 44px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-dollar-sign" style="font-size: 22px; color: #06b6d4;"></i>
                </div>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <h3 style="font-size: 28px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; line-height: 1.2;">{{ number_format($totalValue, 0, ',', '.') }}</h3>
                <p style="font-size: 13px; color: #6b7280; margin: 0 0 12px 0; line-height: 1.4;">VNĐ giá trị nhập kho</p>
            </div>
            <div style="display: flex; align-items: center; gap: 6px; color: #06b6d4; font-size: 12px; margin-top: auto;">
                <i class="fas fa-info-circle"></i>
                <span>Tổng giá trị</span>
            </div>
        </div>
    </div>

    <!-- Phân tích theo nhà cung cấp -->
    <div class="row mb-4" style="margin-bottom: 25px;">
        <div class="col-md-6">
            <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
                <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
                    <h5 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                        <i class="fas fa-truck" style="color: #22c55e; margin-right: 8px;"></i> Phân tích theo nhà cung cấp
                    </h5>
                </div>
                <div class="card-body" style="padding: 25px;">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nhà cung cấp</th>
                                    <th>Số lượng</th>
                                    <th>Giá trị</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplierStats as $stat)
                                <tr>
                                    <td>{{ $stat->supplier ?? 'Không xác định' }}</td>
                                    <td><span class="badge bg-primary">{{ number_format($stat->total_quantity) }}</span></td>
                                    <td>{{ number_format($stat->total_value, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phân tích theo danh mục -->
        <div class="col-md-6">
            <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
                <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
                    <h5 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                        <i class="fas fa-folder" style="color: #22c55e; margin-right: 8px;"></i> Phân tích theo danh mục
                    </h5>
                </div>
                <div class="card-body" style="padding: 25px;">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th>Số lượng</th>
                                    <th>Giá trị</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $stat)
                                <tr>
                                    <td>{{ $stat['category']->ten_the_loai ?? 'Không xác định' }}</td>
                                    <td><span class="badge bg-success">{{ number_format($stat['quantity']) }}</span></td>
                                    <td>{{ number_format($stat['value'], 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân tích theo tác giả -->
    <div class="row mb-4" style="margin-bottom: 25px;">
        <div class="col-md-12">
            <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
                <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
                    <h5 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                        <i class="fas fa-user" style="color: #22c55e; margin-right: 8px;"></i> Phân tích theo tác giả (Top 20)
                    </h5>
                </div>
                <div class="card-body" style="padding: 25px;">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tác giả</th>
                                    <th>Số lượng</th>
                                    <th>Giá trị</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($authorStats as $stat)
                                <tr>
                                    <td>{{ $stat['author'] }}</td>
                                    <td><span class="badge bg-info">{{ number_format($stat['quantity']) }}</span></td>
                                    <td>{{ number_format($stat['value'], 0, ',', '.') }} VNĐ</td>
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
    <div class="row mb-4" style="margin-bottom: 25px;">
        <div class="col-md-12">
            <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
                <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
                    <h5 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                        <i class="fas fa-chart-line" style="color: #22c55e; margin-right: 8px;"></i> Thống kê theo tháng
                    </h5>
                </div>
                <div class="card-body" style="padding: 25px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết phiếu nhập -->
    <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
        <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
            <h5 style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
                <i class="fas fa-list" style="color: #22c55e; margin-right: 8px;"></i> Chi tiết phiếu nhập
            </h5>
        </div>
        <div class="card-body" style="padding: 25px;">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Số phiếu</th>
                            <th>Ngày nhập</th>
                            <th>Tên sách</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th>Nhà cung cấp</th>
                            <th>Người nhận</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $receipt)
                        <tr>
                            <td>{{ $receipt->receipt_number }}</td>
                            <td>{{ $receipt->receipt_date->format('d/m/Y') }}</td>
                            <td>{{ $receipt->book->ten_sach ?? 'N/A' }}</td>
                            <td><span class="badge bg-primary">{{ $receipt->quantity }}</span></td>
                            <td>{{ number_format($receipt->unit_price, 0, ',', '.') }} VNĐ</td>
                            <td><strong>{{ number_format($receipt->total_price, 0, ',', '.') }} VNĐ</strong></td>
                            <td>{{ $receipt->supplier ?? 'N/A' }}</td>
                            <td>{{ $receipt->receiver->name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $receipts->links() }}
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
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month_label),
            datasets: [{
                label: 'Số lượng sách nhập',
                data: monthlyData.map(item => item.quantity),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                yAxisID: 'y'
            }, {
                label: 'Giá trị (VNĐ)',
                data: monthlyData.map(item => item.value),
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                yAxisID: 'y1',
                type: 'line'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endsection

