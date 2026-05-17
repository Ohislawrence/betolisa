<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('bettor.plans') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Choose Payment Method</h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Order summary banner --}}
            <div class="rounded-2xl overflow-hidden border border-yellow-300 shadow-md">
                <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-4 sm:px-6 py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-black/60 uppercase tracking-wider mb-0.5">Your Order</p>
                        <h3 class="text-lg sm:text-xl font-bold text-black">Premium Monthly Subscription</h3>
                        <p class="text-sm text-black/70 mt-0.5">{{ $duration }}-day access &bull; All premium features included</p>
                    </div>
                    <div class="sm:text-right">
                        <span class="text-3xl sm:text-4xl font-bold text-black">&#8358;{{ number_format($cost) }}</span>
                        <p class="text-xs text-black/60 mt-0.5">one-time payment</p>
                    </div>
                </div>
                <div class="bg-white px-4 sm:px-6 py-3 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-gray-500">
                    <span>✅ Daily premium tips</span>
                    <span>✅ High-odds selections</span>
                    <span>✅ Telegram group access</span>
                    <span>✅ Expert analysis</span>
                </div>
            </div>

            {{-- Main payment card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                {{-- Tab navigation --}}
                <div class="border-b border-gray-200 overflow-x-auto">
                    <div class="flex w-full">
                        <button onclick="switchTab('card')" id="tab-card"
                            class="tab-btn flex-1 px-3 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-all whitespace-nowrap flex items-center justify-center gap-1 sm:gap-1.5 border-blue-600 text-blue-700 bg-blue-50">
                            💳 <span>Pay Online</span>
                        </button>
                        <button onclick="switchTab('transfer')" id="tab-transfer"
                            class="tab-btn flex-1 px-3 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-all whitespace-nowrap flex items-center justify-center gap-1 sm:gap-1.5 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            🏦 <span>Bank Transfer</span>
                        </button>
                        <button onclick="switchTab('email')" id="tab-email"
                            class="tab-btn flex-1 px-3 sm:px-5 py-3 sm:py-4 text-xs sm:text-sm font-medium text-center border-b-2 transition-all whitespace-nowrap flex items-center justify-center gap-1 sm:gap-1.5 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            ✉️ <span>Email Us</span>
                        </button>
                    </div>
                </div>

                {{-- ── Panel: Pay Online (Paystack) ── --}}
                <div id="panel-card" class="p-4 sm:p-6 lg:p-8">
                    <div class="max-w-md mx-auto text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Pay with Card</h3>
                        <p class="text-sm text-gray-500 mb-6">Securely pay with your debit or credit card via Paystack. You will be redirected to their checkout and returned here once done.</p>

                        <form action="{{ route('bettor.payment.initialize') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 text-base shadow hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Pay &#8358;{{ number_format($cost) }} Now
                            </button>
                        </form>

                        <div class="mt-5 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-xs text-gray-400">
                            <span>🔒 256-bit SSL</span>
                            <span class="text-gray-300 hidden sm:inline">|</span>
                            <span>PCI DSS Compliant</span>
                            <span class="text-gray-300 hidden sm:inline">|</span>
                            <span>Powered by Paystack</span>
                        </div>
                    </div>
                </div>

                {{-- ── Panel: Bank Transfer ── --}}
                <div id="panel-transfer" class="p-4 sm:p-6 lg:p-8 hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 text-xs font-bold flex items-center justify-center">1</span>
                                Transfer to this account
                            </h3>
                            <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-200 rounded-xl p-5 mb-4">
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between items-start gap-2 pb-3 border-b border-amber-200">
                                        <span class="text-amber-700 font-medium shrink-0">Bank</span>
                                        <span class="font-bold text-gray-900 text-right">Guarantee Trust Bank (GTB)</span>
                                    </div>
                                    <div class="flex justify-between items-start gap-2 pb-3 border-b border-amber-200">
                                        <span class="text-amber-700 font-medium shrink-0">Account Name</span>
                                        <span class="font-bold text-gray-900 text-right">BETOLISA LIMITED</span>
                                    </div>
                                    <div class="flex justify-between items-center gap-2">
                                        <span class="text-amber-700 font-medium shrink-0">Account No.</span>
                                        <span class="font-bold text-gray-900 text-base sm:text-xl tracking-widest font-mono">3004085537</span>
                                    </div>
                                </div>
                                <button onclick="copyAcct()" id="copy-btn"
                                    class="mt-4 w-full text-xs text-blue-700 border border-blue-300 rounded-lg py-2.5 hover:bg-blue-100 transition-colors font-medium">
                                    📋 Copy Account Number
                                </button>
                            </div>
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm">
                                <p class="font-semibold text-blue-800 mb-1">Amount to transfer</p>
                                <p class="text-2xl font-bold text-blue-900">&#8358;{{ number_format($cost) }}</p>
                                <p class="text-xs text-blue-600 mt-1">Transfer exactly this amount, then fill in the form.</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 text-xs font-bold flex items-center justify-center">2</span>
                                Confirm your transfer
                            </h3>
                            <form action="{{ route('bettor.payment.transfer') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sender Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name', auth()->user()->name) }}" required
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-400 focus:ring-1 focus:ring-amber-400 focus:outline-none"
                                        placeholder="Name used for the transfer">
                                    @error('sender_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transfer Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="transfer_date" value="{{ old('transfer_date', date('Y-m-d')) }}" required
                                        max="{{ date('Y-m-d') }}"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-400 focus:ring-1 focus:ring-amber-400 focus:outline-none">
                                    @error('transfer_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID / Note <span class="text-gray-400 font-normal">(optional)</span></label>
                                    <input type="text" name="note" value="{{ old('note') }}" maxlength="200"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-400 focus:ring-1 focus:ring-amber-400 focus:outline-none"
                                        placeholder="e.g. bank session ID or teller number">
                                </div>
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    I've Transferred — Submit
                                </button>
                                <p class="text-xs text-gray-400 text-center">Admin will verify and activate your subscription within a few hours.</p>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ── Panel: Email Us ── --}}
                <div id="panel-email" class="p-4 sm:p-6 lg:p-8 hidden">
                    <div class="max-w-xl mx-auto">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Send Us a Message</h3>
                                <p class="text-sm text-gray-500">Have questions about payment? We'll reply to <strong>{{ auth()->user()->email }}</strong> as soon as possible.</p>
                            </div>
                        </div>

                        @if(session('email_sent'))
                            <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-5 flex items-start gap-3">
                                <span class="text-2xl">✅</span>
                                <div>
                                    <p class="font-semibold text-green-800">Message sent!</p>
                                    <p class="text-sm text-green-700 mt-0.5">We've received your message and will reply to {{ auth()->user()->email }} shortly.</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('bettor.payment.contact') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject', 'Subscription Payment Enquiry') }}" maxlength="150"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-400 focus:ring-1 focus:ring-amber-400 focus:outline-none"
                                    placeholder="What is this about?">
                                @error('subject')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                                <textarea name="message" required rows="5" maxlength="2000"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-amber-400 focus:ring-1 focus:ring-amber-400 focus:outline-none resize-none"
                                    placeholder="Describe your payment situation or ask your question here...">{{ old('message') }}</textarea>
                                @error('message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Send Message
                            </button>
                            <p class="text-xs text-gray-400 text-center">We typically respond within a few hours during business hours.</p>
                        </form>
                    </div>
                </div>

            </div>

            {{-- Security footer --}}
            <div class="text-center text-xs text-gray-400 pb-4 px-2 leading-relaxed">
                🔒 All payment data is encrypted &bull; We never store your card details &bull; <a href="{{ route('privacy') }}" class="hover:text-gray-600 underline">Privacy Policy</a>
            </div>

        </div>
    </div>

    <script>
        const TABS = ['card', 'transfer', 'email'];

        function switchTab(tab) {
            TABS.forEach(function(t) {
                document.getElementById('panel-' + t).classList.add('hidden');
                const btn = document.getElementById('tab-' + t);
                btn.classList.remove('border-blue-600', 'text-blue-700', 'bg-blue-50');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('panel-' + tab).classList.remove('hidden');
            const active = document.getElementById('tab-' + tab);
            active.classList.remove('border-transparent', 'text-gray-500');
            active.classList.add('border-blue-600', 'text-blue-700', 'bg-blue-50');
        }

        function copyAcct() {
            navigator.clipboard.writeText('3004085537').then(function () {
                const btn = document.getElementById('copy-btn');
                btn.textContent = '✓ Copied!';
                btn.classList.add('bg-blue-100');
                setTimeout(() => {
                    btn.textContent = '📋 Copy Account Number';
                    btn.classList.remove('bg-blue-100');
                }, 2000);
            });
        }

        @if(session('active_tab'))
            switchTab('{{ session('active_tab') }}');
        @elseif($errors->hasAny(['message', 'subject']))
            switchTab('email');
        @elseif($errors->hasAny(['sender_name', 'transfer_date']))
            switchTab('transfer');
        @endif
    </script>
</x-app-layout>
