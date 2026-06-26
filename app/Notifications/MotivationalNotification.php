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
        $channels = ['database'];
        if ($notifiable->telegram_chat_id) {
            $channels[] = 'telegram';
        }
        return $channels;
    }

    public function toTelegram($notifiable): string
    {
        return "{$this->emoji} <b>Mensagem motivacional</b>\n\n{$this->message}\n\nKeep going! 🚀";
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "{$this->emoji} {$this->message}",
            'type' => 'motivational',
        ];
    }
}
