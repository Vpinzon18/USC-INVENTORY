<x-app-layout>
    @php
        $network = $asset->network;
        $primaryAdapter = $network?->adapters->firstWhere('is_primary', true) ?? $network?->adapters->first();
        $ramTotal = $asset->ramModules->isNotEmpty() ? $asset->ramModules->sum('capacity_gb') : $asset->ram_capacity_gb;
    @endphp
    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-6">
            <a href="{{ route('admin.agent-inventory.index') }}" class="text-sm font-bold text-blue-700 hover:underline">← Volver al inventario de agente</a>
            @if (session('status'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>@endif

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-700">SIGMA Agent · Equipo #{{ $asset->id }}</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-900">{{ $asset->hostname ?: 'Equipo sin hostname' }}</h1>
                        <p class="mt-1 text-sm text-slate-500">{{ trim(($asset->manufacturer ?: '').' '.($asset->model_version ?: '')) ?: 'Fabricante y modelo sin registrar' }}</p>
                    </div>
                    @if ($asset->is_online)<span class="rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-bold text-emerald-700">Agente en línea</span>@else<span class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-bold text-slate-600">Agente fuera de línea</span>@endif
                </div>
                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold uppercase text-slate-500">Código / serial</p><p class="mt-1 font-mono text-sm text-slate-900">{{ $asset->internal_code ?: $asset->serial_number ?: '—' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold uppercase text-slate-500">Último heartbeat</p><p class="mt-1 text-sm text-slate-900">{{ $asset->agent_last_seen?->format('d/m/Y H:i:s') ?: 'Sin heartbeat' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold uppercase text-slate-500">Versión del agente</p><p class="mt-1 text-sm text-slate-900">{{ $asset->agent_version ?: '—' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs font-bold uppercase text-slate-500">Último inventario</p><p class="mt-1 text-sm text-slate-900">{{ $asset->last_inventory_at?->format('d/m/Y H:i:s') ?: 'Sin reporte' }}</p></div>
                </div>
            </section>

            <section class="rounded-2xl border border-violet-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div><p class="text-xs font-bold uppercase tracking-[.16em] text-violet-700">Asistencia remota</p><h2 class="mt-1 text-xl font-black text-slate-900">RustDesk Open Source</h2><p class="mt-1 max-w-3xl text-sm text-slate-500">SIGMA guarda el ID de equipo, nunca la contraseña del cliente. El equipo remoto debe tener RustDesk instalado y configurado con el mismo servidor y clave pública.</p></div>
                    @if ($asset->rustdesk_id)<a href="rustdesk://{{ $asset->rustdesk_id }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-violet-700 px-4 text-sm font-bold text-white hover:bg-violet-800">Abrir RustDesk</a>@endif
                </div>
                <div class="mt-4 flex flex-wrap items-end gap-3">
                    <label class="min-w-[260px] flex-1"><span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">ID RustDesk</span><input id="rustdesk-id" type="text" value="{{ $asset->rustdesk_id ?: '' }}" readonly class="w-full rounded-xl border-slate-300 bg-slate-50 font-mono text-sm text-slate-800" placeholder="Sin configurar"></label>
                    @if ($asset->rustdesk_id)<button type="button" onclick="navigator.clipboard.writeText(document.getElementById('rustdesk-id').value)" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50">Copiar ID</button>@endif
                </div>
                @if ((int) auth()->user()->role === 1)
                    <form method="POST" action="{{ route('admin.agent-inventory.rustdesk.update', $asset) }}" class="mt-4 flex flex-wrap items-end gap-3 border-t border-slate-100 pt-4">
                        @csrf @method('PATCH')
                        <label class="min-w-[260px] flex-1"><span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Registrar o cambiar ID</span><input name="rustdesk_id" value="{{ old('rustdesk_id', $asset->rustdesk_id) }}" maxlength="64" pattern="[A-Za-z0-9-]+" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="ID asignado por el cliente RustDesk">@error('rustdesk_id')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror</label>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-bold text-white hover:bg-slate-700">Guardar ID</button>
                    </form>
                @endif
                <p class="mt-3 text-xs text-slate-500">Confirma la identidad del equipo antes de aceptar la sesión y sigue el procedimiento de autorización de soporte de la USC.</p>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900">Procesador y memoria</h2>
                    <dl class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div><dt class="text-xs font-bold uppercase text-slate-500">CPU</dt><dd class="mt-1 text-sm text-slate-800">{{ trim(($asset->cpu_brand ?: '').' '.($asset->cpu_model ?: '')) ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Núcleos / hilos</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->cpu_cores ?? '—' }} / {{ $asset->cpu_threads ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Arquitectura</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->cpu_architecture ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">RAM total</dt><dd class="mt-1 text-sm text-slate-800">{{ $ramTotal !== null ? number_format((float) $ramTotal, 0).' GB' : ($asset->ram ?: '—') }}</dd></div>
                    </dl>
                    <h3 class="mt-6 text-sm font-bold text-slate-700">Módulos RAM</h3>
                    <div class="mt-2 overflow-x-auto"><table class="w-full text-left text-xs"><thead class="text-slate-500"><tr><th class="py-2">Slot</th><th class="py-2">Fabricante / modelo</th><th class="py-2">Tipo</th><th class="py-2">Capacidad</th><th class="py-2">Velocidad</th></tr></thead><tbody class="divide-y divide-slate-100">
                        @forelse ($asset->ramModules as $module)<tr><td class="py-2">{{ $module->slot ?: $module->bank ?: '—' }}</td><td class="py-2">{{ trim(($module->manufacturer ?: '').' '.($module->model ?: '')) ?: '—' }}</td><td class="py-2">{{ $module->memory_type ?: '—' }}</td><td class="py-2">{{ $module->capacity_gb ? $module->capacity_gb.' GB' : '—' }}</td><td class="py-2">{{ $module->speed_mhz ? $module->speed_mhz.' MHz' : '—' }}</td></tr>@empty<tr><td colspan="5" class="py-3 text-slate-500">Sin módulos desglosados.</td></tr>@endforelse
                    </tbody></table></div>
                    @if ($asset->processors->isNotEmpty())<h3 class="mt-6 text-sm font-bold text-slate-700">Procesadores detectados</h3><ul class="mt-2 space-y-1 text-sm text-slate-600">@foreach ($asset->processors as $processor)<li>{{ trim(($processor->brand ?: '').' '.($processor->model ?: '')) }} · {{ $processor->cores ?? '—' }} núcleos</li>@endforeach</ul>@endif
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900">BIOS y placa base</h2>
                    <dl class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Fabricante BIOS</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->bios_manufacturer ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Versión BIOS</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->bios_version ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Serial BIOS</dt><dd class="mt-1 font-mono text-sm text-slate-800">{{ $asset->bios_serial ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Fecha BIOS</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->bios_release_date ?: $asset->bios_date ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Fabricante placa</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->board_brand ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Modelo placa</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->board_model ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Serial placa</dt><dd class="mt-1 font-mono text-sm text-slate-800">{{ $asset->board_serial ?: '—' }}</dd></div>
                    </dl>
                    <h2 class="mt-7 text-lg font-black text-slate-900">Sistema operativo y seguridad</h2>
                    <dl class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Windows</dt><dd class="mt-1 text-sm text-slate-800">{{ trim(($asset->windows_edition ?: $asset->windows_version ?: '').' '.($asset->windows_build ?: '')) ?: ($asset->os_version ?: '—') }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Usuario reportado</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->logged_user ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">TPM / BitLocker</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->tpm_version ?: '—' }} / {{ $asset->bitlocker_status ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Secure Boot / Firewall</dt><dd class="mt-1 text-sm text-slate-800">{{ $asset->secure_boot ?: '—' }} / {{ $asset->firewall_enabled ? 'Activo' : 'No reportado / desactivado' }}</dd></div>
                    </dl>
                </section>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-lg font-black text-slate-900">Red</h2><p class="mt-1 text-xs text-slate-500">Gateway: {{ $network?->gateway ?: '—' }} · DNS: {{ $network?->dns_server ?: '—' }}</p><div class="mt-3 space-y-2">
                    @forelse ($network?->adapters ?? [] as $adapter)<article class="rounded-xl bg-slate-50 p-3 text-sm"><div class="font-bold text-slate-800">{{ $adapter->name ?: $adapter->description ?: 'Adaptador de red' }} @if($adapter->is_primary)<span class="ml-1 text-xs text-blue-700">Principal</span>@endif</div><div class="mt-1 grid grid-cols-1 gap-1 text-xs text-slate-600 sm:grid-cols-2"><span>IPv4: <span class="font-mono">{{ $adapter->ipv4 ?: '—' }}</span></span><span>IPv6: <span class="font-mono">{{ $adapter->ipv6 ?: '—' }}</span></span><span>MAC: <span class="font-mono">{{ $adapter->mac_address ?: '—' }}</span></span><span>Estado: {{ $adapter->connected ? 'Conectado' : ($adapter->is_active ? 'Activo' : 'Inactivo') }}</span></div></article>@empty<p class="py-3 text-sm text-slate-500">Sin adaptadores registrados.</p>@endforelse
                </div><p class="mt-3 text-xs text-slate-500">Dirección destacada: {{ $primaryAdapter?->ipv4 ?: '—' }}</p></section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-lg font-black text-slate-900">Almacenamiento</h2><div class="mt-3 space-y-2">
                    @forelse ($asset->storageDevices as $device)<article class="rounded-xl bg-slate-50 p-3 text-sm"><div class="font-bold text-slate-800">{{ trim(($device->brand ?: '').' '.($device->model ?: '')) ?: 'Unidad de almacenamiento' }}</div><div class="mt-1 grid grid-cols-2 gap-1 text-xs text-slate-600"><span>Tipo: {{ $device->type ?: '—' }}</span><span>Capacidad: {{ $device->capacity_gb ? $device->capacity_gb.' GB' : '—' }}</span><span>Libre: {{ $device->free_gb ? $device->free_gb.' GB' : '—' }}</span><span>Salud: {{ $device->health ?: '—' }}</span><span class="col-span-2">Serial: {{ $device->serial_number ?: '—' }}</span></div></article>@empty<p class="py-3 text-sm text-slate-500">Sin unidades desglosadas.</p>@endforelse
                </div>@if ($asset->gpus->isNotEmpty())<h3 class="mt-6 text-sm font-bold text-slate-700">GPU</h3><ul class="mt-2 space-y-1 text-sm text-slate-600">@foreach ($asset->gpus as $gpu)<li>{{ trim(($gpu->brand ?: '').' '.($gpu->model ?: '')) }} @if($gpu->memory_mb)· {{ number_format($gpu->memory_mb / 1024, 1) }} GB@endif</li>@endforeach</ul>@endif @if ($asset->battery)<p class="mt-4 text-sm text-slate-600">Batería: {{ $asset->battery->health ?: 'salud no reportada' }} · {{ $asset->battery->design_capacity ?: '—' }} diseño</p>@endif</section>
            </div>
        </div>
    </div>
</x-app-layout>
