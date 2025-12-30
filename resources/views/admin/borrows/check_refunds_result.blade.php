@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kết quả kiểm tra và sửa hoàn tiền cọc</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng đơn hàng</span>
                                    <span class="info-box-number">{{ $results['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đã sửa</span>
                                    <span class="info-box-number">{{ $results['fixed'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đã bỏ qua</span>
                                    <span class="info-box-number">{{ $results['skipped'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Lỗi</span>
                                    <span class="info-box-number">{{ $results['errors'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($results['details']) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Đơn hàng</th>
                                    <th>Trạng thái</th>
                                    <th>Thông báo</th>
                                    <th>Số tiền</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['details'] as $detail)
                                <tr>
                                    <td>#{{ $detail['borrow_id'] }}</td>
                                    <td>
                                        @if($detail['status'] == 'fixed')
                                            <span class="badge badge-success">Đã sửa</span>
                                        @elseif($detail['status'] == 'already_refunded')
                                            <span class="badge badge-info">Đã hoàn tiền</span>
                                        @elseif($detail['status'] == 'error')
                                            <span class="badge badge-danger">Lỗi</span>
                                        @else
                                            <span class="badge badge-warning">Bỏ qua</span>
                                        @endif
                                    </td>
                                    <td>{{ $detail['message'] }}</td>
                                    <td>
                                        @if($detail['amount'] > 0)
                                            {{ number_format($detail['amount']) }} VNĐ
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($detail['balance_before']))
                                            <small>
                                                Số dư trước: {{ number_format($detail['balance_before']) }} VNĐ<br>
                                                Số dư sau: {{ number_format($detail['balance_after']) }} VNĐ<br>
                                                User ID: {{ $detail['user_id'] }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Không có đơn hàng nào để xử lý.
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.borrows.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('admin.borrows.check-and-fix-refunds.get') }}" class="btn btn-success">
                            <i class="fas fa-sync"></i> Chạy lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


