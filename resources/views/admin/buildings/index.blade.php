<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Listado de Bloques</h2>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Gestione los bloques, edificios o zonas asignadas a cada sede.</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('buildings.create') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 text-xs uppercase tracking-wider">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuevo Bloque
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-500 uppercase text-[10px] font-extrabold tracking-widest border-y border-slate-200">
                                <th class="py-4 px-6 w-24">ID</th>
                                <th class="py-4 px-6">Nombre del Bloque</th>
                                <th class="py-4 px-6">Sede Asignada</th>
                                <th class="py-4 px-6 text-center w-32">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($buildings as $building)
                                <tr class="hover:bg-blue-50/50 transition-colors group">
                                    <td class="py-4 px-6 text-slate-400 font-mono text-xs font-semibold">
                                        #{{ str_pad($building->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-4 px-6 font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                        {{ $building->name }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($building->campus)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <svg class="w-3 h-3 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $building->campus->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                Sin Sede Asignada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('buildings.edit', $building) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-100 rounded-lg transition-all" title="Editar Bloque">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            
                                            <form action="{{ route('buildings.destroy', $building) }}" method="POST" onsubmit="return confirm('¿Está seguro que desea eliminar el bloque: {{ $building->name }}? Tenga en cuenta que esto podría afectar los salones asociados.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-100 rounded-lg transition-all" title="Eliminar Bloque">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-slate-700 mb-1">No hay bloques registrados</h3>
                                            <p class="text-xs text-slate-500 mb-4">Aún no se han configurado bloques o edificios en el sistema.</p>
                                            <a href="{{ route('buildings.create') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wide">
                                                + Registrar Primer Bloque
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