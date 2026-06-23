<x-app-layout>
    <div class="py-4 px-2 sm:px-4 max-w-full mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[calc(100vh-130px)] min-h-[500px]">
            
           <div class="p-4 border-b border-gray-100 bg-white shrink-0 space-y-4">
                 <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col sm:flex-row justify-between items-center gap-6 relative overflow-hidden mb-8">
                {{-- mb-8 crea el espacio necesario antes de los KPIs --}}

                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                <div class="flex items-center gap-5 w-full sm:w-auto">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />

                        </svg>

                    </div>
                    <div>
                            <h2 class="font-bold text-xl text-gray-800 tracking-tight uppercase">Directorio de Responsables</h2>
                            <p class="text-xs text-gray-500">Gestión operativa del personal con activos asignados.</p>
                        </div>
                </div>

                <div class="shrink-0 w-full sm:w-auto">
                    <a href="{{ route('custodians.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2 px-4 rounded-xl text-xs transition-all shadow-md shrink-0 text-center uppercase tracking-wide flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Nuevo Responsable
                    </a>
                </div>
            </div>

                <div class="pt-3 border-t border-gray-100 flex flex-col xl:flex-row justify-between gap-4">
                    
                    <form id="search-form" method="GET" action="{{ route('custodians.index') }}" class="w-full xl:w-1/2">
                        @if(request('per_page'))
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        @if(request('quick'))
                            <input type="hidden" name="quick" value="{{ request('quick') }}">
                        @endif

                        <div class="w-full">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Buscador Inteligente</label>
                            <div class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Escribe nombre, documento o área..." 
                                       class="w-full border-gray-200 rounded-2xl py-3 pl-11 pr-4 text-xs font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all shadow-sm text-gray-700 placeholder-gray-400 outline-none">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="flex items-end gap-2 overflow-x-auto pb-1 xl:pb-0 hide-scrollbar w-full xl:w-auto">
                        <a href="{{ route('custodians.index') }}" class="px-5 py-3 rounded-2xl text-xs font-bold whitespace-nowrap transition-all shadow-sm {{ !request('quick') ? 'bg-gray-800 text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200' }}">Todos</a>
                        <a href="{{ route('custodians.index', ['quick' => 'with_assets']) }}" class="px-5 py-3 rounded-2xl text-xs font-bold whitespace-nowrap transition-all shadow-sm {{ request('quick') === 'with_assets' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200' }}">Tienen Equipos</a>
                        <a href="{{ route('custodians.index', ['quick' => 'no_assets']) }}" class="px-5 py-3 rounded-2xl text-xs font-bold whitespace-nowrap transition-all shadow-sm {{ request('quick') === 'no_assets' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200' }}">Sin Equipos</a>
                    </div>

                </div>
            </div>

            <div class="overflow-x-auto overflow-y-auto flex-1 bg-white relative" id="tableContainer">
                <table class="w-full text-sm text-left table-auto whitespace-nowrap">
                    <thead class="text-[11px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 border-b border-gray-100 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center w-16">Estado</th>
                            <th class="px-6 py-3 bg-gray-50">Funcionario / Responsable</th>
                            <th class="px-6 py-3 bg-gray-50">Cargo / Dependencia</th>
                            <th class="px-6 py-3 bg-gray-50 text-center">Equipos</th>
                            <th class="px-6 py-3 bg-gray-50 text-center w-24">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($custodians as $custodian)
                        <tr class="transition-all duration-200 {{ $custodian->status === 'inactive' ? 'bg-gray-50/50 opacity-50 select-none hover:bg-gray-100/50' : 'hover:bg-blue-50/30' }}">
                            
                            <td class="px-6 py-3 text-center">
                                @if($custodian->status === 'active')
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 shadow-sm">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-gray-400 shadow-sm">
                                        <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full border flex items-center justify-center font-bold text-sm shrink-0
                                        {{ $custodian->status === 'active' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-gray-200 text-gray-400 border-gray-300' }}">
                                        {{ mb_substr($custodian->full_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs uppercase {{ $custodian->status === 'inactive' ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                            {{ $custodian->full_name }}
                                            @if($custodian->status === 'inactive')
                                                <span class="ml-2 px-1.5 py-0.5 rounded text-[9px] bg-gray-200 text-gray-600 font-extrabold tracking-wider border border-gray-300">INACTIVO</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-gray-500 font-mono mt-0.5 tracking-tight">CC: {{ $custodian->document_number }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-3">
                                @if($custodian->status === 'active')
                                    <div class="font-bold text-gray-700 text-xs flex items-center gap-1.5 uppercase">
                                        <span class="truncate max-w-[200px]" title="{{ $custodian->jobTitle->name ?? 'Sin Cargo' }}">{{ $custodian->jobTitle->name ?? 'Sin Cargo' }}</span>
                                    </div>
                                    <div class="text-[10px] text-blue-600 font-black uppercase mt-0.5 flex items-center gap-1.5 tracking-wider">
                                        <span class="truncate max-w-[200px]" title="{{ $custodian->dependency->name ?? 'Sin Dependencia' }}">{{ $custodian->dependency->name ?? 'Sin Dependencia' }}</span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-gray-400 italic font-bold uppercase tracking-widest flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        Liberado
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider 
                                    {{ $custodian->assets_count > 0 
                                        ? ($custodian->status === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-500') 
                                        : 'bg-gray-100 text-gray-400' }}">
                                    {{ $custodian->assets_count }}
                                </span>
                            </td>

                            <td class="px-6 py-3 text-center">
                                <a href="{{ route('custodians.edit', $custodian) }}" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 hover:border-blue-400 hover:text-blue-600 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-wide transition-all shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    Editar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400 italic bg-white">
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    No se encontraron responsables con los filtros aplicados.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0 z-30">
                <div class="flex items-center gap-2">
                    <form action="{{ route('custodians.index') }}" method="GET" class="flex items-center gap-2">
                        
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('quick'))
                            <input type="hidden" name="quick" value="{{ request('quick') }}">
                        @endif
                        
                        <label for="per_page_bottom" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mostrar:</label>
                        <select name="per_page" id="per_page_bottom" onchange="this.form.submit()" 
                                class="border-gray-200 rounded-xl py-1.5 pl-3 pr-8 text-xs font-bold text-gray-700 bg-white shadow-sm focus:ring-4 focus:ring-blue-50 focus:border-blue-400 transition-all cursor-pointer">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 filas</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 filas</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 filas</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 filas</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 filas</option>
                            <option value="1000" {{ $perPage == 1000 ? 'selected' : '' }}>Todo</option>
                        </select>
                    </form>
                </div>
                <div class="w-full sm:w-auto font-medium text-xs text-gray-600">
                    {{ $custodians->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>