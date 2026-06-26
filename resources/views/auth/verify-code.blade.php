@extends('layouts.guest')
@section('title', 'Verificar conta')

@section('content')
<div x-data="{ loading: false }" x-init="$nextTick(() => $refs.input.focus())">
    <h2 class="text-xl font-extrabold text-kvnavy dark:text-white mb-1 tracking-tight">Verifique sua conta</h2>
    <p class="text-sm text-slate-400 mb-7 font-medium">Enviamos um código de 4 dígitos para <strong class="text-slate-600 dark:text-slate-300">{{ $email }}</strong>.</p>

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

    <form method="POST" action="{{ route('verify.store') }}" class="space-y-6" @submit="loading = true">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="flex justify-center gap-3" x-data="{
            val: '',
            get chars() { return this.val.padEnd(4, '').split('').slice(0, 4) },
            handleInput(e) {
                const cleaned = e.target.value.replace(/\D/g, '').slice(0, 4);
                e.target.value = cleaned;
                this.val = cleaned;
                if (cleaned.length === 4) document.querySelector('button[type=submit]')?.focus();
            }
        }">
            <input type="text" x-ref="input" inputmode="numeric" maxlength="4" required autocomplete="one-time-code"
                   name="code"
                   @input="handleInput"
                   @keydown="if ($event.key === 'Backspace' || $event.key === 'Delete' || $event.key.match(/^\d$/) || $event.key === 'Tab' || $event.key === 'Escape') ; else $event.preventDefault()"
                   class="absolute opacity-0 pointer-events-none">

            <template x-for="(ch, i) in 4" :key="i">
                <span @click="$refs.input.focus()"
                      class="w-14 h-14 flex items-center justify-center text-xl font-extrabold border-2 rounded-xl transition-all duration-150 cursor-text"
                      :class="chars[i] ? 'border-kvteal bg-kvteal/5 text-kvnavy dark:text-white' : (i === chars.length ? 'border-kvteal ring-2 ring-kvteal/20 bg-white dark:bg-gray-800' : 'border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-800 text-slate-300 dark:text-slate-600')"
                      x-text="chars[i] || '_'">
                </span>
            </template>
        </div>

        <button type="submit" :disabled="loading"
                class="w-full bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] disabled:from-kvteal/60 disabled:to-kvteal-dark/60 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 inline-flex items-center justify-center gap-2.5"
                x-text="loading ? 'Verificando...' : 'Verificar'">
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6 font-medium">
        Não recebeu?
        <a href="{{ route('verify.resend') }}?email={{ urlencode($email) }}" class="text-kvteal font-bold hover:underline transition-colors">Reenviar código</a>
    </p>
</div>
@endsection
