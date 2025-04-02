<x-app-layout>
    <div class="max-w-xl mx-auto p-6 sm:p-8 text-gray-900 dark:text-white">

        <h2 class="text-2xl font-bold mb-6">Editar Utilizador</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 dark:bg-red-900 dark:text-white rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-semibold mb-1">Nome</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                       class="w-full border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white" required>
            </div>

            <div>
                <label for="email" class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                       class="w-full border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white" required>
            </div>

            <div>
                <label for="role" class="block font-semibold mb-1">Função</label>
                <select name="role" id="role"
                        class="w-full border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white" required>
                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                    <option value="client" @selected($user->role === 'client')>Cliente</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                    ← Voltar para lista
                </a>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Atualizar Utilizador
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
