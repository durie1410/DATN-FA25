@extends('account._layout')

@section('title', 'Thông tin độc giả')
@section('breadcrumb', 'Thông tin độc giả')

@section('content')
<div class="account-section">
    <h2 class="section-title">Thông tin độc giả</h2>
    
    @if(!$reader)
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h3>Bạn chưa đăng ký làm độc giả</h3>
            <p>Vui lòng đăng ký làm độc giả để có thể mượn sách từ thư viện và xem thông tin độc giả của bạn!</p>
            <a href="{{ route('account.register-reader') }}" class="btn-primary">Đăng ký độc giả</a>
        </div>
    @else
        <div class="reader-info-card">
            <div class="reader-header">
                <div class="reader-avatar">
                    {{ strtoupper(substr($reader->ho_ten, 0, 1)) }}
                </div>
                <div class="reader-name-section">
                    <h3 class="reader-name">{{ $reader->ho_ten }}</h3>
                    <p class="reader-code">Mã độc giả: {{ $reader->so_the_doc_gia }}</p>
                </div>
                <div class="reader-status-badge">
                    @if($reader->trang_thai === 'Hoat dong')
                        <span class="status-badge active">Hoạt động</span>
                    @elseif($reader->trang_thai === 'Tam dung')
                        <span class="status-badge suspended">Tạm dừng</span>
                    @elseif($reader->trang_thai === 'Khoa')
                        <span class="status-badge locked">Khóa</span>
                    @else
                        <span class="status-badge">{{ $reader->trang_thai }}</span>
                    @endif
                </div>
            </div>

            <div class="reader-info-grid">
                <div class="info-group">
                    <h4 class="info-group-title">Thông tin cá nhân</h4>
                    <div class="info-item">
                        <span class="info-label">📧 Email:</span>
                        <span class="info-value">{{ $reader->email ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📞 Số điện thoại:</span>
                        <span class="info-value">{{ $reader->so_dien_thoai ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🆔 Số CCCD:</span>
                        <span class="info-value">{{ $reader->so_cccd ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🎂 Ngày sinh:</span>
                        <span class="info-value">{{ $reader->ngay_sinh ? $reader->ngay_sinh->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">⚧️ Giới tính:</span>
                        <span class="info-value">{{ $reader->gioi_tinh ?? 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🏠 Địa chỉ:</span>
                        <span class="info-value">{{ $reader->dia_chi ?? 'Chưa cập nhật' }}</span>
                    </div>
                </div>

                <div class="info-group">
                    <h4 class="info-group-title">Thông tin thẻ độc giả</h4>
                    <div class="info-item">
                        <span class="info-label">🆔 Số thẻ độc giả:</span>
                        <span class="info-value">{{ $reader->so_the_doc_gia ?? 'Chưa có' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Ngày cấp thẻ:</span>
                        <span class="info-value">{{ $reader->ngay_cap_the ? $reader->ngay_cap_the->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">⏰ Ngày hết hạn:</span>
                        <span class="info-value {{ $reader->ngay_het_han && $reader->ngay_het_han->isPast() ? 'text-danger' : '' }}">
                            {{ $reader->ngay_het_han ? $reader->ngay_het_han->format('d/m/Y') : 'Chưa cập nhật' }}
                            @if($reader->ngay_het_han && $reader->ngay_het_han->isPast())
                                <span class="expired-badge">(Đã hết hạn)</span>
                            @elseif($reader->ngay_het_han && $reader->ngay_het_han->diffInDays(now()) <= 30)
                                <span class="expiring-badge">(Sắp hết hạn)</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📊 Trạng thái:</span>
                        <span class="info-value">
                            @if($reader->trang_thai === 'Hoat dong')
                                <span class="status-text active">Hoạt động</span>
                            @elseif($reader->trang_thai === 'Tam dung')
                                <span class="status-text suspended">Tạm dừng</span>
                            @elseif($reader->trang_thai === 'Khoa')
                                <span class="status-text locked">Khóa</span>
                            @else
                                {{ $reader->trang_thai }}
                            @endif
                        </span>
                    </div>
                </div>

                @if($reader->faculty || $reader->department)
                <div class="info-group">
                    <h4 class="info-group-title">Thông tin học tập</h4>
                    @if($reader->faculty)
                    <div class="info-item">
                        <span class="info-label">🎓 Khoa:</span>
                        <span class="info-value">{{ $reader->faculty->ten_khoa ?? 'Chưa cập nhật' }}</span>
                    </div>
                    @endif
                    @if($reader->department)
                    <div class="info-item">
                        <span class="info-label">📚 Ngành:</span>
                        <span class="info-value">{{ $reader->department->ten_nganh ?? 'Chưa cập nhật' }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="info-group">
                    <h4 class="info-group-title">Thống kê</h4>
                    <div class="info-item">
                        <span class="info-label">📖 Sách đang mượn:</span>
                        <span class="info-value">{{ $reader->activeBorrows()->count() }} cuốn</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">💰 Phí phạt chưa thanh toán:</span>
                        <span class="info-value {{ $reader->totalPendingFines() > 0 ? 'text-danger' : '' }}">
                            {{ number_format($reader->totalPendingFines(), 0, ',', '.') }} VNĐ
                        </span>
                    </div>
                </div>
            </div>

            <div class="reader-actions">
                <a href="{{ route('account.borrowed-books') }}" class="btn-secondary">📚 Xem sách đang mượn</a>
                @if($reader->ngay_het_han && $reader->ngay_het_han->diffInDays(now()) <= 30)
                    <span class="btn-warning">⚠️ Thẻ của bạn sắp hết hạn, vui lòng gia hạn!</span>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
.reader-info-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.reader-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 25px;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 30px;
}

.reader-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: bold;
    color: white;
    flex-shrink: 0;
}

.reader-name-section {
    flex: 1;
}

.reader-name {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin: 0 0 8px 0;
}

.reader-code {
    font-size: 16px;
    color: #666;
    margin: 0;
}

.reader-status-badge {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background-color: #4caf50;
    color: white;
}

.status-badge.suspended {
    background-color: #ff9800;
    color: white;
}

.status-badge.locked {
    background-color: #f44336;
    color: white;
}

.reader-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.info-group {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
}

.info-group-title {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #e0e0e0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 12px 0;
    border-bottom: 1px solid #e8e8e8;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #555;
    flex: 0 0 150px;
}

.info-value {
    flex: 1;
    text-align: right;
    color: #333;
}

.status-text {
    font-weight: 600;
}

.status-text.active {
    color: #4caf50;
}

.status-text.suspended {
    color: #ff9800;
}

.status-text.locked {
    color: #f44336;
}

.text-danger {
    color: #f44336;
    font-weight: 600;
}

.expired-badge {
    display: inline-block;
    margin-left: 8px;
    padding: 4px 8px;
    background-color: #f44336;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.expiring-badge {
    display: inline-block;
    margin-left: 8px;
    padding: 4px 8px;
    background-color: #ff9800;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.reader-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding-top: 25px;
    border-top: 2px solid #f0f0f0;
}

.btn-secondary {
    display: inline-block;
    padding: 12px 24px;
    background-color: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #5568d3;
}

.btn-warning {
    display: inline-block;
    padding: 12px 24px;
    background-color: #fff3cd;
    color: #856404;
    border-radius: 6px;
    font-weight: 600;
    border: 1px solid #ffeeba;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 16px;
    color: #666;
    margin-bottom: 30px;
}

.btn-primary {
    display: inline-block;
    padding: 14px 28px;
    background-color: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #5568d3;
}

@media (max-width: 768px) {
    .reader-header {
        flex-direction: column;
        text-align: center;
    }
    
    .reader-info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-item {
        flex-direction: column;
        gap: 8px;
    }
    
    .info-label {
        flex: 1;
    }
    
    .info-value {
        text-align: left;
    }
}
</style>
@endsection






