<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendMotivationalMessage extends Command
{
    protected $signature = 'motivational:send';

    protected $description = 'Envia mensagem motivacional para todos os usuários (DB + Telegram)';

    protected array $messages = [
        'emoji' => ['💪', '🔥', '🚀', '🌟', '✨', '🎯', '⚡', '💡', '🏆', '🌈', '🦅', '🌻'],
        'phrases' => [
            'Você é mais forte do que pensa e mais capaz do que imagina.',
            'Cada passo, por menor que seja, te aproxima do seu objetivo.',
            'O segredo do sucesso é começar. E você já começou!',
            'Não pare quando estiver cansado. Pare quando tiver terminado.',
            'Você não precisa ser extremo, apenas consistente.',
            'Pequenas vitórias diárias levam a grandes resultados.',
            'Acredite no seu potencial. Você está mais preparado do que pensa.',
            'Hoje é mais um dia para fazer acontecer. Vamos nessa!',
            'Disciplina é lembrar o que você quer. Motivação é passageira.',
            'Não compare seu capítulo 1 com o capítulo 10 de outra pessoa.',
            'O impossível é só uma opinião. Siga em frente.',
            'Respira fundo, organiza as ideias e vai. Você consegue.',
            'Cada tarefa concluída é um degrau a mais rumo à grandeza.',
            'Foco no processo, não no resultado. O resultado vem.',
            'Seus hábitos de hoje moldam seu amanhã. Continue firme.',
            'Você já superou dias difíceis antes. Esse não será diferente.',
            'O progresso é invisível quando você olha de perto. Confie no processo.',
            'Não espere pela motivação. Crie disciplina e ela virá.',
            'Fazer o simples com excelência já te coloca à frente.',
            'O melhor momento para agir foi ontem. O segundo melhor é agora.',
        ],
    ];

    public function handle(): int
    {
        $emoji = $this->messages['emoji'][array_rand($this->messages['emoji'])];
        $phrase = $this->messages['phrases'][array_rand($this->messages['phrases'])];

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\MotivationalNotification($phrase, $emoji));
            $this->line("Motivacional enviado para {$user->email}");
            $count++;
        }

        $this->info("Mensagem motivacional enviada para {$count} usuário(s).");

        return Command::SUCCESS;
    }
}
