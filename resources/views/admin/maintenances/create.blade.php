<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen font-sans" x-data="maintenanceCreator()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2 w-full md:w-auto">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-50 to-slate-100 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Registrar Nueva Intervención</h2>
                            <span class="bg-blue-50 text-blue-700 text-[10px] px-2.5 py-1 rounded-md font-black uppercase tracking-widest border border-blue-100">
                                NUEVO TICKET
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-500">
                            Apertura de registro en bitácora técnica para activos del inventario tecnológico SIGMA.
                        </p>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Fecha de Registro</p>
                        <span class="text-xs font-bold text-slate-700">{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Operador</p>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700">
                            <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] font-black">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            {{ auth()->user()->name ?? 'Técnico SIGMA' }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('maintenances.store') }}" method="POST" id="createForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
                @csrf

                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 group hover:border-blue-200 transition-colors">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between rounded-t-2xl">
                            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Detalles de Intervención
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest">Fecha Realizada <span class="text-rose-500">*</span></label>
                                <input type="date" name="performed_at" value="{{ old('performed_at', now()->format('Y-m-d')) }}"
                                    class="w-full border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm">
                                @error('performed_at') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="relative">
                                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest flex items-center justify-between">
                                    Tipo de Servicio <span class="text-rose-500">*</span>
                                </label>

                                <input type="hidden" name="type" :value="selectedCatName">

                                <button type="button" @click="catOpen = !catOpen" :disabled="isLoading" class="w-full flex items-center justify-between border border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm disabled:opacity-50 cursor-pointer">
                                    <span class="truncate text-slate-700" x-text="catLabel"></span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform" :class="catOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="catOpen" @click.away="catOpen = false" x-transition.opacity x-cloak class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                    <div class="p-2 border-b border-slate-100 bg-slate-50">
                                        <input type="text" x-model="catSearch" placeholder="Buscar tipo..." class="w-full pl-3 pr-3 py-2 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @keydown.escape="catOpen = false">
                                    </div>
                                    <ul class="max-h-48 overflow-y-auto py-1">
                                        <template x-for="item in filteredCats" :key="item.id || item.name">
                                            <li @click="selectCat(getName(item))"
                                                class="px-4 py-2.5 text-xs font-bold cursor-pointer flex justify-between hover:bg-blue-50 text-slate-700"
                                                :class="selectedCatName === getName(item) ? 'bg-blue-50 text-blue-700' : ''">
                                                <span x-text="getName(item)"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                @error('type') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 group hover:border-blue-200 transition-colors">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between rounded-t-2xl">
                            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Selección de Activo CMDB
                            </h3>
                            <span class="text-[10px] font-black text-blue-500 bg-blue-50 border border-blue-100 px-2 py-1 rounded-md uppercase">Requerido</span>
                        </div>

                        <div class="p-6">
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest flex items-center justify-between">
                                Buscar Equipo en el Servidor <span class="text-rose-500">*</span>
                            </label>

                            <div class="relative">
                                <input type="hidden" name="asset_id" :value="selectedAssetId">

                                <button type="button" @click="assetOpen = !assetOpen" :disabled="isLoading" class="w-full flex items-center justify-between border border-slate-300 rounded-xl py-3 px-4 text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm disabled:opacity-50 cursor-pointer">
                                    <span class="truncate text-slate-700" x-text="assetLabel"></span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform" :class="assetOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="assetOpen" @click.away="assetOpen = false" x-transition.opacity x-cloak class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden">
                                    <div class="p-2 border-b border-slate-100 bg-slate-50">
                                        <input type="text" x-model="assetSearch" placeholder="Buscar por serial o hostname..." class="w-full pl-3 pr-3 py-2 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @keydown.escape="assetOpen = false">
                                    </div>
                                    <ul class="max-h-56 overflow-y-auto py-1">
                                        <template x-for="item in filteredAssets" :key="item.id">
                                            <li @click="selectAsset(item.id, getAssetLabel(item))" class="px-4 py-3 text-xs cursor-pointer border-b border-slate-50 last:border-0 hover:bg-blue-50 transition-colors" :class="selectedAssetId == item.id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700'">
                                                <div class="font-bold text-slate-800" x-text="'Serial: ' + (item.serial_number || 'S/N') + ' | Equipo: ' + (item.hostname || 'S/H')"></div>
                                                <div class="text-[10px] text-slate-500 mt-0.5" x-text="'Placa/Código Interno: ' + (item.internal_code || 'S/P')"></div>
                                            </li>
                                        </template>
                                        <li x-show="assetSearch.length < 2 && assets.length === 0" class="px-4 py-4 text-xs text-slate-500 text-center font-medium">Escriba al menos 2 caracteres para buscar...</li>
                                        <li x-show="isSearchingAssets" class="px-4 py-4 text-xs text-blue-500 text-center font-bold">Buscando en la base de datos...</li>
                                        <li x-show="assetSearch.length >= 2 && filteredAssets.length === 0 && !isSearchingAssets" class="px-4 py-3 text-xs text-rose-500 text-center font-bold">No se encontraron equipos</li>
                                    </ul>
                                </div>
                            </div>
                            @error('asset_id') <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group hover:border-blue-200 transition-colors">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Seguridad Física (Guaya)
                            </h3>

                            <label class="flex items-center cursor-pointer relative">
                                <input type="checkbox" x-model="cambioGuaya" name="cambio_guaya" value="1" class="sr-only">
                                <div class="w-9 h-5 bg-slate-200 rounded-full transition-colors duration-300" :class="cambioGuaya ? 'bg-blue-600' : 'bg-slate-300'"></div>
                                <div class="absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-300 shadow-sm" :class="cambioGuaya ? 'translate-x-4' : 'translate-x-0'"></div>
                                <span class="ml-3 text-[10px] font-black uppercase tracking-widest" :class="cambioGuaya ? 'text-blue-600' : 'text-slate-400'" x-text="cambioGuaya ? 'Registrando' : 'Sin Cambios'"></span>
                            </label>
                        </div>

                        <div x-show="cambioGuaya" x-collapse x-cloak>
                            <div class="p-6 bg-blue-50/30 border-t border-blue-50">
                                <div class="flex flex-col md:flex-row gap-6 items-center">
                                    <div class="w-full md:w-1/2">
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Estado Anterior en Activo</p>
                                        <div class="w-full border border-dashed border-slate-200 bg-white rounded-xl py-2.5 px-4 flex items-center justify-center shadow-sm">
                                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sujeto a verificación del Activo</span>
                                        </div>
                                    </div>

                                    <div class="w-full md:w-1/2">
                                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Asignar Serial de Guaya</p>
                                        <input type="text" name="security_guaya" value="{{ old('security_guaya') }}"
                                            placeholder="Escriba el serial de la guaya de seguridad..."
                                            class="w-full border-slate-300 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-800 placeholder-slate-300 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all shadow-sm uppercase"
                                            :required="cambioGuaya">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group hover:border-blue-200 transition-colors">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16h-7M4 18h7" />
                                </svg>
                                Bitácora Técnica Realizada <span class="text-rose-500">*</span>
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400" x-text="desc.length + ' / 2000 chars'"></span>
                        </div>
                        <div class="p-6">
                            <textarea x-ref="descInput" x-init="desc = $el.value" x-model="desc" name="description" rows="5" maxlength="2000"
                                class="w-full border-slate-300 rounded-xl py-3 px-4 text-sm text-slate-700 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all leading-relaxed shadow-sm resize-y"
                                placeholder="Describe detalladamente el mantenimiento realizado, síntomas, diagnósticos y piezas cambiadas...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end items-center gap-4 pt-4 pb-8">
                        <a href="{{ route('maintenances.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-200 transition-all active:scale-95 flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Crear Registro
                        </button>
                    </div>

                </div>

                <div class="lg:col-span-4 hidden md:block">
                    <div class="sticky top-6 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="p-6 bg-slate-800 text-white flex flex-col items-center justify-center text-center relative overflow-hidden">
                                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>

                                <div class="w-20 h-20 bg-slate-700 rounded-2xl border-4 border-slate-600 flex items-center justify-center mb-4 relative z-10 shadow-xl">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black tracking-tight relative z-10">Nueva Intervención</h4>
                                <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest mt-1 relative z-10">Módulo ITSM Bitácoras</p>
                            </div>

                            <div class="p-5 bg-slate-50/50 border-b border-slate-100 text-xs text-slate-600 font-medium leading-relaxed">
                                Al abrir una bitácora técnica, la plataforma vinculará este procedimiento al historial de la hoja de vida del equipo. Asegure la exactitud del serial.
                            </div>

                            <div class="p-5 space-y-4">
                                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Estándares de Operación</h5>
                                <ul class="space-y-2.5 text-xs text-slate-600 font-bold">
                                    <li class="flex items-start gap-2">
                                        <span class="text-blue-600 shrink-0">✓</span> Cambios de hardware requieren serial explícito.
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-blue-600 shrink-0">✓</span> Reportar estados físicos anómalos.
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h5 class="text-xs font-black text-blue-800 uppercase tracking-wider mb-1">¿Sabías qué?</h5>
                                    <p class="text-[11px] text-blue-600/80 leading-relaxed font-medium">El buscador de activos consulta en tiempo real mediante coincidencia de texto. Puede teclear tanto el número de serie como el hostname del equipo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @php
        // Evaluamos si viene pre-asignado directo desde otra vista (ej: cronograma)
        $isDirect = isset($assets) && $assets->count() === 1;
        $initialAsset = $isDirect ? $assets->first() : null;
        
        $assetLabel = 'Seleccione el equipo correcto...';
        $assetId = old('asset_id', '');
        
        if ($initialAsset) {
            $assetId = $initialAsset->id;
            $assetLabel = "Serial: {$initialAsset->serial_number} | Equipo: " . ($initialAsset->hostname ?? 'S/H');
        }
    @endphp

    <div id="filter-init-data" class="hidden"
        data-cat="{{ old('type', '') }}"
        data-asset="{{ $assetId }}"
        data-guaya="{{ old('cambio_guaya') ? '1' : '0' }}"
        data-asset-label="{{ $assetLabel }}"
        data-categories="{{ $categories->map(function($c){ return ['id'=>$c->name,'name'=>$c->name]; })->toJson() }}">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            const initData = document.getElementById('filter-init-data').dataset;
            const phpCategories = JSON.parse(initData.categories || '[]');

            Alpine.data('maintenanceCreator', () => ({
                desc: '',
                cambioGuaya: initData.guaya === "1",
                
                isLoading: false,
                isSearchingAssets: false,
                categories: phpCategories,
                assets: [],
                
                catOpen: false, catSearch: '',
                assetOpen: false, assetSearch: '',
                searchTimeout: null,
                
                selectedCatName: initData.cat || '', 
                selectedAssetId: initData.asset || null,
                selectedAssetLabel: initData.assetLabel || 'Seleccione el equipo correcto...',

                init() {
                    this.desc = this.$refs.descInput ? this.$refs.descInput.value : '';
                    
                    this.$watch('assetSearch', (value) => {
                        if(value.length < 2) {
                            this.assets = []; 
                            return;
                        }
                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            this.searchAssetsInDatabase(value);
                        }, 300);
                    });
                },

                async searchAssetsInDatabase(query) {
                    this.isSearchingAssets = true;
                    try {
                        // Consumimos tu ruta de búsqueda optimizada asíncrona
                        const res = await fetch(`/api/filters/api/sigma-filters/assets?q=${encodeURIComponent(query)}`);
                        const data = await res.json();
                        
                        let items = [];
                        if (Array.isArray(data)) items = data;
                        else if (data && Array.isArray(data.data)) items = data.data;
                        else if (data && Array.isArray(data.original)) items = data.original;
                        else if (typeof data === 'object') items = Object.values(data);
                        
                        this.assets = items;
                    } catch (error) {
                        console.error("Error buscando equipos en el creador:", error);
                    } finally {
                        this.isSearchingAssets = false;
                    }
                },

                getName(item) {
                    if (!item) return '';
                    return item.name || item.nombre || item.nombres || item.description || 'Sin nombre';
                },

                getAssetLabel(item) {
                    if (!item) return '';
                    // Usamos las propiedades exactas de tu modelo Asset (serial_number y hostname)
                    return `Serial: ${item.serial_number || 'S/N'} | Eq: ${item.hostname || 'S/H'}`;
                },

                get filteredCats() {
                    if (!this.catSearch) return this.categories;
                    return this.categories.filter(c => this.getName(c).toLowerCase().includes(this.catSearch.toLowerCase()));
                },
                get catLabel() {
                    if (!this.selectedCatName) return 'Seleccione un tipo...';
                    return this.selectedCatName;
                },
                selectCat(name) {
                    this.selectedCatName = name; 
                    this.catOpen = false;
                    this.catSearch = '';
                },

                get filteredAssets() {
                    if (!this.assetSearch) return this.assets;
                    const q = String(this.assetSearch).toLowerCase();
                    
                    return this.assets.filter(a => 
                        String(a.serial_number || '').toLowerCase().includes(q) || 
                        String(a.hostname || '').toLowerCase().includes(q)
                    );
                },
                get assetLabel() {
                    if (!this.assetSearch && this.assets.length === 0) return this.selectedAssetLabel;
                    let a = this.assets.find(x => x.id == this.selectedAssetId);
                    return a ? this.getAssetLabel(a) : this.selectedAssetLabel;
                },
                selectAsset(id, label) {
                    this.selectedAssetId = id; 
                    this.selectedAssetLabel = label;
                    this.assetOpen = false;
                    this.assetSearch = '';
                }
            }));
        });

        // SWEETALERT2: Alerta de Confirmación al Crear
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('updateForm') || document.getElementById('createForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');

                    Swal.fire({
                        title: '¿Registrar intervención?',
                        text: "Se creará un nuevo registro en la bitácora tecnológica general.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#f1f5f9',
                        confirmButtonText: '<span class="font-black uppercase tracking-widest text-xs">Sí, registrar</span>',
                        cancelButtonText: '<span class="font-bold uppercase tracking-widest text-xs text-slate-600">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-slate-100',
                            confirmButton: 'rounded-xl px-6 py-3 shadow-lg shadow-blue-200 transition-all hover:scale-95',
                            cancelButton: 'rounded-xl px-6 py-3 border border-slate-200 transition-all hover:bg-slate-200 mr-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...`;
                            btn.classList.add('opacity-75', 'cursor-not-allowed');
                            btn.disabled = true;
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>