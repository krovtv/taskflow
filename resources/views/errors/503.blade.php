@extends('layouts.guest')
@section('title', 'Em manutenção')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 rounded-2xl bg-sky-50 dark:bg-sky-900/30 flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.58 5.58a2.25 2.25 0 01-3.18-3.18l5.58-5.58M14.83 11.42l5.58-5.58a2.25 2.25 0 00-3.18-3.18l-5.58 5.58M15.17 11.42L9.75 5.25M9.75 5.25l4.5 4.5M9.75 5.25L6.75 2.25M18.75 14.25l-3 3"/></svg>
    </div>
    <h2 class="text-2xl font-extrabold text-kvnavy dark:text-white mb-2">Em manutenção</h2>
    <p class="text-sm text-slate-400 mb-6">Estamos realizando melhorias. Volte em alguns instantes.</p>
</div>
@endsection
