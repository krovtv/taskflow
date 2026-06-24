<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $status = $request->query('status');
        $search = $request->query('search');

        $allowedSorts = ['due_date', 'title', 'priority', 'status', 'created_at'];
        $sort = in_array($request->query('sort', 'due_date'), $allowedSorts)
            ? $request->query('sort', 'due_date') : 'due_date';
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';

        $tasks = $request->user()->tasks()
            ->with('project:id,title', 'cat:id,name,color')
            ->when($category, fn ($q) => $q->where('category_id', $category))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            }))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'categories' => $request->user()->categories()->pluck('name', 'id'),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'currentCategory' => $category,
            'currentStatus' => $status,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function show(Task $task): View
    {
        $this->authorizeTask($task);
        $task->load('attachments');
        return view('tasks.show', compact('task'));
    }

    public function create(Request $request): View
    {
        $projects = $request->user()->projects()->with('phases')->orderBy('title')->get();

        return view('tasks.create', [
            'categories' => $request->user()->categories()->pluck('name', 'id'),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'selectedCategory' => $request->query('category'),
            'selectedProject' => $request->query('project'),
            'projects' => $projects,
        ]);
    }

    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $data = $this->validateData($request);
        $data['status'] = $data['status'] ?? Task::STATUS_PENDENTE;
        $data['priority'] = $data['priority'] ?? Task::PRIORITY_MEDIA;
        $data['category'] = '';

        $task = $request->user()->tasks()->create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task->load('cat:id,name,color')]);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task): View
    {
        $this->authorizeTask($task);

        $projects = $task->user->projects()->with('phases')->orderBy('title')->get();

        return view('tasks.edit', [
            'task' => $task,
            'categories' => $task->user->categories()->pluck('name', 'id'),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'projects' => $projects,
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        $data = $this->validateData($request);

        // Se a data de vencimento mudou, permite notificar novamente
        if ($task->due_date->ne($data['due_date'])) {
            $data['notified_at'] = null;
        }

        $task->update($data);

        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(Task::STATUSES)),
        ]);

        $newStatus = $request->input('status');
        $data = ['status' => $newStatus];

        if ($newStatus === Task::STATUS_PENDENTE) {
            $data['progress'] = 0;
        } elseif ($newStatus === Task::STATUS_CONCLUIDO) {
            $data['progress'] = 100;
        } elseif ($newStatus === Task::STATUS_ANDAMENTO && (!$task->progress || $task->progress === 0)) {
            $data['progress'] = 50;
        }

        $task->update($data);

        // Gerar próxima recorrência se for concluída e for recorrente
        if ($newStatus === Task::STATUS_CONCLUIDO && $task->isRecurring()) {
            $nextDate = $task->getNextRecurringDueDate();
            if ($nextDate) {
                $repeated = $task->replicate()->fill([
                    'due_date' => $nextDate,
                    'status' => Task::STATUS_PENDENTE,
                    'progress' => 0,
                    'notified_at' => null,
                ]);
                $repeated->save();
            }
        }

        return back()->with('success', 'Status atualizado!');
    }

    public function upload(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip,txt,csv,mp4,mp3'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs("tasks/{$task->id}", $filename, 'public');

        $task->attachments()->create([
            'user_id' => $request->user()->id,
            'filename' => $filename,
            'original_name' => $originalName,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'Arquivo anexado com sucesso!');
    }

    public function download(Task $task, TaskAttachment $attachment): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->authorizeTask($task);
        abort_unless($attachment->task_id === $task->id, 404);

        return Storage::disk('public')->download(
            $attachment->path,
            $attachment->original_name
        );
    }

    public function deleteAttachment(Task $task, TaskAttachment $attachment): RedirectResponse
    {
        $this->authorizeTask($task);
        abort_unless($attachment->task_id === $task->id, 404);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Arquivo removido.');
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('q');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $tasks = $request->user()->tasks()
            ->with('project:id,title', 'cat:id,name,color')
            ->where('status', '!=', Task::STATUS_CONCLUIDO)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('tags', 'like', "%{$query}%");
            })
            ->orderByRaw("CASE WHEN title LIKE ? THEN 0 ELSE 1 END", ["{$query}%"])
            ->orderBy('due_date')
            ->limit(8)
            ->get(['id', 'title', 'category_id', 'priority', 'due_date', 'status', 'project_id']);

        return response()->json($tasks);
    }

    public function batchStatus(Request $request): RedirectResponse
    {
        $ids = json_decode($request->input('ids', '[]'), true);
        $status = $request->input('status');

        if (!is_array($ids) || !in_array($status, array_keys(Task::STATUSES))) {
            return back()->with('error', 'Operação inválida.');
        }

        $updateData = ['status' => $status];
        if ($status === Task::STATUS_PENDENTE) {
            $updateData['progress'] = 0;
        } elseif ($status === Task::STATUS_CONCLUIDO) {
            $updateData['progress'] = 100;
        }

        $request->user()->tasks()->whereIn('id', $ids)->update($updateData);

        return back()->with('success', count($ids) . ' tarefa(s) atualizada(s) para "' . Task::STATUSES[$status] . '".');
    }

    public function batchDestroy(Request $request): RedirectResponse
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (!is_array($ids)) {
            return back()->with('error', 'Operação inválida.');
        }

        $request->user()->tasks()->whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' tarefa(s) removida(s).');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarefa removida.');
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'due_date' => ['required', 'date_format:Y-m-d'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'status' => ['nullable', 'in:'.implode(',', array_keys(Task::STATUSES))],
            'priority' => ['nullable', 'in:'.implode(',', array_keys(Task::PRIORITIES))],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tags' => ['nullable', 'string', 'max:500'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'project_phase_id' => ['nullable', 'integer', 'exists:project_phases,id'],
            'recurring_frequency' => ['nullable', 'string', 'in:'.implode(',', array_keys(\App\Models\Task::FREQUENCIES))],
            'recurring_end_date' => ['nullable', 'date_format:Y-m-d'],
        ];

        $validated = $request->validate($rules);

        // Combinar data + hora em um datetime completo
        $time = $validated['due_time'] ?? '23:59';
        $validated['due_date'] = $validated['due_date'] . ' ' . $time . ':00';

        if ($validated['project_phase_id'] && $validated['project_id']) {
            $phase = \App\Models\ProjectPhase::find($validated['project_phase_id']);
            abort_unless($phase && $phase->project_id === (int) $validated['project_id'], 422,
                'A fase selecionada não pertence ao projeto escolhido.');
        }

        return $validated;
    }

    private function authorizeTask(Task $task): void
    {
        abort_unless($task->user_id === auth()->id(), 403);
    }
}
