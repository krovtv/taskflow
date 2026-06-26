@extends('layouts.guest')
@section('title', 'Acesso negado')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
    </div>
    <h2 class="text-2xl font-extrabold text-kvnavy dark:text-white mb-2">403 · Acesso negado</h2>
    <p class="text-sm text-slate-400 mb-6">Você não tem permissão para acessar esta página.</p>
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-kvteal to-kvteal-dark text-white font-bold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md text-sm">Voltar ao início</a>
</div>
@endsection
