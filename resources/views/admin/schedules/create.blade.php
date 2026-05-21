<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" 
                 x-data="multiAssetSearch()">
                
                <div class="p-8 bg-gray-800 text-white">
                    <h2 class="font-black text-xl uppercase tracking-tighter">Programación Masiva de Mantenimientos</h2>
                    <p class="text-gray-400 text-xs font-bold uppercase">Asigne múltiples equipos a un técnico para una fecha determinada</p>
                </div>

                <form action="{{ route('schedules.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ request('schedule_id') }}">
                    <template x-for="asset in selectedAssets" :key="asset.id">
                        <input type="hidden" name="asset_ids[]" :value="asset.id">
                    </template>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                                1. Busque y Seleccione los Equipos 
                                <span class="text-blue-600" x-text="selectedAssets.length > 0 ? '(' + selectedAssets.length + ' en la lista)' : ''"></span>
                            </label>
                            
                            <button type="button" x-show="selectedAssets.length > 0"
                                    @click="selectedAssets = []"
                                    class="text-[10px] font-black uppercase tracking-wider text-red-500 hover:text-red-700 transition-colors focus:outline-none">
                                Vaciar Lista
                            </button>
                        </div>

                        <div class="relative z-50">
                            <input type="text" x-ref="searchInput"
                                   x-model="searchQuery" 
                                   @input.debounce.300ms="fetchAssets()"
                                   @focus="isOpen = true"
                                   @click.away="isOpen = false"
                                   placeholder="Escriba Serial, Placa o Aula para buscar... (Ej: MJ79...)" 
                                   class="w-full pl-4 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm font-bold"
                                   autocomplete="off">
                            
                            <div x-show="isLoading" class="absolute right-4 top-4 text-blue-500">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <ul x-show="isOpen && results.length > 0"
                                x-transition
                                class="absolute w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto divide-y divide-gray-100">
                                <template x-for="asset in results" :key="asset.id">
                                    <li @click="selectAsset(asset)"
                                        class="p-3 hover:bg-blue-50 cursor-pointer transition-colors group flex items-center justify-between">
                                        <div>
                                            <div class="font-black text-gray-800 text-xs uppercase group-hover:text-blue-700 transition-colors" x-text="asset.serial_number"></div>
                                            <div class="text-[10px] text-gray-500 mt-0.5">
                                                Placa: <span class="text-blue-600 font-mono font-bold" x-text="asset.internal_code || 'S/P'"></span> 
                                                — Aula: <span x-text="asset.room ? asset.room.nomenclatura : 'NO ASIGNADO'"></span>
                                            </div>
                                        </div>
                                        <div class="text-blue-500 font-black text-lg bg-blue-100 w-6 h-6 rounded flex items-center justify-center">+</div>
                                    </li>
                                </template>
                            </ul>

                            <div x-show="isOpen && searchQuery.length >= 2 && results.length === 0 && !isLoading"
                                 class="absolute w-full mt-1 bg-white border border-red-200 rounded-xl shadow-lg p-4 text-center text-xs text-red-500 font-bold">
                                No se encontraron equipos disponibles con ese criterio.
                            </div>
                        </div>

                        <div x-show="selectedAssets.length > 0" class="mt-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Equipos en la orden de programación:</p>
                            <div class="max-h-56 overflow-y-auto border border-gray-100 rounded-2xl p-2 bg-gray-50 grid grid-cols-1 md:grid-cols-2 gap-2">
                                <template x-for="asset in selectedAssets" :key="asset.id">
                                    <div class="flex items-center p-3 bg-white rounded-xl border border-blue-200 shadow-sm transition-all group">
                                        
                                        <div class="bg-blue-600 w-4 h-4 rounded mr-3 flex items-center justify-center text-white shrink-0">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] font-black text-gray-800 uppercase truncate" x-text="asset.serial_number"></p>
                                            <p class="text-[9px] text-blue-600 font-bold truncate" x-text="'Placa: ' + (asset.internal_code || 'S/P') + ' — ' + (asset.room ? asset.room.nomenclatura : 'Sin Área')"></p>
                                        </div>

                                        <button type="button" @click="removeAsset(asset.id)" 
                                                class="ml-2 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Quitar de la lista">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('multiAssetSearch', () => ({
                searchQuery: '',
                results: [],
                isOpen: false,
                isLoading: false,
                selectedAssets: [],

                async fetchAssets() {
                    if (this.searchQuery.length < 2) {
                        this.results = [];
                        this.isOpen = false;
                        return;
                    }
                    this.isLoading = true;
                    this.isOpen = true;

                    try {
                        const response = await fetch(`/schedules/search-assets?q=${encodeURIComponent(this.searchQuery)}`);
                        const data = await response.json();
                        
                        // Filtra los resultados para no mostrar equipos que ya están en la canasta de seleccionados
                        this.results = data.filter(asset => !this.selectedAssets.some(s => s.id === asset.id));
                    } catch (error) {
                        console.error('Error buscando equipos:', error);
                        this.results = [];
                    } finally {
                        this.isLoading = false;
                    }
                },

                selectAsset(asset) {
                    // Agrega el equipo a la lista visual
                    this.selectedAssets.push(asset);
                    // Resetea el buscador para seguir buscando
                    this.searchQuery = '';
                    this.results = [];
                    this.isOpen = false;
                    this.$refs.searchInput.focus(); // Mantiene el cursor en el input para búsquedas rápidas
                },

                removeAsset(assetId) {
                    // Elimina el equipo de la lista
                    this.selectedAssets = this.selectedAssets.filter(s => s.id !== assetId);
                }
            }))
        })
    </script>
</x-app-layout>