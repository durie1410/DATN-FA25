@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<style>
/* Thiết lập cơ bản */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
}

.order-detail-wrapper {
    display: flex;
    justify-content: center;
    padding: 20px;
    background-color: #f4f6f8;
}

.invoice-container {
    width: 100%;
    max-width: 900px;
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    padding: 30px;
}

/* Phần tiêu đề */
.invoice-header {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    padding-bottom: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

/* --- Phần Chi tiết đơn hàng --- */
.detail-section {
    padding: 0;
    margin-bottom: 30px;
}

.detail-grid {
    display: grid;
    /* Chia thành 2 cột, mỗi cột là cặp Label - Value */
    grid-template-columns: 1fr 1fr;
    gap: 10px 20px; /* Khoảng cách giữa các item */
}

.detail-item {
    display: flex;
    /* label và value nằm trên 2 dòng */
    flex-direction: column;
    padding: 8px 0;
    /* Dùng border-bottom để ngăn cách giữa các hàng chi tiết */
    border-bottom: 1px solid #f9f9f9;
}

/* Item có độ rộng đầy đủ (dùng cho Ghi chú) */
.detail-item.full-width {
    grid-column: 1 / span 2; /* Chiếm cả 2 cột */
    border-bottom: none; /* Bỏ border nếu là item cuối cùng của section */
}

.label {
    font-size: 13px;
    color: #6a6a6a;
    margin-bottom: 2px;
}

.value {
    font-size: 15px;
    color: #333;
    font-weight: 500;
}

/* Status badge */
.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
}

.status-delivering {
    background-color: #cfe2ff;
    color: #084298;
}

.status-delivered {
    background-color: #d1e7dd;
    color: #0f5132;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #842029;
}

.status-processing {
    background-color: #fff3cd;
    color: #856404;
}

.status-unpaid {
    background-color: #fd7e14;
    color: #ffffff;
}

/* --- Phần Sản phẩm --- */
.product-header {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-top: 20px;
    margin-bottom: 15px;
}

.product-section table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 14px;
}

.product-section th, .product-section td {
    padding: 12px 10px;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
}

.product-section th {
    background-color: #f7f7f7;
    font-weight: 600;
    color: #555;
    text-transform: uppercase;
}

.col-product { width: 80%; }
.col-quantity { width: 20%; text-align: center; }

.product-section td.col-quantity { text-align: center; }

/* --- Phần Tổng kết --- */
.summary-section {
    width: 350px; /* Chiều rộng giống như trong ảnh */
    margin-left: auto;
    padding-right: 10px;
    font-size: 15px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
}

.summary-label {
    color: #6a6a6a;
}

.summary-value {
    font-weight: 600;
    color: #333;
}

.total-row {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px dashed #e0e0e0;
}

.total-value {
    color: #e51d3b; /* Màu đỏ nổi bật cho Tổng cộng */
    font-size: 18px;
    font-weight: 700;
}

.discount {
    color: #28a745; /* Màu xanh cho giá trị giảm giá */
}

/* --- Phần Footer --- */
.invoice-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}

.btn-back {
    text-decoration: none;
    color: #4a90e2;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-back:hover {
    color: #357ab8;
}

</style>

