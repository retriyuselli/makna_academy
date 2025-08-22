@extends('layouts.admin')

@section('title', 'Log Aktivitas Sertifikat')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        Log Aktivitas Sertifikat
    </h2>

    <!-- Filter Section -->
    <div class="mb-8 p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.certificates.activity-log') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Event</label>
                    <select name="event" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Aktivitas</label>
                    <select name="activity_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Aktivitas</option>
                        <option value="issue" {{ request('activity_type') == 'issue' ? 'selected' : '' }}>Penerbitan</option>
                        <option value="revoke" {{ request('activity_type') == 'revoke' ? 'selected' : '' }}>Pencabutan</option>
                        <option value="download" {{ request('activity_type') == 'download' ? 'selected' : '' }}>Pengunduhan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Activity Log Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Waktu
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Event
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Peserta
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aktivitas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Admin
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Detail
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($activities as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $activity->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $activity->registration->event->title }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->registration->event->start_date->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $activity->registration->name }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->registration->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $activity->activity_type === 'issue' ? 'bg-green-100 text-green-800' : 
                                   ($activity->activity_type === 'revoke' ? 'bg-red-100 text-red-800' : 
                                    'bg-blue-100 text-blue-800') }}">
                                {{ $activity->getActivityTypeLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $activity->causer->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button onclick="showActivityDetails('{{ $activity->id }}')"
                                class="text-indigo-600 hover:text-indigo-900">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Tidak ada aktivitas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>

<!-- Activity Details Modal -->
<div id="activityDetailsModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Detail Aktivitas
                        </h3>
                        <div id="activityDetailsContent" class="space-y-4">
                            <!-- Content will be loaded dynamically -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeActivityDetailsModal()"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showActivityDetails(activityId) {
    fetch(`/admin/certificates/activity/${activityId}`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('activityDetailsContent');
            content.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Aktivitas</label>
                        <p class="mt-1 text-sm text-gray-900">${data.created_at}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Aktivitas</label>
                        <p class="mt-1 text-sm text-gray-900">${data.activity_type_label}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Sertifikat</label>
                        <p class="mt-1 text-sm text-gray-900">${data.certificate_number}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <p class="mt-1 text-sm text-gray-900">${data.description}</p>
                    </div>
                    ${data.changes ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Perubahan</label>
                            <pre class="mt-1 text-sm text-gray-900 bg-gray-50 p-2 rounded">${data.changes}</pre>
                        </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('activityDetailsModal').classList.remove('hidden');
        });
}

function closeActivityDetailsModal() {
    document.getElementById('activityDetailsModal').classList.add('hidden');
}
</script>
@endsection
