@extends('layouts.frontend')

@section('title', 'Thư Viện Online 4.0 - Test')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-4">
                <i class="fas fa-book text-primary"></i>
                Thư Viện Online 4.0
            </h1>
            
            <div class="row justify-content-center mb-5">
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h2 class="text-primary">{{ $stats['total_books'] }}</h2>
                            <p class="mb-0">Sách</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h2 class="text-success">{{ $stats['total_categories'] }}</h2>
                            <p class="mb-0">Thể loại</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h2 class="text-warning">{{ $stats['new_books'] }}</h2>
                            <p class="mb-0">Sách mới</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h2 class="text-info">{{ $stats['total_readers'] }}</h2>
                            <p class="mb-0">Độc giả</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-book"></i> Sách mới nhất</h5>
                        </div>
                        <div class="card-body">
                            @forelse($featuredBooks->take(3) as $book)
                            <div class="d-flex align-items-center mb-3">
                                @if($book->hinh_anh)
                                    <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                                         alt="{{ $book->ten_sach }}" 
                                         class="me-3" 
                                         style="width: 50px; height: 70px; object-fit: cover;">
                                @else
                                    <div class="me-3 bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 70px;">
                                        <i class="fas fa-book text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $book->ten_sach }}</h6>
                                    <small class="text-muted">{{ $book->tac_gia }}</small>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">Chưa có sách nào</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-tags"></i> Thể loại</h5>
                        </div>
                        <div class="card-body">
                            @forelse($categories->take(5) as $category)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $category->ten_the_loai }}</span>
                                <span class="badge bg-primary">{{ $category->books_count ?? 0 }}</span>
                            </div>
                            @empty
                            <p class="text-muted">Chưa có thể loại nào</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="/" class="btn btn-primary me-2">
                    <i class="fas fa-home"></i> Trang chủ hiện đại
                </a>
                <a href="/classic" class="btn btn-outline-primary me-2">
                    <i class="fas fa-history"></i> Trang chủ cũ
                </a>
                <a href="/categories" class="btn btn-success me-2">
                    <i class="fas fa-tags"></i> Thể loại
                </a>
                <a href="/books" class="btn btn-info">
                    <i class="fas fa-book"></i> Danh sách sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection













