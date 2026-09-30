@extends('layouts.main')
@section('title', 'Privacy Policy - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Legal',
    'title'  => 'Privacy <em>Policy</em>',
    'intro'  => 'We are committed to protecting your personal information and your right to privacy',
])

{{-- ===== CONTENT ===== --}}
<section class="zt-section zt-section--white" style="padding:64px 0 80px;">
    <div class="container">
        <div style="max-width:820px;margin:0 auto;">

            {{-- Last Updated --}}
            <div class="ziara-note" style="margin-bottom:28px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <i class="fa-solid fa-calendar" aria-hidden="true" style="color:var(--maroon);"></i>
                    <strong>Last Updated: {{ date('F j, Y') }}</strong>
                </div>
                <p style="margin:0;">This privacy policy was last updated on {{ date('F j, Y') }} and is compliant with GDPR regulations.</p>
            </div>

            {{-- Table of Contents --}}
            <div class="ziara-panel" style="margin-bottom:28px;">
                <h3 class="ziara-panel__title" style="display:flex;align-items:center;gap:10px;"><i class="fa-solid fa-list" aria-hidden="true"></i> Table of Contents</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 24px;">
                    <a href="#data-collection" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">1. Data Collection</a>
                    <a href="#data-use" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">2. How We Use Your Data</a>
                    <a href="#data-sharing" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">3. Data Sharing</a>
                    <a href="#data-retention" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">4. Data Retention</a>
                    <a href="#your-rights" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">5. Your Rights (GDPR)</a>
                    <a href="#cookies" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">6. Cookies Policy</a>
                    <a href="#security" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">7. Data Security</a>
                    <a href="#contact" style="color:var(--maroon);font-size:14px;text-decoration:none;padding:6px 0;">8. Contact Information</a>
                </div>
            </div>

            {{-- 1. Data Collection --}}
            <div class="ziara-panel" id="data-collection">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-database" aria-hidden="true"></i> 1. Data Collection</h3>
                <div class="ziara-prose">
                    <h3 style="margin-top:6px;">Personal Information We Collect:</h3>
                    <ul style="padding-left:4px;list-style:none;display:grid;gap:10px;">
                        <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-user" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Account Information:</strong> Name, email address, phone number, date of birth</span></li>
                        <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-plane" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Travel Information:</strong> Passport details, travel preferences, booking history</span></li>
                        <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-credit-card" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Payment Information:</strong> Credit card details, billing address, payment history</span></li>
                        <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-chart-line" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Usage Data:</strong> IP address, browser type, pages visited, search queries</span></li>
                    </ul>
                </div>
                <div class="ziara-note" style="margin-top:20px;">
                    <h3 style="margin:0 0 10px;color:var(--maroon-deep);">Legal Basis for Processing (GDPR Article 6):</h3>
                    <ul style="margin:0;padding-left:16px;">
                        <li><strong>Contract:</strong> Processing necessary for booking and travel services</li>
                        <li><strong>Legitimate Interest:</strong> Fraud prevention, security, and service improvement</li>
                        <li><strong>Consent:</strong> Marketing communications and cookies</li>
                        <li><strong>Legal Obligation:</strong> Compliance with aviation and travel regulations</li>
                    </ul>
                </div>
            </div>

            {{-- 2. Data Use --}}
            <div class="ziara-panel" id="data-use">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-sliders" aria-hidden="true"></i> 2. How We Use Your Data</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                    <div>
                        <h4 style="margin:0 0 10px;color:var(--maroon-deep);">Primary Uses:</h4>
                        <ul style="margin:0;padding-left:16px;">
                            <li>Process flight and hotel bookings</li>
                            <li>Provide customer support</li>
                            <li>Send booking confirmations</li>
                            <li>Comply with legal requirements</li>
                        </ul>
                    </div>
                    <div>
                        <h4 style="margin:0 0 10px;color:var(--maroon-deep);">Secondary Uses:</h4>
                        <ul style="margin:0;padding-left:16px;">
                            <li>Improve our services</li>
                            <li>Personalize user experience</li>
                            <li>Send marketing communications (with consent)</li>
                            <li>Analyze website usage patterns</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 3. Data Sharing --}}
            <div class="ziara-panel" id="data-sharing">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-share-nodes" aria-hidden="true"></i> 3. Data Sharing</h3>
                <div style="display:grid;gap:18px;">
                    <div class="ziara-note">
                        <h4 style="margin:0 0 8px;color:#167a3a;">We Never Sell Your Data</h4>
                        <p style="margin:0;">Your personal information is never sold to third parties for marketing purposes.</p>
                    </div>
                    <div>
                        <h4 style="margin:0 0 12px;color:var(--maroon-deep);">We Share Data With:</h4>
                        <ul style="margin:0;padding-left:4px;list-style:none;display:grid;gap:10px;">
                            <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-plane" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Airlines &amp; Hotels:</strong> To process your bookings and provide services</span></li>
                            <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-credit-card" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Payment Processors:</strong> To process transactions securely</span></li>
                            <li style="display:flex;align-items:flex-start;gap:12px;"><i class="fa-solid fa-shield-halved" aria-hidden="true" style="color:var(--gold);margin-top:5px;width:16px;text-align:center;"></i><span><strong>Legal Authorities:</strong> When required by law or to protect our rights</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- 4. Data Retention --}}
            <div class="ziara-panel" id="data-retention">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-clock" aria-hidden="true"></i> 4. Data Retention</h3>
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:14px;">
                        <thead>
                            <tr style="text-align:left;">
                                <th style="background:var(--maroon);color:#fff;padding:14px 16px;border:1px solid var(--maroon);">Data Type</th>
                                <th style="background:var(--maroon);color:#fff;padding:14px 16px;border:1px solid var(--maroon);">Retention Period</th>
                                <th style="background:var(--maroon);color:#fff;padding:14px 16px;border:1px solid var(--maroon);">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Account Information</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Until account deletion</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Service provision</td>
                            </tr>
                            <tr style="background:var(--gold-pale);">
                                <td style="border:1px solid var(--line);padding:14px 16px;">Booking Records</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">7 years</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Legal/tax requirements</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Payment Data</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">As per PCI DSS requirements</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Compliance &amp; dispute resolution</td>
                            </tr>
                            <tr style="background:var(--gold-pale);">
                                <td style="border:1px solid var(--line);padding:14px 16px;">Marketing Data</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Until consent withdrawn</td>
                                <td style="border:1px solid var(--line);padding:14px 16px;">Marketing communications</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. Your Rights --}}
            <div class="ziara-panel" id="your-rights">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-user-check" aria-hidden="true"></i> 5. Your Rights Under GDPR</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                    <div style="display:grid;gap:12px;align-content:start;">
                        <div style="display:flex;align-items:center;gap:12px;background:var(--gold-pale);border:1px solid var(--gold-border);border-radius:10px;padding:14px 16px;"><i class="fa-solid fa-eye" aria-hidden="true" style="color:var(--maroon);"></i><div><strong>Right to Access</strong><p style="margin:2px 0 0;font-size:13px;color:var(--muted);">Request a copy of your personal data</p></div></div>
                        <div style="display:flex;align-items:center;gap:12px;background:#eef7ef;border:1px solid rgba(22,122,58,.2);border-radius:10px;padding:14px 16px;"><i class="fa-solid fa-pen" aria-hidden="true" style="color:#167a3a;"></i><div><strong>Right to Rectification</strong><p style="margin:2px 0 0;font-size:13px;color:var(--muted);">Correct inaccurate personal data</p></div></div>
                        <div style="display:flex;align-items:center;gap:12px;background:#fdf0ee;border:1px solid rgba(179,38,30,.2);border-radius:10px;padding:14px 16px;"><i class="fa-solid fa-trash" aria-hidden="true" style="color:#b3261e;"></i><div><strong>Right to Erasure</strong><p style="margin:2px 0 0;font-size:13px;color:var(--muted);">Request deletion of your data</p></div></div>
                    </div>
                    <div style="display:grid;gap:12px;align-content:start;">
                        <div style="display:flex;align-items:center;gap:12px;background:#f0effb;border:1px solid rgba(90,78,178,.18);border-radius:10px;padding:14px 16px;"><i class="fa-solid fa-download" aria-hidden="true" style="color:#5a4eb2;"></i><div><strong>Right to Portability</strong><p style="margin:2px 0 0;font-size:13px;color:var(--muted);">Export your data in a readable format</p></div></div>
                        <div style="display:flex;align-items:center;gap:12px;background:#fdf3e7;border:1px solid rgba(200,120,30,.18);border-radius:10px;padding:14px 16px;"><i class="fa-solid fa-pause" aria-hidden="true" style="color:#c8781e;"></i><div><strong>Right to Restrict Processing</strong><p style="margin:2px 0 0;font-size:13px;color:var(--muted);">Limit how we use your data</p></div></div>
                        <div style="display:flex;align-items:center;gap:12px;background:#f6f2ef;border:1px solid var(--line);border-radius:10px;padding:14px 16px;"><i class="fa-solid fa-circle-xmark" aria-hidden="true" style="color:#6b1d15;"></i><div><strong>Right to Object</strong><p style="margin:2px 0 0;font-size:13px;color:var(--muted);">Object to data processing</p></div></div>
                    </div>
                </div>
                <div class="ziara-note" style="margin-top:24px;">
                    <h3 style="margin:0 0 10px;color:var(--maroon-deep);">How to Exercise Your Rights:</h3>
                    <p style="margin:0 0 8px;">Contact our Data Protection Officer at <a href="mailto:dpo@gnc.com" style="color:var(--maroon);font-weight:600;text-decoration:underline;">dpo@gnc.com</a> or use our <a href="{{ route('gdpr.consent') }}" style="color:var(--maroon);font-weight:600;text-decoration:underline;">GDPR portal</a>.</p>
                    <p style="margin:0;font-size:14px;">We will respond to your request within 30 days as required by GDPR.</p>
                </div>
            </div>

            {{-- 6. Cookies --}}
            <div class="ziara-panel" id="cookies">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-cookie-bite" aria-hidden="true"></i> 6. Cookies Policy</h3>
                <div style="display:grid;gap:18px;">
                    <div>
                        <h4 style="margin:0 0 12px;color:var(--maroon-deep);">Types of Cookies We Use:</h4>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                            <div style="background:#faf6f2;border:1px solid var(--line);border-radius:10px;padding:16px;"><strong>Essential Cookies</strong><p style="margin:4px 0 0;font-size:13px;color:var(--muted);">Required for basic site functionality</p></div>
                            <div style="background:#faf6f2;border:1px solid var(--line);border-radius:10px;padding:16px;"><strong>Performance Cookies</strong><p style="margin:4px 0 0;font-size:13px;color:var(--muted);">Help us analyze site usage</p></div>
                            <div style="background:#faf6f2;border:1px solid var(--line);border-radius:10px;padding:16px;"><strong>Functional Cookies</strong><p style="margin:4px 0 0;font-size:13px;color:var(--muted);">Remember your preferences</p></div>
                            <div style="background:#faf6f2;border:1px solid var(--line);border-radius:10px;padding:16px;"><strong>Marketing Cookies</strong><p style="margin:4px 0 0;font-size:13px;color:var(--muted);">Deliver relevant advertisements</p></div>
                        </div>
                    </div>
                    <div class="ziara-note">
                        <h4 style="margin:0 0 8px;color:var(--maroon-deep);">Cookie Consent</h4>
                        <p style="margin:0 0 14px;">We obtain your consent before using non-essential cookies. You can manage your cookie preferences at any time.</p>
                        <button type="button" class="ziara-btn ziara-btn--gold">Manage Cookie Preferences</button>
                    </div>
                </div>
            </div>

            {{-- 7. Data Security --}}
            <div class="ziara-panel" id="security">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> 7. Data Security</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    <div style="background:#faf6f2;border:1px solid var(--line);border-radius:12px;padding:22px 18px;text-align:center;">
                        <i class="fa-solid fa-lock" aria-hidden="true" style="font-size:30px;color:var(--maroon);margin-bottom:12px;"></i>
                        <h4 style="margin:0 0 6px;">SSL Encryption</h4>
                        <p style="margin:0;font-size:13px;color:var(--muted);">All data transmitted is encrypted using SSL/TLS</p>
                    </div>
                    <div style="background:#faf6f2;border:1px solid var(--line);border-radius:12px;padding:22px 18px;text-align:center;">
                        <i class="fa-solid fa-server" aria-hidden="true" style="font-size:30px;color:var(--maroon);margin-bottom:12px;"></i>
                        <h4 style="margin:0 0 6px;">Secure Servers</h4>
                        <p style="margin:0;font-size:13px;color:var(--muted);">Data stored on secure, monitored servers</p>
                    </div>
                    <div style="background:#faf6f2;border:1px solid var(--line);border-radius:12px;padding:22px 18px;text-align:center;">
                        <i class="fa-solid fa-user-check" aria-hidden="true" style="font-size:30px;color:var(--maroon);margin-bottom:12px;"></i>
                        <h4 style="margin:0 0 6px;">Access Controls</h4>
                        <p style="margin:0;font-size:13px;color:var(--muted);">Limited access to authorized personnel only</p>
                    </div>
                </div>
            </div>

            {{-- 8. Contact --}}
            <div class="ziara-panel" id="contact">
                <h3 class="ziara-panel__title"><i class="fa-solid fa-envelope" aria-hidden="true"></i> 8. Contact Information</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                    <div>
                        <h4 style="margin:0 0 10px;color:var(--maroon-deep);">Data Protection Officer</h4>
                        <p style="margin:0 0 8px;font-size:14px;">Email: <a href="mailto:dpo@gnc.com" style="color:var(--maroon);font-weight:600;">dpo@gnc.com</a></p>
                        <p style="margin:0 0 8px;font-size:14px;">Phone: +254 700 123 456</p>
                        <p style="margin:0;font-size:14px;">Address: Nairobi, Kenya</p>
                    </div>
                    <div>
                        <h4 style="margin:0 0 10px;color:var(--maroon-deep);">Supervisory Authority</h4>
                        <p style="margin:0 0 8px;font-size:14px;">Data Protection Commissioner</p>
                        <p style="margin:0 0 8px;font-size:14px;">Kenya</p>
                        <p style="margin:0;font-size:14px;">For complaints about data processing</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection