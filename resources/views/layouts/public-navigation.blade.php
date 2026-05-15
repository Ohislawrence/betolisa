<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
     :class="{ 'bg-black/95 backdrop-blur-md shadow-lg': scrolled, 'bg-transparent': !scrolled }"
     class="fixed w-full z-50 transition-all duration-300 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain">
                    <span class="text-2xl font-display font-bold text-white">
                        {{ config('app.name', 'TipsterPro') }}
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-white hover:text-accent-300 font-medium transition-colors {{ request()->routeIs('home') ? 'text-accent-300' : '' }}">
                    Home
                </a>
                <a href="#features" class="text-white hover:text-accent-300 font-medium transition-colors">
                    Features
                </a>
                <a href="#tips" class="text-white hover:text-accent-300 font-medium transition-colors">
                    Free Tips
                </a>
                <a href="#pricing" class="text-white hover:text-accent-300 font-medium transition-colors">
                    Pricing
                </a>

                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="bg-accent-500 text-black px-6 py-2.5 rounded-full font-medium hover:bg-accent-600 transition-all hover:shadow-lg">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('bettor.dashboard') }}" class="bg-accent-500 text-black px-6 py-2.5 rounded-full font-medium hover:bg-accent-600 transition-all hover:shadow-lg">
                            My Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-accent-300 font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-accent-500 text-black px-6 py-2.5 rounded-full font-medium hover:bg-accent-600 transition-all hover:shadow-lg hover:-translate-y-0.5 transform">
                        Get Started Free
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-white hover:text-accent-300 focus:outline-none">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden bg-slate-950 border-t border-white/10">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium">Home</a>
            <a href="#features" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium">Features</a>
            <a href="#tips" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium">Free Tips</a>
            <a href="#pricing" class="block px-4 py-3 text-white hover:bg-white/10 rounded-lg font-medium">Pricing</a>

            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="block w-full text-center bg-accent-500 text-black px-4 py-3 rounded-lg font-medium">Dashboard</a>
                @else
                    <a href="{{ route('bettor.dashboard') }}" class="block w-full text-center bg-accent-500 text-black px-4 py-3 rounded-lg font-medium">My Dashboard</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="block w-full text-center text-white bg-slate-900/90 px-4 py-3 rounded-lg font-medium hover:text-accent-300">Login</a>
                <a href="{{ route('register') }}" class="block w-full text-center bg-accent-500 text-black px-4 py-3 rounded-lg font-medium hover:bg-accent-600">Get Started Free</a>
            @endauth
        </div>
    </div>
</nav>
