<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectPhase;
    use App\Models\Task;
    use Illuminate\Http\Request;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $projects = $request->user()->projects()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'statuses' => Project::STATUSES,
            'currentStatus' => $status,
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'statuses' => Project::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:'.implode(',', array_keys(Project::STATUSES))],
        ]);

        $data['status'] ??= Project::STATUS_PLANEJAMENTO;

        $project = $request->user()->projects()->create($data);

        return redirect()->route('projects.show', $project)->with('success', 'Projeto criado com sucesso!');
    }

    public function show(Project $project): View
    {
        $this->authorizeProject($project);

        $phases = $project->phases()->orderBy('order')->get();
        $tasks = $project->tasks()->orderBy('due_date')->get();

        return view('projects.show', compact('project', 'phases', 'tasks'));
    }

    public function edit(Project $project): View
    {
        $this->authorizeProject($project);

        return view('projects.edit', [
            'project' => $project,
            'statuses' => Project::STATUSES,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProject($project);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:'.implode(',', array_keys(Project::STATUSES))],
        ]);

        $project->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Projeto atualizado!');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorizeProject($project);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto removido.');
    }

    public function phaseStore(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeProject($project);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $maxOrder = $project->phases()->max('order');
        $data['order'] = ($maxOrder ?? -1) + 1;
        $data['project_id'] = $project->id;

        ProjectPhase::create($data);

        return redirect()->route('projects.show', $project)->with('success', 'Fase adicionada!');
    }

    public function phaseUpdate(Request $request, Project $project, ProjectPhase $phase): RedirectResponse
    {
        $this->authorizeProject($project);
        abort_unless($phase->project_id === $project->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:'.implode(',', array_keys(ProjectPhase::STATUSES))],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $phase->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Fase atualizada!');
    }

    public function phaseDestroy(Project $project, ProjectPhase $phase): RedirectResponse
    {
        $this->authorizeProject($project);
        abort_unless($phase->project_id === $project->id, 404);

        $phase->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Fase removida.');
    }

    private function authorizeProject(Project $project): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
    }
}
