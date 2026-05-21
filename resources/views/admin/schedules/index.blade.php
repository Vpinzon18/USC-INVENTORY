<x-app-layout>            
    <div class="py-4 px-2 sm:px-4 max-w-full mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[calc(100vh-130px)] min-h-[500px]">
            
            <div class="p-4 border-b border-gray-100 bg-white shrink-0 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-xl text-gray-800 tracking-tight uppercase">Cronograma Semestral</h2>
                        <p class="text-xs text-gray-500">Planificación y control de mantenimientos preventivos obligatorios.</p>
                    </div>
                    <a href="{{ route('schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2 px-4 rounded-xl text-xs transition-all shadow-md shrink-0 text-center uppercase tracking-wide">
                        + Programar Equipo
                    </a>
                </div>

                <div class="pt-3 border-t border-gray-100"
     x-data="{ 
        rules: {{ json_encode($currentRules ?? [['field' => 'asset', 'operator' => 'contains', 'value' => '']]) }},
        addRule() {
            this.rules.push({ field: 'asset', operator: 'contains', value: '' });
        },
        removeRule(index) {
            this.rules.splice(index, 1);
            if(this.rules.length === 0) this.addRule();
        }
     }">
                    
                    <form action="{{ route('schedules.index') }}" method="GET" class="space-y-3">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">

                        <div class="space-y-2">
                            <template x-for="(rule, index) in rules" :key="index">
                                <div class="flex flex-wrap items-center gap-2 bg-gray-50/50 p-2 rounded-xl border border-gray-100 shadow-sm transition-all">
                                    
                                    <div class="px-1 text-[10px] font-black text-gray-400 uppercase tracking-widest min-w-[24px] text-center select-none">
                                        <template x-if="index === 0"><span>---</span></template>
                                        <template x-if="index > 0"><span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">Y</span></template>
                                    </div>

                                    <select :name="'rules['+index+'][field]'" x-model="rule.field" 
                                            @change="rule.value = ''; rule.operator = (rule.field === 'sede' || rule.field === 'building' || rule.field === 'status' || rule.field === 'technician') ? 'equals' : 'contains'"
                                            class="border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all cursor-pointer">
                                        <option value="asset">Características - Equipo / Placa / Aula</option>
                                        <option value="sede">Ubicación - Sede Universitaria</option>
                                        <option value="building">Ubicación - Bloque / Edificio</option>
                                        <option value="technician">Características - Técnico Asignado</option>
                                        <option value="status">Características - Estado de Agenda</option>
                                    </select>

                                    <select :name="'rules['+index+'][operator]'" x-model="rule.operator"
                                            class="border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all cursor-pointer">
                                        <option value="contains" x-show="rule.field === 'asset'">contiene</option>
                                        <option value="equals">es exactamente igual a</option>
                                    </select>

                                    <div class="flex-1 min-w-[200px]">
                                        <template x-if="rule.field === 'sede'">
                                            <select :name="'rules['+index+'][value]'" x-model="rule.value" required
                                                    class="w-full border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                                                <option value="">[ Seleccione una Sede Universitaria ]</option> 
                                                @foreach($sedes as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </template>

                                        <template x-if="rule.field === 'building'">
                                            <select :name="'rules['+index+'][value]'" x-model="rule.value" required
                                                    class="w-full border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                                                <option value="">[ Seleccione un Bloque / Edificio ]</option>
                                                @foreach($buildings as $b)
                                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                @endforeach
                                            </select>
                                        </template>

                                        <template x-if="rule.field === 'status'">
                                            <select :name="'rules['+index+'][value]'" x-model="rule.value" required
                                                    class="w-full border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                                                <option value="">[ Seleccione un estado ]</option>
                                                <option value="PENDIENTE">PENDIENTE</option>
                                                <option value="REALIZADO">REALIZADO</option>
                                                <option value="VENCIDO">VENCIDO</option>
                                            </select>
                                        </template>

                                        <template x-if="rule.field === 'technician'">
                                            <select :name="'rules['+index+'][value]'" x-model="rule.value" required
                                                    class="w-full border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                                                <option value="">[ Seleccione un técnico de la lista ]</option>
                                                @foreach($technicians as $tech)
                                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                                @endforeach
                                            </select>
                                        </template>

                                        <template x-if="rule.field === 'asset'">
                                            <input type="text" :name="'rules['+index+'][value]'" x-model="rule.value" required
                                                   placeholder="Escriba el serial, código interno o nomenclatura del aula..."
                                                   class="w-full border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-gray-700 bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all">
                                        </template>
                                    </div>

                                    <button type="button" @click="removeRule(index)" 
                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-all active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <button type="button" @click="addRule()" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[10px] font-black uppercase tracking-wide transition-all active:scale-95 border">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                </svg>
                                + Añadir Regla
                            </button>

                            <div class="flex gap-2">
                                <a href="{{ route('schedules.export', request()->all()) }}" 
           class="inline-flex items-center gap-1 px-4 py-1.5 bg-green-600 hover:bg-green-700 text-white font-black rounded-xl text-[10px] uppercase tracking-wide transition-all shadow-sm active:scale-95 border border-transparent"
           title="Generar PDF Formato R-GT-051">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Exportar R-GT-051
        </a>
                                <a href="{{ route('schedules.index') }}" 
                                   class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-xl text-[10px] uppercase tracking-wide transition-all flex items-center justify-center border">
                                    Limpiar Todo
                                </a>
                                <button type="submit" 
                                        class="px-5 py-1.5 bg-gray-800 hover:bg-gray-900 text-white font-black rounded-xl text-[10px] uppercase tracking-wide transition-all shadow-sm active:scale-95">
                                    Aplicar Reglas Dinámicas
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto overflow-y-auto flex-1 bg-white relative" id="tableContainer">
                <table class="w-full text-sm text-left table-auto">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 bg-gray-50">Equipo / Serial</th>
                            <th class="px-4 py-3 bg-gray-50">Ubicación Asignada</th>
                            <th class="px-4 py-3 bg-gray-50">Técnico Asignado</th>
                            <th class="px-4 py-3 bg-gray-50 text-center">Fecha Límite</th>
                            <th class="px-4 py-3 bg-gray-50 text-center">Estado Agenda</th>
                            <th class="px-4 py-3 bg-gray-50 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($schedules as $schedule)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 py-2.5">
                                    <div class="font-bold text-gray-900 uppercase text-xs">{{ $schedule->asset->serial_number }}</div>
                                    <div class="text-[10px] text-blue-600 font-mono uppercase tracking-tight">{{ $schedule->asset->internal_code ?? 'Sin Placa' }}</div>
                                </td>
                                <td class="px-4 py-2.5">
                                    <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold uppercase">
                                        {{ $schedule->asset->room->nomenclatura ?? 'No asignado' }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-0.5 italic">
                                        {{ $schedule->asset->room->building->name ?? 'Edificio General' }}
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-xs font-bold text-gray-700 uppercase">
                                    {{ $schedule->technician->name ?? 'Sin asignar' }}
                                </td>
                                <td class="px-4 py-2.5 text-center text-xs font-medium text-gray-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    @php
                                        $statusClasses = match($schedule->status) {
                                            'PENDIENTE' => 'bg-amber-100 text-amber-700',
                                            'REALIZADO' => 'bg-green-100 text-green-700',
                                            'VENCIDO'   => 'bg-red-100 text-red-700',
                                            default     => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses }}">
                                        {{ $schedule->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($schedule->status === 'PENDIENTE')
                                            <a href="{{ route('schedules.edit', $schedule) }}" 
                                               class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white font-black py-2 px-3 rounded-xl text-[10px] transition-all shadow-sm hover:shadow-amber-100 active:scale-95 uppercase tracking-wide whitespace-nowrap"
                                               title="Corregir datos de programación">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Corregir
                                            </a>
                                           <a href="{{ route('maintenances.create', [
    'asset_id' => $schedule->asset_id, 
    'schedule_id' => $schedule->id
]) }}" 
   class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-2 px-3 rounded-xl text-[10px] transition-all shadow-sm hover:shadow-blue-100 active:scale-95 uppercase tracking-wide whitespace-nowrap"
   title="Iniciar intervención física">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
    </svg>
    Ejecutar Mantenimiento
