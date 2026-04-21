<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Inventario - ADSI</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen selection:bg-blue-500 selection:text-white">
            
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Cerrar Sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Registrarse</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Panel de Control de Inventario</h1>
                
                @auth
                    <p class="text-lg text-gray-600 mb-12">Bienvenido, {{ Auth::user()->name }}. Seleccione el módulo al que desea acceder:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <a href="{{ route('dashboard') }}" class="group block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-blue-50 transition duration-300">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Dashboard de Inventario</h5>
                            <p class="font-normal text-gray-700">Monitorea en tiempo real los equipos, IPs y hardware detectado por el agente.</p>
                        </a>

                        @if(Auth::user()->role == 1)
                        <a href="{{ route('users.index') }}" class="group block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-green-50 transition duration-300">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Gestión de Usuarios</h5>
                            <p class="font-normal text-gray-700">Administra los accesos del personal técnico y asigna roles administrativos.</p>
                        </a>
                        @else
                        <div class="block p-6 bg-gray-50 border border-gray-200 rounded-lg shadow opacity-60 cursor-not-allowed">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-gray-400 text-white mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-500">Gestión de Usuarios</h5>
                            <p class="font-normal text-gray-500">Módulo restringido solo para administradores del sistema.</p>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 inline-block mx-auto">
                        <p class="text-blue-700">Por favor, inicie sesión para acceder a los módulos del sistema.</p>
                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="bg-blue-500 text-white px-6 py-2 rounded shadow hover:bg-blue-600 transition">Iniciar Sesión Ahora</a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </body>
</html>