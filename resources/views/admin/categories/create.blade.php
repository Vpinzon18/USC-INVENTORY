<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
                <a href="{{ route('categories.index') }}" class="text-slate-400 hover:text-emerald-600 transition-colors p-2 bg-slate-50 rounded-lg border border-slate-100" title="Volver">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-xl text-slate-800 leading-tight">Registrar Nueva Categoría</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Crea un nuevo tipo de equipo para el inventario.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 md:p-8 space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Nombre de la Categoría <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: COMPUTADOR DE ESCRITORIO"
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:ring-2 focus:ring-emerald-500 py-3 px-4 text-sm font-bold text-slate-800 uppercase transition-all bg-slate-50 focus:bg-white placeholder-slate-300">
                            
                            @error('name')
                                <p class="text-xs text-rose-500 font-bold mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-[11px] text-slate-400 mt-2 font-medium">Este valor se guardará automáticamente en mayúsculas para estandarizar los filtros.</p>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('categories.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-100 rounded-lg shadow-sm transition-all">
                            Cancelar
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>