
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                <div class="p-6 bg-white border-b border-gray-100">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div class="flex flex-col">
                            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Historial de Movimientos</h2>
                            <p class="text-sm text-gray-500">Registro de traslados, asignaciones y bajas de equipos.</p>
                        </div>
                        
                        <a href="{{ route('movements.mass.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition ease-in-out duration-150" style="text-decoration: none;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Nuevo Movimiento Masivo
                        </a>
                    </div>

                    <form method="GET" action="{{ route('movements.index') }}" class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center text-sm text-gray-500 font-medium">
                            <span>Mostrar</span>
                            <select name="per_page" class="mx-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span>registros</span>
                        </div>

                        <div class="relative w-full sm:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por acta o responsable..." class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-indigo-500 block w-full pl-10 p-2.5 shadow-sm" onkeyup="if(event.key === 'Enter') this.form.submit()">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50/70 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-bold text-gray-900">Fecha</th>
                                <th class="px-6 py-4 font-bold text-gray-900">N° Acta</th>
                                <th class="px-6 py-4 font-bold text-gray-900 text-center">Cant. Equipos</th> <th class="px-6 py-4 font-bold text-gray-900">Tipo</th>
                                <th class="px-6 py-4 font-bold text-gray-900">Responsable / Custodio</th>
                                <th class="px-6 py-4 font-bold text-gray-900 text-right">Acciones</th>
                                <th class="px-6 py-4 font-bold text-gray-900">Registrado por</th>
                                <th class="px-6 py-4 font-bold text-gray-900 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($movements as $movement)
                            <tr class="bg-white hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-medium text-gray-600">
                                    {{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-indigo-600">
                                    {{ $movement->acta_number ?? 'S/N' }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $movement->total_equipos }} equipos
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $movement->movement_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-800 font-medium whitespace-nowrap">
                                    {{ $movement->custodian->full_name ?? 'Sin asignar' }}
                                </td>
                                <td class="px-6 py-4">
    <div class="flex items-center">
        <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-2">
            {{ strtoupper(substr($movement->user->name ?? 'A', 0, 1)) }}
        </div>
        <span class="text-sm font-medium text-gray-600">
            {{ $movement->user->name ?? 'Admin Sistema' }}
        </span>
    </div>
</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        @if($movement->acta_number)
                                        <a href="{{ route('movements.exportActa', $movement->acta_number) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg text-xs font-bold" style="text-decoration: none;">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Acta
                                        </a>
                                        @endif
                                        
                                        <a href="{{ route('movements.edit', $movement->id) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 rounded-lg text-xs font-bold" style="text-decoration: none;">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">No hay movimientos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-white border-t border-gray-100">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>