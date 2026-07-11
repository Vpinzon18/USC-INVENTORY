<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1300px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>

                <div class="flex items-center gap-5 pl-2 w-full md:w-auto">
                    <a href="{{ route('movements.index') }}" class="text-slate-400 hover:text-amber-600 transition-colors bg-slate-50 p-2 rounded-lg border border-slate-100 hidden sm:block" title="Volver al Historial">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Modificar Composición del Acta</h2>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">
                            Añada o remueva equipos del acta: <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $baseMovement->acta_number ?? 'S/N' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg> {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('movements.update', $baseMovement->id) }}" method="POST" id="movementForm" x-data="movementEngine()" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Tipo de Movimiento <span class="text-rose-500">*</span></label>
                        <select name="movement_type" x-model="form.movement_type" required class="w-full rounded-lg border-slate-300 bg-slate-50 focus:bg-white text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-indigo-500 py-2.5 transition-colors cursor-pointer">
                            <option value="Asignación">Asignación</option>
                            <option value="Traslado">Traslado</option>
                            <option value="Baja">Baja</option>
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
                        <select name="headquarters" x-model="form.headquarters" required class="w-full rounded-lg border-slate-300 bg-slate-50 focus:bg-white text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-indigo-500 py-2.5 transition-colors cursor-pointer">
                            <option value="PAMPALINDA">Pampalinda (Cali)</option>
                            <option value="CENTRO">Centro (Cali)</option>
                            <option value="PALMIRA">Palmira</option>
                        </select>
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
                            <span class="text-[10px] font-black text-indigo-700 bg-indigo-100 px-2 py-1 rounded-md uppercase tracking-widest"><span x-text="form.assets.length"></span> en acta</span>
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
                                        <p class="text-sm font-bold text-slate-500">Acta Vacía</p>
                                        <p class="text-[11px] text-slate-400 mt-1 uppercase tracking-wider">Busque y seleccione los equipos a incluir.</p>
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
                                        <input type="hidden" name="asset_ids[]" :value="asset.id">
                                    </div>
                                </template>
                            </div>
                            @error('asset_ids') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
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
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <<textarea name="observations" x-model="form.observation" rows="4" required
                                    class="w-full flex-1 rounded-xl border-slate-300 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500 p-4 text-slate-700 bg-slate-50 focus:bg-white transition-colors resize-none"
                                    placeholder="Escriba aquí los detalles, justificación o motivos de esta modificación...">
                                    {{ old('observations', $baseMovement->observations) }}
                                    </textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-end pt-4 gap-4">
                    <a href="{{ route('movements.index') }}" class="px-6 py-3.5 text-slate-600 font-extrabold text-sm uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-colors flex items-center justify-center" style="text-decoration: none;">
                        Cancelar
                    </a>
                    <button type="button" @click="validateAndSubmit()" :disabled="form.assets.length === 0 || form.custodian_id === '' || form.room_id === ''"
                        class="w-full md:w-auto px-10 py-3.5 text-white font-extrabold text-sm uppercase tracking-widest rounded-xl shadow-md transition-all flex items-center justify-center gap-2 disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed"
                        :class="form.assets.length === 0 ? 'bg-slate-400' : 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-indigo-200'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
    // 1. Preparamos los datos en PHP puro ANTES del script
    $mappedAssets = $batchMovements->map(function($m) {
    $serial = $m->asset->serial_number ?? '';
    $code = $m->asset->internal_code ?? '';
    return [
    'id' => $m->asset->id,
    'serial_number' => $serial,
    'internal_code' => $code,
    'label' => "SN: " . $serial . ($code ? " | Placa: " . $code : "")
    ];
    })->values();

    $initialRoomName = $baseMovement->room->nomenclatura ?? ($baseMovement->room->name ?? '');

    // 2. Empaquetamos todo en un solo arreglo de datos
    $payload = [
    'assets' => $mappedAssets,
    'movement_type' => $baseMovement->movement_type,
    'headquarters' => $baseMovement->headquarters ?? 'PAMPALINDA',
    'custodian_id' => $baseMovement->custodian_id,
    'room_id' => $baseMovement->room_id ?? '',
    'observation' => $baseMovement->observations,
    'initialRoomName' => $initialRoomName,
    'custodians' => $custodians
    ];
    @endphp

    <div id="server-data" class="hidden" data-payload="{{ json_encode($payload) }}"></div>

    <script>
        document.addEventListener('alpine:init', () => {
            // 4. Leemos los datos desde el HTML de forma nativa con JS puro
            const serverData = JSON.parse(document.getElementById('server-data').dataset.payload);

            Alpine.data('movementEngine', () => ({

                form: {
                    assets: serverData.assets,
                    movement_type: serverData.movement_type,
                    headquarters: serverData.headquarters,
                    custodian_id: serverData.custodian_id,
                    room_id: serverData.room_id,
                    observation: serverData.observation
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
                custodiansList: serverData.custodians,

                get filteredCustodians() {
                    if (this.dropdowns.custodians.search === '') return this.custodiansList;
                    const q = this.dropdowns.custodians.search.toLowerCase();
                    return this.custodiansList.filter(c => c.full_name && c.full_name.toLowerCase().includes(q));
                },

                init() {
                    // Asignamos el nombre del custodio
                    const initialCustodian = this.custodiansList.find(c => c.id == this.form.custodian_id);
                    if (initialCustodian) {
                        this.dropdowns.custodians.search = initialCustodian.full_name;
                    }

                    // Asignamos el nombre del salón
                    if (serverData.initialRoomName) {
                        this.dropdowns.rooms.search = serverData.initialRoomName;
                    }
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

                    // --- LÓGICA AJAX PARA SALONES/OFICINAS ---
                    if (type === 'rooms') {
                        if (this.form.custodian_id === '') return;
                        dd.loading = true;

                        if (this.abortControllerRooms) this.abortControllerRooms.abort();
                        this.abortControllerRooms = new AbortController();

                        try {
                            const url = `/api/filters/api/filters/api/sigma-filters/offices?responsible_id=${this.form.custodian_id}&search=${encodeURIComponent(dd.search)}`;
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
                            this.removeAsset(item.id);
                        }

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

                validateAndSubmit() {
                    const nativeForm = document.getElementById('movementForm');
                    if (!nativeForm.checkValidity()) {
                        nativeForm.reportValidity();
                        return;
                    }
                    nativeForm.submit();
                }
            }));
        });
    </script>
</x-app-layout>