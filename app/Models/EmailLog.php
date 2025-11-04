<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'email',
        'subject',
        'status',
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(EmailSubscriber::class, 'email', 'email');
    }

    // Scopes
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeOpened($query)
    {
        return $query->where('status', 'opened');
    }

    public function scopeClicked($query)
    {
        return $query->where('status', 'clicked');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['bounced', 'failed']);
    }

    public function scopeByCampaign($query, $campaignId)
    {
        return $query->where('campaign_id', $campaignId);
    }

    // Methods
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function markAsOpened()
    {
        $this->update([
            'status' => 'opened',
            'opened_at' => now(),
        ]);
    }

    public function markAsClicked()
    {
        $this->update([
            'status' => 'clicked',
            'clicked_at' => now(),
        ]);
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    public function markAsBounced()
    {
        $this->update([
            'status' => 'bounced',
        ]);
    }
}
