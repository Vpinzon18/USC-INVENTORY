<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-[96%] max-w-3xl space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-[.16em] text-amber-700">Cronograma · Solicitud de ampliación</p>
                <h1 class="mt-2 text-2xl font-black text-slate-900">Solicitar nuevo plazo</h1>
                <p class="mt-1 text-sm text-slate-500">La fecha límite no cambiará hasta que un administrador apruebe la solicitud.</p>
            </section>

            @if(session('error'))
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    <p class="font-bold">Revisa la solicitud:</p>
                    <ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Activo</p><p class="mt-1 font-bold text-slate-900">{{ $schedule->asset?->serial_number ?? 'Activo' }} · {{ $schedule->asset?->internal_code ?? 'Sin placa' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4"><p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Fecha límite vigente</p><p class="mt-1 font-bold text-slate-900">{{ $schedule->deadline_at?->format('d/m/Y H:i') ?? 'No definida' }}</p></div>
                </div>

                <form method="POST" action="{{ route('schedules.extensions.store', $schedule) }}" class="mt-6 space-y-5">
                    @csrf
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-slate-600">Nueva fecha y hora solicitada <span class="text-rose-600">*</span></span>
                        <input type="datetime-local" name="requested_deadline_at" required min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('requested_deadline_at', now()->addDay()->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('requested_deadline_at')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-slate-600">Justificación detallada <span class="text-rose-600">*</span></span>
                        <textarea name="justification" rows="6" minlength="20" maxlength="3000" required placeholder="Explica el motivo del retraso, las acciones realizadas y el tiempo adicional que necesitas." class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('justification') }}</textarea>
                        <span class="mt-1 block text-xs text-slate-500">Mínimo 20 caracteres. Esta explicación quedará en el historial de la solicitud.</span>
                        @error('justification')<span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>@enderror
                    </label>
                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('schedules.index') }}" class="inline-flex min-h-10 items-center justify-center rounded-lg border border-slate-200 px-5 text-sm font-bold text-slate-600 hover:bg-slate-50">Cancelar</a>
                        <button type="submit" class="inline-flex min-h-10 items-center justify-center rounded-lg bg-blue-600 px-5 text-sm font-bold text-white shadow-sm hover:bg-blue-700">Enviar solicitud</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
