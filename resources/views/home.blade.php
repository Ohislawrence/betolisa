@extends('layouts.guest')

@section('title', 'TipsterPro - Premium Football Predictions & Expert Tips')

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="relative min-h-screen flex items-center bg-black overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-accent-500/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: copy --}}
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-2 text-sm text-gray-300">
                    <span class="w-2 h-2 rounded-full bg-accent-400 animate-pulse"></span>
                    Trusted by 10,000+ bettors worldwide
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-display font-bold leading-tight text-white">
                    Win More
                    <span class="block text-accent-400">Football Bets</span>
                </h1>

                <p class="text-xl text-gray-400 max-w-lg leading-relaxed">
                    Expert predictions powered by data analysis. Daily tips, high-odds selections, and an exclusive community built for serious bettors.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-accent-400 hover:bg-accent-300 text-black font-bold px-8 py-4 rounded-full text-lg transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            Start Winning Now
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#how-it-works" class="inline-flex items-center justify-center border border-white/20 text-white hover:bg-white/10 font-semibold px-8 py-4 rounded-full text-lg transition-all duration-200">
                            How It Works
                        </a>
                    @else
                        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('bettor.dashboard') }}" class="inline-flex items-center justify-center gap-2 bg-accent-400 hover:bg-accent-300 text-black font-bold px-8 py-4 rounded-full text-lg transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                            Go to Dashboard
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth
                </div>

                <div class="grid grid-cols-3 divide-x divide-white/10 pt-4">
                    <div class="text-center pr-6">
                        <p class="text-3xl font-bold text-white">95%</p>
                        <p class="text-sm text-gray-400 mt-1">Win Rate</p>
                    </div>
                    <div class="text-center px-6">
                        <p class="text-3xl font-bold text-white">10K+</p>
                        <p class="text-sm text-gray-400 mt-1">Active Users</p>
                    </div>
                    <div class="text-center pl-6">
                        <p class="text-3xl font-bold text-white">&#x20a6;50M+</p>
                        <p class="text-sm text-gray-400 mt-1">Won by Members</p>
                    </div>
                </div>
            </div>

            {{-- Right: sample tip card --}}
            <div class="hidden lg:block">
                <div class="relative rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm p-8 shadow-2xl">

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent-400 flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Today's Best Tip</p>
                                <p class="text-gray-400 text-sm">92% Confidence</p>
                            </div>
                        </div>
                        <span class="bg-accent-400 text-black px-3 py-1 rounded-full text-xs font-bold tracking-wide">PREMIUM</span>
                    </div>

                    <div class="rounded-2xl bg-white/5 border border-white/10 p-6 mb-6">
                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-4">English Premier League</p>
                        <div class="grid grid-cols-3 items-center text-center mb-5">
                            <p class="text-xl font-bold text-white">Man City</p>
                            <div>
                                <p class="text-gray-500 text-xs mb-1">VS</p>
                                <p class="text-accent-400 font-bold text-sm">Odds 2.45</p>
                            </div>
                            <p class="text-xl font-bold text-white">Arsenal</p>
                        </div>
                        <div class="rounded-xl bg-black/40 border border-white/10 p-3 text-center">
                            <p class="text-white text-sm font-medium">Over 2.5 Goals &amp; Both Teams to Score</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Analysis confidence</span>
                            <span class="text-accent-400 font-semibold">92%</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-white/10">
                            <div class="h-1.5 rounded-full bg-accent-400" style="width: 92%"></div>
                        </div>
                    </div>

                    <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-accent-500/30 blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>

{{-- ===================== FEATURES ===================== --}}
<section id="features" class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-display font-bold text-white mb-4">Why Choose Our Platform</h2>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Expert analysis. Data-driven insights. Consistent results.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group rounded-3xl border border-white/10 bg-slate-900 p-8 hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-6 group-hover:bg-accent-400 group-hover:border-accent-400 transition-colors duration-300">
                    <svg class="w-7 h-7 text-accent-400 group-hover:text-black transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Expert Analysis</h3>
                <p class="text-gray-400 leading-relaxed">Professional tipsters analyze form, injuries, head-to-head records and advanced stats before every prediction.</p>
            </div>

            <div class="group rounded-3xl border border-white/10 bg-slate-900 p-8 hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-6 group-hover:bg-accent-400 group-hover:border-accent-400 transition-colors duration-300">
                    <svg class="w-7 h-7 text-accent-400 group-hover:text-black transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">High Win Rate</h3>
                <p class="text-gray-400 leading-relaxed">Consistent 85-95% win rate across all selections. Our track record speaks for itself.</p>
            </div>

            <div class="group rounded-3xl border border-white/10 bg-slate-900 p-8 hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-6 group-hover:bg-accent-400 group-hover:border-accent-400 transition-colors duration-300">
                    <svg class="w-7 h-7 text-accent-400 group-hover:text-black transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Exclusive Community</h3>
                <p class="text-gray-400 leading-relaxed">Private Telegram group with real-time updates, discussion and direct access to our tipsters.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===================== RECENT FREE TIPS ===================== --}}
