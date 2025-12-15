@extends('layouts.admin')

@section('title', 'Thêm Khoa - Admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.faculties.index') }}">Quản lý khoa</a></li>
            <li class="breadcrumb-item active">Thêm khoa</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-plus"></i> Thêm khoa mới</h2>
                <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin khoa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.faculties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ten_khoa" class="form-label">Tên khoa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten_khoa') is-invalid @enderror" 
                                           id="ten_khoa" name="ten_khoa" 
                                           value="{{ old('ten_khoa') }}" required>
                                    @error('ten_khoa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ma_khoa" class="form-label">Mã khoa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ma_khoa') is-invalid @enderror" 
                                           id="ma_khoa" name="ma_khoa" 
                                           value="{{ old('ma_khoa') }}" required>
                                    @error('ma_khoa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="truong_khoa" class="form-label">Trưởng khoa</label>
                                    <input type="text" class="form-control @error('truong_khoa') is-invalid @enderror" 
                                           id="truong_khoa" name="truong_khoa" 
                                           value="{{ old('truong_khoa') }}">
                                    @error('truong_khoa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select @error('trang_thai') is-invalid @enderror" 
                                            id="trang_thai" name="trang_thai" required>
                                        <option value="">Chọn trạng thái</option>
                                        <option value="active" {{ old('trang_thai') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('trang_thai') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                           id="so_dien_thoai" name="so_dien_thoai" 
                                           value="{{ old('so_dien_thoai') }}">
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                           id="website" name="website" 
                                           value="{{ old('website') }}" 
                                           placeholder="https://example.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngay_thanh_lap" class="form-label">Ngày thành lập</label>
                                    <input type="date" class="form-control @error('ngay_thanh_lap') is-invalid @enderror" 
                                           id="ngay_thanh_lap" name="ngay_thanh_lap" 
                                           value="{{ old('ngay_thanh_lap') }}">
                                    @error('ngay_thanh_lap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    <div class="form-text">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control @error('dia_chi') is-invalid @enderror" 
                                              id="dia_chi" name="dia_chi" rows="3">{{ old('dia_chi') }}</textarea>
                                    @error('dia_chi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mo_ta" class="form-label">Mô tả</label>
                                    <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                              id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta') }}</textarea>
                                    @error('mo_ta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Lưu khoa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lưu ý:</h6>
                        <ul class="mb-0">
                            <li>Tên khoa và mã khoa là bắt buộc</li>
                            <li>Mã khoa phải duy nhất trong hệ thống</li>
                            <li>Trạng thái "Hoạt động" cho phép khoa xuất hiện trong hệ thống</li>
                            <li>Sau khi tạo khoa, bạn có thể thêm các ngành học thuộc khoa này</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
