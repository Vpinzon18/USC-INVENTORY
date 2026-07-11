<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1300px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-600"></div>

                <div class="flex items-center gap-5 pl-2 w-full md:w-auto">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Módulo de Movimientos de Activos</h2>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">Formato Institucional R-AF001 - Unidad de Activos Fijos.</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg> {{ session('error') }}</div>
            @endif

            <form action="{{ route('movements.mass.store') }}" method="POST" id="movementForm" x-data="movementEngine()" class="space-y-6">
                @csrf

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Tipo de Movimiento <span class="text-rose-500">*</span></label>
                        <select name="movement_type" required class="w-full rounded-lg border-slate-300 bg-slate-50 focus:bg-white text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-indigo-500 py-2.5 transition-colors cursor-pointer">
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="TRASLADO ASIGNACION">Traslado en calidad de Asignación</option>
                            <option value="ASIGNACION INICIAL">Asignación Inicial</option>
                            <option value="PRESTAMO FUERA USC">Préstamo Fuera de la USC</option>
                            <option value="PRESTAMO DENTRO USC">Préstamo Dentro de la USC</option>
                            <option value="REPARACION DENTRO USC">Reparación Dentro de la USC</option>
                            <option value="REPARACION FUERA USC">Reparación Fuera de la USC</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Sede Origen/Destino <span class="text-rose-500">*</span></label>
                        <select name="headquarters" required class="w-full rounded-lg border-slate-300 bg-slate-50 focus:bg-white text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-indigo-500 py-2.5 transition-colors cursor-pointer">
                            <option value="PAMPALINDA">Pampalinda (Cali)</option>
                            <option value="CENTRO">Centro (Cali)</option>
                            <option value="PALMIRA">Palmira</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-start md:justify-center pb-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="generate_pdf" value="1" checked class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition-colors">
                            <span class="text-sm font-bold text-slate-600 group-hover:text-slate-800 transition-colors">Generar Acta PDF Automática</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <div class="lg:col-span-5 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[550px]">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">1. Selección de Activos</h3>
                            </div>
                            <span class="text-[10px] font-black text-indigo-700 bg-indigo-100 px-2 py-1 rounded-md uppercase tracking-widest"><span x-text="form.assets.length"></span> en bandeja</span>
                        </div>

                        <div class="p-4 flex-1 flex flex-col min-h-0">

                            <div class="relative mb-3 z-50" @click.away="dropdowns.assets.open = false">
                                <input type="text" x-model="dropdowns.assets.search" x-ref="assetSearch"
                                    @focus="dropdowns.assets.open = true; if(dropdowns.assets.search.length > 1) fetch('assets')"
                                    @input.debounce.350ms="dropdowns.assets.open = true; fetch('assets')"
                                    placeholder="Buscar por serial, placa, bloque o salón..." autocomplete="off"
                                    class="w-full border-slate-300 rounded-lg py-2.5 pl-10 pr-10 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">

                                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>

                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg x-show="dropdowns.assets.loading" class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>

                                <div x-show="dropdowns.assets.open" x-transition.opacity.duration.150ms
                                    class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl max-h-56 overflow-y-auto divide-y divide-slate-50">

                                    <div x-show="dropdowns.assets.results.length === 0 && !dropdowns.assets.loading" class="p-4 text-center text-xs font-bold text-slate-400 bg-slate-50 uppercase tracking-wider">Escriba un serial válido...</div>

                                    <template x-for="item in dropdowns.assets.results" :key="item.id">
                                        <div @mousedown.prevent="select('assets', item)"
                                            class="group px-4 py-3 flex items-center justify-between cursor-pointer transition-all border-l-4"
                                            :class="isAssetSelected(item.id) ? 'bg-indigo-50 border-indigo-500' : 'border-transparent hover:bg-slate-100'">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                                    :class="isAssetSelected(item.id) ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:text-indigo-600'">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800" x-text="item.label"></p>
                                                </div>
                                            </div>
                                            <div x-show="isAssetSelected(item.id)" class="text-[10px] font-black uppercase tracking-widest text-indigo-700 bg-indigo-100 px-2 py-1 rounded shadow-sm">✓ Añadido</div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="flex-1 bg-slate-50/80 border border-slate-200 rounded-xl overflow-y-auto custom-scrollbar p-2 space-y-2">
                                <template x-if="form.assets.length === 0">
                                    <div class="flex flex-col items-center justify-center h-full text-center p-4">
                                        <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="text-sm font-bold text-slate-500">Bandeja Vacía</p>
                                        <p class="text-[11px] text-slate-400 mt-1 uppercase tracking-wider">Busque y seleccione los equipos a mover.</p>
                                    </div>
                                </template>

                                <template x-for="asset in form.assets" :key="asset.id">
                                    <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-lg shadow-sm hover:border-indigo-300 transition-all group">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700" x-text="asset.label"></p>
                                            </div>
                                        </div>
                                        <button type="button" @click="removeAsset(asset.id)" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Remover equipo">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <input type="hidden" name="selected_assets[]" :value="asset.id">
                                    </div>
                                </template>
                            </div>
                            @error('selected_assets') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="lg:col-span-7 flex flex-col space-y-6">

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 relative z-20">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2 rounded-t-xl">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">2. Área de Destino</h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="relative z-40" @click.away="dropdowns.custodians.open = false">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Responsable / Custodio (Quién Recibe) <span class="text-rose-500">*</span></label>
                                    <input type="hidden" name="custodian_id" x-model="form.custodian_id" required>

                                    <div class="relative">
                                        <input type="text" x-model="dropdowns.custodians.search" x-ref="custodianSearch"
                                            @focus="dropdowns.custodians.open = true"
                                            :readonly="form.custodian_id !== ''"
                                            placeholder="Buscar nombre del responsable..." autocomplete="off"
                                            class="w-full border-slate-300 rounded-lg py-3 pl-4 pr-10 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm cursor-text"
                                            :class="{'border-emerald-400 bg-emerald-50 text-emerald-800 font-bold': form.custodian_id !== ''}">

                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg x-show="form.custodian_id === ''" class="w-5 h-5 text-slate-400 pointer-events-none mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <button type="button" x-show="form.custodian_id !== ''" @click="clearCustodian()" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-md transition-colors" title="Cambiar responsable">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-show="dropdowns.custodians.open && form.custodian_id === ''" x-transition.opacity
                                        class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-48 overflow-y-auto divide-y divide-slate-50">
                                        <template x-for="custodian in filteredCustodians" :key="custodian.id">
                                            <div @mousedown.prevent="select('custodians', custodian)" class="px-4 py-3 hover:bg-indigo-50 cursor-pointer transition-colors group">
                                                <div class="text-sm font-bold text-slate-700 group-hover:text-indigo-900 truncate" x-text="custodian.full_name"></div>
                                            </div>
                                        </template>
                                        <div x-show="filteredCustodians.length === 0" class="p-4 text-center text-xs font-bold text-slate-400">Sin resultados</div>
                                    </div>
                                </div>

                                <div class="relative z-30" @click.away="dropdowns.rooms.open = false">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Ubicación / Salón <span class="text-rose-500">*</span></label>
                                    <input type="hidden" name="room_id" x-model="form.room_id" required>

                                    <div class="relative">
                                        <input type="text" x-model="dropdowns.rooms.search" x-ref="roomSearch"
                                            @focus="if(form.custodian_id !== '') { dropdowns.rooms.open = true; fetch('rooms'); }"
                                            @input.debounce.300ms="fetch('rooms')"
                                            :disabled="form.custodian_id === ''"
                                            :readonly="form.room_id !== ''"
                                            placeholder="Buscar ubicación o salón..." autocomplete="off"
                                            class="w-full border-slate-300 rounded-lg py-3 pl-4 pr-10 text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm cursor-text disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed"
                                            :class="{'border-emerald-400 bg-emerald-50 text-emerald-800 font-bold': form.room_id !== ''}">

                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg x-show="form.room_id === '' && !dropdowns.rooms.loading" class="w-5 h-5 text-slate-400 pointer-events-none mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                            <svg x-show="dropdowns.rooms.loading" class="w-5 h-5 text-indigo-500 animate-spin pointer-events-none mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <button type="button" x-show="form.room_id !== ''" @click="clearRoom()" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-md transition-colors" title="Cambiar salón">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-show="dropdowns.rooms.open && form.room_id === ''" x-transition.opacity
                                        class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-48 overflow-y-auto divide-y divide-slate-50">
                                        <template x-for="room in dropdowns.rooms.results" :key="room.id">
                                            <div @mousedown.prevent="select('rooms', room)" class="px-4 py-3 hover:bg-indigo-50 cursor-pointer transition-colors group">
                                                <div class="text-sm font-bold text-slate-700 group-hover:text-indigo-900 truncate" x-text="room.label"></div>
                                            </div>
                                        </template>
                                        <div x-show="dropdowns.rooms.results.length === 0 && !dropdowns.rooms.loading" class="p-4 text-center text-xs font-bold text-slate-400">
                                            <span x-show="dropdowns.rooms.search.length > 0">Sin resultados...</span>
                                            <span x-show="dropdowns.rooms.search.length === 0">Escriba para buscar salones...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex-1 flex flex-col">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">3. Justificación y Notas</h3>
                                {{-- Usamos x-cloak para evitar que el div parpadee al cargar --}}
                                <div x-show="form.assets.length > 5" x-cloak x-transition
                                    class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                    Nota Legal Aplicada
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                {{-- Asegúrate que el textarea tenga un nombre único si se usa en varios lugares --}}
                                <textarea name="observation"
                                    x-model="form.observation"
                                    rows="4"
                                    required
                                    class="w-full flex-1 rounded-xl border-slate-300 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500 p-4 text-slate-700 bg-slate-50 focus:bg-white transition-colors resize-none"
                                    placeholder="Escriba aquí los detalles, justificación o motivos del traslado masivo..."></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="button" @click="validateAndSubmit()" :disabled="form.assets.length === 0 || form.custodian_id === '' || form.room_id === ''"
                        class="w-full md:w-auto px-10 py-3.5 text-white font-extrabold text-sm uppercase tracking-widest rounded-xl shadow-md transition-all flex items-center justify-center gap-2 disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed"
                        :class="form.assets.length === 0 ? 'bg-slate-400' : 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-indigo-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Procesar <span x-show="form.assets.length > 0" x-text="form.assets.length + ' Equipos'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('movementEngine', () => ({

                form: {
                    assets: [],
                    custodian_id: '',
                    room_id: '',
                    observation: ''
                },

                dropdowns: {
                    assets: {
                        open: false,
                        search: '',
                        results: [],
                        loading: false
                    },
                    custodians: {
                        open: false,
                        search: '',
                        results: [],
                        loading: false
                    },
                    rooms: {
                        open: false,
                        search: '',
                        results: [],
                        loading: false
                    }
                },

                abortController: null,
                abortControllerRooms: null,

                custodiansList: JSON.parse(`{!! addslashes(json_encode($custodians)) !!}`),

                get filteredCustodians() {
                    if (this.dropdowns.custodians.search === '') return this.custodiansList;
                    const q = this.dropdowns.custodians.search.toLowerCase();
                    return this.custodiansList.filter(c => c.full_name && c.full_name.toLowerCase().includes(q));
                },

                init() {
                    const textoNorma = "Se adjunta relación detallada de equipos (Activo, Modelo, Serial) debido a movimiento masivo.";
                    this.$watch('form.assets', (assets) => {
                        let currentObs = this.form.observation.trim();
                        if (assets.length > 5) {
                            if (!currentObs.includes(textoNorma)) {
                                this.form.observation = currentObs ? currentObs + "\n\n" + textoNorma : textoNorma;
                            }
                        } else {
                            if (currentObs === textoNorma) {
                                this.form.observation = "";
                            }
                        }
                    });
                },

                async fetch(type) {
                    const dd = this.dropdowns[type];

                    // --- LÓGICA AJAX PARA EQUIPOS ---
                    if (type === 'assets') {
                        if (dd.search.length < 2) return;
                        dd.loading = true;

                        if (this.abortController) this.abortController.abort();
                        this.abortController = new AbortController();

                        try {
                            const url = `/api/filters/api/sigma-filters/assets-global?search=${encodeURIComponent(dd.search)}`;
                            const res = await fetch(url, {
                                signal: this.abortController.signal
                            });
                            const rawData = await res.json();

                            const items = Array.isArray(rawData) ? rawData : (rawData.data || rawData.items || []);
                            dd.results = items.map(item => {
                                const serial = item.serial_number || item.serial || item.name || '';
                                const placa = item.internal_code || item.code || '';
                                return {
                                    id: item.id,
                                    label: item.label || `SN: ${serial} ${placa ? '| Placa: ' + placa : ''}`,
                                    serial_number: serial,
                                    internal_code: placa
                                };
                            });
                            dd.loading = false;
                        } catch (e) {
                            if (e.name !== 'AbortError') {
                                dd.results = [];
                                dd.loading = false;
                            }
                        }
                    }

                    // --- NUEVA LÓGICA AJAX PARA SALONES/OFICINAS ---
                    if (type === 'rooms') {
                        if (this.form.custodian_id === '') return;
                        dd.loading = true;

                        if (this.abortControllerRooms) this.abortControllerRooms.abort();
                        this.abortControllerRooms = new AbortController();

                        try {
                           // Reemplaza la línea de la constante 'url' por esta:
const url = `/api/filters/api/sigma-filters/offices?responsible_id=${this.form.custodian_id}&search=${encodeURIComponent(dd.search)}`;
                            const res = await fetch(url, {
                                signal: this.abortControllerRooms.signal
                            });
                            const rawData = await res.json();

                            dd.results = rawData;
                            dd.loading = false;
                        } catch (e) {
                            if (e.name !== 'AbortError') {
                                dd.results = [];
                                dd.loading = false;
                            }
                        }
                    }
                },

                isAssetSelected(id) {
                    return this.form.assets.some(a => a.id == id);
                },

                select(type, item) {
                    const dd = this.dropdowns[type];

                    if (type === 'assets') {
                        if (!this.isAssetSelected(item.id)) {
                            this.form.assets.push({
                                id: item.id,
                                serial_number: item.serial_number,
                                internal_code: item.internal_code,
                                label: item.label
                            });
                        } else {
                            // Efecto Toggle: si ya estaba, lo quitamos
                            this.removeAsset(item.id);
                        }

                        // Mantenemos el foco en el input para seguir seleccionando sin que se cierre
                        setTimeout(() => {
                            this.$refs.assetSearch.focus();
                        }, 50);
                    }

                    if (type === 'custodians') {
                        this.form.custodian_id = item.id;
                        dd.search = item.full_name;
                        dd.open = false;
                        this.clearRoom();
                    }

                    if (type === 'rooms') {
                        this.form.room_id = item.id;
                        dd.search = item.label;
                        dd.open = false;
                    }
                },

                removeAsset(id) {
                    this.form.assets = this.form.assets.filter(a => a.id != id);
                },

                clearCustodian() {
                    this.form.custodian_id = '';
                    this.dropdowns.custodians.search = '';
                    this.clearRoom();
                    setTimeout(() => {
                        this.$refs.custodianSearch.focus();
                    }, 50);
                },

                clearRoom() {
                    this.form.room_id = '';
                    this.dropdowns.rooms.search = '';
                    this.dropdowns.rooms.results = [];
                },

                async validateAndSubmit() {
                    const nativeForm = document.getElementById('movementForm');
                    if (!nativeForm.checkValidity()) {
                        nativeForm.reportValidity();
                        return;
                    }

                    const formData = new FormData(nativeForm);

                    try {
                        const response = await fetch("{{ route('movements.validate-conflict') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.has_conflict) {
                            const result = await Swal.fire({
                                title: 'Equipos ya asignados',
                                html: `Los siguientes activos ya están con el responsable destino:<br>
                                       <b style="color:#e11d48">${data.conflicts.join(', ')}</b><br><br>
                                       ¿Deseas continuar y <b>omitir automáticamente</b> estos equipos del movimiento?`,
                                icon: 'warning',
                                iconColor: '#e11d48',
                                showCancelButton: true,
                                confirmButtonColor: '#4f46e5',
                                confirmButtonText: 'Sí, continuar omitiendo',
                                cancelButtonText: 'Cancelar y revisar',
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });

                            if (result.isConfirmed) {
                                nativeForm.submit();
                            }
                        } else {
                            nativeForm.submit();
                        }
                    } catch (error) {
                        nativeForm.submit();
                    }
                }
            }));
        });
    </script>
</x-app-layout>