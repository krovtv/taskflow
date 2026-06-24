@extends('layouts.app')
@section('title', $project->title)
@section('heading', $project->title)

@section('content')
<div class="max-w-4xl mx-auto animate-in space-y-6">
    {{-- CABEÇALHO --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold border {{ $project->status_class }}">
                        {{ $project->status_label }}
                    </span>
                    @if($project->start_date)
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                            {{ $project->start_date->format('d/m/Y') }}
                            @if($project->end_date)
                                → {{ $project->end_date->format('d/m/Y') }}
                            @endif
                        </span>
                    @endif
                </div>
                @if($project->description)
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $project->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('projects.edit', $project) }}"
                   class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Editar
                </a>
                <x-confirmation-modal
                    :action="route('projects.destroy', $project)"
                    title="Excluir projeto"
                    message='Remover "{{ $project->title }}" permanentemente? Fases e tarefas vinculadas serão desassociadas.'
                    buttonText="Excluir"
                    buttonClass="text-xs font-semibold text-red-400 hover:text-red-600 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    Excluir
                </x-confirmation-modal>
            </div>
        </div>

        {{-- PROGRESSO GERAL --}}
        @php $progress = $project->progress; @endphp
        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-gray-800">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Progresso geral</span>
                <span class="text-sm font-extrabold text-kvteal">{{ $progress }}%</span>
            </div>
            <div class="h-2 rounded-full bg-slate-200 dark:bg-gray-700 overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-kvteal transition-all duration-700 ease-out" style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>

    {{-- FASES --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-amber-400 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-kvnavy dark:text-white">Fases do projeto</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">{{ $phases->count() }} fase(s) · início, meio e fim</p>
                </div>
            </div>
            <button @click="$refs.phaseForm.classList.toggle('hidden')"
                    class="text-xs font-semibold text-purple-500 hover:text-purple-600 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/40 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nova fase
            </button>
        </div>

        {{-- FORM NOVA FASE --}}
        <form x-ref="phaseForm" method="POST" action="{{ route('projects.phases.store', $project) }}" class="hidden mb-6 p-4 bg-slate-50 dark:bg-gray-800/50 rounded-xl border border-slate-200 dark:border-gray-700">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Título da fase</label>
                    <input type="text" name="title" required placeholder="Ex: Concepção, Desenvolvimento, Entrega"
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Início</label>
                    <input type="date" name="start_date"
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Término</label>
                    <input type="date" name="end_date"
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Descrição (opcional)</label>
                    <textarea name="description" rows="2" placeholder="O que será feito nesta fase..."
                              class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none resize-y"></textarea>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" @click="$refs.phaseForm.classList.add('hidden')"
                        class="text-xs font-medium text-slate-400 hover:text-slate-600 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-gray-700 transition-all">
                    Cancelar
                </button>
                <button type="submit" class="text-xs font-semibold text-white bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 px-4 py-1.5 rounded-lg transition-all shadow-sm">
                    Adicionar fase
                </button>
            </div>
        </form>

        {{-- LISTA DE FASES --}}
        @forelse($phases as $phase)
            <div class="flex items-start gap-4 pb-4 mb-4 border-b border-slate-100 dark:border-gray-800 last:border-0 last:mb-0 last:pb-0">
                <div class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2
                        @if($phase->status === 'concluido') bg-emerald-100 dark:bg-emerald-900/30 border-emerald-400 text-emerald-600 dark:text-emerald-400
                        @elseif($phase->status === 'em_andamento') bg-amber-100 dark:bg-amber-900/30 border-amber-400 text-amber-600 dark:text-amber-400
                        @else bg-slate-100 dark:bg-gray-800 border-slate-300 dark:border-gray-600 text-slate-500 dark:text-slate-400 @endif">
                        {{ $loop->iteration }}
                    </div>
                    @if(!$loop->last)
                        <div class="w-0.5 flex-1 min-h-[20px] bg-slate-200 dark:bg-gray-700"></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h4 class="font-semibold text-sm text-kvnavy dark:text-white">{{ $phase->title }}</h4>
                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full border
                            @if($phase->status === 'concluido') bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50
                            @elseif($phase->status === 'em_andamento') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50
                            @else bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700 @endif">
                            {{ $phase->status_label }}
                        </span>
                    </div>
                    @if($phase->description)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ $phase->description }}</p>
                    @endif
                    @if($phase->start_date || $phase->end_date)
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">
                            {{ $phase->start_date?->format('d/m/Y') ?? '—' }} → {{ $phase->end_date?->format('d/m/Y') ?? '—' }}
                        </p>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button @click="$refs.phaseEdit{{ $phase->id }}.classList.toggle('hidden')"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    </button>
                    <x-confirmation-modal
                        :action="route('projects.phases.destroy', [$project, $phase])"
                        title="Excluir fase"
                        message='Remover a fase "{{ $phase->title }}" permanentemente?'
                        buttonText="Excluir"
                        buttonClass="p-1.5 rounded-lg text-red-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </x-confirmation-modal>
                </div>
            </div>

            {{-- EDITAR FASE --}}
            <div x-ref="phaseEdit{{ $phase->id }}" class="hidden mb-4 p-4 bg-slate-50 dark:bg-gray-800/50 rounded-xl border border-slate-200 dark:border-gray-700">
                <form method="POST" action="{{ route('projects.phases.update', [$project, $phase]) }}">
                    @csrf @method('PUT')
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Título</label>
                            <input type="text" name="title" value="{{ $phase->title }}" required
                                   class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Descrição</label>
                            <textarea name="description" rows="2"
                                      class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">{{ $phase->description }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Status</label>
                            <select name="status" required
                                    class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none">
                                @foreach(\App\Models\ProjectPhase::STATUSES as $key => $label)
                                    <option value="{{ $key }}" @selected($phase->status === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end justify-end gap-2">
                            <button type="button" @click="$refs.phaseEdit{{ $phase->id }}.classList.add('hidden')"
                                    class="text-xs font-medium text-slate-400 hover:text-slate-600 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-gray-700 transition-all">
                                Cancelar
                            </button>
                            <button type="submit" class="text-xs font-semibold text-white bg-gradient-to-r from-kvteal to-kvteal-dark px-4 py-1.5 rounded-lg transition-all shadow-sm">
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @empty
            <div class="text-center py-8">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                </div>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Nenhuma fase cadastrada</p>
                <p class="text-xs text-slate-300 dark:text-slate-600 mt-1">Adicione fases para dividir o projeto em etapas: <strong>início, desenvolvimento, entrega...</strong></p>
            </div>
        @endforelse
    </div>

    {{-- TAREFAS VINCULADAS --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-kvteal to-emerald-400 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-kvnavy dark:text-white">Tarefas vinculadas</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">{{ $tasks->count() }} tarefa(s)</p>
                </div>
            </div>
            <a href="{{ route('tasks.create', ['project' => $project->id]) }}"
               class="text-xs font-semibold text-emerald-500 hover:text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nova tarefa
            </a>
        </div>

        @forelse($tasks as $task)
            <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-gray-800 last:border-0">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full shrink-0
                            @if($task->status === 'concluido') bg-emerald-400
                            @elseif($task->isOverdue()) bg-red-400
                            @elseif($task->status === 'em_andamento') bg-amber-400
                            @else bg-slate-300 dark:bg-gray-600 @endif">
                        </span>
                        <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-slate-700 dark:text-slate-200 hover:text-kvteal transition-colors truncate">
                            {{ $task->title }}
                        </a>
                        @if($task->phase)
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">{{ $task->phase->title }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                        {{ $task->due_date->format('d/m/Y H:i') }} · {{ $task->priority_label }}
                    </p>
                </div>
                <div class="flex items-center gap-2 ml-4">
                    @if($task->progress > 0)
                        <span class="text-[11px] font-bold text-kvteal">{{ $task->progress }}%</span>
                    @endif
                    <a href="{{ route('tasks.show', $task) }}" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark transition-colors">Abrir →</a>
                </div>
            </div>
        @empty
            <div class="text-center py-6">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                </div>
                <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Nenhuma tarefa vinculada</p>
                <p class="text-xs text-slate-300 dark:text-slate-600 mt-0.5">Crie tarefas vinculadas a este projeto para acompanhá-las aqui</p>
            </div>
        @endforelse
    </div>

    {{-- NAVEGAÇÃO --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('projects.index') }}" class="text-sm font-medium text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Todos os projetos
        </a>
    </div>
</div>
@endsection
