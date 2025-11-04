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

                <form action="{{ route('admin.fines.store') }}" method="POST">
                    @csrf
                    <div class="fines-filter">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrow_id">Phiếu mượn <span class="text-danger">*</span></label>
                                    <select name="borrow_id" id="borrow_id" class="form-control @error('borrow_id') is-invalid @enderror" required>
                                        <option value="">Chọn phiếu mượn</option>
                                        @foreach($borrows as $borrow)
                                            <option value="{{ $borrow->id }}" 
                                                {{ old('borrow_id', $borrow->id ?? '') == $borrow->id ? 'selected' : '' }}
                                                data-reader="{{ $borrow->reader->ho_ten }}"
                                                data-book="{{ $borrow->book->ten_sach }}">
                                                #{{ $borrow->id }} - {{ $borrow->reader->ho_ten }} - {{ $borrow->book->ten_sach }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('borrow_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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

                        <!-- Thông tin độc giả và sách -->
                        <div class="row" id="borrow-info" style="display: none;">
                            <div class="col-md-12">
                                <div class="fines-alert alert-info">
                                    <h5><i class="fas fa-info-circle"></i> Thông tin phiếu mượn</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Độc giả:</strong> <span id="reader-name"></span><br>
                                            <strong>Mã số thẻ:</strong> <span id="reader-code"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Sách:</strong> <span id="book-name"></span><br>
                                            <strong>Mã sách:</strong> <span id="book-code"></span>
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
    const borrowSelect = document.getElementById('borrow_id');
    const borrowInfo = document.getElementById('borrow-info');
    
    // Lấy dữ liệu phiếu mượn từ server
    const borrows = @json($borrows);
    
    borrowSelect.addEventListener('change', function() {
        const selectedBorrowId = this.value;
        
        if (selectedBorrowId) {
            const borrow = borrows.find(b => b.id == selectedBorrowId);
            if (borrow) {
                document.getElementById('reader-name').textContent = borrow.reader.ho_ten;
                document.getElementById('reader-code').textContent = borrow.reader.ma_so_the;
                document.getElementById('book-name').textContent = borrow.book.ten_sach;
                document.getElementById('book-code').textContent = borrow.book.ma_sach;
                borrowInfo.style.display = 'block';
            }
        } else {
            borrowInfo.style.display = 'none';
        }
    });
    
    // Tự động điền thông tin nếu có borrow_id từ URL
    @if(isset($borrow) && $borrow)
        borrowSelect.value = {{ $borrow->id }};
        borrowSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>
@endsection
