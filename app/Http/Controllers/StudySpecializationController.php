<?php

namespace App\Http\Controllers;

use App\Models\StudyNote;
use App\Models\StudySpecialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudySpecializationController extends Controller
{
    public function index(Request $request): View
    {
        $specializations = $request->user()->studySpecializations()
            ->withCount(['sessions', 'flashcards'])
            ->get();

        return view('studies.specializations.index', compact('specializations'));
    }

    public function show(Request $request, StudySpecialization $specialization): View
    {
        abort_unless($specialization->user_id === $request->user()->id, 403);

        $specialization->load(['notes' => fn($q) => $q->orderBy('order')->orderBy('created_at', 'desc')]);

        $flashcards = $request->user()->studyFlashcards()
            ->where('study_specialization_id', $specialization->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_flashcards' => $specialization->flashcards()->count(),
            'due_flashcards' => $specialization->flashcards()->dueForReview()->count(),
            'total_sessions' => $specialization->sessions()->count(),
            'total_hours' => round($specialization->sessions()->sum('duration_minutes') / 60, 1),
            'today_minutes' => (int) $specialization->sessions()->whereDate('started_at', today())->sum('duration_minutes'),
        ];

        return view('studies.specializations.show', compact('specialization', 'flashcards', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['required', 'string', 'in:' . implode(',', array_keys(\App\Models\Category::COLORS))],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $request->user()->studySpecializations()->create($data);

        return redirect()->route('studies.specializations.index')
            ->with('success', 'Especialização criada!');
    }

    public function update(Request $request, StudySpecialization $specialization): RedirectResponse
    {
        abort_unless($specialization->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['required', 'string', 'in:' . implode(',', array_keys(\App\Models\Category::COLORS))],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $specialization->update($data);

        return redirect()->route('studies.specializations.index')
            ->with('success', 'Especialização atualizada!');
    }

    public function destroy(Request $request, StudySpecialization $specialization): RedirectResponse
    {
        abort_unless($specialization->user_id === $request->user()->id, 403);

        $specialization->delete();

        return redirect()->route('studies.specializations.index')
            ->with('success', 'Especialização removida.');
    }
}
