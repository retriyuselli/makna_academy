@extends('layouts.app')

@section('title', 'Beranda - Makna Academy')

@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Temukan Event <span class="text-yellow-300">Terbaik</span> untuk Anda
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200">
                    Platform terpercaya untuk menemukan workshop, seminar, dan pelatihan berkualitas
                </p>

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">{{ number_format($totalEvents) }}</div>
                        <div class="text-sm text-gray-300">Event Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">{{ number_format($totalParticipants) }}+</div>
                        <div class="text-sm text-gray-300">Peserta Terdaftar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">{{ number_format($totalCompanies) }}+</div>
                        <div class="text-sm text-gray-300">Perusahaan Partner</div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#events"
                        class="bg-yellow-400 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition duration-300">
                        Jelajahi Event
                    </a>
                    <a href="#about"
                        class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="bg-white py-12 -mt-10 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-xl p-6">
                <form action="{{ route('home.search') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Event</label>
                            <input type="text" name="search" placeholder="Nama event..."
                                class="w-full h-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div class="w-full md:w-48 relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>

                            <!-- Ikon -->
                            <i class="fas fa-list-ul absolute left-3 top-[38px] text-gray-400 pointer-events-none"></i>

                            <select name="category"
                                class="appearance-none w-full h-10 pl-10 pr-8 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 cursor-pointer bg-white">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Chevron -->
                            <i
                                class="fas fa-chevron-down absolute right-3 top-[38px] text-gray-400 pointer-events-none"></i>
                        </div>
                        <div class="w-full md:w-48 relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>

                            <!-- Ikon Lokasi -->
                            <i
                                class="fas fa-map-marker-alt absolute left-3 top-[38px] text-gray-400 pointer-events-none"></i>

                            <select name="city"
                                class="appearance-none w-full h-10 pl-10 pr-8 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 cursor-pointer bg-white">
                                <option value="">Semua Kota</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Chevron -->
                            <i
                                class="fas fa-chevron-down absolute right-3 top-[38px] text-gray-400 pointer-events-none"></i>
                        </div>
                        <div>
                            <button type="submit"
                                class="w-full h-10 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Events -->
    <section id="events" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Event Unggulan</h2>
                <p class="text-sm text-gray-600">Event terpopuler dan paling diminati</p>
            </div>

            @if ($featuredEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($featuredEvents as $event)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                            <div class="relative">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/400x250/4F46E5/FFFFFF?text=' . urlencode($event->title) }}"
                                    alt="{{ $event->title }}" class="w-full h-100 object-cover">

                                @if ($event->is_featured)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                                    </div>
                                @elseif($event->is_trending)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Trending</span>
                                    </div>
                                @endif

                                <div class="absolute top-4 right-4">
                                    @if ($event->is_free)
                                        <span
                                            class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">GRATIS</span>
                                    @elseif($event->category && str_contains(strtolower($event->category->name), 'expo'))
                                        {{-- Expo events dengan paket Gold dan Platinum --}}
                                        <div class="flex flex-col space-y-1">
                                            @if($event->price_gold)
                                                <span class="bg-yellow-400 text-gray-900 px-2 py-1 rounded-full text-xs font-semibold">
                                                    Gold: Rp {{ number_format($event->price_gold, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            @if($event->price_platinum)
                                                <span class="bg-gray-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                                    Platinum: Rp {{ number_format($event->price_platinum, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span
                                            class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Rp
                                            {{ number_format($event->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                @if ($event->category)
                                    <div class="flex items-center mb-2">
                                        <span
                                            class="bg-purple-100 text-purple-800 px-2 py-1 rounded-r-lg text-xs font-medium">{{ $event->category->name }}</span>
                                    </div>
                                @endif

                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <p class="text-gray-600 text-xs mb-4">
                                    {{ Str::limit($event->short_description ?? $event->description, 100) }}</p>

                                <div class="flex items-center text-xs text-gray-500 mb-2">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <span>{{ $event->start_date->format('d M Y') }}</span>
                                    @if ($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d'))
                                        - {{ $event->end_date->format('d M Y') }}
                                    @endif
                                </div>

                                <div class="flex items-center text-xs text-gray-500 mb-2">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>
                                        @php
                                            $startTime = is_string($event->start_time) ? $event->start_time : $event->start_time->format('H:i');
                                            $endTime = is_string($event->end_time) ? $event->end_time : $event->end_time->format('H:i');
                                        @endphp
                                        {{ $startTime }} - {{ $endTime }} WIB
                                    </span>
                                </div>

                                <div class="flex items-center text-xs text-gray-500 mb-4">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span>{{ $event->city }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-users text-indigo-500 mr-2"></i>
                                            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ number_format($event->actual_participants) }}/{{ number_format($event->max_participants) }}
                                                    <span class="text-gray-500 text-xs">peserta</span>
                                                </span>
                                            @else
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ number_format($event->max_participants) }}
                                                    <span class="text-gray-500 text-xs">peserta</span>
                                                </span>
                                            @endif
                                        </div>
                                        @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                                            @php
                                                $percentage =
                                                    ($event->actual_participants / $event->max_participants) * 100;
                                                $colorClass =
                                                    $percentage >= 90
                                                        ? 'bg-red-500'
                                                        : ($percentage >= 75
                                                            ? 'bg-yellow-500'
                                                            : ($percentage >= 50
                                                                ? 'bg-green-500'
                                                                : 'bg-indigo-500'));
                                            @endphp
                                            <div class="relative w-full">
                                                <div class="w-full bg-gray-100 rounded-full h-1">
                                                    <div class="{{ $colorClass }} h-full rounded-full transition-all duration-500 ease-out"
                                                        style="width: {{ $percentage }}%">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('events.show', $event) }}"
                                        class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-sm hover:bg-indigo-700 transition duration-300">
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500 text-lg mb-4">
                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                        <p>Belum ada event unggulan tersedia</p>
                    </div>
                    <a href="{{ route('events.index') }}"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-300">
                        Lihat Semua Event
                    </a>
                </div>
            @endif

            <!-- View All Events Button -->
            @if ($featuredEvents->count() > 0)
                <div class="text-center mt-12">
                    <a href="{{ route('events.index') }}"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                        <span>Lihat Semua Event</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kategori Event</h2>
                <p class="text-xl text-gray-600">Temukan event sesuai minat dan kebutuhan Anda</p>
            </div>

            <!-- Swiper -->
            <div class="swiper categorySwiper">
                <div class="swiper-wrapper pb-8">
                    @foreach ($categories as $category)
                        <div class="swiper-slide">
                            <a href="{{ route('events.index', ['category' => $category->id]) }}"
                                class="bg-white rounded-lg p-6 text-center shadow-lg hover:shadow-xl transition-all duration-300 block">
                                <div
                                    class="w-16 h-16 bg-{{ $category->color ?? 'indigo' }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i
                                        class="fas fa-{{ $category->icon ?? 'folder' }} text-2xl text-{{ $category->color ?? 'indigo' }}-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $category->events_count }}
                                    Event{{ $category->events_count != 1 ? 's' : '' }}</p>
                            </a>
                        </div>
                    @endforeach

                    @if ($categories->count() < 6)
                        @for ($i = 0; $i < 6 - $categories->count(); $i++)
                            <div class="swiper-slide">
                                <div class="bg-white rounded-lg p-6 text-center shadow-lg opacity-50">
                                    <div
                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-plus text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-400 mb-2">Kategori Baru</h3>
                                    <p class="text-sm text-gray-400">Segera Hadir</p>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>

                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Add Navigation -->
                <div class="swiper-button-next text-indigo-600"></div>
                <div class="swiper-button-prev text-indigo-600"></div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    {{-- <section class="bg-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-indigo-200">Event Tersedia</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">10K+</div>
                    <div class="text-indigo-200">Peserta Aktif</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">50+</div>
                    <div class="text-indigo-200">Kota di Indonesia</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-indigo-200">Tingkat Kepuasan</div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- About Section -->
    <section id="about" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Tentang Makna Academy</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Makna Academy adalah platform terdepan untuk menemukan dan mengikuti berbagai event edukatif di
                        Indonesia.
                        Kami menghubungkan peserta dengan event berkualitas tinggi yang dapat meningkatkan skill dan
                        pengetahuan.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="text-gray-700">Event berkualitas tinggi dari expert terpercaya</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="text-gray-700">Sertifikat resmi untuk setiap event yang diikuti</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span class="text-gray-700">Komunitas pembelajar yang aktif dan supportif</span>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="About Makna Academy" class="rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-xl text-gray-600">Ada pertanyaan? Kami siap membantu Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg p-6 text-center shadow-lg">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600">info@maknaacademy.com</p>
                </div>

                <div class="bg-white rounded-lg p-6 text-center shadow-lg">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Telepon</h3>
                    <p class="text-gray-600">+62 813 7318 3794</p>
                </div>

                <div class="bg-white rounded-lg p-6 text-center shadow-lg">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Alamat</h3>
                    <p class="text-gray-600">Jl. Sintraman Jaya I No. 2148, 20 Ilir D Kec. Kemuning, Kota Palembang,
                        Sumatera Selatan 30137</p>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript for smooth scrolling -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    </body>

    </html>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper(".categorySwiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    480: {
                        slidesPerView: 1.5,
                        spaceBetween: 15,
                    },
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 25,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
            });
        });
    </script>
@endpush

<style>
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .hover-scale {
        transition: transform 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }

    /* Swiper Styles */
    .categorySwiper {
        padding: 10px 0 !important;
        margin: 0 auto;
        position: relative;
        height: 28%;
    }

    .swiper-button-next,
    .swiper-button-prev {
        background-color: white;
        padding: 20px;
        border-radius: 50%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        top: 50%;
        transform: translateY(-50%);
    }

    .swiper-button-prev {
        left: -10px;
    }

    .swiper-button-next {
        right: -10px;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 14px !important;
        font-weight: bold;
    }

    .swiper-pagination-bullet-active {
        background-color: #4F46E5 !important;
    }
</style>

<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
