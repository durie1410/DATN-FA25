@extends('layouts.admin')

@section('title', 'Thêm Nhà Xuất Bản - Admin')

@section('content')
<style>
    .publisher-modern-form {
        background: rgba(255,255,255,0.09);
        border-radius: 20px;
        box-shadow: 0 10px 32px rgba(31,213,141,0.09);
        padding: 34px 32px 28px 32px;
        margin-bottom: 32px;
        border: 1px solid rgba(0,255,153,0.06);
        animation: fadeIn .6s forwards;
    }
    @keyframes fadeIn { from{opacity:0;transform:translateY(32px);} to{opacity:1;transform:translateY(0);} }
    .publisher-modern-form label { font-weight: 600; color: #25be8b; letter-spacing: 0.4px; margin-bottom: 8px; }
    .publisher-modern-form .form-control, .publisher-modern-form .form-select {
        border-radius: 14px;
        border: 2px solid #edf3f8;
        transition: border 0.25s, box-shadow 0.25s;
        background: rgba(240,250,255,0.16); color: #222;
        box-shadow: 0 2px 8px rgba(0,255,153,0.03);
    }
    .publisher-modern-form .form-control:focus, .publisher-modern-form .form-select:focus {
        border: 2px solid #38d39f;
        box-shadow:0 0 0 2px #25be8b20;
        background: #f9fffb;
    }
    .publisher-modern-form .input-group-text {
        background: #dcfff3;
        color: #38d39f;
        border-radius: 8px 0 0 8px;
        border: none;
    }
    .publisher-modern-form .form-label i {
        margin-right: 6px; color: #00b894; font-size: 1.09em;
    }
    .publisher-modern-form textarea.form-control { min-height: 80px; }
    .publisher-modern-form .btn-gradient {
        background: linear-gradient(90deg, #38d39f 0%, #40c4ff 100%);
        color: white;
        font-weight: 700;
        border-radius: 24px;
        padding: 13px 32px;
        font-size: 1.13em;
        transition: box-shadow .2s, opacity .18s;
        box-shadow: 0 3px 16px 0 rgba(56,211,159,0.16);
        border: none;
        letter-spacing: 0.03em;
    }
    .publisher-modern-form .btn-gradient:hover { opacity: 0.95; box-shadow: 0 6px 24px #31d58d33; }
    .publisher-modern-form .preview-logo {
        background: #f2fffa;
        border: 1.8px dashed #38d39f90;
        border-radius: 12px;
        padding: 18px 0;
        margin-bottom: 12px; margin-top:6px;
        text-align: center;
        min-height: 90px; max-width: 160px;
        display: block;
    }
    @media (max-width: 900px) {
        .publisher-modern-form { padding:20px 6vw; }
    }
</style>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.publishers.index') }}">Quản lý nhà xuất bản</a></li>
            <li class="breadcrumb-item active">Thêm nhà xuất bản</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-plus"></i> Thêm nhà xuất bản mới</h2>
                <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="publisher-modern-form">
                <form action="{{ route('admin.publishers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ten_nha_xuat_ban" class="form-label"><i class="fas fa-building"></i> Tên nhà xuất bản <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ten_nha_xuat_ban') is-invalid @enderror" id="ten_nha_xuat_ban" name="ten_nha_xuat_ban" value="{{ old('ten_nha_xuat_ban') }}" required>
                            @error('ten_nha_xuat_ban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="trang_thai" class="form-label"><i class="fas fa-toggle-on"></i> Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai" name="trang_thai" required>
                                <option value="">Chọn trạng thái</option>
                                <option value="active" {{ old('trang_thai') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('trang_thai') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                            </select>
                            @error('trang_thai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="dia_chi" class="form-label"><i class="fas fa-map-marker-alt"></i> Địa chỉ</label>
                            <textarea class="form-control @error('dia_chi') is-invalid @enderror" id="dia_chi" name="dia_chi" rows="3">{{ old('dia_chi') }}</textarea>
                            @error('dia_chi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="mo_ta" class="form-label"><i class="fas fa-align-left"></i> Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="so_dien_thoai" class="form-label"><i class="fas fa-phone"></i> Số điện thoại</label>
                            <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}">
                            @error('so_dien_thoai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="website" class="form-label"><i class="fas fa-globe"></i> Website</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website') }}" placeholder="https://example.com">
                            @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ngay_thanh_lap" class="form-label"><i class="fas fa-calendar-alt"></i> Ngày thành lập</label>
                            <input type="date" class="form-control @error('ngay_thanh_lap') is-invalid @enderror" id="ngay_thanh_lap" name="ngay_thanh_lap" value="{{ old('ngay_thanh_lap') }}">
                            @error('ngay_thanh_lap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="logo" class="form-label"><i class="fas fa-image"></i> Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*" onchange="previewLogo(event)">
                            <div class="form-text">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</div>
                            <div id="logoPreview" class="preview-logo"></div>
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4 gap-2">
                        <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-save"></i> Lưu nhà xuất bản
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Giữ lại card hướng dẫn như cũ -->
            @includeIf('admin.publishers._guide')
        </div>
    </div>
</div>
<script>
    function previewLogo(event) {
        var output = document.getElementById('logoPreview');
        output.innerHTML = '';
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                output.innerHTML = '<img src="' + e.target.result + '" style="max-width:100%;max-height:110px;border-radius:10px;box-shadow:0 2px 10px #38d39f33;border:1px solid #38d39f;">';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
