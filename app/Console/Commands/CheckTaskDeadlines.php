<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDueSoonNotification;
use Illuminate\Console\Command;

class CheckTaskDeadlines extends Command
{
    /**
     * Executa: php artisan tasks:check-deadlines
     */
    protected $signature = 'tasks:check-deadlines {--hours=24 : Janela de horas até o prazo}';

    protected $description = 'Verifica tarefas próximas do prazo e envia notificações (lembretes) aos usuários';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $count = 0;

        $tasks = Task::with('user')
            ->whereNull('notified_at')
            ->where('status', '!=', Task::STATUS_CONCLUIDO)
            ->where(function ($q) use ($hours) {
                $q->whereBetween('due_date', [now(), now()->addHours($hours)])
                  ->orWhere('due_date', '<', now());
            })
            ->get();

        foreach ($tasks as $task) {
            try {
                $task->user->notify(new TaskDueSoonNotification($task));
            } catch (\Throwable $e) {
                $this->error("Falha ao notificar \"{$task->title}\": {$e->getMessage()}");
            }
            $task->update(['notified_at' => now()]);
            $this->line("Notificação enviada: \"{$task->title}\" (usuário #{$task->user_id})");
            $count++;
        }

        $this->info("Total de notificações enviadas: {$count}");

        return self::SUCCESS;
    }
}
