<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="buildingsEngine()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <!-- Línea decorativa izquierda en tono Azul Institucional -->
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <!-- Icono institucional -->
                    <div class="w-16 h-16 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-inner shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Listado de Bloques</h2>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-widest border border-slate-200 shadow-sm">
                                {{ isset($buildings) && method_exists($buildings, 'total') ? $buildings->total() : ($buildings->count() ?? 0) }} Registros
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mt-1">
                            Gestione los bloques, edificios o zonas pertenecientes a cada sede institucional.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('buildings.create') }}" class="inline-flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 focus:outline-none text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo Bloque
                    </a>
                </div>
            </div>

            <!-- BARRA DE BÚSQUEDA Y FILTROS -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 relative z-30">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                    
                    <!-- Buscador Inteligente -->
                    <div class="relative w-full lg:w-96 flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" x-model="search" @input.debounce.500ms="applyFilters()" placeholder="Buscar bloque o edificio..."
                            class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-sm">
                    </div>

                    <!-- Filtros Rápidos -->
                    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                        
                        <!-- Dropdown Filtro Sede (AJAX Real) -->
                        <div class="relative" @click.away="campusOpen = false">
                            <button @click="campusOpen = !campusOpen" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 hover:bg-slate-100 transition-colors w-full sm:w-auto focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <span x-text="selectedCampusName || 'Filtrar por Sede'"></span>
                            </button>
                            
                            <div x-show="campusOpen" x-transition x-cloak class="absolute right-0 sm:left-0 mt-2 w-full sm:w-72 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="p-2 border-b border-slate-100 bg-slate-50 relative">
                                    <input type="text" x-model="campusSearch" @input.debounce.300ms="fetchCampuses()" placeholder="Buscar sede..." class="w-full px-3 py-2 pr-8 text-xs bg-white border border-slate-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-sm outline-none" @click.stop>
                                    
                                    <!-- Spinner de carga AJAX -->
                                    <div x-show="isFetchingCampuses" class="absolute right-4 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-3.5 w-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                                <ul class="max-h-56 overflow-y-auto py-1 text-sm">
                                    <li @click="setCampus('', ''); campusOpen = false" class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer text-slate-500 font-bold border-b border-slate-100 transition-colors">
                                        Todas las Sedes
                                    </li>
                                    
                                    <!-- Iteración dinámica AJAX -->
                                    <template x-for="campus in campuses" :key="campus.id">
                                        <li @click="setCampus(campus.id, campus.name); campusOpen = false" class="px-4 py-2 hover:bg-slate-50 cursor-pointer text-slate-700 font-medium truncate flex items-center gap-2 transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span x-text="campus.name"></span>
                                        </li>
                                    </template>
                                    
                                    <!-- Estado vacío -->
                                    <li x-show="campuses.length === 0 && !isFetchingCampuses" class="px-4 py-4 text-xs text-slate-400 text-center font-medium">
                                        No se encontraron resultados
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="h-8 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                        <!-- Botón Limpiar Filtros -->
                        <button @click="clearFilters()" class="p-2.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl border border-transparent transition-all focus:outline-none" title="Limpiar Filtros">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CONTENIDO PRINCIPAL (TABLA) -->
            <div id="table-container" class="relative z-10 transition-opacity duration-300">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                                    <th class="px-6 py-4 w-24">ID</th>
                                    <th class="px-6 py-4">Nombre del Bloque</th>
                                    <th class="px-6 py-4">Sede Asignada</th>
                                    <th class="px-6 py-4 text-center">Métricas</th>
                                    <th class="px-6 py-4 text-center w-32">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($buildings as $building)
                                    @php
                                        // Calcular métricas del bloque para mostrar y proteger eliminación
                                        $oficinas = method_exists($building, 'rooms') ? $building->rooms()->count() : 0;
                                        $equipos = method_exists($building, 'assets') ? $building->assets()->count() : 0;
                                        
                                        // Bandera de relaciones activas
                                        $hasRelations = ($oficinas > 0 || $equipos > 0) ? 'true' : 'false';
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <!-- ID -->
                                        <td class="px-6 py-5">
                                            <span class="font-mono text-xs font-bold text-slate-400">
                                                #{{ str_pad($building->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        
                                        <!-- Nombre del Bloque -->
                                        <td class="px-6 py-5">
                                            <span class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-base">
                                                {{ $building->name }}
                                            </span>
                                        </td>
                                        
                                        <!-- Sede Asignada -->
                                        <td class="px-6 py-5">
                                            @if($building->campus)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $building->campus->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Sin Sede Asignada
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Métricas -->
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex items-center justify-center gap-4 text-center">
                                                <div title="Oficinas Físicas">
                                                    <p class="text-lg font-black text-slate-800">{{ $oficinas }}</p>
                                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ofc</p>
                                                </div>
                                                <div class="w-px h-6 bg-slate-200"></div>
                                                <div title="Activos Registrados">
                                                    <p class="text-lg font-black text-slate-800">{{ $equipos }}</p>
                                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Activos</p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Acciones -->
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('buildings.edit', $building) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-blue-500" title="Editar Bloque">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                
                                                <!-- Acción destructiva: Envía el flag $hasRelations -->
                                                <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="inline" @submit.prevent="confirmDelete($event, '{{ addslashes($building->name) }}', {{ $hasRelations }})">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-100 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-rose-500" title="Eliminar Bloque">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- ESTADO VACÍO -->
                                    <tr>
                                        <!-- colspan actualizado a 5 por la nueva columna de métricas -->
                                        <td colspan="5" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-5 shadow-inner">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                                <h3 class="text-base font-extrabold text-slate-800 mb-2 tracking-tight">No existen bloques registrados</h3>
                                                <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">Agregue el primer bloque para comenzar la organización de la infraestructura institucional dentro del sistema SIGMA.</p>
                                                <a href="{{ route('buildings.create') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-widest flex items-center gap-2 bg-blue-50 px-6 py-3 rounded-xl border border-blue-100 hover:bg-blue-100 hover:shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    Registrar Primer Bloque
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- FOOTER DE PAGINACIÓN CORPORATIVO -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-500 mt-auto">
                        <div class="w-full sm:w-auto">
                            @if(isset($buildings) && method_exists($buildings, 'hasPages') && $buildings->hasPages())
                                {{ $buildings->appends(request()->query())->links() }}
                            @else
                                <span class="uppercase tracking-wider">Fin de los registros</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Mostrar</span>
                            <!-- SELECTOR DE PAGINACIÓN DINÁMICO -->
                            <select x-model="perPage" @change="submitForm()" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-bold cursor-pointer shadow-sm bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>registros</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT ALPINE -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('buildingsEngine', () => ({
                // Buscadores y Filtros
                search: new URLSearchParams(window.location.search).get('search') || '',
                campusSearch: '',
                selectedCampus: new URLSearchParams(window.location.search).get('campus_id') || '', 
                selectedCampusName: '', // Para mostrar el nombre en el botón
                campusOpen: false,
                perPage: new URLSearchParams(window.location.search).get('per_page') || '15',
                
                // Arreglo que contendrá los datos reales de la base de datos
                campuses: [],
                isFetchingCampuses: false,

                init() {
                    // Cargamos los campus iniciales en cuanto se renderiza la vista
                    this.fetchCampuses();
                },

                // Llamada AJAX apuntando a tu ruta
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
                            
                            // Mantiene el nombre de la sede seleccionada visible si la URL tiene ?campus_id=X
                            if(this.selectedCampus && !this.selectedCampusName && this.campuses.length > 0) {
                                const selected = this.campuses.find(c => c.id == this.selectedCampus);
                                if(selected) {
                                    this.selectedCampusName = selected.name;
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error cargando las sedes:', error);
                    } finally {
                        this.isFetchingCampuses = false;
                    }
                },

                setCampus(id, name) { 
                    this.selectedCampus = id; 
                    this.selectedCampusName = name;
                    this.applyFilters(); 
                },

                applyFilters() {
                    this.submitForm();
                },

                clearFilters() {
                    this.search = '';
                    this.selectedCampus = '';
                    this.selectedCampusName = '';
                    this.campusSearch = '';
                    this.submitForm();
                    this.fetchCampuses(); // Refrescar lista limpia
                },

                // LÓGICA DE ELIMINACIÓN SEGURA CON SWEETALERT2
                confirmDelete(event, name, hasRelations) {
                    // Validamos si el bloque tiene registros dependientes
                    if (hasRelations) {
                        Swal.fire({
                            title: 'Eliminación Bloqueada',
                            html: `El bloque <strong>${name}</strong> no puede ser eliminado porque tiene oficinas o activos registrados actualmente.<br><br>Debe reasignar o eliminar esos registros primero.`,
                            icon: 'error',
                            confirmButtonColor: '#0f172a', // Color slate-900 (Institucional oscuro)
                            confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Entendido</span>',
                            customClass: {
                                popup: 'rounded-2xl shadow-xl border border-slate-100',
                                confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95 focus:ring-4 focus:ring-slate-200 outline-none'
                            }
                        });
                        return; // Detenemos la ejecución aquí, no se envía el formulario
                    }

                    // Si está en blanco, pedimos confirmación normal
                    Swal.fire({
                        title: '¿Eliminar bloque?',
                        html: `Está a punto de eliminar permanentemente el bloque <br><strong class="text-slate-900 text-lg">${name}</strong><br><br>Esta acción no se puede deshacer.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // ESTÁNDAR SIGMA: Acciones destructivas usan Rose (#e11d48)
                        cancelButtonColor: '#f1f5f9', // Color slate-100
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
                    
                    // Parámetros de URL para los filtros y paginación
                    if (this.search) url.searchParams.set('search', this.search); else url.searchParams.delete('search');
                    if (this.selectedCampus) url.searchParams.set('campus_id', this.selectedCampus); else url.searchParams.delete('campus_id');
                    if (this.perPage) url.searchParams.set('per_page', this.perPage); else url.searchParams.delete('per_page');

                    // Reiniciar página a 1 cuando se altera un filtro
                    url.searchParams.delete('page');

                    // Actualizar URL sin recargar
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