<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }

    public function redirect(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $taskId = $notification->data['task_id'] ?? null;
            if ($taskId && \App\Models\Task::where('id', $taskId)->exists()) {
                return redirect()->route('tasks.show', $taskId);
            }
        }

        return redirect()->route('notifications.index');
    }

    public function latestMotivational(Request $request): JsonResponse
    {
        $notification = $request->user()->notifications()
            ->where('data->type', 'motivational')
            ->whereNull('read_at')
            ->latest()
            ->first();

        if (! $notification) {
            return response()->json(null);
        }

        return response()->json([
            'id' => $notification->id,
            'message' => $notification->data['message'] ?? '',
            'created_at' => $notification->created_at,
        ]);
    }
}
