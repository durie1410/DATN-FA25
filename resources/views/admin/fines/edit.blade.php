@extends('layouts.admin')

@section('title', 'Chỉnh sửa phạt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i>
                        Chỉnh sửa phạt #{{ $fine->id }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.fines.show', $fine->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <a href="{{ route('admin.fines.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.fines.update', $fine->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phiếu mượn</label>
                                    <div class="form-control-plaintext">
                                        @if($fine->borrow && $fine->borrow->book)
                                            #{{ $fine->borrow->id }} - {{ $fine->borrow->book->ten_sach }}
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Độc giả</label>
                                    <div class="form-control-plaintext">
                                        <strong>{{ $fine->reader->ho_ten }}</strong><br>
                                        <small class="text-muted">{{ $fine->reader->ma_so_the }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Loại phạt <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="late_return" {{ old('type', $fine->type) == 'late_return' ? 'selected' : '' }}>Trả muộn</option>
                                        <option value="damaged_book" {{ old('type', $fine->type) == 'damaged_book' ? 'selected' : '' }}>Làm hỏng sách</option>
                                        <option value="lost_book" {{ old('type', $fine->type) == 'lost_book' ? 'selected' : '' }}>Mất sách</option>
                                        <option value="other" {{ old('type', $fine->type) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $fine->status) == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                        <option value="paid" {{ old('status', $fine->status) == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="waived" {{ old('status', $fine->status) == 'waived' ? 'selected' : '' }}>Đã miễn</option>
                                        <option value="cancelled" {{ old('status', $fine->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Số tiền phạt (VND) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount', $fine->amount) }}" 
                                           min="0" step="1000" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date">Hạn thanh toán <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" id="due_date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           value="{{ old('due_date', $fine->due_date->format('Y-m-d')) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Mô tả lý do phạt</label>
                                    <textarea name="description" id="description" rows="3" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Mô tả chi tiết lý do phạt...">{{ old('description', $fine->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Ghi chú</label>
                                    <textarea name="notes" id="notes" rows="2" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              placeholder="Ghi chú thêm...">{{ old('notes', $fine->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày tạo</label>
                                    <div class="form-control-plaintext">
                                        {{ $fine->created_at->format('d/m/Y H:i') }}
                                        @if($fine->creator)
                                            bởi {{ $fine->creator->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày thanh toán</label>
                                    <div class="form-control-plaintext">
                                        @if($fine->paid_date)
                                            {{ $fine->paid_date->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Chưa thanh toán</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($fine->isOverdue())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Cảnh báo:</strong> Phạt này đã quá hạn {{ $fine->days_overdue }} ngày!
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật phạt
                        </button>
                        <a href="{{ route('admin.fines.show', $fine->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                        
                        @if($fine->status === 'pending')
                            <div class="float-right">
                                <form method="POST" action="{{ route('admin.fines.mark-paid', $fine->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận đánh dấu đã thanh toán?')">
                                        <i class="fas fa-check"></i> Đánh dấu đã thanh toán
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.fines.waive', $fine->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-info" onclick="return confirm('Xác nhận miễn phạt?')">
                                        <i class="fas fa-gift"></i> Miễn phạt
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
