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
        
        <div x-show="sidebarOpen" class="flex flex-col">
            <span class="font-bold text-xl tracking-tight text-white leading-tight">SOMA</span>
            <span class="text-[9px] text-blue-400 uppercase font-semibold tracking-tighter">Gestión Tecnológica</span>
        </div>
    </div>
</div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="/" class="flex items-center p-3 rounded-lg hover:bg-[#2b2b40] transition group {{ request()->is('/') ? 'bg-[#2b2b40] text-blue-400' : '' }}">
                    <svg class="w-5 h-5 {{ request()->is('/') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
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
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span x-show="sidebarOpen" class="ml-3 font-medium">Infraestructura</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open && sidebarOpen" class="mt-2 ml-8 space-y-1 text-sm text-gray-400">
                    <a href="{{ route('campuses.index') }}" class="block p-2 hover:text-white">Sedes / Convenios </a>
                    <a href="{{ route('buildings.index') }}" class="block p-2 hover:text-white {{ request()->routeIs('buildings.*') ? 'text-blue-400' : '' }}">Bloques / Edificios</a>
                    <a href="{{ route('campuses.index') }}" class="block p-2 hover:text-white">Oficinas / Salones</a>
                    </div>
                </div>

                @if(Auth::user()->role == 1)
                <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-[#2b2b40] transition group {{ request()->routeIs('users.*') ? 'bg-[#2b2b40] text-blue-400' : '' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('users.*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="ml-3 font-medium">Gestión de Usuarios</span>
                </a>
                @endif
            </nav>

            <div class="p-4 bg-[#1a1a27] border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center text-red-400 hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
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