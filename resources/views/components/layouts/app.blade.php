<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Crie Sites Pro - Painel' }}</title>
    
    <!--
        Sem a diretiva de build de assets, de propósito.
        O bundle do Vite não está no repositório (public/build é ignorado pelo
        git) e, em produção, a diretiva lança "Vite manifest not found" — o painel
        não abriria. Como o Tailwind e o Alpine já vêm de CDN logo abaixo, e
        nenhuma view usa lodash nem axios, o bundle era redundante.
        Para voltar a usar: rode `npm run build` e restaure a diretiva.
    -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AlpineJS for Interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Design System Typography: Plus Jakarta Sans for a refined, modern SaaS aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb', // Active blue
                            700: '#1d4ed8',
                        },
                        danger: {
                            500: '#ef4444',
                        },
                        warning: {
                            100: '#ffedd5',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar for a polished feel */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Evita o menu aparecer por um instante antes do Alpine iniciar */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ menu: true }" class="bg-[#F3F4F6] text-slate-800 antialiased h-screen overflow-hidden flex selection:bg-brand-100 selection:text-brand-700">

    <!-- Botão para reabrir o menu: só aparece com a barra recolhida -->
    <button x-cloak x-show="!menu" @click="menu = true"
            type="button" title="Abrir menu" aria-label="Abrir menu"
            class="fixed top-4 left-4 z-30 w-10 h-10 rounded-xl bg-white border border-slate-200 shadow-sm
                   text-slate-500 hover:text-slate-800 grid place-items-center transition-colors cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Sidebar Layout
         Sem x-cloak de propósito: o estado inicial é aberto, então se o Alpine
         não carregar (CDN fora do ar) o menu continua visível em vez de sumir. -->
    <aside x-show="menu"
           class="w-[260px] bg-white border-r border-slate-200 flex flex-col justify-between h-full flex-shrink-0 relative z-20">
        <!-- Top Section -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden pt-4 pb-6 px-4">
            <!-- Header/Logo Area -->
            <div class="flex items-center justify-between mb-6 px-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 font-bold text-xl tracking-tight">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-600 to-cyan-500 text-white flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                            <path d="M6 12.5 16 6l10 6.5v2.2L16 8.2 6 14.7v-2.2Z"/>
                            <path d="M6 19.4 16 12.9l10 6.5v2.2L16 15.1 6 21.6v-2.2Z" opacity=".6"/>
                            <path d="M6 25.8 16 19.3l10 6.5V28L16 21.5 6 28v-2.2Z" opacity=".35"/>
                        </svg>
                    </div>
                    <span class="text-slate-800">Crie Sites <span class="text-brand-600">Pro</span></span>
                </a>
                <button type="button" @click="menu = false"
                        title="Recolher menu" aria-label="Recolher menu"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer p-1 rounded-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Global Search -->
            <div class="mb-6">
                <div class="relative group">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Pesquisar" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-transparent rounded-lg text-sm focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 placeholder-slate-400 transition-all outline-none">
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="space-y-1 mb-8">
                <span class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Menu</span>
                
                <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-brand-600 text-white shadow-sm shadow-brand-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl transition-colors group">
                    <div class="flex items-center gap-3 relative">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-brand-500' }} transition-colors opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="text-sm font-semibold">Dashboard</span>
                    </div>
                </a>

                <a href="{{ route('clientes.index') }}" class="flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('clientes.*') ? 'bg-brand-600 text-white shadow-sm shadow-brand-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->routeIs('clientes.*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-sm font-medium">Clientes</span>
                    </div>
                </a>

                <a href="{{ route('propostas.index') }}" class="flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('propostas.*') ? 'bg-brand-600 text-white shadow-sm shadow-brand-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->routeIs('propostas.*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-medium">Propostas</span>
                    </div>
                </a>

                <a href="/projetos" class="flex items-center justify-between px-3 py-2.5 {{ request()->is('projetos*') ? 'bg-brand-600 text-white shadow-sm shadow-brand-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->is('projetos*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Projetos</span>
                    </div>
                </a>

                <a href="{{ route('faturas.index') }}" class="flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('faturas.*') ? 'bg-brand-600 text-white shadow-sm shadow-brand-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->routeIs('faturas.*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                        <span class="text-sm font-medium">Faturas</span>
                    </div>
                </a>
            </nav>
        </div>

        <!-- Bottom Section -->
        <div class="px-4 pb-4 pt-2 bg-white">
            <!-- User Profile -->
            <div class="flex items-center justify-between gap-2 pt-3 border-t border-slate-100">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=020617&color=fff"
                             alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Sair do painel" aria-label="Sair do painel"
                            class="text-slate-400 hover:text-danger-500 transition-colors p-1 rounded-md hover:bg-danger-50 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative" :class="menu ? '' : 'pt-14'">
        <!-- Content wrapper for scroll -->
        <div class="flex-1 overflow-y-auto w-full p-8">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
