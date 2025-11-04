@extends('layouts.staff')

@section('title', 'Quản Lý Đánh Giá - Nhân viên')

@section('content')
<div class="mb-4">
    <h3 class="mb-1">
        <i class="fas fa-star text-warning me-2"></i>
        Quản lý đánh giá
    </h3>
    <p class="text-muted mb-0">Phê duyệt và quản lý đánh giá từ độc giả</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Bảng danh sách -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Sách</th>
                        <th>Độc giả</th>
                        <th>Đánh giá</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td><strong>#{{ $review->id }}</strong></td>
                        <td>{{ $review->book->ten_sach ?? 'N/A' }}</td>
                        <td>{{ $review->reader->ho_ten ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($review->rating ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">({{ $review->rating ?? 0 }}/5)</span>
                            </div>
                            @if($review->comment)
                                <small class="text-muted d-block mt-1">{{ Str::limit($review->comment, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($review->approved == 1)
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-warning">Chờ duyệt</span>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($review->approved != 1)
                                    <form action="{{ route('staff.comments.approve', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Phê duyệt">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('staff.comments.reject', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" title="Từ chối" onclick="return confirm('Xác nhận từ chối đánh giá này?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Không có đánh giá nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($reviews, 'links'))
            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

