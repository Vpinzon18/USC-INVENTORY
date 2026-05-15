<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-8 border-b border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-tight">Bitácora de Soporte Técnico</h2>
                        <p class="text-sm text-gray-500 font-medium">Historial global de intervenciones y mantenimientos SOMA</p>
                    </div>
                    <a href="{{ route('maintenances.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-6 rounded-xl text-xs transition-all shadow-lg active:scale-95 uppercase">
                        + Registrar Mantenimiento
                    </a>
                </div>

                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <form action="{{ route('maintenances.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            
                            <div class="md:col-span-7 flex flex-col md:flex-row gap-4 items-end">
                                <div class="w-full md:w-32">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest text-center md:text-left">Ver</label>
                                    <select name="per_page" onchange="this.form.submit()" 
                                            class="w-full border-gray-200 rounded-2xl py-3 text-xs font-bold focus:ring-4 focus:ring-blue-100 transition-all shadow-sm bg-white cursor-pointer">
                                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                        <option value="1000" {{ $perPage == 1000 ? 'selected' : '' }}>Todo</option>
                                    </select>
                                </div>

                                <div class="flex-1 w-full">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Buscador Inteligente</label>
                                    <div class="relative group">
                                        <input type="text" name="search" value="{{ $search }}" 
                                               placeholder="Escribe serial, placa, técnico o detalle..." 
                                               class="w-full border-gray-200 rounded-2xl py-3 pl-11 pr-4 text-xs font-bold focus:ring-4 focus:ring-blue-100 transition-all shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-3 flex space-x-2">
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Desde</label>
                                    <input type="date" name="from_date" value="{{ $fromDate }}" 
                                           class="w-full border-gray-200 rounded-2xl py-3 text-[10px] font-bold focus:ring-4 focus:ring-blue-100 uppercase shadow-sm">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Hasta</label>
                                    <input type="date" name="to_date" value="{{ $toDate }}" 
                                           class="w-full border-gray-200 rounded-2xl py-3 text-[10px] font-bold focus:ring-4 focus:ring-blue-100 uppercase shadow-sm">
                                </div>
                            </div>

                            <div class="md:col-span-2 flex space-x-2">
                                <button type="submit" title="Filtrar" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl py-3 shadow-lg shadow-blue-100 transition-all flex justify-center items-center active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                </button>
                                <a href="{{ route('maintenances.index') }}" title="Limpiar Filtros" class="flex-1 bg-white border border-gray-200 text-gray-400 hover:text-gray-600 rounded-2xl py-3 transition-all flex justify-center items-center hover:bg-gray-50 active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($services->isEmpty() && request()->anyFilled(['search', 'from_date', 'to_date']))
                        <div class="mt-8 p-10 text-center bg-white rounded-3xl border border-dashed border-gray-200 text-gray-400 italic text-sm">
                            No se encontraron mantenimientos para los criterios seleccionados.
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-[10px] uppercase font-black text-gray-400 tracking-widest border-b">
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Equipo / Serial</th>
                                <th class="px-6 py-4">Técnico</th>
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4 w-1/3">Descripción</th> 
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white font-medium">
                            @forelse($services as $service)
                            <tr class="hover:bg-blue-50/20 transition-colors group">
                                <td class="px-6 py-4 text-xs font-bold text-gray-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($service->performed_at)->format('d/m/Y') }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="font-black text-gray-800 uppercase text-xs tracking-tighter">{{ $service->asset->serial_number }}</div>
                                    <div class="text-[9px] text-blue-500 font-bold uppercase italic">
                                        {{ $service->asset->room->building->name ?? 'N/A' }} - {{ $service->asset->room->nomenclatura ?? 'N/A' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-xs font-bold text-gray-700 uppercase">
                                    {{ $service->technician->name ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $typeClasses = match($service->type) {
                                            'PREVENTIVO' => 'bg-green-100 text-green-700',
                                            'CORRECTIVO' => 'bg-red-100 text-red-700',
                                            'DIAGNÓSTICO' => 'bg-amber-100 text-amber-700',
                                            'CAMBIO DE RAM' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase {{ $typeClasses }}">
                                        {{ $service->type }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-xs text-gray-500 italic leading-relaxed">
                                    {{ Str::limit($service->description, 100) }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center">
                                        <a href="{{ route('maintenances.edit', $service) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all border border-transparent hover:border-blue-100 active:scale-95 shadow-sm hover:shadow-md"
                                           title="Editar Bitácora">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                    No hay registros de soporte técnico en la bitácora.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>