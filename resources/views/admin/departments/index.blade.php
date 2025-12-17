@extends('layouts.admin')

@section('title', 'Quản Lý Ngành Học - Admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý ngành học</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-graduation-cap"></i> Quản lý ngành học</h2>
                <a href="{{ route('admin.departments.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm ngành học mới
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
                    <form action="{{ route('admin.departments.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên hoặc mã ngành...">
                        </div>
                        <div class="col-md-3">
                            <select name="faculty_id" class="form-control">
                                <option value="">Tất cả khoa</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->ten_khoa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="trang_thai" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('trang_thai') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('trang_thai') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-control">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sắp xếp theo ngày tạo</option>
                                <option value="ten_nganh" {{ request('sort_by') == 'ten_nganh' ? 'selected' : '' }}>Sắp xếp theo tên</option>
                                <option value="ma_nganh" {{ request('sort_by') == 'ma_nganh' ? 'selected' : '' }}>Sắp xếp theo mã</option>
                                <option value="faculty" {{ request('sort_by') == 'faculty' ? 'selected' : '' }}>Sắp xếp theo khoa</option>
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

    <!-- Departments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách ngành học ({{ $departments->total() }} kết quả)</h5>
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
                    @if($departments->count() > 0)
                        <form id="bulkForm" action="{{ route('admin.departments.bulk-action') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                                            </th>
                                            <th>Logo</th>
                                            <th>Tên ngành</th>
                                            <th>Mã ngành</th>
                                            <th>Khoa</th>
                                            <th>Số sinh viên</th>
                                            <th>Trưởng ngành</th>
                                            <th>Trạng thái</th>
                                            <th width="200">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $department)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_ids[]" value="{{ $department->id }}" class="row-checkbox">
                                                </td>
                                                <td>
                                                    @if($department->logo)
                                                        <img src="{{ asset('storage/' . $department->logo) }}" alt="{{ $department->ten_nganh }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-graduation-cap text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $department->ten_nganh }}</strong>
                                                        @if($department->website)
                                                            <br><small class="text-muted">
                                                                <a href="{{ $department->website }}" target="_blank" class="text-decoration-none">
                                                                    <i class="fas fa-globe"></i> {{ $department->website }}
                                                                </a>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $department->ma_nganh }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $department->faculty->ten_khoa }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $department->readers_count }}</span>
                                                </td>
                                                <td>
                                                    @if($department->truong_nganh)
                                                        <small>{{ $department->truong_nganh }}</small>
                                                    @else
                                                        <small class="text-muted">Chưa cập nhật</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! $department->status_badge !!}
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.departments.show', $department->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.departments.toggle-status', $department->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-{{ $department->trang_thai === 'active' ? 'secondary' : 'success' }}" 
                                                                    title="{{ $department->trang_thai === 'active' ? 'Tạm dừng' : 'Kích hoạt' }}"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn {{ $department->trang_thai === 'active' ? 'tạm dừng' : 'kích hoạt' }} ngành học này?')">
                                                                <i class="fas fa-{{ $department->trang_thai === 'active' ? 'pause' : 'play' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa ngành học này? Hành động này không thể hoàn tác!')">
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
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có ngành học nào</h5>
                            <p class="text-muted">Hãy thêm ngành học đầu tiên để bắt đầu quản lý.</p>
                            <a href="{{ route('admin.departments.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm ngành học mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{ $departments->appends(request()->query())->links('vendor.pagination.admin') }}
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
        alert('Vui lòng chọn ít nhất một ngành học!');
        return false;
    }
    
    if (!action) {
        alert('Vui lòng chọn hành động!');
        return false;
    }
    
    let message = '';
    switch(action) {
        case 'activate':
            message = `Bạn có chắc chắn muốn kích hoạt ${selectedCount} ngành học được chọn?`;
            break;
        case 'deactivate':
            message = `Bạn có chắc chắn muốn tạm dừng ${selectedCount} ngành học được chọn?`;
            break;
        case 'delete':
            message = `Bạn có chắc chắn muốn xóa ${selectedCount} ngành học được chọn? Hành động này không thể hoàn tác!`;
            break;
    }
    
    return confirm(message);
}
</script>
@endsection
