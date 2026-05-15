@php
    $enabled  = (bool) \App\Models\Setting::getValue('free_telegram_popup_enabled', false);
    $link     = \App\Models\Setting::getValue('free_telegram_group_link', '');
    $name     = \App\Models\Setting::getValue('free_telegram_group_name', 'Free Tips Group');
    $message  = \App\Models\Setting::getValue('free_telegram_popup_message', 'Join our FREE Telegram group for daily football tips!');
@endphp

@if($enabled && $link && auth()->guest())
<div
    id="tg-popup"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
    style="display:none!important"
    aria-modal="true"
    role="dialog"
>
    {{-- Backdrop --}}
    <div id="tg-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeTgPopup()"></div>

    {{-- Card --}}
    <div class="relative w-full max-w-md bg-slate-900 border border-accent-400/30 rounded-3xl shadow-2xl overflow-hidden animate-in">

        {{-- Gold top bar --}}
        <div class="h-1.5 w-full bg-accent-400"></div>

        {{-- Close button --}}
        <button
            onclick="closeTgPopup()"
            class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors"
            aria-label="Close"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="p-8 text-center">
            {{-- Telegram icon --}}
            <div class="w-16 h-16 rounded-full bg-accent-400/15 border border-accent-400/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-9 h-9 text-accent-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L8.32 13.617 5.32 12.7c-.651-.204-.664-.651.136-.964l10.37-3.996c.542-.196 1.016.133.868.48z"/>
                </svg>
            </div>

            <h2 class="text-2xl font-display font-bold text-white mb-2">
                Join {{ $name }}
            </h2>

            <p class="text-gray-400 leading-relaxed mb-7">
                {{ $message }}
            </p>

            {{-- CTA --}}
            <a
                href="{{ $link }}"
                target="_blank"
                rel="noopener noreferrer"
                onclick="closeTgPopup()"
                class="flex items-center justify-center gap-2 w-full bg-accent-400 hover:bg-accent-300 text-black font-bold py-3.5 rounded-2xl text-base transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 mb-3"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L8.32 13.617 5.32 12.7c-.651-.204-.664-.651.136-.964l10.37-3.996c.542-.196 1.016.133.868.48z"/>
                </svg>
                Join Free Group Now
            </a>

            <button
                onclick="closeTgPopup()"
                class="text-sm text-gray-500 hover:text-gray-300 transition-colors"
            >
                No thanks, maybe later
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes popup-in {
        from { opacity: 0; transform: translateY(20px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-in { animation: popup-in .25s ease-out both; }
</style>

<script>
(function () {
    const STORAGE_KEY = 'tg_popup_dismissed';
    const DISMISS_DAYS = 7;

    function isDismissed() {
        const val = localStorage.getItem(STORAGE_KEY);
        if (!val) return false;
        return Date.now() < parseInt(val, 10);
    }

    function showPopup() {
        const el = document.getElementById('tg-popup');
        if (el) el.style.removeProperty('display');
    }

    window.closeTgPopup = function () {
        const el = document.getElementById('tg-popup');
        if (el) el.style.display = 'none';
        const expires = Date.now() + DISMISS_DAYS * 24 * 60 * 60 * 1000;
        localStorage.setItem(STORAGE_KEY, expires.toString());
    };

    if (!isDismissed()) {
        // Show after a short delay so the page loads first
        setTimeout(showPopup, 3000);
    }
})();
</script>
@endif
