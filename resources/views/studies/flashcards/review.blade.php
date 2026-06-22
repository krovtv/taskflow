@extends('layouts.app')
@section('title', 'Revisar flashcards')
@section('heading', 'Revisão de flashcards')

@section('content')
<div class="max-w-2xl mx-auto">
    <div x-data="reviewApp()" x-init="init()" class="animate-in">
        {{-- Card count --}}
        <template x-if="cards.length > 0">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-slate-400 dark:text-slate-500">
                    Card <span x-text="index + 1"></span> de <span x-text="cards.length"></span>
                </span>
                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full"
                      :class="cards[index].difficulty <= 2 ? 'bg-red-100 text-red-600' : cards[index].difficulty === 3 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600'">
                    Dificuldade <span x-text="cards[index].difficulty"></span>/5
                </span>
            </div>
        </template>

        {{-- Card front --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-8 text-center mb-4" style="min-height: 240px;">
            <template x-if="cards.length === 0">
                <div class="py-12">
                    <svg class="w-12 h-12 text-emerald-400 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-lg font-bold text-kvnavy dark:text-white mb-1">Revisão completa!</p>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Nenhum flashcard pendente de revisão.</p>
                    <a href="{{ route('studies.flashcards.index') }}" class="inline-block mt-4 text-sm font-semibold text-kvteal hover:text-kvteal-dark transition-colors">&larr; Voltar aos flashcards</a>
                </div>
            </template>
            <template x-if="cards.length > 0">
                <div>
                    <div class="flex items-center justify-center gap-2 mb-6">
                        <span class="w-2.5 h-2.5 rounded-full" :class="cards[index].dot"></span>
                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500" x-text="cards[index].specialization"></span>
                    </div>
                    <p class="text-xl font-bold text-kvnavy dark:text-white leading-relaxed" x-text="cards[index].front"></p>

                    <div x-show="!flipped" class="mt-8">
                        <button @click="flipped = true" class="bg-gradient-to-r from-kvteal to-kvteal-dark text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:from-[#0fa8b3] hover:to-[#0fa8b3] transition-all shadow-sm">
                            Mostrar resposta
                        </button>
                    </div>

                    <template x-if="flipped">
                        <div x-transition:enter="transition-all duration-300" class="mt-6">
                            <div class="border-t border-slate-100 dark:border-gray-800 pt-6">
                                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed" x-text="cards[index].back"></p>
                            </div>

                            <div class="mt-8">
                                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 mb-3">Como foi?</p>
                                <div class="flex flex-wrap justify-center gap-2">
                                    <button @click="review(1)" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-red-500 hover:bg-red-600 transition-all">Muito difícil</button>
                                    <button @click="review(2)" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-orange-500 hover:bg-orange-600 transition-all">Difícil</button>
                                    <button @click="review(3)" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 transition-all">Médio</button>
                                    <button @click="review(4)" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-500 hover:bg-emerald-600 transition-all">Fácil</button>
                                    <button @click="review(5)" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-green-500 hover:bg-green-600 transition-all">Muito fácil</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <template x-if="cards.length > 0">
            <div class="flex justify-between">
                <button @click="prevCard" x-show="index > 0" class="text-sm font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-4 py-2 rounded-lg transition-all">&larr; Anterior</button>
                <span x-show="index === 0"></span>
            </div>
        </template>
    </div>
</div>

<script>
function reviewApp() {
    return {
        cards: @json($flashcards),
        index: 0,
        flipped: false,
        init() {
            if (this.cards.length > 0) {
                this.shuffle();
            }
        },
        shuffle() {
            for (let i = this.cards.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [this.cards[i], this.cards[j]] = [this.cards[j], this.cards[i]];
            }
        },
        async review(difficulty) {
            const card = this.cards[this.index];
            try {
                const res = await fetch('{{ route('studies.flashcards.submit-review') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ flashcard_id: card.id, difficulty }),
                });
                const data = await res.json();
                if (data.success) {
                    this.cards.splice(this.index, 1);
                    this.flipped = false;
                    if (this.index >= this.cards.length) {
                        this.index = Math.max(0, this.cards.length - 1);
                    }
                }
            } catch (e) {
                console.error('Erro ao salvar review:', e);
            }
        },
        prevCard() {
            if (this.index > 0) {
                this.index--;
                this.flipped = false;
            }
        },
    };
}
</script>
@endsection
