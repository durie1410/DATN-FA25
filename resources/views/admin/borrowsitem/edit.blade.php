@extends('layouts.admin')

@section('title', 'Sửa Chi Tiết Mượn Sách')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-edit"></i> Sửa chi tiết mượn sách</h3>

    <form action="{{ route('admin.borrowitems.update', $item->id) }}" method="POST" id="borrowForm">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- KIỂU MƯỢN --}}
            <div class="col-md-6">
    

                {{-- TÌM KIẾM SÁCH --}}
                <label class="form-label mt-2">Sách <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <input type="text" id="bookSearch" class="form-control" placeholder="Tìm kiếm sách..." autocomplete="off">
                    <input type="hidden" name="book_id" id="bookId" value="{{ $item->book_id }}" required>
                    <div id="bookDropdown" class="dropdown-menu w-100" style="display:none; max-height:200px; overflow-y:auto;"></div>
                </div>

                {{-- THÔNG TIN SÁCH ĐÃ CHỌN --}}
                <div id="selectedBook" class="mt-2" style="display:block;">
                    <div class="alert alert-info">
                        <strong>Đã chọn:</strong>
                        <span id="bookName">
                            <div class="fw-bold">{{ $item->book->ten_sach }}</div>
                            <small class="text-muted">Tác giả: {{ $item->book->tac_gia }}</small><br>
                            <small>Năm xuất bản: {{ $item->book->nam_xuat_ban }}</small>
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearBook()">Xóa</button>

                        <div class="mt-2">
                            <p>Giá sách: <span id="bookPrice">{{ number_format($item->book->gia) }}₫</span></p>

                            {{-- TIỀN THUÊ --}}
                            <div class="mb-2">
                                <label for="tienThueInput" class="form-label">Tiền thuê (₫)</label>
                                <input type="number" name="tien_thue" id="tienThueInput" class="form-control" value="{{ $item->tien_thue }}">
                            </div>

                            {{-- TIỀN CỌC --}}
                            <div class="mb-2">
                                <label for="depositInput" class="form-label">Tiền cọc (₫)</label>
                                <input type="number" name="tien_coc" id="depositInput" class="form-control" value="{{ $item->tien_coc }}">
                            </div>

                            {{-- TIỀN SHIP --}}
                            <div class="mb-2">
                                <label for="shipInput" class="form-label">Tiền ship (₫)</label>
                                <input type="number" name="tien_ship" id="shipInput" class="form-control" value="{{ $item->tien_ship }}">
                            </div>

                            {{-- TRẠNG THÁI CỌC --}}
                            <div class="mb-2">
                                <label for="trangThaiCoc" class="form-label">Trạng thái tiền cọc</label>
                                <select name="trang_thai_coc" id="trangThaiCoc" class="form-control">
                                    <option value="cho_xu_ly" {{ $item->trang_thai_coc == 'cho_xu_ly' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="da_thu" {{ $item->trang_thai_coc == 'da_thu' ? 'selected' : '' }}>Đã thu</option>
                                    <option value="da_hoan" {{ $item->trang_thai_coc == 'da_hoan' ? 'selected' : '' }}>Đã hoàn</option>
                                </select>
                            </div>

                            {{-- TỔNG TIỀN --}}
                            <div class="mt-3 border-top pt-2">
                                <p class="fw-bold">
                                    Tổng tiền: <span id="tongTien" class="text-success">
                                        {{ number_format($item->tien_thue + $item->tien_coc + $item->tien_ship) }}₫
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NGÀY MƯỢN, HẸN TRẢ, TRẠNG THÁI, GHI CHÚ --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày mượn <span class="text-danger">*</span></label>
                    <input type="date" name="ngay_muon" value="{{ $item->ngay_muon ? $item->ngay_muon->format('Y-m-d') : now()->toDateString() }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hạn trả <span class="text-danger">*</span></label>
                    <input type="date" name="ngay_hen_tra" value="{{ $item->ngay_hen_tra ? $item->ngay_hen_tra->format('Y-m-d') : now()->addDays(14)->toDateString() }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-control">
                        @foreach(['Cho duyet','Chua nhan','Dang muon','Da tra','Qua han','Mat sach'] as $status)
                            <option value="{{ $status }}" {{ $item->trang_thai == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control" rows="6" placeholder="Ghi chú thêm...">{{ $item->ghi_chu }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.borrows.show', $item->borrow_id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let selectedBook = {
    id: {{ $item->book->id }},
    ten_sach: "{{ $item->book->ten_sach }}",
    tac_gia: "{{ $item->book->tac_gia }}",
    nam_xuat_ban: "{{ $item->book->nam_xuat_ban }}",
    gia: {{ $item->book->gia }}
};
let bookTimeout;

// --- TÌM KIẾM SÁCH ---
document.getElementById('bookSearch').addEventListener('input', function() {
    const query = this.value.trim();
    clearTimeout(bookTimeout);
    bookTimeout = setTimeout(() => {
        if (query.length >= 2) searchBooks(query);
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
    if (books.length === 0) {
        dropdown.innerHTML = '<div class="dropdown-item text-muted">Không tìm thấy sách</div>';
    } else {
        books.forEach(book => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.style.cursor = 'pointer';
            item.innerHTML = `<div class="fw-bold">${book.ten_sach}</div>
                              <small class="text-muted">Tác giả: ${book.tac_gia} | Năm: ${book.nam_xuat_ban}</small>`;
            item.addEventListener('click', () => selectBook(book));
            dropdown.appendChild(item);
        });
    }
    dropdown.style.display = 'block';
}

function selectBook(book) {
    selectedBook = book;
    document.getElementById('bookId').value = book.id;
    document.getElementById('bookName').innerHTML = `
        <div class="fw-bold">${book.ten_sach}</div>
        <small class="text-muted">Tác giả: ${book.tac_gia}</small><br>
        <small>Xuất bản: ${book.nam_xuat_ban}</small>`;
    document.getElementById('selectedBook').style.display = 'block';
    document.getElementById('bookSearch').value = '';
    hideBookDropdown();
    updateMoneyFields();
}

document.getElementById('kieuMuon').addEventListener('change', function() {
    if (selectedBook) updateMoneyFields();
});

function updateMoneyFields() {
    const book = selectedBook;
    if (!book) return;
    let price = Number(book.gia || 0);
    document.getElementById('bookPrice').textContent = price.toLocaleString('vi-VN') + '₫';
    const hasCard = {{ $item->borrow->reader_id ? 'true' : 'false' }};
    const borrowType = document.getElementById('kieuMuon').value;
    let tienThue = 0, tienCoc = 0, tienShip = 0;
    if (hasCard && borrowType === 'tai_cho') {
        tienThue = 0; tienCoc = 0; tienShip = 0;
    } else if (hasCard && borrowType === 'mang_ve') {
        tienThue = 0; tienCoc = Math.round(price * 0.2); tienShip = 10000;
    } else if (!hasCard && borrowType === 'tai_cho') {
        tienThue = Math.round(price * 0.2); tienCoc = 0; tienShip = 0;
    } else if (!hasCard && borrowType === 'mang_ve') {
        tienThue = Math.round(price * 0.2); tienCoc = price; tienShip = 10000;
    }
    setInputDisplay('tienThueInput', tienThue);
    setInputDisplay('depositInput', tienCoc);
    setInputDisplay('shipInput', tienShip);
    updateTotal(tienThue, tienCoc, tienShip);
}

function updateTotal(thue, coc, ship) {
    const total = Number(thue) + Number(coc) + Number(ship);
    document.getElementById('tongTien').textContent = total.toLocaleString('vi-VN') + '₫';
}

function setInputDisplay(inputId, value) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (value === 0) {
        input.type = 'text'; input.value = 'Miễn phí'; input.readOnly = true;
    } else {
        input.type = 'number'; input.value = value; input.readOnly = false;
    }
}

document.getElementById('borrowForm').addEventListener('submit', function() {
    document.querySelectorAll('input').forEach(input => {
        if (input.value === 'Miễn phí') input.value = 0;
    });
});

function clearBook() {
    selectedBook = null;
    document.getElementById('bookId').value = '';
    document.getElementById('selectedBook').style.display = 'none';
    document.getElementById('bookPrice').textContent = '0₫';
}

function hideBookDropdown() {
    document.getElementById('bookDropdown').style.display = 'none';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('#bookSearch') && !e.target.closest('#bookDropdown')) hideBookDropdown();
});
</script>
@endpush
