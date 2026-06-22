<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'))->startOfWeek()
            : now()->startOfWeek();

        $weekEnd = $weekStart->copy()->endOfWeek();

        $tasks = $request->user()->tasks()
            ->whereBetween('due_date', [$weekStart, $weekEnd])
            ->orderBy('due_date')
            ->get()
            ->groupBy(fn (Task $task) => $task->due_date->format('Y-m-d'));

        $dias = collect();
        $cursor = $weekStart->copy();
        while ($cursor->lte($weekEnd)) {
            $dias->push($cursor->copy());
            $cursor->addDay();
        }

        return view('agenda.index', [
            'dias' => $dias,
            'tasksPorDia' => $tasks,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'categories' => $request->user()->categories()->pluck('name', 'id'),
        ]);
    }
}
