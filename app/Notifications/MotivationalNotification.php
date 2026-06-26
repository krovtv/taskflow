<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MotivationalNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $message,
        public string $emoji,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "{$this->emoji} {$this->message}",
            'type' => 'motivational',
        ];
    }
}
