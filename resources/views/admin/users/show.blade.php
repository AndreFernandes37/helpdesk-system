<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 text-gray-900 dark:text-white">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Perfil de Utilizador</h1>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm">← Voltar</a>
        </div>

        {{-- Info Geral --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4">Informações do Utilizador</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><strong>Nome:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Função:</strong> {{ ucfirst($user->role) }}</div>
                <div>
                    <strong>Status:</strong>
                    <span class="px-2 py-1 rounded text-sm font-semibold
                        {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div><strong>Registado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</div>
                <div><strong>Total de Tickets:</strong> {{ $user->tickets->count() }}</div>
            </div>
        </div>

        {{-- Tickets --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4">Tickets Recentes</h2>
            @if($user->tickets->count())
                <ul class="space-y-2">
                    @foreach($user->tickets->sortByDesc('created_at')->take(5) as $ticket)
                        <li class="flex justify-between items-center border-b border-gray-300 dark:border-gray-700 py-2">
                            <div>
                                <strong>{{ $ticket->title }}</strong><br>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                               class="text-blue-600 text-sm hover:underline">Ver</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Este utilizador ainda não criou nenhum ticket.</p>
            @endif
        </div>
    </div>
</x-app-layout>
