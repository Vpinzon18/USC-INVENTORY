<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        // Resolvemos el nombre inicial de la sede para el componente AlpineJS
        $initialId = old('campus_id', $building->campus_id);
        $initialName = '';
        if ($initialId && isset($campuses)) {
            $campus = collect($campuses)->firstWhere('id', $initialId);
            $initialName = $campus ? $campus->name : '';
        }
    @endphp

    <div class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="buildingEditor('{{ $initialId }}', '{{ addslashes($initialName) }}')" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <!-- Línea decorativa izquierda en tono Azul Institucional -->
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <!-- Icono Institucional del Módulo -->
                    <div class="w-16 h-16 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-3">
                            Editar Bloque
                            <!-- Badge Corporativo de Edición (Amber) -->
                            <span class="bg-amber-100 text-amber-700 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Modo Edición
                            </span>
                        </h2>
                        <p class="text-sm font-medium text-slate-500 mt-1">
                            Actualice el nombre o reasigne este bloque a otra sede institucional.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ALERTAS DE VALIDACIÓN ELEGANTES -->
            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-600 p-5 rounded-r-2xl shadow-sm flex items-start gap-4 animate-fade-in-down">
                    <div class="bg-white p-2 rounded-xl shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-rose-900 tracking-tight">Se encontraron {{ $errors->count() }} errores de validación</h3>
                        <ul class="mt-2 list-disc list-inside text-xs text-rose-700 font-medium space-y-1.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- ARQUITECTURA 70/30 (FORMULARIO Y GUÍA) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative items-start">
                
                <!-- COLUMNA IZQUIERDA: FORMULARIO (70%) -->
                <div class="lg:col-span-8">
                    <form action="{{ route('buildings.update', $building) }}" method="POST" id="updateBuildingForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- TARJETA: INFORMACIÓN GENERAL -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Información General
                                </h3>
                            </div>
                            
                            <div class="p-8 space-y-8">
                                
                                <!-- Campo AJAX: Sede Institucional -->
                                <div>
                                    <label class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Sede a la que pertenece <span class="text-rose-500">*</span>
                                    </label>
                                    
                                    <div class="relative" @click.away="campusOpen = false">
                                        <!-- Input Oculto que guarda el ID real para Laravel -->
                                        <input type="hidden" name="campus_id" id="campus_id" x-model="selectedCampusId" required>
                                        
                                        <!-- Botón Simulador de Select -->
                                        <button type="button" @click="campusOpen = !campusOpen; if(campusOpen && campuses.length === 0) fetchCampuses()"
                                            class="w-full bg-slate-50 border text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 pl-11 pr-10 text-left flex items-center justify-between {{ $errors->has('campus_id') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'border-slate-200 text-slate-800' }}">
                                            
                                            <span x-text="selectedCampusName || 'Seleccione una sede...'" :class="{'text-slate-400 font-normal': !selectedCampusName}"></span>
                                            
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                                <svg class="h-5 w-5 transition-transform duration-200" :class="campusOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </button>
                                        
                                        <!-- Icono Frontal Fijo -->
                                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        </div>

                                        <!-- Menú Desplegable AJAX -->
                                        <div x-show="campusOpen" x-transition.opacity x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                            <div class="p-3 border-b border-slate-100 bg-slate-50 relative">
                                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-10">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                                </div>
                                                <input type="text" x-model="campusSearch" @input.debounce.300ms="fetchCampuses()" placeholder="Buscar sede por nombre..." 
                                                    class="w-full pl-9 pr-10 py-2.5 text-sm font-medium bg-white border border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-sm outline-none transition-all" @click.stop>
                                                
                                                <!-- Spinner AJAX -->
                                                <div x-show="isFetchingCampuses" class="absolute right-6 top-1/2 -translate-y-1/2">
                                                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                </div>
                                            </div>
                                            
                                            <ul class="max-h-60 overflow-y-auto py-2">
                                                <template x-for="campus in campuses" :key="campus.id">
                                                    <li @click="selectCampus(campus.id, campus.name)" class="px-5 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 text-sm font-bold flex items-center gap-3 transition-colors border-b border-slate-50 last:border-0">
                                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                                        <span x-text="campus.name"></span>
                                                    </li>
                                                </template>
                                                <!-- Estado Vacío del Buscador -->
                                                <li x-show="campuses.length === 0 && !isFetchingCampuses" class="px-5 py-6 text-sm text-slate-400 text-center font-medium flex flex-col items-center gap-2">
                                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    No se encontraron sedes coincidentes
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <p class="text-[11px] font-medium text-slate-500 mt-2 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Seleccione la sede donde estará ubicado este bloque. (Búsqueda en tiempo real disponible).
                                    </p>
                                    @error('campus_id')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Campo: Nombre del Bloque -->
                                <div>
                                    <label for="name" class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Nombre del Bloque <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                        </div>
                                        <input id="name" type="text" name="name" value="{{ old('name', $building->name) }}" required 
                                            placeholder="Ej: Bloque Administrativo, Bloque C, Edificio Central..." 
                                            class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 pl-11 pr-4">
                                    </div>
                                    <p class="text-[11px] font-medium text-slate-500 mt-2 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Este nombre será utilizado para organizar salones, oficinas y activos tecnológicos.
                                    </p>
                                    @error('name')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            
                            <!-- BOTONES DE ACCIÓN (Footer de la Tarjeta) -->
                            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-4">
                                <a href="{{ route('buildings.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-800 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2 focus:ring-4 focus:ring-slate-100 outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Cancelar
                                </a>
                                
                                <!-- Botón cambiado de "submit" directo a tipo "button" para interceptarlo con SweetAlert -->
                                <button type="button" @click.prevent="confirmUpdate()" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-md shadow-blue-200 transition-all active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-200 flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        Actualizar Bloque
                                    </span>
                                    <span x-show="isSubmitting" class="flex items-center gap-2" x-cloak>
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Procesando...
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
                            <!-- Borde de color superior (Azul Institucional) -->
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-600"></div>

                            <div class="p-6 pt-8">
                                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-700 shrink-0 shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Guía de Edición</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Módulo Bloques</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Cambios en Cascada</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Al modificar la sede a la que pertenece este bloque, todas las oficinas y equipos internos serán reasignados geográficamente.</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Integridad de Datos</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">El sistema conservará el historial de ubicación previo en los reportes de auditoría de los equipos.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Actualización Inmediata</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Los cambios de nomenclatura se reflejarán instantáneamente en las vistas de inventario y actas en PDF.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Última Modificación
                                    </p>
                                    <p class="text-xs font-bold text-slate-600">{{ method_exists($building, 'updated_at') && $building->updated_at ? $building->updated_at->format('d M Y - h:i A') : 'Fecha no registrada' }}</p>
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
            Alpine.data('buildingEditor', (initialId, initialName) => ({
                isSubmitting: false,
                campusOpen: false,
                campusSearch: '',
                selectedCampusId: initialId,
                selectedCampusName: initialName,
                campuses: [],
                isFetchingCampuses: false,

                init() {
                    this.fetchCampuses();
                },

                // Motor de Búsqueda AJAX
                async fetchCampuses() {
                    this.isFetchingCampuses = true;
                    try {
                        const url = new URL('{{ url("api/filters/api/sigma-filters/sedes") }}');
                        if (this.campusSearch) {
                            url.searchParams.set('search', this.campusSearch);
                        }

                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.campuses = Array.isArray(data) ? data : (data.data || []);
                        }
                    } catch (error) {
                        console.error('Error al conectar con la API de Sedes:', error);
                    } finally {
                        this.isFetchingCampuses = false;
                    }
                },

                selectCampus(id, name) {
                    this.selectedCampusId = id;
                    this.selectedCampusName = name;
                    this.campusOpen = false;
                },

                // Verificación SweetAlert antes de enviar el Formulario
                confirmUpdate() {
                    const form = document.getElementById('updateBuildingForm');
                    
                    // Asegurar que las validaciones HTML5 nativas (como "required") se respeten
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar actualización?',
                        html: `Está a punto de sobreescribir los datos maestros de este bloque.<br><br><span class="text-sm font-medium text-slate-500">Asegúrese de que el nombre y la sede elegida sean correctos.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb', // Blue-600 Institucional
                        cancelButtonColor: '#f1f5f9', // Gris para el botón de cancelar
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Sí, actualizar</span>',
                        cancelButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-slate-700">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95 focus:ring-4 focus:ring-blue-200 outline-none',
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

    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translate3d(0, -10px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out forwards;
        }
    </style>
</x-app-layout>