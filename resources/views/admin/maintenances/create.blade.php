<x-app-layout>
    <div class="py-6">
        {{-- Expandimos el contenedor al máximo ancho del sistema (max-w-7xl) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- ENCABEZADO TOTALMENTE INTEGRADO --}}
                <div class="p-5 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex flex-col">
                        <h2 class="font-bold text-xl text-gray-800 tracking-tight">Nuevo Registro Técnico</h2>
                        <p class="text-xs text-gray-500">Gestione e introduzca la intervención técnica del equipo en su hoja de vida.</p>
                    </div>
                    @if($assets->count() === 1)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 tracking-wide uppercase">
                            Asignación Automática
                        </span>
                    @endif
                </div>

                <form action="{{ route('maintenances.store') }}" method="POST" 
                      x-data="{ 
                          type: 'Preventivo', 
                          cambioGuaya: false, 
                          currentGuaya: '{{ $assets->count() === 1 ? ($assets->first()->security_guaya ?? 'Sin Guaya Registrada') : 'Seleccione un equipo...' }}' 
                      }" 
                      class="p-6">
                    @csrf
                    
                    <input type="hidden" name="maintenance_schedule_id" value="{{ request('schedule_id') }}">

                    {{-- GRID PRINCIPAL DE DOS COLUMNAS DE TRABAJO (Distribución de espacio) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        {{-- COLUMNA IZQUIERDA (4 u 5 Espacios): CONTROL DE EQUIPOS --}}
                        <div class="lg:col-span-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">1. Seleccione el Equipo</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="assetSearch" placeholder="Buscar por serial o placa..." 
                                           class="w-full bg-gray-50/50 pl-9 p-2.5 border border-gray-200 rounded-lg text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 outline-none shadow-sm"
                                           {{ $assets->count() === 1 ? 'disabled' : '' }}>
                                </div>
                            </div>

                            {{-- Contenedor con altura controlada para evitar desbordes --}}
                            <div class="max-h-[300px] overflow-y-auto border border-gray-200 rounded-xl p-2 bg-gray-50/30 space-y-1.5" id="assetContainer">
                                @foreach($assets as $asset)
                                    <label class="asset-option flex items-center p-2.5 bg-white border border-gray-100 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-gray-50/50 transition duration-150 group relative">
                                        <input type="radio" name="asset_id" value="{{ $asset->id }}" class="hidden peer" required
                                               {{ $assets->count() === 1 ? 'checked' : '' }}
                                               x-on:change="currentGuaya = '{{ $asset->security_guaya ?? 'Sin Guaya Registrada' }}'">
                                        
                                        <div class="w-4 h-4 rounded-full border border-gray-300 bg-white flex items-center justify-center mr-3 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition duration-150">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-700 group-hover:text-indigo-600 transition uppercase serial-text">{{ $asset->serial_number }}</p>
                                            <p class="text-[10px] text-gray-400 font-semibold tracking-wide plate-text">{{ $asset->internal_code ?? 'S/P' }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA (7 Espacios): DATOS DE INTERVENCIÓN --}}
                        <div class="lg:col-span-7 space-y-5 border-t lg:border-t-0 lg:border-l border-gray-100 pt-5 lg:pt-0 lg:pl-6">
                            
                            {{-- Fila con Fecha y Checkbox alineados en grillas internas --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">2. Fecha de Realización</label>
                                    <input type="date" name="performed_at" value="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-lg p-2 bg-white text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 shadow-sm" required>
                                </div>

                                <div class="flex flex-col justify-start pt-1">
                                    <label class="flex items-center space-x-2.5 cursor-pointer select-none mb-1.5 mt-5">
                                        <input type="checkbox" x-model="cambioGuaya" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition duration-150 cursor-pointer">
                                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">¿Instaló / reemplazó guaya?</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Espacio dinámico para la guaya sin romper el flujo vertical --}}
                            <div x-show="cambioGuaya" x-transition class="bg-gray-50/50 p-3 rounded-xl border border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-3 items-center">
                                <div class="text-[10px] font-bold text-indigo-700 tracking-wide">
                                    ÚLTIMA GUAYA VINCULADA:<br>
                                    <span x-text="currentGuaya" class="inline-block bg-indigo-600 text-white px-2 py-0.5 rounded font-mono uppercase tracking-normal mt-1"></span>
                                </div>
                                <input type="text" name="security_guaya" placeholder="Serial de la nueva guaya..." 
                                       class="w-full border-gray-200 rounded-lg p-2 bg-white text-sm font-medium placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 shadow-sm"
                                       :required="cambioGuaya">
                            </div>

                            {{-- Tipo de Intervención --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">3. Tipo de Intervención</label>
                                <select name="type" x-model="type" class="w-full max-w-md border-gray-200 rounded-lg p-2 bg-white text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 shadow-sm" required>
                                    <option value="Preventivo">Mantenimiento Preventivo</option>
                                    <option value="Correctivo">Mantenimiento Correctivo</option>
                                    <option value="Mejora">Mejora de Hardware/Software</option>
                                    <option value="Diagnóstico">Diagnóstico Técnico</option>
                                    <option value="Otro">Otro (Especificar...)</option>
                                </select>
                                
                                <div x-show="type === 'Otro'" x-transition class="mt-2 max-w-md">
                                    <input type="text" name="custom_type" placeholder="Especifique el tipo..." 
                                           class="w-full border-gray-200 rounded-lg p-2 bg-white text-sm font-medium placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 shadow-sm"
                                           :required="type === 'Otro'">
                                </div>
                            </div>

                            {{-- Descripción del Trabajo --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">4. Descripción del Trabajo</label>
                                <textarea name="description" rows="5" placeholder="Detalle minuciosamente el estado físico, reparaciones efectuadas, software instalado o fallas reportadas..." 
                                          class="w-full border-gray-200 rounded-lg p-3 bg-white text-sm text-gray-700 placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500 resize-none shadow-sm" required></textarea>
                            </div>

                            {{-- BOTONES DE CONTROL ACOPLADOS --}}
                            <div class="pt-2 flex justify-end items-center gap-4">
                                <a href="{{ route('maintenances.index') }}" class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">
                                    Cancelar
                                </a>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest py-2.5 px-6 rounded-lg shadow-sm transition ease-in-out duration-150">
                                    Guardar en Hoja de Vida
                                </button>
                            </div>

                        </div>
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