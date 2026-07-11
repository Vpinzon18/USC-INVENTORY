<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen font-sans" x-data="maintenanceEditor()" x-cloak>
        <div class="max-w-[1400px] w-[96%] mx-auto space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 pl-2 w-full md:w-auto">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-50 to-slate-100 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Editar Intervención</h2>
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-1 rounded-md font-black uppercase tracking-widest border border-slate-200">
                                OP-{{ str_pad($maintenance->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500">
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg> {{ $maintenance->asset->serial_number ?? 'S/N' }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg> {{ $maintenance->asset->model ?? 'Modelo N/A' }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-blue-600 font-bold uppercase tracking-wider text-[10px]">{{ $maintenance->type }}</span>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Estado Operativo</p>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Activo
                        </span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Modificación</p>
                        <span class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($maintenance->updated_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('maintenances.update', $maintenance) }}" method="POST" id="updateForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative">
                @csrf
                @method('PUT')

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
                                <input type="date" name="performed_at" value="{{ old('performed_at', \Carbon\Carbon::parse($maintenance->performed_at)->format('Y-m-d')) }}"
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
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Técnico Responsable</label>
                                <div class="w-full border border-slate-200 bg-slate-50 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-500 flex items-center gap-2 cursor-not-allowed">
                                    <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-600">{{ substr($maintenance->technician->name ?? 'T', 0, 1) }}</div>
                                    {{ $maintenance->technician->name ?? 'Asignación Automática' }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Estado del Registro</label>
                                <div class="w-full border border-slate-200 bg-slate-50 rounded-xl py-2.5 px-4 flex items-center gap-2 cursor-not-allowed">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    <span class="text-sm font-bold text-slate-500">Consolidado</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 group hover:border-blue-200 transition-colors">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between rounded-t-2xl">
                            <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Activo CMDB Vinculado
                            </h3>
                            <span class="text-[10px] font-black text-amber-500 bg-amber-50 border border-amber-200 px-2 py-1 rounded-md uppercase">Modificable</span>
                        </div>

                        <div class="p-6">
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 tracking-widest flex items-center justify-between">
                                Reasignar Equipo (Opcional) <span class="text-rose-500">*</span>
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
                                        <input type="text" x-model="assetSearch" placeholder="Buscar por placa, serial o modelo..." class="w-full pl-3 pr-3 py-2 text-xs font-medium border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm" @keydown.escape="assetOpen = false">
                                    </div>
                                    <ul class="max-h-56 overflow-y-auto py-1">
                                        <template x-for="item in filteredAssets" :key="item.id">
                                            <li @click="selectAsset(item.id, getAssetLabel(item))" class="px-4 py-3 text-xs cursor-pointer border-b border-slate-50 last:border-0 hover:bg-blue-50 transition-colors" :class="selectedAssetId == item.id ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700'">
                                                <div class="font-bold text-slate-800" x-text="'Placa: ' + (item.internal_code || 'S/P') + ' | Serial: ' + (item.serial_number || 'S/N')"></div>
                                                <div class="text-[10px] text-slate-500 mt-0.5" x-text="'Equipo: ' + (item.model || 'N/A')"></div>
                                            </li>
                                        </template>
                                        
                                        <li x-show="assetSearch.length < 2 && assets.length === 0" class="px-4 py-4 text-xs text-slate-500 text-center font-medium">Escriba al menos 2 caracteres para buscar...</li>
                                        <li x-show="isSearchingAssets" class="px-4 py-4 text-xs text-blue-500 text-center font-bold flex justify-center items-center gap-2">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Buscando en la base de datos...
                                        </li>
                                        <li x-show="assetSearch.length >= 2 && filteredAssets.length === 0 && !isSearchingAssets" class="px-4 py-4 text-xs text-rose-500 text-center font-bold">No se encontraron equipos</li>
                                    </ul>
                                </div>
                            </div>
                            @error('asset_id') <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p> @enderror

                            <div class="mt-5 p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3">
                                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-xs text-amber-700 font-medium leading-relaxed">
                                    <strong>Atención:</strong> Si modifica el equipo vinculado, esta bitácora se trasladará permanentemente al historial del nuevo equipo.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 group hover:border-blue-200 transition-colors">
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
                                <span class="ml-3 text-[10px] font-black uppercase tracking-widest" :class="cambioGuaya ? 'text-blue-600' : 'text-slate-400'" x-text="cambioGuaya ? 'Modificando' : 'Sin Cambios'"></span>
                            </label>
                        </div>

                        <div x-show="cambioGuaya" x-collapse x-cloak>
                            <div class="p-6 bg-blue-50/30 border-t border-blue-50">
                                <div class="flex flex-col md:flex-row gap-6 items-center">
                                    <div class="w-full md:w-1/2">
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Guaya en Sistema</p>
                                        @if($maintenance->asset->security_guaya)
                                        <div class="w-full border border-slate-200 bg-white rounded-xl py-2.5 px-4 flex items-center justify-between shadow-sm">
                                            <span class="text-sm font-mono text-slate-600">{{ $maintenance->asset->security_guaya }}</span>
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        @else
                                        <div class="w-full border border-dashed border-amber-200 bg-amber-50 rounded-xl py-2.5 px-4 flex items-center justify-center shadow-sm">
                                            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Sin Guaya Registrada</span>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="w-full md:w-1/2">
                                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Asignar / Corregir Guaya</p>
                                        <input type="text" name="security_guaya" value="{{ old('security_guaya', $maintenance->security_guaya) }}"
                                            placeholder="Ingrese el nuevo serial..."
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
                                placeholder="Describa los síntomas, el diagnóstico, las piezas reemplazadas y las pruebas de funcionamiento realizadas...">{{ old('description', $maintenance->description) }}</textarea>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Guardar Cambios
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-black tracking-tight relative z-10">{{ $maintenance->asset->model ?? 'Desconocido' }}</h4>
                                <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest mt-1 relative z-10">Perfil CMDB Activo</p>
                            </div>

                            <div class="p-6 divide-y divide-slate-100">
                                <div class="py-3 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Serial N°</span>
                                    <span class="text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $maintenance->asset->serial_number ?? 'N/A' }}</span>
                                </div>
                                <div class="py-3 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Responsable</span>
                                    <span class="text-xs font-bold text-slate-700 text-right">{{ $maintenance->asset->custodian->name ?? 'No asignado' }}</span>
                                </div>
                                <div class="py-3 flex justify-between items-center">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sistema OS</span>
                                    <span class="text-xs font-medium text-slate-600">{{ $maintenance->asset->os ?? 'No Registrado' }}</span>
                                </div>
                                <div class="py-3 flex flex-col gap-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ubicación Física</span>
                                    <span class="text-xs font-medium text-slate-600 leading-tight">
                                        {{ $maintenance->asset->room->building->name ?? 'Edificio N/A' }}<br>
                                        <span class="font-bold text-blue-600">{{ $maintenance->asset->room->nomenclatura ?? 'Sala N/A' }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                                <a href="#" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline flex items-center justify-center gap-1">
                                    Ver Hoja de Vida del Equipo <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h5 class="text-xs font-black text-blue-800 uppercase tracking-wider mb-1">Guía Rápida</h5>
                                    <p class="text-[11px] text-blue-600/80 leading-relaxed font-medium">Asegúrese de detallar cualquier pieza sustituida en la descripción. Los cambios de Guaya se verán reflejados inmediatamente en el inventario general tras guardar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div id="filter-init-data" class="hidden"
        data-cat="{{ old('type', $maintenance->type) }}"
        data-asset="{{ old('asset_id', $maintenance->asset_id) }}"
        data-guaya="{{ $maintenance->security_guaya ? '1' : '0' }}"
        data-asset-label="Placa: {{ $maintenance->asset->internal_code ?? 'S/P' }} | Serial: {{ $maintenance->asset->serial_number ?? 'S/N' }} | Eq: {{ $maintenance->asset->model ?? 'N/A' }}"
        data-categories="{{ \App\Models\Category::orderBy('name')->get()->toJson() }}">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('alpine:init', () => {
        const initData = document.getElementById('filter-init-data').dataset;
        const phpCategories = JSON.parse(initData.categories || '[]');

        Alpine.data('maintenanceEditor', () => ({
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
                    const res = await fetch(`/api/filters/api/sigma-filters/assets?q=${encodeURIComponent(query)}`);
                    const data = await res.json();
                    
                    // Extracción a prueba de balas (incluso si Laravel lo envuelve en 'original')
                    let items = [];
                    if (Array.isArray(data)) items = data;
                    else if (data && Array.isArray(data.data)) items = data.data;
                    else if (data && Array.isArray(data.original)) items = data.original;
                    else if (typeof data === 'object') items = Object.values(data);
                    
                    this.assets = items;
                    
                } catch (error) {
                    console.error("Error buscando equipos:", error);
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
                return `Placa: ${item.internal_code || 'S/P'} | Serial: ${item.serial_number || 'S/N'} | Eq: ${item.model || 'N/A'}`;
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

            // --- ¡ESTA ERA LA FUNCIÓN QUE FALTABA EN TU SCRIPT! ---
            // Protegida con String() para que no explote si buscas puros números
            get filteredAssets() {
                if (!this.assetSearch) return this.assets;
                const q = String(this.assetSearch).toLowerCase();
                
                return this.assets.filter(a => 
                    String(a.internal_code || '').toLowerCase().includes(q) || 
                    String(a.serial_number || '').toLowerCase().includes(q) || 
                    String(a.model || '').toLowerCase().includes(q)
                );
            },
            // --------------------------------------------------------

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

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('updateForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');

                Swal.fire({
                    title: '¿Guardar cambios?',
                    text: "Se actualizará la información técnica de esta intervención.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#f1f5f9',
                    confirmButtonText: '<span class="font-black uppercase tracking-widest text-xs">Sí, guardar</span>',
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