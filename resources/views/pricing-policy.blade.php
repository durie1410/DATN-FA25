@extends('layouts.frontend')

@section('title', 'Chính sách giá - Thư Viện Online')

@push('styles')
<style>
    .pricing-policy-page {
        padding: 40px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .pricing-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 50px;
        padding: 40px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-header p {
        font-size: 1.1rem;
        color: #7f8c8d;
        max-width: 600px;
        margin: 0 auto;
    }

    .pricing-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .pricing-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #e8e8e8;
    }

    .section-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-right: 20px;
        color: white;
    }

    .section-icon.rental {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .section-icon.deposit {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .section-icon.shipping {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .section-icon.fine {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .section-icon.late {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    }

    .section-icon.damaged {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .section-icon.lost {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .section-header h2 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .pricing-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .pricing-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
        padding: 25px;
        border-left: 5px solid;
        transition: all 0.3s ease;
    }

    .pricing-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .pricing-card.rental {
        border-left-color: #667eea;
    }

    .pricing-card.deposit {
        border-left-color: #f5576c;
    }

    .pricing-card.shipping {
        border-left-color: #00f2fe;
    }

    .pricing-card.fine {
        border-left-color: #fa709a;
    }

    .pricing-card.late {
        border-left-color: #ff6b6b;
    }

    .pricing-card.damaged {
        border-left-color: #f5576c;
    }

    .pricing-card.lost {
        border-left-color: #4facfe;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        font-size: 1.5rem;
    }

    .card-content {
        color: #555;
        line-height: 1.8;
        margin-bottom: 15px;
    }

    .price-formula {
        background: white;
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
        font-family: 'Courier New', monospace;
        font-size: 0.95rem;
        color: #2c3e50;
        border: 2px dashed #e8e8e8;
    }

    .price-formula strong {
        color: #667eea;
    }

    .example-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .example-box h4 {
        color: #856404;
        margin-bottom: 10px;
        font-size: 1.1rem;
    }

    .example-box p {
        color: #856404;
        margin: 5px 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .info-item i {
        font-size: 1.5rem;
        color: #667eea;
        margin-top: 5px;
    }

    .info-item-content h4 {
        margin: 0 0 8px 0;
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .info-item-content p {
        margin: 0;
        color: #7f8c8d;
        font-size: 0.95rem;
    }

    .rules-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-top: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .rules-list {
        list-style: none;
        padding: 0;
    }

    .rules-list li {
        padding: 15px 0;
        border-bottom: 1px solid #e8e8e8;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .rules-list li:last-child {
        border-bottom: none;
    }

    .rules-list li i {
        color: #667eea;
        font-size: 1.2rem;
        margin-top: 3px;
    }

    .rules-list li strong {
        color: #2c3e50;
        margin-right: 8px;
    }

    .back-button {
        text-align: center;
        margin-top: 40px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }

        .pricing-section {
            padding: 25px;
        }

        .pricing-details {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@include('components.frontend-header')
<div class="pricing-policy-page">
    <div class="pricing-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-tags"></i> Chính sách giá</h1>
            <p>Thông tin chi tiết về phí mượn sách, tiền cọc và phí vận chuyển</p>
        </div>

        <!-- Phí thuê sách -->
        <div class="pricing-section">
            <div class="section-header">
                <div class="section-icon rental">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h2>Phí thuê sách</h2>
            </div>

            <div class="card-content">
                <p>{{ $pricing['rental']['description_detail'] ?? $pricing['description']['rental'] ?? 'Phí thuê sách được tính theo số ngày mượn. Áp dụng cho tất cả sách (1% giá sách mỗi ngày).' }}</p>
            </div>

            <div class="pricing-details">
                <div class="pricing-card rental">
                    <div class="card-title">
                        <i class="fas fa-calendar-day"></i>
                        <span>Công thức tính phí</span>
                    </div>
                    <div class="card-content">
                        <p>Phí thuê được tính dựa trên:</p>
                        <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                            <li>Giá sách</li>
                            <li>Số ngày mượn</li>
                            <li>Tình trạng sách</li>
                        </ul>
                    </div>
                    <div class="price-formula">
                        <strong>{{ $pricing['rental']['formula'] ?? 'Phí thuê = Giá sách × ' . (($pricing['rental']['daily_rate'] ?? 0.01) * 100) . '% × Số ngày' }}</strong>
                    </div>
                </div>

                <div class="pricing-card rental">
                    <div class="card-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Điều kiện áp dụng</span>
                    </div>
                    <div class="card-content">
                        <p><strong>Tính phí thuê cho tất cả sách</strong></p>
                        <p style="margin-top: 10px;">Phí thuê được tính dựa trên giá sách và số ngày mượn, áp dụng cho tất cả sách không phân biệt tình trạng (Mới, Tốt, Trung bình, Cũ).</p>
                        @if(isset($pricing['rental']['round_to']))
                        <p style="margin-top: 10px; color: #7f8c8d;">
                            <i class="fas fa-info-circle"></i> Phí thuê được làm tròn đến hàng {{ number_format($pricing['rental']['round_to']) }} VNĐ.
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> Ví dụ tính phí thuê</h4>
                <p><strong>Giả sử:</strong> Bạn mượn một cuốn sách có giá 100,000 VNĐ trong 7 ngày</p>
                <p><strong>Tính toán:</strong> 100,000 × 1% × 7 = 7,000 VNĐ</p>
                <p><strong>Kết quả:</strong> Phí thuê = <strong>7,000 VNĐ</strong></p>
            </div>
        </div>

        <!-- Tiền cọc -->
        <div class="pricing-section">
            <div class="section-header">
                <div class="section-icon deposit">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Tiền cọc</h2>
            </div>

            <div class="card-content">
                <p>{{ $pricing['deposit']['description_detail'] ?? $pricing['description']['deposit'] ?? 'Tiền cọc bằng 100% giá sách, sẽ được hoàn trả khi trả sách đúng hạn và trong tình trạng tốt.' }}</p>
            </div>

            <div class="pricing-details">
                <div class="pricing-card deposit">
                    <div class="card-title">
                        <i class="fas fa-calculator"></i>
                        <span>Công thức tính cọc</span>
                    </div>
                    <div class="card-content">
                        <p>Tiền cọc được tính bằng:</p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $pricing['deposit']['formula'] ?? 'Tiền cọc = Giá sách × ' . (($pricing['deposit']['rate'] ?? 1.0) * 100) . '%' }}</strong>
                        </div>
                        <p style="margin-top: 15px; color: #e74c3c;">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Lưu ý:</strong> Tiền cọc sẽ được hoàn trả khi bạn trả sách đúng hạn và sách còn nguyên vẹn.
                        </p>
                    </div>
                </div>

                <div class="pricing-card deposit">
                    <div class="card-title">
                        <i class="fas fa-check-circle"></i>
                        <span>Quy định hoàn cọc</span>
                    </div>
                    <div class="card-content">
                        <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                            @if(isset($pricing['deposit']['refund_conditions']))
                                @foreach($pricing['deposit']['refund_conditions'] as $condition)
                                    <li>{{ $condition }}</li>
                                @endforeach
                            @else
                                <li>Trả sách đúng hạn</li>
                                <li>Sách còn nguyên vẹn, không bị hư hỏng</li>
                                <li>Không mất trang, không viết vẽ</li>
                                <li>Hoàn cọc trong vòng 3-5 ngày làm việc</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> Ví dụ tính tiền cọc</h4>
                <p><strong>Giả sử:</strong> Bạn mượn một cuốn sách có giá 100,000 VNĐ</p>
                <p><strong>Tính toán:</strong> 100,000 × 100% = 100,000 VNĐ</p>
                <p><strong>Kết quả:</strong> Tiền cọc = <strong>100,000 VNĐ</strong></p>
            </div>
        </div>

        <!-- Phí vận chuyển -->
        <div class="pricing-section">
            <div class="section-header">
                <div class="section-icon shipping">
                    <i class="fas fa-truck"></i>
                </div>
                <h2>Phí vận chuyển</h2>
            </div>

            <div class="card-content">
                <p>{{ $pricing['description']['shipping'] ?? 'Miễn phí vận chuyển trong phạm vi nhất định.' }}</p>
            </div>

            <div class="pricing-details">
                <div class="pricing-card shipping">
                    <div class="card-title">
                        <i class="fas fa-route"></i>
                        <span>Bảng giá vận chuyển</span>
                    </div>
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fas fa-gift"></i>
                                <div class="info-item-content">
                                    <h4>Miễn phí</h4>
                                    <p>Trong {{ $pricing['shipping']['free_km'] ?? 5 }}km đầu tiên</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <div class="info-item-content">
                                    <h4>Từ km thứ {{ ($pricing['shipping']['free_km'] ?? 5) + 1 }}</h4>
                                    <p>{{ number_format($pricing['shipping']['price_per_km'] ?? 5000) }} VNĐ/km</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pricing-card shipping">
                    <div class="card-title">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Công thức tính phí</span>
                    </div>
                    <div class="card-content">
                        <div class="price-formula">
                            @if(($pricing['shipping']['free_km'] ?? 5) > 0)
                                <p><strong>Nếu khoảng cách ≤ {{ $pricing['shipping']['free_km'] ?? 5 }}km:</strong></p>
                                <p style="color: #27ae60; margin: 5px 0;">Phí vận chuyển = 0 VNĐ (Miễn phí)</p>
                                <p style="margin-top: 15px;"><strong>Nếu khoảng cách > {{ $pricing['shipping']['free_km'] ?? 5 }}km:</strong></p>
                                <p style="color: #e74c3c; margin: 5px 0;">
                                    Phí vận chuyển = (Khoảng cách - {{ $pricing['shipping']['free_km'] ?? 5 }}) × {{ number_format($pricing['shipping']['price_per_km'] ?? 5000) }} VNĐ
                                </p>
                            @else
                                <p><strong>Phí vận chuyển = Khoảng cách × {{ number_format($pricing['shipping']['price_per_km'] ?? 5000) }} VNĐ/km</strong></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> Ví dụ tính phí vận chuyển</h4>
                <p><strong>Trường hợp 1:</strong> Khoảng cách 3km → <strong style="color: #27ae60;">Miễn phí</strong></p>
                <p><strong>Trường hợp 2:</strong> Khoảng cách 8km</p>
                <p style="margin-left: 20px;">Tính toán: (8 - 5) × 5,000 = 15,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí vận chuyển = <strong>15,000 VNĐ</strong></p>
            </div>
        </div>

        <!-- Phí trả muộn -->
        <div class="pricing-section">
            <div class="section-header">
                <div class="section-icon late">
                    <i class="fas fa-clock"></i>
                </div>
                <h2>Phí trả muộn</h2>
            </div>

            <div class="card-content">
                <p>{{ $pricing['fines']['late_return']['description'] ?? $pricing['description']['late_return'] ?? 'Phí trả muộn được tính từ ngày đầu tiên quá hạn.' }}</p>
            </div>

            <div class="pricing-details">
                <div class="pricing-card late">
                    <div class="card-title">
                        <i class="fas fa-calculator"></i>
                        <span>Công thức tính phí</span>
                    </div>
                    <div class="card-content">
                        <div class="price-formula">
                            <strong>{{ $pricing['fines']['late_return']['formula'] ?? 'Phí trả muộn = Số ngày quá hạn × 5,000 VNĐ/ngày' }}</strong>
                        </div>
                        <p style="margin-top: 15px; color: #e74c3c;">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Lưu ý:</strong> {{ $pricing['fines']['late_return']['note'] ?? 'Phí trả muộn được tính từ ngày đầu tiên quá hạn, không có thời gian miễn phí.' }}
                        </p>
                    </div>
                </div>

                <div class="pricing-card late">
                    <div class="card-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Thông tin chi tiết</span>
                    </div>
                    <div class="card-content">
                        <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                            <li>Phí mỗi ngày: <strong>{{ number_format($pricing['fines']['late_return']['daily_rate'] ?? 5000) }} VNĐ</strong></li>
                            <li>Thời gian miễn phí: <strong>{{ $pricing['fines']['late_return']['grace_period'] ?? 0 }} ngày</strong></li>
                            <li>Tính phạt từ: <strong>Ngày đầu tiên quá hạn</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> Ví dụ tính phí trả muộn</h4>
                <p><strong>Trường hợp 1:</strong> Trả muộn 2 ngày</p>
                <p style="margin-left: 20px;">Tính toán: 2 × 5,000 = 10,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí trả muộn = <strong>10,000 VNĐ</strong></p>
                <p style="margin-top: 10px;"><strong>Trường hợp 2:</strong> Trả muộn 5 ngày</p>
                <p style="margin-left: 20px;">Tính toán: 5 × 5,000 = 25,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí trả muộn = <strong>25,000 VNĐ</strong></p>
            </div>
        </div>

        <!-- Phí làm hỏng sách -->
        <div class="pricing-section">
            <div class="section-header">
                <div class="section-icon damaged">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2>Phí làm hỏng sách</h2>
            </div>

            <div class="card-content">
                <p>{{ $pricing['fines']['damaged_book']['description'] ?? $pricing['description']['damaged_book'] ?? 'Phí làm hỏng sách được tính dựa trên loại sách và tình trạng sách khi mượn.' }}</p>
            </div>

            <div class="pricing-details">
                @php
                    $damagedConfig = $pricing['fines']['damaged_book']['by_book_type'] ?? [];
                @endphp
                
                @if(isset($damagedConfig['quy']))
                <div class="pricing-card damaged">
                    <div class="card-title">
                        <i class="fas fa-gem"></i>
                        <span>Sách quý</span>
                    </div>
                    <div class="card-content">
                        <p><strong>{{ $damagedConfig['quy']['description'] ?? 'Sách quý: Phạt 100% giá sách' }}</strong></p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $damagedConfig['quy']['formula'] ?? 'Phí = Giá sách × 100%' }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($damagedConfig['binh_thuong']))
                <div class="pricing-card damaged">
                    <div class="card-title">
                        <i class="fas fa-book"></i>
                        <span>Sách bình thường</span>
                    </div>
                    <div class="card-content">
                        <p><strong>{{ $damagedConfig['binh_thuong']['description'] ?? 'Sách bình thường: Phạt 80% (mới/tốt) hoặc 70% (trung bình/cũ)' }}</strong></p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $damagedConfig['binh_thuong']['formula'] ?? 'Phí = Giá sách × 80% (mới/tốt) hoặc × 70% (trung bình/cũ)' }}</strong>
                        </div>
                        <div style="margin-top: 15px;">
                            <p><strong>Chi tiết theo tình trạng:</strong></p>
                            <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                                @if(isset($damagedConfig['binh_thuong']['by_condition']))
                                    @foreach($damagedConfig['binh_thuong']['by_condition'] as $condition => $rate)
                                        <li>{{ $condition }}: {{ $rate * 100 }}% giá sách</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($damagedConfig['tham_khao']))
                <div class="pricing-card damaged">
                    <div class="card-title">
                        <i class="fas fa-book-open"></i>
                        <span>Sách tham khảo</span>
                    </div>
                    <div class="card-content">
                        <p><strong>{{ $damagedConfig['tham_khao']['description'] ?? 'Sách tham khảo: Phạt 80% (mới/tốt) hoặc 70% (trung bình/cũ)' }}</strong></p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $damagedConfig['tham_khao']['formula'] ?? 'Phí = Giá sách × 80% (mới/tốt) hoặc × 70% (trung bình/cũ)' }}</strong>
                        </div>
                        <div style="margin-top: 15px;">
                            <p><strong>Chi tiết theo tình trạng:</strong></p>
                            <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                                @if(isset($damagedConfig['tham_khao']['by_condition']))
                                    @foreach($damagedConfig['tham_khao']['by_condition'] as $condition => $rate)
                                        <li>{{ $condition }}: {{ $rate * 100 }}% giá sách</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> Ví dụ tính phí làm hỏng sách</h4>
                <p><strong>Trường hợp 1:</strong> Làm hỏng sách quý giá 200,000 VNĐ</p>
                <p style="margin-left: 20px;">Tính toán: 200,000 × 100% = 200,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí = <strong>200,000 VNĐ</strong></p>
                <p style="margin-top: 10px;"><strong>Trường hợp 2:</strong> Làm hỏng sách bình thường (mới) giá 100,000 VNĐ</p>
                <p style="margin-left: 20px;">Tính toán: 100,000 × 80% = 80,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí = <strong>80,000 VNĐ</strong></p>
                <p style="margin-top: 10px;"><strong>Trường hợp 3:</strong> Làm hỏng sách bình thường (trung bình) giá 100,000 VNĐ</p>
                <p style="margin-left: 20px;">Tính toán: 100,000 × 70% = 70,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí = <strong>70,000 VNĐ</strong></p>
            </div>

            @if(isset($pricing['fines']['damaged_book']['note']))
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 10px; margin-top: 20px;">
                <p style="color: #856404; margin: 0;"><i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> {{ $pricing['fines']['damaged_book']['note'] }}</p>
            </div>
            @endif
        </div>

        <!-- Phí mất sách -->
        <div class="pricing-section">
            <div class="section-header">
                <div class="section-icon lost">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2>Phí mất sách</h2>
            </div>

            <div class="card-content">
                <p>{{ $pricing['fines']['lost_book']['description'] ?? $pricing['description']['lost_book'] ?? 'Phí mất sách được tính dựa trên loại sách và tình trạng sách khi mượn.' }}</p>
            </div>

            <div class="pricing-details">
                @php
                    $lostConfig = $pricing['fines']['lost_book']['by_book_type'] ?? [];
                @endphp
                
                @if(isset($lostConfig['quy']))
                <div class="pricing-card lost">
                    <div class="card-title">
                        <i class="fas fa-gem"></i>
                        <span>Sách quý</span>
                    </div>
                    <div class="card-content">
                        <p><strong>{{ $lostConfig['quy']['description'] ?? 'Sách quý: Phạt 100% giá sách' }}</strong></p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $lostConfig['quy']['formula'] ?? 'Phí = Giá sách × 100%' }}</strong>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($lostConfig['binh_thuong']))
                <div class="pricing-card lost">
                    <div class="card-title">
                        <i class="fas fa-book"></i>
                        <span>Sách bình thường</span>
                    </div>
                    <div class="card-content">
                        <p><strong>{{ $lostConfig['binh_thuong']['description'] ?? 'Sách bình thường: Phạt 80% (mới/tốt) hoặc 70% (trung bình/cũ)' }}</strong></p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $lostConfig['binh_thuong']['formula'] ?? 'Phí = Giá sách × 80% (mới/tốt) hoặc × 70% (trung bình/cũ)' }}</strong>
                        </div>
                        <div style="margin-top: 15px;">
                            <p><strong>Chi tiết theo tình trạng:</strong></p>
                            <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                                @if(isset($lostConfig['binh_thuong']['by_condition']))
                                    @foreach($lostConfig['binh_thuong']['by_condition'] as $condition => $rate)
                                        <li>{{ $condition }}: {{ $rate * 100 }}% giá sách</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($lostConfig['tham_khao']))
                <div class="pricing-card lost">
                    <div class="card-title">
                        <i class="fas fa-book-open"></i>
                        <span>Sách tham khảo</span>
                    </div>
                    <div class="card-content">
                        <p><strong>{{ $lostConfig['tham_khao']['description'] ?? 'Sách tham khảo: Phạt 80% (mới/tốt) hoặc 70% (trung bình/cũ)' }}</strong></p>
                        <div class="price-formula" style="margin-top: 15px;">
                            <strong>{{ $lostConfig['tham_khao']['formula'] ?? 'Phí = Giá sách × 80% (mới/tốt) hoặc × 70% (trung bình/cũ)' }}</strong>
                        </div>
                        <div style="margin-top: 15px;">
                            <p><strong>Chi tiết theo tình trạng:</strong></p>
                            <ul style="margin: 10px 0; padding-left: 20px; color: #555;">
                                @if(isset($lostConfig['tham_khao']['by_condition']))
                                    @foreach($lostConfig['tham_khao']['by_condition'] as $condition => $rate)
                                        <li>{{ $condition }}: {{ $rate * 100 }}% giá sách</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="example-box">
                <h4><i class="fas fa-lightbulb"></i> Ví dụ tính phí mất sách</h4>
                <p><strong>Trường hợp 1:</strong> Mất sách quý giá 200,000 VNĐ</p>
                <p style="margin-left: 20px;">Tính toán: 200,000 × 100% = 200,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí = <strong>200,000 VNĐ</strong></p>
                <p style="margin-top: 10px;"><strong>Trường hợp 2:</strong> Mất sách bình thường (mới) giá 100,000 VNĐ</p>
                <p style="margin-left: 20px;">Tính toán: 100,000 × 80% = 80,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí = <strong>80,000 VNĐ</strong></p>
                <p style="margin-top: 10px;"><strong>Trường hợp 3:</strong> Mất sách bình thường (trung bình) giá 100,000 VNĐ</p>
                <p style="margin-left: 20px;">Tính toán: 100,000 × 70% = 70,000 VNĐ</p>
                <p style="margin-left: 20px;"><strong>Kết quả:</strong> Phí = <strong>70,000 VNĐ</strong></p>
            </div>

            @if(isset($pricing['fines']['lost_book']['note']))
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 10px; margin-top: 20px;">
                <p style="color: #856404; margin: 0;"><i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> {{ $pricing['fines']['lost_book']['note'] }}</p>
            </div>
            @endif
        </div>

        <!-- Quy định chung -->
        <div class="rules-section">
            <div class="section-header">
                <div class="section-icon rental">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h2>Quy định chung</h2>
            </div>

            <ul class="rules-list">
                <li>
                    <i class="fas fa-calendar-check"></i>
                    <div>
                        <strong>Thời gian mượn:</strong> Tối thiểu {{ $pricing['rules']['min_borrow_days'] ?? 1 }} ngày, tối đa {{ $pricing['rules']['max_borrow_days'] ?? 30 }} ngày. Mặc định {{ $pricing['rules']['default_borrow_days'] ?? 7 }} ngày.
                    </div>
                </li>
                @if($pricing['rules']['allow_extend'] ?? true)
                <li>
                    <i class="fas fa-redo"></i>
                    <div>
                        <strong>Gia hạn mượn:</strong> Được phép gia hạn tối đa {{ $pricing['rules']['max_extend_times'] ?? 2 }} lần, mỗi lần {{ $pricing['rules']['extend_days'] ?? 7 }} ngày.
                    </div>
                </li>
                @endif
                <li>
                    <i class="fas fa-book"></i>
                    <div>
                        <strong>Phí thuê:</strong> Áp dụng cho tất cả sách, tính dựa trên giá sách và số ngày mượn (1% giá sách mỗi ngày).
                    </div>
                </li>
                <li>
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Tiền cọc:</strong> Bằng 100% giá sách, sẽ được hoàn trả khi trả sách đúng hạn và trong tình trạng tốt.
                    </div>
                </li>
                <li>
                    <i class="fas fa-truck"></i>
                    <div>
                        <strong>Vận chuyển:</strong> Miễn phí trong {{ $pricing['shipping']['free_km'] ?? 5 }}km đầu tiên. Từ km thứ {{ ($pricing['shipping']['free_km'] ?? 5) + 1 }} trở đi, phí {{ number_format($pricing['shipping']['price_per_km'] ?? 5000) }} VNĐ/km.
                    </div>
                </li>
                <li>
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Phí trả muộn:</strong> {{ number_format($pricing['fines']['late_return']['daily_rate'] ?? 5000) }} VNĐ/ngày, tính từ ngày đầu tiên quá hạn.
                    </div>
                </li>
                <li>
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Phí làm hỏng sách:</strong> Sách quý phạt 100% giá sách. Sách bình thường/tham khảo: 80% (mới/tốt) hoặc 70% (trung bình/cũ).
                    </div>
                </li>
                <li>
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <strong>Phí mất sách:</strong> Sách quý phạt 100% giá sách. Sách bình thường/tham khảo: 80% (mới/tốt) hoặc 70% (trung bình/cũ).
                    </div>
                </li>
                <li>
                    <i class="fas fa-calendar-times"></i>
                    <div>
                        <strong>Thời hạn thanh toán phạt:</strong> Trong vòng {{ $pricing['fines']['payment_deadline_days'] ?? 30 }} ngày kể từ ngày phát sinh phạt.
                    </div>
                </li>
                <li>
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Lưu ý:</strong> Tất cả các khoản phí sẽ được tính và hiển thị rõ ràng trước khi bạn xác nhận đơn mượn. Vui lòng đọc kỹ chính sách trước khi mượn sách.
                    </div>
                </li>
            </ul>
        </div>

        <!-- Thông tin hỗ trợ -->
        @if(isset($pricing['support']))
        <div class="rules-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="section-header" style="border-bottom-color: rgba(255,255,255,0.3);">
                <div class="section-icon rental" style="background: rgba(255,255,255,0.2);">
                    <i class="fas fa-headset"></i>
                </div>
                <h2 style="color: white;">Hỗ trợ và liên hệ</h2>
            </div>
            <div style="padding: 20px 0;">
                @if(isset($pricing['support']['contact']))
                <p style="margin-bottom: 15px; font-size: 1.1rem;">
                    <i class="fas fa-phone-alt"></i> {{ $pricing['support']['contact'] }}
                </p>
                @endif
                @if(isset($pricing['support']['update_date']))
                <p style="margin: 0; opacity: 0.9;">
                    <i class="fas fa-info-circle"></i> {{ $pricing['support']['update_date'] }}
                </p>
                @endif
            </div>
        </div>
        @endif

        <!-- Nút quay lại -->
        <div class="back-button">
            <a href="{{ route('home') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay lại trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
