<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Listado de Bloques</h2>
            <a href="{{ route('buildings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                + Nuevo Bloque
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nombre del Bloque</th>
                        <th class="p-4">Sede</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($buildings as $building)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-gray-600">#{{ $building->id }}</td>
                            <td class="p-4 font-semibold text-gray-900">{{ $building->name }}</td>
                            <td class="p-4">
                                {{-- Accedemos al nombre a través de la relación 'campus' que cargaste en el controlador --}}
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $building->campus->name ?? 'Sin Sede Asignada' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('buildings.edit', $building) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Editar
                                    </a>
                                    
                                    <form action="{{ route('buildings.destroy', $building) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este bloque de SOMA?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500 italic">
                                No se encontraron registros de bloques en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>