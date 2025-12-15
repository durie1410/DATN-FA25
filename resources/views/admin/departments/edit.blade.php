@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Ngành Học - Admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Quản lý ngành học</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa: {{ $department->ten_nganh }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-edit"></i> Chỉnh sửa ngành học</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Xem chi tiết
                    </a>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin ngành học</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ten_nganh" class="form-label">Tên ngành học <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten_nganh') is-invalid @enderror" 
                                           id="ten_nganh" name="ten_nganh" 
                                           value="{{ old('ten_nganh', $department->ten_nganh) }}" required>
                                    @error('ten_nganh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ma_nganh" class="form-label">Mã ngành học <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ma_nganh') is-invalid @enderror" 
                                           id="ma_nganh" name="ma_nganh" 
                                           value="{{ old('ma_nganh', $department->ma_nganh) }}" required>
                                    @error('ma_nganh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="faculty_id" class="form-label">Khoa <span class="text-danger">*</span></label>
                                    <select class="form-select @error('faculty_id') is-invalid @enderror" 
                                            id="faculty_id" name="faculty_id" required>
                                        <option value="">Chọn khoa</option>
                                        @foreach($faculties as $faculty)
                                            <option value="{{ $faculty->id }}" {{ old('faculty_id', $department->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                                {{ $faculty->ten_khoa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('faculty_id')
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
                                        <option value="active" {{ old('trang_thai', $department->trang_thai) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('trang_thai', $department->trang_thai) == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="truong_nganh" class="form-label">Trưởng ngành</label>
                                    <input type="text" class="form-control @error('truong_nganh') is-invalid @enderror" 
                                           id="truong_nganh" name="truong_nganh" 
                                           value="{{ old('truong_nganh', $department->truong_nganh) }}">
                                    @error('truong_nganh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ngay_thanh_lap" class="form-label">Ngày thành lập</label>
                                    <input type="date" class="form-control @error('ngay_thanh_lap') is-invalid @enderror" 
                                           id="ngay_thanh_lap" name="ngay_thanh_lap" 
                                           value="{{ old('ngay_thanh_lap', $department->ngay_thanh_lap ? $department->ngay_thanh_lap->format('Y-m-d') : '') }}">
                                    @error('ngay_thanh_lap')
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
                                           value="{{ old('so_dien_thoai', $department->so_dien_thoai) }}">
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
                                           value="{{ old('email', $department->email) }}">
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
                                           value="{{ old('website', $department->website) }}" 
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
                                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control @error('dia_chi') is-invalid @enderror" 
                                              id="dia_chi" name="dia_chi" rows="3">{{ old('dia_chi', $department->dia_chi) }}</textarea>
                                    @error('dia_chi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mo_ta" class="form-label">Mô tả</label>
                                    <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                              id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta', $department->mo_ta) }}</textarea>
                                    @error('mo_ta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
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

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Cập nhật ngành học
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Logo hiện tại</h5>
                </div>
                <div class="card-body text-center">
                    @if($department->logo)
                        <img src="{{ asset('storage/' . $department->logo) }}" alt="{{ $department->ten_nganh }}" 
                             class="img-fluid rounded" style="max-height: 200px;">
                        <p class="mt-2 text-muted">Logo hiện tại</p>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                                <p class="mt-2 text-muted">Chưa có logo</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống kê</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $department->readers_count }}</h4>
                                <small class="text-muted">Số sinh viên</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $department->formatted_founded_date }}</h4>
                            <small class="text-muted">Ngày thành lập</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
