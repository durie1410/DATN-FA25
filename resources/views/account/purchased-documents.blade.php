@extends('account._layout')

@section('title', 'Văn bản đã mua')
@section('breadcrumb', 'Văn bản đã mua')

@section('content')
<div class="account-section">
    <h2 class="section-title">Văn bản đã mua</h2>
    
    @if($documents->count() > 0)
        <div class="documents-grid">
            @foreach($documents as $item)
                <div class="document-card">
                    <div class="document-icon">📝</div>
                    <div class="document-info">
                        <h3 class="document-title">{{ $item->book_title }}</h3>
                        <p class="document-author">{{ $item->book_author }}</p>
                        <div class="document-meta">
                            <p><strong>Mã đơn hàng:</strong> {{ $item->order->order_number }}</p>
                            <p><strong>Ngày mua:</strong> {{ $item->order->created_at->format('d/m/Y') }}</p>
                            <p><strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                        </div>
                        @if($item->purchasableBook)
                            <a href="{{ route('books.show', $item->purchasableBook->id) }}" class="btn-view-document">Xem văn bản</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="pagination-wrapper">
            {{ $documents->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h3>Bạn chưa mua văn bản nào</h3>
            <p>Hãy khám phá và mua văn bản từ thư viện của chúng tôi!</p>
            <a href="{{ route('books.public') }}" class="btn-primary">Khám phá văn bản</a>
        </div>
    @endif
</div>
@endsection