<section id="tips" class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
            <div>
                <h2 class="text-4xl font-display font-bold text-white mb-2">Recent Free Tips</h2>
                <p class="text-gray-400">A sample of our latest predictions</p>
            </div>
            <a href="{{ route('tips.index') }}" class="inline-flex items-center gap-2 text-accent-400 hover:text-accent-300 font-semibold transition-colors">
                View All Tips
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $recentTips = \App\Models\Tip::with('league')
                    ->where('type', 'free')
                    ->active()
                    ->latest()
                    ->take(6)
                    ->get();
            @endphp

            @forelse($recentTips as $tip)
                <div class="rounded-3xl border border-white/10 bg-slate-900 p-6 hover:-translate-y-1 transition-all duration-200">
                    <div class="flex justify-between items-start mb-5">
                        <span class="text-xs font-medium text-gray-300 bg-white/5 border border-white/10 px-3 py-1 rounded-full">
                            {{ $tip->league->name }}
                        </span>
                        @php
                            $statusBadge = $tip->status === 'won'
                                ? 'text-emerald-400 bg-emerald-400/10'
                                : ($tip->status === 'lost' ? 'text-rose-400 bg-rose-400/10' : 'text-amber-400 bg-amber-400/10');
                            $statusDot = $tip->status === 'won'
                                ? 'bg-emerald-400'
                                : ($tip->status === 'lost' ? 'bg-rose-400' : 'bg-amber-400');
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusBadge }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                            {{ ucfirst($tip->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-3 items-center text-center mb-5">
                        <p class="font-bold text-white text-sm">{{ $tip->home_team }}</p>
                        <p class="text-gray-500 text-xs font-medium">VS</p>
                        <p class="font-bold text-white text-sm">{{ $tip->away_team }}</p>
                    </div>

                    <div class="rounded-xl bg-white/5 border border-white/10 p-3 mb-4">
                        <p class="text-gray-200 text-sm text-center">{{ $tip->tip_content }}</p>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500">
                        @if($tip->odds)
                            <span class="font-bold text-accent-400">Odds: {{ $tip->odds }}</span>
                        @else
                            <span></span>
                        @endif
                        <span>{{ $tip->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-3xl border border-white/10 bg-slate-900 p-14 text-center">
                    <p class="text-4xl mb-4">&#9917;</p>
                    <h3 class="text-xl font-bold text-white mb-2">Tips Coming Soon</h3>
                    <p class="text-gray-400">Register now to get notified when new tips drop.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===================== HOW IT WORKS ===================== --}}
<section id="how-it-works" class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-display font-bold text-white mb-4">How It Works</h2>
            <p class="text-xl text-gray-400">Up and running in three simple steps</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full border-2 border-accent-400/40 bg-accent-400/10 flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl font-bold text-accent-400">1</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Create Account</h3>
                <p class="text-gray-400 leading-relaxed">Register for free and set up your profile with your Telegram username.</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 rounded-full border-2 border-accent-400/40 bg-accent-400/10 flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl font-bold text-accent-400">2</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Subscribe</h3>
                <p class="text-gray-400 leading-relaxed">Choose a plan and pay securely with Paystack. Get instant access.</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 rounded-full border-2 border-accent-400/40 bg-accent-400/10 flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl font-bold text-accent-400">3</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Start Winning</h3>
                <p class="text-gray-400 leading-relaxed">Access premium tips, join the Telegram group, and place winning bets.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===================== PRICING ===================== --}}
<section id="pricing" class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-display font-bold text-white mb-4">Simple Pricing</h2>
            <p class="text-xl text-gray-400">One plan — unlimited access to premium tips</p>
        </div>

        @php
            $subscriptionCost = \App\Models\Setting::getValue('subscription_cost', 5000);
            $subscriptionDays = \App\Models\Setting::getValue('subscription_duration_days', 30);
        @endphp

        <div class="max-w-md mx-auto rounded-3xl border border-white/10 bg-slate-900 overflow-hidden shadow-2xl hover:-translate-y-1 transition-all duration-300">
            <div class="bg-accent-400 px-8 py-10 text-center">
                <p class="text-black font-bold text-sm uppercase tracking-widest mb-3">Premium Monthly</p>
                <div class="flex items-baseline justify-center gap-1">
                    <span class="text-5xl font-display font-bold text-black">&#x20a6;{{ number_format($subscriptionCost) }}</span>
                    <span class="text-black/70 text-base">/{{ $subscriptionDays }} days</span>
                </div>
            </div>

            <div class="px-8 py-8">
                <ul class="space-y-4 mb-8">
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-accent-400/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-gray-300">Daily Premium Tips</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-accent-400/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-gray-300">High Odds Selections (2.0+)</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-accent-400/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-gray-300">Exclusive Telegram Group</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-accent-400/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-gray-300">Real-time Match Updates</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-accent-400/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-gray-300">Expert Analysis &amp; Insights</span>
                    </li>
                </ul>

                @guest
                    <a href="{{ route('register') }}" class="block w-full text-center bg-accent-400 hover:bg-accent-300 text-black font-bold py-4 rounded-2xl text-lg transition-all duration-200">
                        Get Started Now
                    </a>
                @else
                    @if(auth()->user()->hasRole('bettor'))
                        <a href="{{ route('bettor.plans') }}" class="block w-full text-center bg-accent-400 hover:bg-accent-300 text-black font-bold py-4 rounded-2xl text-lg transition-all duration-200">
                            Subscribe Now
                        </a>
                    @endif
                @endguest

                <p class="text-center text-xs text-gray-500 mt-4">&#128274; Secure payment powered by Paystack</p>
            </div>
        </div>
    </div>
</section>

{{-- ===================== TESTIMONIALS ===================== --}}
<section class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-display font-bold text-white mb-4">What Our Members Say</h2>
            <p class="text-xl text-gray-400">Join thousands of satisfied bettors</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-3xl border border-white/10 bg-slate-900 p-8">
                <div class="flex gap-0.5 mb-5">
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-gray-300 leading-relaxed mb-6">"Best football tipster I've ever used. The Telegram group is amazing with real-time updates. Won &#x20a6;150,000 last month!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent-400/20 border border-accent-400/30 flex items-center justify-center">
                        <span class="text-accent-400 font-bold text-sm">D</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">David O.</p>
                        <p class="text-xs text-gray-500">Premium Member</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-900 p-8">
                <div class="flex gap-0.5 mb-5">
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-gray-300 leading-relaxed mb-6">"The accuracy is incredible. I've been following their tips for 3 months and my betting has completely transformed."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent-400/20 border border-accent-400/30 flex items-center justify-center">
                        <span class="text-accent-400 font-bold text-sm">C</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Chioma E.</p>
                        <p class="text-xs text-gray-500">Premium Member</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-900 p-8">
                <div class="flex gap-0.5 mb-5">
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-gray-300 leading-relaxed mb-6">"Professional tipsters who really know their stuff. The premium tips are worth every kobo. Highly recommended!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent-400/20 border border-accent-400/30 flex items-center justify-center">
                        <span class="text-accent-400 font-bold text-sm">A</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Ahmed K.</p>
                        <p class="text-xs text-gray-500">Premium Member</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== CTA BANNER ===================== --}}
<section class="py-24 bg-black">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl border border-accent-400/30 bg-accent-400/5 p-12 text-center overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(247,178,0,0.10),_transparent_70%)] pointer-events-none"></div>
            <h2 class="relative text-4xl md:text-5xl font-display font-bold text-white mb-4">Ready to Start Winning?</h2>
            <p class="relative text-xl text-gray-400 mb-10 max-w-xl mx-auto">Join thousands of bettors. Get premium tips, exclusive Telegram access, and transform your game today.</p>

            @guest
                <div class="relative flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-accent-400 hover:bg-accent-300 text-black font-bold px-10 py-4 rounded-full text-lg transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                        Get Started Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center border border-white/20 text-white hover:bg-white/10 font-semibold px-10 py-4 rounded-full text-lg transition-all duration-200">
                        Learn More
                    </a>
                </div>
            @else
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('bettor.plans') }}" class="relative inline-flex items-center justify-center gap-2 bg-accent-400 hover:bg-accent-300 text-black font-bold px-10 py-4 rounded-full text-lg transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5">
                    Go to Dashboard
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            @endguest
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endpush
@endsection
