<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="roomTypesEngine()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <!-- ENCABEZADO CORPORATIVO -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <div class="w-16 h-16 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Tipos de Espacio</h2>
                            <span class="bg-blue-50 text-blue-700 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-widest border border-blue-200">
                                Configuración de Sistema
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mt-1">
                            Gestione las categorías dinámicas (Oficinas, Laboratorios, Auditorios) para la clasificación de ubicaciones.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('admin.room_types.create') }}" class="inline-flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 focus:outline-none text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition-all active:scale-95 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo Tipo
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
                        <input type="text" x-model="search" @input.debounce.500ms="applyFilters()" placeholder="Buscar tipo o descripción..."
                            class="w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-sm outline-none">
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
                                    <th class="px-6 py-4">Clasificación</th>
                                    <th class="px-6 py-4">Descripción / Uso</th>
                                    <th class="px-6 py-4 text-center">Estado</th>
                                    <th class="px-6 py-4 text-center w-32">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($roomTypes as $type)
                                    @php
                                        // Validación segura de relaciones (rooms_count viene del withCount)
                                        $oficinas = $type->rooms_count ?? 0;
                                        $hasRelations = $oficinas > 0 ? 'true' : 'false';
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <!-- ID -->
                                        <td class="px-6 py-5">
                                            <span class="font-mono text-xs font-bold text-slate-400">
                                                #{{ str_pad($type->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        
                                        <!-- Nombre -->
                                        <td class="px-6 py-5">
                                            <span class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-base block">
                                                {{ $type->name }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                                {{ $oficinas }} asignaciones
                                            </span>
                                        </td>
                                        
                                        <!-- Descripción -->
                                        <td class="px-6 py-5">
                                            <p class="text-xs font-medium text-slate-500 max-w-sm truncate">
                                                {{ $type->description ?? 'Sin descripción administrativa.' }}
                                            </p>
                                        </td>

                                        <!-- Estado -->
                                        <td class="px-6 py-5 text-center">
                                            @if($type->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border border-slate-200 bg-white text-slate-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border border-slate-200 bg-white text-slate-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <!-- Acciones -->
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.room_types.edit', $type) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-blue-500" title="Editar Tipo">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                
                                                <!-- Acción destructiva: Hover Rose -->
                                                <form action="{{ route('admin.room_types.destroy', $type) }}" method="POST" class="inline" @submit.prevent="confirmDelete($event, '{{ addslashes($type->name) }}', {{ $hasRelations }})">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-100 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-rose-500" title="Eliminar Tipo">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- ESTADO VACÍO -->
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-5 shadow-inner">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                                <h3 class="text-base font-extrabold text-slate-800 mb-2 tracking-tight">No existen tipos de espacio</h3>
                                                <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">Agregue configuraciones como Oficinas, Laboratorios o Auditorios para organizar su infraestructura.</p>
                                                <a href="{{ route('admin.room_types.create') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-widest flex items-center gap-2 bg-blue-50 px-6 py-3 rounded-xl border border-blue-100 hover:bg-blue-100 hover:shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    Registrar Primer Tipo
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
                            @if(isset($roomTypes) && method_exists($roomTypes, 'hasPages') && $roomTypes->hasPages())
                                {{ $roomTypes->appends(request()->query())->links() }}
                            @else
                                <span class="uppercase tracking-wider">Fin de los registros</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Mostrar</span>
                            <select x-model="perPage" @change="submitForm()" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-bold cursor-pointer shadow-sm bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
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
            Alpine.data('roomTypesEngine', () => ({
                search: new URLSearchParams(window.location.search).get('search') || '',
                perPage: new URLSearchParams(window.location.search).get('per_page') || '15',
                
                applyFilters() {
                    this.submitForm();
                },

                // LÓGICA DE ELIMINACIÓN SEGURA
                confirmDelete(event, name, hasRelations) {
                    if (hasRelations) {
                        Swal.fire({
                            title: 'Eliminación Bloqueada',
                            html: `El tipo de espacio <strong>${name}</strong> está actualmente asignado a una o más oficinas del sistema.<br><br>Debe reasignar esas oficinas a otro tipo antes de eliminar esta configuración.`,
                            icon: 'error',
                            confirmButtonColor: '#0f172a', 
                            confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Entendido</span>',
                            customClass: {
                                popup: 'rounded-2xl shadow-xl border border-slate-100',
                                confirmButton: 'rounded-xl px-6 py-2.5 transition-all hover:scale-95 focus:ring-4 focus:ring-slate-200 outline-none'
                            }
                        });
                        return;
                    }

                    Swal.fire({
                        title: '¿Eliminar clasificación?',
                        html: `Está a punto de eliminar permanentemente la configuración: <br><strong class="text-slate-900 text-lg">${name}</strong>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
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
                    if (this.perPage) url.searchParams.set('per_page', this.perPage); else url.searchParams.delete('per_page');
                    url.searchParams.delete('page');

                    window.history.pushState({}, '', url.toString());
                    
                    const tableContainer = document.getElementById('table-container');
                    if (tableContainer) tableContainer.style.opacity = '0.5';

                    try {
                        const response = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
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