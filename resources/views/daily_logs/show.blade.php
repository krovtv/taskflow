@extends('layouts.app')
@section('title', 'Notas Diárias — ' . $date->format('d/m/Y'))
@section('heading', 'Notas Diárias')

@section('content')
<div class="max-w-3xl mx-auto animate-in">
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <a href="{{ route('daily-log.index') }}" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-gray-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('daily-log.date', $date->copy()->subDay()->format('Y-m-d')) }}"
                       class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-all"
                       title="Dia anterior">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </a>
                    <h3 class="font-bold text-kvnavy dark:text-white text-lg">{{ $date->format('d/m/Y') }}</h3>
                    <a href="{{ route('daily-log.date', $date->copy()->addDay()->format('Y-m-d')) }}"
                       class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-all"
                       title="Próximo dia">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="date" value="{{ $date->format('Y-m-d') }}"
                       onchange="if(this.value) window.location.href = '/diario/' + this.value"
                       class="border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                <a href="{{ route('daily-log.index') }}"
                   class="text-xs font-medium text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all {{ $date->isToday() ? 'hidden' : '' }}">
                    Hoje
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('daily-log.update') }}">
            @csrf
            <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
            <textarea name="content" rows="10"
                      placeholder="O que aconteceu neste dia?"
                      class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 resize-y min-h-[200px]"
            >{{ old('content', $log->content) }}</textarea>

            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center gap-2">
                    <a href="{{ route('daily-log.export-txt', ['from' => $date->format('Y-m-d'), 'to' => $date->format('Y-m-d')]) }}"
                       class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 px-2.5 py-1.5 rounded-lg transition-all inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/></svg>
                        TXT
                    </a>
                    <a href="{{ route('daily-log.export-pdf', ['from' => $date->format('Y-m-d'), 'to' => $date->format('Y-m-d')]) }}"
                       class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 px-2.5 py-1.5 rounded-lg transition-all inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        PDF
                    </a>
                    <a href="{{ route('daily-log.index') }}"
                       class="text-sm font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                        Hoje
                    </a>
                </div>
                <button class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
