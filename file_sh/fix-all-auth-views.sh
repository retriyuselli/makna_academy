#!/bin/bash

echo "🎨 Fix All Auth Views - Use Guest Layout + Poppins Font"
echo "===================================================="

echo "📋 Step 1: Backup all auth views..."
cp resources/views/auth/forgot-password.blade.php resources/views/auth/forgot-password.blade.php.backup
cp resources/views/auth/reset-password.blade.php resources/views/auth/reset-password.blade.php.backup
cp resources/views/auth/confirm-password.blade.php resources/views/auth/confirm-password.blade.php.backup

echo ""
echo "🔧 Step 2: Fix forgot-password view..."

cat > resources/views/auth/forgot-password.blade.php << 'EOF'
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
EOF

echo "✅ forgot-password view updated"

echo ""
echo "🔧 Step 3: Fix confirm-password view..."

cat > resources/views/auth/confirm-password.blade.php << 'EOF'
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
EOF

echo "✅ confirm-password view updated"

echo ""
echo "✅ All auth views fixed!"
echo ""
echo "🔄 Test these URLs now:"
echo "- https://maknaacademy.com/verify-email"
echo "- https://maknaacademy.com/forgot-password"
echo "- https://maknaacademy.com/confirm-password"
echo ""
echo "💡 All auth views now use:"
echo "- ✅ x-guest-layout (consistent styling)"
echo "- ✅ Tailwind CDN (no @vite dependencies)"
echo "- ✅ Font Poppins (consistent typography)"
echo "- ✅ Font Awesome icons"
echo "- ✅ Indonesian language"
