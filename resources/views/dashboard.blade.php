@extends('layouts.app')

@section('title', 'Dashboard - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <x-user-avatar :size="64" class="flex-shrink-0" />
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ Auth::user()->name }}!</h1>
                        <p class="text-gray-600">Kelola profil dan event Anda di sini</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-alt text-2xl text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Event Terdaftar</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $registeredEvents }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-certificate text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Sertifikat</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $certificateCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-star text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Poin</h3>
                            <p class="text-3xl font-bold text-yellow-600">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remaining Payment Alerts -->
            @if(isset($pendingRemainingPayments) && $pendingRemainingPayments->count() > 0)
                <div class="mb-8">
                    @foreach($pendingRemainingPayments as $registration)
                        @php
                            $daysUntilEvent = \Carbon\Carbon::now()->diffInDays($registration->event->start_date, false);
                            $alertClass = $daysUntilEvent <= 7 ? 'bg-red-50 border-red-200' : ($daysUntilEvent <= 14 ? 'bg-orange-50 border-orange-200' : 'bg-blue-50 border-blue-200');
                            $iconClass = $daysUntilEvent <= 7 ? 'text-red-500' : ($daysUntilEvent <= 14 ? 'text-orange-500' : 'text-blue-500');
                            $textClass = $daysUntilEvent <= 7 ? 'text-red-700' : ($daysUntilEvent <= 14 ? 'text-orange-700' : 'text-blue-700');
                        @endphp
                        <div class="{{ $alertClass }} border rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle {{ $iconClass }} text-xl"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-medium {{ $textClass }}">
                                        Sisa Pembayaran Diperlukan - {{ $registration->event->title }}
                                    </h3>
                                    <div class="mt-2 text-sm {{ $textClass }}">
                                        <p>
                                            Pembayaran DP Anda telah dikonfirmasi. Sisa pembayaran sebesar 
                                            <strong>Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}</strong> 
                                            perlu diselesaikan sebelum event dimulai.
                                        </p>
                                        <p class="mt-1">
                                            <strong>Event dimulai:</strong> {{ $registration->event->start_date->format('d M Y, H:i') }}
                                            @if($daysUntilEvent >= 0)
                                                ({{ $daysUntilEvent }} hari lagi)
                                            @else
                                                ({{ abs($daysUntilEvent) }} hari yang lalu)
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('payment.show', $registration->invoice_number) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            Bayar Sisa
                                        </a>
                                        <a href="{{ route('events.show', $registration->event) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Detail Event
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Events -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Event Terbaru Saya</h2>
                        </div>
                        <div class="p-6">
                            @if(isset($recentEvents) && $recentEvents->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentEvents as $registration)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    @if($registration->event->image)
                                                        <img src="{{ Storage::url($registration->event->image) }}" 
                                                            alt="{{ $registration->event->title }}" 
                                                            class="w-12 h-12 rounded-lg object-cover">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900">
                                                        {{ $registration->event->title }}
                                                    </h4>
                                                    <p class="text-xs text-gray-600">
                                                        {{ $registration->event->start_date->format('d M Y') }}
                                                    </p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @if($registration->payment_status === 'down_payment_paid' && $registration->remaining_amount > 0)
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-orange-100 text-orange-800">
                                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                                DP Terbayar - Sisa: Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs
                                                                {{ $registration->payment_status === 'paid' || $registration->payment_status === 'fully_paid' ? 'bg-green-100 text-green-800' : 
                                                                   ($registration->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                                   ($registration->payment_status === 'free' ? 'bg-blue-100 text-blue-800' :
                                                                    'bg-red-100 text-red-800')) }}">
                                                                @if($registration->payment_status === 'paid' || $registration->payment_status === 'fully_paid')
                                                                    <i class="fas fa-check-circle mr-1"></i>
                                                                    Lunas
                                                                @elseif($registration->payment_status === 'pending')
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    Menunggu Pembayaran
                                                                @elseif($registration->payment_status === 'waiting_verification')
                                                                    <i class="fas fa-hourglass-half mr-1"></i>
                                                                    Menunggu Verifikasi
                                                                @elseif($registration->payment_status === 'free')
                                                                    <i class="fas fa-gift mr-1"></i>
                                                                    Gratis
                                                                @else
                                                                    {{ ucfirst($registration->payment_status) }}
                                                                @endif
                                                            </span>
                                                        @endif
                                                        @if($registration->hasCertificate())
                                                            <a href="{{ route('certificates.show', $registration) }}" 
                                                               class="inline-flex items-center px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-800 hover:bg-indigo-200">
                                                                <i class="fas fa-certificate mr-1"></i>
                                                                Lihat Sertifikat
                                                            </a>
                                                        @elseif($registration->isEligibleForCertificate())
                                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                Sertifikat Diproses
                                                            </span>
                                                            @php
                                                                $details = $registration->getCertificateStatusDetails();
                                                            @endphp
                                                            <div class="mt-2 text-xs text-gray-500">
                                                                Status Syarat Sertifikat:<br>
                                                                - Pembayaran: {{ $details['payment_status_ok'] ? '✅' : '❌' }} ({{ $details['payment_status'] }})<br>
                                                                - Kehadiran: {{ $details['attendance_ok'] ? '✅' : '❌' }}<br>
                                                                - Event Selesai: {{ $details['event_ended_ok'] ? '✅' : '❌' }} 
                                                                  ({{ $details['event_end_date']->format('Y-m-d H:i') }})
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col space-y-2">
                                                @if($registration->payment_status === 'down_payment_paid' && $registration->remaining_amount > 0)
                                                    <a href="{{ route('payment.show', $registration->invoice_number) }}" 
                                                       class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                                        <i class="fas fa-credit-card mr-1"></i>
                                                        Bayar Sisa
                                                    </a>
                                                @endif
                                                <a href="{{ route('events.show', $registration->event) }}" 
                                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar-plus text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">Belum ada event yang terdaftar</p>
                                    <a href="{{ route('events.index') }}"
                                        class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                                        Jelajahi Event
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <h2 class="text-xl font-semibold text-gray-900">Aktivitas Terbaru</h2>
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">7 hari terakhir</span>
                            </div>
                            @if(isset($recentActivities) && $recentActivities->isNotEmpty())
                                <div x-data="{ showModal: false }">
                                    <button type="button" 
                                        @click="showModal = true"
                                        class="text-sm text-gray-500 hover:text-red-600 transition-colors duration-200">
                                        <i class="fas fa-trash-alt mr-1"></i>Bersihkan
                                    </button>

                                    <!-- Modal Konfirmasi -->
                                    <div
                                        x-show="showModal"
                                        x-cloak
                                        @keydown.escape.window="showModal = false"
                                        class="fixed inset-0 z-50 overflow-y-auto"
                                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div
                                            x-show="showModal"
                                            x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="ease-in duration-200"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 transition-opacity"
                                            aria-hidden="true">
                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                        </div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                        <div
                                            x-show="showModal"
                                            x-transition:enter="ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave="ease-in duration-200"
                                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                                                    </div>
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                            Hapus Aktivitas
                                                        </h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-500">
                                                                Apakah Anda yakin ingin menghapus semua aktivitas? Tindakan ini tidak dapat dibatalkan.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <form method="POST" action="{{ route('activities.clear') }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                        Hapus Semua
                                                    </button>
                                                </form>
                                                <button type="button"
                                                    @click="showModal = false"
                                                    class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if(session('success'))
                                <div x-data="{ show: true }"
                                    x-show="show"
                                    x-init="setTimeout(() => show = false, 5000)"
                                    class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center justify-between"
                                    role="alert">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span>{{ session('success') }}</span>
                                    </div>
                                    <button @click="show = false" class="text-green-800 hover:text-green-900">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div x-data="{ show: true }"
                                    x-show="show"
                                    x-init="setTimeout(() => show = false, 5000)"
                                    class="bg-red-100 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center justify-between"
                                    role="alert">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <span>{{ session('error') }}</span>
                                    </div>
                                    <button @click="show = false" class="text-red-800 hover:text-red-900">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                            @if(isset($recentActivities) && $recentActivities->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach($recentActivities as $activity)
                                        <div class="flex items-start space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                                {{ match($activity->type ?? 'default') {
                                                    'registration' => 'bg-indigo-100',
                                                    'payment' => 'bg-green-100',
                                                    'completion' => 'bg-yellow-100',
                                                    'certificate' => 'bg-blue-100',
                                                    default => 'bg-gray-100'
                                                } }}">
                                                <i class="text-lg
                                                    {{ match($activity->type ?? 'default') {
                                                        'registration' => 'fas fa-calendar-check text-indigo-600',
                                                        'payment' => 'fas fa-credit-card text-green-600',
                                                        'completion' => 'fas fa-medal text-yellow-600',
                                                        'certificate' => 'fas fa-certificate text-blue-600',
                                                        default => 'fas fa-bell text-gray-600'
                                                    } }}"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $activity->description ?? 'Aktivitas tidak diketahui' }}
                                                </p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <p class="text-xs text-gray-500">
                                                        {{ optional($activity->created_at)->diffForHumans() ?? 'Waktu tidak diketahui' }}
                                                    </p>
                                                    @if($activity->type === 'payment')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs 
                                                            {{ isset($activity->metadata['status']) && $activity->metadata['status'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ isset($activity->metadata['status']) && $activity->metadata['status'] === 'success' ? 'Lunas' : 'Menunggu' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(isset($activity->action_url))
                                                <a href="{{ $activity->action_url }}" 
                                                   class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                    Detail
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-history text-3xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada aktivitas</p>
                                    <p class="text-sm text-gray-400 mt-2">
                                        Aktivitas Anda akan muncul di sini setelah Anda mendaftar dan mengikuti event
                                    </p>
                                    <a href="{{ route('events.index') }}" 
                                       class="inline-flex items-center mt-4 text-indigo-600 hover:text-indigo-800">
                                        <i class="fas fa-search mr-2"></i>
                                        Jelajahi Event
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Profil Saya</h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-4">
                                <div class="mx-auto mb-4 flex justify-center">
                                    <x-user-avatar :size="80" class="flex-shrink-0" />
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                                <p class="text-gray-600 text-xs">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                                Edit Profil
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Aksi Cepat</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('events.index') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-search text-lg"></i>
                                <span>Cari Event</span>
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-user-edit text-lg"></i>
                                <span>Edit Profil</span>
                            </a>
                            <a href="{{ route('payment.index') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-credit-card text-lg"></i>
                                <span>Pembayaran</span>
                            </a>
                            <a href="{{ route('certificates.index') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-certificate text-lg"></i>
                                <span>Sertifikat Saya ({{ $certificateCount }})</span>
                            </a>
                            <a href="{{ route('materi.index') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-book text-lg"></i>
                                <span>Materi Belajar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
