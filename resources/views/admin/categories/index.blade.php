<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-[1000px] w-[96%] mx-auto space-y-4">
            
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                <div class="flex items-center gap-4 pl-2 w-full md:w-auto">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-xl text-slate-800 tracking-tight">Tipos de intervenciones</h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Controla y administra los tipos de intervenciones técnicas ejecutadas durante la atención de incidentes y solicitudes de soporte tecnológico.</p>
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <a href="{{ route('categories.create') }}" class="w-full md:w-auto px-5 py-2.5 text-white font-extrabold text-xs uppercase tracking-widest rounded-xl shadow-md transition-all flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 shadow-blue-200" style="text-decoration: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Nueva Categoría
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm flex items-center gap-3 font-bold text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                
                <form method="GET" action="{{ route('categories.index') }}" class="px-6 py-3 border-b border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar categoría..." 
                               class="w-full pl-9 pr-4 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-medium rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all placeholder-slate-400">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl shadow-md hover:bg-blue-700 text-xs font-bold transition-all">Buscar</button>
                        @if(request('search'))
                            <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-xs font-bold transition-colors shadow-sm">Limpiar</a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto custom-scrollbar w-full">
                    <table class="w-full text-left border-collapse bg-white min-w-[600px]">
                        <thead class="text-xs text-slate-500 uppercase tracking-widest bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="w-20 px-6 py-4 font-black text-center">ID</th>
                                <th class="px-6 py-4 font-black">Nombre de Categoría</th>
                                <th class="w-40 px-6 py-4 font-black text-center">Registro</th>
                                <th class="w-40 px-6 py-4 font-black text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($categories as $category)
                            <tr class="bg-white hover:bg-blue-50/40 transition-colors group">
                                <td class="px-6 py-3.5 font-bold text-slate-400 text-center text-xs">
                                    #{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-3.5 font-extrabold text-slate-800 text-sm uppercase">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-3.5 text-center text-xs text-slate-500 font-medium">
                                    {{ $category->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('categories.edit', $category) }}" 
                                           class="p-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría? Solo se podrá si no tiene equipos vinculados.');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white">
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-10 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">No se encontraron categorías</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($categories->hasPages())
                <div class="px-6 py-3 bg-slate-50 border-t border-slate-100">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>