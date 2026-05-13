<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                
                <div class="p-8 bg-gray-800 text-white">
                    <h2 class="font-black text-xl uppercase tracking-tighter">Nuevo Registro Técnico</h2>
                    <p class="text-gray-400 text-xs font-bold uppercase">Diligencie la intervención realizada al equipo</p>
                </div>

                <form action="{{ route('maintenances.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">1. Seleccione el Equipo</label>
                        <div class="relative">
                            <input type="text" id="assetSearch" placeholder="Buscar por Serial o Placa USC..." 
                                   class="w-full pl-4 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm font-bold">
                        </div>

                        <div class="max-h-48 overflow-y-auto border border-gray-100 rounded-2xl p-2 bg-gray-50 grid grid-cols-1 md:grid-cols-2 gap-2" id="assetContainer">
                            @foreach($assets as $asset)
                                <label class="asset-option flex items-center p-3 bg-white rounded-xl border border-transparent hover:border-blue-500 cursor-pointer transition-all group">
                                    <input type="radio" name="asset_id" value="{{ $asset->id }}" class="hidden peer" required>
                                    <div class="peer-checked:bg-blue-600 peer-checked:border-blue-600 w-4 h-4 rounded-full border-2 border-gray-300 mr-3 transition-all"></div>
                                    <div>
                                        <p class="text-[11px] font-black text-gray-800 uppercase serial-text">{{ $asset->serial_number }}</p>
                                        <p class="text-[9px] text-blue-600 font-bold plate-text">{{ $asset->internal_code ?? 'S/P' }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-1">
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
            2. Fecha de Realización
        </label>
        <input type="date" 
               name="performed_at" 
               value="{{ date('Y-m-d') }}" 
               max="{{ date('Y-m-d') }}"
               class="w-full border-gray-200 rounded-2xl py-4 bg-gray-50 text-sm font-bold focus:ring-4 focus:ring-blue-100 transition-all"
               required>
        <p class="mt-1 text-[10px] text-gray-400 uppercase font-bold italic">* Seleccione el día que hizo el trabajo físico</p>
    </div>


</div>

                    <div class="md:col-span-1" x-data="{ type: 'Preventivo' }">
    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
        3. Tipo de Intervención
    </label>
    
    <select x-model="type" name="type_selector" class="w-full border-gray-200 rounded-2xl py-4 bg-gray-50 text-sm font-bold focus:ring-4 focus:ring-blue-100 transition-all">
        <option value="Preventivo">Mantenimiento Preventivo</option>
        <option value="Correctivo">Mantenimiento Correctivo</option>
        <option value="Mejora">Mejora de Hardware/Software</option>
        <option value="Diagnóstico">Diagnóstico Técnico</option>
        <option value="Otro">Otro (Especificar...)</option>
    </select>

    <div x-show="type === 'Otro'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="mt-3">
        <input type="text" 
               name="custom_type" 
               placeholder="¿Qué tipo de intervención es?" 
               class="w-full border-blue-200 rounded-2xl py-3 bg-blue-50 text-sm font-bold placeholder-blue-300 focus:ring-4 focus:ring-blue-100"
               :required="type === 'Otro'">
    </div>
</div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">3. Descripción del Trabajo</label>
                            <textarea name="description" rows="5" placeholder="Sea detallado: 'Se realiza limpieza, cambio de pasta térmica y guaya de seguridad...'" 
                                      class="w-full border-gray-200 rounded-2xl p-4 bg-gray-50 text-sm focus:ring-4 focus:ring-blue-100" required></textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t flex justify-between items-center">
                        <a href="{{ route('maintenances.index') }}" class="text-xs font-black text-gray-400 uppercase hover:text-gray-600">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-xl transition-all active:scale-95 uppercase text-xs">
                            Guardar en Hoja de Vida
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Script de búsqueda rápida
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