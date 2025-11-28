@extends('layouts.admin')

@section('title', 'Dashboard - Quản Lý Thư Viện LIBHUB')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">
                            <i class="fas fa-tachometer-alt"></i>
        Dashboard Thư Viện
            </h1>
    <p class="page-subtitle">
                        Tổng quan và thống kê hệ thống quản lý thư viện
        - Hôm nay: {{ now()->format('d/m/Y') }} | <span id="current-time">{{ now()->format('H:i') }}</span>
    </p>
</div>

<!-- Stats Cards -->
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
    <!-- Total Books -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.1s both;">
        <div class="stat-header">
            <div class="stat-title">Tổng Sách</div>
            <div class="stat-icon primary">
                <i class="fas fa-book-open"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalBooks ?? 0 }}</div>
        <div class="stat-label">Quyển sách trong hệ thống</div>
        <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: var(--primary-color); display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-up"></i>
            <span>+12% so với tháng trước</span>
        </div>
    </div>
    
    <!-- Currently Borrowing -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.2s both;">
        <div class="stat-header">
            <div class="stat-title">Đang Mượn</div>
            <div class="stat-icon success">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalBorrowingReaders ?? 0 }}</div>
        <div class="stat-label">Sách đang được mượn</div>
        <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: #28a745; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-check-circle"></i>
            <span>Hoạt động bình thường</span>
        </div>
    </div>
    
    <!-- Sold Books -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.3s both;">
        <div class="stat-header">
            <div class="stat-title">Sách Đã Bán</div>
            <div class="stat-icon primary">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalSoldBooks ?? 0 }}</div>
        <div class="stat-label">Quyển sách đã được bán</div>
        <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: var(--primary-color); display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-chart-line"></i>
            <span>Tổng số lượng bán</span>
        </div>
    </div>
    
    <!-- Reservations -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.4s both;">
        <div class="stat-header">
            <div class="stat-title">Đặt Trước</div>
            <div class="stat-icon primary">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalReservations ?? 0 }}</div>
        <div class="stat-label">Yêu cầu đặt trước</div>
    </div>
</div>

