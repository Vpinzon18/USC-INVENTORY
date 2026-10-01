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
            data-agent-managed="{{ $asset->is_agent_managed ? '1' : '0' }}"
            class="space-y-4">

            @csrf
            @method('PUT')


            {{-- =====================================================
                 VARIABLES
            ====================================================== --}}

            @php

            $agentManaged = (bool) $asset->is_agent_managed;

            $readonlyClass =
            'bg-slate-50 border border-slate-200 text-slate-700 cursor-default focus:ring-0';

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
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Modelo
                            </label>

                            <input
                                type="text"
                                name="model_version"
                                value="{{ old('model_version', $asset->model_version) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
            {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>
                        </div>


                        {{-- IP --}}

                        <div>

                            <label class="block text-[10px] font-bold uppercase mb-1 text-slate-400">

                                 IP principal

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
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Dominio
                            </label>

                            <input
                                type="text"
                                name="domain_name"
                                value="{{ old('domain_name', $asset->domain_name) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
            {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>
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

                            {{ $agentManaged ? 'Especificaciones Técnicas Protegidas' : 'Especificaciones Técnicas Editables' }}

                        </h4>

                        <p class="text-[11px] text-slate-500 leading-relaxed">

                            @if($agentManaged)
                                Estos datos son administrados por el <strong>Agente SIGMA</strong>
                                y se almacenan en tablas especializadas.
                            @else
                                Este activo fue registrado manualmente; sus datos técnicos pueden actualizarse.
                            @endif

                        </p>

                    </div>

                </div>


                {{-- =================================================
     3. MOTHERBOARD / BIOS
================================================= --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">

                        <svg class="w-5 h-5 text-emerald-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 002 2v10a2 2 0 002 2z" />

                        </svg>

                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                            3. Motherboard / BIOS
                        </h3>

                    </div>


                    {{-- =================================================
         MOTHERBOARD
    ================================================== --}}

                    <div class="mb-5">

                        <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-3">
                            Motherboard
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4">


                            {{-- FABRICANTE --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Fabricante
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->board_brand ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            {{-- MODELO --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Modelo
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->board_model ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            {{-- SERIAL --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Serial
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->board_serial ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                    readonly>

                            </div>


                            {{-- VERSIÓN --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Versión
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->board_version ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            {{-- ESTADO --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Estado
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->board_status ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
         BIOS
    ================================================== --}}

                    <div class="border-t border-slate-100 pt-5">

                        <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-3">
                            BIOS
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">


                            {{-- FABRICANTE BIOS --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Fabricante BIOS
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->bios_manufacturer ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            {{-- VERSIÓN BIOS --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Versión BIOS
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->bios_version ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>


                            {{-- SERIAL BIOS --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Serial BIOS
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->bios_serial ?? 'N/A' }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2 font-mono"
                                    readonly>

                            </div>


                            {{-- FECHA BIOS --}}

                            <div>

                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                    Fecha BIOS
                                </label>

                                <input
                                    type="text"
                                    value="{{ $asset->bios_release_date ?? ($asset->bios_date ?? 'N/A') }}"
                                    class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                    readonly>

                            </div>

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

                            <tbody class="divide-y divide-slate-100 text-slate-700">

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

                            <tbody class="divide-y divide-slate-100 text-slate-700">

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

                            <tbody class="divide-y divide-slate-100 text-slate-700">

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
                                    {{ $agentManaged ? 'Información detectada por SIGMA Agent.' : 'Información técnica editable del activo manual.' }}
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


                                <tbody class="divide-y divide-slate-100 text-slate-700">

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

                        {{-- FABRICANTE --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Fabricante
                            </label>

                            <input type="text"
                                value="{{ $battery->manufacturer ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- MODELO --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Modelo
                            </label>

                            <input type="text"
                                value="{{ $battery->model ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- SERIAL --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Serial
                            </label>

                            <input type="text"
                                value="{{ $battery->serial_number ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- ESTADO --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Estado
                            </label>

                            <input type="text"
                                value="{{ $battery->health ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- CICLOS --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Ciclos
                            </label>

                            <input type="text"
                                value="{{ $battery->cycle_count ?? 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- CARGA --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Carga
                            </label>

                            <input type="text"
                                value="{{ $battery->charge_percent !== null ? $battery->charge_percent . '%' : 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- ESTADO DE CARGA --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Cargando
                            </label>

                            <input type="text"
                                value="{{ $battery->is_charging === true ? 'Sí' : ($battery->is_charging === false ? 'No' : 'N/A') }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- AUTONOMÍA ESTIMADA --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Autonomía estimada
                            </label>

                            <input type="text"
                                value="{{ $battery->estimated_runtime_minutes !== null ? $battery->estimated_runtime_minutes . ' min' : 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- CAPACIDAD DE DISEÑO --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Capacidad de diseño
                            </label>

                            <input type="text"
                                value="{{ $battery->design_capacity !== null ? $battery->design_capacity . ' mWh' : 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- CAPACIDAD DE CARGA COMPLETA --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Capacidad máxima
                            </label>

                            <input type="text"
                                value="{{ $battery->full_charge_capacity !== null ? $battery->full_charge_capacity . ' mWh' : 'N/A' }}"
                                class="w-full {{ $readonlyClass }} text-xs rounded-lg px-3 py-2"
                                readonly>

                        </div>


                        {{-- CAPACIDAD RESTANTE --}}
                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Capacidad restante
                            </label>

                            <input type="text"
                                value="{{ $battery->remaining_capacity !== null ? $battery->remaining_capacity . ' mWh' : 'N/A' }}"
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

                {{-- =========================================================
     10. MONITORES
========================================================= --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    {{-- CABECERA --}}
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">

                        <div class="flex items-center gap-2">

                            {{-- ICONO --}}
                            <svg class="w-5 h-5 text-indigo-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 17h6m-7 4h8m-9-4h10a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />

                            </svg>

                            <div>

                                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                    10. Monitores
                                </h3>

                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    Monitores detectados por SIGMA Agent.
                                </p>

                            </div>

                        </div>


                        {{-- CONTADOR --}}
                        <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600
                     text-[10px] font-bold">

                            {{ $asset->monitors->count() }}
                            {{ $asset->monitors->count() === 1 ? 'MONITOR' : 'MONITORES' }}

                        </span>

                    </div>


                    {{-- CONTENIDO --}}
                    @if($asset->monitors->count())

                    <div class="overflow-x-auto">

                        <table class="w-full text-left">

                            <thead>

                                <tr class="bg-slate-50 border-b border-slate-100">

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Marca
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Modelo
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Serial
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Código
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Tamaño
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Resolución
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Hz
                                    </th>

                                    <th class="px-3 py-3 text-[10px] font-extrabold
                                   text-slate-500 uppercase">
                                        Activo fijo
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100 text-slate-700">

                                @foreach($asset->monitors as $monitor)

                                <tr class="hover:bg-slate-50 transition">


                                    {{-- MARCA --}}

                                    <td class="px-3 py-3 text-xs text-slate-700 font-medium">
                                        {{ $monitor->brand ?? 'N/A' }}
                                    </td>


                                    {{-- MODELO --}}

                                    <td class="px-3 py-3 text-xs text-slate-700">
                                        {{ $monitor->model ?? 'N/A' }}
                                    </td>


                                    {{-- SERIAL --}}

                                    <td class="px-3 py-3 text-xs text-slate-700 font-mono">
                                        {{ $monitor->serial_number ?? 'N/A' }}
                                    </td>


                                    {{-- CÓDIGO FABRICANTE --}}

                                    <td class="px-3 py-3 text-xs text-slate-700">
                                        {{ $monitor->manufacturer_code ?? 'N/A' }}
                                    </td>


                                    {{-- TAMAÑO --}}

                                    <td class="px-3 py-3 text-xs text-slate-700">
                                        {{ $monitor->size !== null
                                    ? number_format($monitor->size, 2) . '"'
                                    : 'N/A' }}
                                    </td>


                                    {{-- RESOLUCIÓN --}}

                                    <td class="px-3 py-3 text-xs text-slate-700 font-mono">
                                        {{ $monitor->resolution ?? 'N/A' }}
                                    </td>


                                    {{-- REFRESH RATE --}}

                                    <td class="px-3 py-3 text-xs text-slate-700">
                                        {{ $monitor->refresh_rate !== null
                                    ? $monitor->refresh_rate . ' Hz'
                                    : 'N/A' }}
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
                                            class="w-full bg-white border border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           px-3 py-2
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           shadow-sm">

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    @else

                    {{-- SIN MONITORES --}}

                    <div class="py-8 text-center">

                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9 17h6m-7 4h8m-9-4h10a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />

                        </svg>

                        <p class="text-xs italic text-slate-400">
                            No hay monitores registrados.
                        </p>

                        @if(!$agentManaged)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-5 text-left">
                                @foreach(['brand' => 'Marca', 'model' => 'Modelo', 'serial_number' => 'Serial', 'manufacturer_code' => 'Código fabricante', 'size' => 'Tamaño (pulgadas)', 'resolution' => 'Resolución', 'refresh_rate' => 'Hz', 'fixed_asset_code' => 'Activo fijo'] as $monitorField => $monitorLabel)
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">{{ $monitorLabel }}</label>
                                        <input type="{{ in_array($monitorField, ['size', 'refresh_rate']) ? 'number' : 'text' }}"
                                            name="monitors[0][{{ $monitorField }}]"
                                            value="{{ old('monitors.0.' . $monitorField) }}"
                                            @if(in_array($monitorField, ['size', 'refresh_rate'])) min="0" @endif
                                            class="w-full text-xs rounded-lg px-3 py-2 {{ $editableClass }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    @endif

                </div>
                {{-- =================================================
     11. SISTEMA OPERATIVO
================================================= --}}

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">

                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">

                        <svg class="w-5 h-5 text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" />

                        </svg>

                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                            11. Sistema Operativo
                        </h3>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">


                        {{-- WINDOWS --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Sistema operativo
                            </label>

                            <input
                                type="text"
                                name="os_version"
                                value="{{ old('os_version', $asset->os_version) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
                    {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>

                        </div>


                        {{-- VERSIÓN WINDOWS --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Versión Windows
                            </label>

                            <input
                                type="text"
                                name="windows_version"
                                value="{{ old('windows_version', $asset->windows_version) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
                    {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>

                        </div>


                        {{-- BUILD --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Build
                            </label>

                            <input
                                type="text"
                                name="windows_build"
                                value="{{ old('windows_build', $asset->windows_build) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
                    {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>

                        </div>


                        {{-- EDICIÓN --}}

                        <div>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">
                                Edición
                            </label>

                            <input
                                type="text"
                                name="windows_edition"
                                value="{{ old('windows_edition', $asset->windows_edition) }}"
                                class="w-full text-xs rounded-lg px-3 py-2
                    {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                {{ $agentManaged ? 'readonly' : '' }}>

                        </div>

                    </div>

                </div>

                {{-- =================================================
                     11. SEGURIDAD
                ================================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-4">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                            Seguridad
                        </h3>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach([
                            'license_status' => ['Estado de licencia', 'text'],
                            'bitlocker_status' => ['BitLocker', 'text'],
                            'tpm_version' => ['TPM', 'text'],
                            'antivirus' => ['Antivirus', 'text'],
                            'uptime_minutes' => ['Tiempo activo (minutos)', 'number'],
                        ] as $securityName => [$securityLabel, $securityType])
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">{{ $securityLabel }}</label>
                                <input type="{{ $securityType }}" name="{{ $securityName }}"
                                    value="{{ old($securityName, $asset->{$securityName}) }}"
                                    @if($securityType === 'number') min="0" @endif
                                    class="w-full text-xs rounded-lg px-3 py-2 {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                    {{ $agentManaged ? 'readonly' : '' }}>
                            </div>
                        @endforeach
                        @foreach(['secure_boot' => 'Arranque seguro', 'firewall_enabled' => 'Firewall'] as $securityName => $securityLabel)
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">{{ $securityLabel }}</label>
                                <select name="{{ $securityName }}"
                                    class="w-full text-xs rounded-lg px-3 py-2 {{ $agentManaged ? $readonlyClass : $editableClass }}"
                                    {{ $agentManaged ? 'disabled' : '' }}>
                                    <option value="">Sin especificar</option>
                                    <option value="1" @selected(old($securityName, $asset->{$securityName}) === true || old($securityName, $asset->{$securityName}) === 1 || old($securityName, $asset->{$securityName}) === '1')>Sí</option>
                                    <option value="0" @selected(old($securityName, $asset->{$securityName}) === false || old($securityName, $asset->{$securityName}) === 0 || old($securityName, $asset->{$securityName}) === '0')>No</option>
                                </select>
                                @if($agentManaged)
                                    <input type="hidden" name="{{ $securityName }}" value="{{ $asset->{$securityName} ? '1' : '0' }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

{{-- =================================================
     12. SOFTWARE
================================================== --}}

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6">

    {{-- ================================================
         ENCABEZADO
    ================================================= --}}

    <div class="p-4 bg-gray-50/75 border-b border-gray-200
                flex flex-col sm:flex-row justify-between
                items-start sm:items-center gap-3">

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

                <h3 class="font-bold text-gray-800 uppercase
                           tracking-wider text-xs">

                    12. Software Instalado

                </h3>

                <p class="text-[10px] text-gray-400 font-medium">

                    Aplicaciones detectadas por SIGMA Agent.
                    Seleccione cuáles están autorizadas.

                </p>

            </div>

        </div>


        {{-- ============================================
             CONTADOR
        ============================================= --}}

        <div class="flex items-center gap-3 w-full sm:w-auto">

            <div class="relative w-full sm:w-64">

                <div class="absolute inset-y-0 left-0
                            flex items-center pl-3
                            pointer-events-none">

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
                    class="w-full bg-white pl-8 p-1.5
                           border border-gray-200 rounded-lg
                           text-xs font-medium
                           focus:ring-indigo-500
                           focus:border-indigo-500
                           text-gray-700
                           outline-none
                           shadow-sm">

            </div>


            {{-- CONTADOR DINÁMICO --}}

            <span id="softwareAuthorizedCounter"
                class="text-[10px]
                       bg-indigo-50
                       text-indigo-700
                       px-2.5 py-1
                       rounded-md
                       font-bold
                       uppercase
                       shrink-0
                       border
                       border-indigo-100/50">

                {{ $asset->software->where('is_active', true)->count() }}
                autorizadas

            </span>

        </div>

    </div>


    {{-- ================================================
         TABLA
    ================================================= --}}

    <div class="overflow-x-auto max-h-[340px]
                overflow-y-auto bg-white"
        id="softwareTableContainer">

        <table class="w-full text-left text-xs border-collapse">

            <thead class="bg-gray-50 text-gray-500
                          uppercase tracking-wider
                          sticky top-0 z-10
                          border-b border-gray-200">

                <tr>

                    {{-- SOFTWARE --}}

                    <th class="px-4 py-3 font-bold text-gray-700">

                        Nombre de la Aplicación

                    </th>


                    {{-- VERSION --}}

                    <th class="px-4 py-3 font-bold
                               text-gray-700 text-center w-32">

                        Versión

                    </th>


                    {{-- FECHA --}}

                    <th class="px-4 py-3 font-bold
                               text-gray-700 text-center w-36">

                        Fecha Instalación

                    </th>


                    {{-- AUTORIZACIÓN --}}

                    <th class="px-4 py-3 font-bold
                               text-gray-700 text-center w-32">

                        Autorizado

                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100 text-gray-600"
                id="softwareTableBody">


                {{-- ========================================
                     MOSTRAR TODO EL SOFTWARE
                ========================================= --}}

                @forelse($asset->software as $app)


                    <tr class="software-row
                               hover:bg-gray-50/70
                               transition-colors"
                        data-software-name="{{ strtolower($app->name) }}"
                        data-software-version="{{ strtolower($app->version ?? '') }}">


                        {{-- =================================
                             NOMBRE
                        ================================== --}}

                        <td class="px-4 py-2
                                   font-medium
                                   text-gray-800
                                   software-name">

                            {{ $app->name }}

                        </td>


                        {{-- =================================
                             VERSION
                        ================================== --}}

                        <td class="px-4 py-2
                                   text-center
                                   font-mono
                                   text-[11px]
                                   text-gray-500">

                            {{ $app->version ?? 'N/A' }}

                        </td>


                        {{-- =================================
                             FECHA DE INSTALACIÓN
                        ================================== --}}

                        <td class="px-4 py-2 text-center text-gray-400">
    {{ $app->install_date
        ? \Carbon\Carbon::parse($app->install_date)->format('d/m/Y')
        : 'N/A'
    }}
</td>

{{-- =================================
     AUTORIZACIÓN
================================== --}}

<td class="px-4 py-2 text-center">

    <div class="flex items-center justify-center gap-2">

        <label class="relative inline-flex items-center cursor-pointer select-none">

            {{-- Valor cuando NO está autorizado --}}
            <input
                type="hidden"
                name="software[{{ $app->id }}][is_active]"
                value="0"
            >

            {{-- Valor cuando SÍ está autorizado --}}
            <input
                type="checkbox"
                name="software[{{ $app->id }}][is_active]"
                value="1"
                class="software-authorized sr-only"
                data-software-id="{{ $app->id }}"
                {{ $app->is_active ? 'checked' : '' }}
            >

            {{-- SWITCH --}}
            <span
                class="software-switch
                       relative
                       block
                       w-10
                       h-5
                       rounded-full
                       bg-gray-300
                       border
                       border-gray-300
                       transition-all
                       duration-200">

                {{-- CÍRCULO --}}
                <span
                    class="software-switch-thumb
                           absolute
                           top-[2px]
                           left-[2px]
                           w-4
                           h-4
                           bg-white
                           rounded-full
                           shadow-sm
                           transition-transform
                           duration-200">
                </span>

            </span>

        </label>

        {{-- ESTADO --}}
        <span
            class="software-status text-[10px] font-bold">

            {{ $app->is_active ? 'SI' : 'NO' }}

        </span>

    </div>

</td>

                @empty


                    <tr>

                        <td colspan="4"
                            class="px-4 py-12
                                   text-center
                                   text-gray-400
                                   italic
                                   bg-gray-50/20">

                            No hay aplicaciones registradas
                            en este equipo.

                        </td>

                    </tr>


                @endforelse


                {{-- ========================================
                     SIN RESULTADOS DE BÚSQUEDA
                ========================================= --}}

                <tr id="noSoftwareResults"
                    class="hidden">

                    <td colspan="4"
                        class="px-4 py-8
                               text-center
                               text-xs
                               font-bold
                               text-amber-600
                               bg-amber-50/20">

                         No se encontraron aplicaciones
                        que coincidan con la búsqueda.

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
    <div
        id="save-messages"
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"></div>

    <script>
        /*
        |--------------------------------------------------------------------------
        | CAMPOS TÉCNICOS DE ACTIVOS MANUALES
        |--------------------------------------------------------------------------
        | La vista de Agent conserva las mismas tablas como lectura. En activos
        | manuales convertimos sus celdas de inventario en controles del formulario,
        | conservando la presentación y enviando las estructuras que espera update().
        */
        document.addEventListener('DOMContentLoaded', () => {
            const agentManaged = document.getElementById('edit-asset-form')?.dataset.agentManaged === '1';
            if (agentManaged) return;

            const editableClass = 'bg-white border border-slate-300 text-slate-900 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 shadow-sm';
            const textValue = (value) => {
                const normalized = (value || '').replace(/\s+/g, ' ').trim();
                if (!normalized || normalized === 'N/A') return '';
                return normalized.replace(/\s*(GB|MB|MHz|Hz|mWh|min|%)$/i, '').replace(/"$/, '').trim();
            };
            const input = (name, value, type = 'text') => {
                const element = document.createElement('input');
                element.type = type;
                element.name = name;
                element.value = textValue(value);
                if (type === 'number') element.min = '0';
                element.className = `w-full min-w-[88px] text-xs rounded-lg px-2 py-1.5 ${editableClass}`;
                return element;
            };
            const selectBoolean = (name, value) => {
                const element = document.createElement('select');
                element.name = name;
                element.className = `w-full min-w-[88px] text-xs rounded-lg px-2 py-1.5 ${editableClass}`;
                [['', '—'], ['1', 'Sí'], ['0', 'No']].forEach(([optionValue, label]) => {
                    const option = new Option(label, optionValue);
                    const normalized = String(value || '').toLowerCase();
                    option.selected = optionValue === '1'
                        ? ['sí', 'si', 'conectado', 'habilitado'].includes(normalized)
                        : optionValue === '0' && ['no', 'desconectado', 'deshabilitado'].includes(normalized);
                    element.add(option);
                });
                return element;
            };
            const card = (prefix) => [...document.querySelectorAll('h3')]
                .find((heading) => heading.textContent.trim().startsWith(prefix))?.closest('.bg-white');
            const makeLabeledInputs = (prefix, mapping) => {
                const root = card(prefix);
                if (!root) return;
                root.querySelectorAll('label').forEach((label) => {
                    const name = mapping[label.textContent.replace(/\s+/g, ' ').trim()];
                    const control = label.parentElement?.querySelector('input[readonly]');
                    if (!name || !control) return;
                    control.name = name;
                    control.value = textValue(control.value);
                    control.readOnly = false;
                    control.className = `w-full text-xs rounded-lg px-3 py-2 ${editableClass}`;
                });
            };

            makeLabeledInputs('3. Motherboard', {
                'Fabricante': 'board_brand', 'Modelo': 'board_model', 'Serial': 'board_serial',
                'Versión': 'board_version', 'Estado': 'board_status',
                'Fabricante BIOS': 'bios_manufacturer', 'Versión BIOS': 'bios_version',
                'Serial BIOS': 'bios_serial', 'Fecha de lanzamiento': 'bios_release_date'
            });
            makeLabeledInputs('4. Procesador', {
                'Marca': 'processor[brand]', 'Modelo': 'processor[model]', 'Núcleos': 'processor[cores]',
                'Hilos': 'processor[threads]', 'Velocidad': 'processor[speed_mhz]',
                'Arquitectura': 'processor[architecture]'
            });

            const editTable = (prefix, collection, fields, numericFields = [], booleanFields = []) => {
                const root = card(prefix);
                const body = root?.querySelector('tbody');
                if (!body) return;
                let rows = [...body.querySelectorAll('tr')].filter((row) => row.children.length === fields.length);
                if (!rows.length) {
                    body.innerHTML = '';
                    const row = document.createElement('tr');
                    fields.forEach(() => row.appendChild(document.createElement('td')).className = 'px-3 py-2');
                    body.appendChild(row);
                    rows = [row];
                }
                rows.forEach((row, rowIndex) => {
                    [...row.children].slice(0, fields.length).forEach((cell, index) => {
                        const field = fields[index];
                        const value = cell.querySelector('input, select')?.value ?? cell.textContent;
                        cell.textContent = '';
                        cell.appendChild(booleanFields.includes(field)
                            ? selectBoolean(`${collection}[${rowIndex}][${field}]`, value)
                            : input(`${collection}[${rowIndex}][${field}]`, value, numericFields.includes(field) ? 'number' : 'text'));
                    });
                });
            };
            editTable('5. Memoria RAM', 'ram_modules', ['manufacturer', 'model', 'slot', 'memory_type', 'capacity_gb', 'speed_mhz'], ['capacity_gb', 'speed_mhz']);
            editTable('6. Almacenamiento', 'storage_devices', ['brand', 'model', 'serial_number', 'type', 'capacity_gb', 'free_gb', 'health'], ['capacity_gb', 'free_gb']);
            editTable('7. Procesadores Gráficos', 'gpus', ['brand', 'model', 'memory_mb', 'driver_version', 'processor', 'resolution', 'refresh_rate'], ['memory_mb', 'refresh_rate']);
            editTable('10. Monitores', 'monitors', ['brand', 'model', 'serial_number', 'manufacturer_code', 'size', 'resolution', 'refresh_rate', 'fixed_asset_code'], ['size', 'refresh_rate']);

            makeLabeledInputs('8. Conectividad', {
                'Gateway': 'network[gateway]', 'Servidor DNS': 'network[dns_server]',
                'Adaptador': 'network[adapters][0][name]', 'Descripción': 'network[adapters][0][description]',
                'Tipo': 'network[adapters][0][type]', 'MAC': 'network[adapters][0][mac_address]',
                'IPv4': 'network[adapters][0][ipv4]', 'IPv6': 'network[adapters][0][ipv6]'
            });
            const networkRoot = card('8. Conectividad');
            networkRoot?.querySelectorAll('label').forEach((label) => {
                const visible = label.parentElement?.querySelector('input[readonly]');
                const text = label.textContent.trim();
                if (visible && text === 'DHCP') {
                    const replacement = selectBoolean('network[dhcp_enabled]', visible.value);
                    visible.replaceWith(replacement);
                }
            });
            editTable('8. Conectividad', 'network[adapters]', ['name', 'type', 'mac_address', 'ipv4', 'ipv6', 'connected', 'is_primary'], [], ['connected', 'is_primary']);
            makeLabeledInputs('9. Batería', {
                'Fabricante': 'battery[manufacturer]', 'Modelo': 'battery[model]', 'Serial': 'battery[serial_number]',
                'Estado': 'battery[health]', 'Ciclos': 'battery[cycle_count]', 'Carga': 'battery[charge_percent]',
                'Autonomía estimada': 'battery[estimated_runtime_minutes]', 'Capacidad de diseño': 'battery[design_capacity]',
                'Capacidad de carga completa': 'battery[full_charge_capacity]', 'Capacidad restante': 'battery[remaining_capacity]'
            });
            const batteryRoot = card('9. Batería');
            batteryRoot?.querySelectorAll('label').forEach((label) => {
                if (label.textContent.trim() !== 'Cargando') return;
                const visible = label.parentElement?.querySelector('input[readonly]');
                if (visible) visible.replaceWith(selectBoolean('battery[is_charging]', visible.value));
            });
        });

        /*
    |--------------------------------------------------------------------------
    | BUSCADOR DE SOFTWARE
    |--------------------------------------------------------------------------
    */
        const softwareSearch = document.getElementById('softwareSearch');

        if (softwareSearch) {
            softwareSearch.addEventListener('input', function(e) {

                const term = e.target.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.software-row');

                let visibles = 0;

                rows.forEach(row => {

                    const nameElement = row.querySelector('.software-name');

                    const name = nameElement?.textContent.toLowerCase() ?? '';
                    const version = row.children[1]?.textContent.toLowerCase() ?? '';

                    if (name.includes(term) || version.includes(term)) {

                        row.classList.remove('hidden');
                        visibles++;

                    } else {

                        row.classList.add('hidden');

                    }
                });

                const noResultsRow = document.getElementById('noSoftwareResults');

                if (noResultsRow && visibles === 0 && term.length > 0) {

                    noResultsRow.classList.remove('hidden');

                } else if (noResultsRow) {

                    noResultsRow.classList.add('hidden');

                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMACIÓN Y GUARDADO
        |--------------------------------------------------------------------------
        */
        function confirmSave() {

            Swal.fire({

                title: '¿Guardar cambios?',

                text: 'Se actualizará la información administrativa del expediente técnico.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonText: 'Sí, guardar',

                cancelButtonText: 'Cancelar',

                reverseButtons: true,

                allowOutsideClick: false,

                customClass: {

                    popup: 'rounded-2xl',

                    title: 'text-lg font-bold text-slate-800',

                    htmlContainer: 'text-sm text-slate-500',

                    confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase',

                    cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'
                }

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                const form = document.getElementById('edit-asset-form');

                const saveButton = form?.querySelector(
                    'button[onclick="confirmSave()"]'
                );

                /*
                |--------------------------------------------------------------------------
                | DESHABILITAR BOTÓN
                |--------------------------------------------------------------------------
                */

                if (saveButton) {

                    saveButton.disabled = true;

                    saveButton.classList.add(
                        'opacity-60',
                        'cursor-not-allowed'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | ANIMACIÓN DE GUARDADO
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    title: 'Guardando expediente...',

                    html: `
                    <div class="flex flex-col items-center justify-center gap-3">

                        <div class="text-sm text-slate-500">
                            Actualizando información del activo
                        </div>

                        <div class="text-xs text-slate-400">
                            Por favor, espere...
                        </div>

                    </div>
                `,

                    allowOutsideClick: false,

                    allowEscapeKey: false,

                    showConfirmButton: false,

                    didOpen: () => {

                        Swal.showLoading();

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | ENVIAR FORMULARIO
                |--------------------------------------------------------------------------
                */

                form.submit();

            });
        }




        const saveMessages = document.getElementById('save-messages');

        const saveSuccess = saveMessages?.dataset.success || '';
        const saveError = saveMessages?.dataset.error || '';

        /*
        |--------------------------------------------------------------------------
        | MENSAJE DE ÉXITO
        |--------------------------------------------------------------------------
        */

        if (saveSuccess) {

            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({

                    icon: 'success',

                    title: '¡Guardado correctamente!',

                    text: saveSuccess,

                    confirmButtonText: 'Aceptar',

                    timer: 2500,

                    timerProgressBar: true,

                    customClass: {

                        popup: 'rounded-2xl',

                        title: 'text-lg font-bold text-slate-800',

                        htmlContainer: 'text-sm text-slate-500',

                        confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'
                    }

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | MENSAJE DE ERROR
        |--------------------------------------------------------------------------
        */

        if (saveError) {

            document.addEventListener('DOMContentLoaded', function() {

                Swal.fire({

                    icon: 'error',

                    title: 'No se pudo guardar',

                    text: saveError,

                    confirmButtonText: 'Aceptar',

                    customClass: {

                        popup: 'rounded-2xl',

                        title: 'text-lg font-bold text-slate-800',

                        htmlContainer: 'text-sm text-slate-500',

                        confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'
                    }

                });

            });
        }
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {

    const softwareCheckboxes = document.querySelectorAll('.software-authorized');
    const counter = document.getElementById('softwareAuthorizedCounter');

    function updateSoftwareSwitch(checkbox) {

        const row = checkbox.closest('.software-row');

        if (!row) {
            return;
        }

        const switchElement = row.querySelector('.software-switch');
        const thumb = row.querySelector('.software-switch-thumb');
        const status = row.querySelector('.software-status');

        if (!switchElement || !thumb || !status) {
            return;
        }

        if (checkbox.checked) {

            // SWITCH ACTIVADO
            switchElement.classList.remove(
                'bg-gray-300',
                'border-gray-300'
            );

            switchElement.classList.add(
                'bg-indigo-600',
                'border-indigo-600'
            );

            thumb.classList.add('translate-x-5');

            status.textContent = 'SI';

            status.classList.remove('text-red-500');
            status.classList.add('text-green-600');

        } else {

            // SWITCH DESACTIVADO
            switchElement.classList.remove(
                'bg-indigo-600',
                'border-indigo-600'
            );

            switchElement.classList.add(
                'bg-gray-300',
                'border-gray-300'
            );

            thumb.classList.remove('translate-x-5');

            status.textContent = 'NO';

            status.classList.remove('text-green-600');
            status.classList.add('text-red-500');
        }
    }


    function updateSoftwareCounter() {

        if (!counter) {
            return;
        }

        const authorized = document.querySelectorAll(
            '.software-authorized:checked'
        ).length;

        counter.textContent = authorized + ' AUTORIZADAS';
    }


    // Estado inicial
    softwareCheckboxes.forEach(function (checkbox) {

        updateSoftwareSwitch(checkbox);

    });


    // Cuando cambia un software
    softwareCheckboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            updateSoftwareSwitch(this);

            updateSoftwareCounter();

        });

    });

});
</script>

</x-app-layout>
