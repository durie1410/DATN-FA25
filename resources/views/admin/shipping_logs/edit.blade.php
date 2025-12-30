@extends('layouts.admin')

@section('title', 'Sửa đơn hàng')

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
    max-width: 9000px;
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

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #d0d0d0;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
    background-color: #fff;
    transition: border-color 0.3s;
}

.form-control:focus {
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

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.badge-primary {
    background-color: #007bff;
    color: white;
}
</style>

<div class="order-detail-wrapper">
    <div class="invoice-container">
        
        {{-- Header --}}
        <div class="invoice-header">
            Sửa đơn hàng #ORD-{{ str_pad($log->borrow->id ?? $log->id, 6, '0', STR_PAD_LEFT) }}
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
                            $statusInfo = config('borrow_status.statuses.' . $log->status);
                            if (!$statusInfo) {
                                $statusInfo = ['label' => $log->status, 'color' => 'secondary', 'icon' => 'fa-question'];
                            }
                        @endphp
                        <span class="badge badge-{{ $statusInfo['color'] }}">
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

        {{-- Thay đổi trạng thái đơn hàng --}}
        <div class="status-update-section">
            <div class="status-update-header">Thay đổi trạng thái đơn hàng</div>
            
            {{-- Thông báo tự động cập nhật --}}
            <div style="background-color: #e7f3ff; border-left: 4px solid #2196F3; padding: 12px 15px; margin-bottom: 20px; border-radius: 4px;">
                <div style="font-size: 13px; color: #1976D2; line-height: 1.6;">
                    <i class="fas fa-info-circle"></i> <strong>Cập nhật tự động trạng thái thanh toán:</strong>
                    <ul style="margin: 8px 0 0 20px; padding: 0;">
                        <li><strong>Giao hàng thành công</strong> → Tự động xác nhận tất cả các khoản thanh toán (Online + COD) sang <strong>"Đã thanh toán"</strong></li>
                        <li>COD + <strong>Giao hàng thất bại</strong> → Tự động chuyển về <strong>"Chưa thanh toán"</strong></li>
                    </ul>
                </div>
            </div>
            
            <form action="{{ route('admin.shipping_logs.update_status', $log->id) }}" method="POST" class="status-update-form">
                @csrf
                <div class="form-group-inline">
                    <div class="form-field">
                        <label for="status" class="form-label">Chọn trạng thái mới</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            @php
                                $statusConfig = config('borrow_status.statuses');
                                $currentStatusKey = $log->status;
                                $currentStatus = $statusConfig[$currentStatusKey] ?? null;
                                
                                // Lấy danh sách trạng thái có thể chuyển tiếp
                                $availableStatuses = [];
                                
                                // Thêm trạng thái hiện tại (cho phép giữ nguyên)
                                if ($currentStatus) {
                                    $availableStatuses[$currentStatusKey] = $currentStatus;
                                }
                                
                                // Thêm các trạng thái tiếp theo được phép
                                if ($currentStatus && isset($currentStatus['next_statuses'])) {
                                    foreach ($currentStatus['next_statuses'] as $nextStatusKey) {
                                        // Ẩn "giao_hang_thanh_cong" và "giao_hang_that_bai" khi đang ở "dang_giao_hang"
                                        // Vì thành công hay thất bại phụ thuộc vào khách hàng xác nhận
                                        if ($currentStatusKey === 'dang_giao_hang' && 
                                            ($nextStatusKey === 'giao_hang_thanh_cong' || $nextStatusKey === 'giao_hang_that_bai')) {
                                            continue; // Bỏ qua, không hiển thị option này
                                        }
                                        
                                        // Ẩn "dang_van_chuyen_tra_ve" khi đang ở "cho_tra_sach"
                                        // Vì chỉ khi khách hàng xác nhận hoàn trả sách thì mới chuyển sang trạng thái này
                                        if ($currentStatusKey === 'cho_tra_sach' && $nextStatusKey === 'dang_van_chuyen_tra_ve') {
                                            continue; // Bỏ qua, không hiển thị option này
                                        }
                                        
                                        // Ẩn "da_nhan_va_kiem_tra" khi đang ở "giao_hang_that_bai"
                                        // Vì sách phải được vận chuyển trả về trước, sau đó mới có thể nhận và kiểm tra
                                        if ($currentStatusKey === 'giao_hang_that_bai' && $nextStatusKey === 'da_nhan_va_kiem_tra') {
                                            continue; // Bỏ qua, không hiển thị option này
                                        }
                                        
                                        // Tìm trong statuses chính
                                        if (isset($statusConfig[$nextStatusKey])) {
                                            $availableStatuses[$nextStatusKey] = $statusConfig[$nextStatusKey];
                                        }
                                        // Tìm trong special_statuses (như 'huy')
                                        else if ($nextStatusKey === 'huy') {
                                            $specialStatus = config('borrow_status.special_statuses.huy');
                                            $availableStatuses[$nextStatusKey] = array_merge($specialStatus, ['step' => 'X']);
                                        }
                                    }
                                }
                            @endphp
                            @forelse($availableStatuses as $statusKey => $statusInfo)
                                <option value="{{ $statusKey }}" {{ $log->status === $statusKey ? 'selected' : '' }}>
                                    {{ $statusInfo['label'] }}
                                </option>
                            @empty
                                <option value="{{ $currentStatusKey }}" selected>
                                    {{ $currentStatus['label'] ?? 'Trạng thái hiện tại' }}
                                </option>
                            @endforelse
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
            // Hiển thị form khi chọn trạng thái "da_nhan_va_kiem_tra" hoặc "hoan_tat_don_hang"
            if (this.value === 'da_nhan_va_kiem_tra' || this.value === 'hoan_tat_don_hang') {
                refundForm.style.display = 'block';
            } else {
                refundForm.style.display = 'none';
            }
        });
        </script>

        {{-- Sản phẩm --}}
        <div class="product-section">
            
            @if(isset($fines) && $fines->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ảnh</th>
                            <th>Loại</th>
                            <th>Trạng thái</th>
                            <th>hành động</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fines as $fine)
                            <tr>
                                <td>#{{ $fine->id }}</td>
                                <td><img src="{{ asset($fine->img) }}" alt="Ảnh" style="width: 50px; height: auto;"></td>
                                <td>{{ $typeMap[$fine->type] ?? $fine->type }}</td>
                                <td>
                                    <span class="status-badge {{ $fine->status == 'paid' ? 'status-delivered' : ($fine->status == 'pending' ? 'status-unpaid' : '') }}">
                                        {{ $statusMap[$fine->status] ?? $fine->status }}
                                    </span>
                                </td>
                                <td>
                                     {{--nếu là paid thì ẩn nut miễn phạt chuyển thành đã xử lí  --}}
                                    @if($fine->status != 'paid')
                                        <form method="POST" action="{{ route('admin.fines.waive', $fine->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm" title="Miễn phạt" onclick="return confirm('Xác nhận miễn phạt?')">
                                                Miễn phạt
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-success">Đã xử lí</span>
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
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
                $grandTotal = $log->borrow->tong_tien ?? ($totalTienCoc + $totalTienThue + $shippingFee);

                // Phí hỏng sách và tiền cọc hoàn trả (nếu có)
                $phiHong = $log->borrow->phi_hong_sach ?? 0;
                $tienCocHoan = $log->borrow->tien_coc_hoan_tra ?? max(0, $totalTienCoc - $phiHong);
                // Lưu ý: Tổng tiền KHÔNG trừ phí phạt; tổng cuối giữ nguyên để đối soát
                $finalGrand = $grandTotal;

                // Tổng giá trị sách (dùng để ước lượng phí hỏng khi chọn tình trạng)
                $totalBookValue = 0;
                foreach($log->borrow->items as $item) {
                    $totalBookValue += $item->book->gia ?? 0;
                }
            @endphp

            <div class="summary-section" id="order-summary">
                <div class="summary-line">
                    <div class="summary-label">Tiền thuê:</div>
                    <div class="summary-value">{{ number_format($subtotal, 0) }}₫</div>
                </div>

                @if($totalTienCoc > 0)
                <div class="summary-line">
                    <div class="summary-label">Tiền cọc (đã thu):</div>
                    <div class="summary-value">{{ number_format($totalTienCoc, 0) }}₫</div>
                </div>
                @endif

                {{-- Hiện phí phạt nếu đã có hoặc khi chọn tình trạng --}}
                <div class="summary-line" id="phi-hong-line" style="display: {{ $phiHong > 0 ? 'flex' : 'none' }};">
                    <div class="summary-label">Phí phạt:</div>
                    <div class="summary-value text-danger" id="phi-hong-display">{{ number_format($phiHong, 0) }}₫</div>
                </div>

                {{-- Tiền cọc hoàn trả sau trừ phạt (nếu có) --}}


                <div class="summary-line">
                    <div class="summary-label">Phí vận chuyển:</div>
                    <div class="summary-value">{{ number_format($shippingFee, 0) }}₫</div>
                </div>

                <div class="summary-line">
                    <div class="summary-label">Giảm:</div>
                    <div class="summary-value discount">- {{ number_format($discount, 0) }}₫</div>
                </div>

      

                <div class="summary-line total-row" style="border-top:2px solid #e9ecef; padding-top:10px; margin-top:6px;">
                    <div class="summary-label">Tổng tiền mượn() :</div>
                    <div class="total-value" id="final-total-display" style="background:#f8f9fa; padding:8px 12px; border-radius:6px; font-size:18px;">{{ number_format($finalGrand ?? $grandTotal, 0) }}₫</div>
                </div>
                <div class="summary-line" id="tien-coc-hoan-line" style="display: {{ $tienCocHoan >= 0 ? 'flex' : 'none' }};">
                    <div class="summary-label">Tiền cọc hoàn trả:</div>
                    <div class="summary-value" id="tien-coc-hoan-display">{{ number_format($tienCocHoan, 0) }}₫</div>
                </div>
                <div style="font-size:12px; color:#6a6a6a; margin-top:8px;">Ghi chú: <strong>Phí phạt hiển thị riêng và không làm giảm "Tổng phải thu".</strong> Tiền cọc đã thu và tiền cọc hoàn trả hiển thị tách biệt để đối soát.</div>
            </div>

            <script>
                // Biến từ server để tính toán live khi chọn tình trạng
                const totalBookValue = {!! json_encode((float) $totalBookValue) !!};
                const originalTienCoc = {!! json_encode((float) $totalTienCoc) !!};
                const originalGrand = {!! json_encode((float) $grandTotal) !!};

                function formatVnd(value) {
                    return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + '₫';
                }

                // Cập nhật hiển thị khi thay đổi tình trạng sách
                const condSelect = document.querySelector('select[name="tinh_trang_sach"]');
                if (condSelect) {
                    condSelect.addEventListener('change', function() {
                        const cond = this.value;
                        let phiHong = 0;
                        switch(cond) {
                            case 'hong_nhe':
                                phiHong = totalBookValue * 0.1; break;
                            case 'hong_nang':
                                phiHong = totalBookValue * 0.5; break;
                            case 'mat_sach':
                                phiHong = totalBookValue; break;
                            default:
                                phiHong = 0;
                        }

                        // Tiền cọc hoàn trả
                        let tienCocHoan = Math.max(0, originalTienCoc - phiHong);

                        // Tổng không trừ phí phạt — giữ nguyên tổng để đối soát
                        let finalTotal = originalGrand;

                        // Hiển thị các dòng
                        const phiLine = document.getElementById('phi-hong-line');
                        const phiDisplay = document.getElementById('phi-hong-display');
                        const tienCocLine = document.getElementById('tien-coc-hoan-line');
                        const tienCocDisplay = document.getElementById('tien-coc-hoan-display');
                        const originalDisplay = document.getElementById('original-total-display');
                        const finalDisplay = document.getElementById('final-total-display');

                        if (phiLine && phiDisplay) {
                            if (phiHong > 0) {
                                phiLine.style.display = 'flex';
                                phiDisplay.textContent = formatVnd(phiHong);
                            } else {
                                phiLine.style.display = 'none';
                            }
                        }

                        if (tienCocLine && tienCocDisplay) {
                            tienCocLine.style.display = 'flex';
                            tienCocDisplay.textContent = formatVnd(tienCocHoan);
                        }

                        if (originalDisplay) {
                            originalDisplay.textContent = formatVnd(originalGrand);
                        }

                        if (finalDisplay) {
                            finalDisplay.textContent = formatVnd(finalTotal);
                        }
                    });
                }
            </script>
            <div class="invoice-footer">
            <a href="{{ route('admin.shipping_logs.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Quay lại danh sách đơn hàng
            </a>
        </div>
        </div>

        {{-- Footer --}}
        

    </div>
</div>
@endsection

