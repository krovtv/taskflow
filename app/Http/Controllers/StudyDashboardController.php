<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudyDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $specializations = $user->studySpecializations()->withCount(['sessions', 'flashcards'])->get();

        $totalHours = round($user->studySessions()->sum('duration_minutes') / 60, 1);
        $todayMinutes = (int) $user->studySessions()->whereDate('started_at', today())->sum('duration_minutes');
        $weekMinutes = (int) $user->studySessions()->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('duration_minutes');
        $totalSessions = $user->studySessions()->count();
        $totalFlashcards = $user->studyFlashcards()->count();
        $dueFlashcards = $user->studyFlashcards()->dueForReview()->count();

        $cardStats = [
            ['label' => 'Hoje', 'value' => $todayMinutes . 'min', 'icon' => 'clock', 'bg' => 'from-kvteal to-kvteal/80'],
            ['label' => 'Semana', 'value' => $weekMinutes . 'min', 'icon' => 'calendar', 'bg' => 'from-amber-500 to-amber-400'],
            ['label' => 'Total', 'value' => $totalHours . 'h', 'icon' => 'chart', 'bg' => 'from-purple-500 to-purple-400'],
            ['label' => 'Flashcards', 'value' => $dueFlashcards . '/' . $totalFlashcards, 'icon' => 'card', 'bg' => 'from-emerald-500 to-emerald-400'],
        ];

        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $last30Days->push([
                'label' => $day->format('D'),
                'minutes' => (int) $user->studySessions()->whereDate('started_at', $day)->sum('duration_minutes'),
                'full' => $day->format('d/m'),
            ]);
        }

        $perSpecialization = [];
        foreach ($specializations as $spec) {
            $perSpecialization[] = [
                'name' => $spec->name,
                'color' => $spec->color,
                'hours' => round($spec->sessions()->sum('duration_minutes') / 60, 1),
                'sessions' => $spec->sessions_count,
                'flashcards' => $spec->flashcards_count,
            ];
        }

        $recentSessions = $user->studySessions()->with('specialization')->latest()->limit(5)->get();

        return view('studies.dashboard', compact(
            'specializations', 'cardStats', 'last30Days', 'perSpecialization',
            'recentSessions', 'dueFlashcards', 'totalFlashcards'
        ));
    }
}
