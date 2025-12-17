@extends('layouts.admin')

@section('title', 'Quản Lý Đánh Giá - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-star"></i> Quản lý đánh giá sách</h3>
    </div>

    {{-- Form lọc --}}
    <form action="{{ route('admin.reviews.index') }}" method="GET" class="row mb-4">
        <div class="col-md-3">
            <select name="rating" class="form-control">
                <option value="">-- Tất cả rating --</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 sao)</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 sao)</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 sao)</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 sao)</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (1 sao)</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="verified" class="form-control">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Đã xác minh</option>
                <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Chưa xác minh</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Lọc</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Bảng danh sách --}}
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sách</th>
                    <th>Người đánh giá</th>
                    <th>Rating</th>
                    <th>Bình luận</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td><span class="badge bg-secondary fw-bold">{{ $review->id }}</span></td>
                    <td>
                        <strong>{{ $review->book->ten_sach }}</strong>
                        <br><small class="text-muted">{{ $review->book->tac_gia }}</small>
                    </td>
                    <td>{{ $review->user->name }}</td>
                    <td>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            <span class="ms-2">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td>
                        @if($review->comment)
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::limit($review->comment, 100) }}
                            </div>
                        @else
                            <span class="text-muted">Không có bình luận</span>
                        @endif
                    </td>
                    <td>
                        @if($review->is_verified)
                            <span class="badge bg-success">Đã xác minh</span>
                        @else
                            <span class="badge bg-warning">Chưa xác minh</span>
                        @endif
                    </td>
                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa đánh giá này?')">Xóa</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Không có đánh giá nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
.rating {
    display: flex;
    align-items: center;
}
</style>
@endsection
