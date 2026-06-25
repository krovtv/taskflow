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
        $due = $this->task->due_date->format('d/m/Y H:i');
        $pct = $this->percentage;
        return "⚠️ <b>Alerta de SLA · {$pct}%</b>\n\n"
            . "<b>{$this->task->title}</b>\n"
            . "{$this->task->category_label}\n\n"
            . "📅 Prazo: {$due}\n"
            . "🏷 Prioridade: {$this->task->priority_label}\n\n"
            . "🔗 " . route('tasks.show', $this->task);
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
