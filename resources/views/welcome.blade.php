<x-app-layout>
    <div class="flex flex-col items-center justify-center h-full min-h-[calc(100vh-8rem)] w-full">
        
        <div class="text-center space-y-10 max-w-4xl w-full">
            
            <div class="flex flex-col md:flex-row items-center justify-center space-y-8 md:space-y-0 md:space-x-12">
                
                <img src="{{ asset('img/logoUSC.png') }}" 
                     alt="Acreditación USC" 
                     class="h-40 md:h-56 object-contain drop-shadow-md hover:scale-105 transition-transform duration-500">
                
                <div class="hidden md:block h-40 border-l-2 border-slate-300"></div>
                
                <div class="text-center md:text-left">
                    <h1 class="text-7xl md:text-9xl font-black tracking-tighter text-[#e11d48] leading-none drop-shadow-sm uppercase">
                        SIGMA
                    </h1>
                    <p class="text-xl md:text-2xl font-bold text-slate-800 uppercase tracking-[0.2em] mt-3">
                        Gestión Tecnológica
                    </p>
                </div>
            </div>
            
            <div class="pt-12 border-t border-slate-300 w-full text-center">
                <p class="text-slate-700 font-bold tracking-[0.15em] uppercase text-sm md:text-base">
                    Sistema de Informacion Gestión y Manejo de Activos Tecnologicos
                </p>
            </div>
            
        </div>
    </div>
</x-app-layout>