@extends('layouts.admin')

@section('title', 'Thêm Độc Giả Mới - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-plus"></i> Thêm tác giả mới</h3>
    
    <form action="{{ route('admin.readers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="ho_ten" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gioi_tinh" class="form-control" required>
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam">Nam</option>
                        <option value="Nu">Nữ</option>
                        <option value="Khac">Khác</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số thẻ tác giả</label>
                    <input type="text" name="so_the_doc_gia" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <textarea name="dia_chi" class="form-control" rows="3" required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày cấp thẻ</label>
                    <input type="date" name="ngay_cap_the" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày hết hạn</label>
                    <input type="date" name="ngay_het_han" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.readers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
