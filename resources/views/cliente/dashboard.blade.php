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

        <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">
            {{-- Filtro por status --}}
            <select name="status" class="border px-3 py-2 pr-10 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todos os status</option>
                <option value="open" @selected(request('status') === 'open')>Aberto</option>
                <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                <option value="closed" @selected(request('status') === 'closed')>Fechado</option>
            </select>
        
            {{-- Filtro por prioridade --}}
            <select name="priority" class="border px-3 py-2 pr-10 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as prioridades</option>
                <option value="low" @selected(request('priority') === 'low')>Baixa</option>
                <option value="medium" @selected(request('priority') === 'medium')>Média</option>
                <option value="high" @selected(request('priority') === 'high')>Alta</option>
            </select>
        
            {{-- Filtro por categoria --}}
            <select name="categoria_id" class="border px-3 py-2 pr-10 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }" @selected(request('categoria_id') == $categoria->id)>
                        {{ $categoria->name }}
                    </option>
                @endforeach
            </select>        
        
            {{-- Pesquisa por título --}}
            <div>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white"
                       placeholder="Procurar ticket...">
            </div>
        
            {{-- Botão de filtro --}}
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
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

