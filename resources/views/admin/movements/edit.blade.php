<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 p-8">
                
                <div class="border-b pb-4 mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800">Modificar Composición del Acta</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Añada o remueva equipos del acta: <span class="font-bold text-indigo-600">{{ $baseMovement->acta_number }}</span>
                        </p>
                    </div>
                </div>

                <form action="{{ route('movements.update', $baseMovement->id) }}" method="POST" id="movementForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Tipo de Movimiento</label>
                            <select name="movement_type" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full bg-white" required>
                                <option value="Asignación" {{ old('movement_type', $baseMovement->movement_type) == 'Asignación' ? 'selected' : '' }}>Asignación</option>
                                <option value="Traslado" {{ old('movement_type', $baseMovement->movement_type) == 'Traslado' ? 'selected' : '' }}>Traslado</option>
                                <option value="Baja" {{ old('movement_type', $baseMovement->movement_type) == 'Baja' ? 'selected' : '' }}>Baja</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Responsable / Custodio Asignado</label>
                            <select name="custodian_id" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full bg-white" required>
                                @foreach($custodians as $custodian)
                                    <option value="{{ $custodian->id }}" {{ old('custodian_id', $baseMovement->custodian_id) == $custodian->id ? 'selected' : '' }}>
                                        {{ $custodian->full_name }} (CC: {{ $custodian->document_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-8 bg-gray-50/50 p-6 rounded-xl border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Buscar y Añadir Equipos
                        </h3>
                        
                        <div class="relative mb-6">
                            <input type="text" id="assetSearch" placeholder="Escriba el serial o código interno para añadir..." class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm pl-4 pr-10 py-3 text-sm" autocomplete="off">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            
                            <div id="searchResults" class="absolute z-10 w-full bg-white border border-gray-200 mt-1 rounded-lg shadow-xl hidden max-h-60 overflow-y-auto">
                                </div>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-gray-600 mb-3">Equipos actualmente en el acta:</p>
                            <div id="selectedAssetsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                </div>
                            @error('asset_ids') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">Observaciones o Justificación de la Modificación</label>
                        <textarea name="observations" rows="3" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full bg-white">{{ old('observations', $baseMovement->observations) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-6 gap-4">
                        <a href="{{ route('movements.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700" style="text-decoration: none;">Cancelar</a>
                        <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md transition">
                            Actualizar Lote Completo
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div id="server-data" class="hidden"
         data-assets="{{ json_encode($assets->map(function($a) { return ['id' => $a->id, 'serial' => $a->serial_number, 'code' => $a->internal_code, 'type' => $a->type ?? 'Equipo']; })) }}"
         data-initial="{{ json_encode($batchMovements->pluck('asset_id')) }}">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Leemos los datos desde el HTML oculto (VS Code ya no marcará error)
            const serverData = document.getElementById('server-data');
            const allAssets = JSON.parse(serverData.dataset.assets);
            const initialIds = JSON.parse(serverData.dataset.initial);
            
            // Usamos un Set (conjunto) para que no haya IDs repetidos
            let selectedAssets = new Set(initialIds);

            // Referencias del DOM
            const searchInput = document.getElementById('assetSearch');
            const searchResults = document.getElementById('searchResults');
            const container = document.getElementById('selectedAssetsContainer');

            // 3. Función principal para dibujar las tarjetitas azules de los equipos seleccionados
            function renderSelected() {
                container.innerHTML = ''; // Limpiamos
                
                if(selectedAssets.size === 0) {
                    container.innerHTML = '<div class="col-span-full p-4 border border-dashed border-red-300 bg-red-50 text-red-600 rounded-lg text-sm text-center">Debe seleccionar al menos un equipo.</div>';
                    return;
                }

                selectedAssets.forEach(id => {
                    const asset = allAssets.find(a => a.id == id);
                    if(asset) {
                        const card = document.createElement('div');
                        card.className = "flex items-center justify-between p-3 bg-indigo-50 border border-indigo-100 rounded-lg shadow-sm";
                        card.innerHTML = `
                            <div>
                                <span class="block font-bold text-indigo-900 text-sm">${asset.serial}</span>
                                <span class="block text-xs text-indigo-600">${asset.code || 'S/N'} - ${asset.type}</span>
                            </div>
                            <input type="hidden" name="asset_ids[]" value="${asset.id}">
                            <button type="button" onclick="removeAsset(${asset.id})" class="text-indigo-400 hover:text-red-500 transition focus:outline-none p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        `;
                        container.appendChild(card);
                    }
                });
            }

            // 4. Función para buscar mientras se escribe
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                searchResults.innerHTML = '';
                
                if (term.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }

                // Filtramos equipos que coincidan con serial o código y que NO estén seleccionados ya
                const matches = allAssets.filter(a => {
                    const matchText = ((a.serial || '') + ' ' + (a.code || '')).toLowerCase();
                    return matchText.includes(term) && !selectedAssets.has(a.id);
                }).slice(0, 10); // Mostramos máximo 10 sugerencias para no saturar

                if (matches.length > 0) {
                    matches.forEach(asset => {
                        const div = document.createElement('div');
                        div.className = "p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 flex justify-between items-center";
                        div.innerHTML = `
                            <div><span class="font-bold text-gray-800">${asset.serial}</span> <span class="text-xs text-gray-500 ml-2">${asset.code || ''}</span></div>
                            <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded">Añadir +</span>
                        `;
                        // Evento al hacer clic en un resultado
                        div.addEventListener('click', function() {
                            selectedAssets.add(asset.id);
                            searchInput.value = '';
                            searchResults.classList.add('hidden');
                            renderSelected();
                        });
                        searchResults.appendChild(div);
                    });
                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.innerHTML = '<div class="p-3 text-sm text-gray-500">No se encontraron equipos o ya están añadidos.</div>';
                    searchResults.classList.remove('hidden');
                }
            });

            // Ocultar buscador si se hace clic afuera
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });

            // Función expuesta al botón rojo de eliminar
            window.removeAsset = function(id) {
                selectedAssets.delete(id);
                renderSelected();
            };

            // Validar antes de enviar
            document.getElementById('movementForm').addEventListener('submit', function(e) {
                if(selectedAssets.size === 0) {
                    e.preventDefault();
                    alert("Debe seleccionar al menos un equipo para el acta.");
                }
            });

            // Renderizado inicial
            renderSelected();
        });
    </script>
</x-app-layout>