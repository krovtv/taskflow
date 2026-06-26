@extends('layouts.guest')
@section('title', 'Erro interno')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
    </div>
    <h2 class="text-2xl font-extrabold text-kvnavy dark:text-white mb-2">500 · Erro interno</h2>
    <p class="text-sm text-slate-400 mb-6">Algo deu errado. Tente novamente mais tarde.</p>
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-kvteal to-kvteal-dark text-white font-bold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md text-sm">Voltar ao início</a>
</div>
@endsection
