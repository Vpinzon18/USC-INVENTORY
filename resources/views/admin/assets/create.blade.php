<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-slate-50 rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="p-8 border-b border-slate-200 bg-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Registrar Nueva Hoja de Vida</h2>
                            <p class="text-sm text-slate-500 mt-1 font-medium">Complete la información técnica, de hardware y administrativa del nuevo activo.</p>
                        </div>
                        <div class="hidden md:flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <form id="create-asset-form" action="{{ route('assets.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-sm font-bold text-red-800">No se pudo registrar el equipo. Por favor, corrige lo siguiente:</h3>
                        </div>
                        <ul class="list-disc list-inside text-xs text-red-700 ml-7 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all hover:shadow-md">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 10-4 0v1m4 0a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 100 4m0 0a2 2 0 100-4m0 0a2 2 0 110 4z" />
                            </svg>
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">1. Información General y Red</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-7 gap-4">
                            <div class="col-span-2 xl:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial <span class="text-red-500">*</span></label>
                                <input type="text" name="serial_number" value="{{ old('serial_number') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm" required>
                            </div>
                            <div class="col-span-2 xl:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Placa SOMA-ID</label>
                                <input type="text" name="internal_code" value="{{ old('internal_code') }}" placeholder="Ej: USC-IT-2024" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Hostname</label>
                                <input type="text" name="hostname" value="{{ old('hostname') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase">Modelo / Versión (Comercial)</label>
                                <input type="text" name="model_version" class="w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Dirección IP</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm font-mono placeholder:text-slate-300" placeholder="192.168.X.X">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">MAC Address</label>
                                <input type="text" name="mac_address" value="{{ old('mac_address') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm font-mono placeholder:text-slate-300" placeholder="00:00:00:00:00:00">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Dominio</label>
                                <input type="text" name="domain_name" value="{{ old('domain_name') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Sis. Operativo</label>
                                <input type="text" name="os_version" value="{{ old('os_version') }}" placeholder="Ej: Windows 11 Pro" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- TARJETA 2: UBICACIÓN Y ASIGNACIÓN (Select Dependiente) -->
                    <div class="bg-blue-50/50 rounded-2xl border border-blue-100 shadow-sm p-5 transition-all">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-blue-100">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h3 class="text-xs font-extrabold text-blue-900 uppercase tracking-wider">2. Asignación y Ubicación Inicial</h3>
                        </div>

                        <div class="mb-4 p-3 bg-blue-100/50 border border-blue-200 rounded-xl flex items-start gap-3 shadow-inner">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-[11px] text-blue-800 leading-relaxed">
                                Seleccione primero al <strong>Responsable</strong>. El sistema habilitará automáticamente las oficinas que él administra para que asigne el destino de este nuevo equipo.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- SELECT 1: CUSTODIO -->
                            <div>
                                <label class="block text-[10px] font-bold text-blue-900 uppercase mb-1">Responsable (Custodio) <span class="text-red-500">*</span></label>
                                <select name="custodian_id" id="custodian_id" class="w-full bg-white border-blue-200 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-500 px-3 py-2 shadow-sm transition-all" required>
                                    <option value="" disabled selected>1. Seleccione responsable...</option>
                                    @foreach($custodians as $custodian)
                                    <option value="{{ $custodian->id }}" data-rooms="{{ $custodian->rooms }}" {{ old('custodian_id') == $custodian->id ? 'selected' : '' }}>
                                        {{ $custodian->full_name }} ({{ $custodian->job_title }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('custodian_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SELECT 2: SALÓN (Controlado por JS) -->
                            <div>
                                <label class="block text-[10px] font-bold text-blue-900 uppercase mb-1">Oficina / Salón <span class="text-red-500">*</span></label>
                                <select name="room_id" id="room_id" class="w-full bg-slate-50 border-slate-200 text-slate-500 text-xs rounded-lg focus:ring-2 focus:ring-blue-500 px-3 py-2 shadow-sm cursor-not-allowed transition-all" required disabled>
                                    <option value="" disabled selected>2. Esperando al responsable...</option>
                                </select>
                                @error('room_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">3. Hardware Principal</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Board Marca</label>
                                <input type="text" name="board_brand" value="{{ old('board_brand') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Board Modelo</label>
                                <input type="text" name="board_model" value="{{ old('board_model') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">CPU Marca</label>
                                <input type="text" name="cpu_brand" value="{{ old('cpu_brand') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">CPU Modelo</label>
                                <input type="text" name="cpu_model" value="{{ old('cpu_model') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">GPU Marca</label>
                                <input type="text" name="gpu_brand" value="{{ old('gpu_brand') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">GPU Modelo</label>
                                <input type="text" name="gpu_model" value="{{ old('gpu_model') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">4. Memoria RAM</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Detalle General</label>
                                    <input type="text" name="ram" value="{{ old('ram') }}" placeholder="Ej: 16GB DDR4" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Capacidad (GB)</label>
                                    <input type="number" name="ram_capacity_gb" value="{{ old('ram_capacity_gb') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Marca</label>
                                    <input type="text" name="ram_brand" value="{{ old('ram_brand') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Modelo</label>
                                    <input type="text" name="ram_model" value="{{ old('ram_model') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4-8-4s-8 1.79-8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
        </svg>

        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
            5. Almacenamiento
        </h3>
    </div>

    <div class="grid grid-cols-2 gap-4">

        {{-- MARCA --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Marca
            </label>

            <input
                type="text"
                name="storage_brand"
                value="{{ old('storage_brand') }}"
                placeholder="Ej: KIOXIA"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">
        </div>

        {{-- MODELO --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Modelo
            </label>

            <input
                type="text"
                name="storage_model"
                value="{{ old('storage_model') }}"
                placeholder="Ej: KBG50ZNS256G"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">
        </div>

        {{-- SERIAL --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Serial
            </label>

            <input
                type="text"
                name="storage_serial_number"
                value="{{ old('storage_serial_number') }}"
                placeholder="Serial del disco"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm font-mono">
        </div>

        {{-- FIRMWARE --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Firmware
            </label>

            <input
                type="text"
                name="storage_firmware"
                value="{{ old('storage_firmware') }}"
                placeholder="Versión firmware"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">
        </div>

        {{-- CAPACIDAD --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Capacidad (GB)
            </label>

            <input
                type="number"
                name="storage_capacity_gb"
                value="{{ old('storage_capacity_gb') }}"
                min="0"
                step="1"
                placeholder="Ej: 512"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">
        </div>

        {{-- ESPACIO LIBRE --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Espacio libre (GB)
            </label>

            <input
                type="number"
                name="storage_free_gb"
                value="{{ old('storage_free_gb') }}"
                min="0"
                step="1"
                placeholder="Ej: 240"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">
        </div>

        {{-- TIPO --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Tipo
            </label>

            <select
                name="storage_type"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">

                <option value="">Seleccione</option>

                <option value="HDD" {{ old('storage_type') === 'HDD' ? 'selected' : '' }}>
                    HDD
                </option>

                <option value="SSD" {{ old('storage_type') === 'SSD' ? 'selected' : '' }}>
                    SSD
                </option>

                <option value="NVMe SSD" {{ old('storage_type') === 'NVMe SSD' ? 'selected' : '' }}>
                    NVMe SSD
                </option>

                <option value="eMMC" {{ old('storage_type') === 'eMMC' ? 'selected' : '' }}>
                    eMMC
                </option>

                <option value="Otro" {{ old('storage_type') === 'Otro' ? 'selected' : '' }}>
                    Otro
                </option>

            </select>
        </div>

        {{-- SALUD --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">
                Salud
            </label>

            <input
                type="text"
                name="storage_health"
                value="{{ old('storage_health') }}"
                placeholder="Ej: OK"
                class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg
                       focus:ring-2 focus:ring-blue-100 focus:border-blue-500
                       px-3 py-2 shadow-sm">
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
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Marca Tarjeta WiFi</label>
                                    <input type="text" name="wifi_brand" value="{{ old('wifi_brand') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Modelo Tarjeta WiFi</label>
                                    <input type="text" name="wifi_model" value="{{ old('wifi_model') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">7. Periféricos y Seguridad</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Activo Monitor</label>
                                <input type="text" name="monitor_asset" value="{{ old('monitor_asset') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial Monitor</label>
                                <input type="text" name="monitor_serial" value="{{ old('monitor_serial') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial Teclado</label>
                                <input type="text" name="keyboard_serial" value="{{ old('keyboard_serial') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Serial Mouse</label>
                                <input type="text" name="mouse_serial" value="{{ old('mouse_serial') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-800 uppercase mb-1">Guaya Seguridad</label>
                                <input type="text" name="security_guaya" value="{{ old('security_guaya') }}" class="w-full bg-white border-slate-300 text-slate-900 text-xs rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 px-3 py-2 shadow-sm">
                            </div>
                        </div>
                    </div>
                    @isset($asset)
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mt-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">8. Software Instalado (Auditoría)</h3>
                        </div>

                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50 text-gray-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Nombre de la Aplicación</th>
                                        <th class="px-4 py-3">Versión</th>
                                        <th class="px-4 py-3">Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-600">
                                    @forelse($asset->software as $app)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-2 font-medium">{{ $app->name }}</td>
                                        <td class="px-4 py-2">{{ $app->version ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $app->created_at ? $app->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-400 italic">No se detectó software registrado por el agente.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endisset

                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('assets.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>

                        <button type="button" onclick="confirmCreate()" class="px-8 py-2.5 bg-blue-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Registrar Equipo
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const custodianSelect = document.getElementById('custodian_id');
            const roomSelect = document.getElementById('room_id');

            // Recuperamos el ID del salón si la página se recargó por un error de validación
            const oldRoomId = "{{ old('room_id') }}";

            function updateRooms() {
                const selectedOption = custodianSelect.options[custodianSelect.selectedIndex];

                // Reiniciamos el select de salones
                roomSelect.innerHTML = '<option value="" disabled selected>2. Seleccione la ubicación...</option>';

                if (!custodianSelect.value) {
                    roomSelect.disabled = true;
                    roomSelect.classList.add('bg-slate-50', 'cursor-not-allowed', 'text-slate-500');
                    roomSelect.classList.remove('bg-white', 'text-slate-900', 'border-blue-200');
                    return;
                }

                try {
                    // Extraemos los salones desde el atributo data-rooms
                    const rooms = JSON.parse(selectedOption.getAttribute('data-rooms'));

                    if (rooms && rooms.length > 0) {
                        rooms.forEach(room => {
                            const isSelected = (oldRoomId == room.id) ? 'selected' : '';
                            roomSelect.innerHTML += `<option value="${room.id}" ${isSelected}>${room.nomenclatura}</option>`;
                        });

                        // Habilitamos visual y funcionalmente el select
                        roomSelect.disabled = false;
                        roomSelect.classList.remove('bg-slate-50', 'cursor-not-allowed', 'text-slate-500');
                        roomSelect.classList.add('bg-white', 'text-slate-900', 'border-blue-200');
                    } else {
                        roomSelect.innerHTML = '<option value="" disabled>Este responsable no tiene ubicaciones asignadas</option>';
                        roomSelect.disabled = true;
                        roomSelect.classList.add('bg-slate-50', 'cursor-not-allowed', 'text-slate-500');
                    }
                } catch (error) {
                    console.error("Error al procesar las ubicaciones:", error);
                    roomSelect.innerHTML = '<option value="" disabled>Error cargando ubicaciones</option>';
                    roomSelect.disabled = true;
                }
            }

            // Ejecutar al cambiar el selector
            custodianSelect.addEventListener('change', updateRooms);

            // Ejecutar al cargar la página (por si ya había un old('custodian_id') seleccionado)
            if (custodianSelect.value) {
                updateRooms();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmCreate() {
            Swal.fire({
                title: '¿Registrar nuevo equipo?',
                text: "Asegúrate de haber ingresado correctamente el Serial y la Ubicación, ya que son críticos para el inventario.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb', // Azul
                cancelButtonColor: '#94a3b8', // Gris
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Revisar datos',
                reverseButtons: true,
                customClass: {
                    title: 'text-lg font-bold text-slate-800',
                    htmlContainer: 'text-sm text-slate-500',
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase',
                    cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold uppercase'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Registrando...',
                        text: 'Guardando hoja de vida en la base de datos',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    document.getElementById('create-asset-form').submit();
                }
            });
        }
    </script>
</x-app-layout>