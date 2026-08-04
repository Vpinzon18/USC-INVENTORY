<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="jobtitleCreator()" x-cloak>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- CONTENEDOR PRINCIPAL -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <!-- ENCABEZADO CORPORATIVO -->
                <div class="bg-slate-50 border-b border-slate-200 p-6 md:p-8 flex items-start sm:items-center gap-4 md:gap-5">
                    <!-- Icono (Briefcase / Maletín) -->
                    <div class="bg-indigo-100 text-indigo-600 rounded-xl p-3 shrink-0 shadow-sm border border-indigo-200/50">
                        <svg class="w-7 h-7 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-extrabold tracking-tight text-slate-800 flex items-center gap-2.5">
                            Registrar Nuevo Cargo
                            <!-- Badge de Nuevo Registro -->
                            <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-widest shadow-sm flex items-center gap-1.5 whitespace-nowrap border border-emerald-200">
                                Nuevo Registro
                            </span>
                        </h2>
                        <p class="text-slate-500 text-xs font-medium mt-1.5 leading-relaxed">
                            Registre un nuevo cargo institucional que será utilizado en la asignación de custodios, usuarios y clasificación organizacional dentro del sistema SIGMA.
                        </p>
                    </div>
                </div>

                <!-- VALIDACIONES (Caja elegante roja) -->
                @if ($errors->any())
                    <div class="m-6 md:m-8 mb-0 bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 md:p-5 shadow-sm flex items-start gap-4">
                        <div class="bg-white p-1.5 rounded-lg shrink-0 shadow-sm border border-red-100">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xs md:text-sm font-bold text-red-800">Se encontraron errores</h3>
                            <ul class="mt-1.5 list-disc list-inside text-xs text-red-600 font-medium space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- ARQUITECTURA 70/30 (FORMULARIO Y GUÍA) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 relative items-stretch">
                    
                    <!-- COLUMNA IZQUIERDA: FORMULARIO (70%) -->
                    <div class="lg:col-span-8 border-b lg:border-b-0 lg:border-r border-slate-100">
                        <form action="{{ route('jobtitles.store') }}" method="POST" id="createJobtitleForm" class="flex flex-col h-full">
                            @csrf

                            <div class="p-6 md:p-8 flex-1">
                                <!-- Cabecera del Formulario -->
                                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Información General
                                </h3>

                                <!-- Campo Único: Nombre del Cargo -->
                                <div>
                                    <label for="name" class="block uppercase text-[10px] font-bold tracking-widest text-slate-800 mb-2">
                                        Nombre del Cargo <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                        </div>
                                        <input type="text" name="name" id="name" required
                                               value="{{ old('name') }}"
                                               placeholder="Ej: Director Administrativo, Ingeniero de Sistemas, Coordinador TIC..."
                                               class="w-full bg-slate-50 border border-slate-200 text-sm font-bold text-slate-800 rounded-xl pl-11 pr-4 py-3 shadow-inner placeholder:font-normal placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all outline-none">
                                    </div>
                                    
                                    @error('name')
                                        <p class="text-[11px] font-bold text-red-600 mt-2 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <!-- Texto Informativo / Ayuda -->
                                    <p class="mt-3 text-[11px] text-slate-400 flex items-start gap-1.5 font-medium leading-relaxed">
                                        <svg class="w-4 h-4 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        El nombre del cargo será utilizado en la asignación de custodios, perfiles organizacionales, reportes administrativos y demás procesos internos del sistema SIGMA.
                                    </p>
                                </div>
                            </div>

                            <!-- FOOTER (BOTONES DE ACCIÓN) -->
                            <div class="bg-slate-50 border-t border-slate-100 p-6 md:p-8 flex flex-col-reverse sm:flex-row items-center justify-end gap-4 sm:gap-6 rounded-bl-2xl">
                                <!-- Botón Cancelar -->
                                <a href="{{ route('jobtitles.index') }}" class="w-full sm:w-auto text-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-500 hover:text-slate-800 transition-colors focus:outline-none">
                                    Cancelar
                                </a>

                                <!-- Botón Guardar -->
                                <button type="button" @click.prevent="confirmCreate()" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto flex justify-center items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-md hover:shadow-lg transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                        Guardar Cargo
                                    </span>
                                    <span x-show="isSubmitting" class="flex items-center gap-2" x-cloak>
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Guardando...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- COLUMNA DERECHA: GUÍA DE REGISTRO (30%) -->
                    <div class="lg:col-span-4 bg-slate-50/30 p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                            <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-700 shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Guía de Registro</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">MÓDULO CARGOS</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Bloque 1 -->
                            <div class="flex gap-3.5 items-start group">
                                <div class="mt-0.5 p-1.5 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 tracking-tight">Nuevo Cargo</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">El cargo que registre estará disponible inmediatamente para su selección en los módulos de custodios, usuarios y demás procesos administrativos.</p>
                                </div>
                            </div>

                            <!-- Bloque 2 -->
                            <div class="flex gap-3.5 items-start group">
                                <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 tracking-tight">Buenas Prácticas</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-1.5 leading-relaxed">Utilice nombres claros y únicos para evitar duplicidad de cargos y mantener una estructura organizacional consistente.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer informativo -->
                        <div class="mt-8 pt-5 border-t border-slate-200/60 text-center">
                            <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mb-1.5 flex items-center justify-center gap-1 bg-emerald-50 px-2 py-1 rounded-md w-fit mx-auto border border-emerald-100">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                REGISTRO NUEVO
                            </p>
                            <p class="text-[11px] font-medium text-slate-500">El cargo quedará disponible inmediatamente después de guardar la información.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT ALPINEJS + SWEETALERT2 -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('jobtitleCreator', () => ({
                isSubmitting: false,

                confirmCreate() {
                    const form = document.getElementById('createJobtitleForm');
                    
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar registro?',
                        html: `Está a punto de crear un nuevo cargo en el sistema SIGMA.<br><br><span class="text-sm font-medium text-slate-500">Este cargo estará disponible globalmente en la plataforma.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5', // Indigo-600
                        cancelButtonColor: '#f1f5f9', // Slate-100
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