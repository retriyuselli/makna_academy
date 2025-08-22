@extends('layouts.app')

@section('title', 'Detail Pembayaran - Makna Academy')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                        <span class="text-gray-500">Pembayaran</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('registration_success') || session('payment_status'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 8000)"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6" 
             role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/>
                    </svg>
                </div>
                <div>
                    @if(session('registration_success'))
                        <p class="font-bold text-green-800">{{ session('registration_success') }}</p>
                    @endif
                    @if(session('payment_status'))
                        <p class="text-sm mt-1">{{ session('payment_status') }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h1 class="text-xl font-semibold text-gray-900">Detail Pembayaran</h1>
            </div>

            <!-- Payment Status Timeline -->
            @if($registration->payment_status !== 'free')
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex items-center justify-between">
                            <!-- Pending Step -->
                            <div class="flex flex-col items-center">
                                @php
                                    $pendingClass = $registration->payment_status === 'pending' ? 'bg-blue-500 text-blue-500' : 
                                                   (in_array($registration->payment_status, ['down_payment_paid', 'fully_paid', 'paid']) ? 'bg-green-500 text-green-500' : 
                                                   ($registration->payment_status === 'failed' ? 'bg-red-500 text-red-500' : 'bg-gray-300 text-gray-500'));
                                @endphp
                                <div class="{{ explode(' ', $pendingClass)[0] }} rounded-full h-8 w-8 flex items-center justify-center">
                                    <i class="fas fa-file-invoice text-white text-sm"></i>
                                </div>
                                <p class="mt-2 text-xs font-medium {{ explode(' ', $pendingClass)[1] }}">
                                    @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                        Menunggu DP
                                    @else
                                        Menunggu Pembayaran
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Connection Line -->
                            @php
                                $lineClass = in_array($registration->payment_status, ['down_payment_paid', 'fully_paid', 'paid']) ? 'bg-green-500' : 
                                           ($registration->payment_status === 'failed' ? 'bg-red-500' : 'bg-gray-300');
                            @endphp
                            <div class="flex-1 h-1 mx-4 {{ $lineClass }}"></div>
                            
                            <!-- Verification & Process Step -->
                            <div class="flex flex-col items-center">
                                @php
                                    $verificationClass = ($registration->payment_status === 'pending' && $registration->bukti_pembayaran) || $registration->payment_status === 'waiting_verification' ? 'bg-blue-500 text-blue-500' : 
                                                        (in_array($registration->payment_status, ['down_payment_paid', 'fully_paid', 'paid']) ? 'bg-green-500 text-green-500' : 
                                                        ($registration->payment_status === 'failed' ? 'bg-red-500 text-red-500' : 'bg-gray-300 text-gray-500'));
                                @endphp
                                <div class="{{ explode(' ', $verificationClass)[0] }} rounded-full h-8 w-8 flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <p class="mt-2 text-xs font-medium {{ explode(' ', $verificationClass)[1] }}">
                                    @if($registration->payment_status === 'failed')
                                        Ditolak
                                    @else
                                        Proses Verifikasi
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Connection Line -->
                            <div class="flex-1 h-1 mx-4 {{ $lineClass }}"></div>
                            
                            <!-- Completed Step -->
                            <div class="flex flex-col items-center">
                                @php
                                    $completedClass = in_array($registration->payment_status, ['down_payment_paid', 'fully_paid', 'paid']) ? 'bg-green-500 text-green-500' : 'bg-gray-300 text-gray-500';
                                @endphp
                                <div class="{{ explode(' ', $completedClass)[0] }} rounded-full h-8 w-8 flex items-center justify-center">
                                    @if($registration->payment_status === 'down_payment_paid')
                                        <i class="fas fa-percentage text-white text-sm"></i>
                                    @else
                                        <i class="fas fa-check text-white text-sm"></i>
                                    @endif
                                </div>
                                <p class="mt-2 text-xs font-medium {{ explode(' ', $completedClass)[1] }}">
                                    @if($registration->payment_status === 'down_payment_paid')
                                        DP Selesai
                                    @else
                                        Selesai
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
                
                <!-- Status Information -->
                <div class="mt-6 max-w-4xl mx-auto bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-start">
                        @if($registration->payment_status === 'free')
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Event Gratis</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>Event ini tidak memerlukan pembayaran.</p>
                                    <p>Anda dapat langsung mengakses detail event di menu Dashboard.</p>
                                </div>
                            </div>
                        @elseif($registration->payment_status === 'pending' && !$registration->bukti_pembayaran)
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">
                                    @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                        Menunggu Pembayaran DP
                                    @else
                                        Menunggu Pembayaran
                                    @endif
                                </h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                        <p>Silakan selesaikan pembayaran down payment (DP) sesuai nominal yang tertera.</p>
                                        <p>Sisa pembayaran dapat diselesaikan kemudian sebelum acara dimulai.</p>
                                    @else
                                        <p>Silakan selesaikan pembayaran sesuai dengan nominal yang tertera.</p>
                                    @endif
                                    <p>Upload bukti transfer pada form di bawah setelah melakukan pembayaran.</p>
                                </div>
                            </div>
                        @elseif($registration->payment_status === 'waiting_verification' || ($registration->payment_status === 'pending' && $registration->bukti_pembayaran))
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Sedang Diverifikasi</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                        <p>Pembayaran DP Anda sedang diverifikasi oleh tim kami.</p>
                                    @else
                                        <p>Pembayaran Anda sedang diverifikasi oleh tim kami.</p>
                                    @endif
                                    <p>Estimasi waktu verifikasi: 1x24 jam kerja</p>
                                </div>
                            </div>
                        @elseif($registration->payment_status === 'down_payment_paid')
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Down Payment Berhasil</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>Pembayaran DP Anda telah dikonfirmasi.</p>
                                    <p>Sisa pembayaran <strong>Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}</strong> dapat diselesaikan sebelum acara dimulai.</p>
                                    @php
                                        $daysUntilEvent = \Carbon\Carbon::now()->diffInDays($registration->event->start_date, false);
                                    @endphp
                                    @if($daysUntilEvent >= 0)
                                        <p class="mt-2 {{ $daysUntilEvent <= 7 ? 'text-red-600 font-medium' : ($daysUntilEvent <= 14 ? 'text-orange-600 font-medium' : 'text-gray-600') }}">
                                            <i class="fas fa-clock mr-1"></i>
                                            Event dimulai dalam {{ $daysUntilEvent }} hari ({{ $registration->event->start_date->format('d M Y, H:i') }})
                                        </p>
                                        @if($daysUntilEvent <= 7)
                                            <p class="mt-1 text-red-600 font-medium">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Segera selesaikan pembayaran! Event akan dimulai kurang dari seminggu lagi.
                                            </p>
                                        @elseif($daysUntilEvent <= 14)
                                            <p class="mt-1 text-orange-600">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Disarankan untuk menyelesaikan pembayaran dalam minggu ini.
                                            </p>
                                        @endif
                                    @else
                                        <p class="mt-2 text-red-600 font-medium">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Event telah dimulai {{ abs($daysUntilEvent) }} hari yang lalu. Silakan hubungi panitia untuk penyelesaian pembayaran.
                                        </p>
                                    @endif
                                    <p class="mt-2">Anda dapat mengakses detail event di menu Dashboard.</p>
                                </div>
                            </div>
                        @elseif($registration->payment_status === 'fully_paid')
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Pembayaran Lunas</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>Pembayaran Anda telah lunas dan dikonfirmasi.</p>
                                    <p>Anda dapat mengakses detail event di menu Dashboard.</p>
                                </div>
                            </div>
                        @elseif($registration->payment_status === 'paid')
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Pembayaran Berhasil</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>Pembayaran Anda telah dikonfirmasi.</p>
                                    <p>Anda dapat mengakses detail event di menu Dashboard.</p>
                                </div>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <i class="fas fa-times-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Pembayaran Ditolak</h3>
                                <div class="mt-1 text-sm text-gray-600">
                                    <p>Pembayaran Anda tidak dapat diverifikasi.</p>
                                    <p>Silakan upload ulang bukti pembayaran yang valid.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Event Information -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Event</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-medium text-gray-700">{{ $registration->event->title }}</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ \Carbon\Carbon::parse($registration->registration_date)->isoFormat('dddd, D MMMM Y') }}
                            </p>
                        </div>
                        <div class="text-right mr-5">
                            <p class="text-gray-600">Kode Pendaftaran:</p>
                            <p class="font-mono text-gray-900">{{ $registration->confirmation_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h2>
                    <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Invoice</p>
                            <p class="font-medium text-gray-900">{{ $registration->invoice_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                    Pembayaran Down Payment (DP)
                                @elseif($registration->event->has_down_payment && $registration->remaining_amount == 0)
                                    Pembayaran Lunas
                                @else
                                    Total Pembayaran
                                @endif
                            </p>
                            <p class="font-medium text-gray-900">
                                @if($registration->payment_status === 'free')
                                    <span class="text-green-600">GRATIS</span>
                                @else
                                    Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}
                                @endif
                            </p>
                            @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                <p class="text-xs text-gray-500 mt-1">
                                    Sisa pembayaran: Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- Payment Type Information -->
                        @if($registration->event->has_down_payment)
                        <div>
                            <p class="text-sm text-gray-600">Jenis Pembayaran</p>
                            <p class="font-medium text-gray-900">
                                @if($registration->remaining_amount > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-percentage mr-1"></i>
                                        Down Payment (DP)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Pembayaran Penuh
                                    </span>
                                @endif
                            </p>
                            @if($registration->remaining_amount > 0)
                                <p class="text-xs text-gray-500 mt-1">
                                    @if($registration->event->down_payment_type === 'percentage')
                                        DP {{ $registration->event->down_payment_percentage }}% dari total harga
                                    @else
                                        DP tetap Rp {{ number_format($registration->event->down_payment_amount, 0, ',', '.') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="mt-1">
                                @if($registration->payment_status === 'pending' && $registration->bukti_pembayaran)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($registration->payment_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($registration->payment_status === 'down_payment_paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        DP Terbayar
                                    </span>
                                @elseif($registration->payment_status === 'fully_paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Lunas
                                    </span>
                                @elseif($registration->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Lunas
                                    </span>
                                @elseif($registration->payment_status === 'free')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        Gratis
                                    </span>
                                @elseif($registration->payment_status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Pembayaran Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Metode Pembayaran</p>
                            <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $registration->payment_method)) }}</p>
                        </div>
                    </div>
                    
                    <!-- Download Invoice Button -->
                    @if(!$registration->event->is_free && $registration->invoice_number)
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <p>Butuh invoice untuk keperluan administrasi?</p>
                            </div>
                                                        <a href="{{ route('invoice.show', $registration->invoice_number) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Invoice
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Payment Method Details -->
                @if(!$registration->event->is_free)
                    @if($registration->payment_method === 'bank_transfer')
                        <div class="mb-8">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Instruksi Transfer Bank</h2>
                            <div class="bg-blue-50 rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Nama Bank</p>
                                        <p class="font-medium text-gray-900">Bank Mandiri</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Nomor Rekening</p>
                                        <div class="flex items-center space-x-2">
                                            <p class="font-mono text-gray-900">1130051511115</p>
                                            <button onclick="copyToClipboard('1130051511115')" 
                                                    class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Atas Nama</p>
                                        <p class="font-medium text-gray-900">PT. Makna Kreatif Indonesia</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">
                                            @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                                Total Transfer DP
                                            @else
                                                Total Transfer
                                            @endif
                                        </p>
                                        <p class="font-medium text-gray-900">Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}</p>
                                        @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                            <p class="text-xs text-gray-600 mt-1">
                                                Sisa: Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mt-6 border-t border-blue-100 pt-4">
                                    <p class="font-medium text-gray-900 mb-2">
                                        @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                            Petunjuk Transfer Down Payment:
                                        @else
                                            Petunjuk Transfer:
                                        @endif
                                    </p>
                                    <ul class="space-y-2">
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span class="text-gray-700">
                                                @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                                    Transfer sesuai nominal DP yang tertera (Rp {{ number_format($registration->payment_amount, 0, ',', '.') }})
                                                @else
                                                    Transfer sesuai dengan nominal yang tertera
                                                @endif
                                            </span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span class="text-gray-700">Simpan bukti transfer</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span class="text-gray-700">Upload bukti transfer pada form di bawah</span>
                                        </li>
                                        @if($registration->event->has_down_payment && $registration->remaining_amount > 0)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-orange-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L2.732 14.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <span class="text-orange-700">
                                                Sisa pembayaran Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }} dapat dibayar sebelum acara dimulai
                                            </span>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Uploaded Payment Proof -->
                @if($registration->bukti_pembayaran && !$registration->event->is_free)
                    <div class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Bukti Pembayaran</h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Status Verifikasi</p>
                                    @if($registration->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Terverifikasi
                                        </span>
                                    @elseif($registration->payment_status === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ Storage::url($registration->bukti_pembayaran) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 border border-blue-600 rounded-md text-sm font-medium text-blue-600 hover:text-blue-800 hover:border-blue-800">
                                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z">
                                            </path>
                                        </svg>
                                        Lihat Bukti
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upload Payment Form -->
                {{-- @if($registration->payment_status !== 'free')
                    @if($registration->payment_status === 'pending' || $registration->payment_status === 'failed')
                        <div class="mb-8">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Upload Bukti Pembayaran</h2>
                            <form action="{{ route('payment.upload', $registration->id) }}" 
                                method="POST" 
                                enctype="multipart/form-data" 
                                class="bg-white border border-gray-200 rounded-lg p-6">
                                @csrf
                                <div class="mb-4">
                                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                                        File Bukti Pembayaran
                                    </label>
                                    <input type="file" 
                                        id="bukti_pembayaran" 
                                        name="bukti_pembayaran" 
                                        accept="image/*,.pdf"
                                        class="block w-full text-sm text-gray-600
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-lg file:border-0
                                                file:text-sm file:font-medium
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100
                                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                                        required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Format yang didukung: JPG, PNG, atau PDF. Maksimal 2MB.
                                    </p>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" 
                                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Upload Bukti Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif --}}

                <!-- Remaining Payment Information -->
                @if($registration->payment_status === 'down_payment_paid' && $registration->remaining_amount > 0)
                    <div class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran Sisa</h2>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-orange-500 text-xl"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-medium text-orange-800">
                                        Cara Menyelesaikan Pembayaran Sisa
                                    </h3>
                                    <div class="mt-2 text-sm text-orange-700">
                                        <p class="mb-3">
                                            <strong>Sisa pembayaran:</strong> Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}
                                        </p>
                                        <div class="space-y-2">
                                            <p><strong>Opsi 1: Transfer Bank (Direkomendasikan)</strong></p>
                                            <ul class="list-disc list-inside ml-4 space-y-1">
                                                <li>Transfer ke rekening yang sama seperti pembayaran DP</li>
                                                <li>Bank Mandiri : 1130051511115 a.n. PT. Makna Kreatif Indonesia</li>
                                                <li>Nominal: Rp {{ number_format($registration->remaining_amount, 0, ',', '.') }}</li>
                                                <li>Simpan bukti transfer</li>
                                            </ul>

                                            <p class="mt-4"><strong>Opsi 2: Bayar 1 bulan sebelum event</strong></p>
                                            <ul class="list-disc list-inside ml-4 space-y-1">
                                                <li>Bayar sebelum 30 hari sebelum kegiatan (cash/debit/QRIS)</li>
                                                <li>Lakukan komunikasi dengan panitia untuk konfirmasi pembayaran</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20menyelesaikan%20pembayaran%20sisa%20untuk%20event%20{{ urlencode($registration->event->title) }}%20dengan%20kode%20registrasi%20{{ $registration->confirmation_code }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fab fa-whatsapp mr-2"></i>
                                            Hubungi via WhatsApp
                                        </a>
                                        <a href="mailto:{{ $registration->event->contact_email }}?subject=Pembayaran%20Sisa%20-%20{{ urlencode($registration->event->title) }}&body=Halo,%0A%0ASaya%20ingin%20menyelesaikan%20pembayaran%20sisa%20untuk%20event%20{{ urlencode($registration->event->title) }}.%0A%0AKode%20Registrasi:%20{{ $registration->confirmation_code }}%0ASisa%20Pembayaran:%20Rp%20{{ number_format($registration->remaining_amount, 0, ',', '.') }}%0A%0ATerima%20kasih." 
                                           class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-envelope mr-2"></i>
                                            Email Panitia
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Bottom Actions -->
                <div class="mt-8 flex flex-col items-center justify-center space-y-4">
                    <a href="{{ route('events.index') }}" 
                       class="inline-flex items-center text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                        Kembali ke Daftar Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Berhasil disalin!');
        }).catch(function(err) {
            console.error('Gagal menyalin teks: ', err);
        });
    }
</script>
@endpush

@endsection
