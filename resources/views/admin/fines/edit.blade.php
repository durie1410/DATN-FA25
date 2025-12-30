@extends('layouts.admin')

@section('title', 'Chỉnh sửa phạt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i>
                        Chỉnh sửa phạt #{{ $fine->id }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.fines.show', $fine->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.fines.update', $fine->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phiếu mượn & Sách</label>
                                    <div class="form-control-plaintext">
                                        @if($fine->borrowItem && $fine->borrowItem->book)
                                            <strong>Phiếu #{{ $fine->borrow_id }}</strong><br>
                                            <strong>Sách:</strong> {{ $fine->borrowItem->book->ten_sach }}<br>
                                            <small class="text-muted">Mã sách: {{ $fine->borrowItem->book->ma_sach }}</small><br>
                                            <small class="text-info">Item #{{ $fine->borrowItem->id }}</small>
                                        @elseif($fine->borrow)
                                            <strong>Phiếu #{{ $fine->borrow->id }}</strong><br>
                                            <span class="text-warning">Chưa có thông tin sách</span>
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Độc giả</label>
                                    <div class="form-control-plaintext">
                                        <strong>{{ $fine->reader->ho_ten }}</strong><br>
                                        <small class="text-muted">{{ $fine->reader->ma_so_the }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Loại phạt <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="late_return" {{ old('type', $fine->type) == 'late_return' ? 'selected' : '' }}>Trả muộn</option>
                                        <option value="damaged_book" {{ old('type', $fine->type) == 'damaged_book' ? 'selected' : '' }}>Làm hỏng sách</option>
                                        <option value="lost_book" {{ old('type', $fine->type) == 'lost_book' ? 'selected' : '' }}>Mất sách</option>
                                        <option value="other" {{ old('type', $fine->type) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $fine->status) == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                        <option value="paid" {{ old('status', $fine->status) == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="waived" {{ old('status', $fine->status) == 'waived' ? 'selected' : '' }}>Đã miễn</option>
                                        <option value="cancelled" {{ old('status', $fine->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    @error('status')
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
                                           value="{{ old('amount', $fine->amount) }}" 
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
                                           value="{{ old('due_date', $fine->due_date->format('Y-m-d')) }}" required>
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
                                              placeholder="Mô tả chi tiết lý do phạt...">{{ old('description', $fine->description) }}</textarea>
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
                                              placeholder="Ghi chú thêm...">{{ old('notes', $fine->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày tạo</label>
                                    <div class="form-control-plaintext">
                                        {{ $fine->created_at->format('d/m/Y H:i') }}
                                        @if($fine->creator)
                                            bởi {{ $fine->creator->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày thanh toán</label>
                                    <div class="form-control-plaintext">
                                        @if($fine->paid_date)
                                            {{ $fine->paid_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Chưa thanh toán</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($fine->isOverdue())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Cảnh báo:</strong> Phạt này đã quá hạn {{ $fine->days_overdue }} ngày!
                            </div>
                        @endif

                        <!-- Thông tin hư hỏng (chỉ hiển thị khi loại phạt là damaged_book hoặc lost_book) -->
                        @if($fine->type === 'damaged_book' || $fine->type === 'lost_book')
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h4 class="mb-3">
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
                                            <option value="nhe" {{ old('damage_severity', $fine->damage_severity) == 'nhe' ? 'selected' : '' }}>Nhẹ</option>
                                            <option value="trung_binh" {{ old('damage_severity', $fine->damage_severity) == 'trung_binh' ? 'selected' : '' }}>Trung bình</option>
                                            <option value="nang" {{ old('damage_severity', $fine->damage_severity) == 'nang' ? 'selected' : '' }}>Nặng</option>
                                            <option value="mat_sach" {{ old('damage_severity', $fine->damage_severity) == 'mat_sach' ? 'selected' : '' }}>Mất sách</option>
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
                                            <option value="trang_bi_rach" {{ old('damage_type', $fine->damage_type) == 'trang_bi_rach' ? 'selected' : '' }}>Trang bị rách</option>
                                            <option value="bia_bi_hu" {{ old('damage_type', $fine->damage_type) == 'bia_bi_hu' ? 'selected' : '' }}>Bìa bị hỏng</option>
                                            <option value="trang_bi_meo" {{ old('damage_type', $fine->damage_type) == 'trang_bi_meo' ? 'selected' : '' }}>Trang bị méo</option>
                                            <option value="mat_trang" {{ old('damage_type', $fine->damage_type) == 'mat_trang' ? 'selected' : '' }}>Mất trang</option>
                                            <option value="bi_am_moc" {{ old('damage_type', $fine->damage_type) == 'bi_am_moc' ? 'selected' : '' }}>Bị ẩm mốc</option>
                                            <option value="bi_ban" {{ old('damage_type', $fine->damage_type) == 'bi_ban' ? 'selected' : '' }}>Bị bẩn</option>
                                            <option value="mat_sach" {{ old('damage_type', $fine->damage_type) == 'mat_sach' ? 'selected' : '' }}>Mất sách</option>
                                            <option value="khac" {{ old('damage_type', $fine->damage_type) == 'khac' ? 'selected' : '' }}>Khác</option>
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
                                            <option value="Moi" {{ old('condition_before', $fine->condition_before) == 'Moi' ? 'selected' : '' }}>Mới</option>
                                            <option value="Tot" {{ old('condition_before', $fine->condition_before) == 'Tot' ? 'selected' : '' }}>Tốt</option>
                                            <option value="Trung binh" {{ old('condition_before', $fine->condition_before) == 'Trung binh' ? 'selected' : '' }}>Trung bình</option>
                                            <option value="Cu" {{ old('condition_before', $fine->condition_before) == 'Cu' ? 'selected' : '' }}>Cũ</option>
                                            <option value="Hong" {{ old('condition_before', $fine->condition_before) == 'Hong' ? 'selected' : '' }}>Hỏng</option>
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
                                            <option value="Moi" {{ old('condition_after', $fine->condition_after) == 'Moi' ? 'selected' : '' }}>Mới</option>
                                            <option value="Tot" {{ old('condition_after', $fine->condition_after) == 'Tot' ? 'selected' : '' }}>Tốt</option>
                                            <option value="Trung binh" {{ old('condition_after', $fine->condition_after) == 'Trung binh' ? 'selected' : '' }}>Trung bình</option>
                                            <option value="Cu" {{ old('condition_after', $fine->condition_after) == 'Cu' ? 'selected' : '' }}>Cũ</option>
                                            <option value="Hong" {{ old('condition_after', $fine->condition_after) == 'Hong' ? 'selected' : '' }}>Hỏng</option>
                                            <option value="Mat" {{ old('condition_after', $fine->condition_after) == 'Mat' ? 'selected' : '' }}>Mất</option>
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
                                                  placeholder="Mô tả chi tiết tình trạng hư hỏng của sách...">{{ old('damage_description', $fine->damage_description) }}</textarea>
                                        @error('damage_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Ảnh minh chứng hư hỏng hiện tại</label>
                                        @if($fine->hasDamageImages())
                                            <div class="row mb-3">
                                                @foreach($fine->damage_images as $image)
                                                    <div class="col-md-3 mb-2 position-relative">
                                                        <img src="{{ Storage::url($image) }}" alt="Ảnh hư hỏng" 
                                                             class="img-thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                                                        <div class="form-check mt-2">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="remove_images[]" value="{{ $image }}" 
                                                                   id="remove_{{ $loop->index }}">
                                                            <label class="form-check-label text-danger" for="remove_{{ $loop->index }}">
                                                                <i class="fas fa-trash"></i> Xóa
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">Chưa có ảnh minh chứng</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="damage_images">Thêm ảnh minh chứng mới</label>
                                        <input type="file" name="damage_images[]" id="damage_images" 
                                               class="form-control @error('damage_images.*') is-invalid @enderror" 
                                               multiple accept="image/*">
                                        @error('damage_images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Có thể upload nhiều ảnh (tối đa 5MB/ảnh)</small>
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
                                                  placeholder="Ghi chú về quá trình kiểm tra...">{{ old('inspection_notes', $fine->inspection_notes) }}</textarea>
                                        @error('inspection_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if($fine->inspected_by && $fine->inspected_at)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Kiểm tra bởi:</strong> {{ $fine->inspector->name ?? 'N/A' }}<br>
                                            <strong>Thời gian:</strong> {{ $fine->inspected_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật phạt
                        </button>
                        <a href="{{ route('admin.fines.show', $fine->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        
                        @if($fine->status === 'pending')
                            <div class="float-right">
                                <form method="POST" action="{{ route('admin.fines.mark-paid', $fine->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận đánh dấu đã thanh toán?')">
                                        <i class="fas fa-check"></i> Đánh dấu đã thanh toán
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.fines.waive', $fine->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-info" onclick="return confirm('Xác nhận miễn phạt?')">
                                        <i class="fas fa-gift"></i> Miễn phạt
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($fine->type === 'damaged_book' || $fine->type === 'lost_book')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview ảnh trước khi upload
    const damageImagesInput = document.getElementById('damage_images');
    const imagePreview = document.getElementById('image-preview');
    
    if (damageImagesInput) {
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
    }
});
</script>
@endif
@endsection
