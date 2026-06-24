@extends('layouts.app')
@section('title', 'Notas Diárias')
@section('heading', 'Notas Diárias')

@section('content')
<div class="max-w-3xl mx-auto animate-in">
    {{-- EXPORTAR / FILTROS --}}
    <div x-data="{ filters: false, from: '{{ now()->startOfMonth()->format('Y-m-d') }}', to: '{{ now()->format('Y-m-d') }}' }" class="mb-4">
        <div class="flex items-center justify-end gap-2">
            <button @click="filters = !filters"
                    class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 px-3 py-1.5 rounded-lg transition-all inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                <span x-text="filters ? 'Ocultar filtros' : 'Filtrar período'"></span>
            </button>
        </div>
        <div x-show="filters" x-cloak x-transition:enter="transition-all duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-3 bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl p-4 shadow-sm">
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">De</label>
                    <input type="date" x-model="from"
                           class="border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Até</label>
                    <input type="date" x-model="to"
                           class="border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <a :href="'{{ route('daily-log.export-txt') }}?from=' + from + '&to=' + to"
                       class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 bg-slate-100 dark:bg-gray-800 border border-slate-200 dark:border-gray-700 px-3 py-2 rounded-lg transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/></svg>
                        TXT
                    </a>
                    <a :href="'{{ route('daily-log.export-pdf') }}?from=' + from + '&to=' + to"
                       class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 px-3 py-2 rounded-lg transition-all inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- HOJE / NAVEGAÇÃO --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('daily-log.date', $log->date->copy()->subDay()->format('Y-m-d')) }}"
                           class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-all"
                           title="Dia anterior">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </a>
                        <h3 class="font-bold text-kvnavy dark:text-white text-lg">{{ $log->date->format('d/m/Y') }}</h3>
                        <a href="{{ route('daily-log.date', $log->date->copy()->addDay()->format('Y-m-d')) }}"
                           class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-all"
                           title="Próximo dia">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ $log->date->translatedFormat('l') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="date" value="{{ $log->date->format('Y-m-d') }}"
                       onchange="if(this.value) window.location.href = '/diario/' + this.value"
                       class="border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-1.5 text-xs bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
                <a href="{{ route('daily-log.index') }}"
                   class="text-xs font-medium text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all {{ $log->date->isToday() ? 'hidden' : '' }}">
                    Hoje
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('daily-log.update') }}" x-data="{ saving: false }">
            @csrf
            <input type="hidden" name="date" value="{{ $log->date->format('Y-m-d') }}">
            <textarea name="content" rows="8"
                      placeholder="O que você fez hoje? Algum incidente? O que aprendeu? O que ficou pendente?"
                      class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 resize-y min-h-[160px]"
            >{{ old('content', $log->content) }}</textarea>

            <div class="flex items-center justify-between mt-4">
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    <span class="font-medium">{{ str_word_count($log->content) }}</span> palavras
                </p>
                <button type="submit" :disabled="saving"
                        class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md text-sm inline-flex items-center gap-2 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="saving ? 'Salvando...' : 'Salvar'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- DIAS ANTERIORES --}}
    @if($recentLogs->isNotEmpty())
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 shadow-sm">
        <h3 class="font-bold text-kvnavy dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Dias anteriores
        </h3>
        <div class="space-y-3">
            @foreach($recentLogs as $entry)
                <details class="group">
                    <summary class="flex items-center justify-between cursor-pointer text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-kvteal transition-colors py-2 px-3 rounded-lg hover:bg-slate-50 dark:hover:bg-gray-800/50">
                        <span>{{ $entry->date->format('d/m/Y') }} — {{ $entry->date->translatedFormat('l') }}</span>
                        <svg class="w-4 h-4 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </summary>
                    <div class="mt-2 px-3 pb-3">
                        <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-wrap">{{ $entry->content }}</p>
                        <a href="{{ route('daily-log.date', $entry->date->format('Y-m-d')) }}" class="text-xs text-kvteal hover:text-kvteal-dark font-medium mt-2 inline-block">Editar este dia</a>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
