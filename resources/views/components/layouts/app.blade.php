<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SaleHunt - Propostas' }}</title>
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- TailwindCSS for preview/standalone execution (Remove if compiling via Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
    </style>
</head>
<body class="bg-[#F3F4F6] text-slate-800 antialiased h-screen overflow-hidden flex selection:bg-brand-100 selection:text-brand-700">

    <!-- Sidebar Layout -->
    <aside class="w-[260px] bg-white border-r border-slate-200 flex flex-col justify-between h-full flex-shrink-0 relative z-20">
        <!-- Top Section -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden pt-4 pb-6 px-4">
            <!-- Header/Logo Area -->
            <div class="flex items-center justify-between mb-6 px-2">
                <a href="/" class="flex items-center gap-2 text-brand-600 font-bold text-xl tracking-tight">
                    <div class="w-8 h-8 bg-brand-600 text-white rounded-lg flex items-center justify-center font-black">
                        <!-- Custom icon approximating the image logo -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    SaleHunt
                </a>
                <button class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Workspace Selector -->
            <div class="mb-5">
                <span class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Workspace</span>
                <button class="w-full flex items-center justify-between px-3 py-2 border border-slate-200 rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-all bg-white shadow-sm group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 bg-slate-900 rounded-md flex items-center justify-center text-white text-[10px] font-bold">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Studio Pipple</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
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
                
                <a href="/" class="flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('propostas.*') ? '' : 'bg-brand-600 text-white shadow-sm shadow-brand-200' }} text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors group">
                    <div class="flex items-center gap-3 relative">
                        <svg class="w-5 h-5 opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="text-sm font-semibold">Dashboard</span>
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

                <a href="{{ route('clientes.index') }}" class="flex items-center justify-between px-3 py-2.5 {{ request()->routeIs('clientes.*') ? 'bg-brand-600 text-white shadow-sm shadow-brand-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} rounded-xl transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->routeIs('clientes.*') ? 'text-white' : 'text-slate-400 group-hover:text-brand-500' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-sm font-medium">Clientes</span>
                    </div>
                </a>

                <a href="#" class="flex items-center justify-between px-3 py-2.5 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm font-medium">Automações</span>
                    </div>
                    <span class="bg-warning-100 text-warning-600 text-[9px] font-bold px-1.5 py-0.5 rounded uppercase flex items-center tracking-wider">Em breve</span>
                </a>
            </nav>

            <!-- Resources Navigation -->
            <nav class="space-y-1">
                <span class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Recursos</span>
                
                <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium">Assinatura</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0015.086 3H15m-4 1h4m-4 5h4m-4 5h4" />
                    </svg>
                    <span class="text-sm font-medium">Novidades</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-xl transition-colors group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">Enviar sugestão</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Section -->
        <div class="px-4 pb-4 pt-2 bg-white">
            <!-- Upgrade Banner -->
            <div class="mb-4 bg-brand-50 border border-brand-100 rounded-xl p-3 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-1">
                    <button class="text-brand-300 hover:text-brand-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <h4 class="text-sm font-bold text-slate-800 mb-1">Plano free</h4>
                <p class="text-[11px] text-slate-500 mb-2 leading-relaxed">Seu teste irá expirar em breve.</p>
                <a href="#" class="text-[12px] font-semibold text-brand-600 hover:text-brand-700 hover:underline">Atualizar plano</a>
            </div>

            <!-- Footer Links -->
            <div class="space-y-0.5 mb-4">
                <a href="#" class="flex items-center gap-3 px-2 py-1.5 text-slate-500 hover:text-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"/></svg>
                    <span class="text-xs font-medium">Reportar Bug</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-2 py-1.5 text-slate-500 hover:text-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-xs font-medium">Suporte</span>
                </a>
            </div>

            <!-- User Profile -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                        <img src="https://ui-avatars.com/api/?name=Lukas+Lemos&background=020617&color=fff" alt="Lukas Lemos" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-800 group-hover:text-brand-600 transition-colors">Lukas Lemos</span>
                        <span class="text-[10px] text-slate-500 truncate w-32">lukas@pipple.com.br</span>
                    </div>
                </div>
                <button class="text-slate-400 hover:text-danger-500 transition-colors p-1 rounded-md hover:bg-danger-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Content wrapper for scroll -->
        <div class="flex-1 overflow-y-auto w-full p-8">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
