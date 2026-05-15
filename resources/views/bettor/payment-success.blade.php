<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>

                        <h3 class="text-2xl font-bold text-gray-900 mt-4">Payment Successful!</h3>
                        <p class="text-gray-600 mt-2">Your premium subscription has been activated.</p>

                        @if($subscription)
                            <div class="mt-6 bg-gray-50 rounded-lg p-4 max-w-sm mx-auto">
                                <div class="text-sm text-gray-600">
                                    <p>Subscription valid until:</p>
                                    <p class="font-bold text-lg text-gray-900">
                                        {{ $subscription->ends_at->format('d M Y, H:i') }}
                                    </p>
                                    <p class="mt-2">
                                        Days remaining:
                                        <span class="font-bold">{{ $subscription->daysRemaining() }}</span>
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-8 space-x-4">
                            <a href="{{ route('bettor.dashboard') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Go to Dashboard
                            </a>
                            <a href="{{ route('bettor.payment.history') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                View History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
