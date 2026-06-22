@extends('layouts.app')
@section('title', $specialization->name)
@section('heading', $specialization->name)

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/50 dark:border-emerald-800/40 text-emerald-700 dark:text-emerald-300 rounded-xl px-4 py-3 mb-5 text-sm font-medium animate-in">{{ session('success') }}</div>
    @endif

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm p-6 animate-in mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="w-3 h-3 rounded-full {{ $specialization->dot }}"></span>
                <div>
                    <h2 class="text-xl font-bold text-kvnavy dark:text-white">{{ $specialization->name }}</h2>
                    @if($specialization->description)
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-0.5">{{ $specialization->description }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('studies.specializations.index') }}" class="text-sm font-semibold text-slate-400 hover:text-kvteal transition-colors">&larr; Voltar</a>
        </div>

        <div class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-gray-800">
            <div class="text-center">
                <p class="text-2xl font-bold text-kvteal">{{ $stats['total_flashcards'] }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Flashcards</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-500">{{ $stats['due_flashcards'] }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Revisar</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-amber-500">{{ $stats['total_sessions'] }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Sessões</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-500">{{ $stats['total_hours'] }}h</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Estudo</p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- ANOTAÇÕES --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="font-bold text-kvnavy dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                    Anotações
                </h3>
                <button @click="$dispatch('open-note')" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark transition-colors">+ Nova</button>
            </div>

            <div x-data="{ showForm: false, type: 'text' }" @open-note.window="showForm = true">
                {{-- FORM NOVA ANOTAÇÃO --}}
                <div x-show="showForm" x-cloak x-transition:enter="transition-all duration-200" class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 bg-slate-50/50 dark:bg-gray-800/30">
                    <form method="POST" action="{{ route('studies.notes.store', $specialization) }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="study_specialization_id" value="{{ $specialization->id }}">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">Título</label>
                            <input type="text" name="title" required maxlength="255" placeholder="Ex: Documentação oficial React"
                                   class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all dark:text-white placeholder:text-slate-400">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">Tipo</label>
                            <select x-model="type" name="type" class="border border-slate-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all dark:text-white px-3 py-2">
                                <option value="text">Texto / Passo a passo</option>
                                <option value="link">Link</option>
                            </select>
                        </div>
                        <template x-if="type === 'link'">
                            <div>
                                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">URL</label>
                                <input type="url" name="url" placeholder="https://..."
                                       class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all dark:text-white placeholder:text-slate-400">
                            </div>
                        </template>
                        <template x-if="type === 'text'">
                            <div>
                                <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-1">Conteúdo <span class="text-slate-300 dark:text-slate-600 font-normal">— markdown suportado</span></label>
                                <textarea name="content" rows="4" maxlength="10000" placeholder="Digite suas anotações, links, comandos..."
                                          class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 transition-all dark:text-white placeholder:text-slate-400 resize-none font-mono"></textarea>
                            </div>
                        </template>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-gradient-to-r from-purple-500 to-purple-400 hover:from-purple-600 hover:to-purple-500 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all shadow-sm">Salvar</button>
                            <button type="button" @click="showForm = false; type = 'text'" class="text-sm font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-3 py-2 rounded-lg transition-all">Cancelar</button>
                        </div>
                    </form>
                </div>

                {{-- LISTA DE ANOTAÇÕES --}}
                <div class="divide-y divide-slate-100 dark:divide-gray-800">
                    @forelse($specialization->notes as $note)
                        <div class="px-5 py-4 group">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($note->type === 'link')
                                            <svg class="w-3.5 h-3.5 text-purple-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        @endif
                                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ $note->type === 'link' ? 'Link' : 'Nota' }}</span>
                                    </div>
                                    @if($note->type === 'link')
                                        <a href="{{ $note->url }}" target="_blank" rel="noopener noreferrer"
                                           class="text-sm font-semibold text-purple-500 hover:text-purple-600 dark:text-purple-400 dark:hover:text-purple-300 hover:underline transition-colors">
                                            {{ $note->title }}
                                            <svg class="w-3 h-3 inline-block ml-0.5 -mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </a>
                                    @else
                                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $note->title }}</p>
                                        @if($note->content)
                                            <div class="mt-1.5 text-xs text-slate-500 dark:text-slate-400 leading-relaxed whitespace-pre-wrap font-mono bg-slate-50 dark:bg-gray-800/50 rounded-lg p-3">{{ $note->content }}</div>
                                        @endif
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('studies.notes.destroy', $note) }}" class="shrink-0 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity" onsubmit="return confirm('Remover esta anotação?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-400 hover:text-red-500 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-2 py-1 rounded-lg transition-all">Excluir</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <svg class="w-8 h-8 text-slate-200 dark:text-gray-700 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                            <p class="text-sm text-slate-400 dark:text-slate-500">Nenhuma anotação ainda.</p>
                            <p class="text-xs text-slate-300 dark:text-slate-600 mt-0.5">Adicione links úteis, manuais ou passo a passo.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- FLASHCARDS DA ESPECIALIZAÇÃO --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="font-bold text-kvnavy dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    Flashcards
                </h3>
                <a href="{{ route('studies.flashcards.index', ['specialization' => $specialization->id]) }}" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark transition-colors">Ver todos</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-gray-800">
                @forelse($flashcards as $card)
                    <div class="px-5 py-3">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded {{ match($card->difficulty) { 1 => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400', 2 => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400', 3 => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', 4 => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', 5 => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400', default => 'bg-slate-100 text-slate-500' } }}">{{ $card->difficulty }}/5</span>
                            @if($card->isDueForReview())
                                <span class="text-[10px] font-medium bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 dark:text-emerald-400 px-1.5 py-0.5 rounded-full">Revisar</span>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $card->front }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ Str::limit($card->back, 100) }}</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-sm text-slate-400 dark:text-slate-500">Nenhum flashcard nesta especialização.</p>
                        <a href="{{ route('studies.flashcards.index', ['specialization' => $specialization->id]) }}" class="text-xs font-semibold text-kvteal hover:text-kvteal-dark mt-1 inline-block">Criar flashcards →</a>
                    </div>
                @endforelse
            </div>
            @if($flashcards->hasPages())
                <div class="px-5 py-3 border-t border-slate-100 dark:border-gray-800">
                    {{ $flashcards->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>

    {{-- AÇÕES RÁPIDAS --}}
    <div class="flex flex-wrap gap-3 mt-6">
        <a href="{{ route('studies.timer.index') }}"
           class="bg-gradient-to-r from-kvteal to-kvteal-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Estudar agora
        </a>
        <a href="{{ route('studies.flashcards.review') }}"
           class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Revisar flashcards
        </a>
    </div>
</div>
@endsection
