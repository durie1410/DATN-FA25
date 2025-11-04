@extends('layouts.frontend')

@section('title', $book->ten_sach . ' - Thư Viện Online')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchasable-books.index') }}">Sách có thể mua</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($book->ten_sach, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin sách -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($book->hinh_anh)
                                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                                         alt="{{ $book->ten_sach }}" 
                                         class="img-fluid rounded shadow"
                                         style="max-height: 400px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <i class="fas fa-book fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $book->ten_sach }}</h2>
                            
                            <div class="mb-3">
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user"></i> <strong>Tác giả:</strong> {{ $book->tac_gia }}
                                </p>
                                <p class="text-muted mb-1" style="background: rgba(0,255,153,0.04); font-weight: 600; padding: 4px 10px; border-radius: 6px; display: inline-block; margin-bottom: 8px;">
                                    <i class="fas fa-building" style="color: #31d58d;"></i> <strong>Nhà xuất bản:</strong> {{ $book->nha_xuat_ban ?? 'Chưa cập nhật' }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar"></i> <strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban ?? 'Chưa cập nhật' }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-file"></i> <strong>Số trang:</strong> {{ $book->so_trang ? $book->so_trang . ' trang' : 'Chưa cập nhật' }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-file-alt"></i> <strong>Định dạng:</strong> {{ $book->dinh_dang }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-weight"></i> <strong>Kích thước:</strong> {{ $book->formatted_file_size ?? 'Chưa cập nhật' }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-language"></i> <strong>Ngôn ngữ:</strong> {{ $book->ngon_ngu }}
                                </p>
                                @if($book->isbn)
                                <p class="text-muted mb-1">
                                    <i class="fas fa-barcode"></i> <strong>ISBN:</strong> {{ $book->isbn }}
                                </p>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div class="mb-3">
                                <h6>Đánh giá:</h6>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($book->danh_gia_trung_binh))
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $book->danh_gia_trung_binh)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">{{ $book->danh_gia_trung_binh }}/5 ({{ $book->so_luong_ban }} lượt mua)</span>
                            </div>

                            <!-- Stats -->
                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="text-center">
                                        <h5 class="text-primary">{{ $book->so_luong_ban }}</h5>
                                        <small class="text-muted">Lượt mua</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <h5 class="text-info">{{ $book->so_luot_xem }}</h5>
                                        <small class="text-muted">Lượt xem</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <h5 class="text-success">{{ $book->dinh_dang }}</h5>
                                        <small class="text-muted">Định dạng</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mb-4">
                                <h3 class="text-primary">{{ number_format($book->gia, 0, ',', '.') }} VNĐ</h3>
                            </div>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                @if($isPurchased)
                                    <button class="btn btn-success btn-lg" disabled>
                                        <i class="fas fa-check"></i> Đã mua
                                    </button>
                                @else
                                    <button class="btn btn-primary btn-lg buy-details-btn" 
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
                                        <i class="fas fa-shopping-cart"></i> Mua sách
                                    </button>
                                @endif
                                <button class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print"></i> In thông tin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            @if($book->mo_ta)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Mô tả sách</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $book->mo_ta }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Thông tin mua sách -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin mua sách</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lợi ích khi mua:</h6>
                        <ul class="mb-0">
                            <li>Sở hữu vĩnh viễn</li>
                            <li>Đọc offline mọi lúc</li>
                            <li>Không giới hạn thời gian</li>
                            <li>Hỗ trợ nhiều thiết bị</li>
                            <li>Chất lượng cao</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Lưu ý:</h6>
                        <ul class="mb-0">
                            <li>File sẽ được gửi qua email</li>
                            <li>Thời gian gửi: 24 giờ</li>
                            <li>Không hoàn tiền sau khi mua</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sách liên quan -->
            @if($relatedBooks->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Sách liên quan</h5>
                </div>
                <div class="card-body">
                    @foreach($relatedBooks as $relatedBook)
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            @if($relatedBook->hinh_anh)
                                <img src="{{ asset('storage/' . $relatedBook->hinh_anh) }}" 
                                     alt="{{ $relatedBook->ten_sach }}" 
                                     class="rounded" 
                                     style="width: 60px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 80px;">
                                    <i class="fas fa-book text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="{{ route('purchasable-books.show', $relatedBook->id) }}" class="text-decoration-none">
                                    {{ Str::limit($relatedBook->ten_sach, 40) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-1">{{ $relatedBook->tac_gia }}</p>
                            <p class="text-primary small mb-0">{{ number_format($relatedBook->gia, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
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
                            @if($book->hinh_anh)
                                <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                                     alt="{{ $book->ten_sach }}" 
                                     class="img-fluid rounded" 
                                     style="max-height: 300px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-book fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $book->ten_sach }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user"></i> <strong>Tác giả:</strong> {{ $book->tac_gia }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-building"></i> <strong>Nhà xuất bản:</strong> {{ $book->nha_xuat_ban ?? 'Chưa cập nhật' }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar"></i> <strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban ?? 'Chưa cập nhật' }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-file"></i> <strong>Số trang:</strong> {{ $book->so_trang ? $book->so_trang . ' trang' : 'Chưa cập nhật' }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-file-alt"></i> <strong>Định dạng:</strong> {{ $book->dinh_dang }}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-weight"></i> <strong>Kích thước:</strong> {{ $book->formatted_file_size ?? 'Chưa cập nhật' }}
                        </p>
                        <div class="mb-3">
                            <h5 class="text-primary">{{ number_format($book->gia, 0, ',', '.') }} VNĐ</h5>
                        </div>
                        <div class="mb-3">
                            <h6>Mô tả:</h6>
                            <p class="text-muted">{{ $book->mo_ta ?? 'Chưa có mô tả' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="buyFromDetails">
                    <i class="fas fa-shopping-cart"></i> Mua sách
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

    // Xử lý click nút mua sách - hiển thị chi tiết trước
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
                    buyButton.className = 'btn btn-success btn-lg';
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
