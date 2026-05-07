<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Contenedor Principal (Box) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Encabezado con degradado sutil -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-50 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                                Editar Sede
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">Modifique los detalles principales de la ubicación institucional.</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form action="{{ route('campuses.update', $campus) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        
                        <!-- Nombre de la Sede -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre de la Sede
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $campus->name) }}" 
                                   placeholder="Ej: Sede Cali - Meléndez"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all py-2.5"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección / Ubicación (Si tienes este campo en tu BD) -->
                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                Dirección o Referencia
                            </label>
                            <input type="text" name="address" id="address" 
                                   value="{{ old('address', $campus->address) }}" 
                                   placeholder="Calle 5 # 62-00"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all py-2.5">
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                        <a href="{{ route('campuses.index') }}" 
                           class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                            Cancelar y volver
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Actualizar Sede
                        </button>
                    </div>
                </form>
            </div>

            <!-- Nota informativa sutil -->
            <div class="mt-6 flex items-center justify-center text-gray-400 text-xs">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Los cambios realizados se reflejarán inmediatamente en los bloques y oficinas asociados.
            </div>
        </div>
    </div>
</x-app-layout>