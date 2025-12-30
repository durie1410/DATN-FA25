@extends('layouts.admin')

@section('title', 'Cho Mượn Sách Mới - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-plus"></i> phiếu mới</h3>

    <form action="{{ route('admin.borrows.store') }}" method="POST" id="borrowForm">
        @csrf

        {{-- Thông tin người mượn --}}
       <div class="mb-3">
                    <label class="form-label">Độc giả <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="text" id="readerSearch" class="form-control" placeholder="Tìm kiếm độc giả..." autocomplete="off">
                        <input type="hidden" name="reader_id" id="readerId" required>
                        <div id="readerDropdown" class="dropdown-menu w-100" style="display:none; max-height:200px; overflow-y:auto;"></div>
                    </div>
                    <div id="selectedReader" class="mt-2" style="display:none;">
                        <div class="alert alert-info">
                            <strong>Đã chọn:</strong> <span id="readerName"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearReader()">Xóa</button>
                        </div>
                    </div>
                </div>

        {{-- Địa chỉ --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Tên người mượn</label>
                <input type="text" name="ten_nguoi_muon" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tỉnh / Thành</label>
                <input type="text" name="tinh_thanh" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Huyện / Quận</label>
                <input type="text" name="huyen" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Xã / Phường</label>
                <input type="text" name="xa" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Số nhà / Đường</label>
                <input type="text" name="so_nha" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" class="form-control">
            </div>
        </div>
        {{-- Chọn sách --}}
        <div class="row mb-3">
            {{-- <div class="col-md-6">
                <label class="form-label">Sách</label>
                <select name="book_id" class="form-control">
                    <option value="">-- Chọn sách --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->ten_sach }} ({{ $book->tac_gia }})</option>
                    @endforeach
                </select>
            </div> --}}

            <div class="col-md-6">
                <label class="form-label">Thủ thư</label>
                <select name="librarian_id" class="form-control">
                    <option value="">-- Chọn thủ thư --</option>
                    @foreach($librarians as $librarian)
                        <option value="{{ $librarian->id }}">{{ $librarian->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Ngày mượn và trạng thái --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Ngày mượn</label>
                <input type="date" name="ngay_muon" class="form-control" value="{{ now()->toDateString() }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Trạng thái</label>
                <select name="trang_thai" class="form-control">
                    <option value="Cho duyet">Chờ duyệt</option>
                    <option value="Dang muon">Đang mượn</option>
                    <option value="Da tra">Đã trả</option>
                    <option value="Qua han">Quá hạn</option>
                    <option value="Mat sach">Mất sách</option>
                    <option value="Huy">Hủy</option>
                </select>
            </div>
        </div>

        {{-- Ghi chú --}}
        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
        </div>

        {{-- Tiền --}}
        {{-- <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Tiền cọc (₫)</label>
                <input type="number" name="tien_coc" class="form-control" value="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tiền ship (₫)</label>
                <input type="number" name="tien_ship" class="form-control" value="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tiền thuê (₫)</label>
                <input type="number" name="tien_thue" class="form-control" value="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tổng tiền (₫)</label>
                <input type="number" name="tong_tien" class="form-control" value="0" readonly>
            </div>
        </div> --}}

        {{-- Voucher --}}
        {{-- <div class="mb-3">
            <label class="form-label">Voucher</label>
            <select name="voucher_id" class="form-control">
                <option value="">-- Không sử dụng --</option>
                @foreach($vouchers as $voucher)
                    <option value="{{ $voucher->id }}">{{ $voucher->code }} - Giảm {{ $voucher->discount }}%</option>
                @endforeach
            </select>
        </div> --}}

        {{-- Nút submit --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Cho mượn</button>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
  let readerTimeout, bookTimeout;

// --- Tìm kiếm độc giả ---
document.getElementById('readerSearch').addEventListener('input', function() {
    const query = this.value.trim();
    clearTimeout(readerTimeout);
    readerTimeout = setTimeout(() => {
        if(query.length >= 2) searchReaders(query);
        else hideReaderDropdown();
    }, 300);
});

function searchReaders(query) {
    fetch(`/admin/autocomplete/readers?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => showReaderDropdown(data))
        .catch(err => console.error(err));
}

function showReaderDropdown(readers) {
    const dropdown = document.getElementById('readerDropdown');
    dropdown.innerHTML = '';
    if(readers.length === 0) {
        dropdown.innerHTML = '<div class="dropdown-item text-muted">Không tìm thấy độc giả</div>';
    } else {
        readers.forEach(r => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.style.cursor = 'pointer';
            item.innerHTML = `<div class="fw-bold">${r.ho_ten}</div><small class="text-muted">Mã thẻ: ${r.so_the_doc_gia} | Email: ${r.email}</small>`;
            item.onclick = () => selectReader(r);
            dropdown.appendChild(item);
        });
    }
    dropdown.style.display = 'block';
}

function selectReader(reader) {
    document.getElementById('readerId').value = reader.id;
    document.getElementById('readerName').textContent = `${reader.ho_ten} (${reader.so_the_doc_gia})`;
    document.getElementById('readerSearch').value = '';
    document.getElementById('selectedReader').style.display = 'block';
    hideReaderDropdown();
        const bookId = document.getElementById('bookId').value;
    if (bookId) {
        const bookPriceText = document.getElementById('bookPrice').textContent.replace(/[^\d]/g, '');
        const bookPrice = parseInt(bookPriceText) || 0;
        // Tiền cọc = giá sách (1:1)
        const deposit = bookPrice;
        document.getElementById('depositInput').value = deposit;
    }

}

function clearReader() {
    document.getElementById('readerId').value = '';
    document.getElementById('selectedReader').style.display = 'none';
}

function hideReaderDropdown() { document.getElementById('readerDropdown').style.display = 'none'; }

// --- Tìm kiếm sách ---
document.getElementById('bookSearch').addEventListener('input', function() {
    const query = this.value.trim();
    clearTimeout(bookTimeout);
    bookTimeout = setTimeout(() => {
        if(query.length >= 2) searchBooks(query);
        else hideBookDropdown();
    }, 300);
});

function searchBooks(query) {
    fetch(`/admin/autocomplete/books?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => showBookDropdown(data))
        .catch(err => console.error(err));
}

function showBookDropdown(books) {
    const dropdown = document.getElementById('bookDropdown');
    dropdown.innerHTML = '';
    if(books.length === 0) {
        dropdown.innerHTML = '<div class="dropdown-item text-muted">Không tìm thấy sách</div>';
    } else {
        books.forEach(b => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.style.cursor = 'pointer';
            item.innerHTML = `<div class="fw-bold">${b.ten_sach}</div><small class="text-muted">Tác giả: ${b.tac_gia} | Năm: ${b.nam_xuat_ban}</small>`;
            item.onclick = () => selectBook(b);
            dropdown.appendChild(item);
        });
    }
    dropdown.style.display = 'block';
}

function selectBook(book) {
    document.getElementById('bookId').value = book.id;
    document.getElementById('bookName').innerHTML = `
        <div class="fw-bold">${book.ten_sach}</div>
        <small class="text-muted">Tác giả: ${book.tac_gia}</small><br>
        <small>Xuất bản: ${book.nam_xuat_ban}</small>
    `;

    // --- Xử lý giá sách ---
    let price = 0;
    if (book.gia !== undefined && book.gia !== null) {
        price = Number(String(book.gia).replace(/,/g, ''));
        if (isNaN(price)) price = 0;
    }
    document.getElementById('bookPrice').textContent = price.toLocaleString('vi-VN') + '₫';

    // --- Xác định tiền cọc ---
    // Tiền cọc = giá sách (1:1) cho tất cả trường hợp
    const deposit = price;
    document.getElementById('depositInput').value = deposit;

    // --- Tiền ship để trống, người dùng nhập tay ---
    document.getElementById('shipInput').value = '';

    document.getElementById('bookSearch').value = '';
    document.getElementById('selectedBook').style.display = 'block';
    hideBookDropdown();
}

function clearBook() {
    document.getElementById('bookId').value = '';
    document.getElementById('selectedBook').style.display = 'none';
    document.getElementById('bookPrice').textContent = '0₫';
    document.getElementById('depositInput').value = '';
    document.getElementById('shipInput').value = '';
}


function clearBook() {
    document.getElementById('bookId').value = '';
    document.getElementById('selectedBook').style.display = 'none';
    document.getElementById('bookPrice').textContent = '0₫';
    document.getElementById('depositPrice').textContent = '0₫';
    document.getElementById('shipPrice').textContent = '0₫';
}

function hideBookDropdown() { document.getElementById('bookDropdown').style.display = 'none'; }

// Click ngoài dropdown đóng
document.addEventListener('click', function(e){
    if(!e.target.closest('#readerSearch') && !e.target.closest('#readerDropdown')) hideReaderDropdown();
    if(!e.target.closest('#bookSearch') && !e.target.closest('#bookDropdown')) hideBookDropdown();
});
    
</script>
@endpush


