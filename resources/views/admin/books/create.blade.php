@extends('layouts.admin')

@section('title', 'Thêm Sách Mới - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-plus"></i> Thêm sách mới</h3>
    
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tên sách</label>
                    <input type="text" name="ten_sach" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Thể loại</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Chọn thể loại --</option>
                        @foreach($categories as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->ten_the_loai }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tác giả</label>
                    <input type="text" name="tac_gia" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Năm xuất bản</label>
                    <input type="number" name="nam_xuat_ban" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh bìa</label>
            <input type="file" name="hinh_anh" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="4"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Giá (VNĐ)</label>
                    <input type="number" name="gia" class="form-control" min="0" step="1000" placeholder="Để trống nếu miễn phí">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Định dạng</label>
                    <select name="dinh_dang" class="form-control" required>
                        <option value="Sách giấy">Sách giấy</option>
                        <option value="PDF">PDF</option>
                        <option value="EPUB">EPUB</option>
                        <option value="MOBI">MOBI</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-control" required>
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Tạm dừng</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection


