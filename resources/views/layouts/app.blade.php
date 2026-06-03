<!DOCTYPE html>
<html lang="es" x-data="appLayout()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SOMA | USC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('img/logoUSC.png') }}">

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-slate-50 text-slate-800">
    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen && isMobile"
            x-transition.opacity.duration.300ms
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden">
        </div>

        <aside :class="sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 lg:w-20'"
            class="fixed lg:relative inset-y-0 left-0 z-50 flex flex-col bg-[#1e1e2d] border-r border-gray-800 shadow-2xl lg:shadow-none transition-all duration-300 ease-in-out text-slate-300">

            <div class="h-20 flex items-center justify-between px-5 bg-[#1a1a27] border-b border-gray-800 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden cursor-pointer" @click="if(!sidebarOpen) sidebarOpen = true">
                    <img src="{{ asset('img/logo_acreditacion.png') }}" class="h-10 w-auto shrink-0 object-contain" alt="Logo USC">
                    <div class="flex flex-col whitespace-nowrap transition-all duration-300" :class="sidebarOpen ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-4 hidden'">
                        <span class="font-extrabold text-xl tracking-tight text-white leading-none">SOMA</span>
                        <span class="text-[9px] text-blue-400 uppercase font-black tracking-widest mt-0.5">Gestión Tecnológica</span>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-colors focus:outline-none shrink-0">
                    <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 pb-6 space-y-1.5 overflow-y-auto custom-scrollbar">

                <a href="/" class="flex items-center px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group {{ request()->is('/') ? 'bg-[#2b2b40] text-blue-400' : 'text-gray-400 hover:bg-[#2b2b40] hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->is('/') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Inicio</span>
                </a>

                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-[#2b2b40] text-blue-400' : 'text-gray-400 hover:bg-[#2b2b40] hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Dashboard</span>
                </a>

                @if(in_array(Auth::user()->role, [1, 2]))
                
                <div x-data="{ open: {{ request()->is('admin/*') ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-gray-400 hover:bg-[#2b2b40] hover:text-white transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0 text-gray-500 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Infraestructura</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-90' : ''" class="w-4 h-4 shrink-0 transition-transform duration-200 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="pl-6 pr-2 space-y-1 mt-1">
                        <a href="{{ route('campuses.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('campuses.*') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('campuses.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Sedes / Convenios
                        </a>
                        <a href="{{ route('buildings.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('buildings.*') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('buildings.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Bloques / Edificios
                        </a>
                        <a href="{{ route('rooms.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('rooms.*') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('rooms.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Oficinas / Salones
                        </a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('maintenances.*') || request()->routeIs('schedules.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-gray-400 hover:bg-[#2b2b40] hover:text-white transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0 text-gray-500 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Soporte Técnico</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-90' : ''" class="w-4 h-4 shrink-0 transition-transform duration-200 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="pl-6 pr-2 space-y-1 mt-1">
                        <a href="{{ route('maintenances.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('maintenances.index') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('maintenances.index') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Bitácora Global
                        </a>
                        <a href="{{ route('maintenances.create') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('maintenances.create') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('maintenances.create') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Nuevo Registro
                        </a>
                        <a href="{{ route('schedules.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('schedules.*') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('schedules.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Cronograma
                        </a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->is('movements/*') || request()->is('assets/*') || request()->is('custodians/*') ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold text-gray-400 hover:bg-[#2b2b40] hover:text-white transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0 text-gray-500 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Gestión Equipos</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-90' : ''" class="w-4 h-4 shrink-0 transition-transform duration-200 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="pl-6 pr-2 space-y-1 mt-1">
                        <a href="{{ route('movements.mass.create') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('movements.mass.*') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('movements.mass.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            Traslados Masivos
                        </a>
                        <a href="{{ route('assets.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('assets.index') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('assets.index') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                            Inventario / HV
                        </a>
                        <a href="{{ route('custodians.index') }}" class="flex items-center py-2 px-2 rounded-lg text-sm font-medium {{ request()->routeIs('custodians.*') ? 'text-blue-400' : 'text-gray-400 hover:text-white hover:bg-[#2b2b40]' }} transition-colors group">
                            <svg class="w-4 h-4 mr-3 shrink-0 {{ request()->routeIs('custodians.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            Responsables
                        </a>
                    </div>
                </div>
                @endif

                @if(Auth::user()->role == 1)
                <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2.5 rounded-xl font-semibold transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-[#2b2b40] text-blue-400' : 'text-gray-400 hover:bg-[#2b2b40] hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('users.*') ? 'text-blue-400' : 'text-gray-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-3 whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Gestión Usuarios</span>
                </a>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-800 bg-[#1a1a27] shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center p-3 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-colors font-semibold group">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3 text-sm whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0'">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden relative bg-slate-50">

            <header class="h-20 bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 transition-colors duration-300">

                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 focus:outline-none transition-colors lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="hidden lg:block"></div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-1.5 pr-4 rounded-full bg-slate-50 hover:bg-blue-50 border border-slate-200 transition-all group">
                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm select-none group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-right hidden sm:flex flex-col justify-center">
                        <span class="font-bold text-sm text-slate-800 leading-none group-hover:text-blue-600 transition-colors">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest mt-1 leading-none">
                            @if(Auth::user()->role == 1) Administrador @elseif(Auth::user()->role == 2) Técnico @else Usuario @endif
                        </span>
                    </div>
                </a>
            </header>

            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('appLayout', () => ({
                sidebarOpen: window.innerWidth >= 1024,
                isMobile: window.innerWidth < 1024,

                init() {
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 1024;
                        if (!this.isMobile && !this.sidebarOpen) {
                            this.sidebarOpen = false; 
                        } else if (this.isMobile) {
                            this.sidebarOpen = false; 
                        }
                    });
                }
            }))
        })
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: transparent;
            border-radius: 20px;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background-color: #475569; /* slate-600 */
        }
    </style>
</body>
</html>