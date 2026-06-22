@extends('layouts.app')
@section('title', 'Categorias')
@section('heading', 'Gerenciar categorias')

@section('content')
<div class="max-w-2xl mx-auto">
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/40 text-emerald-700 dark:text-emerald-300 rounded-xl px-4 py-3 mb-5 text-sm font-medium animate-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200/50 dark:border-red-800/40 text-red-600 dark:text-red-400 rounded-xl px-4 py-3 mb-5 text-sm font-medium animate-in">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm animate-in">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="font-bold text-kvnavy dark:text-white">Suas categorias</h2>
            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ $categories->count() }} {{ Str::plural('categoria', $categories->count()) }}</span>
        </div>

        <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 bg-slate-50/50 dark:bg-gray-800/30">
            <form method="POST" action="{{ route('categories.store') }}" class="flex items-center gap-3">
                @csrf
                <input type="text" name="name" placeholder="Nova categoria..." required maxlength="255"
                       class="flex-1 border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400">
                <select name="color" class="border border-slate-200 dark:border-gray-700 rounded-lg text-xs bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white py-2 px-2">
                    @foreach(\App\Models\Category::COLORS as $key => $meta)
                        <option value="{{ $key }}" {{ $loop->first ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all shadow-sm shrink-0">
                    Criar
                </button>
            </form>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-gray-800">
            @forelse($categories as $cat)
                @php
                    $dot = \App\Models\Category::COLORS[$cat->color]['dot'] ?? 'bg-slate-400';
                    $badge = \App\Models\Category::COLORS[$cat->color]['badge'] ?? 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400';
                @endphp
                <div class="px-5 py-4 flex items-center gap-4 group">
                    <span class="w-2.5 h-2.5 rounded-full {{ $dot }} shrink-0"></span>

                    <form method="POST" action="{{ route('categories.update', $cat) }}" class="flex-1 flex items-center gap-3">
                        @csrf
                        @method('PUT')

                        <input type="text" name="name" value="{{ $cat->name }}" required maxlength="255"
                               class="flex-1 border border-transparent hover:border-slate-200 dark:hover:border-gray-700 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 rounded-lg px-3 py-1.5 text-sm bg-transparent outline-none transition-all dark:text-white">

                        <select name="color" class="border border-slate-200 dark:border-gray-700 rounded-lg text-xs bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white py-1.5 px-2">
                            @foreach(\App\Models\Category::COLORS as $key => $meta)
                                <option value="{{ $key }}" {{ $cat->color === $key ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="text-xs font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-3 py-1.5 rounded-lg transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 shrink-0">
                            Salvar
                        </button>
                    </form>

                    <x-confirmation-modal
                        :action="route('categories.destroy', $cat)"
                        title="Excluir categoria"
                        message='As tarefas vinculadas a "{{ $cat->name }}" perderão a categoria.'
                        buttonText="Excluir"
                        buttonClass="text-xs font-semibold text-red-400 hover:text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                        Excluir
                    </x-confirmation-modal>

                    @if($cat->tasks_count > 0)
                        <span class="text-xs text-slate-400 dark:text-slate-500 shrink-0 w-16 text-right">{{ $cat->tasks_count }} tarefa{{ $cat->tasks_count !== 1 ? 's' : '' }}</span>
                    @endif
                </div>
            @empty
                <div class="px-5 py-8 text-center">
                    <svg class="w-8 h-8 text-slate-200 dark:text-gray-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Nenhuma categoria criada ainda.</p>
                    <p class="text-xs text-slate-300 dark:text-slate-600 mt-1">Crie categorias ao cadastrar uma tarefa.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-400 hover:text-kvteal transition-colors">&larr; Voltar ao dashboard</a>
    </div>
</div>
@endsection