</a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-2 bg-green-50 text-green-700 font-black rounded-xl text-[10px] uppercase tracking-wider border border-green-200 shadow-sm select-none whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Trabajo Completado
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-400 italic bg-white">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        No se encontraron agendas semestrales con las reglas de filtrado seleccionadas.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0 z-30">
                <div class="flex items-center gap-2">
                    <form action="{{ route('schedules.index') }}" method="GET" class="flex items-center gap-2">
                        @foreach($currentRules as $idx => $rl)
                            <input type="hidden" name="rules[{{ $idx }}][field]" value="{{ $rl['field'] }}">
                            <input type="hidden" name="rules[{{ $idx }}][operator]" value="{{ $rl['operator'] }}">
                            <input type="hidden" name="rules[{{ $idx }}][value]" value="{{ $rl['value'] }}">
                        @endforeach
                        
                        <label for="per_page" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mostrar:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" 
                                class="border-gray-200 rounded-xl py-1.5 pl-3 pr-8 text-xs font-bold text-gray-700 bg-white shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all cursor-pointer">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 filas</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 filas</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 filas</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 filas</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 filas</option>
                        </select>
                    </form>
                </div>
                <div class="w-full sm:w-auto font-medium text-xs text-gray-600">
                    {{ $schedules->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>