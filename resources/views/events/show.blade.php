@extends('layouts.app')

@section('title', $event->title . ' - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-home mr-2"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-indigo-600">Events</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500">{{ $event->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Event Detail -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Event Image -->
                @if ($event->cover_image)
                    <div class="w-full h-96 relative">
                        <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Event Content -->
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full">
                                    {{ $event->category->name }}
                                </span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $event->city }}
                            </div>
                        </div>
                        <div class="text-right">
                            @if ($event->is_free)
                                <span class="text-green-600 font-semibold text-xl">Gratis</span>
                            @else
                                <span class="text-gray-900 font-semibold text-xl">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                            @endif
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $event->current_participants }} / {{ $event->max_participants }} Peserta
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="flex items-center space-x-8 mb-8 text-sm">
                        <div>
                            <div class="text-gray-500 mb-1">Tanggal Mulai</div>
                            <div class="font-semibold flex items-center">
                                <i class="far fa-calendar mr-2 text-indigo-600"></i>
                                {{ \Carbon\Carbon::parse($event->start_date)->isoFormat('D MMMM Y') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">Waktu</div>
                            <div class="font-semibold flex items-center">
                                <i class="far fa-clock mr-2 text-indigo-600"></i>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} WIB
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-500 mb-1">Durasi</div>
                            <div class="font-semibold flex items-center">
                                <i class="fas fa-hourglass-half mr-2 text-indigo-600"></i>
                                {{ $event->duration }} jam
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="prose max-w-none mb-8">
                        {!! $event->description !!}
                    </div>

                    <!-- Features -->
                    @if ($event->features)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Yang Akan Anda Dapatkan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach (json_decode($event->features) as $feature)
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Requirements -->
                    @if ($event->requirements)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Persyaratan</h3>
                            <div class="space-y-2">
                                @foreach (json_decode($event->requirements) as $requirement)
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-dot-circle text-indigo-600"></i>
                                        <span>{{ $requirement }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-8 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            {{-- <button class="text-gray-500 hover:text-gray-600 flex items-center">
                                <i class="far fa-heart mr-2"></i>
                                Simpan
                            </button> --}}
                            <button type="button"
                                class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 hover:shadow-md transition-all duration-200 ease-in-out group">
                                <i
                                    class="fas fa-share-alt mr-2 text-gray-500 transform transition-transform duration-200 group-hover:translate-x-1"></i>
                                <span class="font-medium">Bagikan</span>
                            </button>
                        </div>
                        <div>
                            @if ($event->current_participants >= $event->max_participants)
                                <button disabled class="bg-gray-400 text-white px-8 py-3 rounded-lg cursor-not-allowed">
                                    Event Penuh
                                </button>
                            @else
                                <a href="{{ route('events.register.form', $event) }}"
                                    class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition duration-300">
                                    Daftar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
