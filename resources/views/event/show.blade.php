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
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-indigo-600">Event</a>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Event Header -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" 
                             class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-6xl text-white opacity-50"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if($event->category)
                                <span class="bg-indigo-100 rounded-r-lg text-indigo-800 text-xs font-medium px-2.5 py-0.5">
                                    {{ $event->category->name }}
                                </span>
                            @endif
                            @if($event->is_featured)
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded ml-2">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-3 text-indigo-600"></i>
                                <span>{{ $event->start_date->format('d M Y') }}</span>
                                @if($event->end_date && $event->end_date != $event->start_date)
                                    <span> - {{ $event->end_date->format('d M Y') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-3 text-indigo-600"></i>
                                <span>{{ $event->start_time }} - {{ $event->end_time }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-3 text-indigo-600"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users mr-3 text-indigo-600"></i>
                                <span>{{ $event->actual_participants }}/{{ $event->max_participants }} peserta</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Description -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Deskripsi Event</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($event->short_description)) !!}
                    </div>
                </div>

                <!-- Event Details -->
                @if($event->requirements || $event->benefits || $event->schedule)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Tambahan</h2>
                    
                    @if($event->requirements)
                    <div class="mb-4">
                        <h3 class="font-medium text-gray-900 mb-2">Persyaratan:</h3>
                        @if(is_array($event->requirements))
                            <ul class="text-gray-700 list-disc list-inside space-y-1">
                                @foreach($event->requirements as $requirement)
                                    <li>{{ $requirement }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-gray-700 prose prose-sm max-w-none">
                                {!! $event->requirements !!}
                            </div>
                        @endif
                    </div>
                    @endif
                    
                    @if($event->benefits)
                    <div class="mb-4">
                        <h3 class="font-medium text-gray-900 mb-2">Manfaat yang Didapat:</h3>
                        @if(is_array($event->benefits))
                            <ul class="text-gray-700 list-disc list-inside space-y-1">
                                @foreach($event->benefits as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-gray-700 prose prose-sm max-w-none">
                                {!! $event->benefits !!}
                            </div>
                        @endif
                    </div>
                    @endif
                    
                    @if($event->schedule)
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Agenda:</h3>
                        @if(is_array($event->schedule))
                            <ul class="text-gray-700 list-disc list-inside space-y-1">
                                @foreach($event->schedule as $agenda)
                                    <li>{{ $agenda }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-gray-700 prose prose-sm max-w-none">
                                {!! $event->schedule !!}
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Registration Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 sticky top-6">
                    <div class="text-center mb-6">
                        @if($event->is_free)
                            <div class="text-3xl font-bold text-green-600 mb-2">GRATIS</div>
                        @elseif($event->eventCategory && str_contains(strtolower($event->eventCategory->name), 'expo'))
                            <div class="space-y-2">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <div class="text-sm font-medium text-yellow-800 mb-1">Gold Package</div>
                                    <div class="text-2xl font-bold text-yellow-600">
                                        Rp {{ number_format($event->price_gold, 0, ',', '.') }}
                                    </div>
                                    @if($event->has_down_payment)
                                        <div class="text-sm text-yellow-700 mt-1">
                                            DP: Rp {{ number_format($event->getPackageDownPayment('gold'), 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="text-sm font-medium text-blue-800 mb-1">Platinum Package</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        Rp {{ number_format($event->price_platinum, 0, ',', '.') }}
                                    </div>
                                    @if($event->has_down_payment)
                                        <div class="text-sm text-blue-700 mt-1">
                                            DP: Rp {{ number_format($event->getPackageDownPayment('platinum'), 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-3xl font-bold text-indigo-600 mb-2">
                                Rp {{ number_format($event->price, 0, ',', '.') }}
                            </div>
                            @if($event->has_down_payment)
                                <div class="text-sm text-indigo-700 mt-1">
                                    DP: Rp {{ number_format($event->getPackageDownPayment('regular'), 0, ',', '.') }}
                                </div>
                            @endif
                        @endif
                        <p class="text-gray-600">per peserta</p>
                        
                        @if($event->has_down_payment && !$event->is_free)
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-center text-blue-800 text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span class="font-medium">Tersedia Sistem Down Payment</span>
                                </div>
                                <div class="text-xs text-blue-600 mt-1">
                                    @if($event->down_payment_type === 'percentage')
                                        DP {{ $event->down_payment_percentage }}% dari total harga
                                    @else
                                        DP tetap Rp {{ number_format($event->down_payment_amount, 0, ',', '.') }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sisa Slot:</span>
                            @php
                                $remainingSlots = $event->getRemainingSlots();
                                $percentageFilled = ($event->actual_participants / $event->max_participants) * 100;
                                $slotClass = 'text-green-600';
                                $progressClass = 'bg-green-600';
                                
                                if ($percentageFilled >= 80) {
                                    $slotClass = 'text-red-600';
                                    $progressClass = 'bg-red-600';
                                } elseif ($percentageFilled >= 50) {
                                    $slotClass = 'text-yellow-600';
                                    $progressClass = 'bg-yellow-600';
                                }
                            @endphp
                            <span class="font-medium {{ $slotClass }}">
                                {{ $remainingSlots }} dari {{ $event->max_participants }}
                                @if($percentageFilled >= 80)
                                    <span class="text-xs ml-1">(Hampir Penuh!)</span>
                                @endif
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $progressClass }} h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $percentageFilled }}%"></div>
                        </div>
                        @if($remainingSlots <= 5 && $remainingSlots > 0)
                            <div class="text-center text-red-600 text-sm animate-pulse">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Hanya tersisa {{ $remainingSlots }} slot!
                            </div>
                        @endif
                    </div>
                    
                    @if($event->getRemainingSlots() <= 0)
                        <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-times mr-2"></i>Event Penuh
                        </button>
                    @elseif($event->start_date < now())
                        <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-clock mr-2"></i>Event Sudah Berlalu
                        </button>
                    @else
                        @if(auth()->check())
                            <a href="{{ route('events.register.form', $event) }}" 
                               class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-block text-center">
                                <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                            </a>
                        @else
                            <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                               class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-block text-center">
                                <i class="fas fa-user-plus mr-2"></i>Login untuk Daftar
                            </a>
                        @endif
                    @endif
                </div>

                <!-- Event Organizer -->
                @if($event->organizer_name)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Penyelenggara</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $event->organizer_name }}</p>
                            @if($event->organizer_email)
                                <p class="text-sm text-gray-600">{{ $event->organizer_email }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Share Event -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Bagikan Event</h3>
                    <div class="flex space-x-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank" 
                           class="flex-1 bg-blue-600 text-white px-3 py-2 rounded text-center hover:bg-blue-700 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($event->title) }}" 
                           target="_blank" 
                           class="flex-1 bg-blue-400 text-white px-3 py-2 rounded text-center hover:bg-blue-500 transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . request()->url()) }}" 
                           target="_blank" 
                           class="flex-1 bg-green-600 text-white px-3 py-2 rounded text-center hover:bg-green-700 transition duration-300">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Events -->
        @if($relatedEvents->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Event Serupa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedEvents as $relatedEvent)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300">
                    @if($relatedEvent->image)
                        <img src="{{ asset('storage/' . $relatedEvent->image) }}" alt="{{ $relatedEvent->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-3xl text-white opacity-50"></i>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedEvent->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $relatedEvent->start_date->format('d M Y') }}</p>
                        <a href="{{ route('events.show', $relatedEvent) }}" 
                           class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection