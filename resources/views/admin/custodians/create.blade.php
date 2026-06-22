<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] w-[96%] mx-auto">

            <form action="{{ route('custodians.store') }}" method="POST" id="createCustodianForm">
                @csrf

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                    <div class="flex items-center gap-5 pl-2">
                        <a href="{{ route('custodians.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 p-2 rounded-lg border border-slate-100 hidden sm:block" title="Volver al directorio">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>

                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-extrabold text-2xl text-slate-800 leading-tight">Registrar Nuevo Responsable</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Asigne un nuevo custodio al inventario general de activos tecnológicos de la USC.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    <div class="xl:col-span-2">

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="font-bold text-slate-800">Formulario de Alta de Custodio</h3>
                            </div>

                            <div class="p-6 space-y-8">

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">1. Identificación y Datos Personales</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Nombre Completo <span class="text-rose-500">*</span></label>
                                            <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Ej: Carlos Alberto Restrepo" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white placeholder-slate-400">
                                            @error('full_name') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Número de Documento <span class="text-rose-500">*</span></label>
                                            <input type="text" name="document_number" value="{{ old('document_number') }}" required placeholder="Cédula o pasaporte" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono placeholder-slate-400">
                                            @error('document_number') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">2. Asignación Organizacional</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="flex flex-col gap-1 relative z-[60]">
                                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Dependencia Asignada <span class="text-rose-500">*</span></label>
                                            @php
                                            $depId = old('dependency_id');
                                            $depName = $depId ? \App\Models\Dependency::find($depId)?->name : '';
                                            @endphp
                                            <x-enterprise-select name="dependency_id" placeholder="Buscar Dependencia..." endpoint="/api/filters/dependencies" initial-value="{{ $depId }}" initial-text="{{ $depName }}" />
                                            @error('dependency_id') <span class="text-xs text-rose-500 font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex flex-col gap-1 relative z-[59]">
                                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Cargo Institucional <span class="text-rose-500">*</span></label>
                                            @php
                                            $jobId = old('job_title_id');
                                            $jobName = $jobId ? \App\Models\JobTitle::find($jobId)?->name : '';
                                            @endphp
                                            <x-enterprise-select name="job_title_id" placeholder="Buscar Cargo..." endpoint="/api/filters/job-titles" initial-value="{{ $jobId }}" initial-text="{{ $jobName }}" />
                                            @error('job_title_id') <span class="text-xs text-rose-500 font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Centro de Costos <span class="text-rose-500">*</span></label>
                                            <input type="text" name="cost_center" value="{{ old('cost_center') }}" required class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono placeholder-slate-400" placeholder="Ej: CC-20450">
                                            @error('cost_center') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">3. Directorio y Contacto</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Correo Electrónico</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white placeholder-slate-400" placeholder="usuario@usc.edu.co">
                                            @error('email') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Extensión Telefónica</label>
                                            <input type="text" name="extension" value="{{ old('extension') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono placeholder-slate-400" placeholder="Ej: 4102">
                                            @error('extension') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                               <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase">4. Ubicaciones a Cargo *</h4>
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Seleccionados: <span id="selectedCount">0</span></span>
                                </div>
                                
                                <div class="border rounded-xl shadow-sm">
                                    <div class="p-3 bg-slate-50 border-b">
                                        <input type="text" id="roomSearch" placeholder="Buscar por nombre o bloque..." class="w-full text-sm py-2 px-3 border-slate-300 rounded-lg">
                                    </div>
                                    <div id="roomList" class="h-64 overflow-y-auto p-3 grid grid-cols-1 md:grid-cols-2 gap-2 bg-white">
                                        <div class="col-span-full text-center py-10 text-slate-400">Cargando ubicaciones...</div>
                                    </div>
                                </div>

                </div>

                                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
                                    <a href="{{ route('custodians.index') }}" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 hover:text-slate-800 rounded-lg shadow-sm transition-all text-center">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                        Crear Registro
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="xl:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6 space-y-5">
                            <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Reglas de Validación
                            </h3>

                            <div class="space-y-4 text-xs text-slate-600 leading-relaxed">
                                <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg flex gap-3">
                                    <span class="text-blue-500 font-bold">1.</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Exclusividad Organizacional:</strong> El sistema SIGMA no permitirá guardar este registro si el <strong>Cargo</strong> o la <strong>Dependencia</strong> seleccionada ya se encuentran asignados a otro responsable que esté activo.</p>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 p-3 rounded-lg flex gap-3">
                                    <span class="text-slate-400 font-bold">2.</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Estado por Defecto:</strong> Todo nuevo custodio se creará automáticamente en estado <span class="text-emerald-600 font-bold">Activo</span>, habilitando de inmediato su perfil para recibir asignaciones masivas de hardware.</p>
                                </div>

                                <div class="bg-slate-50 border border-slate-200 p-3 rounded-lg flex gap-3">
                                    <span class="text-slate-400 font-bold">3.</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Ubicaciones Múltiples:</strong> Puede marcar múltiples salones o bloques en este momento. Al guardar, quedará registrado el alcance físico de auditoría de este funcionario.</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-400 flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                <span>Módulo de Control Operativo SIGMA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script id="backend-errors-data" type="application/json">{!! json_encode($errors->all()) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const container = document.getElementById('roomList');
            const form = document.getElementById('createCustodianForm');
            
            // 1. Carga Dinámica
            const response = await fetch("{{ url('/api/rooms-status') }}?custodian_id=0");
            const allRooms = await response.json();
            
            function renderRooms(rooms) {
                container.innerHTML = rooms.length ? '' : '<div class="col-span-full text-center py-10 text-slate-400">Sin resultados.</div>';
                rooms.forEach(room => {
                    container.insertAdjacentHTML('beforeend', `
                        <label class="room-item flex items-start p-3 border rounded-lg ${room.is_occupied ? 'bg-gray-100 opacity-60' : 'bg-slate-50'}">
                            <input type="checkbox" name="rooms[]" value="${room.id}" ${room.is_occupied ? 'disabled' : ''} class="room-checkbox mt-1 h-4 w-4 text-blue-600">
                            <div class="ml-3">
                                <span class="block text-sm font-bold">${room.nomenclatura}</span>
                                <span class="block text-[10px] text-slate-400 uppercase building">${room.building}</span>
                                ${room.is_occupied ? `<span class="text-[9px] text-red-500 font-bold uppercase">OCUPADO: ${room.occupant_name}</span>` : ''}
                            </div>
                        </label>`);
                });
                
                // Actualizar contador de seleccionados
                document.querySelectorAll('.room-checkbox').forEach(cb => {
                    cb.addEventListener('change', () => {
                        document.getElementById('selectedCount').textContent = document.querySelectorAll('.room-checkbox:checked').length;
                    });
                });
            }

            renderRooms(allRooms);

            // 2. Buscador (Unificado)
            document.getElementById('roomSearch').addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                renderRooms(allRooms.filter(r => r.nomenclatura.toLowerCase().includes(term) || r.building.toLowerCase().includes(term)));
            });

            // 3. Validación de envío (La que faltaba)
            form.addEventListener('submit', function(e) {
                const checkedCount = document.querySelectorAll('.room-checkbox:checked').length;
                
                // Si tu lógica requiere al menos un salón:
                if (checkedCount === 0) {
                    e.preventDefault();
                    Swal.fire('Error', 'Debes seleccionar al menos una ubicación.', 'error');
                    return;
                }

                e.preventDefault();
                Swal.fire({ title: '¿Registrar responsable?', icon: 'question', showCancelButton: true }).then((res) => { 
                    if(res.isConfirmed) this.submit(); 
                });
            });
        });
    </script>
   
</x-app-layout>