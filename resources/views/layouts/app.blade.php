<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            /* Animação de fade-in e fade-out */
            .transition-transform {
                transition: transform 0.3s ease, opacity 0.3s ease;
            }

            .scale-100 {
                transform: scale(1);
            }

            .opacity-0 {
                opacity: 0;
            }

            /* Adiciona transição de fade-out nas notificações */
            .notification {
                transition: opacity 1s ease-out;
            }

            .notification.hidden {
                opacity: 0;
                pointer-events: none; /* Evita que a notificação ainda receba eventos ao desaparecer */
            }

            #notifications-container .notification {
                animation: slideIn 0.5s ease-in-out;
            }

            @keyframes slideIn {
                0% {
                    transform: translateX(100%);
                    opacity: 0;
                }
                100% {
                    transform: translateX(0);
                    opacity: 1;
                }
            }


           /* resources/css/app.css ou dentro de <style> no seu arquivo Blade */

            /* Estilos para o botão de notificações */
            /* Estilos para o botão de notificações */
            #notifications-button {
                position: relative;
            }

            /* Estilos para o badge de notificações */
            #notifications-button span {
                position: absolute;
                top: 0;
                right: 0;
                width: 10px;
                height: 10px;
                background-color: red;
                border-radius: 50%;
            }

            /* Estilos para o dropdown de notificações */
            #notifications-dropdown {
                display: none;  /* Inicialmente escondido */
                background-color: white;
                border-radius: 8px;
                max-width: 300px;
                padding: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                z-index: 9999;
            }

            #notifications-dropdown ul {
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            #notifications-dropdown li {
                border-bottom: 1px solid #e2e2e2;
            }

            #notifications-dropdown a {
                text-decoration: none;
                color: #333;
                display: block;
                padding: 8px 16px;
            }

            #notifications-dropdown button {
                background-color: transparent;
                border: none;
                cursor: pointer;
            }




 
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased">

        <div id="notifications-container" class="fixed bottom-5 right-5 z-50 space-y-4">
            @foreach (Auth::user()->unreadNotifications as $notification)
                <div class="notification bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-lg shadow-xl flex items-center justify-between transform transition-all duration-300 ease-in-out hover:scale-105" id="notification-{{ $notification->id }}">
                    <div class="flex flex-col">
                        <p class="font-semibold text-lg">{{ $notification->data['user_name'] }} {{ $notification->data['action'] }} o ticket "{{ $notification->data['ticket_title'] }}"</p>
                        <a href="{{ route('admin.tickets.show', $notification->data['ticket_id']) }}" class="text-white text-sm mt-1 underline hover:text-blue-200">Ver Ticket</a>
                    </div>
                    
                    <!-- Botão de Fechar -->
                    <button onclick="closeNotificationButton('notification-{{ $notification->id }}')" class="text-white ml-4 hover:text-red-600 focus:outline-none">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            @endforeach
        </div>
        
        
            
        <!-- Adicione as notificações no topo do layout -->
        <div id="notifications-container" class="fixed bottom-5 right-5 z-50">
            @if (session('status_update'))
                <div class="bg-blue-500 text-white text-center py-3 px-6 rounded shadow-lg transition-transform transform scale-100 opacity-100" id="statusNotification">
                    {{ session('status_update') }}
                </div>
            @endif

            @if (session('new_response'))
                <div class="bg-green-500 text-white text-center py-3 px-6 rounded shadow-lg transition-transform transform scale-100 opacity-100" id="responseNotification">
                    {{ session('new_response') }}
                </div>
            @endif
        </div>

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

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900"
             x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
             x-init="$watch('dark', val => {
                 localStorage.setItem('theme', val ? 'dark' : 'light');
                 document.documentElement.classList.toggle('dark', val);
             })"
             x-bind:class="{ 'dark': dark }"
        >
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
            lucide.createIcons();
        </script>
        
    </body>
    <script>
        // Função para fechar as notificações automaticamente após 5 segundos
        function closeNotification(notificationId) {
            setTimeout(() => {
                const notification = document.getElementById(notificationId);
                if (notification) {
                    notification.classList.add('hidden');  // Aplica o efeito de fade-out
                }
            }, 5000); // 5 segundos
        }

        // Função para fechar as notificações
        function closeNotificationButton(notificationId) {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.classList.add('hidden');  // Aplica o efeito de fade-out
            }
        }

        // Fecha as notificações ao carregar a página
        document.addEventListener('DOMContentLoaded', function () {
            @foreach (Auth::user()->unreadNotifications as $notification)
                closeNotification('notification-{{ $notification->id }}');
            @endforeach
        });
    </script>
    
</html>
