@extends('layouts.app')
@section('title', 'Telegram')
@section('heading', 'Notificações no Telegram')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- STATUS DO BOT --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-5 md:p-6 animate-in">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-900/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
            </div>
            <div>
                <h2 class="font-bold text-kvnavy dark:text-white">Telegram</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500">Receba notificações de tarefas diretamente no Telegram</p>
            </div>
        </div>

        @if($botInfo)
            <div class="bg-emerald-50/60 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/40 rounded-xl px-4 py-3 flex items-center gap-3 mb-5">
                <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50 shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Bot ativo</p>
                    <p class="text-xs text-emerald-500 dark:text-emerald-400">{{ $botInfo['username'] ?? '—' }}</p>
                </div>
            </div>
        @else
            <div class="bg-amber-50/60 dark:bg-amber-900/20 border border-amber-200/50 dark:border-amber-800/40 rounded-xl px-4 py-3 flex items-center gap-3 mb-5">
                <div class="w-3 h-3 rounded-full bg-amber-500 shadow-sm shadow-amber-500/50 shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-amber-700 dark:text-amber-300">Token não configurado</p>
                    <p class="text-xs text-amber-500 dark:text-amber-400">Adicione <code class="font-mono text-xs bg-amber-100 dark:bg-amber-900/40 px-1 rounded">TELEGRAM_BOT_TOKEN</code> no .env</p>
                </div>
            </div>
        @endif
    </div>

    {{-- CONEXÃO --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-5 md:p-6 animate-in animate-in-d1">
        @if($user->telegram_chat_id)
            {{-- CONECTADO --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-kvnavy dark:text-white">Conectado</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Chat ID: {{ $user->telegram_chat_id }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('settings.telegram.test') }}"
                   class="text-sm font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-4 py-2.5 rounded-xl transition-all">
                    Enviar teste
                </a>
                <a href="{{ route('settings.telegram.disconnect') }}"
                   class="text-sm font-semibold text-red-400 hover:text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-4 py-2.5 rounded-xl transition-all">
                    Desconectar
                </a>
            </div>
        @else
            {{-- DESCONECTADO --}}
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-kvnavy dark:text-white">Não conectado</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Conecte-se para receber notificações</p>
                </div>
            </div>

            @if($botInfo)
                @php $botUsername = $botInfo['username'] ?? null; @endphp
                @if($botUsername)
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                        Ao clicar no botão, o Telegram será aberto. <br class="sm:hidden">
                        Toque em <strong class="text-slate-700 dark:text-slate-200">Iniciar</strong> e o <code class="font-mono text-xs bg-sky-100 dark:bg-sky-900/40 px-1.5 rounded">/start</code> será enviado automaticamente.
                    </p>

                    <a href="https://t.me/{{ $botUsername }}?start={{ auth()->user()->telegram_token }}"
                       target="_blank"
                       class="inline-flex items-center gap-2.5 bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-sm shadow-sky-500/30 hover:shadow-md hover:shadow-sky-500/40">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                        Conectar com Telegram
                    </a>
                @else
                    <p class="text-sm text-slate-400">Username do bot não disponível.</p>
                @endif
            @else
                <p class="text-sm text-amber-500">Configure o token do bot no .env primeiro.</p>
            @endif
        @endif
    </div>
</div>
@endsection