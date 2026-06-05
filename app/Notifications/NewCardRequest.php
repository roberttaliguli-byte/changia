<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCardRequest extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->data['type'],
            'card_id' => $this->data['card_id'],
            'card_title' => $this->data['card_title'],
            'card_type' => $this->data['card_type'],
            'user_name' => $this->data['user_name'],
            'user_phone' => $this->data['user_phone'],
            'message' => $this->data['message'],
            'url' => $this->data['url']
        ];
    }
}