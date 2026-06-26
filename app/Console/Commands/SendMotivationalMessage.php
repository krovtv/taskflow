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
            'Levante, brilhe e faça acontecer. O mundo não espera.',
            'Cada dia é uma nova chance para mudar sua história.',
            'Você está mais perto do que imagina. Não desista agora.',
            'O sucesso é a soma de pequenos esforços repetidos dia após dia.',
            'Acredite no processo. Tudo está se encaixando no tempo certo.',
            'Seu único concorrente é você mesmo. Supere-se.',
            'Faça hoje o que outros não querem, para ter amanhã o que outros não têm.',
            'O talento vence jogos, mas o trabalho em equipe vence campeonatos.',
            'Não espere pelo momento perfeito. Crie o momento.',
            'A jornada de mil milhas começa com um único passo.',
            'Seja a versão mais corajosa de você hoje.',
            'O único lugar onde o sucesso vem antes do trabalho é no dicionário.',
            'Pare de duvidar e comece a confiar no seu potencial.',
            'Grandes conquistas exigem grandes riscos. Vá em frente.',
            'Você é capaz de coisas incríveis. Acredite nisso.',
            'O esforço de hoje é a força de amanhã. Continue.',
            'Não deixe para depois o que pode fazer agora. O agora importa.',
            'Transforme seus medos em combustível para crescer.',
            'Você veio longe. Não pare agora. O melhor ainda está por vir.',
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
