{{-- FOOTER - zf-footer (captured). --}}
<footer class="zf-footer" role="contentinfo">

  <!-- Travel-themed background watermarks (decorative) -->
  <div class="zf-decor" aria-hidden="true">
    <span class="zf-decor__path"></span>
    <i class="fa-solid fa-plane-up zf-decor--plane"></i>
    <i class="fa-solid fa-earth-africa zf-decor--globe"></i>
    <i class="fa-solid fa-compass zf-decor--compass"></i>
    <i class="fa-solid fa-umbrella-beach zf-decor--beach"></i>
    <i class="fa-solid fa-map-location-dot zf-decor--map"></i>
  </div>

  <div class="zf-main">

    <!-- Brand -->
    <div class="zf-brand">
      <a href="/" class="zf-brand__logo" aria-label="Ziara Travels Home">
        <img src="/images/ziara/ziara.jpg" alt="Ziara Travels">
      </a>
      <p class="zf-brand__text">
        Professional travel consultancy for local and international tourists. Flights, safaris,
        holiday packages and more, delivered with integrity and excellence for over a decade.
      </p>
      <div class="zf-social" aria-label="Social media links">
        <a href="https://www.facebook.com/ziaratravels/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" aria-label="X / Twitter"><i class="fa-brands fa-x-twitter"></i></a>
        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
      </div>
    </div>

    <!-- Quick links -->
    <div class="zf-col">
      <h3 class="zf-col__title">Explore</h3>
      <ul class="zf-links">
        <li><a href="{{ route('home') }}"><i class="fa-solid fa-chevron-right"></i>Home</a></li>
        <li><a href="{{ route('aboutus') }}"><i class="fa-solid fa-chevron-right"></i>About Us</a></li>
        <li><a href="{{ route('special-offers') }}"><i class="fa-solid fa-chevron-right"></i>Kenyan Packages</a></li>
        <li><a href="{{ route('news') }}"><i class="fa-solid fa-chevron-right"></i>Articles</a></li>
        <li><a href="{{ route('contactus') }}"><i class="fa-solid fa-chevron-right"></i>Contact Us</a></li>
      </ul>
    </div>

    <!-- Packages -->
    <div class="zf-col">
      <h3 class="zf-col__title">Packages</h3>
      <ul class="zf-links">
        <li><a href="{{ route('special-offers') }}"><i class="fa-solid fa-chevron-right"></i>Kenya Safaris</a></li>
        <li><a href="{{ route('special-offers') }}"><i class="fa-solid fa-chevron-right"></i>Tanzania Safaris</a></li>
        <li><a href="{{ route('special-offers') }}"><i class="fa-solid fa-chevron-right"></i>Dubai Packages</a></li>
        <li><a href="{{ route('special-offers') }}"><i class="fa-solid fa-chevron-right"></i>South Africa</a></li>
        <li><a href="{{ route('special-offers') }}"><i class="fa-solid fa-chevron-right"></i>Bangkok Packages</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div class="zf-col">
      <h3 class="zf-col__title">Get in Touch</h3>
      <ul class="zf-contact">
        <li>
          <i class="fa-solid fa-location-dot"></i>
          <span>Westlands Commercial Centre,<br>Old Buildings, 2nd Floor,<br>Ring Road Parklands,<br>P.O Box 62010 – 00200, Nairobi</span>
        </li>
        <li>
          <i class="fa-solid fa-phone"></i>
          <a href="tel:+254713896265">+254 713 896 265</a>
        </li>
        <li>
          <i class="fa-solid fa-envelope"></i>
          <a href="mailto:info@ziaratravels.co.ke">info@ziaratravels.co.ke</a>
        </li>
      </ul>
    </div>

  </div>

  <!-- Bottom bar -->
  <div class="zf-bottom">
    <div class="zf-bottom__inner">
      <span>&copy; <span class="zf-year">2026</span> Ziara Travels. All rights reserved.</span>
      <nav class="zf-bottom__legal" aria-label="Footer links">
        <a href="{{ route('flights.public.search') }}">Come Travel With Us</a>
      </nav>
    </div>
  </div>

</footer>
<script>
(function () {
  'use strict';
  if (window.__zfFooterInit) return;   // avoid double-run if block renders twice
  window.__zfFooterInit = true;
  // Update every copyright year on the page (supports multiple footers)
  document.querySelectorAll('.zf-footer #zf-year, .zf-footer .zf-year')
    .forEach(function (el) { el.textContent = new Date().getFullYear(); });
})();