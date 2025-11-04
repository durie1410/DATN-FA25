@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Danh sách sách</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Thêm sách</a>

    {{-- Form tìm kiếm + lọc --}}
    <form action="{{ route('books.index') }}" method="GET" class="row mb-4">
        <div class="col-md-4">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên sách hoặc tác giả...">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-control">
                <option value="">-- Tất cả thể loại --</option>
                @foreach($categories as $cate)
                    <option value="{{ $cate->id }}" {{ request('category_id') == $cate->id ? 'selected' : '' }}>
                        {{ $cate->ten_the_loai }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Lọc</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('books.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Bảng danh sách --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sách</th>
                <th>Thể loại</th>
                <th>Tác giả</th>
                <th>Năm XB</th>
                <th>Ảnh bìa</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td>{{ $book->ten_sach }}</td>
                <td>{{ $book->category->ten_the_loai ?? '' }}</td>
                <td>{{ $book->tac_gia }}</td>
                <td>{{ $book->nam_xuat_ban }}</td>
                <td>
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/'.$book->hinh_anh) }}" width="60">
                    @endif
                </td>
                <td>
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa sách này?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Không có sách phù hợp</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
