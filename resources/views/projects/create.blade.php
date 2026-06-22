@extends('layouts.app')
@section('title', 'Novo projeto')
@section('heading', 'Novo projeto')

@section('content')
<div class="max-w-2xl mx-auto animate-in">
    <form method="POST" action="{{ route('projects.store') }}" class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 md:p-8 shadow-sm">
        @csrf

        <div class="flex items-center gap-2.5 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-400 text-white flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-kvnavy dark:text-white text-lg">Informações do projeto</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Defina o ciclo completo do projeto</p>
            </div>
        </div>

        @include('projects._form')

        <div class="mt-8 flex items-center justify-between pt-6 border-t border-slate-100 dark:border-gray-800">
            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Cancelar
            </a>
            <button type="submit" class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white font-bold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-purple-300/30 hover:shadow-md hover:shadow-purple-400/30 text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Criar projeto
            </button>
        </div>
    </form>
</div>
@endsection
