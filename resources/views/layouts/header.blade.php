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

    <!-- Floating WhatsApp Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/6282183544686?text=Halo,%20saya%20ingin%20bertanya%20tentang%20Makna%20Academy" 
           target="_blank" 
           rel="noopener noreferrer"
           class="group bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 flex items-center justify-center">
            <!-- WhatsApp Icon -->
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.891 3.488"/>
            </svg>
            
            <!-- Tooltip -->
            <div class="absolute bottom-full right-0 mb-2 hidden group-hover:block">
                <div class="bg-gray-800 text-white text-sm px-3 py-2 rounded-lg whitespace-nowrap">
                    Hubungi Admin via WhatsApp
                    <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                </div>
            </div>
        </a>
    </div>
</nav>
