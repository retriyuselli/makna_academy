<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Verifikasi Email</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="antialiased">
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6">
            <!-- Header with Logo -->
            <div class="text-center mb-6">
                @php
                    $company = \App\Models\Company::first();
                @endphp
                @if (isset($company) && $company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name ?? 'Company Logo' }}"
                        class="mx-auto h-16 w-auto mb-3">
                @else
                    <div class="mb-3">
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-pink-500 bg-clip-text text-transparent">
                            Makna Academy
                        </h1>
                    </div>
                @endif

                <!-- Email Icon -->
                <div class="mx-auto w-16 h-16 bg-gradient-to-r from-indigo-500 to-pink-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>

                <h2 class="text-xl font-semibold text-gray-900">Verifikasi Email Anda</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan ke email Anda.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                Link verifikasi baru telah dikirim ke email Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Email Display -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Email Anda:</p>
                <p class="font-medium text-gray-900">{{ auth()->user()->email }}</p>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center py-3 px-4 text-sm font-semibold text-white rounded-lg
                               focus:outline-none focus:ring-4 focus:ring-indigo-300
                               transition duration-200 ease-in-out shadow-md"
                        style="background: linear-gradient(to right, #4f46e5, #ec4899); border: none;"
                        onmouseover="this.style.background='linear-gradient(to right, #4338ca, #db2777)'"
                        onmouseout="this.style.background='linear-gradient(to right, #4f46e5, #ec4899)'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-2 px-4 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200">
                        Keluar
                    </button>
                </form>
            </div>

            <!-- Tips -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Tips:</h3>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Periksa folder spam/junk email Anda</li>
                    <li>• Pastikan email {{ auth()->user()->email }} benar</li>
                    <li>• Link verifikasi berlaku selama 60 menit</li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>
