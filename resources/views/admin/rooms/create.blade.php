<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl w-fit">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Registrar Nueva Oficina</h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Agregue un nuevo salón, laboratorio u oficina a un bloque existente.</p>
                    </div>
                </div>

                <form action="{{ route('rooms.store') }}" method="POST" class="p-6 md:p-8 flex flex-col gap-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-sm font-bold text-red-800">Se encontraron errores:</h3>
                            </div>
                            <ul class="list-disc list-inside text-xs text-red-700 ml-7 space-y-1 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm transition-all hover:shadow-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="name" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                    Nombre o Número de la Oficina <span class="text-red-500">*</span>
                                </label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                       placeholder="Ej: Oficina 201, Laboratorio de Redes..." 
                                       class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                            </div>

                            <div>
                                <label for="nomenclatura" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                    Nomenclatura (Cali)
                                </label>
                                <input id="nomenclatura" type="text" name="nomenclatura" value="{{ old('nomenclatura') }}" 
                                       placeholder="Ej: 2011"
                                       class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                                <p class="text-[11px] text-slate-400 mt-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Este código se usará para movimientos masivos.
                                </p>
                            </div>

                            <div>
                                <label for="building_id" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                    Bloque Correspondiente <span class="text-red-500">*</span>
                                </label>
                                <select name="building_id" id="building_id" required
                                        class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                                    <option value="" disabled {{ old('building_id') ? '' : 'selected' }}>-- Seleccione el Bloque --</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }} ({{ $building->campus->name ?? 'Sin Sede' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="floor" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                    Piso / Nivel <span class="text-red-500">*</span>
                                </label>
                                <input id="floor" type="number" name="floor" value="{{ old('floor') }}" required 
                                       placeholder="Ej: 1, 2, 3..."
                                       class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                            </div>

                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('rooms.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit" class="px-8 py-2.5 bg-emerald-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-emerald-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Guardar Oficina
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>