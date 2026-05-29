<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Cabecera dinámica -->
                <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight uppercase">Actualizar Hoja de Vida</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium">
                            Modificando equipo: <span class="text-blue-600 font-black">{{ $asset->serial_number }}</span>
                        </p>
                    </div>
                    <a href="{{ route('assets.index') }}" class="text-gray-400 hover:text-gray-600 transition-transform hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>

                <!-- Bloque de Errores de Validación -->
                @if ($errors->any())
                    <div class="mx-8 mt-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800 uppercase tracking-tight">Se encontraron errores:</h3>
                                <ul class="mt-1 text-xs text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('assets.update', $asset) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-6 mt-6">
    <h3 class="text-xs font-black uppercase text-blue-600 tracking-widest border-b pb-2">Especificaciones Técnicas Detalladas</h3>

    <div class="max-w-7xl mx-auto p-4">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-black uppercase text-blue-600 mb-4 border-b pb-2">Identificación del Activo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Serial (Torre)</label>
                        <input type="text" value="{{ $asset->serial_number }}" class="w-full rounded-xl bg-gray-50 border-gray-200 text-gray-500 font-mono text-sm" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Placa Inventario (USC)</label>
                        <input type="text" name="internal_code" value="{{ old('internal_code', $asset->internal_code) }}" class="w-full rounded-xl border-gray-300 font-medium text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Hostname</label>
                        <input type="text" value="{{ $asset->hostname }}" class="w-full rounded-xl bg-gray-50 border-gray-200 text-gray-500 font-mono text-sm" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Dirección IP</label>
                        <input type="text" name="ip_address" value="{{ old('ip_address', $asset->ip_address) }}" class="w-full rounded-xl border-gray-300 font-mono text-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-black uppercase text-blue-600 mb-4 border-b pb-2">Software y Conectividad</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Sistema Operativo</label>
                        <input type="text" value="{{ $asset->os_version ?? 'N/A' }}" class="w-full rounded-xl bg-gray-50 border-gray-200 text-gray-500 text-sm" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase">Tarjeta Inalámbrica</label>
                        <input type="text" value="{{ ($asset->wifi_brand ?? 'N/A') . ' ' . ($asset->wifi_model ?? '') }}" class="w-full rounded-xl bg-gray-50 border-gray-200 text-gray-500 text-sm" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-xs font-black uppercase text-blue-600 mb-4 border-b pb-2">Componentes Hardware</h3>
            
            <div class="space-y-3">
                @php
                    $hardware = [
                        'Procesador' => $asset->cpu,
                        'Memoria RAM' => $asset->ram,
                        'Board' => ($asset->board_brand ?? 'N/A') . ' ' . ($asset->board_model ?? ''),
                        'Disco Duro' => ($asset->storage_brand ?? 'N/A') . ' ' . ($asset->storage_model ?? ''),
                        'Gráfica' => ($asset->gpu_brand ?? 'N/A') . ' ' . ($asset->gpu_model ?? '')
                    ];
                @endphp
                @foreach($hardware as $label => $value)
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase">{{ $label }}</label>
                        <div class="text-[11px] font-medium text-gray-700 bg-gray-50 p-2 rounded-lg border">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
                            
                            <!-- El campo de MAC ADDRESS ha sido eliminado de esta sección -->
                        </div>

                        <!-- SECCIÓN 3: PERIFÉRICOS Y SEGURIDAD (EDITABLE - RESALTADO) -->
                        <div class="md:col-span-2 bg-blue-50/50 p-6 rounded-2xl border border-blue-100 mt-4 shadow-inner">
                            <h3 class="text-xs font-black uppercase text-blue-800 tracking-widest mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                Periféricos y Seguridad Física (Registro Manual R-GT004)
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-blue-900 mb-2 uppercase italic tracking-wider">Guaya de Seguridad</label>
                                    <input type="text" name="security_guaya" value="{{ old('security_guaya', $asset->security_guaya) }}" 
                                           placeholder="Código guaya..." 
                                           class="w-full rounded-xl border-blue-200 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-bold text-xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-blue-900 mb-2 uppercase italic tracking-wider">Activo Monitor</label>
                                    <input type="text" name="monitor_asset" value="{{ old('monitor_asset', $asset->monitor_asset) }}" 
                                           class="w-full rounded-xl border-blue-200 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-bold text-xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-blue-900 mb-2 uppercase italic tracking-wider">Serial Monitor</label>
                                    <input type="text" name="monitor_serial" value="{{ old('monitor_serial', $asset->monitor_serial) }}" 
                                           class="w-full rounded-xl border-blue-200 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-bold text-xs">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black text-blue-900 mb-2 uppercase italic tracking-wider">Serial Teclado</label>
                                    <input type="text" name="keyboard_serial" value="{{ old('keyboard_serial', $asset->keyboard_serial) }}" 
                                           class="w-full rounded-xl border-blue-200 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-bold text-xs">
                                </div>
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black text-blue-900 mb-2 uppercase italic tracking-wider">Serial Mouse</label>
                                    <input type="text" name="mouse_serial" value="{{ old('mouse_serial', $asset->mouse_serial) }}" 
                                           class="w-full rounded-xl border-blue-200 focus:ring-blue-500 focus:border-blue-500 shadow-sm font-bold text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN UBICACIÓN -->
                        <div class="md:col-span-2 bg-amber-50 p-6 rounded-2xl border border-amber-100 mt-4">
                            <div class="flex items-start">
                                <div class="shrink-0 pt-1 text-amber-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-sm font-bold text-amber-900 uppercase tracking-tight">Ubicación Registrada</h3>
                                    <div class="max-w-md mt-4">
                                        <label class="block text-xs font-bold text-amber-900 mb-2 uppercase tracking-tighter">Oficina / Salón Actual</label>
                                        <select name="room_id" class="w-full rounded-xl border-amber-200 focus:ring-amber-500 shadow-sm text-xs font-bold uppercase transition-all">
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->id }}" {{ old('room_id', $asset->room_id) == $room->id ? 'selected' : '' }}>
                                                    {{ $room->nomenclatura }} ({{ $room->building->name ?? 'Sin Edificio' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTONES -->
                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end space-x-4 items-center">
                        <a href="{{ route('assets.index') }}" class="px-6 py-2.5 text-xs font-black uppercase text-gray-400 hover:text-gray-800 transition-colors tracking-widest">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-12 rounded-xl shadow-lg transition-all active:scale-95 uppercase text-xs tracking-widest hover:shadow-blue-200">
                            Guardar Actualización HV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>