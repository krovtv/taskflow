@props(['action', 'method' => 'DELETE', 'title' => 'Confirmar exclusão', 'message' => '', 'buttonText' => 'Excluir', 'buttonClass' => ''])
<div x-data="{ open: false }">
    <button type="button" @click="open = true" class="{{ $buttonClass }}">
        {!! $slot !!}
    </button>
    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-xl p-6 w-full max-w-sm"
             @click.outside="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="scale-100 opacity-100"
             x-transition:leave-end="scale-95 opacity-0">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 2.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-kvnavy dark:text-white">{{ $title }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $message }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
                <button type="button" @click="open = false"
                        class="text-sm font-semibold text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 px-4 py-2 rounded-lg transition-colors">
                    Cancelar
                </button>
                <form method="POST" action="{{ $action }}" class="inline">
                    @csrf
                    @method($method)
                    <button type="submit"
                            class="text-sm font-semibold text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-all shadow-sm">
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
