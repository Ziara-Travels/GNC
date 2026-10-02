{{-- Ziara Travels header (clone of ziaratravels.co.ke). Styled by css/ziara.css + css/ziara-theme.css. --}}

<div class="zt-topbar" role="complementary" aria-label="Contact information">
  <div class="zt-topbar__inner">
    <div class="zt-topbar__contacts">
      <span>
        <i class="fa-solid fa-phone" aria-hidden="true"></i>
        <a href="tel:+254713896265" aria-label="Call us">+254 713 896 265</a>
      </span>
      <span>
        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
        <a href="mailto:info@ziaratravels.co.ke" aria-label="Email us">info@ziaratravels.co.ke</a>
      </span>
      <span>
        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
        Westlands Commercial Centre, Old Buildings, 2nd Floor, Ring Road Parklands, P.O. Box 62010-00200
      </span>
    </div>
    <div class="zt-topbar__social" aria-label="Social media links">
      <a href="https://www.facebook.com/ziaratravels/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
      </a>
      <a href="#" aria-label="X / Twitter">
        <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
      </a>
      <a href="#" aria-label="Instagram">
        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
      </a>
      <a href="#" aria-label="WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</div>

<header class="zt-header" id="zt-header">
  <div class="zt-header__inner">
    <a href="{{ route('home') }}" class="zt-logo" aria-label="Ziara Travels — Home">
      <img src="{{ asset('images/ziara/ziara.jpg') }}" alt="Ziara Travels" width="2285" height="1022" decoding="async">
    </a>

    <nav class="zt-nav" id="zt-nav" aria-label="Primary navigation">
      <ul class="zt-nav__list" role="menubar">
        <li class="zt-nav__item" role="none">
          <a href="{{ route('home') }}" class="zt-nav__link" role="menuitem">Home</a>
        </li>
        <li class="zt-nav__item" role="none">
          <a href="{{ route('aboutus') }}" class="zt-nav__link" role="menuitem">About Us</a>
        </li>
        <li class="zt-nav__item" role="none">
          <a href="{{ route('tours.category', 'kenya-safaris') }}" class="zt-nav__link" role="menuitem">Kenyan Packages</a>
        </li>
        <li class="zt-nav__item has-dd" role="none">
          <a href="{{ route('tours.category', 'kenya-safaris') }}" class="zt-nav__link" role="menuitem" aria-haspopup="true" aria-expanded="false">
            Safaris
            <i class="fa-solid fa-chevron-down zt-chev" aria-hidden="true"></i>
          </a>
          <ul class="zt-dropdown" role="menu" aria-label="Safaris submenu">
            <li role="none"><a href="{{ route('tours.category', 'kenya-safaris') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>Kenya Safaris</a></li>
            <li role="none"><a href="{{ route('tours.category', 'international-packages') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>Uganda Safaris</a></li>
            <li role="none"><a href="{{ route('tours.category', 'international-packages') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>Tanzania Safaris</a></li>
            <li role="none"><a href="{{ route('tours.category', 'international-packages') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>Rwanda Safaris</a></li>
          </ul>
        </li>
        <li class="zt-nav__item has-dd" role="none">
          <a href="{{ route('tours.category', 'international-packages') }}" class="zt-nav__link" role="menuitem" aria-haspopup="true" aria-expanded="false">
            International Packages
            <i class="fa-solid fa-chevron-down zt-chev" aria-hidden="true"></i>
          </a>
          <ul class="zt-dropdown" role="menu" aria-label="International Packages submenu">
            <li role="none"><a href="{{ route('tours.category', 'dubai-package') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>Dubai Packages</a></li>
            <li role="none"><a href="{{ route('tours.category', 'international-packages') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>South Africa Packages</a></li>
            <li role="none"><a href="{{ route('tours.category', 'bangkok-packages') }}" role="menuitem"><i class="fa-solid fa-circle" aria-hidden="true"></i>Bangkok Packages</a></li>
          </ul>
        </li>
        <li class="zt-nav__item" role="none">
          <a href="{{ route('news') }}" class="zt-nav__link" role="menuitem">Articles</a>
        </li>
        <li class="zt-nav__item" role="none">
          <a href="{{ route('contactus') }}" class="zt-nav__link" role="menuitem">Contact Us</a>
        </li>
      </ul>
    </nav>

    <div class="zt-actions">
      <div class="zt-auth">
        @guest
          <a href="{{ route('login') }}" class="zt-auth__link"><i class="fa-solid fa-user" aria-hidden="true"></i>Sign In</a>
          <a href="{{ route('register') }}" class="zt-auth__link zt-auth__cta">Register</a>
        @else
          <a href="{{ route('dashboard') }}" class="zt-auth__link"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i>Dashboard</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" aria-label="Logout"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>Logout</button>
          </form>
        @endguest
      </div>
      <a href="{{ route('flights.public.search') }}" class="zt-book" aria-label="Book a tour">
        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
        <span class="zt-btn-text">Book A Tour</span>
      </a>
      <button class="zt-toggle" id="zt-toggle" type="button" aria-label="Toggle navigation menu" aria-controls="zt-nav" aria-expanded="false">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
      </button>
    </div>
  </div>
