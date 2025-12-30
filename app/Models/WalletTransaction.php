<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_type',
        'reference_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Quan hệ với Wallet
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Quan hệ đa hình với đối tượng tham chiếu
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Scope: lấy giao dịch nạp tiền
     */
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    /**
     * Scope: lấy giao dịch hoàn cọc
     */
    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }

    /**
     * Scope: lấy giao dịch rút tiền
     */
    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdraw');
    }

    /**
     * Scope: lấy giao dịch thanh toán
     */
    public function scopePayments($query)
    {
        return $query->where('type', 'payment');
    }

    /**
     * Kiểm tra xem giao dịch có phải là giao dịch tăng số dư không
     */
    public function isCredit()
    {
        return in_array($this->type, ['deposit', 'refund']);
    }

    /**
     * Kiểm tra xem giao dịch có phải là giao dịch giảm số dư không
     */
    public function isDebit()
    {
        return in_array($this->type, ['withdraw', 'payment']);
    }
}



