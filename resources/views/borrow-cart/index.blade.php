<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giỏ sách - Nhà Xuất Bản Xây Dựng</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .cart-page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
        }
        
        .page-header {
            margin-bottom: 25px;
        }
        
        .page-title {
            font-size: 32px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 10px;
            margin-top: 15px;
        }
        
        .breadcrumb {
            color: #666;
            font-size: 14px;
        }
        
        .breadcrumb a {
            color: #0066cc;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        
        /* Content Wrapper: Items and Summary Side by Side */
        .cart-content-wrapper {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        /* Cart Items Container (Left) */
        .cart-items-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }
        
        /* Header Section inside card */
        .cart-header-section {
            padding: 25px 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .cart-title-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .cart-main-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            letter-spacing: 0.5px;
        }
        
        .buy-more-link {
            color: #ff9800;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.2s;
        }
        
        .buy-more-link:hover {
            color: #f57c00;
            text-decoration: underline;
        }
        
        .cart-select-all-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .select-all-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .select-all-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #ff9800;
        }
        
        .select-all-label {
            font-weight: 500;
            color: #333;
            cursor: pointer;
            font-size: 15px;
        }
        
        .cart-column-headers {
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
        }
        
        .column-header {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            min-width: 100px;
            text-align: center;
        }
        
        .column-header:nth-child(1) {
            min-width: 120px;
        }
        
        .column-header:nth-child(2) {
            min-width: 200px;
        }
        
        .column-header:nth-child(3) {
            min-width: 160px;
        }
        
        .column-header:nth-child(4) {
            min-width: 60px;
        }
        
        /* Cart Items List */
        .cart-items-list {
            display: flex;
            flex-direction: column;
        }
        
        /* Summary Container (Right) */
        .cart-summary-container {
            position: sticky;
            top: 20px;
            height: fit-content;
        }
        
        .summary-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }
        
        .summary-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1a1a1a;
        }
        
        .summary-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .fee-details-section {
            background: #e3f2fd;
            padding: 18px;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
            margin-bottom: 20px;
        }
        
        .fee-details-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1565C0;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .fee-details-header i {
            font-size: 14px;
        }
        
        .fee-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .fee-label {
            color: #333;
        }
        
        .fee-value {
            color: #2196F3;
            font-weight: 600;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .summary-row.final-row {
            border-bottom: none;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 2px solid #e0e0e0;
        }
        
        .summary-label-text {
            color: #333;
        }
        
        .summary-value-text {
            color: #2196F3;
            font-weight: 600;
        }
        
        .summary-label-bold {
            font-weight: 700;
            color: #1a1a1a;
            font-size: 16px;
        }
        
        .summary-value-final {
            color: #ff9800;
            font-weight: 700;
            font-size: 18px;
        }
        
        .discount-code-section {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }
        
        .discount-input-wrapper {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 6px;
            padding: 0 12px;
        }
        
        .discount-input-wrapper i {
            color: #ffc107;
            margin-right: 8px;
            font-size: 14px;
        }
        
        .discount-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 0;
            font-size: 14px;
            outline: none;
        }
        
        .discount-input::placeholder {
            color: #999;
        }
        
        .btn-apply-discount {
            padding: 10px 20px;
            background: #4caf50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .btn-apply-discount:hover {
            background: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
        }
        
        .btn-checkout-new {
            width: 100%;
            padding: 14px;
            background: #ff9800;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
            margin-top: 20px;
        }
        
        .btn-checkout-new:hover {
            background: #f57c00;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 152, 0, 0.4);
        }
        
        .btn-checkout-new i {
            font-size: 16px;
        }
    </style>
