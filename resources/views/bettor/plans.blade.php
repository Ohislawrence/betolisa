<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($activeSubscription)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">✓ You have an active subscription</h3>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-green-600">Started</span>
                            <p class="font-medium text-gray-800">{{ $activeSubscription->starts_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-green-600">Expires</span>
                            <p class="font-medium text-gray-800">{{ $activeSubscription->ends_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-green-600">Days Left</span>
                            <p class="font-medium text-gray-800">{{ $activeSubscription->daysRemaining() }} days</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Plan summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Premium Monthly</h3>
                        <p class="text-sm text-gray-500 mt-1">Full access for {{ $duration }} days</p>
                    </div>
                    <div class="text-right">
                        <span class="text-4xl font-bold text-gray-900">&#8358;{{ number_format($cost) }}</span>
                        <p class="text-xs text-gray-400">/{{ $duration }} days</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm mb-8">
                    @foreach(['Daily premium tips', 'High odds selections', 'Telegram group access', 'Early picks every day', 'Win probability analysis', '24/7 support'] as $feature)
                        <div class="flex items-center gap-2 text-gray-700">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('bettor.payment.options') }}"
                    class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all text-lg shadow hover:shadow-md">
                    {{ $activeSubscription ? 'Renew Subscription →' : 'Subscribe Now →' }}
                </a>
                <p class="text-xs text-gray-400 text-center mt-3">Choose your preferred payment method on the next page</p>
            </div>

        </div>
    </div>
</x-app-layout>
