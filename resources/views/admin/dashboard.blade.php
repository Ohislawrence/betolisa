<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="font-medium text-sm text-blue-800">Total Bettors</h4>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ \App\Models\User::role('bettor')->count() }}
                            </p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="font-medium text-sm text-green-800">Active Leagues</h4>
                            <p class="text-2xl font-bold text-green-900">
                                {{ \App\Models\League::active()->count() }}
                            </p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <h4 class="font-medium text-sm text-yellow-800">Total Tips</h4>
                            <p class="text-2xl font-bold text-yellow-900">
                                {{ \App\Models\Tip::count() }}
                            </p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <h4 class="font-medium text-sm text-purple-800">Premium Tips</h4>
                            <p class="text-2xl font-bold text-purple-900">
                                {{ \App\Models\Tip::premium()->count() }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-indigo-100 p-4 rounded-lg">
                            <h4 class="font-medium text-sm text-indigo-800">New Bettors This Month</h4>
                            <p class="text-2xl font-bold text-indigo-900">
                                {{ \App\Models\User::role('bettor')->whereMonth('created_at', now()->month)->count() }}
                            </p>
                        </div>
                        <div class="bg-pink-100 p-4 rounded-lg">
                            <h4 class="font-medium text-sm text-pink-800">Active Subscriptions</h4>
                            <p class="text-2xl font-bold text-pink-900">
                                {{ \App\Models\Subscription::active()->count() }}
                            </p>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-3">Tips Management</h4>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.tips.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                    Post New Tip
                                </a>
                                <a href="{{ route('admin.tips.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                                    View All Tips
                                </a>
                                <a href="{{ route('admin.settings.telegram') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 text-sm">
                                    Telegram Settings
                                </a>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-3">League Management</h4>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.leagues.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                    Add New League
                                </a>
                                <a href="{{ route('admin.leagues.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                                    View All Leagues
                                </a>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4 mt-6">
                            <h4 class="font-medium mb-3">User Management</h4>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.users.index') }}" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 text-sm">
                                    Manage Bettors
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                                    Add New Bettor
                                </a>
                                <a href="{{ route('admin.subscriptions.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 text-sm">
                                    Subscriptions
                                </a>
                                <a href="{{ route('admin.subscriptions.revenue') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 text-sm">
                                    Revenue Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Tips -->
                    <div class="mt-8">
                        <h4 class="font-medium mb-3">Recent Tips</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Match</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach(\App\Models\Tip::with('league')->latest()->take(5)->get() as $tip)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">{{ $tip->home_team }} vs {{ $tip->away_team }}</td>
                                            <td class="px-4 py-2 text-sm">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $tip->type == 'premium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($tip->type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm">{{ ucfirst($tip->status) }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $tip->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
