<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight">Editar Hoja de Vida</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Modificando equipo: <span class="text-blue-600">{{ $asset->serial_number }}</span></p>
                    </div>
                    <a href="{{ route('assets.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </div>

                <form action="{{ route('assets.update', $asset) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-6">
                            <h3 class="text-xs font-black uppercase text-blue-600 tracking-widest border-b pb-2">Identificación del Activo</h3>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Número de Serial</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-200 focus:border-blue-500 shadow-sm transition-all" required>
                                @error('serial_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Placa de Inventario (USC)</label>
                                <input type="text" name="internal_code" value="{{ old('internal_code', $asset->internal_code) }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-200 focus:border-blue-500 shadow-sm transition-all">
                                @error('internal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de Red (Hostname)</label>
                                <input type="text" name="hostname" value="{{ old('hostname', $asset->hostname) }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-200 focus:border-blue-500 shadow-sm transition-all">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-xs font-black uppercase text-blue-600 tracking-widest border-b pb-2">Especificaciones Técnicas</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Procesador</label>
                                    <input type="text" name="cpu" value="{{ old('cpu', $asset->cpu) }}" class="w-full rounded-xl border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Memoria RAM</label>
                                    <input type="text" name="ram" value="{{ old('ram', $asset->ram) }}" class="w-full rounded-xl border-gray-300 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Almacenamiento (Disco)</label>
                                <input type="text" name="storage" value="{{ old('storage', $asset->storage) }}" class="w-full rounded-xl border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Dirección IP</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address', $asset->ip_address) }}" class="w-full rounded-xl border-gray-300 shadow-sm font-mono text-sm">
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-amber-50 p-6 rounded-2xl border border-amber-100 mt-4">
                            <div class="flex items-start">
                                <div class="shrink-0 pt-1">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-sm font-bold text-amber-900 uppercase tracking-tight">Ubicación Registrada</h3>
                                    <p class="text-xs text-amber-700 mb-4">Si el equipo se movió físicamente de oficina, use el módulo de <b>Traslados</b>. Edite aquí solo si la ubicación inicial fue errónea.</p>
                                    
                                    <div class="max-w-md">
                                        <label class="block text-sm font-bold text-amber-900 mb-2">Oficina / Salón Actual</label>
                                        <select name="room_id" class="w-full rounded-xl border-amber-200 focus:ring-amber-500 shadow-sm">
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->id }}" {{ old('room_id', $asset->room_id) == $room->id ? 'selected' : '' }}>
                                                    {{ $room->nomenclatura }} ({{ $room->building->name ?? 'Sin Edificio' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end space-x-4">
                        <a href="{{ route('assets.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-800 transition">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-10 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 active:scale-95">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>