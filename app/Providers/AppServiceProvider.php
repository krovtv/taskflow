<?php

namespace App\Providers;

use App\Notifications\Channels\TelegramChannel;
use App\Services\TelegramService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
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

        RateLimiter::for('auth', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('verification', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('password', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
    }
}
