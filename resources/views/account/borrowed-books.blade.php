@extends('account._layout')

@section('title', 'Sách đang mượn')
@section('breadcrumb', 'Sách đang mượn')

@section('content')
<div class="account-section">
    <h2 class="section-title">Sách đang mượn</h2>
    
    @if(!$reader)
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h3>Bạn chưa đăng ký làm độc giả</h3>
            <p>Vui lòng đăng ký làm độc giả để có thể mượn sách từ thư viện!</p>
            <a href="{{ route('account.register-reader') }}" class="btn-primary">Đăng ký độc giả</a>
        </div>
    @elseif($borrows->total() > 0)
        <div class="books-grid">
            @foreach($borrows as $borrow)
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
                            <p><strong>Hạn trả:</strong> 
                                <span class="{{ $borrow->isOverdue() ? 'text-danger' : '' }}">
                                    {{ $borrow->ngay_hen_tra ? $borrow->ngay_hen_tra->format('d/m/Y') : 'Chưa xác định' }}
                                </span>
                            </p>
                            @if($borrow->isOverdue())
                                <p class="text-danger"><strong>Quá hạn:</strong> {{ $borrow->days_overdue }} ngày</p>
                            @endif
                            @if($borrow->so_lan_gia_han > 0)
                                <p><strong>Số lần gia hạn:</strong> {{ $borrow->so_lan_gia_han }}/2</p>
                            @endif
                        </div>
                        <div class="book-borrow-info">
                            @if($borrow->librarian)
                                <p><strong>Thủ thư:</strong> {{ $borrow->librarian->name }}</p>
                            @endif
                            @if($borrow->ghi_chu)
                                <p><strong>Ghi chú:</strong> {{ $borrow->ghi_chu }}</p>
                            @endif
                        </div>
                        @if($borrow->book)
                            <a href="{{ route('books.show', $borrow->book->id) }}" class="btn-view-book">Xem chi tiết</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="pagination-wrapper">
            {{ $borrows->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Bạn chưa mượn sách nào</h3>
            <p>Hãy khám phá và mượn sách từ thư viện của chúng tôi!</p>
            <a href="{{ route('books.public') }}" class="btn-primary">Khám phá sách</a>
        </div>
    @endif
</div>
@endsection

