<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl w-fit">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Registrar Nueva Sede</h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Agregue una nueva ubicación principal, campus o convenio.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('campuses.store') }}" class="p-6 md:p-8 flex flex-col gap-6">
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
                        <label for="name" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                            Nombre de la Sede / Convenio <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="Ej: Campus Principal Palmira, Sede Sur, Convenios..." 
                               class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                        
                        <p class="text-[11px] text-slate-400 mt-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Este nombre será visible en las listas de asignación de equipos y movimientos.
                        </p>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('campuses.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Guardar Sede
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>