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
                <a href="{{ route('daily-log.index') }}"
                   class="text-sm font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Voltar
                </a>
                <button class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
