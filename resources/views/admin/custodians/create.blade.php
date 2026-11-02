<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] w-[96%] mx-auto">

            <form action="{{ route('custodians.store') }}" method="POST" id="createCustodianForm" data-sigma-create-form>
                @csrf
                <input type="hidden" name="status" value="active">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>
                    <div class="flex items-center gap-5 pl-2">
                        <a href="{{ route('custodians.index') }}" class="text-slate-400 hover:text-blue-600 transition-colors bg-slate-50 p-2 rounded-lg border border-slate-100 hidden sm:block" title="Volver al directorio">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        </a>
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
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
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <h3 class="font-bold text-slate-800">Formulario de Alta de Custodio</h3>
                            </div>

                            <div class="p-6 space-y-8">

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">1. Identificación y Datos Personales</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Nombre Completo <span class="text-rose-500">*</span></label>
                                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white placeholder-slate-400">
                                            @error('full_name') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Número de Documento <span class="text-rose-500">*</span></label>
                                            <input type="text" name="document_number" value="{{ old('document_number') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono placeholder-slate-400">
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
                                            <input type="text" name="cost_center" value="{{ old('cost_center') }}" required class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono" placeholder="Ej: CC-20450">
                                            @error('cost_center') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">3. Directorio y Contacto</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Correo Electrónico</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white" placeholder="usuario@usc.edu.co">
                                            @error('email') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Extensión Telefónica</label>
                                            <input type="text" name="extension" value="{{ old('extension') }}" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm transition-all bg-slate-50 focus:bg-white font-mono" placeholder="Ej: 4102">
                                            @error('extension') <p class="text-rose-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-blue-100 bg-blue-50/70 p-4 md:p-5">
                                    <h4 class="text-xs font-extrabold text-blue-900 uppercase tracking-wide">4. Espacios de la dependencia</h4>
                                    <p class="mt-2 text-sm text-blue-800">Al seleccionar una dependencia, este responsable quedará asociado automáticamente a todas las oficinas y salones registrados para ella. Administra los espacios desde Infraestructura → Oficinas / Salones.</p>
                                    <p class="mt-2 text-xs text-blue-700">No es necesario seleccionar los espacios uno por uno. Si cambias la dependencia del responsable, sus espacios asociados se actualizarán automáticamente.</p>
                                </div>

                                <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
                                    <a href="{{ route('custodians.index') }}" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg shadow-sm text-center">Cancelar</a>
                                    <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                                        Crear Registro
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="xl:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6 space-y-5">
                            <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Reglas de Validación
                            </h3>

                            <div class="space-y-4 text-xs text-slate-600 leading-relaxed">
                                <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg flex gap-3">
                                    <span class="text-blue-500 font-bold">1.</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Exclusividad Organizacional:</strong> El sistema SIGMA no permitirá guardar este registro si el <strong>Cargo</strong> o la <strong>Dependencia</strong> seleccionada ya se encuentran asignados.</p>
                                </div>
                                <div class="bg-slate-50 border border-slate-200 p-3 rounded-lg flex gap-3">
                                    <span class="text-slate-400 font-bold">2.</span>
                                    <p><strong class="text-slate-700 block mb-0.5">Espacios por dependencia:</strong> Las oficinas y salones se vinculan automáticamente según la dependencia seleccionada.</p>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createCustodianForm');
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: '¿Registrar responsable?',
                        text: 'Los espacios de su dependencia quedarán asociados automáticamente.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Sí, crear registro',
                        cancelButtonText: 'Revisar de nuevo'
                    }).then(result => { if (result.isConfirmed) form.submit(); });
                });
            }
            const errorsTag = document.getElementById('backend-errors-data');
            if (errorsTag) {
                const errors = JSON.parse(errorsTag.textContent);
                if (errors.length) Swal.fire({ icon: 'error', title: 'Validación rechazada', html: errors.map(error => `<p>${error}</p>`).join(''), confirmButtonColor: '#2563eb' });
            }
        });
    </script>
</x-app-layout>
