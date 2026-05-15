@extends('layouts.guest')

@section('title', 'Privacy Policy')

@section('content')
<div class="bg-black min-h-screen">
    {{-- Hero --}}
    <div class="bg-slate-950 border-b border-white/10 py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-accent-400/10 text-accent-400 border border-accent-400/20 uppercase tracking-widest mb-4">Legal</span>
            <h1 class="text-4xl font-display font-bold text-white mb-3">Privacy Policy</h1>
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
                At <strong>{{ config('app.name', 'TipsterPro') }}</strong>, your privacy matters to us. This Privacy Policy explains what personal information we collect, how we use it, and the choices you have regarding your data.
            </p>

            <h2>1. Information We Collect</h2>
            <h3>1.1 Information You Provide</h3>
            <ul>
                <li><strong>Account data:</strong> name, email address, and password when you register.</li>
                <li><strong>Profile information:</strong> any optional details you add to your profile.</li>
                <li><strong>Communications:</strong> messages you send to our support team.</li>
            </ul>
            <h3>1.2 Payment Information</h3>
            <p>
                Subscription payments are processed by <strong>Paystack</strong>, a PCI DSS-compliant third-party payment processor. We do <strong>not</strong> collect, store, or have access to your card number, CVV, or bank account details. Paystack provides us with a transaction reference and status only. For details on how Paystack handles your payment data, please review
                <a href="https://paystack.com/privacy" target="_blank" rel="noopener noreferrer">Paystack's Privacy Policy</a>.
            </p>
            <h3>1.3 Usage Data</h3>
            <ul>
                <li>IP address, browser type, and device information.</li>
                <li>Pages visited, time spent, and links clicked within our platform.</li>
                <li>Referring URLs and search terms used to find our site.</li>
            </ul>
            <h3>1.4 Cookies &amp; Similar Technologies</h3>
            <p>
                We use essential session cookies required for authentication and platform functionality. We may also use analytics cookies to understand how visitors use our platform. You can disable cookies in your browser settings, though some features may not function correctly as a result.
            </p>

            <h2>2. How We Use Your Information</h2>
            <ul>
                <li>To create and manage your account.</li>
                <li>To process subscription payments and maintain billing records.</li>
                <li>To deliver premium tips and platform content you have subscribed to.</li>
                <li>To send transactional emails (receipts, subscription confirmations, expiry reminders).</li>
                <li>To add you to our Telegram premium group upon subscription activation and remove you upon expiry.</li>
                <li>To respond to your support enquiries.</li>
                <li>To improve our platform through aggregated, anonymised analytics.</li>
                <li>To comply with legal obligations.</li>
            </ul>
            <p>We will never sell your personal data to third parties.</p>

            <h2>3. Legal Basis for Processing (GDPR &amp; NDPR)</h2>
            <p>We process your personal data under the following lawful bases:</p>
            <ul>
                <li><strong>Contractual necessity:</strong> processing required to deliver the service you subscribed to.</li>
                <li><strong>Legitimate interests:</strong> platform security, fraud prevention, and service improvement.</li>
                <li><strong>Legal obligation:</strong> retaining transaction records as required by financial regulations.</li>
                <li><strong>Consent:</strong> for optional marketing communications, which you may withdraw at any time.</li>
            </ul>

            <h2>4. How We Share Your Information</h2>
            <p>We share your data only in the following limited circumstances:</p>
            <ul>
                <li><strong>Paystack:</strong> to facilitate payment processing. Data shared is limited to name, email, and transaction amount.</li>
                <li><strong>Telegram:</strong> your Telegram username/ID if you join our premium Telegram group.</li>
                <li><strong>Legal requirements:</strong> when required by law, court order, or governmental authority.</li>
                <li><strong>Business transfer:</strong> in the event of a merger or acquisition, your data may be transferred to the new entity, subject to the same privacy protections.</li>
            </ul>

            <h2>5. Data Retention</h2>
            <p>
                We retain your account data for as long as your account is active. If you delete your account, your personal data will be removed within 30 days, except where we are legally required to retain financial transaction records (typically 7 years under Nigerian financial regulations).
            </p>

            <h2>6. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li><strong>Access:</strong> request a copy of the personal data we hold about you.</li>
                <li><strong>Correction:</strong> request correction of inaccurate or incomplete data.</li>
                <li><strong>Deletion:</strong> request deletion of your personal data, subject to legal retention requirements.</li>
                <li><strong>Portability:</strong> receive your data in a machine-readable format.</li>
                <li><strong>Objection:</strong> object to processing based on legitimate interests.</li>
                <li><strong>Withdraw consent:</strong> for marketing communications at any time via the unsubscribe link in our emails.</li>
            </ul>
            <p>To exercise any of these rights, contact us at <a href="mailto:support@betolisa.com">support@betolisa.com</a>. We will respond within 30 days.</p>

            <h2>7. Security</h2>
            <p>
                We implement industry-standard security measures including HTTPS encryption, hashed passwords, and access controls to protect your personal data. However, no internet transmission is 100% secure, and we cannot guarantee absolute security.
            </p>

            <h2>8. Children's Privacy</h2>
            <p>
                Our platform is intended for users aged 18 and over. We do not knowingly collect personal data from anyone under 18. If we become aware that a minor has registered, we will promptly delete their account and associated data.
            </p>

            <h2>9. Third-Party Links</h2>
            <p>
                Our platform may contain links to third-party websites (e.g., Paystack, Telegram). We are not responsible for the privacy practices of those sites. We encourage you to review their privacy policies before providing any personal information.
            </p>

            <h2>10. Changes to This Policy</h2>
            <p>
                We may update this Privacy Policy periodically. We will notify you of significant changes via email or a notice on our platform. The date at the top of this page reflects the latest revision.
            </p>

            <h2>11. Contact Us</h2>
            <p>
                If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact our Data Protection Officer:
            </p>
            <p>
                <strong>Email:</strong> <a href="mailto:support@betolisa.com">support@betolisa.com</a><br>
                <strong>Location:</strong> Lagos, Nigeria
            </p>
        </div>

        {{-- Bottom nav --}}
        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
            <a href="{{ route('terms') }}" class="text-accent-400 hover:text-accent-300 transition-colors">
                Read our Terms &amp; Conditions →
            </a>
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