</header>

<button class="zt-scroll-top" id="zt-scroll-top" aria-label="Scroll to top" type="button">
  <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
</button>

<script>
(function () {
  'use strict';
  var header    = document.getElementById('zt-header');
  var nav       = document.getElementById('zt-nav');
  var toggle    = document.getElementById('zt-toggle');
  var scrollBtn = document.getElementById('zt-scroll-top');
  var dropItems = document.querySelectorAll('.zt-nav__item.has-dd');
  if (!header || !nav || !toggle || !scrollBtn) return;

  (function setActive () {
    function norm (p) {
      p = (p || '/').toLowerCase();
      if (p.length > 1 && p.charAt(p.length - 1) === '/') p = p.slice(0, -1);
      return p || '/';
    }
    var current = norm(window.location.pathname);
    var best = null, bestLen = -1;
    nav.querySelectorAll('a[href]').forEach(function (a) {
      var url;
      try { url = new URL(a.getAttribute('href'), window.location.href); }
      catch (e) { return; }
      if (url.host !== window.location.host) return;
      var path = norm(url.pathname);
      var match = path === '/'
        ? current === '/'
        : (current === path || current.indexOf(path + '/') === 0);
      if (match && path.length > bestLen) { best = a; bestLen = path.length; }
    });
    if (best) {
      var item = best.closest('.zt-nav__item');
      if (item) {
        item.classList.add('is-active');
        var topLink = item.querySelector('.zt-nav__link');
        if (topLink) topLink.setAttribute('aria-current', 'page');
      }
    }
  })();

  function onScroll () {
    var y = window.scrollY;
    if (header) header.classList.toggle('scrolled', y > 40);
    scrollBtn.classList.toggle('visible', y > 320);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  toggle.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
    toggle.querySelector('i').className = open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
  });

  dropItems.forEach(function (item) {
    var link = item.querySelector('.zt-nav__link');
    link.addEventListener('click', function (e) {
      if (window.innerWidth > 992) return;
      e.preventDefault();
      var wasOpen = item.classList.contains('is-open');
      dropItems.forEach(function (other) {
        other.classList.remove('is-open');
        var ol = other.querySelector('.zt-nav__link');
        if (ol) ol.setAttribute('aria-expanded', 'false');
      });
      if (!wasOpen) {
        item.classList.add('is-open');
        link.setAttribute('aria-expanded', 'true');
      }
    });
  });

  document.addEventListener('click', function (e) {
    if (!header.contains(e.target) && nav.classList.contains('is-open')) {
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.querySelector('i').className = 'fa-solid fa-bars';
    }
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > 992) {
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.querySelector('i').className = 'fa-solid fa-bars';
      dropItems.forEach(function (item) { item.classList.remove('is-open'); });
    }
  });

  scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    nav.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.querySelector('i').className = 'fa-solid fa-bars';
    dropItems.forEach(function (item) { item.classList.remove('is-open'); });
  });
})();
</script>