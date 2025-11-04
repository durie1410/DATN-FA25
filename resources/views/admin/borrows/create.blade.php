@extends('layouts.admin')

@section('title', 'Cho Mượn Sách Mới - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-plus"></i> Cho mượn sách mới</h3>
    
    <form action="{{ route('admin.borrows.store') }}" method="POST" id="borrowForm">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Độc giả <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="text" id="readerSearch" class="form-control" placeholder="Tìm kiếm tác giả..." autocomplete="off">
                        <input type="hidden" name="reader_id" id="readerId" required>
                        <div id="readerDropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                    </div>
                    <div id="selectedReader" class="mt-2" style="display: none;">
                        <div class="alert alert-info">
                            <strong>Đã chọn:</strong> <span id="readerName"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearReader()">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Sách <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="text" id="bookSearch" class="form-control" placeholder="Tìm kiếm sách..." autocomplete="off">
                        <input type="hidden" name="book_id" id="bookId" required>
                        <div id="bookDropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                    </div>
                    <div id="selectedBook" class="mt-2" style="display: none;">
                        <div class="alert alert-info">
                            <strong>Đã chọn:</strong> <span id="bookName"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearBook()">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Thủ thư</label>
                    <select name="librarian_id" class="form-control">
                        <option value="">-- Chọn thủ thư --</option>
                        @foreach($librarians as $librarian)
                            <option value="{{ $librarian->id }}" {{ auth()->id() == $librarian->id ? 'selected' : '' }}>{{ $librarian->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày mượn <span class="text-danger">*</span></label>
                    <input type="date" name="ngay_muon" value="{{ now()->toDateString() }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Hạn trả <span class="text-danger">*</span></label>
                    <input type="date" name="ngay_hen_tra" value="{{ now()->addDays(14)->toDateString() }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Ghi chú thêm..."></textarea>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cho mượn
            </button>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let readerTimeout, bookTimeout;

// Tìm kiếm tác giả
document.getElementById('readerSearch').addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(readerTimeout);
    readerTimeout = setTimeout(() => {
        if (query.length >= 2) {
            searchReaders(query);
        } else {
            hideReaderDropdown();
        }
    }, 300);
});

// Tìm kiếm sách
document.getElementById('bookSearch').addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(bookTimeout);
    bookTimeout = setTimeout(() => {
        if (query.length >= 2) {
            searchBooks(query);
        } else {
            hideBookDropdown();
        }
    }, 300);
});

function searchReaders(query) {
    fetch(`/admin/autocomplete/readers?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            showReaderDropdown(data);
        })
        .catch(error => console.error('Error:', error));
}

function searchBooks(query) {
    fetch(`/admin/autocomplete/books?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            showBookDropdown(data);
        })
        .catch(error => console.error('Error:', error));
}

function showReaderDropdown(readers) {
    const dropdown = document.getElementById('readerDropdown');
    dropdown.innerHTML = '';
    
    if (readers.length === 0) {
        dropdown.innerHTML = '<div class="dropdown-item text-muted">Không tìm thấy tác giả</div>';
    } else {
        readers.forEach(reader => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.style.cursor = 'pointer';
            item.innerHTML = `
                <div class="fw-bold">${reader.ho_ten}</div>
                <small class="text-muted">Mã thẻ: ${reader.so_the_doc_gia} | Email: ${reader.email}</small>
            `;
            item.addEventListener('click', () => selectReader(reader));
            dropdown.appendChild(item);
        });
    }
    
    dropdown.style.display = 'block';
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
            item.innerHTML = `
                <div class="fw-bold">${book.ten_sach}</div>
                <small class="text-muted">Tác giả: ${book.tac_gia} | Năm: ${book.nam_xuat_ban}</small>
            `;
            item.addEventListener('click', () => selectBook(book));
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
}

function selectBook(book) {
    document.getElementById('bookId').value = book.id;
    document.getElementById('bookName').textContent = `${book.ten_sach} - ${book.tac_gia}`;
    document.getElementById('bookSearch').value = '';
    document.getElementById('selectedBook').style.display = 'block';
    hideBookDropdown();
}

function clearReader() {
    document.getElementById('readerId').value = '';
    document.getElementById('selectedReader').style.display = 'none';
}

function clearBook() {
    document.getElementById('bookId').value = '';
    document.getElementById('selectedBook').style.display = 'none';
}

function hideReaderDropdown() {
    document.getElementById('readerDropdown').style.display = 'none';
}

function hideBookDropdown() {
    document.getElementById('bookDropdown').style.display = 'none';
}

// Ẩn dropdown khi click bên ngoài
document.addEventListener('click', function(e) {
    if (!e.target.closest('#readerSearch') && !e.target.closest('#readerDropdown')) {
        hideReaderDropdown();
    }
    if (!e.target.closest('#bookSearch') && !e.target.closest('#bookDropdown')) {
        hideBookDropdown();
    }
});
</script>
@endpush

