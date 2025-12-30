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

    <!-- Status tabs - 11 TRẠNG THÁI MỚI -->
    <div class="status-tabs">
        <a href="{{ route('admin.shipping_logs.index', ['status' => 'all']) }}" 
           class="status-tab {{ request('status', 'all') === 'all' ? 'active' : '' }}">
            Tất cả
            @if(($statusCounts['all'] ?? 0) > 0)
                <span class="badge bg-secondary">{{ $statusCounts['all'] }}</span>
            @endif
        </a>
        
        @php
            $statusConfig = config('borrow_status.statuses');
        @endphp
        
        @foreach($statusConfig as $statusKey => $statusInfo)
            <a href="{{ route('admin.shipping_logs.index', ['status' => $statusKey]) }}" 
               class="status-tab {{ request('status') === $statusKey ? 'active' : '' }}">
                <i class="{{ $statusInfo['icon'] }}" style="font-size: 12px;"></i>
                {{ $statusInfo['label'] }}
                @if(($statusCounts[$statusKey] ?? 0) > 0)
                    <span class="badge bg-{{ $statusInfo['color'] }}">{{ $statusCounts[$statusKey] }}</span>
                @endif
            </a>
        @endforeach
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
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    {{-- Mã đơn hàng --}}
                    <td><strong>ORD-{{ str_pad($log->borrow->id ?? $log->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                    
                    {{-- Người đặt --}}
                    <td>
                        @if($log->borrow)
                            @if($log->borrow->reader)
                                {{ $log->borrow->reader->ho_ten }}
                            @elseif($log->borrow->ten_nguoi_muon)
                                {{ $log->borrow->ten_nguoi_muon }}
                            @else
                                —
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    
                    {{-- Tổng tiền --}}
                    <td>{{ number_format($log->borrow->tong_tien ?? 0, 0) }}</td>
                    
                    {{-- Ngày đặt --}}
                    <td>
                        <small>{{ $log->created_at->format('H:i:s d/m/Y') }}</small>
                    </td>
                    
                    {{-- Phương thức thanh toán --}}
                    <td>
                        @if($log->borrow && $log->borrow->payments->count() > 0)
                            @php
                                $firstPayment = $log->borrow->payments->first();
                                $paymentMethod = $firstPayment->payment_method === 'online' 
                                    ? 'Online (VNPay/MoMo)' 
                                    : 'Thanh toán khi nhận hàng (COD)';
                            @endphp
                            {{ $paymentMethod }}
                        @else
                            Thanh toán khi nhận hàng (COD)
                        @endif
                    </td>
                    
                    {{-- Trạng thái thanh toán --}}
                    <td>
                        @php
                            $isPaid = false;
                            if($log->borrow && $log->borrow->payments->count() > 0) {
                                // Kiểm tra tất cả các khoản thanh toán
                                $allPaid = $log->borrow->payments
                                    ->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee'])
                                    ->every(function($payment) {
                                        return $payment->payment_status === 'success';
                                    });
                                $isPaid = $allPaid && $log->borrow->payments->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee'])->count() > 0;
                            }
                        @endphp
                        <span class="status-badge {{ $isPaid ? 'status-paid' : 'status-unpaid' }}">
                            {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </td>
                    
                    {{-- Trạng thái đơn hàng - 11 TRẠNG THÁI MỚI --}}
                    <td>
                        @php
                            $statusInfo = config('borrow_status.statuses.' . $log->status);
                            if (!$statusInfo) {
                                // Fallback nếu không tìm thấy trong config
                                $statusInfo = ['label' => $log->status, 'color' => 'secondary', 'icon' => 'fa-question'];
                            }
                        @endphp
                        <span class="badge badge-{{ $statusInfo['color'] }}" style="font-size: 13px; padding: 8px 16px;">
                            {{ $statusInfo['label'] }}
                        </span>
                    </td>
                    
                    {{-- Hành động --}}
                    <td>
                        <a href="{{ route('admin.shipping_logs.show', $log->id) }}" 
                           class="btn btn-action btn-detail">
                            <i class="bi bi-eye"></i> CHI TIẾT
                        </a>
                        <a href="{{ route('admin.shipping_logs.edit', $log->id) }}" 
                           class="btn btn-action btn-edit">
                            <i class="bi bi-pencil"></i> SỬA
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
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
