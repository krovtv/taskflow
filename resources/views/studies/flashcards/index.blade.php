@extends('layouts.app')
@section('title', 'Flashcards')
@section('heading', 'Flashcards')

@section('content')
<div class="max-w-3xl mx-auto">
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/40 text-emerald-700 dark:text-emerald-300 rounded-xl px-4 py-3 mb-5 text-sm font-medium animate-in">{{ session('success') }}</div>
    @endif

    {{-- EMPTY STATE: nenhuma especialização --}}
    @if($specializations->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm animate-in p-10 text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500/10 to-purple-500/5 flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-kvnavy dark:text-white mb-2">Nenhuma especialização ainda</h2>
            <p class="text-sm text-slate-400 dark:text-slate-500 mb-6 max-w-sm mx-auto">Os flashcards são organizados por especialização. Crie uma para começar a adicionar seus cards de estudo.</p>

            <form method="POST" action="{{ route('studies.specializations.store') }}" class="max-w-sm mx-auto space-y-3">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="name" placeholder="Ex: React, Inglês, Data Science..." required maxlength="255"
                           class="flex-1 border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all dark:text-white placeholder:text-slate-400">
                    <select name="color" class="border border-slate-200 dark:border-gray-700 rounded-xl text-xs bg-white dark:bg-gray-800 outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all dark:text-white px-2">
                        @foreach(\App\Models\Category::COLORS as $key => $meta)
                            <option value="{{ $key }}" {{ $loop->first ? 'selected' : '' }}>{{ ucfirst($key) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 justify-center">
                    <button type="submit" class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                        Criar especialização
                    </button>
                    <a href="{{ route('studies.specializations.index') }}" class="text-sm font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-4 py-2.5 rounded-xl transition-all">
                        Gerenciar
                    </a>
                </div>
            </form>
        </div>
    @else
        {{-- CRIAÇÃO DE FLASHCARD --}}
        <div x-data="{ showForm: false }" class="mb-6">
            <button @click="showForm = !showForm"
                    class="w-full bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm hover:shadow-md transition-all p-4 flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-kvteal to-kvteal-dark text-white flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" :class="showForm ? 'rotate-45' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </span>
                    <span class="font-bold text-kvnavy dark:text-white" x-text="showForm ? 'Fechar' : 'Novo flashcard'"></span>
                </div>
                <span class="text-xs text-slate-400">Ctrl+N</span>
            </button>

            <div x-show="showForm" x-cloak x-transition:enter="transition-all duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm mt-2">
                    <form method="POST" action="{{ route('studies.flashcards.store') }}" class="p-5 space-y-4">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Especialização</label>
                                <select name="study_specialization_id" required
                                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white appearance-none">
                                    <option value="">Selecione...</option>
                                    @foreach($specializations as $spec)
                                        <option value="{{ $spec->id }}" {{ request('specialization') == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Dificuldade inicial (opcional)</label>
                                <select name="difficulty" class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white appearance-none">
                                    <option value="3">Médio (3)</option>
                                    <option value="1">Muito difícil (1)</option>
                                    <option value="2">Difícil (2)</option>
                                    <option value="4">Fácil (4)</option>
                                    <option value="5">Muito fácil (5)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Frente <span class="text-slate-300 dark:text-slate-600 font-normal">— pergunta, conceito, termo</span></label>
                            <textarea name="front" required rows="2" maxlength="1000"
                                      class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400 resize-none"
                                      placeholder="Ex: O que é uma closure em JavaScript?"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Verso <span class="text-slate-300 dark:text-slate-600 font-normal">— resposta, definição, explicação</span></label>
                            <textarea name="back" required rows="3" maxlength="2000"
                                      class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400 resize-none"
                                      placeholder="Ex: Uma closure é uma função que se 'lembra' do escopo onde foi criada, mesmo após esse escopo ter sido executado."></textarea>
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    Criar flashcard
                                </span>
                            </button>
                            <button type="reset" @click="showForm = false" class="text-sm font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-4 py-2.5 rounded-xl transition-all">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- CABEÇALHO DA LISTA --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <span class="font-bold text-kvnavy dark:text-white">Seus flashcards</span>
                <span class="text-xs font-medium bg-slate-100 dark:bg-gray-800 text-slate-400 dark:text-slate-500 px-2.5 py-0.5 rounded-full">{{ $flashcards->total() }} total</span>
                @php $due = $flashcards->filter(fn($f) => $f->isDueForReview())->count(); @endphp
                @if($due > 0)
                    <span class="text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-2.5 py-0.5 rounded-full">{{ $due }} p/ revisar</span>
                @endif
            </div>
            <a href="{{ route('studies.flashcards.review') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-4 py-1.5 rounded-lg transition-all shadow-sm">Revisar agora</a>
        </div>

        {{-- LISTA DE FLASHCARDS --}}
        <div class="space-y-2">
            @forelse($flashcards as $card)
                @php $dot = $card->specialization?->dot ?? 'bg-slate-400'; @endphp
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-slate-200/70 dark:border-gray-700/50 px-5 py-3.5 flex items-start gap-3 group shadow-sm hover:shadow-md transition-all">
                    @php $cardJson = json_encode(['id' => $card->id, 'front' => $card->front, 'back' => $card->back, 'spec' => $card->study_specialization_id, 'difficulty' => $card->difficulty]); @endphp
                    <span class="w-2 h-2 rounded-full {{ $dot }} mt-2 shrink-0"></span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ $card->specialization?->name ?? '—' }}</span>
                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded {{ match($card->difficulty) { 1 => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400', 2 => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400', 3 => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', 4 => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', 5 => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400', default => 'bg-slate-100 text-slate-500 dark:bg-gray-800 dark:text-slate-400' } }}">{{ $card->difficulty }}/5</span>
                            @if($card->isDueForReview())
                                <span class="text-[10px] font-medium bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 dark:text-emerald-400 px-1.5 py-0.5 rounded-full">Revisar</span>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-0.5">{{ $card->front }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">{{ Str::limit($card->back, 160) }}</p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-all duration-200">
                        <button @click="$dispatch('open-edit', JSON.parse($el.dataset.card))"
                                data-card='{{ $cardJson }}'
                                class="text-xs font-semibold text-kvteal hover:text-white hover:bg-kvteal bg-kvteal/10 px-2.5 py-1.5 rounded-lg transition-all">
                            Editar
                        </button>
                        <form method="POST" action="{{ route('studies.flashcards.destroy', $card) }}" onsubmit="return confirm('Excluir este flashcard?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-400 hover:text-white hover:bg-red-500 bg-red-50 dark:bg-red-900/30 px-2.5 py-1.5 rounded-lg transition-all">Excluir</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-slate-200/70 dark:border-gray-700/50 p-10 text-center">
                    <svg class="w-12 h-12 text-slate-200 dark:text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    <p class="text-sm font-semibold text-slate-400 dark:text-slate-500 mb-1">Nenhum flashcard ainda</p>
                    <p class="text-xs text-slate-300 dark:text-slate-600">Clique em "Novo flashcard" acima para criar o primeiro.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5">
            {{ $flashcards->links('pagination::tailwind') }}
        </div>
    @endif

    <div class="mt-5 text-center">
        <a href="{{ route('studies.dashboard') }}" class="text-sm font-semibold text-slate-400 hover:text-kvteal transition-colors">&larr; Dashboard de estudos</a>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div x-data="{ open: false, id: null, front: '', back: '', spec: null, difficulty: 3 }"
     @open-edit.window="open = true; id = $event.detail.id; front = $event.detail.front; back = $event.detail.back; spec = $event.detail.spec; difficulty = $event.detail.difficulty"
     x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-transition:enter="transition-all duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-all duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
    <div class="relative bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-xl w-full max-w-lg p-6 z-10" @click.outside="open = false">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-kvnavy dark:text-white">Editar flashcard</h3>
            <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" x-bind:action="`/estudos/flashcards/${id}`" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Especialização</label>
                <select name="study_specialization_id" x-model="spec" required
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white">
                    <option value="">Selecione...</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Frente</label>
                <textarea x-model="front" name="front" required maxlength="1000" rows="2"
                          class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400 resize-none"></textarea>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Verso</label>
                <textarea x-model="back" name="back" required maxlength="2000" rows="3"
                          class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3.5 py-2.5 text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white placeholder:text-slate-400 resize-none"></textarea>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1.5">Dificuldade</label>
                <select name="difficulty" x-model="difficulty" class="border border-slate-200 dark:border-gray-700 rounded-xl text-sm bg-white dark:bg-gray-800 outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all dark:text-white py-2.5 px-3.5 w-28">
                    <option value="1">Muito difícil (1)</option>
                    <option value="2">Difícil (2)</option>
                    <option value="3">Médio (3)</option>
                    <option value="4">Fácil (4)</option>
                    <option value="5">Muito fácil (5)</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">Salvar</button>
                <button type="button" @click="open = false" class="text-sm font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-4 py-2.5 rounded-xl transition-all">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection
