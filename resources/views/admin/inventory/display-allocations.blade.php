@extends('layouts.admin')

@section('title', 'Phân Bổ Trưng Bày - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fas fa-store"></i> Phân Bổ Trưng Bày</h3>
        <a href="{{ route('admin.inventory.display-allocations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Phân Bổ Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bộ lọc -->
    <form method="GET" action="{{ route('admin.inventory.display-allocations') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="book_id" class="form-control">
                    <option value="">-- Tất cả sách --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->ten_sach }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="display_area" class="form-control" value="{{ request('display_area') }}" placeholder="Khu vực trưng bày">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">-- Tất cả --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sách</th>
                    <th>Khu vực trưng bày</th>
                    <th>Số lượng trưng bày</th>
                    <th>Số lượng trong kho</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Người phân bổ</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allocations as $allocation)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $allocation->book->ten_sach }}</strong>
                                <br>
                                <small class="text-muted">{{ $allocation->book->tac_gia ?? 'N/A' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                <i class="fas fa-map-marker-alt"></i> {{ $allocation->display_area }}
                            </span>
                        </td>
                        <td>
                            <strong style="font-size: 18px; color: var(--primary-color);">
                                {{ $allocation->quantity_on_display }}
                            </strong>
                            <small class="text-muted d-block">cuốn</small>
                        </td>
                        <td>
                            <strong style="font-size: 18px; color: #28a745;">
                                {{ $allocation->quantity_in_stock }}
                            </strong>
                            <small class="text-muted d-block">cuốn</small>
                        </td>
                        <td>{{ $allocation->display_start_date ? $allocation->display_start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $allocation->display_end_date ? $allocation->display_end_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div>
                                <i class="fas fa-user"></i> {{ $allocation->allocator->name }}
                                <br>
                                <small class="text-muted">{{ $allocation->created_at->format('d/m/Y') }}</small>
                            </div>
                        </td>
                        <td>
                            @if($allocation->isActive())
                                <span class="badge badge-success">Đang hoạt động</span>
                            @else
                                <span class="badge badge-secondary">Đã kết thúc</span>
                            @endif
                        </td>
                        <td>
                            @if($allocation->isActive() && $allocation->quantity_on_display > 0)
                                <button type="button" class="btn btn-sm btn-warning" onclick="returnFromDisplay({{ $allocation->id }})">
                                    <i class="fas fa-undo"></i> Thu hồi
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Không có phân bổ trưng bày nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $allocations->links() }}
</div>

<!-- Modal thu hồi sách -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="returnForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thu hồi sách từ trưng bày về kho</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Vị trí kho nhận sách về: <span class="text-danger">*</span></label>
                        <input type="text" name="return_location" class="form-control" placeholder="Ví dụ: Kệ A1, Tầng 2" required>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú:</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Thu hồi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function returnFromDisplay(id) {
    document.getElementById('returnForm').action = '{{ url("admin/inventory-display-allocations") }}/' + id + '/return';
    $('#returnModal').modal('show');
}
</script>
@endsection

