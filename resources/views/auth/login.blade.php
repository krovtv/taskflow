@extends('layouts.guest')
@section('title', 'Entrar')

@section('content')
    <h2 class="text-xl font-extrabold text-kvnavy mb-1 tracking-tight">Bem-vindo de volta</h2>
    <p class="text-sm text-slate-400 mb-7 font-medium">Acesse sua conta para continuar organizado.</p>

    @if($errors->any())
        <div class="mb-5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" autocomplete="off">
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
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Senha</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <input type="password" name="password" required autocomplete="current-password"
                       placeholder="• • • • • • • •"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500">
            </div>
        </div>
        <label class="flex items-center gap-2.5 text-sm text-slate-500 dark:text-slate-400 cursor-pointer group">
            <input type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-slate-300 dark:border-gray-600 text-kvteal focus:ring-kvteal/20 transition-all">
            <span class="font-medium group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Lembrar de mim</span>
        </label>

        <button type="submit"
                class="w-full bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30">
            Entrar
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6 font-medium">
        Não tem conta?
        <a href="{{ route('register') }}" class="text-kvteal font-bold hover:underline">Criar conta</a>
    </p>

@endsection