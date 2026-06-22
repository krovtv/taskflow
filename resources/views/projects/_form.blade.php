@php $project = $project ?? null; @endphp

<div class="grid md:grid-cols-2 gap-5">
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">
            Título do projeto <span class="text-red-400">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required
               placeholder="Ex: App de entregas"
               class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 shadow-sm">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Descrição</label>
        <textarea name="description" rows="4" placeholder="Descreva o objetivo, escopo e entregas do projeto..."
                  class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none placeholder:text-slate-300 dark:placeholder:text-slate-500 resize-y min-h-[100px] shadow-sm">{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Data de início</label>
        <input type="date" name="start_date"
               value="{{ old('start_date', $project->start_date ?? '') }}"
               class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Data de término</label>
        <input type="date" name="end_date"
               value="{{ old('end_date', $project->end_date ?? '') }}"
               class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Status</label>
        <div class="relative">
            <select name="status"
                    class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-white dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none appearance-none shadow-sm">
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(old('status', $project->status ?? 'planejamento') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>
    </div>
</div>
