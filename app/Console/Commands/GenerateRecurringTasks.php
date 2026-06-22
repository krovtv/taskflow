<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class GenerateRecurringTasks extends Command
{
    protected $signature = 'tasks:generate-recurring';
    protected $description = 'Gera novas instâncias de tarefas recorrentes vencidas';

    public function handle(): int
    {
        $tasks = Task::recurringDue()->get();
        $generated = 0;

        foreach ($tasks as $task) {
            $nextDate = $task->getNextRecurringDueDate();
            if (!$nextDate) continue;

            $repeated = $task->replicate()->fill([
                'due_date' => $nextDate,
                'status' => Task::STATUS_PENDENTE,
                'progress' => 0,
                'notified_at' => null,
            ]);
            $repeated->save();

            $task->update(['status' => Task::STATUS_CONCLUIDO]);

            $generated++;
        }

        $this->info("{$generated} tarefa(s) recorrente(s) gerada(s).");
        return Command::SUCCESS;
    }
}
