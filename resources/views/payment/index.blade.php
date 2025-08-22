@extends('layouts.app')

@section('title', 'Daftar Pembayaran - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-indigo-600">Event</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500">Pembayaran</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Daftar Pembayaran</h2>
                </div>
                <div class="p-6">
                    @if($registrations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Event
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Daftar
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($registrations as $registration)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $registration->event->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $registration->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($registration->payment_amount > 0)
                                                    Rp {{ number_format($registration->payment_amount, 0, ',', '.') }}
                                                @else
                                                    Gratis
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($registration->payment_status === 'paid')
                                                        bg-green-100 text-green-800
                                                    @elseif($registration->payment_status === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($registration->payment_status === 'failed')
                                                        bg-red-100 text-red-800
                                                    @elseif($registration->payment_status === 'free')
                                                        bg-blue-100 text-blue-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                                    @if($registration->payment_status === 'paid')
                                                        Lunas
                                                    @elseif($registration->payment_status === 'pending')
                                                        Menunggu Pembayaran
                                                    @elseif($registration->payment_status === 'failed')
                                                        Gagal
                                                    @elseif($registration->payment_status === 'free')
                                                        Gratis
                                                    @else
                                                        {{ ucfirst($registration->payment_status) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if(!$registration->event->is_free)
                                                    <a href="{{ route('payment.show', $registration->invoice_number) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">
                                                        Detail Pembayaran
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $registrations->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">Belum ada pembayaran</p>
                            <p class="text-sm text-gray-400 mt-2">Pembayaran Anda akan muncul di sini setelah Anda mendaftar event</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
