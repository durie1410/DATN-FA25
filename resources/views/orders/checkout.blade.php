@extends('layouts.frontend')

@section('title', 'Thanh toán - Thư Viện Online')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-credit-card text-primary"></i> Thanh toán</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                        <li class="breadcrumb-item active">Thanh toán</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form id="checkoutForm">
        @csrf
        <div class="row">
            <!-- Thông tin khách hàng -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                           value="{{ auth()->user()->name ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                           value="{{ auth()->user()->email ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Phương thức thanh toán <span class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Chọn phương thức thanh toán</option>
                                        <option value="cash_on_delivery">Thanh toán khi nhận hàng</option>
                                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Địa chỉ giao hàng</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" 
                                      placeholder="Nhập địa chỉ chi tiết để giao hàng..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                      placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Thông tin thanh toán -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-gift"></i> Ưu đãi đặc biệt:</h6>
                            <ul class="mb-0">
                                <li><i class="fas fa-check text-success"></i> Miễn phí vận chuyển cho tất cả đơn hàng</li>
                                <li><i class="fas fa-check text-success"></i> Giao hàng ngay lập tức (sách điện tử)</li>
                                <li><i class="fas fa-check text-success"></i> Hỗ trợ khách hàng 24/7</li>
                            </ul>
                        </div>
                        
                        <div id="paymentInfo" class="mt-3" style="display: none;">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-bank"></i> Thông tin chuyển khoản:</h6>
                                <p class="mb-1"><strong>Ngân hàng:</strong> Vietcombank</p>
                                <p class="mb-1"><strong>Số tài khoản:</strong> 1234567890</p>
                                <p class="mb-1"><strong>Chủ tài khoản:</strong> Thư Viện Online</p>
                                <p class="mb-0"><strong>Nội dung:</strong> <span id="transferContent"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <!-- Danh sách sản phẩm -->
                        <div class="mb-3">
                            @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0">{{ $item->purchasableBook->ten_sach }}</h6>
                                    <small class="text-muted">{{ $item->purchasableBook->tac_gia }}</small>
                                    <br>
                                    <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">{{ number_format($item->total_price, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>
                            @if(!$loop->last)
                            <hr class="my-2">
                            @endif
                            @endforeach
                        </div>

                        <!-- Tổng kết -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($cart->total_amount, 0, ',', '.') }} VNĐ</span>
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
                                <strong class="text-primary">{{ number_format($cart->total_amount, 0, ',', '.') }} VNĐ</strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="placeOrderBtn">
                                <i class="fas fa-shopping-cart"></i> Đặt hàng
                            </button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentInfo = document.getElementById('paymentInfo');
    const transferContent = document.getElementById('transferContent');
    const orderToast = new bootstrap.Toast(document.getElementById('orderToast'));

    // Xử lý thay đổi phương thức thanh toán
    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'bank_transfer') {
            paymentInfo.style.display = 'block';
            transferContent.textContent = 'Thanh toan don hang - ' + new Date().toISOString().slice(0,10);
        } else {
            paymentInfo.style.display = 'none';
        }
    });

    // Xử lý submit form
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const button = placeOrderBtn;
        const originalText = button.innerHTML;
        
        // Hiển thị loading
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        button.disabled = true;
        
        // Lấy dữ liệu form
        const formData = new FormData(this);
        
        // Gửi request
        fetch('{{ route("orders.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => {
                    window.location.href = data.redirect_url;
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
});
</script>
@endpush

