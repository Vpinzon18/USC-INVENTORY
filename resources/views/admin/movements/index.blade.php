<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-[1300px] w-[96%] mx-auto space-y-4">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-600"></div>

                <div class="flex items-center gap-4 pl-2 w-full md:w-auto">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-xl text-slate-800 tracking-tight">Historial de Movimientos</h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Registro histórico de traslados, asignaciones y bajas de equipos.</p>
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <a href="{{ route('movements.mass.create') }}" class="w-full md:w-auto px-5 py-2.5 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-indigo-200" style="text-decoration: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Movimiento Masivo
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <form method="GET" action="{{ route('movements.index') }}" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">

                <div class="px-6 py-3 border-b border-slate-100 bg-slate-50 flex justify-end items-center">
                    <div class="relative w-full sm:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por acta o responsable..."
                            class="w-full pl-9 pr-4 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all placeholder-slate-400"
                            onkeyup="if(event.key === 'Enter') this.form.submit()">
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-xs text-left">
                        <thead class="text-[10px] text-slate-500 uppercase tracking-widest bg-slate-50/50 border-b border-slate-200">
                            <tr>
                                <th class="w-24 px-4 py-3 font-black text-center whitespace-nowrap">Fecha</th>
                                <th class="w-28 px-4 py-3 font-black text-center whitespace-nowrap">N° Acta</th>
                                <th class="w-16 px-4 py-3 font-black text-center whitespace-nowrap">Cant.</th>
                                <th class="w-36 px-4 py-3 font-black text-center whitespace-nowrap">Tipo</th>
                                <th class="px-4 py-3 font-black whitespace-nowrap">Responsable</th>
                                <th class="px-4 py-3 font-black whitespace-nowrap">Registrado por</th>
                                <th class="w-20 px-4 py-3 font-black text-center whitespace-nowrap">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($movements as $movement)
                            <tr class="bg-white hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-4 py-2.5 font-medium text-slate-600 text-center whitespace-nowrap">
                                    <span class="block">{{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y') }}</span>
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ \Carbon\Carbon::parse($movement->created_at)->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="font-extrabold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 inline-block whitespace-nowrap">
                                        {{ $movement->acta_number ?? 'S/N' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $movement->total_equipos }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                        {{ $movement->movement_type }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-slate-800 font-bold">
                                    {{ $movement->custodian->full_name ?? 'Sin asignar' }}
                                </td>
                                <td class="px-4 py-2.5">
                                    <span class="text-xs font-bold text-slate-700">
                                        {{ $movement->user->name ?? 'Admin' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        @if($movement->acta_number)
                                        <a href="{{ route('movements.exportActa', $movement->acta_number) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-[10px] font-black uppercase tracking-wider hover:bg-rose-100 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Acta
                                        </a>
                                        @endif

                                        <a href="{{ route('movements.edit', $movement->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[10px] font-black uppercase tracking-wider hover:bg-amber-100 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white">
                                <td colspan="7" class="px-4 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-10 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">No hay movimientos registrados</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">

                    <div class="w-full sm:w-auto text-xs font-semibold text-slate-600">
                        @if($movements->hasPages())
                        {{ $movements->links() }}
                        @else
                        <span class="text-slate-400 uppercase tracking-wider text-[10px]">Mostrando todos los registros</span>
                        @endif
                    </div>

                    <div class="flex items-center text-xs text-slate-500 font-bold uppercase tracking-wider shrink-0">
                        <span>Mostrar</span>
                        <select name="per_page" class="mx-2 bg-white border border-slate-300 text-slate-700 font-extrabold rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-1 px-3 transition-colors cursor-pointer text-xs" onchange="this.form.submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="50" {{ $perPage == 15 ? 'selected' : '' }}>50</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="50" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span>por página</span>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>