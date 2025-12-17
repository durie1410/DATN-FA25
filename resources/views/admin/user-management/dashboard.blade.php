@extends('layouts.admin')

@section('title', 'Tổng quan - Admin')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-users" style="color: #22c55e;"></i>
            Tổng quan
        </h1>
        <p class="page-subtitle">Quản lý và theo dõi người dùng và độc giả</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="background: #22c55e; color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-user-plus"></i>
            Thêm Người Dùng
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-info" style="background: #3b82f6; color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-users"></i>
            Quản Lý Người Dùng
        </a>
        <a href="{{ route('admin.readers.statistics') }}" class="btn btn-secondary" style="background: #6b7280; color: white; border: none; padding: 12px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-file-alt"></i>
            Báo Cáo Thống Kê
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<!-- Thống kê nhanh -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px;">
    <!-- Card 1: Tổng người dùng -->
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; padding: 24px; position: relative; min-height: 180px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h6 style="font-size: 13px; font-weight: 700; text-transform: uppercase; margin: 0 0 16px 0; letter-spacing: 0.5px; line-height: 1.4; opacity: 0.9;">TỔNG NGƯỜI DÙNG</h6>
        <h3 style="font-size: 42px; font-weight: 700; margin: 0 0 8px 0; line-height: 1.2;">{{ $totalUsers }}</h3>
        <div style="margin-top: 12px; font-size: 14px; opacity: 0.9;">
            <i class="fas fa-user-plus"></i> {{ $newUsersThisMonth }} mới trong tháng
        </div>
        <div style="position: absolute; bottom: 24px; right: 24px; opacity: 0.3;">
            <i class="fas fa-users" style="font-size: 64px;"></i>
        </div>
    </div>

    <!-- Card 2: Phân loại người dùng -->
    <div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; position: relative; min-height: 180px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h6 style="font-size: 13px; font-weight: 700; color: #374151; text-transform: uppercase; margin: 0 0 16px 0; letter-spacing: 0.5px; line-height: 1.4;">PHÂN LOẠI NGƯỜI DÙNG</h6>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;"><i class="fas fa-crown" style="color: #dc3545;"></i> Quản trị viên</span>
                <strong style="color: #1f2937; font-size: 18px;">{{ $adminUsers }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;"><i class="fas fa-user" style="color: #17a2b8;"></i> Người dùng</span>
                <strong style="color: #1f2937; font-size: 18px;">{{ $regularUsers }}</strong>
            </div>
        </div>
    </div>

    <!-- Card 3: Độc giả -->
    <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; border-radius: 12px; padding: 24px; position: relative; min-height: 180px; color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h6 style="font-size: 13px; font-weight: 700; text-transform: uppercase; margin: 0 0 16px 0; letter-spacing: 0.5px; line-height: 1.4; opacity: 0.9;">TỔNG ĐỘC GIẢ</h6>
        <h3 style="font-size: 42px; font-weight: 700; margin: 0 0 8px 0; line-height: 1.2;">{{ $totalReaders }}</h3>
        <div style="margin-top: 12px; font-size: 14px; opacity: 0.9;">
            <i class="fas fa-check-circle"></i> {{ $activeReaders }} hoạt động
            <span style="margin-left: 12px;"><i class="fas fa-exclamation-triangle"></i> {{ $suspendedReaders + $expiredReaders }} cần xử lý</span>
        </div>
        <div style="position: absolute; bottom: 24px; right: 24px; opacity: 0.3;">
            <i class="fas fa-book-reader" style="font-size: 64px;"></i>
        </div>
    </div>

</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 25px; background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
    <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 20px;">
        <h3 class="card-title" style="font-size: 18px; font-weight: 600; color: #1f2937; margin: 0;">
            <i class="fas fa-search" style="color: #22c55e; margin-right: 8px;"></i>
            Tìm kiếm & Lọc
        </h3>
    </div>
    <form method="GET" action="{{ route('admin.user-management.dashboard') }}" style="padding: 0 25px 25px 25px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Tìm kiếm người dùng</label>
                <input type="text" name="user_search" class="form-control" value="{{ request('user_search') }}" placeholder="Tên, email..." style="width: 100%;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Vai trò</label>
                <select name="user_role" class="form-control" style="width: 100%;">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="admin" {{ request('user_role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    <option value="user" {{ request('user_role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Tìm kiếm độc giả</label>
                <input type="text" name="reader_search" class="form-control" value="{{ request('reader_search') }}" placeholder="Tên, email, số thẻ..." style="width: 100%;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500; color: #374151;">Trạng thái độc giả</label>
                <select name="reader_status" class="form-control" style="width: 100%;">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="Hoat dong" {{ request('reader_status') == 'Hoat dong' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="Tam khoa" {{ request('reader_status') == 'Tam khoa' ? 'selected' : '' }}>Tạm khóa</option>
                    <option value="Het han" {{ request('reader_status') == 'Het han' ? 'selected' : '' }}>Hết hạn</option>
                </select>
            </div>
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-start;">
            <button type="submit" class="btn btn-primary" style="background: #22c55e; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-filter"></i>
                Lọc
            </button>
            <a href="{{ route('admin.user-management.dashboard') }}" class="btn btn-secondary" style="background: white; color: #1f2937; border: 1px solid #e5e7eb; padding: 10px 20px; border-radius: 10px; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-redo"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Tabs để hiển thị danh sách -->
<div class="card" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
    <div class="card-header" style="border-bottom: 1px solid #e5e7eb; padding: 0;">
        <ul class="nav nav-tabs" role="tablist" style="border-bottom: none; margin: 0; display: flex; list-style: none; padding: 0;">
            <li class="nav-item" style="margin: 0;">
                <a class="nav-link active" href="#users" style="color: #1f2937; padding: 15px 20px; border: none; border-bottom: 3px solid #22c55e; display: block; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-users"></i> Người Dùng ({{ $totalUsers }})
                </a>
            </li>
            <li class="nav-item" style="margin: 0;">
                <a class="nav-link" href="#readers" style="color: #1f2937; padding: 15px 20px; border: none; display: block; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                    <i class="fas fa-book-reader"></i> Độc Giả ({{ $totalReaders }})
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="tab-content">
            <!-- Tab Người Dùng -->
            <div id="users" class="tab-pane show active" style="display: block;">
                <div class="table-responsive" style="padding: 20px;">
                    <table class="table table-striped" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td><strong>#{{ $user->id }}</strong></td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge badge-danger">Quản trị viên</span>
                                        @else
                                            <span class="badge badge-info">Người dùng</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" style="background: #ffc107; color: #000; border: none; padding: 5px 10px;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-info" style="background: #17a2b8; color: white; border: none; padding: 5px 10px;">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có người dùng nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding: 20px;">
                    {{ $users->appends(request()->except('users_page'))->links() }}
                </div>
            </div>

            <!-- Tab Độc Giả -->
            <div id="readers" class="tab-pane" style="display: none;">
                <div class="table-responsive" style="padding: 20px;">
                    <table class="table table-striped" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>Số thẻ</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($readers as $reader)
                                <tr>
                                    <td><strong>{{ $reader->so_the_doc_gia }}</strong></td>
                                    <td>{{ $reader->ho_ten }}</td>
                                    <td>{{ $reader->email }}</td>
                                    <td>{{ $reader->so_dien_thoai }}</td>
                                    <td>
                                        @if($reader->trang_thai == 'Hoat dong')
                                            <span class="badge badge-success">Hoạt động</span>
                                        @elseif($reader->trang_thai == 'Tam khoa')
                                            <span class="badge badge-warning">Tạm khóa</span>
                                        @else
                                            <span class="badge badge-danger">Hết hạn</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.readers.show', $reader->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có độc giả nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding: 20px;">
                    {{ $readers->appends(request()->except('readers_page'))->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Tab switching with vanilla JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.nav-tabs a');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            
            // Remove active class from all tabs and panes
            tabLinks.forEach(function(tab) {
                tab.classList.remove('active');
                tab.style.borderBottom = 'none';
            });
            
            tabPanes.forEach(function(pane) {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            this.style.borderBottom = '3px solid #22c55e';
            
            // Show corresponding pane
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
                targetPane.style.display = 'block';
            }
        });
    });
});
</script>
@endsection

