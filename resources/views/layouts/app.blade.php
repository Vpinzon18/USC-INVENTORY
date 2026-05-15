<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SOMA | USC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('img/logoUSC.png') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-[#f4f7fa]">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-[#1e1e2d] text-white transition-all duration-300 flex flex-col z-20">
    
    <div class="h-20 flex items-center justify-center bg-[#1a1a27] border-b border-gray-700 px-4">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('img/logo_acreditacion.png') }}" 
                 :class="sidebarOpen ? 'h-12 w-auto' : 'h-8 w-auto'" 
                 class="transition-all duration-300 object-contain"
                 alt="Logo USC">
            
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:leave="transition ease-in duration-200" class="flex flex-col">
                <span class="font-bold text-xl tracking-tight text-white leading-tight">SOMA</span>
                <span class="text-[9px] text-blue-400 uppercase font-semibold tracking-tighter">Gestión Tecnológica</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="/" class="flex items-center p-3 rounded-lg hover:bg-[#2b2b40] transition group {{ request()->is('/') ? 'bg-[#2b2b40] text-blue-400' : '' }}">
            <svg class="w-5 h-5 {{ request()->is('/') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="sidebarOpen" class="ml-3 font-medium">Inicio</span>
        </a>

        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-[#2b2b40] transition group {{ request()->routeIs('dashboard') ? 'bg-[#2b2b40] text-blue-400' : '' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span x-show="sidebarOpen" class="ml-3 font-medium">Dashboard</span>
        </a>

        <div x-data="{ open: {{ request()->is('admin/*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-[#2b2b40] transition group">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3 font-medium">Infraestructura</span>
                </div>
                <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            
            <div x-show="open && sidebarOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-1 ml-4 space-y-1">
                
                <a href="{{ route('campuses.index') }}" class="flex items-center p-2 rounded-md text-sm text-gray-400 hover:text-white hover:bg-[#2b2b40] transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Sedes / Convenios
                </a>
                <a href="{{ route('buildings.index') }}" class="flex items-center p-2 rounded-md text-sm {{ request()->routeIs('buildings.*') ? 'text-blue-400 bg-[#2b2b40]' : 'text-gray-400' }} hover:text-white hover:bg-[#2b2b40] transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Bloques / Edificios
                </a>
                <a href="{{ route('rooms.index') }}" class="flex items-center p-2 rounded-md text-sm text-gray-400 hover:text-white hover:bg-[#2b2b40] transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Oficinas / Salones
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->routeIs('maintenances.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-[#2b2b40] transition group">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3 font-medium">Soporte Técnico</span>
                </div>
                <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="open && sidebarOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-1 ml-4 space-y-1">
                
                <a href="{{ route('maintenances.index') }}" class="flex items-center p-2 rounded-md text-sm {{ request()->routeIs('maintenances.index') ? 'text-blue-400 bg-[#2b2b40]' : 'text-gray-400' }} hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Bitácora Global
                </a>
                <a href="{{ route('maintenances.create') }}" class="flex items-center p-2 rounded-md text-sm {{ request()->routeIs('maintenances.create') ? 'text-blue-400 bg-[#2b2b40]' : 'text-gray-400' }} hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Nuevo Registro
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->is('movements/*') || request()->is('assets/*') || request()->is('custodians/*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-[#2b2b40] transition group">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3 font-medium">Gestión de Equipos</span>
                </div>
                <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            
            <div x-show="open && sidebarOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-1 ml-4 space-y-1">
                
                <a href="{{ route('movements.mass.create') }}" class="flex items-center p-2 rounded-md text-sm {{ request()->routeIs('movements.mass.*') ? 'text-blue-400 bg-[#2b2b40]' : 'text-gray-400' }} hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Traslados Masivos
                </a>
                <a href="{{ route('assets.index') }}" class="flex items-center p-2 rounded-md text-sm {{ request()->routeIs('assets.index') ? 'text-blue-400 bg-[#2b2b40]' : 'text-gray-400' }} hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Inventario / HV
                </a>
                <a href="{{ route('custodians.index') }}" class="flex items-center p-2 rounded-md text-sm {{ request()->routeIs('custodians.*') ? 'text-blue-400 bg-[#2b2b40]' : 'text-gray-400' }} hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Responsables
                </a>
            </div>
        </div>

        @if(Auth::user()->role == 1)
        <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-[#2b2b40] transition group {{ request()->routeIs('users.*') ? 'bg-[#2b2b40] text-blue-400' : '' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="sidebarOpen" class="ml-3 font-medium">Gestión de Usuarios</span>
        </a>
        @endif
    </nav>

    <div class="p-4 bg-[#1a1a27] border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center text-red-400 hover:text-red-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="sidebarOpen" class="ml-3 text-xs font-bold uppercase">Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>
        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-600 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <span class="font-medium text-slate-700">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600 border border-blue-200">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>