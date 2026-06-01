<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-emerald-100 rounded-xl text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Listado de Oficinas y Salones</h2>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Administración de áreas, laboratorios y oficinas registradas en el sistema.</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('rooms.create') }}" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nueva Oficina
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-500 uppercase text-[10px] font-extrabold tracking-widest border-y border-slate-200">
                                <th class="py-4 px-6 w-20">ID</th>
                                <th class="py-4 px-6">Nombre / Número</th>
                                <th class="py-4 px-6">Nomenclatura</th>
                                <th class="py-4 px-6">Ubicación (Sede/Bloque)</th>
                                <th class="py-4 px-6 text-center">Piso</th>
                                <th class="py-4 px-6 text-center w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($rooms as $room)
                                <tr class="hover:bg-emerald-50/40 transition-colors group">
                                    
                                    <td class="py-4 px-6 text-slate-400 font-mono text-xs font-semibold">
                                        #{{ str_pad($room->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    
                                    <td class="py-4 px-6 font-bold text-slate-700 group-hover:text-emerald-700 transition-colors">
                                        {{ $room->name }}
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        @if($room->nomenclatura)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                <svg class="w-3 h-3 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                {{ $room->nomenclatura }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs italic">N/A</span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center text-[11px] font-bold text-blue-600">
                                                <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                {{ $room->building->campus->name ?? 'Sede no asignada' }}
                                            </span>
                                            <span class="inline-flex items-center text-[11px] font-bold text-indigo-600">
                                                <svg class="w-3 h-3 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                                                {{ $room->building->name ?? 'Bloque no asignado' }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200">
                                            {{ $room->floor }}
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('rooms.edit', $room) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-100 rounded-lg transition-all" title="Editar Oficina">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            
                                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('¿Está seguro que desea eliminar la oficina/salón: {{ $room->name }}? Los activos asignados a este salón podrían quedar sin ubicación.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-100 rounded-lg transition-all" title="Eliminar Oficina">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-slate-700 mb-1">No hay oficinas registradas</h3>
                                            <p class="text-xs text-slate-500 mb-4">Comience agregando salones, laboratorios u oficinas a sus bloques.</p>
                                            <a href="{{ route('rooms.create') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 uppercase tracking-wide">
                                                + Registrar Primera Oficina
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 border-t border-slate-200 p-4">
                    <p class="text-xs text-slate-500 text-center font-medium">Fin de los registros.</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>