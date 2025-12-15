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

.col-product { width: 45%; }
.col-price { width: 20%; text-align: right; }
.col-quantity { width: 15%; text-align: center; }
.col-total { width: 20%; text-align: right; }

.product-section td.col-price { text-align: right; color: #555; }
.product-section td.col-quantity { text-align: center; }
.product-section td.col-total { font-weight: 600; text-align: right; color: #333; }

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

/* --- Phần Cập nhật trạng thái --- */
.status-update-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.status-update-header {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.status-update-form {
    display: block;
}

.form-group-inline {
    display: flex;
    gap: 15px;
    align-items: flex-end;
}

.form-field {
    flex: 1;
    max-width: 400px;
}

.form-label {
    display: block;
    font-size: 13px;
    color: #6a6a6a;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-select {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #d0d0d0;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
    background-color: #fff;
    transition: border-color 0.3s;
}

.form-select:focus {
    outline: none;
    border-color: #1a73e8;
}

.btn-update {
    padding: 10px 25px;
    background-color: #1a73e8;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-update:hover {
    background-color: #1557b0;
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
                    <div class="value">{{ number_format($log->borrow->tien_ship ?? 0, 2) }}₫</div>
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
                            if($log->borrow && $log->borrow->payments->count() > 0) {
                                $payment = $log->borrow->payments->first();
                                $isPaid = ($payment->trang_thai === 'thanh_cong' || $payment->trang_thai === 'hoan_thanh');
                            }
                        @endphp
                        <span class="status-badge {{ $isPaid ? 'status-delivered' : 'status-unpaid' }}">
                            {{ $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán' }}
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

        {{-- Thay đổi trạng thái đơn hàng --}}
        <div class="status-update-section">
            <div class="status-update-header">Thay đổi trạng thái đơn hàng</div>
            
            <form action="{{ route('admin.shipping_logs.update_status', $log->id) }}" method="POST" class="status-update-form">
                @csrf
                <div class="form-group-inline">
                    <div class="form-field">
                        <label for="status" class="form-label">Chọn trạng thái mới</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="cho_xu_ly" {{ $log->status === 'cho_xu_ly' ? 'selected' : '' }}>1. Chờ xử lý</option>
                            <option value="dang_chuan_bi" {{ $log->status === 'dang_chuan_bi' ? 'selected' : '' }}>2. Đang chuẩn bị hàng</option>
                            <option value="dang_giao" {{ $log->status === 'dang_giao' ? 'selected' : '' }}>3. Đang giao hàng</option>
                            <option value="da_giao_thanh_cong" {{ $log->status === 'da_giao_thanh_cong' ? 'selected' : '' }}>4. Đã giao thành công</option>
                            <option value="giao_that_bai" {{ $log->status === 'giao_that_bai' ? 'selected' : '' }}>5. Giao thất bại</option>
                            <option value="tra_lai_sach" {{ $log->status === 'tra_lai_sach' ? 'selected' : '' }}>6. Trả lại sách</option>
                            <option value="dang_gui_lai" {{ $log->status === 'dang_gui_lai' ? 'selected' : '' }}>7. Đang gửi lại sách</option>
                            <option value="da_nhan_hang" {{ $log->status === 'da_nhan_hang' ? 'selected' : '' }}>8. Đã nhận hàng</option>
                            <option value="dang_kiem_tra" {{ $log->status === 'dang_kiem_tra' ? 'selected' : '' }}>9. Đang kiểm tra</option>
                            <option value="thanh_toan_coc" {{ $log->status === 'thanh_toan_coc' ? 'selected' : '' }}>10. Thanh toán cọc</option>
                            <option value="hoan_thanh" {{ $log->status === 'hoan_thanh' ? 'selected' : '' }}>11. Hoàn thành</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-update">Cập nhật trạng thái</button>
                </div>
                
                {{-- Form cho thanh toán cọc --}}
                <div id="refund-form" style="display: none; margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <h6 style="margin-bottom: 15px; color: #495057;">Thông tin thanh toán cọc</h6>
                    
                    <div class="form-field" style="margin-bottom: 15px;">
                        <label class="form-label">Tình trạng sách <span style="color: red;">*</span></label>
                        <select name="tinh_trang_sach" class="form-select">
                            <option value="">-- Chọn tình trạng --</option>
                            <option value="binh_thuong">Bình thường (Hoàn 100%)</option>
                            <option value="hong_nhe">Hỏng nhẹ (Trừ 10% giá sách)</option>
                            <option value="hong_nang">Hỏng nặng (Trừ 50% giá sách)</option>
                            <option value="mat_sach">Mất sách (Trừ 100% giá sách)</option>
                        </select>
                    </div>
                    
                    <div class="form-field" style="margin-bottom: 15px;">
                        <label class="form-label">Ghi chú kiểm tra</label>
                        <textarea name="ghi_chu_kiem_tra" class="form-control" rows="2" placeholder="Mô tả tình trạng sách..."></textarea>
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Ghi chú hoàn cọc</label>
                        <textarea name="ghi_chu_hoan_coc" class="form-control" rows="2" placeholder="Thông tin về việc hoàn cọc..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        
        <script>
        document.getElementById('status').addEventListener('change', function() {
            const refundForm = document.getElementById('refund-form');
            if (this.value === 'thanh_toan_coc') {
                refundForm.style.display = 'block';
            } else {
                refundForm.style.display = 'none';
            }
        });
        </script>

        {{-- Sản phẩm --}}
        <div class="product-section">
            <div class="product-header">Sản phẩm</div>
            
            <table>
                <thead>
                    <tr>
                        <th class="col-product">Phân loại</th>
                        <th class="col-price">Giá</th>
                        <th class="col-quantity">Số lượng</th>
                        <th class="col-total">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                    @endphp
                    @forelse($log->borrow->items as $item)
                        @php
                            $itemPrice = $item->tien_thue ?? 0;
                            $quantity = 1;
                            $itemTotal = $itemPrice * $quantity;
                            $subtotal += $itemTotal;
                        @endphp
                        <tr>
                            <td class="col-product">{{ $item->book->ten_sach ?? 'Sách không xác định' }}</td>
                            <td class="col-price">{{ number_format($itemPrice, 2) }}₫</td>
                            <td class="col-quantity">{{ $quantity }}</td>
                            <td class="col-total">{{ number_format($itemTotal, 0) }}.000₫</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #999;">
                                Không có sản phẩm nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Tổng kết --}}
            <div class="summary-section">
                <div class="summary-line">
                    <div class="summary-label">Tạm tính:</div>
                    <div class="summary-value">{{ number_format($subtotal, 0) }}.000₫</div>
                </div>
                <div class="summary-line">
                    <div class="summary-label">Phí vận chuyển:</div>
                    <div class="summary-value">{{ number_format($log->borrow->tien_ship ?? 0, 0) }}.000₫</div>
                </div>
                <div class="summary-line">
                    <div class="summary-label">Giảm:</div>
                    <div class="summary-value discount">- 0₫</div>
                </div>
                <div class="summary-line total-row">
                    <div class="summary-label">Tổng cộng:</div>
                    <div class="total-value">{{ number_format($log->borrow->tong_tien ?? ($subtotal + ($log->borrow->tien_ship ?? 0)), 0) }}.000₫</div>
                </div>
            </div>
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
