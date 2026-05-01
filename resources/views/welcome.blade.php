<x-app-layout>
    <!-- Contenedor que ocupa el 100% del espacio restante al lado del sidebar -->
    <div class="flex flex-col min-h-screen w-full bg-[#] items-center justify-center p-6 md:p-12">
        
        <div class="text-center space-y-10 max-w-4xl w-full">
            
            <!-- Bloque de Logos e Identidad de SOMA -->
            <div class="flex flex-col md:flex-row items-center justify-center space-y-8 md:space-y-0 md:space-x-12">
                
                <!-- Logo USC -->
                <img src="{{ asset('img/logo_acreditacion.png') }}" 
                     alt="Acreditación USC" 
                     class="h-40 md:h-56 brightness-110 drop-shadow-[0_0_20px_rgba(255,255,255,0.15)] object-contain">
                
                <!-- Línea Divisora -->
                <div class="hidden md:block h-40 border-l-2 border-slate-700"></div>
                
                <!-- Identidad SOMA -->
                <div class="text-center md:text-left">
                    <h1 class="text-7xl md:text-9xl font-black tracking-tighter text-[#e11d48] leading-none drop-shadow-lg uppercase">
                        SOMA
                    </h1>
                    <p class="text-xl md:text-2xl font-light text-slate-400 uppercase tracking-[0.25em] mt-3">
                        Gestión Tecnológica - Palmira
                    </p>
                </div>
            </div>
            
            <!-- Frase de Aseguramiento Oficial -->
            <div class="pt-12 border-t border-slate-800/60 w-full text-center">
                <p class="text-slate-400 font-medium tracking-[0.15em] uppercase text-sm md:text-base opacity-80">
                    Sistema de Organización y Manejo de Activos Tecnológicos
                </p>
            </div>
            
        </div>
    </div>
</x-app-layout>