@extends('layouts.admin')

@section('title', 'Quản Lý Mã Giảm Giá - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý mã giảm giá</h4>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Thanh công cụ -->
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Thêm mã giảm giá mới
            </a>
        </div>
    </div>

    <!-- Bảng danh sách -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mã giảm giá</th>
                            <th>Độc giả</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Số lượng</th>
                            <th>Đơn tối thiểu</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th width="200">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $voucher)
                        <tr class="{{ $voucher->trashed() ? 'table-secondary' : '' }}">
                            <td><span class="badge bg-secondary fw-bold">{{ $voucher->id }}</span></td>
                            <td>
                                <strong>{{ $voucher->ma }}</strong>
                                @if($voucher->trashed())
                                    <span class="badge bg-danger ms-1">Đã xóa</span>
                                @endif
                            </td>
                            <td>
                                @if($voucher->reader)
                                    {{ $voucher->reader->ho_ten }}
                                @else
                                    <span class="text-muted">Tất cả</span>
                                @endif
                            </td>
                            <td>
                                @if($voucher->loai == 'percentage')
                                    <span class="badge bg-info">Giảm %</span>
                                @else
                                    <span class="badge bg-primary">Giảm tiền</span>
                                @endif
                            </td>
                            <td>
                                @if($voucher->loai == 'percentage')
                                    {{ number_format($voucher->gia_tri) }}%
                                @else
                                    {{ number_format($voucher->gia_tri) }} đ
                                @endif
                            </td>
                            <td><span class="badge bg-primary">{{ $voucher->so_luong }}</span></td>
                            <td>{{ number_format($voucher->don_toi_thieu) }} đ</td>
                            <td>{{ $voucher->ngay_bat_dau ? \Carbon\Carbon::parse($voucher->ngay_bat_dau)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $voucher->ngay_ket_thuc ? \Carbon\Carbon::parse($voucher->ngay_ket_thuc)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($voucher->kich_hoat && $voucher->trang_thai == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($voucher->trang_thai == 'expired')
                                    <span class="badge bg-danger">Hết hạn</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($voucher->trashed())
                                        <a href="{{ route('admin.vouchers.restore', $voucher->id) }}" class="btn btn-success btn-sm" title="Khôi phục" onclick="return confirm('Khôi phục mã giảm giá này?')">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.vouchers.forceDelete', $voucher->id) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn" onclick="return confirm('Xóa vĩnh viễn mã giảm giá này? Hành động này không thể hoàn tác!')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher->id) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Xóa mã giảm giá này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                                    <p>Không có mã giảm giá nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            {{ $vouchers->appends(request()->query())->links('vendor.pagination.admin') }}
        </div>
    </div>
</div>
@endsection






