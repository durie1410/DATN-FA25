@extends('layouts.admin')

@section('title', 'Sửa Sách - Admin')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Chỉnh sửa sách
        </h1>
        <p class="page-subtitle">Cập nhật thông tin sách</p>
    </div>
    <div>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Edit Form -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-book"></i>
            Thông tin sách
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tên sách <span class="text-danger">*</span></label>
                        <input type="text" name="ten_sach" value="{{ old('ten_sach', $book->ten_sach) }}" class="form-control @error('ten_sach') is-invalid @enderror" required>
                        @error('ten_sach')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Thể loại <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $cate)
                                <option value="{{ $cate->id }}" {{ old('category_id', $book->category_id) == $cate->id ? 'selected' : '' }}>
                                    {{ $cate->ten_the_loai }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tác giả <span class="text-danger">*</span></label>
                        <input type="text" name="tac_gia" value="{{ old('tac_gia', $book->tac_gia) }}" class="form-control @error('tac_gia') is-invalid @enderror" required>
                        @error('tac_gia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Năm xuất bản <span class="text-danger">*</span></label>
                        <input type="number" name="nam_xuat_ban" value="{{ old('nam_xuat_ban', $book->nam_xuat_ban) }}" class="form-control @error('nam_xuat_ban') is-invalid @enderror" required>
                        @error('nam_xuat_ban')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nhà xuất bản</label>
                        <select name="nha_xuat_ban_id" class="form-control @error('nha_xuat_ban_id') is-invalid @enderror">
                            <option value="">-- Chọn nhà xuất bản --</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ old('nha_xuat_ban_id', $book->nha_xuat_ban_id) == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->ten_nha_xuat_ban }}
                                </option>
                            @endforeach
                        </select>
                        @error('nha_xuat_ban_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh bìa</label>
                <div id="image-preview-container" class="mb-2">
                    @if($book->hinh_anh)
                        @php
                            // Clean path and build URL directly - same method as index page
                            $imagePath = ltrim(str_replace('\\', '/', $book->hinh_anh), '/');
                            // Add timestamp to prevent caching issues
                            $imageUrl = asset('storage/' . $imagePath) . '?t=' . $book->updated_at->timestamp;
                        @endphp
                        <img id="book-cover-image" 
                             src="{{ $imageUrl }}" 
                             width="120" height="160" 
                             style="object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                             alt="{{ $book->ten_sach }}"
                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display: none; width: 120px; height: 160px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); align-items: center; justify-content: center; border: 1px solid rgba(0, 255, 153, 0.2);">
                            <i class="fas fa-book" style="color: #666; font-size: 48px;"></i>
                        </div>
                    @else
                        <img id="book-cover-image" 
                             src="" 
                             width="120" height="160" 
                             style="object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: none;"
                             alt="Preview">
                    @endif
                </div>
                <input type="file" name="hinh_anh" id="hinh_anh_input" class="form-control @error('hinh_anh') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                <small class="form-text text-muted">Chọn ảnh mới để thay thế ảnh hiện tại (tối đa 2MB, định dạng: JPG, PNG)</small>
                @error('hinh_anh')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" rows="4" placeholder="Nhập mô tả về sách...">{{ old('mo_ta', $book->mo_ta) }}</textarea>
                @error('mo_ta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Giá (VNĐ)</label>
                        <input type="number" name="gia" value="{{ old('gia', $book->gia) }}" class="form-control @error('gia') is-invalid @enderror" min="0" step="1000" placeholder="Để trống nếu miễn phí">
                        @error('gia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Số lượng sách <span class="text-danger">*</span></label>
                        <input type="number" name="so_luong" value="{{ old('so_luong', $book->so_luong ?? 0) }}" class="form-control @error('so_luong') is-invalid @enderror" min="0" required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Số lượng có thể chỉnh sửa trực tiếp hoặc được cập nhật tự động khi duyệt phiếu nhập kho
                        </small>
                        @error('so_luong')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="trang_thai" class="form-control @error('trang_thai') is-invalid @enderror" required>
                            <option value="active" {{ old('trang_thai', $book->trang_thai) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('trang_thai', $book->trang_thai) == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật sách
                </button>
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('hinh_anh_input');
    const imagePreview = document.getElementById('book-cover-image');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Kích thước file vượt quá 2MB. Vui lòng chọn file nhỏ hơn.');
                    imageInput.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Chỉ chấp nhận file ảnh định dạng JPEG, PNG hoặc JPG.');
                    imageInput.value = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
@endsection