<!-- Financial Summary Section -->
<div class="card" style="margin-bottom: 25px; animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s both;">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                <i class="fas fa-money-bill-wave"></i>
                Tổng Hợp Tiền
            </h3>
            <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Thống kê doanh thu từ bán sách, mượn sách và tiền phạt</p>
        </div>
    </div>
    <div style="padding: 25px;">
        <!-- Tổng hợp chính -->
        <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px;">
            <!-- Total Revenue -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(0, 255, 153, 0.15), rgba(0, 255, 153, 0.08)); border: 2px solid rgba(0, 255, 153, 0.3);">
                <div class="stat-header">
                    <div class="stat-title">Tổng Doanh Thu</div>
                    <div class="stat-icon primary">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--primary-color); font-size: 32px;">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }} <span style="font-size: 16px;">VNĐ</span></div>
                <div class="stat-label" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888;">
                        <span>• Bán sách: {{ number_format($totalRevenueFromSales ?? 0, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888; margin-top: 4px;">
                        <span>• Mượn sách: {{ number_format($totalRevenueFromBorrows ?? 0, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Revenue -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(40, 167, 69, 0.08)); border: 2px solid rgba(40, 167, 69, 0.3);">
                <div class="stat-header">
                    <div class="stat-title">Doanh Thu Tháng Này</div>
                    <div class="stat-icon success">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #28a745; font-size: 32px;">{{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }} <span style="font-size: 16px;">VNĐ</span></div>
                <div class="stat-label" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888;">
                        <span>• Bán sách: {{ number_format($monthlyRevenueFromSales ?? 0, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888; margin-top: 4px;">
                        <span>• Mượn sách: {{ number_format($monthlyRevenueFromBorrows ?? 0, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
                @if(isset($revenueChangePercent) && $revenueChangePercent != 0)
                <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: {{ $revenueChangePercent > 0 ? '#28a745' : '#ff6b6b' }}; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-arrow-{{ $revenueChangePercent > 0 ? 'up' : 'down' }}"></i>
                    <span>{{ number_format(abs($revenueChangePercent), 1) }}% so với tháng trước</span>
                </div>
                @endif
            </div>
            
            <!-- Today Revenue -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(255, 221, 0, 0.15), rgba(255, 221, 0, 0.08)); border: 2px solid rgba(255, 221, 0, 0.3);">
                <div class="stat-header">
                    <div class="stat-title">Doanh Thu Hôm Nay</div>
                    <div class="stat-icon" style="background: rgba(255, 221, 0, 0.2); color: var(--secondary-color);">
                        <i class="fas fa-sun"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: var(--secondary-color); font-size: 32px;">{{ number_format($todayRevenue ?? 0, 0, ',', '.') }} <span style="font-size: 16px;">VNĐ</span></div>
                <div class="stat-label" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888;">
                        <span>• Bán sách: {{ number_format($todayRevenueFromSales ?? 0, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #888; margin-top: 4px;">
                        <span>• Mượn sách: {{ number_format($todayRevenueFromBorrows ?? 0, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chi tiết từng nguồn và tiền phạt -->
        <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); gap: 20px;">
            <!-- Revenue from Sales -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(0, 123, 255, 0.05)); border: 1px solid rgba(0, 123, 255, 0.2);">
                <div class="stat-header">
                    <div class="stat-title">Tiền Bán Sách</div>
                    <div class="stat-icon" style="background: rgba(0, 123, 255, 0.2); color: #007bff;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #007bff;">{{ number_format($totalRevenueFromSales ?? 0, 0, ',', '.') }} <span style="font-size: 18px;">VNĐ</span></div>
                <div class="stat-label">Tổng từ đơn hàng đã thanh toán</div>
            </div>
            
            <!-- Revenue from Borrows -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(111, 66, 193, 0.05)); border: 1px solid rgba(111, 66, 193, 0.2);">
                <div class="stat-header">
                    <div class="stat-title">Tiền Mượn Sách</div>
                    <div class="stat-icon" style="background: rgba(111, 66, 193, 0.2); color: #6f42c1;">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #6f42c1;">{{ number_format($totalRevenueFromBorrows ?? 0, 0, ',', '.') }} <span style="font-size: 18px;">VNĐ</span></div>
                <div class="stat-label">Tổng từ phiếu mượn</div>
            </div>
            
            <!-- Fines Paid -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05)); border: 1px solid rgba(40, 167, 69, 0.2);">
                <div class="stat-header">
                    <div class="stat-title">Tiền Phạt Đã Thu</div>
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #28a745;">{{ number_format($totalFinesPaid ?? 0, 0, ',', '.') }} <span style="font-size: 18px;">VNĐ</span></div>
                <div class="stat-label">Tổng tiền phạt đã thanh toán</div>
            </div>
            
            <!-- Fines Pending -->
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 107, 107, 0.05)); border: 1px solid rgba(255, 107, 107, 0.2);">
                <div class="stat-header">
                    <div class="stat-title">Tiền Phạt Chưa Thu</div>
                    <div class="stat-icon" style="background: rgba(255, 107, 107, 0.2); color: #ff6b6b;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-value" style="color: #ff6b6b;">{{ number_format($totalFinesPending ?? 0, 0, ',', '.') }} <span style="font-size: 18px;">VNĐ</span></div>
                <div class="stat-label">Tổng tiền phạt chưa thanh toán</div>
                @if(isset($totalFinesOverdue) && $totalFinesOverdue > 0)
                <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: #ff6b6b; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-clock"></i>
                    <span>Quá hạn: {{ number_format($totalFinesOverdue, 0, ',', '.') }} VNĐ</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 25px;">
    <!-- Borrow Chart -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">
                            <i class="fas fa-chart-line"></i>
                    Mượn Sách Theo Tháng
                </h3>
                <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Thống kê xu hướng mượn và trả sách</p>
                        </div>
            <select class="form-select" id="chartPeriod" style="width: auto; padding: 8px 12px;">
                        <option value="7">7 ngày qua</option>
                        <option value="30" selected>30 ngày qua</option>
                        <option value="90">3 tháng qua</option>
                        <option value="365">1 năm qua</option>
                    </select>
                </div>
        <div style="height: 300px; padding: 20px;">
            <canvas id="borrowChart"></canvas>
        </div>
    </div>
    
    <!-- Category Chart -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i>
                    Thể Loại Sách
                </h3>
                <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Phân bố theo danh mục</p>
            </div>
                        </div>
        <div style="height: 300px; padding: 20px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Revenue Chart Row -->
<div style="display: grid; grid-template-columns: 1fr; gap: 25px; margin-bottom: 25px;">
    <!-- Revenue Chart -->
    <div class="card" style="border: 2px solid rgba(0, 255, 153, 0.3);">
        <div class="card-header">
            <div>
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Tổng Doanh Thu Theo Tháng
                </h3>
                <p style="font-size: 13px; color: #888; margin: 5px 0 0 0;">Thống kê xu hướng doanh thu theo tháng</p>
            </div>
            <select class="form-select" id="revenueChartPeriod" style="width: auto; padding: 8px 12px;">
                <option value="7">7 ngày qua</option>
                <option value="30" selected>30 ngày qua</option>
                <option value="90">3 tháng qua</option>
                <option value="365">1 năm qua</option>
            </select>
        </div>
        <div style="height: 300px; padding: 20px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Activity and System Info Row -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
    <!-- Recent Activity -->
    <div class="card">
                <div class="card-header">
            <h3 class="card-title">
                        <i class="fas fa-history"></i>
                Hoạt Động Gần Đây
            </h3>
            <a href="{{ route('admin.logs.index') }}" style="color: var(--primary-color); text-decoration: none; font-size: 14px; font-weight: 500;">
                Xem tất cả <i class="fas fa-arrow-right" style="font-size: 12px;"></i>
            </a>
            </div>
        <div style="padding: 0;">
            <div style="display: flex; flex-direction: column; gap: 0;">
                @forelse($recentActivities ?? [] as $activity)
                    <a href="{{ $activity['action_url'] }}" class="activity-item" style="padding: 18px 25px; {{ !$loop->last ? 'border-bottom: 1px solid rgba(255, 255, 255, 0.05);' : '' }} display: flex; align-items: flex-start; gap: 15px; transition: all 0.3s; cursor: pointer; text-decoration: none; color: inherit;" onmouseover="this.style.background='{{ str_replace('0.2', '0.05', $activity['bg_color']) }}'" onmouseout="this.style.background='transparent'">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, {{ $activity['bg_color'] }}, {{ str_replace('0.2', '0.1', $activity['bg_color']) }}); display: flex; align-items: center; justify-content: center; color: {{ $activity['icon_color'] }}; flex-shrink: 0; box-shadow: 0 2px 8px {{ $activity['bg_color'] }};">
                            <i class="{{ $activity['icon'] }}"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="color: var(--text-primary); font-size: 14px; margin-bottom: 6px; font-weight: 500;">{{ $activity['title'] }}</div>
                            @if(!empty($activity['description']))
                                <div style="font-size: 12px; color: #888; margin-bottom: 6px;">{{ Str::limit($activity['description'], 50) }}</div>
                            @endif
                            <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-clock" style="font-size: 10px;"></i>
                                <span>{{ $activity['time']->diffForHumans() }}</span>
                                <span style="margin: 0 4px;">•</span>
                                <span style="color: {{ $activity['text_color'] }}; font-weight: 500;">{{ $activity['action_text'] }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="padding: 40px 25px; text-align: center; color: #888;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p style="margin: 0;">Chưa có hoạt động nào</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- System Info -->
    <div class="card">
                <div class="card-header">
            <h3 class="card-title">
                        <i class="fas fa-server"></i>
                Thông Tin Hệ Thống
            </h3>
            <span class="badge badge-success" style="display: inline-flex; align-items: center; gap: 5px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #28a745; animation: pulse 2s infinite;"></span>
                Online
            </span>
            </div>
        <div style="padding: 0;">
            <div style="display: flex; flex-direction: column; gap: 0;">
                <div style="padding: 15px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                <i class="fas fa-code-branch"></i>
                            </div>
                        <span style="color: #888; font-size: 14px;">Phiên bản hệ thống</span>
                            </div>
                    <span style="color: var(--text-primary); font-weight: 600; font-size: 14px;">v2.1.0</span>
                </div>
                
                <div style="padding: 15px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                <i class="fas fa-clock"></i>
                            </div>
                        <span style="color: #888; font-size: 14px;">Thời gian hoạt động</span>
                            </div>
                    <span style="color: var(--text-primary); font-weight: 600; font-size: 14px;">15 ngày 8 giờ</span>
                </div>
                
                <div style="padding: 15px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                <i class="fas fa-database"></i>
                            </div>
                        <span style="color: #888; font-size: 14px;">Dung lượng database</span>
                            </div>
                    <span style="color: var(--text-primary); font-weight: 600; font-size: 14px;">245.6 MB</span>
                        </div>

                <div style="padding: 15px 25px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                            <i class="fas fa-tachometer-alt"></i>
                    </div>
                        <span style="color: #888; font-size: 14px;">Response Time</span>
                    </div>
                    <span style="color: var(--text-primary); font-weight: 600; font-size: 14px;">45ms</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.1); }
    }

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

    .stat-card {
        animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    .card {
        animation: fadeInScale 0.5s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    .card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .stat-value {
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .stat-trend {
        opacity: 0;
        animation: fadeIn 0.5s ease-out 0.8s both;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Activity items animation */
    .activity-item {
        animation: slideInRight 0.4s ease-out both;
    }

    .activity-item:nth-child(1) { animation-delay: 0.1s; }
    .activity-item:nth-child(2) { animation-delay: 0.2s; }
    .activity-item:nth-child(3) { animation-delay: 0.3s; }
    .activity-item:nth-child(4) { animation-delay: 0.4s; }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Update current time
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('vi-VN', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    // Update time every minute
    setInterval(updateCurrentTime, 60000);
    updateCurrentTime();
    
    // Chart data from Laravel
    const categoryData = @json($categoryStats ?? []);
    const labels = categoryData.map(item => item.ten_the_loai || item.name || 'Unknown');
    const data = categoryData.map(item => item.books_count || item.count || 0);
    
    // Revenue chart data
    const monthlyRevenueData = @json($monthlyRevenueStats ?? []);
    
    // Monthly borrow statistics data
    const monthlyBorrowData = @json($monthlyBorrowStats ?? []);
    
    // Initialize charts when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Borrow Chart (Line Chart)
        const borrowCtx = document.getElementById('borrowChart');
        if (borrowCtx && typeof Chart !== 'undefined') {
            // Sử dụng dữ liệu thực từ database
            const borrowLabels = monthlyBorrowData.length > 0 
                ? monthlyBorrowData.map(item => item.label) 
                : ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
            const borrowCounts = monthlyBorrowData.length > 0 
                ? monthlyBorrowData.map(item => item.count) 
                : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            
            let borrowChartInstance = new Chart(borrowCtx, {
                    type: 'line',
        data: {
                        labels: borrowLabels,
            datasets: [{
                            label: 'Sách mượn',
                        data: borrowCounts,
                        borderColor: 'rgb(0, 255, 153)',
                        backgroundColor: 'rgba(0, 255, 153, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                        pointBackgroundColor: 'rgb(0, 255, 153)',
                            pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
            }]
        },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                            display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                            borderColor: 'rgb(0, 255, 153)',
                                borderWidth: 1,
                            cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                color: '#888',
                                font: { size: 11 }
                                }
                            },
                            x: {
                                grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                color: '#888',
                                font: { size: 11 }
                            }
                        }
                        }
                    }
                });
            
            // Xử lý thay đổi period cho biểu đồ mượn sách
            const chartPeriodSelect = document.getElementById('chartPeriod');
            if (chartPeriodSelect && borrowChartInstance) {
                chartPeriodSelect.addEventListener('change', function() {
                    const period = parseInt(this.value);
                    // Có thể thêm logic để reload dữ liệu theo period nếu cần
                    // Hiện tại giữ nguyên dữ liệu 12 tháng
                });
            }
        }

        // Category Chart (Doughnut Chart)
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx && typeof Chart !== 'undefined') {
                if (labels.length > 0) {
                    const colors = [
                    'rgb(0, 255, 153)',
                    'rgb(255, 221, 0)',
                    'rgb(255, 107, 107)',
                    '#6f42c1',
                    '#20c997',
                    '#fd7e14',
                    '#6610f2'
                    ];
                    
                    new Chart(categoryCtx, {
                        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                                backgroundColor: colors.slice(0, labels.length),
                            borderWidth: 0
            }]
        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 15,
                                    color: '#888',
                                    font: { size: 11 }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                borderColor: 'rgb(0, 255, 153)',
                                    borderWidth: 1,
                                cornerRadius: 8
                            }
                        },
                        cutout: '65%'
                    }
                });
                } else {
                    categoryCtx.parentElement.innerHTML = `
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #888;">
                        <i class="fas fa-chart-pie" style="font-size: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p style="margin: 0;">Chưa có dữ liệu thể loại</p>
                        </div>
                    `;
                }
        }

        // Revenue Chart (Bar Chart)
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx && typeof Chart !== 'undefined' && monthlyRevenueData.length > 0) {
            const revenueLabels = monthlyRevenueData.map(item => item.label);
            const revenueValues = monthlyRevenueData.map(item => item.revenue);
            
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: revenueValues,
                        backgroundColor: 'rgba(0, 255, 153, 0.8)',
                        borderColor: 'rgb(0, 255, 153)',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgb(0, 255, 153)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#888',
                                font: { size: 11 },
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toFixed(0) + 'K';
                                    }
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false,
                                display: false
                            },
                            ticks: {
                                color: '#888',
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

