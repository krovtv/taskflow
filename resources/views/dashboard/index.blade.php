@extends('layouts.app')
@section('title', 'Visão geral')
@section('heading', 'Visão geral')

@section('content')
{{-- CARDS DE ESTATÍSTICAS --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    @php
        $icons = [
            'Total' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>',
            'Pendentes' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'Em andamento' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"/></svg>',
            'Concluídas' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'Atrasadas' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>',
            'Semana' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008z"/></svg>',
        ];
        $cards = [
            ['label' => 'Total', 'value' => $stats['total'], 'bg' => 'from-kvnavy to-kvnavy/80'],
            ['label' => 'Pendentes', 'value' => $stats['pendentes'], 'bg' => 'from-slate-500 to-slate-400'],
            ['label' => 'Em andamento', 'value' => $stats['andamento'], 'bg' => 'from-amber-500 to-amber-400'],
            ['label' => 'Concluídas', 'value' => $stats['concluidas'], 'bg' => 'from-emerald-500 to-emerald-400'],
            ['label' => 'Atrasadas', 'value' => $stats['atrasadas'], 'bg' => 'from-red-500 to-red-400'],
            ['label' => 'Semana', 'value' => $stats['concluidas_semana'], 'bg' => 'from-violet-500 to-violet-400'],
        ];
    @endphp
    @foreach($cards as $i => $card)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 card-hover animate-in animate-in-d{{ $i + 1 }} shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $card['bg'] }} text-white flex items-center justify-center shadow-sm">
                    {!! $icons[$card['label']] !!}
                </span>
            </div>
            <p class="text-2xl font-bold text-kvnavy dark:text-white tracking-tight">{{ $card['value'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

{{-- GRÁFICOS --}}
<div class="grid md:grid-cols-2 gap-6 mb-8">
    {{-- PROGRESSO GERAL --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover animate-in animate-in-d5 shadow-sm">
        <h3 class="font-bold text-kvnavy dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            Progresso geral
        </h3>
        @php $concluidos = $stats['concluidas']; $total = max($stats['total'], 1); $pct = round($concluidos / $total * 100); @endphp
        <div class="flex items-center gap-5 mb-4">
            <div class="relative w-20 h-20">
                <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="currentColor" class="text-slate-100 dark:text-gray-800" stroke-width="3"/>
                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="#1ec2cf" stroke-width="3" stroke-dasharray="97.4" stroke-dashoffset="{{ 97.4 - (97.4 * $pct / 100) }}" stroke-linecap="round"/>
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-sm font-extrabold text-kvnavy dark:text-white">{{ $pct }}%</span>
            </div>
            <div class="flex-1 space-y-2">
                @php
                    $statusData = [
                        ['label' => 'Concluídas', 'count' => $stats['concluidas'], 'color' => 'bg-emerald-500'],
                        ['label' => 'Em andamento', 'count' => $stats['andamento'], 'color' => 'bg-amber-400'],
                        ['label' => 'Pendentes', 'count' => $stats['pendentes'], 'color' => 'bg-slate-400'],
                        ['label' => 'Atrasadas', 'count' => $stats['atrasadas'], 'color' => 'bg-red-500'],
                    ];
                @endphp
                @foreach($statusData as $s)
                    @php $barPct = $total > 0 ? round($s['count'] / $total * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">{{ $s['label'] }}</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200">{{ $s['count'] }} ({{ $barPct }}%)</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-slate-100 dark:bg-gray-800 overflow-hidden">
                            <div class="h-full rounded-full {{ $s['color'] }} transition-all duration-700" style="width: {{ $barPct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PRIORIDADES --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover animate-in animate-in-d5 shadow-sm">
        <h3 class="font-bold text-kvnavy dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25"/></svg>
            Tarefas por prioridade
        </h3>
        <div class="space-y-3">
            @foreach($porPrioridade as $key => $p)
                @php
                    $barMax = max(max(array_column($porPrioridade, 'total')), 1);
                    $barW = round($p['total'] / $barMax * 100);
                    $colors = ['baixa' => 'bg-slate-400', 'media' => 'bg-blue-500', 'alta' => 'bg-amber-500', 'urgente' => 'bg-red-500'];
                    $color = $colors[$key] ?? 'bg-slate-400';
                @endphp
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium text-slate-600 dark:text-slate-300">{{ $p['label'] }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $p['total'] }} tarefas · {{ $p['concluidas'] }} concluídas</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100 dark:bg-gray-800 overflow-hidden">
                        <div class="h-full rounded-full {{ $color }} transition-all duration-700" style="width: {{ $barW }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ATIVIDADE SEMANAL --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover animate-in animate-in-d5 shadow-sm mb-8">
    <h3 class="font-bold text-kvnavy dark:text-white mb-5 flex items-center gap-2">
        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
        Atividade semanal (concluídas)
    </h3>
    <div class="flex items-end gap-3 h-28">
        @php $maxAtv = max(max(array_column($atividadeSemanal, 'count')), 1); @endphp
        @foreach($atividadeSemanal as $day)
            @php $h = round($day['count'] / $maxAtv * 100); @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">{{ $day['count'] }}</span>
                <div class="w-full rounded-lg bg-gradient-to-t from-kvteal to-kvteal-light transition-all duration-700" style="height: {{ max($h, 4) }}%"></div>
                <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500">{{ $day['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- CATEGORIAS --}}
<div class="grid md:grid-cols-4 gap-4 mb-8">
    {{-- DONUT --}}
    @php
        $totalCat = array_sum(array_column($porCategoria, 'total'));
        $circ = 2 * M_PI * 15.5;
        $acc = 0;
    @endphp
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 card-hover shadow-sm flex items-center justify-center min-h-[180px] animate-in animate-in-d1">
        <div class="relative w-28 h-28">
            <svg class="w-28 h-28 -rotate-90" viewBox="0 0 36 36">
                <circle cx="18" cy="18" r="15.5" fill="none" stroke="currentColor" class="text-slate-100 dark:text-gray-800" stroke-width="3.5"/>
                @if($totalCat > 0)
                    @foreach($porCategoria as $cat)
                        @php
                            $segLen = $circ * ($cat['total'] / $totalCat);
                            $offset = -$acc;
                            $acc += $segLen;
                        @endphp
                        @if($segLen > 0)
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="{{ $cat['stroke'] }}" stroke-width="3.5"
                                    stroke-dasharray="{{ $segLen }} {{ $circ - $segLen }}"
                                    stroke-dashoffset="{{ $offset }}" stroke-linecap="butt"/>
                        @endif
                    @endforeach
                @endif
            </svg>
            <span class="absolute inset-0 flex items-center justify-center text-lg font-extrabold text-kvnavy dark:text-white">{{ $totalCat }}</span>
        </div>
    </div>
    {{-- CARDS --}}
    <div class="md:col-span-3 grid grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($porCategoria as $key => $cat)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 card-hover animate-in animate-in-d{{ $loop->iteration + 1 }} shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-2.5 h-2.5 rounded-full {{ $cat['dot'] }} shrink-0"></span>
                        <h3 class="font-bold text-kvnavy dark:text-white text-sm truncate">{{ $cat['label'] }}</h3>
                    </div>
                    <span class="text-lg font-extrabold text-kvteal shrink-0 ml-2">{{ $cat['total'] }}</span>
                </div>
                @php $catPct = $cat['total'] > 0 ? round(($cat['total'] - $cat['pendentes']) / $cat['total'] * 100) : 0; @endphp
                <div class="h-1 rounded-full bg-slate-100 dark:bg-gray-800 overflow-hidden mb-2">
                    <div class="h-full rounded-full bg-kvteal transition-all duration-700" style="width: {{ $catPct }}%"></div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ $cat['pendentes'] }} pendente(s)</p>
                    <p class="text-xs font-semibold text-kvteal">{{ $catPct }}%</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- AÇÕES RÁPIDAS --}}
<div class="flex justify-end mb-4 animate-in animate-in-d6">
    <a href="{{ route('reports.index') }}"
        class="bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500 dark:text-red-400 hover:text-red-600 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all border border-red-200 dark:border-red-800/50 hover:border-red-300 inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
        Relatório PDF
    </a>
</div>

{{-- PROJETOS ATIVOS --}}
@if(isset($projetos) && $projetos->count() > 0)
<div class="mb-8 animate-in animate-in-d6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-kvnavy dark:text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
            Projetos ativos
        </h3>
        <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-purple-500 hover:text-purple-600 transition-colors">Ver todos →</a>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
        @foreach($projetos as $projeto)
            <a href="{{ route('projects.show', $projeto) }}" class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 card-hover shadow-sm group">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-sm text-kvnavy dark:text-white group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors truncate">{{ $projeto->title }}</h4>
                    <span class="shrink-0 ml-2 text-[10px] font-medium px-2 py-0.5 rounded-full border
                        @if($projeto->status === 'planejamento') bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 border-sky-200 dark:border-sky-800/50
                        @elseif($projeto->status === 'em_andamento') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/50
                        @elseif($projeto->status === 'concluido') bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50
                        @else bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 border-red-200 dark:border-red-800/50 @endif">
                        {{ $projeto->status_label }}
                    </span>
                </div>
                <div class="flex items-center gap-3 text-[11px] text-slate-400 dark:text-slate-500">
                    <span>{{ $projeto->phases_count }} fase(s)</span>
                    <span>{{ $projeto->tasks_count }} tarefa(s)</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

{{-- PRÓXIMAS E ATRASADAS --}}
<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover shadow-sm">
        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-kvteal/20 to-kvteal/5 dark:from-kvteal/10 dark:to-transparent flex items-center justify-center">
                <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            </div>
            <h3 class="font-bold text-kvnavy dark:text-white">Próximas tarefas</h3>
        </div>
        <div class="space-y-3">
            @forelse($proximasTarefas as $task)
                    <div class="flex items-center justify-between border-b border-slate-100/80 dark:border-gray-800 pb-3 last:border-0 last:pb-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $task->title }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 flex items-center gap-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full {{ \App\Models\Category::COLORS[$task->cat?->color]['dot'] ?? 'bg-slate-400' }}"></span>
                            {{ $task->category_label }} · {{ $task->due_date->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <a href="{{ route('tasks.show', $task) }}" class="shrink-0 text-xs font-semibold text-kvteal hover:text-kvteal-dark transition-colors ml-4">Abrir →</a>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    </div>
                    <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Nenhuma tarefa pendente.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover shadow-sm">
        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-red-100 to-red-50 dark:from-red-900/30 dark:to-transparent flex items-center justify-center">
                <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 2.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <h3 class="font-bold text-kvnavy dark:text-white">Tarefas atrasadas</h3>
        </div>
        <div class="space-y-3">
            @forelse($atrasadas as $task)
                <div class="flex items-center justify-between border-b border-slate-100/80 pb-3 last:border-0 last:pb-0">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $task->title }}</p>
                        <p class="text-xs text-red-400 mt-0.5 flex items-center gap-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full {{ \App\Models\Category::COLORS[$task->cat?->color]['dot'] ?? 'bg-slate-400' }}"></span>
                            {{ $task->category_label }} · venceu em {{ $task->due_date->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <a href="{{ route('tasks.show', $task) }}" class="shrink-0 text-xs font-semibold text-red-400 hover:text-red-600 transition-colors ml-4">Abrir →</a>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm text-slate-400 dark:text-slate-500 font-medium">Nenhuma tarefa atrasada!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
