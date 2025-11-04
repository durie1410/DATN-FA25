@extends('layouts.admin')

@section('title', 'Chi Tiết Phiếu Mượn - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-eye"></i> Chi tiết phiếu mượn #{{ $borrow->id }}</h3>
        <div>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.borrows.edit', $borrow->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Thông tin phiếu mượn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mã phiếu:</label>
                                <p class="form-control-plaintext">#{{ $borrow->id }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Độc giả:</label>
                                <p class="form-control-plaintext">{{ $borrow->reader->ho_ten }}</p>
                                <small class="text-muted">Mã thẻ: {{ $borrow->reader->so_the_doc_gia }}</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sách:</label>
                                <p class="form-control-plaintext">{{ $borrow->book->ten_sach }}</p>
                                <small class="text-muted">Tác giả: {{ $borrow->book->tac_gia }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Thủ thư:</label>
                                <p class="form-control-plaintext">{{ $borrow->librarian ? $borrow->librarian->name : 'Chưa xác định' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày mượn:</label>
                                <p class="form-control-plaintext">{{ $borrow->ngay_muon->format('d/m/Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hạn trả:</label>
                                <p class="form-control-plaintext {{ $borrow->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                    {{ $borrow->ngay_hen_tra->format('d/m/Y') }}
                                    @if($borrow->isOverdue())
                                        <span class="badge bg-danger ms-2">Quá hạn {{ $borrow->days_overdue }} ngày</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($borrow->ngay_tra_thuc_te)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày trả thực tế:</label>
                                <p class="form-control-plaintext">{{ $borrow->ngay_tra_thuc_te->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái:</label>
                                <p class="form-control-plaintext">
                                    @if($borrow->trang_thai == 'Dang muon')
                                        <span class="badge bg-primary">Đang mượn</span>
                                    @elseif($borrow->trang_thai == 'Da tra')
                                        <span class="badge bg-success">Đã trả</span>
                                    @elseif($borrow->trang_thai == 'Qua han')
                                        <span class="badge bg-danger">Quá hạn</span>
                                    @else
                                        <span class="badge bg-warning">Mất sách</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số lần gia hạn:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info">{{ $borrow->so_lan_gia_han }}/2</span>
                                    @if($borrow->ngay_gia_han_cuoi)
                                        <br><small class="text-muted">Lần cuối: {{ $borrow->ngay_gia_han_cuoi->format('d/m/Y') }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($borrow->ghi_chu)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú:</label>
                        <p class="form-control-plaintext">{{ $borrow->ghi_chu }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-cogs"></i> Hành động</h5>
                </div>
                <div class="card-body">
                    @if($borrow->trang_thai == 'Dang muon')
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('admin.borrows.return', $borrow->id) }}" class="btn btn-success" onclick="return confirm('Xác nhận trả sách?')">
                                <i class="fas fa-undo"></i> Trả sách
                            </a>
                        </div>
                        
                        @if($borrow->canExtend())
                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#extendModal">
                                <i class="fas fa-clock"></i> Gia hạn mượn
                            </button>
                        </div>
                        @endif
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.borrows.edit', $borrow->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
            </div>

            @if($borrow->fines->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-exclamation-triangle"></i> Phí phạt</h5>
                </div>
                <div class="card-body">
                    @foreach($borrow->fines as $fine)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ $fine->loai_phat }}</span>
                            <span class="fw-bold">{{ number_format($fine->so_tien) }} VNĐ</span>
                        </div>
                        <small class="text-muted">{{ $fine->created_at->format('d/m/Y') }}</small>
                        <div>
                            @if($fine->status == 'pending')
                                <span class="badge bg-warning">Chưa thanh toán</span>
                            @else
                                <span class="badge bg-success">Đã thanh toán</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Extension Modal --}}
@if($borrow->canExtend())
<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gia hạn mượn sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.borrows.extend', $borrow->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số ngày gia hạn:</label>
                        <select name="days" class="form-control" required>
                            <option value="7">7 ngày</option>
                            <option value="14">14 ngày</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hạn trả mới:</label>
                        <p class="form-control-plaintext" id="newDueDate">
                            {{ $borrow->ngay_hen_tra->addDays(7)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Gia hạn</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const daysSelect = document.querySelector('select[name="days"]');
    const newDueDate = document.getElementById('newDueDate');
    const originalDate = new Date('{{ $borrow->ngay_hen_tra->format('Y-m-d') }}');
    
    if (daysSelect && newDueDate) {
        daysSelect.addEventListener('change', function() {
            const days = parseInt(this.value);
            const newDate = new Date(originalDate);
            newDate.setDate(newDate.getDate() + days);
            newDueDate.textContent = newDate.toLocaleDateString('vi-VN');
        });
    }
});
</script>
@endpush



















