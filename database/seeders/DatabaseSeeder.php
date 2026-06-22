<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'kvbaoop@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Kvba0809@@'),
            ]
        );

        $categorias = $user->categories()->pluck('id', 'name');

        if ($categorias->isEmpty()) {
            foreach (['Estudos' => 'blue', 'Trabalho' => 'amber', 'Projetos' => 'purple'] as $nome => $cor) {
                $cat = $user->categories()->create(['name' => $nome, 'color' => $cor]);
                $categorias[$nome] = $cat->id;
            }
        }

        $status = array_keys(Task::STATUSES);

        $exemplos = [
            ['title' => 'Estudar Eloquent avançado', 'cat' => 'Estudos', 'old_cat' => 'estudos', 'days' => 1],
            ['title' => 'Revisar anotações de Inglês', 'cat' => 'Estudos', 'old_cat' => 'estudos', 'days' => 3],
            ['title' => 'Concluir curso de Laravel', 'cat' => 'Estudos', 'old_cat' => 'estudos', 'days' => 7],
            ['title' => 'Finalizar layout do sistema KV Tech', 'cat' => 'Projetos', 'old_cat' => 'projetos', 'days' => 2],
            ['title' => 'Configurar deploy do projeto pessoal', 'cat' => 'Projetos', 'old_cat' => 'projetos', 'days' => 5],
            ['title' => 'Reunião de alinhamento com cliente', 'cat' => 'Trabalho', 'old_cat' => 'trabalho', 'days' => 1],
            ['title' => 'Entregar relatório mensal', 'cat' => 'Trabalho', 'old_cat' => 'trabalho', 'days' => 4],
            ['title' => 'Planejar sprint da próxima semana', 'cat' => 'Trabalho', 'old_cat' => 'trabalho', 'days' => 6],
        ];

        foreach ($exemplos as $i => $exemplo) {
            Task::create([
                'user_id' => $user->id,
                'title' => $exemplo['title'],
                'description' => 'Tarefa de exemplo gerada pelo seeder do KV Tech Organizer.',
                'category' => $exemplo['old_cat'],
                'category_id' => $categorias[$exemplo['cat']] ?? null,
                'due_date' => now()->addDays($exemplo['days'])->setTime(14, 0),
                'status' => $status[$i % count($status)],
            ]);
        }

        Task::create([
            'user_id' => $user->id,
            'title' => 'Tarefa atrasada de exemplo',
            'description' => 'Esta tarefa está com o prazo vencido para fins de demonstração.',
            'category' => 'projetos',
            'category_id' => $categorias['Projetos'] ?? null,
            'due_date' => now()->subDays(2),
            'status' => Task::STATUS_PENDENTE,
        ]);
    }
}
