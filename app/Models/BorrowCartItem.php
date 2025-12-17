<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowCartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_cart_id',
        'book_id',
        'quantity',
        'borrow_days',
        'distance',
        'note',
        'is_selected',
        'tien_coc',
        'tien_thue',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    protected $attributes = [
        'is_selected' => true,
    ];

    public function cart()
    {
        return $this->belongsTo(BorrowCart::class, 'borrow_cart_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
