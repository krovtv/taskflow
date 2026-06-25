<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class CheckSlaWarning extends Command
{
    protected $signature = 'tasks:check-sla {--threshold=70}';

    protected $description = 'Notifica tarefas cujo SLA (tempo decorrido entre criação e prazo) atingiu o percentual definido';

    public function handle(): int
    {
        $threshold = (int) $this->option('threshold');

        $tasks = Task::whereNull('sla_notified_at')
            ->where('status', '!=', Task::STATUS_CONCLUIDO)
            ->whereNotNull('due_date')
            ->get()
            ->filter(function (Task $task) use ($threshold) {
                return $task->dateProgress >= $threshold;
            });

        $count = 0;
        foreach ($tasks as $task) {
            $task->user->notify(new \App\Notifications\SlaWarningNotification(
                $task,
                $task->dateProgress,
            ));
            $task->update(['sla_notified_at' => now()]);
            $this->info("SLA: {$task->title} ({$task->dateProgress}%)");
            $count++;
        }

        $this->info("{$count} tarefa(s) notificada(s).");

        return Command::SUCCESS;
    }
}
