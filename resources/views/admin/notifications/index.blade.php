@extends('layouts.admin')

@section('title', 'Quản Lý Thông Báo - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-bell"></i> Quản lý thông báo</h3>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendRemindersModal">
                <i class="fas fa-paper-plane"></i> Gửi nhắc nhở
            </button>
        </div>
    </div>

    {{-- Thống kê nhanh --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ $dueSoonCount }}</h4>
                    <p class="mb-0">Sách sắp đến hạn (3 ngày)</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ $overdueCount }}</h4>
                    <p class="mb-0">Sách quá hạn</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ $templates->count() }}</h4>
                    <p class="mb-0">Template thông báo</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách template --}}
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-file-alt"></i> Template thông báo</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th>Kênh</th>
                            <th>Tiêu đề</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $template->type }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $template->channel }}</span>
                            </td>
                            <td>{{ $template->subject }}</td>
                            <td>
                                @if($template->is_active)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Tạm dừng</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#templateModal{{ $template->id }}">
                                    <i class="fas fa-eye"></i> Xem
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal gửi nhắc nhở --}}
<div class="modal fade" id="sendRemindersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gửi nhắc nhở tự động</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.notifications.send-reminders') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Loại nhắc nhở:</label>
                        <select name="type" class="form-control" required>
                            <option value="all">Tất cả</option>
                            <option value="due_soon">Sắp đến hạn (3 ngày)</option>
                            <option value="overdue">Quá hạn</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Hệ thống sẽ tự động gửi email nhắc nhở cho các độc giả có sách sắp đến hạn hoặc quá hạn.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Gửi nhắc nhở</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal xem template --}}
@foreach($templates as $template)
<div class="modal fade" id="templateModal{{ $template->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $template->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Loại:</label>
                            <p class="form-control-plaintext">{{ $template->type }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kênh:</label>
                            <p class="form-control-plaintext">{{ $template->channel }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái:</label>
                            <p class="form-control-plaintext">
                                @if($template->is_active)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-danger">Tạm dừng</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Biến có thể sử dụng:</label>
                            <div class="bg-light p-2 rounded">
                                @if($template->variables && is_array($template->variables))
                                    @foreach($template->variables as $variable)
                                        <code>{!! '{{' . $variable . '}}' !!}</code>
                                    @endforeach
                                @else
                                    <small class="text-muted">Không có biến nào</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề:</label>
                    <div class="bg-light p-2 rounded">{{ $template->subject }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung:</label>
                    <div class="bg-light p-2 rounded" style="white-space: pre-line;">{{ $template->content }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

