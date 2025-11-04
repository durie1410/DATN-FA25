@extends('layouts.admin')

@section('title', 'Chi tiết phạt #' . $fine->id)

@section('content')
<link href="{{ asset('css/fines-management.css') }}" rel="stylesheet">

<div class="container-fluid fines-management">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="fines-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1><i class="fas fa-exclamation-triangle"></i> Chi tiết phạt #{{ $fine->id }}</h1>
                            <p class="subtitle">Thông tin chi tiết về khoản phạt</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.fines.edit', $fine->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Thông tin cơ bản -->
                    <div class="col-md-8">
                        <div class="fines-filter">
                            <h3><i class="fas fa-info-circle"></i> Thông tin phạt</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID Phạt:</strong></td>
                                            <td>#{{ $fine->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loại phạt:</strong></td>
                                            <td>
                                                @switch($fine->type)
                                                    @case('late_return')
                                                        <span class="fines-badge late-return">Trả muộn</span>
                                                        @break
                                                    @case('damaged_book')
                                                        <span class="fines-badge damaged-book">Làm hỏng sách</span>
                                                        @break
                                                    @case('lost_book')
                                                        <span class="fines-badge lost-book">Mất sách</span>
                                                        @break
                                                    @default
                                                        <span class="fines-badge other">Khác</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số tiền:</strong></td>
                                            <td>
                                                <span class="text-danger font-weight-bold h4">
                                                    {{ number_format($fine->amount, 0, ',', '.') }} VND
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Trạng thái:</strong></td>
                                            <td>
                                                @switch($fine->status)
                                                    @case('pending')
                                                        <span class="fines-badge pending">Chưa thanh toán</span>
                                                        @if($fine->isOverdue())
                                                            <br><small class="text-danger">Quá hạn {{ $fine->days_overdue }} ngày</small>
                                                        @endif
                                                        @break
                                                    @case('paid')
                                                        <span class="fines-badge paid">Đã thanh toán</span>
                                                        @if($fine->paid_date)
                                                            <br><small class="text-muted">{{ $fine->paid_date->format('d/m/Y') }}</small>
                                                        @endif
                                                        @break
                                                    @case('waived')
                                                        <span class="fines-badge waived">Đã miễn</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="fines-badge cancelled">Đã hủy</span>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Hạn thanh toán:</strong></td>
                                            <td>
                                                {{ $fine->due_date->format('d/m/Y') }}
                                                @if($fine->isOverdue())
                                                    <br><small class="text-danger">Quá hạn</small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày tạo:</strong></td>
                                            <td>{{ $fine->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Người tạo:</strong></td>
                                            <td>
                                                @if($fine->creator)
                                                    {{ $fine->creator->name }}
                                                @else
                                                    <span class="text-muted">Hệ thống</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cập nhật cuối:</strong></td>
                                            <td>{{ $fine->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($fine->description)
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <strong>Mô tả lý do phạt:</strong>
                                        <div class="fines-alert alert-info mt-2">
                                            {{ $fine->description }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($fine->notes)
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <strong>Ghi chú:</strong>
                                        <div class="fines-alert alert-info mt-2">
                                            {{ $fine->notes }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Thông tin độc giả và sách -->
                    <div class="col-md-4">
                        <div class="fines-filter">
                            <h3><i class="fas fa-user"></i> Thông tin độc giả</h3>
                            <div class="text-center mb-3">
                                <i class="fas fa-user fa-3x text-primary"></i>
                            </div>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Họ tên:</strong></td>
                                    <td>{{ $fine->reader->ho_ten }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mã số thẻ:</strong></td>
                                    <td>{{ $fine->reader->ma_so_the }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $fine->reader->email ?? 'Chưa có' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Số điện thoại:</strong></td>
                                    <td>{{ $fine->reader->so_dien_thoai ?? 'Chưa có' }}</td>
                                </tr>
                            </table>
                            <div class="text-center">
                                <a href="{{ route('admin.readers.show', $fine->reader->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Xem chi tiết độc giả
                                </a>
                            </div>
                        </div>

                        @if($fine->borrow && $fine->borrow->book)
                            <div class="fines-filter mt-3">
                                <h3><i class="fas fa-book"></i> Thông tin sách</h3>
                                <div class="text-center mb-3">
                                    <i class="fas fa-book fa-3x text-success"></i>
                                </div>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Tên sách:</strong></td>
                                        <td>{{ $fine->borrow->book->ten_sach }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mã sách:</strong></td>
                                        <td>{{ $fine->borrow->book->ma_sach }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tác giả:</strong></td>
                                        <td>{{ $fine->borrow->book->tac_gia ?? 'Chưa có' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nhà xuất bản:</strong></td>
                                        <td>{{ $fine->borrow->book->nha_xuat_ban ?? 'Chưa có' }}</td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <a href="{{ route('admin.books.show', $fine->borrow->book->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-eye"></i> Xem chi tiết sách
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($fine->isOverdue())
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="fines-alert alert-danger">
                                <h4><i class="fas fa-exclamation-triangle"></i> Cảnh báo quá hạn</h4>
                                <p>Phạt này đã quá hạn <strong>{{ $fine->days_overdue }} ngày</strong>. 
                                Vui lòng liên hệ độc giả để thu phạt hoặc xem xét miễn phạt.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.fines.edit', $fine->id) }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                        <div class="col-md-6">
                            @if($fine->status === 'pending')
                                <form method="POST" action="{{ route('admin.fines.mark-paid', $fine->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Xác nhận đánh dấu đã thanh toán?')">
                                        <i class="fas fa-check"></i> Đánh dấu đã thanh toán
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.fines.waive', $fine->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-lg" onclick="return confirm('Xác nhận miễn phạt?')">
                                        <i class="fas fa-gift"></i> Miễn phạt
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
