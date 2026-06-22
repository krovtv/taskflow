@extends('layouts.app')
@section('title', $project->title ?? 'Kanban')
@section('heading', isset($project) ? $project->title . ' — Kanban' : 'Kanban')

@section('content')
<div x-data="kanbanBoard(@json($grouped))" class="animate-in">
    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            @isset($project)
                <a href="{{ route('projects.show', $project) }}" class="p-2 rounded-xl hover:bg-white/50 dark:hover:bg-gray-800/50 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </a>
            @endisset
            <div>
                <p class="text-sm text-slate-400 dark:text-slate-500">Arraste os cards entre as colunas para mudar o status</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(!isset($project))
                <select @change="window.location = '?project=' + ($event.target.value || '') + '&category=' + (new URLSearchParams(window.location.search).get('category') || '')"
                        class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none">
                    <option value="">Todos os projetos</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @selected($projectId == $p->id)>{{ $p->title }}</option>
                    @endforeach
                </select>
            @endif
            <a href="{{ route('tasks.create', isset($project) ? ['project' => $project->id] : []) }}" class="text-xs font-semibold text-white bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-kvteal-dark hover:to-kvteal px-4 py-2.5 rounded-xl transition-all shadow-sm shadow-kvteal/20 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nova tarefa
            </a>
        </div>
    </div>

    {{-- COLUNAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
        {{-- PENDENTE --}}
        <div class="bg-slate-50/80 dark:bg-gray-800/40 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 flex flex-col transition-all duration-200"
             @drop.prevent="onDrop($event, 'pendente')"
             @dragover.prevent="dragOverColumn = 'pendente'"
             @dragleave="dragOverColumn = null"
             :class="dragOverColumn === 'pendente' ? 'ring-2 ring-kvteal/40 shadow-lg shadow-kvteal/5' : ''">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-slate-200 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pendente</h3>
                </div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 bg-white dark:bg-gray-800 px-2 py-0.5 rounded-lg border border-slate-200 dark:border-gray-700 tabular-nums" x-text="tasks.pendente.length"></span>
            </div>
            <div class="px-4 pb-4 space-y-3 min-h-[120px] flex-1 overflow-y-auto max-h-[calc(100vh-260px)]" x-ref="columnPendente">
                <template x-for="task in tasks.pendente" :key="task.id">
                    <div draggable="true"
                         @dragstart="onDragStart($event, task)"
                         @dragend="onDragEnd()"
                         :class="['bg-white dark:bg-gray-800 rounded-xl p-4 border border-slate-200 dark:border-gray-700 shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md hover:border-slate-300 dark:hover:border-gray-600 transition-all select-none',
                                  dragging === task.id ? 'opacity-40 scale-[0.96] rotate-[-0.5deg] shadow-none' : '']">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <a :href="'/tasks/' + task.id"
                               class="text-sm font-semibold text-kvnavy dark:text-white hover:text-kvteal transition-colors line-clamp-2 flex-1 min-w-0"
                               x-text="task.title"
                               @click.stop></a>
                            <span class="shrink-0 w-2.5 h-2.5 rounded-full mt-1"
                                  :class="{'bg-red-400 shadow-sm shadow-red-400/30': task.priority === 'urgente',
                                           'bg-amber-400 shadow-sm shadow-amber-400/30': task.priority === 'alta',
                                           'bg-blue-400 shadow-sm shadow-blue-400/30': task.priority === 'media',
                                           'bg-slate-300 dark:bg-gray-600': !task.priority || task.priority === 'baixa'}"></span>
                        </div>
                        <template x-if="task.project">
                            <div class="mb-2">
                                <span class="text-[10px] font-semibold text-purple-500 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-0.5 rounded-md inline-block"
                                      x-text="task.project.title"></span>
                            </div>
                        </template>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span x-text="formatDate(task.due_date)"></span>
                            </span>
                            <span x-show="task.progress > 0" class="font-bold text-kvteal tabular-nums" x-text="task.progress + '%'"></span>
                        </div>
                    </div>
                </template>
                <div x-show="tasks.pendente.length === 0" class="text-center py-10">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-gray-700/50 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Nenhuma tarefa pendente</p>
                </div>
            </div>
        </div>

        {{-- EM ANDAMENTO --}}
        <div class="bg-amber-50/60 dark:bg-amber-900/10 rounded-2xl border border-amber-200/50 dark:border-amber-800/30 flex flex-col transition-all duration-200"
             @drop.prevent="onDrop($event, 'em_andamento')"
             @dragover.prevent="dragOverColumn = 'em_andamento'"
             @dragleave="dragOverColumn = null"
             :class="dragOverColumn === 'em_andamento' ? 'ring-2 ring-kvteal/40 shadow-lg shadow-kvteal/5' : ''">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-200/60 dark:bg-amber-800/30 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    </div>
                    <h3 class="font-bold text-xs text-amber-700 dark:text-amber-400 uppercase tracking-wider">Em andamento</h3>
                </div>
                <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-white dark:bg-gray-800 px-2 py-0.5 rounded-lg border border-amber-200 dark:border-amber-800/50 tabular-nums" x-text="tasks.em_andamento.length"></span>
            </div>
            <div class="px-4 pb-4 space-y-3 min-h-[120px] flex-1 overflow-y-auto max-h-[calc(100vh-260px)]" x-ref="columnAndamento">
                <template x-for="task in tasks.em_andamento" :key="task.id">
                    <div draggable="true"
                         @dragstart="onDragStart($event, task)"
                         @dragend="onDragEnd()"
                         :class="['bg-white dark:bg-gray-800 rounded-xl p-4 border border-amber-200/60 dark:border-amber-800/40 shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md hover:border-amber-300 dark:hover:border-amber-700/60 transition-all select-none',
                                  dragging === task.id ? 'opacity-40 scale-[0.96] rotate-[-0.5deg] shadow-none' : '']">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <a :href="'/tasks/' + task.id"
                               class="text-sm font-semibold text-kvnavy dark:text-white hover:text-kvteal transition-colors line-clamp-2 flex-1 min-w-0"
                               x-text="task.title"
                               @click.stop></a>
                            <span class="shrink-0 w-2.5 h-2.5 rounded-full mt-1"
                                  :class="{'bg-red-400 shadow-sm shadow-red-400/30': task.priority === 'urgente',
                                           'bg-amber-400 shadow-sm shadow-amber-400/30': task.priority === 'alta',
                                           'bg-blue-400 shadow-sm shadow-blue-400/30': task.priority === 'media',
                                           'bg-slate-300 dark:bg-gray-600': !task.priority || task.priority === 'baixa'}"></span>
                        </div>
                        <template x-if="task.project">
                            <div class="mb-2">
                                <span class="text-[10px] font-semibold text-purple-500 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-0.5 rounded-md inline-block"
                                      x-text="task.project.title"></span>
                            </div>
                        </template>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span x-text="formatDate(task.due_date)"></span>
                            </span>
                            <span x-show="task.progress > 0" class="font-bold text-kvteal tabular-nums" x-text="task.progress + '%'"></span>
                        </div>
                    </div>
                </template>
                <div x-show="tasks.em_andamento.length === 0" class="text-center py-10">
                    <div class="w-10 h-10 rounded-xl bg-amber-100/50 dark:bg-amber-900/20 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs text-amber-500 dark:text-amber-400 font-medium">Nenhuma tarefa em andamento</p>
                </div>
            </div>
        </div>

        {{-- CONCLUÍDO --}}
        <div class="bg-emerald-50/60 dark:bg-emerald-900/10 rounded-2xl border border-emerald-200/50 dark:border-emerald-800/30 flex flex-col transition-all duration-200"
             @drop.prevent="onDrop($event, 'concluido')"
             @dragover.prevent="dragOverColumn = 'concluido'"
             @dragleave="dragOverColumn = null"
             :class="dragOverColumn === 'concluido' ? 'ring-2 ring-kvteal/40 shadow-lg shadow-kvteal/5' : ''">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-200/60 dark:bg-emerald-800/30 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </div>
                    <h3 class="font-bold text-xs text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Concluído</h3>
                </div>
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-white dark:bg-gray-800 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-800/50 tabular-nums" x-text="tasks.concluido.length"></span>
            </div>
            <div class="px-4 pb-4 space-y-3 min-h-[120px] flex-1 overflow-y-auto max-h-[calc(100vh-260px)]" x-ref="columnConcluido">
                <template x-for="task in tasks.concluido" :key="task.id">
                    <div draggable="true"
                         @dragstart="onDragStart($event, task)"
                         @dragend="onDragEnd()"
                         :class="['bg-white dark:bg-gray-800 rounded-xl p-4 border border-emerald-200/60 dark:border-emerald-800/40 shadow-sm cursor-grab active:cursor-grabbing hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700/60 transition-all select-none',
                                  dragging === task.id ? 'opacity-40 scale-[0.96] rotate-[-0.5deg] shadow-none' : '']">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                <a :href="'/tasks/' + task.id"
                                   class="text-sm font-semibold text-kvnavy dark:text-white hover:text-kvteal transition-colors line-clamp-2"
                                   x-text="task.title"
                                   @click.stop></a>
                            </div>
                            <span class="shrink-0 w-2.5 h-2.5 rounded-full mt-1"
                                  :class="{'bg-red-400 shadow-sm shadow-red-400/30': task.priority === 'urgente',
                                           'bg-amber-400 shadow-sm shadow-amber-400/30': task.priority === 'alta',
                                           'bg-blue-400 shadow-sm shadow-blue-400/30': task.priority === 'media',
                                           'bg-slate-300 dark:bg-gray-600': !task.priority || task.priority === 'baixa'}"></span>
                        </div>
                        <template x-if="task.project">
                            <div class="mb-2 ml-6">
                                <span class="text-[10px] font-semibold text-purple-500 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-0.5 rounded-md inline-block"
                                      x-text="task.project.title"></span>
                            </div>
                        </template>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 ml-6">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span x-text="formatDate(task.due_date)"></span>
                            </span>
                        </div>
                    </div>
                </template>
                <div x-show="tasks.concluido.length === 0" class="text-center py-10">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100/50 dark:bg-emerald-900/20 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs text-emerald-500 dark:text-emerald-400 font-medium">Nenhuma tarefa concluída</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function kanbanBoard(tasks) {
    return {
        tasks: tasks,
        dragging: null,
        dragOverColumn: null,
        onDragStart(event, task) {
            this.dragging = task.id;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', task.id);
        },
        onDrop(event, newStatus) {
            this.dragOverColumn = null;
            const taskId = parseInt(event.dataTransfer.getData('text/plain'));
            if (!taskId) return;
            this.moveTask(taskId, newStatus);
        },
        onDragEnd() {
            this.dragging = null;
            this.dragOverColumn = null;
        },
        moveTask(taskId, newStatus) {
            let task = null;
            let oldStatus = null;
            for (const status of ['pendente', 'em_andamento', 'concluido']) {
                const idx = this.tasks[status].findIndex(t => t.id === taskId);
                if (idx !== -1) {
                    task = this.tasks[status].splice(idx, 1)[0];
                    oldStatus = status;
                    break;
                }
            }
            if (!task) return;

            if (oldStatus === newStatus) {
                this.tasks[newStatus].push(task);
                return;
            }

            this.tasks[newStatus].push(task);

            fetch('/tasks/' + taskId + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            }).then(res => {
                if (!res.ok) throw new Error();
                return res.json();
            }).catch(() => {
                const idx = this.tasks[newStatus].findIndex(t => t.id === taskId);
                if (idx !== -1) this.tasks[newStatus].splice(idx, 1);
                this.tasks[oldStatus].push(task);
            });
        },
        formatDate(dateStr) {
            if (!dateStr) return 'Sem prazo';
            const d = new Date(dateStr);
            return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
        }
    }
}
</script>
@endpush
