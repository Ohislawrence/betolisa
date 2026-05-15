<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($activeSubscription)
                <!-- Active Subscription Info -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-medium text-green-800 mb-2">Active Subscription</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm text-green-600">Started</span>
                            <p class="font-medium">{{ $activeSubscription->starts_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-green-600">Expires</span>
                            <p class="font-medium">{{ $activeSubscription->ends_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-green-600">Days Remaining</span>
                            <p class="font-medium">{{ $activeSubscription->daysRemaining() }} days</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pricing Card -->
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2 border-blue-500">
                    <div class="bg-blue-500 text-white text-center py-4">
                        <h3 class="text-2xl font-bold">Premium Monthly</h3>
                        <p class="text-sm opacity-90">Access all premium tips</p>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <span class="text-4xl font-bold">₦{{ number_format($cost) }}</span>
                            <span class="text-gray-500">/{{ $duration }} days</span>
                        </div>

                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Daily premium tips
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                High odds selections
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Telegram group access
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                24/7 support
                            </li>
                        </ul>

                        <form action="{{ route('bettor.payment.initialize') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                                @if($activeSubscription)
                                    Renew Subscription
                                @else
                                    Subscribe Now
                                @endif
                            </button>
                        </form>

                        <p class="text-xs text-gray-500 text-center mt-4">
                            Secure payment powered by Paystack
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
