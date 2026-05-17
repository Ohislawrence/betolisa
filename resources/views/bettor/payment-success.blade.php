<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Successful') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Success card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-1">Payment Successful!</h3>
                    <p class="text-gray-500">Your premium subscription is now active.</p>

                    @if($subscription)
                        <div class="mt-6 inline-flex flex-col items-center bg-amber-50 border border-amber-200 rounded-xl px-8 py-4 text-sm">
                            <p class="text-amber-700 font-medium mb-0.5">Valid until</p>
                            <p class="text-xl font-bold text-gray-900">{{ $subscription->ends_at->format('d M Y') }}</p>
                            @if(method_exists($subscription, 'daysRemaining'))
                                <p class="text-amber-600 text-xs mt-1">{{ $subscription->daysRemaining() }} days remaining</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Telegram join card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-6 h-6 text-sky-500" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">Join the Premium Telegram Group</h4>
                            <p class="text-sm text-gray-500 mb-4">
                                Click the button below to join our exclusive premium tips group on Telegram. You'll get daily high-odds picks, analysis, and expert selections.
                            </p>

                            @if($groupLink)
                                <a href="{{ $groupLink }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white font-bold px-6 py-3 rounded-xl transition-colors text-sm shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                    </svg>
                                    Join Premium Telegram Group →
                                </a>
                            @else
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                                    Our admin will send your Telegram invite link shortly. Make sure your Telegram username is saved in your <a href="{{ route('profile.edit') }}" class="underline font-medium">profile</a>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="flex flex-wrap gap-3 justify-center pb-4">
                <a href="{{ route('bettor.dashboard') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow">
                    Go to Dashboard →
                </a>
                <a href="{{ route('bettor.payment.history') }}"
                    class="inline-flex items-center gap-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-xl transition-colors">
                    View Payment History
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
