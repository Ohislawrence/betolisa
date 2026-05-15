<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Stats Cards -->
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
                    <h4 class="text-sm font-medium text-gray-500">Revenue This Month</h4>
                    <p class="text-2xl font-bold text-purple-600">₦{{ number_format($stats['revenue_this_month']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="text-sm font-medium text-gray-500">Expired Subscriptions</h4>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['total_expired'] }}</p>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.settings.subscription.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium mb-4">Subscription Configuration</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label for="subscription_cost" class="block text-sm font-medium text-gray-700">
                                            Monthly Subscription Cost (₦)
                                        </label>
                                        <input type="number" name="subscription_cost" id="subscription_cost"
                                            value="{{ old('subscription_cost', $cost) }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('subscription_cost')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="subscription_duration_days" class="block text-sm font-medium text-gray-700">
                                            Subscription Duration (Days)
                                        </label>
                                        <input type="number" name="subscription_duration_days" id="subscription_duration_days"
                                            value="{{ old('subscription_duration_days', $duration) }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('subscription_duration_days')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-4">Paystack Configuration</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label for="paystack_public_key" class="block text-sm font-medium text-gray-700">
                                            Paystack Public Key
                                        </label>
                                        <input type="text" name="paystack_public_key" id="paystack_public_key"
                                            value="{{ old('paystack_public_key', $paystackPublicKey) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('paystack_public_key')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="paystack_secret_key" class="block text-sm font-medium text-gray-700">
                                            Paystack Secret Key
                                        </label>
                                        <input type="text" name="paystack_secret_key" id="paystack_secret_key"
                                            value="{{ old('paystack_secret_key', $paystackSecretKey) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('paystack_secret_key')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
