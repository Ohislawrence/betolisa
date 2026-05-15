@extends('layouts.guest')

@section('title', 'Register - TipsterPro')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900/95 border border-white/10 rounded-3xl shadow-2xl p-8 backdrop-blur-sm">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-4xl text-white">
                <img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain mx-auto">
            </a>
            <h2 class="mt-4 text-3xl font-display font-bold text-white">Join Us!</h2>
            <p class="mt-2 text-gray-300">Create your free account and start winning</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-200">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white shadow-sm focus:border-accent-400 focus:ring-accent-400">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-200">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white shadow-sm focus:border-accent-400 focus:ring-accent-400">
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Telegram Number -->
            <div>
                <label for="telegram_number" class="block text-sm font-medium text-gray-200">Telegram Username/Number</label>
                <input id="telegram_number" type="text" name="telegram_number" value="{{ old('telegram_number') }}" required
                    placeholder="@username or phone number"
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white shadow-sm focus:border-accent-400 focus:ring-accent-400">
                <p class="mt-1 text-xs text-gray-400">You'll be added to our premium Telegram group upon subscription</p>
                @error('telegram_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white shadow-sm focus:border-accent-400 focus:ring-accent-400">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-200">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white shadow-sm focus:border-accent-400 focus:ring-accent-400">
            </div>

            <button type="submit" class="w-full bg-accent-500 text-black py-3 rounded-xl font-bold hover:bg-accent-600 transition-all hover:shadow-lg">
                Create Account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-accent-300 hover:text-accent-200 font-semibold">
                Sign in
            </a>
        </p>
    </div>
</div>
@endsection
