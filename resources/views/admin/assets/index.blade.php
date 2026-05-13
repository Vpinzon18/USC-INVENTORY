<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Cabecera e Inputs -->
                <div class="p-6 border-b border-gray-100 bg-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Hojas de Vida (Equipos)</h2>
                            <p class="text-sm text-gray-500">Inventario técnico y asignaciones de activos.</p>
                        </div>

                        <div class="flex flex-1 max-w-md mx-4">
                            <form action="{{ route('assets.index') }}" method="GET" class="w-full">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por serial, placa o hostname..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 sm:text-sm transition-all shadow-sm">
                                </div>
                            </form>
                        </div>

                        <a href="{{ route('assets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm transition-all shadow-md shrink-0">
                            + Registrar Equipo
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4">Serial / Placa</th>
                                <th class="px-6 py-4 text-center">Estado HV</th> <!-- Nueva columna de control -->
                                <th class="px-6 py-4">Ubicación / Hostname</th>
                                <th class="px-6 py-4">Monitor / Periféricos</th> <!-- Nueva columna para datos físicos -->
                                <th class="px-6 py-4">Responsable</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($assets as $asset)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Serial y Placa -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $asset->serial_number }}</div>
                                        <div class="text-[10px] text-blue-600 font-mono uppercase">{{ $asset->internal_code ?? 'Sin Placa' }}</div>
                                    </td>

                                    <!-- Estado de Completitud (R-GT004) -->
                                    <td class="px-6 py-4 text-center">
                                        @if($asset->monitor_serial && $asset->keyboard_serial && $asset->security_guaya)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Completa</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 uppercase">Pendiente Físico</span>
                                        @endif
                                    </td>

                                    <!-- Ubicación y Hostname -->
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold uppercase">
                                            {{ $asset->room->nomenclatura ?? 'No asignado' }}
                                        </span>
                                        <div class="text-[10px] text-gray-400 mt-1 italic">{{ $asset->hostname ?? 'Sin Hostname' }}</div>
                                    </td>

                                    <!-- Datos de Periféricos (Mecánicos) -->
                                    <td class="px-6 py-4">
                                        <div class="text-[10px] text-gray-600 flex flex-col gap-0.5">
                                            <span><strong class="text-gray-400">MON:</strong> {{ $asset->monitor_serial ?? '---' }}</span>
                                            <span><strong class="text-gray-400">GUAYA:</strong> {{ $asset->security_guaya ?? '---' }}</span>
                                        </div>
                                    </td>

                                    <!-- Responsable -->
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900 font-medium text-xs">{{ $asset->currentCustodian->full_name ?? 'Sin responsable' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $asset->currentCustodian->job_title ?? '' }}</div>
                                    </td>

                                    <!-- Acciones actualizadas -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-1">
                                            <!-- Botón Actualizar HV -->
                                            <a href="{{ route('assets.edit', $asset) }}" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg transition-all border border-blue-100 font-bold text-[10px] uppercase group" 
                                               title="Actualizar Hoja de Vida">
                                                <svg class="w-3.5 h-3.5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Actualizar HV
                                            </a>
                                            <a href="{{ route('assets.preview', $asset->id) }}" target="_blank"
   class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm inline-flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
    Previsualizar Hoja de Vida
</a>

                                            <!-- Botón Ver PDF -->
                                            <a href="{{ route('assets.show', $asset) }}" class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors" title="Ver Formato R-GT004">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 italic font-medium">No se encontraron equipos registrados.</td></tr>
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