@extends('layouts.app')
@section('title', 'Agenda')
@section('heading', 'Agenda semanal')

@section('content')
{{-- NAVEGAÇÃO SEMANAL --}}
<div class="flex items-center justify-between mb-6 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 px-4 md:px-6 py-3.5 shadow-sm animate-in">
    <a href="{{ route('agenda.index', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}"
       class="text-sm font-semibold text-kvteal hover:text-kvteal-dark transition-colors inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Anterior
    </a>
    <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-kvteal to-kvteal-dark flex items-center justify-center shadow-sm shadow-kvteal/20">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        </div>
        <p class="font-bold text-kvnavy dark:text-white text-sm">{{ $weekStart->format('d/m') }} — {{ $weekEnd->format('d/m/Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('agenda.index') }}"
           class="text-xs font-medium text-slate-400 dark:text-slate-500 hover:text-kvteal px-2 py-1 rounded-lg hover:bg-kvteal/5 transition-all">
            Hoje
        </a>
        <a href="{{ route('agenda.index', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}"
           class="text-sm font-semibold text-kvteal hover:text-kvteal-dark transition-colors inline-flex items-center gap-1.5">
            Próximo
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </a>
    </div>
</div>

{{--- RESUMO DA SEMANA ---}}
<div class="flex flex-wrap items-center gap-3 mb-5 animate-in animate-in-d1">
    @php
        $totalSemana = 0;
        $porCategoria = [];
        foreach ($dias as $dia) {
            $key = $dia->format('Y-m-d');
            $tasks = $tasksPorDia->get($key, collect());
            $totalSemana += $tasks->count();
            foreach ($tasks as $t) {
                $catName = $t->cat?->name ?? $t->category ?? 'sem categoria';
                $badge = \App\Models\Category::COLORS[$t->cat?->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400';
                if (!isset($porCategoria[$catName])) {
                    $porCategoria[$catName] = ['count' => 0, 'badge' => $badge];
                }
                $porCategoria[$catName]['count']++;
            }
        }
    @endphp
    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 bg-white dark:bg-gray-900 px-3 py-1.5 rounded-lg border border-slate-200/70 dark:border-gray-700/50 shadow-sm">
        <span class="text-kvnavy dark:text-white">{{ $totalSemana }}</span> tarefas nesta semana
    </span>
    @foreach($porCategoria as $catName => $info)
        <span class="text-xs font-medium px-2.5 py-1 rounded-lg {{ $info['badge'] }}">
            {{ $catName }}: {{ $info['count'] }}
        </span>
    @endforeach
</div>

{{-- GRADE DIAS --}}
<div class="grid grid-cols-2 md:grid-cols-7 gap-3">
    @foreach($dias as $i => $dia)
        @php
            $key = $dia->format('Y-m-d');
            $tasksHoje = $tasksPorDia->get($key, collect());
        @endphp
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 min-h-[180px] md:min-h-[220px] card-hover shadow-sm animate-in animate-in-d{{ min($i + 1, 5) }} flex flex-col {{ $dia->isToday() ? 'ring-2 ring-kvteal ring-offset-2 ring-offset-slate-50 dark:ring-offset-gray-900' : '' }}">
            {{-- CABECALHO DO DIA --}}
            <div class="px-3 pt-3 pb-2 border-b border-slate-100 dark:border-gray-800 {{ $dia->isToday() ? 'bg-gradient-to-r from-kvteal/[0.04] to-transparent' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest {{ $dia->isToday() ? 'text-kvteal' : 'text-slate-400 dark:text-slate-500' }}">{{ $dia->translatedFormat('D') }}</p>
                        <p class="font-extrabold text-kvnavy dark:text-white text-base leading-tight {{ $dia->isToday() ? 'text-kvteal dark:text-kvteal' : '' }}">
                            {{ $dia->format('d') }}
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500 ml-0.5">{{ $dia->format('M') }}</span>
                        </p>
                    </div>
                    @if($tasksHoje->count() > 0)
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">{{ $tasksHoje->count() }}</span>
                    @endif
                </div>
                @if($tasksHoje->count() > 0)
                    @php
                        $catDots = $tasksHoje->pluck('cat.color')->unique()->filter()->map(fn($c) => \App\Models\Category::COLORS[$c]['dot'] ?? null)->filter();
                    @endphp
                    @if($catDots->count() > 0)
                        <div class="flex items-center gap-1 mt-1.5">
                            @foreach($catDots as $dot)
                                <span class="w-2 h-2 rounded-full {{ $dot }}"></span>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            {{-- TAREFAS DO DIA --}}
            <div class="flex-1 p-3 space-y-1.5 overflow-y-auto">
                @forelse($tasksHoje as $task)
                    <a href="{{ route('tasks.show', $task) }}"
                       class="block text-xs rounded-xl font-medium transition-all duration-200
                        {{ \App\Models\Category::COLORS[$task->cat?->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400' }} hover:shadow-sm

                       @if($task->isOverdue()) border-l-2 border-red-400 @endif
                       @if($task->status === 'concluido') opacity-50 line-through @endif">
                        <div class="px-2 py-1.5">
                            <div class="flex items-center gap-1.5">
                                @if($task->priority === 'urgente')
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                                @elseif($task->priority === 'alta')
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                @endif
                                <p class="truncate font-semibold">{{ $task->title }}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="opacity-70 text-[10px] font-medium">{{ $task->due_date->format('H:i') }}</span>
                                @if($task->status === 'em_andamento')
                                    <span class="text-[9px] px-1 py-0.5 rounded bg-amber-200/40 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 font-semibold">em andamento</span>
                                @endif
                                @if($task->isOverdue() && $task->status !== 'concluido')
                                    <span class="text-[9px] px-1 py-0.5 rounded bg-red-200/40 dark:bg-red-900/40 text-red-600 dark:text-red-400 font-semibold">atrasada</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center h-full min-h-[60px]">
                        <svg class="w-5 h-5 text-slate-200 dark:text-gray-700 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        <p class="text-[10px] text-slate-300 dark:text-gray-600 font-medium">Sem tarefas</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection