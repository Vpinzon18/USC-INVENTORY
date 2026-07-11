<x-app-layout>
    <div class="py-4 bg-slate-50 min-h-[calc(100vh-4rem)] font-sans flex flex-col" x-data="campusCreator()" x-cloak>
        <div class="max-w-[1200px] w-[96%] mx-auto space-y-4 flex-1">

            <!-- ENCABEZADO COMPACTO Y CONTROLES -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-col md:flex-row justify-between items-center gap-4 relative overflow-hidden">
                <!-- Línea decorativa izquierda SIGMA -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                <div class="flex items-center gap-4 pl-2 w-full md:w-auto">
                    <!-- Icono Institucional -->
                    <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-extrabold text-lg text-slate-800 tracking-tight leading-none">Registrar Nueva Sede</h2>
                            <span class="bg-blue-50 text-blue-600 text-[9px] px-2 py-0.5 rounded-md font-black uppercase tracking-widest border border-blue-200 flex items-center gap-1 shadow-sm">
                                Nuevo Registro
                            </span>
                        </div>
                        <p class="text-[11px] font-medium text-slate-500 mt-1">
                            Agregue una nueva sede o convenio que hará parte de la infraestructura institucional de SIGMA.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ALERTAS DE VALIDACIÓN GLOBALES Y ELEGANTES -->
            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-start gap-4 animate-fade-in-down">
                    <div class="bg-rose-100 p-2 rounded-lg shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-rose-800 tracking-tight">Atención: No se pudo registrar la sede</h3>
                        <ul class="mt-2 list-disc list-inside text-xs text-rose-700 font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- ARCHITECTURE LAYOUT 70% / 30% -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 relative items-start">
                
                <!-- COLUMNA IZQUIERDA: FORMULARIO (70%) -->
                <div class="lg:col-span-8">
                    <form action="{{ route('campuses.store') }}" method="POST" id="createCampusForm" @submit="isSubmitting = true">
                        @csrf
                        
                        <!-- TARJETA ÚNICA DE FORMULARIO -->
                        <div class="bg-white rounded-2xl shadow-sm border {{ $errors->any() ? 'border-rose-300 ring-1 ring-rose-100' : 'border-slate-200' }} overflow-hidden group transition-all">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Información General
                                </h3>
                            </div>
                            
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-7">
                                
                                <!-- Nombre (Input Grande - Ocupa 2 columnas) -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest">
                                        Nombre de la Sede <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 {{ $errors->has('name') ? 'text-rose-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Ej: Campus Pampalinda, Centro Cali, Hospital Raúl Orejuela, Clínica Santa Bárbara"
                                            class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white bg-slate-50 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'border-slate-200' }}" required autofocus>
                                    </div>
                                    @error('name')
                                        <p class="text-[10px] text-rose-600 mt-1.5 font-bold">{{ $message }}</p>
                                    @enderror
                                    <p class="text-[11px] text-slate-400 mt-2 flex items-start sm:items-center gap-1.5 leading-tight">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Este nombre será utilizado en todo el sistema para identificar la sede.
                                    </p>
                                </div>

                                <!-- Dirección Física -->
                                <div>
                                    <label for="address" class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest">
                                        Dirección Física
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 {{ $errors->has('address') ? 'text-rose-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                        </div>
                                        <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="Ej: Calle 5 #62-00"
                                            class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-400 focus:bg-white bg-slate-50 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm border {{ $errors->has('address') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'border-slate-200' }}">
                                    </div>
                                    @error('address')
                                        <p class="text-[10px] text-rose-600 mt-1.5 font-bold">{{ $message }}</p>
                                    @enderror
                                    <p class="text-[11px] text-slate-400 mt-2 flex items-start sm:items-center gap-1.5 leading-tight">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Ubicación física donde se encuentran los activos tecnológicos.
                                    </p>
                                </div>

                                <!-- Estado Operativo -->
                                <div>
                                    <label for="status" class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest">
                                        Estado Operativo <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 {{ $errors->has('status') ? 'text-rose-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <select name="status" id="status" class="w-full pl-10 pr-8 py-3 rounded-xl text-sm font-bold text-slate-800 focus:bg-white bg-slate-50 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm appearance-none cursor-pointer border {{ $errors->has('status') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'border-slate-200' }}">
                                            <option value="Activa" {{ old('status', 'Activa') == 'Activa' ? 'selected' : '' }}>🟢 Activa</option>
                                            <option value="Inactiva" {{ old('status') == 'Inactiva' ? 'selected' : '' }}>🔴 Inactiva</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
                                    @error('status')
                                        <p class="text-[10px] text-rose-600 mt-1.5 font-bold">{{ $message }}</p>
                                    @enderror
                                    <p class="text-[11px] text-slate-400 mt-2 flex items-start sm:items-center gap-1.5 leading-tight">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Las sedes inactivas no estarán disponibles para nuevas asignaciones.
                                    </p>
                                </div>

                                <!-- BOTONES DE ACCIÓN EN EL FORMULARIO (Sticky Bottom / Mobile friendly) -->
                                <div class="md:col-span-2 mt-2 pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-4">
                                    <a href="{{ route('campuses.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Cancelar
                                    </a>
                                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-200 transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 flex justify-center items-center gap-2">
                                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                            Guardar Sede
                                        </span>
                                        <span x-show="isSubmitting" class="flex items-center gap-2" x-cloak>
                                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Procesando...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- COLUMNA DERECHA: PANEL INFORMATIVO INSTITUCIONAL (30%) -->
                <!-- Se mantiene la estructura 70/30 de la vista Edit para consistencia total -->
                <div class="lg:col-span-4 hidden lg:block">
                    <div class="sticky top-6 space-y-4">
                        
                        <!-- Tarjeta de Guía -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                            <!-- Borde superior azul -->
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-600"></div>

                            <div class="p-6 pt-8">
                                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-700 shrink-0 shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Guía de Registro</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Acerca del Módulo</p>
                                    </div>
                                </div>

                                <div class="space-y-5">
                                    <div class="flex gap-3 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Creación de Nodos</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Al crear esta sede, se habilitará en el sistema para que pueda contener múltiples bloques y oficinas físicas.</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-3 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Disponibilidad Inmediata</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Una vez guardada, estará visible automáticamente en los formularios de traslados y asignación de equipos.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-3 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">Integridad de Datos</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">SIGMA protege las relaciones. Las sedes con infraestructura asignada estarán aseguradas contra eliminaciones accidentales.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SCRIPT ALPINE -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('campusCreator', () => ({
                isSubmitting: false
            }));
        });
    </script>

    <style>
        /* Animación suave para la alerta de errores */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translate3d(0, -10px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.4s ease-out forwards;
        }
    </style>
</x-app-layout>