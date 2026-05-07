<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contenedor Principal (Box) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Encabezado INTERNO del Box -->
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                            Gestión de Oficinas y Espacios
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Administración de áreas y salones registrados en el sistema.</p>
                    </div>
                    
                    <a href="{{ route('rooms.create') }}" 
                       class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg text-xs uppercase tracking-wider shadow-sm transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Oficina
                    </a>
                </div>

                <!-- Cuerpo del Box (Tabla) -->
                <div class="overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                <th class="p-4">ID</th>
                <th class="p-4">Nombre / Número</th>
                <th class="p-4">Nomenclatura</th> 
                <th class="p-4">Sede</th>
                <th class="p-4">Bloque</th>
                <th class="p-4">Piso</th>
                <th class="p-4 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse ($rooms as $room)
                <tr class="hover:bg-gray-50 transition-colors">
                    <!-- ID -->
                    <td class="p-4 text-gray-600 font-mono">#{{ $room->id }}</td>
                    
                    <!-- Nombre -->
                    <td class="p-4 font-bold text-gray-900">{{ $room->name }}</td>
                    
                    <!-- Nomenclatura -->
                    <td class="p-4 text-blue-600 font-semibold">
                        {{ $room->nomenclatura ?? 'N/A' }}
                    </td>

                    <!-- Sede (Relación Room -> Building -> Campus) -->
                    <td class="p-4 text-gray-700">
                        <div class="flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $room->building->campus->name ?? 'No asignada' }}
                        </div>
                    </td>

                    <!-- Bloque -->
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium border border-blue-100">
                            {{ $room->building->name ?? 'Sin Bloque' }}
                        </span>
                    </td>
                    
                    <!-- Piso -->
                    <td class="p-4 font-medium text-gray-600">Piso {{ $room->floor }}</td>
                    
                    <!-- Acciones -->
                    <td class="p-4 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('rooms.edit', $room) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                Editar
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta oficina?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-gray-500 italic mb-4">No se encontraron oficinas registradas en SOMA.</p>
                            <a href="{{ route('rooms.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition shadow-sm">
                                + Crear la primera oficina
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
            </div>
        </div>
    </div>
</x-app-layout>