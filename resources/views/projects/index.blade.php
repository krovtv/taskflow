@extends('layouts.app')
@section('title', 'Projetos')
@section('heading', 'Projetos')

@section('content')
<div class="animate-in">
    {{-- FILTROS --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-2.5">
            <div class="relative">
                <select name="status" onchange="if(this.value) window.location.href='?status='+this.value; else window.location.href='{{ route('projects.index') }}'"
                        class="border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none pr-10 shadow-sm">
                    <option value="">Todos os status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected($currentStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
        </div>
        <a href="{{ route('projects.create') }}"
           class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-purple-300/20 hover:shadow-md hover:shadow-purple-400/30 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Novo projeto
        </a>
    </div>

    {{-- LISTA --}}
    <div class="grid md:grid-cols-2 gap-4">
        @forelse($projects as $project)
            <a href="{{ route('projects.show', $project) }}" class="block bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover shadow-sm group">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-kvnavy dark:text-white group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors truncate">{{ $project->title }}</h3>
                        @if($project->description)
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 line-clamp-2">{{ $project->description }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 ml-3 text-[10px] font-bold px-2 py-1 rounded-lg border {{ $project->status_class }}">
                        {{ $project->status_label }}
                    </span>
                </div>

                <div class="flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500 mb-3">
                    @if($project->start_date)
                        <span>{{ $project->start_date->format('d/m/Y') }}</span>
                        @if($project->end_date)
                            <span>→</span>
                            <span>{{ $project->end_date->format('d/m/Y') }}</span>
                        @endif
                    @endif
                    <span class="ml-auto">{{ $project->phases->count() }} fase(s) · {{ $project->tasks->count() }} tarefa(s)</span>
                </div>

                @php $p = $project->progress; @endphp
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-1.5 rounded-full bg-slate-200 dark:bg-gray-700 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-kvteal transition-all duration-500" style="width: {{ $p }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-purple-500 min-w-[32px] text-right">{{ $p }}%</span>
                </div>
            </a>
        @empty
            <div class="md:col-span-2 text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                </div>
                <p class="text-base font-semibold text-slate-400 dark:text-slate-500">Nenhum projeto ainda</p>
                <p class="text-sm text-slate-300 dark:text-slate-600 mt-1">Organize seu trabalho em projetos com início, meio e fim.</p>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 mt-4 bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Criar primeiro projeto
                </a>
            </div>
        @endforelse
    </div>

    {{-- PAGINAÇÃO --}}
    @if($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
