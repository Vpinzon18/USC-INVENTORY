<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">Registrar Nueva Hoja de Vida</h2>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Complete la información técnica y administrativa del activo.</p>
                </div>

                <form action="{{ route('assets.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h3 class="text-xs font-black uppercase text-blue-600 tracking-widest border-b pb-2">Identificación</h3>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Número de Serial</label>
                                <input type="text" name="serial_number" class="w-full rounded-xl border-gray-300 focus:ring-blue-200 focus:border-blue-500 shadow-sm transition-all" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Placa Interna (SOMA-ID)</label>
                                <input type="text" name="internal_code" class="w-full rounded-xl border-gray-300 focus:ring-blue-200 focus:border-blue-500 shadow-sm transition-all" placeholder="Ej: USC-IT-2024">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de Red (Hostname)</label>
                                <input type="text" name="hostname" class="w-full rounded-xl border-gray-300 focus:ring-blue-200 focus:border-blue-500 shadow-sm transition-all" placeholder="Ej: PC-LAB-01">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-xs font-black uppercase text-blue-600 tracking-widest border-b pb-2">Especificaciones Técnicas</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Procesador</label>
                                    <input type="text" name="cpu" placeholder="Ej: Core i7" class="w-full rounded-xl border-gray-300 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Memoria RAM</label>
                                    <input type="text" name="ram" placeholder="Ej: 16GB" class="w-full rounded-xl border-gray-300 shadow-sm transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Almacenamiento</label>
                                <input type="text" name="storage" placeholder="Ej: 512GB SSD" class="w-full rounded-xl border-gray-300 shadow-sm transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Dirección IP (Opcional)</label>
                                <input type="text" name="ip_address" placeholder="192.168..." class="w-full rounded-xl border-gray-300 shadow-sm transition-all">
                            </div>
                        </div>

                        <div class="md:col-span-2 bg-blue-50/50 p-6 rounded-2xl border border-blue-100 mt-4">
                            <h3 class="text-xs font-black uppercase text-blue-700 tracking-widest mb-4">Ubicación y Responsable Inicial</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-blue-900 mb-2">Oficina / Salón</label>
                                    <select name="room_id" class="w-full rounded-xl border-blue-200 focus:ring-blue-500 shadow-sm" required>
                                        <option value="">Seleccione oficina...</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->nomenclatura }} ({{ $room->building->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-blue-900 mb-2">Responsable (Custodio)</label>
                                    <select name="custodian_id" class="w-full rounded-xl border-blue-200 focus:ring-blue-500 shadow-sm" required>
                                        <option value="">Seleccione jefe responsable...</option>
                                        @foreach($custodians as $custodian)
                                            <option value="{{ $custodian->id }}">{{ $custodian->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end space-x-4">
                        <a href="{{ route('assets.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-800 transition">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-10 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 active:scale-95">
                            Crear Hoja de Vida
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>