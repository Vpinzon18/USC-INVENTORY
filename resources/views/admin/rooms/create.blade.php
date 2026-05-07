<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Registrar Nueva Oficina
            </h2>
            <a href="{{ route('rooms.index') }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm transition">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                
                <form action="{{ route('rooms.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Nombre de la Oficina -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre o Número de la Oficina</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                   placeholder="Ej: Oficina 201, Laboratorio de Redes..." required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label for="nomenclatura" class="block text-sm font-medium text-gray-700">Nomenclatura (Cali)</label>
                            <input type="text" name="nomenclatura" id="nomenclatura" value="{{ old('nomenclatura') }}" 
                            placeholder="Ej: 2011"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-400">Este código se usará para movimientos masivos de equipos.</p>
                        </div>
                        <div>
                            <!-- Selección de Bloque -->
                            <div>
                                <label for="building_id" class="block text-sm font-semibold text-gray-700 mb-2">Bloque Correspondiente</label>
                                <select name="building_id" id="building_id" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition" required>
                                    <option value="">-- Seleccione un Bloque --</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }} ({{ $building->campus->name ?? 'Sin Sede' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('building_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo de Piso (Agregado para coincidir con tu controlador) -->
                            <div>
                                <label for="floor" class="block text-sm font-semibold text-gray-700 mb-2">Piso / Nivel</label>
                                <input type="number" name="floor" id="floor" value="{{ old('floor') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="Ej: 1, 2, 3..." required>
                                @error('floor')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="pt-4 flex items-center justify-end space-x-4">
                            <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-gray-700 font-semibold text-sm">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-md transition-all transform hover:-translate-y-0.5">
                                Guardar Oficina
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>