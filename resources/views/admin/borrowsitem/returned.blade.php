@extends('layouts.admin')

@section('title', 'Danh sách sách đã trả')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Danh sách sách đã trả</h4>
    </div>
<form method="GET" action="" class="mb-3 d-flex" style="max-width: 350px;display:flex;">
    <input type="text" name="keyword" class="form-control me-2"
           placeholder="Tìm kiếm theo tên sách..."
           value="{{ request('keyword') }}">
    <button class="btn btn-primary">Tìm</button>
</form>

    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sách</th>
                    <th>Bản thể</th>
                    <th>Người mượn</th>
                    <th>Ngày mượn</th>
                    <th>Ngày trả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>{{ $item->book->ten_sach ?? '---' }}</td>
<td>{{ $item->inventory->id }}::{{ $item->inventory->location ?? '---' }}</td>

                    <td>{{ $item->borrow->ten_nguoi_muon ?? '---' }}</td>
                    <td>{{ $item->ngay_muon }}</td>
                    <td>{{ $item->ngay_tra_thuc_te }}</td>
  <td>
    @if($item->inventory)
        @if($item->inventory->status != 'Co san')
            <form action="{{ route('admin.inventories.return', $item->inventory->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="badge bg-success border-0" onclick="return confirm('Xác nhận hoàn kho?')">
                    <i class="fas fa-warehouse me-1"></i> hoàn kho
                </button>
            </form>
        @else
            <span class="badge bg-secondary">
                <i class="fas fa-check me-1"></i> đã hoàn
            </span>
        @endif
    @else
        <span class="text-muted">Không có bản thể</span>
    @endif
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Không có sách nào đã trả.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
