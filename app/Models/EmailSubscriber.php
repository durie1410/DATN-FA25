<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status',
        'preferences',
        'tags',
        'subscribed_at',
        'unsubscribed_at',
        'source',
        'user_id',
    ];

    protected $casts = [
        'preferences' => 'array',
        'tags' => 'array',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(EmailLog::class, 'email', 'email');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    public function scopeWithTags($query, $tags)
    {
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', $tag);
            }
        } else {
            $query->whereJsonContains('tags', $tags);
        }
        return $query;
    }

    // Methods
    public function unsubscribe()
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }

    public function resubscribe()
    {
        $this->update([
            'status' => 'active',
            'unsubscribed_at' => null,
        ]);
    }

    public function addTag($tag)
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    public function removeTag($tag)
    {
        $tags = $this->tags ?? [];
        $tags = array_filter($tags, function($t) use ($tag) {
            return $t !== $tag;
        });
        $this->update(['tags' => array_values($tags)]);
    }

    public function hasTag($tag)
    {
        return in_array($tag, $this->tags ?? []);
    }
}
