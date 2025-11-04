@extends('layouts.user')

@section('title', 'Dashboard - WAKA')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt"></i>
            Chào mừng trở lại, {{ auth()->user()->name }}!
        </h1>
        <p class="page-subtitle">Theo dõi hoạt động đọc sách và quản lý thư viện cá nhân của bạn</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đang mượn</div>
            <div class="stat-icon primary">
                <i class="fas fa-book-reader"></i>
            </div>
        </div>
        <div class="stat-value">{{ $borrowingCount ?? 0 }}</div>
        <div class="stat-label">Sách đang mượn</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đã đọc</div>
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $completedCount ?? 0 }}</div>
        <div class="stat-label">Sách đã hoàn thành</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Yêu thích</div>
            <div class="stat-icon warning">
                <i class="fas fa-heart"></i>
            </div>
        </div>
        <div class="stat-value">{{ $favoriteCount ?? 0 }}</div>
        <div class="stat-label">Sách yêu thích</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đánh giá</div>
            <div class="stat-icon warning">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="stat-value">{{ $reviewCount ?? 0 }}</div>
        <div class="stat-label">Đánh giá của bạn</div>
    </div>
</div>

<!-- Currently Borrowing Books -->
<div class="card">
    <h2 class="card-title">
        <i class="fas fa-book-open"></i>
        Sách đang mượn
    </h2>
    
    @if(isset($currentBorrows) && $currentBorrows->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($currentBorrows as $borrow)
                <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(0, 255, 153, 0.1); border-radius: 12px; padding: 20px; transition: all 0.3s;">
                    <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                        @if($borrow->book->hinh_anh)
                            <img src="{{ asset('storage/' . $borrow->book->hinh_anh) }}" 
                                 style="width: 60px; height: 85px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(0, 255, 153, 0.2);"
                                 alt="{{ $borrow->book->ten_sach }}">
                        @else
                            <div style="width: 60px; height: 85px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0, 255, 153, 0.2);">
                                <i class="fas fa-book" style="color: #666;"></i>
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 5px; font-size: 15px;">
                                {{ $borrow->book->ten_sach }}
                            </div>
                            <div style="font-size: 13px; color: #888; margin-bottom: 8px;">
                                {{ $borrow->book->tac_gia }}
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                        <div style="display: flex; justify-content: space-between; font-size: 13px;">
                            <span style="color: #888;">Ngày mượn:</span>
                            <span style="color: var(--text-secondary);">{{ $borrow->ngay_muon->format('d/m/Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px;">
                            <span style="color: #888;">Hạn trả:</span>
                            <span style="color: {{ $borrow->isOverdue() ? '#ff6b6b' : 'var(--primary-color)' }}; font-weight: 500;">
                                {{ $borrow->ngay_hen_tra->format('d/m/Y') }}
                            </span>
                        </div>
                        @if($borrow->isOverdue())
                            <div style="background: rgba(255, 107, 107, 0.15); border: 1px solid rgba(255, 107, 107, 0.3); padding: 8px; border-radius: 8px; text-align: center; font-size: 12px; color: #ff6b6b; font-weight: 500; margin-top: 5px;">
                                <i class="fas fa-exclamation-triangle"></i>
                                Đã quá hạn {{ abs($borrow->ngay_hen_tra->diffInDays(now())) }} ngày
                            </div>
                        @else
                            <div style="background: rgba(0, 255, 153, 0.1); border: 1px solid rgba(0, 255, 153, 0.2); padding: 8px; border-radius: 8px; text-align: center; font-size: 12px; color: var(--primary-color); font-weight: 500; margin-top: 5px;">
                                <i class="fas fa-clock"></i>
                                Còn {{ $borrow->ngay_hen_tra->diffInDays(now()) }} ngày
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(0, 255, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <i class="fas fa-book-open" style="font-size: 28px; color: var(--primary-color);"></i>
            </div>
            <h3 style="color: var(--text-primary); margin-bottom: 8px; font-size: 18px;">Bạn chưa mượn sách nào</h3>
            <p style="color: #888; margin-bottom: 20px;">Khám phá thư viện và bắt đầu hành trình đọc sách của bạn!</p>
            <a href="{{ route('books.public') }}" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Khám phá sách
            </a>
        </div>
    @endif
</div>

<!-- Reading Progress & Recommendations -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-top: 25px;">
    <!-- Reading Progress -->
    <div class="card">
        <h2 class="card-title">
            <i class="fas fa-chart-line"></i>
            Tiến độ đọc sách
        </h2>
        
        <div style="padding: 20px 0;">
            @if(isset($monthlyStats))
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="color: var(--text-secondary); font-size: 14px;">Tháng này</span>
                            <span style="color: var(--primary-color); font-weight: 600;">{{ $monthlyStats['current'] ?? 0 }} sách</span>
                        </div>
                        <div style="height: 8px; background: rgba(255, 255, 255, 0.05); border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; background: linear-gradient(90deg, var(--primary-color), var(--primary-dark)); width: {{ min(($monthlyStats['current'] ?? 0) / max(($monthlyStats['goal'] ?? 5), 1) * 100, 100) }}%; transition: width 0.5s;"></div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                        <div>
                            <div style="color: #888; font-size: 12px; margin-bottom: 5px;">Tháng trước</div>
                            <div style="color: var(--text-primary); font-size: 20px; font-weight: 600;">{{ $monthlyStats['previous'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div style="color: #888; font-size: 12px; margin-bottom: 5px;">Mục tiêu</div>
                            <div style="color: var(--text-primary); font-size: 20px; font-weight: 600;">{{ $monthlyStats['goal'] ?? 5 }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div style="text-align: center; padding: 20px; color: #888;">
                    <i class="fas fa-chart-line" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px;"></i>
                    <p>Chưa có dữ liệu thống kê</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h2 class="card-title">
            <i class="fas fa-bolt"></i>
            Thao tác nhanh
        </h2>
        
        <div style="display: flex; flex-direction: column; gap: 12px; padding: 10px 0;">
            <a href="{{ route('books.public') }}" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(0, 255, 153, 0.1); border-radius: 10px; text-decoration: none; transition: all 0.3s;">
                <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 20px;">
                    <i class="fas fa-search"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: var(--text-primary); font-weight: 500; margin-bottom: 3px;">Tìm sách mới</div>
                    <div style="color: #888; font-size: 12px;">Khám phá thư viện sách</div>
                </div>
                <i class="fas fa-chevron-right" style="color: #666;"></i>
            </a>

            <a href="#" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 221, 0, 0.1); border-radius: 10px; text-decoration: none; transition: all 0.3s;">
                <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(255, 221, 0, 0.15); display: flex; align-items: center; justify-content: center; color: var(--secondary-color); font-size: 20px;">
                    <i class="fas fa-heart"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: var(--text-primary); font-weight: 500; margin-bottom: 3px;">Sách yêu thích</div>
                    <div style="color: #888; font-size: 12px;">Xem danh sách yêu thích</div>
                </div>
                <i class="fas fa-chevron-right" style="color: #666;"></i>
            </a>

            <a href="#" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 107, 107, 0.1); border-radius: 10px; text-decoration: none; transition: all 0.3s;">
                <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(255, 107, 107, 0.15); display: flex; align-items: center; justify-content: center; color: #ff6b6b; font-size: 20px;">
                    <i class="fas fa-history"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: var(--text-primary); font-weight: 500; margin-bottom: 3px;">Lịch sử mượn</div>
                    <div style="color: #888; font-size: 12px;">Xem lịch sử mượn sách</div>
                </div>
                <i class="fas fa-chevron-right" style="color: #666;"></i>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card" style="margin-top: 25px;">
    <h2 class="card-title">
        <i class="fas fa-clock"></i>
        Hoạt động gần đây
    </h2>
    
    @if(isset($recentActivities) && count($recentActivities) > 0)
        <div style="display: flex; flex-direction: column; gap: 0;">
            @foreach($recentActivities as $activity)
                <div style="padding: 15px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; gap: 15px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color); flex-shrink: 0;">
                        <i class="fas {{ $activity['icon'] ?? 'fa-book' }}"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="color: var(--text-primary); margin-bottom: 3px;">{{ $activity['title'] }}</div>
                        <div style="color: #888; font-size: 12px;">{{ $activity['time'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #888;">
            <i class="fas fa-history" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px;"></i>
            <p>Chưa có hoạt động nào</p>
        </div>
    @endif
</div>
@endsection
