@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen bg-black flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-slate-900/95 border border-white/10 rounded-3xl shadow-2xl p-8 backdrop-blur-sm">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain mx-auto">
            </a>
            <div class="mt-4 w-16 h-16 bg-accent-400/10 border border-accent-400/30 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="mt-4 text-3xl font-display font-bold text-white">Check Your Email</h2>
            <p class="mt-2 text-gray-400 text-sm">We sent a verification link to your email address. Click it to activate your account.</p>
        </div>

        @if(session('status') == 'verification-link-sent')
            <div class="mb-6 bg-emerald-900/80 border border-emerald-700 text-emerald-100 px-4 py-3 rounded-xl text-sm">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <p class="text-gray-400 text-sm text-center mb-6">
            Didn't receive the email? Check your spam folder, or click below to resend it.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-accent-500 text-black py-3 rounded-xl font-bold hover:bg-accent-400 transition-all hover:shadow-lg mb-4">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-sm text-gray-500 hover:text-gray-300 transition-colors py-2">
                Log out and use a different account
            </button>
        </form>
    </div>
</div>
@endsection
