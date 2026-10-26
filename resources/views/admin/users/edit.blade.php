<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto w-[96%] max-w-[1200px] space-y-6">
            <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-600"></div>
                <div class="flex flex-col gap-4 pl-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-600">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536M4 20h4l10.5-10.5a2.5 2.5 0 00-4-4L4 16v4z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[.16em] text-blue-700">Administración · Usuarios</p>
                            <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Editar usuario</h1>
                            <p class="mt-1 text-sm text-slate-500">Actualiza la cuenta de <span class="font-semibold text-slate-700">{{ $user->name }}</span> y su nivel de acceso.</p>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 transition hover:bg-slate-100">Volver a usuarios</a>
                </div>
            </section>

            @if ($errors->any())
                <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 shadow-sm">
                    <p class="text-sm font-extrabold text-rose-800">Revisa la información ingresada:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-rose-700">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
                        <h2 class="text-sm font-black uppercase tracking-wide text-slate-800">Datos de la cuenta</h2>
                        <p class="mt-1 text-xs text-slate-500">Modifica los datos personales y el rol asignado.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Nombre completo <span class="text-rose-500">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" class="w-full rounded-xl border-slate-300 bg-slate-50 text-sm shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            @error('name')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Correo electrónico <span class="text-rose-500">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" class="w-full rounded-xl border-slate-300 bg-slate-50 text-sm shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            @error('email')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="role" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Nivel de acceso <span class="text-rose-500">*</span></label>
                            <select name="role" id="role" required class="w-full rounded-xl border-slate-300 bg-slate-50 text-sm shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                                <option value="3" @selected(old('role', $user->role) == 3)>Consulta · Solo lectura y reportes</option>
                                <option value="2" @selected(old('role', $user->role) == 2)>Técnico · Gestión de inventario y equipos</option>
                                <option value="1" @selected(old('role', $user->role) == 1)>Administrador · Control total del sistema</option>
                            </select>
                            @error('role')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                            @if ($user->id === auth()->id())
                                <p class="mt-2 text-xs font-semibold text-amber-700">Estás editando tu propia cuenta. Cambiar tu rol podría limitar tu acceso actual.</p>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
                        <h2 class="text-sm font-black uppercase tracking-wide text-slate-800">Cambiar contraseña</h2>
                        <p class="mt-1 text-xs text-slate-500">Déjala vacía para conservar la contraseña actual. Solo los campos de contraseña se enviarán enmascarados.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Nueva contraseña</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" placeholder="Dejar vacía para no cambiar" class="w-full rounded-xl border-slate-300 bg-slate-50 pr-20 text-sm shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                                <button type="button" data-toggle-password="password" class="absolute inset-y-0 right-2 my-auto h-8 rounded-lg px-2 text-xs font-bold text-blue-700 hover:bg-blue-50" aria-label="Mostrar contraseña">Mostrar</button>
                            </div>
                            @error('password')<p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Confirmar nueva contraseña</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" placeholder="Repite la nueva contraseña" class="w-full rounded-xl border-slate-300 bg-slate-50 pr-20 text-sm shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                                <button type="button" data-toggle-password="password_confirmation" class="absolute inset-y-0 right-2 my-auto h-8 rounded-lg px-2 text-xs font-bold text-blue-700 hover:bg-blue-50" aria-label="Mostrar contraseña">Mostrar</button>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('users.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-600 transition hover:bg-slate-100">Cancelar</a>
                    <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                if (!input) return;
                const reveal = input.type === 'password';
                input.type = reveal ? 'text' : 'password';
                button.textContent = reveal ? 'Ocultar' : 'Mostrar';
                button.setAttribute('aria-label', `${reveal ? 'Ocultar' : 'Mostrar'} contraseña`);
            });
        });
    </script>
</x-app-layout>
