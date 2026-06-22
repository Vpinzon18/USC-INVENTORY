<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] w-[96%] mx-auto">

            <form action="{{ route('custodians.update', $custodian) }}" method="POST" id="editCustodianForm">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ ($custodian->status ?? 'active') == 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>

                    <div class="flex items-center gap-5 pl-2">
                        <a href="{{ route('custodians.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 p-2 rounded-lg border border-slate-100 hidden sm:block" title="Volver al directorio">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>

                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 border border-blue-200 flex items-center justify-center text-blue-700 font-black text-xl shadow-sm shrink-0">
                            {{ mb_substr($custodian->full_name, 0, 2) }}
                        </div>
                        <div>
                            <h2 class="font-extrabold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                                {{ $custodian->full_name }}
                            </h2>
                            <div class="flex items-center gap-2 text-sm text-slate-500 mt-0.5">
                                <span class="font-medium">{{ $custodian->dependency->name ?? 'Dependencia no asignada' }}</span>
                                <span class="text-slate-300">•</span>
                                @if(($custodian->status ?? 'active') == 'active')
                                <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Activo</span>
                                @else
                                <span class="inline-flex items-center gap-1 text-slate-600 font-bold text-xs bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    <div class="xl:col-span-2">

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <h3 class="font-bold text-slate-800">Actualizar Información del Responsable</h3>
                            </div>

                            <div class="p-6 space-y-8">

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">1. Identificación y Datos Personales</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Nombre Completo <span class="text-rose-500">*</span></label>
                                            <input type="text" name="full_name" value="{{ old('full_name', $custodian->full_name) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white">
                                            @error('full_name') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Documento <span class="text-rose-500">*</span></label>
                                            <input type="text" name="document_number" value="{{ old('document_number', $custodian->document_number) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono">
                                            <p class="text-[10px] text-amber-600 mt-1 italic font-medium">Solo edite para corregir digitación.</p>
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
                                            $depId = old('dependency_id', $custodian->dependency_id ?? '');
                                            $depName = $depId ? \App\Models\Dependency::find($depId)?->name : '';
                                            @endphp
                                            <x-enterprise-select name="dependency_id" placeholder="Buscar Dependencia..." endpoint="/api/filters/dependencies" initial-value="{{ $depId }}" initial-text="{{ $depName }}" />
                                            @error('dependency_id') <span class="text-xs text-rose-500 font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex flex-col gap-1 relative z-[59]">
                                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Cargo Institucional <span class="text-rose-500">*</span></label>
                                            @php
                                            $jobId = old('job_title_id', $custodian->job_title_id ?? '');
                                            $jobName = $jobId ? \App\Models\JobTitle::find($jobId)?->name : '';
                                            @endphp
                                            <x-enterprise-select name="job_title_id" placeholder="Buscar Cargo..." endpoint="/api/filters/job-titles" initial-value="{{ $jobId }}" initial-text="{{ $jobName }}" />
                                            @error('job_title_id') <span class="text-xs text-rose-500 font-medium">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Centro de Costos <span class="text-rose-500">*</span></label>
                                            <input type="text" name="cost_center" value="{{ old('cost_center', $custodian->cost_center) }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono" placeholder="Ej: CC-10293">
                                            @error('cost_center') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Estado del Responsable <span class="text-rose-500">*</span></label>
                                            <select name="status" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-medium text-slate-700">
                                                <option value="active" {{ old('status', $custodian->status ?? 'active') == 'active' ? 'selected' : '' }}> Activo (En argo)</option>
                                                <option value="inactive" {{ old('status', $custodian->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactivo (Deshabilitado)</option>
                                            </select>
                                            @error('status') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">3. Directorio y Contacto</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Correo Electrónico</label>
                                            <input type="email" name="email" value="{{ old('email', $custodian->email) }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white placeholder-slate-300" placeholder="usuario@usc.edu.co">
                                            @error('email') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Extensión Telefónica</label>
                                            <input type="text" name="extension" value="{{ old('extension', $custodian->extension) }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono placeholder-slate-300" placeholder="Ej: 1234">
                                            @error('extension') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">4. Gestión de Ubicaciones a Cargo</h4>
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded">Total marcadas: <span id="matchCount">{{ count($rooms) }}</span></span>
                                    </div>

                                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                                        <div class="p-3 bg-slate-50/80 border-b border-slate-200">
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </span>
                                                <input type="text" id="roomSearch" placeholder="Filtrar ubicaciones por nombre o bloque..." class="block w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 text-sm transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div class="h-64 overflow-y-auto p-3 grid grid-cols-1 md:grid-cols-2 gap-2 bg-white" id="roomList">
                                            @foreach($rooms as $room)
                                            @php
                                            // Validamos si el salón está ocupado por OTRA persona activa
                                            $isOccupied = \App\Models\Custodian::where('status', 'active')
                                            ->where('id', '!=', $custodian->id) // Ignoramos al custodio actual
                                            ->whereHas('rooms', function($query) use ($room) {
                                            $query->where('rooms.id', $room->id);
                                            })->exists();
                                            @endphp

                                            <label class="room-item flex items-start p-3 border rounded-lg transition-all group 
                    {{ $isOccupied ? 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-60' : 'bg-slate-50 border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer' }}">

                                                <div class="relative flex items-center justify-center mt-0.5">
                                                    <input type="checkbox" name="rooms[]" value="{{ $room->id }}"
                                                        {{ $isOccupied ? 'disabled' : '' }}
                                                        @if(!$isOccupied && $custodian->rooms->contains($room->id)) checked @endif
                                                    class="h-4 w-4 rounded transition-all {{ $isOccupied ? 'text-gray-400 border-gray-300' : 'text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer' }}">
                                                </div>

                                                <div class="ml-3 flex-1">
                                                    <span class="block text-sm font-bold nomenclature transition-colors {{ $isOccupied ? 'text-gray-500' : 'text-slate-700 group-hover:text-blue-700' }}">
                                                        {{ $room->nomenclatura }}
                                                        @if($isOccupied)
                                                        <span class="ml-2 text-[9px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded border border-gray-300 uppercase tracking-widest">Ocupado</span>
                                                        @endif
                                                    </span>
                                                    <span class="block text-[10px] text-slate-400 uppercase font-medium building mt-0.5">
                                                        {{ $room->building->name ?? 'Sede Principal' }}
                                                    </span>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>>

                                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
                                    <a href="{{ route('custodians.index') }}" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 hover:text-slate-800 rounded-lg shadow-sm transition-all text-center">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                        Guardar Cambios
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="xl:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6">
                            <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Resumen Operativo
                            </h3>

                            <ul class="space-y-4">
                                <li class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-600">Equipos Asignados</span>
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $custodian->activeAssets()->count() > 0 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $custodian->activeAssets()->count() }}
                                    </span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-600">Ubicaciones Controladas</span>
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $custodian->rooms()->count() }}
                                    </span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-600">Última Modificación</span>
                                    <span class="text-xs text-slate-500 font-mono">{{ $custodian->updated_at ? $custodian->updated_at->diffForHumans() : 'Nunca' }}</span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-600">Fecha de Creación</span>
                                    <span class="text-xs text-slate-500 font-mono">{{ $custodian->created_at ? $custodian->created_at->format('d/M/Y') : '---' }}</span>
                                </li>
                            </ul>

                            @if($custodian->activeAssets()->count() > 0)
                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Modelos Asignados (Top 5)</h4>
                                <div class="space-y-2">
                                    @foreach($custodian->activeAssets->take(5) as $asset)
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-slate-700 truncate" title="{{ $asset->model_version }}">{{ $asset->model_version ?? 'Equipo Genérico' }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script id="backend-errors-data" type="application/json">
        {
            !!json_encode($errors - > all()) !!
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ---------------------------------------------------------
            // 1. ALERTAS DE VALIDACIÓN DEL BACKEND
            // ---------------------------------------------------------
            // Leemos los errores de la "Isla de Datos" usando Javascript puro
            const errorsDataTag = document.getElementById('backend-errors-data');
            let backendErrors = [];

            if (errorsDataTag && errorsDataTag.textContent.trim() !== '') {
                try {
                    backendErrors = JSON.parse(errorsDataTag.textContent);
                } catch (e) {
                    console.error("Error parseando los errores del backend");
                }
            }

            // Si hay errores, disparamos SweetAlert
            if (backendErrors.length > 0) {
                let errorList = '<div class="text-left text-sm text-slate-600 mt-3 space-y-2 p-3 bg-rose-50 border border-rose-100 rounded-lg">';

                backendErrors.forEach(function(error) {
                    errorList += `<p class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    <span class="font-medium">${error}</span>
                                  </p>`;
                });

                errorList += '</div>';

                Swal.fire({
                    icon: 'error',
                    title: 'Validación Rechazada',
                    html: errorList,
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        popup: 'rounded-2xl border border-slate-200 shadow-xl',
                        title: 'text-lg font-black text-slate-800'
                    }
                });
            }

            // ---------------------------------------------------------
            // 2. ALERTA DE CONFIRMACIÓN ANTES DE GUARDAR
            // ---------------------------------------------------------
            const editForm = document.getElementById('editCustodianForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Confirmar actualización?',
                        text: "Verifica que la asignación organizacional y las ubicaciones a cargo sean correctas antes de proceder.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#f8fafc',
                        confirmButtonText: 'Sí, guardar cambios',
                        cancelButtonText: '<span class="text-slate-600 font-bold">Cancelar</span>',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl border border-slate-200 shadow-xl',
                            title: 'text-xl font-black text-slate-800',
                            cancelButton: 'border border-slate-300 shadow-sm hover:bg-slate-100'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            editForm.submit();
                        }
                    });
                });
            }

            // ---------------------------------------------------------
            // 3. LÓGICA ANTERIOR: BLOQUEO VISUAL POR ESTADO
            // ---------------------------------------------------------
            const statusSelect = document.querySelector('select[name="status"]');
            const orgSection = document.getElementById('org-section');

            function toggleOrgFields() {
                if (statusSelect && orgSection) {
                    if (statusSelect.value === 'inactive') {
                        orgSection.style.opacity = '0.4';
                        orgSection.style.pointerEvents = 'none';
                    } else {
                        orgSection.style.opacity = '1';
                        orgSection.style.pointerEvents = 'auto';
                    }
                }
            }
            if (statusSelect) {
                toggleOrgFields();
                statusSelect.addEventListener('change', toggleOrgFields);
            }

            // ---------------------------------------------------------
            // 4. LÓGICA ANTERIOR: BUSCADOR DE SALONES
            // ---------------------------------------------------------
            const searchInput = document.getElementById('roomSearch');
            const roomItems = document.querySelectorAll('.room-item');
            const matchCount = document.getElementById('matchCount');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase();
                    let visibleCount = 0;

                    roomItems.forEach(item => {
                        const nomenclature = item.querySelector('.nomenclature').textContent.toLowerCase();
                        const building = item.querySelector('.building').textContent.toLowerCase();

                        if (nomenclature.includes(term) || building.includes(term)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    if (matchCount) matchCount.textContent = visibleCount;
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('roomSearch');
            const roomItems = document.querySelectorAll('.room-item');
            const matchCount = document.getElementById('matchCount');

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase();
                    let visibleCount = 0;

                    roomItems.forEach(item => {
                        const nomenclature = item.querySelector('.nomenclature').textContent.toLowerCase();
                        const building = item.querySelector('.building').textContent.toLowerCase();

                        if (nomenclature.includes(term) || building.includes(term)) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    matchCount.textContent = visibleCount;
                });
            }
        });
    </script>
</x-app-layout>