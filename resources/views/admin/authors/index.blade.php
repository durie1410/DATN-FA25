@extends('layouts.admin')

@section('title', 'Quản lý tác giả')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý tác giả</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-user-edit"></i> Quản lý tác giả</h2>
                <a href="{{ route('admin.authors.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm tác giả mới
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Authors Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($authors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh</th>
                                        <th>Tên tác giả</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Ngày sinh</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($authors as $author)
                                        <tr>
                                            <td><span class="badge bg-secondary fw-bold">{{ $author->id }}</span></td>
                                            <td>
                                                @if($author->hinh_anh)
                                                    <img src="{{ asset('storage/' . $author->hinh_anh) }}" 
                                                         width="50" height="50" 
                                                         style="object-fit: cover; border-radius: 50%;"
                                                         alt="{{ $author->ten_tac_gia }}">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; border-radius: 50%;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $author->ten_tac_gia }}</strong>
                                                @if($author->gioi_thieu)
                                                    <br><small class="text-muted">{{ Str::limit($author->gioi_thieu, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $author->email }}</td>
                                            <td>{{ $author->so_dien_thoai ?? 'Chưa cập nhật' }}</td>
                                            <td>{{ $author->formatted_birthday }}</td>
                                            <td>
                                                @if($author->trang_thai === 'active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                    <span class="badge bg-secondary">Tạm dừng</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.authors.show', $author->id) }}" 
                                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.authors.edit', $author->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.authors.destroy', $author->id) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa tác giả này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $authors->appends(request()->query())->links('vendor.pagination.admin') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-edit fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có tác giả nào</h5>
                            <p class="text-muted">Hãy thêm tác giả đầu tiên để bắt đầu quản lý.</p>
                            <a href="{{ route('admin.authors.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm tác giả đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

