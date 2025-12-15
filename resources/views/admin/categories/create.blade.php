@extends('layouts.admin')

@section('title', 'Thêm Thể Loại Mới - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Thêm thể loại mới</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin thể loại</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên thể loại <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_the_loai" class="form-control @error('ten_the_loai') is-invalid @enderror" 
                                           value="{{ old('ten_the_loai') }}" required>
                                    @error('ten_the_loai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                                        <option value="active" {{ old('trang_thai') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('trang_thai') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" 
                                      rows="3" placeholder="Mô tả về thể loại sách...">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Màu sắc</label>
                                    <div class="input-group">
                                        <input type="color" name="mau_sac" class="form-control form-control-color" 
                                               value="{{ old('mau_sac', '#007bff') }}">
                                        <input type="text" class="form-control" placeholder="#007bff" 
                                               value="{{ old('mau_sac', '#007bff') }}" readonly>
                                    </div>
                                    <small class="form-text text-muted">Chọn màu sắc đại diện cho thể loại</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Icon</label>
                                    <select name="icon" class="form-select @error('icon') is-invalid @enderror">
                                        <option value="">-- Chọn icon --</option>
                                        <option value="fas fa-book" {{ old('icon') == 'fas fa-book' ? 'selected' : '' }}>📚 Sách</option>
                                        <option value="fas fa-graduation-cap" {{ old('icon') == 'fas fa-graduation-cap' ? 'selected' : '' }}>🎓 Giáo dục</option>
                                        <option value="fas fa-flask" {{ old('icon') == 'fas fa-flask' ? 'selected' : '' }}>🧪 Khoa học</option>
                                        <option value="fas fa-history" {{ old('icon') == 'fas fa-history' ? 'selected' : '' }}>📜 Lịch sử</option>
                                        <option value="fas fa-palette" {{ old('icon') == 'fas fa-palette' ? 'selected' : '' }}>🎨 Nghệ thuật</option>
                                        <option value="fas fa-heart" {{ old('icon') == 'fas fa-heart' ? 'selected' : '' }}>❤️ Y tế</option>
                                        <option value="fas fa-chart-line" {{ old('icon') == 'fas fa-chart-line' ? 'selected' : '' }}>📈 Kinh tế</option>
                                        <option value="fas fa-code" {{ old('icon') == 'fas fa-code' ? 'selected' : '' }}>💻 Công nghệ</option>
                                        <option value="fas fa-globe" {{ old('icon') == 'fas fa-globe' ? 'selected' : '' }}>🌍 Địa lý</option>
                                        <option value="fas fa-users" {{ old('icon') == 'fas fa-users' ? 'selected' : '' }}>👥 Xã hội</option>
                                    </select>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu thể loại
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lưu ý:</h6>
                        <ul class="mb-0">
                            <li>Tên thể loại phải duy nhất</li>
                            <li>Màu sắc giúp phân biệt thể loại</li>
                            <li>Icon làm cho giao diện đẹp hơn</li>
                            <li>Mô tả giúp người dùng hiểu rõ hơn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Cập nhật text input khi thay đổi color picker
    document.querySelector('input[name="mau_sac"]').addEventListener('change', function() {
        this.nextElementSibling.value = this.value;
    });
</script>
@endsection
