<x-app-layout>
    <div class="py-6 px-2 sm:px-4 lg:px-6 max-w-[98%] mx-auto">

        <div class="mb-4 px-2">
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edición de Expediente Técnico: <span class="text-blue-600 ml-1">{{ $asset->serial_number }}</span>
            </h2>
            <p class="text-[11px] text-slate-500 uppercase font-bold mt-1">
                Última actualización: {{ $asset->updated_at->format('d/m/Y H:i') }} | ID Sistema: {{ $asset->id }}
            </p>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-6 py-3 mb-4 shadow-sm">
            <ul class="text-[10px] text-red-600 font-bold uppercase flex flex-wrap gap-4">
                @foreach ($errors->all() as $error)
                <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="edit-asset-form" action="{{ route('assets.update', $asset) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all hover:shadow-md">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all relative overflow-hidden">
                    @php
                    // Variables dinámicas para el diseño híbrido (Agente vs Manual)
                    $hybridInputClass = $asset->is_agent_managed
                    ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0'
                    : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm';

                    $hybridLabelClass = $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800';
                    @endphp

                    @if($asset->is_agent_managed)
                    <div class="mb-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3 shadow-inner">
                        <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-0.5">Hardware Protegido</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">El hardware de este equipo es reportado por el Agente SOMA. Solo puede editar los Periféricos.</p>
                        </div>
                    </div>
                    @else
                    <div class="mb-4 p-3.5 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3 shadow-inner">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <div>
                            <h4 class="text-[10px] font-bold text-amber-800 uppercase tracking-widest mb-0.5">Modo de Edición Manual</h4>
                            <p class="text-[11px] text-amber-700 leading-relaxed">Este equipo no cuenta con agente SOMA. Puede editar libremente sus componentes de hardware.</p>
                        </div>
                    </div>
                    @endif


                    <!-- @if($asset->is_agent_managed)
                    <div class="absolute top-0 right-0 bg-emerald-100 text-emerald-700 text-[9px] font-extrabold px-3 py-1 rounded-bl-lg flex items-center gap-1 shadow-sm border-b border-l border-emerald-200 uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Agente SOMA (Solo Lectura)
                    </div>
                    @else
                    <div class="absolute top-0 right-0 bg-amber-100 text-amber-700 text-[9px] font-extrabold px-3 py-1 rounded-bl-lg flex items-center gap-1 shadow-sm border-b border-l border-amber-200 uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Registro Manual (Editable)
                    </div>
                    @endif -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all relative overflow-hidden mb-6">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100 mt-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 10-4 0v1m4 0a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 100 4m0 0a2 2 0 100-4m0 0a2 2 0 110 4z" />
                            </svg>
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">1. Información General del Activo</h3>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">

                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-1 {{ $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800' }}">Serial</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                                    class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $asset->is_agent_managed ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0' : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm' }}"
                                    {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Placa Inventario</label>
                                <input type="text" name="internal_code" value="{{ old('internal_code', $asset->internal_code) }}"
                                    class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 transition-shadow shadow-sm">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-1 {{ $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800' }}">Hostname</label>
                                <input type="text" name="hostname" value="{{ old('hostname', $asset->hostname) }}"
                                    class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $asset->is_agent_managed ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0' : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm' }}"
                                    {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-1 {{ $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800' }}">Dirección IP</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address', $asset->ip_address) }}"
                                    class="w-full font-mono text-xs rounded-lg px-3 py-2 transition-all {{ $asset->is_agent_managed ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0' : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm' }}"
                                    {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-1 {{ $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800' }}">MAC Address</label>
                                <input type="text" name="mac_address" value="{{ old('mac_address', $asset->mac_address) }}"
                                    class="w-full font-mono text-xs rounded-lg px-3 py-2 transition-all {{ $asset->is_agent_managed ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0' : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm' }}"
                                    {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-1 {{ $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800' }}">Dominio</label>
                                <input type="text" name="domain_name" value="{{ old('domain_name', $asset->domain_name) }}"
                                    class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $asset->is_agent_managed ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0' : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm' }}"
                                    {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-1 {{ $asset->is_agent_managed ? 'text-slate-400' : 'text-slate-800' }}">Sis. Operativo</label>
                                <input type="text" name="os_version" value="{{ old('os_version', $asset->os_version) }}"
                                    class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $asset->is_agent_managed ? 'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0' : 'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm' }}"
                                    {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                            </div>

                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all hover:shadow-md">
                        <div class="bg-blue-50/50 rounded-2xl border border-blue-100 shadow-sm p-5 transition-all mb-6">
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">2. Ubicación y Asignación</h3>
                            </div>

                            <div class="mb-5 p-3.5 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3 shadow-inner">
                                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[10px] font-bold text-blue-900 uppercase tracking-widest mb-0.5">Gestión de Traslados y Asignaciones</h4>
                                    <p class="text-[11px] text-blue-700 leading-relaxed">
                                        Para reubicar este equipo (Sede, Bloque u Oficina) o cambiar al responsable asignado, debe gestionar el traslado a través del <strong>Módulo de Movimientos</strong> para garantizar la trazabilidad del activo.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Campus / Sede Actual</label>
                                    <input type="text"
                                        value="{{ $asset->room?->building?->campus?->name ?? 'Depende del salón' }}"
                                        class="w-full bg-slate-50 border-slate-100 text-slate-400 text-xs rounded-lg cursor-not-allowed px-3 py-2 focus:ring-0"
                                        readonly>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bloque / Edificio Actual</label>
                                    <input type="text"
                                        value="{{ $asset->room?->building?->name ?? 'Depende del salón' }}"
                                        class="w-full bg-slate-50 border-slate-100 text-slate-400 text-xs rounded-lg cursor-not-allowed px-3 py-2 focus:ring-0"
                                        readonly>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Oficina / Salón Actual</label>
                                    <input type="text"
                                        value="{{ $asset->room->nomenclatura ?? 'Sin asignar' }}"
                                        class="w-full bg-slate-50 border-slate-100 text-slate-400 text-xs rounded-lg cursor-not-allowed px-3 py-2 focus:ring-0"
                                        readonly>
                                    <input type="hidden" name="room_id" value="{{ $asset->room_id }}">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Custodio Asignado</label>
                                    <input type="text"
                                        value="{{ $asset->currentCustodian?->full_name ?? $asset->custodian?->name ?? 'Sin asignar' }}"
                                        class="w-full bg-slate-50 border-slate-100 text-slate-400 text-xs rounded-lg cursor-not-allowed px-3 py-2 focus:ring-0"
                                        readonly>
                                    <input type="hidden" name="custodian_id" value="{{ $asset->custodian_id }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3 shadow-inner">
                            <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-0.5">Especificaciones Bloqueadas</h4>
                                <p class="text-[11px] text-slate-500 leading-relaxed">
                                    Los componentes de Hardware, Software y Periféricos están protegidos por el sistema de auditoría y <strong>no pueden ser modificados manualmente</strong> en esta hoja de vida.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all mb-4">
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">3. Hardware Principal</h3>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Board Marca</label>
                                    <input type="text" name="board_brand" value="{{ old('board_brand', $asset->board_brand) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Board Modelo</label>
                                    <input type="text" name="board_model" value="{{ old('board_model', $asset->board_model) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">CPU Marca</label>
                                    <input type="text" name="cpu_brand" value="{{ old('cpu_brand', $asset->cpu_brand) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">CPU Modelo</label>
                                    <input type="text" name="cpu_model" value="{{ old('cpu_model', $asset->cpu_model) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">GPU Marca</label>
                                    <input type="text" name="gpu_brand" value="{{ old('gpu_brand', $asset->gpu_brand) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">GPU Modelo</label>
                                    <input type="text" name="gpu_model" value="{{ old('gpu_model', $asset->gpu_model) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
                                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">4. Memoria RAM</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Detalle General</label>
                                        <input type="text" name="ram" value="{{ old('ram', $asset->ram) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Capacidad (GB)</label>
                                        <input type="number" name="ram_capacity_gb" value="{{ old('ram_capacity_gb', $asset->ram_capacity_gb) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Marca</label>
                                        <input type="text" name="ram_brand" value="{{ old('ram_brand', $asset->ram_brand) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Modelo</label>
                                        <input type="text" name="ram_model" value="{{ old('ram_model', $asset->ram_model) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
                                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                    </svg>
                                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">5. Almacenamiento</h3>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Marca Disco</label>
                                        <input type="text" name="storage_brand" value="{{ old('storage_brand', $asset->storage_brand) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Modelo / Capacidad</label>
                                        <input type="text" name="storage_model" value="{{ old('storage_model', $asset->storage_model) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
                                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                    </svg>
                                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">6. Conectividad WiFi</h3>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Marca Tarjeta WiFi</label>
                                        <input type="text" name="wifi_brand" value="{{ old('wifi_brand', $asset->wifi_brand) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase mb-1 {{ $hybridLabelClass }}">Modelo Tarjeta WiFi</label>
                                        <input type="text" name="wifi_model" value="{{ old('wifi_model', $asset->wifi_model) }}" class="w-full text-xs rounded-lg px-3 py-2 transition-all {{ $hybridInputClass }}" {{ $asset->is_agent_managed ? 'readonly' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all mb-4 relative overflow-hidden">

                            <div class="absolute top-0 right-0 bg-blue-100 text-blue-700 text-[9px] font-extrabold px-3 py-1 rounded-bl-lg shadow-sm border-b border-l border-blue-200 uppercase tracking-wider">
                                Siempre Editable
                            </div>

                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">7. Periféricos y Seguridad</h3>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Activo Monitor</label>
                                    <input type="text" name="monitor_asset" value="{{ old('monitor_asset', $asset->monitor_asset) }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial Monitor</label>
                                    <input type="text" name="monitor_serial" value="{{ old('monitor_serial', $asset->monitor_serial) }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial Teclado</label>
                                    <input type="text" name="keyboard_serial" value="{{ old('keyboard_serial', $asset->keyboard_serial) }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial Mouse</label>
                                    <input type="text" name="mouse_serial" value="{{ old('mouse_serial', $asset->mouse_serial) }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Guaya Seguridad</label>
                                    <input type="text" name="security_guaya" value="{{ old('security_guaya', $asset->security_guaya) }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                        <a href="{{ route('assets.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>

                        <button type="button" onclick="confirmSave()" class="px-8 py-2.5 bg-blue-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Guardar Expediente
                        </button>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        <script>
                            function confirmSave() {
                                Swal.fire({
                                    title: '¿Guardar cambios?',
                                    text: "Estás a punto de actualizar el expediente técnico de este equipo. Asegúrate de que los datos diligenciados sean correctos.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#2563eb', // Azul Tailwind (blue-600)
                                    cancelButtonColor: '#94a3b8', // Gris Tailwind (slate-400)
                                    confirmButtonText: 'Sí, guardar',
                                    cancelButtonText: 'Cancelar',
                                    reverseButtons: true, // Pone el botón de confirmar a la derecha
                                    customClass: {
                                        title: 'text-lg font-bold text-slate-800',
                                        htmlContainer: 'text-sm text-slate-500',
                                        popup: 'rounded-2xl',
                                        confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase',
                                        cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Muestra un estado de carga opcional mientras envía
                                        Swal.fire({
                                            title: 'Guardando...',
                                            text: 'Actualizando base de datos',
                                            allowOutsideClick: false,
                                            didOpen: () => {
                                                Swal.showLoading()
                                            }
                                        });

                                        // Envía el formulario
                                        document.getElementById('edit-asset-form').submit();
                                    }
                                });
                            }
                        </script>
                    </div>
        </form>
    </div>
</x-app-layout>