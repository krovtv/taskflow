<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SlaWarningNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task,
        public int $percentage,
    ) {}

    public function via($notifiable): array
    {
        $channels = ['database', 'mail'];
        if ($notifiable->telegram_chat_id) {
            $channels[] = 'telegram';
        }
        return $channels;
    }

    public function toTelegram($notifiable): string
    {
        $cat = $this->task->category_label;
        return "⚠️ *Alerta de SLA* · {$this->percentage}%\n"
            . "Tarefa: {$this->task->title}\n"
            . "Categoria: {$cat}\n"
            . "Prazo: {$this->task->due_date->format('d/m/Y H:i')}\n"
            . "Prioridade: {$this->task->priority_label}\n"
            . route('tasks.show', $this->task);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'category' => $this->task->category_label,
            'due_date' => $this->task->due_date->format('d/m/Y H:i'),
            'percentage' => $this->percentage,
            'message' => "⚠️ SLA {$this->percentage}% · {$this->task->title}",
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("⚠️ SLA {$this->percentage}% · {$this->task->title}")
            ->greeting("Alerta de SLA")
            ->line("A tarefa **{$this->task->title}** atingiu **{$this->percentage}%** do tempo estimado até o prazo.")
            ->line("Prazo: {$this->task->due_date->format('d/m/Y H:i')}")
            ->line("Prioridade: {$this->task->priority_label}")
            ->action('Ver tarefa', route('tasks.show', $this->task));
    }
}
