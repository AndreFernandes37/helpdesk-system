<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Todos os Tickets
        </h2>
    </x-slot>

    <div class="px-6 py-4 space-y-4">
        <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">

            <select name="status" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todos os status</option>
                <option value="open" @selected(request('status') === 'open')>Aberto</option>
                <option value="pending" @selected(request('status') === 'pending')>Pendente</option>
                <option value="closed" @selected(request('status') === 'closed')>Fechado</option>
            </select>
        
            <select name="priority" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as prioridades</option>
                <option value="low" @selected(request('priority') === 'low')>Baixa</option>
                <option value="medium" @selected(request('priority') === 'medium')>Média</option>
                <option value="high" @selected(request('priority') === 'high')>Alta</option>
            </select>
        
            <select name="categoria_id" class="border px-3 pr-10 py-2 rounded-md dark:bg-gray-800 dark:text-white">
                <option value="">Todas as categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                        {{ $categoria->name }}
                    </option>
                @endforeach
            </select>
        
            <input type="text" name="search" value="{{ request('search') }}"
                   class="border px-3 py-2 rounded-md dark:bg-gray-800 dark:text-white"
                   placeholder="Procurar ticket...">
        
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filtrar
            </button>
        </form>
        

        <table class="w-full text-sm border rounded">
            <thead>
                <tr class="bg-gray-100 text-left">
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
    </div>
</x-app-layout>
