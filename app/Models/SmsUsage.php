<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsUsage extends Model
{
    use HasFactory;

    protected $table = 'sms_usage';

    protected $fillable = [
        'user_id',
        'event_id',
        'recipient_phone',
        'message_type',
        'message_length',
        'sms_count',
        'cost',
        'status',
        'response_data'
    ];

    protected $casts = [
        'response_data' => 'array',
        'cost' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}