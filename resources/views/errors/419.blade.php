@extends('layouts.guest')
@section('title', 'Sessão expirada')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <h2 class="text-2xl font-extrabold text-kvnavy dark:text-white mb-2">419 · Sessão expirada</h2>
    <p class="text-sm text-slate-400 mb-6">Sua sessão expirou. Faça login novamente para continuar.</p>
    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-kvteal to-kvteal-dark text-white font-bold px-5 py-2.5 rounded-xl transition-all shadow-sm hover:shadow-md text-sm">Fazer login</a>
</div>
@endsection
