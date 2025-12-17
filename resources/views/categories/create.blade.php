@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm thể loại</h1>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="ten_the_loai" class="form-label">Tên thể loại</label>
            <input type="text" name="ten_the_loai" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection



