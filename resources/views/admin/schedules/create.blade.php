<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" 
                 x-data="{ selectedAssets: [] }">
                
                <div class="p-8 bg-gray-800 text-white">
                    <h2 class="font-black text-xl uppercase tracking-tighter">Programación Masiva de Mantenimientos</h2>
                    <p class="text-gray-400 text-xs font-bold uppercase">Asigne múltiples equipos a un técnico para una fecha determinada</p>
                </div>

                <form action="{{ route('schedules.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                                1. Seleccione los Equipos a Programar 
                                <span class="text-blue-600" x-text="selectedAssets.length > 0 ? '(' + selectedAssets.length + ' seleccionados)' : ''"></span>
                            </label>
                            
                            <button type="button" 
                                    @click="if(selectedAssets.length === {{ $assets->count() }}) { selectedAssets = []; } else { selectedAssets = [ @foreach($assets as $asset) '{{ $asset->id }}', @endforeach ]; }"
                                    class="text-[10px] font-black uppercase tracking-wider text-blue-600 hover:text-blue-800 transition-colors focus:outline-none">
                                <span x-text="selectedAssets.length === {{ $assets->count() }} ? 'Desmarcar Todos' : 'Seleccionar Todos'"></span>
                            </button>
                        </div>

                        <div class="relative">
                            <input type="text" id="assetSearch" placeholder="Filtrar por Serial, Placa o Aula..." 
                                   class="w-full pl-4 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm font-bold">
                        </div>

                        <div class="max-h-56 overflow-y-auto border border-gray-100 rounded-2xl p-2 bg-gray-50 grid grid-cols-1 md:grid-cols-2 gap-2" id="assetContainer">
                            @foreach($assets as $asset)
                                <label class="asset-option flex items-center p-3 bg-white rounded-xl border border-transparent hover:border-blue-500 cursor-pointer transition-all group">
                                    
                                    <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" 
                                           x-model="selectedAssets" class="hidden peer">
                                    
                                    <div class="peer-checked:bg-blue-600 peer-checked:border-blue-600 w-4 h-4 rounded border-2 border-gray-300 mr-3 transition-all flex items-center justify-center text-white text-[10px]">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>

                                    <div>
                                        <p class="text-[11px] font-black text-gray-800 uppercase serial-text">{{ $asset->serial_number }}</p>
                                        <p class="text-[9px] text-blue-600 font-bold plate-text">
                                            Placa: {{ $asset->internal_code ?? 'S/P' }} — {{ $asset->room->nomenclatura ?? 'Sin Área' }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-1">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                                2. Técnico Asignado
                            </label>
                            <select name="technician_id" class="w-full border-gray-200 rounded-2xl py-4 bg-gray-50 text-sm font-bold focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all cursor-pointer" required>
                                <option value="" disabled selected>Seleccione el responsable técnico...</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[10px] text-gray-400 uppercase font-bold italic">* Funcionario encargado de realizar la intervención</p>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                                3. Fecha Proyectada
                            </label>
                            <input type="date" 
                                   name="scheduled_date" 
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ date('Y-m-d', strtotime('+1 week')) }}"
                                   class="w-full border-gray-200 rounded-2xl py-4 bg-gray-50 text-sm font-bold focus:ring-4 focus:ring-blue-100 transition-all"
                                   required>
                            <p class="mt-1 text-[10px] text-gray-400 uppercase font-bold italic">* Plazo máximo sugerido para ejecutar la revisión semestral</p>
                        </div>

                    </div>

                    <div class="pt-6 border-t flex justify-between items-center">
                        <a href="{{ route('schedules.index') }}" class="text-xs font-black text-gray-400 uppercase hover:text-gray-600">Cancelar</a>
                        <button type="submit" 
                                :disabled="selectedAssets.length === 0"
                                :class="selectedAssets.length === 0 ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'"
                                class="text-white font-black py-4 px-10 rounded-2xl shadow-xl transition-all active:scale-95 uppercase text-xs">
                            Agendar Equipos
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