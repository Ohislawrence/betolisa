<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Telegram Integration Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Connection Status -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-medium mb-4">Connection Status</h3>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($botToken && $groupId)
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-green-700 font-medium">Configured</span>
                        @else
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-red-700 font-medium">Not Configured</span>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <form action="{{ route('admin.settings.telegram.test') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Test Connection
                            </button>
                        </form>
                        @if($botToken && $groupId)
                            <form action="{{ route('admin.settings.telegram.invite-link') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Generate Invite Link
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($memberCount > 0)
                    <div class="mt-4 p-3 bg-blue-50 rounded">
                        <span class="text-blue-700">Group Members: <strong>{{ $memberCount }}</strong></span>
                    </div>
                @endif
            </div>

            <!-- Settings Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.settings.telegram.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="telegram_bot_token" class="block text-sm font-medium text-gray-700">
                                    Bot Token
                                </label>
                                <input type="text" name="telegram_bot_token" id="telegram_bot_token"
                                    value="{{ old('telegram_bot_token', $botToken) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                                <p class="mt-1 text-sm text-gray-500">
                                    Get this from <a href="https://t.me/BotFather" target="_blank" class="text-blue-500 hover:underline">@BotFather</a> on Telegram
                                </p>
                                @error('telegram_bot_token')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telegram_group_id" class="block text-sm font-medium text-gray-700">
                                    Group/Chat ID
                                </label>
                                <input type="text" name="telegram_group_id" id="telegram_group_id"
                                    value="{{ old('telegram_group_id', $groupId) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="-1001234567890">
                                <p class="mt-1 text-sm text-gray-500">
                                    Add bot to your group as admin, then get the chat ID
                                </p>
                                @error('telegram_group_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($groupLink)
                                <div class="bg-gray-50 p-4 rounded">
                                    <label class="block text-sm font-medium text-gray-700">Current Invite Link</label>
                                    <div class="flex items-center mt-2">
                                        <input type="text" value="{{ $groupLink }}" readonly
                                            class="block w-full rounded-md border-gray-300 bg-white">
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $groupLink }}')"
                                            class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Free Telegram Group Popup -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Free Group Visitor Popup</h3>
                            <p class="text-sm text-gray-500 mt-1">Show a popup to website visitors inviting them to join a free Telegram group for tips.</p>
                        </div>
                        <span class="inline-flex items-center gap-2 text-sm font-semibold {{ $freeGroupEnabled ? 'text-green-600' : 'text-gray-400' }}">
                            <span class="w-2.5 h-2.5 rounded-full {{ $freeGroupEnabled ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            {{ $freeGroupEnabled ? 'Active' : 'Disabled' }}
                        </span>
                    </div>

                    <form action="{{ route('admin.settings.telegram.free-group.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                <input type="hidden" name="free_telegram_popup_enabled" value="0">
                                <input type="checkbox" name="free_telegram_popup_enabled" id="free_telegram_popup_enabled" value="1"
                                    {{ $freeGroupEnabled ? 'checked' : '' }}
                                    class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <label for="free_telegram_popup_enabled" class="text-sm font-medium text-gray-700 cursor-pointer">
                                    Enable popup for guests and visitors
                                </label>
                            </div>

                            <div>
                                <label for="free_telegram_group_name" class="block text-sm font-medium text-gray-700">Group Name</label>
                                <input type="text" name="free_telegram_group_name" id="free_telegram_group_name"
                                    value="{{ old('free_telegram_group_name', $freeGroupName) }}"
                                    placeholder="e.g. TipsterPro Free Tips"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('free_telegram_group_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="free_telegram_group_link" class="block text-sm font-medium text-gray-700">Telegram Join Link</label>
                                <input type="url" name="free_telegram_group_link" id="free_telegram_group_link"
                                    value="{{ old('free_telegram_group_link', $freeGroupLink) }}"
                                    placeholder="https://t.me/+xxxxxxxxxxxxxxxx"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">The public invite link for your free Telegram group.</p>
                                @error('free_telegram_group_link')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="free_telegram_popup_message" class="block text-sm font-medium text-gray-700">Popup Message</label>
                                <textarea name="free_telegram_popup_message" id="free_telegram_popup_message" rows="3"
                                    placeholder="Join our FREE Telegram group for daily football tips!"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('free_telegram_popup_message', $freeGroupMessage) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Max 500 characters. Shown inside the popup to visitors.</p>
                                @error('free_telegram_popup_message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($freeGroupLink)
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
                                    <span class="font-medium">Preview link:</span>
                                    <a href="{{ $freeGroupLink }}" target="_blank" class="ml-2 underline hover:text-blue-900">{{ $freeGroupLink }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Save Popup Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Setup Instructions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Setup Instructions</h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                        <li>Create a bot using <a href="https://t.me/BotFather" target="_blank" class="text-blue-500 hover:underline">@BotFather</a> on Telegram</li>
                        <li>Copy the bot token and paste it above</li>
                        <li>Create a Telegram group for premium subscribers</li>
                        <li>Add your bot to the group as an <strong>administrator</strong></li>
                        <li>Get the group ID (add @RawDataBot to group temporarily to get ID)</li>
                        <li>Paste the group ID above and save</li>
                        <li>Click "Test Connection" to verify everything works</li>
                        <li>Click "Generate Invite Link" to create a link for subscribers</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
