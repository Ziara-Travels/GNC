<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ziara App') }}</title>
    @php
        $seoHelper = new App\Helpers\SEOHelper();
        $currentPage = request()->route()->getName() ?? 'home';
        $pageData = $pageData ?? [];
    @endphp

    {{-- SEO Meta Tags --}}
    {!! $seoHelper->generateSEOTags($currentPage, $pageData) !!}


    {!! $seoHelper->generateResourceHints() !!}

    {{-- OpenSearch --}}
    {{-- <link rel="search" type="application/opensearchdescription+xml" title="Ziara Travel" href="{{ route('opensearch') }}"> --}}
    {{-- Structured Data --}}
    {!! $seoHelper->generateStructuredData($currentPage, $pageData) !!}
    <!-- Fonts -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Script+MT+Bold&family=Jost:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Swiper CSS for carousels -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js-bootstrap-css/1.2.1/typeahead.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet"
        href="{{ asset('build/css/custom.css') }}?v={{ (file_exists(public_path('build/css/custom.css')) ? filemtime(public_path('build/css/custom.css')) : 0) }}">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

    <!-- AOS CSS for animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link href="{{ asset('css/flight-search.css') }}" rel="stylesheet">

    <!-- Ziara Travels site-clone theme (captured and localized from ziaratravels.co.ke) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ziara.css') }}?v={{ (file_exists(public_path('css/ziara.css')) ? filemtime(public_path('css/ziara.css')) : 0) }}">
    <link rel="stylesheet" href="{{ asset('css/ziara-pages.css') }}?v={{ (file_exists(public_path('css/ziara-pages.css')) ? filemtime(public_path('css/ziara-pages.css')) : 0) }}">
    <link rel="stylesheet" href="{{ asset('css/ziara-tours.css') }}?v={{ (file_exists(public_path('css/ziara-tours.css')) ? filemtime(public_path('css/ziara-tours.css')) : 0) }}">
    <link rel="stylesheet" href="{{ asset('css/ziara-theme.css') }}?v={{ (file_exists(public_path('css/ziara-theme.css')) ? filemtime(public_path('css/ziara-theme.css')) : 0) }}">

    <style>

         /* Custom animations for floating buttons */
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            70% { transform: scale(1.1); opacity: 0; }
            100% { transform: scale(1); opacity: 0; }
        }

        @keyframes flightTakeoff {
            0% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-10px) rotate(-20deg); }
            100% { transform: translateY(0) rotate(0); }
        }
        /* Add flight deals animations */  
  
  @keyframes planeFly {
            0% { transform: translateY(0) rotate(-10deg); }
            50% { transform: translateY(-5px) rotate(-8deg); }
            100% { transform: translateY(0) rotate(-10deg); }
        }
        
        @keyframes fly {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
        }

        /* Animation Styles */
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.3; transform: scale(1) rotate(-15deg); }
                50% { opacity: 0.7; transform: scale(1.1) rotate(-10deg); }
            }
            
            .bottom-hero .btn:hover {
                transform: translateY(-3px);
            }
            
            .bottom-hero .btn-light:hover {
                background-color: #f8f9fa;
                box-shadow: 0 8px 25px rgba(255,255,255,0.3);
            }
            
            .bottom-hero .btn-outline-light:hover {
                background-color: #ffffff;
                color: #212529;
                }

        /* FAQ Section Styles */
        .faq-accordion .accordion-item {
            border: 1px solid rgba(0,0,0,.125);
            margin-bottom: 0;
            border-radius: 0.25rem;
        }

        .faq-accordion .accordion-button {
            background-color: #fff;
            color: #212529;
            padding: 1rem 1.25rem;
            font-weight: 500;
            border: none;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            border-radius: 0.25rem;
        }

        .faq-accordion .accordion-button:not(.collapsed) {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.04);
            box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
        }

        .faq-accordion .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0,0,0,.125);
        }

        .faq-accordion .accordion-button::after {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            margin-left: auto;
            content: "";
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-size: 1.25rem;
            transition: transform .2s ease-in-out;
        }

        .faq-accordion .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230d6efd'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            transform: rotate(-180deg);
        }

        .faq-accordion .accordion-body {
            padding: 1rem 1.25rem;
            color: #212529;
            line-height: 1.6;
            background-color: #fff;
        }

        .faq-question {
            font-weight: 500;
        }

        @media (max-width: 767.98px) {
            .faq-accordion .accordion-button {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }
            
            .faq-accordion .accordion-body {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }
        @keyframes planeFly {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-5px) rotate(3deg);
            }
        }

         .faq-question {
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        padding: 15px 0;
        font-weight: 500;
        color: #333;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
        }

        .faq-question:hover {
            color: #007bff;
        }

        .faq-question[aria-expanded="true"] {
            color: #007bff;
            font-weight: 600;
        }

        .faq-question[aria-expanded="true"] .faq-icon {
            transform: rotate(180deg);
        }

        .faq-icon {
            transition: transform 0.3s ease;
            font-size: 14px;
            color: #6c757d;
        }

        .faq-item {
            margin-bottom: 10px;
        }

        .collapse .text-muted {
            color: #000 !important;
            line-height: 1.6;
            font-size: 14px;
        }

        .collapse {
            transition: all 0.3s ease;
        }

        @keyframes fly {
            0% {
                transform: translateX(0) translateY(0) rotate(0);
            }

            25% {
                transform: translateX(5px) translateY(-5px) rotate(5deg);
            }

            50% {
                transform: translateX(10px) translateY(0px) rotate(0deg);
            }

            75% {
                transform: translateX(5px) translateY(5px) rotate(-5deg);
            }

            100% {
                transform: translateX(0) translateY(0) rotate(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>


    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('components.navbar')

        <main>
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    <!-- Scripts -->
    <script>
        // Initialize Lucide icons when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>


    {{-- Analytics Scripts --}}
    @if (config('app.env') === 'production')
        {!! $seoHelper->generateAnalyticsScripts() !!}
    @endif

    <!-- Essential Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('js/flight-search.js') }}"></script>

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 800,
            offset: 100,
            once: true
        });
    </script>

    <!-- Navbar Scroll Effect -->
    <script>
        $(window).scroll(function() {
            if ($(window).scrollTop() > 50) {
                $('.navbar').addClass('navbar-scrolled');
            } else {
                $('.navbar').removeClass('navbar-scrolled');
            }
        });
    </script>

    <script>
    // Debug script loading
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking airport autocomplete setup...');
            
            // Check if the necessary functions exist
            if (typeof setupImprovedAirportAutocomplete === 'function') {
                console.log('Airport autocomplete function found.');
                
                // Initialize main functionality
                setupImprovedAirportAutocomplete('rt-origin', 'rt-destination', 'fromSuggestions', 'toSuggestions', 'from_iata', 'to_iata');
                setupImprovedAirportAutocomplete('ow-origin', 'ow-destination', 'owFromSuggestions', 'owToSuggestions', 'ow-from-iata', 'ow-to-iata');
                
                // Check if elements exist
                console.log('From input exists:', document.getElementById('rt-origin') !== null);
                console.log('To input exists:', document.getElementById('rt-destination') !== null);
                console.log('From suggestions container exists:', document.getElementById('fromSuggestions') !== null);
                console.log('To suggestions container exists:', document.getElementById('toSuggestions') !== null);
            } else {
                console.error('Airport autocomplete function not found. Check if flight-search.js is loaded properly.');
            }
        });
    </script>

    <!-- Animation on Scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-up');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.1
            });

            fadeElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all search forms and add submission handler
    const searchForms = document.querySelectorAll('.tab-pane form');
    
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const buttonText = submitButton.querySelector('span:not(.spinner-border)');
            const spinner = submitButton.querySelector('.spinner-border');
            
            // Store original text
            const originalText = buttonText.textContent;
            
            // Disable button and show spinner
            submitButton.disabled = true;
            spinner.classList.remove('d-none');
            buttonText.textContent = 'Searching...';
            
            // Make sure the form has valid IATA codes
            const fromIataField = this.querySelector('input[name="from_iata"], input[name="from_iata[]"]');
            const toIataField = this.querySelector('input[name="to_iata"], input[name="to_iata[]"]');
            
            if (!fromIataField.value || !toIataField.value) {
                e.preventDefault();
                alert('Please select valid airports from the suggestions.');
                
                // Reset button state
                submitButton.disabled = false;
                spinner.classList.add('d-none');
                buttonText.textContent = originalText;
                return false;
            }
            
            // Add a parameter to indicate the search is happening
            const redirectParam = document.createElement('input');
            redirectParam.type = 'hidden';
            redirectParam.name = 'redirect_to_results';
            redirectParam.value = 'true';
            this.appendChild(redirectParam);
            
            // Let the form submit normally now
            return true;
        });
    });
    
    // Set default dates for all date fields
    const departDateFields = document.querySelectorAll('input[name="date"], input[name="date[]"]');
    const returnDateFields = document.querySelectorAll('input[name="return_date"]');
    
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const nextWeek = new Date(today);
    nextWeek.setDate(nextWeek.getDate() + 7);
    
    const formatDate = date => {
        return date.toISOString().split('T')[0];
    };
    
    departDateFields.forEach(field => {
        field.min = formatDate(tomorrow);
        field.value = formatDate(tomorrow);
    });
    
    returnDateFields.forEach(field => {
        field.min = formatDate(tomorrow);
        field.value = formatDate(nextWeek);
    });
});

