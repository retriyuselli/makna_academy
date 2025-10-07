@extends('layouts.app')

@section('title', 'Event - Makna Academy')

@section('content')
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 max-w-7xl mx-auto mt-4"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Temukan Event Terbaik</h1>
                <p class="text-xl opacity-90 mb-8">Jelajahi berbagai event menarik dan bergabunglah dengan komunitas yang
                    luar biasa</p>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="py-8 bg-gradient-to-r from-indigo-50 via-white to-indigo-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('events.index') }}" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4 items-center">

                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                    </div>

                    <!-- Category Filter -->
                    <div class="w-full md:w-48 relative">
                        <i class="fas fa-tags absolute left-3 top-3 text-gray-400 pointer-events-none"></i>
                        <select name="category"
                            class="appearance-none w-full pl-10 pr-8 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 cursor-pointer bg-white">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- City Filter -->
                    <div class="w-full md:w-48 relative">
                        <i class="fas fa-map-marker-alt absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="city" value="{{ request('city') }}" placeholder="Kota..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    </div>

                    <!-- Price Filter -->
                    <div class="w-full md:w-48 relative">
                        <i class="fas fa-money-bill-wave absolute left-3 top-3 text-gray-400 pointer-events-none"></i>
                        <select name="price_filter"
                            class="appearance-none w-full pl-10 pr-8 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 cursor-pointer bg-white">
                            <option value="">Semua Harga</option>
                            <option value="free" {{ request('price_filter') == 'free' ? 'selected' : '' }}>Gratis</option>
                            <option value="paid" {{ request('price_filter') == 'paid' ? 'selected' : '' }}>Berbayar
                            </option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Date Filter -->
                    <div class="w-full md:w-48 relative">
                        <!-- Ikon Kalender -->
                        <i class="fas fa-calendar-alt absolute left-3 top-3 text-gray-400 pointer-events-none"></i>

                        <select name="date_filter"
                            class="appearance-none w-full pl-10 pr-8 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 cursor-pointer bg-white">
                            <option value="">Semua Tanggal</option>
                            <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Mendatang
                            </option>
                            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>Minggu
                                Ini</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Bulan
                                Ini</option>
                        </select>

                        <!-- Chevron Dropdown -->
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                    </div>

                    <!-- Search Button -->
                    <button type="submit"
                        class="flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg transition duration-300">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- All Events Section -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Semua Event</h2>
                <p class="text-gray-600">Menampilkan {{ $events->count() }} dari {{ $events->total() }} event</p>
            </div>

            @if ($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($events as $event)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            @if ($event->image)
                                <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}"
                                    class="w-full h-auto object-contain">
                            @else
                                <div class="w-full h-80 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-3xl text-gray-400"></i>
                                </div>
                            @endif

                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    @if ($event->category)
                                        <span
                                            class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-r-lg">{{ $event->category->name }}</span>
                                    @endif
                                    @if ($event->is_free)
                                        <span class="text-green-600 font-semibold text-xs">GRATIS</span>
                                    @elseif ($event->category && str_contains(strtolower($event->category->name), 'expo'))
                                        <div class="text-right">
                                            <div class="text-yellow-600 font-semibold text-xs">
                                                Gold: Rp {{ number_format($event->price_gold, 0, ',', '.') }}
                                            </div>
                                            <div class="text-blue-600 font-semibold text-xs">
                                                Platinum: Rp {{ number_format($event->price_platinum, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-indigo-600 font-semibold text-xs">Rp
                                            {{ number_format($event->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <h3 class="text-sm font-semibold text-gray-900 mb-2">{{ Str::limit($event->title, 30) }}
                                </h3>

                                <div class="space-y-1 text-xs text-gray-500 mb-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $event->start_date->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $event->city }}
                                    </div>
                                    @php
                                        $percentage = ($event->actual_participants / $event->max_participants) * 100;
                                        $colorClass = $percentage >= 90 ? 'text-red-500' : 
                                                    ($percentage >= 75 ? 'text-yellow-500' : 
                                                    ($percentage >= 50 ? 'text-green-500' : 'text-indigo-500'));
                                    @endphp
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-users {{ $colorClass }} mr-1"></i>
                                            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                                                <span class="font-medium">
                                                    {{ number_format($event->actual_participants) }}/{{ number_format($event->max_participants) }}
                                                </span>
                                            @else
                                                <span class="font-medium">
                                                    {{ number_format($event->max_participants) }} peserta (max)
                                                </span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1">
                                            <div class="{{ str_replace('text-', 'bg-', $colorClass) }} h-full rounded-full transition-all duration-500 ease-out"
                                                 style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('events.show', $event) }}"
                                    class="w-full bg-indigo-600 text-white px-3 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 inline-block text-center text-xs">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada event ditemukan</h3>
                    <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Auto submit form when filter changes
        document.querySelectorAll('select[name="category"], select[name="price_filter"], select[name="date_filter"]')
            .forEach(function(select) {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });
    </script>
@endsection
