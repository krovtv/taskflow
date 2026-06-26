<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendPasswordResetCode extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Código de recuperação · KV Tech')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Recebemos uma solicitação de redefinição de senha da sua conta.')
            ->line('Seu código de recuperação é:')
            ->line("> **{$this->code}**")
            ->line('_Este código expira em 10 minutos._')
            ->line('Se você não solicitou, ignore este e-mail.')
            ->salutation('Equipe KV Tech');
    }
}
