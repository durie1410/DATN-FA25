@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng - Admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="page-title">
                <i class="fas fa-users-cog me-3"></i>
                Quản Lý Người Dùng
            </h1>
            <p class="page-subtitle">Quản lý tài khoản và phân quyền người dùng trong hệ thống</p>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus me-2"></i>
                Thêm Người Dùng
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card blue">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-content">
                <h3>Tổng Người Dùng</h3>
                <div class="number">{{ $totalUsers ?? 0 }} <span class="unit">Người</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card green">
            <div class="stats-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stats-content">
                <h3>Quản Trị Viên</h3>
                <div class="number">{{ $adminUsers ?? 0 }} <span class="unit">Người</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card orange">
            <div class="stats-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stats-content">
                <h3>Nhân Viên</h3>
                <div class="number">{{ $staffUsers ?? 0 }} <span class="unit">Người</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card purple">
            <div class="stats-icon">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stats-content">
                <h3>Người Dùng</h3>
                <div class="number">{{ $regularUsers ?? 0 }} <span class="unit">Người</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-table">
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Tìm kiếm</label>
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, email...">
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Vai trò</label>
            <select class="form-select" name="role">
                <option value="">Tất cả vai trò</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label class="form-label">Trạng thái</label>
            <select class="form-select" name="status">
                <option value="">Tất cả trạng thái</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>
    
    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Thông tin</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hoạt động cuối</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">
                            @switch($user->role)
                                @case('admin')
                                    <i class="fas fa-crown me-1"></i>Quản trị viên
                                    @break
                                @case('staff')
                                    <i class="fas fa-user-tie me-1"></i>Nhân viên
                                    @break
                                @default
                                    <i class="fas fa-user me-1"></i>Người dùng
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-active">
                            <i class="fas fa-circle me-1"></i>N/A
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>{{ $user->updated_at->diffForHumans() }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-info btn-sm" onclick="viewUser({{ $user->id }})" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>Chưa có người dùng nào</h5>
                            <p class="text-muted">Bắt đầu bằng cách thêm người dùng đầu tiên</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(isset($users) && $users->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} trong tổng số {{ $users->total() }} kết quả
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Thêm Người Dùng Mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" required>
                                    <option value="">Chọn vai trò</option>
                                    <option value="admin">Quản trị viên</option>
                                    <option value="staff">Nhân viên</option>
                                    <option value="user">Người dùng</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Lưu Người Dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>
                    Chỉnh Sửa Người Dùng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" name="password">
                                <small class="form-text text-muted">Để trống nếu không muốn thay đổi</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" id="edit_role" required>
                                    <option value="">Chọn vai trò</option>
                                    <option value="admin">Quản trị viên</option>
                                    <option value="staff">Nhân viên</option>
                                    <option value="user">Người dùng</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status" id="edit_status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Cập Nhật Người Dùng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .page-subtitle {
        font-size: 1rem;
        margin: 10px 0 0 0;
        opacity: 0.9;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    
    .user-details {
        flex: 1;
    }
    
    .user-name {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 2px;
    }
    
    .user-email {
        font-size: 12px;
        color: #6c757d;
    }
    
    .role-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-admin {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .role-staff {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }
    
    .role-user {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-active {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .empty-state {
        padding: 40px;
    }
    
    .empty-state i {
        opacity: 0.5;
    }
    
    .btn-group .btn {
        margin: 0 1px;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
    }
    
    .modal-header .btn-close {
        filter: invert(1);
    }

/* --- Bổ sung/ghi đè style các stats card hiển thị tổng số người dùng --- */
.stats-card {
    border-radius: 18px !important;
    box-shadow: 0 6px 26px 0 rgba(0,255,153,0.14) !important;
    border: none !important;
    padding: 38px 28px 32px 28px !important;
    background: linear-gradient(127deg, #142d2d 40%, #00ff99 185%) !important;
    display: flex !important;
    align-items: center;
    gap: 22px;
    transition: transform 0.19s cubic-bezier(0.4,0,0.2,1), box-shadow 0.22s cubic-bezier(0.4,0,0.2,1);
    position: relative;
}
.stats-card:hover {
    transform: scale(1.05) translateY(-7px) !important;
    box-shadow: 0 18px 38px 0 rgba(0,255,153,0.26) !important;
    z-index: 1;
}
.stats-card .stats-icon {
    font-size: 2.6rem !important;
    background: linear-gradient(135deg,#00ff99 65%,#338c81 95%);
    border-radius: 16px;
    width: 60px;
    height: 60px;
    display: flex; align-items: center; justify-content: center;
    color: #fff !important;
    box-shadow: 0 2px 12px #00ff99cc, 0 1.5px 5px #3333;
    margin-right: 14px;
}
.stats-content h3 {
    font-size: 1.21rem !important;
    font-weight: 800;
    color: #fff !important;
    margin-bottom: 7px;
    letter-spacing:.03em;
}
.stats-content .number {
    font-size: 2.35rem !important;
    font-weight: 900;
    color: #fffb70 !important;
    margin-top: 8px;
    text-shadow: 0 2px 8px #050 0.22;
}
.stats-content .unit {
    font-size: 1rem;
    color: #f0f0f0;
    opacity: 0.75;
    margin-left: 3px;
}
/* Hiệu ứng phần stats-card theo màu vai trò */
.stats-card.blue {background:linear-gradient(120deg,#184c4a 55%,#067 160%)!important;}
.stats-card.green {background:linear-gradient(130deg,#035949 66%,#00ff99 186%)!important;}
.stats-card.orange {background:linear-gradient(130deg,#7e4a15 77%,#ffb86c 186%)!important;}
.stats-card.purple {background:linear-gradient(120deg,#2e194d 55%,#9067ff 190%)!important;}
.stats-card .stats-icon {
    border: 2.8px solid #fff3 !important;
}
/* --- END custom section đẹp --- */
</style>

<script>
    function viewUser(userId) {
        // Implement view user functionality
        console.log('View user:', userId);
    }
    
    function editUser(userId) {
        // Fetch user data and populate edit form
        fetch(`/admin/users/${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_status').value = data.status;
                
                document.getElementById('editUserForm').action = `/admin/users/${userId}`;
                
                const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                editModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tải thông tin người dùng');
            });
    }
    
    function deleteUser(userId) {
        if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
            fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa người dùng');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa người dùng');
            });
        }
    }
    
    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection
