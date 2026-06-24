@extends('layouts.app')
@section('title', 'Relatórios')
@section('heading', 'Relatórios')

@section('content')
{{-- FILTROS --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 md:p-5 mb-6 shadow-sm animate-in">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Categoria</label>
            <select name="category" class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 dark:text-slate-200 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                <option value="">Todas</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" @selected($currentCategory === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Status</label>
            <select name="status" class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 dark:text-slate-200 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                <option value="">Todos</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected($currentStatus === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">De</label>
            <input type="date" name="from" value="{{ $dateFrom }}"
                   class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 dark:text-slate-200 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Até</label>
            <input type="date" name="to" value="{{ $dateTo }}"
                   class="border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-slate-50 dark:bg-gray-800 dark:text-slate-200 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
        </div>
        <button class="bg-kvnavy hover:bg-kvnavy-light text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm">Filtrar</button>
        <a href="{{ route('reports.index') }}" class="text-sm text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 px-3 py-2.5 font-medium transition-colors">Limpar</a>
    </form>
</div>

{{-- ESTATÍSTICAS --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6 animate-in animate-in-d1">
    @php
        $statCards = [
            ['label' => 'Total', 'value' => $stats['total'], 'bg' => 'from-kvnavy to-kvnavy/80'],
            ['label' => 'Pendentes', 'value' => $stats['pendentes'], 'bg' => 'from-slate-500 to-slate-400'],
            ['label' => 'Em andamento', 'value' => $stats['andamento'], 'bg' => 'from-amber-500 to-amber-400'],
            ['label' => 'Concluídas', 'value' => $stats['concluidas'], 'bg' => 'from-emerald-500 to-emerald-400'],
            ['label' => 'Atrasadas', 'value' => $stats['atrasadas'], 'bg' => 'from-red-500 to-red-400'],
        ];
    @endphp
    @foreach($statCards as $card)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 shadow-sm">
            <p class="text-2xl font-bold text-kvnavy dark:text-white tracking-tight">{{ $card['value'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

{{-- CATEGORIAS --}}
@if(count($porCategoria) > 0)
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 mb-6 shadow-sm animate-in animate-in-d2">
    <h4 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Distribuição por categoria</h4>
    <div class="flex flex-wrap gap-3">
        @foreach($porCategoria as $cat)
            <div class="flex items-center gap-2 text-sm bg-slate-50 dark:bg-gray-800/50 px-3 py-1.5 rounded-lg border border-slate-100 dark:border-gray-700/50">
                <span class="w-2.5 h-2.5 rounded-full {{ $cat['dot'] }}"></span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $cat['label'] }}</span>
                <span class="text-slate-400 dark:text-slate-500 font-medium">{{ $cat['total'] }}</span>
                @if($cat['total'] > 0)
                    <span class="text-[11px] text-emerald-500 font-semibold">({{ round($cat['concluidas'] / $cat['total'] * 100) }}%)</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- BOTÃO PDF --}}
<div class="flex justify-end mb-4 animate-in animate-in-d3">
    <a href="{{ route('reports.pdf', request()->query()) }}"
       class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
        Exportar PDF
    </a>
</div>

{{-- TABELA --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 overflow-hidden shadow-sm animate-in animate-in-d3">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-slate-50 dark:from-gray-800 to-white dark:to-gray-900 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="text-left px-5 py-4">Tarefa</th>
                    <th class="text-left px-5 py-4 hidden sm:table-cell">Categoria</th>
                    <th class="text-left px-5 py-4 hidden sm:table-cell">Prioridade</th>
                    <th class="text-left px-5 py-4 hidden sm:table-cell">Prazo</th>
                    <th class="text-left px-5 py-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr class="border-t border-slate-100 dark:border-gray-800 hover:bg-slate-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-start gap-2.5">
                                <span class="w-2 h-2 rounded-full mt-2 shrink-0
                                    @if($task->priority === 'urgente') bg-red-500
                                    @elseif($task->priority === 'alta') bg-amber-500
                                    @elseif($task->priority === 'media') bg-blue-500
                                    @else bg-slate-300 dark:bg-gray-600 @endif">
                                </span>
                                <div>
                                    <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $task->title }}</p>
                                    @if($task->description)
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 max-w-xs truncate">{{ $task->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                             <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                 {{ \App\Models\Category::COLORS[$task->cat?->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400' }}">
                                 {{ $task->category_label }}
                             </span>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg border
                                @if($task->priority === 'urgente') bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800
                                @elseif($task->priority === 'alta') bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800
                                @elseif($task->priority === 'media') bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800
                                @else bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-gray-700 @endif">
                                {{ $task->priority_label }}
                            </span>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <span class="text-sm {{ $task->isOverdue() ? 'text-red-500 dark:text-red-400 font-semibold' : 'text-slate-500 dark:text-slate-400' }}">
                                {{ $task->due_date->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg {{ $task->status_class }}">
                                {{ $task->status_label }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-400 dark:text-slate-500 py-16 font-medium">Nenhuma tarefa encontrada para este filtro.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection