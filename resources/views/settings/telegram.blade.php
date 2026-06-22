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
                <h2 class="font-bold text-kvnavy dark:text-white">Telegram Bot</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500">Conecte-se para receber lembretes de prazos</p>
            </div>
        </div>

        @if($botInfo)
            <div class="bg-emerald-50/60 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/40 rounded-xl px-4 py-3 flex items-center gap-3 mb-5">
                <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50 shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Bot ativo</p>
                    <p class="text-xs text-emerald-500 dark:text-emerald-400">@{{ $botInfo['username'] ?? '—' }}</p>
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

    {{-- CONFIGURAÇÃO --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-5 md:p-6 animate-in animate-in-d1">
        <h3 class="font-bold text-kvnavy dark:text-white mb-4">Seu Chat ID</h3>

        <div class="bg-sky-50/60 dark:bg-sky-900/20 border border-sky-200/50 dark:border-sky-800/40 rounded-xl p-4 mb-5 text-sm text-slate-600 dark:text-slate-300 space-y-2">
            <p class="font-semibold text-sky-700 dark:text-sky-300">Como encontrar seu Chat ID:</p>
            <ol class="list-decimal pl-4 space-y-1 text-xs text-slate-500 dark:text-slate-400">
                <li>Abra o Telegram e procure pelo seu bot (o username que aparece acima)</li>
                <li>Envie uma mensagem qualquer para o bot (ex: "/start")</li>
                <li>Acesse: <a href="https://api.telegram.org/bot{SEU_TOKEN}/getUpdates" target="_blank" class="text-kvteal hover:underline break-all">https://api.telegram.org/bot{SEU_TOKEN}/getUpdates</a></li>
                <li>No resultado, procure por <code class="font-mono bg-sky-100 dark:bg-sky-900/40 px-1 rounded">"chat":{"id":123456789}</code> — esse número é seu Chat ID</li>
                <li>Cole abaixo e clique em <strong>Salvar</strong></li>
            </ol>
        </div>

        <form method="POST" action="{{ route('settings.telegram.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Chat ID</label>
                <input type="text" name="telegram_chat_id"
                       value="{{ old('telegram_chat_id', $user->telegram_chat_id) }}"
                       placeholder="Ex: 123456789"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none dark:text-white">
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm">
                    Salvar
                </button>

                @if($user->telegram_chat_id)
                    <a href="{{ route('settings.telegram.test') }}"
                       class="text-sm font-semibold text-kvteal hover:text-kvteal-dark bg-kvteal/5 hover:bg-kvteal/10 px-4 py-2.5 rounded-xl transition-all">
                        Enviar teste
                    </a>

                    <a href="{{ route('settings.telegram.disconnect') }}"
                       class="text-sm font-semibold text-red-400 hover:text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-4 py-2.5 rounded-xl transition-all ml-auto">
                        Desconectar
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- COMO CRIAR UM BOT --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-5 md:p-6 animate-in animate-in-d2">
        <h3 class="font-bold text-kvnavy dark:text-white mb-4">Primeira vez? Criando seu bot</h3>
        <ol class="list-decimal pl-4 space-y-2 text-sm text-slate-500 dark:text-slate-400">
            <li>Abra o Telegram e procure por <strong>@BotFather</strong></li>
            <li>Envie <code class="font-mono bg-slate-100 dark:bg-gray-800 px-1 rounded">/newbot</code> e siga as instruções</li>
            <li>Você receberá um <strong>token</strong> (ex: <code class="font-mono bg-slate-100 dark:bg-gray-800 px-1 rounded">123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11</code>)</li>
            <li>Adicione essa linha no seu arquivo <code class="font-mono bg-slate-100 dark:bg-gray-800 px-1 rounded">.env</code>:</li>
        </ol>
        <div class="mt-3 bg-slate-100 dark:bg-gray-800 rounded-xl px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-300">
            TELEGRAM_BOT_TOKEN=123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
        </div>
    </div>
</div>
@endsection
