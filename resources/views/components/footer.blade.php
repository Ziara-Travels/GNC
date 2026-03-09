<footer class="footer-advanced bg-dark text-white pt-5">
    <!-- Top Footer Section -->
    <div class="footer-top pb-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="footer-logo mb-3" style="height: 60px;">
                    <div class="social-icons d-flex justify-content-center gap-3">
                        <a href="#" class="social-icon-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon-link">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-icon-link">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- About Company Section - Condensed -->
                <div class="col-lg-3">
                    <h5 class="footer-title">About Us</h5>
                    <p class="text-white-50 small mb-3">Your premier travel partner since 2010, providing exceptional travel experiences.</p>
                    <div class="company-stats d-flex justify-content-between mb-3">
                        <div class="stat-item text-center">
                            <h6 class="text-primary mb-0">15K+</h6>
                            <small class="text-white-50">Clients</small>
                        </div>
                        <div class="stat-item text-center">
                            <h6 class="text-primary mb-0">100+</h6>
                            <small class="text-white-50">Destinations</small>
                        </div>
                        <div class="stat-item text-center">
                            <h6 class="text-primary mb-0">24/7</h6>
                            <small class="text-white-50">Support</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Links - Condensed -->
                <div class="col-lg-3">
                    <h5 class="footer-title">Quick Links</h5>
                    <div class="row">
                        <div class="col-6">
                            <ul class="footer-links small">
                                <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right fa-xs"></i> Home</a></li>
                                <li><a href="{{ route('aboutus') }}"><i class="fas fa-chevron-right fa-xs"></i> About</a></li>
                                <li><a href="{{ route('services') }}"><i class="fas fa-chevron-right fa-xs"></i> Services</a></li>
                                <li><a href="{{ route('contactus') }}"><i class="fas fa-chevron-right fa-xs"></i> Contact</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="footer-links small">
                                <li><a href="{{ route('what-we-do') }}"><i class="fas fa-chevron-right fa-xs"></i> What We Do</a></li>
                                <li><a href="{{ route('special-offers') }}"><i class="fas fa-chevron-right fa-xs"></i> Packages</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right fa-xs"></i> Blog</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Services - Condensed -->
                <div class="col-lg-3">
                    <h5 class="footer-title">Services</h5>
                    <div class="row">
                        <div class="col-6">
                            <ul class="footer-links small">
                                <li><a href="{{ route('search-flights') }}"><i class="fas fa-plane fa-xs"></i> Flights</a></li>
                                <li><a href="{{ route('services') }}"><i class="fas fa-hotel fa-xs"></i> Hotels</a></li>
                                <li><a href="{{ route('special-offers') }}"><i class="fas fa-suitcase fa-xs"></i> Packages</a></li>
                                <li><a href="{{ route('services') }}"><i class="fas fa-car fa-xs"></i> Cars</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="footer-links small">
                                <li><a href="{{ route('services') }}"><i class="fas fa-ship fa-xs"></i> Cruises</a></li>
                                <li><a href="{{ route('services') }}"><i class="fas fa-hiking fa-xs"></i> Activities</a></li>
                                <li><a href="{{ route('travel-insurance') }}"><i class="fas fa-shield-alt fa-xs"></i> Insurance</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact & Newsletter - Condensed -->
                <div class="col-lg-3">
                    <h5 class="footer-title">Contact Us</h5>
                    <ul class="list-unstyled small mb-3">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt fa-fw text-primary"></i>
                            123 Travel Plaza, Westlands, Nairobi
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone fa-fw text-primary"></i>
                            +254 123 456 789
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope fa-fw text-primary"></i>
                            info@travelcompany.com
                        </li>
                    </ul>
                    <form action="{{ route('contactus.submit') }}" method="POST" class="newsletter-form">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input type="email" name="email" class="form-control" placeholder="Subscribe to newsletter" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Footer Section - Payment Methods -->
    <div class="footer-middle py-4 bg-dark-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h6 class="mb-0">Secure Payment Methods</h6>
                </div>
                <div class="col-md-6">
                    <div class="payment-methods d-flex justify-content-center justify-content-md-end gap-3">
                        <i class="fab fa-cc-visa fa-2x"></i>
                        <i class="fab fa-cc-mastercard fa-2x"></i>
                        <i class="fab fa-cc-amex fa-2x"></i>
                        <i class="fab fa-cc-paypal fa-2x"></i>
                        <i class="fab fa-cc-discover fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer Section -->
    <div class="footer-bottom py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; {{ date('Y') }} Travel Company. All rights reserved.</p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-bottom-links d-flex justify-content-center justify-content-md-end gap-3 mb-0">
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('cookie-policy') }}">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>