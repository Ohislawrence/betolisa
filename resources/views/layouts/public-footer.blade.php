<footer class="bg-black text-white border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="text-3xl"><img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-contain"></span>
                    <span class="text-2xl font-display font-bold text-white">
                        {{ config('app.name', 'TipsterPro') }}
                    </span>
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    Your trusted source for premium football predictions. Expert analysis, high-odds selections, and a winning community.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-accent-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-accent-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-accent-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a></li>
                    <li><a href="#features" class="text-gray-300 hover:text-white transition-colors">Features</a></li>
                    <li><a href="#tips" class="text-gray-300 hover:text-white transition-colors">Free Tips</a></li>
                    <li><a href="#pricing" class="text-gray-300 hover:text-white transition-colors">Pricing</a></li>
                    <li><a href="/terms" class="text-gray-300 hover:text-white transition-colors">Terms</a></li>
                    <li><a href="/privacy" class="text-gray-300 hover:text-white transition-colors">Privacy</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-white">Contact</h4>
                <ul class="space-y-2">
                    <li class="flex items-center space-x-2 text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>support@betolisa.com</span>
                    </li>
                    <li class="flex items-center space-x-2 text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Lagos, Nigeria</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 mt-8 pt-8 text-sm">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'TipsterPro') }}. All rights reserved.</p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms &amp; Conditions</a>
                    <span class="text-white/20">|</span>
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                </div>
            </div>
            <p class="text-center text-gray-600 mt-3 text-xs">Payments processed securely by Paystack &mdash; your card details are never stored on our servers.</p>
        </div>
    </div>
</footer>
