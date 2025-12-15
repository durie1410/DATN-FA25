<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
        'title',
        'status',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    // Scope để lấy đánh giá đã phê duyệt
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope để lấy đánh giá chờ phê duyệt
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope để lấy đánh giá đã xác minh
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Scope để lấy đánh giá theo rating
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
