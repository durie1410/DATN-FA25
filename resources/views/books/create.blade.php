@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm sách</h1>
    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Tên sách</label>
            <input type="text" name="ten_sach" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Thể loại</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn thể loại --</option>
                @foreach($categories as $cate)
                    <option value="{{ $cate->id }}">{{ $cate->ten_the_loai }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tác giả</label>
            <input type="text" name="tac_gia" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Năm xuất bản</label>
            <input type="number" name="nam_xuat_ban" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ảnh bìa</label>
            <input type="file" name="hinh_anh" class="form-control">
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="mo_ta" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection



