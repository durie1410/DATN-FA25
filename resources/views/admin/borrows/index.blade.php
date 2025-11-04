@extends('layouts.admin')

@section('title', 'Quản Lý Mượn/Trả Sách - WAKA Admin')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-exchange-alt"></i>
            Quản lý mượn/trả sách
        </h1>
        <p class="page-subtitle">Theo dõi và quản lý tất cả hoạt động mượn trả sách</p>
    </div>
        <a href="{{ route('admin.borrows.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Cho mượn sách mới
    </a>
</div>

<!-- Quick Stats -->
<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đang mượn</div>
            <div class="stat-icon primary">
                <i class="fas fa-hand-holding"></i>
            </div>
        </div>
        <div class="stat-value">{{ $borrows->where('trang_thai', 'Dang muon')->count() }}</div>
        <div class="stat-label">Phiếu mượn đang hoạt động</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Quá hạn</div>
            <div class="stat-icon danger">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $borrows->where('trang_thai', 'Qua han')->count() }}</div>
        <div class="stat-label">Cần xử lý ngay</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Đã trả</div>
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value">{{ $borrows->where('trang_thai', 'Da tra')->count() }}</div>
        <div class="stat-label">Hoàn thành</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Tổng số</div>
            <div class="stat-icon warning">
                <i class="fas fa-list"></i>
            </div>
        </div>
        <div class="stat-value">{{ $borrows->total() }}</div>
        <div class="stat-label">Tất cả phiếu mượn</div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card" style="margin-bottom: 25px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter"></i>
            Tìm kiếm & Lọc
        </h3>
    </div>
    <form action="{{ route('admin.borrows.index') }}" method="GET" style="padding: 25px; display: flex; gap: 15px; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 250px;">
            <input type="text" 
                   name="keyword" 
                   value="{{ request('keyword') }}" 
                   class="form-control" 
                   placeholder="Tìm theo tên độc giả hoặc tên sách...">
        </div>
        <div style="flex: 1; min-width: 200px;">
            <select name="trang_thai" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="Dang muon" {{ request('trang_thai') == 'Dang muon' ? 'selected' : '' }}>Đang mượn</option>
                <option value="Da tra" {{ request('trang_thai') == 'Da tra' ? 'selected' : '' }}>Đã trả</option>
                <option value="Qua han" {{ request('trang_thai') == 'Qua han' ? 'selected' : '' }}>Quá hạn</option>
                <option value="Mat sach" {{ request('trang_thai') == 'Mat sach' ? 'selected' : '' }}>Mất sách</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
            Lọc
        </button>
        <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i>
            Reset
        </a>
    </form>
</div>

