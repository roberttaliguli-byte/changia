<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'contributor_id',
        'amount',
        'payment_method',
        'proof',
        'status',
        'approved_by',
        'rejected_by',
        'approved_at',
        'rejected_at',
        'notes',
        'reference_number'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Payment method constants
     */
    const PAYMENT_CASH = 'cash';
    const PAYMENT_MPESA = 'mpesa';
    const PAYMENT_BANK = 'bank';
    const PAYMENT_PENDING = 'pending';

    /**
     * Get the contributor that owns the contribution
     */
    public function contributor(): BelongsTo
    {
        return $this->belongsTo(Contributor::class);
    }

    /**
     * Get the event through contributor (has-one-through relationship)
     */
    public function event(): HasOneThrough
    {
        return $this->hasOneThrough(
            Event::class,
            Contributor::class,
            'id',           // Foreign key on contributors table
            'id',           // Foreign key on events table
            'contributor_id', // Local key on contributions table
            'event_id'      // Local key on contributors table
        );
    }

    /**
     * Get the user who approved the contribution
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected the contribution
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Scope a query to only include pending contributions
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved contributions
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include rejected contributions
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope a query to only include contributions from a specific event
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->whereHas('contributor', function($q) use ($eventId) {
            $q->where('event_id', $eventId);
        });
    }

    /**
     * Scope a query to only include contributions by payment method
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to only include contributions within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Mark contribution as approved
     */
    public function approve($userId): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $userId,
            'approved_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null
        ]);
    }

    /**
     * Mark contribution as rejected
     */
    public function reject($userId, $reason = null): bool
    {
        $data = [
            'status' => self::STATUS_REJECTED,
            'rejected_by' => $userId,
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null
        ];
        
        if ($reason) {
            $data['notes'] = $reason;
        }
        
        return $this->update($data);
    }

    /**
     * Check if contribution is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if contribution is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if contribution is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' TSh';
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute(): string
    {
        return match($this->payment_method) {
            self::PAYMENT_CASH => 'Fedha Taslimu',
            self::PAYMENT_MPESA => 'M-Pesa',
            self::PAYMENT_BANK => 'Benki',
            self::PAYMENT_PENDING => 'Inasubiri',
            default => ucfirst($this->payment_method)
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Inasubiri',
            self::STATUS_APPROVED => 'Imethibitishwa',
            self::STATUS_REJECTED => 'Imekataliwa',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'fas fa-clock',
            self::STATUS_APPROVED => 'fas fa-check-circle',
            self::STATUS_REJECTED => 'fas fa-times-circle',
            default => 'fas fa-question-circle'
        };
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate reference number on creation
        static::creating(function ($contribution) {
            if (empty($contribution->reference_number)) {
                $contribution->reference_number = 'CHG-' . strtoupper(uniqid());
            }
        });
        
        // Log when contribution is created
        static::created(function ($contribution) {
            \Illuminate\Support\Facades\Log::info('Contribution created', [
                'id' => $contribution->id,
                'contributor_id' => $contribution->contributor_id,
                'amount' => $contribution->amount
            ]);
        });
        
        // Log when contribution is updated
        static::updated(function ($contribution) {
            if ($contribution->isDirty('status')) {
                \Illuminate\Support\Facades\Log::info('Contribution status changed', [
                    'id' => $contribution->id,
                    'old_status' => $contribution->getOriginal('status'),
                    'new_status' => $contribution->status,
                    'approved_by' => $contribution->approved_by
                ]);
            }
        });
    }
}