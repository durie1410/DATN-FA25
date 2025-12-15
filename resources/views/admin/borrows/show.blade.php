@extends('layouts.admin')

@section('title', 'Chi Tiết Phiếu Mượn')

@section('content')
<style>
    .status-badge {
    display: inline-block;
    padding: 0.25em 0.5em;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 0.25rem;
    color: #fff;
    text-align: center;
}

.status-Cho-duyet { background-color: #6c757d; }    /* xám */
.status-Chua-nhan { background-color: #0d6efd; }    /* xanh dương */
.status-Dang-muon { background-color: #0dcaf0; }    /* xanh nhạt */
.status-Da-tra { background-color: #198754; }       /* xanh lá */
.status-Qua-han { background-color: #ffc107; color: #000; } /* vàng */
.status-Mat-sach { background-color: #dc3545; }     /* đỏ */
.status-Hong { background-color: #fd7e14; }        /* cam */
.status-Khong-xac-dinh { background-color: #6c757d; } /* xám */

/* Status badges cho trạng thái chi tiết */
.status-detail-badge {
    font-size: 0.95rem;
    padding: 0.5em 1em;
    border-radius: 0.3rem;
}
.status-cho_xu_ly { 
    background-color: #6c757d !important; 
    color: #fff !important;
}
.status-dang_chuan_bi { 
    background-color: #0dcaf0 !important; 
    color: #000 !important;
}
.status-dang_giao { 
    background-color: #0d6efd !important; 
    color: #fff !important;
}
.status-da_giao_thanh_cong { 
    background-color: #198754 !important; 
    color: #fff !important;
}
.status-giao_that_bai { 
    background-color: #dc3545 !important; 
    color: #fff !important;
}
.status-tra_lai_sach { 
    background-color: #fd7e14 !important; 
    color: #fff !important;
}
.status-dang_gui_lai { 
    background-color: #0d6efd !important; 
    color: #fff !important;
}
.status-da_nhan_hang { 
    background-color: #198754 !important; 
    color: #fff !important;
}
.status-dang_kiem_tra { 
    background-color: #ffc107 !important; 
    color: #000 !important;
}
.status-thanh_toan_coc { 
    background-color: #20c997 !important; 
    color: #fff !important;
}
.status-hoan_thanh { 
    background-color: #198754 !important; 
    color: #fff !important;
}

</style>
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fas fa-file-alt"></i> Chi tiết phiếu mượn</h3>
        <div>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-info-circle me-2"></i> Thông tin chung
    </div>
    <div class="card-body bg-light">
        <div class="row mb-2">
            <div class="col-md-6">
                <p class="mb-1"><strong>Mã phiếu:</strong> #{{ $borrow->id }}</p>
                <p class="mb-1">
                    <strong>Độc giả:</strong>
                    {{ optional($borrow->reader)->ho_ten ?? 'Không có thẻ thành viên' }}
                    <small class="text-muted">
                        ({{ optional($borrow->reader)->so_the_doc_gia ?? 'N/A' }})
                    </small>
                </p>
                @if(!empty($borrow->ten_nguoi_muon))
                <p class="mb-1"><strong>Tên người mượn:</strong> {{ $borrow->ten_nguoi_muon }}</p>
                @endif
                <div>
                    <p class="mb-1"><strong>địa chỉ người mượn: </strong>{{ $borrow->tinh_thanh }}, {{ $borrow->huyen }}, {{ $borrow->xa }}, {{ $borrow->so_nha }}
                </div>
                <p class="mb-1">
                    <strong>Thủ thư:</strong>
                    {{ optional($borrow->librarian)->name ?? 'Không xác định' }}
                </p>
            </div>

            <div class="col-md-6">
                <p class="mb-1">
                    <strong>Ngày mượn:</strong>
                    {{ $borrow->ngay_muon ? $borrow->ngay_muon->format('d/m/Y') : '---' }}
                </p>
                <p class="mb-1">
                    <strong>Trạng thái:</strong>
                    @switch($borrow->trang_thai)
                        @case('Dang muon')
                            <span class="badge bg-primary">Đang mượn</span>
                            @break
                        @case('Da tra')
                            <span class="badge bg-success">Đã trả</span>
                            @break
                        @case('Qua han')
                            <span class="badge bg-danger">Quá hạn</span>
                            @break
                        @default
                            <span class="badge bg-warning text-dark">{{ $borrow->trang_thai }}</span>
                    @endswitch
                </p>
                
                @if($borrow->trang_thai_chi_tiet)
                <p class="mb-1">
                    <strong>Trạng thái chi tiết:</strong>
                    @php
                        $statusClass = 'status-' . $borrow->trang_thai_chi_tiet;
                    @endphp
                    <span class="badge {{ $statusClass }} status-detail-badge">
                        {{ $borrow->trang_thai_chi_tiet_label ?? $borrow->trang_thai_chi_tiet }}
                    </span>
                </p>
                @endif
                
                @if($borrow->tinh_trang_sach)
                <p class="mb-1">
                    <strong>Tình trạng sách:</strong>
                    <span class="badge 
                        @if($borrow->tinh_trang_sach == 'binh_thuong') bg-success
                        @elseif($borrow->tinh_trang_sach == 'hong_nhe') bg-warning text-dark
                        @elseif($borrow->tinh_trang_sach == 'hong_nang') bg-orange
                        @else bg-danger
                        @endif">
                        {{ $borrow->tinh_trang_sach_label }}
                    </span>
                </p>
                @endif
                
                @if($borrow->phi_hong_sach > 0)
                <p class="mb-1">
                    <strong>Phí hỏng sách:</strong>
                    <span class="text-danger fw-bold">{{ number_format($borrow->phi_hong_sach) }}₫</span>
                </p>
                @endif
                
                @if($borrow->tien_coc_hoan_tra !== null)
                <p class="mb-1">
                    <strong>Tiền cọc hoàn trả:</strong>
                    <span class="text-success fw-bold">{{ number_format($borrow->tien_coc_hoan_tra) }}₫</span>
                </p>
                @endif
                <p class="mb-1">
                    <strong>Tổng tiền:</strong>
                    <span class="fw-bold text-success">{{ number_format($borrow->tong_tien) }}₫</span>
                </p>
            </div>
        </div>

        @if($borrow->ghi_chu)
        <div class="mt-3">
            <p class="mb-0"><strong>Ghi chú:</strong></p>
            <div class="alert alert-secondary mt-1 p-2">
                <em>{{ $borrow->ghi_chu }}</em>
            </div>
        </div>
        @endif
    </div>
</div>

    <div class="card">
        <div class="card-header"> sách mượn</div>      <span class="badge badge-info">Tổng: {{ $borrow->items->count() }} sách</span>


        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th>Tiền cọc</th>
                        <th>Tiền ship</th>
                        <th>Tiền thuê</th>
                        <th>Ngày hẹn trả</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrow->items as $index => $item)
                    <tr>
                        <th>{{ $item->book->id }}</th>
                        <td>{{ $item->book->ten_sach }}</td>
                        <td>{{ $item->book->tac_gia }}</td>
<td>
    {{ number_format($item->tien_coc) }}₫ 
    (
    @switch($item->trang_thai_coc)
        @case('cho_xu_ly')
            Chờ xử lý
            @break
        @case('da_thu')
            Đã thu
            @break
        @case('da_hoan')
            Đã hoàn
            @break
        @default
            Không xác định
    @endswitch
    )
</td>

                        <td>{{ number_format($item->tien_ship) }}₫</td>
                        <td>{{ number_format($item->tien_thue) }}₫</td>

                        <td>{{ $item->ngay_hen_tra->format('d/m/Y') }}</td>
<td>
    @php
        $statusClass = str_replace(' ', '-', $item->trang_thai);
    @endphp
    <span class="status-badge status-{{ $statusClass }}">
        @switch($item->trang_thai)
            @case('Cho duyet') Chưa duyệt @break
            @case('Chua nhan') Chưa nhận @break
            @case('Dang muon') Đang mượn @break
            @case('Da tra') Đã trả @break
            @case('Qua han') Quá hạn @break
            @case('Mat sach') Mất sách @break
            @case('Hong') Hỏng @break
            @default Không xác định
        @endswitch
    </span>
</td>
              </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>
@endsection

