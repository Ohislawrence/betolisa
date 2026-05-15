<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subscription Management') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.subscriptions.revenue') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Revenue Report
                </a>
                <a href="{{ route('admin.subscriptions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Manual Subscribe
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Active Subscriptions</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total_active'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Revenue</h4>
                    <p class="text-2xl font-bold text-blue-600">₦{{ number_format($stats['total_revenue']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">This Month Revenue</h4>
                    <p class="text-2xl font-bold text-purple-600">₦{{ number_format($stats['revenue_this_month']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Expired</h4>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['total_expired'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="flex gap-4 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" placeholder="Search by bettor name or email..."
                            value="{{ request('search') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <select name="status" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Subscriptions Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bettor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subscriptions as $subscription)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $subscription->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $subscription->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $subscription->starts_at->format('d M Y') }} - {{ $subscription->ends_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₦{{ number_format($subscription->amount_paid, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($subscription->status == 'active') bg-green-100 text-green-800
                                            @elseif($subscription->status == 'expired') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                        @if($subscription->status == 'active')
                                            <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this subscription?')">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No subscriptions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
