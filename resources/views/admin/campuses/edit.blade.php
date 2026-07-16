<x-app-layout>
    <div class="py-4 bg-slate-50 min-h-[calc(100vh-4rem)] font-sans flex flex-col" x-data="campusEditor()" x-cloak>
        <div class="max-w-[1200px] w-[96%] mx-auto space-y-4 flex-1">

            <!-- ENCABEZADO COMPACTO -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-col md:flex-row justify-between items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                <div class="flex items-center gap-4 pl-2 w-full md:w-auto">
                    <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-extrabold text-lg text-slate-800 tracking-tight leading-none">Editar: {{ $campus->name }}</h2>
                            <span class="bg-amber-50 text-amber-600 text-[9px] px-2 py-0.5 rounded-md font-black uppercase tracking-widest border border-amber-200 flex items-center gap-1 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Edición
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LAYOUT 70% / 30% COMPACTO -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 relative items-start">
                
                <!-- COLUMNA IZQUIERDA: FORMULARIO (70%) -->
                <div class="lg:col-span-8">
                    <form action="{{ route('campuses.update', $campus) }}" method="POST" id="updateCampusForm" @submit="isSubmitting = true">
                        @csrf
                        @method('PUT')
                        
                        <!-- TARJETA ÚNICA DE FORMULARIO -->
                        <div class="bg-white rounded-xl shadow-sm border {{ $errors->any() ? 'border-rose-300 ring-1 ring-rose-100' : 'border-slate-200' }} overflow-hidden">
                            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Información y Parámetros
                                </h3>
                            </div>
                            
                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Nombre (Ocupa 2 columnas) -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-[10px] font-black text-slate-500 uppercase mb-1.5 tracking-widest">
                                        Nombre de la Sede / Ubicación <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 {{ $errors->has('name') ? 'text-rose-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name', $campus->name) }}" placeholder="Ej: Sede Principal Pampalinda"
                                            class="w-full pl-9 pr-3 py-2.5 rounded-lg text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-2 transition-all shadow-sm border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-50 focus:border-blue-500' }}" required>
                                    </div>
                                    @error('name')
                                        <p class="text-[10px] text-rose-600 mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Dirección -->
                                <div>
                                    <label for="address" class="block text-[10px] font-black text-slate-500 uppercase mb-1.5 tracking-widest">
                                        Dirección Física
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 {{ $errors->has('address') ? 'text-rose-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                        </div>
                                        <input type="text" name="address" id="address" value="{{ old('address', $campus->address) }}" placeholder="Ej: Calle 5 # 62-00"
                                            class="w-full pl-9 pr-3 py-2.5 rounded-lg text-sm font-bold text-slate-800 placeholder-slate-400 focus:ring-2 transition-all shadow-sm border {{ $errors->has('address') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-50 focus:border-blue-500' }}">
                                    </div>
                                    @error('address')
                                        <p class="text-[10px] text-rose-600 mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label for="status" class="block text-[10px] font-black text-slate-500 uppercase mb-1.5 tracking-widest">
                                        Estado Operativo <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 {{ $errors->has('status') ? 'text-rose-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <select name="status" id="status" class="w-full pl-9 pr-8 py-2.5 rounded-lg text-sm font-bold text-slate-800 focus:ring-2 transition-all shadow-sm appearance-none cursor-pointer border {{ $errors->has('status') ? 'border-rose-300 bg-rose-50/50 focus:ring-rose-100 focus:border-rose-500' : 'bg-white border-slate-300 focus:ring-blue-50 focus:border-blue-500' }}">
                                            <option value="Activa" {{ old('status', $campus->status) == 'Activa' ? 'selected' : '' }}>🟢 Activa y Operativa</option>
                                            <option value="Inactiva" {{ old('status', $campus->status) == 'Inactiva' ? 'selected' : '' }}>🔴 Inactiva / Clausurada</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
                                    @error('status')
                                        <p class="text-[10px] text-rose-600 mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- BOTONES DE ACCIÓN (Ahora en la parte inferior) -->
                                <div class="md:col-span-2 mt-2 pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-center gap-4">
                                    <a href="{{ route('campuses.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 hover:text-slate-900 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-sm flex justify-center items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Cancelar
                                    </a>
                                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-75 cursor-not-allowed': isSubmitting}" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-200 transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 flex justify-center items-center gap-2">
                                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                            Guardar Cambios
                                        </span>
                                        <span x-show="isSubmitting" class="flex items-center gap-2" x-cloak>
                                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Procesando...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- COLUMNA DERECHA: PANEL LATERAL INFORMATIVO (30%) -->
                <div class="lg:col-span-4 hidden lg:block">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
                        <!-- Borde de color superior (Azul institucional) -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>

                        <div class="p-5 pt-6">
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                                <div class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-slate-700 shrink-0 shadow-inner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-slate-900 tracking-tight leading-tight">{{ $campus->name }}</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Sede Registrada</p>
                                </div>
                            </div>

                            <!-- Listado de Relaciones / Indicadores Compacto -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center py-1.5">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Bloques</span>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-800">{{ method_exists($campus, 'buildings') ? $campus->buildings()->count() : 0 }}</span>
                                </div>

                                <div class="flex justify-between items-center py-1.5">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Oficinas</span>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-800">{{ method_exists($campus, 'rooms') ? $campus->rooms()->count() : 0 }}</span>
                                </div>

                                <div class="flex justify-between items-center py-1.5">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Activos</span>
                                    </div>
                                    <span class="text-xs font-extrabold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">{{ method_exists($campus, 'assets') ? $campus->assets()->count() : 0 }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest flex items-center justify-center gap-1">
                                    Última Modificación
                                </p>
                                <p class="text-xs font-bold text-slate-600 mt-0.5">{{ $campus->updated_at ? $campus->updated_at->format('d M Y - h:i A') : 'No registrada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('campusEditor', () => ({
                isSubmitting: false
            }));
        });
    </script>
</x-app-layout>