<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tip Details') }}
            </h2>
            <a href="{{ route('admin.tips.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Tips
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Match Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">League</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tip->league->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Match</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tip->home_team }} vs {{ $tip->away_team }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Match Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $tip->match_date ? $tip->match_date->format('d M Y H:i') : 'N/A' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Odds</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tip->odds ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tip Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tip->type == 'premium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($tip->type) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($tip->status == 'won') bg-green-100 text-green-800
                                            @elseif($tip->status == 'lost') bg-red-100 text-red-800
                                            @elseif($tip->status == 'void') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($tip->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tip->creator->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $tip->created_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tip Content</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-900">{{ $tip->tip_content }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('admin.tips.edit', $tip) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Tip
                        </a>
                        <form action="{{ route('admin.tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Tip
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
