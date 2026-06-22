<?php

namespace App\Notifications\Channels;

use App\Services\TelegramService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TelegramChannel
{
    public function __construct(protected TelegramService $telegram)
    {
    }

    public function send(object $notifiable, Notification $notification): void
    {
        if (! $chatId = $notifiable->telegram_chat_id) {
            return;
        }

        try {
            $message = $notification->toTelegram($notifiable);
            $this->telegram->sendMessage($chatId, $message);
        } catch (\Throwable $e) {
            Log::warning('TelegramChannel: falha ao enviar notificação', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
