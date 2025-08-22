@extends('layouts.app')

@section('title', 'Sertifikat Saya - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Sertifikat Saya</h1>
                <p class="mt-2 text-gray-600">Lihat dan unduh sertifikat dari event yang telah Anda selesaikan</p>
            </div>

            @if($certificates->count() > 0)
                <!-- Certificates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Pratinjau Sertifikat -->
                            <div class="aspect-w-16 aspect-h-12 bg-gray-100 flex items-center justify-center">
                                @if($certificate->certificate_path)
                                    <div class="w-full h-48 flex items-center justify-center bg-indigo-50">
                                        <i class="fas fa-certificate text-5xl text-indigo-300"></i>
                                    </div>
                                @else
                                    <div class="w-full h-48 flex items-center justify-center bg-gray-50">
                                        <i class="fas fa-certificate text-5xl text-gray-300"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Detail Sertifikat -->
                            <div class="p-6">
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $certificate->event->category->name }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    {{ $certificate->event->title }}
                                </h3>

                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                        <span>Diterbitkan: {{ $certificate->certificate_issued_at->isoFormat('D MMMM Y') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-fingerprint w-5 text-gray-400"></i>
                                        <span>Nomor: {{ $certificate->certificate_number }}</span>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('certificates.show', $certificate) }}" 
                                        class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                    @if($certificate->certificate_path)
                                        <a href="{{ route('certificates.preview', $certificate) }}" 
                                            target="_blank"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-300 shadow-sm text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-file-pdf mr-1"></i>
                                            Preview
                                        </a>
                                        <button 
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed"
                                            disabled
                                            title="Feature coming soon">
                                            <i class="fas fa-clock mr-1"></i>
                                            Coming Soon
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Halaman -->
                <div class="mt-8">
                    {{ $certificates->links() }}
                </div>
            @else
                <!-- Status Kosong -->
                <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-200">
                    <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Sertifikat</h3>
                    <p class="text-gray-500 mb-6">Selesaikan event untuk mendapatkan sertifikat</p>
                    <a href="{{ route('events.index') }}" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-search mr-2"></i>
                        Jelajahi Event
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
