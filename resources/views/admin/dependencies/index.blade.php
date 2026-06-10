<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                <div class="p-6 bg-white border-b border-gray-100">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h2 class="font-bold text-xl text-gray-800 tracking-tight">
                            Gestión de Dependencias
                        </h2>
                        
                        <a href="{{ route('dependencies.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-sm" style="text-decoration: none;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nueva Dependencia
                        </a>
                    </div>

                    <form method="GET" action="{{ route('dependencies.index') }}" class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        
                        <div class="flex items-center text-sm text-gray-500 font-medium">
                            <span>Mostrar</span>
                            <select name="per_page" id="per_page" class="mx-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 shadow-sm" onchange="this.form.submit()">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span>registros</span>
                        </div>

                        <div class="relative w-full sm:w-80">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar dependencia..." class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 shadow-sm placeholder-gray-400" autocomplete="off" onkeyup="if(event.key === 'Enter') this.form.submit()">
                        </div>

                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50/70 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 w-24">ID</th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900">Nombre de la Dependencia</th>
                                <th scope="col" class="px-6 py-4 font-bold text-gray-900 text-right pr-12">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($dependencies as $dependency)
                            <tr class="bg-white hover:bg-gray-50/80 transition ease-in-out duration-150">
                                <td class="px-6 py-4 font-semibold text-indigo-600 whitespace-nowrap">
                                    #{{ $dependency->id }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    {{ $dependency->name }}
                                </td>
                                <td class="px-6 py-4 text-right pr-6">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('dependencies.edit', $dependency->id) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 rounded-lg text-xs font-bold tracking-wide transition ease-in-out duration-150" style="text-decoration: none;">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white">
                                <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-400 font-medium">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>No se encontraron dependencias registradas.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-white border-t border-gray-100">
                    {{ $dependencies->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>