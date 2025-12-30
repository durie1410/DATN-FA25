@php
    $currentRoute = request()->route()->getName();
    $user = auth()->user();
    // Load relationship reader để hiển thị "Sách đang mượn" nếu có
    $user->load('reader');
@endphp
<aside class="account-sidebar">
    <div class="user-profile">
        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="username">{{ $user->name }}</div>
    </div>
    <nav class="account-nav">
        <ul>
            @if($user->reader)
            <li class="{{ $currentRoute === 'account.borrowed-books' ? 'active' : '' }}">
                <a href="{{ route('account.borrowed-books') }}"><span class="icon">📚</span> Sách đang mượn</a>
            </li>
            @endif
            <li class="{{ $currentRoute === 'account' ? 'active' : '' }}">
                <a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin cá nhân</a>
            </li>
            <li class="{{ $currentRoute === 'account.change-password' ? 'active' : '' }}">
                <a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a>
            </li>
            <li class="{{ in_array($currentRoute, ['account.wallet', 'account.wallet.transactions']) ? 'active' : '' }}">
                <a href="{{ route('account.wallet') }}"><span class="icon">💰</span> Ví của tôi</a>
            </li>
            <li class="{{ $currentRoute === 'orders.index' ? 'active' : '' }}">
                 <a href="{{ route('orders.index') }}" class="dropdown-item"><span>📋</span> Lịch sử đơn mượn</a>
            </li>
            <li><a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon">➡️</span> Đăng xuất</a></li>
        </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</aside>

