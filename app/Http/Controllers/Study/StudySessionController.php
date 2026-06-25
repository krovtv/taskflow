<?php

namespace App\Http\Controllers\Study;

use App\Http\Controllers\Controller;
use App\Models\StudySession;
use App\Models\StudySpecialization;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudySessionController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $sessions = $user->studySessions()
            ->with('specialization')
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        $todayMinutes = (int) $user->studySessions()
            ->whereDate('started_at', today())
            ->sum('duration_minutes');

        $weekMinutes = (int) $user->studySessions()
            ->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('duration_minutes');

        $totalHours = round($user->studySessions()->sum('duration_minutes') / 60, 1);

        $specializations = $user->studySpecializations;

        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $last30Days->push([
                'label' => $day->format('d/m'),
                'minutes' => (int) $user->studySessions()
                    ->whereDate('started_at', $day)
                    ->sum('duration_minutes'),
            ]);
        }

        return view('studies.timer.index', compact(
            'sessions', 'todayMinutes', 'weekMinutes', 'totalHours',
            'specializations', 'last30Days'
        ));
    }

    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'study_specialization_id' => ['required', 'exists:study_specializations,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $spec = StudySpecialization::findOrFail($data['study_specialization_id']);
        abort_unless($spec->user_id === $request->user()->id, 403);

        $request->user()->studySessions()
            ->whereNull('ended_at')
            ->each(fn ($s) => $s->update(['ended_at' => now(), 'duration_minutes' => now()->diffInMinutes($s->started_at)]));

        $session = $request->user()->studySessions()->create([
            'study_specialization_id' => $spec->id,
            'started_at' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json(['success' => true, 'session' => $session->load('specialization')]);
    }

    public function stop(Request $request): JsonResponse
    {
        $session = $request->user()->studySessions()
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Nenhuma sessão ativa.'], 400);
        }

        $endedAt = now();
        $minutes = $endedAt->diffInMinutes($session->started_at);

        $session->update([
            'ended_at' => $endedAt,
            'duration_minutes' => $minutes,
        ]);

        $todayMinutes = (int) $request->user()->studySessions()
            ->whereDate('started_at', today())
            ->sum('duration_minutes');

        return response()->json([
            'success' => true,
            'duration' => $minutes,
            'today_minutes' => $todayMinutes,
            'session' => $session->load('specialization'),
        ]);
    }

    public function status(Request $request): JsonResponse
    {
        $session = $request->user()->studySessions()
            ->whereNull('ended_at')
            ->with('specialization')
            ->latest()
            ->first();

        if (!$session) {
            return response()->json(['active' => false]);
        }

        $elapsed = now()->diffInSeconds($session->started_at);

        return response()->json([
            'active' => true,
            'session' => $session,
            'elapsed_seconds' => $elapsed,
        ]);
    }
}
