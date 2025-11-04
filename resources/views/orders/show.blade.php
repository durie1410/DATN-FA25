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
                        @if($order->canBeCancelled())
                        <button class="btn btn-outline-danger" onclick="cancelOrder({{ $order->id }})">
                            <i class="fas fa-times"></i> Hủy đơn hàng
                        </button>
                        @endif
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

<!-- Modal xác nhận hủy đơn hàng -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy đơn hàng <strong>{{ $order->order_number }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Lưu ý:</strong> Hành động này không thể hoàn tác.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times"></i> Xác nhận hủy
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast thông báo -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="orderToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="fas fa-shopping-cart text-success me-2"></i>
            <strong class="me-auto">Thông báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Nội dung thông báo sẽ được thêm vào đây -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentOrderId = null;
const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
const orderToast = new bootstrap.Toast(document.getElementById('orderToast'));

// Hàm hủy đơn hàng
function cancelOrder(orderId) {
    currentOrderId = orderId;
    cancelOrderModal.show();
}

// Xác nhận hủy đơn hàng
document.getElementById('confirmCancelBtn').addEventListener('click', function() {
    if (!currentOrderId) return;
    
    const button = this;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch(`/orders/${currentOrderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            cancelOrderModal.hide();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Hàm hiển thị toast
function showToast(type, message) {
    const toastElement = document.getElementById('orderToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    
    const toastHeader = toastElement.querySelector('.toast-header');
    const icon = toastHeader.querySelector('i');
    
    if (type === 'success') {
        icon.className = 'fas fa-check-circle text-success me-2';
        toastElement.classList.remove('bg-danger');
    } else {
        icon.className = 'fas fa-exclamation-circle text-danger me-2';
        toastElement.classList.add('bg-danger');
    }
    
    orderToast.show();
}
</script>
@endpush

