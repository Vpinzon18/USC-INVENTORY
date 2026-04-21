<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="text-2xl font-bold mb-4">Editar Usuario: {{ $user->name }}</h2>
                
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Nombre</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Rol de Usuario</label>
                        <select name="role" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Administrador (Tú)</option>
                            <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Técnico</option>
                            <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Consulta / Invitado</option>
                        </select>
                    </div>

                    <form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('users.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900 mr-4">
            {{ __('Cancelar') }}
        </a>

        <x-primary-button>
            {{ __('Guardar Cambios') }}
        </x-primary-button>
    </div>
</form>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>