@extends('layouts.app')
@section('title', 'Editar tarefa')
@section('heading', 'Editar tarefa')

@section('content')
<div class="max-w-3xl mx-auto animate-in">
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('tasks.update', $task) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('tasks._form')

            <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full
                        @if($task->status === 'concluido') bg-emerald-400
                        @elseif($task->isOverdue()) bg-red-400
                        @elseif($task->status === 'em_andamento') bg-amber-400
                        @else bg-slate-400 @endif">
                    </span>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Status atual: <strong class="text-slate-600 dark:text-slate-300">{{ $task->status_label }}</strong></span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Ver detalhes
                    </a>
                    <button class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 text-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                        Atualizar tarefa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection