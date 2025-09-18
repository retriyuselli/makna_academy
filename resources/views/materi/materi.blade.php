@extends('layouts.app')

@section('title', 'Materi Belajar - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Materi Belajar</h1>
                        <p class="text-gray-600 mt-1">Akses materi pembelajaran dari event yang telah Anda selesaikan</p>
                    </div>
                </div>
            </div>

            @if($eventsWithMaterials->count() > 0)
                <!-- Learning Materials Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($eventsWithMaterials as $event)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <!-- Event Header -->
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                                <h3 class="text-xl font-semibold mb-2">{{ $event->title }}</h3>
                                <div class="flex items-center space-x-4 text-indigo-100">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $event->start_date ? $event->start_date->format('d M Y') : 'Tanggal belum ditentukan' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $event->location ?? 'Online' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Description -->
                            <div class="p-6">
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ Str::limit($event->description, 120) }}
                                </p>

                                <!-- Materials Section -->
                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Materi Tersedia ({{ $event->materials->count() }})
                                    </h4>

                                    <!-- Real Materials from Database -->
                                    <div class="space-y-2">
                                        @foreach($event->materials as $materi)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                                <div class="flex items-center space-x-3">
                                                    <svg class="w-6 h-6 text-{{ $materi->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $materi->icon }}"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $materi->title }}</p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ strtoupper($materi->type) }} • {{ $materi->formatted_file_size }} 
                                                            @if($materi->download_count > 0)
                                                                • {{ $materi->download_count }} unduhan
                                                            @endif
                                                        </p>
                                                        @if($materi->description)
                                                            <p class="text-xs text-gray-400 mt-1">{{ Str::limit($materi->description, 60) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <a href="{{ $materi->download_url }}" 
                                                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors duration-200 flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <span>Download</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Event Info Footer -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Status Event:</span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            Selesai
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Materi Tersedia</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Anda belum menyelesaikan event apapun. Selesaikan event terlebih dahulu untuk mengakses materi pembelajaran.
                    </p>
                    <a href="{{ route('events.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Event
                    </a>
                </div>
            @endif

            <!-- Info Banner -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-lg font-semibold text-blue-900 mb-2">Informasi Materi Belajar</h4>
                        <ul class="text-blue-800 space-y-1 text-sm">
                            <li>• Materi hanya tersedia untuk event yang telah Anda selesaikan dengan pembayaran lunas</li>
                            <li>• Setiap event dapat memiliki berbagai jenis materi (PDF, video, kode sumber, dll.)</li>
                            <li>• Materi diupload oleh admin setelah event selesai</li>
                            <li>• Anda dapat mengunduh materi kapan saja tanpa batas waktu</li>
                            <li>• Jenis file yang didukung: PDF, Video (MP4), Audio (MP3), Image (JPG/PNG), Archive (ZIP), Source Code</li>
                            <li>• Setiap unduhan akan tercatat untuk statistik penggunaan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
