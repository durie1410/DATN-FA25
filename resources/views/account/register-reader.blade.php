@extends('account._layout')

@section('title', 'Đăng ký độc giả')
@section('breadcrumb', 'Đăng ký độc giả')

@section('content')
<div class="account-details-form">
    <h2 class="form-title">Đăng ký độc giả</h2>
    
    @if($user->reader)
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #bee5eb;">
            <strong>Thông báo:</strong> Bạn đã có thẻ độc giả rồi! Số thẻ: <strong>{{ $user->reader->so_the_doc_gia }}</strong>
        </div>
    @else
        <form method="POST" action="{{ route('account.register-reader.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="ho_ten">Họ và tên</label>
                <div class="input-with-icon">
                    <input type="text" id="ho_ten" name="ho_ten" value="{{ $user->name }}" readonly>
                    <span class="input-icon">👤</span>
                </div>
                <small class="form-text text-muted">Thông tin này được lấy từ tài khoản của bạn</small>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-with-icon">
                    <input type="email" id="email" name="email" value="{{ $user->email }}" readonly>
                    <span class="input-icon">✉️</span>
                </div>
                <small class="form-text text-muted">Thông tin này được lấy từ tài khoản của bạn</small>
            </div>
            
            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <input type="text" id="so_dien_thoai" name="so_dien_thoai" 
                           value="{{ old('so_dien_thoai') }}" 
                           class="@error('so_dien_thoai') is-invalid @enderror" 
                           placeholder="Nhập số điện thoại" required>
                    <span class="input-icon">📞</span>
                </div>
                @error('so_dien_thoai')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="so_cccd">Số CCCD <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <input type="text" id="so_cccd" name="so_cccd" 
                           value="{{ old('so_cccd') }}" 
                           class="@error('so_cccd') is-invalid @enderror" 
                           placeholder="Nhập số CCCD đầy đủ" required>
                    <span class="input-icon">🆔</span>
                </div>
                @error('so_cccd')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group half-width">
                <label for="ngay_sinh">Ngày sinh <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <input type="date" id="ngay_sinh" name="ngay_sinh" 
                           value="{{ old('ngay_sinh') }}" 
                           class="@error('ngay_sinh') is-invalid @enderror" 
                           max="{{ date('Y-m-d', strtotime('-1 day')) }}" required>
                    <span class="input-icon">📅</span>
                </div>
                @error('ngay_sinh')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group half-width">
                <label for="gioi_tinh">Giới tính <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <select id="gioi_tinh" name="gioi_tinh" 
                            class="@error('gioi_tinh') is-invalid @enderror" required>
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam" {{ old('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nu" {{ old('gioi_tinh') == 'Nu' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khac" {{ old('gioi_tinh') == 'Khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                    <span class="input-icon arrow-down">▼</span>
                </div>
                @error('gioi_tinh')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group full-width">
                <label for="dia_chi">Địa chỉ <span class="text-danger">*</span></label>
                <div class="input-with-icon">
                    <textarea id="dia_chi" name="dia_chi" 
                              class="@error('dia_chi') is-invalid @enderror" 
                              rows="3" placeholder="Nhập địa chỉ đầy đủ" required>{{ old('dia_chi') }}</textarea>
                    <span class="input-icon">🏠</span>
                </div>
                @error('dia_chi')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; padding: 12px 20px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #bee5eb;">
                <strong>Lưu ý:</strong>
                <ul style="margin-bottom: 0; margin-top: 8px; padding-left: 20px;">
                    <li>Sau khi đăng ký, bạn sẽ được cấp thẻ độc giả tự động</li>
                    <li>Thẻ độc giả có hiệu lực trong 1 năm</li>
                    <li>Bạn có thể mượn tối đa 5 cuốn sách cùng lúc</li>
                    <li>Thời gian mượn mỗi cuốn sách là 14 ngày</li>
                </ul>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-update">Đăng ký độc giả</button>
                <a href="{{ route('account') }}" class="btn-secondary" style="display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; margin-left: 10px;">Quay lại</a>
            </div>
        </form>
    @endif
</div>
@endsection



