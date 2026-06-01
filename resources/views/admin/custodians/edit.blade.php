<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center">
                        <a href="{{ route('custodians.index') }}" class="mr-4 text-gray-400 hover:text-blue-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 leading-tight">Editar Responsable</h2>
                            <p class="text-sm text-gray-500 mt-1">Actualice la información de contacto o el cargo del custodio.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('custodians.update', $custodian) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre Completo</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $custodian->full_name) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   required>
                            @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Documento</label>
    <input type="text" name="document_number" 
           value="{{ old('document_number', $custodian->document_number) }}" 
           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
    <p class="text-[10px] text-amber-600 mt-1 italic font-medium">
        ⚠️ Solo edite este campo para corregir errores de digitación.
    </p>
</div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cargo</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $custodian->job_title) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   required>
                            @error('job_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dependencia / Área</label>
                            <input type="text" name="dependency" value="{{ old('dependency', $custodian->dependency) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   required>
                            @error('dependency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-1">
    <label class="block text-sm font-semibold text-gray-700 mb-2">Centro de Costos (CC)</label>
    <input type="text" name="cost_center" 
           value="{{ old('cost_center', $custodian->cost_center) }}" 
           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200"
           required>
    @error('cost_center') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $custodian->email) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Extensión Telefónica</label>
                            <input type="text" name="extension" value="{{ old('extension', $custodian->extension) }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all">
                            @error('extension') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
<div class="col-span-2 mt-6">
    <label class="block text-sm font-bold text-gray-800 mb-3 uppercase tracking-wider">
        Gestión de Ubicaciones a Cargo
    </label>
    
    <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <!-- Buscador Indexado -->
        <div class="p-4 bg-white border-b border-gray-200">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="roomSearch" 
                       placeholder="Filtrar ubicaciones por nombre o bloque..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 sm:text-sm transition-all">
            </div>
        </div>

        <!-- Lista de Checkboxes con Lógica de Marcado -->
        <div class="max-h-64 overflow-y-auto p-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="roomList">
            @foreach($rooms as $room)
                <label class="room-item flex items-center p-3 bg-white border border-gray-100 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="rooms[]" value="{{ $room->id }}" 
                               class="h-5 w-5 text-blue-600 border-gray-300 rounded-md focus:ring-blue-500 transition-all cursor-pointer"
                               {{-- Lógica para marcar los que ya tiene asignados --}}
                               @if($custodian->rooms->contains($room->id)) checked @endif>
                    </div>
                    <div class="ml-3">
                        <span class="block text-sm font-bold text-gray-700 group-hover:text-blue-700 nomenclature">
                            {{ $room->nomenclatura }}
                        </span>
                        <span class="block text-[10px] text-gray-400 uppercase font-medium building">
                            {{ $room->building->name ?? 'Sede Principal' }}
                        </span>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="bg-blue-50 px-4 py-2 text-[11px] text-blue-500 font-bold flex justify-between items-center uppercase tracking-tighter border-t border-blue-100">
            <span>Resultados: <span id="matchCount">{{ count($rooms) }}</span></span>
            <span>Ubicaciones marcadas aparecerán en su historial</span>
        </div>
    </div>
</div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end space-x-4">
                        <a href="{{ route('custodians.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Actualizar Información
                        </button>
                    </div>
                </form>
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
            </div>
        </div>
    </div>
</x-app-layout>