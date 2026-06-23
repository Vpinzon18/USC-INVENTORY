<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1200px] w-[96%] mx-auto space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>

                <div class="flex items-center gap-5 pl-2">
                    <a href="{{ route('schedules.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 p-2 rounded-lg border border-slate-100 hidden sm:block" title="Volver al Cronograma">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </a>
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight">Modificar Agenda de Mantenimiento</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Actualiza la agenda actual o añade más equipos a esta misma programación.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('schedules.update', $schedule) }}" method="POST" id="scheduleEditForm" x-data="scheduleFormEngine()">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 space-y-6">
                        
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                <h3 class="font-bold text-slate-800">Detalles de la Agenda Actual</h3>
                            </div>
                            <div class="p-6 space-y-8">
                                
                                <div class="relative z-[60]" @click.away="dropdowns.assets.open = false">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">1. Seleccione los Equipos <span class="text-rose-500">*</span></label>
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded shadow-sm border border-blue-100"><span x-text="form.assets.length"></span> Seleccionados</span>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-3" x-show="form.assets.length > 0">
                                        <template x-for="asset in form.assets" :key="asset.id">
                                            <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all hover:bg-blue-100">
                                                <span x-text="asset.label" class="truncate max-w-[300px]"></span>
                                                <button type="button" @click="removeAsset(asset.id)" class="text-blue-400 hover:text-rose-500 transition-colors ml-1 focus:outline-none" title="Quitar equipo">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                                <input type="hidden" name="asset_ids[]" :value="asset.id">
                                            </span>
                                        </template>
                                    </div>

                                    <div class="relative">
                                        <input type="text" x-model="dropdowns.assets.search" x-ref="assetSearch"
                                               @focus="dropdowns.assets.open = true; if(dropdowns.assets.search.length > 0) fetch('assets')" 
                                               @input.debounce.300ms="fetch('assets')"
                                               placeholder="Escriba el Serial o Placa del equipo para añadirlo..." autocomplete="off"
                                               class="w-full border-slate-300 rounded-lg py-3 pl-4 pr-12 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 shadow-sm transition-all cursor-text">
                                        
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg x-show="!dropdowns.assets.loading" class="w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                            <svg x-show="dropdowns.assets.loading" class="w-5 h-5 text-blue-500 animate-spin pointer-events-none" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </div>
                                    </div>
                                    @error('asset_ids') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror

                                    <div x-show="dropdowns.assets.open" x-transition.opacity.duration.200ms
                                         class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl max-h-72 overflow-y-auto divide-y divide-slate-100">
                                        
                                        <div x-show="dropdowns.assets.results.length === 0 && dropdowns.assets.search.length === 0 && !dropdowns.assets.loading" class="p-6 text-center">
                                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-600">Busca más equipos</p>
                                        </div>

                                        <template x-for="item in dropdowns.assets.results" :key="item.id">
                                            <div @mousedown.prevent="select('assets', item)" 
                                                 class="group px-4 py-3 cursor-pointer flex items-center gap-3 transition-colors"
                                                 :class="isAssetSelected(item.id) ? 'bg-slate-50 opacity-50 cursor-not-allowed' : 'hover:bg-blue-50/80'">
                                                
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                                     :class="isAssetSelected(item.id) ? 'bg-slate-200 text-slate-400' : 'bg-slate-100 group-hover:bg-blue-200 text-slate-500 group-hover:text-blue-700'">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold truncate transition-colors" 
                                                       :class="isAssetSelected(item.id) ? 'text-slate-400' : 'text-slate-700 group-hover:text-blue-800'" 
                                                       x-text="item.label"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="relative z-[59]" @click.away="dropdowns.technicians.open = false">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">2. Técnico de Soporte Asignado <span class="text-rose-500">*</span></label>
                                    <input type="hidden" name="technician_id" x-model="form.technician_id" required>
                                    
                                    <div class="relative">
                                        <input type="text" x-model="dropdowns.technicians.search"
                                               @focus="dropdowns.technicians.open = true; if(dropdowns.technicians.search.length === 0) fetch('technicians')" 
                                               @input.debounce.300ms="fetch('technicians')"
                                               :readonly="form.technician_id !== ''"
                                               placeholder="Buscar nombre del técnico..." autocomplete="off"
                                               class="w-full border-slate-300 rounded-lg py-3 pl-4 pr-12 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 shadow-sm transition-all cursor-text"
                                               :class="{'border-emerald-400 bg-emerald-50 focus:ring-emerald-500 cursor-default': form.technician_id !== ''}">
                                        
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" x-show="form.technician_id !== ''" @click="clearSelection('technicians')" class="p-1 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-md transition-colors" title="Cambiar de técnico">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                    @error('technician_id') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror

                                    <div x-show="dropdowns.technicians.open && form.technician_id === ''" x-transition.opacity.duration.200ms
                                         class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto divide-y divide-slate-100">
                                        <template x-for="item in dropdowns.technicians.results" :key="item.id">
                                            <div @mousedown.prevent="select('technicians', item)" class="group px-4 py-3 hover:bg-blue-50/80 cursor-pointer flex items-center gap-3 transition-colors">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-blue-200 flex items-center justify-center text-slate-500 group-hover:text-blue-700 transition-colors shrink-0 font-black text-xs uppercase" x-text="item.label.substring(0,2)"></div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-slate-700 group-hover:text-blue-800 truncate" x-text="item.label"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">3. Fecha Programada <span class="text-rose-500">*</span></label>
                                        <input type="date" name="scheduled_date" required value="{{ old('scheduled_date', \Carbon\Carbon::parse($schedule->scheduled_date)->format('Y-m-d')) }}" 
                                               class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-2 focus:ring-blue-500 text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium cursor-pointer py-2.5">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Estado de Agenda <span class="text-rose-500">*</span></label>
                                        <select name="status" class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-2 focus:ring-blue-500 text-sm transition-all bg-slate-50 text-slate-700 font-bold uppercase tracking-wider cursor-pointer py-2.5">
                                            <option value="PENDIENTE" {{ $schedule->status === 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                            <option value="REALIZADO" {{ $schedule->status === 'REALIZADO' ? 'selected' : '' }}>REALIZADO</option>
                                            <option value="VENCIDO" {{ $schedule->status === 'VENCIDO' ? 'selected' : '' }}>VENCIDO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <a href="{{ route('schedules.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 hover:text-slate-800 rounded-lg shadow-sm transition-all text-center">
                                Cancelar
                            </a>
                            <button type="submit" :disabled="form.assets.length === 0 || form.technician_id === ''"
                                    class="px-8 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 disabled:bg-slate-300 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121 12h-4.5" /></svg>
                                Actualizar Agenda
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6 space-y-5">
                            <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Bitácora de Cambios
                            </h3>
                            <div class="space-y-4 text-xs text-slate-600 leading-relaxed">
                                <div class="bg-amber-50/60 border border-amber-200 p-3 rounded-lg">
                                    <p class="text-amber-800 font-medium">Estás editando un registro operativo activo. Cualquier cambio en la fecha o técnico impactará directamente las notificaciones.</p>
                                </div>
                                <div class="bg-slate-50 border border-slate-200 p-3 rounded-lg flex gap-3">
                                    <span class="text-slate-400 font-bold">1.</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Añadir más equipos:</strong> Si decides agregar más equipos a esta edición, el sistema actualizará la agenda actual y creará <strong>nuevas agendas</strong> para los equipos adicionales.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scheduleFormEngine', () => ({
                
                form: {
                    assets: [
                        {
                            id: '{{ $schedule->asset_id }}',
                            label: 'SN: {{ $schedule->asset->serial_number }}{{ $schedule->asset->internal_code ? " | Placa: ".$schedule->asset->internal_code : "" }} - Ubicación: {{ $schedule->asset->room->nomenclatura ?? "Sin Salón" }}'
                        }
                    ],
                    technician_id: '{{ $schedule->technician_id ?? "" }}'
                },

                dropdowns: {
                    assets:       { open: false, search: '', results: [], loading: false },
                    technicians:  { open: false, search: '{{ $schedule->technician->name ?? "" }}', results: [], loading: false }
                },

                isAssetSelected(id) {
                    return this.form.assets.some(a => a.id === id);
                },

                async fetch(type) {
                    const dd = this.dropdowns[type];
                    if (type === 'technicians' && this.form.technician_id !== '') return;

                    dd.loading = true;
                    const endpoints = {
                        'assets': '/api/filters/api/sigma-filters/assets',
                        'technicians': '/api/filters/api/sigma-filters/technicians'
                    };

                    let url = new URL(endpoints[type], window.location.origin);
                    url.searchParams.append('search', dd.search);

                    try {
                        let res = await fetch(url);
                        let raw = await res.json();
                        let items = Array.isArray(raw) ? raw : (raw.data || raw.items || []);
                        
                        dd.results = items.map(i => {
                            let label = i.name || i.nomenclatura || i.full_name || i.label || `ID: ${i.id}`;
                            return { id: i.id, label: label };
                        });
                    } catch(e) { dd.results = []; }
                    dd.loading = false;
                },

                select(type, item) {
                    const dd = this.dropdowns[type];
                    
                    if(type === 'assets') {
                        if(!this.isAssetSelected(item.id)) {
                            this.form.assets.push({ id: item.id, label: item.label });
                        }
                        dd.search = '';
                        this.$refs.assetSearch.focus(); 
                    }
                    if(type === 'technicians') {
                        this.form.technician_id = item.id;
                        dd.search = item.label;
                        dd.open = false;
                    }
                },

                removeAsset(id) {
                    this.form.assets = this.form.assets.filter(a => a.id !== id);
                },

                clearSelection(type) {
                    if(type === 'technicians') {
                        this.dropdowns.technicians.search = '';
                        this.form.technician_id = '';
                        setTimeout(() => { this.$el.querySelector(`input[name=technician_id]`).previousElementSibling.focus(); }, 50);
                    }
                }
            }));
        });
    </script>
</x-app-layout>