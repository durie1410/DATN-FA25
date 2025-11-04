@extends('layouts.frontend')

@section('title', 'Nạp tiền vào tài khoản')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Nạp tiền vào tài khoản thành viên</h3>

    <form action="{{ route('nap-tien.momo') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="so_tien" class="form-label">Số tiền muốn nạp (VND):</label>
            <input type="number" class="form-control" name="so_tien" min="1000" required>
        </div>
        <button type="submit" class="btn btn-success">Thanh toán qua MoMo</button>
    </form>
</div>
@endsection
