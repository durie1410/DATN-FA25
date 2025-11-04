@extends('layouts.frontend')

@section('title', 'Đăng Nhập - Thư Viện Online')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-sign-in-alt"></i> Đăng Nhập</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="text-center my-3">
                        <span class="text-muted">hoặc</span>
                    </div>

                    <!-- Google OAuth Button -->
                    <div class="d-grid">
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-danger">
                            <i class="fab fa-google"></i> Đăng nhập với Google
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a> | <a href="{{ route('register.reader.form') }}">Đăng ký độc giả</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



