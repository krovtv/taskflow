<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total' => $user->tasks()->count(),
            'pendentes' => $user->tasks()->where('status', Task::STATUS_PENDENTE)->count(),
            'andamento' => $user->tasks()->where('status', Task::STATUS_ANDAMENTO)->count(),
            'concluidas' => $user->tasks()->where('status', Task::STATUS_CONCLUIDO)->count(),
            'atrasadas' => $user->tasks()->overdue()->count(),
            'concluidas_semana' => $user->tasks()
                ->where('status', Task::STATUS_CONCLUIDO)
                ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        $porCategoria = [];
        foreach ($user->categories as $cat) {
            $meta = \App\Models\Category::COLORS[$cat->color] ?? [];
            $porCategoria[$cat->id] = [
                'label' => $cat->name,
                'total' => $user->tasks()->where('category_id', $cat->id)->count(),
                'pendentes' => $user->tasks()->where('category_id', $cat->id)
                    ->where('status', '!=', Task::STATUS_CONCLUIDO)->count(),
                'dot' => $meta['dot'] ?? 'bg-slate-400',
                'border' => $meta['border'] ?? 'border-slate-400',
                'stroke' => $meta['stroke'] ?? '#94a3b8',
            ];
        }

        $porPrioridade = [];
        foreach (Task::PRIORITIES as $key => $label) {
            $porPrioridade[$key] = [
                'label' => $label,
                'total' => $user->tasks()->where('priority', $key)->count(),
                'concluidas' => $user->tasks()->where('priority', $key)
                    ->where('status', Task::STATUS_CONCLUIDO)->count(),
            ];
        }

        $atividadeSemanal = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $count = $user->tasks()
                ->where('status', Task::STATUS_CONCLUIDO)
                ->whereDate('updated_at', $day)
                ->count();
            $atividadeSemanal[] = [
                'label' => $day->format('D'),
                'count' => $count,
                'full' => $day->format('d/m'),
            ];
        }

        $proximasTarefas = $user->tasks()
            ->where('status', '!=', Task::STATUS_CONCLUIDO)
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        $atrasadas = $user->tasks()->overdue()->orderBy('due_date')->limit(5)->get();

        $projetos = $user->projects()->withCount('phases', 'tasks')->orderBy('created_at', 'desc')->limit(4)->get();

        return view('dashboard.index', compact(
            'stats', 'porCategoria', 'porPrioridade', 'atividadeSemanal',
            'proximasTarefas', 'atrasadas', 'projetos'
        ));
    }
}
