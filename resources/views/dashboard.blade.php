<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Activos Totales</h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $total }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100">
                    <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Equipos Online</h3>
                    <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $online }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100">
                    <h3 class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Modelo Más Utilizado</h3>
                    <p class="text-lg font-bold text-slate-800 mt-2 truncate">{{ $mostUsedModel->model_version ?? 'N/A' }}</p>
                    <p class="text-xs text-blue-600 font-semibold">{{ $mostUsedModel->total ?? 0 }} Equipos</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sedes / Salones</h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalCampuses }} / {{ $totalRooms }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs mb-6">Top 10 Modelos de Equipos</h3>
                <div id="modelChart"></div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Inventario Tecnológico</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-widest border-b border-slate-200">
                            <tr>
                                <th class="py-4 px-6">Equipo / Modelo</th>
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
                                        <div class="text-blue-600 font-bold text-[10px] uppercase">{{ $asset->model_version ?? 'Sin modelo' }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-0.5 text-slate-600">
                                            <span class="font-medium">CPU: {{ $asset->cpu ?? 'N/A' }}</span>
                                            <span>RAM: {{ $asset->ram ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $asset->ip_address }}</td>
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
<div id="chart-data" data-stats='@json($modelStats)' style="display:none;"></div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>

        // Buscamos el div contenedor
    const container = document.getElementById('chart-data');
    
    // Leemos el atributo data-stats
    const chartData = JSON.parse(container.dataset.stats);


        const options = {
            series: [{
                name: 'Equipos',
                data: chartData.map(item => item.total)
            }],
            chart: {
                type: 'bar',
                height: 350,
                animations: { enabled: true, easing: 'easeinout', speed: 800 },
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                    barHeight: '70%',
                }
            },
            colors: ['#2563eb'], // Azul corporativo (Tailwind blue-600)
            xaxis: {
                categories: chartData.map(item => item.model_version),
            },
            dataLabels: {
                enabled: true,
                style: { colors: ['#333'] },
                offsetX: 5
            },
            tooltip: {
                theme: 'light',
                y: { formatter: (val) => `${val} equipos` }
            }
        };

        const chart = new ApexCharts(document.querySelector("#modelChart"), options);
        chart.render();
    </script>
</x-app-layout>