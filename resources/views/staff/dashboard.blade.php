@extends('layouts.staff')

@section('title', 'Dashboard - Nhân viên')

@section('content')
<!-- Page Header -->
<div class="staff-page-header">
    <h1 class="staff-page-title">
        <i class="fas fa-tachometer-alt"></i>
        Dashboard - Nhân viên thư viện
    </h1>
    <p class="staff-page-subtitle">
        Tổng quan và thống kê hệ thống quản lý thư viện
        - Hôm nay: {{ now()->format('d/m/Y') }} | <span id="staff-current-time">{{ now()->format('H:i') }}</span>
    </p>
</div>

<!-- Stats Cards -->
<div class="staff-stats-grid">
    <!-- Total Books -->
    <div class="staff-stat-card" style="animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.1s both;">
        <div class="staff-stat-header">
            <div class="staff-stat-title">Tổng Sách</div>
            <div class="staff-stat-icon staff-icon-primary">
                <i class="fas fa-book-open"></i>
            </div>
        </div>
        <div class="staff-stat-value">{{ number_format($stats['total_books']) }}</div>
        <div class="staff-stat-label">Quyển sách trong hệ thống</div>
        <div class="staff-stat-trend">
            <i class="fas fa-book-reader"></i>
            <span>Trong kho</span>
        </div>
    </div>
    
    <!-- Total Readers -->
    <div class="staff-stat-card" style="animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.2s both;">
        <div class="staff-stat-header">
            <div class="staff-stat-title">Độc Giả</div>
            <div class="staff-stat-icon staff-icon-success">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="staff-stat-value">{{ number_format($stats['total_readers']) }}</div>
        <div class="staff-stat-label">Độc giả đã đăng ký</div>
        <div class="staff-stat-trend">
            <i class="fas fa-user-check"></i>
            <span>Đang hoạt động</span>
        </div>
    </div>
    
    <!-- Currently Borrowing -->
    <div class="staff-stat-card" style="animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.3s both;">
        <div class="staff-stat-header">
            <div class="staff-stat-title">Đang Mượn</div>
            <div class="staff-stat-icon staff-icon-info">
                <i class="fas fa-hand-holding"></i>
            </div>
        </div>
        <div class="staff-stat-value">{{ number_format($stats['active_borrows']) }}</div>
        <div class="staff-stat-label">Sách đang được mượn</div>
        <div class="staff-stat-trend">
            <i class="fas fa-exchange-alt"></i>
            <span>Hoạt động bình thường</span>
        </div>
    </div>
    
    <!-- Overdue Books -->
    <div class="staff-stat-card" style="animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.4s both;">
        <div class="staff-stat-header">
            <div class="staff-stat-title">Quá Hạn</div>
            <div class="staff-stat-icon staff-icon-danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="staff-stat-value" style="color: #ff6b6b;">{{ number_format($stats['overdue_books']) }}</div>
        <div class="staff-stat-label">Sách quá hạn trả</div>
        <div class="staff-stat-trend" style="color: #ff6b6b;">
            <i class="fas fa-clock"></i>
            <span>Cần xử lý ngay</span>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="staff-content-row">
    <!-- Left Column: Popular Books & Active Readers -->
    <div class="staff-left-column">
        <!-- Sách được mượn nhiều nhất -->
        <div class="staff-card" style="animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s both;">
            <div class="staff-card-header">
                <div>
                    <h3 class="staff-card-title">
                        <i class="fas fa-fire"></i>
                        Sách được mượn nhiều nhất
                    </h3>
                    <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Top sách được độc giả yêu thích</p>
                </div>
            </div>
            <div class="staff-card-body">
                <div class="staff-table-responsive">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Tên sách</th>
                                <th style="text-align: center;">Số lần mượn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popular_books as $book)
                            <tr class="staff-table-row-animated">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-book" style="color: var(--staff-primary);"></i>
                                        <span>{{ $book->ten_sach }}</span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="staff-badge staff-badge-primary">{{ $book->borrows_count ?? 0 }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align: center; padding: 30px; color: #888;">
                                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; opacity: 0.3;"></i>
                                    <p style="margin: 0;">Chưa có dữ liệu</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Độc giả tích cực -->
        <div class="staff-card" style="animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.6s both;">
            <div class="staff-card-header">
                <div>
                    <h3 class="staff-card-title">
                        <i class="fas fa-star"></i>
                        Độc giả tích cực
                    </h3>
                    <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Top độc giả mượn sách nhiều nhất</p>
                </div>
            </div>
            <div class="staff-card-body">
                <div class="staff-table-responsive">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Tên độc giả</th>
                                <th style="text-align: center;">Số lần mượn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($active_readers as $reader)
                            <tr class="staff-table-row-animated">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-user" style="color: #28a745;"></i>
                                        <span>{{ $reader->ho_ten }}</span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="staff-badge staff-badge-success">{{ $reader->borrows_count ?? 0 }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" style="text-align: center; padding: 30px; color: #888;">
                                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; opacity: 0.3;"></i>
                                    <p style="margin: 0;">Chưa có dữ liệu</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Upcoming Returns & Pending Reservations -->
    <div class="staff-right-column">
        <!-- Sách sắp đến hạn trả -->
        <div class="staff-card" style="animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s both;">
            <div class="staff-card-header">
                <div>
                    <h3 class="staff-card-title">
                        <i class="fas fa-calendar-check"></i>
                        Sách sắp đến hạn trả
                    </h3>
                    <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Sách cần trả trong 3 ngày tới</p>
                </div>
            </div>
            <div class="staff-card-body">
                <div class="staff-table-responsive">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Sách</th>
                                <th>Độc giả</th>
                                <th style="text-align: center;">Hạn trả</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcoming_returns as $item)
                            <tr class="staff-table-row-animated">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-book" style="color: #ffc107;"></i>
                                        <span>{{ $item->book->ten_sach ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->borrow->reader->ho_ten ?? 'N/A' }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $isOverdue = $item->ngay_hen_tra < now();
                                        $daysRemaining = now()->diffInDays($item->ngay_hen_tra, false);
                                    @endphp
                                    <span class="staff-badge {{ $isOverdue ? 'staff-badge-danger' : ($daysRemaining <= 1 ? 'staff-badge-warning' : 'staff-badge-info') }}">
                                        {{ $item->ngay_hen_tra->format('d/m/Y') }}
                                        @if($isOverdue)
                                            <i class="fas fa-exclamation-circle ms-1"></i>
                                        @elseif($daysRemaining <= 1)
                                            <i class="fas fa-clock ms-1"></i>
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 30px; color: #888;">
                                    <i class="fas fa-check-circle" style="font-size: 32px; margin-bottom: 10px; color: #28a745; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Không có sách sắp đến hạn</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Đặt chỗ chờ xử lý -->
        <div class="staff-card" style="animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.6s both;">
            <div class="staff-card-header">
                <div>
                    <h3 class="staff-card-title">
                        <i class="fas fa-calendar-plus"></i>
                        Đặt chỗ chờ xử lý
                    </h3>
                    <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Yêu cầu đặt chỗ đang chờ xử lý</p>
                </div>
                @if(count($pending_reservations) > 0)
                <span class="staff-badge staff-badge-warning">{{ count($pending_reservations) }}</span>
                @endif
            </div>
            <div class="staff-card-body">
                <div class="staff-table-responsive">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Sách</th>
                                <th>Độc giả</th>
                                <th style="text-align: center;">Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pending_reservations as $reservation)
                            <tr class="staff-table-row-animated">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-book" style="color: #667eea;"></i>
                                        <span>{{ $reservation->book->ten_sach ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>{{ $reservation->reader->ho_ten ?? 'N/A' }}</td>
                                <td style="text-align: center;">
                                    <span class="staff-badge staff-badge-secondary">
                                        {{ $reservation->created_at->format('d/m/Y') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 30px; color: #888;">
                                    <i class="fas fa-check-circle" style="font-size: 32px; margin-bottom: 10px; color: #28a745; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Không có đặt chỗ chờ xử lý</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Statistics -->
<div class="staff-category-section">
    <div class="staff-card" style="animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.7s both;">
        <div class="staff-card-header">
            <div>
                <h3 class="staff-card-title">
                    <i class="fas fa-chart-pie"></i>
                    Thống kê theo danh mục
                </h3>
                <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Phân bố sách theo từng danh mục</p>
            </div>
        </div>
        <div class="staff-card-body">
            <div class="staff-category-grid">
                @forelse($category_stats as $index => $category)
                <div class="staff-category-item" style="animation: slideInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) {{ 0.8 + ($index * 0.1) }}s both;">
                    <div class="staff-category-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="staff-category-value">{{ $category->books_count }}</div>
                    <div class="staff-category-name">{{ $category->name }}</div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: #888;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p style="margin: 0;">Chưa có dữ liệu danh mục</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0%, 100% { 
            opacity: 1; 
            transform: scale(1); 
        }
        50% { 
            opacity: 0.8; 
            transform: scale(1.05); 
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Page Header */
    .staff-page-header {
        margin-bottom: 30px;
        padding: 20px 0;
        border-bottom: 2px solid rgba(102, 126, 234, 0.1);
    }

    .staff-page-title {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .staff-page-title i {
        color: var(--staff-primary);
        font-size: 24px;
    }

    .staff-page-subtitle {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    #staff-current-time {
        font-weight: 600;
        color: var(--staff-primary);
    }

    /* Stats Grid */
    .staff-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Stat Cards */
    .staff-stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(102, 126, 234, 0.1);
        position: relative;
        overflow: hidden;
    }

    .staff-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--staff-primary), var(--staff-secondary));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .staff-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    }

    .staff-stat-card:hover::before {
        transform: scaleX(1);
    }

    .staff-stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .staff-stat-title {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .staff-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .staff-icon-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .staff-icon-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .staff-icon-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .staff-icon-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
    }

    .staff-stat-value {
        font-size: 36px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
        background: linear-gradient(135deg, #333 0%, #666 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .staff-stat-label {
        font-size: 13px;
        color: #888;
        margin-bottom: 12px;
    }

    .staff-stat-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--staff-primary);
        font-weight: 500;
    }

    /* Content Layout */
    .staff-content-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .staff-left-column,
    .staff-right-column {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Cards */
    .staff-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(102, 126, 234, 0.1);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .staff-card:hover {
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .staff-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .staff-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .staff-card-title i {
        color: var(--staff-primary);
    }

    .staff-card-body {
        padding: 24px;
    }

    /* Tables */
    .staff-table-responsive {
        overflow-x: auto;
    }

    .staff-table {
        width: 100%;
        border-collapse: collapse;
    }

    .staff-table thead tr {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid rgba(102, 126, 234, 0.1);
    }

    .staff-table th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .staff-table tbody tr {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .staff-table tbody tr.staff-table-row-animated {
        animation: slideInRight 0.3s ease-out both;
    }

    .staff-table tbody tr:nth-child(1) { animation-delay: 0.05s; }
    .staff-table tbody tr:nth-child(2) { animation-delay: 0.1s; }
    .staff-table tbody tr:nth-child(3) { animation-delay: 0.15s; }
    .staff-table tbody tr:nth-child(4) { animation-delay: 0.2s; }
    .staff-table tbody tr:nth-child(5) { animation-delay: 0.25s; }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .staff-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
        transform: translateX(5px);
    }

    .staff-table td {
        padding: 14px 16px;
        color: #333;
        font-size: 14px;
    }

    /* Badges */
    .staff-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .staff-badge-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .staff-badge-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .staff-badge-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
    }

    .staff-badge-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: white;
    }

    .staff-badge-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .staff-badge-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    /* Category Section */
    .staff-category-section {
        margin-bottom: 30px;
    }

    .staff-category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
    }

    .staff-category-item {
        text-align: center;
        padding: 24px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 2px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .staff-category-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        border-color: var(--staff-primary);
    }

    .staff-category-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .staff-category-value {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .staff-category-name {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .staff-content-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .staff-stats-grid {
            grid-template-columns: 1fr;
        }

        .staff-page-title {
            font-size: 22px;
        }

        .staff-stat-value {
            font-size: 28px;
        }

        .staff-category-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Update current time
    function updateStaffCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('vi-VN', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        const timeElement = document.getElementById('staff-current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    // Update time every minute
    setInterval(updateStaffCurrentTime, 60000);
    updateStaffCurrentTime();

    // Add loading animation to stat cards
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.staff-stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${0.1 + (index * 0.1)}s`;
        });

        // Add hover effect with pulse animation
        const overdueCard = document.querySelector('.staff-stat-card:has(.staff-icon-danger)');
        if (overdueCard) {
            const overdueValue = overdueCard.querySelector('.staff-stat-value');
            if (overdueValue && overdueValue.textContent.trim() !== '0') {
                overdueValue.style.animation = 'pulse 2s infinite';
            }
        }
    });
</script>
@endpush
