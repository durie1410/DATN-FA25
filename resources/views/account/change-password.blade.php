@extends('account._layout')

@section('title', 'Đổi mật khẩu')
@section('breadcrumb', 'Đổi mật khẩu')

@section('content')
<div class="account-details-form">
    <h2 class="form-title">Đổi mật khẩu</h2>
    <form method="POST" action="{{ route('account.update-password') }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="current_password">Mật khẩu hiện tại</label>
            <div class="input-with-icon">
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
                <span class="input-icon">🔒</span>
            </div>
            @error('current_password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">Mật khẩu mới</label>
            <div class="input-with-icon">
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <span class="input-icon">🔒</span>
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Xác nhận mật khẩu mới</label>
            <div class="input-with-icon">
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                <span class="input-icon">🔒</span>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-update">Cập nhật mật khẩu</button>
        </div>
    </form>
</div>
@endsection

