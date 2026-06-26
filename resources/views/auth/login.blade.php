@extends('layouts.guest')
@section('title', 'Entrar')

@section('content')
<div x-data="{ loading: false, showPassword: false }">
    <h2 class="text-xl font-extrabold text-kvnavy dark:text-white mb-1 tracking-tight">Bem-vindo de volta</h2>
    <p class="text-sm text-slate-400 mb-7 font-medium">Acesse sua conta para continuar organizado.</p>

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

    <form method="POST" action="{{ route('login') }}" class="space-y-5" @submit="loading = true">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">E-mail</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       placeholder="seu@email.com"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 @error('email') border-red-300 dark:border-red-700 @enderror">
            </div>
            @error('email')
                <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Senha</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <input :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                       placeholder="• • • • • • • •"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-12 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 @error('password') border-red-300 dark:border-red-700 @enderror">
                <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
            @error('password')
                <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2.5 text-sm text-slate-500 dark:text-slate-400 cursor-pointer group">
                <input type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-kvteal focus:ring-kvteal/20 transition-all">
                <span class="font-medium group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Lembrar de mim</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-xs font-medium text-kvteal hover:text-kvteal-dark hover:underline transition-colors">Esqueci a senha</a>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] disabled:from-kvteal/60 disabled:to-kvteal-dark/60 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 inline-flex items-center justify-center gap-2.5" x-text="loading ? 'Entrando...' : 'Entrar'">
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6 font-medium">
        Não tem conta?
        <a href="{{ route('register') }}" class="text-kvteal font-bold hover:underline transition-colors">Criar conta</a>
    </p>
</div>
@endsection