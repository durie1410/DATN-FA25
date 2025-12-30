@extends('layouts.admin')

@section('title', 'Tạo phạt mới')

@section('content')
<link href="{{ asset('css/fines-management.css') }}" rel="stylesheet">

<div class="container-fluid fines-management">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="fines-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1><i class="fas fa-plus"></i> Tạo phạt mới</h1>
                            <p class="subtitle">Tạo phạt cho các vi phạm của độc giả</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.fines.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="fines-filter">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrow_item_id">Sách mượn <span class="text-danger">*</span></label>
                                    <select name="borrow_item_id" id="borrow_item_id" class="form-control @error('borrow_item_id') is-invalid @enderror" required>
                                        <option value="">Chọn sách mượn</option>
                                        @foreach($borrowItems as $item)
                                            <option value="{{ $item->id }}" 
                                                {{ old('borrow_item_id', $borrowItem->id ?? '') == $item->id ? 'selected' : '' }}
                                                data-reader="{{ $item->borrow->reader->ho_ten ?? 'N/A' }}"
                                                data-reader-code="{{ $item->borrow->reader->ma_so_the ?? 'N/A' }}"
                                                data-book="{{ $item->book->ten_sach ?? 'N/A' }}"
                                                data-book-code="{{ $item->book->ma_sach ?? 'N/A' }}"
                                                data-borrow-id="{{ $item->borrow_id }}"
                                                data-status="{{ $item->trang_thai }}"
                                                data-due-date="{{ $item->ngay_hen_tra ? $item->ngay_hen_tra->format('d/m/Y') : 'N/A' }}">
                                                #{{ $item->id }} - {{ $item->book->ten_sach ?? 'N/A' }} 
                                                ({{ $item->borrow->reader->ho_ten ?? 'N/A' }}) 
                                                - {{ $item->trang_thai }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('borrow_item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Chọn sách cụ thể để tạo phạt</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Loại phạt <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">Chọn loại phạt</option>
                                        <option value="late_return" {{ old('type') == 'late_return' ? 'selected' : '' }}>Trả muộn</option>
                                        <option value="damaged_book" {{ old('type') == 'damaged_book' ? 'selected' : '' }}>Làm hỏng sách</option>
                                        <option value="lost_book" {{ old('type') == 'lost_book' ? 'selected' : '' }}>Mất sách</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Số tiền phạt (VND) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount') }}" 
                                           min="0" step="1000" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Hạn thanh toán <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Mô tả lý do phạt</label>
                                    <textarea name="description" id="description" rows="3" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Mô tả chi tiết lý do phạt...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="notes" rows="2" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              placeholder="Ghi chú thêm...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin hư hỏng (chỉ hiển thị khi chọn loại phạt là damaged_book hoặc lost_book) -->
                        <div id="damage-info-section" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mt-4 mb-3">
                                        <i class="fas fa-exclamation-triangle text-warning"></i> 
                                        Thông tin hư hỏng/Mất sách
                                    </h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="damage_severity">Mức độ hư hỏng</label>
                                        <select name="damage_severity" id="damage_severity" 
                                                class="form-control @error('damage_severity') is-invalid @enderror">
                                            <option value="">Chọn mức độ</option>
                                            <option value="nhe" {{ old('damage_severity') == 'nhe' ? 'selected' : '' }}>Nhẹ</option>
                                            <option value="trung_binh" {{ old('damage_severity') == 'trung_binh' ? 'selected' : '' }}>Trung bình</option>
                                            <option value="nang" {{ old('damage_severity') == 'nang' ? 'selected' : '' }}>Nặng</option>
                                            <option value="mat_sach" {{ old('damage_severity') == 'mat_sach' ? 'selected' : '' }}>Mất sách</option>
                                        </select>
                                        @error('damage_severity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="damage_type">Loại hư hỏng</label>
                                        <select name="damage_type" id="damage_type" 
                                                class="form-control @error('damage_type') is-invalid @enderror">
                                            <option value="">Chọn loại hư hỏng</option>
                                            <option value="trang_bi_rach" {{ old('damage_type') == 'trang_bi_rach' ? 'selected' : '' }}>Trang bị rách</option>
                                            <option value="bia_bi_hu" {{ old('damage_type') == 'bia_bi_hu' ? 'selected' : '' }}>Bìa bị hỏng</option>
                                            <option value="trang_bi_meo" {{ old('damage_type') == 'trang_bi_meo' ? 'selected' : '' }}>Trang bị méo</option>
                                            <option value="mat_trang" {{ old('damage_type') == 'mat_trang' ? 'selected' : '' }}>Mất trang</option>
                                            <option value="bi_am_moc" {{ old('damage_type') == 'bi_am_moc' ? 'selected' : '' }}>Bị ẩm mốc</option>
                                            <option value="bi_ban" {{ old('damage_type') == 'bi_ban' ? 'selected' : '' }}>Bị bẩn</option>
                                            <option value="mat_sach" {{ old('damage_type') == 'mat_sach' ? 'selected' : '' }}>Mất sách</option>
                                            <option value="khac" {{ old('damage_type') == 'khac' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                        @error('damage_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condition_before">Tình trạng trước khi mượn</label>
                                        <select name="condition_before" id="condition_before" 
                                                class="form-control @error('condition_before') is-invalid @enderror">
                                            <option value="">Chọn tình trạng</option>
                                            <option value="Moi" {{ old('condition_before') == 'Moi' ? 'selected' : '' }}>Mới</option>
                                            <option value="Tot" {{ old('condition_before') == 'Tot' ? 'selected' : '' }}>Tốt</option>
                                            <option value="Trung binh" {{ old('condition_before') == 'Trung binh' ? 'selected' : '' }}>Trung bình</option>
                                            <option value="Cu" {{ old('condition_before') == 'Cu' ? 'selected' : '' }}>Cũ</option>
                                            <option value="Hong" {{ old('condition_before') == 'Hong' ? 'selected' : '' }}>Hỏng</option>
                                        </select>
                                        @error('condition_before')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="condition_after">Tình trạng sau khi trả</label>
                                        <select name="condition_after" id="condition_after" 
                                                class="form-control @error('condition_after') is-invalid @enderror">
                                            <option value="">Chọn tình trạng</option>
                                            <option value="Moi" {{ old('condition_after') == 'Moi' ? 'selected' : '' }}>Mới</option>
                                            <option value="Tot" {{ old('condition_after') == 'Tot' ? 'selected' : '' }}>Tốt</option>
                                            <option value="Trung binh" {{ old('condition_after') == 'Trung binh' ? 'selected' : '' }}>Trung bình</option>
                                            <option value="Cu" {{ old('condition_after') == 'Cu' ? 'selected' : '' }}>Cũ</option>
                                            <option value="Hong" {{ old('condition_after') == 'Hong' ? 'selected' : '' }}>Hỏng</option>
                                            <option value="Mat" {{ old('condition_after') == 'Mat' ? 'selected' : '' }}>Mất</option>
                                        </select>
                                        @error('condition_after')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="damage_description">Mô tả chi tiết tình trạng hư hỏng</label>
                                        <textarea name="damage_description" id="damage_description" rows="4" 
                                                  class="form-control @error('damage_description') is-invalid @enderror" 
                                                  placeholder="Mô tả chi tiết tình trạng hư hỏng của sách...">{{ old('damage_description') }}</textarea>
                                        @error('damage_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Mô tả chi tiết về tình trạng hư hỏng, vị trí hư hỏng, mức độ nghiêm trọng...</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="damage_images">Ảnh minh chứng hư hỏng</label>
                                        <input type="file" name="damage_images[]" id="damage_images" 
                                               class="form-control @error('damage_images.*') is-invalid @enderror" 
                                               multiple accept="image/*">
                                        @error('damage_images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Có thể upload nhiều ảnh (tối đa 5MB/ảnh). Hỗ trợ: JPEG, PNG, JPG, GIF</small>
                                        <div id="image-preview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inspection_notes">Ghi chú kiểm tra</label>
                                        <textarea name="inspection_notes" id="inspection_notes" rows="2" 
                                                  class="form-control @error('inspection_notes') is-invalid @enderror" 
                                                  placeholder="Ghi chú về quá trình kiểm tra...">{{ old('inspection_notes') }}</textarea>
                                        @error('inspection_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin độc giả và sách -->
                        <div class="row" id="borrow-info" style="display: none;">
                            <div class="col-md-12">
                                <div class="fines-alert alert-info">
                                    <h5><i class="fas fa-info-circle"></i> Thông tin chi tiết</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Độc giả:</strong> <span id="reader-name"></span><br>
                                            <strong>Mã số thẻ:</strong> <span id="reader-code"></span><br>
                                            <strong>Mã phiếu mượn:</strong> <span id="borrow-id"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Sách:</strong> <span id="book-name"></span><br>
                                            <strong>Mã sách:</strong> <span id="book-code"></span><br>
                                            <strong>Trạng thái:</strong> <span id="item-status"></span><br>
                                            <strong>Hạn trả:</strong> <span id="due-date"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Tạo phạt
                        </button>
                        <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const borrowItemSelect = document.getElementById('borrow_item_id');
    const borrowInfo = document.getElementById('borrow-info');
    
    borrowItemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            document.getElementById('reader-name').textContent = selectedOption.getAttribute('data-reader') || 'N/A';
            document.getElementById('reader-code').textContent = selectedOption.getAttribute('data-reader-code') || 'N/A';
            document.getElementById('book-name').textContent = selectedOption.getAttribute('data-book') || 'N/A';
            document.getElementById('book-code').textContent = selectedOption.getAttribute('data-book-code') || 'N/A';
            document.getElementById('borrow-id').textContent = '#' + (selectedOption.getAttribute('data-borrow-id') || 'N/A');
            document.getElementById('item-status').textContent = selectedOption.getAttribute('data-status') || 'N/A';
            document.getElementById('due-date').textContent = selectedOption.getAttribute('data-due-date') || 'N/A';
            borrowInfo.style.display = 'block';
        } else {
            borrowInfo.style.display = 'none';
        }
    });
    
    // Tự động điền thông tin nếu có borrow_item_id từ URL
    @if(isset($borrowItem) && $borrowItem)
        borrowItemSelect.value = {{ $borrowItem->id }};
        borrowItemSelect.dispatchEvent(new Event('change'));
    @endif

    // Hiển thị/ẩn phần thông tin hư hỏng dựa trên loại phạt
    const typeSelect = document.getElementById('type');
    const damageInfoSection = document.getElementById('damage-info-section');
    
    function toggleDamageInfo() {
        const selectedType = typeSelect.value;
        if (selectedType === 'damaged_book' || selectedType === 'lost_book') {
            damageInfoSection.style.display = 'block';
        } else {
            damageInfoSection.style.display = 'none';
        }
    }
    
    typeSelect.addEventListener('change', toggleDamageInfo);
    toggleDamageInfo(); // Chạy lần đầu để set trạng thái ban đầu

    // Preview ảnh trước khi upload
    const damageImagesInput = document.getElementById('damage_images');
    const imagePreview = document.getElementById('image-preview');
    
    damageImagesInput.addEventListener('change', function(e) {
        imagePreview.innerHTML = '';
        const files = e.target.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail m-1';
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';
                    imagePreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    });
});
</script>
@endsection
