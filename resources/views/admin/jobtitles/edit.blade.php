<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-4 md:py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="jobtitleEditor()" x-cloak>
        <div class="max-w-[1400px] w-full px-4 sm:px-6 lg:px-8 mx-auto space-y-4 md:space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 md:gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 md:w-2.5 bg-indigo-600"></div>

                <div class="flex items-center gap-4 md:gap-5 pl-2">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-xl md:text-2xl text-slate-900 tracking-tight flex flex-wrap items-center gap-2 md:gap-3">
                            Editar Cargo
                            <span class="bg-amber-100 text-amber-700 text-[9px] md:text-[10px] px-2.5 md:px-3 py-1 rounded-full font-bold uppercase tracking-widest shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                MODO EDICIÓN
                            </span>
                        </h2>
                        <p class="text-xs md:text-sm font-medium text-slate-500 mt-1">
                            Actualice la información del cargo institucional utilizado para la clasificación organizacional, asignación de custodios y generación de reportes dentro del sistema SIGMA.
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
                        <h3 class="text-xs md:text-sm font-extrabold text-rose-900 tracking-tight">Se encontraron errores.</h3>
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
                    <form action="{{ route('jobtitles.update', $jobtitle->id) }}" method="POST" id="updateJobtitleForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 relative z-20">
                            
                            <div class="px-5 md:px-8 py-4 md:py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                                <h3 class="text-[11px] md:text-xs font-extrabold text-slate-800 uppercase tracking-widest flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    INFORMACIÓN GENERAL
                                </h3>
                            </div>
                            
                            <div class="p-5 md:p-8 space-y-8">
                                
                                <!-- Campo: Nombre del Cargo -->
                                <div>
                                    <label for="name" class="flex items-center gap-2 text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        Nombre del Cargo <span class="text-rose-500">*</span>
                                    </label>
                                    
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                        </div>
                                        <input id="name" type="text" name="name" value="{{ old('name', $jobtitle->name) }}" required 
                                            placeholder="Ej: Director Administrativo, Ingeniero de Sistemas, Analista de Infraestructura..." 
                                            class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 shadow-inner hover:bg-white transition-all py-3 pl-11 pr-4">
                                    </div>
                                    
                                    <p class="text-[11px] font-medium text-slate-500 mt-2 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        El nombre del cargo será utilizado para la asignación de custodios, generación de reportes, perfiles organizacionales y consultas dentro del sistema SIGMA.
                                    </p>
                                    
                                    @error('name')
                                        <p class="text-[11px] font-bold text-rose-600 mt-2 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            
                            <!-- BOTONES DE ACCIÓN (Footer de Tarjeta) -->
                            <div class="px-5 md:px-8 py-4 md:py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-3 md:gap-4 rounded-b-2xl">
                                <a href="{{ route('jobtitles.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-800 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2 focus:ring-4 focus:ring-slate-100 outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Cancelar
                                </a>
                                
                                <button type="button" @click.prevent="confirmUpdate()" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-lg shadow-indigo-200 hover:shadow-xl transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Actualizar Cargo
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
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Guía de Edición</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">MÓDULO CARGOS</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Impacto Organizacional</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Modificar el nombre del cargo actualizará automáticamente los listados utilizados en custodios, reportes administrativos y procesos de asignación.</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Actualización Inmediata</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Los cambios estarán disponibles inmediatamente en todos los formularios del sistema donde se utilice este cargo.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Última Modificación
                                    </p>
                                    <p class="text-xs font-bold text-slate-600">{{ method_exists($jobtitle, 'updated_at') && $jobtitle->updated_at ? $jobtitle->updated_at->format('d M Y - h:i A') : 'Fecha no registrada' }}</p>
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
            Alpine.data('jobtitleEditor', () => ({
                isSubmitting: false,

                confirmUpdate() {
                    const form = document.getElementById('updateJobtitleForm');
                    
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar actualización?',
                        html: `Está a punto de modificar este cargo en el sistema SIGMA.<br><br><span class="text-sm font-medium text-slate-500">Los cambios se verán reflejados en todos los registros vinculados al mismo.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5', // Indigo-600
                        cancelButtonColor: '#f1f5f9', // Slate-100
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Sí, actualizar</span>',
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