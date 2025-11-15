@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid" style="padding: 20px 40px; background-color: #f5f5f5; min-height: 100vh;">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header -->
    <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h1 style="font-size: 28px; font-weight: 600; color: #333; margin-bottom: 30px;">Danh Sách Đơn Hàng</h1>

        <!-- Status Tabs and Search -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <!-- Status Filter Tabs -->
            <div style="display: flex; gap: 0; flex-wrap: wrap; flex: 1; min-width: 0;">
                <a href="{{ route('admin.orders.index') }}" 
                   class="status-tab {{ !request('status') ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Tất cả
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
                   class="status-tab {{ request('status') == 'pending' ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Chờ xác nhận
                    @if($stats['pending'] > 0)
                        <span class="status-count">{{ $stats['pending'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
                   class="status-tab {{ in_array(request('status'), ['confirmed', 'processing']) ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Đã xác nhận
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}" 
                   class="status-tab {{ request('status') == 'preparing' ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Đang chuẩn bị hàng
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'packing']) }}" 
                   class="status-tab {{ request('status') == 'packing' ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Đang đóng gói
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'sent_to_post_office']) }}" 
                   class="status-tab {{ request('status') == 'sent_to_post_office' ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Đã gửi bưu cục
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" 
                   class="status-tab {{ in_array(request('status'), ['shipping', 'shipped']) ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Đang giao hàng
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
                   class="status-tab {{ request('status') == 'delivered' ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Đã giao thành công
                    @if($stats['delivered'] > 0)
                        <span class="status-count">{{ $stats['delivered'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivery_failed']) }}" 
                   class="status-tab {{ request('status') == 'delivery_failed' ? 'active' : '' }}"
                   style="text-decoration: none;">
                    Giao thất bại
                </a>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div style="position: relative;">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="search-input"
                           placeholder="Tìm kiếm đơn hàng"
                           style="padding: 10px 40px 10px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; width: 250px; outline: none;">
                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                </div>
                <button type="submit" style="display: none;"></button>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div style="background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        @if($orders->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Mã Đơn Hàng</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Người Đặt</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Tổng Tiền</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Ngày Đặt</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Phương Thức Thanh Toán</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Trạng Thái Thanh Toán</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Trạng Thái Đơn Hàng</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Lý do hủy</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333; font-size: 14px;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid #e9ecef; transition: background-color 0.2s;">
                                <td style="padding: 15px; color: #333; font-weight: 500; font-size: 14px;">{{ $order->order_number }}</td>
                                <td style="padding: 15px; color: #555; font-size: 14px;">{{ $order->customer_name }}</td>
                                <td style="padding: 15px; color: #555; font-size: 14px;">{{ number_format($order->total_amount, 2, '.', '') }}</td>
                                <td style="padding: 15px; color: #555; font-size: 14px;">{{ $order->created_at->format('H:i:s d/m/Y') }}</td>
                                <td style="padding: 15px; color: #555; font-size: 14px;">
                                    @if($order->payment_method == 'cash_on_delivery')
                                        Thanh toán khi nhận hàng (COD)
                                    @elseif($order->payment_method == 'bank_transfer')
                                        Chuyển khoản ngân hàng
                                    @else
                                        {{ $order->payment_method ?? 'N/A' }}
                                    @endif
                                </td>
                                <td style="padding: 15px;">
                                    @if($order->payment_status === 'paid')
                                        <span class="status-badge" style="background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đã thanh toán</span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="status-badge" style="background-color: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Chưa thanh toán</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="status-badge" style="background-color: #f8d7da; color: #721c24; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Thanh toán thất bại</span>
                                    @elseif($order->payment_status === 'refunded')
                                        <span class="status-badge" style="background-color: #d1ecf1; color: #0c5460; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đã hoàn tiền</span>
                                    @endif
                                </td>
                                <td style="padding: 15px;">
                                    @if(in_array($order->status, ['pending']))
                                        <span class="status-badge" style="background-color: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Chờ xác nhận</span>
                                    @elseif(in_array($order->status, ['confirmed', 'processing']))
                                        <span class="status-badge" style="background-color: #cfe2ff; color: #084298; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đã xác nhận</span>
                                    @elseif($order->status === 'preparing')
                                        <span class="status-badge" style="background-color: #d1ecf1; color: #055160; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đang chuẩn bị hàng</span>
                                    @elseif($order->status === 'packing')
                                        <span class="status-badge" style="background-color: #cfe2ff; color: #084298; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đang đóng gói</span>
                                    @elseif($order->status === 'sent_to_post_office')
                                        <span class="status-badge" style="background-color: #d1ecf1; color: #055160; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đã gửi bưu cục</span>
                                    @elseif(in_array($order->status, ['shipping', 'shipped']))
                                        <span class="status-badge" style="background-color: #cfe2ff; color: #084298; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đang giao hàng</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="status-badge" style="background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Đã giao thành công</span>
                                    @elseif($order->status === 'delivery_failed')
                                        <span class="status-badge" style="background-color: #f8d7da; color: #721c24; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">Giao thất bại</span>
                                    @else
                                        <span class="status-badge" style="background-color: #e2e3e5; color: #383d41; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td style="padding: 15px; color: #555; font-size: 14px;">
                                    @if(in_array($order->status, ['delivery_failed']) && $order->notes)
                                        {{ $order->notes }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="padding: 15px;">
                                    <div style="display: flex; gap: 8px;">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="action-btn detail-btn"
                                           style="background-color: #17a2b8; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                                            <i class="fas fa-eye"></i>
                                            CHI TIẾT
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                           class="action-btn edit-btn"
                                           style="background-color: #ff9800; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                                            <i class="fas fa-edit"></i>
                                            SỬA
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="padding: 20px; border-top: 1px solid #e9ecef; display: flex; justify-content: center;">
                {{ $orders->appends(request()->query())->links('vendor.pagination.admin') }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-shopping-cart" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                <h5 style="color: #666; margin-bottom: 10px;">Chưa có đơn hàng nào</h5>
                <p style="color: #999;">Tất cả đơn hàng sẽ hiển thị ở đây.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .status-tab {
        padding: 10px 20px;
        color: #666;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        white-space: nowrap;
        position: relative;
    }

    .status-tab:hover {
        color: #333;
        background-color: #f8f9fa;
    }

    .status-tab.active {
        color: #dc2626;
        border-bottom-color: #dc2626;
        font-weight: 600;
    }

    .status-count {
        display: inline-block;
        background-color: #dc2626;
        color: white;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 12px;
        margin-left: 6px;
        font-weight: 600;
    }

    .search-input:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
    }

    table tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    .action-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .detail-btn:hover {
        background-color: #138496 !important;
    }

    .edit-btn:hover {
        background-color: #e68900 !important;
    }

    @media (max-width: 1200px) {
        .container-fluid {
            padding: 15px 20px;
        }
        
        .status-tab {
            padding: 8px 15px;
            font-size: 13px;
        }

        table {
            font-size: 13px;
        }

        th, td {
            padding: 10px 8px !important;
        }

        .action-btn {
            padding: 6px 12px !important;
            font-size: 12px !important;
        }
    }
</style>

<script>
    // Auto submit search on Enter
    document.querySelector('.search-input')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
</script>
@endsection
