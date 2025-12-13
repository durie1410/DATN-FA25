<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn mượn #{{ $borrow->id }} - Nhà Xuất Bản Xây Dựng</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <style>
        .detail-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .detail-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .detail-card {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .detail-section {
            padding: 25px;
            border-bottom: 1px solid #eee;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            width: 200px;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 14px;
        }

        .status-Cho-duyet {
            background-color: #ffc107;
            color: #000;
        }

        .status-Dang-muon {
            background-color: #2196f3;
            color: #fff;
        }

        .status-Da-tra {
            background-color: #28a745;
            color: #fff;
        }

        .status-Huy {
            background-color: #dc3545;
            color: #fff;
        }

        .status-Qua-han {
            background-color: #ff5722;
            color: #fff;
        }

        .book-item {
            display: flex;
            gap: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .book-image {
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .book-info {
            flex: 1;
        }

        .book-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .book-author {
            color: #666;
            margin-bottom: 8px;
        }

        .book-meta {
            font-size: 14px;
            color: #888;
        }

        .price-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .price-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 18px;
            color: #d82329;
            padding-top: 15px;
            border-top: 2px solid #333;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #c82333;
        }

        /* Modal styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .modal-header {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .modal-body textarea {
            width: 100%;
            min-height: 120px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            resize: vertical;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
        }

        .alert-custom {
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    @include('account._header')

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb-nav" style="padding: 15px 0; background: #f8f9fa; margin-bottom: 20px;">
        <div class="breadcrumb-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <a href="{{ route('home') }}" class="breadcrumb-link" style="color: #666; text-decoration: none;">
                🏠
            </a>
            <span class="breadcrumb-separator" style="margin: 0 10px; color: #999;">/</span>
            <a href="{{ route('orders.index') }}" class="breadcrumb-link" style="color: #666; text-decoration: none;">Lịch sử đơn mượn</a>
            <span class="breadcrumb-separator" style="margin: 0 10px; color: #999;">/</span>
            <span class="breadcrumb-current" style="color: #333;">Chi tiết đơn</span>
        </div>
    </nav>

    <div class="detail-container">
        <div class="detail-header">
            <h1><i class="fas fa-file-alt"></i> Chi tiết đơn mượn #BRW{{ str_pad($borrow->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p style="margin: 0; opacity: 0.9;">Ngày tạo: {{ $borrow->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="detail-card">
            <!-- Thông tin khách hàng -->
            <div class="detail-section">
                <div class="section-title"><i class="fas fa-user"></i> Thông tin khách hàng</div>
                <div class="info-row">
                    <div class="info-label">Họ và tên:</div>
                    <div class="info-value">{{ $borrow->ten_nguoi_muon }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Số điện thoại:</div>
                    <div class="info-value">{{ $borrow->so_dien_thoai }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Địa chỉ:</div>
                    <div class="info-value">
                        {{ $borrow->so_nha ? $borrow->so_nha . ', ' : '' }}
                        {{ $borrow->xa ? $borrow->xa . ', ' : '' }}
                        {{ $borrow->huyen ? $borrow->huyen . ', ' : '' }}
                        {{ $borrow->tinh_thanh }}
                    </div>
                </div>
                @if($borrow->reader)
                <div class="info-row">
                    <div class="info-label">Mã độc giả:</div>
                    <div class="info-value">{{ $borrow->reader->so_the_doc_gia }}</div>
                </div>
                @endif
            </div>

            <!-- Trạng thái đơn -->
            <div class="detail-section">
                <div class="section-title"><i class="fas fa-info-circle"></i> Trạng thái đơn mượn</div>
                <div class="info-row">
                    <div class="info-label">Trạng thái:</div>
                    <div class="info-value">
                        @if($borrow->trang_thai === 'Cho duyet')
                            <span class="status-badge status-Cho-duyet">⏳ Đang chờ xử lí</span>
                        @elseif($borrow->trang_thai === 'Dang muon')
                            <span class="status-badge status-Dang-muon">📖 Đang mượn</span>
                        @elseif($borrow->trang_thai === 'Da tra')
                            <span class="status-badge status-Da-tra">✅ Đã trả</span>
                        @elseif($borrow->trang_thai === 'Huy')
                            <span class="status-badge status-Huy">❌ Đã hủy</span>
                        @elseif($borrow->trang_thai === 'Qua han')
                            <span class="status-badge status-Qua-han">⚠️ Quá hạn</span>
                        @else
                            <span class="status-badge">{{ $borrow->trang_thai }}</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ngày mượn:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($borrow->ngay_muon)->format('d/m/Y') }}</div>
                </div>
                @if($borrow->ghi_chu)
                <div class="info-row">
                    <div class="info-label">Ghi chú:</div>
                    <div class="info-value">{{ $borrow->ghi_chu }}</div>
                </div>
                @endif
            </div>

            <!-- Sách mượn -->
            <div class="detail-section">
                <div class="section-title"><i class="fas fa-book"></i> Sách đã mượn ({{ $borrow->items->count() }} cuốn)</div>
                @foreach($borrow->items as $item)
                <div class="book-item">
                    @if($item->book)
                    <img src="{{ $item->book->hinh_anh ? asset('storage/' . $item->book->hinh_anh) : asset('images/default-book.png') }}" 
                         alt="{{ $item->book->ten_sach }}" 
                         class="book-image">
                    <div class="book-info">
                        <div class="book-title">{{ $item->book->ten_sach }}</div>
                        <div class="book-author">Tác giả: {{ $item->book->tac_gia ?? 'Chưa cập nhật' }}</div>
                        <div class="book-meta">
                            <div>📅 Ngày hẹn trả: {{ \Carbon\Carbon::parse($item->ngay_hen_tra)->format('d/m/Y') }}</div>
                            @if($item->ngay_tra_thuc_te)
                            <div>✅ Đã trả ngày: {{ \Carbon\Carbon::parse($item->ngay_tra_thuc_te)->format('d/m/Y') }}</div>
                            @endif
                            @if($item->inventory)
                            <div>🏷️ Mã sách: {{ $item->inventory->barcode ?? 'N/A' }}</div>
                            @endif
                        </div>
                        <div style="margin-top: 10px;">
                            <strong>Trạng thái:</strong>
                            @if($item->trang_thai === 'Cho duyet')
                                <span class="status-badge status-Cho-duyet">⏳ Chờ duyệt</span>
                            @elseif($item->trang_thai === 'Dang muon')
                                <span class="status-badge status-Dang-muon">📖 Đang mượn</span>
                            @elseif($item->trang_thai === 'Da tra')
                                <span class="status-badge status-Da-tra">✅ Đã trả</span>
                            @elseif($item->trang_thai === 'Huy')
                                <span class="status-badge status-Huy">❌ Đã hủy</span>
                            @else
                                <span class="status-badge">{{ $item->trang_thai }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Thanh toán -->
            <div class="detail-section">
                <div class="section-title"><i class="fas fa-money-bill-wave"></i> Thông tin thanh toán</div>
                <div class="price-summary">
                    <div class="price-row">
                        <span>Tiền cọc:</span>
                        <span>{{ number_format($borrow->tien_coc, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="price-row">
                        <span>Tiền thuê:</span>
                        <span>{{ number_format($borrow->tien_thue, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="price-row">
                        <span>Tiền ship:</span>
                        <span>{{ number_format($borrow->tien_ship, 0, ',', '.') }}₫</span>
                    </div>
                    @if($borrow->voucher)
                    <div class="price-row">
                        <span>Giảm giá ({{ $borrow->voucher->ma_voucher }}):</span>
                        <span>-{{ number_format($borrow->voucher->gia_tri, 0, ',', '.') }}{{ $borrow->voucher->loai === 'phan_tram' ? '%' : '₫' }}</span>
                    </div>
                    @endif
                    <div class="price-row">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($borrow->tong_tien, 0, ',', '.') }}₫</span>
                    </div>
                </div>

                @if($borrow->payments->count() > 0)
                <div style="margin-top: 20px;">
                    <strong>Phương thức thanh toán:</strong>
                    @php $payment = $borrow->payments->first(); @endphp
                    @if($payment->payment_method === 'online')
                        <span>💳 Thanh toán online</span>
                    @else
                        <span>💰 Thanh toán khi nhận hàng</span>
                    @endif
                </div>
                @endif
            </div>

            <!-- Action buttons -->
            <div class="action-buttons">
                <a href="{{ route('orders.index') }}" class="btn-custom btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                @if($borrow->trang_thai === 'Cho duyet')
                <button class="btn-custom btn-cancel" onclick="showCancelModal()">
                    <i class="fas fa-times-circle"></i> Hủy đơn mượn
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i>
                Lí do hủy đơn
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 15px; color: #666;">Vui lòng cho chúng tôi biết lí do bạn muốn hủy đơn mượn này:</p>
                <textarea id="cancelReason" placeholder="Nhập lí do hủy đơn (ít nhất 10 ký tự)..."></textarea>
                <div id="errorMessage" class="alert-custom alert-error" style="display: none; margin-top: 10px;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-custom btn-back" onclick="hideCancelModal()">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button class="btn-custom btn-cancel" onclick="confirmCancel()">
                    <i class="fas fa-check"></i> Xác nhận hủy
                </button>
            </div>
        </div>
    </div>

    <footer style="background: #333; color: #fff; padding: 40px 0 20px; margin-top: 60px;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
            <p style="margin: 0 0 10px 0; font-size: 14px;">© 2025 Thư Viện LibHub. All rights reserved.</p>
            <p style="margin: 0; font-size: 12px; color: #999;">Hotline: 0327888669 | Email: support@libhub.vn</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const borrowId = {{ $borrow->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showCancelModal() {
            document.getElementById('cancelModal').classList.add('active');
            document.getElementById('cancelReason').value = '';
            document.getElementById('errorMessage').style.display = 'none';
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.remove('active');
        }

        function confirmCancel() {
            const reason = document.getElementById('cancelReason').value.trim();
            const errorDiv = document.getElementById('errorMessage');

            // Validate
            if (reason.length < 10) {
                errorDiv.textContent = 'Lí do hủy đơn phải có ít nhất 10 ký tự';
                errorDiv.style.display = 'block';
                return;
            }

            // Disable button
            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

            // Send request
            fetch(`/borrows/${borrowId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cancellation_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Đã hủy đơn mượn thành công!');
                    window.location.reload();
                } else {
                    errorDiv.textContent = data.message || 'Có lỗi xảy ra khi hủy đơn mượn';
                    errorDiv.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check"></i> Xác nhận hủy';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorDiv.textContent = 'Có lỗi xảy ra khi hủy đơn mượn';
                errorDiv.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Xác nhận hủy';
            });
        }

        // Close modal when clicking outside
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideCancelModal();
            }
        });
    </script>
</body>
</html>
