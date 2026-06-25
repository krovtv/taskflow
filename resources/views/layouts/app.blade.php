<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', 'Dashboard') · KV Tech Organizer</title>
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

        @media (max-width: 767px) {
            .sidebar-open { transform: translateX(0) !important; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeIn 0.4s ease-out both;
        }
        .animate-in-d1 { animation-delay: 0.05s; }
        .animate-in-d2 { animation-delay: 0.1s; }
        .animate-in-d3 { animation-delay: 0.15s; }
        .animate-in-d4 { animation-delay: 0.2s; }
        .animate-in-d5 { animation-delay: 0.25s; }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 8px rgba(30, 194, 207, 0.15); }
            50% { box-shadow: 0 0 20px rgba(30, 194, 207, 0.3); }
        }
        .logo-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .dropdown-animate {
            animation: slideDown 0.2s ease-out both;
        }

        .card-hover {
            transition: all 0.25s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -8px rgba(7, 8, 33, 0.08);
        }
        .dark .card-hover:hover {
            box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.4);
        }

        .nav-link {
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0.75rem;
            right: 0.75rem;
            bottom: 0;
            height: 2px;
            border-radius: 1px;
            background: #1ec2cf;
            transform: scaleX(0);
            transition: transform 0.2s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            transform: scaleX(1);
        }
    </style>
</head>
<body x-data="{ dark: document.documentElement.classList.contains('dark'), search: false, searchQuery: '', searchResults: [], searchIndex: -1, searchLoading: false, searchTimer: null }"
      x-init="$watch('dark', val => { document.documentElement.classList.toggle('dark', val); localStorage.setItem('theme', val ? 'dark' : 'light') });
              $watch('searchQuery', val => { clearTimeout(searchTimer); if (val.length < 1) { searchResults = []; return }; searchLoading = true; searchTimer = setTimeout(() => { fetch('{{ route('tasks.search') }}?q='+encodeURIComponent(val)).then(r=>r.json()).then(r=>{ searchResults = r; searchLoading = false }).catch(()=>{ searchLoading = false }) }, 200) });
              document.addEventListener('keydown', e => { if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); search = true; searchQuery = ''; searchResults = []; searchIndex = -1; setTimeout(() => document.getElementById('search-input')?.focus(), 100) } if (e.key === 'Escape') search = false })"
      class="bg-[#f1f5f9] dark:bg-gray-950 text-slate-800 dark:text-slate-200 font-sans antialiased">

