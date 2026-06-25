<?php

namespace App\Http\Controllers\Study;

use App\Http\Controllers\Controller;
use App\Models\StudyFlashcard;
use App\Models\StudySpecialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudyFlashcardController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->studyFlashcards()->with('specialization');

        if ($request->filled('specialization')) {
            $spec = StudySpecialization::find($request->specialization);
            if ($spec && $spec->user_id === $request->user()->id) {
                $query->where('study_specialization_id', $spec->id);
            }
        }

        $flashcards = $query->orderBy('next_review_at')->orderBy('created_at', 'desc')->paginate(20);

        $specializations = $request->user()->studySpecializations;

        return view('studies.flashcards.index', compact('flashcards', 'specializations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'study_specialization_id' => ['required', 'exists:study_specializations,id'],
            'front' => ['required', 'string', 'max:1000'],
            'back' => ['required', 'string', 'max:2000'],
            'difficulty' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $spec = StudySpecialization::findOrFail($data['study_specialization_id']);
        abort_unless($spec->user_id === $request->user()->id, 403);

        $data['user_id'] = $request->user()->id;
        $data['next_review_at'] = now();

        $request->user()->studyFlashcards()->create($data);

        return back()->with('success', 'Flashcard criado!');
    }

    public function update(Request $request, StudyFlashcard $flashcard): RedirectResponse
    {
        abort_unless($flashcard->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'study_specialization_id' => ['required', 'exists:study_specializations,id'],
            'front' => ['required', 'string', 'max:1000'],
            'back' => ['required', 'string', 'max:2000'],
        ]);

        $flashcard->update($data);

        return back()->with('success', 'Flashcard atualizado!');
    }

    public function destroy(Request $request, StudyFlashcard $flashcard): RedirectResponse
    {
        abort_unless($flashcard->user_id === $request->user()->id, 403);

        $flashcard->delete();

        return back()->with('success', 'Flashcard removido.');
    }

    public function review(Request $request): View
    {
        $user = $request->user();

        $flashcards = $user->studyFlashcards()
            ->with('specialization')
            ->dueForReview()
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $dueToday = $flashcards->count();
        $totalDue = $user->studyFlashcards()->dueForReview()->count();
        $specializations = $user->studySpecializations;

        return view('studies.flashcards.review', compact('flashcards', 'dueToday', 'totalDue', 'specializations'));
    }

    public function submitReview(Request $request): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'flashcard_id' => ['required', 'exists:study_flashcards,id'],
            'difficulty' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $flashcard = StudyFlashcard::findOrFail($data['flashcard_id']);
        abort_unless($flashcard->user_id === $request->user()->id, 403);

        $flashcard->review($data['difficulty']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Review registrado!');
    }
}
