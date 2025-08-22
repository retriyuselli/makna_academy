<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-2 font-poppins">
            <i class="fas fa-key text-orange-600 mr-2"></i>
            Reset Password
        </h2>
        <p class="text-gray-600 font-poppins font-medium">
            Lupa password? Masukkan email Anda dan kami akan mengirimkan link reset password.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">
                <i class="fas fa-envelope mr-1"></i>
                Email Address
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors font-poppins"
                   placeholder="Masukkan email Anda">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg hover:from-orange-600 hover:to-red-700 transition-all duration-300 font-poppins">
            <i class="fas fa-paper-plane mr-2"></i>
            Kirim Link Reset Password
        </button>

        <!-- Back to Login -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-gray-600 font-poppins">
                Ingat password Anda?
                <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-800 font-semibold transition-colors">
                    Kembali ke Login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
