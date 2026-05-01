<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nueva Sede</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('campuses.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nombre de la Sede')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required placeholder="Ej: Palmira o Cali" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <x-primary-button>Guardar Sede</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>