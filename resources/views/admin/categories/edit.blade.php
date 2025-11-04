@extends('layouts.admin')

@section('title', 'Sửa Thể Loại - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-edit"></i> Sửa thể loại</h3>
    
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên thể loại</label>
            <input type="text" name="ten_the_loai" value="{{ $category->ten_the_loai }}" class="form-control" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection



