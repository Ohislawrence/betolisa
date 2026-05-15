@extends('layouts.guest')

@section('title', 'Free Football Tips & Predictions')

@section('content')
{{-- Hero --}}
<section class="relative bg-black py-20 border-b border-white/10">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-4">Free Football Tips</h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-8">
                Expert predictions and analysis across top leagues worldwide
            </p>
            <div class="inline-flex flex-wrap justify-center gap-8 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-8 py-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-white">{{ $stats['total_tips'] }}</p>
                    <p class="text-sm text-gray-400">Total Tips</p>
                </div>
                <div class="text-center border-x border-white/20 px-8">
                    <p class="text-3xl font-bold text-emerald-400">{{ $stats['won_tips'] }}</p>
                    <p class="text-sm text-gray-400">Won Tips</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-accent-400">{{ $stats['today_tips'] }}</p>
                    <p class="text-sm text-gray-400">Today's Tips</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Filters & Content --}}
<section class="py-12 bg-slate-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Premium Banner --}}
        @guest
            <div class="mb-8 bg-accent-400/10 border border-accent-400/30 rounded-2xl p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-4xl">&#11088;</span>
                        <div>
                            <h3 class="text-xl font-bold text-white">Want Premium Tips?</h3>
                            <p class="text-gray-400">Exclusive high-odds predictions, Telegram group, and more!</p>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="shrink-0 bg-accent-400 hover:bg-accent-300 text-black px-6 py-3 rounded-xl font-bold transition-all hover:-translate-y-0.5 transform">
                        Join Now &mdash; &#x20a6;{{ number_format(\App\Models\Setting::getValue('subscription_cost', 5000)) }}/mo
                    </a>
                </div>
            </div>
        @endguest

        {{-- Search & Filter Bar --}}
        <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 mb-8">
            <form action="{{ route('tips.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="lg:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-300 mb-1">Search</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Search teams, leagues..."
                                class="pl-10 w-full rounded-xl bg-black/40 border-white/10 text-white placeholder-gray-600 focus:border-accent-400 focus:ring-accent-400">
                        </div>
                    </div>

                    <div>
                        <label for="league_id" class="block text-sm font-medium text-gray-300 mb-1">League</label>
                        <select name="league_id" id="league_id"
                            class="w-full rounded-xl bg-black/40 border-white/10 text-white focus:border-accent-400 focus:ring-accent-400">
                            <option value="">All Leagues</option>
                            @foreach($leagues as $league)
                                <option value="{{ $league->id }}" {{ request('league_id') == $league->id ? 'selected' : '' }}>
                                    {{ $league->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Result</label>
                        <select name="status" id="status"
                            class="w-full rounded-xl bg-black/40 border-white/10 text-white focus:border-accent-400 focus:ring-accent-400">
                            <option value="">All Results</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>Won</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                            <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>Void</option>
                        </select>
                    </div>

                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-300 mb-1">Sort By</label>
                        <select name="sort" id="sort"
                            class="w-full rounded-xl bg-black/40 border-white/10 text-white focus:border-accent-400 focus:ring-accent-400">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="odds_high" {{ request('sort') == 'odds_high' ? 'selected' : '' }}>Highest Odds</option>
                            <option value="odds_low" {{ request('sort') == 'odds_low' ? 'selected' : '' }}>Lowest Odds</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-300 mb-1">Date From</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-xl bg-black/40 border-white/10 text-white focus:border-accent-400 focus:ring-accent-400">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-300 mb-1">Date To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-xl bg-black/40 border-white/10 text-white focus:border-accent-400 focus:ring-accent-400">
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 justify-between items-center">
                    <div class="flex gap-3">
                        <button type="submit" class="bg-accent-400 hover:bg-accent-300 text-black px-6 py-2.5 rounded-xl font-semibold transition-all hover:shadow-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Apply Filters
                        </button>
                        @if(request()->anyFilled(['search', 'league_id', 'status', 'sort', 'date_from', 'date_to']))
                            <a href="{{ route('tips.index') }}" class="bg-white/10 text-gray-300 hover:bg-white/20 px-6 py-2.5 rounded-xl font-medium transition-all">
                                Clear Filters
                            </a>
                        @endif
                    </div>
                    <p class="text-sm text-gray-400">
                        Showing <span class="font-bold text-white">{{ $tips->count() }}</span> of <span class="font-bold text-white">{{ $tips->total() }}</span> tips
                    </p>
                </div>
            </form>
        </div>

        {{-- Tips Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tips as $tip)
                <article class="bg-slate-900 border border-white/10 rounded-2xl hover:border-accent-400/30 hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center space-x-2">
                                @if($tip->league->logo)
                                    <img src="{{ $tip->league->logo }}" alt="{{ $tip->league->name }}" class="w-6 h-6 rounded-full">
                                @endif
                                <span class="text-xs font-medium text-gray-300 bg-white/5 border border-white/10 px-3 py-1 rounded-full">
                                    {{ $tip->league->name }}
                                </span>
                            </div>
                            @php
                                $statusClass = match($tip->status) {
                                    'won'  => 'text-emerald-400 bg-emerald-400/10',
                                    'lost' => 'text-rose-400 bg-rose-400/10',
                                    'void' => 'text-gray-400 bg-white/10',
                                    default => 'text-amber-400 bg-amber-400/10',
                                };
                                $dotClass = match($tip->status) {
                                    'won'  => 'bg-emerald-400',
                                    'lost' => 'bg-rose-400',
                                    'void' => 'bg-gray-400',
                                    default => 'bg-amber-400',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                {{ ucfirst($tip->status) }}
                            </span>
                        </div>

                        <div class="text-center mb-4">
                            <div class="flex justify-between items-center gap-4">
                                <div class="flex-1">
                                    <p class="font-bold text-white text-sm md:text-base truncate">{{ $tip->home_team }}</p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-black text-gray-700">VS</span>
                                    @if($tip->match_date)
                                        <span class="text-xs text-gray-500 mt-1">{{ $tip->match_date->format('d M') }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-white text-sm md:text-base truncate">{{ $tip->away_team }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-4">
                            <p class="text-gray-300 font-medium text-sm leading-relaxed">
                                {{ Str::limit($tip->tip_content, 120) }}
                            </p>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                @if($tip->odds)
                                    <span class="text-accent-400 font-bold text-sm">Odds: {{ $tip->odds }}</span>
                                @endif
                                @if($tip->match_date)
                                    <span class="text-xs text-gray-500">{{ $tip->match_date->format('H:i') }}</span>
                                @endif
                            </div>
                            <a href="{{ route('tips.show', $tip) }}"
                               class="text-accent-400 hover:text-accent-300 font-medium text-sm inline-flex items-center gap-1 transition-colors">
                                Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    @guest
                        <div class="border-t border-white/10 bg-white/5 px-6 py-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500">
                                    <span class="text-accent-400">&#11088;</span> Premium tips available
                                </p>
                                <a href="{{ route('register') }}" class="text-xs font-semibold text-accent-400 hover:text-accent-300">
                                    Unlock &rarr;
                                </a>
                            </div>
                        </div>
                    @endguest
                </article>
            @empty
                <div class="col-span-full">
                    <div class="bg-slate-900 border border-white/10 rounded-2xl p-16 text-center">
                        <div class="text-7xl mb-6">&#128269;</div>
                        <h3 class="text-2xl font-bold text-white mb-2">No Tips Found</h3>
                        <p class="text-gray-400 mb-6 max-w-md mx-auto">
                            No tips match your current filters. Try adjusting your search criteria or check back later.
                        </p>
                        <a href="{{ route('tips.index') }}" class="inline-flex items-center gap-2 bg-accent-400 hover:bg-accent-300 text-black px-6 py-3 rounded-xl font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filters
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($tips->hasPages())
            <div class="mt-8">
                {{ $tips->links() }}
            </div>
        @endif

        {{-- CTA for Guests --}}
        @guest
            <div class="mt-16 rounded-3xl border border-accent-400/30 bg-accent-400/5 p-8 md:p-12 text-center relative overflow-hidden">
                <div class="relative">
                    <span class="text-5xl mb-4 block">&#11088;</span>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">Ready for Premium Tips?</h2>
                    <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                        Exclusive high-odds predictions, Telegram community, and expert daily analysis.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-accent-400 hover:bg-accent-300 text-black px-8 py-4 rounded-xl font-bold text-lg transition-all hover:shadow-xl hover:-translate-y-0.5 transform">
                            Get Started &mdash; &#x20a6;{{ number_format(\App\Models\Setting::getValue('subscription_cost', 5000)) }}/mo
                        </a>
                        <a href="{{ route('login') }}" class="border-2 border-white/20 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition-all">
                            I Already Have an Account
                        </a>
                    </div>
                    <div class="mt-6 flex flex-wrap justify-center gap-6 text-gray-400 text-sm">
                        <span>&#10003; Daily Premium Tips</span>
                        <span>&#10003; High Odds (2.0+)</span>
                        <span>&#10003; Telegram Group</span>
                        <span>&#10003; Expert Analysis</span>
                    </div>
                </div>
            </div>
        @endguest
    </div>
</section>
@endsection
