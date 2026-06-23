<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1200px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>

                <div class="flex items-center gap-5 pl-2">
                    <a href="{{ route('schedules.index') }}" class="text-slate-400 hover:text-emerald-600 transition-colors bg-slate-50 p-2 rounded-lg border border-slate-100 hidden sm:block" title="Volver al Cronograma">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 leading-tight">Registro de Intervención Técnica</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Ejecuta y documenta el mantenimiento para actualizar la hoja de vida del equipo.</p>
                    </div>
                </div>

                @if($assets->count() === 1)
                <div class="shrink-0 flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-[11px] font-black text-emerald-700 uppercase tracking-widest">Asignación Directa</span>
                </div>
                @endif
            </div>

            <form action="{{ route('maintenances.store') }}" method="POST" id="maintenanceForm"
                data-assets="{{ json_encode($assets->map(fn($a) => ['id' => $a->id, 'serial' => $a->serial_number, 'code' => $a->internal_code, 'guaya' => $a->security_guaya])) }}"
                x-data="maintenanceEngine()">
                @csrf
                <input type="hidden" name="maintenance_schedule_id" value="{{ request('schedule_id') }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 space-y-6">

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="font-bold text-slate-800">Detalles de la Intervención</h3>
                            </div>

                            <div class="p-6 space-y-8">

                                <div class="relative z-[60]">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">1. Equipo Intervenido <span class="text-rose-500">*</span></label>
                                    <input type="hidden" name="asset_id" x-model="selectedId" required>

                                    @if($assets->count() === 1)
                                    <div class="inline-flex items-center gap-3 bg-emerald-50 border border-emerald-200 p-3 rounded-xl shadow-sm w-full">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-emerald-800">SN: {{ $assets->first()->serial_number }}</p>
                                            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">{{ $assets->first()->internal_code ? 'Placa: ' . $assets->first()->internal_code : 'Sin Placa Interna' }}</p>
                                        </div>
                                    </div>
                                    @else
                                    <div class="relative">
                                        <input type="text" x-model="search" placeholder="Buscar equipo por serial o placa..."
                                            class="w-full border-slate-300 rounded-lg py-3 pl-4 pr-12 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 shadow-sm transition-all">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="mt-3 max-h-[250px] overflow-y-auto border border-slate-200 rounded-xl bg-slate-50/50 p-2 space-y-1 divide-y divide-slate-100">
                                        <template x-for="asset in filteredAssets" :key="asset.id">
                                            <label class="flex items-center p-3 bg-white border border-transparent rounded-lg cursor-pointer hover:border-emerald-200 hover:bg-emerald-50 transition-all"
                                                :class="{'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50': selectedId == asset.id}">
                                                <input type="radio" name="asset_id_radio" :value="asset.id" @change="selectAsset(asset)" class="hidden">
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-slate-800" x-text="'SN: ' + asset.serial"></p>
                                                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider" x-text="asset.code ? 'Placa: ' + asset.code : 'Sin Placa'"></p>
                                                </div>
                                                <div x-show="selectedId == asset.id" class="text-emerald-500">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </label>
                                        </template>
                                        <div x-show="filteredAssets.length === 0" class="p-4 text-center text-sm font-bold text-slate-400">
                                            No se encontraron equipos con esa búsqueda.
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">2. Fecha Realizada <span class="text-rose-500">*</span></label>
                                        <input type="date" name="performed_at" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                                            class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-2 focus:ring-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-medium cursor-pointer py-2.5">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">3. Tipo de Intervención <span class="text-rose-500">*</span></label>
                                        <select name="type" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-2 focus:ring-emerald-500 text-sm transition-all bg-slate-50 focus:bg-white text-slate-700 font-bold cursor-pointer py-2.5">
                                            <option value="Preventivo">Mantenimiento Preventivo</option>
                                            <option value="Correctivo">Mantenimiento Correctivo</option>
                                            <option value="Mejora">Actualización / Mejora</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">4. Seguridad Física del Equipo</label>
                                    </div>

                                    <label class="inline-flex items-center gap-3 cursor-pointer group bg-slate-50 hover:bg-slate-100 border border-slate-200 px-4 py-3 rounded-xl transition-colors">
                                        <input type="checkbox" x-model="cambioGuaya" class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900">Se instaló o reemplazó la guaya de seguridad durante la visita</span>
                                    </label>

                                    <div x-show="cambioGuaya" x-collapse>
                                        <div class="bg-emerald-50/50 p-5 rounded-xl border border-emerald-100 grid grid-cols-1 md:grid-cols-2 gap-6 relative overflow-hidden mt-2">
                                            <div class="absolute right-0 top-0 opacity-10 text-emerald-600">
                                                <svg class="w-24 h-24 -mt-4 -mr-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                                                </svg>
                                            </div>

                                            <div class="relative z-10">
                                                <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-1">Guaya Registrada Actual:</p>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    <span x-text="currentGuaya" class="text-sm font-mono font-bold text-slate-700"></span>
                                                </div>
                                            </div>

                                            <div class="relative z-10">
                                                <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-1">Nuevo Serial de Guaya:</p>
                                                <input type="text" name="security_guaya" placeholder="Ej: GUA-98765..."
                                                    class="w-full border-emerald-200 rounded-lg py-2 px-3 text-sm font-bold text-slate-800 bg-white focus:ring-2 focus:ring-emerald-500 transition-all uppercase"
                                                    :required="cambioGuaya" x-model="newGuaya">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">5. Descripción Detallada del Servicio <span class="text-rose-500">*</span></label>
                                    <textarea name="description" rows="5" required
                                        placeholder="Describa los procedimientos realizados, piezas cambiadas o el estado final del equipo..."
                                        class="w-full border-slate-300 rounded-xl p-4 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 shadow-sm transition-all resize-none"></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <a href="{{ route('schedules.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 hover:text-slate-800 rounded-lg shadow-sm transition-all text-center">
                                Cancelar
                            </a>
                            <button type="submit" :disabled="selectedId === ''"
                                class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 disabled:bg-slate-300 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar y Completar
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6 space-y-5">
                            <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Registro Técnico
                            </h3>
                            <div class="space-y-4 text-xs text-slate-600 leading-relaxed">
                                <div class="bg-emerald-50/60 border border-emerald-200 p-3 rounded-lg">
                                    <p class="text-emerald-800 font-medium">Al guardar este formulario, el equipo actualizará automáticamente su fecha del <strong>Último Mantenimiento</strong> en su Hoja de Vida.</p>
                                </div>
                                @if(request('schedule_id'))
                                <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg flex gap-3">
                                    <span class="text-blue-500 font-bold">✓</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Cierre de Agenda:</strong> Esta acción marcará la agenda programada como <strong>REALIZADA</strong> y la sacará de sus tareas pendientes.</p>
                                </div>
                                @endif
                                <div class="bg-slate-50 border border-slate-200 p-3 rounded-lg flex gap-3">
                                    <span class="text-slate-400 font-bold">!</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Inventario Físico:</strong> Si instaló una guaya nueva, no olvide activar la casilla correspondiente para actualizar el registro patrimonial.</p>
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
            Alpine.data('maintenanceEngine', () => ({
                
                search: '',
                selectedId: '{{ $assets->count() === 1 ? $assets->first()->id : "" }}',
                currentGuaya: '{{ $assets->count() === 1 ? ($assets->first()->security_guaya ?? "Sin Guaya Registrada") : "Seleccione un equipo..." }}',
                cambioGuaya: false,
                newGuaya: '',
                
                // Inicializamos vacío
                assetsList: [],

                // Init se ejecuta automáticamente al cargar el componente
                init() {
                    // Extraemos los datos del HTML de forma segura. ¡Adiós errores de VS Code!
                    const rawData = document.getElementById('maintenanceForm').dataset.assets;
                    if(rawData) {
                        this.assetsList = JSON.parse(rawData);
                    }
                },

                get filteredAssets() {
                    if (this.search === '') return this.assetsList;
                    const q = this.search.toLowerCase();
                    return this.assetsList.filter(a => 
                        (a.serial && a.serial.toLowerCase().includes(q)) || 
                        (a.code && a.code.toLowerCase().includes(q))
                    );
                },

                selectAsset(asset) {
                    this.selectedId = asset.id;
                    this.currentGuaya = asset.guaya || 'Sin Guaya Registrada';
                    
                    this.newGuaya = '';
                    this.cambioGuaya = false;
                }
            }));
        });
    </script>
</x-app-layout>