<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_locked',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'remember_token',
        'sms_quota',
        'sms_used_this_month',
        'sms_balance',
        'sms_quota_reset_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_locked' => 'boolean',
        'last_login_at' => 'datetime',
        'sms_quota_reset_at' => 'datetime',
        'sms_quota' => 'integer',
        'sms_used_this_month' => 'integer',
        'sms_balance' => 'decimal:2'
    ];

    protected $attributes = [
        'role' => 'user',
        'is_locked' => false,
    ];

    protected $appends = [
        'initial',
        'role_display',
    ];

    // Get user's initials
    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    // Get role display name
    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Msimamizi',
            'accountant' => 'Mhasibu',
            'event_user' => 'Mratibu wa Tukio',
            'user' => 'Mtumiaji',
            default => 'Mtumiaji',
        };
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if user is accountant
    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    // Check if user can approve contributions
    public function canApproveContributions(): bool
    {
        return in_array($this->role, ['admin', 'accountant']);
    }

    // Check if user owns an event
    public function ownsEvent($event): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        // If $event is an ID, find the event first
        if (is_numeric($event)) {
            $event = Event::find($event);
        }
        
        if (!$event) {
            return false;
        }
        
        // Check if user is the event owner
        return $this->id === $event->user_id;
    }

    // Record login activity
    public function recordLogin(Request $request): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);
    }

    // Lock account after multiple failed attempts
    public function lockAccount(): void
    {
        $this->update(['is_locked' => true]);
    }

    // Unlock account
    public function unlockAccount(): void
    {
        $this->update(['is_locked' => false]);
    }

    // Check if account is locked
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    // Get user's events (as owner)
    public function ownedEvents()
    {
        return $this->hasMany(Event::class, 'user_id')->latest();
    }

    // Get assigned events (for accountants)
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_accountant', 'accountant_id', 'event_id')
                    ->withTimestamps();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'user';
            }
        });
    }
}