@extends('layouts.app')
@section('title', 'Tarefas')
@section('heading', 'Tarefas')

@section('content')
{{-- FILTROS + BUSCA --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 md:p-5 mb-6 shadow-sm animate-in">
    <form method="GET" class="space-y-4">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Buscar</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                           placeholder="Pesquisar por título, descrição ou tags..."
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Categoria</label>
                <select name="category" class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                    <option value="">Todas</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" @selected($currentCategory == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                    <option value="">Todos</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected($currentStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button class="bg-kvnavy hover:bg-kvnavy-light text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm">Filtrar</button>
            <a href="{{ route('tasks.index') }}" class="text-sm text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 px-3 py-2.5 font-medium transition-colors">Limpar</a>
        </div>
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">
    </form>
</div>

{{-- AÇÕES --}}
<div class="flex items-center justify-between mb-4 animate-in animate-in-d1">
    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
        {{ $tasks->total() }} tarefa(s) encontrada(s)
    </p>
    <div class="flex items-center gap-2">
        {{-- BATCH ACTIONS --}}
        <div x-data="{ selected: [], selectAll: false }"
             x-init="
                document.addEventListener('keydown', e => { if (e.key === 'Escape') { selected = []; selectAll = false } });
                $watch('selected', val => { document.querySelectorAll('.task-checkbox').forEach(cb => { if (val.includes(cb.value)) cb.checked = true; else cb.checked = false }) });
                $watch('selectAll', val => {
                    document.querySelectorAll('.task-checkbox').forEach(cb => { cb.checked = val; });
                    selected = val ? [...document.querySelectorAll('.task-checkbox')].map(cb => cb.value) : [];
                })
             "
             class="hidden md:flex items-center gap-2"
             :class="{ 'hidden': selected.length === 0 }">
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="selected.length + ' selecionada(s)'"></span>
            <form method="POST" action="{{ route('tasks.batch.status') }}" class="inline">
                @csrf
                <input type="hidden" name="ids" :value="JSON.stringify(selected)">
                <input type="hidden" name="status" value="concluido">
                <button type="submit"
                        class="text-xs font-semibold text-emerald-500 hover:text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 px-3 py-1.5 rounded-lg transition-all border border-emerald-200 dark:border-emerald-800/50">
                    Concluir
                </button>
            </form>
            <form method="POST" action="{{ route('tasks.batch.destroy') }}" class="inline">
                @csrf @method('DELETE')
                <input type="hidden" name="ids" :value="JSON.stringify(selected)">
                <button type="submit" onclick="return confirm('Excluir as '+selected.length+' tarefas selecionadas?')"
                        class="text-xs font-semibold text-red-400 hover:text-red-500 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-all border border-red-200 dark:border-red-800/50">
                    Excluir
                </button>
            </form>
            <button @click="selected = []; selectAll = false"
                    class="text-xs font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors px-2 py-1.5">
                Cancelar
            </button>
        </div>
        <a href="{{ route('reports.index', request()->query()) }}"
           class="bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-500 dark:text-red-400 hover:text-red-600 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all border border-red-200 dark:border-red-800/50 hover:border-red-300 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Relatório PDF
        </a>
    </div>
</div>

{{-- VIEW EM TABELA (Desktop) --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 overflow-hidden shadow-sm hidden md:block animate-in animate-in-d2">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-slate-50 dark:from-gray-800 to-white dark:to-gray-900 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="text-left px-5 py-4 w-10">
                        <input type="checkbox" onchange="document.querySelectorAll('.task-checkbox').forEach(cb => { cb.checked = this.checked; cb.dispatchEvent(new Event('change')) })"
                               class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-kvteal focus:ring-kvteal/30 transition-all cursor-pointer">
                    </th>
                    <th class="text-left px-5 py-4 w-10">
                        <span class="sr-only">Status</span>
                    </th>
                    <th class="text-left px-5 py-4">
                        <a href="{{ route('tasks.index', array_merge(request()->query(), ['sort' => 'title', 'direction' => ($sort === 'title' && $direction === 'asc' ? 'desc' : 'asc')])) }}"
                           class="inline-flex items-center gap-1 hover:text-kvnavy dark:hover:text-white transition-colors">
                            Tarefa
                            @if($sort === 'title')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $direction === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/>
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-4 hidden lg:table-cell">
                        <a href="{{ route('tasks.index', array_merge(request()->query(), ['sort' => 'priority', 'direction' => ($sort === 'priority' && $direction === 'asc' ? 'desc' : 'asc')])) }}"
                           class="inline-flex items-center gap-1 hover:text-kvnavy dark:hover:text-white transition-colors">
                            Prioridade
                            @if($sort === 'priority')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $direction === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/>
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-4 hidden md:table-cell">Categoria</th>
                    <th class="text-left px-5 py-4 hidden lg:table-cell">Progresso</th>
                    <th class="text-left px-5 py-4">
                        <a href="{{ route('tasks.index', array_merge(request()->query(), ['sort' => 'due_date', 'direction' => ($sort === 'due_date' && $direction === 'asc' ? 'desc' : 'asc')])) }}"
                           class="inline-flex items-center gap-1 hover:text-kvnavy dark:hover:text-white transition-colors">
                            Prazo
                            @if($sort === 'due_date')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $direction === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/>
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-left px-5 py-4">Status</th>
                    <th class="text-right px-5 py-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr class="border-t border-slate-100 dark:border-gray-800 hover:bg-slate-50/50 dark:hover:bg-gray-800/50 transition-colors {{ $task->status === 'concluido' ? 'opacity-60' : '' }}">
                        {{-- SELECT --}}
                        <td class="px-5 py-4">
                            <input type="checkbox" value="{{ $task->id }}" class="task-checkbox w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-kvteal focus:ring-kvteal/30 transition-all cursor-pointer"
                                   onchange="this.closest('table').querySelector('thead input[type=checkbox]').checked = document.querySelectorAll('.task-checkbox:checked').length === document.querySelectorAll('.task-checkbox').length">
                        </td>
                        {{-- CHECKBOX QUICK COMPLETE --}}
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('tasks.status', $task) }}" class="quick-status-form">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status"
                                       value="{{ $task->status === 'concluido' ? 'pendente' : 'concluido' }}">
                                <button type="submit"
                                        class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-200
                                        {{ $task->status === 'concluido'
                                            ? 'bg-emerald-500 border-emerald-500 text-white'
                                            : 'border-slate-300 hover:border-kvteal hover:bg-kvteal/10' }}">
                                    @if($task->status === 'concluido')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    @endif
                                </button>
                            </form>
                        </td>
                        {{-- TAREFA --}}
                        <td class="px-5 py-4">
                            <div class="min-w-0">
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="font-semibold text-slate-700 dark:text-slate-200 hover:text-kvteal transition-colors truncate block">
                                    {{ $task->title }}
                                </a>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    @if($task->isRecurring())
                                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-kvteal/10 text-kvteal inline-flex items-center gap-0.5" title="{{ \App\Models\Task::FREQUENCIES[$task->recurring_frequency] ?? $task->recurring_frequency }}">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/></svg>
                                            {{ \App\Models\Task::FREQUENCIES[$task->recurring_frequency] ?? $task->recurring_frequency }}
                                        </span>
                                    @endif
                                    @if($task->project)
                                        <a href="{{ route('projects.show', $task->project) }}" class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-purple-50 dark:bg-purple-900/30 text-purple-500 dark:text-purple-400 hover:text-purple-600 dark:hover:text-purple-300 transition-colors inline-flex items-center gap-1">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                                            {{ $task->project->title }}
                                        </a>
                                    @endif
                                    @if($task->description)
                                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate max-w-xs">{{ $task->description }}</p>
                                    @endif
                                </div>
                                @if($task->tags)
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @foreach($task->tags_array as $tag)
                                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </td>
                        {{-- PRIORIDADE --}}
                        <td class="px-5 py-4 hidden lg:table-cell">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg border whitespace-nowrap
                                @if($task->priority === 'urgente') bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800/50
                                @elseif($task->priority === 'alta') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50
                                @elseif($task->priority === 'media') bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/50
                                @else bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700 @endif">
                                {{ $task->priority_label }}
                            </span>
                        </td>
                        {{-- CATEGORIA --}}
                        <td class="px-5 py-4 hidden md:table-cell">
                             <span class="text-xs px-2.5 py-1 rounded-full font-medium whitespace-nowrap
                                 {{ \App\Models\Category::COLORS[$task->cat?->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400' }}">
                                 {{ $task->category_label }}
                             </span>
                        </td>
                        {{-- PROGRESSO --}}
                        <td class="px-5 py-4 hidden lg:table-cell">
                            @if($task->progress > 0)
                                <div class="flex items-center gap-2 min-w-[80px]">
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-200 dark:bg-gray-700 overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-kvteal to-emerald-400 transition-all duration-500"
                                             style="width: {{ $task->progress }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 tabular-nums">{{ $task->progress }}%</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-300 dark:text-slate-600">—</span>
                            @endif
                        </td>
                        {{-- PRAZO --}}
                        <td class="px-5 py-4">
                            <span class="text-sm whitespace-nowrap {{ $task->isOverdue() ? 'text-red-500 font-semibold' : 'text-slate-500 dark:text-slate-400' }}">
                                {{ $task->due_date->format('d/m/Y') }}
                            </span>
                        </td>
                        {{-- STATUS --}}
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('tasks.status', $task) }}">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="text-xs font-semibold border rounded-lg px-2 py-1 outline-none transition-all cursor-pointer
                                        @class([
                                            'bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700' => $task->status === 'pendente',
                                            'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50' => $task->status === 'em_andamento',
                                            'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50' => $task->status === 'concluido',
                                        ])">
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" @selected($task->status === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        {{-- AÇÕES --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="text-xs font-semibold text-slate-400 dark:text-slate-500 hover:text-kvteal bg-slate-50 dark:bg-gray-800 hover:bg-kvteal/5 px-2.5 py-1.5 rounded-lg transition-all">
                                    Ver
                                </a>
                                <a href="{{ route('tasks.edit', $task) }}"
                                   class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-2.5 py-1.5 rounded-lg transition-all">
                                    Editar
                                </a>
                                <x-confirmation-modal
                                    :action="route('tasks.destroy', $task)"
                                    title="Excluir tarefa"
                                    message='Tem certeza que deseja remover "{{ $task->title }}"?'
                                    buttonText="Excluir"
                                    buttonClass="text-xs font-semibold text-red-400 dark:text-red-400 hover:text-red-600 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/40 px-2.5 py-1.5 rounded-lg transition-all">
                                    Excluir
                                </x-confirmation-modal>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-slate-400 dark:text-slate-500 py-16 font-medium">Nenhuma tarefa encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- BATCH BAR MOBILE --}}
<div class="md:hidden flex items-center gap-2 px-3 py-2 mb-3 bg-white dark:bg-gray-900 rounded-xl border border-slate-200 dark:border-gray-700 shadow-sm mobile-batch-bar hidden animate-in">
    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium mobile-batch-count">0 selecionada(s)</span>
    <div class="flex-1"></div>
    <form method="POST" action="{{ route('tasks.batch.status') }}" class="inline">
        @csrf
        <input type="hidden" name="ids" class="batch-ids">
        <input type="hidden" name="status" value="concluido">
        <button type="submit"
                class="text-xs font-semibold text-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800/50"
                onclick="document.querySelector('.batch-ids').value = JSON.stringify([...document.querySelectorAll('.task-checkbox:checked')].map(cb => cb.value))">
            Concluir
        </button>
    </form>
    <form method="POST" action="{{ route('tasks.batch.destroy') }}" class="inline">
        @csrf @method('DELETE')
        <input type="hidden" name="ids" class="batch-ids-del">
        <button type="submit"
                class="text-xs font-semibold text-red-400 bg-red-50 dark:bg-red-900/30 px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-800/50"
                onclick="if(!confirm('Excluir tarefas selecionadas?')) return false; document.querySelector('.batch-ids-del').value = JSON.stringify([...document.querySelectorAll('.task-checkbox:checked')].map(cb => cb.value))">
            Excluir
        </button>
    </form>
</div>

{{-- VIEW EM CARDS (Mobile) --}}
<div class="md:hidden space-y-3 animate-in animate-in-d2">
    @forelse($tasks as $task)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 shadow-sm {{ $task->status === 'concluido' ? 'opacity-60' : '' }}">
            {{-- HEADER --}}
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-start gap-2.5 min-w-0 flex-1">
                    <input type="checkbox" value="{{ $task->id }}" class="task-checkbox mt-1.5 w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-kvteal focus:ring-kvteal/30 transition-all cursor-pointer shrink-0"
                           onchange="const sel = document.querySelector('.mobile-batch-bar'); if (sel) { const checked = document.querySelectorAll('.task-checkbox:checked').length; sel.classList.toggle('hidden', checked === 0); document.querySelector('.mobile-batch-count').textContent = checked + ' selecionada(s)' }">
                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="quick-status-form shrink-0 mt-0.5">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status"
                               value="{{ $task->status === 'concluido' ? 'pendente' : 'concluido' }}">
                        <button type="submit"
                                class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all duration-200
                                {{ $task->status === 'concluido'
                                    ? 'bg-emerald-500 border-emerald-500 text-white'
                                    : 'border-slate-300 hover:border-kvteal hover:bg-kvteal/10' }}">
                            @if($task->status === 'concluido')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @endif
                        </button>
                    </form>
                    <div class="min-w-0">
                        <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-slate-700 dark:text-slate-200 hover:text-kvteal transition-colors block truncate">
                            {{ $task->title }}
                        </a>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                             <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                 {{ \App\Models\Category::COLORS[$task->cat?->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400' }}">
                                 {{ $task->category_label }}
                             </span>
                             <span class="text-xs font-semibold px-2 py-0.5 rounded-lg border
                                @if($task->priority === 'urgente') bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800/50
                                @elseif($task->priority === 'alta') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50
                                @elseif($task->priority === 'media') bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/50
                                @else bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700 @endif">
                                {{ $task->priority_label }}
                            </span>
                            @if($task->isRecurring())
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-kvteal/10 text-kvteal inline-flex items-center gap-0.5" title="{{ \App\Models\Task::FREQUENCIES[$task->recurring_frequency] ?? $task->recurring_frequency }}">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/></svg>
                                </span>
                            @endif
                            @if($task->project)
                                <a href="{{ route('projects.show', $task->project) }}" class="text-[10px] font-medium px-1.5 py-0.5 rounded-md bg-purple-50 dark:bg-purple-900/30 text-purple-500 dark:text-purple-400 inline-flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                                    {{ $task->project->title }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PROGRESSO --}}
            @if($task->progress > 0)
            <div class="flex items-center gap-2 mb-2">
                <div class="flex-1 h-1.5 rounded-full bg-slate-200 dark:bg-gray-700 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-kvteal to-emerald-400 transition-all duration-500"
                         style="width: {{ $task->progress }}%"></div>
                </div>
                <span class="text-[11px] font-bold text-kvteal tabular-nums">{{ $task->progress }}%</span>
            </div>
            @endif

            {{-- FOOTER --}}
            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('tasks.status', $task) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                class="text-[11px] font-semibold border rounded-lg px-2 py-1 outline-none transition-all cursor-pointer
                                @class([
                                    'bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700' => $task->status === 'pendente',
                                    'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50' => $task->status === 'em_andamento',
                                    'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50' => $task->status === 'concluido',
                                ])">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" @selected($task->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="text-xs {{ $task->isOverdue() ? 'text-red-500 font-medium' : 'text-slate-400 dark:text-slate-500' }}">
                        {{ $task->due_date->format('d/m') }}
                    </span>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('tasks.show', $task) }}" class="text-[11px] font-semibold text-kvteal hover:text-kvteal-dark px-2 py-1 rounded-lg transition-all">Ver</a>
                    <a href="{{ route('tasks.edit', $task) }}" class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 px-2 py-1 rounded-lg transition-all">Editar</a>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-8 text-center shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
            </div>
            <p class="text-slate-400 dark:text-slate-500 font-semibold">Nenhuma tarefa encontrada</p>
            <p class="text-slate-300 dark:text-slate-600 text-xs mt-0.5">Tente ajustar os filtros ou crie uma nova tarefa</p>
        </div>
    @endforelse
</div>

<div class="mt-5 animate-in animate-in-d3">{{ $tasks->appends(request()->query())->links() }}</div>
@endsection