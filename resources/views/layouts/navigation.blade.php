<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain">
                        <span class="text-lg font-display font-bold text-gray-900">{{ config('app.name', 'TipsterPro') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <!-- Admin Navigation -->
                @if(auth()->user()?->hasRole('admin'))
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.leagues.index')" :active="request()->routeIs('admin.leagues.*')">
                            {{ __('Leagues') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.tips.index')" :active="request()->routeIs('admin.tips.*')">
                            {{ __('Tips') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Bettors') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.settings.subscription')" :active="request()->routeIs('admin.settings.subscription')">
                            {{ __('Subscription Settings') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.settings.telegram')" :active="request()->routeIs('admin.settings.telegram')">
                            {{ __('Telegram') }}
                        </x-nav-link>
                    </div>
                @endif

                <!-- Bettor Navigation -->
                @if(auth()->user()?->hasRole('bettor'))
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('bettor.dashboard')" :active="request()->routeIs('bettor.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('bettor.tips.free')" :active="request()->routeIs('bettor.tips.free')">
                            {{ __('Free Tips') }}
                        </x-nav-link>
                        @if(auth()->user()?->hasActiveSubscription())
                            <x-nav-link :href="route('bettor.tips.premium')" :active="request()->routeIs('bettor.tips.premium')">
                                {{ __('Premium Tips') }}
                            </x-nav-link>
                        @endif
                        <x-nav-link :href="route('bettor.payment.history')" :active="request()->routeIs('bettor.payment.*')">
                            {{ __('Payments') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>



            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Notification Bell -->
                @if(auth()->user()?->hasRole('bettor') || auth()->user()?->hasRole('admin'))
                    @php
                        $isAdmin = auth()->user()->hasRole('admin');
                        $notifIndexRoute = $isAdmin ? route('admin.notifications.index') : route('bettor.notifications.index');
                        $notifMarkAllRoute = $isAdmin ? route('admin.notifications.mark-all-read') : route('bettor.notifications.mark-all-read');
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-96 overflow-y-auto">
                            <div class="p-4 border-b">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-medium">Notifications</h3>
                                    @if($unreadCount > 0)
                                        <form action="{{ $notifMarkAllRoute }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-500">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="divide-y">
                                @php
                                    $notifications = auth()->user()->notifications()->take(5)->get();
                                @endphp

                                @forelse($notifications as $notification)
                                    <div class="p-4 hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-indigo-50' }}">
                                        <p class="text-sm text-gray-800">{{ $notification->data['message'] }}</p>
                                        <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm text-gray-500">
                                        No notifications yet
                                    </div>
                                @endforelse
                            </div>

                            <div class="p-3 border-t text-center">
                                <a href="{{ $notifIndexRoute }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                                    View All Notifications
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Responsive Navigation for Bettor -->
        @if(auth()->user()?->hasRole('bettor'))
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('bettor.dashboard')" :active="request()->routeIs('bettor.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bettor.tips.free')" :active="request()->routeIs('bettor.tips.free')">
                    {{ __('Free Tips') }}
                </x-responsive-nav-link>
                @if(auth()->user()?->hasActiveSubscription())
                    <x-responsive-nav-link :href="route('bettor.tips.premium')" :active="request()->routeIs('bettor.tips.premium')">
                        {{ __('Premium Tips') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('bettor.payment.history')" :active="request()->routeIs('bettor.payment.*')">
                    {{ __('Payments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bettor.profile.edit')" :active="request()->routeIs('bettor.profile.*')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
            </div>
        @endif

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
