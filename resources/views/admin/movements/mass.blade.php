<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        Movimiento Masivo de Equipos
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Escanee o pegue los seriales para reubicar múltiples equipos simultáneamente.</p>
                </div>

                <!-- Alerta de éxito/error -->
                @if(session('success'))
                    <div class="m-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('movements.mass.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Columna Izquierda: Seriales -->
                        <div>
                            <label for="serials" class="block text-sm font-semibold text-gray-700 mb-2">
                                Seriales de los Equipos (Uno por línea)
                            </label>
                            <textarea name="serials" id="serials" rows="10" 
                                      placeholder="Ejemplo:&#10;SN-10293&#10;SN-40592&#10;SN-99384"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all font-mono text-sm"
                                      required></textarea>
                            @error('serials')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Columna Derecha: Destino y Responsable -->
                        <div class="space-y-6">
                            
                            <!-- Nomenclatura -->
                            <div>
                                <label for="nomenclatura" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomenclatura Destino (Ubicación)
                                </label>
                                <input type="text" name="nomenclatura" id="nomenclatura" 
                                       placeholder="Ej: 2011"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                       required>
                                @error('nomenclatura')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Responsable -->
                            <div>
                                <label for="custodian_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nuevo Responsable
                                </label>
                                <select name="custodian_id" id="custodian_id" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                        required>
                                    <option value="" disabled selected>Seleccione un jefe/director</option>
                                    @foreach($custodians as $custodian)
                                        <option value="{{ $custodian->id }}">{{ $custodian->full_name }} - {{ $custodian->job_title }}</option>
                                    @endforeach
                                </select>
                                @error('custodian_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Ejecutar Traslado Masivo
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>