@extends('layouts.admin')

@section('title', 'Quản Lý Sách - WAKA Admin')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-book-open"></i>
            Quản lý sách
        </h1>
        <p class="page-subtitle">Quản lý và theo dõi tất cả sách trong thư viện</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Thêm sách mới
    </a>
</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-search"></i>
            Tìm kiếm & Lọc
        </h3>
    </div>
    <form action="{{ route('admin.books.index') }}" method="GET" style="padding: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 250px;">
            <input type="text" 
                   name="keyword" 
                   value="{{ request('keyword') }}" 
                   class="form-control" 
                   placeholder="Tìm theo tên sách hoặc tác giả...">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <select name="category_id" class="form-select">
                <option value="">-- Tất cả thể loại --</option>
                @foreach($categories as $cate)
                    <option value="{{ $cate->id }}" {{ request('category_id') == $cate->id ? 'selected' : '' }}>
                        {{ $cate->ten_the_loai }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i>
            Lọc
        </button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i>
            Reset
        </a>
    </form>
</div>

<!-- Books List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Danh sách sách
        </h3>
        <span class="badge badge-info">Tổng: {{ $books->total() }} sách</span>
    </div>
    
    @if($books->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Thông tin sách</th>
                        <th>Tác giả</th>
                        <th>Giá</th>
                        <th>Định dạng</th>
                        <th>Trạng thái</th>
                        <th>Loại</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <span class="badge badge-info">{{ $book->id }}</span>
                            </td>
                            <td>
                                @if($book->hinh_anh)
                                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                                         width="50" 
                                         height="70" 
                                         style="object-fit: cover; border-radius: 8px; border: 1px solid rgba(0, 255, 153, 0.2);"
                                         alt="{{ $book->ten_sach }}">
                                @else
                                    <div style="width: 50px; height: 70px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0, 255, 153, 0.2);">
                                        <i class="fas fa-book" style="color: #666;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="max-width: 300px;">
                                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 5px;">
                                        {{ $book->ten_sach }}
                                    </div>
                                    <div style="font-size: 12px; color: #888; margin-bottom: 3px;">
                                        <i class="fas fa-tags"></i>
                                        {{ $book->category->ten_the_loai ?? 'N/A' }} • {{ $book->nam_xuat_ban }}
                                    </div>
                                    @if($book->mo_ta)
                                        <div style="font-size: 12px; color: #666;">
                                            {{ Str::limit($book->mo_ta, 60) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td style="color: var(--primary-color);">{{ $book->tac_gia }}</td>
                            <td>
                                <span class="badge badge-success">{{ $book->formatted_price }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $book->dinh_dang ?? 'Sách giấy' }}</span>
                            </td>
                            <td>
                                @if($book->trang_thai === 'active')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i>
                                        Tạm dừng
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(0, 255, 153, 0.2); color: var(--primary-color);">
                                    <i class="fas fa-hand-holding"></i>
                                    Mượn
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="{{ route('admin.books.show', $book->id) }}" 
                                       class="btn btn-sm btn-secondary" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px;">
            {{ $books->appends(request()->query())->links('vendor.pagination.admin') }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0, 255, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-book" style="font-size: 36px; color: var(--primary-color);"></i>
            </div>
            <h3 style="color: var(--text-primary); margin-bottom: 10px;">Chưa có sách nào</h3>
            <p style="color: #888; margin-bottom: 25px;">Hãy thêm sách đầu tiên để bắt đầu quản lý thư viện của bạn.</p>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Thêm sách đầu tiên
            </a>
        </div>
    @endif
</div>
@endsection
