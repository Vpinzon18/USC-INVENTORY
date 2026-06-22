<x-app-layout>
    <div x-data="{ viewMode: 'list', showFilters: false }" class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                        </svg>
                        CMDB - Hojas de Vida
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">Gestión centralizada de configuración y activos TI.</p>
                </div>

                <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm transition-all shadow-sm ring-1 ring-blue-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Activo
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition duration-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Activos</div>
                    <div class="text-2xl font-black text-slate-800">{{ $total ?? 0 }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition duration-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-3"><span class="flex w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span></div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">En Línea (10m)</div>
                    <div class="text-2xl font-black text-emerald-600">{{ $online ?? 0 }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition duration-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Desconectados</div>
                    <div class="text-2xl font-black text-rose-600">{{ $offline ?? 0 }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition duration-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Agente Instalado</div>
                    <div class="text-2xl font-black text-blue-600">{{ $agentManaged ?? 0 }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition duration-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sin Responsable</div>
                    <div class="text-2xl font-black text-amber-500">{{ $unassigned ?? 0 }}</div>
                </div>
                <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition duration-200">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Incompletos</div>
                    <div class="text-2xl font-black text-slate-800">{{ $incomplete ?? 0 }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 mb-6 relative z-30">
                <form action="{{ route('assets.index') }}" method="GET" class="w-full"
                    x-data="{ showFilters: {{ request()->hasAny(['campus_id', 'building_id', 'room_id', 'custodian_id', 'os_version', 'agent', 'inventory_status', 'connectivity']) ? 'true' : 'false' }} }">

                    <div class="flex flex-col md:flex-row gap-3 items-center w-full">
                        <div class="relative flex-1 w-full group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por Hostname, IP, Serial o MAC..."
                                class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border-transparent rounded-lg focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm transition-all">
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <button type="button" @click="showFilters = !showFilters"
                                :class="{'bg-blue-50 text-blue-700 border-blue-200': showFilters, 'bg-white text-slate-700 border-slate-300': !showFilters}"
                                class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 py-2.5 border rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filtros
                            </button>
                        </div>
                    </div>

                    <div x-show="showFilters"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="mt-4 pt-4 border-t border-slate-100 relative z-40" style="display: none;">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                            <x-enterprise-select name="campus_id" placeholder="Sede" endpoint="/api/filters/campuses" initial-value="{{ request('campus_id') }}" initial-text="{{ $selectedCampusName ?? '' }}" />
                            <x-enterprise-select name="building_id" placeholder="Bloque / Edificio" endpoint="/api/filters/buildings" initial-value="{{ request('building_id') }}" initial-text="{{ $selectedBuildingName ?? '' }}" />
                            <x-enterprise-select name="room_id" placeholder="Oficina / Salón" endpoint="/api/filters/rooms" initial-value="{{ request('room_id') }}" initial-text="{{ $selectedRoomName ?? '' }}" />
                            <x-enterprise-select name="custodian_id" placeholder="Responsable" endpoint="/api/filters/custodians" initial-value="{{ request('custodian_id') }}" initial-text="{{ $selectedCustodianName ?? '' }}" />

                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Sistema Operativo</label>
                                <select name="os_version" class="w-full text-sm border border-slate-300 rounded-lg shadow-sm h-[42px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                                    <option value="">Cualquier S.O.</option>
                                    @foreach($osVersions ?? [] as $os)
                                    <option value="{{ $os }}" {{ request('os_version') == $os ? 'selected' : '' }}>{{ $os }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Conectividad</label>
                                <select name="connectivity" class="w-full text-sm border border-slate-300 rounded-lg shadow-sm h-[42px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                                    <option value="">Todos los estados</option>
                                    <option value="online" {{ request('connectivity') == 'online' ? 'selected' : '' }}>🟢 En Línea</option>
                                    <option value="offline" {{ request('connectivity') == 'offline' ? 'selected' : '' }}>🔴 Desconectados</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Ficha Técnica</label>
                                <select name="inventory_status" class="w-full text-sm border border-slate-300 rounded-lg shadow-sm h-[42px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                                    <option value="">Completa / Incompleta</option>
                                    <option value="completo" {{ request('inventory_status') == 'completo' ? 'selected' : '' }}>✔ Inventario Completo</option>
                                    <option value="incompleto" {{ request('inventory_status') == 'incompleto' ? 'selected' : '' }}>⚠ Pendiente Físico</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Gestión de Agente</label>
                                <select name="agent" class="w-full text-sm border border-slate-300 rounded-lg shadow-sm h-[42px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all">
                                    <option value="">Agente SIGMA</option>
                                    <option value="1" {{ request('agent') == '1' ? 'selected' : '' }}>Instalado</option>
                                    <option value="0" {{ request('agent') == '0' ? 'selected' : '' }}>Sin Agente</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end items-center gap-3">
                            <a href="{{ route('assets.index') }}" class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">Limpiar todo</a>
                            <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">Aplicar Filtros</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="relative z-10" :class="{'space-y-3': viewMode === 'list', 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4': viewMode === 'grid'}">

                @forelse ($assets as $asset)
                <div class="group bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-200 relative overflow-hidden flex flex-col"
                    :class="{'lg:flex-row': viewMode === 'list'}">

                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->gt(now()->subMinutes(10)) ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>

                    <div class="p-4 md:p-5 flex-1 flex items-start gap-4 min-w-[250px]">
                        <div class="shrink-0 w-12 h-12 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center relative">
                            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white {{ $asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->gt(now()->subMinutes(10)) ? 'bg-emerald-500' : 'bg-rose-500' }}" title="{{ $asset->last_seen_at ? 'Última conexión: ' . \Carbon\Carbon::parse($asset->last_seen_at)->diffForHumans() : 'Sin conexión' }}"></span>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-lg text-slate-800 leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ $asset->hostname ?? 'SIN-HOSTNAME' }}
                                </h3>
                                @if($asset->is_agent_managed)
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide border border-blue-200">Agente SIGMA</span>
                                @else
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide border border-slate-200">Sin Agente</span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-500 mt-1 font-mono uppercase">{{ $asset->serial_number }}</div>
                            <div class="text-xs font-semibold text-slate-600 mt-0.5">{{ $asset->model_version ?? 'Modelo Desconocido' }}</div>
                        </div>
                    </div>

                    <div class="p-4 md:p-5 border-t lg:border-t-0 lg:border-l border-slate-100 grid grid-cols-2 md:grid-cols-3 gap-4 flex-[2]"
                        :class="{'w-full': viewMode === 'grid'}">

                        <div class="flex flex-col gap-1.5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Asignación</div>
                            <div class="text-sm font-medium text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">{{ $asset->currentCustodian->full_name ?? 'No Asignado' }}</span>
                            </div>
                            <div class="text-xs text-slate-500 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $asset->room->nomenclatura ?? 'Bodega / Sin ubicación' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Red & S.O.</div>
                            <div class="text-sm font-medium text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate font-mono">{{ $asset->ip_address ?? '0.0.0.0' }}</span>
                            </div>
                            <div class="text-xs text-slate-500 truncate" title="{{ $asset->os_version }}">
                                {{ $asset->os_version ?? 'SO no reportado' }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5 col-span-2 md:col-span-1">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ficha Técnica</div>
                            <div class="mt-1">
                                @if($asset->monitor_serial && $asset->keyboard_serial && $asset->security_guaya)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Inventario Completo
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Pendiente Revisión
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="p-4 bg-slate-50 lg:bg-transparent border-t lg:border-t-0 lg:border-l border-slate-100 flex flex-row flex-wrap items-center justify-end gap-2 shrink-0 lg:w-auto"
                        :class="{'flex-row bg-slate-50 border-t': viewMode === 'grid'}">

                        <a href="{{ route('assets.preview', $asset->id) }}" target="_blank"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-100 hover:border-blue-600 rounded-lg text-sm font-semibold transition-all shadow-sm"
                            title="Ver Hoja de Vida Completa">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Ver</span>
                        </a>

                        <a href="{{ route('assets.edit', $asset) }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300 rounded-lg text-sm font-medium transition-all shadow-sm"
                            title="Editar Atributos">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Editar</span>
                        </a>

                        <a href="{{ route('assets.download.pdf', $asset->id) }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 border border-slate-200 hover:border-indigo-200 rounded-lg text-sm font-medium transition-all shadow-sm"
                            title="Descargar Acta PDF">
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="hidden sm:inline">PDF</span>
                        </a>

                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="inline-block m-0 p-0 form-baja-activo">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 rounded-lg text-sm font-medium transition-all shadow-sm"
                                title="Dar de Baja">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="hidden sm:inline">Baja</span>
                            </button>
                        </form>

                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center bg-white rounded-xl border border-dashed border-slate-300 flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No se encontraron activos</h3>
                    <p class="text-slate-500 max-w-sm mt-2 text-sm">Prueba ajustando los filtros de búsqueda o registra un nuevo equipo en el CMDB de SIGMA.</p>
                </div>
                @endforelse

            </div>

            <div class="mt-6 relative z-10">
                {{ $assets->links() }}
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formsBaja = document.querySelectorAll('.form-baja-activo');

            formsBaja.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Dar de baja este activo?',
                        text: "Esta acción eliminará el equipo permanentemente del sistema SIGMA. No se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Sí, dar de baja',
                        cancelButtonText: 'Cancelar',
                        background: '#ffffff',
                        customClass: {
                            title: 'text-slate-800 font-bold',
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout> 