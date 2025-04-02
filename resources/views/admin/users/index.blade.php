<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 text-gray-900 dark:text-white">

        <h1 class="text-2xl font-bold mb-6">Gestão de Utilizadores</h1>

        {{-- Filtros --}}
        <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white"
                   placeholder="Pesquisar por nome ou email...">

            <select name="role" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as funções</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="client" @selected(request('role') === 'client')>Cliente</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filtrar
            </button>
        </form>

        {{-- Tabela --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                    <tr>
                        <th class="py-2 px-4 text-left">Nome</th>
                        <th class="py-2 px-4 text-left">Email</th>
                        <th class="py-2 px-4 text-left">Função</th>
                        <th class="py-2 px-4 text-left">Status</th>
                        <th class="py-2 px-4 text-left">Criado em</th>
                        <th class="py-2 px-4 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-t border-gray-200 dark:border-gray-600">
                            <td class="py-2 px-4">{{ $user->name }}</td>
                            <td class="py-2 px-4">{{ $user->email }}</td>
                            <td class="py-2 px-4 capitalize">{{ $user->role }}</td>
                            <td class="py-2 px-4">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>                            
                            <td class="py-2 px-4">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="flex items-center gap-3">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="flex items-center gap-1 text-blue-600 hover:underline text-sm">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    Editar
                                </a>
                            
                                <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="flex items-center gap-1 px-2 py-1 rounded text-sm text-white
                                               {{ $user->active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                        <i data-lucide="{{ $user->active ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                                        {{ $user->active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                            </td>
                            
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                Nenhum utilizador encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
