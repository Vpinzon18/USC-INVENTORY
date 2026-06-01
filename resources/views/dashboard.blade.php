<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Panel de Control USC</h2>
                    <p class="text-sm text-slate-500">Resumen operativo del Sistema de Gestión de Activos</p>
                </div>
                <div class="text-right text-xs text-slate-400 font-medium">
                    {{ now()->format('d/m/Y | H:i') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Activos Totales</h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $total }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100">
                    <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Equipos Online</h3>
                    <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $online }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Usuarios</h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sedes / Salones</h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalCampuses }} / {{ $totalRooms }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs mb-4">Distribución por Sede</h3>
                    <canvas id="campusChart" height="150"></canvas>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs mb-4">Estado de Conectividad</h3>
                    <canvas id="connectionChart" height="150"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Inventario Tecnológico</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-widest border-b border-slate-200">
                                <th class="py-4 px-6">Equipo / Hostname</th>
                                <th class="py-4 px-6">Hardware</th>
                                <th class="py-4 px-6">Red</th>
                                <th class="py-4 px-6 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($assets as $asset)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800">{{ $asset->hostname ?? 'N/A' }}</div>
                                        <div class="text-slate-400 font-mono">{{ $asset->serial_number }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-0.5 text-slate-600">
                                            <span class="font-medium">CPU: {{ $asset->cpu ?? 'N/A' }}</span>
                                            <span>RAM: {{ $asset->ram ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">
                                        {{ $asset->ip_address }}
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $asset->mac_address ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->gt(now()->subMinutes(10)))
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase bg-emerald-100 text-emerald-700">En Línea</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase bg-slate-100 text-slate-500">Offline</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="campus-data-container" 
     data-json='@json($assetsByCampus)' 
     style="display:none;">
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ahora leemos los datos desde el HTML, no desde Blade. 
    // ¡Tu editor dejará de marcar errores porque esto es JS puro!
    const container = document.getElementById('campus-data-container');
    const campusData = JSON.parse(container.dataset.json);

    const canvas = document.getElementById('campusChart');
    if (canvas) {
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: campusData.map(c => c.name),
                datasets: [{ 
                    label: 'Equipos', 
                    data: campusData.map(c => c.total), 
                    backgroundColor: '#3b82f6' 
                }]
            },
            options: { responsive: true }
        });
    }
</script>
</x-app-layout>