<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'template',
        'target_criteria',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'target_criteria' => 'array',
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(EmailLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    // Accessors & Mutators
    public function getOpenRateAttribute()
    {
        if ($this->sent_count == 0) return 0;
        return round(($this->opened_count / $this->sent_count) * 100, 2);
    }

    public function getClickRateAttribute()
    {
        if ($this->sent_count == 0) return 0;
        return round(($this->clicked_count / $this->sent_count) * 100, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'scheduled' => 'warning',
            'sending' => 'info',
            'sent' => 'success',
            'cancelled' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Methods
    public function canBeSent()
    {
        return in_array($this->status, ['draft', 'scheduled']) && 
               $this->scheduled_at <= now();
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function getRecipients()
    {
        $query = EmailSubscriber::where('status', 'active');

        if ($this->target_criteria) {
            foreach ($this->target_criteria as $key => $value) {
                if ($key === 'tags' && is_array($value)) {
                    $query->whereJsonContains('tags', $value);
                } elseif ($key === 'preferences' && is_array($value)) {
                    foreach ($value as $pref => $prefValue) {
                        $query->whereJsonContains('preferences->' . $pref, $prefValue);
                    }
                }
            }
        }

        return $query->get();
    }
}
