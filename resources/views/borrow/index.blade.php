@extends('account._layout')

@section('content')
<!-- CSS Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- JS Bootstrap 5 (Popper + JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container py-4">
    <h3 class="mb-4">phiếu mượn</h3>

  

    @foreach($borrow_items as $item)
    <div class="card mb-3 shadow-sm">
        <div class="row g-0 align-items-center">
            <!-- Hình sách -->
            <div class="col-md-2 text-center p-2">
                <img src="{{ asset('storage/' . $item->book->hinh_anh) }}" 
                     class="img-fluid rounded" alt="">

            </div>

            <!-- Thông tin sách -->
            <div class="col-md-6">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->book->title }}</h5>
                    <p class="card-text mb-1"><small>Tác giả: {{ $item->book->author }}</small></p>
                    <p class="card-text mb-1"><small>Tên sách : {{ $item->book->ten_sach }}</small></p>
                    <p class="card-text mb-1"><small>tiền cọc : {{number_format($item->tien_coc, 0, ',', '.') }}</small></p>
                    <p class="card-text mb-1"><small>Tiền thuê : {{ number_format($item->tien_thue, 0, ',', '.') }}</small></p>
                    <p class="card-text mb-1">
                        Tiền cọc: <strong>{{ number_format($item->tien_coc, 0, ',', '.') }} VND</strong>
                    </p>
                </div>
            </div>

         
        </div>
    </div>
    @endforeach

@if($borrow_items->isEmpty())
    <p class="text-center text-muted mt-4">Chưa có sách nào trong giỏ sách.</p>
@else
    <div class="card shadow-sm mt-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5>Tổng tiền cọc:</h5>
            <h5 class="text-primary">{{ number_format($total, 0, ',', '.') }} VND</h5>
        </div>
    </div>
    <a href="" class="btn btn-success mt-2">Đặt mượn</a>
@endif


    <!-- Phân trang -->
    <div class="mt-4">
        {{ $borrow_items->links() }}
    </div>
</div>
@endsection
