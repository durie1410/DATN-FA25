@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<style>
    /* Thanh search */
    .search-bar {
        max-width: 400px;
        margin-bottom: 20px;
    }

    /* Status tabs */
    .status-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e9ecef;
    }

    .status-tab {
        padding: 8px 16px;
        background: transparent;
        border: none;
        color: #6c757d;
        cursor: pointer;
        position: relative;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .status-tab:hover {
        color: #0d6efd;
    }

    .status-tab.active {
        color: #dc3545;
        font-weight: 600;
    }

    .status-tab.active::after {
        content: '';
        position: absolute;
        bottom: -17px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dc3545;
    }

    .status-tab .badge {
        margin-left: 5px;
        font-size: 0.75rem;
    }

    /* Table styles */
    .table-responsive {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 15px 12px;
        vertical-align: middle;
    }

    .table tbody td {
        padding: 15px 12px;
        vertical-align: middle;
    }

    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-paid {
        background-color: #d4edda;
        color: #155724;
    }

    .status-unpaid {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-cho-xu-ly {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-dang-giao {
        background-color: #cfe2ff;
        color: #084298;
    }

    .status-da-giao,
    .status-da-giao-thanh-cong {
        background-color: #d4edda;
        color: #155724;
    }

    .status-da-huy {
        background-color: #f8d7da;
        color: #842029;
    }

    .status-giao-that-bai {
        background-color: #f8d7da;
        color: #842029;
    }

    .status-tra-lai,
    .status-dang-gui-lai {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-da-nhan-hang {
        background-color: #d4edda;
        color: #155724;
    }

    .status-dang-kiem-tra {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .status-thanh-toan-coc {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-hoan-thanh {
        background-color: #d4edda;
        color: #155724;
    }

    /* Action buttons */
    .btn-action {
        padding: 6px 12px;
        font-size: 0.875rem;
        border-radius: 5px;
        margin: 0 3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-detail {
        background-color: #17a2b8;
        color: white;
        border: none;
    }

    .btn-detail:hover {
        background-color: #138496;
        color: white;
    }

    .btn-edit {
        background-color: #fd7e14;
        color: white;
        border: none;
    }

    .btn-edit:hover {
        background-color: #e66c0b;
        color: white;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
</style>

<div class="container-fluid py-4">
    
    <div class="page-header">
        <h3 class="mb-0">
            <i class="bi bi-box-seam me-2"></i>Quản lý Đơn hàng
        </h3>
        <!-- Search bar -->
        <div class="search-bar">
            <form action="{{ route('admin.shipping_logs.index') }}" method="GET" class="d-flex">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Tìm kiếm đơn hàng">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status tabs -->
    <div class="status-tabs">
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'all']) }}" 
           class="status-tab {{ request('status', 'all') === 'all' ? 'active' : '' }}">
            Tất cả
            @if(($statusCounts['all'] ?? 0) > 0)
                <span class="badge bg-secondary">{{ $statusCounts['all'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'cho_xu_ly']) }}" 
           class="status-tab {{ request('status') === 'cho_xu_ly' ? 'active' : '' }}">
            Chờ xử lý
            @if(($statusCounts['cho_xu_ly'] ?? 0) > 0)
                <span class="badge bg-warning">{{ $statusCounts['cho_xu_ly'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'dang_chuan_bi']) }}" 
           class="status-tab {{ request('status') === 'dang_chuan_bi' ? 'active' : '' }}">
            Đang chuẩn bị
            @if(($statusCounts['dang_chuan_bi'] ?? 0) > 0)
                <span class="badge bg-info">{{ $statusCounts['dang_chuan_bi'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'dang_giao']) }}" 
           class="status-tab {{ request('status') === 'dang_giao' ? 'active' : '' }}">
            Đang giao
            @if(($statusCounts['dang_giao'] ?? 0) > 0)
                <span class="badge bg-primary">{{ $statusCounts['dang_giao'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'da_giao_thanh_cong']) }}" 
           class="status-tab {{ request('status') === 'da_giao_thanh_cong' ? 'active' : '' }}">
            Đã giao thành công
            @if(($statusCounts['da_giao_thanh_cong'] ?? 0) > 0)
                <span class="badge bg-success">{{ $statusCounts['da_giao_thanh_cong'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'giao_that_bai']) }}" 
           class="status-tab {{ request('status') === 'giao_that_bai' ? 'active' : '' }}">
            Giao thất bại
            @if(($statusCounts['giao_that_bai'] ?? 0) > 0)
                <span class="badge bg-danger">{{ $statusCounts['giao_that_bai'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'tra_lai_sach']) }}" 
           class="status-tab {{ request('status') === 'tra_lai_sach' ? 'active' : '' }}">
            Trả lại sách
            @if(($statusCounts['tra_lai_sach'] ?? 0) > 0)
                <span class="badge bg-info">{{ $statusCounts['tra_lai_sach'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'dang_gui_lai']) }}" 
           class="status-tab {{ request('status') === 'dang_gui_lai' ? 'active' : '' }}">
            Đang gửi lại
            @if(($statusCounts['dang_gui_lai'] ?? 0) > 0)
                <span class="badge bg-info">{{ $statusCounts['dang_gui_lai'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'da_nhan_hang']) }}" 
           class="status-tab {{ request('status') === 'da_nhan_hang' ? 'active' : '' }}">
            Đã nhận hàng
            @if(($statusCounts['da_nhan_hang'] ?? 0) > 0)
                <span class="badge bg-success">{{ $statusCounts['da_nhan_hang'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'dang_kiem_tra']) }}" 
           class="status-tab {{ request('status') === 'dang_kiem_tra' ? 'active' : '' }}">
            Đang kiểm tra
            @if(($statusCounts['dang_kiem_tra'] ?? 0) > 0)
                <span class="badge bg-secondary">{{ $statusCounts['dang_kiem_tra'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'thanh_toan_coc']) }}" 
           class="status-tab {{ request('status') === 'thanh_toan_coc' ? 'active' : '' }}">
            Thanh toán cọc
            @if(($statusCounts['thanh_toan_coc'] ?? 0) > 0)
                <span class="badge bg-warning">{{ $statusCounts['thanh_toan_coc'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'hoan_thanh']) }}" 
           class="status-tab {{ request('status') === 'hoan_thanh' ? 'active' : '' }}">
            Hoàn thành
            @if(($statusCounts['hoan_thanh'] ?? 0) > 0)
                <span class="badge bg-success">{{ $statusCounts['hoan_thanh'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'da_huy']) }}" 
           class="status-tab {{ request('status') === 'da_huy' ? 'active' : '' }}">
            Đã hủy
            @if(($statusCounts['da_huy'] ?? 0) > 0)
                <span class="badge bg-danger">{{ $statusCounts['da_huy'] }}</span>
            @endif
        </a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Người Đặt</th>
                    <th>Tổng Tiền</th>
                    <th>Ngày Đặt</th>
                    <th>Phương Thức Thanh Toán</th>
                    <th>Trạng Thái Thanh Toán</th>
                    <th>Trạng Thái Đơn Hàng</th>
                    <th>Lý do hủy</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    {{-- Mã đơn hàng --}}
                    <td><strong>ORD-{{ str_pad($log->borrow->id ?? $log->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                    
                    {{-- Người đặt --}}
                    <td>{{ $log->borrow->reader->name ?? '—' }}</td>
                    
                    {{-- Tổng tiền --}}
                    <td>{{ number_format($log->borrow->tong_tien ?? 0, 2) }}</td>
                    
                    {{-- Ngày đặt --}}
                    <td>
                        <small>{{ $log->created_at->format('H:i:s d/m/Y') }}</small>
                    </td>
                    
                    {{-- Phương thức thanh toán --}}
                    <td>
                        @if($log->borrow && $log->borrow->payments->count() > 0)
                            @php
                                $payment = $log->borrow->payments->first();
                                $paymentMethodMap = [
                                    'tien_mat' => 'Tiền mặt',
                                    'chuyen_khoan' => 'Chuyển khoản',
                                    'vnpay' => 'VNPay',
                                    'momo' => 'MoMo',
                                    'cod' => 'Thanh toán khi nhận hàng (COD)'
                                ];
                            @endphp
                            {{ $paymentMethodMap[$payment->phuong_thuc ?? 'cod'] ?? 'COD' }}
                        @else
                            Thanh toán khi nhận hàng (COD)
                        @endif
                    </td>
                    
                    {{-- Trạng thái thanh toán --}}
                    <td>
                        @php
                            $isPaid = false;
                            if($log->borrow && $log->borrow->payments->count() > 0) {
                                $payment = $log->borrow->payments->first();
                                $isPaid = ($payment->trang_thai === 'thanh_cong' || $payment->trang_thai === 'hoan_thanh');
                            }
                        @endphp
                        <span class="status-badge {{ $isPaid ? 'status-paid' : 'status-unpaid' }}">
                            {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </td>
                    
                    {{-- Trạng thái đơn hàng --}}
                    <td>
                        @php
                            $statusMap = [
                                'cho_xu_ly' => ['label' => 'Chờ xử lý', 'class' => 'status-cho-xu-ly'],
                                'dang_chuan_bi' => ['label' => 'Đang chuẩn bị', 'class' => 'status-cho-xu-ly'],
                                'dang_giao' => ['label' => 'Đang giao', 'class' => 'status-dang-giao'],
                                'da_giao_thanh_cong' => ['label' => 'Đã giao thành công', 'class' => 'status-da-giao-thanh-cong'],
                                'giao_that_bai' => ['label' => 'Giao thất bại', 'class' => 'status-giao-that-bai'],
                                'tra_lai_sach' => ['label' => 'Trả lại sách', 'class' => 'status-tra-lai'],
                                'dang_gui_lai' => ['label' => 'Đang gửi lại', 'class' => 'status-dang-gui-lai'],
                                'da_nhan_hang' => ['label' => 'Đã nhận hàng', 'class' => 'status-da-nhan-hang'],
                                'dang_kiem_tra' => ['label' => 'Đang kiểm tra', 'class' => 'status-dang-kiem-tra'],
                                'thanh_toan_coc' => ['label' => 'Thanh toán cọc', 'class' => 'status-thanh-toan-coc'],
                                'hoan_thanh' => ['label' => 'Hoàn thành', 'class' => 'status-hoan-thanh'],
                                'da_huy' => ['label' => 'Đã hủy', 'class' => 'status-da-huy'],
                            ];
                            $statusInfo = $statusMap[$log->status] ?? ['label' => $log->status, 'class' => 'status-cho-xu-ly'];
                        @endphp
                        <span class="status-badge {{ $statusInfo['class'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                    </td>
                    
                    {{-- Lý do hủy --}}
                    <td>
                        @if(in_array($log->status, ['da_huy', 'giao_that_bai', 'khong_nhan']))
                            {{ $log->receiver_note ?? $log->shipper_note ?? '—' }}
                        @else
                            —
                        @endif
                    </td>
                    
                    {{-- Hành động --}}
                    <td>
                        <a href="{{ route('admin.shipping_logs.show', $log->id) }}" 
                           class="btn btn-action btn-detail">
                            <i class="bi bi-eye"></i> CHI TIẾT
                        </a>
                        <a href="{{ route('admin.shipping_logs.show', $log->id) }}" 
                           class="btn btn-action btn-edit">
                            <i class="bi bi-pencil"></i> SỬA
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                        <p class="mt-2 text-muted">Không có đơn hàng nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        @if($logs->hasPages())
        <div class="p-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
