@extends('layouts.frontend')

@section('title', 'Sách có thể mua - Thư Viện Online')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Sách có thể mua</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-shopping-cart text-primary"></i> Sách có thể mua</h2>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên sách, tác giả...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá từ</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá đến</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="1000000">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Định dạng</label>
                            <select name="format" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="PDF" {{ request('format') == 'PDF' ? 'selected' : '' }}>PDF</option>
                                <option value="EPUB" {{ request('format') == 'EPUB' ? 'selected' : '' }}>EPUB</option>
                                <option value="MOBI" {{ request('format') == 'MOBI' ? 'selected' : '' }}>MOBI</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sắp xếp</label>
                            <select name="sort_by" class="form-select">
                                <option value="sales" {{ request('sort_by') == 'sales' ? 'selected' : '' }}>Bán chạy</option>
                                <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá</option>
                                <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Lượt xem</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('purchasable-books.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sách -->
    <div class="row">
        @forelse($books as $book)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="position-relative">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                             class="card-img-top" 
                             alt="{{ $book->ten_sach }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-book fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary">{{ $book->dinh_dang }}</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ Str::limit($book->ten_sach, 50) }}</h6>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-user"></i> {{ $book->tac_gia }}
                    </p>
                    
                    <!-- Rating -->
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($book->danh_gia_trung_binh))
                                <i class="fas fa-star text-warning"></i>
                            @elseif($i - 0.5 <= $book->danh_gia_trung_binh)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                        <small class="text-muted ms-1">({{ $book->danh_gia_trung_binh }})</small>
                    </div>

                    <!-- Stats -->
                    <div class="small text-muted mb-2">
                        <i class="fas fa-shopping-cart"></i> {{ $book->so_luong_ban }} lượt mua
                        <span class="ms-2">
                            <i class="fas fa-eye"></i> {{ $book->so_luot_xem }} lượt xem
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="mt-auto">
                        <h5 class="text-primary mb-3">{{ number_format($book->gia, 0, ',', '.') }} VNĐ</h5>
                        
                        <div class="d-grid gap-2">
                            @if(in_array($book->id, $purchasedBookIds))
                                <button class="btn btn-success btn-sm" disabled>
                                    <i class="fas fa-check"></i> Đã mua
                                </button>
                            @else
                                <!-- Add to Cart Button -->
                                <button class="btn btn-success btn-sm add-to-cart-btn" 
                                        data-book-id="{{ $book->id }}"
                                        data-book-title="{{ $book->ten_sach }}"
                                        data-book-price="{{ $book->gia }}">
                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                </button>
                                
                                <!-- View Details Button -->
                                <button class="btn btn-outline-primary btn-sm buy-details-btn" 
                                        data-book-id="{{ $book->id }}" 
                                        data-book-title="{{ $book->ten_sach }}"
                                        data-book-description="{{ $book->mo_ta }}"
                                        data-book-price="{{ $book->gia }}"
                                        data-book-author="{{ $book->tac_gia }}"
                                        data-book-publisher="{{ $book->nha_xuat_ban }}"
                                        data-book-year="{{ $book->nam_xuat_ban }}"
                                        data-book-pages="{{ $book->so_trang }}"
                                        data-book-format="{{ $book->dinh_dang }}"
                                        data-book-size="{{ $book->formatted_file_size }}">
                                    <i class="fas fa-info-circle"></i> Xem chi tiết
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-book fa-3x mb-3"></i>
                    <h5>Không tìm thấy sách nào</h5>
                    <p>Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Phân trang -->
    @if($books->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $books->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal chi tiết sách -->
<div class="modal fade" id="bookDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="book-image-large text-center">
                            <img id="detailBookImage" src="" alt="" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 id="detailBookTitle"></h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user"></i> <strong>Tác giả:</strong> <span id="detailBookAuthor"></span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-building"></i> <strong>Nhà xuất bản:</strong> <span id="detailBookPublisher"></span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar"></i> <strong>Năm xuất bản:</strong> <span id="detailBookYear"></span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-file"></i> <strong>Số trang:</strong> <span id="detailBookPages"></span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-file-alt"></i> <strong>Định dạng:</strong> <span id="detailBookFormat"></span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-weight"></i> <strong>Kích thước:</strong> <span id="detailBookSize"></span>
                        </p>
                        <div class="mb-3">
                            <h5 class="text-primary" id="detailBookPrice"></h5>
                        </div>
                        <div class="mb-3">
                            <h6>Mô tả:</h6>
                            <p id="detailBookDescription" class="text-muted"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success" id="addToCartFromDetails">
                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                </button>
                <button type="button" class="btn btn-primary" id="buyFromDetails">
                    <i class="fas fa-credit-card"></i> Mua ngay
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal mua sách -->
<div class="modal fade" id="buyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mua sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn mua sách <strong id="buyBookTitle"></strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Thông tin mua sách:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Sau khi mua, bạn sẽ sở hữu sách vĩnh viễn</li>
                        <li>Có thể đọc offline mọi lúc</li>
                        <li>Không giới hạn thời gian sử dụng</li>
                        <li>Hỗ trợ đọc trên nhiều thiết bị</li>
                    </ul>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phương thức thanh toán:</label>
                    <select id="paymentMethod" class="form-control">
                        <option value="credit_card">Thẻ tín dụng</option>
                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                        <option value="momo">Ví MoMo</option>
                        <option value="zalopay">Ví ZaloPay</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmBuy">
                    <i class="fas fa-shopping-cart"></i> Xác nhận mua
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentBookId = null;
    const buyModal = new bootstrap.Modal(document.getElementById('buyModal'));
    const bookDetailsModal = new bootstrap.Modal(document.getElementById('bookDetailsModal'));

    // Xử lý click nút "Thêm vào giỏ hàng"
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.bookId;
            const bookTitle = this.dataset.bookTitle;
            const bookPrice = this.dataset.bookPrice;
            const btn = this;
            const originalHTML = btn.innerHTML;
            
            // Hiển thị loading
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            btn.disabled = true;
            
            // Gửi request thêm vào giỏ hàng
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    book_id: bookId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', `Đã thêm "${bookTitle}" vào giỏ hàng!`);
                    
                    // Cập nhật số lượng giỏ hàng trong header
                    const cartBadge = document.getElementById('cartCount');
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
                        cartBadge.style.display = 'flex';
                    }
                    
                    // Hiệu ứng success
                    btn.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                    
                    // Reset sau 2 giây
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('btn-outline-success');
                        btn.classList.add('btn-success');
                        btn.disabled = false;
                    }, 2000);
                } else {
                    showToast('danger', data.message || 'Không thể thêm vào giỏ hàng');
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('danger', 'Có lỗi xảy ra. Vui lòng thử lại!');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            });
        });
    });

    // Xử lý click nút xem chi tiết
    document.querySelectorAll('.buy-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.bookId;
            const bookTitle = this.dataset.bookTitle;
            const bookDescription = this.dataset.bookDescription;
            const bookPrice = this.dataset.bookPrice;
            const bookAuthor = this.dataset.bookAuthor;
            const bookPublisher = this.dataset.bookPublisher;
            const bookYear = this.dataset.bookYear;
            const bookPages = this.dataset.bookPages;
            const bookFormat = this.dataset.bookFormat;
            const bookSize = this.dataset.bookSize;
            
            // Cập nhật modal chi tiết sách mua
            document.getElementById('detailBookTitle').textContent = bookTitle;
            document.getElementById('detailBookAuthor').textContent = bookAuthor;
            document.getElementById('detailBookPublisher').textContent = bookPublisher || 'Chưa cập nhật';
            document.getElementById('detailBookYear').textContent = bookYear || 'Chưa cập nhật';
            document.getElementById('detailBookPages').textContent = bookPages ? bookPages + ' trang' : 'Chưa cập nhật';
            document.getElementById('detailBookFormat').textContent = bookFormat || 'PDF';
            document.getElementById('detailBookSize').textContent = bookSize || 'Chưa cập nhật';
            document.getElementById('detailBookPrice').textContent = new Intl.NumberFormat('vi-VN').format(bookPrice) + ' VNĐ';
            document.getElementById('detailBookDescription').textContent = bookDescription || 'Chưa có mô tả';
            
            // Lưu thông tin để mua sách
            currentBookId = bookId;
            
            // Hiển thị modal chi tiết trước
            bookDetailsModal.show();
        });
    });

    // Xử lý click nút "Thêm vào giỏ hàng" từ modal chi tiết
    document.getElementById('addToCartFromDetails').addEventListener('click', function() {
        const btn = this;
        const originalHTML = btn.innerHTML;
        
        // Hiển thị loading
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
        btn.disabled = true;
        
        // Gửi request thêm vào giỏ hàng
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_id: currentBookId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Đã thêm sách vào giỏ hàng!');
                
                // Cập nhật số lượng giỏ hàng
                const cartBadge = document.getElementById('cartCount');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count;
                    cartBadge.style.display = 'flex';
                }
                
                // Đóng modal
                bookDetailsModal.hide();
            } else {
                showToast('danger', data.message || 'Không thể thêm vào giỏ hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('danger', 'Có lỗi xảy ra. Vui lòng thử lại!');
        })
        .finally(() => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        });
    });

    // Xử lý click nút mua sách từ modal chi tiết
    document.getElementById('buyFromDetails').addEventListener('click', function() {
        bookDetailsModal.hide();
        buyModal.show();
    });

    // Xử lý xác nhận mua
    document.getElementById('confirmBuy').addEventListener('click', function() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const button = this;
        const originalText = button.innerHTML;
        
        // Hiển thị loading
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        button.disabled = true;
        
        // Gửi request mua sách
        fetch('/purchasable-books/purchase', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_id: currentBookId,
                payment_method: paymentMethod
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                alert(data.message);
                
                // Đóng modal
                buyModal.hide();
                
                // Thay đổi nút mua sách thành "Đã mua"
                const buyButton = document.querySelector('.buy-details-btn');
                if (buyButton) {
                    buyButton.innerHTML = '<i class="fas fa-check"></i> Đã mua';
                    buyButton.className = 'btn btn-success btn-sm';
                    buyButton.disabled = true;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });
    
    // Hàm cập nhật số lượng giỏ hàng trong UI
    function updateCartCount(count) {
        const cartBadge = document.getElementById('cart-count');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'inline' : 'none';
        }
    }
    
    // Hàm hiển thị toast thông báo
    function showToast(type, message) {
        // Tạo toast element
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        // Thêm vào container toast
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        // Hiển thị toast
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Xóa toast sau khi ẩn
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
});
</script>
@endpush
