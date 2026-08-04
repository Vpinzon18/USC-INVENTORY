<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-4 md:py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="dependencyCreator()" x-cloak>
        <div class="max-w-[1400px] w-full px-4 sm:px-6 lg:px-8 mx-auto space-y-4 md:space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 md:gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 md:w-2.5 bg-indigo-600"></div>

                <div class="flex items-center gap-4 md:gap-5 pl-2">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-xl md:text-2xl text-slate-900 tracking-tight flex flex-wrap items-center gap-2 md:gap-3">
                            Registrar Nueva Dependencia
                            <span class="bg-indigo-50 text-indigo-700 text-[9px] md:text-[10px] px-2.5 md:px-3 py-1 rounded-full font-bold uppercase tracking-widest shadow-sm border border-indigo-200 whitespace-nowrap">
                                Nuevo Registro
                            </span>
                        </h2>
                        <p class="text-xs md:text-sm font-medium text-slate-500 mt-1">
                            Agregue una nueva dependencia institucional que posteriormente podrá ser utilizada en la asignación de oficinas, custodios y activos tecnológicos.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ALERTAS DE VALIDACIÓN ELEGANTES -->
            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-600 p-4 md:p-5 rounded-r-2xl shadow-sm flex items-start gap-4 animate-fade-in-down">
                    <div class="bg-white p-2 rounded-xl shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs md:text-sm font-extrabold text-rose-900 tracking-tight">Se encontraron errores</h3>
                        <ul class="mt-2 list-disc list-inside text-xs text-rose-700 font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- ARQUITECTURA 70/30 (FORMULARIO Y GUÍA) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 md:gap-6 relative items-start">
                
                <!-- COLUMNA IZQUIERDA: FORMULARIO (70%) -->
                <div class="lg:col-span-8">
                    <form action="{{ route('dependencies.store') }}" method="POST" id="createDependencyForm" class="space-y-6">
                        @csrf
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 relative z-20">
                            
                            <div class="px-5 md:px-8 py-4 md:py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                                <h3 class="text-[11px] md:text-xs font-extrabold text-slate-800 uppercase tracking-widest flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Información General
                                </h3>
                            </div>
                            
                            <div class="p-5 md:p-8 space-y-8">
                                
                                <!-- Campo: Nombre de la Dependencia -->
                                <div>
                                    <label for="name" class="flex items-center gap-2 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Nombre de la Dependencia <span class="text-rose-500">*</span>
                                    </label>
                                    
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                        </div>
                                        <input id="name" type="text" name="name" value="{{ old('name') }}" required 
                                            placeholder="Ej: Dirección Administrativa, Bienestar Universitario..." 
                                            class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 shadow-inner hover:bg-white transition-all py-3 pl-11 pr-4">
                                    </div>
                                    
                                    <p class="text-[11px] font-medium text-slate-500 mt-2 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Este nombre estará disponible en los diferentes módulos del sistema para clasificar oficinas, custodios y activos tecnológicos.
                                    </p>
                                    
                                    @error('name')
                                        <p class="text-[11px] font-bold text-rose-600 mt-2 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            
                            <!-- BOTONES DE ACCIÓN (Footer de Tarjeta) -->
                            <div class="px-5 md:px-8 py-4 md:py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-3 md:gap-4 rounded-b-2xl">
                                <a href="{{ route('dependencies.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-800 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2 focus:ring-4 focus:ring-slate-100 outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Cancelar
                                </a>
                                
                                <button type="button" @click.prevent="confirmCreate()" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-md shadow-indigo-200 transition-all active:scale-95 focus:outline-none focus:ring-4 focus:ring-indigo-200 flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Guardar Dependencia
                                    </span>
                                    <span x-show="isSubmitting" class="flex items-center gap-2" x-cloak>
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Guardando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- COLUMNA DERECHA: GUÍA CORPORATIVA (30%) -->
                <div class="lg:col-span-4 hidden lg:block">
                    <div class="sticky top-6 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                            <!-- Borde superior de color -->
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-indigo-600"></div>

                            <div class="p-6 pt-8">
                                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-700 shrink-0 shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Guía de Registro</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Módulo Dependencias</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Estructura Organizacional</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Las dependencias son el pilar para agrupar custodios, oficinas y centros de costo de manera organizada.</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Disponibilidad Inmediata</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Una vez guardada, esta dependencia aparecerá disponible para la creación de nuevos registros en todo el ecosistema.</p>
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

    <!-- SCRIPT ALPINEJS + SWEETALERT2 -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dependencyCreator', () => ({
                isSubmitting: false,

                confirmCreate() {
                    const form = document.getElementById('createDependencyForm');
                    
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar registro?',
                        html: `Está a punto de crear una nueva dependencia en el sistema SIGMA.<br><br><span class="text-sm font-medium text-slate-500">Esta opción estará disponible globalmente al registrar nuevos activos o responsables.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5', // Indigo-600 Institucional
                        cancelButtonColor: '#f1f5f9', // Slate-100 para cancelar
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Sí, registrar</span>',
                        cancelButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-slate-700">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95 focus:ring-4 focus:ring-indigo-200 outline-none',
                            cancelButton: 'rounded-xl px-6 py-2.5 border border-slate-200 transition-all hover:bg-slate-200 mr-3 focus:ring-4 focus:ring-slate-200 outline-none'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.isSubmitting = true;
                            form.submit();
                        }
                    });
                }
            }));
        });
    </script>
</x-app-layout>