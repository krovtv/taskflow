<?php

namespace App\Http\Controllers;

use App\Models\StudyNote;
use App\Models\StudySpecialization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudyNoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'study_specialization_id' => ['required', 'exists:study_specializations,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:10000'],
            'type' => ['required', 'in:text,link'],
            'url' => ['nullable', 'url', 'max:2000'],
        ]);

        $spec = StudySpecialization::findOrFail($data['study_specialization_id']);
        abort_unless($spec->user_id === $request->user()->id, 403);

        $data['user_id'] = $request->user()->id;
        $request->user()->studyNotes()->create($data);

        return back()->with('success', 'Anotação adicionada!');
    }

    public function update(Request $request, StudyNote $note): RedirectResponse
    {
        abort_unless($note->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:10000'],
            'type' => ['required', 'in:text,link'],
            'url' => ['nullable', 'url', 'max:2000'],
        ]);

        $note->update($data);

        return back()->with('success', 'Anotação atualizada!');
    }

    public function destroy(Request $request, StudyNote $note): RedirectResponse
    {
        abort_unless($note->user_id === $request->user()->id, 403);

        $note->delete();

        return back()->with('success', 'Anotação removida.');
    }
}
