<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventario IT - Dark Mode</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="antialiased font-sans bg-slate-950 text-slate-200">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <div class="hidden md:flex md:w-2/3 bg-slate-900 items-center justify-center p-12 border-r border-slate-800 shadow-2xl">
            <div class="text-center space-y-8 max-w-2xl">
                
                <div class="flex items-center justify-center space-x-8">
                    <img src="{{ asset('img/logo_acreditacion.png') }}" alt="Acreditación" class="h-48 brightness-110 drop-shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                    
                    <div class="h-32 border-l-2 border-slate-700"></div>
                    
                    <div class="text-left">
                        <h1 class="text-7xl font-black tracking-tighter text-red-600 leading-none drop-shadow-md">INVENTARIO</h1>
                        <p class="text-xl font-light text-slate-400 uppercase tracking-[0.2em] mt-2">Gestión Tecnológica</p>
                    </div>
                </div>
                
                <div class="pt-10 border-t border-slate-800">
                    <p class="text-slate-500 font-semibold tracking-wide uppercase text-sm">
                        Sistema para el Control y Aseguramiento de Activos Tecnológicos
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col justify-center py-12 px-8 sm:px-12 lg:px-24 bg-slate-950">
            <div class="mx-auto w-full max-w-sm">
                
                <div class="mb-10 flex justify-center">
                    <img src="{{ asset('img/logoUSC.png') }}" alt="USC Logo" class="h-32 brightness-300 contrast-125">
                </div>

                <div class="bg-slate-900 p-8 rounded-2xl shadow-2xl border border-slate-800">
                    <h2 class="text-2xl font-bold text-white mb-6 border-b-2 border-red-700 pb-2 inline-block">
                        Iniciar sesión
                    </h2>
                    
                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                                    <i class="fas fa-user text-xs"></i>
                                </span>
                                <input type="email" name="email" :value="old('email')" required autofocus
                                    class="block w-full pl-10 pr-3 py-3 bg-slate-950 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-600 focus:ring-2 focus:ring-red-700 focus:border-transparent transition duration-200 text-sm" 
                                    placeholder="Usuario o Correo Institucional">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
                        </div>

                        <div>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                                    <i class="fas fa-lock text-xs"></i>
                                </span>
                                <input type="password" name="password" required 
                                    class="block w-full pl-10 pr-3 py-3 bg-slate-950 border border-slate-700 rounded-xl text-slate-200 placeholder-slate-600 focus:ring-2 focus:ring-red-700 focus:border-transparent transition duration-200 text-sm" 
                                    placeholder="Contraseña">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-400">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-950 text-red-700 focus:ring-offset-slate-900">
                                <span class="ml-2 group-hover:text-slate-200 transition">Recordarme</span>
                            </label>
                            <a href="#" class="hover:text-red-500 transition">¿Olvidó su contraseña?</a>
                        </div>

                        <button type="submit" 
                            class="w-full py-3 px-4 bg-red-700 hover:bg-red-800 text-white font-bold rounded-xl transition duration-300 shadow-lg shadow-red-900/20 flex items-center justify-center space-x-2 uppercase text-xs tracking-widest">
                            <span>Ingresar al panel</span>
                            <i class="fas fa-sign-in-alt"></i>
                        </button>
                    </form>
                </div>

                <div class="mt-12 text-center text-[10px] text-slate-600 uppercase tracking-[0.3em] leading-relaxed">
                    © Universidad Santiago de Cali <br>
                    <span class="text-slate-700 font-black">Transformación & Buen Gobierno</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>