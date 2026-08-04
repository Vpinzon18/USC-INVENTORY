<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 bg-slate-50 min-h-screen font-sans text-slate-800" x-data="categoriesEngine()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight">Tipos de Intervenciones</h2>
                            <span class="bg-blue-50 text-blue-700 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-widest border border-blue-200 shadow-sm hidden sm:inline-block">
                                Configuración de Sistema
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 mt-1 max-w-2xl leading-relaxed">
                            Administre los tipos de intervenciones técnicas utilizadas durante la atención de incidentes, mantenimientos, instalaciones y solicitudes registradas en SIGMA.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('categories.create') }}" class="inline-flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 focus:outline-none text-white font-bold text-sm px-6 py-3 rounded-xl shadow-md transition-all active:scale-95 uppercase tracking-wider">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nueva Categoría
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-2xl p-4 shadow-sm flex items-start gap-4 animate-fade-in-down">
                    <div class="bg-white p-2 rounded-xl shrink-0 shadow-sm border border-emerald-100 mt-0.5">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div class="pt-1">
                        <h3 class="text-sm font-bold text-emerald-800">{{ session('success') }}</h3>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 rounded-2xl p-4 shadow-sm flex items-start gap-4 animate-fade-in-down">
                    <div class="bg-white p-2 rounded-xl shrink-0 shadow-sm border border-rose-100 mt-0.5">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="pt-1">
                        <h3 class="text-sm font-bold text-rose-800">{{ session('error') }}</h3>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 relative z-30">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                    
                    <div class="relative w-full lg:w-[28rem] flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" x-model="search" @input.debounce.500ms="applyFilters()" placeholder="Buscar categoría..."
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-sm outline-none">
                    </div>

                    <div class="flex gap-3 w-full lg:w-auto">
                        <button type="button" @click="applyFilters()" class="flex-1 lg:flex-none px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold uppercase tracking-widest shadow-md transition-all active:scale-95 focus:outline-none focus:ring-4 focus:ring-blue-200">
                            Buscar
                        </button>
                        
                        <button type="button" @click="clearFilters()" x-show="search.length > 0" x-transition class="flex-1 lg:flex-none flex items-center justify-center px-8 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-extrabold uppercase tracking-widest transition-all focus:outline-none focus:ring-4 focus:ring-slate-100 border border-transparent shadow-sm" x-cloak>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <div id="table-container" class="relative z-10 transition-opacity duration-300">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                                    <th class="px-6 py-5 w-24">ID</th>
                                    <th class="px-6 py-5">Tipo de Intervención</th>
                                    <th class="px-6 py-5 w-48 text-center">Fecha de Registro</th>
                                    <th class="px-6 py-5 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs font-bold text-slate-400">
                                                #{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4">
                                            <span class="font-extrabold text-slate-800 uppercase group-hover:text-blue-600 transition-colors text-sm block">
                                                {{ $category->name }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-medium text-slate-500 inline-flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $category->created_at ? $category->created_at->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('categories.edit', $category) }}" class="p-2 text-slate-400 bg-transparent hover:bg-amber-50 hover:text-amber-600 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500" title="Editar Categoría">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" @submit.prevent="confirmDelete($event, '{{ addslashes($category->name) }}')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 bg-transparent hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-rose-500" title="Eliminar Categoría">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-24 text-center bg-white">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-6 shadow-inner border border-slate-100">
                                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                </div>
                                                <h3 class="text-lg font-extrabold text-slate-800 mb-2 tracking-tight">No se encontraron tipos de intervenciones</h3>
                                                <p class="text-sm font-medium text-slate-500 mb-8 max-w-md mx-auto leading-relaxed">Aún no existen categorías registradas para clasificar las intervenciones técnicas del sistema.</p>
                                                <a href="{{ route('categories.create') }}" class="text-xs font-extrabold text-white hover:bg-blue-700 transition-colors uppercase tracking-widest flex items-center gap-2 bg-blue-600 px-8 py-3.5 rounded-xl shadow-md active:scale-95">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    Registrar Primera Categoría
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
                            @if(isset($categories) && method_exists($categories, 'hasPages') && $categories->hasPages())
                                {{ $categories->appends(request()->query())->links() }}
                            @else
                                <span class="uppercase tracking-wider">Mostrando {{ isset($categories) ? $categories->count() : 0 }} registros</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="uppercase tracking-widest text-[10px] font-black">Mostrar</span>
                            <select x-model="perPage" @change="submitForm()" class="border-slate-300 rounded-lg py-1.5 px-3 text-xs font-bold cursor-pointer shadow-sm bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
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
            Alpine.data('categoriesEngine', () => ({
                search: new URLSearchParams(window.location.search).get('search') || '',
                perPage: new URLSearchParams(window.location.search).get('per_page') || '15',
                
                applyFilters() {
                    this.submitForm();
                },

                clearFilters() {
                    this.search = '';
                    this.submitForm();
                },

                // LÓGICA DE ELIMINACIÓN SEGURA CON SWEETALERT2
                confirmDelete(event, name) {
                    Swal.fire({
                        title: '¿Eliminar categoría?',
                        html: `Está a punto de eliminar permanentemente el tipo de intervención: <br><strong class="text-slate-900 text-lg uppercase mt-2 inline-block">${name}</strong><br><br><span class="text-sm font-medium text-slate-500">Asegúrese de que no haya intervenciones vinculadas a esta categoría.</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // Rose-600 para eliminación
                        cancelButtonColor: '#f1f5f9', // Slate claro
                        confirmButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-white">Eliminar</span>',
                        cancelButtonText: '<span class="font-bold text-xs uppercase tracking-widest text-slate-700">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-8 py-3 transition-all hover:scale-95 focus:ring-4 focus:ring-rose-200 outline-none',
                            cancelButton: 'rounded-xl px-8 py-3 border border-slate-200 transition-all hover:bg-slate-200 mr-3 focus:ring-4 focus:ring-slate-200 outline-none'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            event.target.submit();
                        }
                    });
                },

                // LÓGICA AJAX PARA BÚSQUEDA Y PAGINACIÓN
                async submitForm() {
                    const url = new URL(window.location.href);
                    
                    if (this.search) url.searchParams.set('search', this.search); else url.searchParams.delete('search');
                    if (this.perPage) url.searchParams.set('per_page', this.perPage); else url.searchParams.delete('per_page');

                    // Reiniciar paginación a página 1 cuando se alteran filtros
                    url.searchParams.delete('page');

                    // Actualizamos URL de forma invisible
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
                        
                        // Inyectamos el nuevo contenido renderizado de la tabla
                        const newTable = doc.getElementById('table-container');
                        if (tableContainer && newTable) tableContainer.innerHTML = newTable.innerHTML;

                    } catch (e) {
                        console.error('Error al actualizar tabla AJAX:', e);
                        // Fallback seguro: recarga tradicional
                        window.location.href = url.toString(); 
                    }

                    if (tableContainer) tableContainer.style.opacity = '1';
                }
            }));
        });
    </script>

    <style>
        /* Animación para las alertas y estado vacío */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translate3d(0, -10px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.4s ease-out forwards;
        }
    </style>
</x-app-layout>