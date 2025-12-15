@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Danh sách thể loại</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Thêm thể loại</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thể loại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cate)
            <tr>
                <td>{{ $cate->id }}</td>
                <td>{{ $cate->ten_the_loai }}</td>
                <td>
                    <a href="{{ route('categories.edit', $cate->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('categories.destroy', $cate->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection



