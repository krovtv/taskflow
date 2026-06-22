@extends('layouts.app')
@section('title', 'Nova tarefa')
@section('heading', 'Nova tarefa')

@section('content')
<div class="max-w-3xl mx-auto animate-in">
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
            @csrf
            @include('tasks._form')

            <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-gray-800">
                <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Cancelar
                </a>
                <button class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 text-sm inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Salvar tarefa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection