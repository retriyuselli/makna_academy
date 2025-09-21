@extends('layouts.app')

@section('title', 'Daftar Event: ' . $event->title . ' - Makna Academy')

@section('scripts')
    <style>
        .package-card.selected {
            border-color: #3B82F6;
            background-color: #EFF6FF;
        }

        .package-card.selected.gold {
            border-color: #F59E0B;
            background-color: #FFFBEB;
        }

        .package-card.selected.platinum {
            border-color: #3B82F6;
            background-color: #EFF6FF;
        }

        .radio-circle.selected {
            border-color: #3B82F6;
            background-color: #3B82F6;
            position: relative;
        }

        .radio-circle.selected::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            background-color: white;
            border-radius: 50%;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
            const form = document.querySelector('form');
            const modal = document.getElementById('successModal');

            // Handle package selection for Expo events
            const packageRadios = document.querySelectorAll('.package-radio');
            const packageCards = document.querySelectorAll('.package-card');
            const radioCircles = document.querySelectorAll('.radio-circle');

            // Function to update package selection UI
            function updatePackageSelection() {
                packageRadios.forEach((radio, index) => {
                    const card = packageCards[index];
                    const circle = radioCircles[index];

                    if (radio.checked) {
                        card.classList.add('selected');
                        circle.classList.add('selected');

                        if (radio.value === 'gold') {
                            card.classList.add('gold');
                        } else if (radio.value === 'platinum') {
                            card.classList.add('platinum');
                        }
                    } else {
                        card.classList.remove('selected', 'gold', 'platinum');
                        circle.classList.remove('selected');
                    }
                });
            }

            // Handle package card clicks
            packageCards.forEach((card, index) => {
                card.addEventListener('click', function() {
                    packageRadios[index].checked = true;
                    updatePackageSelection();
                });
            });

            // Initialize package selection
            updatePackageSelection();

            // Handle modal close button
            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('successModal').classList.add('hidden');
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                const modal = document.getElementById('successModal');
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // Show modal on successful form submission
            form.addEventListener('submit', function(e) {
                // Form will still submit normally, we just show the modal
                if (form.checkValidity()) {
                    document.getElementById('successModal').classList.remove('hidden');
                }
            });

            // Handle Terms and Conditions Modal
            document.getElementById('termsLink').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('termsModal').classList.remove('hidden');
            });

            document.getElementById('closeTermsModal').addEventListener('click', function() {
                document.getElementById('termsModal').classList.add('hidden');
            });

            document.getElementById('acceptTerms').addEventListener('click', function() {
                document.getElementById('termsModal').classList.add('hidden');
            });

            // Handle Privacy Policy Modal
            document.getElementById('privacyLink').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('privacyModal').classList.remove('hidden');
            });

            document.getElementById('closePrivacyModal').addEventListener('click', function() {
                document.getElementById('privacyModal').classList.add('hidden');
            });

            document.getElementById('acceptPrivacy').addEventListener('click', function() {
                document.getElementById('privacyModal').classList.add('hidden');
            });

            // Close modals when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === document.getElementById('termsModal')) {
                    document.getElementById('termsModal').classList.add('hidden');
                }
                if (e.target === document.getElementById('privacyModal')) {
                    document.getElementById('privacyModal').classList.add('hidden');
                }
            });
            const bankTransferSection = document.getElementById('bank_transfer_section');
            const buktiPembayaranInput = document.getElementById('bukti_pembayaran');

            paymentMethodInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value === 'bank_transfer') {
                        bankTransferSection.classList.remove('hidden');
                        buktiPembayaranInput.required = true;
                    } else {
                        bankTransferSection.classList.add('hidden');
                        buktiPembayaranInput.required = false;
                    }
                });
            });

            // Trigger the change event if bank_transfer is pre-selected
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPaymentMethod && selectedPaymentMethod.value === 'bank_transfer') {
                selectedPaymentMethod.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Daftar Event</h1>
                <p class="text-xl opacity-90">{{ $event->title }}</p>
            </div>
        </div>
    </section>

    <!-- Registration Form -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Event Info Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                        <div class="mb-4">
                            <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/400x250/4F46E5/FFFFFF?text=' . urlencode($event->title) }}"
                                alt="{{ $event->title }}" class="w-full h-auto object-cover rounded-lg">
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $event->title }}</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar mr-3 text-indigo-500"></i>
                                <span>{{ $event->start_date->format('d M Y') }}</span>
                                @if ($event->end_date && $event->end_date->format('Y-m-d') !== $event->start_date->format('Y-m-d'))
                                    - {{ $event->end_date->format('d M Y') }}
                                @endif
                            </div>

                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock mr-3 text-indigo-500"></i>
                                <span>{{ $event->start_time }} - {{ $event->end_time }} WIB</span>
                            </div>

                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-3 text-indigo-500"></i>
                                <span>{{ $event->location }}, {{ $event->city }}</span>
                            </div>

                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-user mr-3 text-indigo-500"></i>
                                <span>{{ $event->actual_participants }}/{{ $event->max_participants }} peserta</span>
                            </div>

                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tag mr-3 text-indigo-500"></i>
                                <span class="font-semibold text-lg">
                                    @if ($event->is_free)
                                        <span class="text-green-600">GRATIS</span>
                                    @elseif ($event->category && str_contains(strtolower($event->category->name), 'expo'))
                                        <div class="space-y-1">
                                            <div class="text-yellow-600">
                                                Gold: Rp {{ number_format($event->price_gold, 0, ',', '.') }}
                                            </div>
                                            <div class="text-blue-600">
                                                Platinum: Rp {{ number_format($event->price_platinum, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-indigo-600">Rp
                                            {{ number_format($event->price, 0, ',', '.') }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if ($event->category)
                            <div class="mt-4">
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $event->category->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        @if ($alreadyRegistered)
                            <div class="text-center py-12">
                                <div class="text-green-500 text-6xl mb-4">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Anda Sudah Terdaftar</h3>
                                <p class="text-gray-600 mb-6">Anda sudah terdaftar untuk event ini.</p>

                                @if (session('success'))
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <a href="{{ route('events.index') }}"
                                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-300">
                                    Kembali ke Event
                                </a>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Form Pendaftaran</h2>

                            @if (session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('events.register', $event) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <!-- Personal Information -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <input type="text" name="name"
                                            value="{{ old('name', Auth::user()->name ?? '') }}" readonly
                                            class="w-full px-4 text-sm py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300">

                                        <input type="email" name="email"
                                            value="{{ old('email', Auth::user()->email ?? '') }}" readonly
                                            class="w-full px-4 text-sm py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300">

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon
                                                *</label>
                                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            @error('phone')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="experience_level"
                                                class="block text-sm font-medium text-gray-700 mb-2">
                                                Level Pengalaman <span class="text-red-500">*</span>
                                            </label>

                                            <div class="relative">
                                                <select name="experience_level" id="experience_level" required
                                                    class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500focus:border-indigo-500 transition ease-in-out duration-200 cursor-pointer">
                                                    <option value="" class="text-gray-400">Pilih Level</option>
                                                    <option value="beginner"
                                                        {{ old('experience_level') === 'beginner' ? 'selected' : '' }}>
                                                        Pemula</option>
                                                    <option value="intermediate"
                                                        {{ old('experience_level') === 'intermediate' ? 'selected' : '' }}>
                                                        Menengah</option>
                                                    <option value="advanced"
                                                        {{ old('experience_level') === 'advanced' ? 'selected' : '' }}>
                                                        Lanjutan</option>
                                                </select>

                                                {{-- Ikon panah di kanan --}}
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>

                                            @error('experience_level')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Professional Information -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Profesional</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Perusahaan/Institusi</label>
                                            <input type="text" name="company" value="{{ old('company') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            @error('company')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Posisi/Jabatan</label>
                                            <input type="text" name="position" value="{{ old('position') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            @error('position')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Motivation -->
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Motivasi & Kebutuhan</h3>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Motivasi mengikuti
                                                event ini *</label>
                                            <textarea name="motivation" rows="4" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                                placeholder="Ceritakan mengapa Anda tertarik mengikuti event ini...">{{ old('motivation') }}</textarea>
                                            @error('motivation')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Harapan</label>
                                            <input type="text" name="special_needs"
                                                value="{{ old('special_needs') }}"
                                                placeholder="Aksesibilitas, bantuan khusus, dll."
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            @error('special_needs')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Package Selection (for Expo events) -->
                                @if (!$event->is_free && $event->category && str_contains(strtolower($event->category->name), 'expo'))
                                    <div class="border-b border-gray-200 pb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Package</h3>

                                        <div class="space-y-4">
                                            <label class="block">
                                                <input type="radio" name="package_type" value="gold"
                                                    {{ old('package_type', 'gold') === 'gold' ? 'checked' : '' }}
                                                    class="sr-only package-radio" required>
                                                <div
                                                    class="package-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-yellow-400 transition-colors">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div
                                                                class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3 radio-circle">
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-yellow-600">Gold
                                                                    Package</h4>
                                                                <p class="text-gray-600">Paket standar dengan fasilitas
                                                                    lengkap</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-2xl font-bold text-yellow-600">
                                                                Rp {{ number_format($event->price_gold, 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>

                                            <label class="block">
                                                <input type="radio" name="package_type" value="platinum"
                                                    {{ old('package_type') === 'platinum' ? 'checked' : '' }}
                                                    class="sr-only package-radio" required>
                                                <div
                                                    class="package-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition-colors">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <div
                                                                class="w-4 h-4 rounded-full border-2 border-gray-300 mr-3 radio-circle">
                                                            </div>
                                                            <div>
                                                                <h4 class="text-lg font-semibold text-blue-600">Platinum
                                                                    Package</h4>
                                                                <p class="text-gray-600">Paket premium dengan fasilitas
                                                                    eksklusif</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-2xl font-bold text-blue-600">
                                                                Rp {{ number_format($event->price_platinum, 0, ',', '.') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        @error('package_type')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Payment Method (if not free) -->
                                @if (!$event->is_free)
                                    <div class="border-b border-gray-200 pb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h3>

                                        <div class="space-y-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="payment_method"
                                                    value="{{ App\Models\EventRegistration::PAYMENT_METHOD_BANK_TRANSFER }}"
                                                    {{ old('payment_method') == App\Models\EventRegistration::PAYMENT_METHOD_BANK_TRANSFER ? 'checked' : '' }}
                                                    class="mr-3 text-indigo-600" required>
                                                <div>
                                                    <span class="text-gray-700">Transfer Bank</span>
                                                    <p class="text-sm text-gray-500 ml-6">Transfer manual ke rekening yang
                                                        ditentukan</p>
                                                </div>
                                            </label>

                                            <label class="flex items-center opacity-50 cursor-not-allowed">
                                                <input type="radio" name="payment_method"
                                                    value="{{ App\Models\EventRegistration::PAYMENT_METHOD_CREDIT_CARD }}"
                                                    {{ old('payment_method') == App\Models\EventRegistration::PAYMENT_METHOD_CREDIT_CARD ? 'checked' : '' }}
                                                    class="mr-3 text-indigo-600" disabled>
                                                <div>
                                                    <span class="text-gray-700">Kartu Kredit</span>
                                                    <p class="text-sm text-gray-500 ml-6">Segera hadir</p>
                                                </div>
                                            </label>

                                            <label class="flex items-center opacity-50 cursor-not-allowed">
                                                <input type="radio" name="payment_method"
                                                    value="{{ App\Models\EventRegistration::PAYMENT_METHOD_E_WALLET }}"
                                                    {{ old('payment_method') == App\Models\EventRegistration::PAYMENT_METHOD_E_WALLET ? 'checked' : '' }}
                                                    class="mr-3 text-indigo-600" disabled>
                                                <div>
                                                    <span class="text-gray-700">E-Wallet</span>
                                                    <p class="text-sm text-gray-500 ml-6">GoPay, OVO, DANA (Segera hadir)
                                                    </p>
                                                </div>
                                            </label>
                                        </div>

                                        @error('payment_method')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror

                                        <!-- Down Payment Option (if available) -->
                                        @if ($event->has_down_payment)
                                            <div class="mt-6 border-t border-gray-200 pt-6">
                                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Opsi Pembayaran</h4>

                                                <div class="space-y-3">
                                                    <label class="flex items-start cursor-pointer">
                                                        <input type="radio" name="payment_type" value="full_payment"
                                                            {{ old('payment_type', 'full_payment') == 'full_payment' ? 'checked' : '' }}
                                                            class="mt-1 mr-3 text-indigo-600" required>
                                                        <div class="flex-1">
                                                            <span class="text-gray-700 font-medium">Bayar Lunas</span>
                                                            <p class="text-sm text-gray-500">Bayar full harga event
                                                                sekaligus</p>
                                                            <div class="mt-2 text-sm">
                                                                @if ($event->eventCategory && str_contains(strtolower($event->eventCategory->name), 'expo'))
                                                                    <div class="grid grid-cols-2 gap-2">
                                                                        <div class="bg-yellow-50 p-2 rounded">
                                                                            <span class="text-yellow-700">Gold: Rp
                                                                                {{ number_format($event->price_gold, 0, ',', '.') }}</span>
                                                                        </div>
                                                                        <div class="bg-blue-50 p-2 rounded">
                                                                            <span class="text-blue-700">Platinum: Rp
                                                                                {{ number_format($event->price_platinum, 0, ',', '.') }}</span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <span class="text-indigo-600 font-medium">Rp
                                                                        {{ number_format($event->price, 0, ',', '.') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>

                                                    <label class="flex items-start cursor-pointer">
                                                        <input type="radio" name="payment_type" value="down_payment"
                                                            {{ old('payment_type') == 'down_payment' ? 'checked' : '' }}
                                                            class="mt-1 mr-3 text-indigo-600">
                                                        <div class="flex-1">
                                                            <span class="text-gray-700 font-medium">Bayar Down Payment
                                                                (DP)</span>
                                                            <p class="text-sm text-gray-500">
                                                                @if ($event->down_payment_type === 'percentage')
                                                                    Bayar {{ $event->down_payment_percentage }}% dari total
                                                                    harga, sisanya dapat dibayar kemudian
                                                                @else
                                                                    Bayar DP Rp
                                                                    {{ number_format($event->down_payment_amount, 0, ',', '.') }},
                                                                    sisanya dapat dibayar kemudian
                                                                @endif
                                                            </p>
                                                            <div class="mt-2 text-sm">
                                                                @if ($event->eventCategory && str_contains(strtolower($event->eventCategory->name), 'expo'))
                                                                    <div class="grid grid-cols-2 gap-2">
                                                                        <div class="bg-yellow-50 p-2 rounded">
                                                                            <span class="text-yellow-700">Gold DP: Rp
                                                                                {{ number_format($event->getPackageDownPayment('gold'), 0, ',', '.') }}</span>
                                                                        </div>
                                                                        <div class="bg-blue-50 p-2 rounded">
                                                                            <span class="text-blue-700">Platinum DP: Rp
                                                                                {{ number_format($event->getPackageDownPayment('platinum'), 0, ',', '.') }}</span>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <span class="text-green-600 font-medium">DP: Rp
                                                                        {{ number_format($event->getPackageDownPayment('regular'), 0, ',', '.') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>

                                                @error('payment_type')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif

                                        <!-- Bank Transfer Details -->
                                        <div id="bank_transfer_section" class="mt-4 space-y-4 hidden">
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h5 class="font-medium text-gray-900 mb-2">Informasi Rekening</h5>
                                                <div class="space-y-2 text-sm text-gray-600">
                                                    <p>Bank : Mandiri</p>
                                                    <p>No. Rekening : 1130051511115</p>
                                                    <p>Atas Nama : PT Makna Kreatif Indonesia</p>
                                                    <p class="mt-2 text-xs text-gray-500">Mohon transfer sesuai dengan
                                                        nominal yang tertera</p>
                                                </div>
                                            </div>

                                            <!-- Upload Bukti Transfer -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                                                </label>
                                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                                    accept="image/jpeg,image/png"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                                <p class="mt-1 text-sm text-gray-500">
                                                    Format yang diterima: JPG, PNG (Maks. 2MB)
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Pastikan bukti pembayaran jelas dan menampilkan:<br>
                                                    - Tanggal dan waktu transfer<br>
                                                    - Nominal transfer<br>
                                                    - Status berhasil
                                                </p>
                                                @error('bukti_pembayaran')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Terms and Submit -->
                                <div class="pt-6">
                                    <div class="mb-6">
                                        <label class="flex items-start">
                                            <input type="checkbox" required class="mr-3 mt-1 text-indigo-600">
                                            <span class="text-sm text-gray-700">
                                                Saya menyetujui <a href="#" id="termsLink"
                                                    class="text-indigo-600 hover:underline">syarat dan ketentuan</a>
                                                serta <a href="#" id="privacyLink"
                                                    class="text-indigo-600 hover:underline">kebijakan
                                                    privasi</a> yang berlaku.
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <button type="submit"
                                            class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                                            @if ($event->is_free)
                                                Daftar Sekarang
                                            @else
                                                Daftar & Lanjut ke Pembayaran
                                            @endif
                                        </button>

                                        <a href="{{ route('events.index') }}"
                                            class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition duration-300 font-semibold flex items-center justify-center">
                                            Batal
                                        </a>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
        style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Pendaftaran Berhasil!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Terima kasih telah mendaftar di event {{ $event->title }}.
                        @if (!$event->is_free)
                            Silakan melakukan pembayaran sesuai dengan instruksi yang telah diberikan.
                        @endif
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal"
                        class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
        style="z-index: 50;">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">Syarat dan Ketentuan</h3>
                    <button id="closeTermsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="max-h-96 overflow-y-auto px-2">
                    <div class="space-y-4 text-sm text-gray-700">
                        <h4 class="font-semibold text-lg text-gray-900">1. Ketentuan Umum</h4>
                        <p>Dengan mendaftar dan mengikuti event Makna Academy, peserta dianggap telah membaca, memahami, dan
                            menyetujui seluruh syarat dan ketentuan yang berlaku.</p>

                        <h4 class="font-semibold text-lg text-gray-900">2. Pendaftaran</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Peserta wajib mengisi formulir pendaftaran dengan data yang benar dan lengkap</li>
                            <li>Pendaftaran baru dianggap sah setelah melakukan pembayaran (untuk event berbayar)</li>
                            <li>Kuota peserta terbatas dan akan ditutup jika sudah mencapai batas maksimal</li>
                            <li>Makna Academy berhak menolak pendaftaran tanpa memberikan alasan</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">3. Pembayaran dan Refund</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Pembayaran dilakukan sesuai dengan nominal yang tertera</li>
                            <li>Bukti pembayaran wajib diunggah untuk verifikasi</li>
                            <li>Refund hanya dapat dilakukan jika event dibatalkan oleh penyelenggara</li>
                            <li>Pembatalan dari peserta tidak dapat dikembalikan biayanya</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">4. Pelaksanaan Event</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Peserta wajib hadir tepat waktu sesuai jadwal yang ditentukan</li>
                            <li>Peserta wajib mematuhi protokol kesehatan yang berlaku</li>
                            <li>Penyelenggara berhak mengubah jadwal, lokasi, atau format event dengan pemberitahuan
                                sebelumnya</li>
                            <li>Peserta dilarang melakukan tindakan yang mengganggu jalannya event</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">5. Sertifikat</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Sertifikat hanya diberikan kepada peserta yang mengikuti event hingga selesai</li>
                            <li>Sertifikat akan dikirimkan dalam bentuk digital paling lambat 7 hari kerja setelah event
                            </li>
                            <li>Sertifikat fisik dapat diminta dengan biaya tambahan</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">6. Hak Cipta dan Dokumentasi</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Seluruh materi event adalah hak cipta Makna Academy</li>
                            <li>Peserta dilarang merekam, menyebarkan, atau menggunakan materi event tanpa izin</li>
                            <li>Penyelenggara berhak melakukan dokumentasi dan menggunakan foto/video untuk keperluan
                                promosi</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">7. Tanggung Jawab</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Peserta bertanggung jawab atas keamanan barang pribadi selama event</li>
                            <li>Penyelenggara tidak bertanggung jawab atas kehilangan atau kerusakan barang peserta</li>
                            <li>Peserta bertanggung jawab atas kesehatan diri sendiri selama mengikuti event</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">8. Force Majeure</h4>
                        <p>Penyelenggara tidak bertanggung jawab atas pembatalan atau penundaan event yang disebabkan oleh
                            keadaan kahar (force majeure) seperti bencana alam, wabah penyakit, kebijakan pemerintah, atau
                            kondisi darurat lainnya.</p>

                        <h4 class="font-semibold text-lg text-gray-900">9. Perubahan Syarat dan Ketentuan</h4>
                        <p>Makna Academy berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan
                            sebelumnya. Perubahan akan berlaku efektif sejak dipublikasikan.</p>

                        <h4 class="font-semibold text-lg text-gray-900">10. Hukum yang Berlaku</h4>
                        <p>Syarat dan ketentuan ini tunduk pada hukum Republik Indonesia. Segala perselisihan akan
                            diselesaikan melalui musyawarah atau melalui pengadilan yang berwenang.</p>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button id="acceptTerms"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden"
        style="z-index: 50;">
        <div
            class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900">Kebijakan Privasi</h3>
                    <button id="closePrivacyModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="max-h-96 overflow-y-auto px-2">
                    <div class="space-y-4 text-sm text-gray-700">
                        <h4 class="font-semibold text-lg text-gray-900">1. Informasi yang Kami Kumpulkan</h4>
                        <p>Kami mengumpulkan informasi pribadi yang Anda berikan saat mendaftar event, termasuk namun tidak
                            terbatas pada:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Nama lengkap</li>
                            <li>Alamat email</li>
                            <li>Nomor telepon</li>
                            <li>Informasi perusahaan/institusi</li>
                            <li>Motivasi dan kebutuhan khusus</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">2. Penggunaan Informasi</h4>
                        <p>Informasi yang kami kumpulkan digunakan untuk:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Memproses pendaftaran dan pembayaran event</li>
                            <li>Berkomunikasi dengan peserta mengenai event</li>
                            <li>Menyediakan layanan pelanggan</li>
                            <li>Mengirimkan sertifikat dan materi event</li>
                            <li>Meningkatkan kualitas layanan kami</li>
                            <li>Mengirimkan informasi tentang event mendatang (dengan persetujuan)</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">3. Pembagian Informasi</h4>
                        <p>Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga
                            tanpa persetujuan, kecuali:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Untuk memenuhi kewajiban hukum</li>
                            <li>Untuk melindungi hak dan keamanan kami</li>
                            <li>Kepada penyedia layanan yang membantu operasional kami (dengan kontrak kerahasiaan)</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">4. Keamanan Data</h4>
                        <p>Kami menerapkan langkah-langkah keamanan yang tepat untuk melindungi informasi pribadi Anda dari
                            akses yang tidak sah, perubahan, pengungkapan, atau penghancuran.</p>

                        <h4 class="font-semibold text-lg text-gray-900">5. Cookies</h4>
                        <p>Website kami menggunakan cookies untuk meningkatkan pengalaman pengguna. Anda dapat mengatur
                            browser untuk menolak cookies, namun beberapa fitur website mungkin tidak berfungsi optimal.</p>

                        <h4 class="font-semibold text-lg text-gray-900">6. Hak Pengguna</h4>
                        <p>Anda memiliki hak untuk:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Mengakses dan memperbarui informasi pribadi Anda</li>
                            <li>Meminta penghapusan data pribadi Anda</li>
                            <li>Menolak penggunaan data untuk tujuan pemasaran</li>
                            <li>Mengajukan keluhan terkait penanganan data pribadi</li>
                        </ul>

                        <h4 class="font-semibold text-lg text-gray-900">7. Penyimpanan Data</h4>
                        <p>Data pribadi Anda akan disimpan selama diperlukan untuk tujuan yang dijelaskan dalam kebijakan
                            ini, atau sesuai dengan ketentuan hukum yang berlaku.</p>

                        <h4 class="font-semibold text-lg text-gray-900">8. Perubahan Kebijakan</h4>
                        <p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Perubahan akan diinformasikan
                            melalui website atau email.</p>

                        <h4 class="font-semibold text-lg text-gray-900">9. Kontak</h4>
                        <p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami di:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Email: privacy@maknaacademy.com</li>
                            <li>Telepon: +62 21 1234 5678</li>
                        </ul>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button id="acceptPrivacy"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
