@extends('layouts.admin')

@section('title', 'Logs & Audit Trail - Admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="page-title">
                <i class="fas fa-file-alt me-3"></i>
                Logs & Audit Trail
            </h1>
            <p class="page-subtitle">Theo dõi và quản lý nhật ký hoạt động hệ thống</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <button class="btn btn-success" onclick="exportLogs()">
                    <i class="fas fa-download me-2"></i>
                    Xuất Logs
                </button>
                <button class="btn btn-warning" onclick="clearOldLogs()">
                    <i class="fas fa-trash me-2"></i>
                    Xóa Logs Cũ
                </button>
                <button class="btn btn-info" onclick="refreshLogs()">
                    <i class="fas fa-sync-alt me-2"></i>
                    Làm Mới
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card blue">
            <div class="stats-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stats-content">
                <h3>Tổng Logs</h3>
                <div class="number">{{ $totalLogs ?? 0 }} <span class="unit">Bản ghi</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card green">
            <div class="stats-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stats-content">
                <h3>Đăng Nhập</h3>
                <div class="number">{{ $loginLogs ?? 0 }} <span class="unit">Lần</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card orange">
            <div class="stats-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stats-content">
                <h3>Lỗi Hệ Thống</h3>
                <div class="number">{{ $errorLogs ?? 0 }} <span class="unit">Lỗi</span></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card purple">
            <div class="stats-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stats-content">
                <h3>Bảo Mật</h3>
                <div class="number">{{ $securityLogs ?? 0 }} <span class="unit">Sự kiện</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-table">
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Tìm kiếm</label>
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm theo nội dung...">
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Loại Log</label>
            <select class="form-select" name="type">
                <option value="">Tất cả</option>
                <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Đăng nhập</option>
                <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>Đăng xuất</option>
                <option value="create" {{ request('type') == 'create' ? 'selected' : '' }}>Tạo mới</option>
                <option value="update" {{ request('type') == 'update' ? 'selected' : '' }}>Cập nhật</option>
                <option value="delete" {{ request('type') == 'delete' ? 'selected' : '' }}>Xóa</option>
                <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Lỗi</option>
                <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>Bảo mật</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Người dùng</label>
            <select class="form-select" name="user_id">
                <option value="">Tất cả</option>
                @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Từ ngày</label>
            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Đến ngày</label>
            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
        </div>
        
        <div class="col-md-1">
            <label class="form-label">&nbsp;</label>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>
    
    <!-- Logs Table -->
    <div class="table-responsive">
        <table class="table table-hover logs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Thời gian</th>
                    <th>Người dùng</th>
                    <th>Loại</th>
                    <th>Hành động</th>
                    <th>Mô tả</th>
                    <th>IP Address</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs ?? [] as $log)
                <tr class="log-row log-{{ $log->type ?? 'info' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="log-time">
                            <div class="time">{{ $log->created_at->format('d/m/Y') }}</div>
                            <div class="time-small">{{ $log->created_at->format('H:i:s') }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ $log->user->name ?? 'System' }}</div>
                                <div class="user-role">{{ $log->user->role ?? 'system' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="log-type-badge log-type-{{ $log->type ?? 'info' }}">
                            @switch($log->type ?? 'info')
                                @case('login')
                                    <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                                    @break
                                @case('logout')
                                    <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                                    @break
                                @case('create')
                                    <i class="fas fa-plus me-1"></i>Tạo mới
                                    @break
                                @case('update')
                                    <i class="fas fa-edit me-1"></i>Cập nhật
                                    @break
                                @case('delete')
                                    <i class="fas fa-trash me-1"></i>Xóa
                                    @break
                                @case('error')
                                    <i class="fas fa-exclamation-triangle me-1"></i>Lỗi
                                    @break
                                @case('security')
                                    <i class="fas fa-shield-alt me-1"></i>Bảo mật
                                    @break
                                @default
                                    <i class="fas fa-info-circle me-1"></i>Thông tin
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <span class="action-text">{{ $log->action ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div class="log-description">
                            {{ Str::limit($log->description ?? 'Không có mô tả', 50) }}
                        </div>
                    </td>
                    <td>
                        <span class="ip-address">{{ $log->ip_address ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-info btn-sm" onclick="viewLogDetail({{ $log->id ?? 0 }})" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="exportLog({{ $log->id ?? 0 }})" title="Xuất log">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5>Chưa có logs nào</h5>
                            <p class="text-muted">Chưa có hoạt động nào được ghi lại</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(isset($logs) && $logs->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Hiển thị {{ $logs->firstItem() }} - {{ $logs->lastItem() }} trong tổng số {{ $logs->total() }} kết quả
        </div>
        <div>
            {{ $logs->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Log Detail Modal -->
<div class="modal fade" id="logDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i>
                    Chi Tiết Log
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="exportCurrentLog()">
                    <i class="fas fa-download me-2"></i>
                    Xuất Log
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Log Monitor -->
<div class="real-time-monitor">
    <div class="monitor-header">
        <h5>
            <i class="fas fa-broadcast-tower me-2"></i>
            Monitor Real-time
            <span class="status-indicator" id="monitorStatus"></span>
        </h5>
        <button class="btn btn-sm btn-success" onclick="toggleRealTimeMonitor()" id="monitorToggle">
            <i class="fas fa-play me-1"></i>
            Bắt đầu
        </button>
    </div>
    <div class="monitor-content" id="realTimeLogs">
        <div class="log-stream">
            <div class="log-item">
                <span class="log-time">14:30:25</span>
                <span class="log-type">INFO</span>
                <span class="log-message">User admin@library.com logged in successfully</span>
            </div>
            <div class="log-item">
                <span class="log-time">14:30:20</span>
                <span class="log-type">CREATE</span>
                <span class="log-message">New book "Laravel Advanced" was created</span>
            </div>
            <div class="log-item">
                <span class="log-time">14:30:15</span>
                <span class="log-type">UPDATE</span>
                <span class="log-message">Book "PHP Basics" was updated</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .logs-table .log-row.log-error {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .logs-table .log-row.log-security {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .logs-table .log-row.log-login {
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .log-time {
        font-size: 14px;
    }
    
    .log-time .time {
        font-weight: 600;
        color: #343a40;
    }
    
    .log-time .time-small {
        font-size: 12px;
        color: #6c757d;
    }
    
    .log-type-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .log-type-login {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .log-type-logout {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .log-type-create {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
    }
    
    .log-type-update {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
    }
    
    .log-type-delete {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .log-type-error {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    .log-type-security {
        background: linear-gradient(135deg, #fd7e14, #e55a00);
        color: white;
    }
    
    .log-type-info {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
        color: white;
    }
    
    .action-text {
        font-weight: 500;
        color: #495057;
    }
    
    .log-description {
        font-size: 13px;
        color: #6c757d;
        max-width: 200px;
    }
    
    .ip-address {
        font-family: monospace;
        font-size: 12px;
        color: #6c757d;
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .real-time-monitor {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-top: 30px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .monitor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }
    
    .monitor-header h5 {
        margin: 0;
        color: #343a40;
        font-weight: bold;
    }
    
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #dc3545;
        display: inline-block;
        margin-left: 10px;
        animation: pulse 2s infinite;
    }
    
    .status-indicator.active {
        background-color: #28a745;
    }
    
    .monitor-content {
        max-height: 300px;
        overflow-y: auto;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }
    
    .log-stream {
        font-family: monospace;
        font-size: 13px;
    }
    
    .log-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .log-item:last-child {
        border-bottom: none;
    }
    
    .log-item .log-time {
        color: #6c757d;
        font-weight: 600;
        min-width: 70px;
    }
    
    .log-item .log-type {
        font-weight: bold;
        min-width: 60px;
        text-align: center;
    }
    
    .log-item .log-type.INFO {
        color: #007bff;
    }
    
    .log-item .log-type.CREATE {
        color: #28a745;
    }
    
    .log-item .log-type.UPDATE {
        color: #ffc107;
    }
    
    .log-item .log-type.DELETE {
        color: #dc3545;
    }
    
    .log-item .log-message {
        color: #343a40;
        flex: 1;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>

<script>
    let realTimeMonitorActive = false;
    let monitorInterval;
    
    function viewLogDetail(logId) {
        // Fetch log detail via AJAX
        fetch(`/admin/logs/${logId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('logDetailContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>ID:</strong> ${data.id}<br>
                            <strong>Thời gian:</strong> ${data.created_at}<br>
                            <strong>Người dùng:</strong> ${data.user_name || 'System'}<br>
                            <strong>Loại:</strong> ${data.type}<br>
                        </div>
                        <div class="col-md-6">
                            <strong>Hành động:</strong> ${data.action}<br>
                            <strong>IP Address:</strong> ${data.ip_address}<br>
                            <strong>User Agent:</strong> ${data.user_agent || 'N/A'}<br>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <strong>Mô tả chi tiết:</strong><br>
                        <div class="mt-2 p-3 bg-light rounded">
                            ${data.description || 'Không có mô tả chi tiết'}
                        </div>
                    </div>
                    ${data.old_values ? `
                    <div class="mt-3">
                        <strong>Dữ liệu cũ:</strong><br>
                        <pre class="mt-2 p-3 bg-light rounded">${JSON.stringify(data.old_values, null, 2)}</pre>
                    </div>
                    ` : ''}
                    ${data.new_values ? `
                    <div class="mt-3">
                        <strong>Dữ liệu mới:</strong><br>
                        <pre class="mt-2 p-3 bg-light rounded">${JSON.stringify(data.new_values, null, 2)}</pre>
                    </div>
                    ` : ''}
                `;
                
                const modal = new bootstrap.Modal(document.getElementById('logDetailModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tải chi tiết log');
            });
    }
    
    function exportLog(logId) {
        window.open(`/admin/logs/${logId}/export`, '_blank');
    }
    
    function exportCurrentLog() {
        const logId = document.getElementById('logDetailContent').dataset.logId;
        if (logId) {
            exportLog(logId);
        }
    }
    
    function exportLogs() {
        const params = new URLSearchParams(window.location.search);
        window.open(`/admin/logs/export?${params.toString()}`, '_blank');
    }
    
    function clearOldLogs() {
        if (confirm('Bạn có chắc chắn muốn xóa các logs cũ hơn 30 ngày?')) {
            fetch('/admin/logs/clear-old', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Đã xóa ${data.deleted_count} logs cũ`);
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa logs');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xóa logs');
            });
        }
    }
    
    function refreshLogs() {
        location.reload();
    }
    
    function toggleRealTimeMonitor() {
        const toggleBtn = document.getElementById('monitorToggle');
        const statusIndicator = document.getElementById('monitorStatus');
        
        if (!realTimeMonitorActive) {
            // Start monitoring
            realTimeMonitorActive = true;
            toggleBtn.innerHTML = '<i class="fas fa-stop me-1"></i>Dừng';
            toggleBtn.className = 'btn btn-sm btn-danger';
            statusIndicator.classList.add('active');
            
            // Start polling for new logs
            monitorInterval = setInterval(() => {
                fetchNewLogs();
            }, 5000);
            
        } else {
            // Stop monitoring
            realTimeMonitorActive = false;
            toggleBtn.innerHTML = '<i class="fas fa-play me-1"></i>Bắt đầu';
            toggleBtn.className = 'btn btn-sm btn-success';
            statusIndicator.classList.remove('active');
            
            clearInterval(monitorInterval);
        }
    }
    
    function fetchNewLogs() {
        fetch('/admin/logs/realtime')
            .then(response => response.json())
            .then(data => {
                if (data.logs && data.logs.length > 0) {
                    updateRealTimeLogs(data.logs);
                }
            })
            .catch(error => {
                console.error('Error fetching real-time logs:', error);
            });
    }
    
    function updateRealTimeLogs(logs) {
        const logStream = document.querySelector('.log-stream');
        
        logs.forEach(log => {
            const logItem = document.createElement('div');
            logItem.className = 'log-item';
            logItem.innerHTML = `
                <span class="log-time">${new Date(log.created_at).toLocaleTimeString()}</span>
                <span class="log-type">${log.type.toUpperCase()}</span>
                <span class="log-message">${log.description}</span>
            `;
            
            logStream.insertBefore(logItem, logStream.firstChild);
            
            // Keep only last 50 items
            while (logStream.children.length > 50) {
                logStream.removeChild(logStream.lastChild);
            }
        });
    }
    
    // Auto-refresh every 30 seconds
    setInterval(() => {
        if (!realTimeMonitorActive) {
            fetchNewLogs();
        }
    }, 30000);
    
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
