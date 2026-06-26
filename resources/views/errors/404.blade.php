@extends('layouts.guest')
@section('title', 'Página não encontrada')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
    </div>
    <h2 class="text-2xl font-extrabold text-kvnavy dark:text-white mb-2">404 · Página não encontrada</h2>
    <p class="text-sm text-slate-400 mb-6">A página que você procura não existe ou foi removida.</p>
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-kvteal to-kvteal-dark text-white font-bold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md text-sm">Voltar ao início</a>
</div>
@endsection
