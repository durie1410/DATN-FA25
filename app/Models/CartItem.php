<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'purchasable_book_id',
        'quantity',
        'price',
        'total_price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    /**
     * Relationship với Cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Relationship với PurchasableBook
     */
    public function purchasableBook(): BelongsTo
    {
        return $this->belongsTo(PurchasableBook::class);
    }

    /**
     * Cập nhật số lượng và tính lại tổng tiền
     */
    public function updateQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->total_price = $this->price * $quantity;
        $this->save();
        
        // Cập nhật tổng của cart
        $this->cart->recalculateTotals();
    }

    /**
     * Tạo hoặc cập nhật cart item
     */
    public static function addOrUpdate($cartId, $bookId, $quantity = 1)
    {
        $book = PurchasableBook::findOrFail($bookId);
        
        $cartItem = static::where('cart_id', $cartId)
            ->where('purchasable_book_id', $bookId)
            ->first();

        if ($cartItem) {
            // Cập nhật số lượng
            $cartItem->updateQuantity($cartItem->quantity + $quantity);
        } else {
            // Tạo mới
            $cartItem = static::create([
                'cart_id' => $cartId,
                'purchasable_book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $book->gia,
                'total_price' => $book->gia * $quantity
            ]);
            
            // Cập nhật tổng của cart
            $cartItem->cart->recalculateTotals();
        }

        return $cartItem;
    }
}