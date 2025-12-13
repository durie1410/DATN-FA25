@extends('layouts.frontend')

@section('title', 'Xác nhận mượn sách - Thư Viện Online')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}?v={{ time() }}">
<style>
    html, body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%) !important;
        background-size: 400% 400% !important;
        animation: gradientShift 15s ease infinite !important;
        min-height: 100vh !important;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(248, 250, 252, 0.75);
        z-index: 0;
        pointer-events: none;
    }

    .checkout-page { position: relative; z-index: 1; }
    .payment-option { position: relative; }
    .payment-option input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }

    .payment-card {
        display: flex; align-items: center; padding: 18px; background: #ffffff;
        border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer;
        transition: all 0.3s ease; position: relative; min-height: 90px;
    }
    .payment-card:hover { border-color: #3b82f6; box-shadow: 0 4px 12px rgba(59,130,246,0.15); transform: translateY(-2px); }
    .payment-option input[type="radio"]:checked + .payment-card { border-color: #10b981; background: #f0fdf4; box-shadow: 0 4px 12px rgba(16,185,129,0.2); }

    .payment-icon { width:50px; height:50px; display:flex; align-items:center; justify-content:center; background:#f8fafc; border-radius:10px; margin-right:15px; }
    .payment-info { flex:1; }
    .payment-info h6 { font-size:0.95rem; font-weight:600; margin:0; }
    .payment-info small { font-size:0.8rem; }

    .payment-check { position:absolute; top:12px; right:12px; width:24px; height:24px; display:flex; align-items:center; justify-content:center; opacity:0; transition:all 0.3s ease; }
    .payment-check i { color:#10b981; font-size:1.3rem; }
    .payment-option input[type="radio"]:checked + .payment-card .payment-check { opacity:1; }

    .input-icon-wrapper { position: relative; }
    .input-icon { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#64748b; font-size:0.95rem; z-index:1; }
    .input-icon-wrapper .form-control { padding-left:45px !important; }

    .summary-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .summary-label, .summary-value { font-size:0.95rem; color:var(--checkout-text); }
    .summary-value { font-weight:500; }
    .summary-value.text-primary { color:#3b82f6 !important; font-weight:600; }

    .discount-section .input-group-text { background:#fff; border:1.5px solid #e2e8f0; border-right:0; border-radius:8px 0 0 8px; }
    .discount-section .form-control { background:#f8fafc; border:1.5px solid #e2e8f0; border-left:0; border-right:0; color:var(--checkout-text); }
    .discount-section .btn-success { background:#10b981; border:none; border-radius:0 8px 8px 0; padding:0 20px; color:#fff; }

    .shipping-info-box { background:#fef3c7; border:1.5px dashed #fbbf24; border-radius:8px; padding:12px 15px; }
    .total-payment { padding-top:15px; border-top:2px solid #e2e8f0; margin-top:5px; }

    .btn-checkout {
        background: linear-gradient(135deg,#f97316 0%,#ea580c 100%) !important; color:#fff !important;
        border:none !important; padding:14px 28px !important; border-radius:10px !important;
        font-weight:600 !important; font-size:1rem !important; box-shadow:0 4px 15px rgba(249,115,22,0.3) !important;
    }
    .btn-checkout:hover { transform:translateY(-2px) !important; box-shadow:0 6px 20px rgba(249,115,22,0.4) !important; }
    .btn-checkout:disabled { opacity:0.7 !important; cursor:not-allowed !important; transform:none !important; }
</style>
@endpush

@section('content')
<div class="container py-5 checkout-page">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-book-reader text-primary"></i> Xác nhận mượn sách</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('borrow-cart.index') }}">Giỏ sách</a></li>
                        <li class="breadcrumb-item active">Xác nhận mượn sách</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form id="borrowCheckoutForm" method="POST" action="{{ route('borrow-cart.process-checkout') }}" novalidate>
        @csrf

        {{-- Hidden source if items came from URL params --}}
        <input type="hidden" name="checkout_source" value="{{ isset($fromUrl) && $fromUrl ? 'url' : 'cart' }}">
        @if(isset($fromUrl) && $fromUrl && isset($items))
            {{-- Chỉ gửi những field cần thiết (book_id, quantity, borrow_days, distance) - NOT giá --}}
            <input type="hidden" name="items_json" value="{{ json_encode(collect($items)->map(function($item) {
                return [
                    'book_id' => $item['book']->id ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'borrow_days' => $item['borrow_days'] ?? 14,
                    'distance' => $item['distance'] ?? 0,
                    'note' => $item['note'] ?? '',
                ];
            })->toArray()) }}">
        @endif

        <div class="row">
            <div class="col-lg-7">
                {{-- Payment options --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-credit-card"></i> Chọn phương thức thanh toán</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="payment-option" data-payment="bank_transfer">
                                    <input type="radio" name="payment_method" id="payment_bank" value="bank_transfer" checked>
                                    <label for="payment_bank" class="payment-card">
                                        <div class="payment-icon"><i class="fas fa-university" style="color:#3b82f6;font-size:2rem"></i></div>
                                        <div class="payment-info">
                                            <h6 style="margin-bottom:4px;color:var(--checkout-text)">Thanh toán chuyển khoản</h6>
                                            <small class="text-muted">Thanh toán bằng ứng dụng ngân hàng</small>
                                        </div>
                                        <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="payment-option" data-payment="vnpay">
                                    <input type="radio" name="payment_method" id="payment_vnpay" value="vnpay">
                                    <label for="payment_vnpay" class="payment-card">
                                        <div class="payment-icon"><i class="fas fa-qrcode" style="color:#ef4444;font-size:2rem"></i></div>
                                        <div class="payment-info">
                                            <h6 style="margin-bottom:4px;color:var(--checkout-text)">Thanh toán qua VNPAY</h6>
                                            <small class="text-muted">Cổng thanh toán VNPAY-QR</small>
                                        </div>
                                        <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="payment-option" data-payment="wallet">
                                    <input type="radio" name="payment_method" id="payment_wallet" value="wallet">
                                    <label for="payment_wallet" class="payment-card">
                                        <div class="payment-icon"><i class="fas fa-wallet" style="color:#8b5cf6;font-size:2rem"></i></div>
                                        <div class="payment-info">
                                            <h6 style="margin-bottom:4px;color:var(--checkout-text)">Thanh toán bằng ví</h6>
                                            <small class="text-muted">Số dư hiện tại: 0đ - <span style="color:#ef4444">Nạp thêm</span></small>
                                        </div>
                                        <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="payment-option" data-payment="cod">
                                    <input type="radio" name="payment_method" id="payment_cod" value="cod">
                                    <label for="payment_cod" class="payment-card">
                                        <div class="payment-icon"><i class="fas fa-money-bill-wave" style="color:#10b981;font-size:2rem"></i></div>
                                        <div class="payment-info">
                                            <h6 style="margin-bottom:4px;color:var(--checkout-text)">Thanh toán khi nhận hàng</h6>
                                            <small class="text-muted">Thanh toán khi nhận được hàng</small>
                                        </div>
                                        <div class="payment-check"><i class="fas fa-check-circle"></i></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Receiver info --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-user"></i> Thông tin người nhận</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="form-control ps-5" id="reader_name" name="reader_name" value="{{ $reader->ho_ten ?? '' }}" placeholder="Họ và tên" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="tel" class="form-control ps-5" id="reader_phone" name="reader_phone" value="{{ $reader->so_dien_thoai ?? '' }}" placeholder="Số điện thoại" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-icon-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" class="form-control ps-5" id="reader_email" name="reader_email" value="{{ auth()->user()->email ?? '' }}" placeholder="Email" required>
                            </div>
                        </div>

                        @php
                            $addressParts = explode(',', $reader->dia_chi ?? '');
                            $tinh = count($addressParts) > 2 ? trim($addressParts[count($addressParts)-1]) : 'Hà Nội';
                            $huyen = count($addressParts) > 1 ? trim($addressParts[count($addressParts)-2]) : '';
                            $xa = count($addressParts) > 0 ? trim($addressParts[0]) : '';
                        @endphp

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <select class="form-select" id="tinh_thanh" name="tinh_thanh">
                                    <option value="Hà Nội" {{ $tinh == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="Hồ Chí Minh" {{ $tinh == 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                    <option value="Đà Nẵng" {{ $tinh == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="huyen" name="huyen" value="{{ $huyen }}" placeholder="Quận/Huyện">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="xa" name="xa" value="{{ $xa }}" placeholder="Phường/Xã">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="so_nha" name="so_nha" placeholder="Số nhà">
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Nhập ghi chú (không bắt buộc)"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Items list: support URL params ($items) or cart ($cart->items) --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Danh sách sách mượn
                            @if(isset($fromUrl) && $fromUrl)
                                ({{ count($items ?? []) }} sách)
                            @else
                                ({{ optional($cart->items)->count() ?? 0 }} sách)
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th style="color:var(--checkout-text)">Sách</th>
                                        <th style="color:var(--checkout-text)" class="text-center">Số lượng</th>
                                        <th style="color:var(--checkout-text)" class="text-center">Số ngày</th>
                                        <th style="color:var(--checkout-text)" class="text-end">Tiền cọc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($fromUrl) && $fromUrl)
                                        @forelse($items ?? [] as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($item['book']->hinh_anh))
                                                        <img src="{{ asset('storage/books/' . $item['book']->hinh_anh) }}" alt="{{ $item['book']->ten_sach }}" style="width:50px;height:70px;object-fit:cover;border-radius:4px;margin-right:15px;">
                                                    @else
                                                        <div style="width:50px;height:70px;background:#e2e8f0;border-radius:4px;margin-right:15px;display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-book" style="color:#94a3b8"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0" style="color:var(--checkout-text)">{{ $item['book']->ten_sach }}</h6>
                                                        <small class="text-muted">{{ $item['book']->tac_gia ?? 'Không rõ tác giả' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" style="vertical-align:middle;color:var(--checkout-text)">{{ $item['quantity'] ?? 1 }} cuốn</td>
                                            <td class="text-center" style="vertical-align:middle;color:var(--checkout-text)">{{ $item['borrow_days'] ?? 14 }} ngày</td>
                                            <td class="text-end" style="vertical-align:middle;color:var(--checkout-text)">{{ number_format($item['tien_coc'] ?? 0,0,',','.') }}₫</td>
                                        </tr>
                                        @if(!$loop->last)
                                        <tr><td colspan="4"><hr style="margin:10px 0;"></td></tr>
                                        @endif
                                        @empty
                                        <tr><td colspan="4" class="text-center py-4" style="color:#94a3b8">Không có sách nào</td></tr>
                                        @endforelse
                                    @else
                                        @forelse($cart->items ?? [] as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($item->book->hinh_anh))
                                                        <img src="{{ asset('storage/books/' . $item->book->hinh_anh) }}" alt="{{ $item->book->ten_sach }}" style="width:50px;height:70px;object-fit:cover;border-radius:4px;margin-right:15px;">
                                                    @else
                                                        <div style="width:50px;height:70px;background:#e2e8f0;border-radius:4px;margin-right:15px;display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-book" style="color:#94a3b8"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0" style="color:var(--checkout-text)">{{ $item->book->ten_sach }}</h6>
                                                        <small class="text-muted">{{ $item->book->tac_gia ?? 'Không rõ tác giả' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center" style="vertical-align:middle;color:var(--checkout-text)">{{ $item->quantity }} cuốn</td>
                                            <td class="text-center" style="vertical-align:middle;color:var(--checkout-text)">{{ $item->borrow_days }} ngày</td>
                                            <td class="text-end" style="vertical-align:middle;color:var(--checkout-text)">
                                                {{ number_format(($item->tien_coc ?? 0) * ($item->quantity ?? 1),0,',','.') }}₫
                                            </td>
                                        </tr>
                                        @if(!$loop->last)
                                        <tr><td colspan="4"><hr style="margin:10px 0;"></td></tr>
                                        @endif
                                        @empty
                                        <tr><td colspan="4" class="text-center py-4" style="color:#94a3b8">Giỏ sách trống</td></tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Summary column --}}
            <div class="col-lg-5">
                <div class="card mb-4" style="position:sticky; top:100px">
                    <div class="card-header"><h5 class="mb-0">Tóm tắt đơn mượn</h5></div>
                    <div class="card-body">
                        <div class="summary-row">
                            <span class="summary-label">Mã đơn:</span>
                            <span class="summary-value" style="color:#64748b">#{{ now()->format('ymdHis') }}{{ auth()->id() ?? '' }}</span>
                        </div>

                        @if(isset($fromUrl) && $fromUrl && isset($items))
                            @php
                                $itemsCollect = collect($items ?? []);
                                $sumTienCoc = $itemsCollect->sum('tien_coc');
                                $sumTienThue = $itemsCollect->sum('tien_thue');
                                $sumTienShip = $itemsCollect->sum('tien_ship');
                                $sumTotal = ($sumTienCoc + $sumTienThue + $sumTienShip);
                            @endphp

                            <div class="summary-row">
                                <span class="summary-label">Tiền cọc:</span>
                                <span class="summary-value text-primary">{{ number_format($sumTienCoc,0,',','.') }}₫</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Tiền thuê:</span>
                                <span class="summary-value text-primary">{{ number_format($sumTienThue,0,',','.') }}₫</span>
                            </div>
                        @else
                            <div class="summary-row">
                                <span class="summary-label">Tiền cọc:</span>
                                <span class="summary-value text-primary">{{ number_format($totalTienCoc ?? 0,0,',','.') }}₫</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Tiền thuê:</span>
                                <span class="summary-value text-primary">{{ number_format($totalTienThue ?? 0,0,',','.') }}₫</span>
                            </div>
                        @endif

                        <div class="summary-row mb-3" style="padding-bottom:12px;border-bottom:1px dashed #e2e8f0">
                            <span class="summary-label">Giảm giá:</span>
                            <span class="summary-value">-0₫</span>
                        </div>

                        <div class="discount-section mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white" style="border-right:0;"><i class="fas fa-tag" style="color:#64748b"></i></span>
                                <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Nhập mã giảm giá" style="border-left:0;">
                                <button class="btn btn-success" type="button" onclick="applyDiscount()">Áp dụng</button>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Tạm tính:</span>
                            <span class="summary-value text-primary">
                                @if(isset($fromUrl) && $fromUrl && isset($items))
                                    {{ number_format(($sumTienCoc + $sumTienThue),0,',','.') }}₫
                                @else
                                    {{ number_format(($totalTienCoc ?? 0) + ($totalTienThue ?? 0),0,',','.') }}₫
                                @endif
                            </span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Giảm giá đơn:</span>
                            <span class="summary-value">-0₫</span>
                        </div>

                        <div class="summary-row mb-3">
                            <span class="summary-label">Phí vận chuyển:</span>
                            <span class="summary-value">
                                @if(isset($fromUrl) && $fromUrl && isset($items))
                                    {{ number_format($sumTienShip ?? 0,0,',','.') }}₫
                                @else
                                    {{ number_format($totalTienShip ?? 0,0,',','.') }}₫
                                @endif
                            </span>
                        </div>

                        <div class="shipping-info-box mb-3">
                            <small style="color:#92400e;line-height:1.5"><i class="fas fa-info-circle me-1"></i> Phí ship tính theo đơn cứ sau 5km mỗi 1km tăng thêm 5 nghìn.</small>
                        </div>

                        <div class="summary-row total-payment mb-4">
                            <span class="summary-label" style="font-size:1.1rem;font-weight:600">Thanh toán:</span>
                            <span class="summary-value" style="font-size:1.4rem;font-weight:700;color:#ef4444">
                                @if(isset($fromUrl) && $fromUrl && isset($items))
                                    {{ number_format($sumTotal ?? 0,0,',','.') }}₫
                                @else
                                    {{ number_format($tongTien ?? 0,0,',','.') }}₫
                                @endif
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-checkout" id="confirmBorrowBtn"><i class="fas fa-shopping-cart me-2"></i> Mượn sách</button>
                            <a href="{{ route('borrow-cart.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Quay lại giỏ sách</a>
                        </div>

                        <div class="mt-3 pt-3" style="border-top:1px solid #e2e8f0">
                            <small style="color:#64748b;line-height:1.6"><i class="fas fa-info-circle me-1"></i> Bằng việc tiến hành đặt mượn sách, bạn đồng ý với điều khoản của Thư Viện Online</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Toast --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="borrowToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="fas fa-book-reader text-success me-2"></i>
            <strong class="me-auto">Thông báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    function initBorrowCheckout(){
        const form = document.getElementById('borrowCheckoutForm');
        const btn = document.getElementById('confirmBorrowBtn');
        if(!form || !btn) return false;

        document.querySelectorAll('.payment-card').forEach(card=>{
            card.addEventListener('click', function(){
                const radio = this.previousElementSibling;
                if(radio && radio.type === 'radio') radio.checked = true;
            });
        });

        let borrowToast;
        try {
            const el = document.getElementById('borrowToast');
            if(el && window.bootstrap && bootstrap.Toast) borrowToast = new bootstrap.Toast(el);
        } catch(e){ console.error(e); }

        function showToast(type, message){
            const el = document.getElementById('borrowToast');
            const msg = document.getElementById('toastMessage');
            if(!el || !msg){ alert(message); return; }
            msg.textContent = message;
            const icon = el.querySelector('.toast-header i');
            if(icon){
                icon.className = type === 'success' ? 'fas fa-check-circle text-success me-2' : 'fas fa-exclamation-circle text-danger me-2';
                if(type === 'success') el.classList.remove('bg-danger'); else el.classList.add('bg-danger');
            }
            if(borrowToast) borrowToast.show(); else alert(message);
        }

        form.addEventListener('submit', function(e){
            e.preventDefault(); e.stopPropagation();
            const original = btn.innerHTML;
            const name = document.getElementById('reader_name')?.value?.trim();
            const phone = document.getElementById('reader_phone')?.value?.trim();
            const email = document.getElementById('reader_email')?.value?.trim();
            const payment = document.querySelector('input[name="payment_method"]:checked');

            if(!name){ showToast('error','Vui lòng nhập họ và tên'); return; }
            if(!phone){ showToast('error','Vui lòng nhập số điện thoại'); return; }
            if(!email){ showToast('error','Vui lòng nhập email'); return; }
            if(!payment){ showToast('error','Vui lòng chọn phương thức thanh toán'); return; }
            if(!confirm('Bạn có chắc chắn muốn mượn tất cả các sách này?')) return;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...'; btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
            if(!csrfToken){ showToast('error','Không tìm thấy token bảo mật. Vui lòng tải lại trang.'); btn.innerHTML = original; btn.disabled = false; return; }

            const data = new FormData(form);

            fetch('{{ route("borrow-cart.process-checkout") }}', {
                method: 'POST',
                body: data,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(async res=>{
                const ct = res.headers.get('content-type') || '';
                if(!ct.includes('application/json')){
                    const text = await res.text();
                    if(text.includes('vnpayment.vn')){
                        const m = text.match(/https?:\/\/[\w\-\./?&=:%]+vnpayment\.vn[^\s"']*/);
                        if(m && m[0]){ showToast('success','Chuyển đến cổng thanh toán...'); setTimeout(()=>window.location.href = m[0], 800); return; }
                    }
                    showToast('error','Phản hồi từ server không đúng định dạng'); btn.innerHTML = original; btn.disabled = false; return;
                }
                const json = await res.json();
                if(!res.ok){
                    let err = json.message || 'Có lỗi xảy ra';
                    if(json.errors){ err = Object.values(json.errors).flat().join(', '); }
                    showToast('error', err);
                    if(json.redirect) setTimeout(()=>window.location.href = json.redirect, 1200);
                    else { btn.innerHTML = original; btn.disabled = false; }
                    return;
                }
                if(json.success){
                    if(json.payment_required && json.payment_url){ showToast('success','Đang chuyển đến trang thanh toán...'); setTimeout(()=>window.location.href = json.payment_url,800); return; }
                    showToast('success', json.message || 'Tạo yêu cầu thành công');
                    setTimeout(()=>window.location.href = json.redirect_url || '{{ route("account.borrowed-books") }}', 900);
                } else {
                    showToast('error', json.message || 'Có lỗi xảy ra'); btn.innerHTML = original; btn.disabled = false;
                }
            })
            .catch(err=>{
                console.error('Fetch Error:', err);
                showToast('error', 'Lỗi kết nối: ' + (err.message || err));
                btn.innerHTML = original; btn.disabled = false;
            });
        });

        window.applyDiscount = function(){
            const code = document.getElementById('discount_code')?.value?.trim();
            if(!code){ showToast('error','Vui lòng nhập mã giảm giá'); return; }
            showToast('error','Tính năng mã giảm giá đang được phát triển');
        };

        return true;
    }

    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', initBorrowCheckout);
    } else initBorrowCheckout();
})();
</script>
@endpush