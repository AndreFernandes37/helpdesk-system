<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Detalhes do Ticket') }}
        </h2>
    </x-slot>

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

    <div class="py-4 px-6 space-y-4">
        <a href="{{ route('cliente.dashboard') }}" class="text-blue-600 underline">← Voltar</a>
    
        <div class="border rounded p-4 bg-white shadow">
            <h3 class="text-lg font-semibold">{{ $ticket->title }}</h3>
        
            <p>
                <strong>Status:</strong>
                <span class="
                    px-2 py-1 rounded text-white text-xs font-semibold
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
        
            <p>
                <strong>Prioridade:</strong>
                <span class="
                    px-2 py-1 rounded text-white text-xs font-semibold
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
    
        </div>
    </div>

    <hr class="my-6">

    <h4 class="text-md font-semibold text-black dark:text-white">Respostas</h4>

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
                    <div class="flex items-center justify-center text-white w-10 h-10 rounded-full text-sm font-bold bg-blue-500 dark:bg-blue-400">
                        {{ strtoupper(substr($resposta->user->name, 0, 2)) }}
                    </div>
                @endif
    
                {{-- Balão de mensagem --}}
                <div class="max-w-[70%] px-4 py-2 rounded-2xl shadow
                    @if ($resposta->user_id === auth()->id())
                        bg-green-600 text-neutral-100 rounded-br-none dark:bg-green-500
                    @else
                        bg-gray-300 text-gray-900 rounded-bl-none dark:bg-gray-700 dark:text-gray-100
                    @endif
                relative">
                    <p class="text-sm font-semibold mb-1">
                        {{ $resposta->user->name }}
                        <span class="text-xs text-gray-500 ml-2 dark:text-gray-300">
                            {{ $resposta->created_at->format('d/m/Y H:i') }}
                        </span>
                    </p>
                    <p class="text-sm leading-snug">{{ $resposta->content }}</p>
                    {{-- Ícone de lido/não lido dentro da bolha --}}
                    <div class="absolute bottom-1 right-1">
                        @if ($resposta->is_read)
                            <i data-lucide="check-circle" class="text-blue-600 w-4 h-4"></i>  <!-- Lido -->
                        @else
                            <i data-lucide="circle" class="text-gray-600 w-4 h-4"></i>  <!-- Não lido -->
                        @endif
                    </div>
                </div>

                @if ($resposta->user_id === auth()->id())
                    <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold dark:bg-green-400">
                        {{ strtoupper(substr($resposta->user->name, 0, 2)) }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-300">Nenhuma resposta ainda.</p>
        @endforelse
    
        <div id="scroll-bottom"></div>
    </div>
    

    <hr class="my-6">

    <h4 class="text-md font-semibold mb-2 text-black dark:text-white">Responder</h4>

    <form action="{{ route('cliente.ticket.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <textarea name="content" rows="4" class="w-full border rounded px-3 py-2" required placeholder="Escreva sua resposta..."></textarea>
        <input type="file" name="attachment" accept="image/*,.pdf,.doc,.docx" class="mt-2">
        @error('content')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
        @error('attachment')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
        @enderror
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">
                Enviar
            </button>
        </div>
    </form>
    
    
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
                respostasContainer.innerHTML = ''; // Limpa as respostas anteriores
                
                // Adiciona as respostas
                respostas.forEach(resposta => {
                    const userId = resposta.user_id;
                    const userName = resposta.user ? resposta.user.name : 'Anônimo';

                    const respostaDiv = document.createElement('div');
                    respostaDiv.classList.add('flex', 'items-start', 'gap-3', 'resposta');

                    if (userId === "{{ auth()->id() }}") {
                        respostaDiv.classList.add('justify-end');
                    } else {
                        respostaDiv.classList.add('justify-start');
                    }

                    // Criação do avatar
                    const avatarDiv = document.createElement('div');
                    avatarDiv.classList.add('flex', 'items-center', 'justify-center', 'text-white','w-10', 'h-10', 'rounded-full', 'text-sm', 'font-bold', 'bg-blue-600','dark:bg-blue-400');
                    avatarDiv.innerText = userName.substring(0, 2).toUpperCase();
                    avatarDiv.style.backgroundColor = userId === "{{ auth()->id() }}" ? '#00c850' : '#3b82f6';

                    // Criação da bolha da resposta
                    const bubbleDiv = document.createElement('div');
                    bubbleDiv.classList.add('relative', 'max-w-[70%]', 'px-4', 'py-2', 'rounded-2xl', 'shadow');

                    // Ícone de lido ou não lido (dentro da bolha, no canto inferior direito)
                    const statusIcon = document.createElement('i');
                    statusIcon.classList.add('w-4', 'h-4', 'absolute', 'bottom-1', 'right-1');  // Alinha o ícone no canto inferior direito

                    if (resposta.is_read) {
                        // Resposta lida - Ícone de 'check-circle'
                        statusIcon.setAttribute('data-lucide', 'check-circle');
                        statusIcon.classList.add('text-blue-600');
                    } else {
                        // Resposta não lida - Ícone de 'circle'
                        statusIcon.setAttribute('data-lucide', 'circle');
                        statusIcon.classList.add('text-gray-600');
                    }

                    // Coloca o ícone dentro da bolha
                    bubbleDiv.appendChild(statusIcon);

                     // Formatação da data para o formato 'd/m/Y H:i'
                    const createdAt = new Date(resposta.created_at);
                    const formattedDate = `${createdAt.getDate().toString().padStart(2, '0')}/${(createdAt.getMonth() + 1).toString().padStart(2, '0')}/${createdAt.getFullYear()} ${createdAt.getHours().toString().padStart(2, '0')}:${createdAt.getMinutes().toString().padStart(2, '0')}`;

                    // Preenche o conteúdo da resposta
                    bubbleDiv.innerHTML += `
                        <p class="text-sm font-semibold mb-1">
                            ${userName} 
                            <span class="text-xs text-gray-500 ml-2">
                                ${formattedDate}
                            </span>
                        </p>
                        <p class="text-sm leading-snug">${resposta.content}</p>
                    `;

                    // Verifica se a resposta tem anexo e cria um link para visualização
                    if (resposta.attachment) {
                        const attachmentLink = document.createElement('a');
                        attachmentLink.href = `/storage/${resposta.attachment}`;
                        attachmentLink.textContent = 'Ver Anexo';
                        attachmentLink.target = '_blank';
                        attachmentLink.classList.add('text-blue-500', 'underline', 'mt-2', 'block');
                        bubbleDiv.appendChild(attachmentLink);
                    }

                    // Aplica estilos com base no usuário
                    if (userId === "{{ auth()->id() }}") {
                        bubbleDiv.classList.add('bg-green-600', 'text-neutral-100', 'rounded-br-none');
                    } else {
                        bubbleDiv.classList.add('bg-gray-300', 'text-gray-900', 'rounded-bl-none');
                    }

                    // Adiciona a resposta ao container de respostas
                    if (userId !== "{{ auth()->id() }}") {
                        respostaDiv.appendChild(avatarDiv);
                        respostaDiv.appendChild(bubbleDiv);
                    } else {
                        respostaDiv.appendChild(bubbleDiv);
                        respostaDiv.appendChild(avatarDiv);
                    }

                    respostasContainer.appendChild(respostaDiv);
                    
                    // Se a resposta for do outro usuário, envie uma requisição para marcar como lida
                    if (!resposta.is_read && userId !== "{{ auth()->id() }}") {
                        markAsRead(resposta.id); // Chama a função para marcar como lida
                    }
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

                // Re-renderiza os ícones Lucide
                lucide.createIcons();
            }

            // Função para marcar a resposta como lida
            async function markAsRead(respostaId) {
                try {
                    const response = await fetch(`{{ url('/cliente/resposta/${respostaId}/mark-read') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            is_read: true,
                        }),
                    });

                    if (response.ok) {
                        console.log('Resposta marcada como lida');
                    } else {
                        console.log('Erro ao marcar resposta como lida');
                    }
                } catch (error) {
                    console.error('Erro ao enviar requisição para marcar resposta como lida:', error);
                }
            }

            // Faz o scroll inicial logo ao carregar
            scrollToBottom();

            // Faz a primeira busca
            fetchRespostas();

            // Atualiza automaticamente a cada 15 segundos
            setInterval(fetchRespostas, 2000);
        });

    </script>
    
</x-app-layout>
