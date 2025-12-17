@extends('layouts.admin')

@section('title', 'Chi tiết chiến dịch Email Marketing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn"></i> {{ $campaign->name }}
                    </h3>
                    <div class="btn-group">
                        @if($campaign->status === 'draft')
                            <a href="{{ route('admin.email-marketing.edit', $campaign->id) }}" 
                               class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                        @endif
                        
                        @if($campaign->canBeSent())
                            <form action="{{ route('admin.email-marketing.send', $campaign->id) }}" 
                                  method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Bạn có chắc muốn gửi chiến dịch này?')">
                                    <i class="fas fa-paper-plane"></i> Gửi ngay
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($campaign->status, ['draft', 'scheduled']))
                            <form action="{{ route('admin.email-marketing.cancel', $campaign->id) }}" 
                                  method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Bạn có chắc muốn hủy chiến dịch này?')">
                                    <i class="fas fa-times"></i> Hủy
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Thông tin chiến dịch -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Trạng thái</span>
                                            <span class="info-box-number">
                                                <span class="badge badge-{{ $campaign->status_badge }}">
                                                    @switch($campaign->status)
                                                        @case('draft') Bản nháp @break
                                                        @case('scheduled') Đã lên lịch @break
                                                        @case('sending') Đang gửi @break
                                                        @case('sent') Đã gửi @break
                                                        @case('cancelled') Đã hủy @break
                                                    @endswitch
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Người nhận</span>
                                            <span class="info-box-number">{{ $campaign->total_recipients }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Nội dung email</h5>
                                </div>
                                <div class="card-body">
                                    <h6><strong>Tiêu đề:</strong> {{ $campaign->subject }}</h6>
                                    <hr>
                                    <div class="email-preview">
                                        {!! nl2br(e($campaign->content)) !!}
                                    </div>
                                </div>
                            </div>

                            @if($campaign->target_criteria)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Tiêu chí đối tượng</h5>
                                </div>
                                <div class="card-body">
                                    <pre class="bg-light p-3">{{ json_encode($campaign->target_criteria, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Thống kê -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Thống kê chiến dịch</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-right">
                                                <h4 class="text-success">{{ $stats['total_sent'] }}</h4>
                                                <small class="text-muted">Đã gửi</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-info">{{ $stats['delivered'] }}</h4>
                                            <small class="text-muted">Đã giao</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-right">
                                                <h4 class="text-warning">{{ $stats['opened'] }}</h4>
                                                <small class="text-muted">Đã mở</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-primary">{{ $stats['clicked'] }}</h4>
                                            <small class="text-muted">Đã click</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-right">
                                                <h4 class="text-success">{{ $stats['delivery_rate'] }}%</h4>
                                                <small class="text-muted">Tỷ lệ giao</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-warning">{{ $stats['open_rate'] }}%</h4>
                                            <small class="text-muted">Tỷ lệ mở</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h4 class="text-primary">{{ $stats['click_rate'] }}%</h4>
                                        <small class="text-muted">Tỷ lệ click</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Thông tin chiến dịch</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Template:</strong></td>
                                            <td>{{ ucfirst($campaign->template) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tạo bởi:</strong></td>
                                            <td>{{ $campaign->creator->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày tạo:</strong></td>
                                            <td>{{ $campaign->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @if($campaign->scheduled_at)
                                        <tr>
                                            <td><strong>Lên lịch:</strong></td>
                                            <td>{{ $campaign->scheduled_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @endif
                                        @if($campaign->sent_at)
                                        <tr>
                                            <td><strong>Đã gửi:</strong></td>
                                            <td>{{ $campaign->sent_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách người nhận -->
                    @if($recipients->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-users"></i> Danh sách người nhận ({{ $recipients->count() }})
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Tên</th>
                                                    <th>Tags</th>
                                                    <th>Nguồn</th>
                                                    <th>Ngày đăng ký</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recipients as $recipient)
                                                <tr>
                                                    <td>{{ $recipient->email }}</td>
                                                    <td>{{ $recipient->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($recipient->tags)
                                                            @foreach($recipient->tags as $tag)
                                                                <span class="badge badge-secondary">{{ $tag }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $recipient->source ?? 'N/A' }}</td>
                                                    <td>{{ $recipient->subscribed_at->format('d/m/Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.email-marketing.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-box {
    display: block;
    min-height: 90px;
    background: #fff;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 2px;
    margin-bottom: 15px;
}

.info-box-icon {
    border-top-left-radius: 2px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 2px;
    display: block;
    float: left;
    height: 90px;
    width: 90px;
    text-align: center;
    font-size: 45px;
    line-height: 90px;
    background: rgba(0,0,0,0.2);
}

.info-box-content {
    padding: 5px 10px;
    margin-left: 90px;
}

.info-box-text {
    text-transform: uppercase;
    font-weight: bold;
    font-size: 14px;
}

.info-box-number {
    display: block;
    font-weight: bold;
    font-size: 18px;
}

.email-preview {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.border-right {
    border-right: 1px solid #dee2e6;
}
</style>
@endpush



















