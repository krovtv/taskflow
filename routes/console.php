<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Agendamento: verificação de prazos de tarefas
|--------------------------------------------------------------------------
| Roda a cada hora, verificando tarefas que vencem nas próximas 24h
| e que ainda não foram notificadas, disparando o lembrete interno
| (Laravel Notifications - canais database + mail + telegram).
|
| Para que o agendador funcione, é necessário manter o cron do sistema
| chamando "php artisan schedule:run" a cada minuto, OU em desenvolvimento
| rodar manualmente: php artisan schedule:work
*/
Schedule::command('tasks:check-deadlines --hours=24')->hourly();
Schedule::command('tasks:check-sla --threshold=70')->everyThirtyMinutes();
Schedule::command('tasks:generate-recurring')->dailyAt('06:00');
