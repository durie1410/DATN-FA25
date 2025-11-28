@extends('layouts.frontend')

@section('title', 'Chi tiết đơn hàng - Thư Viện Online')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-receipt text-primary"></i> Chi tiết đơn hàng</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
                        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Số đơn hàng:</strong> {{ $order->order_number }}</p>
                            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'delivered' ? 'success' : 'danger')) }}">
                                    @switch($order->status)
                                        @case('pending')
                                            Chờ xử lý
                                            @break
                                        @case('processing')
                                            Đang xử lý
                                            @break
                                        @case('shipped')
                                            Đã giao hàng
                                            @break
                                        @case('delivered')
                                            Đã hoàn thành
                                            @break
                                        @case('cancelled')
                                            Đã hủy
                                            @break
                                    @endswitch
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Thanh toán:</strong> 
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    @switch($order->payment_status)
                                        @case('pending')
                                            Chờ thanh toán
                                            @break
                                        @case('paid')
                                            Đã thanh toán
                                            @break
                                        @case('failed')
                                            Thanh toán thất bại
                                            @break
                                        @case('refunded')
                                            Đã hoàn tiền
                                            @break
                                    @endswitch
                                </span>
                            </p>
                            <p><strong>Phương thức:</strong> 
                                @switch($order->payment_method)
                                    @case('cash_on_delivery')
                                        Thanh toán khi nhận hàng
                                        @break
                                    @case('bank_transfer')
                                        Chuyển khoản ngân hàng
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin khách hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Họ và tên:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone ?? 'Chưa cập nhật' }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->customer_address ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->book_title }}</td>
                                    <td>{{ $item->book_author }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold">{{ number_format($item->total_price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tóm tắt thanh toán -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tóm tắt thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->subtotal, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Thuế:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-primary">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong>
                    </div>

                    @if($order->notes)
                    <div class="mt-3">
                        <h6>Ghi chú:</h6>
                        <p class="text-muted">{{ $order->notes }}</p>
                    </div>
                    @endif

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

            <!-- Thông tin hỗ trợ -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="fas fa-headset"></i> Hỗ trợ khách hàng</h6>
                    <p class="mb-2">Nếu bạn có bất kỳ câu hỏi nào về đơn hàng, vui lòng liên hệ:</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-phone text-primary"></i> 1900-1234</li>
                        <li><i class="fas fa-envelope text-primary"></i> support@thuvienonline.com</li>
                        <li><i class="fas fa-clock text-primary"></i> 24/7</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Đổi nền sang màu trắng */
    body {
        background-color: #ffffff !important;
    }
    
    .container {
        background-color: #ffffff !important;
    }
    
    /* Đảm bảo các card có nền trắng */
    .card {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #e0e0e0;
        color: #333 !important;
    }
    
    .card-body {
        background-color: #ffffff !important;
        color: #333 !important;
    }
    
    /* Đảm bảo text có màu tối để dễ đọc */
    h2, h5, p, strong, span {
        color: #333 !important;
    }
    
    .breadcrumb {
        background-color: transparent !important;
    }
    
    .breadcrumb-item a {
        color: #0d6efd !important;
    }
    
    .breadcrumb-item.active {
        color: #6c757d !important;
    }
    
    /* Đảm bảo table có nền trắng */
    .table {
        background-color: #ffffff !important;
        color: #333 !important;
    }
    
    .table thead {
        background-color: #f8f9fa !important;
    }
    
    .table tbody tr {
        background-color: #ffffff !important;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@endsection

