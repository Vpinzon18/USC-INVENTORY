<x-app-layout>
    <style>
        /* Animación fluida para la búsqueda de activos */
        .asset-hidden {
            opacity: 0;
            transform: scale(0.95);
            max-height: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
            border-width: 0 !important;
            overflow: hidden;
        }

        .asset-item {
            max-height: 120px;
            /* Suficiente para que quepa el contenido normal */
        }
    </style>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                @if(session('success'))
                <div class="m-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="m-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm">{{ session('error') }}</div>
                @endif

                <form action="{{ route('movements.mass.store') }}" method="POST" class="p-6" id="movementForm">
                    @csrf

                    {{-- ENCABEZADO MINIMALISTA --}}
                    <div class="flex flex-col mb-6 border-b border-gray-100 pb-4">
                        <h1 class="font-bold text-xl text-gray-800 tracking-tight">Modulo de Movimientos de Activos</h1>
                        <p class="text-xs text-gray-500">Formato Institucional R-AF001 - Unidad de Activos Fijos.</p>
                    </div>

                    {{-- CAMPOS DE CONFIGURACIÓN UNIFICADOS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 tracking-wider">Tipo de Movimiento</label>
                            <select name="movement_type" class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white text-gray-800" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="TRASLADO ASIGNACION">Traslado en calidad de Asignación</option>
                                <option value="ASIGNACION INICIAL">Asignación Inicial</option>
                                <option value="PRESTAMO FUERA USC">Préstamo Fuera de la USC</option>
                                <option value="PRESTAMO DENTRO USC">Préstamo Dentro de la USC</option>
                                <option value="REPARACION DENTRO USC">Reparación Dentro de la USC</option>
                                <option value="REPARACION FUERA USC">Reparación Fuera de la USC</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 tracking-wider">Sede</label>
                            <select name="headquarters" class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white text-gray-800" required>
                                <option value="PAMPALINDA">Pampalinda (Cali)</option>
                                <option value="CENTRO">Centro (Cali)</option>
                                <option value="PALMIRA">Palmira</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center pt-5">
                            <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="generate_pdf" value="1" checked class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition duration-150">
                                <span class="text-sm font-medium text-gray-600">Generar Acta PDF Automática</span>
                            </label>
                        </div>
                    </div>

                    {{-- CUERPO DE TRABAJO EN DOS COLUMNAS --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        {{-- COLUMNA IZQUIERDA: SELECCIÓN DE HARDWARE --}}
                        <div class="lg:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 tracking-wider">Seleccionar Equipos</label>
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="assetSearch" placeholder="Buscar por serial o placa..." class="w-full bg-gray-50/50 pl-9 p-2.5 border-b border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 outline-none text-gray-700">
                                </div>

                                <div class="max-h-[340px] overflow-y-auto p-2 space-y-1 bg-white" id="assetList">
                                    @foreach($assets as $asset)
                                    <label class="flex items-center p-2.5 bg-white border border-gray-100 rounded-lg cursor-pointer hover:border-indigo-200 hover:bg-gray-50/50 asset-item transition duration-150 group"
                                        data-custodian="{{ $asset->currentAssignment->custodian_id ?? 0 }}"
                                        data-room="{{ $asset->currentAssignment->room_id ?? 0 }}">
                                        <input type="checkbox" name="selected_assets[]" value="{{ $asset->id }}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 asset-checkbox">
                                        <div class="ml-3 flex-1 min-w-0">
                                            <span class="block text-xs font-bold text-gray-700 group-hover:text-indigo-600 transition">{{ $asset->serial_number }}</span>
                                            <span class="block text-[10px] text-gray-400 font-semibold tracking-wide">{{ $asset->internal_code }}</span>
                                            <span class="warning-text block text-[9px] text-amber-600 font-bold mt-0.5 hidden">⚠️ UBICADO EN EL DESTINO ACTUAL</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: ASIGNACIÓN Y NOTAS --}}
                        <div class="lg:col-span-2 flex flex-col justify-between space-y-4">

                            {{-- ÁREA DE DESTINO ESTILO HISTORIAL --}}
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                                <h3 class="text-gray-800 text-xs font-bold uppercase tracking-wider mb-3">Área de Destino (Quién Recibe)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <select name="custodian_id" id="custodian_id" onchange="filterRooms(this.value)" class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white text-gray-800" required>
                                            <option value="" disabled selected>Seleccione Responsable...</option>
                                            @foreach($custodians as $custodian)
                                            <option value="{{ $custodian->id }}" data-rooms='@json($custodian->rooms)'>{{ $custodian->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <select name="room_id" id="room_id" class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white text-gray-800 disabled:bg-gray-50 disabled:text-gray-400" disabled required>
                                            <option value="" disabled selected>Seleccione Ubicación...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- TEXTAREA DE OBSERVACIONES ESTILIZADO --}}
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 tracking-wider">Observaciones / Justificación</label>
                                <textarea name="observation" id="observation" rows="4" class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3 text-gray-700 placeholder-gray-400 resize-none" placeholder="Escriba aquí los detalles o motivos del traslado de hardware..."></textarea>
                            </div>

                            {{-- BOTÓN ACCIÓN INTEGRADO AL DISEÑO SIGMA --}}
                            <div>
                                <button type="button" id="submitBtn" onclick="validarYEnviar()"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-widest py-3.5 rounded-lg shadow-sm transition ease-in-out duration-150">
                                    Procesar Traslado Masivo
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // FUNCIÓN PARA ENVIAR Y VALIDAR CONFLICTOS
        async function validarYEnviar() {
            const form = document.getElementById('movementForm');
            const formData = new FormData(form);

            // 1. Llamada al servidor para validar conflictos
            const response = await fetch("{{ route('movements.validate-conflict') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.has_conflict) {
                const result = await Swal.fire({
                    title: 'Equipos ya asignados',
                    html: `Los siguientes activos ya están con el responsable destino:<br>
                           <b style="color:red">${data.conflicts.join(', ')}</b><br><br>
                           ¿Deseas continuar y <b>omitir automáticamente</b> estos equipos?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                });

                if (result.isConfirmed) {
                    form.submit(); 
                }
            } else {
                form.submit(); 
            }
        }


document.getElementById('assetSearch').addEventListener('input', function() {
    let searchTerm = this.value.toLowerCase().trim();
    let assetItems = document.querySelectorAll('.asset-item');

    assetItems.forEach(function(item) {

        let serial = item.querySelector('span.font-black')?.textContent.toLowerCase() || "";
        let internalCode = item.querySelector('span.text-blue-600')?.textContent.toLowerCase() || "";
        
        let textoCompleto = item.textContent.toLowerCase();

        if (serial.includes(searchTerm) || internalCode.includes(searchTerm) || textoCompleto.includes(searchTerm)) {
            item.classList.remove('asset-hidden');
            item.style.display = '';
        } else {
      
            item.classList.add('asset-hidden');
            item.style.display = 'none';
        }
    });
});

        document.addEventListener('DOMContentLoaded', function() {
            const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
            const observationField = document.getElementById('observation');

            const textoNormaSoporte = "Se adjunta relación detallada de equipos (Activo, Modelo, Serial) debido a movimiento masivo.";

            function evaluarCantidadDeActivos() {
                const seleccionados = document.querySelectorAll('.asset-checkbox:checked').length;

                if (seleccionados > 5) {
                   
                    if (!observationField.value.includes(textoNormaSoporte)) {
                        observationField.value = observationField.value ?
                            observationField.value + "\n\n" + textoNormaSoporte :
                            textoNormaSoporte;
                    }
                } else {
         
                    if (observationField.value.trim() === textoNormaSoporte) {
                        observationField.value = "";
                    }
                }
            }

           
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('asset-checkbox')) {
                    evaluarCantidadDeActivos();
                }
            });
        });

        // FILTRO DE HABITACIONES/SALONES
        function filterRooms(custodianId) {
            const roomSelect = document.getElementById('room_id');
            const selectedOption = document.getElementById('custodian_id').options[document.getElementById('custodian_id').selectedIndex];
            roomSelect.innerHTML = '<option value="">Cargando...</option>';
            const rooms = JSON.parse(selectedOption.getAttribute('data-rooms'));
            if (rooms) {
                roomSelect.innerHTML = '<option value="" disabled selected>Seleccione oficina...</option>';
                rooms.forEach(room => roomSelect.innerHTML += `<option value="${room.id}">${room.nomenclatura}</option>`);
                roomSelect.disabled = false;
            }
        }

        // ALERTA VISUAL DE DUPLICADOS EN TIEMPO REAL
        $('#custodian_id, #room_id').on('change', function() {
            const sc = $('#custodian_id').val();
            const sr = $('#room_id').val();
            $('.asset-item').each(function() {
                const ac = $(this).data('custodian');
                const ar = $(this).data('room');
                const warning = $(this).find('.warning-text');
                if (sc && sr && ac == sc && ar == sr) {
                    $(this).addClass('bg-orange-50 border-orange-300');
                    warning.removeClass('hidden');
                } else {
                    $(this).removeClass('bg-orange-50 border-orange-300');
                    warning.addClass('hidden');
                }
            });
        });

        // LÓGICA DE VALIDACIÓN CON CERROJO
        document.getElementById('movementForm').addEventListener('submit', async function(e) {
            if (this.dataset.validated === 'true') return;

            e.preventDefault();
            const formData = new FormData(this);

            const response = await fetch("{{ route('movements.validate-conflict') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.has_conflict) {
                const result = await Swal.fire({
                    title: 'Equipos ya asignados',
                    html: `Los siguientes activos ya están con el responsable destino:<br>
                           <b style="color:red">${data.conflicts.join(', ')}</b><br><br>
                           ¿Deseas continuar y <b>omitir automáticamente</b> estos equipos del movimiento?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Continuar, omitiendo duplicados',
                    cancelButtonText: 'Cancelar y revisar'
                });

                if (result.isConfirmed) {
                    this.dataset.validated = 'true';
                    this.submit();
                }
            } else {
                this.dataset.validated = 'true';
                this.submit();
            }
        });
    </script>
</x-app-layout>