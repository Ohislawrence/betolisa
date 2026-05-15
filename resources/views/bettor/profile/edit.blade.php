<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl font-bold text-blue-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>

                            @if($activeSubscription)
                                <div class="mt-4 bg-green-50 rounded p-3">
                                    <p class="text-sm font-medium text-green-800">Premium Member</p>
                                    <p class="text-xs text-green-600">
                                        {{ $activeSubscription->daysRemaining() }} days remaining
                                    </p>
                                </div>
                            @else
                                <div class="mt-4 bg-gray-50 rounded p-3">
                                    <p class="text-sm text-gray-600">Free Member</p>
                                    <a href="{{ route('bettor.plans') }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                        Upgrade to Premium →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Profile Information -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h3>

                            <form action="{{ route('bettor.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" value="{{ $user->email }}" disabled
                                            class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 shadow-sm">
                                        <p class="mt-1 text-sm text-gray-500">Email cannot be changed</p>
                                    </div>

                                    <div>
                                        <label for="telegram_number" class="block text-sm font-medium text-gray-700">
                                            Telegram Username/Number
                                        </label>
                                        <input type="text" name="telegram_number" id="telegram_number"
                                            value="{{ old('telegram_number', $user->telegram_number) }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="@username or phone number">
                                        @error('telegram_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-gray-500">
                                            This is used to add you to the premium Telegram group
                                        </p>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>

                            <form action="{{ route('bettor.profile.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">
                                            Current Password
                                        </label>
                                        <input type="password" name="current_password" id="current_password" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">
                                            New Password
                                        </label>
                                        <input type="password" name="password" id="password" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                            Confirm New Password
                                        </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                        Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
