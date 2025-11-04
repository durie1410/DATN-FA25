@extends('layouts.admin')

@section('title', 'Sửa Độc Giả - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-edit"></i> Sửa tác giả</h3>
    
    <form action="{{ route('admin.readers.update', $reader->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="ho_ten" value="{{ $reader->ho_ten }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ $reader->email }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" value="{{ $reader->so_dien_thoai }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" value="{{ $reader->ngay_sinh->format('Y-m-d') }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gioi_tinh" class="form-control" required>
                        <option value="Nam" {{ $reader->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nu" {{ $reader->gioi_tinh == 'Nu' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khac" {{ $reader->gioi_tinh == 'Khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số thẻ tác giả</label>
                    <input type="text" name="so_the_doc_gia" value="{{ $reader->so_the_doc_gia }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <textarea name="dia_chi" class="form-control" rows="3" required>{{ $reader->dia_chi }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Ngày cấp thẻ</label>
                    <input type="date" name="ngay_cap_the" value="{{ $reader->ngay_cap_the->format('Y-m-d') }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Ngày hết hạn</label>
                    <input type="date" name="ngay_het_han" value="{{ $reader->ngay_het_han->format('Y-m-d') }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-control" required>
                        <option value="Hoat dong" {{ $reader->trang_thai == 'Hoat dong' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="Tam khoa" {{ $reader->trang_thai == 'Tam khoa' ? 'selected' : '' }}>Tạm khóa</option>
                        <option value="Het han" {{ $reader->trang_thai == 'Het han' ? 'selected' : '' }}>Hết hạn</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.readers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
