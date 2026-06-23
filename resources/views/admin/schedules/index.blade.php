<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight">Cronograma Semestral</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Planificación y control de mantenimientos preventivos obligatorios.</p>
                    </div>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('schedules.create') }}" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Programar Equipo
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col min-h-[500px]">
                
                <div class="p-5 border-b border-slate-100 bg-slate-50/50"
                     x-data="{ 
                        rules: {{ json_encode($currentRules ?? [['field' => 'asset', 'operator' => 'contains', 'value' => '', 'text' => '']]) }},
                        addRule() {
                            this.rules.push({ field: 'asset', operator: 'contains', value: '', text: '' });
                        },
                        removeRule(index) {
                            this.rules.splice(index, 1);
                            if(this.rules.length === 0) this.addRule();
                        }
                     }">
                    
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                        <h3 class="font-bold text-slate-800">Filtros de Búsqueda Avanzada</h3>
                    </div>

                    <form action="{{ route('schedules.index') }}" method="GET" class="space-y-4" id="filterForm">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">

                        <div class="space-y-3">
                            <template x-for="(rule, index) in rules" :key="index">
                                <div class="flex flex-wrap items-center gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm transition-all hover:border-blue-300">
                                    
                                    <div class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest min-w-[30px] text-center select-none">
                                        <template x-if="index === 0"><span>---</span></template>
                                        <template x-if="index > 0"><span class="text-blue-600 bg-blue-50 border border-blue-100 px-2 py-1 rounded">Y</span></template>
                                    </div>

                                    <select :name="'rules['+index+'][field]'" x-model="rule.field" 
                                            @change="rule.value = ''; rule.text = ''; rule.operator = 'contains'"
                                            class="border-slate-300 rounded-lg py-2 px-3 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all cursor-pointer">
                                        <option value="asset">Equipo / Placa</option>
                                        <option value="sede">Ubicación - Sede</option>
                                        <option value="building">Ubicación - Bloque / Edificio</option>
                                        <option value="room">Ubicación - Salón / Espacio</option>
                                        <option value="dependency">Organización - Dependencia</option>
                                        <option value="technician">Técnico Asignado</option>
                                        <option value="status">Estado de Agenda</option>
                                    </select>

                                    <div class="min-w-[160px]">
                                        <select :name="'rules['+index+'][operator]'" x-model="rule.operator"
                                                class="w-full border-slate-300 rounded-lg py-2 px-3 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all cursor-pointer">
                                            <option value="contains">contiene el texto</option>
                                            <option value="equals">es exactamente</option>
                                        </select>
                                    </div>

                                    <div class="flex-1 min-w-[250px]">
                                        
                                        <template x-if="rule.field === 'asset'">
                                            <input type="text" :name="'rules['+index+'][value]'" x-model="rule.value" required
                                                   placeholder="Escriba el serial o placa..."
                                                   class="w-full border-slate-300 rounded-lg py-2 px-3 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                                        </template>

                                        <template x-if="rule.field === 'status'">
                                            <select :name="'rules['+index+'][value]'" x-model="rule.value" required class="w-full border-slate-300 rounded-lg py-2 px-3 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all">
                                                <option value="">[ Seleccione un Estado ]</option>
                                                <option value="PENDIENTE">PENDIENTE</option>
                                                <option value="REALIZADO">REALIZADO</option>
                                                <option value="VENCIDO">VENCIDO</option>
                                            </select>
                                        </template>

                                        <template x-if="['sede', 'building', 'technician', 'room', 'dependency'].includes(rule.field)">
                                            <div x-data="ajaxSelect()" class="relative w-full" @click.away="open = false">
                                                
                                                <input type="hidden" :name="'rules['+index+'][value]'" x-model="rule.value">
                                                <input type="hidden" :name="'rules['+index+'][text]'" x-model="rule.text">
                                                
                                                <div class="relative">
                                                    <input type="text" x-model="search" 
                                                           @input.debounce.400ms="fetchData()" 
                                                           @focus="open = true; fetchData()"
                                                           :placeholder="placeholder"
                                                           autocomplete="off"
                                                           class="w-full border-slate-300 rounded-lg py-2 pl-3 pr-10 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all cursor-text">
                                                    
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <svg x-show="!loading" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                        <svg x-show="loading" class="w-4 h-4 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    </div>
                                                </div>

                                                <div x-show="open" x-transition.opacity
                                                     class="absolute z-[60] w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                                    
                                                    <template x-for="item in results" :key="item.id">
                                                        <div @mousedown.prevent="selectItem(item)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-slate-50 flex items-center transition-colors">
                                                            <span x-text="item.label" class="text-sm font-bold text-slate-700 block truncate"></span>
                                                        </div>
                                                    </template>

                                                    <div x-show="!loading && results.length === 0 && search.length > 0" class="px-4 py-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                                                        No se encontraron resultados
                                                    </div>
                                                    <div x-show="!loading && results.length === 0 && search.length === 0" class="px-4 py-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl">
                                                        Escribe algo para buscar en SIGMA...
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" @click="removeRule(index)" class="p-2 text-rose-500 hover:bg-rose-50 hover:text-rose-700 border border-transparent hover:border-rose-200 rounded-lg transition-all" title="Eliminar filtro">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between pt-2 gap-4">
                            <button type="button" @click="addRule()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold uppercase tracking-wide transition-all border border-slate-200 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                + Añadir Regla
                            </button>

                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <a href="{{ route('schedules.export', request()->all()) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs uppercase tracking-wide transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Exportar R-GT-051
                                </a>
                                
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <a href="{{ route('schedules.index') }}" class="px-5 py-2 bg-white hover:bg-slate-50 text-slate-600 border border-slate-300 font-bold rounded-lg text-xs uppercase tracking-wide transition-all flex items-center justify-center flex-1 sm:flex-none">
                                        Limpiar Todo
                                    </a>
                                    <button type="submit" class="px-6 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-lg text-xs uppercase tracking-wide transition-all shadow-sm flex-1 sm:flex-none">
                                        Aplicar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto flex-1 bg-white relative">
                    <table class="w-full text-left table-auto">
                        <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-20">
                            <tr>
                                <th class="px-5 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Equipo / Serial</th>
                                <th class="px-5 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Ubicación Asignada</th>
                                <th class="px-5 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Técnico Asignado</th>
                                <th class="px-5 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-center">Fecha Límite</th>
                                <th class="px-5 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-center">Estado Agenda</th>
                                <th class="px-5 py-4 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($schedules as $schedule)
                                <tr class="hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-5 py-3.5">
                                        <div class="font-bold text-slate-800 text-sm">{{ $schedule->asset->serial_number }}</div>
                                        <div class="text-[11px] text-blue-600 font-mono font-medium tracking-tight mt-0.5">{{ $schedule->asset->internal_code ?? 'Sin Placa' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-block px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-700 rounded text-[11px] font-bold uppercase tracking-wide">
                                            {{ $schedule->asset->room->nomenclatura ?? 'No asignado' }}
                                        </span>
                                        <div class="text-[10px] text-slate-500 mt-1 uppercase font-medium">
                                            {{ $schedule->asset->room->building->name ?? 'Edificio General' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="text-sm font-bold text-slate-700">{{ $schedule->technician->name ?? 'Sin asignar' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="text-sm font-medium text-slate-600">{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        @php
                                            $statusClasses = match($schedule->status) {
                                                'PENDIENTE' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'REALIZADO' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'VENCIDO'   => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default     => 'bg-slate-50 text-slate-700 border-slate-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border {{ $statusClasses }}">
                                            {{ $schedule->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($schedule->status === 'PENDIENTE')
                                                <a href="{{ route('schedules.edit', $schedule) }}" 
                                                   class="inline-flex items-center gap-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold py-1.5 px-3 rounded-lg text-xs transition-colors border border-amber-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    Corregir
                                                </a>
                                                <a href="{{ route('maintenances.create', ['asset_id' => $schedule->asset_id, 'schedule_id' => $schedule->id]) }}" 
                                                   class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white font-bold py-1.5 px-3 rounded-lg text-xs transition-colors border border-blue-200 hover:border-blue-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                                    Ejecutar
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-500 font-bold rounded-lg text-xs border border-slate-200 select-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Completado
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-slate-400 bg-white">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span class="text-sm font-medium">No se encontraron agendas con las reglas de filtrado seleccionadas.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0 z-30">
                    <div class="flex items-center gap-3">
                        <label for="per_page_select" class="text-xs font-bold text-slate-500 uppercase tracking-wide">Mostrar:</label>
                        <select id="per_page_select" onchange="document.querySelector('input[name=per_page]').value = this.value; document.getElementById('filterForm').submit();" 
                                class="border-slate-300 rounded-lg py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 bg-white shadow-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all cursor-pointer">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 filas</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 filas</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 filas</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 filas</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 filas</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto font-medium text-sm text-slate-600">
                        {{ $schedules->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ajaxSelect', () => ({
                open: false,
                search: '',
                results: [],
                loading: false,
                
                init() {
                    this.search = this.rule.text || (this.rule.value ? `[ID: ${this.rule.value}]` : '');
                    
                    this.$watch('rule.field', () => {
                        this.search = '';
                        this.rule.value = '';
                        this.rule.text = '';
                        this.results = [];
                    });
                },

                get endpoint() {
                    const map = {
                        'sede': '/api/filters/api/sigma-filters/sedes',
                        'building': '/api/filters/api/sigma-filters/buildings',
                        'technician': '/api/filters/api/sigma-filters/technicians',
                        'room': '/api/filters/api/sigma-filters/rooms',
                        'dependency': '/api/filters/api/sigma-filters/dependencies'
                    };
                    return map[this.rule.field] || null;
                },

                get placeholder() {
                    const map = {
                        'sede': 'Buscar sede...',
                        'building': 'Buscar bloque...',
                        'technician': 'Buscar técnico...',
                        'room': 'Buscar salón...',
                        'dependency': 'Buscar dependencia...'
                    };
                    return map[this.rule.field] || 'Buscar...';
                },

                async fetchData() {
                    if (!this.endpoint) return;
                    this.loading = true;
                    try {
                        const res = await fetch(`${this.endpoint}?search=${this.search}`);
                        const rawData = await res.json();
                        
                        const itemsArray = Array.isArray(rawData) ? rawData : (rawData.data || rawData.items || rawData.results || []);
                        
                        this.results = itemsArray.map(item => {
                            let foundLabel = item.name || item.nomenclatura || item.full_name || item.text || item.title || item.label;
                            if (!foundLabel) {
                                const possibleTexts = Object.values(item).filter(val => typeof val === 'string' && isNaN(val));
                                foundLabel = possibleTexts.length > 0 ? possibleTexts[0] : `ID: ${item.id}`;
                            }
                            return { id: item.id, label: foundLabel };
                        });
                    } catch (e) {
                        console.error("Error consultando la API de filtros SIGMA:", e);
                        this.results = [];
                    }
                    this.loading = false;
                },

                selectItem(item) {
                    this.rule.value = item.id;
                    this.rule.text = item.label; 
                    this.search = item.label;
                    this.open = false;
                }
            }));
        });
    </script>
</x-app-layout>