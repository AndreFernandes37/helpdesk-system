<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Detalhes do Ticket
        </h2>
    </x-slot>

    <div class="px-6 py-4 space-y-6">

        <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 underline">← Voltar</a>

        <div class="border rounded p-4 bg-white shadow">
            <h3 class="text-lg font-bold">{{ $ticket->title }}</h3>
            <p><strong>Cliente:</strong> {{ $ticket->user->name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
            <p><strong>Prioridade:</strong> {{ ucfirst($ticket->priority) }}</p>
            <p><strong>Descrição:</strong><br>{{ $ticket->description }}</p>
            <p class="text-sm text-gray-500">Criado em: {{ $ticket->created_at->format('d/m/Y H:i') }}</p>

            <form method="POST" action="{{ route('admin.tickets.status', $ticket->id) }}" class="mt-4">
                @csrf
                <label for="status" class="block font-semibold mb-1">Alterar status:</label>
                <select name="status" id="status" class="border px-3 py-2 rounded">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Aberto</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Em andamento</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Fechado</option>
                </select>
                <button type="submit" class="ml-2 bg-white text-black border px-4 py-2 rounded hover:bg-gray-200">
                    Atualizar
                </button>
                
            </form>
        </div>

        <hr>

        <h4 class="text-md font-semibold">Respostas</h4>

        <div x-data x-init="$nextTick(() => {
            const bottom = document.getElementById('scroll-bottom');
            bottom?.scrollIntoView({ behavior: 'auto' });
        })"
             class="h-[400px] overflow-y-auto space-y-3 mt-6 pr-2">
            
            @forelse ($ticket->respostas as $resposta)
                <div class="flex items-start gap-3
                    @if ($resposta->user_id === auth()->id())
                        justify-end
                    @else
                        justify-start
                    @endif
                ">
                    {{-- Avatar --}}
                    @if ($resposta->user_id !== auth()->id())
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold dark:bg-blue-400">
                            {{ strtoupper(substr($resposta->user->name, 0, 2)) }}
                        </div>
                    @endif
        
                    {{-- Balão de mensagem --}}
                    <div class="max-w-[70%] px-4 py-2 rounded-2xl shadow
                        @if ($resposta->user_id === auth()->id())
                            bg-green-500 text-white rounded-br-none dark:bg-green-600
                        @else
                            bg-gray-200 text-gray-900 rounded-bl-none dark:bg-gray-700 dark:text-gray-100
                        @endif
                    ">
                        <p class="text-sm font-semibold mb-1">
                            {{ $resposta->user->name }}
                            <span class="text-xs text-gray-100/80 ml-2 dark:text-gray-300">
                                {{ $resposta->created_at->format('d/m/Y H:i') }}
                            </span>
                        </p>
                        <p class="text-sm leading-snug">{{ $resposta->content }}</p>
                    </div>
        
                    @if ($resposta->user_id === auth()->id())
                        <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold dark:bg-green-400">
                            {{ strtoupper(substr($resposta->user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-300">Nenhuma resposta ainda.</p>
            @endforelse
        
            <div id="scroll-bottom"></div>
        </div>
        

        <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" class="space-y-4 mt-6">
            @csrf
            <label class="block font-semibold">Responder:</label>
            <textarea name="content" rows="4" class="w-full border rounded px-3 py-2" required placeholder="Escreva sua resposta..."></textarea>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Enviar Resposta
            </button>
        </form>

    </div>
</x-app-layout>
