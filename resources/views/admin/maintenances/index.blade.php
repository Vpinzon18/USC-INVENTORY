<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen font-sans" x-data="bitacoraEngine()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-5">

            <!-- ENCABEZADO -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 md:p-6 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2 w-full md:w-auto">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-50 to-sky-50 border border-cyan-200 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Bitácoras de Intervención</h2>
                        <p class="text-xs font-medium text-slate-500 mt-1">
                            Historial completo de mantenimientos, reparaciones y servicios técnicos.
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('maintenances.create') }}" class="inline-flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest px-6 py-3 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Nueva Bitácora General
                    </a>
                </div>
            </div>

            <!-- MOTOR DE FILTROS COLAPSABLE -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-5 relative z-20">
                <!-- CABECERA DEL FILTRO (SIEMPRE VISIBLE) -->
                <div class="flex items-center justify-between cursor-pointer" @click="showFilters = !showFilters">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 transition-transform duration-300" :class="showFilters ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.707 7.293A1 1 0 013.414 6.586V4z" /></svg>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Filtros de Búsqueda</h3>
                    </div>
                    
                    <button type="button" class="text-xs font-bold text-blue-600 uppercase tracking-wider hover:text-blue-800 transition-colors flex items-center gap-1">
                        <span x-text="showFilters ? 'Ocultar Filtros' : 'Mostrar Filtros'"></span>
                        <svg class="w-3 h-3 transition-transform duration-300" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>

                <!-- CONTENIDO DEL FILTRO (SE OCULTA/MUESTRA) -->
                <div x-show="showFilters" x-collapse x-cloak>
                    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 relative z-30">
                        
                        <!-- Search Global -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" x-model="searchQuery" @keydown.enter="submitForm()" placeholder="Serial, Placa..."
                                class="w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        <!-- Técnico con Buscador -->
                        <div class="relative" @click.away="techOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <button type="button" @click="techOpen = !techOpen" class="w-full flex items-center justify-between border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <span class="block truncate" x-text="technicians.find(t => t.id == selectedTech)?.name || 'Todos los técnicos'"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="techOpen" x-transition x-cloak class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                    <input type="text" x-model="techSearch" placeholder="Buscar técnico..." class="w-full px-3 py-1.5 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @click.stop>
                                </div>
                                <ul class="py-1 text-xs max-h-48 overflow-y-auto">
                                    <li @click="selectTech('')" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-600 font-bold">Todos los técnicos</li>
                                    <template x-for="item in filteredTechnicians" :key="item.id">
                                        <li @click="selectTech(item.id)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-700"
                                            :class="selectedTech == item.id ? 'bg-blue-50 text-blue-700 font-bold' : ''" x-text="item.name"></li>
                                    </template>
                                    <li x-show="filteredTechnicians.length === 0" class="px-4 py-3 text-center text-slate-400 font-bold">No hay resultados</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Categoría (Tipo) con Buscador -->
                        <div class="relative" @click.away="catOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                            </div>
                            <button type="button" @click="catOpen = !catOpen" class="w-full flex items-center justify-between border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <span class="block truncate" x-text="selectedCat || 'Todas las categorías'"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="catOpen" x-transition x-cloak class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                    <input type="text" x-model="catSearch" placeholder="Buscar categoría..." class="w-full px-3 py-1.5 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @click.stop>
                                </div>
                                <ul class="py-1 text-xs max-h-48 overflow-y-auto">
                                    <li @click="selectCat('')" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-600 font-bold">Todas las categorías</li>
                                    <template x-for="item in filteredCategories" :key="item.id || item.name">
                                        <li @click="selectCat(item.name || item.id)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-700"
                                            :class="selectedCat == (item.name || item.id) ? 'bg-blue-50 text-blue-700 font-bold' : ''" x-text="item.name || item.description"></li>
                                    </template>
                                    <li x-show="filteredCategories.length === 0" class="px-4 py-3 text-center text-slate-400 font-bold">No hay resultados</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Edificio con Buscador -->
                        <div class="relative" @click.away="buildOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <button type="button" @click="buildOpen = !buildOpen" class="w-full flex items-center justify-between border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <span class="block truncate" x-text="buildings.find(b => b.id == selectedBuild)?.name || 'Todos los edificios'"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="buildOpen" x-transition x-cloak class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                    <input type="text" x-model="buildSearch" placeholder="Buscar edificio..." class="w-full px-3 py-1.5 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @click.stop>
                                </div>
                                <ul class="py-1 text-xs max-h-48 overflow-y-auto">
                                    <li @click="selectBuild('')" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-600 font-bold">Todos los edificios</li>
                                    <template x-for="item in filteredBuildings" :key="item.id">
                                        <li @click="selectBuild(item.id)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-700"
                                            :class="selectedBuild == item.id ? 'bg-blue-50 text-blue-700 font-bold' : ''" x-text="item.name"></li>
                                    </template>
                                    <li x-show="filteredBuildings.length === 0" class="px-4 py-3 text-center text-slate-400 font-bold">No hay resultados</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dependencia con Buscador -->
                        <div class="relative" @click.away="depOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <button type="button" @click="depOpen = !depOpen" class="w-full flex items-center justify-between border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <span class="block truncate" x-text="dependencies.find(d => d.id == selectedDep)?.name || 'Todas las dependencias'"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="depOpen" x-transition x-cloak class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                    <input type="text" x-model="depSearch" placeholder="Buscar dependencia..." class="w-full px-3 py-1.5 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @click.stop>
                                </div>
                                <ul class="py-1 text-xs max-h-48 overflow-y-auto">
                                    <li @click="selectDep('')" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-600 font-bold">Todas las dependencias</li>
                                    <template x-for="item in filteredDependencies" :key="item.id">
                                        <li @click="selectDep(item.id)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-slate-700"
                                            :class="selectedDep == item.id ? 'bg-blue-50 text-blue-700 font-bold' : ''" x-text="item.name"></li>
                                    </template>
                                    <li x-show="filteredDependencies.length === 0" class="px-4 py-3 text-center text-slate-400 font-bold">No hay resultados</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- CONTROLES DE ACCIÓN (Añadidos de nuevo para aplicar cambios en lote) -->
                    <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-4 mt-4 border-t border-slate-100">
                        <button type="button" @click="clearFilters()" class="w-full sm:w-auto px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-widest transition-all text-center">
                            Limpiar Filtros
                        </button>
                        <button type="button" @click="submitForm()" class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-200 transition-all flex justify-center items-center gap-2 cursor-pointer active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.707 7.293A1 1 0 013.414 6.586V4z" />
                            </svg>
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <!-- RESULTADOS Y KPIs -->
            <div id="kpis-container">
                @if(isset($kpis))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg></div>
                        <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Filtro</p><p class="text-xl font-extrabold text-slate-800">{{ $kpis['total'] }}</p></div>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Preventivos</p><p class="text-xl font-extrabold text-slate-800">{{ $kpis['preventivos'] }}</p></div>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                        <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Correctivos</p><p class="text-xl font-extrabold text-slate-800">{{ $kpis['correctivos'] }}</p></div>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div>
                        <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Este Mes</p><p class="text-xl font-extrabold text-slate-800">{{ $kpis['este_mes'] }}</p></div>
                    </div>
                </div>
                @endif
            </div>

            <!-- CONTENEDOR DE TABLA (Usado para reemplazo AJAX sin parpadeo) -->
            <div id="table-container" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative z-10 transition-opacity duration-300">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                                <th class="px-5 py-4 w-16 text-center">ID</th>
                                <th class="px-5 py-4">Equipo / Placa</th>
                                <th class="px-5 py-4">Ubicación</th>
                                <th class="px-5 py-4">Detalle de Intervención</th>
                                <th class="px-5 py-4">Tipo & Estado</th>
                                <th class="px-5 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-100">
                            @forelse($services as $service)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-5 py-4 text-center">
                                    <span class="font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">OP-{{ $service->id }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-slate-800">{{ $service->asset->serial_number ?? 'S/N' }}</p>
                                            <p class="text-[10px] font-bold text-slate-500">{{ $service->asset->internal_code ?? 'Sin Placa' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-700">{{ $service->asset->room->nomenclatura ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-slate-500 uppercase">{{ $service->asset->room->building->name ?? 'Edificio N/A' }}</p>
                                </td>
                                <td class="px-5 py-4 max-w-xs">
                                    <p class="font-medium text-slate-600 line-clamp-2" title="{{ strip_tags($service->description) }}">{{ Str::limit(strip_tags($service->description), 60) }}</p>
                                    <div class="flex items-center gap-2 mt-1.5 text-[10px]">
                                        <span class="text-blue-600 font-bold flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> {{ \Carbon\Carbon::parse($service->performed_at)->format('d M Y') }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-slate-500 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> {{ substr($service->technician->name ?? 'N/A', 0, 15) }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-1.5 items-start">
                                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border {{ strtoupper($service->type) == 'PREVENTIVO' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (strtoupper($service->type) == 'CORRECTIVO' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200') }}">
                                            {{ $service->type }}
                                        </span>
                                        <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border border-slate-200 bg-white text-slate-500 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $service->status == 'EN PROCESO' ? 'bg-amber-400 animate-pulse' : 'bg-blue-500' }}"></span>
                                            {{ $service->status ?? 'CONSOLIDADO' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('maintenances.edit', $service) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('maintenances.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta bitácora?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                    <p class="font-bold text-sm">No se encontraron registros</p>
                                    <p class="text-xs">Modifique los filtros o registre una nueva bitácora.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bitacoraEngine', () => ({
                // Añadimos el estado del acordeón
                showFilters: false,

                searchQuery: '',
                technicians: [], categories: [], buildings: [], dependencies: [],
                
                techOpen: false, catOpen: false, buildOpen: false, depOpen: false,
                
                techSearch: '', catSearch: '', buildSearch: '', depSearch: '',
                
                selectedTech: '', selectedCat: '', selectedBuild: '', selectedDep: '',
                isLoading: false, filtersLoaded: false,

                init() {
                    const params = new URLSearchParams(window.location.search);
                    this.searchQuery = params.get('search') || '';
                    this.selectedTech = params.get('technician_id') || '';
                    this.selectedCat = params.get('type') || '';
                    this.selectedBuild = params.get('building_id') || '';
                    this.selectedDep = params.get('dependency_id') || '';

                    // Si hay algún filtro activo al cargar, abrimos el acordeón automáticamente
                    if (this.searchQuery || this.selectedTech || this.selectedCat || this.selectedBuild || this.selectedDep) {
                        this.showFilters = true;
                    }

                    this.fetchFilters();
                },

                ensureArray(res) {
                    if (!res) return [];
                    if (Array.isArray(res)) return res;
                    if (res.data && Array.isArray(res.data)) return res.data;
                    if (typeof res === 'object') return Object.values(res);
                    return [];
                },

                get filteredTechnicians() {
                    if (this.techSearch === '') return this.technicians;
                    return this.technicians.filter(i => (i.name || '').toLowerCase().includes(this.techSearch.toLowerCase()));
                },
                get filteredCategories() {
                    if (this.catSearch === '') return this.categories;
                    return this.categories.filter(i => (i.name || i.description || '').toLowerCase().includes(this.catSearch.toLowerCase()));
                },
                get filteredBuildings() {
                    if (this.buildSearch === '') return this.buildings;
                    return this.buildings.filter(i => (i.name || '').toLowerCase().includes(this.buildSearch.toLowerCase()));
                },
                get filteredDependencies() {
                    if (this.depSearch === '') return this.dependencies;
                    return this.dependencies.filter(i => (i.name || '').toLowerCase().includes(this.depSearch.toLowerCase()));
                },

                selectTech(id) { this.selectedTech = id; this.techOpen = false; this.techSearch = ''; },
                selectCat(id) { this.selectedCat = id; this.catOpen = false; this.catSearch = ''; },
                selectBuild(id) { this.selectedBuild = id; this.buildOpen = false; this.buildSearch = ''; },
                selectDep(id) { this.selectedDep = id; this.depOpen = false; this.depSearch = ''; },

                async submitForm() {
                    const url = new URL(window.location.href);
                    
                    if (this.searchQuery) url.searchParams.set('search', this.searchQuery); else url.searchParams.delete('search');
                    if (this.selectedTech) url.searchParams.set('technician_id', this.selectedTech); else url.searchParams.delete('technician_id');
                    if (this.selectedCat) url.searchParams.set('type', this.selectedCat); else url.searchParams.delete('type');
                    if (this.selectedBuild) url.searchParams.set('building_id', this.selectedBuild); else url.searchParams.delete('building_id');
                    if (this.selectedDep) url.searchParams.set('dependency_id', this.selectedDep); else url.searchParams.delete('dependency_id');

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

                        const kpisContainer = document.getElementById('kpis-container');
                        const newKpis = doc.getElementById('kpis-container');
                        if (kpisContainer && newKpis) kpisContainer.innerHTML = newKpis.innerHTML;

                    } catch (e) {
                        console.error('Error al actualizar tabla:', e);
                        window.location.href = url.toString(); 
                    }

                    if (tableContainer) tableContainer.style.opacity = '1';
                },

                clearFilters() {
                    window.location.href = window.location.pathname;
                },

                async fetchFilters() {
                    this.isLoading = true;
                    try {
                        const [techRes, catRes, buildRes, depRes] = await Promise.all([
                            fetch('/api/filters/api/sigma-filters/technicians').then(r => r.json()),
                            fetch('/api/filters/api/sigma-filters/categories').then(r => r.json()),
                            fetch('/api/filters/api/sigma-filters/buildings').then(r => r.json()),
                            fetch('/api/filters/api/sigma-filters/dependencies').then(r => r.json())
                        ]);

                        this.technicians = this.ensureArray(techRes);
                        this.categories = this.ensureArray(catRes);
                        this.buildings = this.ensureArray(buildRes);
                        this.dependencies = this.ensureArray(depRes);

                        this.filtersLoaded = true;
                    } catch (e) { console.error('Error AJAX:', e); }
                    this.isLoading = false;
                }
            }));
        });
    </script>
</x-app-layout>