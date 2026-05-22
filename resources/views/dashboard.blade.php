<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - Gestión Tecnológica USC') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 font-bold text-lg">Equipos en Inventario (Total: {{ $assets->count() }})</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-200 text-xs">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border p-2">Hostname</th>
                                    <th class="border p-2">IP</th>
                                    <th class="border p-2">Serial</th>
                                    <th class="border p-2">Hardware (CPU/RAM/HDD)</th>
                                    <th class="border p-2">Red (MAC/Dominio)</th>
                                    <th class="border p-2">S.O.</th>
                                    <th class="border p-2">Software Instalado</th>
                                    <th class="border p-2">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assets as $asset)
                                <tr class="hover:bg-gray-50 text-center">
                                    <td class="border p-2 font-bold">{{ $asset->hostname }}</td>
                                    <td class="border p-2">{{ $asset->ip_address }}</td>
                                    <td class="border p-2">{{ $asset->serial_number }}</td>
                                    <td class="border p-2">
                                        {{ $asset->cpu }} / {{ $asset->ram }} / {{ $asset->storage }}
                                    </td>
                                    <td class="border p-2">
                                        {{ $asset->mac_address ?? 'N/A' }} <br>
                                        <span class="text-blue-600 font-semibold">{{ $asset->domain_name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="border p-2 italic">{{ $asset->os_version ?? 'N/A' }}</td>
                                    
                                    <td class="border p-2 text-left">
                                        <div class="max-h-24 overflow-y-auto">
                                            @forelse($asset->software as $app)
                                                <span class="block text-[10px] text-gray-600 truncate" title="{{ $app->name }} (v{{ $app->version }})">
                                                    • {{ $app->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 italic">Sin datos</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="border p-2">
                                        @if($asset->last_seen_at && \Carbon\Carbon::parse($asset->last_seen_at)->diffInMinutes() < 10)
                                            <span class="text-green-600 font-bold">● En línea</span>
                                        @else
                                            <span class="text-gray-400">Desconectado</span>
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
    </div>
</x-app-layout>