<div class="order-detail-wrapper">
    <div class="invoice-container">
        
        {{-- Header --}}
        <div class="invoice-header">
            Chi tiết đơn hàng #ORD-{{ str_pad($log->borrow->id ?? $log->id, 6, '0', STR_PAD_LEFT) }}
        </div>

        {{-- Chi tiết đơn hàng --}}
        <div class="detail-section">
            <div class="detail-grid">
                {{-- Họ tên --}}
                <div class="detail-item">
                    <div class="label">Họ tên:</div>
                    <div class="value">{{ $log->borrow->ten_nguoi_muon ?? ($log->borrow->reader->name ?? '—') }}</div>
                </div>

                {{-- SĐT --}}
                <div class="detail-item">
                    <div class="label">SĐT:</div>
                    <div class="value">{{ $log->borrow->so_dien_thoai ?? ($log->borrow->reader->sdt ?? '—') }}</div>
                </div>

                {{-- Email --}}
                <div class="detail-item">
                    <div class="label">Email:</div>
                    <div class="value">{{ $log->borrow->reader->email ?? '—' }}</div>
                </div>

                {{-- Địa chỉ giao hàng --}}
                <div class="detail-item">
                    <div class="label">Địa chỉ giao hàng:</div>
                    <div class="value">
                        {{ $log->borrow->so_nha ?? '' }} {{ $log->borrow->xa ?? '' }}, {{ $log->borrow->huyen ?? '' }}, Tỉnh/TP: {{ $log->borrow->tinh_thanh ?? '' }}
                    </div>
                </div>

                {{-- Phí vận chuyển --}}
                <div class="detail-item">
                    <div class="label">Phí vận chuyển:</div>
                    <div class="value">{{ number_format($log->borrow->tien_ship ?? 0, 0) }}₫</div>
                </div>

                {{-- Phương thức thanh toán --}}
                <div class="detail-item">
                    <div class="label">Phương thức thanh toán:</div>
                    <div class="value">
                        @if($log->borrow && $log->borrow->payments->count() > 0)
                            @php
                                $payment = $log->borrow->payments->first();
                                $paymentMethodMap = [
                                    'tien_mat' => 'Tiền mặt',
                                    'chuyen_khoan' => 'Chuyển khoản',
                                    'vnpay' => 'VNPay',
                                    'momo' => 'MoMo',
                                    'cod' => 'Thanh toán khi nhận hàng (COD)'
                                ];
                            @endphp
                            {{ $paymentMethodMap[$payment->phuong_thuc ?? 'cod'] ?? 'Thanh toán khi nhận hàng (COD)' }}
                        @else
                            Thanh toán khi nhận hàng (COD)
                        @endif
                    </div>
                </div>

                {{-- Phương thức vận chuyển --}}
                <div class="detail-item">
                    <div class="label">Phương thức vận chuyển:</div>
                    <div class="value">Giao hàng tiêu chuẩn</div>
                </div>

                {{-- Trạng thái --}}
                <div class="detail-item">
                    <div class="label">Trạng thái:</div>
                    <div class="value">
                        @php
                            $statusMap = [
                                'cho_xu_ly' => ['label' => 'Chờ xử lý', 'class' => 'status-processing'],
                                'dang_chuan_bi' => ['label' => 'Đang chuẩn bị', 'class' => 'status-processing'],
                                'dang_dong_goi' => ['label' => 'Đang đóng gói', 'class' => 'status-processing'],
                                'da_gui_buu_cuc' => ['label' => 'Đã gửi bưu cục', 'class' => 'status-delivering'],
                                'dang_giao' => ['label' => 'Đang giao hàng', 'class' => 'status-delivering'],
                                'da_giao' => ['label' => 'Đã giao thành công', 'class' => 'status-delivered'],
                                'khong_nhan' => ['label' => 'Không nhận', 'class' => 'status-cancelled'],
                                'giao_that_bai' => ['label' => 'Giao thất bại', 'class' => 'status-cancelled'],
                                'hoan_hang' => ['label' => 'Hoàn hàng', 'class' => 'status-cancelled'],
                                'da_huy' => ['label' => 'Đã hủy', 'class' => 'status-cancelled'],
                            ];
                            $statusInfo = $statusMap[$log->status] ?? ['label' => $log->status, 'class' => 'status-processing'];
                        @endphp
                        <span class="status-badge {{ $statusInfo['class'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                    </div>
                </div>

                {{-- Trạng thái thanh toán --}}
                <div class="detail-item">
                    <div class="label">Trạng thái thanh toán:</div>
                    <div class="value">
                        @php
                            $isPaid = false;
                            $paymentMethod = 'N/A';
                            if($log->borrow && $log->borrow->payments->count() > 0) {
                                // Kiểm tra tất cả các khoản thanh toán
                                $allPaid = $log->borrow->payments
                                    ->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee'])
                                    ->every(function($payment) {
                                        return $payment->payment_status === 'success';
                                    });
                                $isPaid = $allPaid && $log->borrow->payments->whereIn('payment_type', ['deposit', 'borrow_fee', 'shipping_fee'])->count() > 0;
                                
                                // Lấy phương thức thanh toán
                                $firstPayment = $log->borrow->payments->first();
                                if ($firstPayment) {
                                    $paymentMethod = $firstPayment->payment_method === 'online' ? 'Online (VNPay/MoMo)' : 'Thanh toán khi nhận hàng (COD)';
                                }
                            }
                        @endphp
                        <span class="status-badge {{ $isPaid ? 'status-delivered' : 'status-unpaid' }}">
                            {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                        <span style="font-size: 13px; color: #6a6a6a; margin-left: 10px;">
                            ({{ $paymentMethod }})
                        </span>
                    </div>
                </div>

                {{-- Ghi chú (full width) --}}
                <div class="detail-item full-width">
                    <div class="label">Ghi chú:</div>
                    <div class="value">{{ $log->shipper_note ?: ($log->receiver_note ?: 'Không có') }}</div>
                </div>
            </div>
        </div>

        {{-- Sản phẩm --}}
        <div class="product-section">
            <div class="product-header">Sản phẩm</div>
            
            <table>
                <thead>
                    <tr>
                        <th class="col-product">Tên</th>
                        <th class="col-quantity">Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalTienCoc = 0;
                        $totalTienThue = 0;
                        $totalTienShip = 0;
                    @endphp
                    @forelse($log->borrow->items as $item)
                        @php
                            $itemTienCoc = $item->tien_coc ?? 0;
                            $itemTienThue = $item->tien_thue ?? 0;
                            $itemTienShip = $item->tien_ship ?? 0;
                            
                            $totalTienCoc += $itemTienCoc;
                            $totalTienThue += $itemTienThue;
                            $totalTienShip += $itemTienShip;
                        @endphp
                        <tr>
                            <td class="col-product">{{ $item->book->ten_sach ?? 'Sách không xác định' }}</td>
                            <td class="col-quantity">1</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center; padding: 40px; color: #999;">
                                Không có sản phẩm nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Tổng kết --}}
            @php
                // Tạm tính = tổng phí thuê từ tất cả items
                $subtotal = $totalTienThue;
                // Phí vận chuyển lấy từ borrow (có thể là phí chung cho cả đơn)
                $shippingFee = $log->borrow->tien_ship ?? 0;
                $discount = 0;
                // Tổng cộng = tien_coc + tien_thue + tien_ship (từ borrow)
                // Sử dụng tong_tien từ borrow để đảm bảo đúng với dữ liệu thực tế
                $grandTotal = $log->borrow->tong_tien ?? ($totalTienCoc + $totalTienThue + $shippingFee - $discount);
            @endphp
            <div class="summary-section">
                <div class="summary-line">
                    <div class="summary-label">Tiền thuê:</div>
                    <div class="summary-value">{{ number_format($subtotal, 0) }}₫</div>
                </div>
                @if($totalTienCoc > 0)
                <div class="summary-line">
                    <div class="summary-label">Tiền cọc:</div>
                    <div class="summary-value">{{ number_format($totalTienCoc, 0) }}₫</div>
                </div>
                @endif
                <div class="summary-line">
                    <div class="summary-label">Phí vận chuyển:</div>
                    <div class="summary-value">{{ number_format($shippingFee, 0) }}₫</div>
                </div>
                <div class="summary-line">
                    <div class="summary-label">Giảm:</div>
                    <div class="summary-value discount">- {{ number_format($discount, 0) }}₫</div>
                </div>
                <div class="summary-line total-row">
                    <div class="summary-label">Tổng cộng:</div>
                    <div class="total-value">{{ number_format($grandTotal, 0) }}₫</div>
                </div>
            </div>
        </div>

        {{-- Phạt / Fines --}}
        <div class="detail-section" style="margin-top:30px;">
            <div class="product-header">Phạt (Fines)</div>

            @php
                $typeMap = [
                    'late_return' => 'Trả muộn',
                    'damaged_book' => 'Làm hỏng',
                    'lost_book' => 'Mất sách',
                    'other' => 'Khác'
                ];
                $statusMap = [
                    'pending' => 'Chưa thanh toán',
                    'paid' => 'Đã thanh toán',
                    'waived' => 'Đã miễn',
                    'cancelled' => 'Đã hủy'
                ];
            @endphp

            @if(isset($fines) && $fines->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Loại</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fines as $fine)
                            <tr>
                                <td>#{{ $fine->id }}</td>
                                <td>{{ $typeMap[$fine->type] ?? $fine->type }}</td>
                                <td><strong class="text-danger">{{ number_format($fine->amount, 0, ',', '.') }} VND</strong></td>
                                <td>
                                    <span class="status-badge {{ $fine->status == 'paid' ? 'status-delivered' : ($fine->status == 'pending' ? 'status-unpaid' : '') }}">
                                        {{ $statusMap[$fine->status] ?? $fine->status }}
                                    </span>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding:12px 0; color:#666;">Không có phạt nào cho phiếu này.</div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="invoice-footer">
            <a href="{{ route('admin.shipping_logs.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Quay lại danh sách đơn hàng
            </a>
        </div>

    </div>
</div>
@endsection
