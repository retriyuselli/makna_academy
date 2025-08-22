@extends('layouts.app')

@section('title', 'Tentang Kami - Makna Academy')

@section('content')
    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Tentang <span class="text-yellow-300">Makna Academy</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200">
                    Membangun masa depan melalui pendidikan dan pelatihan berkualitas
                </p>
            </div>
        </div>
    </section>

    <!-- Company Information Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Company Description -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Siapa Kami?</h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        {!! str($company->description)->sanitizeHtml() !!}
                    </p>
                    <!-- Company Stats -->
                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <h3 class="text-2xl font-bold text-indigo-600">
                                {{ now()->year - (isset($company->established_date) ? \Carbon\Carbon::parse($company->established_date)->year : 2020) }}+
                            </h3>
                            <p class="text-gray-600">Tahun Pengalaman</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <h3 class="text-2xl font-bold text-green-600">{{ number_format($totalEvents) }}+</h3>
                            <p class="text-gray-600">Event Terselenggara</p>
                        </div>
                    </div>
                </div>

                <!-- Company Image/Logo -->
                <div class="text-center">
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-8 text-white">
                        <i class="fas fa-graduation-cap text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold mb-2">{{ $company->name ?? 'Makna Academy' }}</h3>
                        <p class="text-indigo-100">Platform Event Edukatif Terpercaya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Misi & Visi Kami</h2>
                <p class="text-lg text-gray-600">Komitmen kami untuk memajukan pendidikan di Indonesia</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover-scale">
                    <div class="text-center mb-6">
                        <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bullseye text-2xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Misi</h3>
                    </div>
                    <p class="text-gray-600 text-center leading-relaxed">
                        Menyediakan platform yang mudah diakses untuk menghubungkan peserta dengan event-event edukatif
                        berkualitas tinggi, serta memfasilitasi pertumbuhan keterampilan dan pengetahuan melalui
                        pembelajaran yang interaktif dan relevan.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover-scale">
                    <div class="text-center mb-6">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-eye text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Visi</h3>
                    </div>
                    <p class="text-gray-600 text-center leading-relaxed">
                        Menjadi platform event edukatif terdepan di Indonesia yang menginspirasi dan memberdayakan individu
                        untuk mencapai potensi terbaik mereka melalui pembelajaran berkelanjutan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nilai-Nilai Kami</h2>
                <p class="text-lg text-gray-600">Prinsip yang memandu setiap langkah kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Quality -->
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kualitas</h3>
                    <p class="text-gray-600">Kami berkomitmen menyediakan event dan pelatihan dengan standar kualitas
                        tertinggi.</p>
                </div>

                <!-- Innovation -->
                <div class="text-center">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lightbulb text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Inovasi</h3>
                    <p class="text-gray-600">Selalu menghadirkan metode pembelajaran terbaru dan teknologi terdepan.</p>
                </div>

                <!-- Accessibility -->
                <div class="text-center">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Aksesibilitas</h3>
                    <p class="text-gray-600">Memastikan pendidikan berkualitas dapat diakses oleh semua kalangan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600">Kami siap membantu perjalanan pembelajaran Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Email -->
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover-scale">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600">{{ $company->email ?? 'info@maknaacademy.com' }}</p>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover-scale">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Telepon</h3>
                    <p class="text-gray-600">{{ $company->phone ?? '+62 21 1234 5678' }}</p>
                </div>

                <!-- Address -->
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover-scale">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Alamat</h3>
                    <p class="text-gray-600">{{ $company->address ?? 'Jakarta, Indonesia' }}</p>
                </div>

                <!-- Website -->
                <div class="bg-white rounded-xl p-6 text-center shadow-lg hover-scale">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Website</h3>
                    <p class="text-gray-600">{{ $company->website ?? 'maknaacademy.com' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Section -->
    @if (isset($company->social_media) && is_array($company->social_media))
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Ikuti Kami</h2>
                <div class="flex justify-center space-x-6">
                    @if (isset($company->social_media['facebook']))
                        <a href="https://facebook.com/{{ $company->social_media['facebook'] }}" target="_blank"
                            class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-blue-700 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif

                    @if (isset($company->social_media['instagram']))
                        <a href="https://instagram.com/{{ $company->social_media['instagram'] }}" target="_blank"
                            class="bg-pink-600 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-pink-700 transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif

                    @if (isset($company->social_media['twitter']))
                        <a href="https://twitter.com/{{ $company->social_media['twitter'] }}" target="_blank"
                            class="bg-blue-400 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-blue-500 transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif

                    @if (isset($company->social_media['linkedin']))
                        <a href="https://linkedin.com/company/{{ $company->social_media['linkedin'] }}" target="_blank"
                            class="bg-blue-800 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-blue-900 transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Siap Memulai Perjalanan Pembelajaran Anda?</h2>
            <p class="text-xl mb-8 text-gray-200">Bergabunglah dengan ribuan peserta lainnya dan temukan event yang tepat
                untuk Anda</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}"
                    class="bg-yellow-400 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition duration-300">
                    Jelajahi Event
                </a>
                {{-- <a href="{{ route('contact') }}"
                    class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition duration-300">
                    Hubungi Kami
                </a> --}}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
