@extends('layouts.staff')

@section('title', 'Quản Lý Danh Mục - Nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-folder text-primary me-2"></i>
            Quản lý danh mục
        </h3>
        <p class="text-muted mb-0">Quản lý các danh mục sách</p>
    </div>
    <div>
        <a href="{{ route('staff.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Thêm mới
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên danh mục</th>
                        <th>Số lượng sách</th>
                        <th>Mô tả</th>
                        <th width="120">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><strong>#{{ $category->id }}</strong></td>
                        <td>
                            <strong>{{ $category->ten_danh_muc ?? $category->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $category->books_count ?? $category->books()->count() }}</span>
                        </td>
                        <td>{{ Str::limit($category->mo_ta ?? $category->description ?? 'N/A', 50) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staff.categories.show', $category->id) }}" class="btn btn-info" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.categories.edit', $category->id) }}" class="btn btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Không có danh mục nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($categories, 'links'))
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

