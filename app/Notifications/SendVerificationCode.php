<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendVerificationCode extends Notification
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
            ->subject('Código de verificação · KV Tech')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Seu código de verificação é:')
            ->line("**{$this->code}**")
            ->line('Este código expira em 10 minutos.')
            ->salutation('Equipe KV Tech');
    }
}
