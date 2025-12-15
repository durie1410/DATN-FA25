@extends('layouts.admin')

@section('title', 'Quản Lý Khoa - Admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý khoa</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-university"></i> Quản lý khoa</h2>
                <a href="{{ route('admin.faculties.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm khoa mới
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

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.faculties.index') }}" method="GET" class="row">
                        <div class="col-md-4">
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên hoặc mã khoa...">
                        </div>
                        <div class="col-md-3">
                            <select name="trang_thai" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('trang_thai') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('trang_thai') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="sort_by" class="form-control">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sắp xếp theo ngày tạo</option>
                                <option value="ten_khoa" {{ request('sort_by') == 'ten_khoa' ? 'selected' : '' }}>Sắp xếp theo tên</option>
                                <option value="ma_khoa" {{ request('sort_by') == 'ma_khoa' ? 'selected' : '' }}>Sắp xếp theo mã</option>
                                <option value="departments_count" {{ request('sort_by') == 'departments_count' ? 'selected' : '' }}>Sắp xếp theo số ngành</option>
                                <option value="readers_count" {{ request('sort_by') == 'readers_count' ? 'selected' : '' }}>Sắp xếp theo số sinh viên</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculties Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách khoa ({{ $faculties->total() }} kết quả)</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-square"></i> Chọn tất cả
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                                <i class="fas fa-square"></i> Bỏ chọn
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($faculties->count() > 0)
                        <form id="bulkForm" action="{{ route('admin.faculties.bulk-action') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                                            </th>
                                            <th>Logo</th>
                                            <th>Tên khoa</th>
                                            <th>Mã khoa</th>
                                            <th>Số ngành</th>
                                            <th>Số sinh viên</th>
                                            <th>Trưởng khoa</th>
                                            <th>Trạng thái</th>
                                            <th width="200">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($faculties as $faculty)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_ids[]" value="{{ $faculty->id }}" class="row-checkbox">
                                                </td>
                                                <td>
                                                    @if($faculty->logo)
                                                        <img src="{{ asset('storage/' . $faculty->logo) }}" alt="{{ $faculty->ten_khoa }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-university text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $faculty->ten_khoa }}</strong>
                                                        @if($faculty->website)
                                                            <br><small class="text-muted">
                                                                <a href="{{ $faculty->website }}" target="_blank" class="text-decoration-none">
                                                                    <i class="fas fa-globe"></i> {{ $faculty->website }}
                                                                </a>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $faculty->ma_khoa }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $faculty->departments_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $faculty->readers_count }}</span>
                                                </td>
                                                <td>
                                                    @if($faculty->truong_khoa)
                                                        <small>{{ $faculty->truong_khoa }}</small>
                                                    @else
                                                        <small class="text-muted">Chưa cập nhật</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! $faculty->status_badge !!}
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.faculties.show', $faculty->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.faculties.edit', $faculty->id) }}" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.faculties.toggle-status', $faculty->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-{{ $faculty->trang_thai === 'active' ? 'secondary' : 'success' }}" 
                                                                    title="{{ $faculty->trang_thai === 'active' ? 'Tạm dừng' : 'Kích hoạt' }}"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn {{ $faculty->trang_thai === 'active' ? 'tạm dừng' : 'kích hoạt' }} khoa này?')">
                                                                <i class="fas fa-{{ $faculty->trang_thai === 'active' ? 'pause' : 'play' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.faculties.destroy', $faculty->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa khoa này? Hành động này không thể hoàn tác!')">
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
                            
                            <!-- Bulk Actions -->
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span id="selectedCount">0</span> mục được chọn
                                    </div>
                                    <div class="d-flex gap-2">
                                        <select name="action" class="form-select form-select-sm" style="width: auto;" required>
                                            <option value="">Chọn hành động</option>
                                            <option value="activate">Kích hoạt</option>
                                            <option value="deactivate">Tạm dừng</option>
                                            <option value="delete">Xóa</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirmBulkAction()">
                                            <i class="fas fa-check"></i> Thực hiện
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-university fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có khoa nào</h5>
                            <p class="text-muted">Hãy thêm khoa đầu tiên để bắt đầu quản lý.</p>
                            <a href="{{ route('admin.faculties.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm khoa mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{ $faculties->appends(request()->query())->links('vendor.pagination.admin') }}
</div>

<script>
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

function selectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    selectAllCheckbox.checked = true;
    toggleSelectAll();
}

function clearSelection() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    selectAllCheckbox.checked = false;
    toggleSelectAll();
}

function updateSelectedCount() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    document.getElementById('selectedCount').textContent = selectedCheckboxes.length;
}

// Add event listeners to row checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    updateSelectedCount();
});

function confirmBulkAction() {
    const selectedCount = document.querySelectorAll('.row-checkbox:checked').length;
    const action = document.querySelector('select[name="action"]').value;
    
    if (selectedCount === 0) {
        alert('Vui lòng chọn ít nhất một khoa!');
        return false;
    }
    
    if (!action) {
        alert('Vui lòng chọn hành động!');
        return false;
    }
    
    let message = '';
    switch(action) {
        case 'activate':
            message = `Bạn có chắc chắn muốn kích hoạt ${selectedCount} khoa được chọn?`;
            break;
        case 'deactivate':
            message = `Bạn có chắc chắn muốn tạm dừng ${selectedCount} khoa được chọn?`;
            break;
        case 'delete':
            message = `Bạn có chắc chắn muốn xóa ${selectedCount} khoa được chọn? Hành động này không thể hoàn tác!`;
            break;
    }
    
    return confirm(message);
}
</script>
@endsection