<!-- Borrows List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Danh sách mượn/trả
        </h3>
        <span class="badge badge-info">Tổng: {{ $borrows->total() }} phiếu</span>
    </div>
    
    @if($borrows->count() > 0)
    <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Độc giả</th>
                    <th>Sách</th>
                    <th>Ngày mượn</th>
                    <th>Hạn trả</th>
                    <th>Ngày trả</th>
                    <th>Gia hạn</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                    @foreach($borrows as $borrow)
                        <tr style="{{ $borrow->isOverdue() ? 'border-left: 3px solid #ff6b6b;' : '' }}">
                            <td>
                                <span class="badge badge-info">{{ $borrow->id }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(0, 255, 153, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: 600;">
                                        {{ strtoupper(substr($borrow->reader->ho_ten, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 500; color: var(--text-primary);">
                                            {{ $borrow->reader->ho_ten }}
                                        </div>
                                        <div style="font-size: 12px; color: #888;">
                                            ID: {{ $borrow->reader->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: var(--primary-color); margin-bottom: 3px;">
                                    {{ $borrow->book->ten_sach }}
                                </div>
                                <div style="font-size: 12px; color: #888;">
                                    {{ $borrow->book->tac_gia }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 13px; color: var(--text-secondary);">
                                    <i class="fas fa-calendar" style="color: #888; font-size: 11px;"></i>
                                    {{ $borrow->ngay_muon->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 13px; color: var(--text-secondary);">
                                    <i class="fas fa-clock" style="color: #888; font-size: 11px;"></i>
                                    {{ $borrow->ngay_hen_tra->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                @if($borrow->ngay_tra_thuc_te)
                                    <div style="font-size: 13px; color: #28a745;">
                                        <i class="fas fa-check-circle" style="font-size: 11px;"></i>
                                        {{ $borrow->ngay_tra_thuc_te->format('d/m/Y') }}
                                    </div>
                                @else
                                    <span style="color: #666;">Chưa trả</span>
                        @endif
                    </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                    <span class="badge" style="background: rgba(0, 255, 153, 0.2); color: var(--primary-color); width: fit-content;">
                                        {{ $borrow->so_lan_gia_han }}/2 lần
                                    </span>
                                    @if($borrow->ngay_gia_han_cuoi)
                                        <span style="font-size: 11px; color: #888;">
                                            {{ $borrow->ngay_gia_han_cuoi->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                    <td>
                        @if($borrow->trang_thai == 'Dang muon')
                                    <span class="badge" style="background: rgba(0, 123, 255, 0.2); color: #007bff;">
                                        <i class="fas fa-book-reader"></i>
                                        Đang mượn
                                    </span>
                        @elseif($borrow->trang_thai == 'Da tra')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i>
                                        Đã trả
                                    </span>
                        @elseif($borrow->trang_thai == 'Qua han')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Quá hạn
                                    </span>
                        @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-times"></i>
                                        Mất sách
                                    </span>
                        @endif
                                @if($borrow->isOverdue() && $borrow->trang_thai != 'Da tra')
                                    <br>
                                    <span class="badge badge-danger" style="margin-top: 5px;">
                                        <i class="fas fa-bell"></i>
                                        Quá hạn
                                    </span>
                        @endif
                    </td>
                    <td>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            @if($borrow->trang_thai == 'Dang muon')
                                        <a href="{{ route('admin.borrows.return', $borrow->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           onclick="return confirm('Xác nhận trả sách?')"
                                           title="Trả sách">
                                            <i class="fas fa-undo"></i>
                                </a>
                                @if($borrow->canExtend())
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#extendModal{{ $borrow->id }}"
                                                    title="Gia hạn">
                                                <i class="fas fa-clock"></i>
                                    </button>
                                @endif
                            @endif
                                    <a href="{{ route('admin.borrows.show', $borrow->id) }}" 
                                       class="btn btn-sm btn-secondary"
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.borrows.edit', $borrow->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.borrows.destroy', $borrow->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Xóa phiếu mượn này?')">
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
        {{ $borrows->appends(request()->query())->links('vendor.pagination.admin') }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0, 255, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fas fa-exchange-alt" style="font-size: 36px; color: var(--primary-color);"></i>
            </div>
            <h3 style="color: var(--text-primary); margin-bottom: 10px;">Chưa có phiếu mượn nào</h3>
            <p style="color: #888; margin-bottom: 25px;">Hãy tạo phiếu mượn đầu tiên để bắt đầu quản lý.</p>
            <a href="{{ route('admin.borrows.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i>
                Tạo phiếu mượn đầu tiên
            </a>
        </div>
    @endif
    </div>

<!-- Extension Modals -->
    @foreach($borrows as $borrow)
        @if($borrow->canExtend())
    <div class="modal fade" id="extendModal{{ $borrow->id }}" tabindex="-1" style="display: none;">
        <div class="modal-dialog" style="max-width: 500px; margin: 100px auto;">
            <div class="modal-content" style="background: var(--background-card); border: 1px solid rgba(0, 255, 153, 0.2); border-radius: 15px; color: var(--text-primary);">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 20px 25px;">
                    <h5 class="modal-title" style="color: var(--primary-color); font-weight: 600;">
                        <i class="fas fa-clock"></i>
                        Gia hạn mượn sách
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="background: transparent; border: none; color: #888; font-size: 24px; cursor: pointer; opacity: 0.6; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                    <form action="{{ route('admin.borrows.extend', $borrow->id) }}" method="POST">
                        @csrf
                    <div class="modal-body" style="padding: 25px;">
                        <div class="form-group">
                                <label class="form-label">Độc giả:</label>
                            <div style="padding: 10px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; margin-top: 5px; color: var(--text-primary);">
                                {{ $borrow->reader->ho_ten }}
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="form-label">Sách:</label>
                            <div style="padding: 10px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; margin-top: 5px; color: var(--text-primary);">
                                {{ $borrow->book->ten_sach }}
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="form-label">Hạn trả hiện tại:</label>
                            <div style="padding: 10px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; margin-top: 5px; color: var(--primary-color);">
                                <i class="fas fa-calendar"></i>
                                {{ $borrow->ngay_hen_tra->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="form-label">Số ngày gia hạn:</label>
                            <select name="days" class="form-select" required style="margin-top: 5px;">
                                    <option value="7">7 ngày</option>
                                    <option value="14">14 ngày</option>
                                </select>
                            </div>
                        <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label">Lần gia hạn:</label>
                            <div style="padding: 10px; background: rgba(0, 255, 153, 0.1); border-radius: 8px; margin-top: 5px; color: var(--primary-color); font-weight: 600;">
                                {{ $borrow->so_lan_gia_han + 1 }}/2
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 20px 25px; display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i>
                            Xác nhận gia hạn
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endsection

@push('styles')
<style>
    .modal.fade {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
    }
    
    .modal-dialog {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
