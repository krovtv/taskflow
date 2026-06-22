<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    public function sendMessage(int|string $chatId, string $text, array $options = []): bool
    {
        if (empty($this->token)) {
            Log::warning('Telegram: BOT_TOKEN não configurado.');
            return false;
        }

        $response = Http::timeout(10)->post("{$this->apiUrl}/sendMessage", array_merge([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
        ], $options));

        if ($response->failed()) {
            Log::error('Telegram: falha ao enviar mensagem', [
                'chat_id' => $chatId,
                'error' => $response->body(),
            ]);
            return false;
        }

        return true;
    }

    public function getMe(): ?array
    {
        if (empty($this->token)) return null;

        $response = Http::timeout(5)->get("{$this->apiUrl}/getMe");

        if ($response->successful()) {
            return $response->json('result');
        }

        return null;
    }
}
