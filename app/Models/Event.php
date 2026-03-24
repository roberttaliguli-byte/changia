<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_name',
        'event_date',
        'target_amount',
        'description',
        'event_type',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date',
        'target_amount' => 'decimal:2'
    ];

    /**
     * Get the user that owns the event
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all contributors for this event
     */
    public function contributors()
    {
        return $this->hasMany(Contributor::class);
    }

    /**
     * Get all contributions through contributors
     */
    public function contributions()
    {
        return $this->hasManyThrough(Contribution::class, Contributor::class, 'event_id', 'contributor_id');
    }

    /**
     * Get pending contributions (specify the table)
     */
    public function pendingContributions()
    {
        return $this->hasManyThrough(Contribution::class, Contributor::class, 'event_id', 'contributor_id')
                    ->where('contributions.status', 'pending'); // Specify table name
    }

    /**
     * Get approved contributions (specify the table)
     */
    public function approvedContributions()
    {
        return $this->hasManyThrough(Contribution::class, Contributor::class, 'event_id', 'contributor_id')
                    ->where('contributions.status', 'approved'); // Specify table name
    }

    /**
     * Get accountants assigned to this event
     * This relationship links events to accountant users
     */
    public function accountants()
    {
        return $this->belongsToMany(User::class, 'event_accountant', 'event_id', 'accountant_id')
                    ->where('users.role', 'accountant')
                    ->withTimestamps();
    }

    /**
     * Calculate total collected amount (only approved)
     */
    public function getTotalCollectedAttribute()
    {
        return $this->contributions()
                    ->where('contributions.status', 'approved') // Specify table name
                    ->sum('contributions.amount');
    }

    /**
     * Get contribution count
     */
    public function getContributionCountAttribute()
    {
        return $this->contributions()->count();
    }

    /**
     * Get contributor count
     */
    public function getContributorCountAttribute()
    {
        return $this->contributors()->count();
    }

    /**
     * Check if event target is reached
     */
    public function getIsTargetReachedAttribute()
    {
        if (!$this->target_amount || $this->target_amount == 0) {
            return false;
        }
        return $this->total_collected >= $this->target_amount;
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        if (!$this->target_amount || $this->target_amount == 0) {
            return 0;
        }
        return min(round(($this->total_collected / $this->target_amount) * 100), 100);
    }
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($event) {
            foreach ($event->contributors as $contributor) {
                $contributor->contributions()->delete();
                $contributor->delete();
            }
        });
    }
}