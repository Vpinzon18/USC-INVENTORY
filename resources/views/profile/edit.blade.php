<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Mi Perfil</h2>
                    <p class="text-sm text-slate-500 mt-1">Administra tu información personal y la seguridad de tu cuenta.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2" x-data="{ activeTab: 'perfil' }">
                    
                    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 overflow-hidden transition-all">
                        
                        <div class="flex border-b border-slate-100 bg-slate-50/50 px-2">
                            <button @click="activeTab = 'perfil'" 
                                    :class="activeTab === 'perfil' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" 
                                    class="px-6 py-4 font-extrabold text-xs uppercase tracking-widest border-b-2 transition-colors focus:outline-none">
                                Datos Generales
                            </button>
                            <button @click="activeTab = 'seguridad'" 
                                    :class="activeTab === 'seguridad' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" 
                                    class="px-6 py-4 font-extrabold text-xs uppercase tracking-widest border-b-2 transition-colors focus:outline-none">
                                Seguridad y Contraseña
                            </button>
                        </div>

                        <div class="p-6 sm:p-8">
                            <div x-show="activeTab === 'perfil'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                <div class="max-w-xl">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div>

                            <div x-show="activeTab === 'seguridad'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
                                <div class="max-w-xl">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="p-6 sm:p-8 bg-red-50/50 shadow-sm rounded-2xl border border-red-100 transition-all hover:shadow-md hover:border-red-200">
                        <div class="max-w-full">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>