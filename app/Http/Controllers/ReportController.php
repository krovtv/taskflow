<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $category = $request->query('category');
        $status = $request->query('status');
        $dateFrom = $request->query('from');
        $dateTo = $request->query('to');

        $tasks = $user->tasks()
            ->when($category, fn ($q) => $q->where('category_id', $category))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($dateFrom, fn ($q) => $q->whereDate('due_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('due_date', '<=', $dateTo))
            ->orderBy('due_date')
            ->get();

        $stats = [
            'total' => $tasks->count(),
            'pendentes' => $tasks->where('status', Task::STATUS_PENDENTE)->count(),
            'andamento' => $tasks->where('status', Task::STATUS_ANDAMENTO)->count(),
            'concluidas' => $tasks->where('status', Task::STATUS_CONCLUIDO)->count(),
            'atrasadas' => $tasks->filter(fn ($t) => $t->isOverdue())->count(),
        ];

        $porCategoria = [];
        foreach ($user->categories as $cat) {
            $catTasks = $tasks->where('category_id', $cat->id);
            $meta = \App\Models\Category::COLORS[$cat->color] ?? [];
            $porCategoria[] = [
                'label' => $cat->name,
                'total' => $catTasks->count(),
                'concluidas' => $catTasks->where('status', Task::STATUS_CONCLUIDO)->count(),
                'dot' => $meta['dot'] ?? 'bg-slate-400',
            ];
        }

        return view('reports.index', [
            'tasks' => $tasks,
            'stats' => $stats,
            'porCategoria' => $porCategoria,
            'categories' => $user->categories()->pluck('name', 'id'),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'currentCategory' => $category,
            'currentStatus' => $status,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function pdf(Request $request)
    {
        $user = $request->user();

        $category = $request->query('category');
        $status = $request->query('status');
        $dateFrom = $request->query('from');
        $dateTo = $request->query('to');

        $tasks = $user->tasks()
            ->when($category, fn ($q) => $q->where('category_id', $category))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($dateFrom, fn ($q) => $q->whereDate('due_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('due_date', '<=', $dateTo))
            ->orderBy('due_date')
            ->get();

        $stats = [
            'total' => $tasks->count(),
            'pendentes' => $tasks->where('status', Task::STATUS_PENDENTE)->count(),
            'andamento' => $tasks->where('status', Task::STATUS_ANDAMENTO)->count(),
            'concluidas' => $tasks->where('status', Task::STATUS_CONCLUIDO)->count(),
            'atrasadas' => $tasks->filter(fn ($t) => $t->isOverdue())->count(),
        ];

        $porCategoria = [];
        foreach ($user->categories as $cat) {
            $catTasks = $tasks->where('category_id', $cat->id);
            $porCategoria[$cat->id] = [
                'label' => $cat->name,
                'total' => $catTasks->count(),
                'concluidas' => $catTasks->where('status', Task::STATUS_CONCLUIDO)->count(),
            ];
        }

        $nome = $user->name;
        $data = now()->format('d/m/Y H:i');

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return back()->with('error', 'Pacote DomPDF não instalado. Execute: composer require barryvdh/laravel-dompdf');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact(
            'tasks', 'stats', 'porCategoria', 'nome', 'data'
        ));

        return $pdf->download('relatorio-tarefas-' . now()->format('Y-m-d') . '.pdf');
    }
}
