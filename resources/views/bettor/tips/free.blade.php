<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Free Tips') }}
            </h2>
            @if(!auth()->user()->hasActiveSubscription())
                <a href="{{ route('bettor.plans') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-sm">
                    Upgrade to Premium
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('bettor.tips.free') }}" method="GET" class="flex gap-4 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" placeholder="Search tips..." value="{{ request('search') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <select name="league_id" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Leagues</option>
                            @foreach($leagues as $league)
                                <option value="{{ $league->id }}" {{ request('league_id') == $league->id ? 'selected' : '' }}>
                                    {{ $league->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>Won</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Tips Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tips as $tip)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                        <div class="p-6">
                            <!-- Match Info -->
                            <div class="mb-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-medium text-gray-500">{{ $tip->league->name }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Free
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $tip->home_team }} vs {{ $tip->away_team }}
                                </h3>
                                @if($tip->match_date)
                                    <p class="text-sm text-gray-500 mt-1">
                                        📅 {{ $tip->match_date->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Tip Content Preview -->
                            <p class="text-gray-700 mb-4 line-clamp-3">
                                {{ Str::limit($tip->tip_content, 120) }}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex justify-between items-center text-sm">
                                @if($tip->odds)
                                    <span class="font-medium text-blue-600">Odds: {{ $tip->odds }}</span>
                                @endif
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($tip->status == 'won') bg-green-100 text-green-800
                                    @elseif($tip->status == 'lost') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($tip->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t px-6 py-3 bg-gray-50">
                            <a href="{{ route('bettor.tips.show', $tip) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                View Details →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Free Tips Available</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new tips.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $tips->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
