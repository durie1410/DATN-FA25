@extends('layouts.admin')

@section('title', 'Chi tiết tác giả - Quản Lý Thư Viện')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Chi tiết tác giả: {{ $reader->ho_ten }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-primary text-white font-size-24">
                                {{ substr($reader->ho_ten, 0, 1) }}
                            </div>
                        </div>
                        <h5>{{ $reader->ho_ten }}</h5>
                        <p class="text-muted">{{ $reader->so_the_doc_gia }}</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $reader->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Số điện thoại:</strong></td>
                                <td>{{ $reader->so_dien_thoai }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày sinh:</strong></td>
                                <td>{{ $reader->ngay_sinh ? $reader->ngay_sinh->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Giới tính:</strong></td>
                                <td>{{ $reader->gioi_tinh }}</td>
                            </tr>
                            <tr>
                                <td><strong>Địa chỉ:</strong></td>
                                <td>{{ $reader->dia_chi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày cấp thẻ:</strong></td>
                                <td>{{ $reader->ngay_cap_the ? $reader->ngay_cap_the->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Ngày hết hạn:</strong></td>
                                <td>
                                    {{ $reader->ngay_het_han ? $reader->ngay_het_han->format('d/m/Y') : 'N/A' }}
                                    @if($reader->ngay_het_han && $reader->ngay_het_han < now())
                                        <span class="badge bg-danger ms-1">Hết hạn</span>
                                    @elseif($reader->ngay_het_han && $reader->ngay_het_han < now()->addDays(30))
                                        <span class="badge bg-warning ms-1">Sắp hết hạn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Trạng thái:</strong></td>
                                <td>
                                    @if($reader->trang_thai == 'Hoat dong')
                                        <span class="badge bg-success">Hoạt động</span>
                                    @elseif($reader->trang_thai == 'Tam khoa')
                                        <span class="badge bg-warning">Tạm khóa</span>
                                    @else
                                        <span class="badge bg-danger">Hết hạn</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-primary text-white font-size-24">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">{{ $stats['total_borrows'] }}</h5>
                                    <p class="text-muted mb-0">Tổng lượt mượn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-warning text-white font-size-24">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">{{ $stats['active_borrows'] }}</h5>
                                    <p class="text-muted mb-0">Đang mượn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-danger text-white font-size-24">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">{{ number_format($stats['pending_fines']) }} VNĐ</h5>
                                    <p class="text-muted mb-0">Phạt chưa thanh toán</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-info text-white font-size-24">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">{{ $stats['active_reservations'] }}</h5>
                                    <p class="text-muted mb-0">Đặt chỗ đang chờ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thao tác -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.readers.edit', $reader->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa
                        </a>
                        
                        @if($reader->trang_thai == 'Hoat dong')
                            <form method="POST" action="{{ route('admin.readers.suspend', $reader->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Bạn có chắc muốn tạm khóa tác giả này?')">
                                    <i class="fas fa-lock me-1"></i>Tạm khóa
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.readers.activate', $reader->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc muốn kích hoạt tác giả này?')">
                                    <i class="fas fa-unlock me-1"></i>Kích hoạt
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.readers.renew-card', $reader->id) }}" class="btn btn-info" onclick="return confirm('Bạn có chắc muốn gia hạn thẻ tác giả này?')">
                            <i class="fas fa-calendar-plus me-1"></i>Gia hạn thẻ
                        </a>

                        <a href="{{ route('admin.readers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch sử mượn sách -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Lịch sử mượn sách</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sách</th>
                                    <th>Ngày mượn</th>
                                    <th>Hạn trả</th>
                                    <th>Ngày trả</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reader->borrows as $borrow)
                                <tr>
                                    <td>{{ $borrow->book->ten_sach ?? 'N/A' }}</td>
                                    <td>{{ $borrow->ngay_muon->format('d/m/Y') }}</td>
                                    <td>{{ $borrow->ngay_hen_tra->format('d/m/Y') }}</td>
                                    <td>{{ $borrow->ngay_tra_thuc_te ? $borrow->ngay_tra_thuc_te->format('d/m/Y') : 'Chưa trả' }}</td>
                                    <td>
                                        @if($borrow->trang_thai == 'Dang muon')
                                            <span class="badge bg-primary">Đang mượn</span>
                                        @elseif($borrow->trang_thai == 'Da tra')
                                            <span class="badge bg-success">Đã trả</span>
                                        @elseif($borrow->trang_thai == 'Qua han')
                                            <span class="badge bg-danger">Quá hạn</span>
                                        @else
                                            <span class="badge bg-warning">{{ $borrow->trang_thai }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có lịch sử mượn sách</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch sử phạt -->
    @if($reader->fines->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Lịch sử phạt</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Loại phạt</th>
                                    <th>Số tiền</th>
                                    <th>Ngày tạo</th>
                                    <th>Hạn thanh toán</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reader->fines as $fine)
                                <tr>
                                    <td>{{ $fine->type }}</td>
                                    <td>{{ number_format($fine->amount) }} VNĐ</td>
                                    <td>{{ $fine->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $fine->due_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if($fine->status == 'pending')
                                            <span class="badge bg-warning">Chưa thanh toán</span>
                                        @elseif($fine->status == 'paid')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @elseif($fine->status == 'waived')
                                            <span class="badge bg-info">Được miễn</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $fine->status }}</span>
                                        @endif
                                    </td>
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
@endsection
