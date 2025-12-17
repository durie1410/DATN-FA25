<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reader_id',
        'total_items',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    public function items()
    {
        return $this->hasMany(BorrowCartItem::class);
    }

    public function getTotalItemsAttribute()
    {
        return $this->items()->sum('quantity');
    }
}
