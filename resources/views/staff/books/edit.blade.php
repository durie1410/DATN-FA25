@extends('layouts.staff')

@section('title', 'Chỉnh sửa sách - Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Chỉnh sửa sách</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin sách</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.books.update', $book->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="ten_sach" class="form-label">Tên sách</label>
                            <input type="text" class="form-control @error('ten_sach') is-invalid @enderror" 
                                   id="ten_sach" name="ten_sach" value="{{ old('ten_sach', $book->ten_sach) }}" required>
                            @error('ten_sach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tac_gia" class="form-label">Tác giả</label>
                            <input type="text" class="form-control @error('tac_gia') is-invalid @enderror" 
                                   id="tac_gia" name="tac_gia" value="{{ old('tac_gia', $book->tac_gia) }}" required>
                            @error('tac_gia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nam_xuat_ban" class="form-label">Năm xuất bản</label>
                            <input type="number" class="form-control @error('nam_xuat_ban') is-invalid @enderror" 
                                   id="nam_xuat_ban" name="nam_xuat_ban" value="{{ old('nam_xuat_ban', $book->nam_xuat_ban) }}" 
                                   min="1900" max="{{ date('Y') }}" required>
                            @error('nam_xuat_ban')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->ten_the_loai }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta', $book->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('staff.books.show', $book->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

