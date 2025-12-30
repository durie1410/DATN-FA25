<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'support_user_id',
        'sender_type',
        'sender_name',
        'sender_email',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supportUser()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }
}

