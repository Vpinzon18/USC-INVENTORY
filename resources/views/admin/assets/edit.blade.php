<x-app-layout>

    <div class="py-6 px-2 sm:px-4 lg:px-6 max-w-[98%] mx-auto">

        {{-- =========================================================
             ENCABEZADO
        ========================================================== --}}

        <div class="mb-4 px-2">

            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">

                <svg class="w-6 h-6 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />

                </svg>

                Edición de Expediente Técnico:

                <span class="text-blue-600 ml-1">
                    {{ $asset->serial_number }}
                </span>

            </h2>

            <p class="text-[11px] text-slate-500 uppercase font-bold mt-1">

                Última actualización:
                {{ $asset->updated_at?->format('d/m/Y H:i') }}

                |

                ID Sistema:
                {{ $asset->id }}

            </p>

        </div>


        {{-- =========================================================
             ERRORES
        ========================================================== --}}

        @if ($errors->any())

            <div class="bg-red-50 border border-red-200 rounded-xl px-6 py-3 mb-4 shadow-sm">

                <ul class="text-[10px] text-red-600 font-bold uppercase flex flex-wrap gap-4">

                    @foreach ($errors->all() as $error)

                        <li>
                            ⚠️ {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             FORMULARIO
        ========================================================== --}}

        <form id="edit-asset-form"
            action="{{ route('assets.update', $asset) }}"
            method="POST"
            class="space-y-4">

            @csrf
            @method('PUT')


            {{-- =====================================================
                 VARIABLES
            ====================================================== --}}

            @php

                $agentManaged = (bool) $asset->is_agent_managed;

                $readonlyClass =
                    'bg-slate-50 border-slate-100 text-slate-400 cursor-not-allowed focus:ring-0';

                $editableClass =
                    'bg-white border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm';


                /*
                |--------------------------------------------------------------------------
                | Procesador
                |--------------------------------------------------------------------------
                */

                $processor = $asset->processors->first();


                /*
                |--------------------------------------------------------------------------
                | GPU
                |--------------------------------------------------------------------------
                */

                $gpu = $asset->gpus->first();


                /*
                |--------------------------------------------------------------------------
                | RAM
                |--------------------------------------------------------------------------
                */

                $ramModule = $asset->ramModules->first();


                /*
                |--------------------------------------------------------------------------
                | Storage
                |--------------------------------------------------------------------------
                */

                $storage = $asset->storageDevices->first();


                /*
                |--------------------------------------------------------------------------
                | Batería
                |--------------------------------------------------------------------------
                */

                $battery = $asset->battery;


                /*
                |--------------------------------------------------------------------------
                | RED
                |--------------------------------------------------------------------------
                */

                $network = $asset->network;

                $networkAdapters =
                    $network?->adapters ?? collect();

                $primaryAdapter =
                    $networkAdapters->firstWhere(
                        'is_primary',
                        true
                    );

            @endphp


            {{-- =====================================================
                 BLOQUE PRINCIPAL
            ====================================================== --}}

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">


                {{-- =================================================
                     ESTADO DEL ACTIVO
                ================================================== --}}

                @if($agentManaged)

                    <div class="mb-5 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3 shadow-inner">

                        <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />

                        </svg>

                        <div>

                            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-0.5">
                                Hardware Protegido
                            </h4>

                            <p class="text-[11px] text-slate-500 leading-relaxed">

                                El hardware de este equipo es reportado por el
                                <strong>Agente SIGMA</strong>.

                                Las especificaciones técnicas no pueden modificarse manualmente.

                            </p>

                        </div>

                    </div>

                @else

                    <div class="mb-5 p-3.5 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3 shadow-inner">

                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />

                        </svg>

                        <div>

                            <h4 class="text-[10px] font-bold text-amber-800 uppercase tracking-widest mb-0.5">
                                Modo de Edición Manual
                            </h4>

                            <p class="text-[11px] text-amber-700 leading-relaxed">

                                Este equipo no cuenta con agente SIGMA.
                                La información técnica puede ser administrada manualmente.

                            </p>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     1. INFORMACIÓN GENERAL
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">

                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">

                        <svg class="w-5 h-5 text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 10-4 0v1m4 0a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 100 4m0 0a2 2 0 100-4m0 0a2 2 0 110 4z" />

                        </svg>

                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                            1. Información General del Activo
                        </h3>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">


                        {{-- SERIAL --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1
                                {{ $agentManaged ? 'text-slate-400' : 'text-slate-800' }}">

                                Serial

                            </label>

                            <input type="text"
                                name="serial_number"
                                value="{{ old('serial_number', $asset->serial_number) }}"
                                class="w-full text-xs rounded-lg px-3 py-2 transition-all
                                {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>

                        </div>


                        {{-- PLACA --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">

                                Placa Inventario

                            </label>

                            <input type="text"
                                name="internal_code"
                                value="{{ old('internal_code', $asset->internal_code) }}"
                                class="w-full {{ $editableClass }} text-xs rounded-lg px-3 py-2">

                        </div>


                        {{-- HOSTNAME --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1
                                {{ $agentManaged ? 'text-slate-400' : 'text-slate-800' }}">

                                Hostname

                            </label>

                            <input type="text"
                                name="hostname"
                                value="{{ old('hostname', $asset->hostname) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
                                {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>

                        </div>


                        {{-- MODELO --}}

                        <div class="md:col-span-2">

                            <label class="block text-[10px] font-bold uppercase mb-1 text-slate-400">

                                Modelo

                            </label>

                            <input type="text"
                                value="{{ $asset->model_version ?? 'No registrado' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- IP --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1 text-slate-400">

                                Dirección IP principal

                            </label>

                            <input type="text"
                                value="{{ $primaryAdapter?->ipv4 ?? 'No registrada' }}"
                                class="w-full font-mono {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- MAC --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1 text-slate-400">

                                MAC principal

                            </label>

                            <input type="text"
                                value="{{ $primaryAdapter?->mac_address ?? 'No registrada' }}"
                                class="w-full font-mono {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- DOMINIO --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1 text-slate-400">

                                Dominio

                            </label>

                            <input type="text"
                                value="{{ $asset->domain_name ?? 'No registrado' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- SISTEMA OPERATIVO --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1 text-slate-400">

                                Sistema Operativo

                            </label>

                            <input type="text"
                                value="{{ $asset->os_version ?? 'No registrado' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     2. UBICACIÓN
                ================================================== --}}

                <div class="bg-blue-50/50 rounded-2xl border border-blue-100 shadow-sm p-5 mb-6">

                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-blue-100">

                        <svg class="w-5 h-5 text-amber-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

                        </svg>

                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">

                            2. Ubicación y Asignación

                        </h3>

                    </div>


                    <div class="mb-5 p-3.5 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3 shadow-inner">

                        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                        <div>

                            <h4 class="text-[10px] font-bold text-blue-900 uppercase tracking-widest mb-0.5">

                                Gestión de Traslados y Asignaciones

                            </h4>

                            <p class="text-[11px] text-blue-700 leading-relaxed">

                                Para cambiar ubicación o responsable,
                                gestione el traslado mediante el
                                <strong>Módulo de Movimientos de Activos</strong>.

                            </p>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">


                        {{-- CAMPUS --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">

                                Campus / Sede Actual

                            </label>

                            <input type="text"
                                value="{{ $asset->room?->building?->campus?->name ?? 'Sin asignar' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- EDIFICIO --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">

                                Bloque / Edificio Actual

                            </label>

                            <input type="text"
                                value="{{ $asset->room?->building?->name ?? 'Sin asignar' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- SALÓN --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">

                                Oficina / Salón Actual

                            </label>

                            <input type="text"
                                value="{{ $asset->room?->nomenclatura ?? 'Sin asignar' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                            <input type="hidden"
                                name="room_id"
                                value="{{ $asset->room_id }}">

                        </div>


                        {{-- CUSTODIO --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">

                                Custodio Asignado

                            </label>

                            <input type="text"
                                value="{{ $asset->currentCustodian?->full_name ?? $asset->custodian?->name ?? 'Sin asignar' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                            <input type="hidden"
                                name="custodian_id"
                                value="{{ $asset->currentCustodian?->id ?? $asset->custodian_id }}">

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     AVISO HARDWARE
                ================================================== --}}

                <div class="mb-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3 shadow-inner">

                    <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 0 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />

                    </svg>

                    <div>

                        <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-widest mb-0.5">

                            Especificaciones Técnicas

                        </h4>

                        <p class="text-[11px] text-slate-500 leading-relaxed">

                            Estos datos son administrados por el
                            <strong>Agente SIGMA</strong>
                            y se almacenan en tablas especializadas.

                        </p>

                    </div>

                </div>


                {{-- =================================================
                     3. MOTHERBOARD
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">

                        <svg class="w-5 h-5 text-emerald-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />

                        </svg>

                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">

                            3. Hardware Principal

                        </h3>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4">


                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Board Marca
                            </label>

                            <input type="text"
                                value="{{ $asset->board_brand ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Board Modelo
                            </label>

                            <input type="text"
                                value="{{ $asset->board_model ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Board Serial
                            </label>

                            <input type="text"
                                value="{{ $asset->board_serial ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Board Versión
                            </label>

                            <input type="text"
                                value="{{ $asset->board_version ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Estado
                            </label>

                            <input type="text"
                                value="{{ $asset->board_status ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     4. CPU
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-blue-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                4. Procesadores
                            </h3>

                        </div>

                        <span class="text-[9px] font-bold uppercase bg-blue-50 text-blue-600 px-2 py-1 rounded-md">

                            {{ $asset->processors->count() }} detectado(s)

                        </span>

                    </div>


                    @forelse($asset->processors as $processor)

                        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4 mb-3">

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Marca
                                </label>

                                <input type="text"
                                    value="{{ $processor->brand ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                            <div class="md:col-span-2">

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Modelo
                                </label>

                                <input type="text"
                                    value="{{ $processor->model ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Núcleos
                                </label>

                                <input type="text"
                                    value="{{ $processor->cores ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Hilos
                                </label>

                                <input type="text"
                                    value="{{ $processor->threads ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Velocidad
                                </label>

                                <input type="text"
                                    value="{{ $processor->speed_mhz ? $processor->speed_mhz . ' MHz' : 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Arquitectura
                                </label>

                                <input type="text"
                                    value="{{ $processor->architecture ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                        </div>

                    @empty

                        <div class="py-6 text-center text-xs text-slate-400 italic">

                            No hay procesadores registrados por SIGMA Agent.

                        </div>

                    @endforelse

                </div>


                {{-- =================================================
                     5. RAM
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-indigo-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                5. Memoria RAM
                            </h3>

                        </div>

                        <span class="text-[9px] font-bold uppercase bg-indigo-50 text-indigo-600 px-2 py-1 rounded-md">

                            {{ $asset->ramModules->count() }} módulo(s)

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-left text-xs">

                            <thead class="bg-slate-50 text-slate-500 uppercase">

                                <tr>

                                    <th class="px-3 py-2">
                                        Fabricante
                                    </th>

                                    <th class="px-3 py-2">
                                        Modelo
                                    </th>

                                    <th class="px-3 py-2">
                                        Slot
                                    </th>

                                    <th class="px-3 py-2">
                                        Tipo
                                    </th>

                                    <th class="px-3 py-2">
                                        Capacidad
                                    </th>

                                    <th class="px-3 py-2">
                                        Velocidad
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @forelse($asset->ramModules as $ram)

                                    <tr>

                                        <td class="px-3 py-2 font-medium">
                                            {{ $ram->manufacturer ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $ram->model ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $ram->slot ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $ram->memory_type ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $ram->capacity_gb !== null ? $ram->capacity_gb . ' GB' : 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $ram->speed_mhz !== null ? $ram->speed_mhz . ' MHz' : 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="px-3 py-6 text-center text-slate-400 italic">

                                            No hay módulos RAM registrados.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =================================================
                     6. STORAGE
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-rose-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                6. Almacenamiento
                            </h3>

                        </div>

                        <span class="text-[9px] font-bold uppercase bg-rose-50 text-rose-600 px-2 py-1 rounded-md">

                            {{ $asset->storageDevices->count() }} dispositivo(s)

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-left text-xs">

                            <thead class="bg-slate-50 text-slate-500 uppercase">

                                <tr>

                                    <th class="px-3 py-2">
                                        Marca
                                    </th>

                                    <th class="px-3 py-2">
                                        Modelo
                                    </th>

                                    <th class="px-3 py-2">
                                        Serial
                                    </th>

                                    <th class="px-3 py-2">
                                        Tipo
                                    </th>

                                    <th class="px-3 py-2">
                                        Capacidad
                                    </th>

                                    <th class="px-3 py-2">
                                        Libre
                                    </th>

                                    <th class="px-3 py-2">
                                        Estado
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @forelse($asset->storageDevices as $disk)

                                    <tr>

                                        <td class="px-3 py-2 font-medium">
                                            {{ $disk->brand ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $disk->model ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2 font-mono text-[10px]">
                                            {{ $disk->serial_number ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $disk->type ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $disk->capacity_gb !== null ? $disk->capacity_gb . ' GB' : 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $disk->free_gb !== null ? $disk->free_gb . ' GB' : 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $disk->health ?? 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="px-3 py-6 text-center text-slate-400 italic">

                                            No hay dispositivos de almacenamiento registrados.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =================================================
                     7. GPU
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-purple-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                7. Procesadores Gráficos
                            </h3>

                        </div>

                        <span class="text-[9px] font-bold uppercase bg-purple-50 text-purple-600 px-2 py-1 rounded-md">

                            {{ $asset->gpus->count() }} GPU(s)

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="w-full text-left text-xs">

                            <thead class="bg-slate-50 text-slate-500 uppercase">

                                <tr>

                                    <th class="px-3 py-2">
                                        Marca
                                    </th>

                                    <th class="px-3 py-2">
                                        Modelo
                                    </th>

                                    <th class="px-3 py-2">
                                        Memoria
                                    </th>

                                    <th class="px-3 py-2">
                                        Driver
                                    </th>

                                    <th class="px-3 py-2">
                                        Procesador
                                    </th>

                                    <th class="px-3 py-2">
                                        Resolución
                                    </th>

                                    <th class="px-3 py-2">
                                        Hz
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @forelse($asset->gpus as $gpu)

                                    <tr>

                                        <td class="px-3 py-2 font-medium">
                                            {{ $gpu->brand ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $gpu->model ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $gpu->memory_mb !== null ? $gpu->memory_mb . ' MB' : 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $gpu->driver_version ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $gpu->processor ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $gpu->resolution ?? 'N/A' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            {{ $gpu->refresh_rate ?? 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="px-3 py-6 text-center text-slate-400 italic">

                                            No hay GPU registradas.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =================================================
                     8. CONECTIVIDAD Y RED
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    {{-- ENCABEZADO --}}

                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-cyan-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />

                            </svg>

                            <div>

                                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                    8. Conectividad y Red
                                </h3>

                                <p class="text-[10px] text-slate-400">
                                    Información detectada por SIGMA Agent.
                                </p>

                            </div>

                        </div>


                        <span class="text-[9px] font-bold uppercase bg-cyan-50 text-cyan-600 px-2 py-1 rounded-md">

                            {{ $networkAdapters->count() }} adaptador(es)

                        </span>

                    </div>


                    {{-- =================================================
                         CONFIGURACIÓN GENERAL
                    ================================================== --}}

                    @if($network)

                        <div class="mb-5">

                            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">
                                Configuración general
                            </h4>


                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">


                                {{-- ESTADO --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        Conectividad
                                    </label>

                                    <input type="text"
                                        value="{{ $network->connected ? 'Conectado' : 'Desconectado' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                        readonly>

                                </div>


                                {{-- GATEWAY --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        Gateway
                                    </label>

                                    <input type="text"
                                        value="{{ $network->gateway ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                        readonly>

                                </div>


                                {{-- DNS --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        Servidor DNS
                                    </label>

                                    <input type="text"
                                        value="{{ $network->dns_server ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                        readonly>

                                </div>


                                {{-- DHCP --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        DHCP
                                    </label>

                                    <input type="text"
                                        value="{{ $network->dhcp_enabled ? 'Habilitado' : 'Deshabilitado' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="py-6 text-center text-xs text-slate-400 italic">

                            No hay configuración general de red registrada.

                        </div>

                    @endif


                    {{-- =================================================
                         ADAPTADOR PRINCIPAL
                    ================================================== --}}

                    @if($primaryAdapter)

                        <div class="mb-5">

                            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">
                                Adaptador principal
                            </h4>


                            <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-4">


                                {{-- NOMBRE --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        Adaptador
                                    </label>

                                    <input type="text"
                                        value="{{ $primaryAdapter->name ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                        readonly>

                                </div>


                                {{-- DESCRIPCIÓN --}}

                                <div class="md:col-span-2">

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        Descripción
                                    </label>

                                    <input type="text"
                                        value="{{ $primaryAdapter->description ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                        readonly>

                                </div>


                                {{-- TIPO --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        Tipo
                                    </label>

                                    <input type="text"
                                        value="{{ $primaryAdapter->type ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                        readonly>

                                </div>


                                {{-- MAC --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        MAC
                                    </label>

                                    <input type="text"
                                        value="{{ $primaryAdapter->mac_address ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                        readonly>

                                </div>


                                {{-- IPV4 --}}

                                <div>

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        IPv4
                                    </label>

                                    <input type="text"
                                        value="{{ $primaryAdapter->ipv4 ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                        readonly>

                                </div>


                                {{-- IPV6 --}}

                                <div class="md:col-span-2">

                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                        IPv6
                                    </label>

                                    <input type="text"
                                        value="{{ $primaryAdapter->ipv6 ?? 'N/A' }}"
                                        class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                         TODOS LOS ADAPTADORES
                    ================================================== --}}

                    <div>

                        <div class="flex items-center justify-between mb-3">

                            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                Adaptadores detectados
                            </h4>

                            <span class="text-[9px] text-slate-400">
                                {{ $networkAdapters->count() }} registrado(s)
                            </span>

                        </div>


                        <div class="overflow-x-auto">

                            <table class="w-full text-left text-xs">

                                <thead class="bg-slate-50 text-slate-500 uppercase">

                                    <tr>

                                        <th class="px-3 py-2">
                                            Adaptador
                                        </th>

                                        <th class="px-3 py-2">
                                            Tipo
                                        </th>

                                        <th class="px-3 py-2">
                                            MAC
                                        </th>

                                        <th class="px-3 py-2">
                                            IPv4
                                        </th>

                                        <th class="px-3 py-2">
                                            IPv6
                                        </th>

                                        <th class="px-3 py-2">
                                            Estado
                                        </th>

                                        <th class="px-3 py-2">
                                            Principal
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-slate-100">

                                    @forelse($networkAdapters as $adapter)

                                        <tr>

                                            <td class="px-3 py-2">

                                                <div class="font-medium text-slate-700">
                                                    {{ $adapter->name ?? 'N/A' }}
                                                </div>

                                                @if($adapter->description)

                                                    <div class="text-[9px] text-slate-400 truncate max-w-[220px]">

                                                        {{ $adapter->description }}

                                                    </div>

                                                @endif

                                            </td>


                                            <td class="px-3 py-2">
                                                {{ $adapter->type ?? 'N/A' }}
                                            </td>


                                            <td class="px-3 py-2 font-mono text-[10px]">
                                                {{ $adapter->mac_address ?? 'N/A' }}
                                            </td>


                                            <td class="px-3 py-2 font-mono text-[10px]">
                                                {{ $adapter->ipv4 ?? 'N/A' }}
                                            </td>


                                            <td class="px-3 py-2 font-mono text-[10px]">
                                                {{ $adapter->ipv6 ?? 'N/A' }}
                                            </td>


                                            <td class="px-3 py-2">

                                                @if($adapter->connected)

                                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase bg-emerald-50 text-emerald-600 px-2 py-1 rounded-md">
                                                        Conectado
                                                    </span>

                                                @else

                                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase bg-slate-100 text-slate-500 px-2 py-1 rounded-md">
                                                        Desconectado
                                                    </span>

                                                @endif

                                            </td>


                                            <td class="px-3 py-2">

                                                @if($adapter->is_primary)

                                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase bg-blue-50 text-blue-600 px-2 py-1 rounded-md">
                                                        Sí
                                                    </span>

                                                @else

                                                    <span class="text-slate-400">
                                                        No
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="7"
                                                class="px-3 py-6 text-center text-slate-400 italic">

                                                No hay adaptadores de red registrados por SIGMA Agent.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     9. BATERÍA
                ================================================== --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-amber-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 7h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2zm14 4h2v2h-2" />

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                9. Batería
                            </h3>

                        </div>

                        <span class="text-[9px] font-bold uppercase bg-amber-50 text-amber-600 px-2 py-1 rounded-md">
                            SIGMA Agent
                        </span>

                    </div>


                    @if($battery)

                        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-4">

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Fabricante
                                </label>

                                <input type="text"
                                    value="{{ $battery->manufacturer ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Modelo
                                </label>

                                <input type="text"
                                    value="{{ $battery->model ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Salud
                                </label>

                                <input type="text"
                                    value="{{ $battery->health_percent !== null ? $battery->health_percent . '%' : 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Ciclos
                                </label>

                                <input type="text"
                                    value="{{ $battery->cycle_count ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Carga
                                </label>

                                <input type="text"
                                    value="{{ $battery->charge_percent !== null ? $battery->charge_percent . '%' : 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Estado
                                </label>

                                <input type="text"
                                    value="{{ $battery->is_charging ? 'Cargando' : 'No cargando' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                        </div>

                    @else

                        <div class="py-6 text-center text-xs text-slate-400 italic">

                            Este equipo no tiene información de batería registrada.

                        </div>

                    @endif

                </div>


{{-- MONITORES --}}

<div class="mb-5">

    <div class="flex items-center justify-between mb-3">

        <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
            Monitores detectados por SIGMA
        </h4>

        <span class="text-[9px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded-md font-bold">
            {{ $asset->monitors->count() }} monitor(es)
        </span>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full text-left text-xs">

            <thead class="bg-slate-50 text-slate-500 uppercase">

                <tr>

                    <th class="px-3 py-2">
                        Marca
                    </th>

                    <th class="px-3 py-2">
                        Modelo
                    </th>

                    <th class="px-3 py-2">
                        Serial
                    </th>

                    <th class="px-3 py-2">
                        Código
                    </th>

                    <th class="px-3 py-2">
                        Tamaño
                    </th>

                    <th class="px-3 py-2">
                        Resolución
                    </th>

                    <th class="px-3 py-2">
                        Hz
                    </th>

                    <th class="px-3 py-2">
                        Activo fijo
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($asset->monitors as $monitor)

                    <tr>

                        {{-- MARCA --}}

                        <td class="px-3 py-2">
                            {{ $monitor->brand ?? 'N/A' }}
                        </td>


                        {{-- MODELO --}}

                        <td class="px-3 py-2">
                            {{ $monitor->model ?? 'N/A' }}
                        </td>


                        {{-- SERIAL --}}

                        <td class="px-3 py-2 font-mono text-[10px]">
                            {{ $monitor->serial_number ?? 'N/A' }}
                        </td>


                        {{-- CÓDIGO FABRICANTE --}}

                        <td class="px-3 py-2">
                            {{ $monitor->manufacturer_code ?? 'N/A' }}
                        </td>


                        {{-- TAMAÑO --}}

                        <td class="px-3 py-2">
                            {{ $monitor->size ?? 'N/A' }}
                        </td>


                        {{-- RESOLUCIÓN --}}

                        <td class="px-3 py-2">
                            {{ $monitor->resolution ?? 'N/A' }}
                        </td>


                        {{-- HZ --}}

                        <td class="px-3 py-2">
                            {{ $monitor->refresh_rate ?? 'N/A' }}
                        </td>


                        {{-- ACTIVO FIJO --}}

                        <td class="px-3 py-2 min-w-[180px]">

                            <input
                                type="text"
                                name="monitors[{{ $monitor->id }}][fixed_asset_code]"
                                value="{{ old(
                                    'monitors.' . $monitor->id . '.fixed_asset_code',
                                    $monitor->fixed_asset_code
                                ) }}"
                                placeholder="Activo fijo"
                                class="w-full {{ $editableClass }} text-xs rounded-lg px-3 py-2"
                            >

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8"
                            class="px-3 py-5 text-center text-slate-400 italic">

                            No hay monitores registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


                {{-- =================================================
                     11. SOFTWARE
                ================================================== --}}

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6">

                    <div class="p-4 bg-gray-50/75 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">

                        <div class="flex items-center gap-2">

                            <svg class="w-5 h-5 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                            <div>

                                <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs">
                                    11. Software Instalado
                                </h3>

                                <p class="text-[10px] text-gray-400 font-medium">
                                    Aplicaciones detectadas por SIGMA Agent.
                                </p>

                            </div>

                        </div>


                        <div class="flex items-center gap-3 w-full sm:w-auto">

                            <div class="relative w-full sm:w-64">

                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

                                    <svg class="w-3.5 h-3.5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                    </svg>

                                </div>

                                <input type="text"
                                    id="softwareSearch"
                                    placeholder="Filtrar por nombre o versión..."
                                    class="w-full bg-white pl-8 p-1.5 border border-gray-200 rounded-lg text-xs font-medium focus:ring-indigo-500 focus:border-indigo-500 text-gray-700 outline-none shadow-sm">

                            </div>


                            <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md font-bold uppercase shrink-0 border border-indigo-100/50">

                                {{ $asset->software->where('is_active', true)->count() }} aplicaciones

                            </span>

                        </div>

                    </div>


                    <div class="overflow-x-auto max-h-[340px] overflow-y-auto bg-white"
                        id="softwareTableContainer">

                        <table class="w-full text-left text-xs border-collapse">

                            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider sticky top-0 z-10 border-b border-gray-200">

                                <tr>

                                    <th class="px-4 py-3 font-bold text-gray-700">
                                        Nombre de la Aplicación
                                    </th>

                                    <th class="px-4 py-3 font-bold text-gray-700 text-center w-40">
                                        Versión
                                    </th>

                                    <th class="px-4 py-3 font-bold text-gray-700 text-center w-48">
                                        Fecha de Registro
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100 text-gray-600"
                                id="softwareTableBody">

                                @forelse($asset->software->where('is_active', true) as $app)

                                    <tr class="software-row hover:bg-gray-50/70 transition-colors">

                                        <td class="px-4 py-2 font-medium text-gray-800 software-name">
                                            {{ $app->name }}
                                        </td>

                                        <td class="px-4 py-2 text-center font-mono text-[11px] text-gray-500">
                                            {{ $app->version ?? 'N/A' }}
                                        </td>

                                        <td class="px-4 py-2 text-center text-gray-400">
                                            {{ $app->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="3"
                                            class="px-4 py-12 text-center text-gray-400 italic bg-gray-50/20">

                                            No hay aplicaciones registradas en este equipo.

                                        </td>

                                    </tr>

                                @endforelse


                                <tr id="noSoftwareResults" class="hidden">

                                    <td colspan="3"
                                        class="px-4 py-8 text-center text-xs font-bold text-amber-600 bg-amber-50/20">

                                        ⚠️ No se encontraron aplicaciones que coincidan con la búsqueda.

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =================================================
                     BOTONES
                ================================================== --}}

                <div class="mt-8 flex items-center justify-end gap-3 pt-4 border-t border-slate-200">

                    <a href="{{ route('assets.index') }}"
                        class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">

                        Cancelar

                    </a>


                    <button type="button"
                        onclick="confirmSave()"
                        class="px-8 py-2.5 bg-blue-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 active:scale-95 flex items-center gap-2">

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />

                        </svg>

                        Guardar Expediente

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- =============================================================
         SWEETALERT
    ============================================================= --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>

        /*
        |--------------------------------------------------------------------------
        | BUSCADOR DE SOFTWARE
        |--------------------------------------------------------------------------
        */

        const softwareSearch =
            document.getElementById('softwareSearch');

        if (softwareSearch) {

            softwareSearch.addEventListener(
                'input',
                function (e) {

                    const term =
                        e.target.value
                            .toLowerCase()
                            .trim();

                    const rows =
                        document.querySelectorAll(
                            '.software-row'
                        );

                    let visibles = 0;

                    rows.forEach(row => {

                        const nameElement =
                            row.querySelector(
                                '.software-name'
                            );

                        const name =
                            nameElement?.textContent
                                .toLowerCase() ?? '';

                        const version =
                            row.children[1]
                                ?.textContent
                                .toLowerCase() ?? '';

                        if (
                            name.includes(term) ||
                            version.includes(term)
                        ) {

                            row.classList.remove('hidden');

                            visibles++;

                        } else {

                            row.classList.add('hidden');

                        }

                    });


                    const noResultsRow =
                        document.getElementById(
                            'noSoftwareResults'
                        );

                    if (
                        noResultsRow &&
                        visibles === 0 &&
                        term.length > 0
                    ) {

                        noResultsRow.classList.remove('hidden');

                    } else if (noResultsRow) {

                        noResultsRow.classList.add('hidden');

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMACIÓN
        |--------------------------------------------------------------------------
        */

        function confirmSave() {

            Swal.fire({

                title: '¿Guardar cambios?',

                text: 'Se actualizará la información administrativa del expediente técnico.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Sí, guardar',

                cancelButtonText: 'Cancelar',

                reverseButtons: true,

                customClass: {

                    popup: 'rounded-2xl',

                    title: 'text-lg font-bold text-slate-800',

                    htmlContainer: 'text-sm text-slate-500',

                    confirmButton:
                        'rounded-lg px-6 py-2.5 text-xs font-bold uppercase',

                    cancelButton:
                        'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'

                }

            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Guardando...',

                        text: 'Actualizando información administrativa.',

                        allowOutsideClick: false,

                        didOpen: () => {

                            Swal.showLoading();

                        }

                    });

                    document
                        .getElementById('edit-asset-form')
                        .submit();

                }

            });
        }

    </script>

</x-app-layout>