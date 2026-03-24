<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'initial',
        'role_display',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    /**
     * Get all events created by this user (as event owner)
     */
    public function ownedEvents()
    {
        return $this->hasMany(Event::class, 'user_id')->latest();
    }

    /**
     * Get all events this user is assigned to (as accountant)
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_accountant', 'accountant_id', 'event_id')
                    ->withTimestamps();
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Msimamizi',
            'accountant' => 'Mhasibu',
            default => 'Mtumiaji'
        };
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return in_array($this->role, ['user', 'event_user', null]);
    }

    public function canApproveContributions(): bool
    {
        return $this->isAdmin() || $this->isAccountant();
    }

    public function ownsEvent(Event $event): bool
    {
        return $this->id === $event->user_id;
    }

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