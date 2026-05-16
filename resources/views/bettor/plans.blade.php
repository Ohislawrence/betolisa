<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($activeSubscription)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">✓ You have an active subscription</h3>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-green-600">Started</span>
                            <p class="font-medium text-gray-800">{{ $activeSubscription->starts_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-green-600">Expires</span>
                            <p class="font-medium text-gray-800">{{ $activeSubscription->ends_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <span class="text-green-600">Days Left</span>
                            <p class="font-medium text-gray-800">{{ $activeSubscription->daysRemaining() }} days</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Plan summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Premium Monthly</h3>
                        <p class="text-sm text-gray-500">Full access for {{ $duration }} days</p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-gray-900">&#8358;{{ number_format($cost) }}</span>
                        <p class="text-xs text-gray-400">/{{ $duration }} days</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach(['Daily premium tips', 'High odds selections', 'Telegram group access', '24/7 support'] as $feature)
                        <div class="flex items-center gap-2 text-gray-700">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment method tabs --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200">
                    <div class="flex">
                        <button onclick="switchTab('card')" id="tab-card"
                            class="tab-btn flex-1 py-4 text-sm font-semibold text-center border-b-2 border-blue-500 text-blue-600 transition-colors">
                            💳 Pay with Card
                        </button>
                        <button onclick="switchTab('transfer')" id="tab-transfer"
                            class="tab-btn flex-1 py-4 text-sm font-semibold text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-colors">
                            🏦 Pay via Bank Transfer
                        </button>
                    </div>
                </div>

                {{-- Card payment panel --}}
                <div id="panel-card" class="p-6">
                    <p class="text-sm text-gray-600 mb-6">
                        Pay securely with your debit or credit card. You'll be redirected to Paystack's secure checkout page.
                    </p>
                    <form action="{{ route('bettor.payment.initialize') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            {{ $activeSubscription ? 'Renew with Card' : 'Pay ₦'.number_format($cost).' with Card' }}
                        </button>
                    </form>
                    <p class="text-xs text-gray-400 text-center mt-3">Secured by Paystack &bull; PCI DSS Compliant</p>
                </div>

                {{-- Bank transfer panel --}}
                <div id="panel-transfer" class="p-6 hidden">
                    {{-- Bank details --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-6">
                        <h4 class="font-semibold text-amber-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Bank Account Details
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-amber-200">
                                <span class="text-amber-700">Bank</span>
                                <span class="font-bold text-gray-900">Guarantee Trust Bank (GTB)</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-amber-200">
                                <span class="text-amber-700">Account Number</span>
                                <span class="font-bold text-gray-900 text-lg tracking-widest" id="acct-no">3004085537</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-amber-700">Account Name</span>
                                <span class="font-bold text-gray-900">BETOLISA LIMITED</span>
                            </div>
                        </div>
                        <button onclick="copyAcct()" class="mt-4 w-full text-xs text-amber-700 border border-amber-300 rounded-lg py-2 hover:bg-amber-100 transition-colors" id="copy-btn">
                            📋 Copy Account Number
                        </button>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-800">
                        <strong>How it works:</strong> Transfer exactly <strong>&#8358;{{ number_format($cost) }}</strong> to the account above, then fill in the form below. Your subscription will be activated within a few hours after admin confirmation.
                    </div>

                    {{-- Transfer confirmation form --}}
                    <form action="{{ route('bettor.payment.transfer') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Your Name / Sender Name <span class="text-red-500">*</span></label>
                            <input type="text" name="sender_name" value="{{ old('sender_name', auth()->user()->name) }}" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none"
                                placeholder="Name used for the transfer">
                            @error('sender_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Transfer Date <span class="text-red-500">*</span></label>
                            <input type="date" name="transfer_date" value="{{ old('transfer_date', date('Y-m-d')) }}" required
                                max="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none">
                            @error('transfer_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Additional Note <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="text" name="note" value="{{ old('note') }}" maxlength="200"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none"
                                placeholder="e.g. transaction ID or bank session ID">
                        </div>
                        <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            I've Made the Transfer — Submit for Review
                        </button>
                        <p class="text-xs text-gray-400 text-center">
                            Submitting this form does not activate your subscription immediately. An admin will verify your payment and activate it shortly.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.getElementById('panel-card').classList.add('hidden');
            document.getElementById('panel-transfer').classList.add('hidden');
            document.getElementById('panel-' + tab).classList.remove('hidden');

            document.getElementById('tab-card').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('tab-card').classList.add('border-transparent', 'text-gray-500');
            document.getElementById('tab-transfer').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('tab-transfer').classList.add('border-transparent', 'text-gray-500');

            document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tab).classList.add('border-blue-500', 'text-blue-600');
        }

        function copyAcct() {
            navigator.clipboard.writeText('3004085537').then(function () {
                const btn = document.getElementById('copy-btn');
                btn.textContent = '✓ Copied!';
                setTimeout(() => { btn.textContent = '📋 Copy Account Number'; }, 2000);
            });
        }

        // If there are validation errors on the transfer form, auto-switch to transfer tab
        @if($errors->any())
            switchTab('transfer');
        @endif
    </script>
</x-app-layout>
