@extends('layouts.admin')

@section('title', 'Tạo chiến dịch Email Marketing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Tạo chiến dịch Email Marketing mới
                    </h3>
                </div>
                
                <form action="{{ route('admin.email-marketing.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Thông tin cơ bản -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Tên chiến dịch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="subject">Tiêu đề email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Sử dụng {{name}} để cá nhân hóa email
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="template">Template <span class="text-danger">*</span></label>
                                    <select class="form-control @error('template') is-invalid @enderror" 
                                            id="template" name="template" required>
                                        <option value="">Chọn template</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template }}" {{ old('template') == $template ? 'selected' : '' }}>
                                                {{ ucfirst($template) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Nội dung email <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Các placeholder có sẵn: {{name}}, {{email}}, {{library_name}}, {{current_date}}
                                    </small>
                                </div>
                            </div>

                            <!-- Cài đặt nâng cao -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Cài đặt nâng cao</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="scheduled_at">Lên lịch gửi</label>
                                            <input type="datetime-local" class="form-control" 
                                                   id="scheduled_at" name="scheduled_at" 
                                                   value="{{ old('scheduled_at') }}">
                                            <small class="form-text text-muted">
                                                Để trống để gửi ngay lập tức
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label>Đối tượng nhận email</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="target_all" name="target_all" 
                                                       {{ old('target_all') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="target_all">
                                                    Tất cả subscribers
                                                </label>
                                            </div>
                                        </div>

                                        <div id="target-criteria" style="display: none;">
                                            <div class="form-group">
                                                <label for="target_tags">Tags</label>
                                                <select class="form-control" id="target_tags" name="target_criteria[tags][]" multiple>
                                                    @foreach($tags as $tag)
                                                        <option value="{{ $tag }}">{{ $tag }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="target_source">Nguồn đăng ký</label>
                                                <select class="form-control" id="target_source" name="target_criteria[source]">
                                                    <option value="">Tất cả</option>
                                                    <option value="website">Website</option>
                                                    <option value="facebook">Facebook</option>
                                                    <option value="email_invitation">Email mời</option>
                                                    <option value="admin_manual">Thêm thủ công</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Metadata bổ sung</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="include_featured_books" name="metadata[include_featured_books]" 
                                                       {{ old('metadata.include_featured_books') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="include_featured_books">
                                                    Bao gồm sách nổi bật
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="include_stats" name="metadata[include_stats]" 
                                                       {{ old('metadata.include_stats') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="include_stats">
                                                    Bao gồm thống kê thư viện
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="action_url">URL hành động</label>
                                            <input type="url" class="form-control" 
                                                   id="action_url" name="metadata[action_url]" 
                                                   value="{{ old('metadata.action_url') }}" 
                                                   placeholder="https://example.com">
                                        </div>

                                        <div class="form-group">
                                            <label for="action_text">Text nút hành động</label>
                                            <input type="text" class="form-control" 
                                                   id="action_text" name="metadata[action_text]" 
                                                   value="{{ old('metadata.action_text') }}" 
                                                   placeholder="Xem thêm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.email-marketing.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Tạo chiến dịch
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle target criteria
    $('#target_all').change(function() {
        if ($(this).is(':checked')) {
            $('#target-criteria').hide();
        } else {
            $('#target-criteria').show();
        }
    });

    // Initialize target criteria visibility
    if ($('#target_all').is(':checked')) {
        $('#target-criteria').hide();
    } else {
        $('#target-criteria').show();
    }

    // Preview template
    $('#template').change(function() {
        var template = $(this).val();
        if (template === 'marketing') {
            $('#content').val('Xin chào {{name}},\n\nChúng tôi muốn chia sẻ với bạn những tin tức mới nhất từ thư viện...\n\nTrân trọng,\nThư viện');
        } else if (template === 'simple') {
            $('#content').val('Xin chào {{name}},\n\nThông báo quan trọng từ thư viện...\n\nTrân trọng,\nThư viện');
        }
    });
});
</script>
@endpush



















