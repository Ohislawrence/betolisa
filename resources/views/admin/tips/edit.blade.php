<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.tips.update', $tip) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Same fields as create form but with old() fallback to $tip values -->
                            <div>
                                <label for="league_id" class="block text-sm font-medium text-gray-700">League *</label>
                                <select name="league_id" id="league_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach($leagues as $league)
                                        <option value="{{ $league->id }}" {{ old('league_id', $tip->league_id) == $league->id ? 'selected' : '' }}>
                                            {{ $league->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('league_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="match_date" class="block text-sm font-medium text-gray-700">Match Date</label>
                                <input type="datetime-local" name="match_date" id="match_date"
                                    value="{{ old('match_date', $tip->match_date ? $tip->match_date->format('Y-m-d\TH:i') : '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('match_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="home_team" class="block text-sm font-medium text-gray-700">Home Team *</label>
                                <input type="text" name="home_team" id="home_team" value="{{ old('home_team', $tip->home_team) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('home_team')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="away_team" class="block text-sm font-medium text-gray-700">Away Team *</label>
                                <input type="text" name="away_team" id="away_team" value="{{ old('away_team', $tip->away_team) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('away_team')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="odds" class="block text-sm font-medium text-gray-700">Odds</label>
                                <input type="number" step="0.01" name="odds" id="odds" value="{{ old('odds', $tip->odds) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('odds')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Tip Type *</label>
                                <select name="type" id="type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="free" {{ old('type', $tip->type) == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="premium" {{ old('type', $tip->type) == 'premium' ? 'selected' : '' }}>Premium</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending" {{ old('status', $tip->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="won" {{ old('status', $tip->status) == 'won' ? 'selected' : '' }}>Won</option>
                                    <option value="lost" {{ old('status', $tip->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                                    <option value="void" {{ old('status', $tip->status) == 'void' ? 'selected' : '' }}>Void</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="tip_content" class="block text-sm font-medium text-gray-700">Tip Content *</label>
                                <textarea name="tip_content" id="tip_content" rows="4" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('tip_content', $tip->tip_content) }}</textarea>
                                @error('tip_content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="is_active" class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $tip->is_active) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.tips.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Tip
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
