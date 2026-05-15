<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bettor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                    @php
                        $activeSubscription = auth()->user()->activeSubscription;
                    @endphp

                    <!-- Subscription Status -->
                    <div class="mb-8">
                        @if($activeSubscription)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-medium text-green-800">Active Premium Subscription</h4>
                                        <p class="text-sm text-green-600 mt-1">
                                            Expires: {{ $activeSubscription->ends_at->format('d M Y') }}
                                            ({{ $activeSubscription->daysRemaining() }} days remaining)
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-sm font-medium">
                                        Active
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-100 p-6 rounded-lg">
                                <h4 class="font-medium">Subscription Status</h4>
                                <p class="text-xl font-bold text-red-600 mt-2">No Active Subscription</p>
                                <p class="text-sm text-gray-600 mt-1">Subscribe to access premium tips and Telegram group</p>
                                <a href="{{ route('bettor.plans') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                                    Subscribe Now
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Links -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('bettor.payment.history') }}" class="border p-4 rounded-lg hover:shadow-lg transition">
                            <h4 class="font-medium">Payment History</h4>
                            <p class="text-gray-600 text-sm">View your payment transactions</p>
                        </a>

                        @if($activeSubscription)
                            <div class="border p-4 rounded-lg bg-green-50">
                                <h4 class="font-medium">Premium Tips</h4>
                                <p class="text-gray-600 text-sm">Access premium tips (Coming Soon)</p>
                            </div>
                        @else
                            <div class="border p-4 rounded-lg bg-gray-50">
                                <h4 class="font-medium">Free Tips</h4>
                                <p class="text-gray-600 text-sm">View available free tips (Coming Soon)</p>
                            </div>
                        @endif

                        <div class="border p-4 rounded-lg">
                            <h4 class="font-medium">Profile Settings</h4>
                            <p class="text-gray-600 text-sm">Update your profile and telegram details</p>
                        </div>
                    </div>

                    <!-- Recent Free Tips -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Free Tips</h3>
                            <a href="{{ route('bettor.tips.free') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $recentTips = \App\Models\Tip::with('league')
                                    ->where('type', 'free')
                                    ->active()
                                    ->latest()
                                    ->take(6)
                                    ->get();
                            @endphp

                            @forelse($recentTips as $tip)
                                <a href="{{ route('bettor.tips.show', $tip) }}" class="block bg-white rounded-lg shadow hover:shadow-md transition p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-xs text-gray-500">{{ $tip->league->name }}</span>
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Free</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900 mb-2">{{ $tip->home_team }} vs {{ $tip->away_team }}</h4>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($tip->tip_content, 80) }}</p>
                                    <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                                        <span>{{ $tip->created_at->format('d M Y') }}</span>
                                        @if($tip->odds)
                                            <span class="font-medium text-blue-600">Odds: {{ $tip->odds }}</span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full text-center py-8 text-gray-500">
                                    No tips available yet. Check back soon!
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if(auth()->user()->hasActiveSubscription())
                        <!-- Recent Premium Tips (only visible to subscribers) -->
                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Premium Tips</h3>
                                <a href="{{ route('bettor.tips.premium') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View All →
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    $premiumTips = \App\Models\Tip::with('league')
                                        ->where('type', 'premium')
                                        ->active()
                                        ->latest()
                                        ->take(3)
                                        ->get();
                                @endphp

                                @forelse($premiumTips as $tip)
                                    <a href="{{ route('bettor.tips.show', $tip) }}"
                                    class="block bg-white rounded-lg shadow hover:shadow-md transition p-4 border-t-4 border-yellow-500">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs text-gray-500">{{ $tip->league->name }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">⭐ Premium</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">{{ $tip->home_team }} vs {{ $tip->away_team }}</h4>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($tip->tip_content, 80) }}</p>
                                        <div class="flex justify-between items-center mt-3 text-xs text-gray-500">
                                            <span>{{ $tip->created_at->format('d M Y') }}</span>
                                            @if($tip->odds)
                                                <span class="font-medium text-blue-600">Odds: {{ $tip->odds }}</span>
                                            @endif
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-span-full text-center py-8 text-gray-500">
                                        Premium tips coming soon!
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
