@extends('layouts.admin')

@section('title', 'Dashboard - Quản Lý Thư Viện WAKA')

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
<div class="stats-grid">
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
    
    <!-- Total Readers -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.2s both;">
        <div class="stat-header">
            <div class="stat-title">Tổng Độc Giả</div>
            <div class="stat-icon danger">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalReaders ?? 0 }}</div>
        <div class="stat-label">Độc giả đã đăng ký</div>
        <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: #ff6b6b; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-up"></i>
            <span>+8% so với tháng trước</span>
        </div>
    </div>
    
    <!-- Currently Borrowing -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.3s both;">
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
    
    <!-- Total Librarians -->
    <div class="stat-card" style="animation: slideInUp 0.5s var(--ease-smooth) 0.4s both;">
        <div class="stat-header">
            <div class="stat-title">Thủ Thư</div>
            <div class="stat-icon warning">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <div class="stat-value">{{ $totalLibrarians ?? 0 }}</div>
        <div class="stat-label">Thủ thư hoạt động</div>
        <div class="stat-trend" style="margin-top: 12px; font-size: 12px; color: var(--secondary-color); display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-info-circle"></i>
            <span>Đang trực ca</span>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="stats-grid">
    <!-- Overdue Books -->
    <div class="stat-card">
            <div class="stat-header">
            <div class="stat-title">Sách Quá Hạn</div>
            <div class="stat-icon danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
                </div>
        <div class="stat-value">{{ $overdueBooks ?? 0 }}</div>
        <div class="stat-label">Sách cần xử lý</div>
    </div>
    
    <!-- Reservations -->
    <div class="stat-card">
            <div class="stat-header">
            <div class="stat-title">Đặt Trước</div>
            <div class="stat-icon primary">
                <i class="fas fa-calendar-check"></i>
            </div>
                </div>
        <div class="stat-value">{{ $totalReservations ?? 0 }}</div>
        <div class="stat-label">Yêu cầu đặt trước</div>
    </div>
    
    <!-- Reviews -->
    <div class="stat-card">
            <div class="stat-header">
            <div class="stat-title">Đánh Giá</div>
            <div class="stat-icon warning">
                <i class="fas fa-star"></i>
            </div>
                </div>
        <div class="stat-value">{{ $totalReviews ?? 0 }}</div>
        <div class="stat-label">Đánh giá từ người dùng</div>
    </div>
    
    <!-- Fines -->
    <div class="stat-card">
            <div class="stat-header">
            <div class="stat-title">Phí Phạt</div>
            <div class="stat-icon danger">
                <i class="fas fa-dollar-sign"></i>
            </div>
                </div>
        <div class="stat-value">{{ number_format($totalFines ?? 0) }}</div>
        <div class="stat-label">VNĐ phí phạt cần thu</div>
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
                <div class="activity-item" style="padding: 18px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: flex-start; gap: 15px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='rgba(0, 255, 153, 0.05)'" onmouseout="this.style.background='transparent'">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(0, 255, 153, 0.2), rgba(0, 255, 153, 0.1)); display: flex; align-items: center; justify-content: center; color: var(--primary-color); flex-shrink: 0; box-shadow: 0 2px 8px rgba(0, 255, 153, 0.2);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="color: var(--text-primary); font-size: 14px; margin-bottom: 6px; font-weight: 500;">Sách mới đã được thêm vào hệ thống</div>
                        <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clock" style="font-size: 10px;"></i>
                            <span>2 giờ trước</span>
                            <span style="margin: 0 4px;">•</span>
                            <span style="color: var(--primary-color); font-weight: 500;">Thêm sách</span>
                        </div>
                    </div>
                </div>
                
                <div class="activity-item" style="padding: 18px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: flex-start; gap: 15px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='rgba(40, 167, 69, 0.05)'" onmouseout="this.style.background='transparent'">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(40, 167, 69, 0.2), rgba(40, 167, 69, 0.1)); display: flex; align-items: center; justify-content: center; color: #28a745; flex-shrink: 0; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="color: var(--text-primary); font-size: 14px; margin-bottom: 6px; font-weight: 500;">Độc giả mới đã đăng ký</div>
                        <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clock" style="font-size: 10px;"></i>
                            <span>4 giờ trước</span>
                            <span style="margin: 0 4px;">•</span>
                            <span style="color: #28a745; font-weight: 500;">Đăng ký</span>
                        </div>
                    </div>
                </div>
                
                <div class="activity-item" style="padding: 18px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: flex-start; gap: 15px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='rgba(255, 221, 0, 0.05)'" onmouseout="this.style.background='transparent'">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(255, 221, 0, 0.2), rgba(255, 221, 0, 0.1)); display: flex; align-items: center; justify-content: center; color: var(--secondary-color); flex-shrink: 0; box-shadow: 0 2px 8px rgba(255, 221, 0, 0.2);">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="color: var(--text-primary); font-size: 14px; margin-bottom: 6px; font-weight: 500;">Sách đã được trả về thư viện</div>
                        <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clock" style="font-size: 10px;"></i>
                            <span>6 giờ trước</span>
                            <span style="margin: 0 4px;">•</span>
                            <span style="color: var(--secondary-color); font-weight: 500;">Trả sách</span>
                        </div>
                    </div>
                </div>

                <div class="activity-item" style="padding: 18px 25px; display: flex; align-items: flex-start; gap: 15px; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.background='rgba(255, 107, 107, 0.05)'" onmouseout="this.style.background='transparent'">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(255, 107, 107, 0.2), rgba(255, 107, 107, 0.1)); display: flex; align-items: center; justify-content: center; color: #ff6b6b; flex-shrink: 0; box-shadow: 0 2px 8px rgba(255, 107, 107, 0.2);">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="color: var(--text-primary); font-size: 14px; margin-bottom: 6px; font-weight: 500;">Phát hiện sách quá hạn</div>
                        <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-clock" style="font-size: 10px;"></i>
                            <span>8 giờ trước</span>
                            <span style="margin: 0 4px;">•</span>
                            <span style="color: #ff6b6b; font-weight: 500;">Cảnh báo</span>
                        </div>
                    </div>
                </div>
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
    
    // Initialize charts when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Borrow Chart (Line Chart)
        const borrowCtx = document.getElementById('borrowChart');
        if (borrowCtx && typeof Chart !== 'undefined') {
                new Chart(borrowCtx, {
                    type: 'line',
        data: {
                        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                            label: 'Sách mượn',
                        data: [12, 19, 15, 18, 22, 25, 30, 28, 32, 35, 38, 40],
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
    });
</script>
@endpush
