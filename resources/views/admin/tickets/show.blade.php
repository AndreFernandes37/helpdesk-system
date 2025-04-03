<x-app-layout>
    <x-slot name="header">
        

        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Detalhes do Ticket
        </h2>
    </x-slot>

    <div class="px-6 py-4 space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded shadow mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded shadow mb-4">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 underline">← Voltar</a>

        <div class="border rounded p-4 bg-white shadow">
            <h3 class="text-lg font-bold">{{ $ticket->title }}</h3>
            <p><strong>Cliente:</strong> {{ $ticket->user->name }}</p>
            <p><strong>Status:</strong>
                <span class="px-2 py-1 rounded text-white text-xs font-semibold 
                    @switch($ticket->status)
                        @case('open') bg-yellow-500 @break
                        @case('in_progress') bg-blue-500 @break
                        @case('closed') bg-green-600 @break
                        @default bg-gray-400
                    @endswitch
                ">
                    {{ ucfirst($ticket->status) }}
                </span>
             </p>             
             <p><strong>Prioridade:</strong>
                <span class="px-2 py-1 rounded text-white text-xs font-semibold 
                    @switch($ticket->priority)
                        @case('high') bg-red-600 @break
                        @case('medium') bg-yellow-600 @break
                        @case('low') bg-green-600 @break
                        @default bg-gray-400
                    @endswitch
                ">
                    {{ ucfirst($ticket->priority) }}
                </span>
             </p>
             
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

        <div id="respostas-container" class="h-[400px] overflow-y-auto space-y-3 mt-6 pr-2">
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
        
        

        <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-6">
            @csrf
            <label class="block font-semibold">Responder:</label>
            <textarea name="content" rows="4" class="w-full border rounded px-3 py-2" required placeholder="Escreva sua resposta..."></textarea>
            @error('content')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            @error('attachment')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Enviar Resposta
            </button>
        </form>

    </div>
    <script>
        let lastMessageCount = document.querySelectorAll('#respostas-container .resposta').length;
        document.addEventListener('DOMContentLoaded', function () {
            const respostasContainer = document.querySelector('#respostas-container');
            const ticketId = '{{ $ticket->id }}';

            function scrollToBottom() {
                const scrollBottom = document.getElementById('scroll-bottom');
                if (scrollBottom) {
                    scrollBottom.scrollIntoView({ behavior: 'auto' });
                }
            }

            async function fetchRespostas() {
                try {
                    const response = await fetch(`{{ url('/cliente/ticket/${ticketId}/respostas') }}`);
                    if (response.ok) {
                        const respostas = await response.json();
                        renderRespostas(respostas);
                    }
                } catch (error) {
                    console.error('Erro ao buscar respostas:', error);
                }
            }

            function renderRespostas(respostas) {
                respostasContainer.innerHTML = '';
                
                // Adiciona as respostas
                respostas.forEach(resposta => {
                    const userId = resposta.user_id;
                    const userName = resposta.user ? resposta.user.name : 'Anônimo';

                    const respostaDiv = document.createElement('div');
                    respostaDiv.classList.add('flex', 'items-start', 'gap-3');

                    if (userId === "{{ auth()->id() }}") {
                        respostaDiv.classList.add('justify-end');
                    } else {
                        respostaDiv.classList.add('justify-start');
                    }

                    const avatarDiv = document.createElement('div');
                    avatarDiv.classList.add('flex', 'items-center', 'justify-center', 'w-10', 'h-10', 'rounded-full', 'text-sm', 'font-bold');
                    avatarDiv.innerText = userName.substring(0, 2).toUpperCase();
                    avatarDiv.style.backgroundColor = userId === "{{ auth()->id() }}" ? '#22c55e' : '#3b82f6';

                    const bubbleDiv = document.createElement('div');
                    bubbleDiv.classList.add('max-w-[70%]', 'px-4', 'py-2', 'rounded-2xl', 'shadow');
                    bubbleDiv.innerHTML = `
                        <p class="text-sm font-semibold mb-1">
                            ${userName} 
                            <span class="text-xs text-gray-500 ml-2">
                                ${resposta.created_at}
                            </span>
                        </p>
                        <p class="text-sm leading-snug">${resposta.content}</p>
                    `;

                    if (resposta.attachment) {
                        const attachmentLink = document.createElement('a');
                        attachmentLink.href = `/storage/${resposta.attachment}`;
                        attachmentLink.textContent = 'Ver Anexo';
                        attachmentLink.target = '_blank';
                        attachmentLink.classList.add('text-blue-500', 'underline', 'mt-2', 'block');
                        bubbleDiv.appendChild(attachmentLink);
                    }


                    if (userId === "{{ auth()->id() }}") {
                        bubbleDiv.classList.add('bg-green-500', 'text-white', 'rounded-br-none');
                    } else {
                        bubbleDiv.classList.add('bg-gray-200', 'text-gray-900', 'rounded-bl-none');
                    }

                    if (userId !== "{{ auth()->id() }}") {
                        respostaDiv.appendChild(avatarDiv);
                        respostaDiv.appendChild(bubbleDiv);
                    } else {
                        respostaDiv.appendChild(bubbleDiv);
                        respostaDiv.appendChild(avatarDiv);
                    }

                    respostasContainer.appendChild(respostaDiv);
                });

                // Recria o "scroll-bottom"
                const scrollBottomDiv = document.createElement('div');
                scrollBottomDiv.id = 'scroll-bottom';
                respostasContainer.appendChild(scrollBottomDiv);

                const currentMessageCount = respostas.length;
            
                // Scroll para o fim após renderizar
                if (currentMessageCount > lastMessageCount) {
                    scrollToBottom();
                }

                // Atualiza o lastMessageCount para refletir a contagem atual de mensagens
                lastMessageCount = currentMessageCount;

            }

            // Faz o scroll inicial logo ao carregar
            scrollToBottom();

            // Faz a primeira busca
            fetchRespostas();

            // Atualiza automaticamente a cada 15 segundos
            setInterval(fetchRespostas, 15000);
        });

    </script>
    
</x-app-layout>
