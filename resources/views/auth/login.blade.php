@extends('layouts.guest')

@section('title', 'Login - TipsterPro')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900/95 border border-white/10 rounded-3xl shadow-2xl p-8 backdrop-blur-sm">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-4xl text-white">
                <img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain mx-auto">
            </a>
            <h2 class="mt-4 text-3xl font-display font-bold text-white">Welcome Back!</h2>
            <p class="mt-2 text-gray-300">Login to access your account</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="mb-4 bg-emerald-900/80 border border-emerald-700 text-emerald-100 px-4 py-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-200">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white shadow-sm focus:border-accent-400 focus:ring-accent-400">
                @error('email')
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

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-white/10 bg-slate-900 text-accent-500 shadow-sm focus:ring-accent-500">
                    <span class="ml-2 text-sm text-gray-300">Remember me</span>
                </label>

                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-accent-300 hover:text-accent-200 font-medium">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full bg-accent-500 text-black py-3 rounded-xl font-bold hover:bg-accent-600 transition-all hover:shadow-lg">
                Sign In
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-accent-300 hover:text-accent-200 font-semibold">
                Create one now
            </a>
        </p>
    </div>
</div>
@endsection
