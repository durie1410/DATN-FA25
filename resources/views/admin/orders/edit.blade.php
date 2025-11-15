@extends('layouts.admin')

@section('title', 'Chỉnh sửa đơn hàng')

@section('content')
<div style="background-color: #f5f5f5; min-height: 100vh; padding: 20px 40px;">
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

    <!-- Order Details Section -->
    <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h2 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 25px;">Thông tin đơn hàng</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Left Column -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Họ tên</div>
                    <div style="color: #333; font-size: 14px;">{{ $order->customer_name }}</div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Email</div>
                    <div style="color: #333; font-size: 14px;">{{ $order->customer_email }}</div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Phí vận chuyển</div>
                    <div style="color: #333; font-size: 14px;">{{ number_format($order->shipping_amount, 2, '.', '') }}₫</div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Phương thức vận chuyển</div>
                    <div style="color: #333; font-size: 14px;">Giao hàng tiêu chuẩn</div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Ghi chú</div>
                    <div style="color: #333; font-size: 14px;">{{ $order->notes ? $order->notes : 'Không có' }}</div>
                </div>
            </div>

            <!-- Right Column -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">SĐT</div>
                    <div style="color: #333; font-size: 14px;">{{ $order->customer_phone ?? 'N/A' }}</div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Địa chỉ giao hàng</div>
                    <div style="color: #333; font-size: 14px;">{{ $order->customer_address ?? 'N/A' }}</div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Phương thức thanh toán</div>
                    <div style="color: #333; font-size: 14px;">
                        @if($order->payment_method == 'cash_on_delivery')
                            Thanh toán khi nhận hàng (COD)
                        @elseif($order->payment_method == 'bank_transfer')
                            Chuyển khoản ngân hàng
                        @else
                            {{ $order->payment_method ?? 'N/A' }}
                        @endif
                    </div>
                </div>

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Trạng thái</div>
                    <div>
                        @if(in_array($order->status, ['pending']))
                            <span style="background-color: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Chờ xác nhận</span>
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
                </div>

                @if($order->status === 'cancelled' && $order->cancellation_reason)
                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Lý do hủy</div>
                    <div style="color: #721c24; background-color: #f8d7da; padding: 12px; border-radius: 4px; font-size: 13px;">
                        <i class="fas fa-info-circle"></i> {{ $order->cancellation_reason }}
                    </div>
                </div>
                @endif

                <div>
                    <div style="color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Trạng thái thanh toán</div>
                    <div>
                        @if($order->payment_status === 'paid')
                            <span style="background-color: #d4edda; color: #155724; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đã thanh toán</span>
                        @elseif($order->payment_status === 'pending')
                            <span style="background-color: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Chưa thanh toán</span>
                        @elseif($order->payment_status === 'failed')
                            <span style="background-color: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Thanh toán thất bại</span>
                        @elseif($order->payment_status === 'refunded')
                            <span style="background-color: #d1ecf1; color: #0c5460; padding: 4px 12px; border-radius: 12px; font-size: 13px;">Đã hoàn tiền</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Update Section -->
    <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 20px;">Thay đổi trạng thái đơn hàng</h2>
        
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="display: flex; gap: 15px; align-items: flex-end;">
            @csrf
            @method('PUT')
            
            <div style="flex: 1;">
                <label style="display: block; color: #666; font-weight: 500; margin-bottom: 8px; font-size: 14px;">Chọn trạng thái mới</label>
                <select name="status" id="order-status-select" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; outline: none; background-color: white;">
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
            
            <button type="submit" style="padding: 10px 24px; background-color: #0d6efd; color: white; border: none; border-radius: 4px; font-size: 14px; font-weight: 500; cursor: pointer; white-space: nowrap; transition: background-color 0.2s;">
                Cập nhật trạng thái
            </button>
        </form>
    </div>

    <!-- Product List Section -->
    <div style="background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 25px;">Sản phẩm</h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e9ecef; background-color: #f8f9fa;">
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Phân loại</th>
                    <th style="padding: 12px; text-align: right; color: #666; font-weight: 600; font-size: 14px;">Giá</th>
                    <th style="padding: 12px; text-align: center; color: #666; font-weight: 600; font-size: 14px;">Số lượng</th>
                    <th style="padding: 12px; text-align: right; color: #666; font-weight: 600; font-size: 14px;">Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px 12px; color: #333; font-size: 14px;">
                            {{ $item->book_title }}
                            @if($item->book_author)
                                <div style="color: #999; font-size: 13px; margin-top: 4px;">{{ $item->book_author }}</div>
                            @endif
                        </td>
                        <td style="padding: 15px 12px; text-align: right; color: #333; font-size: 14px;">{{ number_format($item->price, 2, '.', '') }}₫</td>
                        <td style="padding: 15px 12px; text-align: center; color: #333; font-size: 14px;">{{ $item->quantity }}</td>
                        <td style="padding: 15px 12px; text-align: right; color: #333; font-size: 14px; font-weight: 500;">{{ number_format($item->total_price, 0, '.', '.') }}₫</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Back Button -->
    <div style="margin-top: 30px;">
        <a href="{{ route('admin.orders.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background-color: #198754; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: 500; transition: background-color 0.2s;">
            <i class="fas fa-arrow-left"></i>
            Quay lại danh sách đơn hàng
        </a>
    </div>
</div>

<style>
    select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    button:hover {
        background-color: #0b5ed7 !important;
    }

    a:hover {
        background-color: #157347 !important;
        text-decoration: none !important;
    }

    table tbody tr:hover {
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }

        form[style*="display: flex"] {
            flex-direction: column !important;
        }

        button[type="submit"] {
            width: 100%;
        }
    }
</style>
@endsection

