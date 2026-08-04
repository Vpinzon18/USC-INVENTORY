<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 
        Inyectamos los datos en atributos HTML para que el validador JS no marque errores.
        Blade escapará automáticamente el JSON de forma segura.
    -->
    <div id="room-editor-container" 
         class="py-4 md:py-6 bg-slate-50 min-h-screen font-sans text-slate-800" 
         x-data="roomEditor()" 
         data-initial-building="{{ old('building_id', $room->building_id) }}"
         data-buildings="{{ json_encode($buildings ?? []) }}"
         data-initial-type="{{ old('room_type_id', $room->room_type_id) }}"
         data-roomtypes="{{ json_encode($roomTypes ?? []) }}"
         x-cloak>
         
        <!-- 🌟 SOLUCIÓN: Cambiamos w-[96%] por w-full px-4 sm:px-6 lg:px-8 para respetar márgenes en móviles -->
        <div class="max-w-[1400px] w-full px-4 sm:px-6 lg:px-8 mx-auto space-y-4 md:space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 md:gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 md:w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-4 md:gap-5 pl-2">
                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-xl md:text-2xl text-slate-900 tracking-tight flex flex-wrap items-center gap-2 md:gap-3">
                            Editar Oficina: <span class="text-blue-600">{{ $room->name }}</span>
                            <span class="bg-amber-100 text-amber-700 text-[9px] md:text-[10px] px-2.5 md:px-3 py-1 rounded-full font-bold uppercase tracking-widest shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Modo Edición
                            </span>
                        </h2>
                        <p class="text-xs md:text-sm font-medium text-slate-500 mt-1">
                            Modifique los parámetros, nomenclatura o el bloque asignado a este espacio físico.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ALERTAS DE VALIDACIÓN -->
            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-600 p-4 md:p-5 rounded-r-2xl shadow-sm flex items-start gap-4 animate-fade-in-down">
                    <div class="bg-white p-2 rounded-xl shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xs md:text-sm font-extrabold text-rose-900 tracking-tight">Se encontraron {{ $errors->count() }} errores de validación</h3>
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
                    <form action="{{ route('rooms.update', $room) }}" method="POST" id="updateRoomForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- 🌟 SOLUCIÓN: Quitamos overflow-hidden, aplicamos relative z-20 -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 relative z-20">
                            
                            <!-- 🌟 SOLUCIÓN: px-5 md:px-8 y rounded-t-2xl -->
                            <div class="px-5 md:px-8 py-4 md:py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                                <h3 class="text-[11px] md:text-xs font-extrabold text-slate-800 uppercase tracking-widest flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Información General
                                </h3>
                            </div>
                            
                            <!-- 🌟 SOLUCIÓN: p-5 md:p-8 y gap-5 md:gap-8 -->
                            <div class="p-5 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                                
                                <!-- BLOQUE / EDIFICIO (AJAX Local Alpine) -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-2 text-[10px] md:text-[11px] font-extrabold text-slate-600 uppercase mb-2 md:mb-2.5 tracking-widest">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Bloque o Edificio Asignado <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative" @click.away="buildingOpen = false">
                                        <input type="hidden" name="building_id" x-model="selectedBuildingId" required>
                                        
                                        <button type="button" @click="buildingOpen = !buildingOpen"
                                            class="w-full bg-slate-50 border text-xs md:text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3 md:py-3.5 pl-10 md:pl-11 pr-10 text-left flex items-center justify-between border-slate-200 text-slate-800">
                                            
                                            <span x-text="selectedBuildingName || 'Seleccione un bloque...'" :class="{'text-slate-400 font-normal': !selectedBuildingName}" class="truncate"></span>
                                            
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 md:px-4 text-slate-400">
                                                <svg class="h-4 w-4 md:h-5 md:w-5 transition-transform duration-200" :class="buildingOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </button>
                                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-3.5 md:pl-4 flex items-center text-slate-400">
                                            <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </div>

                                        <!-- Menú Desplegable Inteligente -->
                                        <div x-show="buildingOpen" x-transition.opacity x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                            <div class="p-2 md:p-3 border-b border-slate-100 bg-slate-50 relative">
                                                <div class="absolute inset-y-0 left-0 pl-5 md:pl-6 flex items-center pointer-events-none z-10">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                                </div>
                                                <input type="text" x-model="buildingSearch" placeholder="Buscar bloque..." class="w-full pl-8 md:pl-9 pr-4 py-2 md:py-2.5 text-xs md:text-sm font-medium bg-white border border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-sm outline-none transition-all" @click.stop>
                                            </div>
                                            <ul class="max-h-48 md:max-h-60 overflow-y-auto py-2">
                                                <template x-for="building in filteredBuildings" :key="building.id">
                                                    <li @click="selectBuilding(building)" class="px-4 md:px-5 py-2.5 md:py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 text-xs md:text-sm font-bold flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 transition-colors border-b border-slate-50 last:border-0">
                                                        <div class="flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-blue-500 shrink-0"></span>
                                                            <span x-text="building.name" class="truncate"></span>
                                                        </div>
                                                        <span class="text-[9px] md:text-[10px] font-medium text-slate-400 sm:ml-auto truncate" x-text="'(' + (building.campus ? building.campus.name : '') + ')'"></span>
                                                    </li>
                                                </template>
                                                <li x-show="filteredBuildings.length === 0" class="px-5 py-4 md:py-6 text-xs md:text-sm text-slate-400 text-center font-medium">No se encontraron bloques</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('building_id')
                                        <p class="text-[10px] md:text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre del Salón -->
                                <div>
                                    <label for="name" class="flex items-center gap-2 text-[10px] md:text-[11px] font-extrabold text-slate-600 uppercase mb-2 md:mb-2.5 tracking-widest">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        Nombre / Número <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="name" type="text" name="name" value="{{ old('name', $room->name) }}" required placeholder="Ej: Sala 1, Oficina 402..." 
                                        class="w-full bg-slate-50 border-slate-200 text-slate-800 text-xs md:text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3 md:py-3.5 px-3.5 md:px-4">
                                    @error('name')
                                        <p class="text-[10px] md:text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomenclatura -->
                                <div>
                                    <label for="nomenclatura" class="flex items-center gap-2 text-[10px] md:text-[11px] font-extrabold text-slate-600 uppercase mb-2 md:mb-2.5 tracking-widest">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                        Nomenclatura
                                    </label>
                                    <input id="nomenclatura" type="text" name="nomenclatura" value="{{ old('nomenclatura', $room->nomenclatura) }}" placeholder="Ej: BLQ4-PISO4-SALA1" 
                                        class="w-full bg-slate-50 border-slate-200 text-slate-800 text-xs md:text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3 md:py-3.5 px-3.5 md:px-4">
                                    @error('nomenclatura')
                                        <p class="text-[10px] md:text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- 🌟 TIPO DE ESPACIO (COMBO BOX DINÁMICO) 🌟 -->
                                <div>
                                    <label for="room_type_id" class="flex items-center gap-2 text-[10px] md:text-[11px] font-extrabold text-slate-600 uppercase mb-2 md:mb-2.5 tracking-widest">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        Clasificación / Tipo <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative" @click.away="typeOpen = false">
                                        <input type="hidden" name="room_type_id" x-model="selectedTypeId" required>
                                        
                                        <button type="button" @click="typeOpen = !typeOpen"
                                            class="w-full bg-slate-50 border text-xs md:text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3 md:py-3.5 pl-10 md:pl-11 pr-10 text-left flex items-center justify-between border-slate-200 text-slate-800">
                                            
                                            <span x-text="selectedTypeName || 'Seleccione...'" :class="{'text-slate-400 font-normal': !selectedTypeName}" class="truncate"></span>
                                            
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 md:px-4 text-slate-400">
                                                <svg class="h-4 w-4 md:h-5 md:w-5 transition-transform duration-200" :class="typeOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </button>
                                        <div class="pointer-events-none absolute inset-y-0 left-0 pl-3.5 md:pl-4 flex items-center text-slate-400">
                                            <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                        </div>

                                        <!-- Menú Desplegable Inteligente -->
                                        <div x-show="typeOpen" x-transition.opacity x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                            <div class="p-2 md:p-3 border-b border-slate-100 bg-slate-50 relative">
                                                <div class="absolute inset-y-0 left-0 pl-5 md:pl-6 flex items-center pointer-events-none z-10">
                                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                                </div>
                                                <input type="text" x-model="typeSearch" placeholder="Buscar tipo..." class="w-full pl-8 md:pl-9 pr-4 py-2 md:py-2.5 text-xs md:text-sm font-medium bg-white border border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-sm outline-none transition-all" @click.stop>
                                            </div>
                                            <ul class="max-h-48 md:max-h-60 overflow-y-auto py-2">
                                                <template x-for="type in filteredTypes" :key="type.id">
                                                    <li @click="selectType(type)" class="px-4 md:px-5 py-2.5 md:py-3 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 text-xs md:text-sm font-bold flex items-center gap-2 md:gap-3 transition-colors border-b border-slate-50 last:border-0">
                                                        <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-blue-500 shrink-0"></span>
                                                        <span x-text="type.name" class="truncate"></span>
                                                    </li>
                                                </template>
                                                <li x-show="filteredTypes.length === 0" class="px-5 py-4 md:py-6 text-xs md:text-sm text-slate-400 text-center font-medium">No se encontraron tipos</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('room_type_id')
                                        <p class="text-[10px] md:text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Piso -->
                                <div>
                                    <label for="floor" class="flex items-center gap-2 text-[10px] md:text-[11px] font-extrabold text-slate-600 uppercase mb-2 md:mb-2.5 tracking-widest">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                        Nivel / Piso <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="floor" type="text" name="floor" value="{{ old('floor', $room->floor) }}" required placeholder="Ej: 1, 4, Sótano..." 
                                        class="w-full bg-slate-50 border-slate-200 text-slate-800 text-xs md:text-sm font-bold placeholder:font-normal placeholder:text-slate-400 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3 md:py-3.5 px-3.5 md:px-4">
                                    @error('floor')
                                        <p class="text-[10px] md:text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado Operativo -->
                                <div class="md:col-span-2">
                                    <label for="status" class="flex items-center gap-2 text-[10px] md:text-[11px] font-extrabold text-slate-600 uppercase mb-2 md:mb-2.5 tracking-widest">
                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        Estado Operativo <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative w-full md:w-1/2">
                                        <select name="status" id="status" required
                                            class="w-full bg-slate-50 border-slate-200 text-slate-800 text-xs md:text-sm font-bold rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 shadow-inner hover:bg-white transition-all py-3 md:py-3.5 pl-3.5 md:pl-4 pr-10 appearance-none cursor-pointer">
                                            <option value="Activo" {{ old('status', $room->status) == 'Activo' ? 'selected' : '' }}>🟢 Activo y Disponible</option>
                                            <option value="Inactivo" {{ old('status', $room->status) == 'Inactivo' ? 'selected' : '' }}>🔴 Inactivo / Cerrado</option>
                                            <option value="Remodelación" {{ old('status', $room->status) == 'Remodelación' ? 'selected' : '' }}>🚧 En Remodelación</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 md:px-4 text-slate-400">
                                            <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                                        </div>
                                    </div>
                                    @error('status')
                                        <p class="text-[10px] md:text-[11px] font-bold text-rose-600 mt-1.5 flex items-center gap-1"><svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                            
                            <!-- BOTONES DE ACCIÓN -->
                            <!-- 🌟 SOLUCIÓN: px-5 md:px-8 y rounded-b-2xl -->
                            <div class="px-5 md:px-8 py-4 md:py-5 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-3 md:gap-4 rounded-b-2xl">
                                <a href="{{ route('rooms.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-800 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2 focus:ring-4 focus:ring-slate-100 outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Cancelar
                                </a>
                                
                                <button type="button" @click.prevent="confirmUpdate()" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-md shadow-blue-200 transition-all active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-200 flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                        Actualizar Oficina
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
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Cambios en Cascada</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Si modifica la ubicación, todos los activos tecnológicos dentro de este espacio actualizarán su locación automáticamente en el inventario.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3.5 items-start group">
                                        <div class="mt-0.5 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-800 tracking-tight">Registro Histórico</p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1 leading-relaxed">Los cambios operativos (como poner el espacio en Remodelación) quedarán guardados en las hojas de vida del sistema.</p>
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
            Alpine.data('roomEditor', () => ({
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
                    const container = document.getElementById('room-editor-container');
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

                    // Auto-seleccionar al cargar
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

                confirmUpdate() {
                    const form = document.getElementById('updateRoomForm');
                    
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: '¿Confirmar actualización?',
                        html: `Está a punto de actualizar los datos de este espacio en SIGMA.<br><br><span class="text-sm font-medium text-slate-500">Esta acción afectará los reportes de inventario asociados.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb', // Blue-600
                        cancelButtonColor: '#f1f5f9',
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
</x-app-layout>