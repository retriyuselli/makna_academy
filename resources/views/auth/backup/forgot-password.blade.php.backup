<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Reset Password</title>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6">
            <!-- Header with Logo -->
            <div class="text-center mb-4">
                <!-- Logo Area - dapat diganti dengan logo company -->
                @if(isset($company) && $company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name ?? 'Company Logo' }}" class="mx-auto h-16 w-auto mb-3">
                @else
                    <!-- Default Text Logo -->
                    <div class="mb-2">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent">
                            Makna Academy
                        </h1>
                    </div>
                @endif
                <h2 class="text-xl font-semibold text-gray-900">Reset Password</h2>
                <p class="mt-1 text-sm text-gray-600">Masukkan email Anda untuk reset password</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form class="space-y-4" method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}" 
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200" 
                           placeholder="Masukkan email Anda">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex items-center justify-center py-3 px-4 text-sm font-semibold text-white rounded-lg
               focus:outline-none focus:ring-4 focus:ring-indigo-300
               transition duration-200 ease-in-out shadow-md"
                        style="background: linear-gradient(to right, #4f46e5, #ec4899); 
                               border: none;"
                        onmouseover="this.style.background='linear-gradient(to right, #4338ca, #db2777)'"
                        onmouseout="this.style.background='linear-gradient(to right, #4f46e5, #ec4899)'">
                        Kirim Link Reset Password
                    </button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <div class="text-center pt-3 border-t border-gray-200 mt-3">
                <p class="text-sm text-gray-600">
                    Ingat password Anda? 
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                        Kembali ke login
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
