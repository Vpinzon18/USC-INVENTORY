<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100">
                    <h2 class="font-bold text-2xl text-gray-800">Editar Oficina: {{ $room->name }}</h2>
                </div>

                <form action="{{ route('rooms.update', $room) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre de la Oficina</label>
                            <input type="text" name="name" value="{{ old('name', $room->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <!-- Nomenclatura -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomenclatura (Cali)</label>
                            <input type="text" name="nomenclatura" value="{{ old('nomenclatura', $room->nomenclatura) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Bloque -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bloque / Edificio</label>
                            <select name="building_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ $room->building_id == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Piso -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Piso</label>
                            <input type="number" name="floor" value="{{ old('floor', $room->floor) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('rooms.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>