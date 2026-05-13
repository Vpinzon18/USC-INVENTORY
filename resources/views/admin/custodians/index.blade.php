<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800">Responsables</h2>
                            <p class="text-sm text-gray-500">Gestión y búsqueda de personal.</p>
                        </div>

                        <div class="flex flex-1 max-w-md mx-4">
                            <form action="{{ route('custodians.index') }}" method="GET" class="w-full">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" 
                                             fill="none" 
                                             stroke="currentColor" 
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    
                                    <input type="text" 
                                           name="search" 
                                           value="{{ $search }}"
                                           placeholder="Buscar por nombre, cédula o área..."
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-200 focus:border-blue-500 sm:text-sm transition-all shadow-sm"
                                    >
                                </div>
                            </form>
                        </div>

                        <a href="{{ route('custodians.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all shadow-sm shrink-0 text-center">
                            + Nuevo Responsable
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4">Nombre Completo</th>
                                <th class="px-6 py-4">Cargo</th>
                                <th class="px-6 py-4 text-center">Ubicaciones a Cargo</th>
                                <th class="px-6 py-4">Documento</th>
                                <th class="px-6 py-4">Dependencia</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($custodians as $custodian)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $custodian->full_name }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono">{{ $custodian->email ?? 'Sin correo' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $custodian->job_title }}
                                        <div class="text-[10px] text-gray-400 italic">Ext: {{ $custodian->extension ?? '---' }}</div>
                                    </td>
                                    
                                    <!-- Nueva Columna: Ubicaciones (Nomenclaturas) -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap justify-center gap-1.5 max-w-[200px] mx-auto">
                                            @forelse($custodian->rooms as $room)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200" 
                                                      title="{{ $room->building->name ?? 'Ubicación General' }}">
                                                    <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                    {{ $room->nomenclatura }}
                                                </span>
                                            @empty
                                                <span class="text-[10px] text-gray-300 italic">Sin asignar</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $custodian->document_number }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-[10px] font-bold uppercase">{{ $custodian->dependency }}</span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-1">
                                            <a href="{{ route('custodians.edit', $custodian) }}" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('custodians.destroy', $custodian) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar responsable?')" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                        No se encontraron responsables que coincidan con "{{ $search }}".
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $custodians->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>