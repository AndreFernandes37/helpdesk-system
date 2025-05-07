<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if (Auth::user()->role === 'client')
                            <a href="{{ route('cliente.dashboard') }}">Painel do Cliente</a>
                        @elseif (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}">Painel de Administrador</a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Dark mode toggle -->
            <div class="ml-auto mr-4">
                <button @click="dark = !dark"
                    class="px-3 py-1.5 rounded text-sm font-medium transition
                        bg-gray-800 text-white dark:bg-yellow-400 dark:text-black">
                    <span x-show="!dark">🌙</span>
                    <span x-show="dark">☀️</span>
                </button>
            </div>

                        <!-- Navbar -->
                        <!-- Dropdown de Notificações na Navbar -->
            <!-- Navbar -->
            <!-- Navbar -->
            <div class="flex items-center">
                <button class="relative" id="notifications-button">
                    <!-- Ícone de Notificações -->
                    <i data-lucide="bell" class="w-6 h-6 text-gray-600"></i>

                    <!-- Badge para notificações não lidas -->
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 block w-2.5 h-2.5 bg-red-600 rounded-full"></span>
                    @endif
                </button>

                <!-- Dropdown de Notificações -->
                <div id="notifications-dropdown" class="absolute top-12 right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-50 hidden">
                    <ul class="list-none p-0 m-0">
                        @foreach (Auth::user()->unreadNotifications as $notification)
                            <li class="border-b" id="notification-{{ $notification->id }}">


                                @auth
                                    @if (Auth::user()->role === 'client')
                                        <a href="{{ route('cliente.ticket.show', $notification->data['ticket_id']) }}" class="block px-4 py-2 text-sm text-gray-700">
                                            {{ $notification->data['user_name'] }} {{ $notification->data['action'] }} o ticket "{{ $notification->data['ticket_title'] }}"
                                        </a>
                                        <form action="{{ route('cliente.notifications.markAsRead', $notification->id) }}" method="POST" class="px-4 py-2 text-sm">
                                            @csrf
                                            <button type="submit" class="text-red-600">Fechar</button>
                                        </form>
                                    @elseif (Auth::user()->role === 'admin')

                                        <a href="{{ route('admin.tickets.show', $notification->data['ticket_id']) }}" class="block px-4 py-2 text-sm text-gray-700">
                                            {{ $notification->data['user_name'] }} {{ $notification->data['action'] }} o ticket "{{ $notification->data['ticket_title'] }}"
                                        </a>
                                        <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST" class="px-4 py-2 text-sm">
                                            @csrf
                                            <button type="submit" class="text-red-600">Fechar</button>
                                        </form>
                                    @endif
                                @endauth
                                
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>






            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Referência ao botão de notificação
        const notificationsButton = document.getElementById('notifications-button');
        // Referência ao dropdown de notificações
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        // Verifica se o botão e o dropdown existem
        if (notificationsButton && notificationsDropdown) {
            notificationsButton.addEventListener('click', function () {
                // Alterna o display do dropdown de notificações entre 'none' e 'block'
                if (notificationsDropdown.style.display === 'block') {
                    notificationsDropdown.style.display = 'none';  // Esconde o dropdown
                } else {
                    notificationsDropdown.style.display = 'block';  // Exibe o dropdown
                }
            });
        }

        // Função para fechar a notificação
        function closeNotificationButton(notificationId) {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.classList.add('hidden'); // Fecha a notificação
            }
        }

        // Fechar o dropdown automaticamente após 5 segundos
        function closeDropdownAutomatically() {
            setTimeout(() => {
                const dropdown = document.getElementById('notifications-dropdown');
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';  // Fecha o dropdown após 5 segundos
                }
            }, 5000); // 5 segundos
        }

        // Quando a página carrega, chama a função para esconder o dropdown após 5 segundos
        window.onload = () => {
            closeDropdownAutomatically();
        }
    });
</script>



