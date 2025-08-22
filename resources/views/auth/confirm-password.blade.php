<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-2 font-poppins">
            <i class="fas fa-shield-alt text-red-600 mr-2"></i>
            Konfirmasi Password
        </h2>
        <p class="text-gray-600 font-poppins font-medium">
            Untuk keamanan, mohon konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 font-poppins">
                <i class="fas fa-lock mr-1"></i>
                Password Anda
            </label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors font-poppins"
                   placeholder="Masukkan password Anda">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-gradient-to-r from-red-500 to-pink-600 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 font-poppins">
            <i class="fas fa-check-circle mr-2"></i>
            Konfirmasi
        </button>
    </form>
</x-guest-layout>
