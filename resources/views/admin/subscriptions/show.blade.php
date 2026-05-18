<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Subscription Details
            </h2>
            <a href="{{ route('admin.subscriptions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                &larr; Back to Subscriptions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Subscription Overview --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Subscription #{{ $subscription->id }}</h3>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                        @if($subscription->status === 'active') bg-green-100 text-green-800
                        @elseif($subscription->status === 'expired') bg-gray-100 text-gray-800
                        @elseif($subscription->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($subscription->status) }}
                    </span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Bettor</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $subscription->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $subscription->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Amount Paid</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">₦{{ number_format($subscription->amount_paid, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Start Date</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $subscription->starts_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">End Date</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $subscription->ends_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($subscription->isActive())
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Days Remaining</p>
                        <p class="mt-1 text-sm font-semibold text-green-700">{{ $subscription->daysRemaining() }} day(s)</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Payment Method</p>
                        <p class="mt-1 text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $subscription->payment_method ?? 'N/A')) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Transaction Reference</p>
                        <p class="mt-1 text-sm font-mono text-gray-700 break-all">{{ $subscription->transaction_ref ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Created At</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $subscription->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($subscription->creator)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Created By</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $subscription->creator->name }}</p>
                    </div>
                    @endif
                </div>

                @if($subscription->admin_notes)
                <div class="px-6 pb-6">
                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Admin Notes</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line bg-gray-50 rounded p-3">{{ $subscription->admin_notes }}</p>
                </div>
                @endif

                @if($subscription->payment_details)
                <div class="px-6 pb-6">
                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Payment Details</p>
                    <div class="bg-gray-50 rounded p-3 text-sm text-gray-700 space-y-1">
                        @foreach($subscription->payment_details as $key => $value)
                            @if($value)
                            <div><span class="font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span> {{ $value }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Transaction --}}
            @if($subscription->transaction)
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Transaction</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Reference</p>
                        <p class="mt-1 text-sm font-mono text-gray-700 break-all">{{ $subscription->transaction->reference }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Amount</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">₦{{ number_format($subscription->transaction->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Status</p>
                        <span class="mt-1 inline-block px-2 py-0.5 text-xs font-semibold rounded-full
                            @if($subscription->transaction->status === 'successful') bg-green-100 text-green-800
                            @elseif($subscription->transaction->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($subscription->transaction->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Channel</p>
                        <p class="mt-1 text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $subscription->transaction->payment_channel ?? 'N/A')) }}</p>
                    </div>
                    @if($subscription->transaction->paid_at)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Paid At</p>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($subscription->transaction->paid_at)->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Actions --}}
            @if($subscription->status === 'active')
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Actions</h3>
                </div>
                <div class="p-6 flex flex-col sm:flex-row gap-4">

                    {{-- Extend --}}
                    <form action="{{ route('admin.subscriptions.extend', $subscription) }}" method="POST" class="flex-1">
                        @csrf
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-gray-700">Extend Subscription</p>
                            <div class="flex gap-2">
                                <input type="number" name="additional_days" min="1" max="365" placeholder="Days" required
                                    class="w-24 rounded border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <input type="number" name="amount_paid" min="0" step="0.01" placeholder="Amount (₦)" required
                                    class="w-36 rounded border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                                    Extend
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Cancel --}}
                    <div class="flex items-end">
                        <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded">
                                Cancel Subscription
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
