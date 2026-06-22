@extends('layouts.app')
@section('title', $task->title)
@section('heading', $task->title)

@section('content')
<div class="max-w-3xl mx-auto animate-in">
    {{-- STATUS BAR --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-2.5">
            <span class="w-3 h-3 rounded-full
                @if($task->priority === 'urgente') bg-red-500 shadow-sm shadow-red-500/30
                @elseif($task->priority === 'alta') bg-amber-500 shadow-sm shadow-amber-500/30
                @elseif($task->priority === 'media') bg-blue-500 shadow-sm shadow-blue-500/30
                @else bg-slate-300 @endif">
            </span>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg border
                @if($task->priority === 'urgente') bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800/50
                @elseif($task->priority === 'alta') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50
                @elseif($task->priority === 'media') bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/50
                @else bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700 @endif">
                {{ $task->priority_label }}
            </span>
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $task->category_badge }}">
                {{ $task->category_label }}
            </span>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg
                @if($task->status === 'pendente') bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400
                @elseif($task->status === 'em_andamento') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                @else bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 @endif">
                {{ $task->status_label }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.edit', $task) }}"
               class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                Editar
            </a>
            <x-confirmation-modal
                :action="route('tasks.destroy', $task)"
                title="Excluir tarefa"
                message='Remover "{{ $task->title }}" permanentemente?'
                buttonText="Excluir"
                buttonClass="text-xs font-semibold text-red-400 dark:text-red-400 hover:text-red-600 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                Excluir
            </x-confirmation-modal>
        </div>
    </div>

    {{-- CONTEÚDO --}}
    <div class="grid gap-6">
        {{-- DESCRIÇÃO --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
            <h3 class="font-bold text-kvnavy dark:text-white text-base mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Descrição
            </h3>
            @if($task->description)
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $task->description }}</p>
            @else
                <p class="text-sm text-slate-400 dark:text-slate-500 italic">Nenhuma descrição fornecida.</p>
            @endif
        </div>

        {{-- DETALHES --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
            <h3 class="font-bold text-kvnavy dark:text-white text-base mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                Detalhes
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Prazo</p>
                    <p class="text-sm font-semibold text-kvnavy dark:text-white {{ $task->isOverdue() ? 'text-red-500' : '' }}">
                        {{ $task->due_date->format('d/m/Y H:i') }}
                    </p>
                    @if($task->isOverdue())
                        <p class="text-[10px] text-red-400 font-medium mt-0.5">
                            {{ $task->due_date->diffForHumans() }}
                        </p>
                    @endif
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Status</p>
                    <p class="text-sm font-semibold">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                            @if($task->status === 'pendente') bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400
                            @elseif($task->status === 'em_andamento') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                            @else bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 @endif">
                            {{ $task->status_label }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Categoria</p>
                    <p class="text-sm font-semibold text-kvnavy dark:text-white">{{ $task->category_label }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Prioridade</p>
                    <p class="text-sm font-semibold text-kvnavy dark:text-white">{{ $task->priority_label }}</p>
                </div>
                @if($task->estimated_hours)
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Horas estimadas</p>
                    <p class="text-sm font-semibold text-kvnavy dark:text-white">{{ $task->estimated_hours }}h</p>
                </div>
                @if($task->isRecurring())
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Recorrência</p>
                    <p class="text-sm font-semibold text-kvnavy dark:text-white inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/></svg>
                        {{ \App\Models\Task::FREQUENCIES[$task->recurring_frequency] ?? $task->recurring_frequency }}
                        @if($task->recurring_end_date)
                            · até {{ $task->recurring_end_date->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
                @endif
                @endif
                @if($task->progress > 0)
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Progresso</p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-1.5 rounded-full bg-slate-200 dark:bg-gray-700 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-kvteal to-emerald-400 transition-all duration-500"
                                 style="width: {{ $task->progress }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-kvteal">{{ $task->progress }}%</span>
                    </div>
                </div>
                @endif
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Criada em</p>
                    <p class="text-sm font-semibold text-kvnavy dark:text-white">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($task->project)
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Projeto</p>
                    <a href="{{ route('projects.show', $task->project) }}" class="text-sm font-semibold text-purple-500 dark:text-purple-400 hover:text-purple-600 dark:hover:text-purple-300 transition-colors inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                        {{ $task->project->title }}
                    </a>
                    @if($task->phase)
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Fase: {{ $task->phase->title }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- TAGS --}}
        @if($task->tags)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
            <h3 class="font-bold text-kvnavy dark:text-white text-base mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                Tags
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($task->tags_array as $tag)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-gray-700">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ANEXOS --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
            <h3 class="font-bold text-kvnavy dark:text-white text-base mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                Anexos
            </h3>
            @if($task->attachments->count() > 0)
                <div class="space-y-2 mb-4">
                    @foreach($task->attachments as $att)
                        <div class="flex items-center justify-between bg-slate-50 dark:bg-gray-800/50 rounded-xl px-4 py-3 border border-slate-100 dark:border-gray-700/50">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($att->is_image)
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-200 dark:bg-gray-700 shrink-0">
                                        <img src="{{ $att->url }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-200 dark:bg-gray-700 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $att->original_name }}</p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $att->size_for_humans }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-3">
                                <a href="{{ route('tasks.download', [$task, $att]) }}" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all">Download</a>
                                <x-confirmation-modal
                                    :action="route('tasks.attachment.delete', [$task, $att])"
                                    title="Excluir anexo"
                                    message='Remover "{{ $att->original_name }}" permanentemente?'
                                    buttonText="Excluir"
                                    buttonClass="text-xs font-semibold text-red-400 hover:text-red-600 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-all">
                                    Excluir
                                </x-confirmation-modal>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400 dark:text-slate-500 italic mb-4">Nenhum anexo.</p>
            @endif
            <form method="POST" action="{{ route('tasks.upload', $task) }}" enctype="multipart/form-data" class="flex items-center gap-3">
                @csrf
                <label class="flex-1 flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-slate-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-kvteal/50 dark:hover:border-kvteal/50 transition-colors text-sm text-slate-400 dark:text-slate-500">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <span>Clique para anexar arquivo</span>
                    <input type="file" name="file" class="hidden" onchange="this.form.submit()">
                </label>
                <button type="submit" class="text-sm font-semibold text-white bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] px-4 py-2.5 rounded-xl transition-all shadow-sm">Enviar</button>
            </form>
        </div>
    </div>

    {{-- NAVEGAÇÃO --}}
    <div class="mt-8 flex items-center justify-between">
        <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Voltar para tarefas
        </a>
        <a href="{{ route('tasks.edit', $task) }}" class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-bold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
            Editar tarefa
        </a>
    </div>
</div>
@endsection