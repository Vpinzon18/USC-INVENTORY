<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 
        Inyectamos los datos en atributos HTML para que el validador JS no marque errores.
        Blade escapará automáticamente el JSON de forma segura.
    -->
    <div id="room-creator-container" 
         class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" 
         x-data="roomCreator()" 
         data-initial-building="{{ old('building_id') }}"
         data-buildings="{{ json_encode($buildings ?? []) }}"
         data-initial-type="{{ old('room_type_id') }}"
         data-roomtypes="{{ json_encode($roomTypes ?? []) }}"
         x-cloak>
         
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight flex items-center gap-3">
                            Registrar Nueva Ubicación
                            <span class="bg-blue-50 text-blue-700 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest shadow-sm border border-blue-200">
                                Nuevo Registro
                            </span>
                        </h2>
                        <p class="text-sm font-medium text-slate-500 mt-1">
                            Añada una nueva oficina, salón o laboratorio al mapa físico de la institución.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ALERTAS DE VALIDACIÓN -->
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
                    <!-- Asegúrate que la ruta corresponda a tu archivo web.php (e.g. rooms.store o admin.rooms.store) -->
                    <form action="{{ route('rooms.store') }}" method="POST" id="createRoomForm" class="space-y-6">
                        @csrf
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Información General
                                </h3>
                            </div>
                            
                            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                                
                                <!-- BLOQUE / EDIFICIO (AJAX Local Alpine) -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Bloque o Edificio Asignado <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative" @click.away="buildingOpen = false">
                                        <input type="hidden" name="building_id" x-model="selectedBuildingId" required>
                                        
                                        <button type="button" @click="buildingOpen = !buildingOpen"
                                            class="w-full bg-slate-50 border text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 pl-11 pr-10 text-left flex items-center justify-between border-slate-200 text-slate-800">
                                            
                                            <span x-text="selectedBuildingName || 'Seleccione un bloque...'" :class="{'text-slate-400 font-normal': !selectedBuildingName}"></span>
                                            
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                                <svg class="h-5 w-5 transition-transform duration-200" :class="buildingOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </button>
                                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </div>

                                        <!-- Menú Desplegable Inteligente -->
                                        <div x-show="buildingOpen" x-transition.opacity x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                            <div class="p-3 border-b border-slate-100 bg-slate-50 relative">
                                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-10">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                                </div>
                                                <input type="text" x-model="buildingSearch" placeholder="Buscar bloque o sede..." class="w-full pl-9 pr-4 py-2.5 text-sm font-medium bg-white border border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-sm outline-none transition-all" @click.stop>
                                            </div>
                                            <ul class="max-h-60 overflow-y-auto py-2">
                                                <template x-for="building in filteredBuildings" :key="building.id">
                                                    <li @click="selectBuilding(building)" class="px-5 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 text-sm font-bold flex items-center gap-3 transition-colors border-b border-slate-50 last:border-0">
                                                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                                        <span>
                                                            <span x-text="building.name"></span>
                                                            <span class="text-[10px] font-medium text-slate-400 ml-1" x-text="'(' + (building.campus ? building.campus.name : '') + ')'"></span>
                                                        </span>
                                                    </li>
                                                </template>
                                                <li x-show="filteredBuildings.length === 0" class="px-5 py-6 text-sm text-slate-400 text-center font-medium">No se encontraron bloques</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('building_id')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre del Salón -->
                                <div>
                                    <label for="name" class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        Nombre / Número <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: Sala 1, Oficina 402..." 
                                        class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 px-4">
                                    @error('name')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomenclatura -->
                                <div>
                                    <label for="nomenclatura" class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                        Nomenclatura
                                    </label>
                                    <input id="nomenclatura" type="text" name="nomenclatura" value="{{ old('nomenclatura') }}" placeholder="Ej: BLQ4-PISO4-SALA1" 
                                        class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 px-4">
                                    @error('nomenclatura')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 🌟 TIPO DE ESPACIO (COMBO BOX DINÁMICO CON ALPINE) 🌟 -->
                                <div>
                                    <label for="room_type_id" class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        Clasificación / Tipo <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative" @click.away="typeOpen = false">
                                        <input type="hidden" name="room_type_id" x-model="selectedTypeId" required>
                                        
                                        <button type="button" @click="typeOpen = !typeOpen"
                                            class="w-full bg-slate-50 border text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 pl-11 pr-10 text-left flex items-center justify-between border-slate-200 text-slate-800">
                                            
                                            <span x-text="selectedTypeName || 'Seleccione un tipo...'" :class="{'text-slate-400 font-normal': !selectedTypeName}"></span>
                                            
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                                <svg class="h-5 w-5 transition-transform duration-200" :class="typeOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </button>
                                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                        </div>

                                        <!-- Menú Desplegable Inteligente -->
                                        <div x-show="typeOpen" x-transition.opacity x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                            <div class="p-3 border-b border-slate-100 bg-slate-50 relative">
                                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none z-10">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                                </div>
                                                <input type="text" x-model="typeSearch" placeholder="Buscar tipo..." class="w-full pl-9 pr-4 py-2.5 text-sm font-medium bg-white border border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-sm outline-none transition-all" @click.stop>
                                            </div>
                                            <ul class="max-h-60 overflow-y-auto py-2">
                                                <template x-for="type in filteredTypes" :key="type.id">
                                                    <li @click="selectType(type)" class="px-5 py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 text-sm font-bold flex items-center gap-3 transition-colors border-b border-slate-50 last:border-0">
                                                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                                        <span x-text="type.name"></span>
                                                    </li>
                                                </template>
                                                <li x-show="filteredTypes.length === 0" class="px-5 py-6 text-sm text-slate-400 text-center font-medium">No se encontraron tipos de espacio</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('room_type_id')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Piso -->
                                <div>
                                    <label for="floor" class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                        Nivel / Piso <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="floor" type="text" name="floor" value="{{ old('floor') }}" required placeholder="Ej: 1, 4, Sótano..." 
                                        class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 px-4">
                                    @error('floor')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado Operativo -->
                                <div class="md:col-span-2">
                                    <label for="status" class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase mb-2.5 tracking-widest">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        Estado Operativo <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative w-full md:w-1/2">
                                        <select name="status" id="status" required
                                            class="w-full bg-slate-50 border-slate-200 text-slate-800 text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                            <option value="Activo" {{ old('status', 'Activo') == 'Activo' ? 'selected' : '' }}>🟢 Activo y Disponible</option>
                                            <option value="Inactivo" {{ old('status') == 'Inactivo' ? 'selected' : '' }}>🔴 Inactivo / Cerrado</option>
                                            <option value="Remodelación" {{ old('status') == 'Remodelación' ? 'selected' : '' }}>🚧 En Remodelación</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                        </div>
                                    </div>
                                    @error('status')
                                        <p class="text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            
                            <!-- BOTONES DE ACCIÓN -->
                            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-4">
                                <a href="{{ route('rooms.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-800 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2 focus:ring-4 focus:ring-slate-100 outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Cancelar
                                </a>
                                
                                <button type="button" @click.prevent="confirmCreate()" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-md shadow-blue-200 transition-all active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-200 flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        Registrar Ubicación
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

                <!-- COLUMNA DERECHA: GUÍA CORPORATIVA -->
                <div class="lg:col-span-4 hidden lg:block">
                    <div class="sticky top-6 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-600"></div>
                            <div class="p-6 pt-8">
                                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-700 shrink-0 shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">Mapeo Físico</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Módulo Oficinas</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Estructura Jerárquica</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Este espacio quedará anclado al bloque seleccionado, formando la estructura: Sede > Bloque > Oficina.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Disponibilidad Inmediata</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Una vez registrada, esta oficina estará habilitada para asignar nuevos activos tecnológicos.</p>
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

    <!-- SCRIPT ALPINEJS PURE JS -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('roomCreator', () => ({
                isSubmitting: false,
                
                // Building Data
                buildingOpen: false,
                buildingSearch: '',
                selectedBuildingId: '',
                selectedBuildingName: '',
                buildingsData: [],

                // Type Data
                typeOpen: false,
                typeSearch: '',
                selectedTypeId: '',
                selectedTypeName: '',
                roomTypesData: [],

                init() {
                    const container = document.getElementById('room-creator-container');
                    if (container) {
                        this.selectedBuildingId = container.dataset.initialBuilding || '';
                        this.selectedTypeId = container.dataset.initialType || '';

                        if (container.dataset.buildings) {
                            try {
                                this.buildingsData = JSON.parse(container.dataset.buildings);
                            } catch (e) {
                                console.error("No se pudo analizar el JSON de edificios", e);
                            }
                        }

                        if (container.dataset.roomtypes) {
                            try {
                                this.roomTypesData = JSON.parse(container.dataset.roomtypes);
                            } catch (e) {
                                console.error("No se pudo analizar el JSON de tipos de espacio", e);
                            }
                        }
                    }

                    // Auto-seleccionar si hay error de validación (old)
                    if (this.selectedBuildingId && this.buildingsData.length > 0) {
                        const found = this.buildingsData.find(b => b.id == this.selectedBuildingId);
                        if(found) {
                            this.selectedBuildingName = `${found.name} (${found.campus?.name || ''})`;
                        }
                    }

                    if (this.selectedTypeId && this.roomTypesData.length > 0) {
                        const foundType = this.roomTypesData.find(t => t.id == this.selectedTypeId);
                        if(foundType) {
                            this.selectedTypeName = foundType.name;
                        }
                    }
                },

                get filteredBuildings() {
                    if (this.buildingSearch === '') return this.buildingsData;
                    return this.buildingsData.filter(b => 
                        b.name.toLowerCase().includes(this.buildingSearch.toLowerCase()) || 
                        (b.campus && b.campus.name.toLowerCase().includes(this.buildingSearch.toLowerCase()))
                    );
                },

                selectBuilding(building) {
                    this.selectedBuildingId = building.id;
                    this.selectedBuildingName = `${building.name} (${building.campus?.name || ''})`;
                    this.buildingOpen = false;
                    this.buildingSearch = '';
                },

                get filteredTypes() {
                    if (this.typeSearch === '') return this.roomTypesData;
                    return this.roomTypesData.filter(t => 
                        t.name.toLowerCase().includes(this.typeSearch.toLowerCase())
                    );
                },

                selectType(type) {
                    this.selectedTypeId = type.id;
                    this.selectedTypeName = type.name;
                    this.typeOpen = false;
                    this.typeSearch = '';
                },

                confirmCreate() {
                    const form = document.getElementById('createRoomForm');
                    
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar registro?',
                        html: `Está a punto de registrar un nuevo espacio físico en SIGMA.<br><br><span class="text-sm font-medium text-slate-500">Asegúrese de que el bloque y el tipo de espacio sean los correctos.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb', // Blue-600
                        cancelButtonColor: '#f1f5f9',
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Sí, registrar</span>',
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
</x-app-layout>