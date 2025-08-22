@extends('layouts.app')

@section('title', 'Admin Dashboard - Makna Academy')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <x-user-avatar :size="64" class="flex-shrink-0" />
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ Auth::user()->name }}!</h1>
                        <p class="text-gray-600">Kelola event dan registrasi di sini</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-2xl text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Total Event</h3>
                            <p class="text-3xl font-bold text-indigo-600"> {{ $totalEvents }} events</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-check text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Total Registrasi</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $totalRegistrations }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Registrasi Terkonfirmasi</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ $confirmedRegistrations }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Event Terbaru</h2>
                        </div>
                        <div class="p-6">
                            @if ($recentEvents->isEmpty())
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar-plus text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">Belum ada event yang dibuat</p>
                                    <a href="{{ route('admin.events.create') }}"
                                        class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                                        Buat Event Baru
                                    </a>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nama Event
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Aksi</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($recentEvents as $event)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $event->title }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $event->formatted_date }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if ($event->isUpcoming)
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                Mendatang
                                                            </span>
                                                        @elseif ($event->isOngoing)
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Berlangsung
                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Selesai
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('admin.events.edit', $event->id) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                        <a href="{{ route('admin.events.show', $event->id) }}"
                                                            class="ml-2 text-gray-600 hover:text-gray-900">Lihat</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 text-right">
                                    <a href="{{ route('admin.events.index') }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Lihat Semua Event &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Registrasi Terbaru</h2>
                        </div>
                        <div class="p-6">
                            @if ($recentRegistrations->isEmpty())
                                <div class="text-center py-8">
                                    <i class="fas fa-users-slash text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">Belum ada registrasi baru</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Nama Peserta
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Event
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal Daftar
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($recentRegistrations as $registration)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $registration->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $registration->email }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $registration->event->title }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if ($registration->isConfirmed())
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Terkonfirmasi
                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $registration->created_at->format('d M Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 text-right">
                                    <a href="{{ route('admin.registrations.index') }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Lihat Semua Registrasi &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Profil Saya</h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center mb-4">
                                <div class="mx-auto mb-4 flex justify-center">
                                    <x-user-avatar :size="80" class="flex-shrink-0" />
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                                <p class="text-gray-600 text-xs">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">
                                Edit Profil
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Aksi Cepat</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.events.create') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-plus-circle text-lg"></i>
                                <span>Buat Event Baru</span>
                            </a>
                            <a href="{{ route('admin.events.index') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-calendar-alt text-lg"></i>
                                <span>Kelola Event</span>
                            </a>
                            <a href="{{ route('admin.registrations.index') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-users text-lg"></i>
                                <span>Kelola Registrasi</span>
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 transition duration-300">
                                <i class="fas fa-user-edit text-lg"></i>
                                <span>Edit Profil Admin</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection