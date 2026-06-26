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
        $code = $this->code;

        return (new MailMessage)
            ->subject('Código de verificação · KV Tech')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Recebemos uma solicitação de verificação da sua conta.')
            ->line('Use o código abaixo para confirmar seu e-mail:')
            ->line("> **{$code}**")
            ->line('_Este código expira em 10 minutos._')
            ->salutation('Equipe KV Tech');
    }
}
