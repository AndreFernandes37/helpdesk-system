<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Ticket') }}
        </h2>
    </x-slot>

    <div class="py-4 px-6 space-y-4">
        <a href="{{ route('cliente.dashboard') }}" class="text-blue-600 underline">← Voltar</a>

        <h3 class="text-lg font-semibold">{{ $ticket->title }}</h3>

        <p><strong>Status:</strong> <span class="
            px-2 py-1 rounded text-white text-xs font-semibold
            @if ($ticket->status === 'open') bg-yellow-500
            @elseif ($ticket->status === 'in_progress') bg-blue-500
            @elseif ($ticket->status === 'closed') bg-green-600
            @endif
        ">
            {{ ucfirst($ticket->status) }}
        </span></p>
        <p><strong>Prioridade:</strong> <span class="capitalize">{{ $ticket->priority }}</span></p>
        <p><strong>Descrição:</strong><br>{{ $ticket->description }}</p>
        <p class="text-sm text-gray-500">Criado em: {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <hr class="my-6">

    <h4 class="text-md font-semibold mb-2">Respostas</h4>

    <div class="space-y-3 mt-6">
        @forelse ($ticket->respostas as $resposta)
            <div class="flex 
                @if ($resposta->user_id === auth()->id())
                    justify-end
                @else
                    justify-start
                @endif
            ">
                <div class="max-w-[70%] px-4 py-2 rounded-2xl shadow
                    @if ($resposta->user_id === auth()->id())
                        bg-green-500 text-white rounded-br-none
                    @else
                        bg-gray-200 text-gray-900 rounded-bl-none
                    @endif
                ">
                    <p class="text-sm font-semibold mb-1">
                        {{ $resposta->user->name }}
                        <span class="text-xs text-gray-100/80 ml-2">
                            {{ $resposta->created_at->format('d/m/Y H:i') }}
                        </span>
                    </p>
                    <p class="text-sm leading-snug">{{ $resposta->content }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Nenhuma resposta ainda.</p>
        @endforelse
    </div>
    

    <hr class="my-6">

    <h4 class="text-md font-semibold mb-2">Responder</h4>

    <form action="{{ route('cliente.ticket.reply', $ticket->id) }}" method="POST" class="space-y-4">
        @csrf
        <textarea name="content" rows="4" class="w-full border rounded px-3 py-2" required placeholder="Escreva sua resposta..."></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Enviar
        </button>
    </form>

</x-app-layout>
