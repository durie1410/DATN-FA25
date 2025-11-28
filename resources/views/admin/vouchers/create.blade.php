@extends('layouts.admin')

@section('title', 'Thêm Mã Giảm Giá Mới - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Thêm mã giảm giá mới</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin mã giảm giá</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vouchers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Độc giả <span class="text-danger">*</span></label>
                                    <select name="reader_id" class="form-select @error('reader_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn độc giả --</option>
                                        @foreach($readers as $reader)
                                            <option value="{{ $reader->id }}" {{ old('reader_id') == $reader->id ? 'selected' : '' }}>
                                                {{ $reader->ho_ten }} ({{ $reader->so_the_doc_gia }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reader_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                                    <input type="text" name="ma" class="form-control @error('ma') is-invalid @enderror" 
                                           value="{{ old('ma') }}" placeholder="VD: GIAM10" required>
                                    @error('ma')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                    <select name="loai" class="form-select @error('loai') is-invalid @enderror" required>
                                        <option value="percentage" {{ old('loai') == 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                                        <option value="fixed" {{ old('loai') == 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định</option>
                                    </select>
                                    @error('loai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                                    <input type="number" name="gia_tri" class="form-control @error('gia_tri') is-invalid @enderror" 
                                           value="{{ old('gia_tri') }}" min="0" step="0.01" required>
                                    @error('gia_tri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nhập % nếu là giảm %, hoặc số tiền nếu là giảm tiền</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" name="so_luong" class="form-control @error('so_luong') is-invalid @enderror" 
                                           value="{{ old('so_luong') }}" min="0" required>
                                    @error('so_luong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Đơn tối thiểu <span class="text-danger">*</span></label>
                                    <input type="number" name="don_toi_thieu" class="form-control @error('don_toi_thieu') is-invalid @enderror" 
                                           value="{{ old('don_toi_thieu') }}" min="0" step="0.01" required>
                                    @error('don_toi_thieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Giá trị đơn hàng tối thiểu để áp dụng mã</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="ngay_bat_dau" class="form-control @error('ngay_bat_dau') is-invalid @enderror" 
                                           value="{{ old('ngay_bat_dau') }}" required>
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="date" name="ngay_ket_thuc" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" 
                                           value="{{ old('ngay_ket_thuc') }}" required>
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" 
                                      rows="3" placeholder="Mô tả về mã giảm giá...">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu mã giảm giá
                            </button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lưu ý:</h6>
                        <ul class="mb-0">
                            <li>Mã giảm giá phải duy nhất</li>
                            <li>Chọn loại giảm giá phù hợp</li>
                            <li>Ngày kết thúc phải sau ngày bắt đầu</li>
                            <li>Đơn tối thiểu là giá trị đơn hàng tối thiểu để áp dụng mã</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






