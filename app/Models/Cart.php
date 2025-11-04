<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'total_items',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_items' => 'integer',
        'status' => 'string'
    ];

    /**
     * Relationship với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với CartItem
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scope cho cart active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope cho cart của user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope cho cart của session
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Tính lại tổng tiền và số lượng
     */
    public function recalculateTotals()
    {
        $this->total_items = $this->items()->sum('quantity');
        $this->total_amount = $this->items()->sum('total_price');
        $this->save();
    }

    /**
     * Kiểm tra giỏ hàng có trống không
     */
    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }

    /**
     * Lấy hoặc tạo cart cho user
     */
    public static function getOrCreateForUser($userId)
    {
        return static::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            ['total_amount' => 0, 'total_items' => 0]
        );
    }

    /**
     * Lấy hoặc tạo cart cho session
     */
    public static function getOrCreateForSession($sessionId)
    {
        return static::firstOrCreate(
            ['session_id' => $sessionId, 'status' => 'active'],
            ['total_amount' => 0, 'total_items' => 0]
        );
    }
}