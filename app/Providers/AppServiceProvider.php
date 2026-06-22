<?php

namespace App\Providers;

use App\Notifications\Channels\TelegramChannel;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TelegramService::class);
    }

    public function boot(): void
    {
        Notification::extend('telegram', function ($app) {
            return new TelegramChannel($app->make(TelegramService::class));
        });
    }
}
