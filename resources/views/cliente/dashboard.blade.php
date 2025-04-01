<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-4 px-6 space-y-4">
        <a href="{{ route('cliente.ticket.create') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Novo Ticket
        </a>

        <h3 class="text-lg font-semibold">Meus Tickets</h3>

        <form method="GET" class="mb-4 flex flex-wrap gap-3 items-end">
            <div>
                <label for="status" class="block text-sm font-medium">Status</label>
                <select name="status" id="status" class="border rounded px-3 py-2">
                    <option value="">Todos</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Aberto</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Em andamento</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechado</option>
                </select>
            </div>
        
            <div>
                <label for="priority" class="block text-sm font-medium">Prioridade</label>
                <select name="priority" id="priority" class="border rounded px-3 py-2">
                    <option value="">Todas</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Baixa</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Média</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>
        
            <div>
                <label for="search" class="block text-sm font-medium">Título</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="border rounded px-3 py-2" placeholder="Buscar título...">
            </div>
        
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-6">
                    Filtrar
                </button>
            </div>
        </form>

        @if(request()->hasAny(['status', 'priority', 'search']))
            <p class="text-sm text-gray-600">
                <strong>Filtros ativos:</strong>
                @if(request('status')) Status: {{ request('status') }} @endif
                @if(request('priority')) | Prioridade: {{ request('priority') }} @endif
                @if(request('search')) | Título: "{{ request('search') }}" @endif
            </p>
        @endif

        

        @if ($tickets->count())
            <table class="w-full text-sm border rounded">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2">Título</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Prioridade</th>
                        <th class="p-2">Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-2">
                                <a href="{{ route('cliente.ticket.show', $ticket->id) }}" class="text-blue-600 underline">
                                    {{ $ticket->title }}
                                </a>
                            </td>
                            <td class="p-2 capitalize">
                                <span class="
                                    px-2 py-1 rounded text-white text-xs font-semibold
                                    @if ($ticket->status === 'open') bg-yellow-500
                                    @elseif ($ticket->status === 'in_progress') bg-blue-500
                                    @elseif ($ticket->status === 'closed') bg-green-600
                                    @endif
                                ">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="p-2 capitalize">{{ $ticket->priority }}</td>
                            <td class="p-2">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-600">Ainda não tens tickets abertos.</p>
        @endif
    </div>
</x-app-layout>

