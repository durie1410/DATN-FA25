@extends('layouts.admin')

@section('title', 'Người Dùng - Admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="page-title">
                <i class="fas fa-users-cog me-3"></i>
                Người Dùng
            </h1>
            <p class="page-subtitle">Quản lý tài khoản và phân quyền người dùng trong hệ thống</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Người Dùng
            </a>
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
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-info btn-sm" onclick="viewUser({{ $user->id }})" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
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
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
    @endif
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
</style>

<script>
    function viewUser(userId) {
        // Implement view user functionality
        console.log('View user:', userId);
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
