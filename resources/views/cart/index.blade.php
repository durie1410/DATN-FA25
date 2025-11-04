@extends('layouts.app')

@section('title', 'Giỏ hàng')

@push('styles')
<link href="{{ asset('css/cart.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="cart-container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cart-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="cart-title">
                            <i class="fas fa-shopping-cart"></i> 
                            Giỏ hàng của bạn
                        </h2>
                        <div class="cart-actions">
                            <button class="btn btn-outline-danger" id="clearCartBtn">
                                <i class="fas fa-trash"></i> Xóa tất cả
                            </button>
                        </div>
                    </div>
                </div>

            @if($cartItems->count() > 0)
                <div class="row">
                    <!-- Danh sách sản phẩm -->
                    <div class="col-lg-8">
                        <div class="cart-items-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table cart-table">
                                        <thead>
                                            <tr>
                                                <th width="100">Hình ảnh</th>
                                                <th>Tên sách</th>
                                                <th width="120">Giá</th>
                                                <th width="150">Số lượng</th>
                                                <th width="120">Thành tiền</th>
                                                <th width="100">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cartItems as $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <td data-label="Hình ảnh">
                                                        @if($item->purchasableBook->hinh_anh)
                                                            <img src="{{ asset('storage/' . $item->purchasableBook->hinh_anh) }}" 
                                                                 alt="{{ $item->purchasableBook->ten_sach }}" 
                                                                 class="product-image">
                                                        @else
                                                            <div class="product-image-placeholder">
                                                                <i class="fas fa-book"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td data-label="Tên sách">
                                                        <div class="product-info">
                                                            <h6>{{ $item->purchasableBook->ten_sach }}</h6>
                                                            <small>{{ $item->purchasableBook->tac_gia }}</small>
                                                        </div>
                                                    </td>
                                                    <td data-label="Giá">
                                                        <span class="price">
                                                            {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                                        </span>
                                                    </td>
                                                    <td data-label="Số lượng">
                                                        <div class="quantity-controls">
                                                            <button class="quantity-btn" 
                                                                    data-action="decrease" 
                                                                    data-item-id="{{ $item->id }}"
                                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" 
                                                                   class="quantity-input" 
                                                                   value="{{ $item->quantity }}" 
                                                                   min="1" 
                                                                   max="10"
                                                                   data-item-id="{{ $item->id }}">
                                                            <button class="quantity-btn" 
                                                                    data-action="increase" 
                                                                    data-item-id="{{ $item->id }}"
                                                                    {{ $item->quantity >= 10 ? 'disabled' : '' }}>
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td data-label="Thành tiền">
                                                        <span class="item-total">
                                                            {{ number_format($item->total_price, 0, ',', '.') }} VNĐ
                                                        </span>
                                                    </td>
                                                    <td data-label="Thao tác">
                                                        <button class="remove-btn remove-item-btn" 
                                                                data-item-id="{{ $item->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tổng kết -->
                    <div class="col-lg-4">
                        <div class="cart-summary-card">
                            <div class="cart-summary-header">
                                <h5>Tổng kết đơn hàng</h5>
                            </div>
                            <div class="cart-summary-body">
                                <div class="summary-row">
                                    <span class="summary-label">Số sản phẩm:</span>
                                    <span class="summary-value" id="total-items">{{ $cart->total_items }}</span>
                                </div>
                                <div class="summary-row">
                                    <span class="summary-label">Tạm tính:</span>
                                    <span class="summary-value" id="subtotal">{{ number_format($cart->total_amount, 0, ',', '.') }} VNĐ</span>
                                </div>
                                <div class="summary-total">
                                    <div class="summary-row">
                                        <span class="summary-label">Tổng cộng:</span>
                                        <span class="summary-value" id="total-amount">{{ number_format($cart->total_amount, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                                
                                <div class="cart-actions-grid">
                                    <a href="{{ route('checkout') }}" class="btn-checkout">
                                        <i class="fas fa-credit-card"></i> Thanh toán
                                    </a>
                                    <a href="{{ route('purchasable-books.index') }}" class="btn-continue">
                                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin hỗ trợ -->
                        <div class="support-card">
                            <div class="card-body">
                                <h6><i class="fas fa-info-circle"></i> Thông tin hỗ trợ</h6>
                                <ul class="support-list">
                                    <li><i class="fas fa-check"></i> Miễn phí vận chuyển</li>
                                    <li><i class="fas fa-check"></i> Giao hàng trong 24h</li>
                                    <li><i class="fas fa-check"></i> Hỗ trợ 24/7</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Giỏ hàng trống -->
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h4>Giỏ hàng của bạn đang trống</h4>
                    <p>Hãy thêm một số sách vào giỏ hàng để bắt đầu mua sắm!</p>
                    <a href="{{ route('purchasable-books.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sách này khỏi giỏ hàng?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa tất cả -->
<div class="modal fade" id="confirmClearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa tất cả</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tất cả sách khỏi giỏ hàng?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmClearBtn">Xóa tất cả</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const confirmClearModal = new bootstrap.Modal(document.getElementById('confirmClearModal'));
    let itemToDelete = null;

    // Xử lý thay đổi số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.dataset.itemId;
            const quantity = parseInt(this.value);
            
            if (quantity >= 1 && quantity <= 10) {
                updateQuantity(itemId, quantity);
            } else {
                this.value = 1;
            }
        });
    });

    // Xử lý nút tăng/giảm số lượng
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`input[data-item-id="${itemId}"]`);
            let quantity = parseInt(input.value);

            if (action === 'increase' && quantity < 10) {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            input.value = quantity;
            updateQuantity(itemId, quantity);
        });
    });

    // Xử lý xóa item
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            itemToDelete = this.dataset.itemId;
            confirmDeleteModal.show();
        });
    });

    // Xử lý xóa tất cả
    document.getElementById('clearCartBtn').addEventListener('click', function() {
        confirmClearModal.show();
    });

    // Xác nhận xóa item
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (itemToDelete) {
            removeItem(itemToDelete);
            confirmDeleteModal.hide();
        }
    });

    // Xác nhận xóa tất cả
    document.getElementById('confirmClearBtn').addEventListener('click', function() {
        clearCart();
        confirmClearModal.hide();
    });

    // Hàm cập nhật số lượng
    function updateQuantity(itemId, quantity) {
        fetch(`/cart/update/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật UI
                const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
                const totalElement = row.querySelector('.item-total');
                totalElement.textContent = data.total_price;

                // Cập nhật tổng
                document.getElementById('total-items').textContent = data.cart_count;
                
                // Reload trang để cập nhật tổng tiền
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật số lượng');
        });
    }

    // Hàm xóa item
    function removeItem(itemId) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Xóa row khỏi table
                const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
                row.remove();

                // Cập nhật tổng
                document.getElementById('total-items').textContent = data.cart_count;

                // Kiểm tra nếu giỏ hàng trống
                if (data.cart_count == 0) {
                    location.reload();
                } else {
                    location.reload();
                }
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }

    // Hàm xóa tất cả
    function clearCart() {
        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa giỏ hàng');
        });
    }
});
</script>
@endpush

