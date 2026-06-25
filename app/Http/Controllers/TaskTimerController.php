<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskTimeEntry;
use Illuminate\Http\Request;

class TaskTimerController extends Controller
{
    public function start(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        // Stop any active timer for this user and calculate duration
        $activeTimers = TaskTimeEntry::where('user_id', $request->user()->id)
            ->whereNull('ended_at')
            ->get();

        foreach ($activeTimers as $active) {
            $active->update([
                'ended_at' => now(),
                'duration_minutes' => (int) $active->started_at->diffInMinutes(now()),
            ]);
        }

        $entry = $task->timeEntries()->create([
            'user_id' => $request->user()->id,
            'started_at' => now(),
        ]);

        // Update task progress to em_andamento if pendente
        if ($task->status === Task::STATUS_PENDENTE) {
            $task->update(['status' => Task::STATUS_ANDAMENTO, 'progress' => 50]);
        }

        return response()->json([
            'success' => true,
            'entry' => [
                'id' => $entry->id,
                'started_at' => $entry->started_at->toIso8601String(),
                'task' => ['id' => $task->id, 'title' => $task->title],
            ],
        ]);
    }

    public function stop(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $entry = $task->timeEntries()
            ->where('user_id', $request->user()->id)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->firstOrFail();

        $entry->update([
            'ended_at' => now(),
            'duration_minutes' => (int) $entry->started_at->diffInMinutes(now()),
        ]);

        return response()->json([
            'success' => true,
            'entry' => [
                'id' => $entry->id,
                'started_at' => $entry->started_at->toIso8601String(),
                'ended_at' => $entry->ended_at->toIso8601String(),
                'duration_minutes' => $entry->duration_minutes,
            ],
            'tracked_minutes' => $task->tracked_minutes,
        ]);
    }

    public function status(Request $request)
    {
        $active = TaskTimeEntry::where('user_id', $request->user()->id)
            ->whereNull('ended_at')
            ->with('task:id,title')
            ->latest('started_at')
            ->first();

        if (!$active) {
            return response()->json(['active' => false]);
        }

        $elapsed = (int) $active->started_at->diffInSeconds(now());

        return response()->json([
            'active' => true,
            'entry' => [
                'id' => $active->id,
                'started_at' => $active->started_at->toIso8601String(),
                'elapsed_seconds' => $elapsed,
                'task' => ['id' => $active->task->id, 'title' => $active->task->title],
            ],
        ]);
    }

    public function taskStatus(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $active = $task->timeEntries()
            ->where('user_id', $request->user()->id)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->first();

        if (!$active) {
            return response()->json(['active' => false]);
        }

        $elapsed = (int) $active->started_at->diffInSeconds(now());

        return response()->json([
            'active' => true,
            'entry' => [
                'id' => $active->id,
                'started_at' => $active->started_at->toIso8601String(),
                'elapsed_seconds' => $elapsed,
            ],
        ]);
    }

    private function authorizeTask(Task $task): void
    {
        abort_unless($task->user_id === auth()->id(), 403);
    }
}
