<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.svg') }}" alt="Makna Academy Logo" class="h-10 w-auto">
                        <div class="ml-3 flex flex-col">
                            <h1 class="text-xl font-bold text-indigo-600 leading-none">Makna Academy</h1>
                            <span class="text-xs text-gray-500 font-medium">Belajar Untuk Masa Depan</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition duration-300">
                    Beranda
                </a>
                <a href="{{ route('events.index') }}"
                    class="{{ request()->routeIs('events*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition duration-300">
                    Event
                </a>
                <a href="{{ route('about') }}"
                    class="{{ request()->routeIs('about') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} px-3 py-2 text-sm font-medium transition duration-300">
                    Kontak
                </a>

                <!-- Auth Section -->
                <div class="flex items-center space-x-4 ml-6">
                    @auth
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                                <!-- User Avatar with Google support -->
                                <x-user-avatar :size="32" class="flex-shrink-0" />
                                <!-- User Name -->
                                <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                                <!-- Dropdown Arrow -->
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                    :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                                <!-- User Info -->
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->role }}</p>
                                </div>

                                <!-- Dashboard Link -->
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('dashboard') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                                    <i
                                        class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                                    Dashboard
                                </a>
                                <!-- Admin Access - hanya untuk admin/super_admin -->
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                <a href="{{ route('filament.admin.pages.dashboard') }}"
                                    class="flex items-center px-4 py-2 text-sm {{ request()->is('admin*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                                    <i
                                        class="fas fa-shield-alt mr-3 {{ request()->is('admin*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                                    Masuk Admin
                                </a>
                                @endif

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-200">
                                        <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Auth Buttons -->
                        <a href="{{ route('login') }}"
                            class="{{ request()->routeIs('login') ? 'text-indigo-600 border-indigo-600 bg-indigo-50' : 'text-gray-700 hover:text-indigo-600 border-gray-300 hover:border-indigo-600' }} px-3 py-2 text-sm font-medium border rounded-lg transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="{{ request()->routeIs('register') ? 'bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white px-4 py-2 text-sm font-medium rounded-lg transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center" x-data="{ mobileOpen: false }">
                <button @click="mobileOpen = !mobileOpen" class="text-gray-700 hover:text-indigo-600 p-2">
                    <i class="fas fa-bars text-xl" x-show="!mobileOpen"></i>
                    <i class="fas fa-times text-xl" x-show="mobileOpen"></i>
                </button>

                <!-- Mobile Menu -->
                <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95" @click.away="mobileOpen = false"
                    class="absolute top-16 right-4 left-4 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">

                    <!-- Mobile Navigation Links -->
                    <a href="{{ route('home') }}"
                        class="block px-4 py-2 {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                        <i class="fas fa-home mr-3"></i>Beranda
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="block px-4 py-2 {{ request()->routeIs('events*') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                        <i class="fas fa-calendar mr-3"></i>Event
                    </a>
                    <a href="{{ route('about') }}"
                        class="block px-4 py-2 {{ request()->routeIs('about') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                        <i class="fas fa-envelope mr-3"></i>Kontak
                    </a>

                    <hr class="my-2">

                    @auth
                        <!-- Mobile User Info -->
                        <div class="px-4 py-2 bg-gray-50">
                            <div class="flex items-center space-x-3">
                                <x-user-avatar :size="40" class="flex-shrink-0" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 {{ request()->routeIs('dashboard') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                        </a>

                        <!-- Admin Access Mobile - hanya untuk admin/super_admin -->
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="block px-4 py-2 {{ request()->is('admin*') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                            <i class="fas fa-shield-alt mr-3"></i>Masuk Admin
                        </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 {{ request()->routeIs('login') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }} transition duration-200">
                            <i class="fas fa-sign-in-alt mr-3"></i>Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="block px-4 py-2 {{ request()->routeIs('register') ? 'text-indigo-600 bg-indigo-50 border-r-4 border-indigo-600 font-medium' : 'text-indigo-600 hover:bg-indigo-50 font-medium' }} transition duration-200">
                            <i class="fas fa-user-plus mr-3"></i>Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
