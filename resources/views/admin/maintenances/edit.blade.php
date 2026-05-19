<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-tight">Editar Registro de Bitácora</h2>
                        <p class="text-sm text-gray-500 font-medium">Corrigiendo intervención del equipo: <span class="text-blue-600 font-black">{{ $maintenance->asset->serial_number }}</span></p>
                    </div>
                </div>
                <form action="{{ route('maintenances.update', $maintenance) }}" method="POST" 
                      x-data="{ 
                          cambioGuaya: {{ $maintenance->security_guaya ? 'true' : 'false' }}, 
                          currentGuaya: '{{ $maintenance->asset->security_guaya ?? 'Sin Guaya Registrada' }}' 
                      }" 
                      class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                   
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Fecha Realizada</label>
                            <input type="date" name="performed_at" value="{{ old('performed_at', \Carbon\Carbon::parse($maintenance->performed_at)->format('Y-m-d')) }}" 
                                   class="w-full border-gray-200 rounded-xl py-3 text-xs font-bold focus:ring-4 focus:ring-blue-50 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Tipo de Servicio</label>
                            <select name="type" class="w-full border-gray-200 rounded-xl py-3 text-xs font-bold focus:ring-4 focus:ring-blue-50 transition-all">
                                <option value="PREVENTIVO" {{ $maintenance->type == 'PREVENTIVO' ? 'selected' : '' }}>PREVENTIVO</option>
                                <option value="CORRECTIVO" {{ $maintenance->type == 'CORRECTIVO' ? 'selected' : '' }}>CORRECTIVO</option>
                                <option value="DIAGNÓSTICO" {{ $maintenance->type == 'DIAGNÓSTICO' ? 'selected' : '' }}>DIAGNÓSTICO</option>
                                <option value="CAMBIO DE RAM" {{ $maintenance->type == 'CAMBIO DE RAM' ? 'selected' : '' }}>CAMBIO DE RAM</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Equipo Intervenido</label>
                            <select class="w-full border-gray-200 rounded-xl py-3 text-xs font-bold bg-gray-100 text-gray-500 cursor-not-allowed" disabled>
                                <option selected>
                                    {{ $maintenance->asset->serial_number }} - {{ $maintenance->asset->hostname }}
                                </option>
                            </select>
                            <input type="hidden" name="asset_id" value="{{ $maintenance->asset_id }}">
                        </div>

                        <div>
                            <label class="flex items-center space-x-3 cursor-pointer mb-2 mt-1 select-none">
                                <input type="checkbox" x-model="cambioGuaya" name="cambio_guaya" value="1"
                                       class="w-4 h-4 rounded border-gray-200 text-blue-600 focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">¿Modificar o Registrar Guaya?</span>
                            </label>
                            <div x-show="cambioGuaya" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="space-y-2">
                                
                                <div class="p-2.5 bg-blue-50/70 rounded-xl border border-blue-100 text-[10px] font-bold text-blue-700 flex justify-between items-center tracking-wider uppercase">
                                    <span>Guaya en Inventario:</span>
                                    <span x-text="currentGuaya" class="bg-blue-600 text-white px-2 py-0.5 rounded font-mono normal-case tracking-normal"></span>
                                </div>

                                <input type="text" 
                                       name="security_guaya" 
                                       value="{{ old('security_guaya', $maintenance->security_guaya) }}"
                                       placeholder="Escriba el serial de la guaya de seguridad..." 
                                       class="w-full border-gray-200 rounded-xl py-3 px-3 text-xs font-bold placeholder-gray-300 bg-gray-50 text-gray-800 focus:ring-4 focus:ring-blue-50 transition-all uppercase"
                                       :required="cambioGuaya">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Descripción del Trabajo Realizado</label>
                            <textarea name="description" rows="5" 
                                      class="w-full border-gray-200 rounded-2xl py-3 px-4 text-xs font-medium focus:ring-4 focus:ring-blue-50 transition-all italic leading-relaxed"
                                      placeholder="Describe detalladamente el mantenimiento...">{{ old('description', $maintenance->description) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end items-center gap-4">
                        <a href="{{ route('maintenances.index') }}" class="text-xs font-black uppercase text-gray-400 hover:text-gray-600 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-3 px-10 rounded-xl text-xs uppercase shadow-xl shadow-blue-100 active:scale-95 transition-all">
                            Actualizar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 