@extends('layouts.staff')

@section('title', 'Thông Báo - Nhân viên')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">
            <i class="fas fa-bell text-primary me-2"></i>
            Quản lý thông báo
        </h3>
        <p class="text-muted mb-0">Gửi thông báo đến độc giả</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendReminderModal">
            <i class="fas fa-paper-plane me-1"></i>Gửi nhắc nhở
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Danh sách thông báo -->
<div class="card">
    <div class="card-body">
        <div class="list-group">
            @forelse($notifications ?? [] as $notification)
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">{{ $notification->title ?? 'Thông báo' }}</h6>
                        <p class="mb-1">{{ $notification->message ?? 'Nội dung thông báo' }}</p>
                        <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') ?? 'N/A' }}</small>
                    </div>
                    <span class="badge bg-primary">Đã gửi</span>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3 d-block"></i>
                <p class="text-muted">Chưa có thông báo nào</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal gửi nhắc nhở -->
<div class="modal fade" id="sendReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gửi nhắc nhở</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('staff.notifications.send-reminders') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Loại nhắc nhở</label>
                        <select name="type" class="form-select">
                            <option value="overdue">Sách quá hạn</option>
                            <option value="due_soon">Sách sắp đến hạn</option>
                            <option value="reservation_ready">Sách đặt trước đã sẵn sàng</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Gửi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

