<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="campusEngine()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <!-- ENCABEZADO EJECUTIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-blue-00 shadow-inner shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Sedes y Convenios</h2>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-widest border border-slate-200">
                                {{ isset($campuses) && method_exists($campuses, 'total') ? $campuses->total() : ($campuses->count() ?? 0) }} Registros
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mt-1">
                            Administración centralizada de infraestructura y distribución geográfica SIGMA.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('campuses.create') }}" class="inline-flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nueva Sede
                    </a>
                </div>
            </div>

            <!-- BARRA DE BÚSQUEDA Y CONTROLES -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 relative z-30">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                    
                    <!-- Buscador Inteligente -->
                    <div class="relative w-full lg:w-96">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" x-model="search" @input.debounce.500ms="applyFilters()" placeholder="Buscar sede..."
                            class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all shadow-sm">
                    </div>

                    <!-- Vistas -->
                    <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                        <!-- Toggle View -->
                        <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                            <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-400 hover:text-slate-700'" class="p-1.5 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                            </button>
                            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-400 hover:text-slate-700'" class="p-1.5 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENIDO PRINCIPAL (TABLA O TARJETAS) -->
            <div id="table-container" class="relative z-10 transition-opacity duration-300">
                
                <!-- VISTA DE TABLA -->
                <div x-show="viewMode === 'table'" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                                    <th class="px-6 py-4">Sede / Ubicación</th>
                                    <th class="px-6 py-4 text-center">Estado Operativo</th>
                                    <th class="px-6 py-4 text-center">Métricas</th>
                                    <th class="px-6 py-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($campuses as $campus)
                                    @php
                                        // Validación segura y directa con Eloquent
                                        $estado = $campus->status ?? 'Activa';
                                        $bloques = $campus->buildings()->count();
                                        $oficinas = $campus->rooms()->count();
                                        $equipos = $campus->assets()->count();
                                        
                                        $statusColor = 'bg-emerald-500';
                                        if($estado !== 'Activa') $statusColor = 'bg-rose-500';

                                        // Para el SweetAlert de borrado seguro
                                        $hasRelations = ($bloques > 0 || $oficinas > 0 || $equipos > 0) ? 'true' : 'false';
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                                </div>
                                                <div>
                                                    <p class="font-extrabold text-slate-900">
                                                        {{ $campus->name }}
                                                    </p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="text-[10px] font-mono text-slate-400 font-bold border border-slate-200 rounded px-1.5">#{{ str_pad($campus->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                        @if(!empty($campus->address))
                                                            <span class="text-[10px] text-slate-500">{{ $campus->address }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border border-slate-200 bg-white text-slate-600">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $statusColor }}"></span>
                                                {{ $estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- Métricas simplificadas -->
                                            <div class="flex items-center justify-center gap-4 text-center">
                                                <div title="Bloques Registrados">
                                                    <p class="text-lg font-black text-slate-800">{{ $bloques }}</p>
                                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Blq</p>
                                                </div>
                                                <div class="w-px h-6 bg-slate-200"></div>
                                                <div title="Oficinas Registradas">
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
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                
                                                <!-- Acción Principal 1: Editar -->
                                                <a href="{{ route('campuses.edit', $campus) }}" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors" title="Editar Sede">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                
                                                <!-- Acción Principal 2: Eliminar (Con SweetAlert) -->
                                                <form action="{{ route('campuses.destroy', $campus) }}" method="POST" class="inline" @submit.prevent="confirmDelete($event, '{{ addslashes($campus->name) }}', {{ $hasRelations }})">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar Sede">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                </div>
                                                <h3 class="text-sm font-bold text-slate-700 mb-1">No hay sedes registradas</h3>
                                                <p class="text-xs text-slate-500 mb-4">Comience registrando una nueva ubicación en el sistema.</p>
                                                <a href="{{ route('campuses.create') }}" class="text-xs font-bold text-slate-900 hover:text-blue-600 transition-colors uppercase flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    Registrar Sede
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- FOOTER DE PAGINACIÓN -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-500 mt-auto">
                        <div class="w-full sm:w-auto">
                            @if(isset($campuses) && method_exists($campuses, 'hasPages') && $campuses->hasPages())
                                {{ $campuses->appends(request()->query())->links() }}
                            @else
                                <span class="uppercase tracking-wider">Fin de los registros</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Mostrar</span>
                            <select x-model="perPage" @change="submitForm()" class="border-slate-300 rounded-md py-1 px-2 text-xs font-bold cursor-pointer shadow-sm bg-white focus:ring-2 focus:ring-slate-900 focus:border-slate-900 outline-none">
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

                <!-- VISTA DE TARJETAS (GRID) -->
                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($campuses as $campus)
                        @php
                            $estado = $campus->status ?? 'Activa';
                            $bloques = $campus->buildings()->count();
                            $oficinas = $campus->rooms()->count();
                            $equipos = $campus->assets()->count();
                            $hasRelations = ($bloques > 0 || $oficinas > 0 || $equipos > 0) ? 'true' : 'false';
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col hover:shadow-md transition-shadow group relative overflow-hidden">
                            <!-- Acabado Top Decorativo -->
                            <div class="absolute top-0 left-0 right-0 h-1.5 {{ $estado == 'Activa' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                            
                            <div class="flex justify-between items-start mb-4 mt-2">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                                <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $estado == 'Activa' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    {{ $estado }}
                                </span>
                            </div>
                            
                            <h3 class="font-extrabold text-lg text-slate-900 leading-tight mb-1">{{ $campus->name }}</h3>
                            <p class="text-[11px] font-medium text-slate-500 flex items-center gap-1 mb-4">
                                {{ $campus->address ?? 'Dirección no registrada' }}
                            </p>

                            <div class="grid grid-cols-2 gap-2 mb-5 mt-auto">
                                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Bloques</p>
                                    <p class="text-sm font-extrabold text-slate-800">{{ $bloques }}</p>
                                </div>
                                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Activos</p>
                                    <p class="text-sm font-extrabold text-slate-800">{{ $equipos }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                                <a href="{{ route('campuses.edit', $campus) }}" class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold py-2 rounded-lg transition-colors text-center">
                                    Editar Sede
                                </a>
                                <form action="{{ route('campuses.destroy', $campus) }}" method="POST" class="inline" @submit.prevent="confirmDelete($event, '{{ addslashes($campus->name) }}', {{ $hasRelations }})">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT ALPINE -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('campusEngine', () => ({
                viewMode: window.innerWidth < 768 ? 'grid' : 'table',
                
                // Buscador y Paginador
                search: new URLSearchParams(window.location.search).get('search') || '',
                perPage: new URLSearchParams(window.location.search).get('per_page') || '15',

                init() {
                    window.addEventListener('resize', () => {
                        if (window.innerWidth < 768) {
                            this.viewMode = 'grid';
                        }
                    });
                },

                applyFilters() {
                    this.submitForm();
                },

                // LÓGICA DE ELIMINACIÓN SEGURA CON SWEETALERT2
                confirmDelete(event, campusName, hasRelations) {
                    if (hasRelations) {
                        Swal.fire({
                            title: 'Eliminación Bloqueada',
                            html: `La sede <strong>${campusName}</strong> no puede ser eliminada porque tiene bloques, oficinas o activos registrados actualmente.<br><br>Debe reasignar o eliminar esos registros primero.`,
                            icon: 'error',
                            confirmButtonColor: '#0f172a',
                            confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest">Entendido</span>',
                            customClass: {
                                popup: 'rounded-2xl shadow-xl border border-slate-100',
                                confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95'
                            }
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Eliminar sede',
                        html: `Está a punto de eliminar:<br><strong class="text-slate-900 text-lg">${campusName}</strong><br><br>Esta acción no podrá deshacerse.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#f1f5f9',
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Eliminar</span>',
                        cancelButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-slate-700">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95',
                            cancelButton: 'rounded-xl px-6 py-2.5 border border-slate-200 transition-all hover:bg-slate-200 mr-3'
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
                    if (this.perPage) url.searchParams.set('per_page', this.perPage); else url.searchParams.delete('per_page');

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
                        
                        // Actualizar solo el contenedor de la tabla y grid
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