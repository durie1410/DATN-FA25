@extends('account._layout')

@section('title', 'Hộp thư hỗ trợ')
@section('breadcrumb', 'Hộp thư hỗ trợ')

@section('content')
    <div class="account-section">
        <div class="account-section-header">
            <h1 class="account-section-title">Hộp thư hỗ trợ khách</h1>
            <p class="account-section-subtitle">
                Danh sách phiên chat của khách hàng với bộ phận hỗ trợ.
            </p>
        </div>

        <form method="GET" action="{{ route('admin.chat.index') }}" class="account-search-form" style="margin-bottom: 16px;">
            <div style="display: flex; gap: 8px; align-items: center;">
                <input type="text"
                       name="keyword"
                       value="{{ $keyword }}"
                       placeholder="Tìm theo tên hoặc email khách..."
                       style="flex: 1; padding: 8px 10px; border-radius: 6px; border: 1px solid #ddd;">
                <button type="submit" class="btn btn-primary">Tìm</button>
            </div>
        </form>

        <div class="account-card" style="padding: 0; overflow: hidden;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f3f4f6;">
                    <tr>
                        <th style="padding: 10px; text-align: left;">Khách hàng</th>
                        <th style="padding: 10px; text-align: left;">Email</th>
                        <th style="padding: 10px; text-align: left;">Tin cuối</th>
                        <th style="padding: 10px; text-align: left;">Thời gian</th>
                        <th style="padding: 10px; text-align: center; width: 120px;">Chưa đọc</th>
                        <th style="padding: 10px; text-align: right; width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($threads as $thread)
                        <tr style="border-top: 1px solid #e5e7eb;">
                            <td style="padding: 10px;">
                                <strong>{{ $thread->name ?? 'Khách' }}</strong>
                            </td>
                            <td style="padding: 10px;">
                                {{ $thread->email ?? '—' }}
                            </td>
                            <td style="padding: 10px; max-width: 260px;">
                                {{ \Illuminate\Support\Str::limit($thread->preview ?? '', 80) }}
                            </td>
                            <td style="padding: 10px;">
                                {{ $thread->last_at->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding: 10px; text-align: center;">
                                @if($thread->unread_count > 0)
                                    <span style="display: inline-block; min-width: 24px; padding: 2px 6px; border-radius: 999px; background: #fee2e2; color: #b91c1c; font-weight: 600; font-size: 12px;">
                                        {{ $thread->unread_count }}
                                    </span>
                                @else
                                    <span style="color: #6b7280; font-size: 12px;">0</span>
                                @endif
                            </td>
                            <td style="padding: 10px; text-align: right;">
                                <a href="{{ route('admin.chat.show', $thread->session_id) }}" class="btn btn-sm btn-primary">
                                    Xem chat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 16px; text-align: center; color: #6b7280;">
                                Chưa có phiên chat nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 12px;">
            {{ $threads->links() }}
        </div>
    </div>
@endsection


