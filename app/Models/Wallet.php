<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với WalletTransaction
     */
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc');
    }

    /**
     * Lấy hoặc tạo wallet cho user
     */
    public static function getOrCreateForUser($userId)
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'is_active' => true]
        );
    }

    /**
     * Nạp tiền vào ví
     */
    public function deposit($amount, $description = null, $reference = null)
    {
        return $this->addTransaction('deposit', $amount, $description, $reference);
    }

    /**
     * Rút tiền từ ví
     */
    public function withdraw($amount, $description = null, $reference = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Số dư không đủ để thực hiện giao dịch');
        }
        return $this->addTransaction('withdraw', -$amount, $description, $reference);
    }

    /**
     * Hoàn tiền cọc vào ví
     */
    public function refund($amount, $description = null, $reference = null)
    {
        return $this->addTransaction('refund', $amount, $description, $reference);
    }

    /**
     * Thanh toán từ ví
     */
    public function pay($amount, $description = null, $reference = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Số dư không đủ để thanh toán');
        }
        return $this->addTransaction('payment', -$amount, $description, $reference);
    }

    /**
     * Thêm giao dịch vào ví
     */
    protected function addTransaction($type, $amount, $description = null, $reference = null)
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        
        if ($this->balance < 0) {
            throw new \Exception('Số dư ví không thể âm');
        }
        
        $balanceAfter = $this->balance;
        
        $this->save();
        
        $transaction = WalletTransaction::create([
            'wallet_id' => $this->id,
            'type' => $type,
            'amount' => abs($amount),
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
            'status' => 'completed',
        ]);
        
        return $transaction;
    }
}



