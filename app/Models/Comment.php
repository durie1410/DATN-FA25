<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
        'content',
        'parent_id',
        'likes_count',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Scope để lấy comment đã được duyệt
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope để lấy comment gốc (không phải reply)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
