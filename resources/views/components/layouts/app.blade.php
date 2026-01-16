<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - CONOCE Seguros</title>

    <!-- Google Fonts: Plus Jakarta Sans (Premium SaaS Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Palette CONOCE Enterprise */
            --primary: #7B2D3B;
            --primary-hover: #5A1F2C;
            --primary-dark: #3D1520;
            --secondary: #C7A172;
            --secondary-light: #E8D5B7;
            --sidebar-width: 280px;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Scrollbar Personalizado */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sidebar Gradient */
        .sidebar-gradient {
            background: linear-gradient(195deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        /* Glassmorphism Header */
        .glass-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>

<body class="h-full overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden"
        @click="sidebarOpen = false"></div>

    <!-- Sidebar Principal -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-[280px] sidebar-gradient text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-2xl">

        <!-- Logo Area -->
        <div class="flex items-center justify-center h-20 border-b border-white/10 px-6">
            <div class="flex items-center gap-3">
                <!-- Logo Fallback / Imagen -->
                <div class="bg-white/10 p-2 rounded-lg">
                    <img src="/logo_conoce.png" alt="CONOCE" class="h-8 w-auto brightness-0 invert">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg leading-none tracking-tight">CONOCE</span>
                    <span class="text-[10px] text-white/50 uppercase tracking-widest mt-1">Cotizador Pro</span>
                </div>
            </div>
            <!-- Close Mobile -->
            <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-white/70 hover:text-white">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">

            <!-- Section Label -->
            <div class="text-xs font-bold text-white/40 uppercase tracking-wider mb-2 px-4 mt-2">Principal</div>

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-lg' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-secondary' : 'text-white/50 group-hover:text-white' }}"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-white/70 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 text-white/50 group-hover:text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="font-medium">Cotizaciones</span>
            </a>

            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-white/70 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 text-white/50 group-hover:text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium">Clientes</span>
            </a>

            <!-- Separator -->
            <div class="my-4 border-t border-white/10"></div>
            <div class="text-xs font-bold text-white/40 uppercase tracking-wider mb-2 px-4">Gestión</div>

            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-white/70 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 text-white/50 group-hover:text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="font-medium">Usuarios</span>
            </a>

            <a href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group text-white/70 hover:bg-white/10 hover:text-white">
                <svg class="w-5 h-5 text-white/50 group-hover:text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="font-medium">Aseguradoras</span>
            </a>

        </nav>

        <!-- User Profile Bottom -->
        <div class="p-4 border-t border-white/10 bg-black/20">
            <div class="flex items-center gap-3 px-2">
                <div
                    class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-primary-dark font-bold text-sm shadow-md border-2 border-white/10">
                    {{ auth()->user()->initials ?? 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="p-2 text-white/50 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
                        title="Cerrar Sesión">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 h-full overflow-hidden w-full">
        <!-- Header -->
        <header class="h-16 glass-header flex items-center justify-between px-6 lg:px-8 z-30 sticky top-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true"
                    class="lg:hidden text-slate-500 hover:text-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden md:flex flex-col">
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
                    @if (isset($subheader))
                        <p class="text-xs text-slate-500 font-medium">{{ $subheader }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <button
                    class="relative p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-full transition-all">
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Content Scrollable -->
        <main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Alpine.js is required -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
