@extends('layouts.admin')

@section('title', 'Email Marketing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-envelope"></i> Email Marketing
                    </h3>
                    <a href="{{ route('admin.email-marketing.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo chiến dịch mới
                    </a>
                </div>
                
                <!-- Thống kê tổng quan -->
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-bullhorn"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng chiến dịch</span>
                                    <span class="info-box-number">{{ $stats['total_campaigns'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Subscribers</span>
                                    <span class="info-box-number">{{ $stats['active_subscribers'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-paper-plane"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Email đã gửi</span>
                                    <span class="info-box-number">{{ number_format($stats['total_emails_sent']) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tỷ lệ mở</span>
                                    <span class="info-box-number">{{ $stats['open_rate'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách chiến dịch -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tên chiến dịch</th>
                                    <th>Trạng thái</th>
                                    <th>Người nhận</th>
                                    <th>Tỷ lệ mở</th>
                                    <th>Tỷ lệ click</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $campaign)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $campaign->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $campaign->subject }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $campaign->status_badge }}">
                                            @switch($campaign->status)
                                                @case('draft') Bản nháp @break
                                                @case('scheduled') Đã lên lịch @break
                                                @case('sending') Đang gửi @break
                                                @case('sent') Đã gửi @break
                                                @case('cancelled') Đã hủy @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $campaign->total_recipients }}</span>
                                        @if($campaign->sent_count > 0)
                                            <br><small class="text-muted">Đã gửi: {{ $campaign->sent_count }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaign->sent_count > 0)
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-success" style="width: {{ $campaign->open_rate }}%"></div>
                                            </div>
                                            <small>{{ $campaign->open_rate }}% ({{ $campaign->opened_count }})</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaign->sent_count > 0)
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-primary" style="width: {{ $campaign->click_rate }}%"></div>
                                            </div>
                                            <small>{{ $campaign->click_rate }}% ({{ $campaign->clicked_count }})</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $campaign->created_at->format('d/m/Y H:i') }}
                                        <br>
                                        <small class="text-muted">bởi {{ $campaign->creator->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.email-marketing.show', $campaign->id) }}" 
                                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($campaign->status === 'draft')
                                                <a href="{{ route('admin.email-marketing.edit', $campaign->id) }}" 
                                                   class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            @if($campaign->canBeSent())
                                                <form action="{{ route('admin.email-marketing.send', $campaign->id) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            title="Gửi ngay" 
                                                            onclick="return confirm('Bạn có chắc muốn gửi chiến dịch này?')">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if(in_array($campaign->status, ['draft', 'scheduled']))
                                                <form action="{{ route('admin.email-marketing.cancel', $campaign->id) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="Hủy" 
                                                            onclick="return confirm('Bạn có chắc muốn hủy chiến dịch này?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <br>Chưa có chiến dịch nào
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center">
                        {{ $campaigns->links() }}
                    </div>
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

.progress-sm {
    height: 0.5rem;
}
</style>
@endpush























