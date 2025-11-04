@extends('layouts.staff')

@section('title', 'Chi tiết sách - Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Chi tiết sách</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $book->ten_sach }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tác giả:</strong> {{ $book->tac_gia }}</p>
                            <p><strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban }}</p>
                            <p><strong>Danh mục:</strong> {{ $book->category->ten_the_loai ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tổng số bản:</strong> {{ $book->getTotalCopiesAttribute() }}</p>
                            <p><strong>Có sẵn:</strong> {{ $book->getAvailableCopiesAttribute() }}</p>
                            <p><strong>Đang mượn:</strong> {{ $book->getBorrowedCopiesAttribute() }}</p>
                        </div>
                    </div>
                    
                    @if($book->mo_ta)
                    <div class="mt-3">
                        <h6>Mô tả:</h6>
                        <p>{{ $book->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.books.edit', $book->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        <a href="{{ route('staff.books.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch sử mượn -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Lịch sử mượn</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Độc giả</th>
                                    <th>Ngày mượn</th>
                                    <th>Hạn trả</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($book->borrows as $borrow)
                                <tr>
                                    <td>{{ $borrow->reader->ho_ten }}</td>
                                    <td>{{ $borrow->ngay_muon->format('d/m/Y') }}</td>
                                    <td>{{ $borrow->ngay_hen_tra->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $borrow->trang_thai == 'Dang muon' ? 'bg-primary' : 'bg-success' }}">
                                            {{ $borrow->trang_thai }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Chưa có lịch sử mượn</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

