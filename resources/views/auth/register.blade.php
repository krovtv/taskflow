@extends('layouts.guest')
@section('title', 'Criar conta')

@section('content')
    <h2 class="text-xl font-extrabold text-kvnavy mb-1 tracking-tight">Crie sua conta</h2>
    <p class="text-sm text-slate-400 mb-7 font-medium">Comece a organizar estudos, projetos e trabalho.</p>

    @if($errors->any())
        <div class="mb-5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5" autocomplete="off">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Nome</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       placeholder="Seu nome"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 @error('name') border-red-300 dark:border-red-700 @enderror">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">E-mail</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
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
                <input type="password" name="password" required autocomplete="new-password"
                       placeholder="• • • • • • • •"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 @error('password') border-red-300 dark:border-red-700 @enderror">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Confirmar senha</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="• • • • • • • •"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl pl-10 pr-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500">
            </div>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30">
            Criar conta
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6 font-medium">
        Já tem conta?
        <a href="{{ route('login') }}" class="text-kvteal font-bold hover:underline">Entrar</a>
    </p>
@endsection