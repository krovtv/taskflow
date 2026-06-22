@php
    $task = $task ?? null;
    $isEdit = isset($task);
    $selectedCategory = $selectedCategory ?? null;
    $selectedProject = $selectedProject ?? null;
    $projects = $projects ?? collect();
@endphp

{{-- SEÇÃO 1: INFORMAÇÕES BÁSICAS --}}
<div class="bg-gradient-to-r from-kvteal/[0.02] dark:from-kvteal/[0.05] to-transparent rounded-2xl p-5 md:p-6 mb-6 border border-kvteal/5">
    <div class="flex items-center gap-2.5 mb-6">
        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-kvteal to-kvteal-dark text-white flex items-center justify-center text-xs font-bold shadow-sm">1</div>
        <div>
            <h3 class="font-bold text-kvnavy dark:text-white text-base">Informações básicas</h3>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">O que precisa ser feito</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Título da tarefa <span class="text-red-400">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" required
                   placeholder="Ex: Implementar módulo de relatórios"
                   class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 shadow-sm">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Descrição detalhada</label>
            <textarea name="description" rows="4" placeholder="Descreva o escopo, requisitos e critérios de aceitação..."
                      class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 resize-y min-h-[100px] shadow-sm">{{ old('description', $task->description ?? '') }}</textarea>
        </div>

        <div x-data="categoryManager()">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Categoria <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <select name="category_id" id="category-select" required
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" data-name="{{ $name }}"
                            @selected(old('category_id', $task->category_id ?? $selectedCategory ?? '') == $id)>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
            <button type="button" @click="show = true"
                    class="mt-1.5 text-xs font-medium text-kvteal hover:text-kvteal-dark transition-colors inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nova categoria
            </button>

            {{-- Modal criar categoria --}}
            <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 @keydown.escape.window="show = false">
                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-slate-200 dark:border-gray-700 p-6 w-full max-w-sm">
                    <h4 class="font-bold text-kvnavy dark:text-white text-base mb-4">Nova categoria</h4>
                    <input type="text" x-model="name" placeholder="Nome da categoria"
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-slate-50 dark:bg-gray-900 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none mb-4">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Cor</p>
                    <div class="flex flex-wrap gap-2 mb-5">
                        <template x-for="(cls, c) in colors" :key="c">
                            <button type="button" @click="color = c"
                                    :class="[cls.dot, 'w-7 h-7 rounded-full transition-all']"
                                    :class="{ 'ring-2 ring-offset-2 ring-kvnavy dark:ring-white scale-110': color === c }">
                            </button>
                        </template>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" @click="show = false"
                                class="text-sm font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-slate-100 dark:bg-gray-900 px-4 py-2 rounded-xl transition-all">Cancelar</button>
                        <button type="button" @click="create()" :disabled="!name.trim()"
                                class="text-sm font-semibold text-white bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-kvteal-dark hover:to-kvteal px-4 py-2 rounded-xl transition-all shadow-sm disabled:opacity-50">
                            Criar
                        </button>
                    </div>
                    <p x-show="error" x-text="error" class="text-xs text-red-400 mt-2"></p>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Prazo <span class="text-red-400">*</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                <div class="relative">
                    <input type="date" name="due_date" id="due_date"
                           value="{{ old('due_date', $isEdit ? $task->due_date->format('Y-m-d') : '') }}" required
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none shadow-sm [&::-webkit-calendar-picker-indicator]:dark:invert">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                </div>
                <div class="relative">
                    <input type="time" name="due_time" id="due_time"
                           value="{{ old('due_time', $isEdit ? $task->due_date->format('H:i') : '23:59') }}" required
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none shadow-sm [&::-webkit-calendar-picker-indicator]:dark:invert">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-1.5 mt-2">
                <button type="button" onclick="setPrazo(0, 18, 0)" class="text-[11px] font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 hover:bg-kvteal/10 hover:text-kvteal transition-all">Hoje 18h</button>
                <button type="button" onclick="setPrazo(0, 23, 59)" class="text-[11px] font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 hover:bg-kvteal/10 hover:text-kvteal transition-all">Hoje 23:59</button>
                <button type="button" onclick="setPrazo(1, 9, 0)" class="text-[11px] font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 hover:bg-kvteal/10 hover:text-kvteal transition-all">Amanhã 9h</button>
                <button type="button" onclick="setPrazo(1, 14, 0)" class="text-[11px] font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 hover:bg-kvteal/10 hover:text-kvteal transition-all">Amanhã 14h</button>
                <button type="button" onclick="setPrazo(3, 9, 0)" class="text-[11px] font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 hover:bg-kvteal/10 hover:text-kvteal transition-all">+3 dias 9h</button>
                <button type="button" onclick="setPrazo(7, 9, 0)" class="text-[11px] font-medium px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-slate-400 hover:bg-kvteal/10 hover:text-kvteal transition-all">+7 dias 9h</button>
            </div>
            <p id="prazo-preview" class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5 font-medium hidden">
                <span id="prazo-preview-text"></span>
            </p>
        </div>
    </div>
