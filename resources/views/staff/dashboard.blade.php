@extends('layouts.staff')

@section('title', 'Dashboard - Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard - Nhân viên thư viện</h4>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">Tổng sách</h5>
                            <h3 class="my-2 py-1">{{ number_format($stats['total_books']) }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="fas fa-book text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">Độc giả</h5>
                            <h3 class="my-2 py-1">{{ number_format($stats['total_readers']) }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="fas fa-users text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">Đang mượn</h5>
                            <h3 class="my-2 py-1">{{ number_format($stats['active_borrows']) }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="fas fa-hand-holding text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">Quá hạn</h5>
                            <h3 class="my-2 py-1 text-danger">{{ number_format($stats['overdue_books']) }}</h3>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sách được mượn nhiều nhất -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sách được mượn nhiều nhất</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tên sách</th>
                                    <th>Số lần mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popular_books as $book)
                                <tr>
                                    <td>{{ $book->ten_sach }}</td>
                                    <td><span class="badge bg-primary">{{ $book->borrows_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Độc giả tích cực -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Độc giả tích cực</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tên độc giả</th>
                                    <th>Số lần mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($active_readers as $reader)
                                <tr>
                                    <td>{{ $reader->ho_ten }}</td>
                                    <td><span class="badge bg-success">{{ $reader->borrows_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sách sắp đến hạn trả -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sách sắp đến hạn trả</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sách</th>
                                    <th>Độc giả</th>
                                    <th>Hạn trả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming_returns as $borrow)
                                <tr>
                                    <td>{{ $borrow->book->ten_sach }}</td>
                                    <td>{{ $borrow->reader->ho_ten }}</td>
                                    <td>
                                        <span class="badge {{ $borrow->ngay_hen_tra < now() ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $borrow->ngay_hen_tra->format('d/m/Y') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đặt chỗ chờ xử lý -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Đặt chỗ chờ xử lý</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sách</th>
                                    <th>Độc giả</th>
                                    <th>Ngày đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pending_reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->book->ten_sach }}</td>
                                    <td>{{ $reservation->reader->ho_ten }}</td>
                                    <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo danh mục -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thống kê theo danh mục</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($category_stats as $category)
                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="text-center">
                                <h5>{{ $category->books_count }}</h5>
                                <p class="text-muted">{{ $category->name }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
