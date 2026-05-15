<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bettor Management') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Export CSV
                </a>
                <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Bettor
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Bettors</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Active Accounts</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Subscribed</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['subscribed'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Unsubscribed</h4>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['unsubscribed'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-4 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" placeholder="Search by name, email, telegram..."
                            value="{{ request('search') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <select name="subscription_status" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">All Subscriptions</option>
                            <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('subscription_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <select name="is_active" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">All Status</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Bettors Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bettor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telegram</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscription</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bettors as $bettor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ strtoupper(substr($bettor->name, 0, 1)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $bettor->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $bettor->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $bettor->telegram_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bettor->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $bettor->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bettor->activeSubscription)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Active ({{ $bettor->activeSubscription->daysRemaining() }} days)
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $bettor->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.users.show', $bettor) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('admin.users.edit', $bettor) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.users.toggle-status', $bettor) }}" method="POST" class="inline mr-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-{{ $bettor->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $bettor->is_active ? 'orange' : 'green' }}-900">
                                                {{ $bettor->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        @if(!$bettor->hasActiveSubscription())
                                            <form action="{{ route('admin.users.destroy', $bettor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No bettors found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $bettors->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
