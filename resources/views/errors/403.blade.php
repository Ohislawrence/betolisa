<x-app-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Access Denied</h2>
            <p class="text-gray-600 mb-8">You don't have permission to access this page.</p>
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('bettor.dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Go to Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Login
                </a>
            @endauth
        </div>
    </div>
</x-app-layout>
