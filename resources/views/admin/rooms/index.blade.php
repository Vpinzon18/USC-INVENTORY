<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div id="rooms-index-container" 
         class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" 
         x-data="roomsEngine()"
         data-url-campuses="{{ url('api/filters/api/sigma-filters/sedes') }}"
         data-url-buildings="{{ url('api/filters/api/sigma-filters/buildings') }}" 
         data-url-types="{{ url('api/filters/api/sigma-filters/searchRoomTypes') }}"
         x-cloak>
         
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2 w-full lg:w-auto">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-1">
                            <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Oficinas y Salones</h2>
                            <span class="bg-blue-50 text-blue-700 border border-blue-200 text-[10px] px-3 py-1 rounded-full font-extrabold uppercase tracking-widest shadow-sm">
                                Módulo SIGMA
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500">
                            Gestione las oficinas, laboratorios, aulas y espacios físicos donde se ubican los activos.
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 w-full lg:w-auto">
                    <a href="{{ route('rooms.create') }}" class="w-full lg:w-auto inline-flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 focus:outline-none text-white font-extrabold text-xs px-6 py-3 rounded-xl shadow-sm transition-all active:scale-95 uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nueva Ubicación
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col relative z-30 transition-all">
                
                <div class="p-4 flex flex-col md:flex-row justify-between items-center gap-4 border-b border-slate-100">
                    <div class="relative w-full md:w-1/2 lg:w-2/5">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" x-model="search" @input.debounce.500ms="applyFilters()" placeholder="Buscar oficina, nomenclatura o piso..."
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-inner outline-none">
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <button @click="showFilters = !showFilters" class="w-full md:w-auto px-5 py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-colors flex items-center justify-center gap-2 focus:ring-4 focus:ring-slate-100 outline-none">
                            <svg class="w-4 h-4 transition-transform duration-300" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                            <span x-text="showFilters ? 'Ocultar Filtros AJAX' : 'Filtros Avanzados'"></span>
                        </button>
                    </div>
                </div>

                <div x-show="showFilters" x-collapse x-cloak class="bg-slate-50/50 p-6 border-b border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        
                        <div class="relative" @click.away="campusOpen = false">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Filtrar por Sede</label>
                            <button @click="campusOpen = !campusOpen" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-bold py-2.5 px-4 rounded-xl flex items-center justify-between hover:bg-slate-50 transition-colors shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none">
                                <span x-text="selectedCampusName || 'Todas las Sedes'" class="truncate"></span>
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="campusOpen" x-transition x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50 relative">
                                    <input type="text" x-model="campusSearch" @input.debounce.300ms="fetchCampuses()" placeholder="Buscar sede..." class="w-full px-3 py-2 text-xs border-slate-200 rounded-lg focus:ring-blue-500 shadow-inner outline-none" @click.stop>
                                    <div x-show="isFetchingCampuses" class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-3.5 w-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                                <ul class="max-h-48 overflow-y-auto py-1 text-sm">
                                    <li @click="setCampus('', 'Todas las Sedes')" class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-slate-500 font-bold border-b border-slate-50">Todas las Sedes</li>
                                    <template x-for="campus in campuses" :key="campus.id">
                                        <li @click="setCampus(campus.id, campus.name)" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 font-bold flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> <span x-text="campus.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="relative" @click.away="buildingOpen = false">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Filtrar por Bloque</label>
                            <button @click="buildingOpen = !buildingOpen" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-bold py-2.5 px-4 rounded-xl flex items-center justify-between hover:bg-slate-50 transition-colors shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none">
                                <span x-text="selectedBuildingName || 'Todos los Bloques'" class="truncate"></span>
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="buildingOpen" x-transition x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50 relative">
                                    <input type="text" x-model="buildingSearch" @input.debounce.300ms="fetchBuildings()" placeholder="Buscar bloque..." class="w-full px-3 py-2 text-xs border-slate-200 rounded-lg focus:ring-blue-500 shadow-inner outline-none" @click.stop>
                                    <div x-show="isFetchingBuildings" class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-3.5 w-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                                <ul class="max-h-48 overflow-y-auto py-1 text-sm">
                                    <li @click="setBuilding('', 'Todos los Bloques')" class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-slate-500 font-bold border-b border-slate-50">Todos los Bloques</li>
                                    <template x-for="building in buildings" :key="building.id">
                                        <li @click="setBuilding(building.id, building.name)" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 font-bold flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> <span x-text="building.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="relative" @click.away="typeOpen = false">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Clasificación / Tipo</label>
                            <button @click="typeOpen = !typeOpen" class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-bold py-2.5 px-4 rounded-xl flex items-center justify-between hover:bg-slate-50 transition-colors shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none">
                                <span x-text="selectedTypeName || 'Todos los Tipos'" class="truncate"></span>
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="typeOpen" x-transition x-cloak class="absolute left-0 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50 relative">
                                    <input type="text" x-model="typeSearch" @input.debounce.300ms="fetchTypes()" placeholder="Buscar tipo..." class="w-full px-3 py-2 text-xs border-slate-200 rounded-lg focus:ring-blue-500 shadow-inner outline-none" @click.stop>
                                    <div x-show="isFetchingTypes" class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-3.5 w-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                                <ul class="max-h-48 overflow-y-auto py-1 text-sm">
                                    <li @click="setType('', 'Todos los Tipos')" class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-slate-500 font-bold border-b border-slate-50">Todos los Tipos</li>
                                    <template x-for="type in types" :key="type.id">
                                        <li @click="setType(type.id, type.name)" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-700 cursor-pointer text-slate-700 font-bold flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> <span x-text="type.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="mt-4 flex justify-end">
                        <button @click="clearFilters()" class="px-4 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all outline-none">
                            Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <div id="table-container" class="relative z-10 transition-opacity duration-300">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                                    <th class="px-6 py-4 w-24">ID</th>
                                    <th class="px-6 py-4">Nombre / Número</th>
                                    <th class="px-6 py-4">Clasificación</th>
                                    <th class="px-6 py-4">Ubicación Física</th>
                                    <th class="px-6 py-4 text-center">Piso</th>
                                    <th class="px-6 py-4 text-center w-32">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($rooms as $room)
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-5">
                                            <span class="font-mono text-xs font-extrabold text-slate-400">
                                                #{{ str_pad($room->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            <span class="font-extrabold text-slate-800 group-hover:text-blue-600 transition-colors text-sm block">
                                                {{ $room->name }}
                                            </span>
                                            @if($room->nomenclatura)
                                                <span class="text-[10px] font-bold text-slate-500 mt-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                    {{ $room->nomenclatura }}
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-extrabold border border-slate-200 bg-white text-slate-600 shadow-sm uppercase tracking-widest">
                                                {{ $room->type->name ?? 'Sin Clasificar' }}
                                            </span>
                                            @if($room->status !== 'Activo')
                                                <span class="block text-[9px] font-bold text-rose-500 uppercase tracking-widest mt-1.5">
                                                    {{ $room->status }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-5">
                                            <div class="flex flex-col gap-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 w-fit shadow-sm">
                                                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    {{ $room->building->campus->name ?? 'Sede N/A' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 w-fit shadow-sm">
                                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    {{ $room->building->name ?? 'Bloque N/A' }}
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center justify-center min-w-[2.5rem] px-3 py-1.5 rounded-lg text-xs font-black bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                                {{ $room->floor ?? '-' }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('rooms.edit', $room) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-blue-500" title="Editar Ubicación">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                
                                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" @submit.prevent="confirmDelete($event, '{{ addslashes($room->name) }}')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-100 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-rose-500" title="Eliminar Ubicación">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-5 shadow-inner">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                                </div>
                                                <h3 class="text-base font-extrabold text-slate-800 mb-2 tracking-tight">No existen ubicaciones registradas</h3>
                                                <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">Comience agregando salones, laboratorios u oficinas para mapear físicamente los activos de la institución.</p>
                                                <a href="{{ route('rooms.create') }}" class="text-xs font-extrabold text-white hover:bg-blue-700 transition-colors uppercase tracking-widest flex items-center gap-2 bg-blue-600 px-6 py-3 rounded-xl shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    Registrar Primera Ubicación
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-500 mt-auto">
                        <div class="w-full sm:w-auto">
                            @if(isset($rooms) && method_exists($rooms, 'hasPages') && $rooms->hasPages())
                                {{ $rooms->appends(request()->query())->links() }}
                            @else
                                <span class="uppercase tracking-wider">Mostrando {{ isset($rooms) ? $rooms->count() : 0 }} registros encontrados</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="uppercase tracking-widest text-[10px] font-black">Mostrar</span>
                            <select x-model="perPage" @change="submitForm()" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-bold cursor-pointer shadow-sm bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="uppercase tracking-widest text-[10px] font-black">por página</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('roomsEngine', () => ({
                showFilters: false,

                // URLs inyectadas desde los data-attributes (Seguro para JS)
                urlCampuses: '',
                urlBuildings: '',
                urlTypes: '',

                // Parámetros de URL iniciales
                search: new URLSearchParams(window.location.search).get('search') || '',
                perPage: new URLSearchParams(window.location.search).get('per_page') || '15',

                // Sede (Campus)
                campusOpen: false,
                campusSearch: '',
                selectedCampusId: new URLSearchParams(window.location.search).get('campus_id') || '',
                selectedCampusName: '',
                campuses: [],
                isFetchingCampuses: false,

                // Bloque (Building)
                buildingOpen: false,
                buildingSearch: '',
                selectedBuildingId: new URLSearchParams(window.location.search).get('building_id') || '',
                selectedBuildingName: '',
                buildings: [],
                isFetchingBuildings: false,

                // Tipo de Espacio (RoomType)
                typeOpen: false,
                typeSearch: '',
                selectedTypeId: new URLSearchParams(window.location.search).get('room_type_id') || '',
                selectedTypeName: '',
                types: [],
                isFetchingTypes: false,

                init() {
                    // Capturar las URLs del contenedor
                    const container = document.getElementById('rooms-index-container');
                    if (container) {
                        this.urlCampuses = container.dataset.urlCampuses;
                        this.urlBuildings = container.dataset.urlBuildings;
                        this.urlTypes = container.dataset.urlTypes;
                    }

                    // Cargar opciones en segundo plano
                    this.fetchCampuses();
                    this.fetchBuildings();
                    this.fetchTypes();
                },

                // Llamadas a tu API local dinámica
                async fetchCampuses() {
                    if (!this.urlCampuses) return;
                    this.isFetchingCampuses = true;
                    try {
                        const url = new URL(this.urlCampuses);
                        if (this.campusSearch) url.searchParams.set('search', this.campusSearch);
                        
                        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.campuses = Array.isArray(data) ? data : (data.data || []);
                            
                            // Restaurar nombre inicial de los filtros activos en URL
                            if(this.selectedCampusId && !this.selectedCampusName) {
                                const found = this.campuses.find(c => c.id == this.selectedCampusId);
                                if(found) this.selectedCampusName = found.name;
                            }
                        }
                    } catch (e) { console.error(e); } finally { this.isFetchingCampuses = false; }
                },

                async fetchBuildings() {
                    if (!this.urlBuildings) return;
                    this.isFetchingBuildings = true;
                    try {
                        const url = new URL(this.urlBuildings);
                        if (this.buildingSearch) url.searchParams.set('search', this.buildingSearch);
                        if (this.selectedCampusId) url.searchParams.set('campus_id', this.selectedCampusId); 
                        
                        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.buildings = Array.isArray(data) ? data : (data.data || []);

                            // Restaurar nombre inicial
                            if(this.selectedBuildingId && !this.selectedBuildingName) {
                                const found = this.buildings.find(b => b.id == this.selectedBuildingId);
                                if(found) this.selectedBuildingName = found.name;
                            }
                        }
                    } catch (e) { console.error(e); } finally { this.isFetchingBuildings = false; }
                },

                async fetchTypes() {
                    if (!this.urlTypes) return;
                    this.isFetchingTypes = true;
                    try {
                        const url = new URL(this.urlTypes);
                        if (this.typeSearch) url.searchParams.set('search', this.typeSearch);
                        
                        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        if (res.ok) {
                            const data = await res.json();
                            this.types = Array.isArray(data) ? data : (data.data || []);

                            // Restaurar nombre inicial
                            if(this.selectedTypeId && !this.selectedTypeName) {
                                const found = this.types.find(t => t.id == this.selectedTypeId);
                                if(found) this.selectedTypeName = found.name;
                            }
                        }
                    } catch (e) { console.error(e); } finally { this.isFetchingTypes = false; }
                },

                setCampus(id, name) { 
                    this.selectedCampusId = id; 
                    this.selectedCampusName = name;
                    this.selectedBuildingId = ''; // Limpiamos el bloque si cambian la sede
                    this.selectedBuildingName = '';
                    this.applyFilters(); 
                },

                setBuilding(id, name) {
                    this.selectedBuildingId = id;
                    this.selectedBuildingName = name;
                    this.applyFilters();
                },

                setType(id, name) {
                    this.selectedTypeId = id;
                    this.selectedTypeName = name;
                    this.applyFilters();
                },

                applyFilters() {
                    this.submitForm();
                },

                clearFilters() {
                    this.search = '';
                    this.selectedCampusId = ''; this.selectedCampusName = '';
                    this.selectedBuildingId = ''; this.selectedBuildingName = '';
                    this.selectedTypeId = ''; this.selectedTypeName = '';
                    this.submitForm();
                },

                // LÓGICA DE ELIMINACIÓN SEGURA
                confirmDelete(event, name) {
                    Swal.fire({
                        title: '¿Eliminar ubicación?',
                        html: `Está a punto de eliminar la oficina/salón <br><strong class="text-slate-900 text-lg">${name}</strong><br><br>Si tiene activos asignados, la eliminación se bloqueará automáticamente por seguridad.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // Rose para botón destructivo
                        cancelButtonColor: '#f1f5f9',
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Sí, eliminar</span>',
                        cancelButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-slate-700">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95 focus:ring-4 focus:ring-rose-200 outline-none',
                            cancelButton: 'rounded-xl px-6 py-2.5 border border-slate-200 transition-all hover:bg-slate-200 mr-3 focus:ring-4 focus:ring-slate-200 outline-none'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.submit();
                        }
                    });
                },

                async submitForm() {
                    const url = new URL(window.location.href);
                    
                    if (this.search) url.searchParams.set('search', this.search); else url.searchParams.delete('search');
                    if (this.selectedCampusId) url.searchParams.set('campus_id', this.selectedCampusId); else url.searchParams.delete('campus_id');
                    if (this.selectedBuildingId) url.searchParams.set('building_id', this.selectedBuildingId); else url.searchParams.delete('building_id');
                    if (this.selectedTypeId) url.searchParams.set('room_type_id', this.selectedTypeId); else url.searchParams.delete('room_type_id');
                    if (this.perPage) url.searchParams.set('per_page', this.perPage); else url.searchParams.delete('per_page');

                    // Reiniciar paginación a página 1 cuando se alteran filtros
                    url.searchParams.delete('page');

                    window.history.pushState({}, '', url.toString());
                    
                    const tableContainer = document.getElementById('table-container');
                    if (tableContainer) tableContainer.style.opacity = '0.5';

                    try {
                        const response = await fetch(url.toString(), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newTable = doc.getElementById('table-container');
                        if (tableContainer && newTable) tableContainer.innerHTML = newTable.innerHTML;

                    } catch (e) {
                        console.error('Error al actualizar tabla:', e);
                        window.location.href = url.toString(); 
                    }

                    if (tableContainer) tableContainer.style.opacity = '1';
                }
            }));
        });
    </script>
</x-app-layout>