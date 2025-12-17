@extends('account._layout')

@section('title', 'Trả lời khách')
@section('breadcrumb', 'Trả lời khách')

@section('content')
    <div class="account-section">
        <div class="account-section-header">
            <h1 class="account-section-title">Phiên chat với khách</h1>
            <p class="account-section-subtitle">
                {{ $customerName }} @if($customerEmail) ({{ $customerEmail }}) @endif
            </p>
        </div>

        @if(session('success'))
            <div style="margin-bottom: 12px; padding: 10px 12px; border-radius: 6px; background: #dcfce7; color: #166534;">
                {{ session('success') }}
            </div>
        @endif

        <div class="account-card" style="display: flex; flex-direction: column; gap: 12px; max-height: 520px;">
            <div style="flex: 1; overflow-y: auto; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                @foreach($messages as $msg)
                    <div style="margin-bottom: 10px; display: flex; {{ $msg->sender_type === 'support' ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                        <div style="
                            max-width: 70%;
                            padding: 8px 10px;
                            border-radius: 10px;
                            background: {{ $msg->sender_type === 'support' ? '#2563eb' : '#e5e7eb' }};
                            color: {{ $msg->sender_type === 'support' ? '#fff' : '#111827' }};
                            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
                        ">
                            <div style="font-size: 13px; margin-bottom: 2px; font-weight: 600;">
                                {{ $msg->sender_type === 'support' ? 'Bạn' : ($msg->sender_name ?? 'Khách') }}
                            </div>
                            <div style="font-size: 14px; white-space: pre-line;">
                                {{ $msg->message }}
                            </div>
                            <div style="font-size: 11px; margin-top: 4px; opacity: 0.8;">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('admin.chat.reply', $sessionId) }}">
                @csrf
                <div style="margin-bottom: 6px;">
                    <textarea name="message"
                              rows="3"
                              required
                              style="width: 100%; padding: 8px 10px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px;"
                              placeholder="Nhập nội dung trả lời cho khách...">{{ old('message') }}</textarea>
                    @error('message')
                        <div style="color: #b91c1c; font-size: 12px; margin-top: 2px;">{{ $message }}</div>
                    @enderror
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('admin.chat.index') }}" style="font-size: 13px; color: #2563eb;">← Quay lại danh sách</a>
                    <button type="submit" class="btn btn-primary">
                        Gửi trả lời
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


