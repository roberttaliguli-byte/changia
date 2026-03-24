<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'phone',
        'email',
        'promised_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'registration_method',
        'registered_at',
        'notes'
    ];

    protected $casts = [
        'promised_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'registered_at' => 'datetime'
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PARTIAL = 'partial';
    const STATUS_COMPLETED = 'completed';

    /**
     * Registration method constants
     */
    const REGISTRATION_MANUAL = 'manual';
    const REGISTRATION_SMS = 'sms';
    const REGISTRATION_WHATSAPP = 'whatsapp';

    /**
     * Get the event that this contributor belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get all contributions for this contributor
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    /**
     * Get approved contributions
     */
    public function approvedContributions(): HasMany
    {
        return $this->hasMany(Contribution::class)->where('status', Contribution::STATUS_APPROVED);
    }

    /**
     * Get pending contributions
     */
    public function pendingContributions(): HasMany
    {
        return $this->hasMany(Contribution::class)->where('status', Contribution::STATUS_PENDING);
    }

    /**
     * Get total amount contributed (approved only)
     */
    public function getTotalContributedAttribute(): float
    {
        return $this->approvedContributions()->sum('amount');
    }

    /**
     * Get total pending amount
     */
    public function getTotalPendingAttribute(): float
    {
        return $this->pendingContributions()->sum('amount');
    }

    /**
     * Update remaining amount based on approved contributions
     */
    public function updateRemainingAmount(): void
    {
        $paid = $this->approvedContributions()->sum('amount');
        
        $this->update([
            'paid_amount' => $paid,
            'remaining_amount' => max(0, $this->promised_amount - $paid),
            'status' => $this->determineStatus($paid)
        ]);
    }

    /**
     * Determine contributor status based on payment
     */
    private function determineStatus(float $paid): string
    {
        if ($paid >= $this->promised_amount) {
            return self::STATUS_COMPLETED;
        }
        
        if ($paid > 0) {
            return self::STATUS_PARTIAL;
        }
        
        return self::STATUS_PENDING;
    }

    /**
     * Check if contributor has completed payment
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if contributor has partial payment
     */
    public function isPartial(): bool
    {
        return $this->status === self::STATUS_PARTIAL;
    }

    /**
     * Check if contributor has pending payment
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Get formatted promised amount
     */
    public function getFormattedPromisedAmountAttribute(): string
    {
        return number_format($this->promised_amount, 2) . ' TSh';
    }

    /**
     * Get formatted paid amount
     */
    public function getFormattedPaidAmountAttribute(): string
    {
        return number_format($this->paid_amount, 2) . ' TSh';
    }

    /**
     * Get formatted remaining amount
     */
    public function getFormattedRemainingAmountAttribute(): string
    {
        return number_format($this->remaining_amount, 2) . ' TSh';
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Bado',
            self::STATUS_PARTIAL => 'Sehemu',
            self::STATUS_COMPLETED => 'Imekamilika',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-secondary',
            self::STATUS_PARTIAL => 'bg-info text-dark',
            self::STATUS_COMPLETED => 'bg-success',
            default => 'bg-secondary'
        };
    }

    /**
     * Get registration method display name
     */
    public function getRegistrationMethodDisplayAttribute(): string
    {
        return match($this->registration_method) {
            self::REGISTRATION_MANUAL => 'Kwa Mkono',
            self::REGISTRATION_SMS => 'Kwa SMS',
            self::REGISTRATION_WHATSAPP => 'Kwa WhatsApp',
            default => ucfirst($this->registration_method)
        };
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): int
    {
        if ($this->promised_amount <= 0) {
            return 0;
        }
        
        return min(round(($this->paid_amount / $this->promised_amount) * 100), 100);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-set registered_at on creation
        static::creating(function ($contributor) {
            if (empty($contributor->registered_at)) {
                $contributor->registered_at = now();
            }
            
            if (empty($contributor->registration_method)) {
                $contributor->registration_method = self::REGISTRATION_MANUAL;
            }
        });
        
        // Update remaining amount when contributions change
        static::updated(function ($contributor) {
            if ($contributor->isDirty('paid_amount')) {
                \Illuminate\Support\Facades\Log::info('Contributor payment updated', [
                    'id' => $contributor->id,
                    'paid_amount' => $contributor->paid_amount,
                    'remaining_amount' => $contributor->remaining_amount
                ]);
            }
        });
    }
}