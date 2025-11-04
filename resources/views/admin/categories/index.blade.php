@extends('layouts.admin')

@section('title', 'Quản Lý Thể Loại - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý thể loại sách</h4>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
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

    <!-- Thanh công cụ -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm thể loại mới
                </a>
                <a href="{{ route('admin.categories.statistics') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-1"></i>Thống kê
                </a>
                <a href="{{ route('admin.categories.export', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i>Xuất Excel
                </a>
                <a href="{{ route('admin.categories.print', request()->query()) }}" class="btn btn-warning" target="_blank">
                    <i class="fas fa-print me-1"></i>In danh sách
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" onclick="selectAll()">
                    <i class="fas fa-check-square me-1"></i>Chọn tất cả
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                    <i class="fas fa-square me-1"></i>Bỏ chọn
                </button>
            </div>
        </div>
    </div>

    <!-- Form tìm kiếm và lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên thể loại...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Số sách từ</label>
                    <input type="number" name="min_books" value="{{ request('min_books') }}" class="form-control" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Số sách đến</label>
                    <input type="number" name="max_books" value="{{ request('max_books') }}" class="form-control" placeholder="100">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sắp xếp</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="books_count" {{ request('sort_by') == 'books_count' ? 'selected' : '' }}>Số sách</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Thứ tự</label>
                    <select name="sort_order" class="form-select">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk actions -->
    <form id="bulkForm" action="{{ route('admin.categories.bulk-action') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted">Hành động hàng loạt:</span>
            <select name="action" class="form-select" style="width: auto;">
                <option value="">-- Chọn hành động --</option>
                <option value="activate">Kích hoạt</option>
                <option value="deactivate">Vô hiệu hóa</option>
                <option value="delete">Xóa</option>
            </select>
            <button type="submit" class="btn btn-outline-primary" onclick="return confirmBulkAction()">
                <i class="fas fa-play me-1"></i>Thực hiện
            </button>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" onchange="toggleAll()">
                            </th>
                            <th>ID</th>
                            <th>Tên thể loại</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Số sách</th>
                            <th>Ngày tạo</th>
                            <th width="200">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="category-checkbox">
                            </td>
                            <td><span class="badge bg-secondary fw-bold">{{ $category->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($category->mau_sac)
                                        <div class="color-indicator me-2" style="width: 20px; height: 20px; background-color: {{ $category->mau_sac }}; border-radius: 50%;"></div>
                                    @endif
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} me-2"></i>
                                    @endif
                                    <strong>{{ $category->ten_the_loai }}</strong>
                                </div>
                            </td>
                            <td>
                                @if($category->mo_ta)
                                    <span class="text-muted">{{ Str::limit($category->mo_ta, 50) }}</span>
                                @else
                                    <span class="text-muted">Chưa có mô tả</span>
                                @endif
                            </td>
                            <td>
                                @if($category->trang_thai == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->books_count }}</span>
                                @if($category->books_count > 0)
                                    <br><small class="text-muted">{{ $category->books_count }} cuốn</small>
                                @endif
                            </td>
                            <td>{{ $category->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($category->trang_thai == 'active')
                                        <form method="POST" action="{{ route('admin.categories.bulk-action') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="deactivate">
                                            <input type="hidden" name="category_ids[]" value="{{ $category->id }}">
                                            <button type="submit" class="btn btn-warning btn-sm" title="Vô hiệu hóa" onclick="return confirm('Vô hiệu hóa thể loại này?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.categories.bulk-action') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="activate">
                                            <input type="hidden" name="category_ids[]" value="{{ $category->id }}">
                                            <button type="submit" class="btn btn-success btn-sm" title="Kích hoạt" onclick="return confirm('Kích hoạt thể loại này?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Xóa thể loại này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-folder fa-3x mb-3"></i>
                                    <p>Không có thể loại nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            {{ $categories->appends(request()->query())->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.category-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }

    function selectAll() {
        const checkboxes = document.querySelectorAll('.category-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        document.getElementById('selectAll').checked = true;
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.category-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
    }

    function confirmBulkAction() {
        const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
        const action = document.querySelector('select[name="action"]').value;
        
        if (selectedCheckboxes.length === 0) {
            alert('Vui lòng chọn ít nhất một thể loại!');
            return false;
        }
        
        if (!action) {
            alert('Vui lòng chọn hành động!');
            return false;
        }
        
        let message = '';
        switch(action) {
            case 'activate':
                message = `Bạn có chắc muốn kích hoạt ${selectedCheckboxes.length} thể loại đã chọn?`;
                break;
            case 'deactivate':
                message = `Bạn có chắc muốn vô hiệu hóa ${selectedCheckboxes.length} thể loại đã chọn?`;
                break;
            case 'delete':
                message = `Bạn có chắc muốn xóa ${selectedCheckboxes.length} thể loại đã chọn? Hành động này không thể hoàn tác!`;
                break;
        }
        
        return confirm(message);
    }

    // Cập nhật trạng thái checkbox "Chọn tất cả" khi thay đổi checkbox riêng lẻ
    document.querySelectorAll('.category-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.category-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.category-checkbox:checked');
            
            document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length;
        });
    });
</script>
@endsection
