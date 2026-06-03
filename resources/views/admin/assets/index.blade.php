<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-white">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Hojas de Vida (Equipos)</h2>
                            <p class="text-sm text-gray-500">Inventario técnico y asignaciones de activos.</p>
                        </div>

                        <div class="flex flex-col md:flex-row flex-1 max-w-2xl gap-3">
                            <form action="{{ route('assets.index') }}" method="GET" class="w-full flex flex-col md:flex-row gap-3 items-center">
                                
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-xs font-bold text-gray-400 uppercase">Ver:</span>
                                    <select name="per_page" onchange="this.form.submit()" 
                                            class="block pl-3 pr-8 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-sm transition-all shadow-sm">
                                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                        <option value="1000" {{ $perPage == 1000 ? 'selected' : '' }}>Todo</option>
                                    </select>
                                </div>

                                <div class="relative group flex-1 w-full">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por serial, placa o hostname..." 
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-sm transition-all shadow-sm">
                                </div>

                                <button type="submit" class="md:hidden w-full bg-blue-600 text-white font-bold py-2 rounded-xl">Filtrar</button>
                            </form>

                            <a href="{{ route('assets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition-all shadow-md shrink-0 text-center">
                                + Registrar Equipo
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 border-b">
                            <tr>
                                <th class="px-6 py-4">Serial / Modelo</th>
                                <th class="px-6 py-4 text-center">Estado HV</th>
                                <th class="px-6 py-4">Ubicación / Hostname</th>
                                <th class="px-6 py-4">Monitor / Periféricos</th>
                                <th class="px-6 py-4">Responsable</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($assets as $asset)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $asset->serial_number }}</div>
                                        <div class="text-[10px] text-blue-600 font-bold uppercase tracking-tight">{{ $asset->model_version ?? 'Sin modelo' }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono uppercase">{{ $asset->internal_code ?? 'Sin Placa' }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($asset->monitor_serial && $asset->keyboard_serial && $asset->security_guaya)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Completa</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 uppercase">Pendiente Físico</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold uppercase">
                                            {{ $asset->room->nomenclatura ?? 'No asignado' }}
                                        </span>
                                        <div class="text-[10px] text-gray-400 mt-1 italic">{{ $asset->hostname ?? 'Sin Hostname' }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-[10px] text-gray-600 flex flex-col gap-0.5">
                                            <span><strong class="text-gray-400">MON:</strong> {{ $asset->monitor_serial ?? '---' }}</span>
                                            <span><strong class="text-gray-400">GUAYA:</strong> {{ $asset->security_guaya ?? '---' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-gray-900 font-medium text-xs">{{ $asset->currentCustodian->full_name ?? 'Sin responsable' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $asset->currentCustodian->job_title ?? '' }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('assets.edit', $asset) }}" 
                                               class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all" title="Editar HV">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>

                                            <a href="{{ route('assets.preview', $asset->id) }}" target="_blank" 
                                               class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-lg transition-all" title="Previsualizar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>

                                            <a href="{{ route('assets.download.pdf', $asset->id) }}" 
                                               class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all" title="Descargar PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            No se encontraron equipos que coincidan con la búsqueda.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>