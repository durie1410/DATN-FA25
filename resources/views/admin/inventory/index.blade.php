@extends('layouts.admin')

@section('title', 'Quản Lý Kho - LIBHUB Admin')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-boxes"></i>
            Quản lý kho
        </h1>
        <p class="page-subtitle">Quản lý và theo dõi tất cả sách trong kho</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <form action="{{ route('admin.inventory.import-all-books') }}" method="POST" style="display: inline;" id="importAllForm">
            @csrf
            <button type="submit" class="btn btn-warning" id="importAllBtn">
                <i class="fas fa-download"></i>
                Import tất cả sách vào kho
            </button>
        </form>
        <form action="{{ route('admin.inventory.sync-to-homepage') }}" method="POST" style="display: inline;" id="syncForm">
            @csrf
            <button type="submit" class="btn btn-success" id="syncBtn">
                <i class="fas fa-sync-alt"></i>
                Đồng bộ lên trang chủ
            </button>
        </form>
        <a href="{{ route('admin.inventory.export', request()->all()) }}" class="btn btn-info">
            <i class="fas fa-file-excel"></i>
            Xuất Excel
        </a>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-file-import"></i>
            Nhập Excel
        </button>
        <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Thêm sách vào kho
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
@endif

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-search"></i>
            Tìm kiếm & Lọc
        </h3>
    </div>
    <form action="{{ route('admin.inventory.index') }}" method="GET" style="padding: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 250px;">
            <input type="text" 
                   name="book_title" 
                   value="{{ request('book_title') }}" 
                   class="form-control" 
                   placeholder="Tìm theo tên sách...">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <select name="book_id" class="form-select">
                <option value="">-- Tất cả sách --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->ten_sach }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <input type="text" 
                   name="barcode" 
                   value="{{ request('barcode') }}" 
                   class="form-control" 
                   placeholder="Mã vạch...">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <input type="text" 
                   name="location" 
                   value="{{ request('location') }}" 
                   class="form-control" 
                   placeholder="Vị trí...">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <select name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="Co san" {{ request('status') == 'Co san' ? 'selected' : '' }}>Có sẵn</option>
                <option value="Dang muon" {{ request('status') == 'Dang muon' ? 'selected' : '' }}>Đang mượn</option>
                <option value="Mat" {{ request('status') == 'Mat' ? 'selected' : '' }}>Mất</option>
                <option value="Hong" {{ request('status') == 'Hong' ? 'selected' : '' }}>Hỏng</option>
                <option value="Thanh ly" {{ request('status') == 'Thanh ly' ? 'selected' : '' }}>Thanh lý</option>
            </select>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <select name="condition" class="form-select">
                <option value="">-- Tất cả tình trạng --</option>
                <option value="Moi" {{ request('condition') == 'Moi' ? 'selected' : '' }}>Mới</option>
                <option value="Tot" {{ request('condition') == 'Tot' ? 'selected' : '' }}>Tốt</option>
                <option value="Trung binh" {{ request('condition') == 'Trung binh' ? 'selected' : '' }}>Trung bình</option>
                <option value="Cu" {{ request('condition') == 'Cu' ? 'selected' : '' }}>Cũ</option>
                <option value="Hong" {{ request('condition') == 'Hong' ? 'selected' : '' }}>Hỏng</option>
            </select>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <select name="storage_type" class="form-select">
                <option value="">-- Tất cả loại --</option>
                <option value="Kho" {{ request('storage_type') == 'Kho' ? 'selected' : '' }}>Kho</option>
                <option value="Trung bay" {{ request('storage_type') == 'Trung bay' ? 'selected' : '' }}>Trưng bày</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i>
            Lọc
        </button>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i>
            Reset
        </a>
    </form>
</div>

