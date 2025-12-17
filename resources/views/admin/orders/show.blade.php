@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div style="background-color: #f5f5f5; min-height: 100vh; padding: 20px 40px;">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 600; color: #333; margin-bottom: 0;">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">
        <!-- Left Column: Order and Customer Information -->
        <div style="flex: 1; min-width: 400px;">
            <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 25px;">Thông tin đơn hàng</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px 15px; align-items: start;">
                    <div style="color: #666; font-weight: 500;">Họ tên:</div>
                    <div style="color: #333;">{{ $order->customer_name }}</div>

                    <div style="color: #666; font-weight: 500;">SĐT:</div>
                    <div style="color: #333;">{{ $order->customer_phone ?? 'N/A' }}</div>

                    <div style="color: #666; font-weight: 500;">Email:</div>
                    <div style="color: #333;">{{ $order->customer_email }}</div>

                    <div style="color: #666; font-weight: 500;">Địa chỉ giao hàng:</div>
                    <div style="color: #333;">{{ $order->customer_address ?? 'N/A' }}</div>

                    <div style="color: #666; font-weight: 500;">Phí vận chuyển:</div>
                    <div style="color: #333;">{{ number_format($order->shipping_amount, 2, '.', '') }}₫</div>

                    <div style="color: #666; font-weight: 500;">Phương thức thanh toán:</div>
                    <div style="color: #333;">
                        @if($order->payment_method == 'cash_on_delivery')
                            Thanh toán khi nhận hàng (COD)
                        @elseif($order->payment_method == 'bank_transfer')
                            Chuyển khoản ngân hàng
                        @else
                            {{ $order->payment_method ?? 'N/A' }}
                        @endif
                    </div>

                    <div style="color: #666; font-weight: 500;">Phương thức vận chuyển:</div>
                    <div style="color: #333;">Giao hàng tiêu chuẩn</div>

                    <div style="color: #666; font-weight: 500;">Trạng thái:</div>
                    <div style="color: #333;">
                        @if(in_array($order->status, ['pending']))
                            <span style="background-color: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đang chờ duyệt</span>
                        @elseif(in_array($order->status, ['confirmed', 'processing']))
                            <span style="background-color: #cfe2ff; color: #084298; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đã xác nhận</span>
                        @elseif($order->status === 'preparing')
                            <span style="background-color: #d1ecf1; color: #055160; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đang chuẩn bị hàng</span>
                        @elseif($order->status === 'packing')
                            <span style="background-color: #cfe2ff; color: #084298; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đang đóng gói</span>
                        @elseif($order->status === 'sent_to_post_office')
                            <span style="background-color: #d1ecf1; color: #055160; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đã gửi bưu cục</span>
                        @elseif(in_array($order->status, ['shipping', 'shipped']))
                            <span style="background-color: #cfe2ff; color: #084298; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đang giao hàng</span>
                        @elseif($order->status === 'delivered')
                            <span style="background-color: #d4edda; color: #155724; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đã giao thành công</span>
                        @elseif($order->status === 'delivery_failed')
                            <span style="background-color: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Giao thất bại</span>
                        @elseif($order->status === 'cancelled')
                            <span style="background-color: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đã hủy</span>
                        @else
                            <span style="background-color: #e2e3e5; color: #383d41; padding: 4px 12px; border-radius: 12px; font-size: 13px;">{{ $order->status }}</span>
                        @endif
                    </div>

                    @if($order->status === 'cancelled' && $order->cancellation_reason)
                    <div style="color: #666; font-weight: 500;">Lý do hủy:</div>
                    <div style="color: #721c24; background-color: #f8d7da; padding: 12px; border-radius: 4px; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> {{ $order->cancellation_reason }}
                    </div>
                    @endif

                    <div style="color: #666; font-weight: 500;">Ghi chú:</div>
                    <div style="color: #333;">{{ $order->notes ? $order->notes : 'Không có' }}</div>
                </div>
            </div>

            <!-- Back Link -->
            <div style="margin-top: 20px;">
                <a href="{{ route('admin.orders.index') }}" style="color: #17a2b8; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại danh sách đơn hàng
                </a>
            </div>
        </div>

        <!-- Right Column: Products and Summary -->
        <div style="flex: 1; min-width: 400px;">
            <!-- Product List -->
            <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 25px;">Sản phẩm trong đơn hàng</h2>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e9ecef;">
                            <th style="padding: 12px 0; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Sản phẩm</th>
                            <th style="padding: 12px 0; text-align: center; color: #666; font-weight: 600; font-size: 14px;">Số lượng</th>
                            <th style="padding: 12px 0; text-align: right; color: #666; font-weight: 600; font-size: 14px;">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 15px 0; color: #333; font-size: 14px;">
                                    {{ $item->book_title }}
                                    @if($item->book_author)
                                        <div style="color: #999; font-size: 13px; margin-top: 4px;">{{ $item->book_author }}</div>
                                    @endif
                                </td>
                                <td style="padding: 15px 0; text-align: center; color: #333; font-size: 14px;">{{ $item->quantity }}</td>
                                <td style="padding: 15px 0; text-align: right; color: #333; font-size: 14px; font-weight: 500;">{{ number_format($item->total_price, 0, '.', '.') }}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Order Summary -->
            <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 25px;">Tóm tắt đơn hàng</h2>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
                    <span style="color: #666; font-size: 14px;">Tạm tính:</span>
                    <span style="color: #333; font-size: 14px; font-weight: 500;">{{ number_format($order->subtotal, 0, '.', '.') }}₫</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
                    <span style="color: #666; font-size: 14px;">Phí vận chuyển:</span>
                    <span style="color: #333; font-size: 14px; font-weight: 500;">{{ number_format($order->shipping_amount, 2, '.', '') }}₫</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
                    <span style="color: #666; font-size: 14px;">Giảm:</span>
                    <span style="color: #333; font-size: 14px; font-weight: 500;">- 0₫</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0 0 0; margin-top: 10px; border-top: 2px solid #333;">
                    <span style="color: #333; font-size: 18px; font-weight: 600;">Tổng cộng:</span>
                    <span style="color: #333; font-size: 20px; font-weight: 700;">{{ number_format($order->total_amount, 0, '.', '.') }}₫</span>
                </div>
            </div>

            <!-- Order Management (if edit mode) -->
            @if(request('edit'))
            <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 20px;">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 25px;">Quản lý đơn hàng</h2>
                
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Trạng thái đơn hàng</label>
                        <select name="status" id="order-status-select" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; outline: none;">
                            @php
                                // Xác định trạng thái tiếp theo dựa trên trạng thái hiện tại
                                $currentStatus = $order->status;
                                $nextStatus = null;
                                $nextStatusLabel = null;
                                
                                // Workflow: pending -> confirmed -> preparing -> packing -> sent_to_post_office -> shipping -> delivered/delivery_failed
                                if (in_array($currentStatus, ['pending'])) {
                                    $nextStatus = 'confirmed';
                                    $nextStatusLabel = 'Đã xác nhận';
                                } elseif (in_array($currentStatus, ['confirmed', 'processing'])) {
                                    $nextStatus = 'preparing';
                                    $nextStatusLabel = 'Đang chuẩn bị hàng';
                                } elseif ($currentStatus === 'preparing') {
                                    $nextStatus = 'packing';
                                    $nextStatusLabel = 'Đang đóng gói';
                                } elseif ($currentStatus === 'packing') {
                                    $nextStatus = 'sent_to_post_office';
                                    $nextStatusLabel = 'Đã gửi bưu cục';
                                } elseif ($currentStatus === 'sent_to_post_office') {
                                    $nextStatus = 'shipping';
                                    $nextStatusLabel = 'Đang giao hàng';
                                } elseif (in_array($currentStatus, ['shipping', 'shipped'])) {
                                    // Khi đang giao hàng, có thể chuyển sang giao thành công hoặc thất bại
                                    $nextStatus = 'delivered';
                                    $nextStatusLabel = 'Đã giao thành công';
                                }
                            @endphp
                            
                            @if($nextStatus)
                                <option value="{{ $nextStatus }}" selected>{{ $nextStatusLabel }}</option>
                                @if(in_array($currentStatus, ['shipping', 'shipped']))
                                    <option value="delivery_failed">Giao thất bại</option>
                                @endif
                            @else
                                <option value="{{ $currentStatus }}" disabled selected>Đơn hàng đã hoàn thành</option>
                            @endif
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Trạng thái thanh toán</label>
                        <select name="payment_status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; outline: none;">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                    </div>

                    <button type="submit" style="width: 100%; padding: 12px; background-color: #17a2b8; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;">
                        <i class="fas fa-save"></i> Cập nhật trạng thái
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    select:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
    }

    button:hover {
        background-color: #138496 !important;
    }

    a:hover {
        text-decoration: underline !important;
    }

    @media (max-width: 768px) {
        div[style*="display: flex"] {
            flex-direction: column !important;
        }
        
        div[style*="min-width: 400px"] {
            min-width: 100% !important;
        }

        div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
