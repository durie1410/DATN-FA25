@extends('layouts.admin')

@section('title', 'Quản Lý Nhà Xuất Bản - Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-building"></i> Quản lý nhà xuất bản</h2>
                <a href="{{ route('admin.publishers.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm nhà xuất bản mới
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
    <div class="row mb-4 publisher-search-card">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.publishers.index') }}" method="GET" class="row">
                        <div class="col-md-4">
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tìm theo tên nhà xuất bản...">
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
                                <option value="ten_nha_xuat_ban" {{ request('sort_by') == 'ten_nha_xuat_ban' ? 'selected' : '' }}>Sắp xếp theo tên</option>
                                <option value="books_count" {{ request('sort_by') == 'books_count' ? 'selected' : '' }}>Sắp xếp theo số sách</option>
                                <option value="ngay_thanh_lap" {{ request('sort_by') == 'ngay_thanh_lap' ? 'selected' : '' }}>Sắp xếp theo ngày thành lập</option>
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

    <!-- Publishers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách nhà xuất bản ({{ $publishers->total() }} kết quả)</h5>
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
                    @if($publishers->count() > 0)
                        <form id="bulkForm" action="{{ route('admin.publishers.bulk-action') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 publisher-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                                            </th>
                                            <th>Logo</th>
                                            <th>Tên nhà xuất bản</th>
                                            <th>Địa chỉ</th>
                                            <th>Liên hệ</th>
                                            <th>Số sách</th>
                                            <th>Ngày thành lập</th>
                                            <th>Trạng thái</th>
                                            <th width="200">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($publishers as $publisher)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_ids[]" value="{{ $publisher->id }}" class="row-checkbox">
                                                </td>
                                                <td>
                                                    @if($publisher->logo)
                                                        <img src="{{ asset('storage/' . $publisher->logo) }}" alt="{{ $publisher->ten_nha_xuat_ban }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-building text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $publisher->ten_nha_xuat_ban }}</strong>
                                                        @if($publisher->website)
                                                            <br><small class="text-muted">
                                                                <a href="{{ $publisher->website }}" target="_blank" class="text-decoration-none">
                                                                    <i class="fas fa-globe"></i> {{ $publisher->website }}
                                                                </a>
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($publisher->dia_chi)
                                                        <small>{{ Str::limit($publisher->dia_chi, 50) }}</small>
                                                    @else
                                                        <small class="text-muted">Chưa cập nhật</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        @if($publisher->so_dien_thoai)
                                                            <small><i class="fas fa-phone"></i> {{ $publisher->so_dien_thoai }}</small><br>
                                                        @endif
                                                        @if($publisher->email)
                                                            <small><i class="fas fa-envelope"></i> {{ $publisher->email }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $publisher->books_count }}</span>
                                                </td>
                                                <td>
                                                    <small>{{ $publisher->formatted_founded_date }}</small>
                                                </td>
                                                <td>
                                                    {!! $publisher->status_badge !!}
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.publishers.show', $publisher->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.publishers.edit', $publisher->id) }}" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.publishers.toggle-status', $publisher->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-{{ $publisher->trang_thai === 'active' ? 'secondary' : 'success' }}" 
                                                                    title="{{ $publisher->trang_thai === 'active' ? 'Tạm dừng' : 'Kích hoạt' }}"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn {{ $publisher->trang_thai === 'active' ? 'tạm dừng' : 'kích hoạt' }} nhà xuất bản này?')">
                                                                <i class="fas fa-{{ $publisher->trang_thai === 'active' ? 'pause' : 'play' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.publishers.destroy', $publisher->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa nhà xuất bản này? Hành động này không thể hoàn tác!')">
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
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có nhà xuất bản nào</h5>
                            <p class="text-muted">Hãy thêm nhà xuất bản đầu tiên để bắt đầu quản lý.</p>
                            <a href="{{ route('admin.publishers.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm nhà xuất bản mới
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{ $publishers->appends(request()->query())->links('vendor.pagination.admin') }}
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
        alert('Vui lòng chọn ít nhất một nhà xuất bản!');
        return false;
    }
    
    if (!action) {
        alert('Vui lòng chọn hành động!');
        return false;
    }
    
    let message = '';
    switch(action) {
        case 'activate':
            message = `Bạn có chắc chắn muốn kích hoạt ${selectedCount} nhà xuất bản được chọn?`;
            break;
        case 'deactivate':
            message = `Bạn có chắc chắn muốn tạm dừng ${selectedCount} nhà xuất bản được chọn?`;
            break;
        case 'delete':
            message = `Bạn có chắc chắn muốn xóa ${selectedCount} nhà xuất bản được chọn? Hành động này không thể hoàn tác!`;
            break;
    }
    
    return confirm(message);
}
</script>
@endsection

<style>
    .publisher-search-card {
        box-shadow: 0 4px 20px rgba(80,220,180,0.05);
        border-radius: 16px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(0,255,153,0.1);
        margin-bottom: 32px;
    }
    .publisher-table thead tr th {
        font-weight: 700;
        color: #4facfe;
        background: rgba(255,255,255,0.02);
        letter-spacing: 0.5px;
    }
    .publisher-table tbody td {
        vertical-align: middle;
    }
    .publisher-table {
        border-radius: 16px;
        overflow: hidden;
    }
    .publisher-table .badge.bg-info {
        background: linear-gradient(90deg,#31d58d,#40c4ff);
        color: white;
        font-size: 1em;
        font-weight: 700;
        border-radius: 6px;
        padding: 6px 14px;
        box-shadow: 0 2px 8px rgba(0,255,153,0.08);
    }
    .publisher-table .badge.bg-info {
        border: none;
    }
    .publisher-table td:first-child input[type="checkbox"] {
        width: 20px; height: 20px;
        accent-color: #1de9b6;
    }
    .publisher-table td img.img-thumbnail {
        border:2px solid #1de9b6;
        box-shadow:0 2px 10px rgba(31,213,141,0.15);
    }
    .publisher-status-badge {
        border-radius: 6px;
        font-weight: 500;
        color: white !important;
        padding: 2px 12px;
        font-size: 0.93em;
    }
    .publisher-status-badge.bg-success { background: #38d39f; }
    .publisher-status-badge.bg-warning { background: #FFD600; color: #333 !important;}
    .publisher-status-badge.bg-secondary { background: #90a4ae; }
    .publisher-status-badge.bg-danger { background: #ff5252; }
    .publisher-table .fas.fa-building { color: #00b894; }
</style>
