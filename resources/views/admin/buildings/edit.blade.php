<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Contenedor Principal (Box) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Encabezado -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        Editar Bloque
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Actualice la información del bloque y su sede correspondiente.</p>
                </div>

                <!-- Formulario -->
                <form action="{{ route('buildings.update', $building) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-y-6">
                        
                        <!-- Nombre del Bloque -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre del Bloque
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $building->name) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selección de Sede -->
                        <div>
                            <label for="campus_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Sede Perteneciente
                            </label>
                            <div class="relative">
                                <select name="campus_id" id="campus_id" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 appearance-none py-2.5 pl-4 pr-10 transition-all"
                                        required>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ $building->campus_id == $campus->id ? 'selected' : '' }}>
                                            {{ $campus->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Icono de flecha personalizado -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                        <a href="{{ route('buildings.index') }}" 
                           class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                            Cancelar y volver
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm hover:shadow transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>