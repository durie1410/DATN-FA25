@extends('layouts.app')

@section('title', 'Danh sách sách - Thư Viện Online')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book me-2"></i>Danh sách sách</h2>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" onclick="toggleView()">
            <i class="fas fa-th" id="viewIcon"></i>
        </button>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter me-1"></i>Lọc
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" id="searchForm">
            <div class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" 
                               class="form-control" placeholder="Tìm kiếm sách...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-sort"></i>
                        </span>
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            <option value="author_asc" {{ request('sort') == 'author_asc' ? 'selected' : '' }}>Tác giả A-Z</option>
                            <option value="author_desc" {{ request('sort') == 'author_desc' ? 'selected' : '' }}>Tác giả Z-A</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Results Info -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">
        Tìm thấy <strong>{{ $books->total() }}</strong> sách
        @if(request('keyword'))
            cho từ khóa "<strong>{{ request('keyword') }}</strong>"
        @endif
    </p>
    <div class="d-flex gap-2">
        <span class="badge bg-primary">{{ $books->count() }} hiển thị</span>
    </div>
</div>

<!-- Books Grid/List -->
<div id="booksContainer" class="row">
    @forelse($books as $book)
    <div class="col-6 col-md-4 col-lg-3 mb-4 book-item">
        <div class="card h-100 book-card">
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="height: 200px;">
                @if($book->hinh_anh)
                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" alt="{{ $book->ten_sach }}" class="img-fluid" style="max-height: 190px;">
                @else
                    <i class="fas fa-book text-muted" style="font-size: 4rem;"></i>
                @endif
                
                <!-- Book Status Badge -->
                <div class="position-absolute top-0 end-0 m-2">
                    @if($book->so_luong > 0)
                        <span class="badge bg-success">Có sẵn</span>
                    @else
                        <span class="badge bg-danger">Hết sách</span>
                    @endif
                </div>
            </div>
            
            <div class="card-body d-flex flex-column">
                <h6 class="card-title text-truncate" title="{{ $book->ten_sach }}">
                    {{ $book->ten_sach }}
                </h6>
                
                <p class="card-text text-muted small mb-2">
                    <i class="fas fa-user me-1"></i>{{ $book->tac_gia }}
                </p>
                
                <p class="card-text text-muted small mb-2">
                    <i class="fas fa-calendar me-1"></i>{{ $book->nam_xuat_ban }}
                </p>
                
                <p class="card-text text-muted small mb-3">
                    <i class="fas fa-tag me-1"></i>{{ $book->category->ten_the_loai ?? 'N/A' }}
                </p>
                
                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">
                            <i class="fas fa-layer-group me-1"></i>{{ $book->so_luong }} cuốn
                        </small>
                        @if($book->reviews_count > 0)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <small class="text-muted">{{ number_format($book->average_rating, 1) }}</small>
                        </div>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                        
                        @auth
                        @if($book->so_luong > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-search text-muted mb-3" style="font-size: 4rem;"></i>
            <h4 class="text-muted">Không tìm thấy sách nào</h4>
            <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác</p>
            <a href="{{ route('books.public') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-1"></i>Xem tất cả sách
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($books->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $books->appends(request()->query())->links() }}
</div>
@endif

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Bộ lọc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="GET" id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Thể loại</label>
                        <select name="category_id" class="form-select">
                            <option value="">Tất cả thể loại</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->ten_the_loai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Năm xuất bản</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="year_from" value="{{ request('year_from') }}" 
                                       class="form-control" placeholder="Từ năm" min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="year_to" value="{{ request('year_to') }}" 
                                       class="form-control" placeholder="Đến năm" min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tình trạng</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="available_only" value="1" 
                                   {{ request('available_only') ? 'checked' : '' }} id="availableOnly">
                            <label class="form-check-label" for="availableOnly">
                                Chỉ hiển thị sách có sẵn
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-outline-danger" onclick="clearFilters()">Xóa bộ lọc</button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">Áp dụng</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let isGridView = true;

    function toggleView() {
        const container = document.getElementById('booksContainer');
        const icon = document.getElementById('viewIcon');
        
        if (isGridView) {
            // Switch to list view
            container.classList.remove('row');
            container.classList.add('list-view');
            icon.classList.remove('fa-th');
            icon.classList.add('fa-list');
            
            // Update book items
            document.querySelectorAll('.book-item').forEach(item => {
                item.classList.remove('col-6', 'col-md-4', 'col-lg-3');
                item.classList.add('col-12', 'mb-3');
            });
        } else {
            // Switch to grid view
            container.classList.remove('list-view');
            container.classList.add('row');
            icon.classList.remove('fa-list');
            icon.classList.add('fa-th');
            
            // Update book items
            document.querySelectorAll('.book-item').forEach(item => {
                item.classList.remove('col-12');
                item.classList.add('col-6', 'col-md-4', 'col-lg-3');
            });
        }
        
        isGridView = !isGridView;
    }

    function applyFilters() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        
        // Preserve existing search parameters
        const existingParams = new URLSearchParams(window.location.search);
        for (let [key, value] of existingParams.entries()) {
            if (!params.has(key)) params.append(key, value);
        }
        
        window.location.href = '{{ route("books.public") }}?' + params.toString();
    }

    function clearFilters() {
        document.getElementById('filterForm').reset();
        const params = new URLSearchParams(window.location.search);
        params.delete('category_id');
        params.delete('year_from');
        params.delete('year_to');
        params.delete('available_only');
        
        window.location.href = '{{ route("books.public") }}?' + params.toString();
    }

    // Auto-submit search form on input change
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="keyword"]');
        const sortSelect = document.querySelector('select[name="sort"]');
        
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });
        
        sortSelect.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });
</script>

<style>
    .list-view .book-item {
        display: flex;
        flex-direction: row;
    }
    
    .list-view .book-card {
        flex-direction: row;
        height: auto;
    }
    
    .list-view .card-img-top {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
    }
    
    .list-view .card-body {
        flex: 1;
    }
    
    .book-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    @media (max-width: 768px) {
        .list-view .book-item {
            flex-direction: column;
        }
        
        .list-view .book-card {
            flex-direction: column;
        }
        
        .list-view .card-img-top {
            width: 100%;
            height: 150px;
        }
    }
</style>
@endsection
















