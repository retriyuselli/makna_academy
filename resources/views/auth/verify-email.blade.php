<x-guest-layout>
    <!-- Email Verification Content -->
    <div class="text-center mb-6">
        <!-- Logo Section -->
        @php
            $company = \App\Models\Company::first();
        @endphp
        @if (isset($company) && $company->logo)
            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name ?? 'Company Logo' }}"
                class="mx-auto h-16 w-auto mb-4">
        @else
            <div class="mb-4">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent font-poppins">
                    Makna Academy
                </h1>
            </div>
        @endif

        <!-- Email Icon -->
        <div class="mx-auto w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-envelope text-white text-2xl"></i>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-poppins">
            <i class="fas fa-check-circle text-blue-600 mr-2"></i>
            Verifikasi Email Anda
        </h2>
        <p class="text-gray-600 font-poppins text-sm leading-relaxed">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan.
        </p>
    </div>

    <!-- Success Message -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-green-800 font-poppins">
                        Link verifikasi baru telah dikirim ke email Anda!
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- User Email Display -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
        <div class="flex items-center">
            <i class="fas fa-user-circle text-gray-500 text-lg mr-3"></i>
            <div>
                <p class="text-xs text-gray-500 font-poppins font-medium">Email Anda:</p>
                <p class="font-semibold text-gray-900 font-poppins">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-4">
        <!-- Resend Email Button -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center py-3 px-6 text-white font-semibold rounded-lg
                       bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700
                       focus:outline-none focus:ring-4 focus:ring-blue-300
                       transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-poppins">
                <i class="fas fa-paper-plane mr-2"></i>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-3 px-6 text-gray-700 font-semibold bg-gray-100 border border-gray-300 rounded-lg 
                       hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 
                       transition-all duration-200 font-poppins">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Keluar
            </button>
        </form>
    </div>

    <!-- Tips Section -->
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="text-sm font-semibold text-blue-800 mb-3 font-poppins flex items-center">
            <i class="fas fa-lightbulb mr-2"></i>
            Tips Verifikasi Email:
        </h3>
        <ul class="text-sm text-blue-700 space-y-2 font-poppins">
            <li class="flex items-start">
                <i class="fas fa-arrow-right text-blue-500 mr-2 mt-0.5 text-xs"></i>
                Periksa folder spam/junk email Anda
            </li>
            <li class="flex items-start">
                <i class="fas fa-arrow-right text-blue-500 mr-2 mt-0.5 text-xs"></i>
                Pastikan email <strong>{{ auth()->user()->email }}</strong> benar
            </li>
            <li class="flex items-start">
                <i class="fas fa-arrow-right text-blue-500 mr-2 mt-0.5 text-xs"></i>
                Link verifikasi berlaku selama 60 menit
            </li>
            <li class="flex items-start">
                <i class="fas fa-arrow-right text-blue-500 mr-2 mt-0.5 text-xs"></i>
                Jika masih bermasalah, hubungi support kami
            </li>
        </ul>
    </div>

    <!-- Back to Home Link -->
    <div class="text-center mt-6 pt-4 border-t border-gray-200">
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors font-poppins text-sm">
            <i class="fas fa-home mr-1"></i>
            Kembali ke Beranda
        </a>
    </div>
</x-guest-layout>
