@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa thể loại</h1>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="ten_the_loai" class="form-label">Tên thể loại</label>
            <input type="text" name="ten_the_loai" class="form-control" value="{{ $category->ten_the_loai }}" required>
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection



