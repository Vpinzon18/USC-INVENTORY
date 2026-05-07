<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center">
                        <a href="{{ route('custodians.index') }}" class="mr-4 text-gray-400 hover:text-blue-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </a>
                        <div>
                            <h2 class="font-bold text-2xl text-gray-800 leading-tight">Registrar Nuevo Responsable</h2>
                            <p class="text-sm text-gray-500 mt-1">Ingrese los datos del Director, Jefe o Coordinador de área.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('custodians.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre Completo</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   placeholder="Ej: Juan Pérez" required>
                            @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Documento</label>
                            <input type="text" name="document_number" value="{{ old('document_number') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   placeholder="CC / NIT" required>
                            @error('document_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cargo</label>
                            <input type="text" name="job_title" value="{{ old('job_title') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   placeholder="Ej: Director de Facultad" required>
                            @error('job_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dependencia / Área</label>
                            <input type="text" name="dependency" value="{{ old('dependency') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   placeholder="Ej: Facultad de Ingeniería" required>
                            @error('dependency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   placeholder="usuario@usc.edu.co">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Extensión Telefónica</label>
                            <input type="text" name="extension" value="{{ old('extension') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                                   placeholder="Ej: 1234">
                            @error('extension') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end space-x-4">
                        <a href="{{ route('custodians.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-800 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">
                            Guardar Responsable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>