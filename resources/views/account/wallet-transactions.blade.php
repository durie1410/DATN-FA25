@extends('account._layout')

@section('title', 'Lịch sử giao dịch ví')
@section('breadcrumb', 'Lịch sử giao dịch ví')

@section('content')
<div class="account-section">
    <div class="wallet-header">
        <h2 class="section-title">Lịch sử giao dịch ví</h2>
        <div class="wallet-summary">
            <div class="summary-item">
                <span class="summary-label">Số dư hiện tại:</span>
                <span class="summary-value">{{ number_format($wallet->balance, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>
    </div>

    <div class="filters-section">
        <form method="GET" action="{{ route('account.wallet.transactions') }}" class="filters-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="type">Loại giao dịch:</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">Tất cả</option>
                        <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Hoàn tiền cọc</option>
                        <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Nạp tiền</option>
                        <option value="withdraw" {{ request('type') === 'withdraw' ? 'selected' : '' }}>Rút tiền</option>
                        <option value="payment" {{ request('type') === 'payment' ? 'selected' : '' }}>Thanh toán</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_from">Từ ngày:</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label for="date_to">Đến ngày:</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">Lọc</button>
                    <a href="{{ route('account.wallet.transactions') }}" class="btn-reset">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="wallet-transactions-section">
        @if($transactions->count() > 0)
            <div class="transactions-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ngày giờ</th>
                            <th>Loại giao dịch</th>
                            <th>Mô tả</th>
                            <th>Số tiền</th>
                            <th>Số dư sau</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="transaction-type-badge type-{{ $transaction->type }}">
                                        @if($transaction->type === 'refund')
                                            Hoàn cọc
                                        @elseif($transaction->type === 'deposit')
                                            Nạp tiền
                                        @elseif($transaction->type === 'withdraw')
                                            Rút tiền
                                        @elseif($transaction->type === 'payment')
                                            Thanh toán
                                        @else
                                            {{ $transaction->type }}
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td>
                                    <span class="transaction-amount {{ $transaction->isCredit() ? 'credit' : 'debit' }}">
                                        {{ $transaction->isCredit() ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', '.') }} VNĐ
                                    </span>
                                </td>
                                <td>{{ number_format($transaction->balance_after, 0, ',', '.') }} VNĐ</td>
                                <td>
                                    <span class="status-badge status-{{ $transaction->status }}">
                                        @if($transaction->status === 'completed')
                                            Hoàn thành
                                        @elseif($transaction->status === 'pending')
                                            Đang xử lý
                                        @elseif($transaction->status === 'failed')
                                            Thất bại
                                        @else
                                            {{ ucfirst($transaction->status) }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">💼</div>
                <h3>Không có giao dịch nào</h3>
                <p>Không tìm thấy giao dịch phù hợp với bộ lọc của bạn.</p>
                <a href="{{ route('account.wallet.transactions') }}" class="btn-primary">Xem tất cả</a>
            </div>
        @endif
    </div>
</div>

<style>
.wallet-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-label {
    font-weight: 600;
    color: #333;
}

.summary-value {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
}

.filters-section {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    margin-bottom: 30px;
}

.filters-form {
    width: 100%;
}

.filter-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn-filter, .btn-reset {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin-right: 10px;
}

.btn-filter {
    background-color: #007bff;
    color: white;
}

.btn-filter:hover {
    background-color: #0056b3;
}

.btn-reset {
    background-color: #6c757d;
    color: white;
}

.btn-reset:hover {
    background-color: #5a6268;
}

.transactions-table {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.table thead {
    background-color: #f8f9fa;
}

.table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #e0e0e0;
}

.table td {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.transaction-type-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.type-refund {
    background-color: #d4edda;
    color: #155724;
}

.type-deposit {
    background-color: #d1ecf1;
    color: #0c5460;
}

.type-withdraw {
    background-color: #fff3cd;
    color: #856404;
}

.type-payment {
    background-color: #f8d7da;
    color: #721c24;
}

.transaction-amount.credit {
    color: #28a745;
    font-weight: 700;
}

.transaction-amount.debit {
    color: #dc3545;
    font-weight: 700;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-completed {
    background-color: #d4edda;
    color: #155724;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-failed {
    background-color: #f8d7da;
    color: #721c24;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #333;
    margin-bottom: 10px;
}

.empty-state p {
    color: #666;
    margin-bottom: 20px;
}

.btn-primary {
    display: inline-block;
    padding: 12px 24px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.pagination-wrapper {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}
</style>
@endsection



