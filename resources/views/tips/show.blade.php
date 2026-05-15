@extends('layouts.guest')

@section('title', $tip->home_team . ' vs ' . $tip->away_team . ' - Free Tip')

@section('content')
{{-- Header --}}
<section class="relative bg-slate-950 py-12 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-4 text-gray-400 mb-4">
            <a href="{{ route('tips.index') }}" class="hover:text-white transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Tips
            </a>
            <span>/</span>
            <span class="text-white">{{ $tip->league->name }}</span>
        </div>
    </div>
</section>

{{-- Tip Content --}}
<section class="py-12 bg-black min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Main Card --}}
        <div class="bg-slate-900 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">

            {{-- Status Bar --}}
            <div class="bg-slate-800 border-b border-white/10 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    @php
                        $dotColor = match($tip->status) {
                            'won'  => 'bg-emerald-400',
                            'lost' => 'bg-rose-400',
                            'void' => 'bg-gray-400',
                            default => 'bg-amber-400',
                        };
                        $statusColor = match($tip->status) {
                            'won'  => 'text-emerald-400',
                            'lost' => 'text-rose-400',
                            'void' => 'text-gray-400',
                            default => 'text-amber-400',
                        };
                    @endphp
                    <span class="w-3 h-3 rounded-full {{ $dotColor }} animate-pulse"></span>
                    <span class="font-medium {{ $statusColor }}">{{ ucfirst($tip->status) }}</span>
                </div>
                <span class="bg-white/10 text-gray-300 px-3 py-1 rounded-full text-sm font-medium">
                    Free Tip
                </span>
            </div>

            <div class="p-6 md:p-8">

                {{-- League & Date --}}
                <div class="flex justify-between items-center mb-8">
                    <div class="flex items-center space-x-3">
                        @if($tip->league->logo)
                            <img src="{{ $tip->league->logo }}" alt="{{ $tip->league->name }}" class="w-10 h-10 rounded-full">
                        @endif
                        <div>
                            <p class="text-sm text-gray-400">{{ $tip->league->name }}</p>
                            @if($tip->match_date)
                                <p class="text-sm text-gray-500">{{ $tip->match_date->format('l, d M Y - H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    @if($tip->odds)
                        <div class="bg-accent-400/20 border border-accent-400/30 text-accent-400 px-4 py-2 rounded-xl font-bold text-lg">
                            Odds: {{ $tip->odds }}
                        </div>
                    @endif
                </div>

                {{-- Match --}}
                <div class="text-center mb-8">
                    <div class="flex justify-center items-center gap-6 md:gap-8">
                        <div class="flex-1 text-center">
                            <h1 class="text-2xl md:text-4xl font-display font-bold text-white">{{ $tip->home_team }}</h1>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center">
                                <span class="text-2xl md:text-3xl font-black text-gray-600">VS</span>
                            </div>
                        </div>
                        <div class="flex-1 text-center">
                            <h1 class="text-2xl md:text-4xl font-display font-bold text-white">{{ $tip->away_team }}</h1>
                        </div>
                    </div>
                </div>

                {{-- Prediction Box --}}
                <div class="bg-accent-400/5 border border-accent-400/20 rounded-2xl p-6 md:p-8 mb-8">
                    <h3 class="text-lg font-bold text-accent-400 mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Prediction
                    </h3>
                    <p class="text-xl md:text-2xl text-white font-bold leading-relaxed">
                        {{ $tip->tip_content }}
                    </p>
                </div>

                {{-- Meta Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-400 mb-1">Status</p>
                        <p class="font-bold {{ $statusColor }}">{{ ucfirst($tip->status) }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-400 mb-1">Type</p>
                        <p class="font-bold text-accent-400">Free</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-400 mb-1">Odds</p>
                        <p class="font-bold text-accent-400">{{ $tip->odds ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-400 mb-1">Posted</p>
                        <p class="font-bold text-white">{{ $tip->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Author / Back --}}
                <div class="border-t border-white/10 pt-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-accent-400/20 rounded-full flex items-center justify-center">
                            <span class="text-accent-400 font-bold">{{ strtoupper(substr($tip->creator->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $tip->creator->name }}</p>
                            <p class="text-xs text-gray-500">Expert Tipster</p>
                        </div>
                    </div>
                    <a href="{{ route('tips.index') }}" class="text-accent-400 hover:text-accent-300 font-medium text-sm flex items-center gap-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to All Tips
                    </a>
                </div>
            </div>
        </div>

        {{-- Related Tips --}}
        @if($relatedTips->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-display font-bold text-white mb-6">
                    More Tips from {{ $tip->league->name }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedTips as $relatedTip)
                        <a href="{{ route('tips.show', $relatedTip) }}"
                           class="block bg-slate-900 border border-white/10 rounded-2xl hover:border-accent-400/30 hover:-translate-y-1 transition-all p-6">
                            <div class="flex justify-between items-start mb-3">
                                <span class="text-sm text-gray-400">{{ $relatedTip->league->name }}</span>
                                @php
                                    $relClass = match($relatedTip->status) {
                                        'won'  => 'text-emerald-400',
                                        'lost' => 'text-rose-400',
                                        default => 'text-amber-400',
                                    };
                                    $relDot = match($relatedTip->status) {
                                        'won'  => 'bg-emerald-400',
                                        'lost' => 'bg-rose-400',
                                        default => 'bg-amber-400',
                                    };
                                @endphp
                                <span class="flex items-center gap-1.5 text-sm {{ $relClass }}">
                                    <span class="w-2 h-2 rounded-full {{ $relDot }}"></span>
                                    {{ ucfirst($relatedTip->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-bold text-white">{{ $relatedTip->home_team }}</span>
                                <span class="text-gray-600 text-sm">vs</span>
                                <span class="font-bold text-white">{{ $relatedTip->away_team }}</span>
                            </div>
                            <p class="text-sm text-gray-400 line-clamp-2">{{ $relatedTip->tip_content }}</p>
                            @if($relatedTip->odds)
                                <p class="text-sm font-bold text-accent-400 mt-2">Odds: {{ $relatedTip->odds }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Premium CTA for Guests --}}
        @guest
            <div class="mt-12 border border-accent-400/30 bg-accent-400/5 rounded-3xl p-8 text-center">
                <span class="text-4xl block mb-4">&#11088;</span>
                <h3 class="text-2xl font-bold text-white mb-3">Want More Winning Tips?</h3>
                <p class="text-gray-400 mb-6">Join our premium membership for exclusive high-odds predictions and Telegram group access</p>
                <a href="{{ route('register') }}" class="inline-block bg-accent-400 hover:bg-accent-300 text-black px-8 py-3 rounded-xl font-bold transition-all hover:shadow-xl">
                    Get Started &mdash; &#x20a6;{{ number_format(\App\Models\Setting::getValue('subscription_cost', 5000)) }}/mo
                </a>
            </div>
        @endguest
    </div>
</section>
@endsection