<!-- Inventory List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Danh sách sách trong kho
        </h3>
        <span class="badge badge-info">Tổng: {{ $inventories->total() }} sách</span>
    </div>
    
    @if($inventories->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã vạch</th>
                        <th>Thông tin sách</th>
                        <th>Loại</th>
                        <th>Vị trí</th>
                        <th>Tình trạng</th>
                        <th>Trạng thái</th>
                        <th>Giá mua</th>
                        <th>Người nhập</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventories as $inventory)
                        <tr>
                            <td>
                                <span class="badge badge-info">{{ $inventory->id }}</span>
                            </td>
                            <td>
                                <code style="background: rgba(0, 255, 153, 0.1); padding: 4px 8px; border-radius: 4px; color: var(--primary-color);">
                                    {{ $inventory->barcode }}
                                </code>
                            </td>
                            <td>
                                <div style="max-width: 300px;">
                                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 5px;">
                                        {{ $inventory->book->ten_sach ?? 'N/A' }}
                                    </div>
                                    <div style="font-size: 12px; color: #888;">
                                        <i class="fas fa-user"></i> {{ $inventory->book->tac_gia ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($inventory->storage_type == 'Kho')
                                    <span class="badge badge-info">
                                        <i class="fas fa-warehouse"></i>
                                        Kho
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-store"></i>
                                        Trưng bày
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $inventory->location }}
                                </span>
                            </td>
                            <td>
                                @if($inventory->condition == 'Moi')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Mới
                                    </span>
                                @elseif($inventory->condition == 'Tot')
                                    <span class="badge badge-info">
                                        <i class="fas fa-check"></i>
                                        Tốt
                                    </span>
                                @elseif($inventory->condition == 'Trung binh')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Trung bình
                                    </span>
                                @elseif($inventory->condition == 'Cu')
                                    <span class="badge" style="background: #6c757d; color: white;">
                                        <i class="fas fa-clock"></i>
                                        Cũ
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i>
                                        Hỏng
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($inventory->status == 'Co san')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i>
                                        Có sẵn
                                    </span>
                                @elseif($inventory->status == 'Dang muon')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-hand-holding"></i>
                                        Đang mượn
                                    </span>
                                @elseif($inventory->status == 'Mat')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Mất
                                    </span>
                                @elseif($inventory->status == 'Hong')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i>
                                        Hỏng
                                    </span>
                                @else
                                    <span class="badge" style="background: #6c757d; color: white;">
                                        <i class="fas fa-trash"></i>
                                        Thanh lý
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($inventory->purchase_price)
                                    <span class="badge badge-success">
                                        {{ number_format($inventory->purchase_price, 0, ',', '.') }} đ
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <div style="font-weight: 600; color: var(--text-primary);">
                                        <i class="fas fa-user"></i>
                                        {{ $inventory->creator->name ?? 'N/A' }}
                                    </div>
                                    <div style="color: #888; margin-top: 3px;">
                                        <i class="fas fa-calendar"></i>
                                        {{ $inventory->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="{{ route('admin.inventory.show', $inventory->id) }}" 
                                       class="btn btn-sm btn-secondary" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.inventory.destroy', $inventory->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này khỏi kho? Hành động này không thể hoàn tác!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px;">
            {{ $inventories->appends(request()->query())->links('vendor.pagination.admin') }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0, 255, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-boxes" style="font-size: 36px; color: var(--primary-color);"></i>
            </div>
            <h3 style="color: var(--text-primary); margin-bottom: 10px;">Chưa có sách nào trong kho</h3>
            <p style="color: #888; margin-bottom: 25px;">Hãy thêm sách đầu tiên vào kho để bắt đầu quản lý.</p>
            <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Thêm sách vào kho
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý import tất cả sách
    const importAllForm = document.getElementById('importAllForm');
    const importAllBtn = document.getElementById('importAllBtn');
    
    if (importAllForm && importAllBtn) {
        importAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Bạn có chắc muốn import tất cả sách từ quản lý sách vào kho? Sách đã có trong kho sẽ được bỏ qua.')) {
                return;
            }
            
            // Disable button và hiển thị loading
            importAllBtn.disabled = true;
            importAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang import...';
            
            // Gửi request
            fetch('{{ route("admin.inventory.import-all-books") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success';
                    alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    importAllForm.parentElement.insertBefore(alertDiv, importAllForm);
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Reload trang sau 2 giây
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Hiển thị thông báo lỗi
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + data.message;
                    importAllForm.parentElement.insertBefore(alertDiv, importAllForm);
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Xóa thông báo sau 5 giây
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Hiển thị thông báo lỗi
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra khi import sách. Vui lòng thử lại.';
                importAllForm.parentElement.insertBefore(alertDiv, importAllForm);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Xóa thông báo sau 5 giây
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            })
            .finally(() => {
                // Enable lại button
                importAllBtn.disabled = false;
                importAllBtn.innerHTML = '<i class="fas fa-download"></i> Import tất cả sách vào kho';
            });
        });
    }

    // Xử lý đồng bộ trang chủ
    const syncForm = document.getElementById('syncForm');
    const syncBtn = document.getElementById('syncBtn');
    
    if (syncForm && syncBtn) {
        syncForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button và hiển thị loading
            syncBtn.disabled = true;
            syncBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đồng bộ...';
            
            // Gửi request
            fetch('{{ route("admin.inventory.sync-to-homepage") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success';
                    alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    syncForm.parentElement.insertBefore(alertDiv, syncForm);
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Xóa thông báo sau 5 giây
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                } else {
                    // Hiển thị thông báo lỗi
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + data.message;
                    syncForm.parentElement.insertBefore(alertDiv, syncForm);
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Xóa thông báo sau 5 giây
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Hiển thị thông báo lỗi
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra khi đồng bộ hóa. Vui lòng thử lại.';
                syncForm.parentElement.insertBefore(alertDiv, syncForm);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Xóa thông báo sau 5 giây
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            })
            .finally(() => {
                // Enable lại button
                syncBtn.disabled = false;
                syncBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Đồng bộ lên trang chủ';
            });
        });
    }
});
</script>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inventory.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nhập kho từ Excel</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Chọn file Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            Định dạng: book_id, barcode, location, condition, status, purchase_price, purchase_date
                        </small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Lưu ý:</strong> File Excel phải có định dạng đúng. Nếu không có mã vạch, hệ thống sẽ tự động tạo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Nhập file
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

