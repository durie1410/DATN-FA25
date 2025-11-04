@extends('layouts.frontend')
@section('title', 'Nạp tiền thất bại')
@section('content')
<div class="container py-5 text-center">
    <h3 class="text-danger">❌ Giao dịch thất bại!</h3>
    <p>Vui lòng thử lại sau.</p>
    <a href="{{ route('nap-tien.form') }}" class="btn btn-warning">Thử lại</a>
</div>
@endsection
