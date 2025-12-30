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
    
    /* Address autocomplete suggestions */
    #xa_suggestions { 
        background: #fff; 
        border: 1px solid #e2e8f0; 
        border-radius: 8px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        margin-top: 4px;
    }
    #xa_suggestions .list-group-item { 
        border: none; 
        padding: 10px 15px; 
        cursor: pointer; 
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    #xa_suggestions .list-group-item:hover { 
        background: #f0f9ff; 
        color: #3b82f6; 
    }
    #xa_suggestions .list-group-item:not(:last-child) { 
        border-bottom: 1px solid #f1f5f9; 
    }
    .form-label { 
        font-size: 0.85rem; 
        font-weight: 500; 
        color: #64748b; 
    }
    .form-label i { 
        color: #3b82f6; 
    }
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
                                            <small class="text-muted">Số dư hiện tại: <strong style="color:#10b981">{{ number_format($walletBalance ?? 0, 0, ',', '.') }}₫</strong> - <a href="{{ route('account.wallet') }}" style="color:#ef4444;text-decoration:none">Nạp thêm</a></small>
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input type="text" class="form-control ps-5" id="reader_cccd" name="reader_cccd" value="{{ $reader->so_cccd ?? auth()->user()->so_cccd ?? '' }}" placeholder="Số CCCD/CMND" maxlength="20">
                                </div>
                                <small class="text-muted">(Không bắt buộc)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><i class="fas fa-birthday-cake me-1"></i>Ngày sinh <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="reader_birthday" name="reader_birthday" value="{{ $reader->ngay_sinh ? $reader->ngay_sinh->format('Y-m-d') : (auth()->user()->ngay_sinh ? auth()->user()->ngay_sinh->format('Y-m-d') : '') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><i class="fas fa-venus-mars me-1"></i>Giới tính <span class="text-danger">*</span></label>
                                <select class="form-select" id="reader_gender" name="reader_gender" required>
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="Nam" {{ ($reader->gioi_tinh ?? auth()->user()->gioi_tinh ?? '') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nu" {{ ($reader->gioi_tinh ?? auth()->user()->gioi_tinh ?? '') == 'Nu' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khac" {{ ($reader->gioi_tinh ?? auth()->user()->gioi_tinh ?? '') == 'Khac' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-3">

                        <h6 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Thông tin địa chỉ nhận hàng</h6>

                        @php
                            // Lấy địa chỉ từ reader hoặc user
                            $diaChi = $reader->dia_chi ?? auth()->user()->address ?? '';
                            $tinh = '';
                            $xa = '';
                            $soNha = '';
                            
                            // Danh sách các tỉnh/thành phố để so khớp
                            $provinces = [
                                'Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
                                'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
                                'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
                                'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông',
                                'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang',
                                'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang', 'Hòa Bình',
                                'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
                                'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định',
                                'Nghệ An', 'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Phú Yên',
                                'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
                                'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
                                'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang',
                                'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
                            ];
                            
                            if (!empty($diaChi)) {
                                $addressParts = array_map('trim', explode(',', $diaChi));
                                $addressParts = array_filter($addressParts); // Loại bỏ phần tử rỗng
                                $addressParts = array_values($addressParts); // Đánh lại index
                                
                                $count = count($addressParts);
                                
                                // Tìm tỉnh/thành phố (thường ở cuối)
                                for ($i = $count - 1; $i >= 0; $i--) {
                                    $part = trim($addressParts[$i]);
                                    // Loại bỏ "Việt Nam" nếu có
                                    $part = str_replace('Việt Nam', '', $part);
                                    $part = trim($part);
                                    
                                    // So khớp với danh sách tỉnh/thành phố
                                    foreach ($provinces as $province) {
                                        if (stripos($part, $province) !== false || stripos($province, $part) !== false) {
                                            $tinh = $province;
                                            break 2;
                                        }
                                    }
                                }
                                
                                // Nếu không tìm thấy tỉnh, mặc định là Hà Nội
                                if (empty($tinh)) {
                                    $tinh = 'Hà Nội';
                                }
                                
                                // Lấy phần còn lại làm phường/xã và số nhà
                                // Loại bỏ phần tỉnh/thành phố và "Việt Nam"
                                $remainingParts = [];
                                foreach ($addressParts as $part) {
                                    $cleanPart = trim(str_replace('Việt Nam', '', $part));
                                    if (!empty($cleanPart)) {
                                        $isProvince = false;
                                        foreach ($provinces as $province) {
                                            if (stripos($cleanPart, $province) !== false || stripos($province, $cleanPart) !== false) {
                                                $isProvince = true;
                                                break;
                                            }
                                        }
                                        if (!$isProvince) {
                                            $remainingParts[] = $cleanPart;
                                        }
                                    }
                                }
                                
                                // Phần đầu là số nhà hoặc phường/xã
                                if (count($remainingParts) > 0) {
                                    $xa = $remainingParts[0];
                                    if (count($remainingParts) > 1) {
                                        // Nếu có nhiều phần, phần đầu là số nhà, phần thứ 2 là phường/xã
                                        $soNha = $remainingParts[0];
                                        $xa = $remainingParts[1];
                                    }
                                }
                            } else {
                                // Nếu không có địa chỉ, mặc định là Hà Nội
                                $tinh = 'Hà Nội';
                            }
                        @endphp

                        @php
                            // Kiểm tra xem có địa chỉ từ khách hàng không
                            $hasAddress = !empty($tinh) && !empty($xa);
                        @endphp
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i>Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                @if($hasAddress)
                                    <input type="hidden" name="tinh_thanh" value="{{ $tinh }}">
                                @endif
                                <select class="form-select" id="tinh_thanh" {{ $hasAddress ? 'disabled' : 'name="tinh_thanh"' }} required style="{{ $hasAddress ? 'background-color: #e9ecef; cursor: not-allowed;' : '' }}">
                                    <option value="">-- Chọn tỉnh/thành phố --</option>
                                    <option value="Hà Nội" {{ $tinh == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="Hồ Chí Minh" {{ $tinh == 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                    <option value="Đà Nẵng" {{ $tinh == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                    <option value="Hải Phòng">Hải Phòng</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                    <option value="An Giang">An Giang</option>
                                    <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                                    <option value="Bắc Giang">Bắc Giang</option>
                                    <option value="Bắc Kạn">Bắc Kạn</option>
                                    <option value="Bạc Liêu">Bạc Liêu</option>
                                    <option value="Bắc Ninh">Bắc Ninh</option>
                                    <option value="Bến Tre">Bến Tre</option>
                                    <option value="Bình Định">Bình Định</option>
                                    <option value="Bình Dương">Bình Dương</option>
                                    <option value="Bình Phước">Bình Phước</option>
                                    <option value="Bình Thuận">Bình Thuận</option>
                                    <option value="Cà Mau">Cà Mau</option>
                                    <option value="Cao Bằng">Cao Bằng</option>
                                    <option value="Đắk Lắk">Đắk Lắk</option>
                                    <option value="Đắk Nông">Đắk Nông</option>
                                    <option value="Điện Biên">Điện Biên</option>
                                    <option value="Đồng Nai">Đồng Nai</option>
                                    <option value="Đồng Tháp">Đồng Tháp</option>
                                    <option value="Gia Lai">Gia Lai</option>
                                    <option value="Hà Giang">Hà Giang</option>
                                    <option value="Hà Nam">Hà Nam</option>
                                    <option value="Hà Tĩnh">Hà Tĩnh</option>
                                    <option value="Hải Dương">Hải Dương</option>
                                    <option value="Hậu Giang">Hậu Giang</option>
                                    <option value="Hòa Bình">Hòa Bình</option>
                                    <option value="Hưng Yên">Hưng Yên</option>
                                    <option value="Khánh Hòa">Khánh Hòa</option>
                                    <option value="Kiên Giang">Kiên Giang</option>
                                    <option value="Kon Tum">Kon Tum</option>
                                    <option value="Lai Châu">Lai Châu</option>
                                    <option value="Lâm Đồng">Lâm Đồng</option>
                                    <option value="Lạng Sơn">Lạng Sơn</option>
                                    <option value="Lào Cai">Lào Cai</option>
                                    <option value="Long An">Long An</option>
                                    <option value="Nam Định">Nam Định</option>
                                    <option value="Nghệ An">Nghệ An</option>
                                    <option value="Ninh Bình">Ninh Bình</option>
                                    <option value="Ninh Thuận">Ninh Thuận</option>
                                    <option value="Phú Thọ">Phú Thọ</option>
                                    <option value="Phú Yên">Phú Yên</option>
                                    <option value="Quảng Bình">Quảng Bình</option>
                                    <option value="Quảng Nam">Quảng Nam</option>
                                    <option value="Quảng Ngãi">Quảng Ngãi</option>
                                    <option value="Quảng Ninh">Quảng Ninh</option>
                                    <option value="Quảng Trị">Quảng Trị</option>
                                    <option value="Sóc Trăng">Sóc Trăng</option>
                                    <option value="Sơn La">Sơn La</option>
                                    <option value="Tây Ninh">Tây Ninh</option>
                                    <option value="Thái Bình">Thái Bình</option>
                                    <option value="Thái Nguyên">Thái Nguyên</option>
                                    <option value="Thanh Hóa">Thanh Hóa</option>
                                    <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                                    <option value="Tiền Giang">Tiền Giang</option>
                                    <option value="Trà Vinh">Trà Vinh</option>
                                    <option value="Tuyên Quang">Tuyên Quang</option>
                                    <option value="Vĩnh Long">Vĩnh Long</option>
                                    <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                                    <option value="Yên Bái">Yên Bái</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted mb-1"><i class="fas fa-home me-1"></i>Phường/Xã/Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="xa" name="xa" value="{{ $xa }}" placeholder="Nhập phường/xã hoặc địa chỉ cụ thể" autocomplete="street-address" required {{ $hasAddress ? 'readonly' : '' }} style="{{ $hasAddress ? 'background-color: #e9ecef; cursor: not-allowed;' : '' }}">
                                <div id="xa_suggestions" class="list-group position-absolute" style="z-index:1000; display:none; max-height:200px; overflow-y:auto;"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted mb-1"><i class="fas fa-map-marked-alt me-1"></i>Số nhà, tên đường (không bắt buộc)</label>
                                <input type="text" class="form-control" id="so_nha" name="so_nha" value="{{ $soNha }}" placeholder="Ví dụ: 123 Đường Láng, Đống Đa" autocomplete="address-line1" {{ $hasAddress && !empty($soNha) ? 'readonly' : '' }} style="{{ $hasAddress && !empty($soNha) ? 'background-color: #e9ecef; cursor: not-allowed;' : '' }}">
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
                                                        <img src="{{ $item['book']->image_url }}" alt="{{ $item['book']->ten_sach }}" style="width:50px;height:70px;object-fit:cover;border-radius:4px;margin-right:15px;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                                        @if($item->book)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($item->book->hinh_anh))
                                                        <img src="{{ $item->book->image_url }}" alt="{{ $item->book->ten_sach }}" style="width:50px;height:70px;object-fit:cover;border-radius:4px;margin-right:15px;" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                                // Tính tổng tiền cọc và tiền thuê (nhân với số lượng)
                                $sumTienCoc = $itemsCollect->sum(function($item) {
                                    return ($item['tien_coc'] ?? 0) * ($item['quantity'] ?? 1);
                                });
                                $sumTienThue = $itemsCollect->sum(function($item) {
                                    return ($item['tien_thue'] ?? 0) * ($item['quantity'] ?? 1);
                                });
                                // Note: $totalTienShip và $tongTien đã được tính trong controller
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
                            <span class="summary-label">Giảm giá SP:</span>
                            <span class="summary-value" id="product_discount">-0₫</span>
                        </div>

                        <div class="discount-section mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white" style="border-right:0;"><i class="fas fa-tag" style="color:#64748b"></i></span>
                                <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Nhập mã giảm giá" style="border-left:0;" value="{{ old('discount_code') }}">
                                <button class="btn btn-success" type="button" onclick="applyDiscount()" id="apply_voucher_btn">Áp dụng</button>
                            </div>
                            <div id="voucher_message" class="mt-2" style="font-size:0.85rem;"></div>
                            <input type="hidden" id="voucher_id" name="voucher_id" value="">
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Tạm tính:</span>
                            <span class="summary-value text-primary" id="subtotal_amount">
                                @if(isset($fromUrl) && $fromUrl && isset($items))
                                    @php
                                        $subtotal = ($sumTienCoc ?? 0) + ($sumTienThue ?? 0);
                                    @endphp
                                    {{ number_format($subtotal,0,',','.') }}₫
                                @else
                                    {{ number_format(($totalTienCoc ?? 0) + ($totalTienThue ?? 0),0,',','.') }}₫
                                @endif
                            </span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Giảm giá đơn:</span>
                            <span class="summary-value text-success" id="order_discount">-0₫</span>
                        </div>

                        <div class="summary-row mb-3">
                            <span class="summary-label">Phí vận chuyển:</span>
                            <span class="summary-value" id="shipping-fee-display">
                                {{-- Sử dụng $totalTienShip cho cả 2 trường hợp --}}
                                {{ number_format($totalTienShip ?? 0,0,',','.') }}₫
                            </span>
                        </div>

                        <div id="shipping-info" class="mb-2" style="display: none;">
                            <small class="text-info">
                                <i class="fas fa-map-marker-alt"></i> Khoảng cách: <span id="shipping-distance">0</span> km
                            </small>
                        </div>

                        <div class="shipping-info-box mb-3">
                            <small style="color:#92400e;line-height:1.5;display:block;">
                                <i class="fas fa-info-circle me-1"></i> Phí ship tính từ Cao đẳng FPT Polytechnic Hà Nội. Miễn phí 5km đầu, từ km thứ 6 trở đi mỗi km thêm 5.000₫. Phí sẽ được tính tự động dựa trên địa chỉ bạn nhập.
                                </small>
                        </div>

                        <div class="summary-row total-payment mb-4">
                            <span class="summary-label" style="font-size:1.1rem;font-weight:600">Thanh toán:</span>
                            <span class="summary-value" style="font-size:1.4rem;font-weight:700;color:#ef4444" id="total_payment">
                                {{-- Sử dụng $tongTien đã được tính trong controller cho cả 2 trường hợp --}}
                                {{ number_format($tongTien ?? 0,0,',','.') }}₫
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

@section('scripts')
<script>
console.log('📄 Checkout page script loaded');
(function(){
    console.log('🔄 IIFE started - Initializing checkout page...');
    // Address autocomplete data (Vietnamese common addresses)
    const vietnamAddresses = {
        'Hà Nội': [
            'Ba Đình', 'Hoàn Kiếm', 'Tây Hồ', 'Long Biên', 'Cầu Giấy', 'Đống Đa', 'Hai Bà Trưng', 'Hoàng Mai', 
            'Thanh Xuân', 'Sóc Sơn', 'Đông Anh', 'Gia Lâm', 'Nam Từ Liêm', 'Bắc Từ Liêm', 'Hà Đông', 'Mê Linh',
            'Láng Hạ', 'Khâm Thiên', 'Phố Huế', 'Giảng Võ', 'Kim Mã', 'Nguyễn Chí Thanh', 'Xuân Thủy'
        ],
        'Hồ Chí Minh': [
            'Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10',
            'Quận 11', 'Quận 12', 'Bình Thạnh', 'Gò Vấp', 'Phú Nhuận', 'Tân Bình', 'Tân Phú', 'Bình Tân',
            'Thủ Đức', 'Nhà Bè', 'Cần Giờ', 'Hóc Môn', 'Củ Chi', 'Bình Chánh'
        ],
        'Đà Nẵng': [
            'Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ', 'Hòa Vang', 'Hoàng Sa'
        ]
    };

    function initAddressAutocomplete() {
        const xaInput = document.getElementById('xa');
        const tinhSelect = document.getElementById('tinh_thanh');
        const suggestionsDiv = document.getElementById('xa_suggestions');
        
        if (!xaInput || !tinhSelect || !suggestionsDiv) return;

        xaInput.addEventListener('input', function() {
            const value = this.value.trim();
            const selectedCity = tinhSelect.value;
            
            if (value.length < 2 || !selectedCity || !vietnamAddresses[selectedCity]) {
                suggestionsDiv.style.display = 'none';
                return;
            }

            const suggestions = vietnamAddresses[selectedCity].filter(addr => 
                addr.toLowerCase().includes(value.toLowerCase())
            );

            if (suggestions.length === 0) {
                suggestionsDiv.style.display = 'none';
                return;
            }

            suggestionsDiv.innerHTML = suggestions.map(suggestion => 
                `<button type="button" class="list-group-item list-group-item-action" data-value="${suggestion}">${suggestion}</button>`
            ).join('');
            
            suggestionsDiv.style.display = 'block';
            suggestionsDiv.style.width = xaInput.offsetWidth + 'px';
        });

        suggestionsDiv.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON') {
                xaInput.value = e.target.getAttribute('data-value');
                suggestionsDiv.style.display = 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target !== xaInput && e.target !== suggestionsDiv) {
                suggestionsDiv.style.display = 'none';
            }
        });

        // Chỉ bật autocomplete nếu cho phép tính lại phí ship
        @if(!$hasAddress || ($totalTienShip ?? 0) == 0)
        tinhSelect.addEventListener('change', function() {
            // Không xóa giá trị phường/xã khi đổi tỉnh/thành phố
            // Chỉ ẩn suggestions và tính lại phí nếu địa chỉ đã đầy đủ
            suggestionsDiv.style.display = 'none';
            
            // Chỉ tính lại phí nếu cả tỉnh và xã đều có giá trị
            const tinh = this.value?.trim() || '';
            const xa = xaInput?.value?.trim() || '';
            if (tinh && xa && xa.length >= 2) {
                calculateShippingFee(); // Tính lại phí khi đổi tỉnh/thành
            }
        });
        @endif
    }

    // Tính phí vận chuyển tự động
    function initShippingFeeCalculation() {
        console.log('Initializing shipping fee calculation...');
        const tinhSelect = document.getElementById('tinh_thanh');
        const xaInput = document.getElementById('xa');
        const soNhaInput = document.getElementById('so_nha');
        const shippingFeeDisplay = document.getElementById('shipping-fee-display');
        const shippingInfo = document.getElementById('shipping-info');
        const shippingDistance = document.getElementById('shipping-distance');
        const totalPaymentEl = document.getElementById('total_payment');
        
        console.log('Elements found:', {
            tinhSelect: !!tinhSelect,
            xaInput: !!xaInput,
            soNhaInput: !!soNhaInput,
            shippingFeeDisplay: !!shippingFeeDisplay,
            shippingInfo: !!shippingInfo,
            shippingDistance: !!shippingDistance,
            totalPaymentEl: !!totalPaymentEl
        });
        
        let shippingFee = {{ $totalTienShip ?? 0 }};
        let calculateTimeout = null;
        
        // Khởi tạo window.currentShippingFee với giá trị ban đầu
        if (window.currentShippingFee === undefined) {
            window.currentShippingFee = shippingFee;
        }

        // Expose function to window để có thể gọi từ bên ngoài
        window.calculateShippingFee = function() {
            // Lấy lại giá trị từ DOM mỗi lần gọi để đảm bảo có giá trị mới nhất
            const tinhSelectEl = document.getElementById('tinh_thanh');
            const xaInputEl = document.getElementById('xa');
            const soNhaInputEl = document.getElementById('so_nha');
            
            const tinh = tinhSelectEl?.value?.trim() || '';
            const xa = xaInputEl?.value?.trim() || '';
            const soNha = soNhaInputEl?.value?.trim() || '';

            console.log('🚚 Calculating shipping fee:', { 
                tinh, 
                xa, 
                soNha,
                tinhLength: tinh.length,
                xaLength: xa.length,
                hasTinhSelect: !!tinhSelectEl,
                hasXaInput: !!xaInputEl,
                tinhSelectValue: tinhSelectEl?.value,
                xaInputValue: xaInputEl?.value
            });

            // Kiểm tra địa chỉ có đầy đủ không (cần ít nhất tỉnh và xã)
            if (!tinh || !xa || xa.length < 2) {
                // Địa chỉ chưa đầy đủ, không gọi API
                // Nhưng giữ lại phí ship cũ nếu đã có (từ giỏ hàng hoặc tính trước đó)
                if (shippingInfo) shippingInfo.style.display = 'none';
                
                // Chỉ reset về 0 nếu chưa có phí ship nào được tính trước đó
                if (window.currentShippingFee === undefined || window.currentShippingFee === 0) {
                    shippingFee = 0;
                    window.currentShippingFee = 0;
                    window.currentDistance = 0;
                    if (shippingFeeDisplay) {
                        shippingFeeDisplay.textContent = '0₫';
                    }
                } else {
                    // Giữ lại phí ship cũ và hiển thị
                    shippingFee = window.currentShippingFee;
                    if (shippingFeeDisplay) {
                        shippingFeeDisplay.textContent = new Intl.NumberFormat('vi-VN').format(shippingFee) + '₫';
                    }
                }
                updateTotalPayment();
                return;
            }

            // Ghép địa chỉ đầy đủ
            let fullAddress = '';
            if (soNha) fullAddress += soNha + ', ';
            if (xa) fullAddress += xa + ', ';
            if (tinh) fullAddress += tinh + ', Việt Nam';

            console.log('📍 Full address:', fullAddress, 'Length:', fullAddress.length);

            if (!fullAddress || fullAddress.length < 10) {
                // Địa chỉ quá ngắn
                if (shippingInfo) shippingInfo.style.display = 'none';
                shippingFee = 0;
                window.currentShippingFee = 0;
                window.currentDistance = 0;
                if (shippingFeeDisplay) {
                    shippingFeeDisplay.textContent = '0₫';
                }
                updateTotalPayment();
                return;
            }

            // Hiển thị đang tính
            if (shippingFeeDisplay) {
                shippingFeeDisplay.textContent = 'Đang tính...';
            }

            console.log('Calling API with address:', fullAddress);

            // Gọi API tính phí
            fetch('/api/shipping/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                   document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify({ address: fullAddress })
            })
            .then(response => {
                // Chỉ log khi response không OK và chưa log lỗi trước đó
                if (!response.ok && !window.shippingErrorLogged) {
                    console.warn('⚠️ API Response not OK:', response.status, response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('📦 API Response data:', data);
                if (data.success) {
                    shippingFee = data.shipping_fee || 0;
                    const distance = data.distance || 0;
                    
                    // Đồng bộ với biến global
                    window.currentShippingFee = shippingFee;
                    window.currentDistance = distance;
                    
                    // Chỉ log khi có phí > 0 hoặc có message quan trọng
                    if (shippingFee > 0 || (data.message && !data.message.includes('chưa cấu hình'))) {
                        console.log('✅ Shipping fee calculated successfully:', shippingFee, '₫, Distance:', distance, 'km');
                    }
                    
                    if (shippingFeeDisplay) {
                        shippingFeeDisplay.textContent = new Intl.NumberFormat('vi-VN').format(shippingFee) + '₫';
                    }
                    
                    if (shippingInfo && shippingDistance) {
                        if (distance > 0) {
                            shippingDistance.textContent = distance.toFixed(2);
                            shippingInfo.style.display = 'block';
                        } else {
                            shippingInfo.style.display = 'none';
                        }
                    }
                } else {
                    // Khi API trả về lỗi, đặt phí = 0 và không hiển thị lỗi (để tránh spam)
                    shippingFee = 0;
                    window.currentShippingFee = 0;
                    window.currentDistance = 0;
                    if (shippingFeeDisplay) {
                        shippingFeeDisplay.textContent = '0₫';
                    }
                    if (shippingInfo) {
                        shippingInfo.style.display = 'none';
                    }
                    // Chỉ log lỗi một lần, không spam
                    if (!window.shippingErrorLogged) {
                        console.warn('⚠️ Shipping calculation failed:', data.message || 'Unknown error');
                        window.shippingErrorLogged = true;
                    }
                }
                updateTotalPayment();
            })
            .catch(error => {
                // Chỉ log lỗi một lần để tránh spam
                if (!window.shippingErrorLogged) {
                    console.warn('⚠️ Error calculating shipping:', error.message);
                    window.shippingErrorLogged = true;
                }
                shippingFee = 0;
                window.currentShippingFee = 0;
                window.currentDistance = 0;
                if (shippingFeeDisplay) {
                    shippingFeeDisplay.textContent = '0₫';
                }
                if (shippingInfo) {
                    shippingInfo.style.display = 'none';
                }
                updateTotalPayment();
            });
        }

        function updateTotalPayment() {
            if (!totalPaymentEl) return;
            
            // Lấy các giá trị từ DOM
            const tienCoc = parseFloat('{{ $totalTienCoc ?? 0 }}') || 0;
            const tienThue = parseFloat('{{ $totalTienThue ?? 0 }}') || 0;
            const orderDiscountEl = document.getElementById('order_discount');
            let orderDiscount = 0;
            
            if (orderDiscountEl) {
                const discountText = orderDiscountEl.textContent.replace(/[^\d]/g, '');
                orderDiscount = parseFloat(discountText) || 0;
            }
            
            // Sử dụng shippingFee từ biến global hoặc từ DOM
            const currentShippingFee = window.currentShippingFee !== undefined ? window.currentShippingFee : shippingFee;
            
            const total = tienCoc + tienThue + currentShippingFee - orderDiscount;
            totalPaymentEl.textContent = new Intl.NumberFormat('vi-VN').format(Math.max(0, total)) + '₫';
        }

        // Lắng nghe sự kiện thay đổi địa chỉ (chỉ nếu cho phép tính lại)
        if (tinhSelect && allowRecalculate) {
            tinhSelect.addEventListener('change', function() {
                console.log('Tỉnh/Thành phố changed:', this.value);
                clearTimeout(calculateTimeout);
                calculateTimeout = setTimeout(calculateShippingFee, 1000);
            });
            
            // Trigger change event nếu đã có giá trị mặc định
            if (tinhSelect.value) {
                setTimeout(() => {
                    console.log('Triggering change event for tinh_thanh with value:', tinhSelect.value);
                    tinhSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }, 200);
            }
        }

        if (xaInput && allowRecalculate) {
            xaInput.addEventListener('input', function() {
                clearTimeout(calculateTimeout);
                calculateTimeout = setTimeout(calculateShippingFee, 1000);
            });
            
            xaInput.addEventListener('blur', function() {
                clearTimeout(calculateTimeout);
                calculateShippingFee();
            });
        }

        if (soNhaInput && allowRecalculate) {
            soNhaInput.addEventListener('input', function() {
                clearTimeout(calculateTimeout);
                calculateTimeout = setTimeout(calculateShippingFee, 1000);
            });
            
            soNhaInput.addEventListener('blur', function() {
                clearTimeout(calculateTimeout);
                calculateShippingFee();
            });
        }

        // Tính phí ngay nếu đã có địa chỉ (chỉ nếu cho phép tính lại)
        function autoCalculateOnLoad() {
            // Nếu đã có phí ship từ giỏ hàng, không tính lại
            if (!allowRecalculate && window.initialShippingFee > 0) {
                console.log('⚠️ Shipping fee from cart is locked, skipping auto calculate');
                return false;
            }
            // Lấy lại từ DOM để đảm bảo có giá trị mới nhất
            const tinhSelectEl = document.getElementById('tinh_thanh');
            const xaInputEl = document.getElementById('xa');
            
            const tinh = tinhSelectEl?.value?.trim() || '';
            const xa = xaInputEl?.value?.trim() || '';
            
            console.log('Auto calculate check:', {
                tinhSelect: !!tinhSelectEl,
                xaInput: !!xaInputEl,
                tinh: tinh,
                xa: xa,
                tinhLength: tinh.length,
                xaLength: xa.length,
                tinhSelectValue: tinhSelectEl?.value,
                xaInputValue: xaInputEl?.value
            });
            
            if (tinh && xa) {
                const soNhaInputEl = document.getElementById('so_nha');
                console.log('✅ Address already filled, calculating immediately...', {
                    tinh: tinh,
                    xa: xa,
                    soNha: soNhaInputEl?.value?.trim() || ''
                });
                calculateShippingFee();
                return true;
            } else {
                console.log('⚠️ No address filled yet, waiting for user input...', {
                    hasTinh: !!tinh,
                    hasXa: !!xa,
                    tinhValue: tinh,
                    xaValue: xa
                });
                return false;
            }
        }
        
        // Thử tính ngay khi DOM ready
        function initAutoCalculate() {
            // Thử nhiều lần để đảm bảo
            setTimeout(() => {
                if (autoCalculateOnLoad()) {
                    console.log('✅ Shipping fee calculated on first try');
                } else {
                    // Thử lại sau 500ms
                    setTimeout(() => {
                        if (autoCalculateOnLoad()) {
                            console.log('✅ Shipping fee calculated on second try');
                        } else {
                            // Thử lại sau 1 giây nữa
                            setTimeout(() => {
                                if (autoCalculateOnLoad()) {
                                    console.log('✅ Shipping fee calculated on third try');
                                } else {
                                    // Thử lại sau 2 giây nữa
                                    setTimeout(() => {
                                        autoCalculateOnLoad();
                                        console.log('✅ Shipping fee calculated on fourth try');
                                    }, 2000);
                                }
                            }, 1000);
                        }
                    }, 500);
                }
            }, 500);
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAutoCalculate);
        } else {
            initAutoCalculate();
        }
        
        // Thử lại sau 3 giây để đảm bảo (nếu vẫn chưa có phí ship)
        setTimeout(() => {
            const currentFee = window.currentShippingFee || 0;
            const displayText = shippingFeeDisplay?.textContent || '0₫';
            console.log('Final check - Current shipping fee:', currentFee, 'Display:', displayText);
            
            if (currentFee === 0 && displayText === '0₫') {
                console.log('⚠️ Still no shipping fee, trying one more time...');
                autoCalculateOnLoad();
            }
        }, 3000);
    }


    // Hàm test để tính phí thủ công (có thể gọi từ console hoặc button)
    window.testCalculateShipping = function() {
        console.log('Manual test: Calculating shipping fee...');
        const tinhSelect = document.getElementById('tinh_thanh');
        const xaInput = document.getElementById('xa');
        const soNhaInput = document.getElementById('so_nha');
        
        const tinh = tinhSelect?.value?.trim() || '';
        const xa = xaInput?.value?.trim() || '';
        const soNha = soNhaInput?.value?.trim() || '';
        
        let fullAddress = '';
        if (soNha) fullAddress += soNha + ', ';
        if (xa) fullAddress += xa + ', ';
        if (tinh) fullAddress += tinh + ', Việt Nam';
        
        console.log('Test address:', fullAddress);
        
        if (!fullAddress || fullAddress.length < 10) {
            alert('Vui lòng nhập địa chỉ đầy đủ (Tỉnh/Thành phố và Phường/Xã)');
            return;
        }
        
        const shippingFeeDisplay = document.getElementById('shipping-fee-display');
        if (shippingFeeDisplay) {
            shippingFeeDisplay.textContent = 'Đang tính...';
        }
        
        fetch('/api/shipping/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({ address: fullAddress })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Test API Response:', data);
            if (data.success) {
                const fee = data.shipping_fee || 0;
                const distance = data.distance || 0;
                if (shippingFeeDisplay) {
                    shippingFeeDisplay.textContent = new Intl.NumberFormat('vi-VN').format(fee) + '₫';
                }
                const shippingInfo = document.getElementById('shipping-info');
                const shippingDistance = document.getElementById('shipping-distance');
                if (shippingInfo && shippingDistance) {
                    shippingDistance.textContent = distance.toFixed(2);
                    shippingInfo.style.display = 'block';
                }
                alert(`Tính phí thành công!\nKhoảng cách: ${distance.toFixed(2)} km\nPhí vận chuyển: ${new Intl.NumberFormat('vi-VN').format(fee)} VNĐ`);
            } else {
                alert('Không thể tính phí: ' + (data.message || 'Lỗi không xác định'));
            }
        })
        .catch(error => {
            console.error('Test API Error:', error);
            alert('Lỗi: ' + error.message);
        });
    };

    function initBorrowCheckout(){
        console.log('🎯 initBorrowCheckout() called');
        const form = document.getElementById('borrowCheckoutForm');
        const btn = document.getElementById('confirmBorrowBtn');
        if(!form || !btn) {
            console.error('❌ Form or button not found!', { form: !!form, btn: !!btn });
            return false;
        }
        console.log('✅ Form and button found');

        // Initialize address autocomplete
        try {
        initAddressAutocomplete();
            console.log('✅ Address autocomplete initialized');
        } catch (error) {
            console.error('❌ Error initializing address autocomplete:', error);
        }

        // Initialize shipping fee calculation
        try {
            console.log('🔄 About to call initShippingFeeCalculation()...');
            if (typeof initShippingFeeCalculation === 'function') {
            initShippingFeeCalculation();
                console.log('✅ Shipping fee calculation initialized successfully');
            } else {
                console.error('❌ initShippingFeeCalculation is not a function!', typeof initShippingFeeCalculation);
            }
            
            // Force check và tính phí ship sau khi init xong
            setTimeout(() => {
                const tinhSelectEl = document.getElementById('tinh_thanh');
                const xaInputEl = document.getElementById('xa');
                if (tinhSelectEl && xaInputEl) {
                    const tinh = tinhSelectEl.value?.trim() || '';
                    const xa = xaInputEl.value?.trim() || '';
                    console.log('🔍 Force check after init:', { 
                        tinh, 
                        xa, 
                        hasBoth: !!(tinh && xa),
                        tinhSelectValue: tinhSelectEl.value,
                        xaInputValue: xaInputEl.value
                    });
                    
                    if (tinh && xa && (!window.currentShippingFee || window.currentShippingFee === 0)) {
                        console.log('🔄 Force calculating shipping fee...');
                        // Đợi một chút để đảm bảo hàm đã được định nghĩa
                        setTimeout(() => {
                            if (window.calculateShippingFee && typeof window.calculateShippingFee === 'function') {
                                console.log('✅ Calling calculateShippingFee function');
                                window.calculateShippingFee();
                            } else {
                                console.log('⚠️ calculateShippingFee not available yet, will retry in autoCalculateOnLoad');
                            }
                        }, 300);
                    }
                }
            }, 1500);
        } catch (error) {
            console.error('❌ Error initializing shipping fee calculation:', error);
            console.error('Error stack:', error.stack);
        }
        
        // Đánh dấu đã khởi tạo
        window.checkoutInitialized = true;

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

        form.addEventListener('submit', async function(e){
            e.preventDefault(); e.stopPropagation();
            const original = btn.innerHTML;
            const name = document.getElementById('reader_name')?.value?.trim();
            const phone = document.getElementById('reader_phone')?.value?.trim();
            const email = document.getElementById('reader_email')?.value?.trim();
            const birthday = document.getElementById('reader_birthday')?.value?.trim();
            const gender = document.getElementById('reader_gender')?.value?.trim();
            const tinhThanh = document.getElementById('tinh_thanh')?.value?.trim();
            const xa = document.getElementById('xa')?.value?.trim();
            const payment = document.querySelector('input[name="payment_method"]:checked');

            if(!name){ showToast('error','Vui lòng nhập họ và tên'); return; }
            if(!phone){ showToast('error','Vui lòng nhập số điện thoại'); return; }
            if(!email){ showToast('error','Vui lòng nhập email'); return; }
            if(!birthday){ showToast('error','Vui lòng nhập ngày sinh'); return; }
            if(!gender){ showToast('error','Vui lòng chọn giới tính'); return; }
            if(!tinhThanh){ showToast('error','Vui lòng chọn tỉnh/thành phố'); return; }
            if(!xa){ showToast('error','Vui lòng nhập phường/xã/địa chỉ'); return; }
            if(!payment){ showToast('error','Vui lòng chọn phương thức thanh toán'); return; }
            if(!confirm('Bạn có chắc chắn muốn mượn tất cả các sách này?')) return;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...'; btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
            if(!csrfToken){ showToast('error','Không tìm thấy token bảo mật. Vui lòng tải lại trang.'); btn.innerHTML = original; btn.disabled = false; return; }

            // Đảm bảo tính phí ship trước khi submit nếu chưa có
            if (window.currentShippingFee === undefined || window.currentShippingFee === null) {
                // Nếu chưa tính, thử tính ngay
                if (window.calculateShippingFee && typeof window.calculateShippingFee === 'function' && tinhThanh && xa) {
                    console.log('🔄 Calculating shipping fee before submit...');
                    window.calculateShippingFee();
                    // Đợi một chút để API response (tối đa 2 giây)
                    await new Promise(resolve => setTimeout(resolve, 2000));
                }
            }
            
            const data = new FormData(form);
            
            // Thêm phí ship (luôn gửi, kể cả khi = 0 để server biết đã tính)
            const shippingFeeToSend = window.currentShippingFee !== undefined && window.currentShippingFee !== null ? window.currentShippingFee : 0;
            data.append('manual_shipping_fee', shippingFeeToSend);
            console.log('📦 Sending shipping fee:', shippingFeeToSend, 'currentShippingFee:', window.currentShippingFee);

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
            const btn = document.getElementById('apply_voucher_btn');
            const messageDiv = document.getElementById('voucher_message');
            
            if(!code){ 
                showToast('error','Vui lòng nhập mã giảm giá'); 
                return; 
            }
            
            // Lấy tổng tiền hiện tại (tạm tính)
            let totalAmount = 0;
            @if(isset($fromUrl) && $fromUrl && isset($items))
                totalAmount = {{ ($sumTienCoc ?? 0) + ($sumTienThue ?? 0) }};
            @else
                totalAmount = {{ ($totalTienCoc ?? 0) + ($totalTienThue ?? 0) }};
            @endif
            
            // Disable button
            if(btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }
            
            // Clear message
            if(messageDiv) messageDiv.innerHTML = '';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
            
            fetch('{{ route("borrow-cart.apply-voucher") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    voucher_code: code,
                    total_amount: totalAmount
                })
            })
            .then(async res => {
                const data = await res.json();
                
                if(btn) {
                    btn.disabled = false;
                    btn.innerHTML = 'Áp dụng';
                }
                
                if(!res.ok || !data.success) {
                    const errorMsg = data.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá';
                    showToast('error', errorMsg);
                    if(messageDiv) {
                        messageDiv.innerHTML = '<span style="color:#ef4444;"><i class="fas fa-times-circle"></i> ' + errorMsg + '</span>';
                    }
                    // Reset voucher
                    document.getElementById('voucher_id').value = '';
                    updateDiscountDisplay(0, totalAmount);
                    return;
                }
                
                // Áp dụng thành công
                showToast('success', data.message || 'Áp dụng mã giảm giá thành công');
                
                // Lưu voucher_id
                document.getElementById('voucher_id').value = data.voucher.id;
                
                // Hiển thị thông báo
                if(messageDiv) {
                    messageDiv.innerHTML = '<span style="color:#10b981;"><i class="fas fa-check-circle"></i> Đã áp dụng mã: ' + data.voucher.code + '</span>';
                }
                
                // Cập nhật UI
                updateDiscountDisplay(data.discount_amount, data.final_amount, totalAmount);
            })
            .catch(err => {
                console.error('Error applying voucher:', err);
                showToast('error', 'Lỗi kết nối: ' + (err.message || 'Không thể kết nối đến server'));
                if(btn) {
                    btn.disabled = false;
                    btn.innerHTML = 'Áp dụng';
                }
                if(messageDiv) messageDiv.innerHTML = '';
            });
        };
        
        function updateDiscountDisplay(discountAmount, finalAmount, originalAmount) {
            // Cập nhật giảm giá đơn
            const orderDiscountEl = document.getElementById('order_discount');
            if(orderDiscountEl) {
                orderDiscountEl.textContent = '-' + formatCurrency(discountAmount);
                orderDiscountEl.style.color = discountAmount > 0 ? '#10b981' : '#64748b';
            }
            
            // Cập nhật tổng thanh toán
            const totalPaymentEl = document.getElementById('total_payment');
            if(totalPaymentEl) {
                totalPaymentEl.textContent = formatCurrency(finalAmount) + '₫';
            }
        }
        
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(Math.round(amount));
        }

        return true;
    }

    console.log('📋 Document readyState:', document.readyState);
    if(document.readyState === 'loading'){
        console.log('⏳ Document still loading, waiting for DOMContentLoaded...');
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ DOMContentLoaded fired, calling initBorrowCheckout()');
            initBorrowCheckout();
        });
    } else {
        console.log('✅ Document already loaded, calling initBorrowCheckout() immediately');
        initBorrowCheckout();
    }
    
    // Fallback: Đảm bảo init chạy sau 2 giây nếu chưa chạy
    setTimeout(function() {
        if (!window.checkoutInitialized) {
            console.log('⚠️ Fallback: initBorrowCheckout not called yet, calling now...');
            window.checkoutInitialized = true;
            initBorrowCheckout();
        }
    }, 2000);
})();
console.log('📄 Checkout page script execution completed');
</script>
@endsection