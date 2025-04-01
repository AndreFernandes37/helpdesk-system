<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Painel Administrativo
        </h2>
    </x-slot>

    <div class="px-6 py-4">
        <p class="text-lg">Bem-vindo, {{ auth()->user()->username }}!</p>
    </div>
    <a href="{{ route('admin.tickets.index') }}">Consultar Tickets</a>
</x-app-layout>
