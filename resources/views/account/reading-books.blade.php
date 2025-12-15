@extends('account._layout')

@section('title', 'Sách đang đọc')
@section('breadcrumb', 'Sách đang đọc')

@section('content')
<div class="account-section">
    <h2 class="section-title">Sách đang đọc</h2>
    
    @if($borrowedBooks->count() > 0 || $purchasedBooks->count() > 0)
        @if($borrowedBooks->count() > 0)
            <div class="section-subtitle">
                <h3>Sách đang mượn ({{ $borrowedBooks->count() }})</h3>
            </div>
            <div class="books-grid">
                @foreach($borrowedBooks as $borrow)
                    <div class="book-card">
                        <div class="book-image">
                            @if($borrow->book && $borrow->book->hinh_anh)
                                <img src="{{ asset('storage/' . $borrow->book->hinh_anh) }}" alt="{{ $borrow->book->ten_sach }}">
                            @else
                                <div class="book-placeholder">📖</div>
                            @endif
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $borrow->book->ten_sach ?? 'N/A' }}</h3>
                            <p class="book-author">{{ $borrow->book->tac_gia ?? '' }}</p>
                            <div class="book-meta">
                                <p><strong>Ngày mượn:</strong> {{ $borrow->ngay_muon ? $borrow->ngay_muon->format('d/m/Y') : $borrow->created_at->format('d/m/Y') }}</p>
                                <p><strong>Hạn trả:</strong> {{ $borrow->ngay_hen_tra ? $borrow->ngay_hen_tra->format('d/m/Y') : 'Chưa xác định' }}</p>
                            </div>
                            @if($borrow->book)
                                <a href="{{ route('books.show', $borrow->book->id) }}" class="btn-view-book">Xem chi tiết</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if($purchasedBooks->count() > 0)
            <div class="section-subtitle" style="margin-top: 30px;">
                <h3>Sách đã mua có thể đọc ({{ $purchasedBooks->count() }})</h3>
            </div>
            <div class="books-grid">
                @foreach($purchasedBooks as $item)
                    <div class="book-card">
                        <div class="book-image">
                            @if($item->purchasableBook && $item->purchasableBook->hinh_anh)
                                <img src="{{ asset('storage/' . $item->purchasableBook->hinh_anh) }}" alt="{{ $item->book_title }}">
                            @else
                                <div class="book-placeholder">📖</div>
                            @endif
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $item->book_title }}</h3>
                            <p class="book-author">{{ $item->book_author }}</p>
                            <div class="book-meta">
                                <p><strong>Ngày mua:</strong> {{ $item->created_at->format('d/m/Y') }}</p>
                            </div>
                            @if($item->purchasableBook)
                                <a href="{{ route('books.show', $item->purchasableBook->id) }}" class="btn-view-book">Đọc sách</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Bạn chưa có sách nào đang đọc</h3>
            <p>Hãy mượn hoặc mua sách để bắt đầu đọc!</p>
            <a href="{{ route('books.public') }}" class="btn-primary">Khám phá sách</a>
        </div>
    @endif
</div>
@endsection

