<x-app-layout>
    <div class="py-6">
        {{-- Expandimos al ancho máximo del ecosistema SIGMA (max-w-7xl) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- ENCABEZADO MINIMALISTA UNIFICADO --}}
                <div class="p-5 bg-white border-b border-gray-100">
                    <div class="flex flex-col">
                        <h2 class="font-bold text-xl text-gray-800 tracking-tight">Editar Programación</h2>
                        <p class="text-xs text-gray-500">Corrija los datos de asignación, el técnico responsable o la fecha proyectada para el mantenimiento preventivo.</p>
                    </div>
                </div>

                <form action="{{ route('schedules.update', $schedule) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    {{-- ARQUITECTURA DE DATOS EN DOS COLUMNAS PARALELAS --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        {{-- COLUMNA IZQUIERDA (5 Espacios): MODIFICACIÓN DE HARDWARE VINCULADO --}}
                        <div class="lg:col-span-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">1. Equipo Programado</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="assetSearch" placeholder="Buscar para cambiar de equipo..." 
                                           class="w-full bg-gray-50/50 pl-9 p-2.5 border border-gray-200 rounded-lg text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- Listado de equipos con scroll interno controlado --}}
                            <div class="max-h-[260px] overflow-y-auto border border-gray-200 rounded-xl p-2 bg-gray-50/30 space-y-1.5" id="assetContainer">
                                @foreach($assets as $asset)
                                    <label class="asset-option flex items-center p-2.5 bg-white border border-gray-100 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-gray-50/50 transition duration-150 group relative">
                                        <input type="radio" name="asset_id" value="{{ $asset->id }}" class="hidden peer" required
                                               {{ $schedule->asset_id == $asset->id ? 'checked' : '' }}>
                                        
                                        {{-- Indicador de Radio Button estilizado --}}
                                        <div class="w-4 h-4 rounded-full border border-gray-300 bg-white flex items-center justify-center mr-3 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition duration-150">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-700 group-hover:text-indigo-600 transition uppercase serial-text">{{ $asset->serial_number }}</p>
                                            <p class="text-[10px] text-gray-400 font-semibold tracking-wide plate-text">
                                                Placa: {{ $asset->internal_code ?? 'S/P' }} — {{ $asset->room->nomenclatura ?? 'Sin Área' }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA (7 Espacios): PARÁMETROS OPERATIVOS --}}
                        <div class="lg:col-span-7 space-y-5 border-t lg:border-t-0 lg:border-l border-gray-100 pt-5 lg:pt-0 lg:pl-6">
                            
                            {{-- Técnico Asignado --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    2. Técnico Asignado
                                </label>
                                <select name="technician_id" class="w-full max-w-md rounded-lg border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white text-gray-800" required>
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}" {{ $schedule->technician_id == $technician->id ? 'selected' : '' }}>
                                            {{ $technician->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fecha Proyectada --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    3. Fecha Proyectada
                                </label>
                                <input type="date" 
                                       name="scheduled_date" 
                                       value="{{ $schedule->scheduled_date }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full max-w-xs border-gray-200 rounded-lg p-2 bg-white text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 shadow-sm"
                                       required>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN INFERIOR DE BOTONES (Abarca todo el ancho del formulario) --}}
                    <div class="pt-5 border-t border-gray-100 flex justify-between items-center">
                        <a href="{{ route('schedules.index') }}" class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest py-2.5 px-6 rounded-lg shadow-sm transition ease-in-out duration-150">
                            Actualizar Programación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('assetSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const options = document.querySelectorAll('.asset-option');

            options.forEach(opt => {
                const serial = opt.querySelector('.serial-text').textContent.toLowerCase();
                const plate = opt.querySelector('.plate-text').textContent.toLowerCase();
                opt.style.display = (serial.includes(term) || plate.includes(term)) ? 'flex' : 'none';
            });
        });
    </script>
</x-app-layout>