<x-app-layout>            
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">CRONOGRAMA SEMESTRAL</h2>
                            <p class="text-sm text-gray-500">Planificación y control de mantenimientos preventivos obligatorios.</p>
                        </div>
                        <a href="{{ route('schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-5 rounded-xl text-xs transition-all shadow-md shrink-0 text-center uppercase">
                            + Programar Equipo
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 border-b">
                            <tr>
                                <th class="px-6 py-4">Equipo / Serial</th>
                                <th class="px-6 py-4">Ubicación Asignada</th>
                                <th class="px-6 py-4">Técnico Asignado</th>
                                <th class="px-6 py-4 text-center">Fecha Límite</th>
                                <th class="px-6 py-4 text-center">Estado Agenda</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($schedules as $schedule)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 uppercase text-xs">{{ $schedule->asset->serial_number }}</div>
                                        <div class="text-[10px] text-blue-600 font-mono uppercase">{{ $schedule->asset->internal_code ?? 'Sin Placa' }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold uppercase">
                                            {{ $schedule->asset->room->nomenclatura ?? 'No asignado' }}
                                        </span>
                                        <div class="text-[10px] text-gray-400 mt-1 italic">
                                            {{ $schedule->asset->room->building->name ?? 'Edificio General' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-xs font-bold text-gray-700 uppercase">
                                        {{ $schedule->technician->name ?? 'Sin asignar' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-xs font-medium text-gray-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusClasses = match($schedule->status) {
                                                'PENDIENTE' => 'bg-amber-100 text-amber-700',
                                                'REALIZADO' => 'bg-green-100 text-green-700',
                                                'VENCIDO'   => 'bg-red-100 text-red-700',
                                                default     => 'bg-gray-100 text-gray-700'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses }}">
                                            {{ $schedule->status }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-3">
                                            @if($schedule->status === 'PENDIENTE')
                                                <a href="{{ route('schedules.edit', $schedule) }}" 
                                                   class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white font-black py-2.5 px-4 rounded-xl text-[10px] transition-all shadow-md hover:shadow-amber-100 active:scale-95 uppercase tracking-wide"
                                                   title="Corregir datos de programación">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Corregir
                                                </a>

                                                <a href="{{ route('maintenances.create', ['asset_id' => $schedule->asset_id]) }}" 
                                                   class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-4 rounded-xl text-[10px] transition-all shadow-md hover:shadow-blue-100 active:scale-95 uppercase tracking-wide"
                                                   title="Iniciar intervención física">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                                    </svg>
                                                    Ejecutar Mantenimiento
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-green-50 text-green-700 font-black rounded-xl text-[10px] uppercase tracking-wider border border-green-200 shadow-sm select-none">
                                                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Trabajo Completado
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            No hay mantenimientos preventivos registrados o programados en el periodo actual.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $schedules->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>