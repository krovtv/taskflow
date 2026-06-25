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
            ->whereNull('recurring_frequency')
            ->whereBetween('due_date', [$weekStart, $weekEnd])
            ->orderBy('due_date')
            ->get()
            ->groupBy(fn (Task $task) => $task->due_date->format('Y-m-d'));

        $recurringTasks = $request->user()->tasks()
            ->whereNotNull('recurring_frequency')
            ->where('due_date', '<=', $weekEnd)
            ->where(function ($q) use ($weekStart) {
                $q->whereNull('recurring_end_date')
                  ->orWhere('recurring_end_date', '>=', $weekStart);
            })
            ->get();

        foreach ($recurringTasks as $task) {
            $occurrence = $task->due_date->copy();

            if ($occurrence->isFuture() && $occurrence->between($weekStart, $weekEnd)) {
                $key = $occurrence->format('Y-m-d');
                $tasks[$key] = $tasks[$key] ?? collect();
                $tasks[$key]->push($task);
                continue;
            }

            while ($occurrence->lte($weekEnd)) {
                if ($occurrence->between($weekStart, $weekEnd)) {
                    $key = $occurrence->format('Y-m-d');
                    $tasks[$key] = $tasks[$key] ?? collect();
                    $tasks[$key]->push($task);
                }
                $occurrence = match ($task->recurring_frequency) {
                    'daily' => $occurrence->addDay(),
                    'weekly' => $occurrence->addWeek(),
                    'monthly' => $occurrence->addMonth(),
                    'yearly' => $occurrence->addYear(),
                    default => $occurrence,
                };
                if ($task->recurring_end_date && $occurrence->isAfter($task->recurring_end_date)) {
                    break;
                }
                if ($occurrence->isAfter($weekEnd)) {
                    break;
                }
            }
        }

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
