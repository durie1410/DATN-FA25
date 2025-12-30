@extends('layouts.admin')

@section('title', 'Quản lý phí phạt')

@section('content')
<link href="{{ asset('css/fines-management.css') }}" rel="stylesheet">

<div class="container-fluid fines-management">
    <!-- Header Section -->
    <div class="fines-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-exclamation-triangle"></i> Quản lý phí phạt</h1>
                    <p class="subtitle">Quản lý và theo dõi các khoản phạt của độc giả</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="fines-actions">
                        <a href="{{ route('admin.fines.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tạo phạt mới
                        </a>
                        <button type="button" class="btn btn-warning" onclick="createLateReturnFines()">
                            <i class="fas fa-clock"></i> Tạo phạt trả muộn
                        </button>
                        <a href="{{ route('admin.fines.report') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Báo cáo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Section -->
        <div class="fines-filter">
            <form method="GET">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tìm kiếm:</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Tên, mã số thẻ...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Trạng thái:</label>
                            <select name="status" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="waived" {{ request('status') == 'waived' ? 'selected' : '' }}>Đã miễn</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Loại phạt:</label>
                            <select name="type" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="late_return" {{ request('type') == 'late_return' ? 'selected' : '' }}>Trả muộn</option>
                                <option value="damaged_book" {{ request('type') == 'damaged_book' ? 'selected' : '' }}>Làm hỏng sách</option>
                                <option value="lost_book" {{ request('type') == 'lost_book' ? 'selected' : '' }}>Mất sách</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Độc giả:</label>
                            <select name="reader_id" class="form-control">
                                <option value="">Tất cả</option>
                                @foreach($readers as $reader)
                                    <option value="{{ $reader->id }}" {{ request('reader_id') == $reader->id ? 'selected' : '' }}>
                                        {{ $reader->ho_ten }} ({{ $reader->ma_so_the }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Sắp xếp:</label>
                            <select name="sort_by" class="form-control">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                                <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Số tiền</option>
                                <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Hạn thanh toán</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Lọc
                                </button>
                                <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-times"></i> Xóa lọc
                                </a>
                                @if(request('overdue'))
                                    <input type="hidden" name="overdue" value="1">
                                @endif
                                <button type="button" class="btn btn-danger btn-block" onclick="filterOverdue()">
                                    <i class="fas fa-exclamation-circle"></i> Chỉ quá hạn
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="fines-stats">
            <div class="fines-stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-label">Chưa thanh toán</div>
                <div class="stat-value">{{ $stats['pending'] ?? $fines->where('status', 'pending')->count() }}</div>
                <div class="stat-amount">{{ number_format(($stats['total_amount'] ?? $fines->where('status', 'pending')->sum('amount')), 0, ',', '.') }} VND</div>
            </div>
            <div class="fines-stat-card paid">
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-label">Đã thanh toán</div>
                <div class="stat-value">{{ $stats['paid'] ?? $fines->where('status', 'paid')->count() }}</div>
            </div>
            <div class="fines-stat-card overdue">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-label">Quá hạn</div>
                <div class="stat-value">{{ $stats['overdue'] ?? $fines->filter(function($fine) { return $fine->isOverdue(); })->count() }}</div>
            </div>
            <div class="fines-stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-label">Tổng số phạt</div>
                <div class="stat-value">{{ $stats['total'] ?? $fines->count() }}</div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="fines-table-container">
            <div class="table-responsive">
                <table class="table table-hover fines-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Độc giả</th>
                            <th>Sách</th>
                            <th>Loại phạt</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Hạn thanh toán</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fines as $fine)
                            <tr class="{{ $fine->isOverdue() ? 'table-danger' : '' }}">
                                <td><strong>#{{ $fine->id }}</strong></td>
                                <td>
                                    @if($fine->reader)
                                        <div>
                                            <strong>{{ $fine->reader->ho_ten }}</strong><br>
                                            <small class="text-muted">{{ $fine->reader->ma_so_the }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Reader ID: {{ $fine->reader_id }}</span><br>
                                        <small class="text-warning">Không tìm thấy độc giả</small>
                                    @endif
                                </td>
                                <td>
                                    @if($fine->borrowItem && $fine->borrowItem->book)
                                        <strong>{{ $fine->borrowItem->book->ten_sach }}</strong><br>
                                        <small class="text-muted">Mã: {{ $fine->borrowItem->book->ma_sach }}</small><br>
                                        <small class="text-info">Item #{{ $fine->borrowItem->id }}</small>
                                    @elseif($fine->borrow && $fine->borrow->borrowItems && $fine->borrow->borrowItems->count() > 0)
                                        @php
                                            $firstItem = $fine->borrow->borrowItems->first();
                                        @endphp
                                        @if($firstItem->book)
                                            <strong>{{ $firstItem->book->ten_sach }}</strong><br>
                                            <small class="text-muted">Mã: {{ $firstItem->book->ma_sach }}</small><br>
                                            <small class="text-warning">(Dữ liệu cũ - Phiếu #{{ $fine->borrow->id }})</small>
                                        @else
                                            <span class="text-muted">Phiếu #{{ $fine->borrow->id }}</span><br>
                                            <small class="text-warning">Chưa có thông tin sách</small>
                                        @endif
                                    @elseif($fine->borrow)
                                        <span class="text-muted">Phiếu #{{ $fine->borrow->id }}</span><br>
                                        <small class="text-warning">Chưa có thông tin sách</small>
                                    @else
                                        <span class="text-muted">Không có thông tin</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($fine->type)
                                        @case('late_return')
                                            <span class="fines-badge late-return">Trả muộn</span>
                                            @break
                                        @case('damaged_book')
                                            <span class="fines-badge damaged-book">Làm hỏng</span>
                                            @if($fine->damage_severity)
                                                <br><small class="badge badge-{{ $fine->damage_severity == 'nang' ? 'danger' : ($fine->damage_severity == 'trung_binh' ? 'warning' : 'info') }}">
                                                    {{ $fine->damage_severity_text }}
                                                </small>
                                            @endif
                                            @if($fine->hasDamageImages())
                                                <br><small class="text-info"><i class="fas fa-images"></i> Có ảnh</small>
                                            @endif
                                            @break
                                        @case('lost_book')
                                            <span class="fines-badge lost-book">Mất sách</span>
                                            @if($fine->hasDamageImages())
                                                <br><small class="text-info"><i class="fas fa-images"></i> Có ảnh</small>
                                            @endif
                                            @break
                                        @default
                                            <span class="fines-badge other">Khác</span>
                                    @endswitch
                                </td>
                                <td>
                                    <strong class="text-danger">{{ number_format($fine->amount, 0, ',', '.') }} VND</strong>
                                </td>
                                <td>
                                    @switch($fine->status)
                                        @case('pending')
                                            <span class="fines-badge pending">Chưa thanh toán</span>
                                            @if($fine->isOverdue())
                                                <br><small class="text-danger">Quá hạn {{ $fine->days_overdue }} ngày</small>
                                            @endif
                                            @break
                                        @case('paid')
                                            <span class="fines-badge paid">Đã thanh toán</span>
                                            @if($fine->paid_date)
                                                <br><small class="text-muted">{{ $fine->paid_date->format('d/m/Y') }}</small>
                                            @endif
                                            @break
                                        @case('waived')
                                            <span class="fines-badge waived">Đã miễn</span>
                                            @break
                                        @case('cancelled')
                                            <span class="fines-badge cancelled">Đã hủy</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
<img src="{{ asset('storage/' . $fine->img) }}" alt="Ảnh hoàn trả" style="max-width:120px; border-radius:6px;">


   
    
                                    {{-- {{ $fine->due_date->format('d/m/Y') }} --}}
                                    @if($fine->isOverdue())
                                        <br><small class="text-danger">Quá hạn</small>
                                    @endif    <td>{{ $fine->created_at->format('d/m/Y H:i') }}</td>
                                {{-- </td>
                            
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.fines.show', $fine->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.fines.edit', $fine->id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($fine->status === 'pending')
                                            <form method="POST" action="{{ route('admin.fines.mark-paid', $fine->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Đánh dấu đã thanh toán" onclick="return confirm('Xác nhận đánh dấu đã thanh toán?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.fines.waive', $fine->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm" title="Miễn phạt" onclick="return confirm('Xác nhận miễn phạt?')">
                                                    <i class="fas fa-gift"></i>
                                                </button>
                                            </form>
                                            @if($fine->status == 'waived')
    <a href="{{ route('admin.fines.restore', $fine->id) }}" class="btn btn-sm btn-success">Khôi phục</a>
@endif

                                        @endif
                                    </div>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="fines-empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <div class="empty-title">Không có phạt nào</div>
                                        <div class="empty-subtitle">Chưa có phạt nào được tạo trong hệ thống</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="fines-pagination">
            {{ $fines->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
function createLateReturnFines() {
    if (confirm('Bạn có chắc chắn muốn tạo phạt cho tất cả sách trả muộn?')) {
        // Show loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        button.disabled = true;
        
        fetch('{{ route("admin.fines.create-late-returns") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success message
                showMessage('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showMessage('error', 'Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('error', 'Có lỗi xảy ra khi tạo phạt');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

function filterOverdue() {
    const url = new URL(window.location);
    if (url.searchParams.has('overdue')) {
        url.searchParams.delete('overdue');
    } else {
        url.searchParams.set('overdue', '1');
    }
    window.location.href = url.toString();
}

function showMessage(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fines-message ${type}`;
    alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection