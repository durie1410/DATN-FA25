@extends('layouts.admin')

@section('title', 'Sửa Mã Giảm Giá - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Sửa mã giảm giá</h4>
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
                    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Độc giả <span class="text-danger">*</span></label>
                                    <select name="reader_id" class="form-select @error('reader_id') is-invalid @enderror" required>
                                        <option value="">-- Chọn độc giả --</option>
                                        @foreach($readers as $reader)
                                            <option value="{{ $reader->id }}" {{ old('reader_id', $voucher->reader_id) == $reader->id ? 'selected' : '' }}>
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
                                           value="{{ old('ma', $voucher->ma) }}" required>
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
                                        <option value="percentage" {{ old('loai', $voucher->loai) == 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                                        <option value="fixed" {{ old('loai', $voucher->loai) == 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định</option>
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
                                           value="{{ old('gia_tri', $voucher->gia_tri) }}" min="0" step="0.01" required>
                                    @error('gia_tri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" name="so_luong" class="form-control @error('so_luong') is-invalid @enderror" 
                                           value="{{ old('so_luong', $voucher->so_luong) }}" min="0" required>
                                    @error('so_luong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Đơn tối thiểu <span class="text-danger">*</span></label>
                                    <input type="number" name="don_toi_thieu" class="form-control @error('don_toi_thieu') is-invalid @enderror" 
                                           value="{{ old('don_toi_thieu', $voucher->don_toi_thieu) }}" min="0" step="0.01" required>
                                    @error('don_toi_thieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày bắt đầu</label>
                                    <input type="date" name="ngay_bat_dau" class="form-control @error('ngay_bat_dau') is-invalid @enderror" 
                                           value="{{ old('ngay_bat_dau', $voucher->ngay_bat_dau ? \Carbon\Carbon::parse($voucher->ngay_bat_dau)->format('Y-m-d') : '') }}">
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="ngay_ket_thuc" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" 
                                           value="{{ old('ngay_ket_thuc', $voucher->ngay_ket_thuc ? \Carbon\Carbon::parse($voucher->ngay_ket_thuc)->format('Y-m-d') : '') }}">
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kích hoạt <span class="text-danger">*</span></label>
                                    <select name="kich_hoat" class="form-select @error('kich_hoat') is-invalid @enderror" required>
                                        <option value="1" {{ old('kich_hoat', $voucher->kich_hoat) == 1 ? 'selected' : '' }}>Có</option>
                                        <option value="0" {{ old('kich_hoat', $voucher->kich_hoat) == 0 ? 'selected' : '' }}>Không</option>
                                    </select>
                                    @error('kich_hoat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                                        <option value="active" {{ old('trang_thai', $voucher->trang_thai) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('trang_thai', $voucher->trang_thai) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                        <option value="expired" {{ old('trang_thai', $voucher->trang_thai) == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" 
                                      rows="3">{{ old('mo_ta', $voucher->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Cập nhật mã giảm giá
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
                    <h5 class="card-title">Thông tin mã giảm giá</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $voucher->id }}</p>
                    <p><strong>Mã:</strong> {{ $voucher->ma }}</p>
                    <p><strong>Ngày tạo:</strong> {{ $voucher->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Cập nhật:</strong> {{ $voucher->updated_at->format('d/m/Y H:i') }}</p>
                    @if($voucher->trashed())
                        <p><strong>Đã xóa:</strong> {{ $voucher->deleted_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






