<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="divide-y divide-gray-200">
                    @forelse(auth()->user()->notifications as $notification)
                        <div class="p-6 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        @if(!$notification->read_at)
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        @endif
                                        <span class="text-sm text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                            {{ str_replace('_', ' ', ucfirst($notification->data['type'] ?? 'info')) }}
                                        </span>
                                    </div>

                                    <p class="text-gray-900">{{ $notification->data['message'] }}</p>

                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}"
                                           class="inline-block mt-2 text-sm text-blue-600 hover:text-blue-800">
                                            View Details →
                                        </a>
                                    @endif
                                </div>

                                @if(!$notification->read_at)
                                    <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST" class="ml-4">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-400 hover:text-gray-600">
                                            ✕
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                            <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                        </div>
                    @endforelse
                </div>

                <div class="px-6 py-4 border-t">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
