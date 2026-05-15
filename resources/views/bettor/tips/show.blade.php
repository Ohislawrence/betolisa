<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tip Details') }}
            </h2>
            <a href="{{ $tip->type == 'premium' ? route('bettor.tips.premium') : route('bettor.tips.free') }}"
               class="text-blue-600 hover:text-blue-800 font-medium">
                ← Back to Tips
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Header Banner -->
                <div class="{{ $tip->type == 'premium' ? 'bg-yellow-500' : 'bg-green-500' }} px-6 py-4">
                    <div class="flex justify-between items-center">
                        <span class="text-white font-medium">{{ $tip->league->name }}</span>
                        <span class="px-3 py-1 bg-white text-gray-800 rounded-full text-sm font-bold">
                            {{ $tip->type == 'premium' ? '⭐ Premium' : 'Free' }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Match Details -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ $tip->home_team }} vs {{ $tip->away_team }}
                        </h1>
                        @if($tip->match_date)
                            <p class="text-gray-600">
                                📅 {{ $tip->match_date->format('l, d M Y - H:i') }}
                            </p>
                        @endif
                    </div>

                    <!-- Tip Content -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Prediction</h3>
                        <div class="prose max-w-none text-gray-700">
                            {{ $tip->tip_content }}
                        </div>
                    </div>

                    <!-- Meta Information Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <span class="block text-sm text-blue-600 font-medium">Odds</span>
                            <span class="block text-2xl font-bold text-blue-800">{{ $tip->odds ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <span class="block text-sm text-purple-600 font-medium">Status</span>
                            <span class="block text-lg font-bold
                                @if($tip->status == 'won') text-green-600
                                @elseif($tip->status == 'lost') text-red-600
                                @else text-yellow-600
                                @endif">
                                {{ ucfirst($tip->status) }}
                            </span>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <span class="block text-sm text-green-600 font-medium">Type</span>
                            <span class="block text-lg font-bold text-green-800">{{ ucfirst($tip->type) }}</span>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 text-center">
                            <span class="block text-sm text-orange-600 font-medium">Posted</span>
                            <span class="block text-lg font-bold text-orange-800">{{ $tip->created_at->format('d M') }}</span>
                        </div>
                    </div>

                    <!-- Posted By -->
                    <div class="text-sm text-gray-500 border-t pt-4">
                        Posted by {{ $tip->creator->name }} on {{ $tip->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- Related Tips -->
            @if($relatedTips->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">More Tips from {{ $tip->league->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($relatedTips as $relatedTip)
                            <a href="{{ route('bettor.tips.show', $relatedTip) }}"
                               class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition block">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">
                                        {{ $relatedTip->home_team }} vs {{ $relatedTip->away_team }}
                                    </h4>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $relatedTip->type == 'premium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($relatedTip->type) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $relatedTip->tip_content }}</p>
                                <div class="flex justify-between items-center mt-3 text-sm">
                                    @if($relatedTip->odds)
                                        <span class="text-blue-600">Odds: {{ $relatedTip->odds }}</span>
                                    @endif
                                    <span class="text-gray-500">{{ $relatedTip->created_at->format('d M Y') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
