@extends('layouts.admin')

@section('title', 'Quản Lý Độc Giả - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quản lý tác giả</h4>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Thanh công cụ -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.readers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Thêm mới
                </a>
                <a href="{{ route('admin.readers.statistics') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-1"></i>Thống kê
                </a>
                <a href="{{ route('admin.readers.export', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i>Xuất Excel
                </a>
                <a href="{{ route('admin.readers.print', request()->query()) }}" class="btn btn-warning" target="_blank">
                    <i class="fas fa-print me-1"></i>In danh sách
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" onclick="selectAll()">
                    <i class="fas fa-check-square me-1"></i>Chọn tất cả
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                    <i class="fas fa-square me-1"></i>Bỏ chọn
                </button>
            </div>
        </div>
    </div>

    <!-- Form tìm kiếm và lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.readers.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên, email, số thẻ, SĐT...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="Hoat dong" {{ request('trang_thai') == 'Hoat dong' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="Tam khoa" {{ request('trang_thai') == 'Tam khoa' ? 'selected' : '' }}>Tạm khóa</option>
                        <option value="Het han" {{ request('trang_thai') == 'Het han' ? 'selected' : '' }}>Hết hạn</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Giới tính</label>
                    <select name="gioi_tinh" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="Nam" {{ request('gioi_tinh') == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nu" {{ request('gioi_tinh') == 'Nu' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khac" {{ request('gioi_tinh') == 'Khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Năm sinh</label>
                    <input type="number" name="nam_sinh" value="{{ request('nam_sinh') }}" class="form-control" placeholder="Năm sinh">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sắp xếp</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="ho_ten" {{ request('sort_by') == 'ho_ten' ? 'selected' : '' }}>Tên</option>
                        <option value="ngay_het_han" {{ request('sort_by') == 'ngay_het_han' ? 'selected' : '' }}>Ngày hết hạn</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.readers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk actions -->
    <form id="bulkForm" action="{{ route('admin.readers.bulk-action') }}" method="POST" class="mb-3">
        @csrf
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted">Hành động hàng loạt:</span>
            <select name="action" class="form-select" style="width: auto;">
                <option value="">-- Chọn hành động --</option>
                <option value="activate">Kích hoạt</option>
                <option value="suspend">Tạm khóa</option>
                <option value="delete">Xóa</option>
            </select>
            <button type="submit" class="btn btn-outline-primary" onclick="return confirmBulkAction()">
                <i class="fas fa-play me-1"></i>Thực hiện
            </button>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" onchange="toggleAll()">
                            </th>
                            <th>Mã tác giả</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Giới tính</th>
                            <th>Ngày sinh</th>
                            <th>Trạng thái</th>
                            <th>Ngày hết hạn</th>
                            <th width="200">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($readers as $reader)
                        <tr>
                            <td>
                                <input type="checkbox" name="reader_ids[]" value="{{ $reader->id }}" class="reader-checkbox">
                            </td>
                            <td>
                                <a href="{{ route('admin.readers.show', $reader->id) }}" class="text-decoration-none">
                                    {{ $reader->so_the_doc_gia }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title rounded-circle bg-primary text-white">
                                            {{ substr($reader->ho_ten, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $reader->ho_ten }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $reader->dia_chi }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $reader->email }}</td>
                            <td>{{ $reader->so_dien_thoai }}</td>
                            <td>
                                @if($reader->gioi_tinh == 'Nam')
                                    <span class="badge bg-primary">Nam</span>
                                @elseif($reader->gioi_tinh == 'Nu')
                                    <span class="badge bg-pink">Nữ</span>
                                @else
                                    <span class="badge bg-secondary">Khác</span>
                                @endif
                            </td>
                            <td>{{ $reader->ngay_sinh ? $reader->ngay_sinh->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @if($reader->trang_thai == 'Hoat dong')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($reader->trang_thai == 'Tam khoa')
                                    <span class="badge bg-warning">Tạm khóa</span>
                                @else
                                    <span class="badge bg-danger">Hết hạn</span>
                                @endif
                            </td>
                            <td>
                                {{ $reader->ngay_het_han ? $reader->ngay_het_han->format('d/m/Y') : 'N/A' }}
                                @if($reader->ngay_het_han && $reader->ngay_het_han < now())
                                    <br><small class="text-danger">Quá hạn</small>
                                @elseif($reader->ngay_het_han && $reader->ngay_het_han < now()->addDays(30))
                                    <br><small class="text-warning">Sắp hết hạn</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.readers.show', $reader->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.readers.edit', $reader->id) }}" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($reader->trang_thai == 'Hoat dong')
                                        <form method="POST" action="{{ route('admin.readers.suspend', $reader->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" title="Tạm khóa" onclick="return confirm('Tạm khóa tác giả này?')">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.readers.activate', $reader->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Kích hoạt" onclick="return confirm('Kích hoạt tác giả này?')">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.readers.renew-card', $reader->id) }}" class="btn btn-info btn-sm" title="Gia hạn thẻ" onclick="return confirm('Gia hạn thẻ tác giả này?')">
                                        <i class="fas fa-calendar-plus"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.readers.destroy', $reader->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Xóa tác giả này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Không có tác giả nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            @if($readers->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Hiển thị {{ $readers->firstItem() }} đến {{ $readers->lastItem() }} trong tổng số {{ $readers->total() }} tác giả
                </div>
                <div>
                    {{ $readers->appends(request()->query())->links('vendor.pagination.admin') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.reader-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }

    function selectAll() {
        const checkboxes = document.querySelectorAll('.reader-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        document.getElementById('selectAll').checked = true;
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.reader-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
    }

    function confirmBulkAction() {
        const selectedCheckboxes = document.querySelectorAll('.reader-checkbox:checked');
        const action = document.querySelector('select[name="action"]').value;
        
        if (selectedCheckboxes.length === 0) {
            alert('Vui lòng chọn ít nhất một tác giả!');
            return false;
        }
        
        if (!action) {
            alert('Vui lòng chọn hành động!');
            return false;
        }
        
        let message = '';
        switch(action) {
            case 'activate':
                message = `Bạn có chắc muốn kích hoạt ${selectedCheckboxes.length} tác giả đã chọn?`;
                break;
            case 'suspend':
                message = `Bạn có chắc muốn tạm khóa ${selectedCheckboxes.length} tác giả đã chọn?`;
                break;
            case 'delete':
                message = `Bạn có chắc muốn xóa ${selectedCheckboxes.length} tác giả đã chọn? Hành động này không thể hoàn tác!`;
                break;
        }
        
        return confirm(message);
    }

    // Cập nhật trạng thái checkbox "Chọn tất cả" khi thay đổi checkbox riêng lẻ
    document.querySelectorAll('.reader-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll('.reader-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.reader-checkbox:checked');
            
            document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length;
        });
    });
</script>
@endsection
