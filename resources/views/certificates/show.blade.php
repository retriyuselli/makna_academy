@extends('layouts.app')

@section('title', 'Detail Sertifikat - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('certificates.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Sertifikat
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Sertifikat</h1>
            </div>

            <!-- Certificate Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Certificate Actions -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('certificates.template.preview', ['certificate' => $certificate->id]) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-eye mr-2"></i>
                            Preview Sertifikat
                        </a>
                        <button 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed"
                           disabled
                           title="Feature coming soon">
                            <i class="fas fa-clock mr-2"></i>
                            Download Coming Soon
                        </button>
                    </div>
                </div>

                <!-- Certificate Preview -->
                <div class="aspect-w-16 aspect-h-12 bg-indigo-50 flex items-center justify-center p-8">
                    <div class="w-full max-w-2xl mx-auto">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-certificate text-8xl text-indigo-300"></i>
                        </div>
                        <div class="text-center mt-4 text-gray-500">
                            <p>Sertifikat Digital</p>
                            <p class="text-sm">Klik tombol Preview atau Download untuk melihat sertifikat</p>
                        </div>
                    </div>
                </div>

                <!-- Certificate Details -->
                <div class="p-8">
                    <div class="max-w-3xl mx-auto">
                        <!-- Event Info -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $certificate->event->title }}</h2>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt w-5 text-gray-400"></i>
                                    <span>{{ $certificate->event->start_date->format('d M Y') }} - {{ $certificate->event->end_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                                    <span>{{ $certificate->event->city }}, {{ $certificate->event->province }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate w-5 text-gray-400"></i>
                                    <span>{{ $certificate->event->organizer_name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Certificate Info -->
                        <div class="border-t border-gray-200 pt-8">
                            <dl class="divide-y divide-gray-200">
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Nomor Sertifikat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $certificate->certificate_number }}</dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Terbit</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $certificate->certificate_issued_at->format('d M Y') }}</dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Penerima</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $certificate->name }}</dd>
                                </div>
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $certificate->certificate_status === 'issued' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($certificate->certificate_status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Verification Info -->
                        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shield-check text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Verifikasi Sertifikat</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Sertifikat ini dapat diverifikasi melalui link berikut:</p>
                                        <input type="text" 
                                               value="{{ route('certificate.verify', ['number' => $certificate->certificate_number]) }}" 
                                               class="mt-1 block w-full bg-white border border-blue-300 rounded-md py-2 px-3 text-sm"
                                               readonly
                                               onclick="this.select(); document.execCommand('copy');">
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex space-x-2">
                                            <span class="inline-flex items-center text-xs text-blue-700">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Klik link di atas untuk menyalin
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
