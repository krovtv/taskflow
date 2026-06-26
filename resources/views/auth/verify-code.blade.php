@extends('layouts.guest')
@section('title', 'Verificar conta')

@section('content')
<div x-data="{ code: ['', '', '', ''], loading: false }"
     x-init="$nextTick(() => $refs.i0?.focus())">
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

        <fieldset class="flex justify-center gap-3" x-data="{
            next(i) { if (i < 3) this.$refs['i' + (i + 1)].focus() },
            prev(i) { if (i > 0) this.$refs['i' + (i - 1)].focus() },
            handlePaste(e) {
                const data = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 4);
                data.split('').forEach((d, i) => { if (this.$refs['i' + i]) { this.$refs['i' + i].value = d; this.code[i] = d } });
                if (data.length === 4) document.querySelector('button[type=submit]')?.focus();
            }
        }">
            <template x-for="(_, i) in 4" :key="i">
                <input type="text" inputmode="numeric" maxlength="1" required
                       x-ref="'i' + i"
                       x-model="code[i]"
                       @input="if($event.target.value) next(i)"
                       @keydown.backspace="if(!$event.target.value) prev(i)"
                       @paste="handlePaste"
                       name="code[]"
                       autocomplete="one-time-code"
                       class="w-14 h-14 text-center text-xl font-extrabold border border-slate-200 dark:border-gray-700 rounded-xl bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500">
            </template>
        </fieldset>

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
