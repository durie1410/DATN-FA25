@extends('layouts.admin')

@section('title', 'Báo Cáo Mượn/Trả Sách - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-exchange-alt"></i> Báo cáo mượn/trả sách</h3>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- Form lọc --}}
    <form action="{{ route('admin.reports.borrows') }}" method="GET" class="row mb-4">
        <div class="col-md-3">
            <label class="form-label">Từ ngày</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Đến ngày</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-control">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="Dang muon" {{ request('trang_thai') == 'Dang muon' ? 'selected' : '' }}>Đang mượn</option>
                <option value="Da tra" {{ request('trang_thai') == 'Da tra' ? 'selected' : '' }}>Đã trả</option>
                <option value="Qua han" {{ request('trang_thai') == 'Qua han' ? 'selected' : '' }}>Quá hạn</option>
                <option value="Mat sach" {{ request('trang_thai') == 'Mat sach' ? 'selected' : '' }}>Mất sách</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">&nbsp;</label>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Lọc</button>
                <a href="{{ route('admin.reports.borrows') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    {{-- Bảng báo cáo --}}
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Độc giả</th>
                    <th>Sách</th>
                    <th>Ngày mượn</th>
                    <th>Hạn trả</th>
                    <th>Ngày trả</th>
                    <th>Trạng thái</th>
                    <th>Thủ thư</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                <tr class="{{ $borrow->isOverdue() ? 'table-danger' : '' }}">
                    <td>{{ $borrow->id }}</td>
                    <td>{{ $borrow->reader->ho_ten }}</td>
                    <td>{{ $borrow->book->ten_sach }}</td>
                    <td>{{ $borrow->ngay_muon->format('d/m/Y') }}</td>
                    <td>{{ $borrow->ngay_hen_tra->format('d/m/Y') }}</td>
                    <td>{{ $borrow->ngay_tra_thuc_te ? $borrow->ngay_tra_thuc_te->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($borrow->trang_thai == 'Dang muon')
                            <span class="badge bg-primary">Đang mượn</span>
                        @elseif($borrow->trang_thai == 'Da tra')
                            <span class="badge bg-success">Đã trả</span>
                        @elseif($borrow->trang_thai == 'Qua han')
                            <span class="badge bg-danger">Quá hạn</span>
                        @else
                            <span class="badge bg-warning">Mất sách</span>
                        @endif
                        @if($borrow->isOverdue())
                            <span class="badge bg-danger ms-1">Quá hạn</span>
                        @endif
                    </td>
                    <td>{{ $borrow->librarian->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
