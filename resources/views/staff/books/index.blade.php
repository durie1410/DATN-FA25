@extends('layouts.staff')

@section('title', 'Quản lý sách - Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý sách</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Danh sách sách</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Danh mục</th>
                                    <th>Số lượng</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books ?? [] as $book)
                                <tr>
                                    <td>{{ $book->id }}</td>
                                    <td>{{ $book->ten_sach }}</td>
                                    <td>{{ $book->tac_gia }}</td>
                                    <td>{{ $book->category->ten_the_loai ?? 'N/A' }}</td>
                                    <td>{{ $book->getTotalCopiesAttribute() }}</td>
                                    <td>
                                        <a href="{{ route('staff.books.show', $book->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('staff.books.edit', $book->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