</div>

{{-- SEÇÃO 2: PLANEJAMENTO --}}
<div class="bg-gradient-to-r from-amber-50/30 dark:from-amber-900/10 to-transparent rounded-2xl p-5 md:p-6 mb-6 border border-amber-100/30 dark:border-amber-800/30" id="planejamento-section">
    <div class="flex items-center gap-2.5 mb-6">
        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-500 to-amber-400 text-white flex items-center justify-center text-xs font-bold shadow-sm">2</div>
        <div>
            <h3 class="font-bold text-kvnavy dark:text-white text-base">Planejamento</h3>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">Prioridade, esforço e organização</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Prioridade</label>
            <div class="relative">
                <select name="priority"
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                    @foreach($priorities as $key => $label)
                        <option value="{{ $key }}" @selected(old('priority', $task->priority ?? 'media') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
            <div class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500">
                <span class="flex gap-1">
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-gray-600"></span>
                </span>
                <span>Urgente > Alta > Média > Baixa</span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Horas estimadas
                <span class="font-normal text-slate-400 dark:text-slate-500 text-xs ml-1">(opcional)</span>
            </label>
            <div class="relative">
                <input type="number" name="estimated_hours" step="0.5" min="0" max="9999"
                       value="{{ old('estimated_hours', $task->estimated_hours ?? '') }}"
                       placeholder="Ex: 8.5"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500 text-sm font-medium">
                    horas
                </div>
            </div>
        </div>

        {{-- RECORRÊNCIA --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Repetir
                <span class="font-normal text-slate-400 dark:text-slate-500 text-xs ml-1">(opcional)</span>
            </label>
            <div class="relative">
                <select name="recurring_frequency"
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                    <option value="">Não repetir</option>
                    @foreach(\App\Models\Task::FREQUENCIES as $key => $label)
                        <option value="{{ $key }}" @selected(old('recurring_frequency', $task->recurring_frequency ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
            <div class="mt-2">
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Até (opcional)</label>
                <input type="date" name="recurring_end_date"
                       value="{{ old('recurring_end_date', $task->recurring_end_date ?? '') }}"
                       class="w-full border border-slate-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Tags / etiquetas
                <span class="font-normal text-slate-400 dark:text-slate-500 text-xs ml-1">(opcional)</span>
            </label>
            <input type="text" name="tags"
                   value="{{ old('tags', $task->tags ?? '') }}"
                   placeholder="frontend, urgente, equipe-a"
                   class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 shadow-sm">
            <div class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                <span>Separe por vírgula (ex: api, backend, revisão)</span>
            </div>
        </div>
    </div>
</div>

{{-- SEÇÃO 3: ACOMPANHAMENTO --}}
<div class="bg-gradient-to-r from-emerald-50/30 dark:from-emerald-900/10 to-transparent rounded-2xl p-5 md:p-6 mb-6 border border-emerald-100/30 dark:border-emerald-800/30">
    <div class="flex items-center gap-2.5 mb-6">
        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-400 text-white flex items-center justify-center text-xs font-bold shadow-sm">3</div>
        <div>
            <h3 class="font-bold text-kvnavy dark:text-white text-base">Acompanhamento</h3>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">Status, progresso e vínculo com projeto</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Status atual</label>
            <div class="relative">
                <select name="status"
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(old('status', $task->status ?? 'pendente') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
                Progresso
                <span class="font-normal text-slate-400 dark:text-slate-500 text-xs ml-1">(0-100%)</span>
            </label>
            <div class="flex items-center gap-3">
                <input type="range" id="progress-slider" min="0" max="100"
                       value="{{ old('progress', $task->progress ?? 0) }}"
                       oninput="document.getElementById('progress-input').value = this.value"
                       class="flex-1 h-2 rounded-lg appearance-none cursor-pointer
                              [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-gradient-to-r [&::-webkit-slider-thumb]:from-kvteal [&::-webkit-slider-thumb]:to-kvteal-dark [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:shadow-kvteal/30
                              [&::-webkit-slider-runnable-track]:rounded-lg [&::-webkit-slider-runnable-track]:bg-slate-200 dark:bg-gray-700">
                <div class="flex items-center gap-1 min-w-[72px] justify-end">
                    <input type="number" id="progress-input" name="progress" min="0" max="100"
                           value="{{ old('progress', $task->progress ?? 0) }}"
                           oninput="document.getElementById('progress-slider').value = Math.min(100, Math.max(0, this.value || 0))"
                           class="w-14 border border-slate-200 dark:border-gray-700 rounded-lg px-2 py-1.5 text-lg font-extrabold text-kvteal text-right tabular-nums bg-transparent dark:text-white outline-none focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                    <span class="text-lg font-extrabold text-kvteal">%</span>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Projeto <span class="font-normal text-slate-400 dark:text-slate-500 text-xs">(opcional)</span></label>
            <div class="relative">
                <select name="project_id" id="project-select"
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                    <option value="">Sem vínculo</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" @selected(old('project_id', $task->project_id ?? $selectedProject ?? '') == $proj->id)>
                            {{ $proj->title }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
        </div>

        <div id="phase-wrapper" style="{{ old('project_id', $task->project_id ?? $selectedProject ?? '') ? '' : 'display:none' }}">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Fase do projeto</label>
            <div class="relative">
                <select name="project_phase_id" id="phase-select"
                        class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                    <option value="">Nenhuma fase específica</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function categoryManager() {
        return {
            show: false,
            name: '',
            color: 'blue',
            error: '',
            colors: @json(\App\Models\Category::COLORS),
            create() {
                this.error = '';
                if (!this.name.trim()) return;
                fetch('{{ route('categories.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 'Accept': 'application/json' },
                    body: JSON.stringify({ name: this.name.trim(), color: this.color })
                }).then(r => {
                    if (!r.ok) return r.json().then(e => { throw new Error(e.message || 'Erro ao criar'); });
                    return r.json();
                }).then(data => {
                    if (!data.success) { this.error = 'Erro ao criar'; return; }
                    const sel = document.getElementById('category-select');
                    const opt = document.createElement('option');
                    opt.value = data.category.id;
                    opt.textContent = data.category.name;
                    opt.selected = true;
                    sel.appendChild(opt);
                    this.show = false;
                    this.name = '';
                }).catch(e => { this.error = e.message || 'Erro ao criar categoria'; });
            }
        }
    }
    function setPrazo(days, hour, minute) {
        const d = new Date();
        d.setDate(d.getDate() + days);
        d.setHours(hour, minute, 0, 0);
        const pad = n => String(n).padStart(2, '0');
        document.getElementById('due_date').value = d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate());
        document.getElementById('due_time').value = pad(hour) + ':' + pad(minute);
        document.getElementById('due_time').dispatchEvent(new Event('change'));
        atualizarPreview(d);
    }
    function atualizarPreview(d) {
        const el = document.getElementById('prazo-preview');
        const txt = document.getElementById('prazo-preview-text');
        const diff = Math.round((d - new Date()) / 3600000);
        if (diff < 0) { txt.textContent = '⚠ Prazo já passou!'; el.className = 'text-[11px] text-red-400 font-medium mt-1.5'; }
        else if (diff < 2) { txt.textContent = 'Vence em menos de 2 horas!'; el.className = 'text-[11px] text-amber-500 font-medium mt-1.5'; }
        else if (diff < 24) { txt.textContent = 'Vence em ' + diff + ' hora' + (diff > 1 ? 's' : ''); el.className = 'text-[11px] text-amber-500 font-medium mt-1.5'; }
        else { const days = Math.round(diff / 24); txt.textContent = d.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' }) + ' (' + days + ' dia' + (days > 1 ? 's' : '') + ')'; el.className = 'text-[11px] text-slate-400 dark:text-slate-500 font-medium mt-1.5'; }
        el.classList.remove('hidden');
    }
    document.addEventListener('DOMContentLoaded', function () {
        const projectSelect = document.getElementById('project-select');
        const phaseSelect = document.getElementById('phase-select');
        const phaseWrapper = document.getElementById('phase-wrapper');

        const phasesData = @json($projects->mapWithKeys(fn($p) => [$p->id => $p->phases->map(fn($ph) => ['id' => $ph->id, 'title' => $ph->title])]));

        function updatePhases(projectId) {
            const phases = phasesData[projectId] || [];
            phaseSelect.innerHTML = '<option value="">Nenhuma fase específica</option>';
            phases.forEach(function (ph) {
                const opt = document.createElement('option');
                opt.value = ph.id;
                opt.textContent = ph.title;
                @if(isset($task) && $task->project_phase_id)
                    if (ph.id == {{ $task->project_phase_id }}) opt.selected = true;
                @endif
                phaseSelect.appendChild(opt);
            });
            phaseWrapper.style.display = projectId && phases.length > 0 ? '' : 'none';
        }

        if (projectSelect) {
            updatePhases(projectSelect.value);
            projectSelect.addEventListener('change', function () {
                updatePhases(this.value);
            });
        }

        // Preview do prazo ao digitar manualmente
        const dateInput = document.getElementById('due_date');
        const timeInput = document.getElementById('due_time');
        function previewFromInputs() {
            if (dateInput.value && timeInput.value) {
                const parts = dateInput.value.split('-');
                const timeParts = timeInput.value.split(':');
                atualizarPreview(new Date(+parts[0], +parts[1]-1, +parts[2], +timeParts[0], +timeParts[1]));
            }
        }
        if (dateInput) dateInput.addEventListener('change', previewFromInputs);
        if (timeInput) timeInput.addEventListener('change', previewFromInputs);

        // Mostrar preview inicial no edit
        @if($isEdit)
            previewFromInputs();
        @endif
    });
</script>