<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

    {{-- OVERLAY MOBILE --}}
    <div x-show="sidebarOpen" x-cloak
         @click="sidebarOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-20">
    </div>

    {{-- SIDEBAR --}}
    <aside x-bind:class="sidebarOpen ? 'sidebar-open' : ''"
           class="sidebar-panel fixed md:sticky top-0 left-0 z-30 md:z-auto w-64 h-screen bg-gradient-to-b from-kvnavy via-[#070821] to-[#04040f] flex flex-col
                  -translate-x-full md:translate-x-0 shadow-2xl shadow-black/20">

        {{-- LOGO --}}
        <div class="relative px-5 pt-6 pb-5">
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-kvteal/30 to-transparent"></div>
            <div class="flex items-center gap-3">
                <div class="relative shrink-0">
                    <img src="{{ asset('images/logo.svg') }}" alt="KV Tech"
                         class="w-10 h-10 rounded-xl object-cover shadow-lg shadow-kvteal/25 logo-glow">
                </div>
                <div>
                    <p class="font-extrabold tracking-tight text-white text-lg leading-none">
                        KV <span class="text-kvteal">TECH</span>
                    </p>
                    <p class="text-[10px] text-white/30 font-medium tracking-wider leading-none mt-0.5">
                        ORGANIZADOR PESSOAL
                    </p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-thin">
            <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/20">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Visão geral
                @if(request()->routeIs('dashboard'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-kvteal shadow-sm shadow-kvteal/50"></span>
                @endif
            </a>

            <a href="{{ route('tasks.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('tasks.*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                Tarefas
                @if(request()->routeIs('tasks.*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-kvteal shadow-sm shadow-kvteal/50"></span>
                @endif
            </a>

            <div x-data="{ open: {{ request()->routeIs('studies.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                               {{ request()->routeIs('studies.*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                    <span class="flex-1 text-left">Estudos</span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    @if(request()->routeIs('studies.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/50"></span>
                    @endif
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition-all duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="ml-6 mt-0.5 space-y-0.5 border-l border-white/10 pl-3">
                    <a href="{{ route('studies.dashboard') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-200
                              {{ request()->routeIs('studies.dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                        <span class="w-1 h-1 rounded-full {{ request()->routeIs('studies.dashboard') ? 'bg-emerald-400' : 'bg-white/20' }}"></span>
                        Dashboard
                    </a>
                    <a href="{{ route('studies.timer.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-200
                              {{ request()->routeIs('studies.timer.*') ? 'bg-white/10 text-white font-semibold' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                        <span class="w-1 h-1 rounded-full {{ request()->routeIs('studies.timer.*') ? 'bg-emerald-400' : 'bg-white/20' }}"></span>
                        Timer
                    </a>
                    <a href="{{ route('studies.flashcards.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-200
                              {{ request()->routeIs('studies.flashcards.*') ? 'bg-white/10 text-white font-semibold' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                        <span class="w-1 h-1 rounded-full {{ request()->routeIs('studies.flashcards.*') ? 'bg-emerald-400' : 'bg-white/20' }}"></span>
                        Flashcards
                    </a>
                    <a href="{{ route('studies.specializations.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-200
                              {{ request()->routeIs('studies.specializations.*') ? 'bg-white/10 text-white font-semibold' : 'text-white/40 hover:text-white hover:bg-white/5' }}">
                        <span class="w-1 h-1 rounded-full {{ request()->routeIs('studies.specializations.*') ? 'bg-emerald-400' : 'bg-white/20' }}"></span>
                        Especializações
                    </a>
                </div>
            </div>

            <a href="{{ route('projects.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('projects.*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                Projetos
                @if(request()->routeIs('projects.*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-purple-400 shadow-sm shadow-purple-400/50"></span>
                @endif
            </a>

            <a href="{{ route('agenda.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('agenda.*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                Agenda
                @if(request()->routeIs('agenda.*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-kvteal shadow-sm shadow-kvteal/50"></span>
                @endif
            </a>

            <a href="{{ route('daily-log.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('daily-log.*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                Notas Diárias
                @if(request()->routeIs('daily-log.*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-amber-400 shadow-sm shadow-amber-400/50"></span>
                @endif
            </a>

            <a href="{{ route('reports.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('reports.*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
                Relatórios
                @if(request()->routeIs('reports.*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-kvteal shadow-sm shadow-kvteal/50"></span>
                @endif
            </a>

            <div class="pt-6 pb-1">
                <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/20">Categorias</p>
            </div>

            @auth
                @php
                    $sidebarCategories = auth()->user()->categories()->withCount(['tasks' => fn($q) => $q->where('status', '!=', 'concluido')])->get();
                @endphp
            @endauth
            @foreach($sidebarCategories ?? [] as $cat)
                @php $c = \App\Models\Category::COLORS[$cat->color] ?? ['dot' => 'bg-slate-400']; @endphp
                <a href="{{ route('tasks.index', ['category' => $cat->id]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-white/40 hover:bg-white/5 hover:text-white transition-all duration-200 text-sm group">
                    <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }} shadow-sm shrink-0"></span>
                    <span class="flex-1">{{ $cat->name }}</span>
                    @if($cat->tasks_count > 0)
                        <span class="text-[10px] font-bold text-white/30 group-hover:text-white/50 transition-colors">{{ $cat->tasks_count }}</span>
                    @endif
                </a>
            @endforeach
            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-white/30 hover:bg-white/5 hover:text-white/60 transition-all duration-200 text-xs mt-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                Gerenciar categorias
            </a>

            <div class="pt-6 pb-1">
                <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/20">Configurações</p>
            </div>

            <a href="{{ route('settings.telegram') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm
                      {{ request()->routeIs('settings.telegram*') ? 'bg-white/10 text-white font-semibold shadow-sm' : 'text-white/50 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                Telegram
                @if(request()->routeIs('settings.telegram*'))
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-sky-400 shadow-sm shadow-sky-400/50"></span>
                @endif
            </a>
        </nav>

        {{-- NOVA TAREFA RÁPIDA --}}
        <div class="px-3 py-2">
            <a href="{{ route('tasks.create') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-kvteal hover:text-white hover:bg-kvteal/20 transition-all duration-200 border border-kvteal/20 hover:border-kvteal/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nova tarefa
            </a>
        </div>

        {{-- SAIR --}}
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

    {{-- CONTEÚDO PRINCIPAL --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- TOPBAR --}}
        <header class="bg-white/70 dark:bg-gray-900/80 backdrop-blur-xl border-b border-slate-200/50 dark:border-gray-800/50 px-4 md:px-8 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm shadow-slate-200/30 dark:shadow-black/10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-kvnavy dark:text-white tracking-tight">@yield('heading', 'Visão geral')</h1>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium tracking-wide">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                {{-- RELÓGIO DIGITAL --}}
                <span x-data="{ now: new Date() }" x-init="setInterval(() => now = new Date(), 1000)"
                      x-text="now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })"
                      class="text-sm font-mono font-medium tabular-nums text-slate-400 dark:text-slate-500 min-w-[3.5rem] text-center"></span>
                {{-- DARK MODE TOGGLE --}}
                <button @click="dark = !dark"
                        class="w-9 h-9 rounded-xl bg-slate-100/80 dark:bg-gray-800 hover:bg-slate-200/80 dark:hover:bg-gray-700 flex items-center justify-center transition-all">
                    <template x-if="!dark">
                        <svg class="w-[18px] h-[18px] text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    </template>
                    <template x-if="dark">
                        <svg class="w-[18px] h-[18px] text-amber-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    </template>
                </button>
                {{-- NOTIFICAÇÕES --}}
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                            class="relative w-9 h-9 rounded-xl bg-slate-100/80 dark:bg-gray-800 hover:bg-slate-200/80 dark:hover:bg-gray-700 flex items-center justify-center transition-all">
                        <svg class="w-[18px] h-[18px] text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        @auth
                            @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                            @if($unread > 0)
                                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-gradient-to-br from-kvteal to-kvteal-dark text-white text-[10px] font-bold flex items-center justify-center shadow-sm shadow-kvteal/30">{{ $unread }}</span>
                            @endif
                        @endauth
                    </button>
                    <div x-show="notifOpen" x-cloak
                         class="dropdown-animate absolute right-0 mt-2.5 w-80 bg-white dark:bg-gray-900 border border-slate-200/70 dark:border-gray-700/50 rounded-xl shadow-xl shadow-slate-200/50 dark:shadow-black/30 overflow-hidden z-20">
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-gray-800 flex items-center justify-between bg-gradient-to-r from-kvteal/5 to-transparent">
                            <span class="font-semibold text-sm text-kvnavy dark:text-white">Notificações</span>
                            <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-kvteal hover:text-kvteal-dark transition-colors">Ver todas</a>
                        </div>
                        <div class="max-h-72 overflow-y-auto scrollbar-thin">
                            @auth
                                @forelse(auth()->user()->unreadNotifications()->limit(5)->get() as $n)
                                    <a href="{{ route('notifications.redirect', $n->id) }}" class="block px-4 py-3 text-sm border-b border-slate-50 dark:border-gray-800 hover:bg-slate-50/80 dark:hover:bg-gray-800/50 transition-colors">
                                        <p class="text-slate-700 dark:text-slate-200 font-medium">{{ $n->data['message'] ?? 'Nova notificação' }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                        </div>
                                        <p class="text-sm text-slate-400 dark:text-slate-400 font-medium">Tudo em dia!</p>
                                        <p class="text-xs text-slate-300 dark:text-slate-500 mt-0.5">Nenhuma notificação nova</p>
                                    </div>
                                @endforelse
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- TIMER ATIVO --}}
                <div x-data="topTimer()" x-init="init()" x-show="active" x-cloak
                     class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200/50 dark:border-amber-800/30">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <a x-bind:href="'/tasks/' + taskId" class="text-xs font-semibold text-amber-700 dark:text-amber-300 hover:text-kvteal transition-colors truncate max-w-[120px]" x-text="taskTitle"></a>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 tabular-nums" x-text="elapsed"></span>
                </div>

                {{-- BOTÃO NOVO --}}
                <a href="{{ route('tasks.create') }}"
                   class="bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm shadow-kvteal/20 hover:shadow-md hover:shadow-kvteal/30 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span class="hidden sm:inline">Nova tarefa</span>
                </a>
            </div>
        </header>

        {{-- CONTEÚDO --}}
        <main class="flex-1 p-4 md:p-8">
            {{-- ALERTAS --}}
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

{{-- FLOATING QUICK-ADD --}}
<div x-data="{ qa: false, title: '', cat_id: '', priority: 'media', due: '', desc: '', saving: false, error: '' }"
     x-init="document.addEventListener('keydown', e => { if ((e.ctrlKey || e.metaKey) && e.key === 'n') { e.preventDefault(); qa = true } })">
    {{-- FAB --}}
    <button @click="qa = true"
            class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-2xl bg-gradient-to-br from-kvteal to-kvteal-dark text-white shadow-xl shadow-kvteal/30 hover:shadow-2xl hover:shadow-kvteal/40 hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    </button>
    {{-- MODAL --}}
    <div x-show="qa" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="qa = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl border border-slate-200/70 dark:border-gray-700/50 shadow-xl w-full max-w-md"
             @click.outside="qa = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="scale-100 opacity-100"
             x-transition:leave-end="scale-95 opacity-0">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-gray-800">
                <h3 class="font-bold text-kvnavy dark:text-white">Nova tarefa rápida</h3>
                <button @click="qa = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="
                saving = true; error = '';
                fetch('{{ route('tasks.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.content || '', 'Accept': 'application/json' },
                    body: JSON.stringify({ title, category_id: cat_id, priority, due_date: due, due_time: '23:59', description: desc })
                }).then(r => {
                    if (!r.ok) return r.json().then(e => { throw new Error(Object.values(e.errors || {}).flat().join(', ') || 'Erro ao criar') });
                    return r.json();
                }).then(data => {
                    window.location.href = '{{ url('tasks') }}/' + data.task.id;
                }).catch(e => { error = e.message; saving = false; })
            " class="p-5 space-y-4">
                <div>
                    <input type="text" x-model="title" required placeholder="Título da tarefa..."
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <select x-model="cat_id" required
                                class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none dark:text-white">
                            <option value="">Categoria</option>
                            @auth
                                @foreach(auth()->user()->categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            @endauth
                        </select>
                    </div>
                    <div>
                        <select x-model="priority"
                                class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-3 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none dark:text-white">
                            @foreach(\App\Models\Task::PRIORITIES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <input type="date" x-model="due" required
                           class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none dark:text-white [&::-webkit-calendar-picker-indicator]:dark:invert">
                </div>
                <div>
                    <textarea x-model="desc" rows="2" placeholder="Descrição (opcional)..."
                              class="w-full border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 text-sm bg-slate-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-800 focus:border-kvteal focus:ring-2 focus:ring-kvteal/20 transition-all outline-none dark:text-white resize-none"></textarea>
                </div>
                <p x-show="error" x-text="error" class="text-xs text-red-400"></p>
                <div class="flex items-center justify-end gap-3 pt-1">
                    <button type="button" @click="qa = false"
                            class="text-sm font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Cancelar</button>
                    <button type="submit"
                            class="text-sm font-semibold text-white bg-gradient-to-r from-kvteal to-kvteal-dark hover:from-[#0fa8b3] hover:to-[#0fa8b3] px-5 py-2.5 rounded-xl transition-all shadow-sm disabled:opacity-50"
                            :disabled="saving || !title || !cat_id || !due">
                        <span x-show="!saving">Criar tarefa</span>
                        <span x-show="saving">Criando…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- BUSCA RÁPIDA (Ctrl+K) --}}
<div x-cloak x-show="search" @keydown.window.escape="search = false"
     class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh] px-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="search = false"></div>
    <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-2xl shadow-black/20 border border-slate-200/70 dark:border-gray-700/50 overflow-hidden">
        <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 dark:border-gray-800">
            <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input id="search-input" type="text" x-model="searchQuery"
                   placeholder="Buscar tarefas..."
                   class="flex-1 bg-transparent border-0 outline-none text-sm text-slate-700 dark:text-slate-200 placeholder:text-slate-400"
                   @keydown.arrow-down.prevent="searchIndex = Math.min(searchIndex + 1, searchResults.length - 1)"
                   @keydown.arrow-up.prevent="searchIndex = Math.max(searchIndex - 1, 0)"
                   @keydown.enter.prevent="searchResults[searchIndex] && (window.location = '{{ url('tasks') }}/'+searchResults[searchIndex].id)">
            <span class="text-[10px] font-medium text-slate-300 dark:text-slate-600 bg-slate-100 dark:bg-gray-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-gray-700">ESC</span>
        </div>
        <div class="max-h-80 overflow-y-auto">
            <template x-if="searchLoading">
                <div class="px-4 py-6 text-center text-sm text-slate-400">Buscando…</div>
            </template>
            <template x-if="!searchLoading && searchQuery.length > 0 && searchResults.length === 0">
                <div class="px-4 py-8 text-center">
                    <p class="text-sm text-slate-400 font-medium">Nenhuma tarefa encontrada</p>
                    <p class="text-xs text-slate-300 dark:text-slate-600 mt-1">Tente outros termos</p>
                </div>
            </template>
                <template x-for="(r, i) in searchResults" :key="r.id">
                <a :href="'{{ url('tasks') }}/'+r.id"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition-colors border-b border-slate-50 dark:border-gray-800/50 last:border-0"
                   :class="{ 'bg-kvteal/5 dark:bg-kvteal/10': i === searchIndex }">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate" x-text="r.title"></p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full"
                                   :class="r.cat ? 'bg-'+({blue:'blue',amber:'amber',purple:'purple',emerald:'emerald',red:'red',pink:'pink',indigo:'indigo',rose:'rose'}[r.cat.color]||'slate')+'-100 dark:bg-'+({blue:'blue',amber:'amber',purple:'purple',emerald:'emerald',red:'red',pink:'pink',indigo:'indigo',rose:'rose'}[r.cat.color]||'slate')+'-900/30 text-'+({blue:'blue',amber:'amber',purple:'purple',emerald:'emerald',red:'red',pink:'pink',indigo:'indigo',rose:'rose'}[r.cat.color]||'slate')+'-600 dark:text-'+({blue:'blue',amber:'amber',purple:'purple',emerald:'emerald',red:'red',pink:'pink',indigo:'indigo',rose:'rose'}[r.cat.color]||'slate')+'-400' : 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-slate-400'"
                                   x-text="r.cat?.name || 'Sem categoria'"></span>
                            <span class="text-[11px] text-slate-400" x-text="r.due_date ? new Date(r.due_date).toLocaleDateString('pt-BR') : '—'"></span>
                            <span x-show="r.project_id" class="text-[11px] text-kvteal">· Projeto</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </template>
        </div>
    </div>
</div>
@stack('scripts')

<script>
function topTimer() {
    return {
        active: false,
        taskId: null,
        taskTitle: '',
        elapsed: '00:00',
        interval: null,
        init() {
            this.check();
            setInterval(() => this.check(), 10000);
        },
        check() {
            fetch('/tasks/timer/status', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            }).then(r => r.json()).then(data => {
                if (data.active) {
                    const wasActive = this.active;
                    this.active = true;
                    this.taskId = data.entry.task.id;
                    this.taskTitle = data.entry.task.title;
                    if (!wasActive) {
                        this.elapsed = this.formatTime(data.entry.elapsed_seconds);
                        this.startCounter();
                    }
                } else {
                    this.active = false;
                    this.stopCounter();
                }
            }).catch(() => {});
        },
        startCounter() {
            if (this.interval) return;
            let seconds = this.parseTime(this.elapsed);
            this.interval = setInterval(() => {
                seconds++;
                this.elapsed = this.formatTime(seconds);
            }, 1000);
        },
        stopCounter() {
            if (this.interval) { clearInterval(this.interval); this.interval = null; }
        },
        formatTime(s) {
            const m = Math.floor(s / 60);
            const sec = s % 60;
            return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
        },
        parseTime(str) {
            const parts = str.split(':');
            return parseInt(parts[0]) * 60 + parseInt(parts[1]);
        }
    }
}
</script>
</body>
</html>