</head>
<body>
    @include('account._header')
    
    <div class="cart-page-container">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Trang chủ</a> / Giỏ sách
            </div>
        </div>
        
        @if(!$cart || $cart->items->count() === 0)
            <div class="empty-state" style="text-align: center; padding: 60px 20px; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="font-size: 64px; margin-bottom: 20px;">🛒</div>
                <h3 style="font-size: 24px; margin-bottom: 10px; color: #333;">Giỏ sách của bạn đang trống</h3>
                <p style="color: #666; margin-bottom: 30px;">Hãy thêm sách vào giỏ sách để mượn!</p>
                <a href="{{ route('books.public') }}" class="btn-primary" style="display: inline-block; padding: 12px 30px; background: #e51d2e; color: white; text-decoration: none; border-radius: 4px; font-weight: 500;">Xem danh sách sách</a>
            </div>
        @else
            <div class="cart-content-wrapper">
                <!-- Cart Items Container (Left) -->
                <div class="cart-items-container">
                    <!-- Header Section inside card -->
                    <div class="cart-header-section">
                        <div class="cart-title-section">
                            <h2 class="cart-main-title">GIỎ SÁCH CỦA BẠN</h2>
                            <a href="{{ route('books.public') }}" class="buy-more-link">
                                Mua thêm >
                            </a>
                        </div>
                        <div class="cart-select-all-row">
                            <div class="select-all-wrapper">
                                <input type="checkbox" id="select-all-items" class="select-all-checkbox" onchange="toggleSelectAllItems()">
                                <label for="select-all-items" class="select-all-label">
                                    Tất cả ( {{ $cart->getTotalItemsAttribute() }} sản phẩm )
                                </label>
                            </div>
                        <div class="cart-column-headers">
                            <span class="column-header">Giá sách</span>
                            <span class="column-header">Số lượng</span>
                            <span class="column-header">Thành tiền</span>
                            <span class="column-header">Xóa</span>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Cart Items List -->
                    <div class="cart-items-list">
                        @foreach($cart->items as $item)
                        @php
                            $book = $item->book;
                            $availableCopies = \App\Models\Inventory::where('book_id', $book->id)
                                ->where('status', 'Co san')
                                ->count();
                            
                            // Sử dụng giá đã lưu trong database thay vì tính lại
                            $itemFees = [
                                'tien_coc' => $item->tien_coc ?? 0,
                                'tien_thue' => $item->tien_thue ?? 0,
                            ];
                            $borrowDays = $item->borrow_days ?? 14;
                            $itemTienShip = 0;
                            if ($loop->first && $item->distance > 5) {
                                $extraKm = $item->distance - 5;
                                $itemTienShip = (int) ($extraKm * 5000);
                            }
                        @endphp
                        <div class="cart-item" 
                             data-item-id="{{ $item->id }}"
                             data-tien-thue="{{ $itemFees['tien_thue'] * $item->quantity }}"
                             data-tien-coc="{{ $itemFees['tien_coc'] * $item->quantity }}"
                             data-tien-ship="{{ $itemTienShip }}"
                             data-borrow-days="{{ $borrowDays }}"
                             data-is-first="{{ $loop->first ? '1' : '0' }}">
                            <div class="cart-item-checkbox-wrapper">
                                <input type="checkbox" class="item-checkbox" data-item-id="{{ $item->id }}" {{ $item->is_selected ? 'checked' : '' }} onchange="handleCheckboxChange(this)">
                            </div>
                            <div class="cart-item-image">
                                @if($book->hinh_anh)
                                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                                @else
                                    <div class="book-placeholder">📖</div>
                                @endif
                            </div>
                            <div class="cart-item-info">
                                <h3 class="cart-item-title">
                                    <a href="{{ route('books.show', $book->id) }}">{{ $book->ten_sach }}</a>
                                </h3>
                                <p class="cart-item-author">{{ $book->tac_gia ?? 'N/A' }}</p>
                                <p class="cart-item-category">{{ $book->category->ten_the_loai ?? 'N/A' }}</p>
                            </div>
                            <div class="cart-item-price-column">
                                <span class="item-original-price">
                                    {{ number_format($book->gia ?? 0, 0, ',', '.') }}₫
                                </span>
                            </div>
                            <div class="cart-item-quantity-column">
                                <div class="quantity-controls">
                                    <button type="button" class="btn-quantity" onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                    <input type="number" 
                                           id="quantity-{{ $item->id }}" 
                                           value="{{ $item->quantity }}" 
                                           min="1" 
                                           max="{{ $availableCopies }}"
                                           onchange="updateQuantityInput({{ $item->id }})"
                                           class="quantity-input">
                                    <button type="button" class="btn-quantity" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                </div>
                                <span class="quantity-max">/ {{ $availableCopies }} cuốn có sẵn</span>
                                
                                <div class="detail-row-inline">
                                    <label>Số ngày mượn:</label>
                                    <select id="borrow-days-{{ $item->id }}" 
                                            onchange="updateBorrowDays({{ $item->id }})"
                                            class="form-select borrow-days-select">
                                        @for($i = 7; $i <= 30; $i++)
                                            <option value="{{ $i }}" {{ $item->borrow_days == $i ? 'selected' : '' }}>
                                                {{ $i }} ngày
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="cart-item-subtotal-column">
                                @php
                                    // Sử dụng giá đã lưu trong database
                                    $tienCoc = $item->tien_coc ?? 0;
                                    $tienThue = $item->tien_thue ?? 0;
                                    $tienShip = 0;
                                    if ($item->distance > 5) {
                                        $extraKm = $item->distance - 5;
                                        $tienShip = (int) ($extraKm * 5000);
                                    }
                                    $itemTotal = ($tienThue + $tienCoc) * $item->quantity;
                                    if ($loop->first) {
                                        $itemTotal += $tienShip;
                                    }
                                @endphp
                                <span class="item-subtotal" id="subtotal-{{ $item->id }}">
                                    {{ number_format($itemTotal, 0, ',', '.') }}₫
                                </span>
                                
                                <div class="detail-row-inline">
                                    <label>Khoảng cách (km):</label>
                                    <input type="number" 
                                           id="distance-{{ $item->id }}" 
                                           value="{{ $item->distance }}" 
                                           min="0" 
                                           step="0.1"
                                           onchange="updateDistance({{ $item->id }})"
                                           class="form-control distance-input"
                                           placeholder="Nhập khoảng cách">
                                </div>
                            </div>
                            <div class="cart-item-delete-column">
                                <button type="button" 
                                        class="btn-delete-item" 
                                        onclick="removeItem({{ $item->id }})"
                                        title="Xóa khỏi giỏ sách">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Summary Section (Right) -->
                <div class="cart-summary-container">
                    <div class="summary-card">
                        <h3 class="summary-title">Tóm tắt đơn hàng</h3>
                        <div class="summary-subtitle">(Mode: borrow)</div>
                        
                        @php
                            $reader = auth()->user()->reader ?? null;
                            $hasCard = $reader ? true : false;
                            
                            $totalTienThue = 0;
                            $totalTienCoc = 0;
                            $totalTienShip = 0;
                            $totalBorrowDays = 0;
                            $itemCount = 0;
                            $isFirstSelectedItem = true;
                            
                            // CHỈ TÍNH CHO CÁC ITEMS ĐÃ ĐƯỢC CHỌN
                            $selectedItems = $cart->items->where('is_selected', true);
                            
                            foreach($selectedItems as $index => $item) {
                                $book = $item->book;
                                
                                $borrowDays = $item->borrow_days ?? 14;
                                $totalBorrowDays = max($totalBorrowDays, $borrowDays);
                                
                                // Sử dụng giá đã lưu trong database thay vì tính lại
                                $totalTienThue += ($item->tien_thue ?? 0) * $item->quantity;
                                $totalTienCoc += ($item->tien_coc ?? 0) * $item->quantity;
                                
                                // Calculate shipping fee (only once for the first SELECTED item with distance > 5)
                                if ($isFirstSelectedItem && $item->distance > 5) {
                                    $extraKm = $item->distance - 5;
                                    $totalTienShip = (int) ($extraKm * 5000);
                                    $isFirstSelectedItem = false;
                                }
                                
                                $itemCount += $item->quantity;
                            }
                            
                            $tongTien = $totalTienThue + $totalTienCoc + $totalTienShip;
                            $giamGiaSP = 0;
                            $tamTinh = $tongTien - $giamGiaSP;
                            $giamGiaDon = 0;
                            $thanhToan = $tamTinh - $giamGiaDon;
                        @endphp
                        
                        <!-- Fee Details Section -->
                        <div class="fee-details-section">
                            <div class="fee-details-header">
                                <i class="fas fa-file-alt"></i>
                                <span>Chi tiết phí</span>
                            </div>
                            <div id="rental-fees-container">
                                @php
                                    // Nhóm items theo số ngày mượn - CHỈ TÍNH CHO CÁC ITEMS ĐÃ ĐƯỢC CHỌN
                                    $groupedByDays = [];
                                    $selectedItemsForFees = $cart->items->where('is_selected', true);
                                    foreach($selectedItemsForFees as $item) {
                                        $borrowDays = $item->borrow_days ?? 14;
                                        
                                        // Sử dụng giá đã lưu trong database
                                        $tienThue = $item->tien_thue ?? 0;
                                        
                                        if (!isset($groupedByDays[$borrowDays])) {
                                            $groupedByDays[$borrowDays] = 0;
                                        }
                                        $groupedByDays[$borrowDays] += $tienThue * $item->quantity;
                                    }
                                    // Sắp xếp theo số ngày giảm dần
                                    krsort($groupedByDays);
                                @endphp
                                @foreach($groupedByDays as $days => $fee)
                                <div class="fee-detail-row rental-fee-row" data-days="{{ $days }}">
                                    <span class="fee-label">Tiền thuê ({{ $days }} ngày):</span>
                                    <span class="fee-value">{{ number_format($fee, 0, ',', '.') }}₫</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="fee-detail-row">
                                <span class="fee-label">Tiền cọc:</span>
                                <span class="fee-value" id="summary-tien-coc">{{ number_format($totalTienCoc, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="fee-detail-row">
                                <span class="fee-label">Phí ship:</span>
                                <span class="fee-value" id="summary-tien-ship">{{ number_format($totalTienShip, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                        
                        <!-- Thông báo phí ship -->
                        <div class="shipping-info-box mb-3" style="background: #fef3c7; border: 1.5px dashed #fbbf24; border-radius: 8px; padding: 12px 15px; margin-top: 15px;">
                            <small style="color: #92400e; line-height: 1.5;">
                                <i class="fas fa-info-circle me-1"></i>
                                Phí ship tính theo đơn (không giới hạn số lượng sách) cứ sau 5km mỗi 1km tăng thêm 5 nghìn.
                            </small>
                        </div>
                        
                        <!-- Total and Discount Section -->
                        <div class="summary-row">
                            <span class="summary-label-text">Tổng tiền:</span>
                            <span class="summary-value-text" id="summary-tong-tien">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label-text">Giảm giá SP:</span>
                            <span class="summary-value-text">-{{ number_format($giamGiaSP, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <!-- Discount Code Section -->
                        <div class="discount-code-section">
                            <div class="discount-input-wrapper">
                                <i class="fas fa-tag"></i>
                                <input type="text" 
                                       id="discount-code" 
                                       placeholder="Nhập mã giảm giá" 
                                       class="discount-input">
                            </div>
                            <button type="button" 
                                    class="btn-apply-discount" 
                                    onclick="applyDiscountCode()">
                                Áp dụng
                            </button>
                        </div>
                        
                        <!-- Final Calculation Section -->
                        <div class="summary-row">
                            <span class="summary-label-text">Tạm tính:</span>
                            <span class="summary-value-text" id="summary-tam-tinh">{{ number_format($tamTinh, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label-text">Giảm giá đơn:</span>
                            <span class="summary-value-text" id="summary-giam-gia-don">-{{ number_format($giamGiaDon, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="summary-row final-row">
                            <span class="summary-label-bold">Thanh toán:</span>
                            <span class="summary-value-final" id="final-payment">{{ number_format($thanhToan, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <!-- Checkout Button -->
                        <button type="button" 
                                class="btn-checkout-new" 
                                onclick="checkout()">
                            <i class="fas fa-book-open"></i> Đặt mượn sách
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('components.footer')

<style>
.cart-item {
    display: grid;
    grid-template-columns: 40px 100px 2fr 120px 200px 160px 60px;
    gap: 15px;
    padding: 25px 30px;
    background: white;
    border-bottom: 1px solid #f0f0f0;
    align-items: start;
    transition: background 0.2s;
    position: relative;
}

.cart-item:hover {
    background: #fafafa;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-checkbox-wrapper {
    display: flex;
    align-items: flex-start;
    padding-top: 5px;
}

.cart-item-checkbox-wrapper .item-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #ff9800;
}

.cart-item-price-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 5px;
}

.item-original-price {
    color: #333;
    font-weight: 600;
    font-size: 15px;
}

.cart-item-quantity-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 5px;
}

.cart-item-subtotal-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 5px;
}

.quantity-controls {
    height: 35px;
}

.quantity-max {
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-subtotal {
    height: 63px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e51d2e;
    font-weight: 700;
    font-size: 17px;
}

.cart-item-delete-column {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 5px;
}

.btn-delete-item {
    background: #fff;
    border: 2px solid #e0e0e0;
    color: #999;
    cursor: pointer;
    font-size: 16px;
    padding: 10px;
    border-radius: 6px;
    transition: all 0.2s;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-delete-item:hover {
    color: #e51d2e;
    border-color: #e51d2e;
    background: #fff5f5;
}

.cart-item-image {
    width: 100px;
    height: 140px;
    overflow: hidden;
    border-radius: 8px;
    background: #f5f5f5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    background: #e0e0e0;
}

.cart-item-info {
    flex: 1;
}

.cart-item-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.4;
}

.cart-item-title a {
    color: #333;
    text-decoration: none;
}

.cart-item-title a:hover {
    color: #6C63FF;
}

.cart-item-author {
    color: #666;
    margin-bottom: 4px;
}

.cart-item-category {
    color: #999;
    font-size: 14px;
    margin-bottom: 15px;
}

.detail-row {
    margin-bottom: 15px;
}

.detail-row label {
    display: block;
    font-weight: 500;
    margin-bottom: 5px;
    color: #333;
}

.detail-row-inline {
    width: 100%;
    margin-top: 8px;
}

.detail-row-inline label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
    color: #555;
    font-size: 12px;
    text-align: center;
}

.cart-item-quantity-column .detail-row-inline,
.cart-item-subtotal-column .detail-row-inline {
    align-self: stretch;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-quantity {
    width: 35px;
    height: 35px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.btn-quantity:hover {
    background: #f5f5f5;
    border-color: #ff9800;
    color: #ff9800;
}

.quantity-input {
    width: 60px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
}

.quantity-max {
    color: #999;
    font-size: 14px;
}

.borrow-days-select,
.distance-input,
.note-textarea {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.detail-row-inline .borrow-days-select,
.detail-row-inline .distance-input {
    width: 100%;
    padding: 8px 10px;
    font-size: 13px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}



@media (max-width: 968px) {
    .cart-page-container {
        padding: 20px;
    }
    
    .cart-content-wrapper {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .cart-summary-container {
        position: static;
    }
    
    .cart-header-section {
        padding: 15px;
    }
    
    .cart-title-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .cart-select-all-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .cart-column-headers {
        display: none;
    }
    
    .cart-item {
        grid-template-columns: 30px 80px 1fr;
        gap: 10px;
        padding: 15px;
    }
    
    .cart-item-price-column,
    .cart-item-quantity-column,
    .cart-item-subtotal-column,
    .cart-item-delete-column {
        grid-column: 1 / -1;
        justify-content: flex-start;
        align-items: flex-start;
        margin-top: 10px;
    }
    
    .detail-row-inline {
        margin-top: 10px;
    }
    
    .detail-row-inline label {
        text-align: left;
    }
}
</style>

<script>
function toggleSelectAllItems() {
    const selectAll = document.getElementById('select-all-items');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        updateItemSelected(cb.getAttribute('data-item-id'), selectAll.checked);
    });
    recalculateSummary();
}

function handleCheckboxChange(checkbox) {
    const itemId = checkbox.getAttribute('data-item-id');
    const isSelected = checkbox.checked;
    updateItemSelected(itemId, isSelected);
    recalculateSummary();
}

function updateItemSelected(itemId, isSelected) {
    fetch(`{{ route('borrow-cart.update', '') }}/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            is_selected: isSelected
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Failed to update selection:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating selection:', error);
    });
}

function recalculateSummary() {
    let totalTienCoc = 0;
    let totalTienShip = 0;
    let hasSelectedItems = false;
    
    // Nhóm tiền thuê theo số ngày
    let rentalFeesByDays = {};
    
    // Lấy tất cả các item được chọn
    const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
    
    checkedCheckboxes.forEach((checkbox) => {
        hasSelectedItems = true;
        const itemId = checkbox.getAttribute('data-item-id');
        const cartItem = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
        
        if (cartItem) {
            const tienThue = parseFloat(cartItem.getAttribute('data-tien-thue')) || 0;
            const tienCoc = parseFloat(cartItem.getAttribute('data-tien-coc')) || 0;
            const tienShip = parseFloat(cartItem.getAttribute('data-tien-ship')) || 0;
            const borrowDays = parseInt(cartItem.getAttribute('data-borrow-days')) || 0;
            
            // Nhóm tiền thuê theo số ngày
            if (!rentalFeesByDays[borrowDays]) {
                rentalFeesByDays[borrowDays] = 0;
            }
            rentalFeesByDays[borrowDays] += tienThue;
            
            totalTienCoc += tienCoc;
            
            // Chỉ tính ship fee cho item đầu tiên (data-is-first="1")
            if (cartItem.getAttribute('data-is-first') === '1') {
                totalTienShip += tienShip;
            }
        }
    });
    
    // Nếu không có item nào được chọn, reset
    if (!hasSelectedItems) {
        rentalFeesByDays = {};
        totalTienCoc = 0;
        totalTienShip = 0;
    }
    
    // Cập nhật phần tiền thuê theo từng nhóm ngày
    const rentalFeesContainer = document.getElementById('rental-fees-container');
    rentalFeesContainer.innerHTML = '';
    
    // Sắp xếp theo số ngày giảm dần
    const sortedDays = Object.keys(rentalFeesByDays).sort((a, b) => b - a);
    sortedDays.forEach(days => {
        const fee = rentalFeesByDays[days];
        const feeRow = document.createElement('div');
        feeRow.className = 'fee-detail-row rental-fee-row';
        feeRow.setAttribute('data-days', days);
        feeRow.innerHTML = `
            <span class="fee-label">Tiền thuê (${days} ngày):</span>
            <span class="fee-value">${formatCurrency(fee)}</span>
        `;
        rentalFeesContainer.appendChild(feeRow);
    });
    
    // Tính tổng tiền thuê
    const totalTienThue = Object.values(rentalFeesByDays).reduce((sum, fee) => sum + fee, 0);
    
    const tongTien = totalTienThue + totalTienCoc + totalTienShip;
    const giamGiaSP = 0;
    const tamTinh = tongTien - giamGiaSP;
    const giamGiaDon = 0;
    const thanhToan = tamTinh - giamGiaDon;
    
    // Cập nhật UI
    document.getElementById('summary-tien-coc').textContent = formatCurrency(totalTienCoc);
    document.getElementById('summary-tien-ship').textContent = formatCurrency(totalTienShip);
    document.getElementById('summary-tong-tien').textContent = formatCurrency(tongTien);
    document.getElementById('summary-tam-tinh').textContent = formatCurrency(tamTinh);
    document.getElementById('summary-giam-gia-don').textContent = '-' + formatCurrency(giamGiaDon);
    document.getElementById('final-payment').textContent = formatCurrency(thanhToan);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount).replace('₫', '₫');
}

function updateQuantity(itemId, change) {
    const input = document.getElementById('quantity-' + itemId);
    const currentValue = parseInt(input.value) || 1;
    const newValue = Math.max(1, currentValue + change);
    input.value = newValue;
    updateQuantityInput(itemId);
}

function updateQuantityInput(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const quantity = parseInt(input.value) || 1;
    
    fetch(`{{ route('borrow-cart.update', '') }}/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã cập nhật số lượng, đang tải lại trang...', 'success');
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert(data.message || 'Có lỗi xảy ra');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật số lượng');
    });
}

function updateBorrowDays(itemId) {
    const select = document.getElementById('borrow-days-' + itemId);
    const borrowDays = parseInt(select.value);
    
    fetch(`{{ route('borrow-cart.update', '') }}/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            borrow_days: borrowDays
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã cập nhật số ngày mượn, đang tải lại trang...', 'success');
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật số ngày mượn');
    });
}

function updateDistance(itemId) {
    const input = document.getElementById('distance-' + itemId);
    const distance = parseFloat(input.value) || 0;
    
    fetch(`{{ route('borrow-cart.update', '') }}/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            distance: distance
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đã cập nhật khoảng cách, đang tải lại trang...', 'success');
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật khoảng cách');
    });
}

function removeItem(itemId) {
    if (!confirm('Bạn có chắc muốn xóa sách này khỏi giỏ sách?')) {
        return;
    }
    
    fetch(`{{ route('borrow-cart.remove', '') }}/${itemId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-item-id="${itemId}"]`).remove();
            document.getElementById('total-items').textContent = data.cart_count;
            updateBorrowCartCount(data.cart_count);
            
            if (data.cart_count == 0) {
                location.reload();
            }
            
            showToast('Đã xóa sách khỏi giỏ sách', 'success');
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa sách');
    });
}

function clearCart() {
    if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ sách?')) {
        return;
    }
    
    fetch('{{ route('borrow-cart.clear') }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa giỏ sách');
    });
}

function applyDiscountCode() {
    const discountInput = document.getElementById('discount-code');
    const code = discountInput.value.trim();
    
    if (!code) {
        showToast('Vui lòng nhập mã giảm giá', 'error');
        return;
    }
    
    // Tính tổng tiền hiện tại (nếu có)
    const totalAmount = parseFloat(document.querySelector('.cart-total')?.textContent?.replace(/[^\d]/g, '') || 0);
    
    // Gọi API validate voucher
    fetch('{{ route("vouchers.validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            code: code,
            total_amount: totalAmount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            showToast('Áp dụng mã giảm giá thành công! Giảm ' + formatCurrency(data.voucher.discount_amount) + ' VNĐ', 'success');
            // Có thể lưu voucher vào session/localStorage để áp dụng khi checkout
            if (typeof(Storage) !== "undefined") {
                localStorage.setItem('applied_voucher', JSON.stringify(data.voucher));
            }
        } else {
            showToast(data.message || 'Mã giảm giá không hợp lệ', 'error');
        }
    })
    .catch(error => {
        console.error('Error validating voucher:', error);
        showToast('Có lỗi xảy ra khi kiểm tra mã giảm giá', 'error');
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}

function checkout() {
    // Redirect đến trang checkout thay vì gọi API trực tiếp
    window.location.href = '{{ route('borrow-cart.checkout') }}';
}

function showToast(message, type = 'success') {
    const colors = {
        'success': '#4caf50',
        'error': '#ff5252',
        'info': '#2196F3',
        'warning': '#ff9800'
    };
    
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${colors[type] || colors['success']};
        color: white;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Cập nhật số lượng giỏ sách trên header
function updateBorrowCartCount(count) {
    const cartCountElement = document.getElementById('borrow-cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'flex' : 'none';
    }
    
    // Gọi hàm global nếu có
    if (typeof window.updateBorrowCartCount === 'function') {
        window.updateBorrowCartCount(count);
    }
}

// Gọi recalculateSummary() khi trang load để đảm bảo summary hiển thị đúng
document.addEventListener('DOMContentLoaded', function() {
    recalculateSummary();
});
</script>
</body>
</html>
