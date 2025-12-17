<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowPayment extends Model
{
    use HasFactory;

    protected $table = 'borrow_payments';

    protected $fillable = [
        'borrow_id',
        'borrow_item_id',
        'amount',
        'payment_type',
        'payment_method',
        'payment_status',
        'transaction_code',
        'note',
    ];

    // Quan hệ tới phiếu mượn
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    // Quan hệ tới từng cuốn
    public function borrowItem()
    {
        return $this->belongsTo(BorrowItem::class);
    }
}
