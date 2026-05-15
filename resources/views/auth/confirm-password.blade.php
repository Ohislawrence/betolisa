@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900/95 border border-white/10 rounded-3xl shadow-2xl p-8 backdrop-blur-sm">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain mx-auto">
            </a>
            <h2 class="mt-4 text-3xl font-display font-bold text-white">Confirm Password</h2>
            <p class="mt-2 text-gray-400 text-sm">This is a secure area. Please confirm your password before continuing.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full rounded-xl border border-white/10 bg-slate-950 text-white px-4 py-3 shadow-sm focus:border-accent-400 focus:ring-accent-400 focus:outline-none">
                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-accent-500 text-black py-3 rounded-xl font-bold hover:bg-accent-400 transition-all hover:shadow-lg">
                Confirm &amp; Continue
            </button>
        </form>
    </div>
</div>
@endsection
