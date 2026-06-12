<x-app-layout>
    <div class="py-6">
        {{-- Expandimos el layout al ancho máximo institucional del sistema (max-w-7xl) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" 
                 x-data="multiAssetSearch()">
                
                {{-- ENCABEZADO MINIMALISTA UNIFICADO --}}
                <div class="p-5 bg-white border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex flex-col">
                        <h2 class="font-bold text-xl text-gray-800 tracking-tight">Programación Masiva de Mantenimientos</h2>
                        <p class="text-xs text-gray-500">Ordene y agende revisiones de hardware asignando lotes de equipos a un responsable técnico.</p>
                    </div>
                </div>

                <form action="{{ route('schedules.store') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ request('schedule_id') }}">
                    <template x-for="asset in selectedAssets" :key="asset.id">
                        <input type="hidden" name="asset_ids[]" :value="asset.id">
                    </template>
                    
                    {{-- ARQUITECTURA DE TRABAJO EN DOS COLUMNAS SIMÉTRICAS --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        {{-- COLUMNA IZQUIERDA (5 Espacios): MOTOR DE BÚSQUEDA Y SELECCIÓN EN TIEMPO REAL --}}
                        <div class="lg:col-span-5 space-y-4">
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        1. Busque y Seleccione los Equipos 
                                        <span class="text-indigo-600 font-extrabold" x-text="selectedAssets.length > 0 ? '(' + selectedAssets.length + ' añadidos)' : ''"></span>
                                    </label>
                                    
                                    <button type="button" x-show="selectedAssets.length > 0"
                                            @click="selectedAssets = []"
                                            class="text-[10px] font-bold uppercase tracking-wider text-red-500 hover:text-red-700 transition focus:outline-none">
                                        Vaciar Lista
                                    </button>
                                </div>

                                <div class="relative z-50">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" x-ref="searchInput"
                                           x-model="searchQuery" 
                                           @input.debounce.300ms="fetchAssets()"
                                           @focus="isOpen = true"
                                           @click.away="isOpen = false"
                                           placeholder="Escriba Serial, Placa o Aula para buscar..." 
                                           class="w-full bg-gray-50/50 pl-9 p-2.5 border border-gray-200 rounded-lg text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 outline-none shadow-sm"
                                           autocomplete="off">
                                    
                                    {{-- Loader Spinner AJAX --}}
                                    <div x-show="isLoading" class="absolute right-3 top-3 text-indigo-600">
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>

                                    {{-- Desplegable de Resultados Encontrados --}}
                                    <ul x-show="isOpen && results.length > 0"
                                        x-transition
                                        class="absolute w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-48 overflow-y-auto divide-y divide-gray-100">
                                        <template x-for="asset in results" :key="asset.id">
                                            <li @click="selectAsset(asset)"
                                                class="p-2.5 hover:bg-indigo-50/60 cursor-pointer transition group flex items-center justify-between">
                                                <div class="min-w-0">
                                                    <div class="font-bold text-gray-700 text-xs uppercase group-hover:text-indigo-600 transition" x-text="asset.serial_number"></div>
                                                    <div class="text-[10px] text-gray-400 font-medium mt-0.5">
                                                        Placa: <span class="text-indigo-600 font-mono font-bold" x-text="asset.internal_code || 'S/P'"></span> 
                                                        — Aula: <span x-text="asset.room ? asset.room.nomenclatura : 'NO ASIGNADO'"></span>
                                                    </div>
                                                </div>
                                                <div class="text-indigo-600 font-bold text-xs bg-indigo-50 group-hover:bg-indigo-600 group-hover:text-white w-5 h-5 rounded flex items-center justify-center transition border border-indigo-100">+</div>
                                            </li>
                                        </template>
                                    </ul>

                                    <div x-show="isOpen && searchQuery.length >= 2 && results.length === 0 && !isLoading"
                                         class="absolute w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-md p-3 text-center text-xs text-amber-600 font-bold">
                                        ⚠️ No se encontraron equipos disponibles con ese criterio.
                                    </div>
                                </div>
                            </div>

                            {{-- Canasta de Equipos Agendados (Aparece dinámicamente) --}}
                            <div x-show="selectedAssets.length > 0" x-transition class="pt-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 tracking-wider">Equipos en la orden de programación:</p>
                                <div class="max-h-[260px] overflow-y-auto border border-gray-200 rounded-xl p-2 bg-gray-50/30 space-y-1.5">
                                    <template x-for="asset in selectedAssets" :key="asset.id">
                                        <div class="flex items-center p-2 bg-white border border-gray-100 rounded-lg shadow-sm transition group">
                                            <div class="bg-indigo-600 w-3.5 h-3.5 rounded mr-2.5 flex items-center justify-center text-white shrink-0">
                                                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-gray-700 uppercase truncate" x-text="asset.serial_number"></p>
                                                <p class="text-[9px] text-gray-400 font-semibold truncate" x-text="'Placa: ' + (asset.internal_code || 'S/P') + ' — ' + (asset.room ? asset.room.nomenclatura : 'Sin Área')"></p>
                                            </div>

                                            <button type="button" @click="removeAsset(asset.id)" 
                                                    class="ml-2 p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition" title="Quitar de la lista">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA (7 Espacios): PARAMETRIZACIÓN OPERATIVA DE ASIGNACIÓN --}}
                        <div class="lg:col-span-7 space-y-5 border-t lg:border-t-0 lg:border-l border-gray-100 pt-5 lg:pt-0 lg:pl-6">
                            
                            {{-- Selector de Técnico --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    2. Técnico Asignado
                                </label>
                                <select name="technician_id" class="w-full max-w-md rounded-lg border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white text-gray-800" required>
                                    <option value="" disabled selected>Seleccione el responsable técnico...</option>
                                    @foreach($technicians as $technician)
                                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-[9px] text-gray-400 font-medium">* Funcionario del área técnico/soporte encargado de realizar la intervención física.</p>
                            </div>

                            {{-- Selector de Fecha --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    3. Fecha Proyectada
                                </label>
                                <input type="date" 
                                       name="scheduled_date" 
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ date('Y-m-d', strtotime('+1 week')) }}"
                                       class="w-full max-w-xs border-gray-200 rounded-lg p-2 bg-white text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 shadow-sm"
                                       required>
                                <p class="mt-1 text-[9px] text-gray-400 font-medium">* Plazo máximo sugerido y pactado para ejecutar la revisión semestral de inventario.</p>
                            </div>

                            {{-- BOTONES DE CONFIRMACIÓN ALINEADOS AL FINAL --}}
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                <a href="{{ route('schedules.index') }}" class="text-xs font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition">
                                    Cancelar
                                </a>
                                <button type="submit" 
                                        :disabled="selectedAssets.length === 0"
                                        :class="selectedAssets.length === 0 ? 'opacity-40 cursor-not-allowed bg-gray-400' : 'bg-indigo-600 hover:bg-indigo-700 shadow-sm'"
                                        class="text-white font-bold text-xs uppercase tracking-widest py-2.5 px-6 rounded-lg transition ease-in-out duration-150">
                                    Agendar Equipos
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- LÓGICA DE JAVASCRIPT / ALPINE INTACTA --}}
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
                        
                        this.results = data.filter(asset => !this.selectedAssets.some(s => s.id === asset.id));
                    } catch (error) {
                        console.error('Error buscando equipos:', error);
                        this.results = [];
                    } finally {
                        this.isLoading = false;
                    }
                },

                selectAsset(asset) {
                    this.selectedAssets.push(asset);
                    this.searchQuery = '';
                    this.results = [];
                    this.isOpen = false;
                    this.$refs.searchInput.focus(); 
                },

                removeAsset(assetId) {
                    this.selectedAssets = this.selectedAssets.filter(s => s.id !== assetId);
                }
            }))
        })
    </script>
</x-app-layout>