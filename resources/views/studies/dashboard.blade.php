@extends('layouts.app')
@section('title', 'Estudos')
@section('heading', 'Dashboard de estudos')

@section('content')
{{-- CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $icons = [
            'clock' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'calendar' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>',
            'chart' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>',
            'card' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>',
        ];
    @endphp
    @foreach($cardStats as $card)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 card-hover shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $card['bg'] }} text-white flex items-center justify-center shadow-sm">
                    {!! $icons[$card['icon']] !!}
                </span>
            </div>
            <p class="text-2xl font-bold text-kvnavy dark:text-white tracking-tight">{{ $card['value'] }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">{{ $card['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid md:grid-cols-2 gap-6 mb-8">
    {{-- GRÁFICO 30 DIAS --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover shadow-sm">
        <h3 class="font-bold text-kvnavy dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            Últimos 30 dias
        </h3>
        <div class="flex items-end gap-1.5 h-24">
            @php $maxMin = max(max(array_column($last30Days->toArray(), 'minutes')), 1); @endphp
            @foreach($last30Days as $day)
                @php $h = round($day['minutes'] / $maxMin * 100); @endphp
                <div class="flex-1 flex flex-col items-center gap-0.5 group relative">
                    <div class="absolute bottom-full mb-1 hidden group-hover:block bg-kvnavy dark:bg-gray-700 text-white text-[10px] font-medium px-2 py-0.5 rounded whitespace-nowrap z-10">
                        {{ $day['full'] }}: {{ $day['minutes'] }}min
                    </div>
                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500">{{ $day['minutes'] > 0 ? $day['minutes'] : '' }}</span>
                    <div class="w-full rounded-sm bg-gradient-to-t from-kvteal to-kvteal-light transition-all duration-500" style="height: {{ max($h, 1) }}%"></div>
                    <span class="text-[8px] font-medium text-slate-400 dark:text-slate-500">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- POR ESPECIALIZAÇÃO --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover shadow-sm">
        <h3 class="font-bold text-kvnavy dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
            Especializações
        </h3>
        @forelse($perSpecialization as $spec)
            <div class="flex items-center gap-3 mb-3 last:mb-0">
                <span class="w-2.5 h-2.5 rounded-full {{ \App\Models\Category::COLORS[$spec['color']]['dot'] ?? 'bg-slate-400' }}"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $spec['name'] }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $spec['hours'] }}h · {{ $spec['sessions'] }} sessões · {{ $spec['flashcards'] }} cards</p>
                </div>
                @php
                    $maxHours = max(max(array_column($perSpecialization, 'hours')), 1);
                    $barW = round($spec['hours'] / $maxHours * 100);
                @endphp
                <div class="w-20 h-1.5 rounded-full bg-slate-100 dark:bg-gray-800 overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-kvteal transition-all" style="width: {{ $barW }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-6">Nenhuma especialização ainda.</p>
        @endforelse
    </div>
</div>

{{-- AÇÕES RÁPIDAS --}}
<div class="flex flex-wrap gap-3 mb-8">
    <a href="{{ route('studies.timer.index') }}"
       class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Iniciar timer
    </a>
    @if($dueFlashcards > 0)
        <a href="{{ route('studies.flashcards.review') }}"
           class="bg-gradient-to-r from-emerald-500 to-emerald-400 hover:from-emerald-600 hover:to-emerald-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Revisar flashcards ({{ $dueFlashcards }})
        </a>
    @endif
    <a href="{{ route('studies.specializations.index') }}"
       class="bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/40 text-purple-500 hover:text-purple-600 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all border border-purple-200 dark:border-purple-800/50 inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
        Gerenciar especializações
    </a>
</div>

{{-- SESSÕES RECENTES --}}
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm animate-in animate-in-d4">
    <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
        <h3 class="font-bold text-kvnavy dark:text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Sessões recentes
        </h3>
        <a href="{{ route('studies.timer.index') }}" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark transition-colors">Ver todas →</a>
    </div>
    <div class="divide-y divide-slate-100 dark:divide-gray-800">
        @forelse($recentSessions as $session)
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-2 h-2 rounded-full {{ $session->specialization?->dot ?? 'bg-slate-400' }}"></span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $session->specialization?->name ?? '—' }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">{{ $session->started_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-kvteal">{{ $session->duration_formatted }}</span>
            </div>
        @empty
            <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-8">Nenhuma sessão de estudo ainda.</p>
        @endforelse
    </div>
</div>
@endsection
