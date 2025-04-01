<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Abrir Novo Ticket') }}
        </h2>
    </x-slot>

    <div class="py-4 px-6">
        <form action="{{ route('cliente.ticket.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title">Título</label>
                <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label for="description">Descrição</label>
                <textarea name="description" id="description" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
            </div>

            <div>
                <label for="priority">Prioridade</label>
                <select name="priority" id="priority" class="w-full border rounded px-3 py-2" required>
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>
            </div>

            <div>
                <label for="categoria_id">Categoria</label>
                <select name="categoria_id" id="categoria_id" class="w-full border rounded px-3 py-2">
                    <option value="">Nenhuma</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Enviar Ticket
            </button>
        </form>
    </div>
</x-app-layout>
