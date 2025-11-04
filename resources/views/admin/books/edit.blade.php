@extends('layouts.admin')

@section('title', 'Sửa Sách - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-edit"></i> Sửa sách</h3>
    
    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tên sách</label>
                    <input type="text" name="ten_sach" value="{{ $book->ten_sach }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Thể loại</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $cate)
                            <option value="{{ $cate->id }}" {{ $book->category_id == $cate->id ? 'selected' : '' }}>
                                {{ $cate->ten_the_loai }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tác giả</label>
                    <input type="text" name="tac_gia" value="{{ $book->tac_gia }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Năm xuất bản</label>
                    <input type="number" name="nam_xuat_ban" value="{{ $book->nam_xuat_ban }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh bìa</label>
            @if($book->hinh_anh)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" 
                         width="120" height="160" 
                         style="object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                         alt="{{ $book->ten_sach }}">
                </div>
            @endif
            <input type="file" name="hinh_anh" class="form-control">
            <small class="form-text text-muted">Chọn ảnh mới để thay thế ảnh hiện tại</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control" rows="4">{{ $book->mo_ta }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Giá (VNĐ)</label>
                    <input type="number" name="gia" value="{{ $book->gia }}" class="form-control" min="0" step="1000" placeholder="Để trống nếu miễn phí">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Định dạng</label>
                    <select name="dinh_dang" class="form-control" required>
                        <option value="Sách giấy" {{ $book->dinh_dang == 'Sách giấy' ? 'selected' : '' }}>Sách giấy</option>
                        <option value="PDF" {{ $book->dinh_dang == 'PDF' ? 'selected' : '' }}>PDF</option>
                        <option value="EPUB" {{ $book->dinh_dang == 'EPUB' ? 'selected' : '' }}>EPUB</option>
                        <option value="MOBI" {{ $book->dinh_dang == 'MOBI' ? 'selected' : '' }}>MOBI</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-control" required>
                        <option value="active" {{ $book->trang_thai == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ $book->trang_thai == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
