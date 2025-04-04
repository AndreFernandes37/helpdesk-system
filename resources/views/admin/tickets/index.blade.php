<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Todos os Tickets
        </h2>
    </x-slot>

    <div class="px-6 py-4 space-y-4">
        <!-- Filtros -->
        <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">

            <!-- Status -->
            <select name="status" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todos os status</option>
                <option value="open" @selected(request('status') === 'open')>Aberto</option>
                <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                <option value="closed" @selected(request('status') === 'closed')>Fechado</option>
            </select>

            <!-- Prioridade -->
            <select name="priority" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as prioridades</option>
                <option value="low" @selected(request('priority') === 'low')>Baixa</option>
                <option value="medium" @selected(request('priority') === 'medium')>Média</option>
                <option value="high" @selected(request('priority') === 'high')>Alta</option>
            </select>

            <!-- Categoria -->
            <select name="categoria_id" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                        {{ $categoria->name }}
                    </option>
                @endforeach
            </select>

            <!-- Pesquisa -->
            <input type="text" name="search" value="{{ request('search') }}"
                   class="border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white"
                   placeholder="Procurar ticket...">

            <!-- Botão de Filtrar -->
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filtrar
            </button>
        </form>

        <!-- Tabela de Tickets -->
        <table class="w-full text-sm border rounded">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th></th>
                    <th class="p-2">Título</th>
                    <th class="p-2">Cliente</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Prioridade</th>
                    <th class="p-2">Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">
                            @if ($ticket->read)
                                <!-- Ícone de carta aberta (ticket lido) -->
                                <i data-lucide="mail-open" class="w-4 h-4 text-green-600"></i>
                            @else
                                <!-- Ícone de carta fechada (ticket não lido) -->
                                <i data-lucide="mail" class="w-4 h-4 text-red-600"></i>
                            @endif
                        </td>
                        
                        
                        
                        <td class="p-2">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-blue-600 underline hover:text-blue-800">
                                {{ $ticket->title }}
                            </a>
                        </td>
                        <td class="p-2">{{ $ticket->user->username }}</td>
                        <td class="p-2 capitalize">
                            <span class="px-2 py-1 rounded text-white text-xs font-semibold
                                @if ($ticket->status === 'open') bg-yellow-500
                                @elseif ($ticket->status === 'in_progress') bg-blue-500
                                @elseif ($ticket->status === 'closed') bg-green-600
                                @endif">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        
                        <td class="p-2 capitalize">{{ $ticket->priority }}</td>
                        <td class="p-2">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-2 text-gray-600">Nenhum ticket encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
</x-app-layout>
