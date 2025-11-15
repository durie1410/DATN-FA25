@extends('layouts.admin')

@section('title', 'Quản Lý Thủ Thư')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-tie me-2"></i>Quản Lý Thủ Thư
            </h1>
            <p class="text-muted mb-0">Quản lý thông tin và hoạt động của thủ thư</p>
        </div>
        <div>
            <a href="{{ route('admin.librarians.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Thêm Thủ Thư
            </a>
            <button type="button" class="btn btn-success" onclick="exportLibrarians()">
                <i class="fas fa-download me-1"></i>Xuất Excel
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow statistics-card-square">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-tie fa-3x text-gray-300"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                        Tổng Thủ Thư</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $totalLibrarians }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow statistics-card-square">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-gray-300"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                        Đang Hoạt Động</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $activeLibrarians }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow statistics-card-square">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="mb-3">
                        <i class="fas fa-pause-circle fa-3x text-gray-300"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-2">
                        Tạm Dừng</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $inactiveLibrarians }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow statistics-card-square">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-gray-300"></i>
                    </div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-2">
                        Hợp Đồng Sắp Hết Hạn</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $expiringContracts }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search me-2"></i>Tìm Kiếm và Lọc
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.librarians.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Từ khóa</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Tên, email, mã thủ thư...">
                </div>
                <div class="col-md-2">
                    <label for="chuc_vu" class="form-label">Chức vụ</label>
                    <select class="form-select" id="chuc_vu" name="chuc_vu">
                        <option value="">Tất cả</option>
                        <option value="Thủ thư trưởng" {{ request('chuc_vu') == 'Thủ thư trưởng' ? 'selected' : '' }}>Thủ thư trưởng</option>
                        <option value="Nhân viên thư viện" {{ request('chuc_vu') == 'Nhân viên thư viện' ? 'selected' : '' }}>Nhân viên thư viện</option>
                        <option value="Trợ lý thư viện" {{ request('chuc_vu') == 'Trợ lý thư viện' ? 'selected' : '' }}>Trợ lý thư viện</option>
                        <option value="Chuyên viên thư viện" {{ request('chuc_vu') == 'Chuyên viên thư viện' ? 'selected' : '' }}>Chuyên viên thư viện</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="trang_thai" class="form-label">Trạng thái</label>
                    <select class="form-select" id="trang_thai" name="trang_thai">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('trang_thai') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('trang_thai') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="phong_ban" class="form-label">Phòng ban</label>
                    <select class="form-select" id="phong_ban" name="phong_ban">
                        <option value="">Tất cả</option>
                        <option value="Phòng Quản lý Thư viện" {{ request('phong_ban') == 'Phòng Quản lý Thư viện' ? 'selected' : '' }}>Phòng Quản lý Thư viện</option>
                        <option value="Phòng Phục vụ Độc giả" {{ request('phong_ban') == 'Phòng Phục vụ Độc giả' ? 'selected' : '' }}>Phòng Phục vụ Độc giả</option>
                        <option value="Phòng Kỹ thuật" {{ request('phong_ban') == 'Phòng Kỹ thuật' ? 'selected' : '' }}>Phòng Kỹ thuật</option>
                        <option value="Phòng Tài liệu" {{ request('phong_ban') == 'Phòng Tài liệu' ? 'selected' : '' }}>Phòng Tài liệu</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('admin.librarians.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Librarians Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Danh Sách Thủ Thư
            </h6>
            <div>
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('activate')">
                    <i class="fas fa-check me-1"></i>Kích hoạt
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="bulkAction('deactivate')">
                    <i class="fas fa-pause me-1"></i>Vô hiệu hóa
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                    <i class="fas fa-trash me-1"></i>Xóa
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>#</th>
                            <th>Thông tin</th>
                            <th>Mã thủ thư</th>
                            <th>Chức vụ</th>
                            <th>Phòng ban</th>
                            <th>Ngày vào làm</th>
                            <th>Hợp đồng</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($librarians ?? [] as $librarian)
                        <tr>
                            <td>
                                <input type="checkbox" class="librarian-checkbox" value="{{ $librarian->id }}">
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="librarian-info">
                                    <div class="librarian-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="librarian-details">
                                        <div class="librarian-name">{{ $librarian->ho_ten }}</div>
                                        <div class="librarian-email">{{ $librarian->email }}</div>
                                        <div class="librarian-phone">{{ $librarian->so_dien_thoai }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $librarian->ma_thu_thu }}</span>
                            </td>
                            <td>{{ $librarian->chuc_vu }}</td>
                            <td>{{ $librarian->phong_ban }}</td>
                            <td>
                                {{ $librarian->ngay_vao_lam ? $librarian->ngay_vao_lam->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>
                                @if($librarian->ngay_het_han_hop_dong)
                                    <div class="contract-info">
                                        <div class="contract-date">{{ $librarian->ngay_het_han_hop_dong->format('d/m/Y') }}</div>
                                        @if($librarian->isContractExpiringSoon())
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Sắp hết hạn
                                            </small>
                                        @elseif($librarian->isContractExpired())
                                            <small class="text-danger">
                                                <i class="fas fa-times-circle"></i> Đã hết hạn
                                            </small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $librarian->trang_thai }}">
                                    @if($librarian->trang_thai === 'active')
                                        <i class="fas fa-check-circle me-1"></i>Hoạt động
                                    @else
                                        <i class="fas fa-pause-circle me-1"></i>Tạm dừng
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.librarians.show', $librarian) }}" 
                                       class="btn btn-sm btn-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.librarians.edit', $librarian) }}" 
                                       class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-{{ $librarian->trang_thai === 'active' ? 'secondary' : 'success' }}" 
                                            onclick="toggleStatus({{ $librarian->id }})" 
                                            title="{{ $librarian->trang_thai === 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                        <i class="fas fa-{{ $librarian->trang_thai === 'active' ? 'pause' : 'play' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteLibrarian({{ $librarian->id }})" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Không có thủ thư nào</h5>
                                    <p class="text-muted">Chưa có thủ thư nào trong hệ thống.</p>
                                    <a href="{{ route('admin.librarians.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Thêm thủ thư đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($librarians && $librarians->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $librarians->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Renew Contract Modal -->
<div class="modal fade" id="renewContractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gia Hạn Hợp Đồng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="renewContractForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ngay_het_han_moi" class="form-label">Ngày hết hạn mới</label>
                        <input type="date" class="form-control" id="ngay_het_han_moi" name="ngay_het_han_moi" required>
                    </div>
                    <div class="mb-3">
                        <label for="luong_moi" class="form-label">Lương mới (tùy chọn)</label>
                        <input type="number" class="form-control" id="luong_moi" name="luong_moi" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Gia hạn</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.statistics-card-square {
    aspect-ratio: 1;
    min-height: 200px;
}

.statistics-card-square .card-body {
    height: 100%;
    padding: 1.5rem;
}

.librarian-info {
    display: flex;
    align-items: center;
}

.librarian-avatar {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: #6c757d;
}

.librarian-details {
    flex: 1;
}

.librarian-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.librarian-email {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 2px;
}

.librarian-phone {
    font-size: 0.75rem;
    color: #adb5bd;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-inactive {
    background-color: #f8d7da;
    color: #721c24;
}

.contract-info {
    text-align: center;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}

.role-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.role-admin {
    background-color: #fff3cd;
    color: #856404;
}

.role-staff {
    background-color: #d1ecf1;
    color: #0c5460;
}

.role-user {
    background-color: #d4edda;
    color: #155724;
}
</style>
@endsection

@section('scripts')
<script>
// Toggle select all
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.librarian-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Bulk actions
function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.librarian-checkbox:checked');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Vui lòng chọn ít nhất một thủ thư!');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Bạn có chắc muốn kích hoạt ${ids.length} thủ thư?`;
            break;
        case 'deactivate':
            confirmMessage = `Bạn có chắc muốn vô hiệu hóa ${ids.length} thủ thư?`;
            break;
        case 'delete':
            confirmMessage = `Bạn có chắc muốn xóa ${ids.length} thủ thư? Hành động này không thể hoàn tác!`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        fetch('{{ route("admin.librarians.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                action: action,
                ids: ids
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    }
}

// Toggle status
function toggleStatus(librarianId) {
    fetch(`/admin/librarians/${librarianId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra!');
    });
}

// Delete librarian
function deleteLibrarian(librarianId) {
    if (confirm('Bạn có chắc muốn xóa thủ thư này? Hành động này không thể hoàn tác!')) {
        fetch(`/admin/librarians/${librarianId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    }
}

// Export librarians
function exportLibrarians() {
    window.open('{{ route("admin.librarians.export") }}', '_blank');
}

// Renew contract
function renewContract(librarianId) {
    document.getElementById('renewContractForm').action = `/admin/librarians/${librarianId}/renew-contract`;
    new bootstrap.Modal(document.getElementById('renewContractModal')).show();
}

document.getElementById('renewContractForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const librarianId = this.action.split('/').pop();
    
    fetch(`/admin/librarians/${librarianId}/renew-contract`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('renewContractModal')).hide();
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra!');
    });
});
</script>
@endsection
