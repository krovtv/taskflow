<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Estudos') · KV Tech Organizer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>if(localStorage.getItem('theme')==='dark'||(!localStorage.getItem('theme')&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark')</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        kvnavy: { DEFAULT: '#070821', light: '#0e1238', dark: '#04040f' },
                        kvteal: { DEFAULT: '#1ec2cf', light: '#5fe0ea', dark: '#138a93' },
                        kvgold: '#f59e0b',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        html.dark { color-scheme: dark; }
        html:not(.dark) { color-scheme: light; }
        * { scroll-behavior: smooth; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #1ec2cf; border-radius: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .sidebar-overlay { transition: opacity 0.3s ease; }
        .sidebar-panel { transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1); }
        @media (max-width: 767px) { .sidebar-open { transform: translateX(0) !important; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeIn 0.4s ease-out both; }
        .animate-in-d1 { animation-delay: 0.05s; }
        .animate-in-d2 { animation-delay: 0.1s; }
        .animate-in-d3 { animation-delay: 0.15s; }
        .animate-in-d4 { animation-delay: 0.2s; }
        .animate-in-d5 { animation-delay: 0.25s; }
        .card-hover { transition: all 0.25s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -8px rgba(7, 8, 33, 0.08); }
        .dark .card-hover:hover { box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.4); }
    </style>
</head>
<body x-data="{ dark: document.documentElement.classList.contains('dark') }"
      x-init="$watch('dark', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('theme', val ? 'dark' : 'light') })"
      class="bg-[#f1f5f9] dark:bg-gray-950 text-slate-800 dark:text-slate-200 font-sans antialiased">

<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

    {{-- OVERLAY MOBILE --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="md:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-20"></div>

    {{-- SIDEBAR --}}
    <aside x-bind:class="sidebarOpen ? 'sidebar-open' : ''"
           class="sidebar-panel fixed md:sticky top-0 left-0 z-30 md:z-auto w-64 h-screen bg-gradient-to-b from-kvnavy via-[#070821] to-[#04040f] flex flex-col -translate-x-full md:translate-x-0 shadow-2xl shadow-black/20">

        <div class="relative px-5 pt-6 pb-5">
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-purple-400/30 to-transparent"></div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-emerald-400 flex items-center justify-center shadow-lg shadow-purple-500/25 shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                </div>
                <div>
                    <p class="font-extrabold tracking-tight text-white text-lg leading-none">
                        <span class="text-purple-400">EST</span>UDOS
                    </p>
                    <p class="text-[10px] text-white/30 font-medium tracking-wider leading-none mt-0.5">MÓDULO DE APRENDIZADO</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-thin">
            <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/20">Navegação</p>

            <a href="{{ route('studies.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('studies.dashboard') ? 'bg-purple-500/20 text-white font-semibold shadow-sm border border-purple-500/20' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Dashboard
            </a>

            <a href="{{ route('studies.timer.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('studies.timer.*') ? 'bg-purple-500/20 text-white font-semibold shadow-sm border border-purple-500/20' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Timer
            </a>

            <a href="{{ route('studies.flashcards.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('studies.flashcards.*') ? 'bg-purple-500/20 text-white font-semibold shadow-sm border border-purple-500/20' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                Flashcards
            </a>

            <a href="{{ route('studies.specializations.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('studies.specializations.*') ? 'bg-purple-500/20 text-white font-semibold shadow-sm border border-purple-500/20' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                Especializações
            </a>

            <div class="pt-6 pb-1">
                <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/20">Geral</p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm text-white/30 hover:bg-white/5 hover:text-white/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15l3-3m0 0l3-3m-3 3l-3-3m3 3l3 3"/></svg>
                Voltar ao painel principal
            </a>
        </nav>

        <div class="px-3 py-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/30 hover:text-white/70 hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTEÚDO --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white/70 dark:bg-gray-900/80 backdrop-blur-xl border-b border-slate-200/50 dark:border-gray-800/50 px-4 md:px-8 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm shadow-slate-200/30 dark:shadow-black/10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-kvnavy dark:text-white tracking-tight">@yield('heading', 'Dashboard')</h1>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium tracking-wide">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <button @click="dark = !dark"
                        class="w-9 h-9 rounded-xl bg-slate-100/80 dark:bg-gray-800 hover:bg-slate-200/80 dark:hover:bg-gray-700 flex items-center justify-center transition-all">
                    <template x-if="!dark">
                        <svg class="w-[18px] h-[18px] text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    </template>
                    <template x-if="dark">
                        <svg class="w-[18px] h-[18px] text-amber-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </template>
                </button>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-5 flex items-center gap-3 bg-emerald-50/90 dark:bg-emerald-900/30 backdrop-blur-sm text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/50 rounded-xl px-4 py-3.5 text-sm font-medium shadow-sm animate-in">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-800/50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 dark:text-emerald-500 dark:hover:text-emerald-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div x-data="{ show: true }" x-show="show"
                     class="mb-5 bg-red-50/90 dark:bg-red-900/30 backdrop-blur-sm text-red-700 dark:text-red-300 border border-red-200/60 dark:border-red-800/50 rounded-xl px-4 py-3.5 text-sm shadow-sm animate-in">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-800/50 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        </div>
                        <ul class="list-disc pl-4 space-y-1 flex-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
