<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'user_id', 
        'card_type', 
        'title', 
        'groom_name', 
        'bride_name', 
        'honoree_name',
        'event_date', 
        'event_time', 
        'location', 
        'description', 
        'suggested_amount',
        'contact_phone', 
        'contact_email', 
        'card_image_path', 
        'share_link', 
        'views', 
        'shares',
        // Admin fields
        'admin_status',
        'admin_notes',
        'admin_processed_at',
        'processed_by',
        'design_file_path',
        'design_cost'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'suggested_amount' => 'decimal:2',
        'design_cost' => 'decimal:2',
        'admin_processed_at' => 'datetime'
    ];

    /**
     * Get the user who created this card
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed this card
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get admin status badge HTML
     */
    public function getAdminStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge-status badge-pending">Inasubiri</span>',
            'approved' => '<span class="badge-status badge-approved">Imeidhinishwa</span>',
            'rejected' => '<span class="badge-status badge-rejected">Imekataliwa</span>',
            'completed' => '<span class="badge-status badge-completed">Imekamilika</span>'
        ];
        
        return $badges[$this->admin_status] ?? $badges['pending'];
    }

    /**
     * Get admin status text
     */
    public function getAdminStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Inasubiri',
            'approved' => 'Imeidhinishwa',
            'rejected' => 'Imekataliwa',
            'completed' => 'Imekamilika'
        ];
        
        return $statuses[$this->admin_status] ?? 'Inasubiri';
    }

    /**
     * Get card type badge
     */
    public function getCardTypeBadgeAttribute()
    {
        if ($this->card_type === 'invitation') {
            return '<span class="badge-status" style="background:#FFF3E0; color:#FF6F00;">📨 Mwaliko</span>';
        }
        return '<span class="badge-status" style="background:#D1FAE5; color:#10B981;">🤝 Mchango</span>';
    }

    /**
     * Get card type text
     */
    public function getCardTypeTextAttribute()
    {
        return $this->card_type === 'invitation' ? 'Mwaliko' : 'Ombi la Mchango';
    }

    /**
     * Check if card is pending
     */
    public function isPending()
    {
        return $this->admin_status === 'pending';
    }

    /**
     * Check if card is approved
     */
    public function isApproved()
    {
        return $this->admin_status === 'approved';
    }

    /**
     * Check if card is rejected
     */
    public function isRejected()
    {
        return $this->admin_status === 'rejected';
    }

    /**
     * Check if card is completed
     */
    public function isCompleted()
    {
        return $this->admin_status === 'completed';
    }

    /**
     * Scope for pending cards
     */
    public function scopePending($query)
    {
        return $query->where('admin_status', 'pending');
    }

    /**
     * Scope for approved cards
     */
    public function scopeApproved($query)
    {
        return $query->where('admin_status', 'approved');
    }

    /**
     * Scope for completed cards
     */
    public function scopeCompleted($query)
    {
        return $query->where('admin_status', 'completed');
    }

    /**
     * Scope for rejected cards
     */
    public function scopeRejected($query)
    {
        return $query->where('admin_status', 'rejected');
    }

    /**
     * Get event display name (for invitation cards)
     */
    public function getEventDisplayNameAttribute()
    {
        if ($this->card_type === 'invitation') {
            if ($this->groom_name && $this->bride_name) {
                return $this->groom_name . ' & ' . $this->bride_name;
            } elseif ($this->honoree_name) {
                return $this->honoree_name;
            }
        }
        return $this->honoree_name ?? $this->title;
    }

    /**
     * Increment views counter
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Increment shares counter
     */
    public function incrementShares()
    {
        $this->increment('shares');
    }
}