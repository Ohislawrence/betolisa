@extends('layouts.guest')

@section('title', 'Terms & Conditions')

@section('content')
<div class="bg-black min-h-screen">
    {{-- Hero --}}
    <div class="bg-slate-950 border-b border-white/10 py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-accent-400/10 text-accent-400 border border-accent-400/20 uppercase tracking-widest mb-4">Legal</span>
            <h1 class="text-4xl font-display font-bold text-white mb-3">Terms &amp; Conditions</h1>
            <p class="text-gray-400">Last updated: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="prose prose-invert prose-lg max-w-none
                    prose-headings:text-white prose-headings:font-display
                    prose-h2:text-2xl prose-h2:font-bold prose-h2:mt-10 prose-h2:mb-4
                    prose-h3:text-xl prose-h3:font-semibold prose-h3:mt-6 prose-h3:mb-3
                    prose-p:text-gray-300 prose-p:leading-relaxed
                    prose-li:text-gray-300
                    prose-a:text-accent-400 hover:prose-a:text-accent-300
                    prose-strong:text-white">

            <p class="text-gray-300 leading-relaxed">
                Welcome to <strong>{{ config('app.name', 'TipsterPro') }}</strong>. By accessing or using our website and services, you agree to be bound by these Terms &amp; Conditions. Please read them carefully before proceeding.
            </p>

            <h2>1. Acceptance of Terms</h2>
            <p>
                By creating an account, making a payment, or using any part of our platform, you confirm that you are at least 18 years of age, have read and understood these terms, and agree to be legally bound by them. If you do not agree, you must not use our services.
            </p>

            <h2>2. Nature of the Service</h2>
            <p>
                {{ config('app.name', 'TipsterPro') }} provides football betting tips, predictions, and analysis for <strong>informational and entertainment purposes only</strong>. Our predictions are based on research and statistical analysis; they do not constitute financial or gambling advice. We do not guarantee any specific outcome.
            </p>
            <p>
                Betting involves risk. You are solely responsible for any betting decisions you make using our tips. {{ config('app.name', 'TipsterPro') }} accepts no liability for any financial losses arising from the use of our content.
            </p>

            <h2>3. User Accounts</h2>
            <ul>
                <li>You must provide accurate and complete registration information.</li>
                <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
                <li>You must notify us immediately of any unauthorised use of your account.</li>
                <li>We reserve the right to suspend or terminate accounts that violate these terms.</li>
            </ul>

            <h2>4. Subscriptions &amp; Payments</h2>
            <p>
                Access to premium tips requires an active paid subscription. All payments are processed securely by <strong>Paystack</strong>, a third-party payment service provider. By subscribing, you also agree to
                <a href="https://paystack.com/terms" target="_blank" rel="noopener noreferrer">Paystack's Terms of Service</a>.
            </p>
            <h3>4.1 Billing</h3>
            <ul>
                <li>Subscription fees are charged at the time of purchase and at each renewal period.</li>
                <li>Prices are displayed in Nigerian Naira (NGN) unless otherwise stated.</li>
                <li>All fees are inclusive of applicable taxes where required by law.</li>
            </ul>
            <h3>4.2 Refund Policy</h3>
            <p>
                Due to the digital and time-sensitive nature of our tips content, <strong>all payments are non-refundable</strong> once a subscription period has commenced and premium content has been accessed. If you experience a billing error or a technical issue that prevented access, please contact our support team within 48 hours of payment.
            </p>
            <h3>4.3 Cancellation</h3>
            <p>
                You may cancel your subscription at any time from your account dashboard. Cancellation takes effect at the end of your current billing period; no partial refunds are issued for unused days.
            </p>
            <h3>4.4 Payment Security</h3>
            <p>
                We do not store your card details. All payment data is handled and encrypted by Paystack in compliance with PCI DSS standards. {{ config('app.name', 'TipsterPro') }} only receives confirmation of a successful or failed transaction.
            </p>

            <h2>5. Prohibited Conduct</h2>
            <p>You must not:</p>
            <ul>
                <li>Share, resell, or redistribute our premium tips content.</li>
                <li>Use automated tools (bots, scrapers) to extract content from our platform.</li>
                <li>Attempt to gain unauthorised access to any part of our systems.</li>
                <li>Use our platform in any way that violates applicable local, national, or international law.</li>
                <li>Use our platform if online gambling is prohibited in your jurisdiction.</li>
            </ul>

            <h2>6. Intellectual Property</h2>
            <p>
                All content on this platform — including tips, analysis, graphics, logos, and design — is the intellectual property of {{ config('app.name', 'TipsterPro') }} and is protected by copyright law. You may not reproduce, distribute, or create derivative works without our express written consent.
            </p>

            <h2>7. Disclaimer of Warranties</h2>
            <p>
                Our service is provided on an "as is" and "as available" basis without warranties of any kind, either express or implied. We do not warrant that the service will be uninterrupted, error-free, or that any specific tip will result in a winning outcome.
            </p>

            <h2>8. Limitation of Liability</h2>
            <p>
                To the fullest extent permitted by law, {{ config('app.name', 'TipsterPro') }} shall not be liable for any direct, indirect, incidental, special, or consequential damages arising from your use of — or inability to use — our service, including any financial losses from betting activities.
            </p>

            <h2>9. Third-Party Services</h2>
            <p>
                Our platform integrates with third-party services including Paystack (payments) and Telegram (group communication). We are not responsible for the availability, accuracy, or conduct of these services. Your use of such services is governed by their own terms and privacy policies.
            </p>

            <h2>10. Changes to These Terms</h2>
            <p>
                We may update these Terms &amp; Conditions from time to time. Material changes will be notified via email or a prominent notice on our platform. Continued use of the service after changes take effect constitutes acceptance of the revised terms.
            </p>

            <h2>11. Governing Law</h2>
            <p>
                These terms are governed by the laws of the Federal Republic of Nigeria. Any disputes shall be subject to the exclusive jurisdiction of the courts of Lagos State, Nigeria.
            </p>

            <h2>12. Contact Us</h2>
            <p>
                If you have any questions about these Terms &amp; Conditions, please contact us at:
            </p>
            <p>
                <strong>Email:</strong> <a href="mailto:support@betolisa.com">support@betolisa.com</a><br>
                <strong>Location:</strong> Lagos, Nigeria
            </p>
        </div>

        {{-- Bottom nav --}}
        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
            <a href="{{ route('privacy') }}" class="text-accent-400 hover:text-accent-300 transition-colors">
                Read our Privacy Policy →
            </a>
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
