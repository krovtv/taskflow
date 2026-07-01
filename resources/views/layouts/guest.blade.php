<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', 'Acessar') · KV Tech Organizer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>if(localStorage.getItem('theme')==='dark'||(!localStorage.getItem('theme')&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark')</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }, colors: {
            kvnavy: { DEFAULT: '#070821', light: '#0e1238' },
            kvteal: { DEFAULT: '#1ec2cf', dark: '#138a93' },
        } } } }
    </script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .auth-card { animation: fadeInUp 0.6s ease-out both; }
        .auth-logo { animation: fadeInUp 0.5s ease-out both; }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 10px rgba(30, 194, 207, 0.15); }
            50% { box-shadow: 0 0 30px rgba(30, 194, 207, 0.3); }
        }
        .logo-glow { animation: pulse-glow 3s ease-in-out infinite; }
        [x-cloak] { display: none !important; }
        html.dark { color-scheme: dark; }
        html:not(.dark) { color-scheme: light; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-kvnavy via-[#0a0c2c] to-black dark:from-gray-950 dark:via-gray-950 dark:to-gray-950 px-4 font-sans antialiased relative overflow-hidden">
    {{-- BG DECORATIVO --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-kvteal/5 dark:bg-kvteal/[0.03] blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-kvteal/5 dark:bg-kvteal/[0.03] blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-kvteal/[0.02] dark:bg-kvteal/[0.01] blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative">
        <div class="flex flex-col items-center mb-8 auth-logo">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 dark:from-white/5 dark:to-white/[0.02] backdrop-blur-sm flex items-center justify-center mb-4 border border-white/10 dark:border-gray-700/50 shadow-2xl logo-glow p-1">
                <img src="{{ asset('images/logo.svg') }}" alt="KV Tech"
                     class="w-full h-full object-contain">
            </div>
            <h1 class="text-white text-2xl font-extrabold tracking-tight">KV <span class="text-kvteal">TECH</span></h1>
            <p class="text-white/25 dark:text-gray-400 text-xs mt-1.5 font-medium tracking-wide">Organizador de Estudos, Projetos e Trabalho</p>
        </div>

        <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-2xl shadow-2xl shadow-black/30 p-6 md:p-8 border border-white/10 dark:border-gray-700/50 auth-card">
            @yield('content')
        </div>

        <p class="text-center text-white/15 dark:text-gray-500 text-xs mt-6 font-medium tracking-wide">© {{ date('Y') }} KV Tech. Todos os direitos reservados.</p>
    </div>
</body>
</html>