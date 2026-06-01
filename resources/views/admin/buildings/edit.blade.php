<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="p-3 bg-amber-100 text-amber-600 rounded-xl w-fit">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Editar Bloque</h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Actualice el nombre o reasigne este bloque a otra sede.</p>
                    </div>
                </div>

                <form action="{{ route('buildings.update', $building) }}" method="POST" class="p-6 md:p-8 flex flex-col gap-6">
                    @csrf
                    @method('PUT')
                    
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

                    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm transition-all hover:shadow-md flex flex-col gap-5">
                        
                        <div>
                            <label for="campus_id" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Sede a la que pertenece <span class="text-red-500">*</span>
                            </label>
                            <select name="campus_id" id="campus_id" required
                                    class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                                <option value="" disabled>-- Seleccione la Sede --</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id', $building->campus_id) == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="name" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Nombre del Bloque <span class="text-red-500">*</span>
                            </label>
                            <input id="name" type="text" name="name" value="{{ old('name', $building->name) }}" required 
                                   class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('buildings.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Actualizar Bloque
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>