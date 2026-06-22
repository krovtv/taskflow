@extends('layouts.app')
@section('title', 'Timer de estudos')
@section('heading', 'Timer de estudos')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Active timer --}}
    <div x-data="pomodoro()" x-init="init()" class="mb-8">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-6 text-center animate-in">
            <template x-if="!activeSession">
                <div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-kvteal to-kvteal-dark text-white flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-kvnavy dark:text-white mb-1">Timer de estudos</h3>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mb-6">Escolha a especialização e comece a estudar</p>
                    <form @submit.prevent="startSession" class="max-w-md mx-auto space-y-3">
                        <select x-model="form.specialization_id" required
                                class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white">
                            <option value="">Selecione uma especialização...</option>
                            @foreach($specializations as $spec)
                                <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" x-model="form.notes" placeholder="Anotações (opcional)..."
                               class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400">
                        <button type="submit" class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-8 py-2.5 rounded-xl transition-all shadow-sm">
                            Iniciar
                        </button>
                    </form>
                </div>
            </template>

            <template x-if="activeSession">
                <div x-transition:enter="transition-all duration-300">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <span class="w-3 h-3 rounded-full" :class="activeSession.dot"></span>
                        <span class="font-bold text-kvnavy dark:text-white" x-text="activeSession.specialization?.name"></span>
                    </div>
                    <div class="text-5xl font-black text-kvnavy dark:text-white tracking-tight mb-1" x-text="elapsed"></div>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mb-6">Tempo decorrido</p>
                    <div class="flex justify-center gap-3">
                        <button @click="stopSession" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">Parar</button>
                    </div>
                    <p class="mt-4 text-xs text-slate-400 dark:text-slate-500" x-text="activeSession.notes ? '📝 ' + activeSession.notes : ''"></p>
                </div>
            </template>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 text-center card-hover shadow-sm">
            <p class="text-2xl font-bold text-kvteal">{{ $todayMinutes }}min</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">Hoje</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 text-center card-hover shadow-sm">
            <p class="text-2xl font-bold text-amber-500">{{ $weekMinutes }}min</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">Semana</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-4 text-center card-hover shadow-sm">
            <p class="text-2xl font-bold text-purple-500">{{ $totalHours }}h</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mt-0.5">Total</p>
        </div>
    </div>

    {{-- Last 30 days chart --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 p-5 card-hover shadow-sm mb-8">
        <h3 class="font-bold text-kvnavy dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 text-kvteal" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
            Últimos 30 dias
        </h3>
        <div class="flex items-end gap-1.5 h-24">
            @php $maxMin = max(max(array_column($last30Days->toArray(), 'minutes')), 1); @endphp
            @foreach($last30Days as $day)
                @php $h = round($day['minutes'] / $maxMin * 100); @endphp
                <div class="flex-1 flex flex-col items-center gap-0.5 group relative">
                    <div class="absolute bottom-full mb-1 hidden group-hover:block bg-kvnavy dark:bg-gray-700 text-white text-[10px] font-medium px-2 py-0.5 rounded whitespace-nowrap z-10">
                        {{ $day['full'] ?? $day['label'] }}: {{ $day['minutes'] }}min
                    </div>
                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500">{{ $day['minutes'] > 0 ? $day['minutes'] : '' }}</span>
                    <div class="w-full rounded-sm bg-gradient-to-t from-kvteal to-kvteal-light transition-all duration-500" style="height: {{ max($h, 1) }}%"></div>
                    <span class="text-[8px] font-medium text-slate-400 dark:text-slate-500">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Session history --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800">
            <h2 class="font-bold text-kvnavy dark:text-white">Histórico de sessões</h2>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-gray-800">
            @forelse($sessions as $session)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-2 h-2 rounded-full {{ $session->specialization?->dot ?? 'bg-slate-400' }}"></span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $session->specialization?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $session->started_at->format('d/m/Y H:i') }}
                                @if($session->notes)
                                    · {{ Str::limit($session->notes, 40) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-kvteal">{{ $session->duration_formatted }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 dark:text-slate-500 text-center py-8">Nenhuma sessão ainda.</p>
            @endforelse
        </div>
        <div class="px-5 py-3 border-t border-slate-100 dark:border-gray-800">
            {{ $sessions->links('pagination::tailwind') }}
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="{{ route('studies.dashboard') }}" class="text-sm font-semibold text-slate-400 hover:text-kvteal transition-colors">&larr; Dashboard de estudos</a>
    </div>
</div>

<script>
function pomodoro() {
    return {
        activeSession: null,
        elapsed: '00:00',
        interval: null,
        form: {
            specialization_id: '',
            notes: '',
        },
        async init() {
            const res = await fetch('{{ route('studies.timer.status') }}');
            const data = await res.json();
            if (data.active) {
                this.activeSession = data.session;
                if (data.session.specialization) {
                    this.activeSession.dot = data.session.specialization.dot || 'bg-slate-400';
                } else {
                    this.activeSession.dot = 'bg-slate-400';
                }
                this.startClock(data.elapsed_seconds);
            }
        },
        startClock(seconds) {
            this.elapsed = this.formatTime(seconds);
            if (this.interval) clearInterval(this.interval);
            this.interval = setInterval(() => {
                seconds++;
                this.elapsed = this.formatTime(seconds);
            }, 1000);
        },
        formatTime(s) {
            const m = Math.floor(s / 60);
            const sec = s % 60;
            return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
        },
        async startSession() {
            if (!this.form.specialization_id) return;
            const res = await fetch('{{ route('studies.timer.start') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    study_specialization_id: this.form.specialization_id,
                    notes: this.form.notes,
                }),
            });
            const data = await res.json();
            if (data.success) {
                this.activeSession = data.session;
                if (data.session.specialization) {
                    this.activeSession.dot = data.session.specialization.dot || 'bg-slate-400';
                } else {
                    this.activeSession.dot = 'bg-slate-400';
                }
                this.startClock(0);
                this.form.specialization_id = '';
                this.form.notes = '';
            }
        },
        async stopSession() {
            const res = await fetch('{{ route('studies.timer.stop') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
            });
            const data = await res.json();
            if (data.success) {
                clearInterval(this.interval);
                this.activeSession = null;
                this.elapsed = '00:00';
                location.reload();
            }
        },
    };
}
</script>
@endsection
