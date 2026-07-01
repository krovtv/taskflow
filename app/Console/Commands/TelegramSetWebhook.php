<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:webhook {url? : URL pública do webhook (ex: https://dominio.com/telegram/webhook)}';

    protected $description = 'Registra ou remove o webhook do Telegram';

    public function handle(TelegramService $telegram): int
    {
        $url = $this->argument('url');

        if ($url) {
            $result = $telegram->setWebhook($url);

            if ($result['ok'] ?? false) {
                $this->info("Webhook registrado: {$url}");
                return Command::SUCCESS;
            }

            $this->error("Falha: " . ($result['description'] ?? 'Erro desconhecido'));
            return Command::FAILURE;
        }

        $current = $telegram->getMe();

        if ($current) {
            $this->line("Bot: @{$current['username']} ({$current['first_name']})");
        }

        $this->line("Uso: php artisan telegram:webhook https://seudominio.com/telegram/webhook");
        $this->line("Para remover: php artisan telegram:webhook --remove");

        return Command::SUCCESS;
    }
}
