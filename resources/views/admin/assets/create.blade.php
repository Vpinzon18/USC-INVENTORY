<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- =====================================================
                 CONTENEDOR PRINCIPAL
            ====================================================== --}}

            <div class="bg-slate-50 rounded-2xl shadow-sm border border-slate-200 overflow-hidden">


                {{-- =================================================
                     CABECERA
                ================================================== --}}

                <div class="p-8 border-b border-slate-200 bg-white">

                    <div class="flex justify-between items-center">

                        <div>

                            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">

                                Registrar Nueva Hoja de Vida

                            </h2>

                            <p class="text-sm text-slate-500 mt-1 font-medium">

                                Complete la información técnica, de hardware y administrativa del nuevo activo.

                            </p>

                        </div>


                        <div class="hidden md:flex items-center justify-center
                                    w-12 h-12 bg-blue-50 text-blue-600 rounded-xl">

                            <svg class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4" />

                            </svg>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     FORMULARIO
                ================================================== --}}

                <form id="create-asset-form"
                    action="{{ route('assets.store') }}"
                    method="POST"
                    class="p-6 md:p-8 space-y-6">

                    @csrf


                    {{-- =================================================
                         ERRORES
                    ================================================== --}}

                    @if ($errors->any())

                        <div class="mb-6 p-4 bg-red-50 border-l-4
                                    border-red-500 rounded-r-xl shadow-sm">

                            <div class="flex items-center mb-2">

                                <svg class="w-5 h-5 text-red-500 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01
                                           M21 12a9 9 0 11-18 0
                                           9 9 0 0118 0z"/>

                                </svg>

                                <h3 class="text-sm font-bold text-red-800">

                                    No se pudo registrar el equipo.
                                    Por favor, corrige lo siguiente:

                                </h3>

                            </div>


                            <ul class="list-disc list-inside text-xs
                                       text-red-700 ml-7 space-y-1 font-medium">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif



                    {{-- =================================================
                         1. INFORMACIÓN GENERAL
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center gap-2 mb-4
                                    pb-2 border-b border-slate-100">

                            <svg class="w-5 h-5 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01
                                       M12 18h.01
                                       M21 12a9 9 0 11-18 0
                                       9 9 0 0118 0z"/>

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700
                                       uppercase tracking-wider">

                                1. Información General

                            </h3>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2
                                    lg:grid-cols-4 gap-4">


                            {{-- SERIAL --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Serial
                                    <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="serial_number"
                                    value="{{ old('serial_number') }}"
                                    required
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- CÓDIGO INTERNO --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Código interno

                                </label>

                                <input
                                    type="text"
                                    name="internal_code"
                                    value="{{ old('internal_code') }}"
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- HOSTNAME --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Hostname

                                </label>

                                <input
                                    type="text"
                                    name="hostname"
                                    value="{{ old('hostname') }}"
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- FABRICANTE --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Fabricante

                                </label>

                                <input
                                    type="text"
                                    name="manufacturer"
                                    value="{{ old('manufacturer') }}"
                                    placeholder="Dell / HP / Lenovo..."
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- MODELO --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Modelo

                                </label>

                                <input
                                    type="text"
                                    name="model_version"
                                    value="{{ old('model_version') }}"
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- SISTEMA OPERATIVO --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Sistema operativo

                                </label>

                                <input
                                    type="text"
                                    name="os_version"
                                    value="{{ old('os_version') }}"
                                    placeholder="Windows 11"
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- DOMINIO --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Dominio

                                </label>

                                <input
                                    type="text"
                                    name="domain_name"
                                    value="{{ old('domain_name') }}"
                                    placeholder="WORKGROUP / DOMINIO"
                                    class="w-full bg-white border-slate-300
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-100
                                           focus:border-blue-500
                                           px-3 py-2 shadow-sm">

                            </div>


                            {{-- CHASSIS --}}

                         

                        </div>

                    </div>



                    {{-- =================================================
                         2. UBICACIÓN Y ASIGNACIÓN
                    ================================================== --}}

                    <div class="bg-blue-50/50 rounded-2xl border border-blue-100
                                shadow-sm p-5">

                        <div class="flex items-center gap-2 mb-4
                                    pb-2 border-b border-blue-100">

                            <svg class="w-5 h-5 text-amber-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9
                                       a1.998 1.998 0 01-2.827 0
                                       l-4.244-4.243a8 8 0 1111.314 0z"/>

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0
                                       3 3 0 016 0z"/>

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700
                                       uppercase tracking-wider">

                                2. Ubicación y Asignación

                            </h3>

                        </div>


                        <div class="mb-5 p-3.5 bg-blue-50
                                    border border-blue-100 rounded-xl
                                    flex items-start gap-3 shadow-inner">

                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01
                                       M21 12a9 9 0 11-18 0
                                       9 9 0 0118 0z"/>

                            </svg>

                            <div>

                                <h4 class="text-[10px] font-bold text-blue-900
                                           uppercase tracking-widest mb-0.5">

                                    Gestión de Traslados y Asignaciones

                                </h4>

                                <p class="text-[11px] text-blue-700
                                          leading-relaxed">

                                    La ubicación inicial se registra durante
                                    la creación del activo. Los cambios
                                    posteriores deberán gestionarse desde
                                    el módulo de Movimientos de Activos.

                                </p>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                            {{-- CUSTODIO --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-blue-900 uppercase mb-1">

                                    Responsable / Custodio
                                    <span class="text-red-500">*</span>

                                </label>

                                <select
                                    name="custodian_id"
                                    id="custodian_id"
                                    required
                                    class="w-full bg-white border-blue-200
                                           text-slate-900 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-500
                                           px-3 py-2 shadow-sm">

                                    <option value="">
                                        1. Seleccione responsable...
                                    </option>

                                    @foreach($custodians as $custodian)

                                        <option
                                            value="{{ $custodian->id }}"
                                            data-rooms="{{ $custodian->rooms }}"
                                            {{ old('custodian_id') == $custodian->id ? 'selected' : '' }}>

                                            {{ $custodian->full_name }}

                                            @if($custodian->job_title)
                                                ({{ $custodian->job_title }})
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('custodian_id')

                                    <p class="text-red-500 text-xs mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- SALÓN --}}

                            <div>

                                <label class="block text-[10px] font-bold
                                              text-blue-900 uppercase mb-1">

                                    Oficina / Salón
                                    <span class="text-red-500">*</span>

                                </label>

                                <select
                                    name="room_id"
                                    id="room_id"
                                    required
                                    disabled
                                    class="w-full bg-slate-50
                                           border-slate-200
                                           text-slate-500 text-xs rounded-lg
                                           focus:ring-2 focus:ring-blue-500
                                           px-3 py-2 shadow-sm
                                           cursor-not-allowed transition-all">

                                    <option value="">
                                        2. Esperando al responsable...
                                    </option>

                                </select>

                                @error('room_id')

                                    <p class="text-red-500 text-xs mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         3. PROCESADOR
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center gap-2 mb-4
                                    pb-2 border-b border-slate-100">

                            <svg class="w-5 h-5 text-emerald-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2
                                       M5 9H3m2 6H3m18-6h-2m2 6h-2
                                       M7 5h10a2 2 0 012 2v10a2 2
                                       0 01-2 2H7a2 2 0 01-2-2V7
                                       a2 2 0 012-2z"/>

                            </svg>

                            <h3 class="text-xs font-extrabold text-slate-700
                                       uppercase tracking-wider">

                                3. Procesador

                            </h3>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2
                                    lg:grid-cols-4 gap-4">


                            <div>

                                <label class="field-label">
                                    Fabricante
                                </label>

                                <input
                                    type="text"
                                    name="processor[brand]"
                                    value="{{ old('processor.brand') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Modelo
                                </label>

                                <input
                                    type="text"
                                    name="processor[model]"
                                    value="{{ old('processor.model') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Socket
                                </label>

                                <input
                                    type="text"
                                    name="processor[socket]"
                                    value="{{ old('processor.socket') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Velocidad MHz
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="processor[speed_mhz]"
                                    value="{{ old('processor.speed_mhz') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Núcleos
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="processor[cores]"
                                    value="{{ old('processor.cores') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Hilos
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="processor[threads]"
                                    value="{{ old('processor.threads') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Arquitectura
                                </label>

                                <input
                                    type="text"
                                    name="processor[architecture]"
                                    value="{{ old('processor.architecture') }}"
                                    placeholder="x64"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Familia
                                </label>

                                <input
                                    type="text"
                                    name="processor[family]"
                                    value="{{ old('processor.family') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="field-label">
                                    Generación
                                </label>

                                <input
                                    type="text"
                                    name="processor[generation]"
                                    value="{{ old('processor.generation') }}"
                                    class="field-input">

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         4. MEMORIA RAM
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-indigo-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6
                                           a2 2 0 01-2 2H5a2 2 0 01-2-2
                                           v-6a2 2 0 012-2m14 0V9a2 2
                                           0 00-2-2M5 11V9a2 2 0 012-2
                                           m0 0V5a2 2 0 012-2h6a2 2
                                           0 012 2v2M7 7h10"/>

                                </svg>

                                <h3 class="text-xs font-extrabold
                                           text-slate-700 uppercase
                                           tracking-wider">

                                    4. Memoria RAM

                                </h3>

                            </div>


                            <button
                                type="button"
                                onclick="addRamModule()"
                                class="text-[9px] bg-indigo-50
                                       text-indigo-600 px-3 py-1.5
                                       rounded-lg font-bold uppercase
                                       hover:bg-indigo-100 transition">

                                + Agregar módulo

                            </button>

                        </div>


                        <div id="ram-container" class="space-y-4">


                            <div class="ram-item bg-slate-50/50
                                        border border-slate-100
                                        rounded-xl p-4">

                                <div class="flex justify-between
                                            items-center mb-3">

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 uppercase">

                                        Módulo 1

                                    </span>

                                </div>


                                <div class="grid grid-cols-2 md:grid-cols-4
                                            gap-4">


                                    <div>

                                        <label class="field-label">
                                            Fabricante
                                        </label>

                                        <input
                                            type="text"
                                            name="ram_modules[0][manufacturer]"
                                            value="{{ old('ram_modules.0.manufacturer') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Modelo
                                        </label>

                                        <input
                                            type="text"
                                            name="ram_modules[0][model]"
                                            value="{{ old('ram_modules.0.model') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Serial
                                        </label>

                                        <input
                                            type="text"
                                            name="ram_modules[0][serial_number]"
                                            value="{{ old('ram_modules.0.serial_number') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Banco
                                        </label>

                                        <input
                                            type="text"
                                            name="ram_modules[0][bank]"
                                            value="{{ old('ram_modules.0.bank') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Slot
                                        </label>

                                        <input
                                            type="text"
                                            name="ram_modules[0][slot]"
                                            value="{{ old('ram_modules.0.slot') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Tipo
                                        </label>

                                        <input
                                            type="text"
                                            name="ram_modules[0][memory_type]"
                                            value="{{ old('ram_modules.0.memory_type') }}"
                                            placeholder="DDR4"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Capacidad GB
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="ram_modules[0][capacity_gb]"
                                            value="{{ old('ram_modules.0.capacity_gb') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Velocidad MHz
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="ram_modules[0][speed_mhz]"
                                            value="{{ old('ram_modules.0.speed_mhz') }}"
                                            class="field-input">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         5. ALMACENAMIENTO
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-rose-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4
                                           8 4s8-1.79 8-4V7
                                           M4 7c0 2.21 3.582 4
                                           8 4s8-1.79 8-4
                                           M4 7c0-2.21 3.582-4
                                           8-4s8 1.79 8 4
                                           m0 5c0 2.21-3.582 4
                                           -8 4s-8-1.79-8-4"/>

                                </svg>

                                <h3 class="text-xs font-extrabold
                                           text-slate-700 uppercase
                                           tracking-wider">

                                    5. Almacenamiento

                                </h3>

                            </div>


                            <button
                                type="button"
                                onclick="addStorage()"
                                class="text-[9px] bg-rose-50
                                       text-rose-600 px-3 py-1.5
                                       rounded-lg font-bold uppercase
                                       hover:bg-rose-100 transition">

                                + Agregar unidad

                            </button>

                        </div>


                        <div id="storage-container"
                             class="space-y-4">


                            <div class="storage-item
                                        bg-slate-50/50
                                        border border-slate-100
                                        rounded-xl p-4">

                                <div class="flex justify-between
                                            items-center mb-3">

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 uppercase">

                                        Unidad 1

                                    </span>

                                </div>


                                <div class="grid grid-cols-2 md:grid-cols-4
                                            gap-4">


                                    <div>

                                        <label class="field-label">
                                            Marca
                                        </label>

                                        <input
                                            type="text"
                                            name="storage_devices[0][brand]"
                                            value="{{ old('storage_devices.0.brand') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Modelo
                                        </label>

                                        <input
                                            type="text"
                                            name="storage_devices[0][model]"
                                            value="{{ old('storage_devices.0.model') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Serial
                                        </label>

                                        <input
                                            type="text"
                                            name="storage_devices[0][serial_number]"
                                            value="{{ old('storage_devices.0.serial_number') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Tipo
                                        </label>

                                        <input
                                            type="text"
                                            name="storage_devices[0][type]"
                                            value="{{ old('storage_devices.0.type') }}"
                                            placeholder="NVMe SSD"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Capacidad GB
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="storage_devices[0][capacity_gb]"
                                            value="{{ old('storage_devices.0.capacity_gb') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Libre GB
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="storage_devices[0][free_gb]"
                                            value="{{ old('storage_devices.0.free_gb') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Firmware
                                        </label>

                                        <input
                                            type="text"
                                            name="storage_devices[0][firmware]"
                                            value="{{ old('storage_devices.0.firmware') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Estado
                                        </label>

                                        <input
                                            type="text"
                                            name="storage_devices[0][health]"
                                            value="{{ old('storage_devices.0.health') }}"
                                            placeholder="Healthy"
                                            class="field-input">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         6. GPU
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-purple-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2
                                           M5 9H3m2 6H3m18-6h-2m2 6h-2
                                           M7 5h10a2 2 0 012 2v6a2 2
                                           0 01-2 2H7a2 2 0 01-2-2V7
                                           a2 2 0 012-2z"/>

                                </svg>

                                <h3 class="text-xs font-extrabold
                                           text-slate-700 uppercase
                                           tracking-wider">

                                    6. GPU

                                </h3>

                            </div>


                            <button
                                type="button"
                                onclick="addGpu()"
                                class="text-[9px] bg-purple-50
                                       text-purple-600 px-3 py-1.5
                                       rounded-lg font-bold uppercase
                                       hover:bg-purple-100 transition">

                                + Agregar GPU

                            </button>

                        </div>


                        <div id="gpu-container"
                             class="space-y-4">


                            <div class="gpu-item bg-slate-50/50
                                        border border-slate-100
                                        rounded-xl p-4">

                                <div class="flex justify-between
                                            items-center mb-3">

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 uppercase">

                                        GPU 1

                                    </span>

                                </div>


                                <div class="grid grid-cols-2 md:grid-cols-4
                                            gap-4">


                                    <div>
                                        <label class="field-label">
                                            Marca
                                        </label>

                                        <input
                                            type="text"
                                            name="gpus[0][brand]"
                                            value="{{ old('gpus.0.brand') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Modelo
                                        </label>

                                        <input
                                            type="text"
                                            name="gpus[0][model]"
                                            value="{{ old('gpus.0.model') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Memoria MB
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="gpus[0][memory_mb]"
                                            value="{{ old('gpus.0.memory_mb') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Driver
                                        </label>

                                        <input
                                            type="text"
                                            name="gpus[0][driver_version]"
                                            value="{{ old('gpus.0.driver_version') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Procesador
                                        </label>

                                        <input
                                            type="text"
                                            name="gpus[0][processor]"
                                            value="{{ old('gpus.0.processor') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Resolución
                                        </label>

                                        <input
                                            type="text"
                                            name="gpus[0][resolution]"
                                            value="{{ old('gpus.0.resolution') }}"
                                            placeholder="1920x1080"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Frecuencia Hz
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="gpus[0][refresh_rate]"
                                            value="{{ old('gpus.0.refresh_rate') }}"
                                            class="field-input">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         7. TARJETA MADRE Y BIOS
                    ================================================== --}}

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


                        {{-- TARJETA MADRE --}}

                        <div class="bg-white rounded-2xl
                                    border border-slate-200
                                    shadow-sm p-5">

                            <div class="flex items-center gap-2 mb-4
                                        pb-2 border-b border-slate-100">

                                <svg class="w-5 h-5 text-cyan-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16v12H4z
                                           M8 10h8v4H8z"/>

                                </svg>

                                <h3 class="text-xs font-extrabold
                                           text-slate-700 uppercase
                                           tracking-wider">

                                    7. Tarjeta Madre

                                </h3>

                            </div>


                            <div class="grid grid-cols-2 gap-4">


                                <div>
                                    <label class="field-label">
                                        Fabricante
                                    </label>

                                    <input
                                        type="text"
                                        name="board_brand"
                                        value="{{ old('board_brand') }}"
                                        class="field-input">
                                </div>


                                <div>
                                    <label class="field-label">
                                        Modelo
                                    </label>

                                    <input
                                        type="text"
                                        name="board_model"
                                        value="{{ old('board_model') }}"
                                        class="field-input">
                                </div>


                                <div>
                                    <label class="field-label">
                                        Serial
                                    </label>

                                    <input
                                        type="text"
                                        name="board_serial"
                                        value="{{ old('board_serial') }}"
                                        class="field-input">
                                </div>


                                <div>
                                    <label class="field-label">
                                        Versión
                                    </label>

                                    <input
                                        type="text"
                                        name="board_version"
                                        value="{{ old('board_version') }}"
                                        class="field-input">
                                </div>


                                <div class="col-span-2">
                                    <label class="field-label">
                                        Estado
                                    </label>

                                    <input
                                        type="text"
                                        name="board_status"
                                        value="{{ old('board_status') }}"
                                        placeholder="OK"
                                        class="field-input">
                                </div>

                            </div>

                        </div>



                        {{-- BIOS --}}

                        <div class="bg-white rounded-2xl
                                    border border-slate-200
                                    shadow-sm p-5">

                            <div class="flex items-center gap-2 mb-4
                                        pb-2 border-b border-slate-100">

                                <svg class="w-5 h-5 text-orange-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6V4m0 16v-2
                                           M6 12H4m16 0h-2
                                           M7.05 7.05L5.64 5.64
                                           m11.31 11.31l1.41 1.41
                                           M7.05 16.95l-1.41 1.41
                                           M16.95 7.05l1.41-1.41
                                           M12 16a4 4 0 100-8
                                           4 4 0 000 8z"/>

                                </svg>

                                <h3 class="text-xs font-extrabold
                                           text-slate-700 uppercase
                                           tracking-wider">

                                    BIOS

                                </h3>

                            </div>


                            <div class="grid grid-cols-2 gap-4">


                                <div>
                                    <label class="field-label">
                                        Fabricante
                                    </label>

                                    <input
                                        type="text"
                                        name="bios_manufacturer"
                                        value="{{ old('bios_manufacturer') }}"
                                        class="field-input">
                                </div>


                                <div>
                                    <label class="field-label">
                                        Versión
                                    </label>

                                    <input
                                        type="text"
                                        name="bios_version"
                                        value="{{ old('bios_version') }}"
                                        class="field-input">
                                </div>


                                <div>
                                    <label class="field-label">
                                        Serial
                                    </label>

                                    <input
                                        type="text"
                                        name="bios_serial"
                                        value="{{ old('bios_serial') }}"
                                        class="field-input">
                                </div>


                                <div>
                                    <label class="field-label">
                                        Fecha lanzamiento
                                    </label>

                                    <input
                                        type="date"
                                        name="bios_release_date"
                                        value="{{ old('bios_release_date') }}"
                                        class="field-input">
                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         8. SISTEMA OPERATIVO Y SEGURIDAD
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5">

                        <div class="flex items-center gap-2 mb-4
                                    pb-2 border-b border-slate-100">

                            <svg class="w-5 h-5 text-sky-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0
                                       002-2v-6a2 2 0 00-2-2V9
                                       a4 4 0 00-8 0v2a2 2 0
                                       00-2 2v6a2 2 0 002 2z"/>

                            </svg>

                            <h3 class="text-xs font-extrabold
                                       text-slate-700 uppercase
                                       tracking-wider">

                                8. Sistema Operativo y Seguridad

                            </h3>

                        </div>


                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">


                            <div>
                                <label class="field-label">
                                    Versión Windows
                                </label>

                                <input
                                    type="text"
                                    name="windows_version"
                                    value="{{ old('windows_version') }}"
                                    placeholder="Windows 11"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Build
                                </label>

                                <input
                                    type="text"
                                    name="windows_build"
                                    value="{{ old('windows_build') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Edición
                                </label>

                                <input
                                    type="text"
                                    name="windows_edition"
                                    value="{{ old('windows_edition') }}"
                                    placeholder="Pro"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Estado licencia
                                </label>

                                <input
                                    type="text"
                                    name="license_status"
                                    value="{{ old('license_status') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Secure Boot
                                </label>

                                <select
                                    name="secure_boot"
                                    class="field-input">

                                    <option value="">
                                        No especificado
                                    </option>

                                    <option value="1"
                                        {{ old('secure_boot') === '1' ? 'selected' : '' }}>
                                        Activado
                                    </option>

                                    <option value="0"
                                        {{ old('secure_boot') === '0' ? 'selected' : '' }}>
                                        Desactivado
                                    </option>

                                </select>
                            </div>


                            <div>
                                <label class="field-label">
                                    BitLocker
                                </label>

                                <input
                                    type="text"
                                    name="bitlocker_status"
                                    value="{{ old('bitlocker_status') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    TPM
                                </label>

                                <input
                                    type="text"
                                    name="tpm_version"
                                    value="{{ old('tpm_version') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Antivirus
                                </label>

                                <input
                                    type="text"
                                    name="antivirus"
                                    value="{{ old('antivirus') }}"
                                    placeholder="Windows Defender"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Firewall
                                </label>

                                <select
                                    name="firewall_enabled"
                                    class="field-input">

                                    <option value="">
                                        No especificado
                                    </option>

                                    <option value="1"
                                        {{ old('firewall_enabled') === '1' ? 'selected' : '' }}>
                                        Activado
                                    </option>

                                    <option value="0"
                                        {{ old('firewall_enabled') === '0' ? 'selected' : '' }}>
                                        Desactivado
                                    </option>

                                </select>
                            </div>


                            <div>
                                <label class="field-label">
                                    Uptime (minutos)
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="uptime_minutes"
                                    value="{{ old('uptime_minutes') }}"
                                    class="field-input">
                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         9. RED
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-blue-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h8m-8 4h5
                                           M4 7h16a1 1 0 011 1v8
                                           a1 1 0 01-1 1H4a1 1 0
                                           01-1-1V8a1 1 0 011-1z
                                           M7 20h10"/>

                                </svg>

                                <h3 class="text-xs font-extrabold
                                           text-slate-700 uppercase
                                           tracking-wider">

                                    9. Red y Adaptadores

                                </h3>

                            </div>


                            <button
                                type="button"
                                onclick="addNetworkAdapter()"
                                class="text-[9px] bg-blue-50
                                       text-blue-600 px-3 py-1.5
                                       rounded-lg font-bold uppercase
                                       hover:bg-blue-100 transition">

                                + Agregar adaptador

                            </button>

                        </div>


                        {{-- RED GENERAL --}}

                        <div class="grid grid-cols-1 md:grid-cols-3
                                    gap-4 mb-5">


                            <div>

                                <label class="field-label">
                                    Gateway
                                </label>

                                <input
                                    type="text"
                                    name="network[gateway]"
                                    value="{{ old('network.gateway') }}"
                                    placeholder="172.16.0.1"
                                    class="field-input font-mono">

                            </div>


                            <div>

                                <label class="field-label">
                                    DNS
                                </label>

                                <input
                                    type="text"
                                    name="network[dns_server]"
                                    value="{{ old('network.dns_server') }}"
                                    placeholder="172.16.43.100"
                                    class="field-input font-mono">

                            </div>


                            <div>

                                <label class="field-label">
                                    DHCP
                                </label>

                                <select
                                    name="network[dhcp_enabled]"
                                    class="field-input">

                                    <option value="1"
                                        {{ old('network.dhcp_enabled') !== '0' ? 'selected' : '' }}>
                                        Activado
                                    </option>

                                    <option value="0"
                                        {{ old('network.dhcp_enabled') === '0' ? 'selected' : '' }}>
                                        Desactivado
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- ADAPTADORES --}}

                        <div id="network-adapters-container"
                             class="space-y-4">


                            <div class="network-adapter-item
                                        border border-slate-100
                                        rounded-xl p-4
                                        bg-slate-50/50">

                                <div class="flex justify-between
                                            items-center mb-3">

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 uppercase">

                                        Adaptador 1

                                    </span>

                                </div>


                                <div class="grid grid-cols-2
                                            md:grid-cols-4 gap-4">


                                    <div>

                                        <label class="field-label">
                                            Nombre
                                        </label>

                                        <input
                                            type="text"
                                            name="network[adapters][0][name]"
                                            value="{{ old('network.adapters.0.name') }}"
                                            placeholder="Ethernet / Wi-Fi"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Descripción
                                        </label>

                                        <input
                                            type="text"
                                            name="network[adapters][0][description]"
                                            value="{{ old('network.adapters.0.description') }}"
                                            class="field-input">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Tipo
                                        </label>

                                        <select
                                            name="network[adapters][0][type]"
                                            class="field-input">

                                            <option value="">
                                                Tipo
                                            </option>

                                            <option value="Ethernet">
                                                Ethernet
                                            </option>

                                            <option value="Wi-Fi">
                                                Wi-Fi
                                            </option>

                                            <option value="Otro">
                                                Otro
                                            </option>

                                        </select>

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            MAC Address
                                        </label>

                                        <input
                                            type="text"
                                            name="network[adapters][0][mac_address]"
                                            value="{{ old('network.adapters.0.mac_address') }}"
                                            placeholder="00:00:00:00:00:00"
                                            class="field-input font-mono">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            IPv4
                                        </label>

                                        <input
                                            type="text"
                                            name="network[adapters][0][ipv4]"
                                            value="{{ old('network.adapters.0.ipv4') }}"
                                            placeholder="172.16.X.X"
                                            class="field-input font-mono">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            IPv6
                                        </label>

                                        <input
                                            type="text"
                                            name="network[adapters][0][ipv6]"
                                            value="{{ old('network.adapters.0.ipv6') }}"
                                            class="field-input font-mono">

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Conectado
                                        </label>

                                        <select
                                            name="network[adapters][0][connected]"
                                            class="field-input">

                                            <option value="1">
                                                Sí
                                            </option>

                                            <option value="0">
                                                No
                                            </option>

                                        </select>

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Adaptador principal
                                        </label>

                                        <select
                                            name="network[adapters][0][is_primary]"
                                            class="field-input">

                                            <option value="0">
                                                No
                                            </option>

                                            <option value="1">
                                                Sí
                                            </option>

                                        </select>

                                    </div>


                                    <div>

                                        <label class="field-label">
                                            Activo
                                        </label>

                                        <select
                                            name="network[adapters][0][is_active]"
                                            class="field-input">

                                            <option value="1">
                                                Activo
                                            </option>

                                            <option value="0">
                                                Inactivo
                                            </option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         10. BATERÍA
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-amber-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 7h11a2 2 0 012 2v6
                                           a2 2 0 01-2 2H6a2 2
                                           0 01-2-2V9a2 2 0 012-2z
                                           M21 10v4"/>

                                </svg>

                                <div>

                                    <h3 class="text-xs font-extrabold
                                               text-slate-700 uppercase
                                               tracking-wider">

                                        10. Batería

                                    </h3>

                                    <p class="text-[10px] text-slate-400
                                              font-medium mt-0.5">

                                        Información disponible principalmente
                                        para equipos portátiles.

                                    </p>

                                </div>

                            </div>


                            <span class="text-[9px] bg-slate-100
                                         text-slate-500 px-2 py-1
                                         rounded-md font-bold uppercase">

                                Opcional

                            </span>

                        </div>


                        <div class="mb-5 p-3.5 bg-slate-50
                                    border border-slate-200 rounded-xl
                                    flex items-start gap-3">

                            <svg class="w-5 h-5 text-slate-400
                                        shrink-0 mt-0.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01
                                       M12 18h.01
                                       M21 12a9 9 0 11-18 0
                                       9 9 0 0118 0z"/>

                            </svg>

                            <p class="text-[10px] text-slate-500
                                      leading-relaxed">

                                Los equipos Desktop normalmente no poseen
                                batería interna. En estos casos todos los
                                campos pueden dejarse vacíos.

                            </p>

                        </div>


                        <div class="grid grid-cols-2 md:grid-cols-4
                                    xl:grid-cols-6 gap-4">


                            <div>
                                <label class="field-label">
                                    Fabricante
                                </label>

                                <input
                                    type="text"
                                    name="battery[manufacturer]"
                                    value="{{ old('battery.manufacturer') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Modelo
                                </label>

                                <input
                                    type="text"
                                    name="battery[model]"
                                    value="{{ old('battery.model') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Serial
                                </label>

                                <input
                                    type="text"
                                    name="battery[serial_number]"
                                    value="{{ old('battery.serial_number') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Estado
                                </label>

                                <select
                                    name="battery[health]"
                                    class="field-input">

                                    <option value="">
                                        No especificado
                                    </option>

                                    <option value="AC Power">
                                        AC Power
                                    </option>

                                    <option value="Charging">
                                        Charging
                                    </option>

                                    <option value="Discharging">
                                        Discharging
                                    </option>

                                    <option value="Fully Charged">
                                        Fully Charged
                                    </option>

                                    <option value="Unknown">
                                        Unknown
                                    </option>

                                </select>
                            </div>


                            <div>
                                <label class="field-label">
                                    Ciclos
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="battery[cycle_count]"
                                    value="{{ old('battery.cycle_count') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Carga %
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    max="100"
                                    name="battery[charge_percent]"
                                    value="{{ old('battery.charge_percent') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Capacidad diseño
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="battery[design_capacity]"
                                    value="{{ old('battery.design_capacity') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Capacidad completa
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="battery[full_charge_capacity]"
                                    value="{{ old('battery.full_charge_capacity') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Capacidad restante
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="battery[remaining_capacity]"
                                    value="{{ old('battery.remaining_capacity') }}"
                                    class="field-input">
                            </div>


                            <div>
                                <label class="field-label">
                                    Cargando
                                </label>

                                <select
                                    name="battery[is_charging]"
                                    class="field-input">

                                    <option value="">
                                        No especificado
                                    </option>

                                    <option value="1">
                                        Sí
                                    </option>

                                    <option value="0">
                                        No
                                    </option>

                                </select>
                            </div>


                            <div>
                                <label class="field-label">
                                    Autonomía (min)
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    max="1440"
                                    name="battery[estimated_runtime_minutes]"
                                    value="{{ old('battery.estimated_runtime_minutes') }}"
                                    placeholder="Máx. 1440"
                                    class="field-input">
                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         11. MONITORES
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 17h6m-7 4h8
                                           a2 2 0 002-2V5a2 2 0
                                           00-2-2H6a2 2 0 00-2 2v14
                                           a2 2 0 002 2z"/>

                                </svg>

                                <div>

                                    <h3 class="text-xs font-extrabold
                                               text-slate-700 uppercase
                                               tracking-wider">

                                        11. Monitores

                                    </h3>

                                    <p class="text-[10px] text-slate-400
                                              font-medium mt-0.5">

                                        Monitores externos o integrados
                                        detectados/asignados al activo.

                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                onclick="addMonitor()"
                                class="text-[9px] bg-purple-50
                                       text-purple-600 px-3 py-1.5
                                       rounded-lg font-bold uppercase
                                       hover:bg-purple-100 transition">

                                + Agregar monitor

                            </button>

                        </div>


                        <div id="monitors-container"
                             class="space-y-4">


                            <div class="monitor-item border border-slate-100
                                        rounded-xl p-4 bg-slate-50/50">

                                <div class="flex justify-between
                                            items-center mb-3">

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 uppercase">

                                        Monitor 1

                                    </span>

                                </div>


                                <div class="grid grid-cols-2 md:grid-cols-4
                                            gap-4">


                                    <div>
                                        <label class="field-label">
                                            Marca
                                        </label>

                                        <input
                                            type="text"
                                            name="monitors[0][brand]"
                                            value="{{ old('monitors.0.brand') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Modelo
                                        </label>

                                        <input
                                            type="text"
                                            name="monitors[0][model]"
                                            value="{{ old('monitors.0.model') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Serial
                                        </label>

                                        <input
                                            type="text"
                                            name="monitors[0][serial_number]"
                                            value="{{ old('monitors.0.serial_number') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Código fabricante
                                        </label>

                                        <input
                                            type="text"
                                            name="monitors[0][manufacturer_code]"
                                            value="{{ old('monitors.0.manufacturer_code') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Año
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="monitors[0][year]"
                                            value="{{ old('monitors.0.year') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Tamaño
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            step="0.1"
                                            name="monitors[0][size]"
                                            value="{{ old('monitors.0.size') }}"
                                            placeholder="21.5"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Resolución
                                        </label>

                                        <input
                                            type="text"
                                            name="monitors[0][resolution]"
                                            value="{{ old('monitors.0.resolution') }}"
                                            placeholder="1920x1080"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Frecuencia Hz
                                        </label>

                                        <input
                                            type="number"
                                            min="0"
                                            name="monitors[0][refresh_rate]"
                                            value="{{ old('monitors.0.refresh_rate') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Código activo fijo
                                        </label>

                                        <input
                                            type="text"
                                            name="monitors[0][fixed_asset_code]"
                                            value="{{ old('monitors.0.fixed_asset_code') }}"
                                            placeholder="Activo fijo"
                                            class="field-input">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         12. PERIFÉRICOS Y SEGURIDAD FÍSICA
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center gap-2 mb-4
                                    pb-2 border-b border-slate-100">

                            <svg class="w-5 h-5 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2
                                       0 002-2v-6a2 2 0
                                       00-2-2V9a4 4 0
                                       00-8 0v2a2 2 0
                                       00-2 2v6a2 2
                                       0 002 2z"/>

                            </svg>

                            <h3 class="text-xs font-extrabold
                                       text-slate-700 uppercase
                                       tracking-wider">

                                12. Periféricos y Seguridad

                            </h3>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">


                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Serial Teclado

                                </label>

                                <input
                                    type="text"
                                    name="keyboard_serial"
                                    value="{{ old('keyboard_serial') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Serial Mouse

                                </label>

                                <input
                                    type="text"
                                    name="mouse_serial"
                                    value="{{ old('mouse_serial') }}"
                                    class="field-input">

                            </div>


                            <div>

                                <label class="block text-[10px] font-bold
                                              text-slate-800 uppercase mb-1">

                                    Guaya de Seguridad

                                </label>

                                <input
                                    type="text"
                                    name="security_guaya"
                                    value="{{ old('security_guaya') }}"
                                    class="field-input">

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         13. SOFTWARE
                    ================================================== --}}

                    <div class="bg-white rounded-2xl border border-slate-200
                                shadow-sm p-5 transition-all">

                        <div class="flex items-center justify-between mb-4
                                    pb-2 border-b border-slate-100">

                            <div class="flex items-center gap-2">

                                <svg class="w-5 h-5 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6
                                           m2 5H7a2 2 0 01-2-2V5
                                           a2 2 0 012-2h5.586a1
                                           1 0 01.707.293l5.414
                                           5.414a1 1 0 01.293.707V19
                                           a2 2 0 01-2 2z"/>

                                </svg>

                                <div>

                                    <h3 class="text-xs font-extrabold
                                               text-slate-700 uppercase
                                               tracking-wider">

                                        13. Software Instalado

                                    </h3>

                                    <p class="text-[10px] text-slate-400
                                              font-medium mt-0.5">

                                        Aplicaciones instaladas manualmente
                                        en el equipo.

                                    </p>

                                </div>

                            </div>


                            <button
                                type="button"
                                onclick="addSoftware()"
                                class="text-[9px] bg-indigo-50
                                       text-indigo-600 px-3 py-1.5
                                       rounded-lg font-bold uppercase
                                       hover:bg-indigo-100 transition">

                                + Agregar software

                            </button>

                        </div>


                        <div id="software-container"
                             class="space-y-4">


                            <div class="software-item border border-slate-100
                                        rounded-xl p-4 bg-slate-50/50">

                                <div class="flex justify-between
                                            items-center mb-3">

                                    <span class="text-[10px] font-bold
                                                 text-slate-500 uppercase">

                                        Aplicación 1

                                    </span>

                                </div>


                                <div class="grid grid-cols-2 md:grid-cols-4
                                            gap-4">


                                    <div>
                                        <label class="field-label">
                                            Nombre
                                        </label>

                                        <input
                                            type="text"
                                            name="software[0][name]"
                                            value="{{ old('software.0.name') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Versión
                                        </label>

                                        <input
                                            type="text"
                                            name="software[0][version]"
                                            value="{{ old('software.0.version') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Fabricante
                                        </label>

                                        <input
                                            type="text"
                                            name="software[0][publisher]"
                                            value="{{ old('software.0.publisher') }}"
                                            class="field-input">
                                    </div>


                                    <div>
                                        <label class="field-label">
                                            Fecha instalación
                                        </label>

                                        <input
                                            type="date"
                                            name="software[0][installed_at]"
                                            value="{{ old('software.0.installed_at') }}"
                                            class="field-input">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                         BOTONES
                    ================================================== --}}

                    <div class="mt-8 flex items-center justify-end gap-3
                                pt-6 border-t border-slate-200">


                        <a
                            href="{{ route('assets.index') }}"
                            class="px-6 py-2.5 text-xs font-bold
                                   text-slate-500 uppercase
                                   hover:text-slate-800
                                   transition-colors">

                            Cancelar

                        </a>


                        <button
                            type="button"
                            onclick="confirmCreate()"
                            class="px-8 py-2.5 bg-blue-600
                                   text-white text-xs font-bold
                                   uppercase rounded-xl shadow-md
                                   hover:bg-blue-700 hover:shadow-lg
                                   transition-all
                                   focus:ring-2 focus:ring-offset-2
                                   focus:ring-blue-600
                                   active:scale-95
                                   flex items-center gap-2">


                            <svg class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6
                                       m0-6h6m-6 0H6"/>

                            </svg>

                            Registrar Equipo

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =============================================================
         ESTILOS AUXILIARES
    ============================================================== --}}

    <style>

        .field-label {

            display: block;
            font-size: 10px;
            font-weight: 700;
            color: rgb(30 41 59);
            text-transform: uppercase;
            margin-bottom: 0.25rem;

        }


        .field-input {

            width: 100%;
            background-color: white;
            border: 1px solid rgb(203 213 225);
            color: rgb(15 23 42);
            font-size: 0.75rem;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);

        }


        .field-input:focus {

            outline: none;

            border-color: rgb(59 130 246);

            box-shadow:
                0 0 0 2px rgb(219 234 254);

        }

    </style>



    {{-- =============================================================
         SWEETALERT
    ============================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>

        /*
        |--------------------------------------------------------------------------
        | CUSTODIO → UBICACIONES
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const custodianSelect =
                    document.getElementById('custodian_id');

                const roomSelect =
                    document.getElementById('room_id');

                const oldRoomId =
                    "{{ old('room_id') }}";


                function updateRooms()
                {

                    const selectedOption =
                        custodianSelect.options[
                            custodianSelect.selectedIndex
                        ];

                    custodianSelect.setCustomValidity('');


                    roomSelect.innerHTML =
                        '<option value="">2. Seleccione ubicación...</option>';


                    if (
                        !selectedOption ||
                        !selectedOption.value
                    ) {

                        roomSelect.disabled = true;

                        roomSelect.classList.remove(
                            'bg-white',
                            'border-blue-200',
                            'text-slate-900',
                            'cursor-pointer'
                        );

                        roomSelect.classList.add(
                            'bg-slate-50',
                            'border-slate-200',
                            'text-slate-500',
                            'cursor-not-allowed'
                        );

                        return;

                    }


                    let rooms = [];


                    try {

                        rooms =
                            JSON.parse(
                                selectedOption.dataset.rooms || '[]'
                            );

                    } catch (error) {

                        console.error(
                            'Error leyendo ubicaciones:',
                            error
                        );

                        rooms = [];

                    }


                    if (!rooms.length) {

                        roomSelect.innerHTML =
                            '<option value="">No hay ubicaciones disponibles</option>';

                        roomSelect.disabled = true;
                        custodianSelect.setCustomValidity('El custodiante seleccionado no tiene ubicaciones asignadas. Asigna una ubicación antes de registrar el activo.');

                        return;

                    }


                    rooms.forEach(function (room) {

                        const option =
                            document.createElement('option');

                        option.value =
                            room.id;

                        option.textContent =
                            room.nomenclatura ??
                            room.name ??
                            `Ubicación ${room.id}`;


                        if (
                            oldRoomId &&
                            String(oldRoomId) === String(room.id)
                        ) {

                            option.selected = true;

                        }


                        roomSelect.appendChild(option);

                    });


                    roomSelect.disabled = false;


                    roomSelect.classList.remove(
                        'bg-slate-50',
                        'border-slate-200',
                        'text-slate-500',
                        'cursor-not-allowed'
                    );


                    roomSelect.classList.add(
                        'bg-white',
                        'border-blue-200',
                        'text-slate-900',
                        'cursor-pointer'
                    );

                }


                custodianSelect.addEventListener(
                    'change',
                    updateRooms
                );


                if (custodianSelect.value) {

                    updateRooms();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | ÍNDICES DINÁMICOS
        |--------------------------------------------------------------------------
        */

        let ramIndex = 1;

        let storageIndex = 1;

        let gpuIndex = 1;

        let networkAdapterIndex = 1;

        let monitorIndex = 1;

        let softwareIndex = 1;



        /*
        |--------------------------------------------------------------------------
        | RAM
        |--------------------------------------------------------------------------
        */

        function addRamModule()
        {

            const container =
                document.getElementById(
                    'ram-container'
                );


            const index =
                ramIndex;


            ramIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                `

                <div class="ram-item border border-slate-100
                            rounded-xl p-4 bg-slate-50/50">

                    <div class="flex justify-between
                                items-center mb-3">

                        <span class="text-[10px] font-bold
                                     text-slate-500 uppercase">

                            Módulo ${index + 1}

                        </span>

                        <button
                            type="button"
                            onclick="this.closest('.ram-item').remove()"
                            class="text-red-500 text-[9px]
                                   font-bold uppercase
                                   hover:text-red-700">

                            Eliminar

                        </button>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <label class="field-label">
                                Fabricante
                            </label>

                            <input
                                type="text"
                                name="ram_modules[${index}][manufacturer]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Modelo
                            </label>

                            <input
                                type="text"
                                name="ram_modules[${index}][model]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Serial
                            </label>

                            <input
                                type="text"
                                name="ram_modules[${index}][serial_number]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Banco
                            </label>

                            <input
                                type="text"
                                name="ram_modules[${index}][bank]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Slot
                            </label>

                            <input
                                type="text"
                                name="ram_modules[${index}][slot]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Tipo
                            </label>

                            <input
                                type="text"
                                name="ram_modules[${index}][memory_type]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Capacidad GB
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="ram_modules[${index}][capacity_gb]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Velocidad MHz
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="ram_modules[${index}][speed_mhz]"
                                class="field-input">
                        </div>

                    </div>

                </div>

                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        function addStorage()
        {

            const container =
                document.getElementById(
                    'storage-container'
                );


            const index =
                storageIndex;


            storageIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                `

                <div class="storage-item border border-slate-100
                            rounded-xl p-4 bg-slate-50/50">

                    <div class="flex justify-between
                                items-center mb-3">

                        <span class="text-[10px] font-bold
                                     text-slate-500 uppercase">

                            Unidad ${index + 1}

                        </span>

                        <button
                            type="button"
                            onclick="this.closest('.storage-item').remove()"
                            class="text-red-500 text-[9px]
                                   font-bold uppercase">

                            Eliminar

                        </button>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <label class="field-label">
                                Marca
                            </label>

                            <input
                                type="text"
                                name="storage_devices[${index}][brand]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Modelo
                            </label>

                            <input
                                type="text"
                                name="storage_devices[${index}][model]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Serial
                            </label>

                            <input
                                type="text"
                                name="storage_devices[${index}][serial_number]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Tipo
                            </label>

                            <input
                                type="text"
                                name="storage_devices[${index}][type]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Capacidad GB
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="storage_devices[${index}][capacity_gb]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Libre GB
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="storage_devices[${index}][free_gb]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Firmware
                            </label>

                            <input
                                type="text"
                                name="storage_devices[${index}][firmware]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Estado
                            </label>

                            <input
                                type="text"
                                name="storage_devices[${index}][health]"
                                class="field-input">
                        </div>

                    </div>

                </div>

                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | GPU
        |--------------------------------------------------------------------------
        */

        function addGpu()
        {

            const container =
                document.getElementById(
                    'gpu-container'
                );


            const index =
                gpuIndex;


            gpuIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                `

                <div class="gpu-item border border-slate-100
                            rounded-xl p-4 bg-slate-50/50">

                    <div class="flex justify-between
                                items-center mb-3">

                        <span class="text-[10px] font-bold
                                     text-slate-500 uppercase">

                            GPU ${index + 1}

                        </span>

                        <button
                            type="button"
                            onclick="this.closest('.gpu-item').remove()"
                            class="text-red-500 text-[9px]
                                   font-bold uppercase">

                            Eliminar

                        </button>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <label class="field-label">
                                Marca
                            </label>

                            <input
                                type="text"
                                name="gpus[${index}][brand]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Modelo
                            </label>

                            <input
                                type="text"
                                name="gpus[${index}][model]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Memoria MB
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="gpus[${index}][memory_mb]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Driver
                            </label>

                            <input
                                type="text"
                                name="gpus[${index}][driver_version]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Procesador
                            </label>

                            <input
                                type="text"
                                name="gpus[${index}][processor]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Resolución
                            </label>

                            <input
                                type="text"
                                name="gpus[${index}][resolution]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Frecuencia Hz
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="gpus[${index}][refresh_rate]"
                                class="field-input">
                        </div>

                    </div>

                </div>

                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK
        |--------------------------------------------------------------------------
        */

        function addNetworkAdapter()
        {

            const container =
                document.getElementById(
                    'network-adapters-container'
                );


            const index =
                networkAdapterIndex;


            networkAdapterIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                `

                <div class="network-adapter-item
                            border border-slate-100
                            rounded-xl p-4
                            bg-slate-50/50">

                    <div class="flex justify-between
                                items-center mb-3">

                        <span class="text-[10px] font-bold
                                     text-slate-500 uppercase">

                            Adaptador ${index + 1}

                        </span>

                        <button
                            type="button"
                            onclick="this.closest('.network-adapter-item').remove()"
                            class="text-red-500 text-[9px]
                                   font-bold uppercase">

                            Eliminar

                        </button>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <label class="field-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="network[adapters][${index}][name]"
                                placeholder="Ethernet / Wi-Fi"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Descripción
                            </label>

                            <input
                                type="text"
                                name="network[adapters][${index}][description]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Tipo
                            </label>

                            <select
                                name="network[adapters][${index}][type]"
                                class="field-input">

                                <option value="">
                                    Tipo
                                </option>

                                <option value="Ethernet">
                                    Ethernet
                                </option>

                                <option value="Wi-Fi">
                                    Wi-Fi
                                </option>

                                <option value="Otro">
                                    Otro
                                </option>

                            </select>
                        </div>

                        <div>
                            <label class="field-label">
                                MAC Address
                            </label>

                            <input
                                type="text"
                                name="network[adapters][${index}][mac_address]"
                                class="field-input font-mono">
                        </div>

                        <div>
                            <label class="field-label">
                                IPv4
                            </label>

                            <input
                                type="text"
                                name="network[adapters][${index}][ipv4]"
                                class="field-input font-mono">
                        </div>

                        <div>
                            <label class="field-label">
                                IPv6
                            </label>

                            <input
                                type="text"
                                name="network[adapters][${index}][ipv6]"
                                class="field-input font-mono">
                        </div>

                        <div>
                            <label class="field-label">
                                Conectado
                            </label>

                            <select
                                name="network[adapters][${index}][connected]"
                                class="field-input">

                                <option value="1">
                                    Sí
                                </option>

                                <option value="0">
                                    No
                                </option>

                            </select>
                        </div>

                        <div>
                            <label class="field-label">
                                Principal
                            </label>

                            <select
                                name="network[adapters][${index}][is_primary]"
                                class="field-input">

                                <option value="0">
                                    No
                                </option>

                                <option value="1">
                                    Sí
                                </option>

                            </select>
                        </div>

                        <div>
                            <label class="field-label">
                                Activo
                            </label>

                            <select
                                name="network[adapters][${index}][is_active]"
                                class="field-input">

                                <option value="1">
                                    Activo
                                </option>

                                <option value="0">
                                    Inactivo
                                </option>

                            </select>
                        </div>

                    </div>

                </div>

                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | MONITORES
        |--------------------------------------------------------------------------
        */

        function addMonitor()
        {

            const container =
                document.getElementById(
                    'monitors-container'
                );


            const index =
                monitorIndex;


            monitorIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                `

                <div class="monitor-item
                            border border-slate-100
                            rounded-xl p-4
                            bg-slate-50/50">

                    <div class="flex justify-between
                                items-center mb-3">

                        <span class="text-[10px] font-bold
                                     text-slate-500 uppercase">

                            Monitor ${index + 1}

                        </span>

                        <button
                            type="button"
                            onclick="this.closest('.monitor-item').remove()"
                            class="text-red-500 text-[9px]
                                   font-bold uppercase">

                            Eliminar

                        </button>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <label class="field-label">
                                Marca
                            </label>

                            <input
                                type="text"
                                name="monitors[${index}][brand]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Modelo
                            </label>

                            <input
                                type="text"
                                name="monitors[${index}][model]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Serial
                            </label>

                            <input
                                type="text"
                                name="monitors[${index}][serial_number]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Código fabricante
                            </label>

                            <input
                                type="text"
                                name="monitors[${index}][manufacturer_code]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Año
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="monitors[${index}][year]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Tamaño
                            </label>

                            <input
                                type="number"
                                min="0"
                                step="0.1"
                                name="monitors[${index}][size]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Resolución
                            </label>

                            <input
                                type="text"
                                name="monitors[${index}][resolution]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Frecuencia Hz
                            </label>

                            <input
                                type="number"
                                min="0"
                                name="monitors[${index}][refresh_rate]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Código activo fijo
                            </label>

                            <input
                                type="text"
                                name="monitors[${index}][fixed_asset_code]"
                                class="field-input">
                        </div>

                    </div>

                </div>

                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | SOFTWARE
        |--------------------------------------------------------------------------
        */

        function addSoftware()
        {

            const container =
                document.getElementById(
                    'software-container'
                );


            const index =
                softwareIndex;


            softwareIndex++;


            container.insertAdjacentHTML(
                'beforeend',
                `

                <div class="software-item
                            border border-slate-100
                            rounded-xl p-4
                            bg-slate-50/50">

                    <div class="flex justify-between
                                items-center mb-3">

                        <span class="text-[10px] font-bold
                                     text-slate-500 uppercase">

                            Aplicación ${index + 1}

                        </span>

                        <button
                            type="button"
                            onclick="this.closest('.software-item').remove()"
                            class="text-red-500 text-[9px]
                                   font-bold uppercase">

                            Eliminar

                        </button>

                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <label class="field-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="software[${index}][name]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Versión
                            </label>

                            <input
                                type="text"
                                name="software[${index}][version]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Fabricante
                            </label>

                            <input
                                type="text"
                                name="software[${index}][publisher]"
                                class="field-input">
                        </div>

                        <div>
                            <label class="field-label">
                                Fecha instalación
                            </label>

                            <input
                                type="date"
                                name="software[${index}][installed_at]"
                                class="field-input">
                        </div>

                    </div>

                </div>

                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | CONFIRMACIÓN
        |--------------------------------------------------------------------------
        */

        function confirmCreate()
        {

            const form =
                document.getElementById(
                    'create-asset-form'
                );


            /*
            |----------------------------------------------------------
            | Validación HTML5
            |----------------------------------------------------------
            */

            const custodianSelect = document.getElementById('custodian_id');
            const roomSelect = document.getElementById('room_id');

            if (!roomSelect.value || roomSelect.disabled) {
                custodianSelect.setCustomValidity('Selecciona un custodiante que tenga al menos una ubicación asignada.');
                form.reportValidity();
                return;
            }

            custodianSelect.setCustomValidity('');

            if (!form.checkValidity()) {

                form.reportValidity();

                return;

            }


            Swal.fire({

                title:
                    '¿Registrar nuevo equipo?',

                text:
                    'Asegúrate de haber ingresado correctamente el Serial y la Ubicación, ya que son críticos para el inventario.',

                icon:
                    'question',

                showCancelButton:
                    true,

                confirmButtonColor:
                    '#2563eb',

                cancelButtonColor:
                    '#94a3b8',

                confirmButtonText:
                    'Sí, registrar',

                cancelButtonText:
                    'Revisar datos',

                reverseButtons:
                    true,

                customClass: {

                    title:
                        'text-lg font-bold text-slate-800',

                    htmlContainer:
                        'text-sm text-slate-500',

                    popup:
                        'rounded-2xl',

                    confirmButton:
                        'rounded-lg px-6 py-2.5 text-xs font-bold uppercase',

                    cancelButton:
                        'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'

                }

            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({

                        title:
                            'Registrando...',

                        text:
                            'Guardando hoja de vida en la base de datos',

                        allowOutsideClick:
                            false,

                        didOpen:
                            () => {

                                Swal.showLoading();

                            }

                    });


                    form.requestSubmit();

                }

            });

        }

    </script>

</x-app-layout>