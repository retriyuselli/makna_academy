<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">Certificate Verification</h2>
                    
                    @if($registration)
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        This is a valid certificate issued by Makna Academy
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Certificate Details</h3>
                                <dl class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200">
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Certificate Number</dt>
                                        <dd class="text-gray-900">{{ $registration->certificate_number }}</dd>
                                    </div>
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Issue Date</dt>
                                        <dd class="text-gray-900">{{ $registration->certificate_issued_at->format('F d, Y') }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Recipient Details</h3>
                                <dl class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200">
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Name</dt>
                                        <dd class="text-gray-900">{{ $registration->user->name }}</dd>
                                    </div>
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Event</dt>
                                        <dd class="text-gray-900">{{ $registration->event->title }}</dd>
                                    </div>
                                    <div class="py-3 flex justify-between text-sm font-medium">
                                        <dt class="text-gray-500">Completion Date</dt>
                                        <dd class="text-gray-900">{{ $registration->completed_at->format('F d, Y') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Invalid or unknown certificate number.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
