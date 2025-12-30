<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Lịch sử đơn mượn - Nhà Xuất Bản Xây Dựng</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <style>
        /* Styles cho bảng lịch sử mua hàng */
        .purchase-history-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .purchase-history-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }

        .purchase-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .purchase-history-table thead {
            background-color: #f5f5f5;
        }

        .purchase-history-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
            font-size: 14px;
        }

        .purchase-history-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #555;
        }

        .purchase-history-table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .order-code {
            font-weight: 600;
            color: #333;
        }

        .order-date {
            color: #666;
        }

        .order-amount {
            font-weight: 600;
            color: #d82329;
        }

        .status-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            cursor: default;
        }

        .status-btn.cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        .status-btn.unpaid {
            background-color: #6c757d;
            color: #fff;
        }

        .status-btn.paid {
            background-color: #28a745;
            color: #fff;
        }

        .status-btn.processing {
            background-color: #17a2b8;
            color: #fff;
        }

        .view-btn {
            background-color: #28a745;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .view-btn:hover {
            background-color: #218838;
            color: #fff;
            text-decoration: none;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
            cursor: pointer;
            margin-left: 5px;
        }

        .cancel-btn:hover {
            background-color: #c82333;
            color: #fff;
            text-decoration: none;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14px;
            color: #777;
            margin-bottom: 30px;
        }

        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <div class="logo-section">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-part1">NHÀ XUẤT BẢN</span>
                    <span class="logo-part2">XÂY DỰNG</span>
                </div>
            </div>
            <div class="hotline-section">
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách lẻ:</span>
                    <a href="tel:0327888669" class="hotline-number">0327888669</a>
                </div>
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách sỉ:</span>
                    <a href="tel:02439741791" class="hotline-number">02439741791 - 0327888669</a>
                </div>
            </div>
            <div class="user-actions">
                @auth
                    <div class="user-menu-dropdown" style="position: relative;">
                        <a href="#" class="auth-link user-menu-toggle">
                            <span class="user-icon">👤</span>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="user-dropdown-menu">
                            <div class="dropdown-header" style="padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: 600; color: #333;">
                                <span class="user-icon">👤</span>
                                {{ auth()->user()->name }}
                            </div>
                            @if(auth()->user()->reader)
                            <a href="{{ route('account.borrowed-books') }}" class="dropdown-item">
                                <span>📚</span> Sách đang mượn
                            </a>
                            @endif
                            <a href="{{ route('account') }}" class="dropdown-item">
                                <span>👤</span> Thông tin tài khoản
                            </a>
                            <a href="{{ route('account.change-password') }}" class="dropdown-item">
                                <span>🔒</span> Đổi mật khẩu
                            </a>
                            <a href="{{ route('orders.index') }}" class="dropdown-item">
                                <span>📋</span> Lịch sử đơn mượn
                            </a>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                            <div style="border-top: 1px solid #eee; margin-top: 5px;"></div>
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <span>📊</span> Dashboard
                            </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <span>➡️</span> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                    <style>
                        .user-menu-dropdown {
                            position: relative;
                        }
                        .user-menu-dropdown .user-dropdown-menu {
                            display: none;
                            position: absolute;
                            top: calc(100% + 5px);
                            right: 0;
                            background: white;
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                            min-width: 220px;
                            z-index: 1000;
                            overflow: hidden;
                        }
                        .user-menu-dropdown:hover .user-dropdown-menu {
                            display: block;
                        }
                        .user-menu-dropdown .dropdown-item {
                            display: block;
                            padding: 10px 15px;
                            color: #333;
                            text-decoration: none;
                            border-bottom: 1px solid #eee;
                            transition: background-color 0.2s;
                            cursor: pointer;
                        }
                        .user-menu-dropdown .dropdown-item:hover {
                            background-color: #f5f5f5;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn {
                            border: none;
                            background: none;
                            width: 100%;
                            text-align: left;
                            color: #d32f2f;
                            border-top: 1px solid #eee;
                            margin-top: 5px;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn:hover {
                            background-color: #ffebee;
                        }
                        .user-menu-dropdown .dropdown-item span {
                            margin-right: 8px;
                        }
                    </style>
                @else
                    <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
                @endauth
            </div>
        </div>
        <div class="header-nav">
            <div class="search-bar">
                <form action="{{ route('books.public') }}" method="GET" class="search-form">
                    <input type="text" name="keyword" placeholder="Tìm sách, tác giả, sản phẩm mong muốn..." value="{{ request('keyword') }}" class="search-input">
                    <button type="submit" class="search-button">🔍 Tìm kiếm</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb-nav">
        <div class="breadcrumb-container">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Lịch sử đơn mượn</span>
        </div>
    </nav>

    <main class="account-container">
        <aside class="account-sidebar">
            <div class="user-profile">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="username">{{ auth()->user()->name }}</div>
            </div>
            <nav class="account-nav">
                <ul>
                    @if(auth()->user()->reader)
                    <li><a href="{{ route('account.borrowed-books') }}"><span class="icon">📚</span> Sách đang mượn</a></li>
                    @endif
<<<<<<< HEAD
                    <li><a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin cá nhân</a></li>
                    <li><a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a></li>
                    <li class="active"><a href="{{ route('orders.index') }}"><span class="icon">📋</span> Lịch sử đơn mượn</a></li>
=======
                    <li><a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin khách hàng</a></li>
                    <li><a href="{{ route('account.reader-info') }}" class="dropdown-item"><span>👥</span> Thông tin độc giả</a></li>
                    <li><a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a></li>
                    <li class="active"><a href="{{ route('orders.index') }}"><span class="icon">📋</span> Lịch sử đơn mượn</a></li>
                    @if(!auth()->user()->reader)
                    <li><a href="{{ route('account.register-reader') }}"><span class="icon">📝</span> Đăng kí độc giả</a></li>
                    @endif
>>>>>>> 6526361d58f679f60113153c54886f88ed175fc1
                    <li><a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon">➡️</span> Đăng xuất</a></li>
                </ul>
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        <section class="account-content">
            <div class="purchase-history-section">
                <h2 class="purchase-history-title">Lịch sử đơn mượn</h2>
                
                @if($orders->count() > 0)
                <table class="purchase-history-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn</th>
                            <th>Ngày mượn</th>
                            <th>Số tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Xử lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>
                                <span class="order-code">#BRW{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                <span class="order-amount">{{ number_format($order->tong_tien ?? 0, 0, ',', '.') }}₫</span>
                            </td>
                            <td>
                                @php
                                    $payment = $order->payments->first();
                                    $paymentMethod = $payment ? $payment->payment_method : null;
                                    $paymentNote = $payment ? $payment->note : '';
                                @endphp
                                @if($paymentMethod === 'online')
                                    @if(str_contains($paymentNote, 'VNPay'))
                                        <span style="color: #2196f3; font-weight: 500;">💳 VNPay</span>
                                    @elseif(str_contains($paymentNote, 'chuyển khoản'))
                                        <span style="color: #17a2b8; font-weight: 500;">🏦 Chuyển khoản</span>
                                    @elseif(str_contains($paymentNote, 'ví điện tử'))
                                        <span style="color: #ff9800; font-weight: 500;">👛 Ví điện tử</span>
                                    @else
                                        <span style="color: #2196f3; font-weight: 500;">💳 Online</span>
                                    @endif
                                @elseif($paymentMethod === 'offline')
                                    <span style="color: #28a745; font-weight: 500;">💰 Thanh toán khi nhận hàng</span>
                                @else
                                    <span style="color: #6c757d; font-weight: 500;">Chưa xác định</span>
                                @endif
                            </td>
                            <td>
                                <div class="status-buttons">
                                    @if($order->trang_thai === 'Cho duyet')
                                        <span class="status-btn" style="background-color: #ffc107; color: #000;">⏳ Chờ duyệt</span>
                                    @elseif($order->trang_thai === 'Dang muon')
                                        <span class="status-btn" style="background-color: #2196f3; color: #fff;">📖 Đang mượn</span>
                                    @elseif($order->trang_thai === 'Da tra')
                                        <span class="status-btn paid">✅ Đã trả</span>
                                    @elseif($order->trang_thai === 'Huy')
                                        <span class="status-btn cancelled">❌ Đã hủy</span>
                                    @elseif($order->trang_thai === 'Qua han')
                                        <span class="status-btn" style="background-color: #ff5722; color: #fff;">⚠️ Quá hạn</span>
                                    @else
                                        <span class="status-btn" style="background-color: #6c757d; color: #fff;">{{ $order->trang_thai }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('orders.detail', $order->id) }}" class="view-btn">Xem</a>
                                    @if($order->trang_thai === 'Cho duyet')
                                        <button class="cancel-btn" onclick="showCancelModal({{ $order->id }})">Hủy đơn</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang -->
                @if($orders->hasPages())
                <div class="pagination-wrapper">
                    {{ $orders->links() }}
                </div>
                @endif

                @else
                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h4>Bạn chưa có đơn mượn nào</h4>
                    <p>Hãy bắt đầu mượn sách để tạo đơn mượn đầu tiên của bạn!</p>
                    <a href="{{ route('books.public') }}" class="btn-primary">
                        Khám phá sách ngay
                    </a>
                </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.footer')

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal" tabindex="-1" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hủy đơn mượn</h5>
                    <button type="button" class="btn-close" onclick="hideCancelModal()"></button>
                </div>
                <div class="modal-body">
                    <p>Vui lòng cho chúng tôi biết lí do bạn muốn hủy đơn mượn này:</p>
                    <textarea id="cancelReason" class="form-control" rows="4" placeholder="Nhập lí do hủy đơn (ít nhất 10 ký tự)..."></textarea>
                    <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideCancelModal()">Đóng</button>
                    <button type="button" class="btn btn-danger" onclick="confirmCancel()">Xác nhận hủy</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentBorrowId = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showCancelModal(borrowId) {
            currentBorrowId = borrowId;
            document.getElementById('cancelModal').style.display = 'block';
            document.getElementById('cancelModal').classList.add('show');
            document.getElementById('cancelReason').value = '';
            document.getElementById('errorMessage').style.display = 'none';
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
            document.getElementById('cancelModal').classList.remove('show');
            currentBorrowId = null;
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';

            // Send request
            fetch(`/borrows/${currentBorrowId}/cancel`, {
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
                    btn.textContent = 'Xác nhận hủy';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorDiv.textContent = 'Có lỗi xảy ra khi hủy đơn mượn';
                errorDiv.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Xác nhận hủy';
            });
        }
    </script>
</body>
</html>
