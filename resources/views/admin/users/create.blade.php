<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="p-6 md:p-8 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="p-3 bg-violet-100 text-violet-600 rounded-xl w-fit">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">Registrar Nuevo Usuario</h2>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Cree una nueva cuenta y asigne su nivel de acceso al sistema.</p>
                    </div>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="p-6 md:p-8 flex flex-col gap-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-sm font-bold text-red-800">No se pudo registrar el usuario:</h3>
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
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                   placeholder="Ej: Juan Pérez" 
                                   class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="ejemplo@institucion.edu.co"
                                   class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                        </div>

                        <div>
                            <label for="role" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                Nivel de Acceso (Rol) <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role" required
                                    class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Seleccione un Rol --</option>
                                <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>Consulta (Solo Lectura y Reportes)</option>
                                <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Técnico (Gestión de Inventario y Equipos)</option>
                                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Administrador (Control Total del Sistema)</option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                El rol determina a qué módulos y botones tendrá acceso este usuario.
                            </p>
                        </div>

                        <hr class="border-slate-100 my-2">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                    Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input id="password" type="password" name="password" required 
                                       placeholder="Mínimo 8 caracteres"
                                       class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-800 uppercase mb-2">
                                    Confirmar Contraseña <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required 
                                       placeholder="Repita la contraseña"
                                       class="w-full bg-slate-50 border-slate-200 text-slate-900 text-sm rounded-lg focus:ring-2 focus:ring-violet-100 focus:border-violet-500 focus:bg-white px-4 py-3 shadow-inner transition-all">
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('users.index') }}" class="px-6 py-2.5 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="submit" class="px-8 py-2.5 bg-violet-600 text-white text-xs font-bold uppercase rounded-xl shadow-md hover:bg-violet-700 hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-violet-600 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Registrar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>