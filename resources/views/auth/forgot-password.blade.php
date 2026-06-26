@extends('layouts.guest')
@section('title', 'Recuperar senha')

@section('content')
<div x-data="{ loading: false }">
    <h2 class="text-xl font-extrabold text-kvnavy dark:text-white mb-1 tracking-tight">Recuperar senha</h2>
    <p class="text-sm text-slate-400 mb-7 font-medium">Digite seu e-mail para receber um código de recuperação.</p>

    @if(session('status'))
        <div class="mb-5 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2.5">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl px-4 py-3 text-sm font-medium flex items-start gap-2.5">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" @submit="loading = true">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">E-mail</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       placeholder="seu@email.com"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500">
            </div>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] disabled:from-kvteal/60 disabled:to-kvteal-dark/60 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 inline-flex items-center justify-center gap-2.5"
                x-text="loading ? 'Enviando...' : 'Enviar código'">
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6 font-medium">
        Lembrou sua senha?
        <a href="{{ route('login') }}" class="text-kvteal font-bold hover:underline transition-colors">Entrar</a>
    </p>
</div>
@endsection
