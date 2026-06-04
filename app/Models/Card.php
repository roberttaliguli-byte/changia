<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'user_id', 'card_type', 'title', 'groom_name', 'bride_name', 'honoree_name',
        'event_date', 'event_time', 'location', 'description', 'suggested_amount',
        'contact_phone', 'contact_email', 'card_image_path', 'share_link', 'views', 'shares'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'suggested_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}