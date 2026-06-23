<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] w-[96%] mx-auto">

            <form action="{{ route('custodians.store') }}" method="POST" id="createCustodianForm">
                @csrf

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

                                <div class="relative z-[58]">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-bold text-slate-600 uppercase tracking-wide">4. Ubicaciones a Cargo <span class="text-rose-500">*</span></label>
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded shadow-sm border border-blue-100"><span id="selectedCount">0</span> Seleccionadas</span>
                                    </div>
                                    
                                    <div class="relative w-full" id="multiSelectContainer">
                                        
                                        <div class="min-h-[42px] w-full rounded-lg border border-slate-300 shadow-sm bg-white p-1.5 flex flex-wrap gap-1.5 items-center cursor-text transition-all focus-within:border-blue-500 focus-within:ring focus-within:ring-blue-200" id="multiSelectBox">
                                            
                                            <div id="selectedChips" class="flex flex-wrap gap-1.5 items-center">
                                                </div>
                                            
                                            <input type="text" id="roomSearch" placeholder="Buscar por nombre o bloque..." class="flex-1 min-w-[150px] border-none focus:ring-0 text-sm p-1 bg-transparent text-slate-700 placeholder-slate-400" autocomplete="off">
                                            
                                            <div class="px-2 text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>

                                        <div id="dropdownMenu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto z-[60]">
                                            <div id="roomList" class="flex flex-col">
                                                <div class="p-4 text-center text-xs text-slate-400 font-medium">Cargando ubicaciones...</div>
                                            </div>
                                        </div>

                                        <div id="hiddenInputsContainer"></div>
                                    </div>
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
                                    <p><strong class="text-slate-700 block mb-0.5">Múltiples Ubicaciones:</strong> Utilice el buscador para seleccionar rápidamente múltiples ubicaciones. Quedarán guardadas como etiquetas azules.</p>
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
        document.addEventListener('DOMContentLoaded', async function() {
            // Variables de UI para Salones
            const searchInput = document.getElementById('roomSearch');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const roomListContainer = document.getElementById('roomList');
            const multiSelectBox = document.getElementById('multiSelectBox');
            const selectedChipsContainer = document.getElementById('selectedChips');
            const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
            const selectedCountDisplay = document.getElementById('selectedCount');
            const form = document.getElementById('createCustodianForm');

            // Estado de los datos
            let allRooms = [];
            let selectedRooms = new Map(); // Mapa para guardar {id -> objeto_salon}

            // 1. Fetch de ubicaciones
            const response = await fetch("{{ route('api.filters.api.rooms.status') }}?custodian_id=0");
            allRooms = await response.json();

            // 2. Controladores del Menú Desplegable
            searchInput.addEventListener('focus', () => {
                dropdownMenu.classList.remove('hidden');
                renderDropdown(allRooms);
            });

            // Cerrar menú al hacer clic fuera
            document.addEventListener('click', (e) => {
                if (!multiSelectBox.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });

            // Enfocar buscador al hacer clic en el contenedor visual
            multiSelectBox.addEventListener('click', () => searchInput.focus());

            // 3. Renderizar la lista dentro del Dropdown
            function renderDropdown(rooms) {
                roomListContainer.innerHTML = '';
                
                if(rooms.length === 0) {
                    roomListContainer.innerHTML = '<div class="p-4 text-center text-xs text-slate-400">No se encontraron ubicaciones.</div>';
                    return;
                }

                // Renderizamos máximo 100 para no saturar el DOM (El usuario debe filtrar si hay más)
                const roomsToRender = rooms.slice(0, 100);

                roomsToRender.forEach(room => {
                    const isOccupied = room.is_occupied;
                    const isSelected = selectedRooms.has(room.id);

                    const optionHTML = `
                        <div class="room-option flex items-center justify-between p-3 border-b border-slate-100 hover:bg-slate-50 transition-colors cursor-pointer ${isOccupied ? 'opacity-50 cursor-not-allowed bg-slate-50' : ''}" data-id="${room.id}">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="h-4 w-4 text-blue-600 rounded border-slate-300 pointer-events-none" ${isSelected ? 'checked' : ''} ${isOccupied ? 'disabled' : ''}>
                                <div>
                                    <span class="block text-sm font-bold text-slate-700">${room.nomenclatura}</span>
                                    <span class="block text-[10px] text-slate-400 uppercase">${room.building}</span>
                                </div>
                            </div>
                            ${isOccupied ? `<span class="text-[9px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100 uppercase">Ocupado: ${room.occupant_name}</span>` : ''}
                        </div>
                    `;
                    roomListContainer.insertAdjacentHTML('beforeend', optionHTML);
                });

                if(rooms.length > 100) {
                    roomListContainer.insertAdjacentHTML('beforeend', `<div class="p-2 text-center text-[10px] text-slate-400 font-bold bg-slate-50 uppercase">Mostrando 100 resultados. Use el buscador para refinar.</div>`);
                }

                // Asignar eventos de clic a cada opción (si no está ocupada)
                document.querySelectorAll('.room-option').forEach(el => {
                    el.addEventListener('click', function(e) {
                        const roomId = parseInt(this.getAttribute('data-id'));
                        const roomData = allRooms.find(r => r.id === roomId);
                        
                        if(roomData.is_occupied) return; // Bloquear si está ocupado
                        
                        if (selectedRooms.has(roomId)) {
                            selectedRooms.delete(roomId);
                        } else {
                            selectedRooms.set(roomId, roomData);
                        }
                        
                        updateUI();
                        // Mantenemos el dropdown abierto para que elijan más y re-renderizamos para actualizar checkboxes
                        renderDropdown(allRooms.filter(r => r.nomenclatura.toLowerCase().includes(searchInput.value.toLowerCase()) || r.building.toLowerCase().includes(searchInput.value.toLowerCase())));
                        searchInput.focus();
                    });
                });
            }

            // 4. Filtrado en tiempo real
            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                const filtered = allRooms.filter(r => r.nomenclatura.toLowerCase().includes(term) || r.building.toLowerCase().includes(term));
                renderDropdown(filtered);
            });

            // 5. Actualizar la Interfaz (Etiquetas y Formularios Ocultos)
            window.removeRoom = function(roomId) {
                selectedRooms.delete(roomId);
                updateUI();
                renderDropdown(allRooms);
            };

            function updateUI() {
                // 5.1 Actualizar contador
                selectedCountDisplay.textContent = selectedRooms.size;

                // 5.2 Limpiar contenedores
                selectedChipsContainer.innerHTML = '';
                hiddenInputsContainer.innerHTML = '';

                // 5.3 Dibujar chips y agregar inputs hidden para Laravel
                selectedRooms.forEach((room, id) => {
                    // El Chip visible
                    selectedChipsContainer.insertAdjacentHTML('beforeend', `
                        <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 px-2 py-1 rounded text-xs font-bold">
                            ${room.nomenclatura}
                            <button type="button" class="text-blue-400 hover:text-blue-600 focus:outline-none" onclick="removeRoom(${id})">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </span>
                    `);

                    // El input invisible que viaja al controlador
                    hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="rooms[]" value="${id}">
                    `);
                });

                // Si hay elementos, ocultamos el placeholder
                searchInput.placeholder = selectedRooms.size > 0 ? "Buscar más..." : "Buscar por nombre o bloque...";
            }

            // 6. Validación Final antes de enviar
            form.addEventListener('submit', function(e) {
                if (selectedRooms.size === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Atención',
                        text: 'Debes seleccionar al menos una ubicación disponible.',
                        icon: 'warning',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                e.preventDefault();
                Swal.fire({
                    title: '¿Registrar responsable?',
                    text: 'Verifica que los datos sean correctos.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Sí, crear registro',
                    cancelButtonText: 'Revisar de nuevo'
                }).then((res) => {
                    if (res.isConfirmed) this.submit();
                });
            });

            // 7. Alertas de Backend
            const errorsTag = document.getElementById('backend-errors-data');
            if(errorsTag) {
                const errors = JSON.parse(errorsTag.textContent);
                if (errors.length > 0) {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Validación Rechazada', 
                        html: errors.map(e => `<p class="text-sm text-left mb-1">⚠️ ${e}</p>`).join(''),
                        confirmButtonColor: '#3b82f6'
                    });
                }
            }
        });
    </script>
</x-app-layout>