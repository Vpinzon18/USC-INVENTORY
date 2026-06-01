<x-app-layout>
    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-800">
                    <h2 class="font-bold text-2xl text-white leading-tight">Movimiento Masivo de Activos</h2>
                    <p class="text-blue-100 mt-1 text-sm opacity-90">Formato Institucional R-AF001 - Unidad de Activos Fijos</p>
                </div>

                @if(session('success'))
                    <div class="m-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="m-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl shadow-sm">{{ session('error') }}</div>
                @endif

                <form action="{{ route('movements.mass.store') }}" method="POST" class="p-8" id="movementForm">
                    @csrf
                    {{-- CAMPOS DE CONFIGURACIÓN --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase mb-2">Tipo de Movimiento</label>
                            <select name="movement_type" class="w-full rounded-xl border-gray-300 shadow-sm py-3" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="TRASLADO ASIGNACION">Traslado en calidad de Asignación</option>
                                <option value="REPARACION_INTERNA">Reparación interna</option>
                                <option value="REPARACION_EXTERNA">Reparación externa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase mb-2">Sede</label>
                            <select name="headquarters" class="w-full rounded-xl border-gray-300 shadow-sm py-3" required>
                                <option value="PAMPALINDA">Pampalinda (Cali)</option>
                                <option value="CENTRO">Centro (Cali)</option>
                                <option value="PALMIRA">Palmira</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center pt-6">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="generate_pdf" value="1" checked class="h-6 w-6 text-blue-600 rounded">
                                <span class="font-bold text-gray-700">Generar Acta PDF</span>
                            </label>
                        </div>
                    </div>

                    {{-- LISTA DE ACTIVOS --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Seleccionar Equipos</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <input type="text" id="assetSearch" placeholder="Buscar por serial..." class="w-full p-3 border-b border-gray-200 text-sm">
                                <div class="max-h-[400px] overflow-y-auto p-3 space-y-2" id="assetList">
                                    @foreach($assets as $asset)
                                        <label class="flex items-center p-3 bg-white border rounded-xl cursor-pointer hover:border-blue-300 asset-item"
                                               data-custodian="{{ $asset->currentAssignment->custodian_id ?? 0 }}"
                                               data-room="{{ $asset->currentAssignment->room_id ?? 0 }}">
                                            <input type="checkbox" name="selected_assets[]" value="{{ $asset->id }}" class="h-5 w-5 text-blue-600 rounded asset-checkbox">
                                            <div class="ml-3">
                                                <span class="block text-xs font-black">{{ $asset->serial_number }}</span>
                                                <span class="block text-[10px] text-blue-600">{{ $asset->internal_code }}</span>
                                                <span class="warning-text block text-[9px] text-orange-600 font-bold mt-1 hidden">YA ASIGNADO AQUÍ</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                                <h3 class="text-blue-800 text-xs font-black uppercase mb-4">Área de Destino (Recibe)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <select name="custodian_id" id="custodian_id" onchange="filterRooms(this.value)" class="w-full rounded-xl border-gray-300 py-3" required>
                                        <option value="" disabled selected>Seleccione Responsable...</option>
                                        @foreach($custodians as $custodian)
                                            <option value="{{ $custodian->id }}" data-rooms='@json($custodian->rooms)'>{{ $custodian->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="room_id" id="room_id" class="w-full rounded-xl border-gray-300 py-3" disabled required></select>
                                </div>
                            </div>
                            <textarea name="observation" rows="3" class="w-full rounded-2xl border-gray-300 p-4" placeholder="Observaciones del movimiento..."></textarea>
                            <button type="button" id="submitBtn" onclick="validarYEnviar()" 
        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-black py-5 rounded-2xl shadow-lg transition-all hover:scale-[1.01]">
    EJECUTAR TRASLADO Y GENERAR ACTA
</button>
<script>
    async function validarYEnviar() {
        const form = document.getElementById('movementForm');
        const formData = new FormData(form);

        // 1. Llamada al servidor para validar conflictos
        const response = await fetch("{{ route('movements.validate-conflict') }}", {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
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
                form.submit(); // Enviamos el formulario manualmente
            }
        } else {
            form.submit(); // Sin conflictos, enviamos directo
        }
    }
</script>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function filterRooms(custodianId) {
            const roomSelect = document.getElementById('room_id');
            const selectedOption = document.getElementById('custodian_id').options[document.getElementById('custodian_id').selectedIndex];
            roomSelect.innerHTML = '<option value="">Cargando...</option>';
            const rooms = JSON.parse(selectedOption.getAttribute('data-rooms'));
            if(rooms) {
                roomSelect.innerHTML = '<option value="" disabled selected>Seleccione oficina...</option>';
                rooms.forEach(room => roomSelect.innerHTML += `<option value="${room.id}">${room.nomenclatura}</option>`);
                roomSelect.disabled = false;
            }
        }

        $('#custodian_id, #room_id').on('change', function() {
            const sc = $('#custodian_id').val();
            const sr = $('#room_id').val();
            $('.asset-item').each(function() {
                const ac = $(this).data('custodian');
                const ar = $(this).data('room');
                const warning = $(this).find('.warning-text');
                if(sc && sr && ac == sc && ar == sr) {
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
            // Si ya validamos, permitimos el envío
            if (this.dataset.validated === 'true') return;

            e.preventDefault();
            const formData = new FormData(this);

            const response = await fetch("{{ route('movements.validate-conflict') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
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
                    this.dataset.validated = 'true'; // Abrimos el cerrojo
                    this.submit(); // Enviamos finalmente
                }
            } else {
                this.dataset.validated = 'true'; // Abrimos el cerrojo
                this.submit();
            }
        });
    </script>
</x-app-layout>