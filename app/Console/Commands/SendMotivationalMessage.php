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
            'O sucesso não é definitivo, o fracasso não é fatal: o que importa é a coragem de continuar.',
            'Você é o autor da sua própria história. Escreva algo incrível.',
            'Não importa o quão devagar você vá, desde que não pare.',
            'A persistência é o caminho do êxito.',
            'Se você pode sonhar, você pode fazer.',
            'O otimismo é a fé que leva à realização.',
            'A diferença entre o ordinário e o extraordinário é esse pequeno extra.',
            'Toda grande jornada começa com um primeiro passo corajoso.',
            'Você tem poder sobre sua mente, não sobre os eventos. Perceba isso e encontrará força.',
            'A vida é 10% do que acontece conosco e 90% de como reagimos.',
            'O conhecimento fala, mas a sabedoria ouve.',
            'A melhor vingança é o sucesso em massa.',
            'O fracasso é a oportunidade de começar de novo com mais inteligência.',
            'Você nunca sabe a força que tem até que a sua força é a única opção.',
            'Acredite que você pode e você já está no meio do caminho.',
            'O que você faz hoje pode melhorar todos os seus amanhãs.',
            'A ação é a chave fundamental para todo sucesso.',
            'Grandes mentes discutem ideias; mentes medianas discutem eventos; mentes pequenas discutem pessoas.',
            'Seu tempo é limitado, não o desperdice vivendo a vida de outra pessoa.',
            'Você não pode cruzar o mar apenas olhando para a água.',
            'Não importa o que você fez, mas o que você vai fazer a partir de agora.',
            'A dificuldade é a desculpa que a história nunca aceita.',
            'A melhor maneira de prever o futuro é criá-lo.',
            'Quando uma porta da felicidade se fecha, outra se abre.',
            'A verdadeira viagem da descoberta consiste não em buscar novas paisagens, mas em ter novos olhos.',
            'Tudo o que você sempre quis está do outro lado do medo.',
            'Se você não sair da sua zona de conforto, não vai aprender nada.',
            'O único modo de fazer um excelente trabalho é amar o que você faz.',
            'Sua atitude, não sua aptidão, determinará sua altitude.',
            'Valorize as pequenas coisas, pois um dia você olhará para trás e perceberá que elas eram as grandes.',
            'Não há atalhos para qualquer lugar que vale a pena ir.',
            'Seja a mudança que você quer ver no mundo.',
            'O que quer que você faça, faça bem feito.',
            'O importante não é vencer todos os dias, mas lutar todos os dias.',
            'Foco é a ponte entre a ideia e a realização.',
            'Tudo parece impossível até que seja feito.',
            'O sucesso consiste em ir de fracasso em fracasso sem perder o entusiasmo.',
            'Não se trata de ter tempo, se trata de fazer tempo.',
            'Excelência não é uma habilidade, é uma atitude.',
            'Cada novo dia é uma tela em branco. Pinte algo bonito.',
            'A felicidade não é algo pronto. Ela vem das suas próprias ações.',
            'Ouse sonhar, ouse tentar, ouse vencer.',
            'Sua única competição é a versão de ontem de você mesmo.',
            'O trabalho duro supera o talento quando o talento não trabalha duro.',
            'Não é sobre ter tempo, é sobre fazer escolhas.',
            'Quem não arrisca, não petisca.',
            'Progresso, não perfeição.',
            'A gratidão transforma o que temos em suficiente.',
            'Respire fundo, dê o seu melhor e confie no processo.',
            'O sucesso é uma jornada, não um destino.',
            'O começo é sempre hoje.',
            'Mude seus pensamentos e você mudará seu mundo.',
            'A consistência supera a intensidade.',
            'Seu potencial é infinito. Apenas comece.',
            'Não tenha medo de recomeçar. É uma chance de construir algo ainda melhor.',
            'O segredo da felicidade é fazer o que você ama.',
            'Pessoas fortes criam dias melhores. Dias melhores criam pessoas fortes.',
            'O sol nasce para todos. A diferença é quem acorda para aproveitá-lo.',
            'Cada momento é uma oportunidade de fazer diferente.',
            'A vida não é esperar a tempestade passar, é aprender a dançar na chuva.',
            'A vontade de vencer é importante, mas a vontade de se preparar é essencial.',
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
