@extends('layouts.admin')

@section('title', 'Quản Lý Banner - Admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="page-title">
                <i class="fas fa-images me-3"></i>
                Quản Lý Banner Trang Chủ
            </h1>
            <p class="page-subtitle">Tải lên và quản lý ảnh banner cho trang chủ</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">
                <i class="fas fa-external-link-alt me-2"></i>
                Xem Trang Chủ
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="banners-container">
    <div class="row">
        @foreach($banners as $banner)
        <div class="col-md-6 mb-4">
            <div class="banner-card">
                <div class="banner-card-header">
                    <div>
                        <h3 class="banner-title">
                            <span class="banner-number">Banner {{ $banner['number'] }}</span>
                            <span class="banner-status {{ $banner['exists'] ? 'status-active' : 'status-inactive' }}">
                                <i class="fas {{ $banner['exists'] ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                {{ $banner['exists'] ? 'Đã tải lên' : 'Chưa có ảnh' }}
                            </span>
                        </h3>
                        <p class="banner-description">{{ $banner['description'] }}</p>
                    </div>
                </div>
                
                <div class="banner-preview">
                    @if($banner['exists'])
                        <img src="{{ $banner['path'] }}" alt="Banner {{ $banner['number'] }}" class="banner-preview-img">
                        <div class="banner-info">
                            <div class="info-item">
                                <i class="fas fa-file me-2"></i>
                                <span>{{ $banner['filename'] }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-weight me-2"></i>
                                <span>{{ $banner['size'] }}</span>
                            </div>
                            @if($banner['updated_at'])
                            <div class="info-item">
                                <i class="fas fa-clock me-2"></i>
                                <span>Cập nhật: {{ $banner['updated_at'] }}</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="banner-placeholder-upload">
                            <i class="fas fa-image"></i>
                            <p>Chưa có ảnh banner</p>
                        </div>
                    @endif
                </div>
                
                <div class="banner-actions">
                    <form action="{{ route('admin.banners.upload', $banner['number']) }}" method="POST" enctype="multipart/form-data" class="banner-upload-form">
                        @csrf
                        <input type="file" name="banner" id="banner{{ $banner['number'] }}" accept="image/jpeg,image/jpg,image/png,image/webp" class="d-none" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('banner{{ $banner['number'] }}').click()" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>
                            {{ $banner['exists'] ? 'Thay đổi ảnh' : 'Tải lên ảnh' }}
                        </button>
                    </form>
                    
                    @if($banner['exists'])
                    <form action="{{ route('admin.banners.delete', $banner['number']) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>
                            Xóa
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.banners-container {
    padding: 20px 0;
}

.banner-card {
    background: var(--background-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 24px;
    transition: all var(--transition-normal) var(--ease-smooth);
    height: 100%;
}

.banner-card:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-lg), var(--shadow-primary);
    transform: translateY(-2px);
}

.banner-card-header {
    margin-bottom: 20px;
}

.banner-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
    font-size: 18px;
    font-weight: 600;
}

.banner-number {
    color: var(--primary-color);
}

.banner-status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-active {
    background: rgba(0, 255, 153, 0.1);
    color: var(--primary-color);
}

.status-inactive {
    background: rgba(255, 107, 107, 0.1);
    color: #ff6b6b;
}

.banner-description {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

.banner-preview {
    margin-bottom: 20px;
    border-radius: 12px;
    overflow: hidden;
    background: var(--background-elevated);
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-preview-img {
    max-width: 100%;
    max-height: 300px;
    object-fit: contain;
    border-radius: 12px;
}

.banner-placeholder-upload {
    padding: 60px 20px;
    text-align: center;
    color: var(--text-muted);
}

.banner-placeholder-upload i {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.banner-info {
    background: rgba(0, 0, 0, 0.3);
    padding: 12px 16px;
    margin-top: 12px;
    border-radius: 8px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.info-item {
    font-size: 12px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
}

.banner-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.banner-actions .btn {
    flex: 1;
    min-width: 120px;
}

.alert {
    margin-bottom: 24px;
    border-radius: 12px;
    border: none;
    padding: 16px 20px;
    display: flex;
    align-items: center;
}

.alert-success {
    background: rgba(0, 255, 153, 0.1);
    color: var(--primary-color);
    border-left: 4px solid var(--primary-color);
}

.alert-danger {
    background: rgba(255, 107, 107, 0.1);
    color: #ff6b6b;
    border-left: 4px solid #ff6b6b;
}

@media (max-width: 768px) {
    .banner-card {
        padding: 16px;
    }
    
    .banner-title {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .banner-actions {
        flex-direction: column;
    }
    
    .banner-actions .btn {
        width: 100%;
    }
}
</style>

@endsection

