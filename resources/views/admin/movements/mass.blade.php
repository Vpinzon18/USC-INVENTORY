<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-800">
                    <h2 class="font-bold text-2xl text-white leading-tight">
                        Movimiento Masivo de Activos
                    </h2>
                    <p class="text-blue-100 mt-1 text-sm opacity-90">Seleccione los equipos existentes y asígnelos a un nuevo destino.</p>
                </div>

                @if(session('success'))
                    <div class="m-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('movements.mass.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                                Equipos Disponibles
                            </label>
                            
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="p-3 bg-white border-b border-gray-200">
                                    <div class="relative">
                                        <input type="text" id="assetSearch" 
                                               placeholder="Buscar por serial o placa..." 
                                               class="w-full pl-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50">
                                    </div>
                                </div>

                                <div class="max-h-[500px] overflow-y-auto p-3 space-y-2" id="assetList">
                                    @foreach($assets as $asset)
                                        <label class="asset-item flex items-center p-3 bg-white border border-gray-100 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer group">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="selected_assets[]" value="{{ $asset->id }}" 
                                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                            </div>
                                            <div class="ml-3">
                                                <span class="block text-xs font-black text-gray-800 asset-serial uppercase">
                                                    {{ $asset->serial_number }}
                                                </span>
                                                <span class="block text-[10px] text-blue-600 font-mono font-bold asset-plate">
                                                    {{ $asset->internal_code ?? 'SIN PLACA' }}
                                                </span>
                                                <div class="flex items-center mt-1 text-[9px] text-gray-400 font-bold uppercase">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                    {{ $asset->room->nomenclatura ?? 'No asignada' }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="bg-gray-100 px-4 py-2 text-[10px] text-gray-500 font-black flex justify-between uppercase">
                                    <span>Resultados: <span id="assetCount">{{ count($assets) }}</span></span>
                                    <span class="text-blue-600">SOMA USC</span>
                                </div>
                            </div>
                            @error('selected_assets')
                                <p class="text-red-500 text-xs mt-2 font-medium">Debe seleccionar al menos un equipo.</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-2 space-y-8">
                            
                            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 shadow-sm">
                                <h3 class="text-blue-800 text-xs font-black uppercase tracking-widest mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Información del Destino
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="custodian_id" class="block text-sm font-bold text-gray-700 mb-2">
                                            Director / Jefe de Área
                                        </label>
                                        <select name="custodian_id" id="custodian_id" 
                                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-3"
                                                onchange="filterRooms(this.value)"
                                                required>
                                            <option value="" disabled selected>Seleccione el responsable receptor</option>
                                            @foreach($custodians as $custodian)
                                                <option value="{{ $custodian->id }}" data-rooms='@json($custodian->rooms)'>
                                                    {{ $custodian->full_name }} — {{ $custodian->job_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="room_id" class="block text-sm font-bold text-gray-700 mb-2">
                                            Oficina o Laboratorio Destino
                                        </label>
                                        <select name="room_id" id="room_id" 
                                                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 py-3 bg-white disabled:bg-gray-100 transition-all"
                                                disabled
                                                required>
                                            <option value="" selected>Primero seleccione un responsable...</option>
                                        </select>
                                        <p class="mt-2 text-[10px] text-blue-600 font-bold uppercase italic tracking-tight">* Se muestran las ubicaciones autorizadas para este director.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="observation" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                                    Justificación del Movimiento
                                </label>
                                <textarea name="observation" id="observation" rows="4" 
                                          placeholder="Describa el motivo del traslado masivo..."
                                          class="w-full rounded-2xl border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 p-4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="hidden md:flex items-center text-amber-600 bg-amber-50 px-4 py-2 rounded-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span class="text-[10px] font-black uppercase">Trazabilidad técnica obligatoria para auditoría interna.</span>
                        </div>
                        
                        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-12 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 active:scale-95">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            EJECUTAR TRASLADO MASIVO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Lógica de Búsqueda de Equipos
            const assetSearch = document.getElementById('assetSearch');
            const assetItems = document.querySelectorAll('.asset-item');
            const assetCount = document.getElementById('assetCount');

            if (assetSearch) {
                assetSearch.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase();
                    let visible = 0;

                    assetItems.forEach(item => {
                        const serial = item.querySelector('.asset-serial').textContent.toLowerCase();
                        const plate = item.querySelector('.asset-plate').textContent.toLowerCase();

                        if (serial.includes(term) || plate.includes(term)) {
                            item.style.display = 'flex';
                            visible++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    assetCount.textContent = visible;
                });
            }
        });

        // 2. Lógica de Filtrado Dinámico de Ubicaciones por Custodio
        function filterRooms(custodianId) {
            const roomSelect = document.getElementById('room_id');
            const custodianSelect = document.getElementById('custodian_id');
            const selectedOption = custodianSelect.options[custodianSelect.selectedIndex];
            
            roomSelect.innerHTML = '<option value="" disabled selected>Cargando ubicaciones...</option>';
            roomSelect.disabled = true;

            try {
                const rooms = JSON.parse(selectedOption.getAttribute('data-rooms'));

                if (rooms && rooms.length > 0) {
                    roomSelect.innerHTML = '<option value="" disabled selected>Seleccione una de las ' + rooms.length + ' ubicaciones a cargo</option>';
                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `${room.nomenclatura} (${room.building ? room.building.name : 'General'})`;
                        roomSelect.appendChild(option);
                    });
                    roomSelect.disabled = false;
                } else {
                    roomSelect.innerHTML = '<option value="" disabled selected>Sin ubicaciones asignadas</option>';
                }
            } catch (e) {
                roomSelect.innerHTML = '<option value="" disabled selected>Error en los datos</option>';
            }
        }
    </script>
</x-app-layout>