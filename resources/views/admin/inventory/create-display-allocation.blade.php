@extends('layouts.admin')

@section('title', 'Tạo Phân Bổ Trưng Bày - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-plus"></i> Tạo Phân Bổ Trưng Bày Mới</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.inventory.display-allocations.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Sách <span class="text-danger">*</span></label>
                    <select name="book_id" id="book_id" class="form-control" required>
                        <option value="">-- Chọn sách --</option>
                        @foreach($books as $book)
                            @php
                                $stockCount = \App\Models\Inventory::where('book_id', $book->id)
                                    ->where('storage_type', 'Kho')
                                    ->where('status', 'Co san')
                                    ->count();
                            @endphp
                            <option value="{{ $book->id }}" data-stock="{{ $stockCount }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->ten_sach }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted" id="stock-info">Chọn sách để xem số lượng có sẵn trong kho</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số lượng trưng bày <span class="text-danger">*</span></label>
                    <input type="number" name="quantity_on_display" id="quantity" class="form-control" value="{{ old('quantity_on_display', 1) }}" min="1" required>
                    <small class="form-text text-muted" id="quantity-info"></small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Khu vực trưng bày <span class="text-danger">*</span></label>
                    <input type="text" name="display_area" class="form-control" value="{{ old('display_area') }}" placeholder="Ví dụ: Kệ A1, Gian trung tâm" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" name="display_start_date" class="form-control" value="{{ old('display_start_date', date('Y-m-d')) }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="date" name="display_end_date" class="form-control" value="{{ old('display_end_date') }}">
                    <small class="form-text text-muted">Để trống nếu không có ngày kết thúc</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.inventory.display-allocations') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Tạo Phân Bổ</button>
        </div>
    </form>
</div>

<script>
document.getElementById('book_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const stock = selectedOption ? selectedOption.dataset.stock : 0;
    document.getElementById('stock-info').textContent = `Số lượng có sẵn trong kho: ${stock}`;
    
    const quantityInput = document.getElementById('quantity');
    quantityInput.max = stock;
    if (quantityInput.value > stock) {
        quantityInput.value = stock;
    }
    document.getElementById('quantity-info').textContent = `Tối đa: ${stock} cuốn`;
});

// Trigger on page load if book is pre-selected
if (document.getElementById('book_id').value) {
    document.getElementById('book_id').dispatchEvent(new Event('change'));
}
</script>
@endsection

