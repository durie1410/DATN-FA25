@extends('account._layout')

@section('title', 'Sách đã mua')
@section('breadcrumb', 'Sách đã mua')

@section('content')
<div class="account-section">
    <h2 class="section-title">Sách đã mua</h2>
    
    @if($orderItems->count() > 0)
        <div class="books-grid">
            @foreach($orderItems as $item)
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
                            <span class="book-price">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                            <span class="book-quantity">Số lượng: {{ $item->quantity }}</span>
                        </div>
                        <div class="book-order-info">
                            <p><strong>Mã đơn hàng:</strong> {{ $item->order->order_number }}</p>
                            <p><strong>Ngày mua:</strong> {{ $item->order->created_at->format('d/m/Y') }}</p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="status-badge status-{{ $item->order->status }}">
                                    @if($item->order->status === 'pending') Chờ xử lý
                                    @elseif($item->order->status === 'processing') Đang xử lý
                                    @elseif($item->order->status === 'shipped') Đã gửi hàng
                                    @elseif($item->order->status === 'delivered') Đã giao hàng
                                    @elseif($item->order->status === 'cancelled') Đã hủy
                                    @else {{ $item->order->status }}
                                    @endif
                                </span>
                            </p>
                        </div>
                        @if($item->purchasableBook)
                            <a href="{{ route('books.show', $item->purchasableBook->id) }}" class="btn-view-book">Xem chi tiết</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="pagination-wrapper">
            {{ $orderItems->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Bạn chưa mua sách nào</h3>
            <p>Hãy khám phá và mua sách từ thư viện của chúng tôi!</p>
            <a href="{{ route('books.public') }}" class="btn-primary">Khám phá sách</a>
        </div>
    @endif
</div>
@endsection

