<?php

namespace App\Console\Commands;

use App\Models\StudyFlashcard;
use App\Models\StudySpecialization;
use App\Models\User;
use Illuminate\Console\Command;

class SeedLinuxFlashcards extends Command
{
    protected $signature = 'flashcards:seed-linux {user? : ID do usuário}';
    protected $description = 'Cria a especialização Linux com 25 flashcards de comandos';

    public function handle(): int
    {
        $userId = $this->argument('user');

        if (!$userId) {
            $users = User::all(['id', 'name', 'email']);
            if ($users->isEmpty()) {
                $this->error('Nenhum usuário encontrado.');
                return self::FAILURE;
            }
            $userId = $this->choice('Qual usuário?', $users->pluck('name', 'id')->map(fn($n, $id) => "$id - $n")->values()->toArray());
            $userId = (int) explode(' - ', $userId)[0];
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuário $userId não encontrado.");
            return self::FAILURE;
        }

        $spec = StudySpecialization::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Linux'],
            ['color' => 'emerald', 'description' => 'Comandos essenciais do Linux']
        );

        if ($spec->wasRecentlyCreated) {
            $this->info("Especialização 'Linux' criada.");
        } else {
            $this->info("Especialização 'Linux' já existe.");
        }

        $flashcards = [
            ['front' => 'Como descobrir em qual diretório você está?', 'back' => "pwd\n\nMostra o caminho absoluto do diretório atual."],
            ['front' => 'Como listar arquivos e diretórios?', 'back' => "ls\n\nMais usados:\nls -l    # formato detalhado\nls -la   # mostra arquivos ocultos\nls -lh   # tamanhos legíveis"],
            ['front' => 'Como entrar em um diretório?', 'back' => "cd /caminho\n\nExemplos:\ncd /var/log\ncd ..\ncd ~"],
            ['front' => 'Como criar um diretório?', 'back' => "mkdir nome_diretorio\n\nCriando vários níveis:\nmkdir -p /tmp/teste/pasta1"],
            ['front' => 'Como criar um arquivo vazio?', 'back' => "touch arquivo.txt"],
            ['front' => 'Como copiar arquivos?', 'back' => "cp origem destino\n\nExemplos:\ncp teste.txt /tmp/\ncp -r pasta1 pasta2\n\n-r = copia diretórios recursivamente."],
            ['front' => 'Como mover ou renomear arquivos?', 'back' => "mv arquivo.txt novo_nome.txt\n\nMover:\nmv arquivo.txt /tmp/"],
            ['front' => 'Como apagar arquivos e diretórios?', 'back' => "Arquivo:\nrm arquivo.txt\n\nDiretório:\nrm -rf pasta/\n\nCuidado:\nrm -rf /\n\nEsse comando pode destruir o sistema inteiro."],
            ['front' => 'Como visualizar o conteúdo de um arquivo?', 'back' => "cat arquivo.txt\n\nOu:\nless arquivo.txt\n\nPara acompanhar logs:\ntail -f arquivo.log"],
            ['front' => 'Como procurar texto em arquivos?', 'back' => "grep \"palavra\" arquivo.txt\n\nExemplo:\ngrep \"ERROR\" /var/log/syslog\n\nIgnorando maiúsculas:\ngrep -i error arquivo.txt"],
            ['front' => 'Como descobrir espaço em disco?', 'back' => "df -h\n\nExemplo:\nFilesystem      Size Used Avail Use%\n/dev/sda1        50G  20G   28G  42%"],
            ['front' => 'Como descobrir quais pastas ocupam mais espaço?', 'back' => "du -sh *\n\nMais usado:\ndu -sh /var/*\n\nOrdenando:\ndu -sh * | sort -hr"],
            ['front' => 'Como ver processos em execução?', 'back' => "ps aux\n\nOu:\ntop\n\nVersão melhor:\nhtop"],
            ['front' => 'Como matar um processo?', 'back' => "Primeiro descubra o PID:\nps aux | grep nginx\n\nDepois:\nkill PID\n\nForçando:\nkill -9 PID"],
            ['front' => 'Como verificar portas abertas?', 'back' => "ss -tulpn\n\nOu:\nnetstat -tulpn\n\nExemplo:\nss -tulpn | grep 3306"],
            ['front' => 'Como testar conectividade?', 'back' => "ping google.com\n\nTestar porta:\ntelnet ip porta\nou\nnc -zv ip porta\n\nExemplo:\nnc -zv 192.168.1.10 3306"],
            ['front' => 'Como ver os serviços do systemd?', 'back' => "systemctl status nome_servico\n\nExemplo:\nsystemctl status mariadb"],
            ['front' => 'Como iniciar, parar e reiniciar serviços?', 'back' => "Iniciar:\nsystemctl start nginx\n\nParar:\nsystemctl stop nginx\n\nReiniciar:\nsystemctl restart nginx"],
            ['front' => 'Como ver logs de serviços?', 'back' => "journalctl -u nome_servico\n\nExemplo:\njournalctl -u mariadb\n\nTempo real:\njournalctl -fu mariadb"],
            ['front' => 'Como descobrir seu IP?', 'back' => "ip a\n\nOu:\nhostname -I"],
            ['front' => 'Como alterar permissões?', 'back' => "chmod 755 arquivo\n\nSignificado:\n7 = rwx\n5 = r-x\n5 = r-x\n\nExemplo:\nchmod +x script.sh\n\nTorna o arquivo executável."],
            ['front' => 'Como alterar dono e grupo?', 'back' => "chown usuario:grupo arquivo\n\nExemplo:\nchown www-data:www-data index.php\n\nRecursivo:\nchown -R www-data:www-data /var/www"],
            ['front' => 'Como compactar arquivos?', 'back' => "Criar:\ntar -czvf backup.tar.gz pasta/\n\nExtrair:\ntar -xzvf backup.tar.gz"],
            ['front' => 'Como encontrar arquivos?', 'back' => "find / -name arquivo.txt\n\nExemplo:\nfind /var -name \"*.log\""],
            ['front' => 'Como executar um comando como root?', 'back' => "sudo comando\n\nExemplo:\nsudo systemctl restart mariadb\n\nVirar root:\nsudo su -"],
        ];

        $created = 0;
        foreach ($flashcards as $card) {
            StudyFlashcard::firstOrCreate(
                ['study_specialization_id' => $spec->id, 'user_id' => $user->id, 'front' => $card['front']],
                ['back' => $card['back'], 'next_review_at' => now()]
            );
            $created++;
        }

        $this->info("$created flashcards inseridos em 'Linux'.");
        return self::SUCCESS;
    }
}
