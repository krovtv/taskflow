<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskDueSoonNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

        if ($notifiable->telegram_chat_id) {
            $channels[] = 'telegram';
        }

        return $channels;
    }

    public function toTelegram(object $notifiable): string
    {
        $due = $this->task->due_date->format('d/m/Y H:i');
        $status = $this->task->isOverdue() ? '⚠️ ATRASADA' : '⏰ Próxima do prazo';

        return "{$status}\n\n"
            . "<b>{$this->task->title}</b>\n"
            . "{$this->task->category_label}\n\n"
            . "📅 Vencimento: {$due}\n"
            . "🏷 Prioridade: {$this->task->priority_label}\n\n"
            . "🔗 " . route('tasks.show', $this->task);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'category' => $this->task->category_label,
            'category_label' => $this->task->category_label,
            'due_date' => $this->task->due_date->toIso8601String(),
            'message' => "A tarefa \"{$this->task->title}\" vence em breve.",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Lembrete: tarefa próxima do prazo - KV Tech')
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Sua tarefa \"{$this->task->title}\" ({$this->task->category_label}) vence em:")
            ->line($this->task->due_date->format('d/m/Y H:i'))
            ->action('Ver tarefa', url('/tasks/'.$this->task->id.'/edit'))
            ->line('Organize-se com a KV Tech!');
    }
}
