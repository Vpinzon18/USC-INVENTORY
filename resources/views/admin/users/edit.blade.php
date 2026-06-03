<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="p-3 bg-violet-100 text-violet-600 rounded-xl w-fit">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">
                            Editar Usuario: <span class="text-violet-600">{{ $user->name }}</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Modifique los datos principales o cambie el nivel de acceso al sistema.</p>
                    </div>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST" class="p-6 md:p-8 flex flex-col gap-6">
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
                            <label for="name" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                        </div>

                        <div>
                            <label for="role" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Nivel de Acceso (Rol) <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role" required
                                    class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                                <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Consulta (Solo Lectura y Reportes)</option>
                                <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Técnico (Gestión de Inventario y Equipos)</option>
                                <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Administrador (Control Total del Sistema)</option>
                            </select>
                            
                            @if($user->id === auth()->id())
                                <p class="text-[11px] font-bold text-amber-600 mt-2 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Atención: Estás editando tu propio usuario. Cambiar tu rol podría limitar tu acceso actual.
                                </p>
                            @endif
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('users.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit" class="px-8 py-2.5 bg-violet-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-violet-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-violet-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>