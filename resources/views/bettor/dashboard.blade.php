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
                            {{-- Upgrade CTA card --}}
                            <div class="rounded-2xl overflow-hidden border border-yellow-300 shadow-md">
                                <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold text-black">Upgrade to Premium</h4>
                                        <p class="text-sm text-black/70">Unlock the full experience — tips that win</p>
                                    </div>
                                    <span class="text-3xl">⭐</span>
                                </div>
                                <div class="bg-white px-6 py-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                                        @foreach([
                                            ['🎯', 'Daily premium football tips', 'Handpicked high-confidence selections every day'],
                                            ['📈', 'High-odds selections', 'Tips with the best value odds on the market'],
                                            ['💬', 'Exclusive Telegram group', 'Get tips directly on Telegram before anyone else'],
                                            ['🔔', 'Instant notifications', 'Never miss a tip with real-time alerts'],
                                            ['📊', 'Expert analysis', 'Full breakdown of every match recommendation'],
                                            ['🛡️', '24/7 support', 'Our team is always available to help you'],
                                        ] as [$icon, $title, $desc])
                                        <div class="flex items-start gap-3">
                                            <span class="text-xl leading-none mt-0.5">{{ $icon }}</span>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">{{ $title }}</p>
                                                <p class="text-xs text-gray-500">{{ $desc }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('bettor.plans') }}"
                                        class="flex items-center justify-center gap-2 w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl transition-all duration-200 hover:shadow-md text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                                        </svg>
                                        Upgrade to Premium Now
                                    </a>
                                    <p class="text-xs text-center text-gray-400 mt-2">Pay with card or bank transfer &bull; Instant activation</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Links -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('bettor.payment.history') }}" class="border p-4 rounded-lg hover:shadow-lg transition flex items-start gap-3">
                            <span class="text-2xl">🧾</span>
                            <div>
                                <h4 class="font-medium text-gray-900">Payment History</h4>
                                <p class="text-gray-500 text-sm">View your transactions</p>
                            </div>
                        </a>

                        @if($activeSubscription)
                            <a href="{{ route('bettor.tips.premium') }}" class="border border-yellow-300 p-4 rounded-lg hover:shadow-lg transition bg-yellow-50 flex items-start gap-3">
                                <span class="text-2xl">⭐</span>
                                <div>
                                    <h4 class="font-medium text-gray-900">Premium Tips</h4>
                                    <p class="text-gray-500 text-sm">View all your premium tips</p>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('bettor.plans') }}" class="border border-amber-300 p-4 rounded-lg hover:shadow-lg transition bg-amber-50 flex items-start gap-3">
                                <span class="text-2xl">🔒</span>
                                <div>
                                    <h4 class="font-medium text-gray-900">Premium Tips</h4>
                                    <p class="text-amber-600 text-sm font-medium">Subscribe to unlock →</p>
                                </div>
                            </a>
                        @endif

                        <a href="{{ route('bettor.tips.free') }}" class="border p-4 rounded-lg hover:shadow-lg transition flex items-start gap-3">
                            <span class="text-2xl">🆓</span>
                            <div>
                                <h4 class="font-medium text-gray-900">Free Tips</h4>
                                <p class="text-gray-500 text-sm">Browse all free tips</p>
                            </div>
                        </a>
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
                    @else
                        {{-- Locked premium tips teaser for non-subscribers --}}
                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Premium Tips
                                    <span class="text-sm text-amber-600 font-normal ml-1">(subscribers only)</span>
                                </h3>
                                <a href="{{ route('bettor.plans') }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium">Unlock Access →</a>
                            </div>

                            <div class="relative">
                                {{-- Blurred fake cards --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 select-none"
                                    style="filter:blur(4px);pointer-events:none;user-select:none">
                                    @foreach([
                                        ['Premier League', 'Man City vs Arsenal', 'Over 2.5 goals looks very likely given both sides\' recent...', '2.10'],
                                        ['La Liga', 'Barcelona vs Real Madrid', 'Both teams to score — high confidence pick based on...', '1.85'],
                                        ['Champions League', 'Bayern vs PSG', 'Home win backed by form data and head-to-head...', '2.40'],
                                    ] as [$league, $match, $preview, $odds])
                                    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-yellow-500">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs text-gray-400">{{ $league }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">⭐ Premium</span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">{{ $match }}</h4>
                                        <p class="text-sm text-gray-500">{{ $preview }}</p>
                                        <div class="flex justify-between items-center mt-3 text-xs text-gray-400">
                                            <span>Today</span>
                                            <span class="font-medium text-blue-500">Odds: {{ $odds }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Overlay CTA --}}
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/70 rounded-xl backdrop-blur-[1px]">
                                    <div class="text-center p-6">
                                        <div class="text-5xl mb-3">🔒</div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-1">Premium Tips are Locked</h4>
                                        <p class="text-sm text-gray-600 mb-5 max-w-xs mx-auto">
                                            Subscribe to unlock expert premium picks, high-odds selections, and Telegram group access.
                                        </p>
                                        <a href="{{ route('bettor.plans') }}"
                                            class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-3 rounded-xl transition-all shadow hover:shadow-md text-sm">
                                            Unlock Premium Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