// Function to add flight segment for multi-city
function addFlightSegment() {
    const container = document.getElementById('multi-city-flights');
    const segmentCount = container.querySelectorAll('.flight-segment').length;
    
    if (segmentCount >= 5) {
        alert('Maximum 5 flight segments allowed.');
        return;
    }
    
    const newSegment = document.createElement('div');
    newSegment.className = 'flight-segment mb-3 p-3 border rounded';
    newSegment.style.backgroundColor = '#f8f9fa';
    
    newSegment.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <h6 class="mb-0 text-primary">Flight ${segmentCount + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-segment" onclick="removeFlightSegment(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                <div class="position-relative">
                    <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                    <input type="text" 
                        class="form-control border-2 ps-4 airport-autocomplete" 
                        name="from[]" 
                        placeholder="Country, city or airport"
                        style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                        required>
                    <input type="hidden" name="from_iata[]">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                <div class="position-relative">
                    <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                    <input type="text" 
                        class="form-control border-2 ps-4 airport-autocomplete" 
                        name="to[]" 
                        placeholder="Country, city or airport"
                        style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                        required>
                    <input type="hidden" name="to_iata[]">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Date</label>
                <div class="position-relative">
                    <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                    <input type="date" 
                        class="form-control border-2 ps-4" 
                        name="date[]"
                        style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                        required>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newSegment);
    
    // Set default date for the new segment
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const dateField = newSegment.querySelector('input[name="date[]"]');
    dateField.min = tomorrow.toISOString().split('T')[0];
    dateField.value = tomorrow.toISOString().split('T')[0];
    
    // Initialize airport autocomplete for new fields
    setupAirportAutocomplete();
}

// Function to remove flight segment
function removeFlightSegment(button) {
    const segment = button.closest('.flight-segment');
    segment.remove();
}
</script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const homeNewsletterForm = document.getElementById('homeNewsletterForm');
            
            if (homeNewsletterForm) {
                homeNewsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitButton = homeNewsletterForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i><span>Processing...</span>';
                    submitButton.disabled = true;
                    
                    const name = document.getElementById('homeNewsletterName').value;
                    const email = document.getElementById('homeNewsletterEmail').value;
                    
                    // Send subscription request via fetch
                    fetch('{{ route("newsletter.subscribe") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            name: name,
                            email: email,
                            source: 'homepage'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        
                        if (data.success) {
                            // Show success toast
                            Toastify({
                                text: data.message,
                                duration: 5000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#10B981",
                                style: {
                                    background: "linear-gradient(to right, #10B981, #059669)",
                                    boxShadow: "0 3px 6px rgba(0,0,0,0.16)",
                                    borderRadius: "5px",
                                    fontSize: "16px",
                                    padding: "12px 20px"
                                },
                                stopOnFocus: true
                            }).showToast();
                            
                            // Reset form
                            homeNewsletterForm.reset();
                        } else {
                            // Show error toast
                            Toastify({
                                text: data.message || 'Something went wrong. Please try again.',
                                duration: 5000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#EF4444",
                                style: {
                                    background: "linear-gradient(to right, #EF4444, #DC2626)",
                                    boxShadow: "0 3px 6px rgba(0,0,0,0.16)",
                                    borderRadius: "5px",
                                    fontSize: "16px",
                                    padding: "12px 20px"
                                },
                                stopOnFocus: true
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        // Reset button
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        
                        // Show error toast
                        Toastify({
                            text: 'Network error. Please try again later.',
                            duration: 5000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#EF4444",
                            style: {
                                background: "linear-gradient(to right, #EF4444, #DC2626)",
                                boxShadow: "0 3px 6px rgba(0,0,0,0.16)",
                                borderRadius: "5px",
                                fontSize: "16px",
                                padding: "12px 20px"
                            },
                            stopOnFocus: true
                        }).showToast();
                        
                        console.error('Newsletter subscription error:', error);
                    });
                });
            }
        });
    </script>

    <!-- Initialize Swiper Carousels -->
    <script>
        const swipers = document.querySelectorAll('.swiper-container');
        swipers.forEach(swiper => {
            new Swiper(swiper, {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    }
                }
            });
        });
    </script>

    <script>
        // AI Trip Planner functionality
        document.getElementById('aiTripPlannerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('/api/ai/trip-plan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    displayTripPlan(result.data);
                    document.getElementById('aiTripResults').style.display = 'block';
                    document.getElementById('aiTripResults').scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    alert('Failed to generate trip plan. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        function displayTripPlan(data) {
            const container = document.getElementById('tripPlanContent');

            if (data.status === 'fallback') {
                container.innerHTML = generateFallbackTripPlan(data.trip_plan);
            } else {
                container.innerHTML = generateTripPlanHTML(data.trip_plan);
            }
        }

        function generateTripPlanHTML(tripPlan) {
            return `
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plane me-2"></i>Flight Recommendations</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.flights?.map(flight => `<li class="mb-2"><i class="fas fa-check text-success me-2"></i>${flight}</li>`).join('') || '<li>No flight suggestions available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Hotel Suggestions</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.hotels?.map(hotel => `<li class="mb-2"><i class="fas fa-check text-success me-2"></i>${hotel}</li>`).join('') || '<li>No hotel suggestions available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Daily Itinerary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.itinerary?.map(day => `<li class="mb-2"><i class="fas fa-calendar-day text-info me-2"></i>${day}</li>`).join('') || '<li>No itinerary available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Budget Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.budget?.map(item => `<li class="mb-2"><i class="fas fa-money-bill text-warning me-2"></i>${item}</li>`).join('') || '<li>No budget breakdown available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Travel Tips</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Transportation</h6>
                                <ul class="list-unstyled">
                                    ${tripPlan.transportation?.map(tip => `<li class="mb-1"><i class="fas fa-arrow-right text-muted me-2"></i>${tip}</li>`).join('') || '<li>No transportation info available</li>'}
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>General Tips</h6>
                                <ul class="list-unstyled">
                                    ${tripPlan.tips?.map(tip => `<li class="mb-1"><i class="fas fa-arrow-right text-muted me-2"></i>${tip}</li>`).join('') || '<li>No tips available</li>'}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
        }

        function generateFallbackTripPlan(tripPlan) {
            return generateTripPlanHTML(tripPlan);
        }

        function downloadTripPlan() {
            // Simple implementation - in production, you'd generate a proper PDF
            const content = document.getElementById('tripPlanContent').innerText;
            const blob = new Blob([content], {
                type: 'text/plain'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'my-trip-plan.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // AI Chatbot functionality
        let currentChatSession = null;

        document.getElementById('chatForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const input = document.getElementById('chatInput');
            const message = input.value.trim();

            if (!message) return;

            // Add user message to chat
            addMessageToChat('user', message);
            input.value = '';

            // Show typing indicator
            showTypingIndicator();

            try {
                const response = await fetch('/api/ai/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        message: message,
                        session_id: currentChatSession,
                        user_id: null // Will use auth user if logged in
                    })
                });

                const result = await response.json();

                if (result.success) {
                    currentChatSession = result.data.session_id;

                    // Remove typing indicator
                    removeTypingIndicator();

                    // Add assistant response
                    addMessageToChat('assistant', result.data.response);

                    // Update quick replies
                    updateQuickReplies(result.data.quick_replies);
                } else {
                    removeTypingIndicator();
                    addMessageToChat('assistant', 'Sorry, I encountered an error. Please try again.');
                }
            } catch (error) {
                console.error('Chat error:', error);
                removeTypingIndicator();
                addMessageToChat('assistant', 'Sorry, I\'m having trouble connecting. Please try again later.');
            }
        });

        function addMessageToChat(role, message) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${role} mb-3`;

            if (role === 'user') {
                messageDiv.innerHTML = `
            <div class="d-flex align-items-start justify-content-end">
                <div class="chat-bubble bg-primary text-white rounded-3 p-3" style="max-width: 80%;">
                    <p class="mb-0">${message}</p>
                </div>
                <div class="chat-avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center ms-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        `;
            } else {
                messageDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="chat-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chat-bubble bg-light rounded-3 p-3" style="max-width: 80%;">
                    <p class="mb-0">${message}</p>
                </div>
            </div>
        `;
            }

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function showTypingIndicator() {
            const messagesContainer = document.getElementById('chatMessages');
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typingIndicator';
            typingDiv.className = 'chat-message assistant mb-3';
            typingDiv.innerHTML = `
        <div class="d-flex align-items-start">
            <div class="chat-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chat-bubble bg-light rounded-3 p-3" style="max-width: 80%;">
                <div class="typing-animation">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    `;

            messagesContainer.appendChild(typingDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function sendQuickReply(message) {
            document.getElementById('chatInput').value = message;
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }

        function updateQuickReplies(replies) {
            const container = document.getElementById('quickReplies');
            if (replies && replies.length > 0) {
                container.innerHTML = `
            <div class="d-flex flex-wrap gap-2">
                ${replies.map(reply => `
                            <button class="btn btn-outline-primary btn-sm" onclick="sendQuickReply('${reply}')">
                                ${reply}
                            </button>
                        `).join('')}
            </div>
        `;
            }
        }
    </script>

   <script>
document.addEventListener('DOMContentLoaded', function() {
    // Fix the price prediction form error by checking if element exists
    const pricePredictionForm = document.getElementById('pricePredictionForm');
    if (pricePredictionForm) {
        pricePredictionForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            data.trip_type = 'one_way'; // Default

            // Show loading
            const resultsDiv = document.getElementById('pricePredictionResults');
            if (!resultsDiv) {
                console.error('pricePredictionResults element not found');
                return;
            }

            resultsDiv.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="text-primary">Analyzing Price Trends...</h5>
            <p class="text-muted">Our AI is processing historical data and market patterns</p>
        </div>
    `;
            resultsDiv.style.display = 'block';

            fetch('/api/ai/price-prediction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const prediction = data.prediction;
                        resultsDiv.innerHTML = `
                <div class="price-prediction-result">
                    <div class="bg-primary text-white p-4 rounded-3 mb-4">
                        <h4 class="mb-2">📊 Price Prediction Results</h4>
                        <p class="mb-0 opacity-90">Based on AI analysis of historical data and market trends</p>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Current Status -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="urgency-badge urgency-${prediction.urgency_level} mb-2">
                                            ${prediction.urgency_level.toUpperCase()} URGENCY
                                        </div>
                                        <h5 class="card-title">Current Price</h5>
                                        <div class="price-display">$${prediction.current_price}</div>
                                    </div>
                                    <p class="text-muted small">${prediction.recommendation.reason}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Forecast -->
                        <div class="col-md-8">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Price Forecast</h5>
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <div class="forecast-item">
                                                <div class="forecast-period">Next Week</div>
                                                <div class="forecast-price">$${prediction.predicted_prices.next_week}</div>
                                                <div class="forecast-change ${prediction.price_changes.next_week >= 0 ? 'increase' : 'decrease'}">
                                                    ${prediction.price_changes.next_week >= 0 ? '+' : ''}${prediction.price_changes.next_week}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="forecast-item">
                                                <div class="forecast-period">2 Weeks</div>
                                                <div class="forecast-price">$${prediction.predicted_prices.next_two_weeks}</div>
                                                <div class="forecast-change ${prediction.price_changes.next_two_weeks >= 0 ? 'increase' : 'decrease'}">
                                                    ${prediction.price_changes.next_two_weeks >= 0 ? '+' : ''}${prediction.price_changes.next_two_weeks}%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="forecast-item">
                                                <div class="forecast-period">1 Month</div>
                                                <div class="forecast-price">$${prediction.predicted_prices.next_month}</div>
                                                <div class="forecast-change ${prediction.price_changes.next_month >= 0 ? 'increase' : 'decrease'}">
                                                    ${prediction.price_changes.next_month >= 0 ? '+' : ''}${prediction.price_changes.next_month}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recommendation -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm bg-light">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="card-title mb-2">💡 Our Recommendation</h5>
                                            <p class="mb-1"><strong>${prediction.recommendation.action}</strong></p>
                                            <p class="text-muted small mb-0">${prediction.recommendation.reason}</p>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Key factors:</strong> ${prediction.factors.join(', ')}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <div class="savings-potential">
                                                <div class="savings-amount">$${prediction.savings_potential}</div>
                                                <div class="savings-label">Potential Savings</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confidence Indicator -->
                    <div class="text-center mt-4">
                        <div class="confidence-meter">
                            <span class="me-2">Prediction Confidence:</span>
                            <div class="confidence-bar">
                                <div class="confidence-fill confidence-${prediction.confidence}" 
                                     style="width: ${prediction.confidence === 'high' ? '85%' : prediction.confidence === 'medium' ? '65%' : '45%'}"></div>
                            </div>
                            <span class="ms-2 confidence-text">${prediction.confidence.toUpperCase()}</span>
                        </div>
                    </div>
                </div>
            `;
                    } else {
                        resultsDiv.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle mb-2"></i>
                    <p>Unable to generate price prediction. Please try again later.</p>
                </div>
            `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="fas fa-wifi-off mb-2"></i>
                <p>Network error. Please check your connection and try again.</p>
            </div>
        `;
                });
        });
    }

    // Fix other similar potential issues
    const aiTripPlannerForm = document.getElementById('aiTripPlannerForm');
    if (aiTripPlannerForm) {
        // AI Trip Planner functionality
        aiTripPlannerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('/api/ai/trip-plan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    displayTripPlan(result.data);
                    const resultsDiv = document.getElementById('aiTripResults');
                    if (resultsDiv) {
                        resultsDiv.style.display = 'block';
                        resultsDiv.scrollIntoView({ behavior: 'smooth' });
                    }
                } else {
                    alert('Failed to generate trip plan. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    const chatForm = document.getElementById('chatForm');
    if (chatForm) {
        // AI Chatbot functionality
        let currentChatSession = null;

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const input = document.getElementById('chatInput');
            if (!input) return;
            
            const message = input.value.trim();

            if (!message) return;

            // Add user message to chat
            addMessageToChat('user', message);
            input.value = '';

            // Show typing indicator
            showTypingIndicator();

            try {
                const response = await fetch('/api/ai/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        message: message,
                        session_id: currentChatSession,
                        user_id: null // Will use auth user if logged in
                    })
                });

                const result = await response.json();

                if (result.success) {
                    currentChatSession = result.data.session_id;

                    // Remove typing indicator
                    removeTypingIndicator();

                    // Add assistant response
                    addMessageToChat('assistant', result.data.response);

                    // Update quick replies
                    if (result.data.quick_replies) {
                        updateQuickReplies(result.data.quick_replies);
                    }
                } else {
                    removeTypingIndicator();
                    addMessageToChat('assistant', 'Sorry, I encountered an error. Please try again.');
                }
            } catch (error) {
                console.error('Chat error:', error);
                removeTypingIndicator();
                addMessageToChat('assistant', 'Sorry, I\'m having trouble connecting. Please try again later.');
            }
        });
    }

    // Safe helper functions with null checks
    function addMessageToChat(role, message) {
        const messagesContainer = document.getElementById('chatMessages');
        if (!messagesContainer) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${role} mb-3`;

        if (role === 'user') {
            messageDiv.innerHTML = `
            <div class="d-flex align-items-start justify-content-end">
                <div class="chat-bubble bg-primary text-white rounded-3 p-3" style="max-width: 80%;">
                    <p class="mb-0">${message}</p>
                </div>
                <div class="chat-avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center ms-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        `;
        } else {
            messageDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="chat-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chat-bubble bg-light rounded-3 p-3" style="max-width: 80%;">
                    <p class="mb-0">${message}</p>
                </div>
            </div>
        `;
        }

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTypingIndicator() {
        const messagesContainer = document.getElementById('chatMessages');
        if (!messagesContainer) return;

        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.className = 'chat-message assistant mb-3';
        typingDiv.innerHTML = `
        <div class="d-flex align-items-start">
            <div class="chat-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chat-bubble bg-light rounded-3 p-3" style="max-width: 80%;">
                <div class="typing-animation">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    `;

        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) {
            indicator.remove();
        }
    }

    function updateQuickReplies(replies) {
        const container = document.getElementById('quickReplies');
        if (!container) return;

        if (replies && replies.length > 0) {
            container.innerHTML = `
            <div class="d-flex flex-wrap gap-2">
                ${replies.map(reply => `
                            <button class="btn btn-outline-primary btn-sm" onclick="sendQuickReply('${reply}')">
                                ${reply}
                            </button>
                        `).join('')}
            </div>
        `;
        }
    }

    // Global function for quick replies
    window.sendQuickReply = function(message) {
        const chatInput = document.getElementById('chatInput');
        if (chatInput) {
            chatInput.value = message;
            const chatForm = document.getElementById('chatForm');
            if (chatForm) {
                chatForm.dispatchEvent(new Event('submit'));
            }
        }
    };

    function displayTripPlan(data) {
        const container = document.getElementById('tripPlanContent');
        if (!container) return;

        if (data.status === 'fallback') {
            container.innerHTML = generateFallbackTripPlan(data.trip_plan);
        } else {
            container.innerHTML = generateTripPlanHTML(data.trip_plan);
        }
    }

    function generateTripPlanHTML(tripPlan) {
        return `
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-plane me-2"></i>Flight Recommendations</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.flights?.map(flight => `<li class="mb-2"><i class="fas fa-check text-success me-2"></i>${flight}</li>`).join('') || '<li>No flight suggestions available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-hotel me-2"></i>Hotel Suggestions</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.hotels?.map(hotel => `<li class="mb-2"><i class="fas fa-check text-success me-2"></i>${hotel}</li>`).join('') || '<li>No hotel suggestions available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Daily Itinerary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.itinerary?.map(day => `<li class="mb-2"><i class="fas fa-calendar-day text-info me-2"></i>${day}</li>`).join('') || '<li>No itinerary available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign me-2"></i>Budget Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            ${tripPlan.budget?.map(item => `<li class="mb-2"><i class="fas fa-money-bill text-warning me-2"></i>${item}</li>`).join('') || '<li>No budget breakdown available</li>'}
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Travel Tips</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Transportation</h6>
                                <ul class="list-unstyled">
                                    ${tripPlan.transportation?.map(tip => `<li class="mb-1"><i class="fas fa-arrow-right text-muted me-2"></i>${tip}</li>`).join('') || '<li>No transportation info available</li>'}
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>General Tips</h6>
                                <ul class="list-unstyled">
                                    ${tripPlan.tips?.map(tip => `<li class="mb-1"><i class="fas fa-arrow-right text-muted me-2"></i>${tip}</li>`).join('') || '<li>No tips available</li>'}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    }

    function generateFallbackTripPlan(tripPlan) {
        return generateTripPlanHTML(tripPlan);
    }

    // Global download function
    window.downloadTripPlan = function() {
        const content = document.getElementById('tripPlanContent');
        if (!content) return;

        // Simple implementation - in production, you'd generate a proper PDF
        const contentText = content.innerText;
        const blob = new Blob([contentText], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'my-trip-plan.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    };
});
</script>

    {{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Airport Search Manager with City Support
    class AirportSearchManager {
        constructor() {
            this.searchTimeout = null;
            this.currentRequest = null;
            this.cache = new Map();
            this.iataCache = new Map();
            this.popularAirports = new Map();
            this.cityHints = new Map(); // Cache for city suggestions
            this.initializeEventListeners();
            this.preloadPopularAirports();
            this.initializeCityHints();
        }

        // Initialize city hints for better UX
        initializeCityHints() {
            const eastAfricanCities = [
                'Nairobi', 'Mombasa', 'Eldoret', 'Kisumu', 'Kampala', 'Entebbe', 'Jinja',
                'Kigali', 'Dar es Salaam', 'Dodoma', 'Arusha', 'Zanzibar', 'Mwanza',
                'Kilimanjaro', 'Moshi', 'Addis Ababa', 'Juba', 'Bujumbura'
            ];
            
            eastAfricanCities.forEach(city => {
                this.cityHints.set(city.toLowerCase(), city);
            });
        }

        async preloadPopularAirports() {
            try {
                const response = await fetch('/api/airports/popular', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const airports = await response.json();
                    airports.forEach(airport => {
                        const iataCode = airport.iata_code || airport.iataCode;
                        if (iataCode) {
                            this.popularAirports.set(iataCode.toLowerCase(), airport);
                        }
                    });
                }
            } catch (error) {
                console.warn('Failed to preload popular airports:', error);
            }
        }

        initializeEventListeners() {
            const airportInputs = document.querySelectorAll('.airport-autocomplete');

            airportInputs.forEach(input => {
                let suggestionsContainer = this.getSuggestionsContainer(input);
                let hiddenInput = this.getHiddenInput(input);

                if (!suggestionsContainer || !hiddenInput) return;

                // Input event with enhanced city detection
                input.addEventListener('input', (e) => {
                    this.handleSearchInput(e.target, suggestionsContainer, hiddenInput);
                });

                // Focus event - show cached or popular results
                input.addEventListener('focus', () => {
                    const query = input.value.trim();
                    if (query.length > 0) {
                        const cacheKey = query.toLowerCase();
                        if (this.cache.has(cacheKey)) {
                            this.displaySuggestions(this.cache.get(cacheKey), suggestionsContainer, input, hiddenInput);
                        } else {
                            // Show city hints if typing looks like a city name
                            this.showCityHints(query, suggestionsContainer, input, hiddenInput);
                        }
                    } else {
                        this.showPopularAirports(suggestionsContainer, input, hiddenInput);
                    }
                });

                input.addEventListener('blur', () => {
                    setTimeout(() => {
                        if (suggestionsContainer) {
                            suggestionsContainer.classList.add('d-none');
                        }
                    }, 200);
                });

                input.addEventListener('keydown', (e) => {
                    this.handleKeyboardNavigation(e, suggestionsContainer, input, hiddenInput);
                });
            });
        }

        // Show city hints when user starts typing city names
        showCityHints(query, container, input, hiddenInput) {
            const queryLower = query.toLowerCase();
            const matchingCities = [];
            
            for (let [cityKey, cityName] of this.cityHints) {
                if (cityKey.startsWith(queryLower) || cityKey.includes(queryLower)) {
                    matchingCities.push({
                        name: `${cityName} (Search for airports)`,
                        city: cityName,
                        country: 'East Africa',
                        iata_code: '',
                        source: 'city_hint',
                        type: 'city_suggestion'
                    });
                }
            }
            
            if (matchingCities.length > 0) {
                this.displaySuggestions(matchingCities.slice(0, 5), container, input, hiddenInput);
            }
        }

        showPopularAirports(container, input, hiddenInput) {
            if (this.popularAirports.size > 0) {
                const popular = Array.from(this.popularAirports.values()).slice(0, 8);
                this.displaySuggestions(popular, container, input, hiddenInput);
            }
        }

        getSuggestionsContainer(input) {
            if (!input || !input.id) return null;
            
            let suggestionsId;
            const idMap = {
                'rt-origin': 'fromSuggestions',
                'rt-destination': 'toSuggestions',
                'ow-origin': 'owFromSuggestions',
                'ow-destination': 'owToSuggestions'
            };
            
            suggestionsId = idMap[input.id];
            
            if (suggestionsId) {
                return document.getElementById(suggestionsId);
            }
            
            const parent = input.closest('.position-relative');
            if (!parent) return null;
            
            let container = parent.querySelector('.airport-suggestions');
            if (!container) {
                container = document.createElement('div');
                container.className = 'airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg d-none';
                container.style.zIndex = '1000';
                parent.appendChild(container);
            }
            return container;
        }

        getHiddenInput(input) {
            if (!input || !input.id) return null;
            
            const hiddenMap = {
                'rt-origin': 'from_iata',
                'rt-destination': 'to_iata',
                'ow-origin': 'ow-from-iata',
                'ow-destination': 'ow-to-iata'
            };
            
            const hiddenId = hiddenMap[input.id];
            if (hiddenId) {
                return document.getElementById(hiddenId);
            }
            
            const parent = input.closest('.position-relative');
            return parent ? parent.querySelector('input[type="hidden"]') : null;
        }

        handleSearchInput(input, suggestionsContainer, hiddenInput) {
            const query = input.value.trim();

            if (hiddenInput) {
                hiddenInput.value = '';
            }

            input.classList.remove('is-invalid');
            
            clearTimeout(this.searchTimeout);

            if (query.length < 1) {
                suggestionsContainer.classList.add('d-none');
                return;
            }

            // Show city hints immediately for potential city names
            if (query.length >= 2 && !this.isIataCode(query)) {
                this.showCityHints(query, suggestionsContainer, input, hiddenInput);
            }

            // Enhanced search timing
            const searchDelay = this.isIataCode(query) ? 150 : 300;
            
            this.searchTimeout = setTimeout(() => {
                this.searchAirports(query)
                    .then(airports => {
                        this.displaySuggestions(airports, suggestionsContainer, input, hiddenInput);
                    })
                    .catch(error => {
                        console.error('Error searching airports:', error);
                    });
            }, searchDelay);
        }

        isIataCode(query) {
            return /^[A-Za-z]{1,3}$/.test(query);
        }

        async searchAirports(query) {
            const cacheKey = query.toLowerCase();
            
            if (this.cache.has(cacheKey)) {
                return this.cache.get(cacheKey);
            }

            if (this.currentRequest) {
                this.currentRequest.abort();
            }

            const controller = new AbortController();
            this.currentRequest = controller;

            try {
                const isIata = this.isIataCode(query);
                let allResults = [];

                if (isIata) {
                    const iataResults = await this.searchByIataCode(query, controller);
                    const localResults = await this.searchLocalAirports(query, controller);
                    allResults = this.combineAndPrioritizeResults(iataResults, localResults, query);
                } else {
                    const [localResults, amadeusResults] = await Promise.all([
                        this.searchLocalAirports(query, controller),
                        this.searchAmadeusLocations(query, controller)
                    ]);
                    allResults = this.combineResults(localResults, amadeusResults, query);
                }

                this.cache.set(cacheKey, allResults);
                return allResults;

            } catch (error) {
                if (error.name === 'AbortError') {
                    return [];
                }
                console.error('Error fetching airports:', error);
                return [];
            }
        }

        async searchByIataCode(query, controller) {
            try {
                const response = await fetch(`/api/airports/search/iata?code=${encodeURIComponent(query)}`, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                return data.map(airport => ({
                    ...airport,
                    source: 'iata'
                }));
            } catch (error) {
                if (error.name === 'AbortError') {
                    throw error;
                }
                console.error('IATA search failed:', error);
                return [];
            }
        }

        async searchLocalAirports(query, controller) {
            try {
                const response = await fetch(`/api/airports/search?q=${encodeURIComponent(query)}`, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                return data.map(airport => ({
                    ...airport,
                    source: 'local'
                }));
            } catch (error) {
                if (error.name === 'AbortError') {
                    throw error;
                }
                console.error('Local airport search failed:', error);
                return [];
            }
        }

        async searchAmadeusLocations(query, controller) {
            try {
                const response = await fetch(
                    `/api/airports/search/locations?keyword=${encodeURIComponent(query)}&subType[]=AIRPORT`, {
                        signal: controller.signal,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (data.success && data.data) {
                    return data.data.map(airport => ({
                        ...airport,
                        source: 'amadeus'
                    }));
                }
                return [];
            } catch (error) {
                if (error.name === 'AbortError') {
                    throw error;
                }
                console.error('Amadeus location search failed:', error);
                return [];
            }
        }

        combineAndPrioritizeResults(iataResults, localResults, query) {
            const queryLower = query.toLowerCase();
            const allResults = [...iataResults];
            const iataCodesSet = new Set(iataResults.map(airport => 
                (airport.iata_code || airport.iataCode || '').toLowerCase()
            ));

            localResults.forEach(airport => {
                const iataCode = (airport.iata_code || airport.iataCode || '').toLowerCase();
                if (!iataCodesSet.has(iataCode)) {
                    allResults.push(airport);
                }
            });

            return this.sortResults(allResults, queryLower);
        }

        combineResults(localResults, amadeusResults, query) {
            const queryLower = query.toLowerCase();
            const allResults = [...localResults];
            const iataCodesSet = new Set(localResults.map(airport => 
                (airport.iata_code || airport.iataCode || '').toLowerCase()
            ));

            amadeusResults.forEach(airport => {
                const iataCode = (airport.iataCode || airport.iata_code || '').toLowerCase();
                if (!iataCodesSet.has(iataCode)) {
                    allResults.push(airport);
                }
            });

            return this.sortResults(allResults, queryLower);
        }

        sortResults(results, queryLower) {
            return results.sort((a, b) => {
                const aIata = (a.iata_code || a.iataCode || '').toLowerCase();
                const bIata = (b.iata_code || b.iataCode || '').toLowerCase();
                const aName = (a.name || a.detailedName || '').toLowerCase();
                const bName = (b.name || b.detailedName || '').toLowerCase();
                const aCity = (a.city || (a.address && a.address.cityName) || '').toLowerCase();
                const bCity = (b.city || (b.address && b.address.cityName) || '').toLowerCase();

                // City mapping results get high priority
                if (a.source === 'city_mapping' && b.source !== 'city_mapping') return -1;
                if (b.source === 'city_mapping' && a.source !== 'city_mapping') return 1;

                // Exact IATA match gets highest priority
                if (aIata === queryLower && bIata !== queryLower) return -1;
                if (bIata === queryLower && aIata !== queryLower) return 1;

                // City name exact match gets high priority
                if (aCity === queryLower && bCity !== queryLower) return -1;
                if (bCity === queryLower && aCity !== queryLower) return 1;

                // IATA starts with query
                if (aIata.startsWith(queryLower) && !bIata.startsWith(queryLower)) return -1;
                if (bIata.startsWith(queryLower) && !aIata.startsWith(queryLower)) return 1;

                // City starts with query
                if (aCity.startsWith(queryLower) && !bCity.startsWith(queryLower)) return -1;
                if (bCity.startsWith(queryLower) && !aCity.startsWith(queryLower)) return 1;

                // IATA contains query
                if (aIata.includes(queryLower) && !bIata.includes(queryLower)) return -1;
                if (bIata.includes(queryLower) && !aIata.includes(queryLower)) return 1;

                // Name starts with query
                if (aName.startsWith(queryLower) && !bName.startsWith(queryLower)) return -1;
                if (bName.startsWith(queryLower) && !aName.startsWith(queryLower)) return 1;

                return aName.localeCompare(bName);
            });
        }

        displaySuggestions(airports, container, input, hiddenInput) {
            if (!container) return;

            container.innerHTML = '';

            if (airports.length === 0) {
                container.classList.add('d-none');
                return;
            }

            const suggestionsContainer = document.createElement('div');
            suggestionsContainer.className = 'bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-y-auto';

            const limitedAirports = airports.slice(0, 8);

            limitedAirports.forEach((airport, index) => {
                const iataCode = airport.iataCode || airport.iata_code;
                const name = airport.name || airport.display_name || airport.detailedName;
                const city = (airport.address?.cityName) || airport.city || '';
                const country = (airport.address?.countryName) || airport.country || '';
                const location = city && country ? `${city}, ${country}` : (city || country);

                const suggestionItem = document.createElement('div');
                suggestionItem.className = `suggestion-item flex items-center gap-3 px-3 py-2 cursor-pointer transition-all duration-200 hover:bg-blue-50 hover:border-l-4 hover:border-blue-500 ${index === 0 ? 'bg-blue-50 border-l-4 border-blue-500' : ''}`;
                suggestionItem.setAttribute('data-index', index);

                // Handle city suggestion vs airport suggestion
                if (airport.type === 'city_suggestion') {
                    suggestionItem.innerHTML = `
                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                            <i data-lucide="map-pin" class="w-4 h-4 text-green-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold text-white bg-green-600">
                                    CITY
                                </span>
                                <span class="text-sm font-medium text-gray-900 truncate">${name}</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                                <i data-lucide="search" class="w-3 h-3"></i>
                                <span class="truncate">Click to search airports in this city</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 transition-transform duration-200"></i>
                        </div>
                    `;

                    suggestionItem.addEventListener('click', () => {
                        // When city is clicked, search for airports in that city
                        input.value = airport.city;
                        this.searchAirports(airport.city)
                            .then(airports => {
                                this.displaySuggestions(airports, container, input, hiddenInput);
                            })
                            .catch(error => {
                                console.error('Error searching airports:', error);
                            });
                    });
                } else {
                    // Regular airport suggestion
                    const query = input.value.trim().toUpperCase();
                    let highlightedIata = iataCode;
                    if (iataCode && query) {
                        const iataUpper = iataCode.toUpperCase();
                        const startIndex = iataUpper.indexOf(query);
                        if (startIndex !== -1) {
                            const before = iataCode.substring(0, startIndex);
                            const match = iataCode.substring(startIndex, startIndex + query.length);
                            const after = iataCode.substring(startIndex + query.length);
                            highlightedIata = `${before}<span class="font-bold text-blue-600">${match}</span>${after}`;
                        }
                    }

                    // Special styling for city mapping results
                    const isFromCityMapping = airport.source === 'city_mapping';
                    const badgeColor = isFromCityMapping ? 'bg-green-600' : 'bg-blue-600';
                    const iconColor = isFromCityMapping ? 'text-green-600' : 'text-blue-600';

                    suggestionItem.innerHTML = `
                        <div class="flex items-center justify-center w-8 h-8 ${isFromCityMapping ? 'bg-green-100' : 'bg-blue-100'} rounded-full">
                            <i data-lucide="plane" class="w-4 h-4 ${iconColor}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold text-white ${badgeColor}">
                                    ${highlightedIata}
                                </span>
                                <span class="text-sm font-medium text-gray-900 truncate">${name}</span>
                                ${isFromCityMapping ? '<span class="text-xs bg-green-100 text-green-800 px-1 rounded">Popular</span>' : ''}
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-500 mt-0.5">
                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                <span class="truncate">${location}</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 transition-transform duration-200"></i>
                        </div>
                    `;

                    suggestionItem.addEventListener('click', () => {
                        suggestionItem.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            suggestionItem.style.transform = '';
                            this.selectAirport(airport, input, hiddenInput);
                            container.classList.add('d-none');
                        }, 100);
                    });
                }

                // Enhanced hover effects
                suggestionItem.addEventListener('mouseenter', function() {
                    container.querySelectorAll('.suggestion-item').forEach(item => {
                        item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
                    });
                    this.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
                    
                    const arrowIcon = this.querySelector('[data-lucide="arrow-right"]');
                    const planeIcon = this.querySelector('[data-lucide="plane"]');
                    if (arrowIcon) arrowIcon.style.transform = 'translateX(4px)';
                    if (planeIcon) {
                        planeIcon.style.transform = 'rotate(10deg) scale(1.1)';
                        planeIcon.style.color = '#2563eb';
                    }
                });

                suggestionItem.addEventListener('mouseleave', function() {
                    const arrowIcon = this.querySelector('[data-lucide="arrow-right"]');
                    const planeIcon = this.querySelector('[data-lucide="plane"]');
                    if (arrowIcon) arrowIcon.style.transform = '';
                    if (planeIcon) {
                        planeIcon.style.transform = '';
                        planeIcon.style.color = '';
                    }
                });

                suggestionsContainer.appendChild(suggestionItem);
            });

            if (airports.length > 8) {
                const footer = document.createElement('div');
                footer.className = 'px-3 py-2 text-xs text-center text-gray-500 bg-gray-50 border-t border-gray-200 flex items-center justify-center gap-1';
                footer.innerHTML = `
                    <i data-lucide="search" class="w-3 h-3"></i>
                    <span>Showing 8 of ${airports.length} results. Type more to refine.</span>
                `;
                suggestionsContainer.appendChild(footer);
            }

            container.appendChild(suggestionsContainer);
            container.classList.remove('d-none');
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        selectAirport(airport, input, hiddenInput) {
            const iataCode = airport.iataCode || airport.iata_code;
            const name = airport.name || airport.display_name || airport.detailedName;
            const city = (airport.address?.cityName) || airport.city || '';

            const displayText = iataCode ? `${iataCode} - ${name}${city ? `, ${city}` : ''}` : name;

            input.value = displayText;

            if (hiddenInput) {
                hiddenInput.value = iataCode;
            }

            input.classList.remove('is-invalid');
            const errorFeedback = input.parentElement.querySelector('.invalid-feedback');
            if (errorFeedback) {
                errorFeedback.remove();
            }

            input.dataset.selectedAirport = JSON.stringify(airport);
        }

        handleKeyboardNavigation(event, container, input, hiddenInput) {
            const items = container.querySelectorAll('.suggestion-item');
            if (items.length === 0) return;

            const activeItem = container.querySelector('.suggestion-item.bg-blue-50');
            let activeIndex = activeItem ? parseInt(activeItem.getAttribute('data-index')) : -1;

            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    activeIndex = (activeIndex + 1) % items.length;
                    this.highlightItem(items, activeIndex);
                    break;

                case 'ArrowUp':
                    event.preventDefault();
                    activeIndex = (activeIndex - 1 + items.length) % items.length;
                    this.highlightItem(items, activeIndex);
                    break;

                case 'Enter':
                    event.preventDefault();
                    if (activeItem) {
                        activeItem.click();
                    }
                    break;

                case 'Escape':
                    event.preventDefault();
                    container.classList.add('d-none');
                    break;
            }
        }

        highlightItem(items, activeIndex) {
            items.forEach((item, index) => {
                if (index === activeIndex) {
                    item.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
                    item.scrollIntoView({
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                } else {
                    item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
                }
            });
        }
    }

    // Initialize the enhanced airport search
    new AirportSearchManager();

    // Rest of your existing code remains the same...
    window.addFlightSegment = function() {
        const container = document.getElementById('multi-city-flights');
        const segmentCount = container.querySelectorAll('.flight-segment').length;

        if (segmentCount >= 6) {
            alert('Maximum 6 flights allowed.');
            return;
        }

        const newSegment = document.createElement('div');
        newSegment.className = 'flight-segment mb-3 p-3 border rounded';
        newSegment.style.backgroundColor = '#f8f9fa';

        const today = new Date().toISOString().split('T')[0];
        
        newSegment.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                    <div class="position-relative">
                        <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                        <input type="text" 
                            class="form-control border-2 ps-4 airport-autocomplete" 
                            name="from[]" 
                            placeholder="Country, city or airport"
                            style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                            required autocomplete="off">
                        <input type="hidden" name="from_iata[]">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                    <div class="position-relative">
                        <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                        <input type="text" 
                            class="form-control border-2 ps-4 airport-autocomplete" 
                            name="to[]" 
                            placeholder="Country, city or airport"
                            style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                            required autocomplete="off">
                        <input type="hidden" name="to_iata[]">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Date</label>
                    <div class="position-relative">
                        <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                        <input type="date" 
                            class="form-control border-2 ps-4" 
                            name="date[]"
                            style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                            min="${today}"
                            required>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeFlightSegment(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(newSegment);
        new AirportSearchManager().initializeEventListeners();
    };

    window.removeFlightSegment = function(button) {
        const segment = button.closest('.flight-segment');
        const container = document.getElementById('multi-city-flights');
        
        if (container && segment && container.children.length > 1) {
            segment.remove();
        }
    };

    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.min = today;
    });

    document.getElementById('rt-departure')?.addEventListener('change', function() {
        const returnInput = document.getElementById('rt-return');
        if (returnInput) {
            returnInput.min = this.value;
            if (returnInput.value && returnInput.value < this.value) {
                returnInput.value = this.value;
            }
        }
    });
});
</script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ultra-fast Airport Search Manager
            class OptimizedAirportSearch {
                constructor() {
                    this.searchTimeout = null;
                    this.currentRequest = null;
                    this.cache = new Map();
                    this.popularCache = new Map();
                    this.requestController = new AbortController();
                    this.minSearchLength = 1;
                    this.searchDelay = 50; // Ultra-fast: 50ms
                    this.iataSearchDelay = 25; // Even faster for IATA codes: 25ms

                    this.init();
                }

                init() {
                    this.preloadPopularAirports();
                    this.setupEventListeners();
                    this.setupGlobalClickHandler();
                }

                async preloadPopularAirports() {
                    try {
                        const response = await fetch('/api/airports/popular', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const airports = await response.json();
                            airports.forEach(airport => {
                                const key = (airport.iata_code || '').toLowerCase();
                                if (key) {
                                    this.popularCache.set(key, airport);
                                }
                            });
                        }
                    } catch (error) {
                        console.warn('Failed to preload popular airports:', error);
                    }
                }

                setupEventListeners() {
                    const inputs = document.querySelectorAll('.airport-autocomplete');

                    inputs.forEach(input => {
                        const container = this.findSuggestionContainer(input);
                        const hiddenInput = this.findHiddenInput(input);

                        if (!container || !hiddenInput) return;

                        // Ultra-fast input handling
                        input.addEventListener('input', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            this.handleInput(input, container, hiddenInput);
                        });

                        // Instant focus handling
                        input.addEventListener('focus', (e) => {
                            e.preventDefault();
                            this.handleFocus(input, container, hiddenInput);
                        });

                        // Optimized blur handling
                        input.addEventListener('blur', (e) => {
                            setTimeout(() => {
                                if (!container.matches(':hover')) {
                                    this.hideSuggestions(container);
                                }
                            }, 150);
                        });

                        // Keyboard navigation
                        input.addEventListener('keydown', (e) => {
                            this.handleKeyboard(e, container, input, hiddenInput);
                        });

                        // Prevent form submission on Enter if suggestions are visible
                        input.addEventListener('keypress', (e) => {
                            if (e.key === 'Enter' && !container.classList.contains('d-none')) {
                                e.preventDefault();
                                const activeItem = container.querySelector(
                                    '.suggestion-item.active');
                                if (activeItem) {
                                    activeItem.click();
                                }
                            }
                        });
                    });
                }

                setupGlobalClickHandler() {
                    document.addEventListener('click', (e) => {
                        if (!e.target.closest('.airport-autocomplete') &&
                            !e.target.closest('.airport-suggestions')) {
                            this.hideAllSuggestions();
                        }
                    });
                }

                handleInput(input, container, hiddenInput) {
                    const query = input.value.trim();

                    // Clear hidden input immediately
                    hiddenInput.value = '';
                    input.classList.remove('is-invalid');

                    // Clear previous timeout
                    clearTimeout(this.searchTimeout);

                    // Cancel previous request
                    if (this.currentRequest && !this.currentRequest.signal.aborted) {
                        this.currentRequest.abort();
                    }

                    if (query.length < this.minSearchLength) {
                        this.hideSuggestions(container);
                        return;
                    }

                    // Check cache first for instant results
                    const cacheKey = query.toLowerCase();
                    if (this.cache.has(cacheKey)) {
                        this.displaySuggestions(this.cache.get(cacheKey), container, input, hiddenInput);
                        return;
                    }

                    // Determine search delay based on input type
                    const delay = this.isLikelyIATA(query) ? this.iataSearchDelay : this.searchDelay;

                    this.searchTimeout = setTimeout(() => {
                        this.performSearch(query, container, input, hiddenInput);
                    }, delay);
                }

                handleFocus(input, container, hiddenInput) {
                    const query = input.value.trim();

                    if (query.length >= this.minSearchLength) {
                        const cacheKey = query.toLowerCase();
                        if (this.cache.has(cacheKey)) {
                            this.displaySuggestions(this.cache.get(cacheKey), container, input, hiddenInput);
                            return;
                        }
                    }

                    // Show popular airports if no query
                    if (query.length === 0) {
                        this.showPopularAirports(container, input, hiddenInput);
                    }
                }

                isLikelyIATA(query) {
                    return /^[A-Za-z]{1,3}$/.test(query);
                }

                async performSearch(query, container, input, hiddenInput) {
                    try {
                        // Create new request controller
                        this.currentRequest = new AbortController();

                        // Show loading indicator
                        this.showLoadingIndicator(container);

                        // Determine search endpoint
                        const endpoint = this.isLikelyIATA(query) ?
                            `/api/airports/search/iata?code=${encodeURIComponent(query)}` :
                            `/api/airports/search?q=${encodeURIComponent(query)}`;

                        const response = await fetch(endpoint, {
                            signal: this.currentRequest.signal,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }

                        const airports = await response.json();

                        // Cache the results
                        this.cache.set(query.toLowerCase(), airports);

                        // Display results
                        this.displaySuggestions(airports, container, input, hiddenInput);

                    } catch (error) {
                        if (error.name === 'AbortError') {
                            return; // Request was cancelled
                        }

                        console.error('Search error:', error);
                        this.hideSuggestions(container);
                    }
                }

                showLoadingIndicator(container) {
                    container.innerHTML = `
                <div class="p-2 text-center">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <small class="d-block text-muted mt-1">Searching...</small>
                </div>
            `;
                    container.classList.remove('d-none');
                }

                displaySuggestions(airports, container, input, hiddenInput) {
                    if (!airports || airports.length === 0) {
                        this.showNoResults(container);
                        return;
                    }

                    const fragment = document.createDocumentFragment();

                    airports.slice(0, 8).forEach((airport, index) => {
                        const item = this.createSuggestionItem(airport, index, input.value);

                        // Add click handler
                        item.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            this.selectAirport(airport, input, hiddenInput);
                            this.hideSuggestions(container);
                        });

                        // Add hover handler
                        item.addEventListener('mouseenter', () => {
                            this.highlightItem(container, index);
                        });

                        fragment.appendChild(item);
                    });

                    container.innerHTML = '';
                    container.appendChild(fragment);
                    container.classList.remove('d-none');
                }

                createSuggestionItem(airport, index, query) {
                    const item = document.createElement('div');
                    item.className =
                        `suggestion-item d-flex align-items-center p-2 border-bottom cursor-pointer ${index === 0 ? 'active' : ''}`;
                    item.style.cssText = 'transition: all 0.1s ease; cursor: pointer;';
                    item.setAttribute('data-index', index);

                    const iataCode = airport.iata_code || airport.iataCode || '';
                    const name = airport.name || airport.display_name || airport.detailedName || '';
                    const city = airport.city || airport.address?.cityName || '';
                    const country = airport.country || airport.address?.countryName || '';

                    // Highlight matching text
                    const highlightedIata = this.highlightText(iataCode, query);
                    const highlightedName = this.highlightText(name, query);
                    const highlightedCity = this.highlightText(city, query);

                    item.innerHTML = `
                <div class="flex-shrink-0 me-2">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 28px; height: 28px; font-size: 11px; font-weight: bold;">
                        ${iataCode.substring(0, 2)}
                    </div>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary" style="font-size: 9px;">${highlightedIata}</span>
                        <span class="text-dark fw-medium text-truncate" style="font-size: 13px;">${highlightedName}</span>
                    </div>
                    <div class="text-muted small" style="font-size: 11px;">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        ${highlightedCity}${city && country ? ', ' : ''}${country}
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                </div>
            `;

                    // Add hover effects
                    item.addEventListener('mouseenter', function() {
                        this.classList.add('bg-light');
                        this.style.borderLeft = '3px solid #007bff';
                    });

                    item.addEventListener('mouseleave', function() {
                        if (!this.classList.contains('active')) {
                            this.classList.remove('bg-light');
                            this.style.borderLeft = '';
                        }
                    });

                    return item;
                }

                highlightText(text, query) {
                    if (!text || !query) return text;

                    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                    return text.replace(regex, '<mark class="bg-warning">$1</mark>');
                }

                showNoResults(container) {
                    container.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-search mb-2"></i>
                    <div>No airports found</div>
                    <small>Try searching by city name or IATA code</small>
                </div>
            `;
                    container.classList.remove('d-none');
                }

                showPopularAirports(container, input, hiddenInput) {
                    if (this.popularCache.size === 0) {
                        this.hideSuggestions(container);
                        return;
                    }

                    const popular = Array.from(this.popularCache.values()).slice(0, 8);
                    this.displaySuggestions(popular, container, input, hiddenInput);
                }

                selectAirport(airport, input, hiddenInput) {
                    const iataCode = airport.iata_code || airport.iataCode || '';
                    const name = airport.name || airport.display_name || airport.detailedName || '';
                    const city = airport.city || airport.address?.cityName || '';

                    const displayText = `${iataCode} - ${name}${city ? `, ${city}` : ''}`;

                    input.value = displayText;
                    hiddenInput.value = iataCode;

                    // Remove validation errors
                    input.classList.remove('is-invalid');
                    const errorElement = input.parentElement.querySelector('.invalid-feedback');
                    if (errorElement) {
                        errorElement.remove();
                    }

                    // Store airport data
                    input.dataset.selectedAirport = JSON.stringify(airport);

                    // Trigger change event
                    input.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }

                handleKeyboard(e, container, input, hiddenInput) {
                    if (container.classList.contains('d-none')) return;

                    const items = container.querySelectorAll('.suggestion-item');
                    if (items.length === 0) return;

                    const activeItem = container.querySelector('.suggestion-item.active');
                    let activeIndex = activeItem ? parseInt(activeItem.getAttribute('data-index')) : -1;

                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            activeIndex = (activeIndex + 1) % items.length;
                            this.highlightItem(container, activeIndex);
                            break;

                        case 'ArrowUp':
                            e.preventDefault();
                            activeIndex = (activeIndex - 1 + items.length) % items.length;
                            this.highlightItem(container, activeIndex);
                            break;

                        case 'Enter':
                            e.preventDefault();
                            if (activeItem) {
                                activeItem.click();
                            }
                            break;

                        case 'Escape':
                            e.preventDefault();
                            this.hideSuggestions(container);
                            break;
                    }
                }

                highlightItem(container, index) {
                    const items = container.querySelectorAll('.suggestion-item');
                    items.forEach((item, i) => {
                        if (i === index) {
                            item.classList.add('active', 'bg-light');
                            item.style.borderLeft = '3px solid #007bff';
                            item.scrollIntoView({
                                block: 'nearest',
                                behavior: 'smooth'
                            });
                        } else {
                            item.classList.remove('active', 'bg-light');
                            item.style.borderLeft = '';
                        }
                    });
                }

                hideSuggestions(container) {
                    container.classList.add('d-none');
                    container.innerHTML = '';
                }

                hideAllSuggestions() {
                    document.querySelectorAll('.airport-suggestions').forEach(container => {
                        this.hideSuggestions(container);
                    });
                }

                findSuggestionContainer(input) {
                    const idMap = {
                        'rt-origin': 'fromSuggestions',
                        'rt-destination': 'toSuggestions',
                        'ow-origin': 'owFromSuggestions',
                        'ow-destination': 'owToSuggestions'
                    };

                    const containerId = idMap[input.id];
                    if (containerId) {
                        return document.getElementById(containerId);
                    }

                    // For dynamic inputs
                    return input.parentElement.querySelector('.airport-suggestions');
                }

                findHiddenInput(input) {
                    const hiddenMap = {
                        'rt-origin': 'from_iata',
                        'rt-destination': 'to_iata',
                        'ow-origin': 'ow-from-iata',
                        'ow-destination': 'ow-to-iata'
                    };

                    const hiddenId = hiddenMap[input.id];
                    if (hiddenId) {
                        return document.getElementById(hiddenId);
                    }

                    return input.parentElement.querySelector('input[type="hidden"]');
                }
            }

            // Initialize the optimized search
            new OptimizedAirportSearch();

            // Your existing multi-city code remains the same...
            window.addFlightSegment = function() {
                const container = document.getElementById('multi-city-flights');
                const segmentCount = container.querySelectorAll('.flight-segment').length;

                if (segmentCount >= 6) {
                    alert('Maximum 6 flights allowed.');
                    return;
                }

                const newSegment = document.createElement('div');
                newSegment.className = 'flight-segment mb-3 p-3 border rounded';
                newSegment.style.backgroundColor = '#f8f9fa';

                const today = new Date().toISOString().split('T')[0];

                newSegment.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                    <div class="position-relative">
                        <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                        <input type="text" 
                            class="form-control border-2 ps-4 airport-autocomplete" 
                            name="from[]" 
                            placeholder="Country, city or airport"
                            style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                            required autocomplete="off">
                        <input type="hidden" name="from_iata[]">
                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg d-none" style="z-index: 1000;"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                    <div class="position-relative">
                        <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                        <input type="text" 
                            class="form-control border-2 ps-4 airport-autocomplete" 
                            name="to[]" 
                            placeholder="Country, city or airport"
                            style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                            required autocomplete="off">
                        <input type="hidden" name="to_iata[]">
                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg d-none" style="z-index: 1000;"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Date</label>
                    <div class="position-relative">
                        <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                        <input type="date" 
                            class="form-control border-2 ps-4" 
                            name="date[]"
                            style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                            min="${today}"
                            required>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeFlightSegment(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

                container.appendChild(newSegment);

                // Reinitialize search for new fields
                new OptimizedAirportSearch();
            };

            window.removeFlightSegment = function(button) {
                const segment = button.closest('.flight-segment');
                const container = document.getElementById('multi-city-flights');

                if (container && segment && container.children.length > 1) {
                    segment.remove();
                }
            };

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.min = today;
            });

            // Update return date min value based on departure date
            document.getElementById('rt-departure')?.addEventListener('change', function() {
                const returnInput = document.getElementById('rt-return');
                if (returnInput) {
                    returnInput.min = this.value;
                    if (returnInput.value && returnInput.value < this.value) {
                        returnInput.value = this.value;
                    }
                }
            });

            // Prevent form submission during search
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const activeSearches = document.querySelectorAll(
                        '.airport-suggestions:not(.d-none)');
                    if (activeSearches.length > 0) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        });
    </script> --}}




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today for all date inputs
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.setAttribute('min', today);
            });

            // Update return date minimum when depart date changes
            document.getElementById('rt-departure')?.addEventListener('change', function() {
                const returnInput = document.getElementById('rt-return');
                if (returnInput) {
                    returnInput.setAttribute('min', this.value);
                }
            });

            // Form validation for passenger counts
            document.querySelectorAll('input[name="adults"], input[name="children"], input[name="infants"]')
                .forEach(input => {
                    input.addEventListener('change', function() {
                        validatePassengerCounts(this.closest('form'));
                    });
                });

            function validatePassengerCounts(form) {
                const adults = parseInt(form.querySelector('input[name="adults"]').value) || 0;
                const children = parseInt(form.querySelector('input[name="children"]').value) || 0;
                const infants = parseInt(form.querySelector('input[name="infants"]').value) || 0;

                // Validate total seated travelers (adults + children) doesn't exceed 9
                if (adults + children > 9) {
                    alert('Total seated travelers (adults + children) cannot exceed 9');
                    return false;
                }

                // Validate infants don't exceed adults
                if (infants > adults) {
                    alert('Number of infants cannot exceed number of adults');
                    return false;
                }

                return true;
            }

            // Loading state for search button
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!validatePassengerCounts(this)) {
                        e.preventDefault();
                        return;
                    }

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const spinner = submitBtn.querySelector('.spinner-border');
                    const icon = submitBtn.querySelector('.fas');

                    if (spinner && icon) {
                        spinner.classList.remove('d-none');
                        icon.classList.add('d-none');
                        submitBtn.disabled = true;
                    }
                });
            });
        });

        // Add flight segment for multi-city
        function addFlightSegment() {
            const container = document.getElementById('multi-city-flights');

            const newSegment = document.createElement('div');
            newSegment.className = 'flight-segment mb-3 p-3 border rounded';
            newSegment.style.backgroundColor = '#f8f9fa';
            newSegment.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                <div class="position-relative">
                    <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                    <input type="text" 
                           class="form-control border-2 ps-4 airport-autocomplete" 
                           name="from[]" 
                           placeholder="Country, city or airport"
                           style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                           required>
                    <input type="hidden" name="from_iata[]">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                <div class="position-relative">
                    <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                    <input type="text" 
                           class="form-control border-2 ps-4 airport-autocomplete" 
                           name="to[]" 
                           placeholder="Country, city or airport"
                           style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                           required>
                    <input type="hidden" name="to_iata[]">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Date</label>
                <div class="position-relative">
                    <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                    <input type="date" 
                           class="form-control border-2 ps-4" 
                           name="date[]"
                           style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                           required>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFlightSegment(this)" style="font-family: 'Jost', sans-serif;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

            container.appendChild(newSegment);

            // Set minimum date for new segment
            const today = new Date().toISOString().split('T')[0];
            newSegment.querySelector('input[type="date"]').setAttribute('min', today);
        }

        // Remove flight segment
        function removeFlightSegment(button) {
            const container = document.getElementById('multi-city-flights');
            if (container.children.length > 1) {
                button.closest('.flight-segment').remove();
            }
        }
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('modernReviewsCarousel');
            const progressBar = document.getElementById('carouselProgress');
            let progressInterval;
            let isHovered = false;

            if (carousel && progressBar) {
                // Initialize progress bar
                function startProgress() {
                    if (isHovered) return;

                    progressBar.style.width = '0%';
                    progressBar.classList.remove('active');

                    setTimeout(() => {
                        if (!isHovered) {
                            progressBar.classList.add('active');
                        }
                    }, 100);
                }

                // Start initial progress
                startProgress();

                // Reset progress on slide change
                carousel.addEventListener('slide.bs.carousel', function() {
                    setTimeout(startProgress, 100);
                });

                // Pause on hover
                carousel.addEventListener('mouseenter', function() {
                    isHovered = true;
                    progressBar.style.animationPlayState = 'paused';
                    progressBar.style.transition = 'none';
                });

                // Resume on mouse leave
                carousel.addEventListener('mouseleave', function() {
                    isHovered = false;
                    progressBar.style.animationPlayState = 'running';
                    progressBar.style.transition = 'width 4s linear';
                    startProgress();
                });

                // Handle manual navigation
                const prevBtn = carousel.querySelector('.carousel-control-prev');
                const nextBtn = carousel.querySelector('.carousel-control-next');
                const indicators = carousel.querySelectorAll('.carousel-indicators button');

                [prevBtn, nextBtn, ...indicators].forEach(control => {
                    if (control) {
                        control.addEventListener('click', function() {
                            setTimeout(startProgress, 200);
                        });
                    }
                });
            }

            // Add smooth scroll effect for review cards
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = `${Math.random() * 0.5}s`;
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);

            // Observe review cards
            document.querySelectorAll('.review-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for card animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.animationPlayState = 'running';
                            entry.target.classList.add('animate-in');
                        }, index * 100);
                    }
                });
            }, observerOptions);

            // Observe all trust cards
            document.querySelectorAll('.modern-trust-card').forEach(card => {
                observer.observe(card);
            });

            // Add enhanced hover effects
            document.querySelectorAll('.modern-trust-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });

            // Dynamic counter animation for trust statistics
            function animateValue(element, start, end, duration) {
                let startTimestamp = null;
                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    const value = Math.floor(progress * (end - start) + start);
                    element.innerHTML = value.toLocaleString() + (element.dataset.suffix || '');
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            }

            // Start counter animation when section is visible
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Animate the 1M+ counter
                        const customerCount = entry.target.querySelector(
                            '[data-count="customers"]');
                        if (customerCount) {
                            customerCount.dataset.suffix = 'M+';
                            animateValue(customerCount, 0, 1, 2000);
                        }

                        // Animate the 4.9 rating
                        const ratingCount = entry.target.querySelector('[data-count="rating"]');
                        if (ratingCount) {
                            ratingCount.dataset.suffix = '/5';
                            animateValue(ratingCount, 0, 4.9, 2000);
                        }

                        // Animate the 50+ awards
                        const awardsCount = entry.target.querySelector('[data-count="awards"]');
                        if (awardsCount) {
                            awardsCount.dataset.suffix = '+';
                            animateValue(awardsCount, 0, 50, 2000);
                        }

                        statsObserver.unobserve(entry.target);
                    }
                });
            });

            const trustSection = document.querySelector('.trust-badges-modern');
            if (trustSection) {
                statsObserver.observe(trustSection);
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('destinationsCarousel');
            const prevBtn = document.getElementById('prevDestination');
            const nextBtn = document.getElementById('nextDestination');
            const pauseBtn = document.getElementById('pauseCarousel');
            const playBtn = document.getElementById('playCarousel');
            const progressBar = document.getElementById('destinationProgress');

            let currentIndex = 0;
            let isPlaying = true;
            let autoScrollInterval;
            const cardWidth = 320 + 16; // card width + margin
            const visibleCards = Math.floor(window.innerWidth / cardWidth);
            const totalCards = document.querySelectorAll('.destination-card-modern').length;
            const originalCards = totalCards / 2; // Since we duplicated the cards

            // Initialize carousel
            function initCarousel() {
                updateCarousel();
                startAutoScroll();
                updateProgress();
            }

            // Update carousel position
            function updateCarousel() {
                const translateX = -currentIndex * cardWidth;
                carousel.style.transform = `translateX(${translateX}px)`;

                // Check if we need to reset for infinite scroll
                if (currentIndex >= originalCards) {
                    setTimeout(() => {
                        carousel.style.transition = 'none';
                        currentIndex = 0;
                        carousel.style.transform = `translateX(0px)`;
                        setTimeout(() => {
                            carousel.style.transition = 'transform 0.5s ease-in-out';
                        }, 50);
                    }, 500);
                } else if (currentIndex < 0) {
                    setTimeout(() => {
                        carousel.style.transition = 'none';
                        currentIndex = originalCards - 1;
                        carousel.style.transform = `translateX(${-currentIndex * cardWidth}px)`;
                        setTimeout(() => {
                            carousel.style.transition = 'transform 0.5s ease-in-out';
                        }, 50);
                    }, 500);
                }
            }

            // Update progress bar
            function updateProgress() {
                const progress = ((currentIndex % originalCards) / (originalCards - 1)) * 100;
                progressBar.style.width = `${progress}%`;
            }

            // Next slide
            function nextSlide() {
                currentIndex++;
                updateCarousel();
                updateProgress();
            }

            // Previous slide
            function prevSlide() {
                currentIndex--;
                updateCarousel();
                updateProgress();
            }

            // Auto scroll functionality
            function startAutoScroll() {
                if (isPlaying) {
                    autoScrollInterval = setInterval(nextSlide, 3000);
                }
            }

            function stopAutoScroll() {
                clearInterval(autoScrollInterval);
            }

            // Event listeners
            nextBtn.addEventListener('click', () => {
                stopAutoScroll();
                nextSlide();
                if (isPlaying) startAutoScroll();
            });

            prevBtn.addEventListener('click', () => {
                stopAutoScroll();
                prevSlide();
                if (isPlaying) startAutoScroll();
            });

            pauseBtn.addEventListener('click', () => {
                isPlaying = false;
                stopAutoScroll();
                pauseBtn.style.display = 'none';
                playBtn.style.display = 'inline-block';
            });

            playBtn.addEventListener('click', () => {
                isPlaying = true;
                startAutoScroll();
                playBtn.style.display = 'none';
                pauseBtn.style.display = 'inline-block';
            });

            // Pause on hover
            carousel.addEventListener('mouseenter', stopAutoScroll);
            carousel.addEventListener('mouseleave', () => {
                if (isPlaying) startAutoScroll();
            });

            // Touch/swipe support for mobile
            let startX = 0;
            let currentX = 0;
            let isDragging = false;

            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                stopAutoScroll();
            });

            carousel.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                currentX = e.touches[0].clientX;
                e.preventDefault();
            });

            carousel.addEventListener('touchend', () => {
                if (!isDragging) return;
                isDragging = false;

                const deltaX = startX - currentX;
                const threshold = 50;

                if (deltaX > threshold) {
                    nextSlide();
                } else if (deltaX < -threshold) {
                    prevSlide();
                }

                if (isPlaying) startAutoScroll();
            });

            // Action button functionality
            document.querySelectorAll('.wishlist-action-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const icon = btn.querySelector('i');

                    // Toggle wishlist state
                    if (icon.classList.contains('fas')) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        btn.style.background = 'rgba(255, 255, 255, 0.95)';
                        btn.style.color = '#e11d48';
                    } else {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        btn.style.background = '#e11d48';
                        btn.style.color = 'white';
                    }

                    // Add animation feedback
                    btn.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        btn.style.transform = 'scale(1)';
                    }, 200);

                    console.log('Added to wishlist');
                });
            });

            document.querySelectorAll('.details-action-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    console.log('View details clicked');
                    // Add your details navigation logic here
                });
            });

            document.querySelectorAll('.book-action-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    console.log('Book now clicked');
                    // Add your booking logic here
                });
            });

            // Book now button functionality (in card body)
            document.querySelectorAll('.book-now-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    console.log('Book now (main button) clicked');
                    // Add your booking logic here
                });
            });

            // Responsive handling
            window.addEventListener('resize', () => {
                stopAutoScroll();
                updateCarousel();
                if (isPlaying) startAutoScroll();
            });

            // Initialize
            initCarousel();

            // AOS Animation initialization (if AOS library is included)
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 100
                });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const blogCarousel = document.getElementById('blogCarousel');
            const prevBlogBtn = document.getElementById('prevBlog');
            const nextBlogBtn = document.getElementById('nextBlog');
            const pauseBlogBtn = document.getElementById('pauseBlogCarousel');
            const playBlogBtn = document.getElementById('playBlogCarousel');
            const blogProgressBar = document.getElementById('blogProgress');

            let blogCurrentIndex = 0;
            let blogIsPlaying = true;
            let blogAutoScrollInterval;
            const blogCardWidth = 350 + 16; // card width + margin
            const blogVisibleCards = Math.floor(window.innerWidth / blogCardWidth);
            const blogTotalCards = document.querySelectorAll('.blog-card-modern').length;
            const blogOriginalCards = blogTotalCards / 2; // Since we duplicated the cards

            // Initialize blog carousel
            function initBlogCarousel() {
                updateBlogCarousel();
                startBlogAutoScroll();
                updateBlogProgress();
            }

            // Update blog carousel position
            function updateBlogCarousel() {
                const translateX = -blogCurrentIndex * blogCardWidth;
                blogCarousel.style.transform = `translateX(${translateX}px)`;

                // Check if we need to reset for infinite scroll
                if (blogCurrentIndex >= blogOriginalCards) {
                    setTimeout(() => {
                        blogCarousel.style.transition = 'none';
                        blogCurrentIndex = 0;
                        blogCarousel.style.transform = `translateX(0px)`;
                        setTimeout(() => {
                            blogCarousel.style.transition = 'transform 0.5s ease-in-out';
                        }, 50);
                    }, 500);
                } else if (blogCurrentIndex < 0) {
                    setTimeout(() => {
                        blogCarousel.style.transition = 'none';
                        blogCurrentIndex = blogOriginalCards - 1;
                        blogCarousel.style.transform = `translateX(${-blogCurrentIndex * blogCardWidth}px)`;
                        setTimeout(() => {
                            blogCarousel.style.transition = 'transform 0.5s ease-in-out';
                        }, 50);
                    }, 500);
                }
            }

            // Update blog progress bar
            function updateBlogProgress() {
                const progress = ((blogCurrentIndex % blogOriginalCards) / (blogOriginalCards - 1)) * 100;
                blogProgressBar.style.width = `${progress}%`;
            }

            // Next blog slide
            function nextBlogSlide() {
                blogCurrentIndex++;
                updateBlogCarousel();
                updateBlogProgress();
            }

            // Previous blog slide
            function prevBlogSlide() {
                blogCurrentIndex--;
                updateBlogCarousel();
                updateBlogProgress();
            }

            // Blog auto scroll functionality
            function startBlogAutoScroll() {
                if (blogIsPlaying) {
                    blogAutoScrollInterval = setInterval(nextBlogSlide, 4000);
                }
            }

            function stopBlogAutoScroll() {
                clearInterval(blogAutoScrollInterval);
            }

            // Event listeners for blog carousel
            nextBlogBtn.addEventListener('click', () => {
                stopBlogAutoScroll();
                nextBlogSlide();
                if (blogIsPlaying) startBlogAutoScroll();
            });

            prevBlogBtn.addEventListener('click', () => {
                stopBlogAutoScroll();
                prevBlogSlide();
                if (blogIsPlaying) startBlogAutoScroll();
            });

            pauseBlogBtn.addEventListener('click', () => {
                blogIsPlaying = false;
                stopBlogAutoScroll();
                pauseBlogBtn.style.display = 'none';
                playBlogBtn.style.display = 'inline-block';
            });

            playBlogBtn.addEventListener('click', () => {
                blogIsPlaying = true;
                startBlogAutoScroll();
                playBlogBtn.style.display = 'none';
                pauseBlogBtn.style.display = 'inline-block';
            });

            // Pause on hover
            blogCarousel.addEventListener('mouseenter', stopBlogAutoScroll);
            blogCarousel.addEventListener('mouseleave', () => {
                if (blogIsPlaying) startBlogAutoScroll();
            });

            // Touch/swipe support for mobile
            let blogStartX = 0;
            let blogCurrentX = 0;
            let blogIsDragging = false;

            blogCarousel.addEventListener('touchstart', (e) => {
                blogStartX = e.touches[0].clientX;
                blogIsDragging = true;
                stopBlogAutoScroll();
            });

            blogCarousel.addEventListener('touchmove', (e) => {
                if (!blogIsDragging) return;
                blogCurrentX = e.touches[0].clientX;
                e.preventDefault();
            });

            blogCarousel.addEventListener('touchend', () => {
                if (!blogIsDragging) return;
                blogIsDragging = false;

                const deltaX = blogStartX - blogCurrentX;
                const threshold = 50;

                if (deltaX > threshold) {
                    nextBlogSlide();
                } else if (deltaX < -threshold) {
                    prevBlogSlide();
                }

                if (blogIsPlaying) startBlogAutoScroll();
            });

            // Share button functionality
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Add share functionality here
                    console.log('Share button clicked');

                    // Animation feedback
                    btn.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        btn.style.transform = 'scale(1)';
                    }, 200);
                });
            });

            // Read more button functionality
            document.querySelectorAll('.read-more-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    // Add navigation to full article
                    console.log('Read more clicked');
                });
            });

            // Card click functionality
            document.querySelectorAll('.blog-card-modern').forEach(card => {
                card.addEventListener('click', (e) => {
                    if (!e.target.closest('.share-btn') && !e.target.closest('.read-more-btn')) {
                        // Navigate to full article
                        console.log('Card clicked');
                    }
                });
            });

            // Responsive handling
            window.addEventListener('resize', () => {
                stopBlogAutoScroll();
                updateBlogCarousel();
                if (blogIsPlaying) startBlogAutoScroll();
            });

            // Initialize blog carousel
            initBlogCarousel();

            // AOS Animation initialization (if AOS library is included)
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 100
                });
            }
        });
    </script>


  
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floatingButtons = document.querySelector('.floating-buttons-container');
            const scrollToTopBtn = document.querySelector('.scroll-to-top');
            const flightIcon = document.querySelector('.flight-icon');
            const progressRingCircle = document.querySelector('.progress-ring-circle');
            const heroSection = document.querySelector('.hero-section');

            let scrollTimeout;
            let isScrolling = false;

            // Calculate circle circumference for progress ring
            const radius = 33;
            const circumference = 2 * Math.PI * radius;

            // Initialize progress ring
            progressRingCircle.style.strokeDasharray = circumference;
            progressRingCircle.style.strokeDashoffset = circumference;

            // Smooth scroll function
            function smoothScrollTo(element) {
                const targetPosition = element.offsetTop;
                const startPosition = window.pageYOffset;
                const distance = targetPosition - startPosition;
                const duration = 1200; // Animation duration in ms
                let start = null;

                // Add flight animation class
                flightIcon.style.animation = 'flightTakeoff 0.8s ease';

                function step(timestamp) {
                    if (!start) start = timestamp;
                    const progress = timestamp - start;
                    const percentage = Math.min(progress / duration, 1);

                    // Easing function for smooth animation
                    const easeInOutCubic = percentage < 0.5 ?
                        4 * percentage * percentage * percentage :
                        (percentage - 1) * (2 * percentage - 2) * (2 * percentage - 2) + 1;

                    window.scrollTo(0, startPosition + distance * easeInOutCubic);

                    if (progress < duration) {
                        window.requestAnimationFrame(step);
                    } else {
                        // Reset flight animation
                        setTimeout(() => {
                            flightIcon.style.animation = '';
                        }, 100);
                    }
                }

                window.requestAnimationFrame(step);
            }

            // Scroll to hero function
            window.scrollToHero = function() {
                if (heroSection) {
                    smoothScrollTo(heroSection);

                    // Add visual feedback
                    scrollToTopBtn.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        scrollToTopBtn.style.transform = '';
                    }, 150);
                }
            };

            // WhatsApp function
            window.openWhatsApp = function() {
                // You can customize the WhatsApp message and number
                const phoneNumber = '1234567890'; // Replace with actual number
                const message = encodeURIComponent('Hello! I need assistance with travel booking.');
                window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
            };

            // Live chat function
            window.openLiveChat = function() {
                // Add your live chat implementation here
                // This could open a chat widget, modal, or redirect to chat service

                // Example: Open a chat modal or integrate with your chat service
                alert(
                    'Live chat feature will be implemented here!\n\nThis could integrate with:\n- Intercom\n- Zendesk Chat\n- Crisp\n- Custom chat solution'
                );

                // Example integration with a chat service:
                // if (window.Intercom) {
                //     window.Intercom('show');
                // }
            };

            // Handle scroll events with throttling
            function handleScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const documentHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrollPercentage = scrollTop / documentHeight;

                // Always show floating buttons (we're making them visible by default)
                document.body.classList.add('floating-buttons-visible');

                // Update progress ring
                const offset = circumference - (scrollPercentage * circumference);
                progressRingCircle.style.strokeDashoffset = offset;

                // Add scrolling class for additional effects
                if (!isScrolling) {
                    document.body.classList.add('is-scrolling');
                    isScrolling = true;
                }

                // Clear timeout and remove scrolling class when scrolling stops
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    document.body.classList.remove('is-scrolling');
                    isScrolling = false;
                }, 150);
            }

            // Throttled scroll event listener
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });

            // Initialize on load
            handleScroll();

            // Add click events with ripple effect
            function addRippleEffect(button) {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            }

            // Add ripple effect to all buttons
            document.querySelectorAll('.whatsapp-btn, .chat-btn, .flight-scroll-btn').forEach(addRippleEffect);
        });

        // Additional CSS for ripple effect
        const rippleCSS = `
                    .ripple {
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.3);
                        pointer-events: none;
                        animation: ripple-animation 0.6s linear;
                    }

                    @keyframes ripple-animation {
                        to {
                            transform: scale(4);
                            opacity: 0;
                        }
                    }
                    `;

        // Inject ripple CSS
        const style = document.createElement('style');
        style.textContent = rippleCSS;
        document.head.appendChild(style);
    </script>



<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{your-app-id}',
      cookie     : true,
      xfbml      : true,
      version    : '{api-version}'
    });
      
    FB.AppEvents.logPageView();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>


{{-- FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
}); --}}


    <!-- Flight search redirect script -->
    <script src="{{ asset('js/flight-redirect.js') }}"></script>
    
    {{-- Styles pushed from includes during content render are emitted
         here as well, since the head stack renders before the content. --}}
    @stack('styles')

    @stack('scripts')
</body>

</html>
