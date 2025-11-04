@extends('layouts.admin')

@section('title', 'Sửa Phiếu Mượn - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-edit"></i> Sửa phiếu mượn</h3>
    
    <form action="{{ route('admin.borrows.update', $borrow->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Độc giả</label>
                    <select name="reader_id" class="form-control" required>
                        @foreach($readers as $reader)
                            <option value="{{ $reader->id }}" {{ $borrow->reader_id == $reader->id ? 'selected' : '' }}>
                                {{ $reader->ho_ten }} ({{ $reader->so_the_doc_gia }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Sách</label>
                    <select name="book_id" class="form-control" required>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ $borrow->book_id == $book->id ? 'selected' : '' }}>
                                {{ $book->ten_sach }} - {{ $book->tac_gia }}
                            </option>
                        @endforeach
                    </select>
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
                            <option value="{{ $librarian->id }}" {{ $borrow->librarian_id == $librarian->id ? 'selected' : '' }}>
                                {{ $librarian->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày mượn</label>
                    <input type="date" name="ngay_muon" value="{{ $borrow->ngay_muon->format('Y-m-d') }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Hạn trả</label>
                    <input type="date" name="ngay_hen_tra" value="{{ $borrow->ngay_hen_tra->format('Y-m-d') }}" class="form-control" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Ngày trả thực tế</label>
                    <input type="date" name="ngay_tra_thuc_te" value="{{ $borrow->ngay_tra_thuc_te ? $borrow->ngay_tra_thuc_te->format('Y-m-d') : '' }}" class="form-control">
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-control" required>
                        <option value="Dang muon" {{ $borrow->trang_thai == 'Dang muon' ? 'selected' : '' }}>Đang mượn</option>
                        <option value="Da tra" {{ $borrow->trang_thai == 'Da tra' ? 'selected' : '' }}>Đã trả</option>
                        <option value="Qua han" {{ $borrow->trang_thai == 'Qua han' ? 'selected' : '' }}>Quá hạn</option>
                        <option value="Mat sach" {{ $borrow->trang_thai == 'Mat sach' ? 'selected' : '' }}>Mất sách</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control" rows="3">{{ $borrow->ghi_chu }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection



