<section>
    <header>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
            Actualizar Contraseña
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <!-- Contraseña Actual -->
        <div class="relative">
            <x-input-label for="update_password_current_password" :value="__('Contraseña Actual')" class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-1.5" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all text-sm py-2.5 px-4" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-500 text-xs font-medium" />
        </div>

        <!-- Nueva Contraseña -->
        <div class="relative">
            <x-input-label for="update_password_password" :value="__('Nueva Contraseña')" class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-1.5" />
            <x-text-input id="update_password_password" name="password" type="password" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all text-sm py-2.5 px-4" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-500 text-xs font-medium" />
        </div>

        <!-- Confirmar Contraseña -->
        <div class="relative">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Contraseña')" class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-1.5" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all text-sm py-2.5 px-4" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-500 text-xs font-medium" />
        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all shadow-md shadow-blue-500/20">
                Guardar Cambios
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Guardado
                </p>
            @endif
        </div>
    </form>
</section>