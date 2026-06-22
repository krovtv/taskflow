@extends('layouts.app')
@section('title', 'Especializações')
@section('heading', 'Especializações')

@section('content')
<div class="max-w-3xl mx-auto">
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/40 text-emerald-700 dark:text-emerald-300 rounded-xl px-4 py-3 mb-5 text-sm font-medium animate-in">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm animate-in">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="font-bold text-kvnavy dark:text-white">Suas especializações</h2>
            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ $specializations->count() }} {{ Str::plural('especialização', $specializations->count()) }}</span>
        </div>

        <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 bg-slate-50/50 dark:bg-gray-800/30">
            <form method="POST" action="{{ route('studies.specializations.store') }}" class="flex items-center gap-3">
                @csrf
                <input type="text" name="name" placeholder="Nova especialização..." required maxlength="255"
                       class="flex-1 border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400">
                <select name="color" class="border border-slate-200 dark:border-gray-700 rounded-lg text-xs bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white py-2 px-2">
                    @foreach(\App\Models\Category::COLORS as $key => $meta)
                        <option value="{{ $key }}" {{ $loop->first ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all shadow-sm shrink-0">Criar</button>
            </form>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-gray-800">
            @forelse($specializations as $spec)
                @php $dot = \App\Models\Category::COLORS[$spec->color]['dot'] ?? 'bg-slate-400'; @endphp
                <div class="px-5 py-4 flex items-center gap-4 group">
                    <span class="w-2.5 h-2.5 rounded-full {{ $dot }} shrink-0"></span>
                    <form method="POST" action="{{ route('studies.specializations.update', $spec) }}" class="flex-1 flex items-center gap-3">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $spec->name }}" required maxlength="255"
                               class="flex-1 border border-transparent hover:border-slate-200 dark:hover:border-gray-700 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 rounded-lg px-3 py-1.5 text-sm bg-transparent outline-none transition-all dark:text-white">
                        <select name="color" class="border border-slate-200 dark:border-gray-700 rounded-lg text-xs bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white py-1.5 px-2">
                            @foreach(\App\Models\Category::COLORS as $key => $meta)
                                <option value="{{ $key }}" {{ $spec->color === $key ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 shrink-0">Salvar</button>
                    </form>
                    <a href="{{ route('studies.specializations.show', $spec) }}" class="text-xs font-semibold text-purple-500 hover:text-purple-600 dark:text-purple-400 dark:hover:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 px-3 py-1.5 rounded-lg transition-all shrink-0">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                            Anotações
                        </span>
                    </a>
                    <form method="POST" action="{{ route('studies.specializations.destroy', $spec) }}" class="shrink-0">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Remover especialização &quot;{{ $spec->name }}&quot;? Sessões e flashcards serão removidos.')"
                                class="text-xs font-semibold text-red-400 hover:text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                            Excluir
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <svg class="w-8 h-8 text-slate-200 dark:text-gray-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Nenhuma especialização criada ainda.</p>
                    <p class="text-xs text-slate-300 dark:text-slate-600 mt-1">Crie especializações como "React", "Inglês", "Data Science"...</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="{{ route('studies.dashboard') }}" class="text-sm font-semibold text-slate-400 hover:text-kvteal transition-colors">&larr; Dashboard de estudos</a>
    </div>
</div>
@endsection
