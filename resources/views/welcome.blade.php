<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tipster - Premium Football Tips</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-black text-white">
    <div class="min-h-screen bg-black">
        <!-- Navigation -->
        <nav class="bg-slate-950/95 border-b border-white/10 backdrop-blur-md shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-white">⚽ Tipster</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-accent-300 font-medium">Dashboard</a>
                            @else
                                <a href="{{ route('bettor.dashboard') }}" class="text-white hover:text-accent-300 font-medium">Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-accent-300 font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-accent-500 text-black font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-accent-600 transition">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(212,175,55,0.18),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(255,255,255,0.08),_transparent_30%)] pointer-events-none"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative">
                <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-gray-300">
                            <span class="block h-2 w-2 rounded-full bg-accent-300 animate-pulse"></span>
                            Trusted by thousands of professional bettors
                        </div>
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-display font-bold tracking-tight text-white">
                            Professional football tips designed to win.
                        </h1>
                        <p class="max-w-2xl text-lg text-gray-300">
                            Access premium predictions, expert analysis, and an elite betting experience in a bold black and gold interface.
                        </p>
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            @guest
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-accent-500 text-black px-8 py-4 rounded-full text-lg font-semibold shadow-xl hover:bg-accent-600 transition">
                                    Start Winning Now
                                </a>
                                <a href="#features" class="inline-flex items-center justify-center border border-white/20 text-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition">
                                    How It Works
                                </a>
                            @else
                                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('bettor.dashboard') }}" class="inline-flex items-center justify-center bg-accent-500 text-black px-8 py-4 rounded-full text-lg font-semibold shadow-xl hover:bg-accent-600 transition">
                                    Go to Dashboard
                                </a>
                            @endauth
                        </div>
                    </div>
                    <div class="relative">
                        <div class="rounded-[2rem] border border-white/10 bg-slate-900/95 p-8 shadow-2xl backdrop-blur-lg">
                            <div class="flex items-center justify-between mb-6">
                                <div class="rounded-2xl bg-white/5 p-3 text-accent-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <span class="rounded-full bg-accent-500 px-3 py-1 text-xs font-semibold text-black">PREMIUM</span>
                            </div>
                            <div class="rounded-3xl bg-white/5 p-6 mb-6">
                                <p class="text-sm text-gray-400 mb-2">English Premier League</p>
                                <div class="grid grid-cols-3 gap-4 text-center mb-4">
                                    <div>
                                        <p class="text-xl font-bold text-white">Man City</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">VS</p>
                                        <p class="text-accent-300 font-bold">Odds: 2.45</p>
                                    </div>
                                    <div>
                                        <p class="text-xl font-bold text-white">Arsenal</p>
                                    </div>
                                </div>
                                <div class="rounded-2xl bg-white/10 p-4 text-center">
                                    <p class="text-white font-medium">Over 2.5 Goals & Both Teams to Score</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between text-sm text-gray-400">
                                    <span>Analysis</span>
                                    <span>Complete</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-white/10">
                                    <div class="h-2 rounded-full bg-accent-500" style="width: 92%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -top-8 -right-10 h-24 w-24 rounded-full bg-accent-500/30 blur-2xl"></div>
                        <div class="absolute -bottom-10 -left-10 h-20 w-20 rounded-full bg-white/10 blur-2xl"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="rounded-[2rem] border border-white/10 bg-slate-950/90 p-10 shadow-2xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">Ready to stop guessing?</h3>
                        <p class="mt-2 text-gray-300 max-w-2xl">Unlock premium football betting tips with a refined strategy and consistent market advantage.</p>
                    </div>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-accent-500 text-black px-8 py-4 rounded-full font-semibold shadow-xl hover:bg-accent-600 transition">
                        Join Premium
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
