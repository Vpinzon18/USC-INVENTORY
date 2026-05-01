<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Listado de Bloques</h2>
            <a href="{{ route('campuses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                + Nueva Sede / Sucursal
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                        <th class="p-4">ID</th>
                        <th class="p-4">Sede</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($campuses as $campuses)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 text-gray-600">#{{ $campuses->id }}</td>
                            <td class="p-4 font-semibold text-gray-900">{{ $campuses->name }}</td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('campuses.edit', $campuses) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Editar
                                    </a>
                                    
                                    <form action="{{ route('campuses.destroy', $campuses) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este bloque de SOMA?')">
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