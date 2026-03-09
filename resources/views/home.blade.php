@extends('layouts.main')

@section('content')


<script>
    // Wishlist Management Functions
    function updateWishlistCounter() {
        const counter = document.getElementById('wishlist-counter');
        if (counter) {
            fetch('/wishlists/count')
                .then(response => response.json())
                .then(data => {
                    counter.textContent = data.count;
                    if (data.count > 0) {
                        counter.classList.remove('hidden');
                    } else {
                        counter.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error updating wishlist counter:', error));
        }
    }
    
    function addToWishlist(item, buttonElement) {
        // Check if user is logged in
        fetch('/check-auth')
            .then(response => response.json())
            .then(data => {
                if (!data.isLoggedIn) {
                    // Show login modal or redirect to login page
                    showLoginRequiredModal();
                    return;
                }
                
                // Proceed with adding to wishlist
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch('/wishlists/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(item)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Update heart icon to filled
                        const heartIcon = buttonElement.querySelector('i');
                        heartIcon.classList.remove('far');
                        heartIcon.classList.add('fas');
                        heartIcon.classList.add('text-danger');
                        
                        // Show success toast
                        showToast('Success', result.message, 'success');
                        
                        // Update wishlist counter if exists
                        updateWishlistCounter();
                        
                        // Dispatch custom event for wishlist update
                        document.dispatchEvent(new CustomEvent('wishlistUpdated'));
                    } else {
                        // Show error toast
                        showToast('Notice', result.message, 'info');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error', 'Something went wrong. Please try again.', 'error');
                });
            })
            .catch(error => {
                console.error('Auth check error:', error);
                showToast('Error', 'Something went wrong. Please try again.', 'error');
            });
    }
    
    function removeFromWishlist(type, itemId, buttonElement) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/wishlist/remove/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Update heart icon to outline
                const heartIcon = buttonElement.querySelector('i');
                heartIcon.classList.remove('fas');
                heartIcon.classList.remove('text-danger');
                heartIcon.classList.add('far');
                
                // Show success toast
                showToast('Success', result.message, 'success');
                
                // Update wishlist counter if exists
                updateWishlistCounter();
            } else {
                // Show error toast
                showToast('Error', result.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Something went wrong. Please try again.', 'error');
        });
    }
    
    function checkWishlistStatus(type, itemId, buttonElement) {
        fetch(`/wishlist/check?type=${type}&item_id=${itemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.in_wishlist) {
                    // Update heart icon to filled
                    const heartIcon = buttonElement.querySelector('i');
                    heartIcon.classList.remove('far');
                    heartIcon.classList.add('fas');
                    heartIcon.classList.add('text-danger');
                }
            })
            .catch(error => {
                console.error('Error checking wishlist status:', error);
            });
    }
    
    function updateWishlistCounter() {
        fetch('/wishlist/count')
            .then(response => response.json())
            .then(data => {
                const counter = document.getElementById('wishlist-counter');
                if (counter) {
                    counter.textContent = data.count;
                    if (data.count > 0) {
                        counter.classList.remove('d-none');
                    } else {
                        counter.classList.add('d-none');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating wishlist counter:', error);
            });
    }
    
    function showLoginRequiredModal() {
        // You can implement a modal or redirect to login page
        if (confirm('Please login to add items to your wishlist. Go to login page?')) {
            window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
        }
    }
    
    function showToast(title, message, type) {
        // Check if we have toast functionality
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            // Create toast element
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            
            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary'} border-0`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toastEl);
            
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            
            // Remove toast after it's hidden
            toastEl.addEventListener('hidden.bs.toast', function () {
                toastEl.remove();
            });
        } else {
            // Fallback to alert
            alert(`${title}: ${message}`);
        }
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1070';
        document.body.appendChild(container);
        return container;
    }
    
    // Initialize wishlist functionality when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize wishlist counter if exists
        updateWishlistCounter();
        
        // Add CSS for wishlist buttons
        const style = document.createElement('style');
        style.textContent = `
            .wishlist-btn {
                background-color: rgba(0,0,0,0.4);
                border-radius: 50%;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                transition: all 0.2s ease;
                z-index: 5;
            }
            .wishlist-btn:hover {
                background-color: rgba(255,255,255,0.9);
            }
            .wishlist-btn:hover i {
                color: #dc3545 !important;
            }
            .wishlist-btn i.fas {
                color: #dc3545 !important;
            }
        `;
        document.head.appendChild(style);
    });
</script>

<section class="hero-section position-relative py-3 overflow-hidden" style="background: linear-gradient(135deg, #6b3125 0%, #8d4331 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.05;">
        <div class="floating-icon" style="position: absolute; top: 15%; left: 10%; animation: destinationFloat 8s ease-in-out infinite;">
            <i class="fas fa-plane" style="font-size: 2.5rem; color: #f8fafc;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 25%; right: 15%; animation: destinationFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-globe" style="font-size: 2rem; color: #f8fafc;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 20%; left: 20%; animation: destinationFloat 12s ease-in-out infinite;">
            <i class="fas fa-suitcase" style="font-size: 2.25rem; color: #f8fafc;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 30%; right: 25%; animation: destinationFloat 9s ease-in-out infinite reverse;">
            <i class="fas fa-map-marker-alt" style="font-size: 1.75rem; color: #f8fafc;"></i>
        </div>
    </div>
    
    <div class="container position-relative">
        <!-- Enhanced Header -->
        <div class="mb-3">
            <div class="mb-2">
                <span class="badge px-3 py-2 rounded-pill" style="background: #f59e0b; color: white; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; backdrop-filter: blur(5px);">
                    🔍 FIND YOUR PERFECT FLIGHT
                </span>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3 justify-content-start">
                <button class="nav-tab-btn active" data-tab="flights" style="font-size: 14px; padding: 0.5rem 0.75rem;">
                    <i class="fas fa-plane me-2" style="font-size: 14px;"></i>
                    <span>Flights</span>
                </button>
                <button class="nav-tab-btn" data-tab="hotels" style="font-size: 14px; padding: 0.5rem 0.75rem;">
                    <i class="fas fa-building me-2" style="font-size: 14px;"></i>
                    <span>Hotels</span>
                </button>
            </div>

            <!-- Main heading - styled like Popular Destinations -->
            <h2 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.8rem; line-height: 1.3; position: relative;">
                <span class="position-relative">
                    <i class="fas fa-search-location me-2" style="color: #f59e0b; font-size: 1.6rem; vertical-align: middle; filter: drop-shadow(0 0 7px rgba(245, 158, 11, 0.4));"></i>
                    Millions of cheap flights. <span style="color: #f59e0b;">One simple search.</span>
                    <!-- Decorative underline -->
                    <div class="position-absolute w-100" style="bottom: -8px; left: 0; height: 3px; background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.6), transparent); border-radius: 2px;"></div>
                </span>
            </h2>
            
            <p class="mb-3" style="color: #f1f5f9; font-size: 14px; font-weight: 300; max-width: 500px; line-height: 1.5;">
                <span class="d-inline-block" style="border-left: 2px solid #f59e0b; padding-left: 10px;">
                    Find and compare the best flight deals from hundreds of airlines
                    <br>
                    <span style="color: rgba(241, 245, 249, 0.9); font-size: 13px;">
                        Best prices • No hidden fees • Flexible booking options
                    </span>
                </span>
            </p>
        </div>

       <!-- Search Form - Styled like Popular Destinations -->
        <div class="row">
            <div class="col-12">
                <div class="search-form-container bg-white rounded-3 shadow-lg p-3" style="border: none; backdrop-filter: blur(5px); border: 2px solid rgba(255, 255, 255, 0.1); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
                    <!-- Trip Type Selection -->
                    <ul class="nav nav-pills mb-3 justify-content-start" id="tripType" role="tablist">
                        <li class="nav-item me-2" role="presentation">
                            <button class="nav-link active px-3 py-2" id="round-trip-tab" data-bs-toggle="pill" data-bs-target="#round-trip" type="button" style="font-weight: 600; background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; border: none; border-radius: 25px; font-size: 14px; box-shadow: 0 2px 5px rgba(6, 182, 212, 0.3);">
                                <i class="fas fa-exchange-alt me-2" style="font-size: 14px;"></i>Round Trip
                            </button>
                        </li>
                        <li class="nav-item me-2" role="presentation">
                            <button class="nav-link px-3 py-2" id="one-way-tab" data-bs-toggle="pill" data-bs-target="#one-way" type="button" style="font-weight: 600; background: linear-gradient(45deg, #64748b, #475569); color: white; border: none; border-radius: 25px; font-size: 14px; opacity: 0.75;">
                                <i class="fas fa-long-arrow-alt-right me-2" style="font-size: 14px;"></i>One Way
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 py-2" id="multi-city-tab" data-bs-toggle="pill" data-bs-target="#multi-city" type="button" style="font-weight: 600; background: linear-gradient(45deg, #64748b, #475569); color: white; border: none; border-radius: 25px; font-size: 14px; opacity: 0.75;">
                                <i class="fas fa-route me-2" style="font-size: 14px;"></i>Multi-City
                            </button>
                        </li>
                    </ul>

                    <!-- Trip Type Content -->
                    <div class="tab-content" id="tripTypeContent">
                        <!-- Round Trip Form -->
                        <div class="tab-pane fade show active" id="round-trip" role="tabpanel">
                            <form action="/api/search" method="POST">
                                @csrf
                                <input type="hidden" name="trip_type" value="round_trip">
                                <div class="row g-3 align-items-end mb-3">
                                    <!-- From -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label mb-2" style="color: #64748b; font-size: 14px; font-weight: 600;">
                                            <i class="fas fa-plane-departure me-2" style="color: #06b6d4; font-size: 14px;"></i>
                                            From
                                        </label>
                                        <div class="position-relative">
                                            <div class="input-group" style="box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                                <span class="input-group-text bg-light border-0 ps-3 pe-2" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                                    <i class="fas fa-map-marker-alt text-primary" style="font-size: 14px;"></i>
                                                </span>
                                                <input type="text" 
                                                    class="form-control border-0 ps-2 airport-autocomplete" 
                                                    id="rt-origin" 
                                                    name="from" 
                                                    placeholder="City or airport"
                                                    style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; padding: 0.5rem; font-size: 14px; color: #1e293b; height: auto;"
                                                    required autocomplete="off">
                                                <input type="hidden" name="from_iata" id="from_iata">
                                            </div>
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="fromSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- To -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label mb-2" style="color: #64748b; font-size: 14px; font-weight: 600;">
                                            <i class="fas fa-plane-arrival me-2" style="color: #06b6d4; font-size: 14px;"></i>
                                            To
                                        </label>
                                        <div class="position-relative">
                                            <div class="input-group" style="box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                                <span class="input-group-text bg-light border-0 ps-3 pe-2" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                                    <i class="fas fa-map-marker-alt text-danger" style="font-size: 14px;"></i>
                                                </span>
                                                <input type="text" 
                                                    class="form-control border-0 ps-2 airport-autocomplete" 
                                                    id="rt-destination" 
                                                    name="to" 
                                                    placeholder="City or airport"
                                                    style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; padding: 0.5rem; font-size: 14px; color: #1e293b; height: auto;"
                                                    required autocomplete="off">
                                                <input type="hidden" name="to_iata" id="to_iata">
                                            </div>
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="toSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- Depart -->
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label mb-2" style="color: #64748b; font-size: 14px; font-weight: 600;">
                                            <i class="fas fa-calendar-alt me-2" style="color: #06b6d4; font-size: 14px;"></i>
                                            Depart
                                        </label>
                                        <div class="position-relative">
                                            <div class="input-group" style="box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                                <span class="input-group-text bg-light border-0 ps-3 pe-2" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                                    <i class="fas fa-calendar-day text-success" style="font-size: 14px;"></i>
                                                </span>
                                                <input type="date" 
                                                    class="form-control border-0 ps-2" 
                                                    id="rt-departure" 
                                                    name="date"
                                                    style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; padding: 0.5rem; font-size: 14px; color: #1e293b; height: auto;"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Return -->
                                    <div class="col-lg-1 col-md-3">
                                        <label class="form-label mb-2" style="color: #64748b; font-size: 14px; font-weight: 600;">
                                            <i class="fas fa-calendar-alt me-2" style="color: #06b6d4; font-size: 14px;"></i>
                                            Return
                                        </label>
                                        <div class="position-relative">
                                            <div class="input-group" style="box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                                                <span class="input-group-text bg-light border-0 ps-3 pe-2" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                                                    <i class="fas fa-calendar-check text-warning" style="font-size: 14px;"></i>
                                                </span>
                                                <input type="date" 
                                                    class="form-control border-0 ps-2" 
                                                    id="rt-return" 
                                                    name="return_date"
                                                    style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; padding: 0.5rem; font-size: 14px; color: #1e293b; height: auto;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Adults -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-2" style="font-family: 'Jost', sans-serif; font-size: 14px;">Adults</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 14px;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="adults" 
                                                value="1" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 14px; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-2" style="font-family: 'Jost', sans-serif; font-size: 14px;">Children</label>
                                        <div class="position-relative">
                                            <i class="fas fa-child position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 14px;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="children" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 14px; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Infants -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-2" style="font-family: 'Jost', sans-serif; font-size: 14px;">Infants</label>
                                        <div class="position-relative">
                                            <i class="fas fa-baby position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 14px;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="infants" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 14px; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Travel Class -->
                                    <div class="col-lg-2 col-md-3">
                                        <label class="form-label fw-medium text-muted mb-2" style="font-family: 'Jost', sans-serif; font-size: 14px;">Travel Class</label>
                                        <div class="position-relative">
                                            <i class="fas fa-star position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 14px;"></i>
                                            <select class="form-select border-2 ps-4" 
                                                    name="travel_class"
                                                    style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 14px; font-family: 'Jost', sans-serif; color: #000000;">
                                                <option value="ECONOMY">Economy</option>
                                                <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                                <option value="BUSINESS">Business</option>
                                                <option value="FIRST">First Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Search button -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label mb-2 invisible" style="color: #64748b; font-size: 14px; font-weight: 600;">
                                            <i class="fas fa-search me-2" style="color: #06b6d4; font-size: 14px;"></i>
                                            Search
                                        </label>
                                        <button type="submit" class="btn w-100 py-2 fw-bold rounded-pill position-relative overflow-hidden" 
                                            style="background: linear-gradient(45deg, #f59e0b, #d97706); border: none; color: white; transition: all 0.3s ease; font-size: 14px; box-shadow: 0 3px 8px rgba(245, 158, 11, 0.3);">
                                            <span class="position-relative">
                                                <i class="fas fa-paper-plane me-2" style="font-size: 14px;"></i>
                                                <span>Search Flights</span>
                                                <span class="spinner-border spinner-border-sm d-none ms-1" role="status" aria-hidden="true" style="font-size: 14px; width: 1rem; height: 1rem;"></span>
                                            </span>
                                            <div class="position-absolute top-0 start-0 w-0 h-100 bg-white opacity-20" 
                                                style="transition: all 0.5s ease;"></div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Search options -->
                                <div class="d-flex flex-wrap gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rt-nearbyAirports" name="nearby_airports" style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-muted" for="rt-nearbyAirports" style="font-family: 'Jost', sans-serif; font-size: 14px; padding-left: 5px;">
                                            Add nearby airports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rt-directFlights" name="non_stop" style="width: 18px; height: 18px;">
                                        <label class="form-check-label text-muted" for="rt-directFlights" style="font-family: 'Jost', sans-serif; font-size: 14px; padding-left: 5px;">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- One Way Trip Form -->
                        <div class="tab-pane fade" id="one-way" role="tabpanel">
                            <form action="/api/search" method="POST">
                                @csrf
                                <input type="hidden" name="trip_type" value="one_way">
                                <div class="row g-2 align-items-end mb-3">
                                    <!-- From -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">From</label>
                                        <div class="position-relative">
                                            <i class="fas fa-plane-departure position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="text" 
                                                class="form-control border-2 ps-4 airport-autocomplete" 
                                                id="ow-origin" 
                                                name="from" 
                                                placeholder="Country, city or airport"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required autocomplete="off">
                                            <input type="hidden" name="from_iata" id="ow-from-iata">
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="owFromSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- To -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">To</label>
                                        <div class="position-relative">
                                            <i class="fas fa-plane-arrival position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="text" 
                                                class="form-control border-2 ps-4 airport-autocomplete" 
                                                id="ow-destination" 
                                                name="to" 
                                                placeholder="Country, city or airport"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required autocomplete="off">
                                            <input type="hidden" name="to_iata" id="ow-to-iata">
                                        </div>
                                        <div class="airport-suggestions position-absolute w-100 mt-1 bg-white rounded shadow-lg" id="owToSuggestions" style="z-index: 1000;"></div>
                                    </div>

                                    <!-- Depart -->
                                    <div class="col-lg-2 col-md-4">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Depart</label>
                                        <div class="position-relative">
                                            <i class="fas fa-calendar position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="date" 
                                                class="form-control border-2 ps-4" 
                                                id="ow-departure" 
                                                name="date"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Adults -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Adults</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="adults" 
                                                value="1" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Children</label>
                                        <div class="position-relative">
                                            <i class="fas fa-child position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="children" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Infants -->
                                    <div class="col-lg-1 col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Infants</label>
                                        <div class="position-relative">
                                            <i class="fas fa-baby position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="infants" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Travel Class -->
                                    <div class="col-lg-2 col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Travel Class</label>
                                        <div class="position-relative">
                                            <i class="fas fa-star position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <select class="form-select border-2 ps-4" 
                                                    name="travel_class"
                                                    style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                                <option value="ECONOMY">Economy</option>
                                                <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                                <option value="BUSINESS">Business</option>
                                                <option value="FIRST">First Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Search button -->
                                    <div class="col-lg-1 col-md-3">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 8px; font-family: 'Jost', sans-serif; font-weight: 700;">
                                            <i class="fas fa-search me-1"></i>
                                            <span>Search</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Search options -->
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ow-nearbyAirports" name="nearby_airports">
                                        <label class="form-check-label text-muted small" for="ow-nearbyAirports" style="font-family: 'Jost', sans-serif;">
                                            Add nearby airports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ow-directFlights" name="non_stop" value="true">
                                        <label class="form-check-label text-muted small" for="ow-directFlights" style="font-family: 'Jost', sans-serif;">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Multi-City Trip Form -->
                        <div class="tab-pane fade" id="multi-city" role="tabpanel">
                            <form action="/api/search" method="POST">
                                @csrf
                                <input type="hidden" name="trip_type" value="multi_city">
                                <div id="multi-city-flights">
                                    <!-- Flight Segment 1 -->
                                    <div class="flight-segment mb-3 p-3 border rounded" style="background-color: #f8f9fa;">
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
                                    </div>
                                </div>

                                <!-- Add Another Flight Button -->
                                <div class="text-center mb-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addFlightSegment()" style="font-family: 'Jost', sans-serif;">
                                        <i class="fas fa-plus me-2"></i>Add Another Flight
                                    </button>
                                </div>

                                <!-- Passengers & Class Row -->
                                <div class="row g-2 align-items-end mb-3">
                                    <!-- Adults -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Adults</label>
                                        <div class="position-relative">
                                            <i class="fas fa-user position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="adults" 
                                                value="1" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Children -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Children</label>
                                        <div class="position-relative">
                                            <i class="fas fa-child position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="children" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Infants -->
                                    <div class="col-md-2">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Infants</label>
                                        <div class="position-relative">
                                            <i class="fas fa-baby position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <input type="number" 
                                                class="form-control border-2 ps-4 text-center" 
                                                name="infants" 
                                                value="0" 
                                                min="0" 
                                                max="10"
                                                style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                        </div>
                                    </div>

                                    <!-- Travel Class -->
                                    <div class="col-md-3">
                                        <label class="form-label fw-medium text-muted mb-1 small" style="font-family: 'Jost', sans-serif;">Travel Class</label>
                                        <div class="position-relative">
                                            <i class="fas fa-star position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); z-index: 5; font-size: 0.8rem;"></i>
                                            <select class="form-select border-2 ps-4" 
                                                    name="travel_class"
                                                    style="border-radius: 8px; padding: 0.5rem 0.5rem 0.5rem 2rem; border-color: #e5e7eb; font-size: 0.9rem; font-family: 'Jost', sans-serif; color: #000000;">
                                                <option value="ECONOMY">Economy</option>
                                                <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                                <option value="BUSINESS">Business</option>
                                                <option value="FIRST">First Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Search button -->
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center" style="border-radius: 8px; font-family: 'Jost', sans-serif; font-weight: 700;">
                                            <i class="fas fa-search me-1"></i>
                                            <span>Search Flights</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Search options -->
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mc-nearbyAirports" name="nearby_airports">
                                        <label class="form-check-label text-muted small" for="mc-nearbyAirports" style="font-family: 'Jost', sans-serif;">
                                            Add nearby airports
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="mc-directFlights" name="non_stop" value="true">
                                        <label class="form-check-label text-muted small" for="mc-directFlights" style="font-family: 'Jost', sans-serif;">
                                            Non-stop flights only
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Enhanced Flight Results Table -->
                    @if(isset($flights) && count($flights) > 0)
                        <div class="mt-4">
                            <h3 class="text-center text-dark mb-3" style="font-family: 'Jost', sans-serif; font-weight: 700;">
                                <i class="fas fa-list-alt text-warning me-2"></i>
                                {{ count($flights) }} Results Found
                            </h3>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col"><i class="fas fa-hashtag me-1"></i>Flight #</th>
                                            <th scope="col"><i class="fas fa-plane me-1"></i>Airline</th>
                                            <th scope="col"><i class="fas fa-route me-1"></i>Route</th>
                                            <th scope="col"><i class="fas fa-clock me-1"></i>Duration</th>
                                            <th scope="col"><i class="fas fa-layer-group me-1"></i>Stops</th>
                                            <th scope="col"><i class="fas fa-chair me-1"></i>Class</th>
                                            <th scope="col"><i class="fas fa-euro-sign me-1"></i>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flights as $flight)
                                            @php
                                                $firstSegment = $flight->itineraries[0]->segments[0];
                                                $lastSegment = end($flight->itineraries[0]->segments);
                                                $carrierCode = $firstSegment->carrierCode;
                                                $airlineName = isset($flight->dictionaries->carriers->{$carrierCode}) 
                                                    ? $flight->dictionaries->carriers->{$carrierCode} 
                                                    : $carrierCode;
                                                $numberOfStops = count($flight->itineraries[0]->segments) - 1;
                                                $cabin = $flight->travelerPricings[0]->fareDetailsBySegment[0]->cabin ?? 'N/A';
                                            @endphp
                                            <tr onclick="document.getElementById('{{ 'form'.$flight->id }}').submit()" style="cursor: pointer;">
                                                <th scope="row">{{ $flight->id }}</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="airline-logo me-2 d-flex align-items-center justify-content-center" 
                                                            style="width: 30px; height: 30px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; color: white; font-weight: bold; font-size: 0.8rem;">
                                                            {{ $carrierCode }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold" style="font-size: 0.9rem;">{{ $airlineName }}</div>
                                                            <small class="text-muted">{{ $carrierCode }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="route-info">
                                                        <div class="fw-bold">{{ $firstSegment->departure->iataCode }} → {{ $lastSegment->arrival->iataCode }}</div>
                                                        <small class="text-muted">
                                                            {{ date('H:i', strtotime($firstSegment->departure->at)) }} - 
                                                            {{ date('H:i', strtotime($lastSegment->arrival->at)) }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $flight->itineraries[0]->duration }}</span>
                                                </td>
                                                <td>
                                                    @if($numberOfStops == 0)
                                                        <span class="badge bg-success">Direct</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $numberOfStops }} Stop{{ $numberOfStops > 1 ? 's' : '' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', strtolower($cabin))) }}</span>
                                                </td>
                                                <td>
                                                    <div class="price-info">
                                                        <div class="fw-bold text-success fs-5">{{ $flight->price->currency }} {{ $flight->price->total }}</div>
                                                        <small class="text-muted">{{ $flight->price->currency }} {{ $flight->price->base }} + taxes</small>
                                                    </div>
                                                </td>
                                            </tr>

                                            <form action="/api/price" hidden id="{{ 'form' . $flight->id }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="flight" value="{{ json_encode($flight) }}">
                                            </form>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    
</section>

<!-- Service Cards Section - Enhanced with Modern Style like Popular Destinations -->
<section class="service-cards py-3 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.05;">
        <div class="floating-icon" style="position: absolute; top: 15%; left: 10%; animation: destinationFloat 8s ease-in-out infinite;">
            <i class="fas fa-briefcase" style="font-size: 2.5rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 25%; right: 15%; animation: destinationFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-passport" style="font-size: 2rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 20%; left: 20%; animation: destinationFloat 12s ease-in-out infinite;">
            <i class="fas fa-map" style="font-size: 2.25rem; color: #94a3b8;"></i>
        </div>
    </div>

    <div class="container position-relative">
        <!-- Enhanced Header -->
        <div class="text-center mb-3">
            <div class="mb-2">
                <span class="badge px-3 py-2 rounded-pill" style="background: #6b3125; color: white; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; backdrop-filter: blur(5px);">
                    ✨ OUR SERVICES
                </span>
            </div>
            
            <h2 class="fw-bold mb-3" style="color: #1e293b; font-size: 24px; line-height: 1.3; position: relative;">
                <span class="position-relative">
                    <i class="fas fa-concierge-bell me-2" style="color: #f59e0b; font-size: 22px; vertical-align: middle; filter: drop-shadow(0 0 7px rgba(245, 158, 11, 0.4));"></i>
                    Travel Services
                    <!-- Decorative underline -->
                    <div class="position-absolute w-100" style="bottom: -6px; left: 0; height: 2px; background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.6), transparent); border-radius: 2px;"></div>
                </span>
            </h2>
            
            <p class="mb-3" style="color: #64748b; font-size: 14px; font-weight: 400; max-width: 500px; margin: 0 auto; line-height: 1.5;">
                <span class="d-inline-block" style="border-left: 2px solid #f59e0b; padding-left: 10px;">
                    Comprehensive travel solutions tailored to your needs
                    <br>
                    <span style="color: rgba(100, 116, 139, 0.8); font-size: 14px;">
                        Expert assistance • 24/7 support • Personalized service
                    </span>
                </span>
            </p>
        </div>
        
        <div class="row g-3">
            <div class="col-md-4">
                <div class="service-card service-card-enhanced position-relative overflow-hidden rounded-3 p-3 text-white h-100" 
                      style="background: #6b3125 !important; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                    <!-- Background Overlay Effect -->
                    <div class="service-card-overlay position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(135deg, rgba(255, 193, 7, 0.9), rgba(255, 152, 0, 0.8)); opacity: 0; transition: all 0.4s ease;"></div>
                    
                    <!-- Animated Background Elements -->
                    <div class="service-bg-elements position-absolute w-100 h-100" style="top: 0; left: 0; opacity: 0; transition: all 0.4s ease;">
                        <div class="floating-element" style="position: absolute; top: 10%; right: 15%; animation: serviceFloat 3s ease-in-out infinite;">
                            <i class="fas fa-plane" style="color: rgba(255, 255, 255, 0.2); font-size: 2rem; transform: rotate(-15deg);"></i>
                        </div>
                        <div class="floating-element" style="position: absolute; bottom: 20%; left: 10%; animation: serviceFloat 3s ease-in-out infinite reverse;">
                            <i class="fas fa-map-marked-alt" style="color: rgba(255, 255, 255, 0.15); font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="service-card-content position-relative z-index-2">
                        <div class="service-icon-wrapper mb-2 position-relative">
                            <i class="fas fa-plane fs-4 service-icon" style="transition: all 0.4s ease; display: inline-block;"></i>
                            <!-- Icon Glow Effect -->
                            <div class="icon-glow position-absolute rounded-circle" style="top: -5px; left: -5px; width: 40px; height: 40px; background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%); opacity: 0; transition: all 0.4s ease;"></div>
                        </div>
                        <h3 class="fs-5 fw-bold mb-1 service-title" style="transition: all 0.3s ease;">Flights</h3>
                        <p class="mb-0 opacity-90 small service-description" style="transition: all 0.3s ease;">Discover new destinations</p>
                        
                        <!-- Hidden CTA Button (appears on hover) -->
                        <div class="service-cta mt-3" style="opacity: 0; transform: translateY(10px); transition: all 0.4s ease;">
                            <button class="btn btn-light btn-sm rounded-pill px-3" style="font-size: 0.8rem; font-weight: 600;">
                                <i class="fas fa-arrow-right me-1"></i>Explore Flights
                            </button>
                        </div>
                    </div>
                    
                    <!-- Shine Effect -->
                    <div class="shine-effect position-absolute w-100 h-100" style="top: 0; left: -100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent); transition: all 0.6s ease;"></div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card service-card-enhanced position-relative overflow-hidden rounded-3 p-3 text-white h-100" 
                       style="background: #6b3125 !important; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                    <!-- Background Overlay Effect -->
                    <div class="service-card-overlay position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(135deg, rgba(59, 130, 246, 0.9), rgba(29, 78, 216, 0.8)); opacity: 0; transition: all 0.4s ease;"></div>
                    
                    <!-- Animated Background Elements -->
                    <div class="service-bg-elements position-absolute w-100 h-100" style="top: 0; left: 0; opacity: 0; transition: all 0.4s ease;">
                        <div class="floating-element" style="position: absolute; top: 15%; right: 10%; animation: serviceFloat 3s ease-in-out infinite;">
                            <i class="fas fa-bed" style="color: rgba(255, 255, 255, 0.2); font-size: 1.8rem;"></i>
                        </div>
                        <div class="floating-element" style="position: absolute; bottom: 15%; left: 15%; animation: serviceFloat 3s ease-in-out infinite reverse;">
                            <i class="fas fa-concierge-bell" style="color: rgba(255, 255, 255, 0.15); font-size: 1.3rem;"></i>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="service-card-content position-relative z-index-2">
                        <div class="service-icon-wrapper mb-2 position-relative">
                            <i class="fas fa-building fs-4 service-icon" style="transition: all 0.4s ease; display: inline-block;"></i>
                            <div class="icon-glow position-absolute rounded-circle" style="top: -5px; left: -5px; width: 40px; height: 40px; background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%); opacity: 0; transition: all 0.4s ease;"></div>
                        </div>
                        <h3 class="fs-5 fw-bold mb-1 service-title" style="transition: all 0.3s ease;">Hotels</h3>
                        <p class="mb-0 opacity-90 small service-description" style="transition: all 0.3s ease;">Find the perfect stay</p>
                        
                        <div class="service-cta mt-3" style="opacity: 0; transform: translateY(10px); transition: all 0.4s ease;">
                            <button class="btn btn-light btn-sm rounded-pill px-3" style="font-size: 0.8rem; font-weight: 600;">
                                <i class="fas fa-search me-1"></i>Find Hotels
                            </button>
                        </div>
                    </div>
                    
                    <div class="shine-effect position-absolute w-100 h-100" style="top: 0; left: -100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent); transition: all 0.6s ease;"></div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card service-card-enhanced position-relative overflow-hidden rounded-3 p-3 text-white h-100" 
                      style="background: #6b3125 !important; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                    <!-- Background Overlay Effect -->
                    <div class="service-card-overlay position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(135deg, rgba(34, 197, 94, 0.9), rgba(22, 163, 74, 0.8)); opacity: 0; transition: all 0.4s ease;"></div>
                    
                    <!-- Animated Background Elements -->
                    <div class="service-bg-elements position-absolute w-100 h-100" style="top: 0; left: 0; opacity: 0; transition: all 0.4s ease;">
                        <div class="floating-element" style="position: absolute; top: 10%; right: 20%; animation: serviceFloat 3s ease-in-out infinite;">
                            <i class="fas fa-compass" style="color: rgba(255, 255, 255, 0.2); font-size: 1.8rem;"></i>
                        </div>
                        <div class="floating-element" style="position: absolute; bottom: 20%; left: 10%; animation: serviceFloat 3s ease-in-out infinite reverse;">
                            <i class="fas fa-mountain" style="color: rgba(255, 255, 255, 0.15); font-size: 1.4rem;"></i>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="service-card-content position-relative z-index-2">
                        <div class="service-icon-wrapper mb-2 position-relative">
                            <i class="fas fa-globe fs-4 service-icon" style="transition: all 0.4s ease; display: inline-block;"></i>
                            <div class="icon-glow position-absolute rounded-circle" style="top: -5px; left: -5px; width: 40px; height: 40px; background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%); opacity: 0; transition: all 0.4s ease;"></div>
                        </div>
                        <h3 class="fs-5 fw-bold mb-1 service-title" style="transition: all 0.3s ease;">Explore everywhere</h3>
                        <p class="mb-0 opacity-90 small service-description" style="transition: all 0.3s ease;">Explore at your own pace</p>
                        
                        <div class="service-cta mt-3" style="opacity: 0; transform: translateY(10px); transition: all 0.4s ease;">
                            <button class="btn btn-light btn-sm rounded-pill px-3" style="font-size: 0.8rem; font-weight: 600;">
                                <i class="fas fa-compass me-1"></i>Start Exploring
                            </button>
                        </div>
                    </div>
                    
                    <div class="shine-effect position-absolute w-100 h-100" style="top: 0; left: -100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent); transition: all 0.6s ease;"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- AI Trip Assistant Section -->
{{-- <section class="ai-trip-assistant py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="mb-3">
                <span class="badge px-4 py-2 rounded-pill" style="background: rgba(255, 255, 255, 0.2); color: white; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">
                    🤖 AI-POWERED ASSISTANT
                </span>
            </div>
            
            <h2 class="fw-bold text-white mb-3" style="font-size: 2.5rem; line-height: 1.2;">
                <i class="fas fa-robot me-3" style="font-size: 2rem; vertical-align: middle;"></i>
                AI Trip Planner
            </h2>
            
            <p class="text-white opacity-90 mb-4" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Let our AI assistant help you plan the perfect trip with personalized recommendations
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white rounded-3 shadow-lg p-4">
                    <form id="aiTripPlannerForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Where do you want to go?</label>
                                <input type="text" class="form-control" name="destination" placeholder="e.g., Dubai, Paris, Tokyo" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">How long is your trip?</label>
                                <select class="form-select" name="duration" required>
                                    <option value="">Select duration</option>
                                    <option value="1-2 days">1-2 days</option>
                                    <option value="3-5 days">3-5 days</option>
                                    <option value="1 week">1 week</option>
                                    <option value="2 weeks">2 weeks</option>
                                    <option value="1 month">1 month</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Budget range</label>
                                <select class="form-select" name="budget" required>
                                    <option value="">Select budget</option>
                                    <option value="Under $500">Under $500</option>
                                    <option value="$500 - $1000">$500 - $1000</option>
                                    <option value="$1000 - $2000">$1000 - $2000</option>
                                    <option value="$2000 - $5000">$2000 - $5000</option>
                                    <option value="Above $5000">Above $5000</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">When are you traveling?</label>
                                <input type="text" class="form-control" name="departure_date" placeholder="e.g., next weekend, December">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium">What are you interested in?</label>
                                <input type="text" class="form-control" name="interests" placeholder="e.g., culture, adventure, food, relaxation, nightlife">
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="fas fa-magic me-2"></i>
                                Generate My Trip Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- AI Trip Results -->
        <div id="aiTripResults" class="mt-5" style="display: none;">
            <div class="bg-white rounded-3 shadow-lg p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">
                        <i class="fas fa-map-marked-alt text-primary me-2"></i>
                        Your AI-Generated Trip Plan
                    </h3>
                    <button class="btn btn-outline-primary btn-sm" onclick="downloadTripPlan()">
                        <i class="fas fa-download me-1"></i>
                        Download PDF
                    </button>
                </div>
                
                <div id="tripPlanContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</section> --}}




<!-- Bottom Hero Section - Enhanced with Popular Destinations Style -->
<section class="bottom-hero position-relative" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/airplane.jpg') }}'); background-size: cover; background-position: center; border-radius: 8px; min-height: 137px; width: 85%; margin: 1.75rem auto 1rem auto; box-shadow: 0 5px 10px rgba(0,0,0,0.2); overflow: hidden;">
    <!-- Animated overlay for depth effect -->
    <div class="position-absolute w-100 h-100 top-0 left-0" style="background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);"></div>
    
    <!-- Subtle animated elements -->
    <div class="position-absolute" style="top: 10%; right: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(40px); animation: float 8s ease-in-out infinite;"></div>
    <div class="position-absolute" style="bottom: 15%; left: 15%; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(50px); animation: float 12s ease-in-out infinite 1s;"></div>
    
    <div class="position-relative h-100 d-flex align-items-center text-start">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <!-- Badge -->
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark mt-4 px-3 py-2 rounded-pill fw-bold" style="font-size: 0.9rem; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);">
                            <i class="fas fa-compass me-2"></i>EXPLORE THE WORLD
                        </span>
                    </div>
                    
                    <p class="text-white fs-6 mb-2 opacity-90" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-weight: 400; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Can't decide where to go?</p>
                    
                    <h2 class="fw-bold text-white mb-3" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-weight: 900; font-size: 2.2rem; line-height: 1.2; color: rgb(255, 255, 255); text-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                        Explore every destination
                        <div class="mt-2" style="width: 100px; height: 5px; background: linear-gradient(90deg, #ffc107, transparent); border-radius: 10px;"></div>
                    </h2>
                    
                    <p class="text-white mb-3 opacity-90" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; max-width: 600px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); font-size: 1rem;">
                        Discover breathtaking destinations, incredible deals, and unforgettable experiences across the globe with our extensive flight network.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <button class="btn btn-light px-4 py-2 fw-bold rounded-pill d-inline-flex align-items-center" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; box-shadow: 0 5px 15px rgba(255,255,255,0.2); transition: all 0.3s ease; font-size: 0.95rem;">
                            <i class="fas fa-search me-2"></i>
                            <span>Search flights</span>
                        </button>
                        <button class="btn btn-outline-light px-4 py-2 fw-bold rounded-pill d-inline-flex align-items-center" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; border-width: 2px; transition: all 0.3s ease; font-size: 0.95rem;">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            <span>View destinations</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Diagonal shape decoration -->
    <div class="position-absolute" style="bottom: 0; right: 0; width: 40%; height: 100%; background: linear-gradient(135deg, transparent 50%, rgba(255,255,255,0.1) 100%);"></div>
    
    <!-- Floating elements -->
    <div class="position-absolute" style="top: 15%; right: 10%; animation: pulse 4s infinite ease-in-out;">
        <i class="fas fa-paper-plane text-white opacity-50" style="font-size: 2.5rem; transform: rotate(-15deg);"></i>
    </div>
    <div class="position-absolute" style="bottom: 20%; right: 20%; animation: float 6s infinite ease-in-out;">
        <i class="fas fa-globe-americas text-white opacity-50" style="font-size: 3rem;"></i>
    </div>
</section>





<!-- FAQ Section - Enhanced with Popular Destinations Style -->
<section class="faq-section py-3 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.05;">
        <div class="floating-icon" style="position: absolute; top: 15%; left: 10%; animation: destinationFloat 8s ease-in-out infinite;">
            <i class="fas fa-question-circle" style="font-size: 2.5rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 25%; right: 15%; animation: destinationFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-info-circle" style="font-size: 2rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 20%; left: 20%; animation: destinationFloat 12s ease-in-out infinite;">
            <i class="fas fa-lightbulb" style="font-size: 2.25rem; color: #94a3b8;"></i>
        </div>
    </div>

    <div class="container position-relative">
        <!-- Enhanced Header -->
        <div class="text-center mb-4">
            <div class="mb-2">
                <span class="badge px-3 py-2 rounded-pill" style="background: #6b3125; color: white; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; backdrop-filter: blur(5px);">
                    💬 FREQUENTLY ASKED
                </span>
            </div>
            
            <h2 class="fw-bold mb-3" style="color: #1e293b; font-size: 1.8rem; line-height: 1.3; position: relative;">
                <span class="position-relative">
                    <i class="fas fa-comments me-2" style="color: #f59e0b; font-size: 1.6rem; vertical-align: middle; filter: drop-shadow(0 0 7px rgba(245, 158, 11, 0.4));"></i>
                    Booking Travel with Ziara Travels
                    <!-- Decorative underline -->
                    <div class="position-absolute w-100" style="bottom: -8px; left: 0; height: 3px; background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.6), transparent); border-radius: 2px;"></div>
                </span>
            </h2>
            
            <p class="mb-4" style="color: #64748b; font-size: 14px; font-weight: 300; max-width: 500px; margin: 0 auto; line-height: 1.5;">
                <span class="d-inline-block" style="border-left: 2px solid #f59e0b; padding-left: 10px;">
                    Your most common questions about booking with us answered
                    <br>
                    <span style="color: rgba(100, 116, 139, 0.8); font-size: 13px;">
                        Clear explanations • Helpful tips • Insider advice
                    </span>
                </span>
            </p>
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1" style="font-size: 15px; padding: 15px 20px;">
                                <i class="fas fa-question-circle me-2 text-primary"></i>
                                How does Ziara Travels work?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" aria-labelledby="headingOne">
                            <div class="accordion-body" style="font-size: 14px; line-height: 1.6; padding: 16px 20px;">
                                Ziara Travels compares flights from hundreds of airlines and travel agents to find you the best deals at the most competitive prices.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                How can I find the cheapest flight using Ziara Travels?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                            <div class="accordion-body">
                                Use our flexible date search, price alerts, and our exclusive deals to track the best offers for your preferred route. Our AI-powered system helps identify optimal booking times.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                What destinations does Ziara Travels recommend?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" aria-labelledby="headingThree">
                            <div class="accordion-body">
                                Check out our curated trending destinations, seasonal specials, and local experiences for travel inspiration tailored to your preferences.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                How does the booking process work with Ziara Travels?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" aria-labelledby="headingFour">
                            <div class="accordion-body">
                                You can complete your booking directly through our secure platform or we can connect you with our trusted partner airlines and travel agents, depending on your preference.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                                What happens after I've booked my trip?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" aria-labelledby="headingFive">
                            <div class="accordion-body">
                                You'll receive instant confirmation via email and SMS, along with your e-ticket and itinerary details. Our 24/7 customer service team will be available to assist with any questions.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                                Does Ziara Travels offer accommodation options?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" aria-labelledby="headingSix">
                            <div class="accordion-body">
                                Yes! We offer a wide range of accommodations from luxury hotels to budget-friendly stays, with exclusive discounts for our loyalty members. Our experts personally vet properties for quality.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                                What about transportation at my destination?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" aria-labelledby="headingSeven">
                            <div class="accordion-body">
                                Ziara Travels offers competitive car rental rates from trusted providers and can arrange airport transfers, chauffeur services, and guided tours to enhance your travel experience.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                                What's the Ziara Price Promise?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" aria-labelledby="headingEight">
                            <div class="accordion-body">
                                Our Price Promise guarantees that if you find a lower price for the same itinerary elsewhere within 24 hours of booking with us, we'll match it and give you Ziara Reward Points worth 10% of the difference.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9">
                                What is Ziara's cancellation policy?
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse" aria-labelledby="headingNine">
                            <div class="accordion-body">
                                We offer flexible booking options with free cancellation on many hotels and the ability to purchase travel insurance with enhanced cancellation coverage for flights. Our premium tier members enjoy additional flexibility benefits.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="accordion faq-accordion mb-3">
                    <div class="accordion-item faq-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed faq-question" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="false" aria-controls="faq10">
                                Does Ziara Travels offer sustainable travel options?
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse" aria-labelledby="headingTen">
                            <div class="accordion-body">
                                Yes, we're committed to sustainable tourism. We provide carbon emissions data for flights, offer eco-friendly accommodation options, and contribute to local conservation projects with each booking through our "Travel Green" initiative.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Get all FAQ questions
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-bs-target');
            const targetCollapse = document.querySelector(targetId);
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Close all other accordions
            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== this) {
                    const otherTargetId = otherQuestion.getAttribute('data-bs-target');
                    const otherCollapse = document.querySelector(otherTargetId);
                    
                    otherQuestion.setAttribute('aria-expanded', 'false');
                    otherCollapse.classList.remove('show');
                }
            });
            
            // Toggle current accordion
            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                targetCollapse.classList.remove('show');
            } else {
                this.setAttribute('aria-expanded', 'true');
                targetCollapse.classList.add('show');
            }
        });
    });
    
    // Handle Bootstrap collapse events for smooth animation
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(collapse => {
        collapse.addEventListener('show.bs.collapse', function() {
            const question = document.querySelector(`[data-bs-target="#${this.id}"]`);
            question.setAttribute('aria-expanded', 'true');
        });
        
        collapse.addEventListener('hide.bs.collapse', function() {
            const question = document.querySelector(`[data-bs-target="#${this.id}"]`);
            question.setAttribute('aria-expanded', 'false');
        });
    });
    });
</script>
</section>







<!-- Flight Deals Section - Styled like Hotel Deals Section -->
<section class="flight-section-hero position-relative" style="background-color: #fffff;">
    <div class="container h-100">
        <div class="text-center mb-1 position-relative">
            <!-- Animated Background Elements - Flight Theme -->
            <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none;">
                <div class="floating-icon" style="position: absolute; top: 20%; left: 15%; animation: fly 8s ease-in-out infinite;">
                    <i class="fas fa-plane" style="color: rgba(255, 255, 255, 0.15); font-size: 1.5rem; transform: rotate(-15deg);"></i>
                </div>
                <div class="floating-icon" style="position: absolute; top: 25%; right: 20%; animation: fly 10s ease-in-out infinite reverse;">
                    <i class="fas fa-ticket-alt" style="color: rgba(255, 255, 255, 0.15); font-size: 1.25rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 25%; left: 25%; animation: float 6s ease-in-out infinite;">
                    <i class="fas fa-globe-africa" style="color: rgba(255, 255, 255, 0.15); font-size: 1rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 30%; right: 15%; animation: fly 12s ease-in-out infinite;">
                    <i class="fas fa-cloud" style="color: rgba(255, 255, 255, 0.15); font-size: 1rem;"></i>
                </div>
            </div>
            
            <!-- Main Heading with Enhanced Typography - Reduced Size -->
            <div class="position-relative z-index-2">
                <div class="mb-2">
                    <span class="badge px-3 py-1 rounded-pill" style="background: linear-gradient(45deg, #3B82F6, #60A5FA); color: white; font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">
                        ✈️ FLIGHT DEALS
                    </span>
                </div>
                
                <h2 class="fw-bold mb-2" style="color: white; font-size: 1.5rem; line-height: 1.2; position: relative;">
                    <span class="position-relative">
                        <i class="fas fa-plane-departure me-2" style="color: #60A5FA; font-size: 1.25rem; vertical-align: middle; text-shadow: 0 0 10px rgba(96, 165, 250, 0.3); animation: flightPulse 3s ease-in-out infinite;"></i>
                        Best Flight Offers
                        <!-- Decorative underline -->
                        <div class="position-absolute w-100" style="bottom: -5px; left: 0; height: 2px; background: linear-gradient(90deg, transparent, #60A5FA, transparent); border-radius: 2px;"></div>
                    </span>
                </h2>
                
                <p class="mb-2" style="color: #caced6; font-size: 0.8rem; font-weight: 300; max-width: 450px; margin: 0 auto; line-height: 1.4;">
                    <span class="d-inline-block" style="border-left: 2px solid #60A5FA; padding-left: 10px;">
                        Discover exclusive flight deals across East African destinations
                        <br>
                        <small style="color: rgba(202, 206, 214, 0.8); font-size: 0.7rem;">
                            Direct flights • Flexible dates • Amazing prices
                        </small>
                    </span>
                </p>
                
                <!-- Flight stats indicator -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tag text-primary me-1" style="animation: pulse 2s infinite; color: #60A5FA !important; font-size: 0.7rem;"></i>
                        <span style="color: #caced6; font-size: 0.7rem; font-weight: 500;">
                            Limited Time Offers
                        </span>
                    </div>
                    <div style="width: 1px; height: 12px; background: rgba(96, 165, 250, 0.3);"></div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plane text-primary me-1" style="color: #60A5FA !important; font-size: 0.7rem;"></i>
                        <span style="color: rgba(202, 206, 214, 0.8); font-size: 0.7rem;">
                            <span id="flight-offer-count">9</span> Flight Routes
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="flight-offers-container">
            <div class="col-12 text-center py-2">
                <div class="spinner-border spinner-border-sm" role="status" style="color: #60A5FA;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2" style="color: #caced6; font-size: 11px;">Finding the best flight deals across East Africa...</p>
            </div>
        </div>
    </div>

    <!-- Flight Offer Template - Styled like Hotel Offer Template -->
    <template id="flight-offer-template">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden hover-lift h-100" style="background-color: #24303F; border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="position-relative">
                    <img src="" class="card-img-top destination-image" alt="Destination" style="height: 160px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge rounded-pill px-2 py-1 discount-badge" style="background-color: #3B82F6; font-size: 10px;">20% OFF</span>
                    </div>
                    <div class="position-absolute top-0 start-0 m-2">
                        <button class="btn btn-sm wishlist-btn" data-type="flight" data-item-id="">
                            <i class="far fa-heart text-white" style="font-size: 14px;"></i>
                        </button>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white mb-0 destination-city" style="font-size: 13px;">City Name</h6>
                                <small class="text-white-50 destination-country" style="font-size: 10px;">Country</small>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill airline-badge" style="background-color: #60A5FA; font-size: 10px;">KQ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge text-dark highlight-badge" style="background-color: rgba(96, 165, 250, 0.3); font-size: 9px;"><i class="fas fa-map-marker-alt me-1"></i> <span class="flight-highlight">Highlight</span></span>
                        <span class="badge text-white cabin-badge" style="background-color: #2563EB; font-size: 9px;">Economy</span>
                    </div>
                    
                    <div class="mb-2 travel-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-semibold origin-city" style="color: #caced6; font-size: 12px;">Nairobi</span>
                                <i class="fas fa-arrow-right mx-1" style="color: #60A5FA; font-size: 9px;"></i>
                                <span class="fw-semibold destination-city-small" style="color: #caced6; font-size: 12px;">Mombasa</span>
                            </div>
                            <small class="text-muted departure-date" style="font-size: 10px;">Aug 15, 2023</small>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <div class="d-flex align-items-center me-2">
                                <i class="fas fa-clock text-muted me-1" style="font-size: 9px;"></i>
                                <small class="text-muted flight-duration" style="font-size: 10px;">1h 30m</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-plane-arrival text-muted me-1" style="font-size: 9px;"></i>
                                <small class="text-muted stops-info" style="font-size: 10px;">Direct</small>
                            </div>
                        </div>
                    </div>
                    
                    <p class="card-text mb-2 destination-description" style="color: #9aa0b0; font-size: 10px;">Beautiful coastal city with pristine beaches and rich culture.</p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="price fw-bold current-price" style="color: #60A5FA; font-size: 14px;">KES 17,000</span>
                            <small class="text-muted text-decoration-line-through ms-1 original-price" style="font-size: 9px;">KES 21,000</small>
                        </div>
                        <a href="#" class="btn rounded-pill px-2 py-1 book-button" style="background-color: #3B82F6; color: white; font-size: 10px;">
                            <i class="fas fa-plane me-1" style="font-size: 9px;"></i>Book
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

     <!-- JavaScript for Dynamic Flight Offers with Amadeus API Integration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadFlightOffers();
            
            // Add custom animation for flight icons
            if (!CSS.supports('animation-name: flightPulse')) {
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes flightPulse {
                        0%, 100% { transform: scale(1); opacity: 1; }
                        50% { transform: scale(1.1); opacity: 0.8; }
                    }
                    @keyframes fly {
                        0%, 100% { transform: translateY(0) rotate(0); }
                        50% { transform: translateY(-15px) rotate(5deg); }
                    }
                `;
                document.head.appendChild(style);
            }
        });
        
        function loadFlightOffers() {
            // Show loading spinner
            const container = document.getElementById('flight-offers-container');
            container.innerHTML = `
                <div class="col-12 text-center py-2">
                    <div class="spinner-border spinner-border-sm" role="status" style="color: #60A5FA;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2" style="color: #caced6; font-size: 11px;">Finding the best flight deals across East Africa...</p>
                </div>
            `;
            
            // Test the API endpoint first
            console.log('Testing Amadeus API endpoint...');
            
            // Fetch real-time flight offers from our Amadeus API endpoint
            fetch('/api/amadeus/flights/search')
                .then(response => {
                    console.log('API Response Status:', response.status);
                    console.log('API Response Headers:', response.headers);
                    
                    if (!response.ok) {
                        throw new Error(`API request failed with status: ${response.status} - ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response Data:', data);
                    
                    if (data && data.data && data.data.length > 0) {
                        console.log(`Found ${data.data.length} flight offers`);
                        const processedOffers = processAmadeusFlightOffers(data);
                        displayFlightOffers(processedOffers);
                    } else if (data && data.error) {
                        console.error('API returned error:', data.error, data.details);
                        throw new Error(`API Error: ${data.error}${data.message ? ' - ' + data.message : ''}`);
                    } else {
                        console.warn('No flight offers found in API response:', data);
                        throw new Error('No flight offers found in API response');
                    }
                })
                .catch(error => {
                    console.error('Error fetching flight offers from Amadeus API:', error);
                    
                    // Try backup search with specific parameters
                    console.log('Trying backup search with specific parameters...');
                    fetch('/api/amadeus/flights/search?originLocationCode=NBO&destinationLocationCode=MBA&departureDate=' + 
                          new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0])
                        .then(response => {
                            console.log('Backup API Response Status:', response.status);
                            if (!response.ok) {
                                throw new Error(`Backup API request failed: ${response.status} - ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Backup API Response Data:', data);
                            
                            if (data && data.data && data.data.length > 0) {
                                console.log(`Backup search found ${data.data.length} flight offers`);
                                const processedOffers = processAmadeusFlightOffers(data);
                                displayFlightOffers(processedOffers);
                            } else if (data && data.error) {
                                console.error('Backup API returned error:', data.error, data.details);
                                throw new Error(`Backup API Error: ${data.error}`);
                            } else {
                                console.warn('No flight offers found in backup response:', data);
                                throw new Error('No flight offers found in backup response');
                            }
                        })
                        .catch(backupError => {
                            console.error('Error fetching backup flight offers:', backupError);
                            // Show detailed error message
                            container.innerHTML = `
                                <div class="col-12 text-center py-3">
                                    <div class="alert alert-warning" role="alert">
                                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Unable to Load Flight Deals</h6>
                                        <p class="mb-2" style="font-size: 0.9rem;">We're experiencing difficulty connecting to our flight search service.</p>
                                        <hr>
                                        <p class="mb-0" style="font-size: 0.8rem;">
                                            <strong>Details:</strong> ${backupError.message}<br>
                                            <small class="text-muted">Please try refreshing the page or contact support if the issue persists.</small>
                                        </p>
                                        <button class="btn btn-outline-primary btn-sm mt-2" onclick="loadFlightOffers()">
                                            <i class="fas fa-redo me-1"></i>Try Again
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                });
        }
        
        // Process raw Amadeus API flight offers into our format
        function processAmadeusFlightOffers(amadeusResponse) {
            const processedOffers = [];
            const destinations = {
                'NBO': {city: 'Nairobi', country: 'Kenya', highlight: 'Capital City', description: 'Vibrant capital city with wildlife and urban attractions'},
                'MBA': {city: 'Mombasa', country: 'Kenya', highlight: 'Coastal Gem', description: 'Coastal city with rich history and beaches'},
                'ZNZ': {city: 'Zanzibar', country: 'Tanzania', highlight: 'Beautiful Beaches', description: 'Crystal clear waters and pristine beaches'},
                'EBB': {city: 'Entebbe', country: 'Uganda', highlight: 'Gateway to Uganda', description: 'Scenic city on the shores of Lake Victoria'},
                'DAR': {city: 'Dar es Salaam', country: 'Tanzania', highlight: 'Cultural Hub', description: 'Major Tanzanian port with vibrant markets'},
                'JRO': {city: 'Kilimanjaro', country: 'Tanzania', highlight: 'Mountain Adventure', description: 'Gateway to Africa\'s highest peak'},
                'KGL': {city: 'Kigali', country: 'Rwanda', highlight: 'Modern Africa', description: 'Clean, green city with vibrant culture'},
                'ADD': {city: 'Addis Ababa', country: 'Ethiopia', highlight: 'Historic Capital', description: 'Historic capital with rich culture and heritage'}
            };
            
            // Flight highlights
            const flightHighlights = [
                'Best Price', 'Direct Flight', 'Popular Route', 'Weekend Getaway', 
                'Quick Escape', 'Business Trip', 'Vacation Deal', 'Fast Connection'
            ];
            
            // Default discount percentage for offers
            const discountPercent = 20;
            
            // Process each flight offer from the Amadeus API
            amadeusResponse.data.forEach((offer, index) => {
                try {
                    // Extract origin and destination from itinerary
                    const itinerary = offer.itineraries[0];
                    const firstSegment = itinerary.segments[0];
                    const lastSegment = itinerary.segments[itinerary.segments.length - 1];
                    
                    const origin = firstSegment.departure.iataCode;
                    const destination = lastSegment.arrival.iataCode;
                    
                    // Skip if we don't have destination info
                    if (!destinations[destination]) {
                        return;
                    }
                    
                    // Calculate duration in hours and minutes
                    const durationMatch = itinerary.duration.match(/PT(?:(\d+)H)?(?:(\d+)M)?/);
                    const hours = durationMatch[1] ? parseInt(durationMatch[1]) : 0;
                    const minutes = durationMatch[2] ? parseInt(durationMatch[2]) : 0;
                    const formattedDuration = `${hours}h ${minutes}m`;
                    
                    // Calculate stops
                    const stops = itinerary.segments.length - 1;
                    
                    // Get fare details
                    const price = parseFloat(offer.price.total);
                    // Calculate original price (pre-discount)
                    const originalPrice = price / (1 - (discountPercent / 100));
                    
                    // Get airline code from first segment's operating carrier
                    const airlineCode = firstSegment.carrierCode || 
                                       (firstSegment.operating ? firstSegment.operating.carrierCode : 'N/A');
                                       
                    // Format departure date
                    const departureDateObj = new Date(firstSegment.departure.at);
                    const departureDate = departureDateObj.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    
                    // Get the cabin class from the traveler pricing
                    let cabin = 'Economy';
                    if (offer.travelerPricings && offer.travelerPricings.length > 0) {
                        const cabinClass = offer.travelerPricings[0].fareDetailsBySegment[0].cabin;
                        if (cabinClass === 'BUSINESS') cabin = 'Business';
                        else if (cabinClass === 'FIRST') cabin = 'First';
                        else if (cabinClass === 'PREMIUM_ECONOMY') cabin = 'Premium';
                    }
                    
                    // Select a flight highlight randomly or based on properties
                    let highlight = flightHighlights[Math.floor(Math.random() * flightHighlights.length)];
                    if (stops === 0) highlight = 'Direct Flight';
                    if (price < 15000) highlight = 'Best Price';
                    
                    // Format the offer into our template structure
                    const processedOffer = {
                        id: offer.id,
                        origin: origin,
                        originCity: getCity(origin),
                        destination: destination,
                        destinationCity: destinations[destination].city,
                        destinationCountry: destinations[destination].country,
                        destinationImage: `${destinations[destination].city.toLowerCase().replace(/\s+/g, '-')}.jpg`,
                        description: destinations[destination].description,
                        highlight: highlight,
                        departureDate: departureDate,
                        departureTime: departureDateObj.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }),
                        returnDate: '', // One-way flights in this example
                        duration: formattedDuration,
                        price: Math.round(price),
                        originalPrice: Math.round(originalPrice),
                        currency: offer.price.currency,
                        discountPercent: discountPercent,
                        cabin: cabin,
                        airline: airlineCode,
                        stops: stops,
                        // Store original Amadeus offer data for booking
                        amadeusData: offer
                    };
                    
                    processedOffers.push(processedOffer);
                } catch (error) {
                    console.error('Error processing flight offer:', error, offer);
                }
            });
            
            // Function to map IATA codes to city names for origins
            function getCity(iataCode) {
                if (destinations[iataCode]) {
                    return destinations[iataCode].city;
                }
                
                const cityMapping = {
                    'NBO': 'Nairobi',
                    'MBA': 'Mombasa',
                    'ZNZ': 'Zanzibar',
                    'EBB': 'Entebbe',
                    'DAR': 'Dar es Salaam',
                    'JRO': 'Kilimanjaro',
                    'KGL': 'Kigali',
                    'ADD': 'Addis Ababa',
                    'JNB': 'Johannesburg',
                    'CPT': 'Cape Town',
                    'LHR': 'London',
                    'CDG': 'Paris',
                    'DXB': 'Dubai'
                };
                
                return cityMapping[iataCode] || iataCode;
            }
            
            return processedOffers;
        }

        // Display flight offers function
        function displayFlightOffers(offers) {
            const container = document.getElementById('flight-offers-container');
            container.innerHTML = '';
            
            // Update count
            document.getElementById('flight-offer-count').textContent = offers.length;
            
            // Create carousel structure
            const carouselDiv = document.createElement('div');
            carouselDiv.className = 'col-12';
            carouselDiv.innerHTML = `
                <div class="carousel slide" id="flightCarousel">
                    <div class="carousel-inner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#flightCarousel" data-bs-slide="prev" style="width: 5%;">
                        <span class="carousel-control-prev-icon" style="width: 1rem; height: 1rem;"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#flightCarousel" data-bs-slide="next" style="width: 5%;">
                        <span class="carousel-control-next-icon" style="width: 1rem; height: 1rem;"></span>
                    </button>
                </div>
            `;
            container.appendChild(carouselDiv);
            const carouselInner = document.querySelector('#flightCarousel .carousel-inner');
        
            // Group offers by 3 for each carousel slide
            const offerGroups = [];
            for (let i = 0; i < offers.length; i += 3) {
                offerGroups.push(offers.slice(i, i + 3));
            }
        
            // Create carousel items
            offerGroups.forEach((group, index) => {
                const carouselItem = document.createElement('div');
                carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''}`;
            
                const row = document.createElement('div');
                row.className = 'row g-4';
            
                group.forEach(offer => {
                    const template = document.getElementById('flight-offer-template');
                    const offerElement = template.content.cloneNode(true);
                
                    // Fill in the data
                    const destinationImage = offerElement.querySelector('.destination-image');
                
                    // Use the local travel images based on destination city name
                    let imagePath = '';
                    const cityName = offer.destinationCity.toLowerCase().replace(/\s+/g, '-');
                
                    // Try to use the image from travel folder first
                    imagePath = `/images/travel/${cityName}.jpg`;
                
                    // Set image with error handling
                    destinationImage.onerror = function() {
                        // If travel image fails, fall back to original path
                        this.src = `/images/${offer.destinationImage}`;
                    
                        // If that also fails, use a default image
                        this.onerror = function() {
                            this.src = '/images/travel/destination-default.jpg';
                            this.onerror = null; // Prevent infinite loop
                        };
                    };
                
                    destinationImage.src = imagePath;
                    destinationImage.alt = offer.destinationCity;
                
                    offerElement.querySelector('.discount-badge').textContent = `${offer.discountPercent}% OFF`;
                    offerElement.querySelector('.destination-city').textContent = offer.destinationCity;
                    offerElement.querySelector('.destination-country').textContent = offer.destinationCountry;
                    offerElement.querySelector('.airline-badge').textContent = offer.airline;
                    offerElement.querySelector('.flight-highlight').textContent = offer.highlight;
                    offerElement.querySelector('.cabin-badge').textContent = offer.cabin;
                    offerElement.querySelector('.origin-city').textContent = offer.originCity;
                    offerElement.querySelector('.destination-city-small').textContent = offer.destinationCity;
                    offerElement.querySelector('.departure-date').textContent = offer.departureDate;
                    offerElement.querySelector('.flight-duration').textContent = offer.duration;
                
                    const stopsInfo = offer.stops === 0 ? 'Direct' : `${offer.stops} Stop${offer.stops > 1 ? 's' : ''}`;
                    offerElement.querySelector('.stops-info').textContent = stopsInfo;
                
                    offerElement.querySelector('.destination-description').textContent = offer.description;
                    offerElement.querySelector('.current-price').textContent = `${offer.currency} ${Number(offer.price).toLocaleString()}`;
                    offerElement.querySelector('.original-price').textContent = `${offer.currency} ${Number(offer.originalPrice).toLocaleString()}`;
                
                    // Set up book button with form submission
                    const bookButton = offerElement.querySelector('.book-button');
                    bookButton.setAttribute('data-flight-offer', JSON.stringify(offer));
                    bookButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Check if user is authenticated
                        @if(!auth()->check())
                            // Redirect to login if not authenticated
                            alert('Please log in to book flights');
                            window.location.href = '/login';
                            return;
                        @endif
                        
                        // Show booking modal or redirect to booking page
                        showFlightBookingModal(offer);
                    });
                
                    // Set up wishlist button
                    const wishlistBtn = offerElement.querySelector('.wishlist-btn');
                    wishlistBtn.setAttribute('data-item-id', offer.id);
                    wishlistBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    
                        const heartIcon = this.querySelector('i');
                        const isInWishlist = heartIcon.classList.contains('fas');
                    
                        if (isInWishlist) {
                            removeFromWishlist('flight', offer.id, this);
                        } else {
                            addToWishlist({
                                type: 'flight',
                                item_data: offer,
                                title: `${offer.originCity} to ${offer.destinationCity}`,
                                description: offer.description,
                                image: `/images/travel/${offer.destinationCity.toLowerCase().replace(/\s+/g, '-')}.jpg`,
                                price: offer.price,
                                original_price: offer.originalPrice,
                                location: `${offer.originCity} - ${offer.destinationCity}`,
                                item_id: offer.id
                            }, this);
                        }
                    });
                
                    // Check if this item is already in the wishlist
                    checkWishlistStatus('flight', offer.id, wishlistBtn);
                
                    row.appendChild(offerElement);
                });
            
                carouselItem.appendChild(row);
                carouselInner.appendChild(carouselItem);
            });
        
            // Initialize carousel
            new bootstrap.Carousel(document.getElementById('flightCarousel'), {
                interval: 5000,
                touch: true
            });
        }

        // Using real-time data from Amadeus API only

    </script>



   
</section>



<!-- Hotel Deals Section - Reduced Size -->
<section class="hotel-section-hero position-relative" style="background-color: #2C2E3E;">
    <div class="container h-100">
        <div class="text-center mb-1 position-relative">
            <!-- Animated Background Elements - Hotel Theme -->
            <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none;">
                <div class="floating-icon" style="position: absolute; top: 20%; left: 15%; animation: fly 8s ease-in-out infinite;">
                    <i class="fas fa-concierge-bell" style="color: rgba(255, 255, 255, 0.15); font-size: 1.5rem; transform: rotate(-15deg);"></i>
                </div>
                <div class="floating-icon" style="position: absolute; top: 25%; right: 20%; animation: fly 10s ease-in-out infinite reverse;">
                    <i class="fas fa-bed" style="color: rgba(255, 255, 255, 0.15); font-size: 1.25rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 25%; left: 25%; animation: float 6s ease-in-out infinite;">
                    <i class="fas fa-spa" style="color: rgba(255, 255, 255, 0.15); font-size: 1rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 30%; right: 15%; animation: fly 12s ease-in-out infinite;">
                    <i class="fas fa-swimming-pool" style="color: rgba(255, 255, 255, 0.15); font-size: 1rem;"></i>
                </div>
            </div>
            
            <!-- Main Heading with Enhanced Typography - Reduced Size -->
            <div class="position-relative z-index-2">
                <div class="mb-2">
                    <span class="badge px-3 py-1 rounded-pill" style="background: linear-gradient(45deg, #6a75ca, #8f95d3); color: white; font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">
                        🏨 LUXURY ACCOMMODATIONS
                    </span>
                </div>
                
                <h2 class="fw-bold mb-2" style="color: white; font-size: 1.5rem; line-height: 1.2; position: relative;">
                    <span class="position-relative">
                        <i class="fas fa-hotel me-2" style="color: #8f95d3; font-size: 1.25rem; vertical-align: middle; text-shadow: 0 0 10px rgba(143, 149, 211, 0.3); animation: hotelPulse 3s ease-in-out infinite;"></i>
                        Featured Hotels
                        <!-- Decorative underline -->
                        <div class="position-absolute w-100" style="bottom: -5px; left: 0; height: 2px; background: linear-gradient(90deg, transparent, #8f95d3, transparent); border-radius: 2px;"></div>
                    </span>
                </h2>
                
                <p class="mb-2" style="color: #caced6; font-size: 0.8rem; font-weight: 300; max-width: 450px; margin: 0 auto; line-height: 1.4;">
                    <span class="d-inline-block" style="border-left: 2px solid #8f95d3; padding-left: 10px;">
                        Discover luxury stays across East Africa's premier destinations
                        <br>
                        <small style="color: rgba(202, 206, 214, 0.8); font-size: 0.7rem;">
                            5-star service • Stunning locations • Exclusive amenities
                        </small>
                    </span>
                </p>
                
                <!-- Hotel stats indicator -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-gem text-primary me-1" style="animation: pulse 2s infinite; color: #8f95d3 !important; font-size: 0.7rem;"></i>
                        <span style="color: #caced6; font-size: 0.7rem; font-weight: 500;">
                            Luxury Deals - Limited Time
                        </span>
                    </div>
                    <div style="width: 1px; height: 12px; background: rgba(143, 149, 211, 0.3);"></div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star text-primary me-1" style="color: #8f95d3 !important; font-size: 0.7rem;"></i>
                        <span style="color: rgba(202, 206, 214, 0.8); font-size: 0.7rem;">
                            <span id="hotel-offer-count">9</span> Exclusive Properties
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="hotel-offers-container">
            <div class="col-12 text-center py-2">
                <div class="spinner-border spinner-border-sm" role="status" style="color: #8f95d3;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2" style="color: #caced6; font-size: 11px;">Discovering luxury stays across East Africa...</p>
            </div>
        </div>
    </div>

    <!-- Hotel Offer Template - Reduced Size -->
    <template id="hotel-offer-template">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden hover-lift h-100" style="background-color: #333545; border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="position-relative">
                    <img src="" class="card-img-top hotel-image" alt="Hotel" style="height: 160px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge rounded-pill px-2 py-1 discount-badge" style="background-color: #e74c3c; font-size: 10px;">20% OFF</span>
                    </div>
                    <div class="position-absolute top-0 start-0 m-2">
                        <button class="btn btn-sm wishlist-btn" data-type="hotel" data-item-id="">
                            <i class="far fa-heart text-white" style="font-size: 14px;"></i>
                        </button>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-white mb-0 hotel-name" style="font-size: 13px;">Hotel Name</h6>
                                <small class="text-white-50 hotel-city" style="font-size: 10px;">City, Country</small>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill hotel-stars" style="background-color: #8f95d3; font-size: 10px;">5⭐</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge text-dark highlight-badge" style="background-color: rgba(143, 149, 211, 0.3); font-size: 9px;"><i class="fas fa-award me-1"></i> <span class="hotel-highlight">Highlight</span></span>
                        <span class="badge text-white hotel-type" style="background-color: #27ae60; font-size: 9px;">Luxury</span>
                    </div>
                    
                    <div class="mb-2 hotel-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-semibold" style="color: #caced6; font-size: 12px;"><span class="hotel-location">Location</span></span>
                            </div>
                            <small class="text-muted available-date" style="font-size: 10px;">From Aug 15, 2023</small>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <div class="d-flex align-items-center me-2">
                                <i class="fas fa-utensils text-muted me-1" style="font-size: 9px;"></i>
                                <small class="text-muted hotel-meals" style="font-size: 10px;">Breakfast Included</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-wifi text-muted me-1" style="font-size: 9px;"></i>
                                <small class="text-muted hotel-wifi" style="font-size: 10px;">Free WiFi</small>
                            </div>
                        </div>
                    </div>
                    
                    <p class="card-text mb-2 hotel-description" style="color: #9aa0b0; font-size: 10px;">Luxury hotel with stunning views and world-class amenities.</p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="price fw-bold current-price" style="color: #8f95d3; font-size: 14px;">KES 17,000</span>
                            <small class="text-muted text-decoration-line-through ms-1 original-price" style="font-size: 9px;">KES 21,000</small>
                        </div>
                        <a href="#" class="btn rounded-pill px-2 py-1 book-button" style="background-color: #8f95d3; color: white; font-size: 10px;">
                            <i class="fas fa-key me-1" style="font-size: 9px;"></i>Book
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

  <!-- JavaScript for Dynamic Hotel Offers with Amadeus API Integration -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
        loadHotelOffers();
    });

    function loadHotelOffers() {
        // Show loading spinner
        const container = document.getElementById('hotel-offers-container');
        container.innerHTML = `
            <div class="col-12 text-center py-2">
                <div class="spinner-border spinner-border-sm" role="status" style="color: #8f95d3;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2" style="color: #caced6; font-size: 11px;">Discovering luxury stays across East Africa...</p>
            </div>
        `;
        
        console.log('Loading hotel offers from Amadeus API...');
        
        // Fetch real-time hotel offers from our Amadeus API endpoint
        fetch('/api/amadeus/hotels/search')
            .then(response => {
                console.log('Hotel API Response Status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`Hotel API request failed with status: ${response.status} - ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Hotel API Response Data:', data);
                
                if (data && data.data && data.data.length > 0) {
                    console.log(`Found ${data.data.length} hotel offers`);
                    const processedOffers = processAmadeusHotelOffers(data);
                    displayHotelOffers(processedOffers);
                } else if (data && data.error) {
                    console.error('Hotel API returned error:', data.error, data.details);
                    throw new Error(`Hotel API Error: ${data.error}${data.message ? ' - ' + data.message : ''}`);
                } else {
                    console.warn('No hotel offers found in API response:', data);
                    throw new Error('No hotel offers found in API response');
                }
            })
            .catch(error => {
                console.error('Error fetching hotel offers from Amadeus API:', error);
                
                // Try backup search for Nairobi with specific dates
                console.log('Trying backup hotel search for Nairobi...');
                const checkIn = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                const checkOut = new Date(Date.now() + 9 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                
                fetch(`/api/amadeus/hotels/search?cityCode=NBO&checkInDate=${checkIn}&checkOutDate=${checkOut}`)
                    .then(response => {
                        console.log('Backup Hotel API Response Status:', response.status);
                        if (!response.ok) {
                            throw new Error(`Backup hotel API request failed: ${response.status} - ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Backup Hotel API Response Data:', data);
                        
                        if (data && data.data && data.data.length > 0) {
                            console.log(`Backup search found ${data.data.length} hotel offers`);
                            const processedOffers = processAmadeusHotelOffers(data);
                            displayHotelOffers(processedOffers);
                        } else if (data && data.error) {
                            console.error('Backup hotel API returned error:', data.error, data.details);
                            throw new Error(`Backup Hotel API Error: ${data.error}`);
                        } else {
                            console.warn('No hotel offers found in backup response:', data);
                            throw new Error('No hotel offers found in backup response');
                        }
                    })
                    .catch(backupError => {
                        console.error('Error fetching backup hotel offers:', backupError);
                        // Fallback to dummy data if API fails
                        console.log('Using fallback dummy data...');
                        displayHotelOffers(getDummyHotelOffers());
                    });
            });
    }
    
    // Process raw Amadeus API hotel offers into our format
    function processAmadeusHotelOffers(amadeusResponse) {
        const processedOffers = [];
        
        // Cities information for mapping
        const citiesInfo = {
            'Nairobi': {country: 'Kenya', highlights: ['Mountain View', 'City Center', 'Urban Oasis']},
            'Mombasa': {country: 'Kenya', highlights: ['Beachfront', 'Ocean View', 'Coastal Gem']},
            'Zanzibar': {country: 'Tanzania', highlights: ['Beautiful Beaches', 'Island Paradise', 'Historic Stone Town']},
            'Dar es Salaam': {country: 'Tanzania', highlights: ['Harbour View', 'Business District', 'Cultural Hub']},
            'Arusha': {country: 'Tanzania', highlights: ['Safari Gateway', 'Mountain View', 'Coffee Estate']},
            'Kigali': {country: 'Rwanda', highlights: ['City View', 'Modern Africa', 'Business District']},
            'Entebbe': {country: 'Uganda', highlights: ['Lake View', 'Airport Hub', 'Botanical Gardens']},
            'Kampala': {country: 'Uganda', highlights: ['City Center', 'Cultural Experience', 'Urban Retreat']},
            'Addis Ababa': {country: 'Ethiopia', highlights: ['Historic Capital', 'Urban Oasis', 'Cultural Center']}
        };
        
        // Hotel types based on rating
        const hotelTypes = {
            5: 'Luxury',
            4: 'Premium',
            3: 'Standard',
            2: 'Budget',
            1: 'Economy'
        };
        
        // Default discount percentage
        const discountPercent = 20;
        
        // Process each hotel offer from the Amadeus API
        amadeusResponse.data.forEach((offer, index) => {
            try {
                const hotel = offer.hotel;
                const hotelOffer = offer.offers[0]; // Take the first offer
                
                // Extract city and country
                let city = '';
                let country = '';
                
                if (hotel.address && hotel.address.cityName) {
                    city = hotel.address.cityName;
                }
                
                if (hotel.address && hotel.address.countryCode) {
                    // Get full country name from code if possible
                    const countryMapping = {
                        'KE': 'Kenya',
                        'TZ': 'Tanzania',
                        'RW': 'Rwanda',
                        'UG': 'Uganda',
                        'ET': 'Ethiopia'
                    };
                    country = countryMapping[hotel.address.countryCode] || hotel.address.countryCode;
                }
                
                // If we don't have city info in our mapping, try to find the closest match
                let cityInfo = citiesInfo[city];
                if (!cityInfo) {
                    // Try to find a match based on name similarity
                    const cities = Object.keys(citiesInfo);
                    for (const knownCity of cities) {
                        if (city.includes(knownCity) || knownCity.includes(city)) {
                            cityInfo = citiesInfo[knownCity];
                            city = knownCity; // Use the known city name
                            break;
                        }
                    }
                    
                    // If still no match, use default values
                    if (!cityInfo) {
                        cityInfo = {
                            country: country || 'East Africa',
                            highlights: ['Featured Property', 'Great Location', 'Exclusive Amenities']
                        };
                    }
                }
                
                // Determine hotel rating (stars)
                let stars = 4; // Default
                if (hotel.rating) {
                    stars = parseInt(hotel.rating);
                }
                
                // Get price info
                const price = parseFloat(hotelOffer.price.total);
                const currency = hotelOffer.price.currency;
                
                // Calculate original price (pre-discount)
                const originalPrice = price / (1 - (discountPercent / 100));
                
                // Get hotel type based on rating
                const hotelType = hotelTypes[stars] || 'Boutique';
                
                // Pick a random highlight for this hotel
                const highlight = cityInfo.highlights[Math.floor(Math.random() * cityInfo.highlights.length)];
                
                // Get location within the city
                let location = 'City Center';
                if (hotel.address && hotel.address.lines && hotel.address.lines.length > 0) {
                    location = hotel.address.lines[0];
                }
                
                // Determine amenities
                const hasBreakfast = Math.random() > 0.3; // 70% chance of breakfast included
                const meals = hasBreakfast ? 'Breakfast Included' : 'Room Only';
                const wifi = Math.random() > 0.1 ? 'Free WiFi' : 'Paid WiFi';
                
                // Format available date (check-in date from offer)
                let availableDate = 'Flexible Dates';
                if (hotelOffer.checkInDate) {
                    const dateObj = new Date(hotelOffer.checkInDate);
                    availableDate = dateObj.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
                
                // Format description
                let description = 'Luxurious accommodation with excellent amenities';
                if (hotel.description && hotel.description.text) {
                    description = hotel.description.text.slice(0, 100) + '...';
                }
                
                // Create the processed offer object
                const processedOffer = {
                    id: offer.id || `hotel-${index + 1}`,
                    hotelName: hotel.name,
                    city: city,
                    country: cityInfo.country,
                    hotelImage: `${city.toLowerCase().replace(/\s+/g, '-')}.jpg`,
                    description: description,
                    highlight: highlight,
                    location: location,
                    availableDate: availableDate,
                    price: Math.round(price),
                    originalPrice: Math.round(originalPrice),
                    currency: currency,
                    discountPercent: discountPercent,
                    hotelType: hotelType,
                    stars: stars,
                    meals: meals,
                    wifi: wifi,
                    // Store original Amadeus offer data for booking
                    amadeusData: offer
                };
                
                processedOffers.push(processedOffer);
            } catch (error) {
                console.error('Error processing hotel offer:', error, offer);
            }
        });
        
        return processedOffers;
    }

    function displayHotelOffers(offers) {
        const container = document.getElementById('hotel-offers-container');
        container.innerHTML = '';
        
        // Update count
        const countElement = document.getElementById('hotel-offer-count');
        if (countElement) {
            countElement.textContent = offers.length;
        }
        
        // Create carousel structure
        const carouselDiv = document.createElement('div');
        carouselDiv.className = 'col-12';
        carouselDiv.innerHTML = `
            <div class="carousel slide" id="hotelsCarousel">
                <div class="carousel-inner"></div>
                <button class="carousel-control-prev" type="button" data-bs-target="#hotelsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#hotelsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        `;
        container.appendChild(carouselDiv);
        
        const carouselInner = document.querySelector('#hotelsCarousel .carousel-inner');
        
        // Group offers by 3 for each carousel slide
        const offerGroups = [];
        for (let i = 0; i < offers.length; i += 3) {
            offerGroups.push(offers.slice(i, i + 3));
        }
        
        // Create carousel items
        offerGroups.forEach((group, index) => {
            const carouselItem = document.createElement('div');
            carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''}`;
            
            const row = document.createElement('div');
            row.className = 'row g-4';
            
            group.forEach(offer => {
                const template = document.getElementById('hotel-offer-template');
                if (!template) {
                    console.error('Hotel offer template not found');
                    return;
                }
                
                const offerElement = template.content.cloneNode(true);
                
                // Use local travel images based on city name
                const hotelImage = offerElement.querySelector('.hotel-image');
                if (hotelImage) {
                    // Try to use the image from travel folder first
                    let imagePath = '';
                    const cityName = offer.city.toLowerCase().replace(/\s+/g, '-');
                    
                    // Set image path to the city image in the travel folder
                    imagePath = `/images/travel/${cityName}.jpg`;
                    
                    // Handle image loading errors
                    hotelImage.onerror = function() {
                        // If city image fails, try to use hotel type image
                        const hotelType = offer.hotelType.toLowerCase().replace(/\s+/g, '-');
                        this.src = `/images/travel/${hotelType}.jpg`;
                        
                        // If hotel type image also fails, use a default image
                        this.onerror = function() {
                            // If highlight is available, try that
                            const highlight = offer.highlight.toLowerCase().replace(/\s+/g, '-');
                            this.src = `/images/travel/${highlight}.jpg`;
                            
                            // Final fallback
                            this.onerror = function() {
                                this.src = `/images/${offer.hotelImage || 'hotel-default.jpg'}`;
                                
                                // If all else fails, use a placeholder
                                this.onerror = function() {
                                    this.src = '/images/travel/destination-default.jpg';
                                    this.onerror = null; // Prevent infinite loop
                                };
                            };
                        };
                    };
                    
                    hotelImage.src = imagePath;
                    hotelImage.alt = offer.hotelName;
                }
                
                // Fill in the rest of the data
                const discountBadge = offerElement.querySelector('.discount-badge');
                if (discountBadge) discountBadge.textContent = `${offer.discountPercent}% OFF`;
                
                const hotelName = offerElement.querySelector('.hotel-name');
                if (hotelName) hotelName.textContent = offer.hotelName;
                
                const hotelCity = offerElement.querySelector('.hotel-city');
                if (hotelCity) hotelCity.textContent = `${offer.city}, ${offer.country}`;
                
                const hotelStars = offerElement.querySelector('.hotel-stars');
                if (hotelStars) hotelStars.textContent = `${offer.stars}⭐`;
                
                const hotelHighlight = offerElement.querySelector('.hotel-highlight');
                if (hotelHighlight) hotelHighlight.textContent = offer.highlight;
                
                const hotelType = offerElement.querySelector('.hotel-type');
                if (hotelType) hotelType.textContent = offer.hotelType;
                
                const hotelLocation = offerElement.querySelector('.hotel-location');
                if (hotelLocation) hotelLocation.textContent = offer.location;
                
                const availableDate = offerElement.querySelector('.available-date');
                if (availableDate) availableDate.textContent = `From ${offer.availableDate}`;
                
                const hotelMeals = offerElement.querySelector('.hotel-meals');
                if (hotelMeals) hotelMeals.textContent = offer.meals;
                
                const hotelWifi = offerElement.querySelector('.hotel-wifi');
                if (hotelWifi) hotelWifi.textContent = offer.wifi;
                
                const hotelDescription = offerElement.querySelector('.hotel-description');
                if (hotelDescription) hotelDescription.textContent = offer.description;
                
                const currentPrice = offerElement.querySelector('.current-price');
                if (currentPrice) currentPrice.textContent = `${offer.currency} ${Number(offer.price).toLocaleString()}`;
                
                const originalPrice = offerElement.querySelector('.original-price');
                if (originalPrice) originalPrice.textContent = `${offer.currency} ${Number(offer.originalPrice).toLocaleString()}`;
                
                // Set up book button with form submission
                const bookButton = offerElement.querySelector('.book-button');
                if (bookButton) {
                    bookButton.setAttribute('data-hotel-offer', JSON.stringify(offer));
                    bookButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Store the selected offer in session storage for the booking page
                        if (offer.amadeusData) {
                            sessionStorage.setItem('selectedAmadeusHotelOffer', JSON.stringify(offer.amadeusData));
                        }
                        
                        // Redirect to hotel booking page with the real Amadeus offer ID
                        window.location.href = `/hotels/book/${offer.id}`;
                    });
                }
                
                // Set up wishlist button
                const wishlistBtn = offerElement.querySelector('.wishlist-btn');
                if (wishlistBtn) {
                    wishlistBtn.setAttribute('data-item-id', offer.id);
                    wishlistBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const heartIcon = this.querySelector('i');
                        const isInWishlist = heartIcon && heartIcon.classList.contains('fas');
                        
                        if (isInWishlist) {
                            // Check if removeFromWishlist function exists
                            if (typeof removeFromWishlist === 'function') {
                                removeFromWishlist('hotel', offer.id, this);
                            }
                        } else {
                            // Check if addToWishlist function exists
                            if (typeof addToWishlist === 'function') {
                                addToWishlist({
                                    type: 'hotel',
                                    item_data: offer,
                                    title: offer.hotelName,
                                    description: offer.description,
                                    image: `/images/travel/${offer.city.toLowerCase().replace(/\s+/g, '-')}.jpg`,
                                    price: offer.price,
                                    original_price: offer.originalPrice,
                                    location: `${offer.city}, ${offer.country}`,
                                    item_id: offer.id
                                }, this);
                            }
                        }
                    });
                    
                    // Check if this item is already in the wishlist
                    if (typeof checkWishlistStatus === 'function') {
                        checkWishlistStatus('hotel', offer.id, wishlistBtn);
                    }
                }
                
                row.appendChild(offerElement);
            });
            
            carouselItem.appendChild(row);
            carouselInner.appendChild(carouselItem);
        });
        
        // Initialize carousel if Bootstrap is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
            new bootstrap.Carousel(document.getElementById('hotelsCarousel'), {
                interval: 5000,
                touch: true
            });
        }
    }

    // Fallback dummy data for when API is not available
    function getDummyHotelOffers() {
        return [
            {
                id: 'hotel1',
                hotelName: 'Serena Mountain Lodge',
                city: 'Nairobi',
                country: 'Kenya',
                hotelImage: 'nairobi-hotel.jpg',
                description: 'Luxury hotel with stunning views of Mount Kenya',
                highlight: 'Mountain View',
                location: 'City Center',
                availableDate: 'Aug 15, 2025',
                price: 22000,
                originalPrice: 27500,
                currency: 'KES',
                discountPercent: 20,
                hotelType: 'Luxury',
                stars: 5,
                meals: 'Breakfast Included',
                wifi: 'Free WiFi'
            },
            {
                id: 'hotel2',
                hotelName: 'Zanzibar Beach Resort',
                city: 'Zanzibar',
                country: 'Tanzania',
                hotelImage: 'zanzibar-hotel.jpg',
                description: 'Beachfront resort with private access to pristine beaches',
                highlight: 'Beachfront',
                location: 'Stone Town',
                availableDate: 'Aug 18, 2025',
                price: 30000,
                originalPrice: 37500,
                currency: 'KES',
                discountPercent: 20,
                hotelType: 'Resort',
                stars: 5,
                meals: 'All-Inclusive',
                wifi: 'Free WiFi'
            },
            {
                id: 'hotel3',
                hotelName: 'Mombasa Reef Hotel',
                city: 'Mombasa',
                country: 'Kenya',
                hotelImage: 'mombasa-hotel.jpg',
                description: 'Oceanfront hotel with direct access to marine park',
                highlight: 'Ocean View',
                location: 'North Coast',
                availableDate: 'Aug 20, 2025',
                price: 18500,
                originalPrice: 23125,
                currency: 'KES',
                discountPercent: 20,
                hotelType: 'Boutique',
                stars: 4,
                meals: 'Breakfast Included',
                wifi: 'Premium WiFi'
            },
            {
                id: 'hotel4',
                hotelName: 'Kigali Marriott Hotel',
                city: 'Kigali',
                country: 'Rwanda',
                hotelImage: 'kigali-hotel.jpg',
                description: 'Sophisticated hotel with panoramic views of the city skyline',
                highlight: 'City View',
                location: 'Business District',
                availableDate: 'Aug 22, 2025',
                price: 25000,
                originalPrice: 31250,
                currency: 'KES',
                discountPercent: 20,
                hotelType: 'Business',
                stars: 5,
                meals: 'Breakfast & Dinner',
                wifi: 'High-speed WiFi'
            },
            {
                id: 'hotel5',
                hotelName: 'Arusha Coffee Lodge',
                city: 'Arusha',
                country: 'Tanzania',
                hotelImage: 'arusha-hotel.jpg',
                description: 'Charming lodge nestled in a working coffee plantation',
                highlight: 'Heritage Property',
                location: 'Coffee Estate',
                availableDate: 'Aug 25, 2025',
                price: 19800,
                originalPrice: 24750,
                currency: 'KES',
                discountPercent: 20,
                hotelType: 'Lodge',
                stars: 4,
                meals: 'Full Board',
                wifi: 'Free WiFi'
            },
            {
                id: 'hotel6',
                hotelName: 'Addis Ababa Hilton',
                city: 'Addis Ababa',
                country: 'Ethiopia',
                hotelImage: 'addis-ababa-hotel.jpg',
                description: 'Luxurious hotel with exceptional dining and spa facilities',
                highlight: 'Urban Oasis',
                location: 'City Center',
                availableDate: 'Aug 28, 2025',
                price: 21000,
                originalPrice: 26250,
                currency: 'KES',
                discountPercent: 20,
                hotelType: 'Luxury',
                stars: 5,
                meals: 'Breakfast Included',
                wifi: 'Premium WiFi'
            }
        ];
    }

    // Add custom animation for hotel icons
    document.addEventListener('DOMContentLoaded', function() {
        if (!CSS.supports('animation-name: hotelPulse')) {
            const style = document.createElement('style');
            style.textContent = `
                @keyframes hotelPulse {
                    0%, 100% { transform: scale(1); opacity: 1; }
                    50% { transform: scale(1.1); opacity: 0.8; }
                }
            `;
            document.head.appendChild(style);
        }
    });
</script>

    {{-- <!-- JavaScript for Dynamic Hotel Offers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadHotelOffers();
        });
        
        // Define Unsplash hotel image mapping for cities
        const hotelCityImages = {
            'Nairobi': 'https://source.unsplash.com/1200x800/?nairobi,hotel,luxury',
            'Mombasa': 'https://source.unsplash.com/1200x800/?mombasa,resort,beach',
            'Zanzibar': 'https://source.unsplash.com/1200x800/?zanzibar,resort,beach',
            'Arusha': 'https://source.unsplash.com/1200x800/?arusha,hotel,lodge',
            'Entebbe': 'https://source.unsplash.com/1200x800/?entebbe,hotel',
            'Kigali': 'https://source.unsplash.com/1200x800/?kigali,hotel,rwanda',
            'Dar Es Salaam': 'https://source.unsplash.com/1200x800/?dar-es-salaam,hotel',
            'Jinja': 'https://source.unsplash.com/1200x800/?jinja,hotel,nile',
            'Addis Ababa': 'https://source.unsplash.com/1200x800/?addis-ababa,hotel',
            // Type-based images (fallback)
            'Luxury': 'https://source.unsplash.com/1200x800/?luxury,hotel,suite',
            'Resort': 'https://source.unsplash.com/1200x800/?resort,pool,tropical',
            'Boutique': 'https://source.unsplash.com/1200x800/?boutique,hotel',
            'Business': 'https://source.unsplash.com/1200x800/?business,hotel',
            'Heritage': 'https://source.unsplash.com/1200x800/?heritage,hotel,historic',
            // Default fallback
            'default': 'https://source.unsplash.com/1200x800/?hotel,accommodation'
        };
        
        // Define highlight-specific images
        const highlightImages = {
            'Mountain View': 'https://source.unsplash.com/1200x800/?mountain,view,hotel',
            'Beachfront': 'https://source.unsplash.com/1200x800/?beachfront,resort',
            'Ocean View': 'https://source.unsplash.com/1200x800/?ocean,view,hotel',
            'Lake View': 'https://source.unsplash.com/1200x800/?lake,view,resort',
            'City View': 'https://source.unsplash.com/1200x800/?city,view,skyline,hotel',
            'City Center': 'https://source.unsplash.com/1200x800/?city,center,hotel',
            'Rooftop Pool': 'https://source.unsplash.com/1200x800/?rooftop,pool,hotel',
            'Urban Oasis': 'https://source.unsplash.com/1200x800/?urban,hotel,garden',
            'Exclusive Suites': 'https://source.unsplash.com/1200x800/?luxury,suite,hotel',
            'Family-Friendly': 'https://source.unsplash.com/1200x800/?family,hotel,pool',
            'Heritage Property': 'https://source.unsplash.com/1200x800/?heritage,historic,hotel',
            'Eco-Luxury': 'https://source.unsplash.com/1200x800/?eco,luxury,resort',
            'Harbor View': 'https://source.unsplash.com/1200x800/?harbor,view,hotel',
            'River View': 'https://source.unsplash.com/1200x800/?river,view,hotel'
        };
        
        function loadHotelOffers() {
            fetch('/east-african-hotels')
                .then(response => response.json())
                .then(data => {
                    displayHotelOffers(data);
                })
                .catch(error => {
                    console.error('Error fetching hotel offers:', error);
                    displayDummyHotelOffers();
                });
        }
        
        function displayHotelOffers(offers) {
            const container = document.getElementById('hotel-offers-container');
            container.innerHTML = '';
            
            // Update count
            document.getElementById('hotel-offer-count').textContent = offers.length;
            
            // Create carousel structure
            const carouselDiv = document.createElement('div');
            carouselDiv.className = 'col-12';
            carouselDiv.innerHTML = `
                <div class="carousel slide" id="hotelsCarousel">
                    <div class="carousel-inner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#hotelsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#hotelsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            `;
            container.appendChild(carouselDiv);
            
            const carouselInner = document.querySelector('#hotelsCarousel .carousel-inner');
            
            // Group offers by 3 for each carousel slide
            const offerGroups = [];
            for (let i = 0; i < offers.length; i += 3) {
                offerGroups.push(offers.slice(i, i + 3));
            }
            
            // Create carousel items
            offerGroups.forEach((group, index) => {
                const carouselItem = document.createElement('div');
                carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                
                const row = document.createElement('div');
                row.className = 'row g-4';
                
                group.forEach(offer => {
                    const template = document.getElementById('hotel-offer-template');
                    const offerElement = template.content.cloneNode(true);
                    
                    // Select image based on hierarchy: highlight > city > hotel type > default
                    let imageUrl;
                    const cacheBuster = `&cache=${new Date().getTime()}-${Math.floor(Math.random() * 1000)}`;
                    
                    if (offer.highlight && highlightImages[offer.highlight]) {
                        imageUrl = highlightImages[offer.highlight];
                    } else if (offer.city && hotelCityImages[offer.city]) {
                        imageUrl = hotelCityImages[offer.city];
                    } else if (offer.hotelType && hotelCityImages[offer.hotelType]) {
                        imageUrl = hotelCityImages[offer.hotelType];
                    } else {
                        imageUrl = hotelCityImages['default'];
                    }
                    
                    // Add hotel name to make image more specific
                    if (offer.hotelName) {
                        imageUrl += `,${offer.hotelName.split(' ').join(',')}`;
                    }
                    
                    const hotelImage = offerElement.querySelector('.hotel-image');
                    
                    // Handle image loading errors
                    hotelImage.onerror = function() {
                        // If Unsplash fails, try to use local image
                        this.src = `/images/${offer.hotelImage || 'hotel-default.jpg'}`;
                        
                        // If local image also fails, use a generic placeholder
                        this.onerror = function() {
                            this.src = "https://via.placeholder.com/800x600?text=Luxury+Hotel";
                            this.onerror = null; // Prevent infinite loop
                        };
                    };
                    
                    hotelImage.src = imageUrl + cacheBuster;
                    hotelImage.alt = offer.hotelName;
                    
                    // Fill in the rest of the data
                    offerElement.querySelector('.discount-badge').textContent = `${offer.discountPercent}% OFF`;
                    offerElement.querySelector('.hotel-name').textContent = offer.hotelName;
                    offerElement.querySelector('.hotel-city').textContent = `${offer.city}, ${offer.country}`;
                    offerElement.querySelector('.hotel-stars').textContent = `${offer.stars}⭐`;
                    offerElement.querySelector('.hotel-highlight').textContent = offer.highlight;
                    offerElement.querySelector('.hotel-type').textContent = offer.hotelType;
                    offerElement.querySelector('.hotel-location').textContent = offer.location;
                    offerElement.querySelector('.available-date').textContent = `From ${offer.availableDate}`;
                    offerElement.querySelector('.hotel-meals').textContent = offer.meals;
                    offerElement.querySelector('.hotel-wifi').textContent = offer.wifi;
                    offerElement.querySelector('.hotel-description').textContent = offer.description;
                    offerElement.querySelector('.current-price').textContent = `${offer.currency} ${Number(offer.price).toLocaleString()}`;
                    offerElement.querySelector('.original-price').textContent = `${offer.currency} ${Number(offer.originalPrice).toLocaleString()}`;
                    
                    // Set up book button
                    const bookButton = offerElement.querySelector('.book-button');
                    bookButton.href = `/hotels/book?id=${offer.id}&city=${offer.city}&check_in=${offer.availableDate}`;
                    
                    row.appendChild(offerElement);
                });
                
                carouselItem.appendChild(row);
                carouselInner.appendChild(carouselItem);
            });
            
            // Initialize carousel
            new bootstrap.Carousel(document.getElementById('hotelsCarousel'), {
                interval: 5000,
                touch: true
            });
        }
        
        function displayDummyHotelOffers() {
            // Fallback function if the API fails
            const dummyOffers = [
                {
                    id: 'hotel1',
                    hotelName: 'Serena Mountain Lodge',
                    city: 'Nairobi',
                    country: 'Kenya',
                    hotelImage: 'nairobi-hotel.jpg',
                    description: 'Luxury hotel with stunning views of Mount Kenya',
                    highlight: 'Mountain View',
                    location: 'City Center',
                    availableDate: 'Aug 15, 2023',
                    price: 22000,
                    originalPrice: 27500,
                    currency: 'KES',
                    discountPercent: 20,
                    hotelType: 'Luxury',
                    stars: 5,
                    meals: 'Breakfast Included',
                    wifi: 'Free WiFi'
                },
                {
                    id: 'hotel2',
                    hotelName: 'Zanzibar Beach Resort',
                    city: 'Zanzibar',
                    country: 'Tanzania',
                    hotelImage: 'zanzibar-hotel.jpg',
                    description: 'Beachfront resort with private access to pristine beaches',
                    highlight: 'Beachfront',
                    location: 'Stone Town',
                    availableDate: 'Aug 18, 2023',
                    price: 30000,
                    originalPrice: 37500,
                    currency: 'KES',
                    discountPercent: 20,
                    hotelType: 'Resort',
                    stars: 5,
                    meals: 'All-Inclusive',
                    wifi: 'Free WiFi'
                },
                {
                    id: 'hotel3',
                    hotelName: 'Mombasa Reef Hotel',
                    city: 'Mombasa',
                    country: 'Kenya',
                    hotelImage: 'mombasa-hotel.jpg',
                    description: 'Oceanfront hotel with direct access to marine park',
                    highlight: 'Ocean View',
                    location: 'North Coast',
                    availableDate: 'Aug 20, 2023',
                    price: 18500,
                    originalPrice: 23125,
                    currency: 'KES',
                    discountPercent: 20,
                    hotelType: 'Boutique',
                    stars: 4,
                    meals: 'Breakfast Included',
                    wifi: 'Premium WiFi'
                }
            ];
            
            displayHotelOffers(dummyOffers);
        }
        
        // Add custom animation for hotel icons
        document.addEventListener('DOMContentLoaded', function() {
            if (!CSS.supports('animation-name: hotelPulse')) {
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes hotelPulse {
                        0%, 100% { transform: scale(1); opacity: 1; }
                        50% { transform: scale(1.1); opacity: 0.8; }
                    }
                `;
                document.head.appendChild(style);
            }
        });
    </script> --}}
</section>


<!-- Tours & Activities Section - Reduced Size -->
<section class="deals-section py-3 bg-light">
    <div class="container">
        <div class="text-center mb-3 position-relative">
            <!-- Animated Background Elements - Tours & Activities Theme -->
            <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none;">
                <div class="floating-icon" style="position: absolute; top: 20%; left: 15%; animation: dealBounce 4s ease-in-out infinite;">
                    <i class="fas fa-hiking" style="color: rgba(40, 167, 69, 0.15); font-size: 1.5rem; transform: rotate(-15deg);"></i>
                </div>
                <div class="floating-icon" style="position: absolute; top: 25%; right: 20%; animation: dealFloat 6s ease-in-out infinite reverse;">
                    <i class="fas fa-binoculars" style="color: rgba(40, 167, 69, 0.15); font-size: 1.25rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 25%; left: 25%; animation: dealSpin 8s linear infinite;">
                    <i class="fas fa-camera" style="color: rgba(40, 167, 69, 0.15); font-size: 1rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 30%; right: 15%; animation: dealBounce 5s ease-in-out infinite;">
                    <i class="fas fa-mountain" style="color: rgba(40, 167, 69, 0.15); font-size: 1rem;"></i>
                </div>
            </div>
            
            <!-- Main Heading with Enhanced Typography - Reduced Size -->
            <div class="position-relative z-index-2">
                <div class="mb-2">
                    <span class="badge px-3 py-1 rounded-pill" style="background: linear-gradient(45deg, #28a745, #20c997); color: white; font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px;">
                        🏞️ EXPERIENCES & TOURS
                    </span>
                </div>
                
                <h2 class="fw-bold mb-2" style="color: #495057; font-size: 1.5rem; line-height: 1.2; position: relative;">
                    <span class="position-relative">
                        <i class="fas fa-map-marked-alt me-2" style="color: #28a745; font-size: 1.25rem; vertical-align: middle; text-shadow: 0 0 10px rgba(40, 167, 69, 0.3); animation: activityPulse 3s ease-in-out infinite;"></i>
                        Explore East Africa
                        <!-- Decorative underline -->
                        <div class="position-absolute w-100" style="bottom: -5px; left: 0; height: 2px; background: linear-gradient(90deg, transparent, #28a745, transparent); border-radius: 2px;"></div>
                    </span>
                </h2>
                
                <p class="mb-2" style="color: #6c757d; font-size: 0.8rem; font-weight: 300; max-width: 450px; margin: 0 auto; line-height: 1.4;">
                    <span class="d-inline-block" style="border-left: 2px solid #28a745; padding-left: 10px;">
                        Discover unforgettable experiences across East Africa's top destinations
                        <br>
                        <small style="color: rgba(108, 117, 125, 0.8); font-size: 0.7rem;">
                            Authentic experiences • Local guides • Unique adventures
                        </small>
                    </span>
                </p>
                
                <!-- Activity stats indicator - Reduced Size -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt text-success me-1" style="animation: bounce 2s infinite; font-size: 0.7rem;"></i>
                        <span style="color: #495057; font-size: 0.7rem; font-weight: 500;">
                            <span id="activity-locations-count">8</span> Unique Locations
                        </span>
                    </div>
                    <div style="width: 1px; height: 12px; background: rgba(40, 167, 69, 0.3);"></div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-route text-success me-1" style="font-size: 0.7rem;"></i>
                        <span style="color: rgba(73, 80, 87, 0.8); font-size: 0.7rem;">
                            <span id="activity-count">24</span> Amazing Experiences
                        </span>
                    </div>
                </div>

                <!-- City Navigation Pills - Reduced Size -->
                <div class="cities-nav mb-2">
                    <ul class="nav nav-pills justify-content-center" id="activities-cities-tab" role="tablist">
                        <li class="nav-item mx-1" role="presentation">
                            <button class="nav-link active px-2 py-1" id="all-cities-tab" data-bs-toggle="pill" data-bs-target="#all-cities" type="button" role="tab" style="font-size: 0.7rem;">
                                All Cities
                            </button>
                        </li>
                        <!-- Dynamic city tabs will be inserted here -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Loading Spinner - Reduced Size -->
        <div class="row mb-2" id="activities-spinner">
            <div class="col-12 text-center py-2">
                <div class="spinner-border spinner-border-sm text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted" style="font-size: 11px;">Discovering amazing experiences across East Africa...</p>
            </div>
        </div>

        <!-- Activities Content -->
        <div class="tab-content" id="activities-cities-content">
            <div class="tab-pane fade show active" id="all-cities" role="tabpanel">
                <div class="row g-4" id="all-activities-container">
                    <!-- Activities will be dynamically inserted here -->
                </div>
                <!-- Pagination for all cities tab -->
                <div class="d-flex justify-content-center mt-5" id="all-cities-pagination">
                    <!-- Pagination will be added here -->
                </div>
            </div>
            <!-- City-specific tab panes will be inserted here -->
        </div>
    </div>

    <!-- Activity Card Template - Reduced Size -->
    <template id="activity-card-template">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
            <div class="position-relative">
                <img src="" class="card-img-top activity-image" alt="Activity" style="height: 160px; object-fit: cover;">
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-success rounded-pill px-2 py-1 activity-rating" style="font-size: 10px;">
                        <i class="fas fa-star me-1" style="font-size: 9px;"></i><span class="rating-value">4.5</span>
                    </span>
                </div>
                <div class="position-absolute top-0 start-0 m-2">
                    <button class="btn btn-sm wishlist-btn" data-type="tour" data-item-id="">
                        <i class="far fa-heart text-white" style="font-size: 14px;"></i>
                    </button>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-white-50 activity-city" style="font-size: 10px;">City, Country</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-2">
                <h6 class="card-title fw-bold mb-1 activity-name" style="font-size: 13px;">Activity Name</h6>
                <p class="card-text text-muted mb-2 activity-description" style="font-size: 10px;">Activity description goes here...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="price fw-bold text-success activity-price" style="font-size: 14px;">USD 45.00</span>
                    <a href="#" class="btn btn-outline-success rounded-pill px-2 py-1 book-activity-btn" style="font-size: 10px;">
                        <i class="fas fa-ticket-alt me-1" style="font-size: 9px;"></i>Book
                    </a>
                </div>
            </div>
        </div>
    </template>
    
    <!-- Pagination Template - Reduced Size -->
    <template id="pagination-template">
        <nav aria-label="Activities pagination">
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true" style="font-size: 10px;">Prev</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#" style="font-size: 10px;">1</a></li>
                <li class="page-item"><a class="page-link" href="#" style="font-size: 10px;">2</a></li>
                <li class="page-item"><a class="page-link" href="#" style="font-size: 10px;">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" style="font-size: 10px;">Next</a>
                </li>
            </ul>
        </nav>
    </template>

<!-- Update the loadActivities function -->
        <script>
        function loadActivities() {
            console.log('Loading East African activities...');
            
            // Show the loading spinner
            const spinner = document.getElementById('activities-spinner');
            if (spinner) {
                spinner.style.display = 'block';
            }
            
            // Try to fetch from Amadeus API
            fetch('/api/amadeus/activities/search')
                .then(response => {
                    console.log('Activities API Response Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Activities API Response Data:', data);
                    
                    if (data && data.data && data.data.length > 0) {
                        console.log(`Successfully loaded ${data.data.length} activities`);
                        const processedActivities = processAmadeusActivities(data);
                        displayActivities(processedActivities);
                    } else {
                        console.log('No activities in API response, using fallback data');
                        displayFallbackActivities();
                    }
                })
                .catch(error => {
                    console.error('Error loading activities:', error);
                    displayFallbackActivities();
                });
        }

        function displayFallbackActivities() {
            console.log('Displaying comprehensive East African activities');
            
            // Hide spinner
            const spinner = document.getElementById('activities-spinner');
            if (spinner) {
                spinner.style.display = 'none';
            }
            
            // Comprehensive East African activities data
            const fallbackData = {
                data: [
                    // Kenya Activities
                    {
                        id: 'nairobi-safari-1',
                        name: 'Nairobi National Park Safari',
                        shortDescription: 'Experience wildlife just minutes from the city center',
                        description: 'Discover the unique Nairobi National Park where you can see lions, rhinos, and giraffes with the city skyline in the background.',
                        city: 'Nairobi',
                        country: 'Kenya',
                        price: { amount: 85, currencyCode: 'USD' },
                        rating: '4.5',
                        duration: 'PT4H',
                        pictures: ['https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -1.292066, longitude: 36.821946 }
                    },
                    {
                        id: 'mombasa-dhow-cruise',
                        name: 'Mombasa Dhow Sunset Cruise',
                        shortDescription: 'Traditional dhow sailing experience along the coast',
                        description: 'Sail along the beautiful Kenyan coast in a traditional dhow boat while enjoying a spectacular sunset.',
                        city: 'Mombasa',
                        country: 'Kenya',
                        price: { amount: 65, currencyCode: 'USD' },
                        rating: '4.7',
                        duration: 'PT3H',
                        pictures: ['https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -4.043477, longitude: 39.668206 }
                    },
                    {
                        id: 'nairobi-city-tour',
                        name: 'Nairobi Cultural Heritage Tour',
                        shortDescription: 'Explore Kenya\'s vibrant capital city',
                        description: 'Discover Nairobi\'s rich cultural heritage, visit local markets, museums, and experience authentic Kenyan urban life.',
                        city: 'Nairobi',
                        country: 'Kenya',
                        price: { amount: 55, currencyCode: 'USD' },
                        rating: '4.3',
                        duration: 'PT6H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -1.286389, longitude: 36.817223 }
                    },
                    {
                        id: 'mombasa-historical-tour',
                        name: 'Mombasa Old Town & Fort Jesus Tour',
                        shortDescription: 'Explore the rich Swahili culture and history',
                        description: 'Walk through Old Town Mombasa and discover centuries of coastal culture, architecture, and traditions at the UNESCO World Heritage Fort Jesus.',
                        city: 'Mombasa',
                        country: 'Kenya',
                        price: { amount: 40, currencyCode: 'USD' },
                        rating: '4.6',
                        duration: 'PT4H',
                        pictures: ['https://images.unsplash.com/photo-1571847140471-1d7766e825ea?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -4.054419, longitude: 39.666060 }
                    },
                    {
                        id: 'kenya-coffee-tour',
                        name: 'Kenya Coffee Plantation Experience',
                        shortDescription: 'Learn about Kenya\'s world-famous coffee production',
                        description: 'Visit a working coffee plantation near Nairobi, learn about coffee cultivation, processing, and enjoy fresh coffee tasting.',
                        city: 'Nairobi',
                        country: 'Kenya',
                        price: { amount: 70, currencyCode: 'USD' },
                        rating: '4.8',
                        duration: 'PT5H',
                        pictures: ['https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -1.232030, longitude: 36.872500 }
                    },
                    
                    // Tanzania Activities
                    {
                        id: 'zanzibar-spice-tour',
                        name: 'Zanzibar Spice Plantation Tour',
                        shortDescription: 'Discover the aromatic spice plantations of Zanzibar',
                        description: 'Take a journey through Zanzibar\'s famous spice plantations and learn about the island\'s rich history and spice trade heritage.',
                        city: 'Zanzibar',
                        country: 'Tanzania',
                        price: { amount: 45, currencyCode: 'USD' },
                        rating: '4.7',
                        duration: 'PT3H',
                        pictures: ['https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -6.165917, longitude: 39.202641 }
                    },
                    {
                        id: 'stone-town-tour',
                        name: 'Stone Town Historical Walking Tour',
                        shortDescription: 'UNESCO World Heritage Site exploration',
                        description: 'Explore Stone Town\'s narrow alleys, historic buildings, spice markets, and learn about Zanzibar\'s fascinating multicultural history.',
                        city: 'Zanzibar',
                        country: 'Tanzania',
                        price: { amount: 35, currencyCode: 'USD' },
                        rating: '4.5',
                        duration: 'PT3H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -6.162510, longitude: 39.190720 }
                    },
                    {
                        id: 'serengeti-day-safari',
                        name: 'Serengeti National Park Day Safari',
                        shortDescription: 'Witness the world\'s greatest wildlife spectacle',
                        description: 'Experience the legendary Serengeti ecosystem with its incredible wildlife diversity and witness the Great Migration.',
                        city: 'Arusha',
                        country: 'Tanzania',
                        price: { amount: 280, currencyCode: 'USD' },
                        rating: '4.9',
                        duration: 'PT8H',
                        pictures: ['https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -3.386925, longitude: 37.101601 }
                    },
                    {
                        id: 'ngorongoro-crater-tour',
                        name: 'Ngorongoro Crater Safari',
                        shortDescription: 'Explore Africa\'s Garden of Eden',
                        description: 'Descend into the Ngorongoro Crater, a UNESCO World Heritage Site and home to an incredible concentration of wildlife.',
                        city: 'Arusha',
                        country: 'Tanzania',
                        price: { amount: 250, currencyCode: 'USD' },
                        rating: '4.8',
                        duration: 'PT8H',
                        pictures: ['https://images.unsplash.com/photo-1549366021-9f761d040dd2?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -3.164706, longitude: 35.589269 }
                    },
                    {
                        id: 'kilimanjaro-day-hike',
                        name: 'Mount Kilimanjaro Day Hike',
                        shortDescription: 'Hike on Africa\'s highest mountain',
                        description: 'Experience a day hike on Mount Kilimanjaro through the montane forest to Mandara Hut with stunning views.',
                        city: 'Arusha',
                        country: 'Tanzania',
                        price: { amount: 120, currencyCode: 'USD' },
                        rating: '4.6',
                        duration: 'PT8H',
                        pictures: ['https://images.unsplash.com/photo-1589553416260-f586c8f1514f?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -3.075833, longitude: 37.353333 }
                    },
                    {
                        id: 'dar-city-tour',
                        name: 'Dar es Salaam Cultural City Tour',
                        shortDescription: 'Explore Tanzania\'s vibrant commercial capital',
                        description: 'Discover Dar es Salaam\'s bustling markets, cultural sites, and learn about Tanzanian urban life and history.',
                        city: 'Dar es Salaam',
                        country: 'Tanzania',
                        price: { amount: 50, currencyCode: 'USD' },
                        rating: '4.2',
                        duration: 'PT5H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -6.792354, longitude: 39.208328 }
                    },
                    
                    // Uganda Activities
                    {
                        id: 'kampala-city-tour',
                        name: 'Kampala Cultural Heritage Tour',
                        shortDescription: 'Explore Uganda\'s vibrant capital city',
                        description: 'Discover Kampala\'s seven hills, visit the Kasubi Tombs, local markets, and experience Ugandan culture and history.',
                        city: 'Kampala',
                        country: 'Uganda',
                        price: { amount: 45, currencyCode: 'USD' },
                        rating: '4.4',
                        duration: 'PT5H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 0.347596, longitude: 32.582520 }
                    },
                    {
                        id: 'entebbe-wildlife-tour',
                        name: 'Uganda Wildlife Education Centre Tour',
                        shortDescription: 'Meet Uganda\'s incredible wildlife up close',
                        description: 'Visit the Uganda Wildlife Education Centre in Entebbe and see rescued and rehabilitated Ugandan wildlife species.',
                        city: 'Entebbe',
                        country: 'Uganda',
                        price: { amount: 35, currencyCode: 'USD' },
                        rating: '4.5',
                        duration: 'PT3H',
                        pictures: ['https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 0.042068, longitude: 32.444130 }
                    },
                    {
                        id: 'lake-victoria-cruise',
                        name: 'Lake Victoria Sunset Cruise',
                        shortDescription: 'Cruise on Africa\'s largest lake',
                        description: 'Enjoy a peaceful sunset cruise on Lake Victoria, with opportunities to see local fishing communities and beautiful scenery.',
                        city: 'Entebbe',
                        country: 'Uganda',
                        price: { amount: 60, currencyCode: 'USD' },
                        rating: '4.6',
                        duration: 'PT3H',
                        pictures: ['https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 0.063267, longitude: 32.455051 }
                    },
                    {
                        id: 'jinja-source-nile',
                        name: 'Source of the Nile Adventure Tour',
                        shortDescription: 'Visit the legendary source of the River Nile',
                        description: 'Experience the source of the world\'s longest river with boat rides, cultural experiences, and optional adventure activities.',
                        city: 'Jinja',
                        country: 'Uganda',
                        price: { amount: 75, currencyCode: 'USD' },
                        rating: '4.7',
                        duration: 'PT6H',
                        pictures: ['https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 0.424664, longitude: 33.196373 }
                    },
                    
                    // Rwanda Activities
                    {
                        id: 'kigali-genocide-memorial',
                        name: 'Kigali Genocide Memorial Tour',
                        shortDescription: 'A moving journey through Rwanda\'s history',
                        description: 'Visit the Kigali Genocide Memorial and learn about Rwanda\'s tragic history and remarkable recovery and reconciliation.',
                        city: 'Kigali',
                        country: 'Rwanda',
                        price: { amount: 25, currencyCode: 'USD' },
                        rating: '4.8',
                        duration: 'PT3H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -1.944386, longitude: 30.061890 }
                    },
                    {
                        id: 'kigali-city-tour',
                        name: 'Kigali City & Culture Tour',
                        shortDescription: 'Discover modern Rwanda\'s capital',
                        description: 'Explore Kigali\'s clean streets, vibrant markets, art galleries, and learn about Rwanda\'s impressive development.',
                        city: 'Kigali',
                        country: 'Rwanda',
                        price: { amount: 40, currencyCode: 'USD' },
                        rating: '4.5',
                        duration: 'PT4H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -1.950851, longitude: 30.060509 }
                    },
                    {
                        id: 'volcanoes-park-trek',
                        name: 'Volcanoes National Park Gorilla Trekking',
                        shortDescription: 'Meet mountain gorillas in their natural habitat',
                        description: 'Trek through bamboo forests to encounter endangered mountain gorillas - one of the world\'s most incredible wildlife experiences.',
                        city: 'Kigali',
                        country: 'Rwanda',
                        price: { amount: 1500, currencyCode: 'USD' },
                        rating: '5.0',
                        duration: 'PT8H',
                        pictures: ['https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&h=600&fit=crop'],
                        geoCode: { latitude: -1.462222, longitude: 29.478889 }
                    },
                    
                    // Ethiopia Activities
                    {
                        id: 'addis-coffee-ceremony',
                        name: 'Ethiopian Coffee Ceremony Experience',
                        shortDescription: 'Experience the birthplace of coffee',
                        description: 'Participate in a traditional Ethiopian coffee ceremony and learn about the cultural significance of coffee in Ethiopian society.',
                        city: 'Addis Ababa',
                        country: 'Ethiopia',
                        price: { amount: 30, currencyCode: 'USD' },
                        rating: '4.6',
                        duration: 'PT2H',
                        pictures: ['https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 9.024325, longitude: 38.746799 }
                    },
                    {
                        id: 'addis-cultural-tour',
                        name: 'Addis Ababa Cultural Heritage Tour',
                        shortDescription: 'Explore Ethiopia\'s capital and African Union headquarters',
                        description: 'Visit the National Museum, Merkato market, Holy Trinity Cathedral, and learn about Ethiopia\'s unique history.',
                        city: 'Addis Ababa',
                        country: 'Ethiopia',
                        price: { amount: 55, currencyCode: 'USD' },
                        rating: '4.4',
                        duration: 'PT6H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 9.030000, longitude: 38.740000 }
                    },
                    {
                        id: 'lalibela-churches-tour',
                        name: 'Lalibela Rock-Hewn Churches Tour',
                        shortDescription: 'Visit the New Jerusalem of Africa',
                        description: 'Explore the magnificent rock-hewn churches of Lalibela, a UNESCO World Heritage Site and pilgrimage destination.',
                        city: 'Lalibela',
                        country: 'Ethiopia',
                        price: { amount: 80, currencyCode: 'USD' },
                        rating: '4.9',
                        duration: 'PT6H',
                        pictures: ['https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 12.031389, longitude: 39.013333 }
                    },
                    {
                        id: 'simien-mountains-trek',
                        name: 'Simien Mountains Day Trek',
                        shortDescription: 'Hike through Ethiopia\'s spectacular highlands',
                        description: 'Trek through the Simien Mountains National Park with chances to see Gelada baboons and stunning highland scenery.',
                        city: 'Gondar',
                        country: 'Ethiopia',
                        price: { amount: 90, currencyCode: 'USD' },
                        rating: '4.7',
                        duration: 'PT8H',
                        pictures: ['https://images.unsplash.com/photo-1589553416260-f586c8f1514f?w=800&h=600&fit=crop'],
                        geoCode: { latitude: 13.136667, longitude: 37.466667 }
                    }
                ],
                fallback: true,
                message: 'Comprehensive East African activities - showcasing the best experiences across the region'
            };
            
            console.log(`Displaying ${fallbackData.data.length} East African activities`);
            const processedActivities = processAmadeusActivities(fallbackData);
            displayActivities(processedActivities);
        }

        // Enhanced processAmadeusActivities function to handle comprehensive data
        function processAmadeusActivities(amadeusResponse) {
            console.log('Processing activities data:', amadeusResponse);
            
            // Activity data grouped by city
            const activitiesByCity = {};
            
            // Cities we want to focus on in East Africa
            const eastAfricanCities = [
                'Nairobi', 'Mombasa', 'Zanzibar', 'Dar es Salaam', 'Arusha',
                'Kigali', 'Kampala', 'Entebbe', 'Jinja', 'Addis Ababa', 'Lalibela', 'Gondar'
            ];
            
            // Country mapping
            const countryMapping = {
                'Nairobi': 'Kenya', 'Mombasa': 'Kenya',
                'Zanzibar': 'Tanzania', 'Dar es Salaam': 'Tanzania', 'Arusha': 'Tanzania',
                'Kigali': 'Rwanda',
                'Kampala': 'Uganda', 'Entebbe': 'Uganda', 'Jinja': 'Uganda',
                'Addis Ababa': 'Ethiopia', 'Lalibela': 'Ethiopia', 'Gondar': 'Ethiopia'
            };
            
            // Process each activity from the response
            amadeusResponse.data.forEach((activity, index) => {
                try {
                    // Extract city from the activity data
                    let city = activity.city || 'Unknown';
                    let country = activity.country || countryMapping[city] || 'East Africa';
                    
                    // If city is not recognized, try to extract from name or description
                    if (city === 'Unknown') {
                        eastAfricanCities.forEach(knownCity => {
                            if (activity.name && activity.name.includes(knownCity)) {
                                city = knownCity;
                                country = countryMapping[city];
                            }
                        });
                    }
                    
                    // Initialize the city in our map if not already there
                    if (!activitiesByCity[city]) {
                        activitiesByCity[city] = [];
                    }
                    
                    // Format price
                    let priceInfo = {
                        amount: 'Contact for pricing',
                        currencyCode: 'USD'
                    };
                    
                    if (activity.price && activity.price.amount) {
                        priceInfo = {
                            amount: parseFloat(activity.price.amount),
                            currencyCode: activity.price.currencyCode || 'USD'
                        };
                    }
                    
                    // Format duration
                    let duration = 'Flexible';
                    if (activity.duration) {
                        const durationString = activity.duration;
                        const hourMatch = durationString.match(/(\d+)H/);
                        const minuteMatch = durationString.match(/(\d+)M/);
                        
                        const hours = hourMatch ? parseInt(hourMatch[1]) : 0;
                        const minutes = minuteMatch ? parseInt(minuteMatch[1]) : 0;
                        
                        if (hours > 0 || minutes > 0) {
                            duration = '';
                            if (hours > 0) duration += `${hours}h `;
                            if (minutes > 0) duration += `${minutes}m`;
                            if (duration === '') duration = 'Flexible';
                            duration = duration.trim();
                        }
                    }
                    
                    // Format rating
                    const rating = activity.rating || (4.0 + Math.random() * 1.0).toFixed(1);
                    
                    // Create picture URL array
                    const pictures = [];
                    if (activity.pictures && activity.pictures.length > 0) {
                        activity.pictures.forEach(pic => {
                            pictures.push(pic);
                        });
                    } else {
                        pictures.push(`/images/travel/${city.toLowerCase().replace(/\s+/g, '-')}.jpg`);
                    }
                    
                    // Format descriptions
                    let shortDescription = activity.shortDescription || 'Experience the best of East Africa with this unique tour';
                    let description = activity.description || shortDescription;
                    
                    if (description.length > 120) {
                        shortDescription = description.substring(0, 117) + '...';
                    }
                    
                    // Create the processed activity object
                    const processedActivity = {
                        id: activity.id || `activity-${index + 1}`,
                        name: activity.name,
                        shortDescription: shortDescription,
                        description: description,
                        city: city,
                        country: country,
                        price: priceInfo,
                        rating: rating,
                        duration: duration,
                        pictures: pictures,
                        inclusions: activity.inclusions || ['Professional guide', 'Transportation', 'Entrance fees'],
                        startTimes: activity.startTimes || ['08:00 AM', '02:00 PM'],
                        activityType: activity.activityType || 'Cultural Experience',
                        highlights: activity.highlights || ['Expert guidance', 'Authentic experience', 'Small group'],
                        amadeusData: activity
                    };
                    
                    // Add to the city's activities
                    activitiesByCity[city].push(processedActivity);
                } catch (error) {
                    console.error('Error processing activity:', error, activity);
                }
            });
            
            console.log('Processed activities by city:', activitiesByCity);
            return activitiesByCity;
        }

        // Enhanced displayActivities function with better error handling
        function displayActivities(cityActivitiesMap) {
            console.log('Displaying activities:', cityActivitiesMap);
            
            // Store the data globally
            allActivitiesByCityMap = cityActivitiesMap;
            
            // Hide spinner
            const spinner = document.getElementById('activities-spinner');
            if (spinner) {
                spinner.style.display = 'none';
            }
            
            // Get elements
            const citiesTabList = document.getElementById('activities-cities-tab');
            const citiesTabContent = document.getElementById('activities-cities-content');
            
            if (!citiesTabList || !citiesTabContent) {
                console.error('Required activity tab elements not found');
                return;
            }
            
            // Clear existing tabs (except All Cities)
            Array.from(citiesTabList.querySelectorAll('li:not(:first-child)')).forEach(tab => {
                tab.remove();
            });
            
            // Clear existing tab panes (except All Cities)
            Array.from(citiesTabContent.querySelectorAll('.tab-pane:not(#all-cities)')).forEach(pane => {
                pane.remove();
            });
            
            // Count total activities and locations
            let totalActivities = 0;
            const cityNames = Object.keys(cityActivitiesMap);
            
            // Create city tabs
            cityNames.forEach((cityName, index) => {
                const cityActivities = cityActivitiesMap[cityName];
                totalActivities += cityActivities.length;
                
                // Create tab button for city
                const cityId = cityName.toLowerCase().replace(/\s+/g, '-');
                const tabButton = document.createElement('li');
                tabButton.className = 'nav-item mx-1';
                tabButton.role = 'presentation';
                tabButton.innerHTML = `
                    <button class="nav-link px-2 py-1" id="${cityId}-tab" data-bs-toggle="pill" 
                        data-bs-target="#${cityId}" type="button" role="tab" style="font-size: 0.7rem;">
                        ${cityName} (${cityActivities.length})
                    </button>
                `;
                citiesTabList.appendChild(tabButton);
                
                // Create tab pane for city
                const tabPane = document.createElement('div');
                tabPane.className = 'tab-pane fade';
                tabPane.id = cityId;
                tabPane.role = 'tabpanel';
                
                const cityRow = document.createElement('div');
                cityRow.className = 'row g-4';
                cityRow.id = `${cityId}-container`;
                
                tabPane.appendChild(cityRow);
                
                // Add pagination for this city
                const cityPagination = document.createElement('div');
                cityPagination.className = 'd-flex justify-content-center mt-5';
                cityPagination.id = `${cityId}-pagination`;
                tabPane.appendChild(cityPagination);
                
                citiesTabContent.appendChild(tabPane);
                
                // Add event listener for tab change
                const tabElement = document.getElementById(`${cityId}-tab`);
                if (tabElement) {
                    tabElement.addEventListener('click', function() {
                        currentCity = cityId;
                        currentPage = 1;
                        displayActivitiesForCity(cityId, 1);
                    });
                }
            });
            
            // Add event listener for all cities tab
            const allCitiesTab = document.getElementById('all-cities-tab');
            if (allCitiesTab) {
                allCitiesTab.addEventListener('click', function() {
                    currentCity = 'all';
                    currentPage = 1;
                    displayAllActivities(1);
                });
            }
            
            // Display initial activities (all cities, page 1)
            displayAllActivities(1);
            
            // Update counts
            const locationCountElement = document.getElementById('activity-locations-count');
            const activityCountElement = document.getElementById('activity-count');
            
            if (locationCountElement) {
                locationCountElement.textContent = cityNames.length;
            }
            if (activityCountElement) {
                activityCountElement.textContent = totalActivities;
            }
            
            console.log(`Successfully displayed ${totalActivities} activities across ${cityNames.length} cities`);
        }

        // Add route-specific search functions for East African destinations
        function searchEastAfricanFlights() {
            console.log('Searching East African flight routes...');
            
            const popularRoutes = [
                { from: 'NBO', to: 'DAR', route: 'Nairobi → Dar es Salaam' },
                { from: 'NBO', to: 'ADD', route: 'Nairobi → Addis Ababa' },
                { from: 'NBO', to: 'EBB', route: 'Nairobi → Entebbe' },
                { from: 'NBO', to: 'KGL', route: 'Nairobi → Kigali' },
                { from: 'NBO', to: 'ZNZ', route: 'Nairobi → Zanzibar' },
                { from: 'DAR', to: 'ZNZ', route: 'Dar es Salaam → Zanzibar' },
                { from: 'ADD', to: 'NBO', route: 'Addis Ababa → Nairobi' }
            ];
            
            // You can implement specific route searches here
            popularRoutes.forEach(route => {
                console.log(`Available route: ${route.route}`);
            });
        }

        function searchEastAfricanHotels() {
            console.log('Searching East African hotels...');
            
            const popularDestinations = [
                'Nairobi', 'Mombasa', 'Zanzibar', 'Dar es Salaam', 'Arusha',
                'Kigali', 'Kampala', 'Entebbe', 'Addis Ababa', 'Lalibela'
            ];
            
            popularDestinations.forEach(destination => {
                console.log(`Hotels available in: ${destination}`);
            });
        }

        // Initialize comprehensive East African search on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing comprehensive East African travel search...');
            
            // Load activities with comprehensive fallback
            loadActivities();
            
            // Initialize other search functions
            searchEastAfricanFlights();
            searchEastAfricanHotels();
            
            console.log('East African travel search initialized successfully');
        });
        </script>
</section> 


<!-- Tours & Activities Section -->
{{-- <section class="deals-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5 position-relative">
            <!-- Animated Background Elements - Tours & Activities Theme -->
            <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none;">
                <div class="floating-icon" style="position: absolute; top: 20%; left: 15%; animation: dealBounce 4s ease-in-out infinite;">
                    <i class="fas fa-hiking" style="color: rgba(40, 167, 69, 0.15); font-size: 2.5rem; transform: rotate(-15deg);"></i>
                </div>
                <div class="floating-icon" style="position: absolute; top: 25%; right: 20%; animation: dealFloat 6s ease-in-out infinite reverse;">
                    <i class="fas fa-binoculars" style="color: rgba(40, 167, 69, 0.15); font-size: 2rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 25%; left: 25%; animation: dealSpin 8s linear infinite;">
                    <i class="fas fa-camera" style="color: rgba(40, 167, 69, 0.15); font-size: 1.8rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 30%; right: 15%; animation: dealBounce 5s ease-in-out infinite;">
                    <i class="fas fa-mountain" style="color: rgba(40, 167, 69, 0.15); font-size: 1.5rem;"></i>
                </div>
            </div>
            
            <!-- Main Heading with Enhanced Typography -->
            <div class="position-relative z-index-2">
                <div class="mb-3">
                    <span class="badge px-4 py-2 rounded-pill" style="background: linear-gradient(45deg, #FF8E3C, #FF5733); color: white; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">
                        � GUIDED TOURS & SAFARIS
                    </span>
                </div>
                
                <h2 class="fw-bold mb-3" style="color: #495057; font-size: 2.5rem; line-height: 1.2; position: relative;">
                    <span class="position-relative">
                        <i class="fas fa-globe-africa me-3" style="color: #FF5733; font-size: 2rem; vertical-align: middle; text-shadow: 0 0 20px rgba(255, 87, 51, 0.3); animation: activityPulse 3s ease-in-out infinite;"></i>
                        Featured Tours
                        <!-- Decorative underline -->
                        <div class="position-absolute w-100" style="bottom: -8px; left: 0; height: 3px; background: linear-gradient(90deg, transparent, #FF5733, transparent); border-radius: 3px;"></div>
                    </span>
                </h2>
                
                <p class="mb-4" style="color: #6c757d; font-size: 1.1rem; font-weight: 300; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                    <span class="d-inline-block" style="border-left: 3px solid #FF5733; padding-left: 15px;">
                        Discover unforgettable guided tours across East Africa
                        <br>
                        <small style="color: rgba(108, 117, 125, 0.8); font-size: 0.9rem;">
                            Expert guides • All-inclusive packages • Once-in-a-lifetime experiences
                        </small>
                    </span>
                </p>
                
                <!-- Tour stats indicator -->
                <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-thumbs-up me-2" style="color: #FF5733; animation: bounce 2s infinite;"></i>
                        <span style="color: #495057; font-size: 0.9rem; font-weight: 500;">
                            <span id="tour-satisfaction">98%</span> Satisfaction Rate
                        </span>
                    </div>
                    <div style="width: 2px; height: 20px; background: rgba(255, 87, 51, 0.3);"></div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check me-2" style="color: #FF5733;"></i>
                        <span style="color: rgba(73, 80, 87, 0.8); font-size: 0.9rem;">
                            <span id="tour-count">12</span> Available Tours
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div class="row mb-4" id="tours-spinner">
            <div class="col-12 text-center py-5">
                <div class="spinner-border" role="status" style="color: #FF5733;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Discovering amazing tours across East Africa...</p>
            </div>
        </div>

        <!-- Tours Container -->
        <div class="row g-4" id="tours-container">
            <!-- Tours will be dynamically inserted here -->
        </div>
        
        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Tours pagination" class="d-flex justify-content-center">
                    <ul class="pagination" id="tours-pagination">
                        <!-- Pagination will be dynamically generated -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Tour Card Template -->
    <template id="tour-card-template">
        <div class="col-md-4 mb-4 tour-card">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-lift h-100">
                <div class="position-relative">
                    <img src="" class="card-img-top tour-image" alt="Tour" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge rounded-pill px-3 py-2 tour-duration" style="background-color: rgba(255, 87, 51, 0.85); color: white;">
                            <i class="fas fa-clock me-1"></i><span class="duration-text">3 Days</span>
                        </span>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-white-50 tour-locations">Nairobi, Kenya</small>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark rounded-pill tour-type">Safari</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-2 tour-name">Masai Mara Safari</h5>
                    <p class="card-text text-muted small mb-3 tour-description">Experience the incredible wildlife of the Masai Mara on this guided safari tour.</p>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-muted me-1" style="font-size: 12px;"></i>
                            <small class="text-muted tour-group-size">Small Group</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1" style="font-size: 12px;"></i>
                            <small class="text-muted tour-rating">4.8 (120 reviews)</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="price fs-4 fw-bold tour-price" style="color: #FF5733;">USD 450.00</span>
                            <small class="text-muted d-block tour-price-info">per person</small>
                        </div>
                        <a href="#" class="btn rounded-pill px-4 book-tour-btn" style="background-color: #FF5733; color: white;">
                            <i class="fas fa-map-marked-alt me-1"></i>Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Enhanced JavaScript for Tours with Pagination -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadTours();
        });

        function loadTours(page = 1) {
            document.getElementById('tours-spinner').style.display = 'block';
            
            fetch(`/east-african-tours?page=${page}&per_page=6`)
                .then(response => response.json())
                .then(data => {
                    displayTours(data.tours);
                    setupPagination(data.pagination);
                    document.getElementById('tour-count').textContent = data.pagination.total;
                    document.getElementById('tours-spinner').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error fetching tours:', error);
                    displayDummyTours();
                    document.getElementById('tours-spinner').style.display = 'none';
                });
        }
        
        function displayTours(tours) {
            const container = document.getElementById('tours-container');
            container.innerHTML = '';
            
            tours.forEach(tour => {
                const template = document.getElementById('tour-card-template');
                const tourCard = template.content.cloneNode(true);
                
                // Set tour data
                const image = tourCard.querySelector('.tour-image');
                
                // Set image with error handling
                if (tour.imageUrl) {
                    image.src = tour.imageUrl;
                } else {
                    // Use local image based on city/destination
                    let destination = tour.destination || tour.city || 'safari';
                    destination = destination.toLowerCase().replace(/\s+/g, '-');
                    image.src = `/images/travel/${destination}.jpg`;
                    
                    // Handle image loading errors
                    image.onerror = function() {
                        this.src = `/images/travel/safari.jpg`;
                        
                        // Final fallback
                        this.onerror = function() {
                            this.src = `/images/travel/destination-default.jpg`;
                            this.onerror = null;
                        };
                    };
                }
                image.alt = tour.tourName || tour.name;
                
                // Set duration badge
                tourCard.querySelector('.duration-text').textContent = tour.duration || '3 Days';
                
                // Set locations and type
                tourCard.querySelector('.tour-locations').textContent = tour.locations || `${tour.city || 'Nairobi'}, ${tour.country || 'Kenya'}`;
                tourCard.querySelector('.tour-type').textContent = tour.tourType || tour.type || 'Safari';
                
                // Set name and description
                tourCard.querySelector('.tour-name').textContent = tour.tourName || tour.name;
                tourCard.querySelector('.tour-description').textContent = tour.description || tour.shortDescription || 'Explore the beauty of East Africa on this guided tour.';
                
                // Set group size and rating
                tourCard.querySelector('.tour-group-size').textContent = tour.groupSize || 'Small Group';
                tourCard.querySelector('.tour-rating').textContent = tour.rating ? `${tour.rating} (${tour.reviewCount || 0} reviews)` : '4.8 (120 reviews)';
                
                // Set price
                const price = tourCard.querySelector('.tour-price');
                const priceInfo = tourCard.querySelector('.tour-price-info');
                if (tour.price) {
                    price.textContent = `${tour.price.currencyCode || 'USD'} ${parseFloat(tour.price.amount).toLocaleString()}`;
                    priceInfo.textContent = tour.price.perPerson ? 'per person' : 'per tour';
                } else {
                    price.textContent = 'USD 450.00';
                    priceInfo.textContent = 'per person';
                }
                
                // Set up booking button
                const bookButton = tourCard.querySelector('.book-tour-btn');
                bookButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Redirect to tour deal booking page
                    window.location.href = `/deals/tours/${tour.id || tour.tourId}?highlight_id=${tour.id || tour.tourId}`;
                });
                
                container.appendChild(tourCard);
            });
        }
        
        function setupPagination(pagination) {
            const paginationEl = document.getElementById('tours-pagination');
            paginationEl.innerHTML = '';
            
            // Create previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${pagination.current_page === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>`;
            if (pagination.current_page > 1) {
                prevLi.querySelector('a').addEventListener('click', (e) => {
                    e.preventDefault();
                    loadTours(pagination.current_page - 1);
                });
            }
            paginationEl.appendChild(prevLi);
            
            // Create page buttons
            for (let i = 1; i <= pagination.last_page; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageLi.querySelector('a').addEventListener('click', (e) => {
                    e.preventDefault();
                    if (i !== pagination.current_page) {
                        loadTours(i);
                    }
                });
                paginationEl.appendChild(pageLi);
            }
            
            // Create next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>`;
            if (pagination.current_page < pagination.last_page) {
                nextLi.querySelector('a').addEventListener('click', (e) => {
                    e.preventDefault();
                    loadTours(pagination.current_page + 1);
                });
            }
            paginationEl.appendChild(nextLi);
        }
        
        function displayDummyTours() {
            const dummyTours = [
                {
                    id: 'tour1',
                    tourName: 'Masai Mara Safari Adventure',
                    description: 'Experience the incredible wildlife of the Masai Mara on this guided safari tour.',
                    city: 'Nairobi',
                    country: 'Kenya',
                    duration: '3 Days',
                    tourType: 'Safari',
                    groupSize: 'Small Group (6-12)',
                    rating: 4.9,
                    reviewCount: 142,
                    price: {
                        amount: 450,
                        currencyCode: 'USD',
                        perPerson: true
                    },
                    locations: 'Nairobi & Masai Mara, Kenya'
                },
                {
                    id: 'tour2',
                    tourName: 'Mount Kilimanjaro Trek',
                    description: 'Conquer Africa\'s highest peak with experienced guides and porters.',
                    city: 'Arusha',
                    country: 'Tanzania',
                    duration: '7 Days',
                    tourType: 'Trekking',
                    groupSize: 'Small Group (4-10)',
                    rating: 4.8,
                    reviewCount: 98,
                    price: {
                        amount: 1850,
                        currencyCode: 'USD',
                        perPerson: true
                    },
                    locations: 'Arusha & Kilimanjaro, Tanzania'
                },
                {
                    id: 'tour3',
                    tourName: 'Zanzibar Beach & Culture',
                    description: 'Discover the rich history and pristine beaches of Zanzibar Island.',
                    city: 'Zanzibar',
                    country: 'Tanzania',
                    duration: '5 Days',
                    tourType: 'Cultural',
                    groupSize: 'Small Group (6-14)',
                    rating: 4.7,
                    reviewCount: 87,
                    price: {
                        amount: 650,
                        currencyCode: 'USD',
                        perPerson: true
                    },
                    locations: 'Stone Town & East Coast, Zanzibar'
                },
                {
                    id: 'tour4',
                    tourName: 'Bwindi Gorilla Tracking',
                    description: 'Trek through the impenetrable forest to encounter endangered mountain gorillas.',
                    city: 'Entebbe',
                    country: 'Uganda',
                    duration: '4 Days',
                    tourType: 'Wildlife',
                    groupSize: 'Small Group (6-8)',
                    rating: 5.0,
                    reviewCount: 76,
                    price: {
                        amount: 1250,
                        currencyCode: 'USD',
                        perPerson: true
                    },
                    locations: 'Entebbe & Bwindi, Uganda'
                },
                {
                    id: 'tour5',
                    tourName: 'Serengeti Migration Safari',
                    description: 'Witness the spectacular wildebeest migration across the Serengeti plains.',
                    city: 'Arusha',
                    country: 'Tanzania',
                    duration: '6 Days',
                    tourType: 'Safari',
                    groupSize: 'Small Group (6-10)',
                    rating: 4.9,
                    reviewCount: 124,
                    price: {
                        amount: 1950,
                        currencyCode: 'USD',
                        perPerson: true
                    },
                    locations: 'Serengeti & Ngorongoro, Tanzania'
                },
                {
                    id: 'tour6',
                    tourName: 'Nairobi Cultural Experience',
                    description: 'Explore Kenya\'s vibrant capital city and its rich cultural heritage.',
                    city: 'Nairobi',
                    country: 'Kenya',
                    duration: '2 Days',
                    tourType: 'Cultural',
                    groupSize: 'Small Group (4-12)',
                    rating: 4.6,
                    reviewCount: 68,
                    price: {
                        amount: 280,
                        currencyCode: 'USD',
                        perPerson: true
                    },
                    locations: 'Nairobi, Kenya'
                }
            ];
            
            // Display the dummy tours
            displayTours(dummyTours);
            
            // Setup dummy pagination
            setupPagination({
                current_page: 1,
                last_page: 2,
                total: 12
            });
            
            // Update tour count
            document.getElementById('tour-count').textContent = '12';
        }
    </script>
</section> --}}

<!-- Flight Search Call to Action -->
<section class="bottom-hero position-relative" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/airplane.jpg') }}'); background-size: cover; background-position: center; border-radius: 8px; min-height: 137px; width: 85%; margin: 1.75rem auto 1rem auto; box-shadow: 0 5px 10px rgba(0,0,0,0.2); overflow: hidden;">
    <!-- Animated overlay for depth effect -->
    <div class="position-absolute w-100 h-100 top-0 left-0" style="background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.4) 100%);"></div>
    
    <!-- Subtle animated elements -->
    <div class="position-absolute" style="top: 10%; right: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(40px); animation: float 8s ease-in-out infinite;"></div>
    <div class="position-absolute" style="bottom: 15%; left: 15%; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(50px); animation: float 12s ease-in-out infinite 1s;"></div>
    
    <div class="position-relative h-100 d-flex align-items-center text-start">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <!-- Badge -->
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark mt-4 px-3 py-2 rounded-pill fw-bold" style="font-size: 0.9rem; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);">
                            <i class="fas fa-compass me-2"></i>EXPLORE THE WORLD
                        </span>
                    </div>
                    
                    <p class="text-white fs-6 mb-2 opacity-90" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-weight: 400; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Can't decide where to go?</p>
                    
                    <h2 class="fw-bold text-white mb-3" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-weight: 900; font-size: 2.2rem; line-height: 1.2; color: rgb(255, 255, 255); text-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                        Explore every destination
                        <div class="mt-2" style="width: 100px; height: 5px; background: linear-gradient(90deg, #ffc107, transparent); border-radius: 10px;"></div>
                    </h2>
                    
                    <p class="text-white mb-3 opacity-90" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; max-width: 600px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); font-size: 1rem;">
                        Discover breathtaking destinations, incredible deals, and unforgettable experiences across the globe with our extensive flight network.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <button class="btn btn-light px-4 py-2 fw-bold rounded-pill d-inline-flex align-items-center" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; box-shadow: 0 5px 15px rgba(255,255,255,0.2); transition: all 0.3s ease; font-size: 0.95rem;">
                            <i class="fas fa-search me-2"></i>
                            <span>Search flights</span>
                        </button>
                        <button class="btn btn-outline-light px-4 py-2 fw-bold rounded-pill d-inline-flex align-items-center" style="font-family: 'Skyscanner Relative', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; border-width: 2px; transition: all 0.3s ease; font-size: 0.95rem;">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            <span>View destinations</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Diagonal shape decoration -->
    <div class="position-absolute" style="bottom: 0; right: 0; width: 40%; height: 100%; background: linear-gradient(135deg, transparent 50%, rgba(255,255,255,0.1) 100%);"></div>
    
    <!-- Floating elements -->
    <div class="position-absolute" style="top: 15%; right: 10%; animation: pulse 4s infinite ease-in-out;">
        <i class="fas fa-paper-plane text-white opacity-50" style="font-size: 2.5rem; transform: rotate(-15deg);"></i>
    </div>
    <div class="position-absolute" style="bottom: 20%; right: 20%; animation: float 6s infinite ease-in-out;">
        <i class="fas fa-globe-americas text-white opacity-50" style="font-size: 3rem;"></i>
    </div>
</section>



<!-- Popular Destinations - East African Cities -->
<section class="destinations-section-modern py-3 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.05;">
        <div class="floating-icon" style="position: absolute; top: 15%; left: 10%; animation: destinationFloat 8s ease-in-out infinite;">
            <i class="fas fa-map-marked-alt" style="font-size: 2rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 25%; right: 15%; animation: destinationFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-compass" style="font-size: 1.5rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 20%; left: 20%; animation: destinationFloat 12s ease-in-out infinite;">
            <i class="fas fa-mountain" style="font-size: 1.75rem; color: #94a3b8;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 30%; right: 25%; animation: destinationFloat 9s ease-in-out infinite reverse;">
            <i class="fas fa-landmark" style="font-size: 1.25rem; color: #94a3b8;"></i>
        </div>
    </div>

    <div class="container position-relative">
        <!-- Enhanced Header -->
        <div class="text-center mb-2">
            <div class="mb-1">
                <span class="badge px-2 py-1 rounded-pill" style="background: #6b3125; color: white; font-weight: 600; font-size: 0.45rem; letter-spacing: 0.5px; backdrop-filter: blur(5px);">
                    🌍 EAST AFRICAN GETAWAYS
                </span>
            </div>
            
            <h2 class="fw-bold mb-2" style="color: #1e293b; font-size: 1.4rem; line-height: 1.2; position: relative;">
                <span class="position-relative">
                    <i class="fas fa-globe-africa me-2" style="color: #f59e0b; font-size: 1.25rem; vertical-align: middle; filter: drop-shadow(0 0 7px rgba(245, 158, 11, 0.4)); animation: globeRotate 10s linear infinite;"></i>
                    Explore East Africa
                    <!-- Decorative underline -->
                    <div class="position-absolute w-100" style="bottom: -6px; left: 0; height: 2px; background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.6), transparent); border-radius: 2px;"></div>
                </span>
            </h2>
            
            <p class="mb-2" style="color: #64748b; font-size: 0.6rem; font-weight: 300; max-width: 350px; margin: 0 auto; line-height: 1.4;">
                <span class="d-inline-block" style="border-left: 1.5px solid #f59e0b; padding-left: 7px;">
                    Discover the beauty of East Africa with direct flights from Nairobi
                    <br>
                    <small style="color: rgba(100, 116, 139, 0.8); font-size: 0.5rem;">
                        Exclusive deals • Flexible booking • Authentic experiences
                    </small>
                </span>
            </p>
        </div>

        <!-- Modern Infinite Carousel -->
        <div class="destinations-carousel-wrapper position-relative">
            <!-- Carousel Container -->
            <div class="destinations-carousel-container overflow-hidden" style="border-radius: 20px; position: relative;">
                <div class="destinations-carousel d-flex" id="destinationsCarousel" style="transition: transform 0.5s ease-in-out;">
                    @php
                        $destinations = [
                            [
                                'id' => 'dest-mba-001',
                                'type' => 'flight',
                                'name' => 'Mombasa', 
                                'price' => 'KES 15,000', 
                                'image' => 'travel/mombasa.jpg', 
                                'country' => 'Kenya', 
                                'rating' => '4.8', 
                                'reviews' => '2.5K', 
                                'tag' => 'Beach', 
                                'from' => 'NBO',
                                'to' => 'MBA',
                                'duration' => '1h 5m',
                                'departure_date' => date('Y-m-d', strtotime('+7 days')),
                                'return_date' => date('Y-m-d', strtotime('+14 days'))
                            ],
                            [
                                'id' => 'dest-znz-002',
                                'type' => 'flight',
                                'name' => 'Zanzibar', 
                                'price' => 'KES 22,000', 
                                'image' => 'travel/zanzibar.jpg', 
                                'country' => 'Tanzania', 
                                'rating' => '4.9', 
                                'reviews' => '3.2K', 
                                'tag' => 'Island', 
                                'from' => 'NBO',
                                'to' => 'ZNZ',
                                'duration' => '1h 30m',
                                'departure_date' => date('Y-m-d', strtotime('+10 days')),
                                'return_date' => date('Y-m-d', strtotime('+17 days'))
                            ],
                            [
                                'id' => 'dest-kgl-003',
                                'type' => 'flight',
                                'name' => 'Kigali', 
                                'price' => 'KES 18,500', 
                                'image' => 'travel/kigali.jpg', 
                                'country' => 'Rwanda', 
                                'rating' => '4.7', 
                                'reviews' => '1.8K', 
                                'tag' => 'Cultural', 
                                'from' => 'NBO',
                                'to' => 'KGL',
                                'duration' => '1h 20m',
                                'departure_date' => date('Y-m-d', strtotime('+8 days')),
                                'return_date' => date('Y-m-d', strtotime('+15 days'))
                            ],
                            [
                                'id' => 'dest-dar-004',
                                'type' => 'flight',
                                'name' => 'Dar es Salaam', 
                                'price' => 'KES 19,000', 
                                'image' => 'travel/dar-es-salaam.jpg', 
                                'country' => 'Tanzania', 
                                'rating' => '4.6', 
                                'reviews' => '1.9K', 
                                'tag' => 'Coastal', 
                                'from' => 'NBO',
                                'to' => 'DAR',
                                'duration' => '1h 35m',
                                'departure_date' => date('Y-m-d', strtotime('+12 days')),
                                'return_date' => date('Y-m-d', strtotime('+19 days'))
                            ],
                            [
                                'name' => 'Entebbe', 
                                'price' => 'KES 16,500', 
                                'image' => 'travel/entebbe.jpg', 
                                'country' => 'Uganda', 
                                'rating' => '4.5', 
                                'reviews' => '1.7K', 
                                'tag' => 'Lake', 
                                'from' => 'NBO',
                                'to' => 'EBB',
                                'duration' => '1h 10m',
                                'departure_date' => date('Y-m-d', strtotime('+9 days')),
                                'return_date' => date('Y-m-d', strtotime('+16 days'))
                            ],
                            [
                                'id' => 'dest-ark-005',
                                'type' => 'flight',
                                'name' => 'Arusha', 
                                'price' => 'KES 17,800', 
                                'image' => 'travel/arusha.jpg', 
                                'country' => 'Tanzania', 
                                'rating' => '4.7', 
                                'reviews' => '2.0K', 
                                'tag' => 'Safari', 
                                'from' => 'NBO',
                                'to' => 'ARK',
                                'duration' => '1h 15m',
                                'departure_date' => date('Y-m-d', strtotime('+11 days')),
                                'return_date' => date('Y-m-d', strtotime('+18 days'))
                            ],
                            [
                                'id' => 'dest-add-006',
                                'type' => 'flight',
                                'name' => 'Addis Ababa', 
                                'price' => 'KES 24,000', 
                                'image' => 'travel/addis-ababa.jpg', 
                                'country' => 'Ethiopia', 
                                'rating' => '4.6', 
                                'reviews' => '1.6K', 
                                'tag' => 'Historic', 
                                'from' => 'NBO',
                                'to' => 'ADD',
                                'duration' => '2h 10m',
                                'departure_date' => date('Y-m-d', strtotime('+13 days')),
                                'return_date' => date('Y-m-d', strtotime('+20 days'))
                            ],
                            [
                                'id' => 'dest-jub-007',
                                'type' => 'flight',
                                'name' => 'Juba', 
                                'price' => 'KES 22,500', 
                                'image' => 'travel/juba.jpg', 
                                'country' => 'South Sudan', 
                                'rating' => '4.4', 
                                'reviews' => '1.1K', 
                                'tag' => 'Emerging', 
                                'from' => 'NBO',
                                'to' => 'JUB',
                                'duration' => '1h 45m',
                                'departure_date' => date('Y-m-d', strtotime('+14 days')),
                                'return_date' => date('Y-m-d', strtotime('+21 days'))
                            ]
                        ];
                        
                        // Duplicate destinations for infinite scroll
                        $allDestinations = array_merge($destinations, $destinations);
                    @endphp
                    
                    @foreach($allDestinations as $index => $destination)
                    <div class="destination-card-modern flex-shrink-0 position-relative me-2" style="width: 160px; cursor: pointer;" 
                         data-aos="fade-up" data-aos-delay="{{ ($index % count($destinations)) * 50 }}">
                        
                        <!-- Main Card - Enhanced with smaller image and glass morphism -->
                        <div class="card border-0 shadow-xl rounded-2 overflow-hidden h-100 position-relative destination-card-inner"
                             data-destination="{{ json_encode($destination) }}" 
                             style="backdrop-filter: blur(5px); transition: all 0.4s ease;">
                            <!-- Image Container with Overlay - Reduced Height -->
                            <div class="image-container position-relative" style="height: 120px; overflow: hidden;">
                                <img src="{{ asset('images/' . $destination['image']) }}" 
                                     class="card-img-top w-100 h-100" 
                                     style="object-fit: cover; transition: transform 0.8s ease;" 
                                     alt="{{ $destination['name'] }}"
                                     onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                                
                                <!-- Enhanced Gradient Overlay with depth -->
                                <div class="position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(45deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.5) 100%);"></div>
                                
                                <!-- Top Right Elements - Stylish Badge -->
                                <div class="position-absolute top-0 end-0 p-2 d-flex flex-column gap-1">
                                    <!-- Category Tag - More visible -->
                                    <span class="badge rounded-pill px-2 py-0" style="background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; font-size: 0.6rem; backdrop-filter: blur(5px); box-shadow: 0 1px 3px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.2);">
                                        {{ $destination['tag'] }}
                                    </span>
                                </div>
                                
                                <!-- Rating Badge - Enhanced -->
                                <div class="position-absolute top-0 start-0 p-2">
                                    <div class="d-flex align-items-center bg-dark rounded-pill px-2 py-0" style="background: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                                        <i class="fas fa-star text-warning me-1" style="font-size: 0.5rem;"></i>
                                        <span class="text-white fw-bold" style="font-size: 0.5rem;">{{ $destination['rating'] }}</span>
                                        <small class="text-white-50 ms-1" style="font-size: 0.4rem;">({{ $destination['reviews'] }})</small>
                                    </div>
                                </div>
                                
                                <!-- Subtle destination name overlay at bottom of image -->
                                <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                    <h5 class="text-white mb-0 fw-bold" style="font-size: 0.9rem;">{{ $destination['name'] }}</h5>
                                    <p class="text-white-50 mb-0 d-flex align-items-center" style="font-size: 0.6rem;">
                                        <i class="fas fa-map-marker-alt me-1 text-danger" style="font-size: 0.6rem;"></i>
                                        {{ $destination['country'] }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Card Body - Redesigned with Modern Style -->
                            <div class="card-body p-2 bg-white" style="border-top: 1px solid rgba(0,0,0,0.05);">
                                <!-- Flight Info - Enhanced and Modern -->
                                <div class="d-flex justify-content-between align-items-center mb-1 p-1 rounded-2" style="background: linear-gradient(to right, rgba(6, 182, 212, 0.08), rgba(6, 182, 212, 0.03)); border: 1px solid rgba(6, 182, 212, 0.1);">
                                    <div class="text-center">
                                        <div class="fw-bold text-primary" style="font-size: 0.6rem;">NBO</div>
                                        <small class="text-muted d-block" style="font-size: 0.5rem;">Nairobi</small>
                                    </div>
                                    <div class="text-center flex-grow-1 px-1">
                                        <div class="flight-line position-relative">
                                            <i class="fas fa-plane" style="font-size: 0.5rem; color: #06b6d4; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);"></i>
                                            <hr style="border-top: 1px dashed #ccc; margin-top: 5px;">
                                        </div>
                                        <span class="badge rounded-pill py-0 px-1" style="background-color: rgba(6, 182, 212, 0.15); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.3); font-size: 0.4rem;">
                                            <i class="far fa-clock me-1" style="font-size: 0.4rem;"></i>
                                            {{ $destination['duration'] }}
                                        </span>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold text-primary" style="font-size: 0.6rem;">{{ $destination['to'] }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.5rem;">{{ $destination['name'] }}</small>
                                    </div>
                                </div>
                                
                                <!-- Price Section - Enhanced -->
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div>
                                        <span class="text-muted" style="font-size: 0.5rem;">Round trip from</span>
                                        <div class="price-display">
                                            <span class="fw-bold" style="color: #059669; font-size: 0.75rem;">{{ $destination['price'] }}</span>
                                            <small class="text-muted ms-1" style="font-size: 0.4rem;">per person</small>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <span class="badge bg-success-subtle text-success px-1 py-0 rounded-pill" style="font-size: 0.4rem;">
                                            <i class="fas fa-tag me-1" style="font-size: 0.4rem;"></i>Best Deal
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Features - Enhanced with Icons -->
                                <div class="features-grid mb-1 p-1 border rounded-2 bg-light">
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <div class="feature-item d-flex align-items-center">
                                                <i class="fas fa-plane-departure me-1" style="color: #06b6d4; font-size: 0.45rem;"></i>
                                                <span style="font-size: 0.4rem;">Direct flight</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="feature-item d-flex align-items-center">
                                                <i class="fas fa-luggage-cart me-1" style="color: #06b6d4; font-size: 0.45rem;"></i>
                                                <span style="font-size: 0.4rem;">23kg baggage</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="feature-item d-flex align-items-center">
                                                <i class="fas fa-calendar-check me-1" style="color: #10b981; font-size: 0.45rem;"></i>
                                                <span style="font-size: 0.4rem;">Free rescheduling</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="feature-item d-flex align-items-center">
                                                <i class="fas fa-headset me-1" style="color: #f59e0b; font-size: 0.45rem;"></i>
                                                <span style="font-size: 0.4rem;">24/7 support</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Book Now Button - Enhanced with Animation -->
                                <button class="btn w-100 py-1 fw-bold rounded-pill book-now-btn position-relative overflow-hidden" 
                                    style="background: linear-gradient(45deg, #f59e0b, #d97706); border: none; color: white; transition: all 0.3s ease; font-size: 0.5rem; box-shadow: 0 2px 5px rgba(245, 158, 11, 0.3);">
                                    <span class="position-relative">
                                        <i class="fas fa-paper-plane me-1" style="font-size: 0.5rem;"></i>
                                        <span>Book Now</span>
                                    </span>
                                    <div class="position-absolute top-0 start-0 w-0 h-100 bg-white opacity-20" 
                                         style="transition: all 0.5s ease;"></div>
                                </button>
                            </div>
                            
                            <!-- Action Buttons Popup (appears on hover) -->
                            <div class="action-buttons-popup position-absolute w-100 d-flex justify-content-center align-items-center gap-1 px-1" 
                                 style="bottom: -30px; left: 0; z-index: 15; opacity: 0; transform: translateY(10px); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                
                                <!-- Wishlist Button -->
                                <button class="btn btn-outline-light btn-sm rounded-circle wishlist-action-btn" 
                                        style="width: 22px; height: 22px; background: rgba(255, 255, 255, 0.95); border: 1px solid #e11d48; color: #e11d48; backdrop-filter: blur(5px); transition: all 0.3s ease; padding: 0;"
                                        data-type="destination" 
                                        data-item-id="{{ $destination['name'] }}">
                                    <i class="far fa-heart" style="font-size: 0.45rem;"></i>
                                </button>
                                
                                <!-- Details Button -->
                                <button class="btn btn-primary btn-sm px-1 details-action-btn" 
                                        style="background: linear-gradient(45deg, #06b6d4, #0891b2); border: none; color: white; backdrop-filter: blur(5px); transition: all 0.3s ease; min-width: 40px; font-size: 0.5rem; padding: 2px 5px;">
                                    <i class="fas fa-info-circle me-1" style="font-size: 0.4rem;"></i>
                                    <span style="font-size: 0.4rem;">Details</span>
                                </button>
                                
                                <!-- Book Button -->
                                <button class="btn btn-warning btn-sm px-1 book-action-btn" 
                                        style="background: linear-gradient(45deg, #f59e0b, #d97706); border: none; color: white; backdrop-filter: blur(5px); transition: all 0.3s ease; min-width: 35px; font-size: 0.5rem; padding: 2px 5px;">
                                    <i class="fas fa-plane me-1" style="font-size: 0.4rem;"></i>
                                    <span style="font-size: 0.4rem;">Book</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Navigation Arrows -->
                <button class="carousel-nav carousel-nav-prev position-absolute" id="prevDestination" 
                        style="left: 10px; top: 50%; transform: translateY(-50%) scale(0.5); z-index: 10;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-nav carousel-nav-next position-absolute" id="nextDestination" 
                        style="right: 10px; top: 50%; transform: translateY(-50%) scale(0.5); z-index: 10;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Carousel Progress Indicator -->
            <div class="carousel-progress-wrapper mt-4 text-center">
                <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                    <span class="text-muted small">Showing</span>
                    <div class="progress" style="width: 200px; height: 6px; background: rgba(6, 182, 212, 0.2); border-radius: 10px;">
                        <div class="progress-bar" id="destinationProgress" 
                             style="background: linear-gradient(90deg, #06b6d4, #0891b2); transition: width 0.3s ease; border-radius: 10px;"></div>
                    </div>
                    <span class="text-muted small">destinations</span>
                </div>
                
                <!-- Auto-play Controls -->
                <div class="carousel-controls d-flex justify-content-center gap-3">
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" id="pauseCarousel">
                        <i class="fas fa-pause me-1"></i>Pause
                    </button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" id="playCarousel" style="display: none;">
                        <i class="fas fa-play me-1"></i>Play
                    </button>
                    <span class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="cursor: default;">
                        <i class="fas fa-clock me-1"></i>Auto-scroll: 3s
                    </span>
                </div>
            </div>
        </div>
        
       <!-- Modern Call to Action -->
        <div class="text-center mt-4">
            <div class="cta-container p-4 rounded-4" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(245, 158, 11, 0.15)); border: 2px solid rgba(6, 182, 212, 0.2); backdrop-filter: blur(10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: translateY(0); transition: all 0.3s ease;">
                <div class="row align-items-center">
                    <div class="col-lg-8 text-lg-start text-center">
                        <h4 class="fw-bold mb-2" style="color: #1e293b; font-size: 1.2rem;">Dream Beyond The Ordinary</h4>
                        <p class="text-muted mb-lg-0 mb-3" style="font-size: 0.9rem;">Let us craft a personalized East African adventure tailored specifically to your travel dreams</p>
                    </div>
                    <div class="col-lg-4 text-lg-end text-center">
                        <button id="customTripBtn" class="btn px-3 py-2 fw-bold rounded-pill d-inline-flex align-items-center position-relative overflow-hidden" 
                            style="background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; border: none; font-size: 0.9rem; transition: all 0.3s ease;">
                            <span class="position-relative z-index-2">
                                <i class="fas fa-wand-magic-sparkles me-2"></i>
                                <span>Create Custom Trip</span>
                            </span>
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, #0891b2, #06b6d4); opacity: 0; transition: all 0.3s ease;"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Trip Modal -->
        <div class="modal fade" id="customTripModal" tabindex="-1" aria-labelledby="customTripModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
                    <div class="modal-header border-0" style="background: linear-gradient(45deg, #06b6d4, #0891b2); padding: 1.25rem;">
                        <h5 class="modal-title text-white" id="customTripModalLabel" style="font-weight: 700; font-size: 1.2rem;">
                            <i class="fas fa-compass me-2"></i>
                            Design Your Dream Adventure
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <p class="text-muted mb-3" style="font-size: 0.9rem;">Share your travel vision with us, and our expert team will design a bespoke East African experience just for you.</p>
                        
                        <form id="customTripForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="tripName" name="name" placeholder="Your Name" required>
                                        <label for="tripName" style="font-size: 0.85rem;">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="tripEmail" name="email" placeholder="Your Email" required>
                                        <label for="tripEmail" style="font-size: 0.85rem;">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="tripPhone" name="phone" placeholder="Phone Number" required>
                                        <label for="tripPhone" style="font-size: 0.85rem;">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="tripTiming" name="travel_timing">
                                            <option value="">When do you plan to travel?</option>
                                            <option value="within_month">Within a month</option>
                                            <option value="1_3_months">1-3 months</option>
                                            <option value="3_6_months">3-6 months</option>
                                            <option value="6_12_months">6-12 months</option>
                                            <option value="just_exploring">Just exploring options</option>
                                        </select>
                                        <label for="tripTiming" style="font-size: 0.85rem;">Travel Timeframe</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="tripDescription" name="description" style="height: 120px;" placeholder="Describe your dream trip" required></textarea>
                                        <label for="tripDescription" style="font-size: 0.85rem;">Describe Your Dream Trip</label>
                                        <div class="form-text mt-1" style="font-size: 0.75rem;">Tell us about your desired destinations, activities, accommodation preferences, budget, and any special requests.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tripNewsletter" name="subscribe_newsletter">
                                    <label class="form-check-label text-muted" for="tripNewsletter" style="font-size: 0.8rem;">
                                        Keep me updated with travel deals and inspiration
                                    </label>
                                </div>
                                <button type="submit" id="submitCustomTrip" class="btn px-3 py-2 fw-bold rounded-pill" style="background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; border: none; font-size: 0.85rem;">
                                    <span id="submitBtnText">Send Request</span>
                                    <span id="submitBtnLoading" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Sending...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light border-0 p-2">
                        <p class="text-muted mb-0 small text-center w-100" style="font-size: 0.75rem;">Our travel experts will contact you within 24 hours to discuss your custom trip.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add JavaScript for modal and form handling -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Button hover effect
                const customTripBtn = document.getElementById('customTripBtn');
                if (customTripBtn) {
                    customTripBtn.addEventListener('mouseenter', function() {
                        this.querySelector('.position-absolute').style.opacity = '1';
                        this.style.transform = 'translateY(-3px)';
                        this.style.boxShadow = '0 10px 25px rgba(6, 182, 212, 0.4)';
                    });
                    
                    customTripBtn.addEventListener('mouseleave', function() {
                        this.querySelector('.position-absolute').style.opacity = '0';
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                    });
                    
                    // Open modal when button is clicked
                    customTripBtn.addEventListener('click', function() {
                        const modal = new bootstrap.Modal(document.getElementById('customTripModal'));
                        modal.show();
                    });
                }
                
                // Form submission
                const customTripForm = document.getElementById('customTripForm');
                if (customTripForm) {
                    customTripForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        // Show loading state
                        document.getElementById('submitBtnText').classList.add('d-none');
                        document.getElementById('submitBtnLoading').classList.remove('d-none');
                        
                        // Get form data
                        const formData = new FormData(this);
                        
                        // Send form data via fetch
                        fetch('{{ route("custom.trip.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset loading state
                            document.getElementById('submitBtnText').classList.remove('d-none');
                            document.getElementById('submitBtnLoading').classList.add('d-none');
                            
                            if (data.success) {
                                // Show success message with Toastify
                                Toastify({
                                    text: "Your custom trip request has been sent successfully!",
                                    duration: 5000,
                                    gravity: "top",
                                    position: "center",
                                    backgroundColor: "#10B981",
                                    style: {
                                        background: "linear-gradient(to right, #10B981, #059669)",
                                        borderRadius: "8px",
                                        boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                    },
                                    stopOnFocus: true
                                }).showToast();
                                
                                // Reset form
                                customTripForm.reset();
                                
                                // Close modal after a short delay
                                setTimeout(() => {
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('customTripModal'));
                                    modal.hide();
                                }, 1500);
                            } else {
                                // Show error message
                                Toastify({
                                    text: data.message || "Something went wrong. Please try again.",
                                    duration: 5000,
                                    gravity: "top",
                                    position: "center",
                                    backgroundColor: "#EF4444",
                                    style: {
                                        background: "linear-gradient(to right, #EF4444, #DC2626)",
                                        borderRadius: "8px",
                                        boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                    },
                                    stopOnFocus: true
                                }).showToast();
                            }
                        })
                        .catch(error => {
                            // Reset loading state
                            document.getElementById('submitBtnText').classList.remove('d-none');
                            document.getElementById('submitBtnLoading').classList.add('d-none');
                            
                            // Show error message
                            Toastify({
                                text: "Network error. Please try again later.",
                                duration: 5000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#EF4444",
                                style: {
                                    background: "linear-gradient(to right, #EF4444, #DC2626)",
                                    borderRadius: "8px",
                                    boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                },
                                stopOnFocus: true
                            }).showToast();
                            
                            console.error('Custom trip request error:', error);
                        });
                    });
                }
            });
        </script>
        {{-- <div class="text-center mt-5">
            <div class="cta-container p-5 rounded-4" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(245, 158, 11, 0.15)); border: 2px solid rgba(6, 182, 212, 0.2); backdrop-filter: blur(10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: translateY(0); transition: all 0.3s ease;">
                <div class="row align-items-center">
                    <div class="col-lg-8 text-lg-start text-center">
                        <h4 class="fw-bold mb-2" style="color: #1e293b; font-size: 1.6rem;">Dream Beyond The Ordinary</h4>
                        <p class="text-muted mb-lg-0 mb-4" style="font-size: 1.05rem;">Let us craft a personalized East African adventure tailored specifically to your travel dreams</p>
                    </div>
                    <div class="col-lg-4 text-lg-end text-center">
                        <button id="customTripBtn" class="btn px-4 py-3 fw-bold rounded-pill d-inline-flex align-items-center position-relative overflow-hidden" 
                            style="background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; border: none; font-size: 1.1rem; transition: all 0.3s ease;">
                            <span class="position-relative z-index-2">
                                <i class="fas fa-wand-magic-sparkles me-2"></i>
                                <span>Create Custom Trip</span>
                            </span>
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, #0891b2, #06b6d4); opacity: 0; transition: all 0.3s ease;"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Custom Trip Modal -->
        {{-- <div class="modal fade" id="customTripModal" tabindex="-1" aria-labelledby="customTripModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
                    <div class="modal-header border-0" style="background: linear-gradient(45deg, #06b6d4, #0891b2); padding: 1.5rem;">
                        <h5 class="modal-title text-white" id="customTripModalLabel" style="font-weight: 700; font-size: 1.4rem;">
                            <i class="fas fa-compass me-2"></i>
                            Design Your Dream Adventure
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4">Share your travel vision with us, and our expert team will design a bespoke East African experience just for you.</p>
                        
                        <form id="customTripForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="tripName" name="name" placeholder="Your Name" required>
                                        <label for="tripName">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="tripEmail" name="email" placeholder="Your Email" required>
                                        <label for="tripEmail">Your Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="tripPhone" name="phone" placeholder="Phone Number" required>
                                        <label for="tripPhone">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="tripTiming" name="travel_timing">
                                            <option value="">When do you plan to travel?</option>
                                            <option value="within_month">Within a month</option>
                                            <option value="1_3_months">1-3 months</option>
                                            <option value="3_6_months">3-6 months</option>
                                            <option value="6_12_months">6-12 months</option>
                                            <option value="just_exploring">Just exploring options</option>
                                        </select>
                                        <label for="tripTiming">Travel Timeframe</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="tripDescription" name="description" style="height: 150px;" placeholder="Describe your dream trip" required></textarea>
                                        <label for="tripDescription">Describe Your Dream Trip</label>
                                        <div class="form-text mt-2">Tell us about your desired destinations, activities, accommodation preferences, budget, and any special requests.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="tripNewsletter" name="subscribe_newsletter">
                                    <label class="form-check-label text-muted" for="tripNewsletter">
                                        Keep me updated with travel deals and inspiration
                                    </label>
                                </div>
                                <button type="submit" id="submitCustomTrip" class="btn px-4 py-2 fw-bold rounded-pill" style="background: linear-gradient(45deg, #06b6d4, #0891b2); color: white; border: none;">
                                    <span id="submitBtnText">Send Request</span>
                                    <span id="submitBtnLoading" class="d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Sending...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3">
                        <p class="text-muted mb-0 small text-center w-100">Our travel experts will contact you within 24 hours to discuss your custom trip.</p>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Add JavaScript for modal and form handling -->
        {{-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Button hover effect
                const customTripBtn = document.getElementById('customTripBtn');
                if (customTripBtn) {
                    customTripBtn.addEventListener('mouseenter', function() {
                        this.querySelector('.position-absolute').style.opacity = '1';
                        this.style.transform = 'translateY(-3px)';
                        this.style.boxShadow = '0 10px 25px rgba(6, 182, 212, 0.4)';
                    });
                    
                    customTripBtn.addEventListener('mouseleave', function() {
                        this.querySelector('.position-absolute').style.opacity = '0';
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                    });
                    
                    // Open modal when button is clicked
                    customTripBtn.addEventListener('click', function() {
                        const modal = new bootstrap.Modal(document.getElementById('customTripModal'));
                        modal.show();
                    });
                }
                
                // Form submission
                const customTripForm = document.getElementById('customTripForm');
                if (customTripForm) {
                    customTripForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        // Show loading state
                        document.getElementById('submitBtnText').classList.add('d-none');
                        document.getElementById('submitBtnLoading').classList.remove('d-none');
                        
                        // Get form data
                        const formData = new FormData(this);
                        
                        // Send form data via fetch
                        fetch('{{ route("custom.trip.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset loading state
                            document.getElementById('submitBtnText').classList.remove('d-none');
                            document.getElementById('submitBtnLoading').classList.add('d-none');
                            
                            if (data.success) {
                                // Show success message with Toastify
                                Toastify({
                                    text: "Your custom trip request has been sent successfully!",
                                    duration: 5000,
                                    gravity: "top",
                                    position: "center",
                                    backgroundColor: "#10B981",
                                    style: {
                                        background: "linear-gradient(to right, #10B981, #059669)",
                                        borderRadius: "8px",
                                        boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                    },
                                    stopOnFocus: true
                                }).showToast();
                                
                                // Reset form
                                customTripForm.reset();
                                
                                // Close modal after a short delay
                                setTimeout(() => {
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('customTripModal'));
                                    modal.hide();
                                }, 1500);
                            } else {
                                // Show error message
                                Toastify({
                                    text: data.message || "Something went wrong. Please try again.",
                                    duration: 5000,
                                    gravity: "top",
                                    position: "center",
                                    backgroundColor: "#EF4444",
                                    style: {
                                        background: "linear-gradient(to right, #EF4444, #DC2626)",
                                        borderRadius: "8px",
                                        boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                    },
                                    stopOnFocus: true
                                }).showToast();
                            }
                        })
                        .catch(error => {
                            // Reset loading state
                            document.getElementById('submitBtnText').classList.remove('d-none');
                            document.getElementById('submitBtnLoading').classList.add('d-none');
                            
                            // Show error message
                            Toastify({
                                text: "Network error. Please try again later.",
                                duration: 5000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#EF4444",
                                style: {
                                    background: "linear-gradient(to right, #EF4444, #DC2626)",
                                    borderRadius: "8px",
                                    boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                },
                                stopOnFocus: true
                            }).showToast();
                            
                            console.error('Custom trip request error:', error);
                        });
                    });
                }
            });
        </script> --}}
    </div>

    <!-- Hidden form for submitting flight data -->
    <form id="bookDestinationForm" method="POST" action="{{ route('book.destination') }}" style="display: none;">
    @csrf
    <input type="hidden" name="destination_data" id="destination_data">
</form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables for carousel
        const carousel = document.getElementById('destinationsCarousel');
        const prevBtn = document.getElementById('prevDestination');
        const nextBtn = document.getElementById('nextDestination');
        const progressBar = document.getElementById('destinationProgress');
        const pauseBtn = document.getElementById('pauseCarousel');
        const playBtn = document.getElementById('playCarousel');
        
        let cardWidth = 324; // card width + margin
        let numCards = {{ count($destinations) }};
        let currentPosition = 0;
        let autoScrollInterval;
        let isAutoScrolling = true;
        
        // Initialize carousel
        initCarousel();
        
        // Set up event listeners
        prevBtn.addEventListener('click', prevCard);
        nextBtn.addEventListener('click', nextCard);
        pauseBtn.addEventListener('click', pauseAutoScroll);
        playBtn.addEventListener('click', startAutoScroll);
        
        // Set up card hover effects
        setupCardHoverEffects();
        
        // Set up booking buttons
        setupBookingButtons();
        
        // Start auto-scrolling
        startAutoScroll();
        
        // Initialize carousel
        function initCarousel() {
            // Update card width based on screen size
            updateCardWidth();
            
            // Update progress bar
            updateProgressBar();
            
            // Listen for window resize
            window.addEventListener('resize', function() {
                updateCardWidth();
                scrollToCard(currentPosition);
            });
        }
        
        // Update card width based on screen size
        function updateCardWidth() {
            if (window.innerWidth < 768) {
                cardWidth = window.innerWidth - 40; // Full width - margins
            } else {
                cardWidth = 324; // Default width
            }
        }
        
        // Next card function
        function nextCard() {
            if (currentPosition < numCards - 1) {
                currentPosition++;
            } else {
                currentPosition = 0; // Loop back to start
            }
            scrollToCard(currentPosition);
        }
        
        // Previous card function
        function prevCard() {
            if (currentPosition > 0) {
                currentPosition--;
            } else {
                currentPosition = numCards - 1; // Loop to end
            }
            scrollToCard(currentPosition);
        }
        
        // Scroll to specific card
        function scrollToCard(index) {
            carousel.style.transform = `translateX(-${index * cardWidth}px)`;
            updateProgressBar();
        }
        
        // Update progress bar
        function updateProgressBar() {
            const progress = ((currentPosition + 1) / numCards) * 100;
            progressBar.style.width = `${progress}%`;
        }
        
        // Start auto-scrolling
        function startAutoScroll() {
            pauseBtn.style.display = 'block';
            playBtn.style.display = 'none';
            isAutoScrolling = true;
            
            autoScrollInterval = setInterval(function() {
                nextCard();
            }, 3000);
        }
        
        // Pause auto-scrolling
        function pauseAutoScroll() {
            clearInterval(autoScrollInterval);
            pauseBtn.style.display = 'none';
            playBtn.style.display = 'block';
            isAutoScrolling = false;
        }
        
        // Set up hover effects for cards
        function setupCardHoverEffects() {
            const cards = document.querySelectorAll('.destination-card-inner');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    // Show action buttons
                    const actionButtons = this.querySelector('.action-buttons-popup');
                    actionButtons.style.opacity = '1';
                    actionButtons.style.transform = 'translateY(0)';
                    
                    // Scale image slightly
                    const image = this.querySelector('.card-img-top');
                    image.style.transform = 'scale(1.05)';
                    
                    // Pause auto-scroll when hovering
                    if (isAutoScrolling) {
                        clearInterval(autoScrollInterval);
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    // Hide action buttons
                    const actionButtons = this.querySelector('.action-buttons-popup');
                    actionButtons.style.opacity = '0';
                    actionButtons.style.transform = 'translateY(20px)';
                    
                    // Reset image scale
                    const image = this.querySelector('.card-img-top');
                    image.style.transform = 'scale(1)';
                    
                    // Resume auto-scroll when not hovering
                    if (isAutoScrolling) {
                        startAutoScroll();
                    }
                });
            });
        }
        
        // Set up booking buttons
        function setupBookingButtons() {
            const bookNowBtns = document.querySelectorAll('.book-now-btn');
            const bookActionBtns = document.querySelectorAll('.book-action-btn');
            const detailsActionBtns = document.querySelectorAll('.details-action-btn');
            const wishlistBtns = document.querySelectorAll('.wishlist-action-btn');
            
            // Main book now buttons
            bookNowBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.destination-card-inner');
                    const destination = JSON.parse(card.dataset.destination);
                    bookDestination(destination);
                });
            });
            
            // Wishlist buttons
            wishlistBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent the card click event
                    
                    const card = this.closest('.destination-card-inner');
                    const destination = JSON.parse(card.dataset.destination);
                    const type = this.getAttribute('data-type');
                    const itemId = this.getAttribute('data-item-id');
                    
                    // Create wishlist item object
                    const wishlistItem = {
                        type: type,
                        item_id: itemId,
                        title: destination.name,
                        description: `Visit ${destination.name}, ${destination.country}`,
                        image: `/images/${destination.image}`,
                        price: destination.price.replace('KES ', ''),
                        location: destination.country,
                        item_data: destination
                    };
                    
                    // Add to wishlist
                    addToWishlist(wishlistItem, this);
                });
            });
            
            // Action button - Book
            bookActionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.destination-card-inner');
                    const destination = JSON.parse(card.dataset.destination);
                    bookDestination(destination);
                });
            });
            
            // Action button - Details
            detailsActionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.destination-card-inner');
                    const destination = JSON.parse(card.dataset.destination);
                    showDestinationDetails(destination);
                });
            });
            
            // Action button - Wishlist
            wishlistBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    this.classList.toggle('active');
                    
                    // Visual feedback
                    if (this.classList.contains('active')) {
                        this.innerHTML = '<i class="fas fa-heart" style="font-size: 0.9rem;"></i>';
                    } else {
                        this.innerHTML = '<i class="far fa-heart" style="font-size: 0.9rem;"></i>';
                    }
                });
            });
        }
        
        // Book destination function - redirects to the appropriate booking page based on destination type
        function bookDestination(destination) {
            // Check if destination has a 'type' property, otherwise determine from available properties
            let type = destination.type;
            let id = destination.id || null;
            
            if (!type) {
                if (destination.airline || destination.flightNumber || destination.origin) {
                    type = 'flight';
                } else if (destination.hotelName || destination.stars || destination.hotelType) {
                    type = 'hotel';
                } else if (destination.tourName || destination.activityType || destination.duration) {
                    type = 'tour';
                } else {
                    type = 'destination';
                }
            }
            
            // Redirect based on type
            if (type === 'flight') {
                // Redirect to flight deal booking page with the real deal id
                if (id) {
                    window.location.href = `/deals/flights/${id}?highlight_id=${id}`;
                } else {
                    // Fallback to search with parameters if no specific deal id
                    const from = destination.from || 'NBO';
                    const to = destination.to || '';
                    const departureDate = destination.departure_date || '';
                    const returnDate = destination.return_date || '';
                    window.location.href = `/flights?from=${from}&to=${to}&departure=${departureDate}&return=${returnDate}`;
                }
            } else if (type === 'hotel') {
                // Redirect to hotel deal booking page with the real deal id
                if (id) {
                    window.location.href = `/deals/hotels/${id}?highlight_id=${id}`;
                } else {
                    // Fallback to search with parameters if no specific deal id
                    const city = destination.city || destination.name || '';
                    const checkIn = destination.check_in || destination.departure_date || '';
                    const checkOut = destination.check_out || destination.return_date || '';
                    window.location.href = `/hotels?city=${city}&check_in=${checkIn}&check_out=${checkOut}`;
                }
            } else if (type === 'tour') {
                // Redirect to tour deal booking page with the real deal id
                if (id) {
                    window.location.href = `/deals/tours/${id}?highlight_id=${id}`;
                } else {
                    // Fallback to tours search
                    window.location.href = `/tours?location=${destination.name || destination.city || ''}`;
                }
            } else {
                // Fall back to the original form submission for regular destinations
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("book.destination") }}';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                // Add destination data
                const destinationInput = document.createElement('input');
                destinationInput.type = 'hidden';
                destinationInput.name = 'destination_data';
                destinationInput.value = JSON.stringify(destination);
                form.appendChild(destinationInput);
                
                // Submit the form
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Show destination details in a modal
        function showDestinationDetails(destination) {
            // Create modal if it doesn't exist
            let modal = document.getElementById('destinationDetailsModal');
            
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'destinationDetailsModal';
                modal.className = 'modal fade';
                modal.setAttribute('tabindex', '-1');
                modal.setAttribute('aria-hidden', 'true');
                
                modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="font-size: 18px; font-weight: 600;">Destination Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="destination-details-content"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; padding: 8px 16px;">Close</button>
                            <button type="button" class="btn btn-warning book-from-modal" style="font-size: 14px; padding: 8px 16px; font-weight: 500;">Book Now</button>
                        </div>
                    </div>
                </div>`;
                
                document.body.appendChild(modal);
                
                // Set up the book button in the modal
                modal.querySelector('.book-from-modal').addEventListener('click', function() {
                    const destData = this.dataset.destination;
                    if (destData) {
                        bookDestination(JSON.parse(destData));
                    }
                    
                    // Hide the modal
                    const bsModal = bootstrap.Modal.getInstance(document.getElementById('destinationDetailsModal'));
                    bsModal.hide();
                });
            }
            
            // Update modal content
            const modalContent = modal.querySelector('.destination-details-content');
            modalContent.innerHTML = `
            <div class="text-center mb-4">
                <img src="{{ asset('images') }}/${destination.image}" alt="${destination.name}" 
                     class="img-fluid rounded" style="max-height: 220px; object-fit: cover; width: 100%;"
                     onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
            </div>
            <h4 class="mb-3" style="font-size: 20px; font-weight: 600;">Nairobi to ${destination.name}</h4>
            
            <div class="flight-route p-4 bg-light rounded mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 16px;">NBO</div>
                        <div class="text-muted" style="font-size: 14px;">Nairobi</div>
                        <div class="text-primary" style="font-size: 14px;">08:00</div>
                    </div>
                    <div class="flex-grow-1 px-3">
                        <div class="text-center text-muted" style="font-size: 14px;">${destination.duration}</div>
                        <div class="flight-line position-relative">
                            <i class="fas fa-plane" style="font-size: 16px; color: #06b6d4; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);"></i>
                            <hr style="border-top: 2px dashed #ccc;">
                        </div>
                        <div class="text-center text-muted" style="font-size: 14px;">Direct</div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 16px;">${destination.to}</div>
                        <div class="text-muted" style="font-size: 14px;">${destination.name}</div>
                        <div class="text-primary" style="font-size: 14px;">09:30</div>
                    </div>
                </div>
            </div>
            
            <div class="flight-info mb-4">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="text-muted small">Depart</div>
                            <div>${destination.departure_date}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="text-muted small">Return</div>
                            <div>${destination.return_date}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="text-muted small">Airline</div>
                            <div>Kenya Airways</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <div class="text-muted small">Aircraft</div>
                            <div>Boeing 737-800</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="destination-attractions p-3 bg-light rounded mb-4">
                <h5 class="mb-2">Popular Attractions</h5>
                <ul class="list-unstyled">
                    ${getDestinationAttractions(destination.name).map(attraction => `
                        <li class="mb-1">
                            <i class="fas fa-check-circle text-success me-2"></i>${attraction}
                        </li>
                    `).join('')}
                </ul>
            </div>
            
            <div class="flight-price p-3 bg-light rounded text-center">
                <div class="text-muted small">Round trip for 1 passenger</div>
                <div class="price-display">
                    <span class="fw-bold fs-3" style="color: #059669;">${destination.price}</span>
                </div>
            </div>`;
            
            // Store destination data in book button
            modal.querySelector('.book-from-modal').dataset.destination = JSON.stringify(destination);
            
            // Show the modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
        
        // Helper function to get attractions based on destination name
        function getDestinationAttractions(destinationName) {
            const attractions = {
                'Mombasa': ['Fort Jesus Historical Tour', 'Diani Beach', 'Haller Park Wildlife Sanctuary'],
                'Zanzibar': ['Stone Town Heritage Walk', 'Spice Tour', 'Prison Island & Giant Tortoises'],
                'Kigali': ['Genocide Memorial Centre', 'Kimironko Market', 'Presidential Palace Museum'],
                'Dar es Salaam': ['National Museum', 'Slipway Shopping Center', 'Mbudya Island'],
                'Entebbe': ['Uganda Wildlife Education Centre', 'Botanical Gardens', 'Ngamba Island Chimpanzee Sanctuary'],
                'Arusha': ['Arusha National Park', 'Maasai Market', 'Cultural Heritage Centre'],
                'Addis Ababa': ['National Museum of Ethiopia', 'Holy Trinity Cathedral', 'Merkato Market'],
                'Juba': ['Juba Grand Market', 'Freedom Square', 'Rejaf Mountain']
            };
            
            return attractions[destinationName] || [
                'City Tours & Sightseeing',
                'Cultural & Historical Sites',
                'Local Food & Shopping'
            ];
        }
    });
</script>

<!-- AI Chatbot Section -->
{{-- <section class="ai-chatbot py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3" style="color: #495057;">
                <i class="fas fa-comments text-primary me-2"></i>
                Need Help? Ask Our AI Assistant
            </h2>
            <p class="text-muted">Get instant answers to your travel questions</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="chat-demo bg-white rounded-3 shadow-sm border p-4">
                    <div class="chat-messages" id="chatMessages" style="height: 300px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                        <div class="chat-message assistant mb-3">
                            <div class="d-flex align-items-start">
                                <div class="chat-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="chat-bubble bg-light rounded-3 p-3" style="max-width: 80%;">
                                    <p class="mb-0">Hello! I'm your AI travel assistant. I can help you with booking flights, cancellation policies, baggage information, and travel recommendations. What would you like to know?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-input">
                        <form id="chatForm" class="d-flex gap-2">
                            <input type="text" class="form-control" id="chatInput" placeholder="Ask me anything about travel..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Quick Reply Buttons -->
                    <div class="quick-replies mt-3" id="quickReplies">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="sendQuickReply('How do I book a flight?')">
                                Book Flight
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="sendQuickReply('What is your cancellation policy?')">
                                Cancellation Policy
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="sendQuickReply('Baggage allowance information')">
                                Baggage Info
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="sendQuickReply('Recommend me a destination')">
                                Get Recommendations
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}



<!-- Modern Travel Blog Section with Carousel -->
<section class="blog-section-modern py-3 position-relative overflow-hidden" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 50%, #cbd5e1 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.08;">
        <div class="floating-icon" style="position: absolute; top: 20%; left: 15%; animation: blogFloat 8s ease-in-out infinite;">
            <i class="fas fa-newspaper" style="color: #64748b; font-size: 0.75rem;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 30%; right: 20%; animation: blogFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-pen-fancy" style="color: #64748b; font-size: 0.6rem;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 25%; left: 25%; animation: blogFloat 12s ease-in-out infinite;">
            <i class="fas fa-blog" style="color: #64748b; font-size: 0.75rem;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 30%; right: 15%; animation: blogFloat 9s ease-in-out infinite reverse;">
            <i class="fas fa-bookmark" style="color: #64748b; font-size: 0.75rem;"></i>
        </div>
    </div>

    <div class="container position-relative">
        <!-- Enhanced Header -->
        <div class="text-center mb-2 position-relative">
            <div class="mb-2">
                <span class="badge px-2 py-1 rounded-pill" style="background: linear-gradient(45deg, #3b82f6, #1d4ed8); color: white; font-weight: 600; font-size: 0.7rem; letter-spacing: 0.5px; backdrop-filter: blur(10px);">
                    📝 EXPERT INSIGHTS
                </span>
            </div>
            
            <h2 class="fw-bold mb-1" style="color: #1e293b; font-size: 1.8rem; line-height: 1.2; position: relative;">
                <span class="position-relative">
                    <i class="fas fa-lightbulb me-2" style="color: #fbbf24; font-size: 1.5rem; vertical-align: middle; filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.4)); animation: lightBulbGlow 3s ease-in-out infinite alternate;"></i>
                    Travel Tips & Insights
                    <!-- Decorative underline -->
                    <div class="position-absolute w-100" style="bottom: -5px; left: 0; height: 2px; background: linear-gradient(90deg, transparent, #3b82f6, transparent); border-radius: 2px;"></div>
                </span>
            </h2>
            
            <p class="mb-2" style="color: #475569; font-size: 14px; font-weight: 300; max-width: 500px; margin: 0 auto; line-height: 1.5;">
                <span class="d-inline-block" style="border-left: 2px solid #fbbf24; padding-left: 10px;">
                    Stay informed with expert travel advice and insider knowledge
                    <br>
                    <small style="color: rgba(71, 85, 105, 0.7); font-size: 0.8rem;">
                        Updated weekly • Expert curated • Travel essentials
                    </small>
                </span>
            </p>
            
          
        </div>

        <!-- Modern Blog Carousel -->
        <div class="blog-carousel-wrapper position-relative">
            <!-- Carousel Container -->
            <div class="blog-carousel-container overflow-hidden" style="border-radius: 20px; position: relative;">
                <div class="blog-carousel d-flex" id="blogCarousel" style="transition: transform 0.5s ease-in-out;">
                    @php
                        $blogPosts = [
                            [
                                'title' => 'Best Time to Book Flights',
                                'excerpt' => 'Discover optimal booking windows and pricing strategies for the best flight deals.',
                                'image' => 'airplane.jpg',
                                'author' => 'Sarah Mitchell',
                                'date' => '3 days ago',
                                'category' => 'Flight Tips',
                                'readTime' => '5 min read',
                                'views' => '2.3K'
                            ],
                            [
                                'title' => 'Complete Travel Insurance Guide',
                                'excerpt' => 'Everything you need about protecting your trip with comprehensive coverage options.',
                                'image' => 'vaccation.jpg',
                                'author' => 'Michael Torres',
                                'date' => '1 week ago',
                                'category' => 'Travel Safety',
                                'readTime' => '8 min read',
                                'views' => '1.8K'
                            ],
                            [
                                'title' => 'Smart Packing Essentials',
                                'excerpt' => 'Master efficient packing with our curated list of must-have travel items.',
                                'image' => 'holiday.jpg',
                                'author' => 'Emma Rodriguez',
                                'date' => '5 days ago',
                                'category' => 'Packing Tips',
                                'readTime' => '6 min read',
                                'views' => '3.1K'
                            ],
                            [
                                'title' => 'Budget Travel Secrets',
                                'excerpt' => 'Learn insider tips for traveling the world without breaking the bank.',
                                'image' => 'destination.jpg',
                                'author' => 'David Chen',
                                'date' => '2 days ago',
                                'category' => 'Budget Tips',
                                'readTime' => '7 min read',
                                'views' => '2.8K'
                            ],
                            [
                                'title' => 'Digital Nomad Guide',
                                'excerpt' => 'Your complete guide to working remotely while traveling the globe.',
                                'image' => 'accomodation.jpg',
                                'author' => 'Lisa Parker',
                                'date' => '4 days ago',
                                'category' => 'Work Travel',
                                'readTime' => '10 min read',
                                'views' => '4.2K'
                            ]
                        ];
                        
                        // Duplicate blog posts for infinite scroll
                        $allBlogPosts = array_merge($blogPosts, $blogPosts);
                    @endphp
                    
                    @foreach($allBlogPosts as $index => $post)
                    <div class="blog-card-modern flex-shrink-0 position-relative me-4" style="width: 320px; cursor: pointer;" 
                         data-aos="fade-up" data-aos-delay="{{ ($index % 5) * 100 }}">
                        
                        <article class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 blog-card-inner">
                            <!-- Featured Image with Overlay - Reduced Height -->
                            <div class="blog-image-container position-relative" style="height: 150px; overflow: hidden;">
                                <img src="{{ asset('images/' . $post['image']) }}" 
                                     class="card-img-top w-100 h-100" 
                                     style="object-fit: cover; transition: transform 0.5s ease;" 
                                     alt="{{ $post['title'] }}">
                                
                                <!-- Gradient Overlay -->
                                <div class="position-absolute w-100 h-100" style="top: 0; left: 0; background: linear-gradient(45deg, rgba(0,0,0,0.4) 0%, transparent 50%, rgba(30,41,59,0.6) 100%);"></div>
                                
                                <!-- Category Badge -->
                                <div class="position-absolute top-0 start-0 p-1">
                                    <span class="badge rounded-pill px-2 py-1" style="background: linear-gradient(45deg, #3b82f6, #1d4ed8); color: white; font-size: 0.6rem; backdrop-filter: blur(10px);">
                                        {{ $post['category'] }}
                                    </span>
                                </div>
                                
                                <!-- Views Badge -->
                                <div class="position-absolute top-0 end-0 p-1">
                                    <div class="d-flex align-items-center bg-dark rounded-pill px-2 py-1" style="background: rgba(0, 0, 0, 0.7) !important; backdrop-filter: blur(10px);">
                                        <i class="fas fa-eye text-white me-1" style="font-size: 0.6rem;"></i>
                                        <span class="text-white fw-medium" style="font-size: 0.6rem;">{{ $post['views'] }}</span>
                                    </div>
                                </div>
                                
                                <!-- Read Time -->
                                <div class="position-absolute bottom-0 end-0 p-1">
                                    <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1" style="background: rgba(255, 255, 255, 0.9) !important; backdrop-filter: blur(10px);">
                                        <i class="fas fa-clock me-1" style="color: #3b82f6; font-size: 0.6rem;"></i>
                                        <span class="fw-medium" style="color: #1e293b; font-size: 0.6rem;">{{ $post['readTime'] }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Content - Reduced Padding -->
                            <div class="card-body p-2 bg-white d-flex flex-column">
                                <!-- Article Title - Compact -->
                                <h5 class="card-title fw-bold mb-2" style="color: #1e293b; font-size: 0.9rem; line-height: 1.3;">
                                    {{ $post['title'] }}
                                </h5>
                                
                                <!-- Article Excerpt - Compact -->
                                <p class="card-text text-muted mb-2 flex-grow-1" style="line-height: 1.5; font-size: 0.75rem;">
                                    {{ $post['excerpt'] }}
                                </p>
                                
                                <!-- Author & Date Info - Compact -->
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 30px; height: 30px; background: linear-gradient(45deg, #3b82f6, #1d4ed8); border-radius: 50%; color: white; font-weight: bold; font-size: 0.7rem;">
                                            {{ substr($post['author'], 0, 2) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold" style="color: #374151; font-size: 0.7rem;">{{ $post['author'] }}</h6>
                                            <small class="text-muted d-flex align-items-center" style="font-size: 0.6rem;">
                                                <i class="fas fa-calendar-alt me-1" style="font-size: 0.6rem;"></i>
                                                {{ $post['date'] }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Social Share Button - Smaller -->
                                    <button class="btn btn-outline-primary btn-sm rounded-circle share-btn" style="width: 24px; height: 24px; border-color: #e5e7eb; color: #6b7280; padding: 0;">
                                        <i class="fas fa-share-alt" style="font-size: 0.6rem;"></i>
                                    </button>
                                </div>
                                
                                <!-- Read More Button - Compact -->
                                <a href="#" class="btn w-100 py-1 fw-semibold rounded-3 read-more-btn" 
                                   style="background: linear-gradient(45deg, #f8fafc, #e2e8f0); border: 1px solid #e5e7eb; color: #374151; transition: all 0.3s ease; font-size: 0.7rem;">
                                    <i class="fas fa-book-open me-1" style="font-size: 0.6rem;"></i>
                                    <span>Read Article</span>
                                    <i class="fas fa-arrow-right ms-1" style="font-size: 0.6rem;"></i>
                                </a>
                            </div>
                            
                            <!-- Hover Effect Overlay -->
                            <div class="card-hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center" 
                                 style="top: 0; left: 0; background: linear-gradient(45deg, rgba(59, 130, 246, 0.95), rgba(29, 78, 216, 0.95)); opacity: 0; transition: all 0.4s ease;">
                                <div class="text-center text-white">
                                    <i class="fas fa-newspaper mb-2" style="font-size: 1.5rem; animation: bounceIn 0.6s ease;"></i>
                                    <h5 class="fw-bold mb-1" style="font-size: 0.9rem;">Read Article</h5>
                                    <p class="mb-2 small" style="font-size: 0.7rem;">Get expert insights and travel tips</p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-light btn-sm rounded-pill px-2" style="font-size: 0.7rem;">
                                            <i class="fas fa-eye me-1"></i>Read Now
                                        </button>
                                        <button class="btn btn-outline-light btn-sm rounded-pill px-2" style="font-size: 0.7rem;">
                                            <i class="fas fa-bookmark me-1"></i>Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>
                
                <!-- Navigation Arrows -->
                <button class="blog-carousel-nav blog-carousel-nav-prev position-absolute" id="prevBlog" 
                        style="left: 20px; top: 50%; transform: translateY(-50%); z-index: 10;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="blog-carousel-nav blog-carousel-nav-next position-absolute" id="nextBlog" 
                        style="right: 20px; top: 50%; transform: translateY(-50%); z-index: 10;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Carousel Progress Indicator -->
            <div class="blog-carousel-progress-wrapper mt-4 text-center">
                <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                    <span class="text-muted small">Latest</span>
                    <div class="progress" style="width: 200px; height: 6px; background: rgba(59, 130, 246, 0.2); border-radius: 10px;">
                        <div class="progress-bar" id="blogProgress" 
                             style="background: linear-gradient(90deg, #3b82f6, #1d4ed8); transition: width 0.3s ease; border-radius: 10px;"></div>
                    </div>
                    <span class="text-muted small">insights</span>
                </div>
                
                <!-- Auto-play Controls -->
                <div class="blog-carousel-controls d-flex justify-content-center gap-3">
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" id="pauseBlogCarousel">
                        <i class="fas fa-pause me-1"></i>Pause
                    </button>
                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" id="playBlogCarousel" style="display: none;">
                        <i class="fas fa-play me-1"></i>Play
                    </button>
                    <span class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="cursor: default;">
                        <i class="fas fa-clock me-1"></i>Auto-scroll: 4s
                    </span>
                </div>
            </div>
        </div>
        
      
    </div>
</section>



<!-- Newsletter Subscription -->
<section class="newsletter-cta position-relative">
    <div class="position-relative h-100 w-100 d-flex align-items-center justify-content-center">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center" style="max-width: 700px; margin: 0 auto;">
                        <p class="text-white mb-2 opacity-90 text-center" style="font-weight: 400; line-height: 1.4; font-size: 0.85rem;">
                            Never miss out on exclusive offers
                        </p>
                        <h2 class="fw-bold text-white mb-3 text-center" style="font-weight: 700; font-size: 1.5rem; line-height: 1.3; color: rgb(255, 255, 255);">
                            Stay updated with best deals
                        </h2>
                        <div class="d-flex justify-content-center align-items-center w-100">
                            <form id="homeNewsletterForm" class="d-flex flex-column align-items-center gap-2 w-100">
                                @csrf
                                <div class="d-flex flex-column flex-md-row align-items-center gap-2 w-100">
                                <input type="text" name="name" id="homeNewsletterName" class="form-control px-3 py-2 rounded-3 border-0" 
                                    placeholder="Your name" style="background: rgba(255,255,255,0.9); flex: 1; color: #000000; font-size: 0.8rem;" required>
                                <input type="email" name="email" id="homeNewsletterEmail" class="form-control px-3 py-2 rounded-3 border-0" 
                                    placeholder="Your email address" style="background: rgba(255,255,255,0.9); flex: 1; color: #000000; font-size: 0.8rem;" required>
                                <button type="submit" class="btn px-3 py-2 fw-bold rounded-3 d-inline-flex align-items-center" style="background-color: #ffc108; color: #212529; border: none; font-size: 0.8rem;">
                                    <i class="fas fa-paper-plane me-2" style="font-size: 0.8rem;"></i>
                                    <span>Subscribe</span>
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Customer Reviews -->
<section class="reviews-section py-4 bg-light position-relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.05;">
        <div class="floating-icon" style="position: absolute; top: 15%; left: 10%; animation: reviewFloat 8s ease-in-out infinite;">
            <i class="fas fa-quote-left" style="font-size: 14px; color: #6c757d;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 20%; right: 15%; animation: reviewFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-heart" style="font-size: 14px; color: #6c757d;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 25%; left: 20%; animation: reviewFloat 12s ease-in-out infinite;">
            <i class="fas fa-thumbs-up" style="font-size: 14px; color: #6c757d;"></i>
        </div>
    </div>

    <div class="container position-relative">
        <div class="text-center mb-2">
            <div class="mb-1">
                <span class="badge px-2 py-1 rounded-pill" style="background: linear-gradient(45deg, #ffc107, #ffeb3b); color: #212529; font-weight: 600; font-size: 0.7rem;">
                    ⭐ CUSTOMER FEEDBACK
                </span>
            </div>
            <h2 class="fw-bold mb-2" style="color: #495057; font-size: 1.5rem;">
                <i class="fas fa-star text-warning me-2" style="font-size: 1.2rem; vertical-align: middle;"></i>
                What Our Customers Say
            </h2>
            <p style="color: #6c757d; font-size: 0.85rem; line-height: 1.4;">Real experiences from real travelers around the world</p>
            
            <!-- Trust Indicators -->
            <div class="d-flex justify-content-center align-items-center gap-3 mt-2">
                <div class="d-flex align-items-center">
                    <div class="rating-stars me-1">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-warning" style="font-size: 0.8rem;"></i>
                        @endfor
                    </div>
                    <span class="fw-bold text-dark" style="font-size: 0.8rem;">4.9/5</span>
                    <span class="text-muted ms-1" style="font-size: 0.7rem;">(2,547 reviews)</span>
                </div>
                <div style="width: 1px; height: 16px; background: rgba(108, 117, 125, 0.3);"></div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt text-success me-1" style="font-size: 0.8rem;"></i>
                    <span class="text-muted" style="font-size: 0.7rem;">Verified Reviews</span>
                </div>
            </div>
        </div>

        <!-- Modern Review Carousel -->
        <div class="reviews-carousel-container position-relative">
            <div class="carousel slide" id="modernReviewsCarousel" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @php
                        $reviews = [
                            [
                                'name' => 'Emily Johnson', 
                                'initials' => 'EJ', 
                                'location' => 'New York, USA',
                                'review' => 'Absolutely fantastic service! The booking process was seamless and I found the perfect flight deal for my European vacation. Customer support was incredibly helpful when I needed to make changes.',
                                'rating' => 5,
                                'date' => '2 days ago',
                                'trip' => 'Flight to Paris'
                            ],
                            [
                                'name' => 'David Chen', 
                                'initials' => 'DC', 
                                'location' => 'Singapore',
                                'review' => 'Best travel booking platform I\'ve ever used! Found amazing hotel deals and the price comparison feature saved me hundreds of dollars. Highly recommend to all travelers.',
                                'rating' => 5,
                                'date' => '1 week ago',
                                'trip' => 'Hotel in Tokyo'
                            ],
                            [
                                'name' => 'Sarah Williams', 
                                'initials' => 'SW', 
                                'location' => 'London, UK',
                                'review' => 'Professional, reliable, and user-friendly. Booked my entire family vacation through this platform. The real-time updates and instant confirmation gave me peace of mind.',
                                'rating' => 5,
                                'date' => '3 days ago',
                                'trip' => 'Family Package'
                            ],
                            [
                                'name' => 'Ahmed Hassan', 
                                'initials' => 'AH', 
                                'location' => 'Dubai, UAE',
                                'review' => 'Exceptional customer service and competitive prices. The mobile app is intuitive and made booking on-the-go incredibly easy. Will definitely use again for future travels.',
                                'rating' => 5,
                                'date' => '5 days ago',
                                'trip' => 'Business Trip'
                            ],
                            [
                                'name' => 'Maria Rodriguez', 
                                'initials' => 'MR', 
                                'location' => 'Madrid, Spain',
                                'review' => 'Outstanding experience from start to finish! The platform offered the best prices and the booking confirmation was instant. Perfect for planning my honeymoon trip.',
                                'rating' => 5,
                                'date' => '1 week ago',
                                'trip' => 'Honeymoon Package'
                            ]
                        ];
                        $reviewChunks = array_chunk($reviews, 3);
                    @endphp
                    
                    @foreach($reviewChunks as $index => $reviewGroup)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach($reviewGroup as $review)
                            <div class="col-lg-4">
                                <div class="review-card h-100 position-relative">
                                    <!-- Quote Icon -->
                                    <div class="quote-icon position-absolute" style="top: -10px; left: 20px; z-index: 10;">
                                        <i class="fas fa-quote-left" style="font-size: 1.5rem; color: #ffc107;"></i>
                                    </div>
                                    
                                    <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden review-card-inner">
                                        <div class="card-body p-4 d-flex flex-column">
                                            <!-- Rating and Date -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="rating-stars">
                                                    @for($i = 0; $i < $review['rating']; $i++)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-muted">{{ $review['date'] }}</small>
                                            </div>
                                            
                                            <!-- Review Text -->
                                            <p class="review-text mb-4 flex-grow-1" style="color: #495057; line-height: 1.6; font-size: 0.95rem;">
                                                "{{ $review['review'] }}"
                                            </p>
                                            
                                            <!-- Trip Type Badge -->
                                            <div class="mb-3">
                                                <span class="badge rounded-pill px-3 py-1" style="background: linear-gradient(45deg, #e3f2fd, #bbdefb); color: #1976d2; font-size: 0.8rem;">
                                                    <i class="fas fa-plane me-1"></i>{{ $review['trip'] }}
                                                </span>
                                            </div>
                                            
                                            <!-- Customer Info -->
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 55px; height: 55px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; color: white; font-weight: bold; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                                    {{ $review['initials'] }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 fw-bold" style="color: #2c3e50;">{{ $review['name'] }}</h6>
                                                    <small class="text-muted d-flex align-items-center">
                                                        <i class="fas fa-map-marker-alt me-1" style="font-size: 0.7rem;"></i>
                                                        {{ $review['location'] }}
                                                    </small>
                                                    <div class="mt-1">
                                                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                                            <i class="fas fa-check-circle me-1"></i>Verified
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                      <!-- Hover Effect Overlay -->
                                <div class="card-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0; background: linear-gradient(45deg, rgba(107, 49, 37, 0.9), rgba(107, 49, 37, 0.7)); opacity: 0; transition: all 0.3s ease;">
                                    <div class="text-center text-white">
                                        <i class="fas fa-heart fs-1 mb-2"></i>
                                        <p class="mb-0 fw-bold">Thank You!</p>
                                        <small>For trusting us with your journey</small>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Custom Carousel Controls -->
                <button class="carousel-control-prev modern-carousel-control" type="button" data-bs-target="#modernReviewsCarousel" data-bs-slide="prev">
                    <div class="carousel-control-icon">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                </button>
                <button class="carousel-control-next modern-carousel-control" type="button" data-bs-target="#modernReviewsCarousel" data-bs-slide="next">
                    <div class="carousel-control-icon">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </button>
                
                <!-- Custom Indicators -->
                <div class="carousel-indicators modern-indicators">
                    @for($i = 0; $i < count($reviewChunks); $i++)
                        <button type="button" data-bs-target="#modernReviewsCarousel" data-bs-slide-to="{{ $i }}" 
                                class="{{ $i === 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}">
                        </button>
                    @endfor
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="carousel-progress mt-4">
                <div class="progress" style="height: 4px; background: rgba(255, 193, 7, 0.2);">
                    <div class="progress-bar" id="carouselProgress" style="background: linear-gradient(90deg, #ffc107, #ffeb3b); transition: width 4s linear;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Price Prediction Section -->
{{-- <section class="ai-price-prediction py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="mb-3">
                <span class="badge px-4 py-2 rounded-pill" style="background: rgba(255, 255, 255, 0.2); color: white; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">
                    📈 SMART PRICING
                </span>
            </div>
            
            <h2 class="fw-bold text-white mb-3" style="font-size: 2.5rem; line-height: 1.2;">
                <i class="fas fa-chart-line me-3" style="font-size: 2rem; vertical-align: middle;"></i>
                AI Price Prediction
            </h2>
            
            <p class="text-white opacity-90 mb-4" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Get intelligent price forecasts and know the best time to book your flights
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="bg-white rounded-3 shadow-lg p-4">
                    <!-- Price Prediction Form -->
                    <form id="pricePredictionForm" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-medium">From</label>
                                <input type="text" class="form-control" name="from" placeholder="e.g., NYC, LAX" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">To</label>
                                <input type="text" class="form-control" name="to" placeholder="e.g., LHR, CDG" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Departure Date</label>
                                <input type="date" class="form-control" name="date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Current Price</label>
                                <input type="number" class="form-control" name="current_price" placeholder="$500" required>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="fas fa-chart-line me-2"></i>
                                Predict Price Trends
                            </button>
                        </div>
                    </form>
                    
                    <!-- Price Prediction Results -->
                    <div id="pricePredictionResults" class="mt-4" style="display: none;">
                        <!-- Results will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- Why Choose Us Section  -->
<section class="trust-badges-modern py-3 position-relative overflow-hidden" style="background: #6b3125;">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none; opacity: 0.1;">
        <div class="floating-icon" style="position: absolute; top: 15%; left: 10%; animation: trustFloat 8s ease-in-out infinite;">
            <i class="fas fa-award" style="font-size: 0.8rem; color: white;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; top: 20%; right: 15%; animation: trustFloat 10s ease-in-out infinite reverse;">
            <i class="fas fa-medal" style="font-size: 0.7rem; color: white;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 20%; left: 20%; animation: trustFloat 12s ease-in-out infinite;">
            <i class="fas fa-trophy" style="font-size: 0.9rem; color: white;"></i>
        </div>
        <div class="floating-icon" style="position: absolute; bottom: 25%; right: 20%; animation: trustFloat 9s ease-in-out infinite reverse;">
            <i class="fas fa-gem" style="font-size: 0.8rem; color: white;"></i>
        </div>
    </div>

    <div class="container position-relative">
        <!-- Enhanced Header -->
        <div class="text-center mb-2">
            
            <h2 class="fw-bold mb-2 text-white" style="font-size: 1.2rem; line-height: 1.2; text-shadow: 0 1px 5px rgba(0,0,0,0.3);">
                <span class="position-relative">
                    <i class="fas fa-shield-check me-1" style="color: #ffc107; font-size: 1rem; vertical-align: middle; filter: drop-shadow(0 0 3px rgba(255, 193, 7, 0.5));"></i>
                    Why Choose Us
                    <!-- Decorative underline -->
                    <div class="position-absolute w-100" style="bottom: -4px; left: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent); border-radius: 1px;"></div>
                </span>
            </h2>
            
            <p class="mb-3 text-white" style="font-size: 0.85rem; font-weight: 300; max-width: 700px; margin: 0 auto; line-height: 1.4; opacity: 0.9;">
                Experience excellence in every journey with our world-class travel services
                <br>
                <small style="opacity: 0.8; font-size: 0.75rem;">
                    Trusted by over 1 million travelers worldwide • Award-winning service since 2020
                </small>
            </p>
            
            <!-- Trust Statistics -->
            <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap mt-4">
                <div class="d-flex align-items-center text-white">
                    <div class="me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="text-start">
                        <div style="font-weight: 700; font-size: 1.1rem;">1M+</div>
                        <small style="opacity: 0.8;">Happy Customers</small>
                    </div>
                </div>
                <div style="width: 2px; height: 40px; background: rgba(255, 255, 255, 0.3);"></div>
                <div class="d-flex align-items-center text-white">
                    <div class="me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="text-start">
                        <div style="font-weight: 700; font-size: 1.1rem;">4.9/5</div>
                        <small style="opacity: 0.8;">Rating</small>
                    </div>
                </div>
                <div style="width: 2px; height: 40px; background: rgba(255, 255, 255, 0.3);"></div>
                <div class="d-flex align-items-center text-white">
                    <div class="me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="text-start">
                        <div style="font-weight: 700; font-size: 1.1rem;">50+</div>
                        <small style="opacity: 0.8;">Awards Won</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Feature Cards - 4 in a Row -->
        <div class="row g-3">
            <!-- Lightning Fast Booking -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-trust-card h-100 position-relative overflow-hidden">
                    <!-- Card Background -->
                    <div class="card-bg position-absolute w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0; transition: all 0.5s ease;"></div>
                    
                    <!-- Card Content -->
                    <div class="card-content position-relative p-3 text-center h-100 d-flex flex-column">
                        <!-- Animated Icon Container - Smaller -->
                        <div class="icon-container mx-auto mb-3 position-relative">
                            <div class="icon-background position-absolute w-100 h-100 rounded-circle" style="background: linear-gradient(45deg, #ff6b6b, #ee5a24); animation: iconPulse 3s ease-in-out infinite;"></div>
                            <div class="icon-wrapper d-flex align-items-center justify-content-center position-relative" style="width: 60px; height: 60px; background: linear-gradient(45deg, #ff6b6b, #ee5a24); border-radius: 50%; box-shadow: 0 8px 20px rgba(255, 107, 107, 0.4);">
                                <i class="fas fa-rocket text-white" style="font-size: 1.5rem; animation: iconBounce 2s ease-in-out infinite;"></i>
                            </div>
                            <!-- Icon Glow Effect -->
                            <div class="icon-glow position-absolute w-100 h-100 rounded-circle" style="background: radial-gradient(circle, rgba(255, 107, 107, 0.4) 0%, transparent 70%); animation: glowPulse 2s ease-in-out infinite alternate;"></div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #2c3e50; font-size: 1.1rem;">Lightning Fast Booking</h5>
                            <p class="text-muted mb-3" style="line-height: 1.5; font-size: 0.85rem;">Complete your booking in under 60 seconds with instant confirmation.</p>
                            
                            <!-- Feature Benefits - Compact -->
                            <div class="features-list text-start">
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Instant confirmation</small>
                                </div>
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">One-click booking</small>
                                </div>
                                <div class="feature-item d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Mobile optimized</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistics Badge -->
                        <div class="stats-badge position-absolute" style="top: 10px; right: 10px;">
                            <span class="badge rounded-pill" style="background: linear-gradient(45deg, #ff6b6b, #ee5a24); color: white; font-size: 0.6rem; padding: 3px 8px;">
                                <i class="fas fa-clock me-1"></i>60s
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI-Powered Search -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-trust-card h-100 position-relative overflow-hidden">
                    <div class="card-bg position-absolute w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0; transition: all 0.5s ease;"></div>
                    
                    <div class="card-content position-relative p-3 text-center h-100 d-flex flex-column">
                        <div class="icon-container mx-auto mb-3 position-relative">
                            <div class="icon-background position-absolute w-100 h-100 rounded-circle" style="background: linear-gradient(45deg, #3498db, #2980b9); animation: iconPulse 3s ease-in-out infinite 1s;"></div>
                            <div class="icon-wrapper d-flex align-items-center justify-content-center position-relative" style="width: 60px; height: 60px; background: linear-gradient(45deg, #3498db, #2980b9); border-radius: 50%; box-shadow: 0 8px 20px rgba(52, 152, 219, 0.4);">
                                <i class="fas fa-brain text-white" style="font-size: 1.5rem; animation: brainPulse 3s ease-in-out infinite;"></i>
                            </div>
                            <div class="icon-glow position-absolute w-100 h-100 rounded-circle" style="background: radial-gradient(circle, rgba(52, 152, 219, 0.4) 0%, transparent 70%); animation: glowPulse 2s ease-in-out infinite alternate 1s;"></div>
                        </div>
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #2c3e50; font-size: 1.1rem;">AI-Powered Search</h5>
                            <p class="text-muted mb-3" style="line-height: 1.5; font-size: 0.85rem;">Smart algorithm finds the best deals and routes in real-time.</p>
                            
                            <div class="features-list text-start">
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Smart comparison</small>
                                </div>
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Personalized results</small>
                                </div>
                                <div class="feature-item d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Real-time updates</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-badge position-absolute" style="top: 10px; right: 10px;">
                            <span class="badge rounded-pill" style="background: linear-gradient(45deg, #3498db, #2980b9); color: white; font-size: 0.6rem; padding: 3px 8px;">
                                <i class="fas fa-robot me-1"></i>AI
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 24/7 Premium Support -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-trust-card h-100 position-relative overflow-hidden">
                    <div class="card-bg position-absolute w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0; transition: all 0.5s ease;"></div>
                    
                    <div class="card-content position-relative p-3 text-center h-100 d-flex flex-column">
                        <div class="icon-container mx-auto mb-3 position-relative">
                            <div class="icon-background position-absolute w-100 h-100 rounded-circle" style="background: linear-gradient(45deg, #9b59b6, #8e44ad); animation: iconPulse 3s ease-in-out infinite 1.5s;"></div>
                            <div class="icon-wrapper d-flex align-items-center justify-content-center position-relative" style="width: 60px; height: 60px; background: linear-gradient(45deg, #9b59b6, #8e44ad); border-radius: 50%; box-shadow: 0 8px 20px rgba(155, 89, 182, 0.4);">
                                <i class="fas fa-headset text-white" style="font-size: 1.5rem; animation: headsetBob 2s ease-in-out infinite;"></i>
                            </div>
                            <div class="icon-glow position-absolute w-100 h-100 rounded-circle" style="background: radial-gradient(circle, rgba(155, 89, 182, 0.4) 0%, transparent 70%); animation: glowPulse 2s ease-in-out infinite alternate 1.5s;"></div>
                        </div>
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #2c3e50; font-size: 1.1rem;">24/7 Premium Support</h5>
                            <p class="text-muted mb-3" style="line-height: 1.5; font-size: 0.85rem;">Expert travel assistance available around the clock.</p>
                            
                            <div class="features-list text-start">
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Multilingual support</small>
                                </div>
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Emergency assistance</small>
                                </div>
                                <div class="feature-item d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Live chat & phone</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-badge position-absolute" style="top: 10px; right: 10px;">
                            <span class="badge rounded-pill" style="background: linear-gradient(45deg, #9b59b6, #8e44ad); color: white; font-size: 0.6rem; padding: 3px 8px;">
                                <i class="fas fa-clock me-1"></i>24/7
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Price Guarantee -->
            <div class="col-lg-3 col-md-6">
                <div class="modern-trust-card h-100 position-relative overflow-hidden">
                    <div class="card-bg position-absolute w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0; transition: all 0.5s ease;"></div>
                    
                    <div class="card-content position-relative p-3 text-center h-100 d-flex flex-column">
                        <div class="icon-container mx-auto mb-3 position-relative">
                            <div class="icon-background position-absolute w-100 h-100 rounded-circle" style="background: linear-gradient(45deg, #f39c12, #e67e22); animation: iconPulse 3s ease-in-out infinite 2s;"></div>
                            <div class="icon-wrapper d-flex align-items-center justify-content-center position-relative" style="width: 60px; height: 60px; background: linear-gradient(45deg, #f39c12, #e67e22); border-radius: 50%; box-shadow: 0 8px 20px rgba(243, 156, 18, 0.4);">
                                <i class="fas fa-hand-holding-dollar text-white" style="font-size: 1.5rem; animation: moneyFloat 3s ease-in-out infinite;"></i>
                            </div>
                            <div class="icon-glow position-absolute w-100 h-100 rounded-circle" style="background: radial-gradient(circle, rgba(243, 156, 18, 0.4) 0%, transparent 70%); animation: glowPulse 2s ease-in-out infinite alternate 2s;"></div>
                        </div>
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2" style="color: #2c3e50; font-size: 1.1rem;">Best Price Guarantee</h5>
                            <p class="text-muted mb-3" style="line-height: 1.5; font-size: 0.85rem;">Lowest prices guaranteed or we'll refund the difference.</p>
                            
                            <div class="features-list text-start">
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Price match guarantee</small>
                                </div>
                                <div class="feature-item d-flex align-items-center mb-1">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">Instant refund</small>
                                </div>
                                <div class="feature-item d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                    <small class="text-muted" style="font-size: 0.75rem;">No hidden fees</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-badge position-absolute" style="top: 10px; right: 10px;">
                            <span class="badge rounded-pill" style="background: linear-gradient(45deg, #f39c12, #e67e22); color: white; font-size: 0.6rem; padding: 3px 8px;">
                                <i class="fas fa-percentage me-1"></i>Best
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Floating Action Buttons - Visible and Properly Sized -->
<div class="floating-buttons-container">
    <!-- WhatsApp Button - Bottom Left -->
    <div class="whatsapp-button position-fixed" 
         style="bottom: 15px; left: 15px; z-index: 1000; opacity: 1; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); transform: scale(1);">
        <a href="https://wa.me/1234567890" target="_blank" class="whatsapp-btn d-flex align-items-center justify-content-center text-decoration-none" style="background: #25d366; color: white; padding: 8px 16px; border-radius: 50px; box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
            <div class="btn-content d-flex align-items-center">
                <i class="fab fa-whatsapp" style="font-size: 14px;"></i>
                <span class="btn-text ms-2" style="font-size: 0.8rem; font-weight: 500;">WhatsApp</span>
            </div>
            <!-- Pulse Animation Ring -->
            <div class="pulse-ring" style="position: absolute; width: 100%; height: 100%; border-radius: 50px; border: 3px solid #25d36680; animation: pulse 1.5s infinite;"></div>
        </a>
    </div>

    <!-- Chat Button - Bottom Right -->
    <div class="chat-button position-fixed" 
         style="bottom: 15px; right: 65px; z-index: 1000; opacity: 1; transform: scale(1); transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);">
        <button class="chat-btn d-flex align-items-center justify-content-center" onclick="openLiveChat()" style="background: #3b82f6; color: white; padding: 8px 16px; border-radius: 50px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
            <div class="btn-content d-flex align-items-center">
                <i class="fas fa-comments" style="font-size: 14px;"></i>
                <span class="btn-text ms-2" style="font-size: 0.8rem; font-weight: 500;">Live Chat</span>
            </div>
            <!-- Online Status Indicator -->
            <div class="online-indicator" style="position: absolute; top: -2px; right: -2px; width: 10px; height: 10px; background: #10b981; border-radius: 50%; border: 2px solid white;"></div>
            <!-- Pulse Animation Ring -->
            <div class="pulse-ring"></div>
        </button>
    </div>

    <!-- Scroll to Top Button with Flight Icon - Bottom Right -->
    <div class="scroll-to-top position-fixed" 
         style="bottom: 15px; right: 15px; z-index: 1000; opacity: 1; transform: scale(1); transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);">
        <button class="flight-scroll-btn d-flex align-items-center justify-content-center" onclick="scrollToHero()" style="background: #ffc107; color: #212529; padding: 8px 16px; border-radius: 50px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
            <div class="btn-content">
                <i class="fas fa-paper-plane flight-icon" style="font-size: 14px;"></i>
                <span class="btn-text ms-1" style="font-size: 0.8rem; font-weight: 500;">Take Off</span>
            </div>
            <!-- Flight Trail Effect -->
            <div class="flight-trail" style="position: absolute; width: 20px; height: 6px; background: linear-gradient(90deg, #ffc107, transparent); border-radius: 3px; opacity: 0.6; right: 70%; top: 50%; transform: translateY(-50%);"></div>
            <!-- Progress Ring -->
            <svg class="progress-ring" width="50" height="50" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-90deg);">
                <circle class="progress-ring-circle" 
                        stroke="#ffffff" 
                        stroke-width="2" 
                        fill="transparent" 
                        r="36" 
                        cx="40" 
                        cy="40"
                        style="transition: stroke-dasharray 0.3s ease;"/>
            </svg>
        </button>
    </div>
</div>

<!-- Flight Booking Modal -->
<div class="modal fade" id="flightBookingModal" tabindex="-1" aria-labelledby="flightBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="flightBookingModalLabel">
                    <i class="fas fa-plane me-2"></i>Book Your Flight
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="flightBookingForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">From</label>
                            <input type="text" class="form-control" id="bookingOrigin" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To</label>
                            <input type="text" class="form-control" id="bookingDestination" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Departure Date</label>
                            <input type="text" class="form-control" id="bookingDepartureDate" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Passengers</label>
                            <input type="number" class="form-control" id="bookingPassengerCount" min="1" max="9" value="1" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Price</label>
                            <input type="text" class="form-control" id="bookingTotalPrice" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select class="form-control" id="bookingPaymentMethod" required>
                                <option value="">Select Payment Method</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <h6>Passenger Details</h6>
                        <div id="passengerDetailsContainer">
                            <!-- Passenger forms will be generated here -->
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mt-3">
                        <h6>Contact Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="contactEmail" value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="contactPhone" value="{{ auth()->user()->phone ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="amadeusOfferId">
                    <input type="hidden" id="amadeusDataJson">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitFlightBooking()">
                    <i class="fas fa-credit-card me-2"></i>Book Flight
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Flight Booking Functions
function showFlightBookingModal(offer) {
    // Populate modal with flight details
    document.getElementById('bookingOrigin').value = `${offer.originCity} (${offer.origin})`;
    document.getElementById('bookingDestination').value = `${offer.destinationCity} (${offer.destination})`;
    document.getElementById('bookingDepartureDate').value = offer.departureDate;
    document.getElementById('bookingTotalPrice').value = `${offer.currency} ${Number(offer.price).toLocaleString()}`;
    document.getElementById('amadeusOfferId').value = offer.id;
    document.getElementById('amadeusDataJson').value = JSON.stringify(offer.amadeusData);
    
    // Generate passenger forms
    generatePassengerForms(1);
    
    // Show modal
    new bootstrap.Modal(document.getElementById('flightBookingModal')).show();
    
    // Update passenger forms when count changes
    document.getElementById('bookingPassengerCount').addEventListener('change', function() {
        generatePassengerForms(parseInt(this.value));
    });
}

function generatePassengerForms(count) {
    const container = document.getElementById('passengerDetailsContainer');
    container.innerHTML = '';
    
    for (let i = 1; i <= count; i++) {
        const passengerForm = document.createElement('div');
        passengerForm.className = 'passenger-form border rounded p-3 mb-3';
        passengerForm.innerHTML = `
            <h6 class="mb-3">Passenger ${i}</h6>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Title</label>
                    <select class="form-control" name="passenger_${i}_title" required>
                        <option value="">Select</option>
                        <option value="Mr">Mr</option>
                        <option value="Ms">Ms</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Dr">Dr</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="passenger_${i}_first_name" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="passenger_${i}_last_name" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="passenger_${i}_dob" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Passport Number</label>
                    <input type="text" class="form-control" name="passenger_${i}_passport" required>
                </div>
            </div>
        `;
        container.appendChild(passengerForm);
    }
}

function submitFlightBooking() {
    const form = document.getElementById('flightBookingForm');
    const formData = new FormData(form);
    
    // Collect passenger details
    const passengerCount = parseInt(document.getElementById('bookingPassengerCount').value);
    const passengers = [];
    
    for (let i = 1; i <= passengerCount; i++) {
        const passenger = {
            title: formData.get(`passenger_${i}_title`),
            first_name: formData.get(`passenger_${i}_first_name`),
            last_name: formData.get(`passenger_${i}_last_name`),
            date_of_birth: formData.get(`passenger_${i}_dob`),
            passport_number: formData.get(`passenger_${i}_passport`)
        };
        passengers.push(passenger);
    }
    
    // Collect contact details
    const contactDetails = {
        email: document.getElementById('contactEmail').value,
        phone: document.getElementById('contactPhone').value
    };
    
    // Prepare booking data
    const bookingData = {
        amadeus_offer_id: document.getElementById('amadeusOfferId').value,
        amadeus_data: document.getElementById('amadeusDataJson').value,
        passenger_details: passengers,
        contact_details: contactDetails,
        origin_code: document.getElementById('amadeusDataJson').value ? JSON.parse(document.getElementById('amadeusDataJson').value).itineraries[0].segments[0].departure.iataCode : '',
        origin_city: document.getElementById('bookingOrigin').value.split(' (')[0],
        destination_code: document.getElementById('amadeusDataJson').value ? JSON.parse(document.getElementById('amadeusDataJson').value).itineraries[0].segments[0].arrival.iataCode : '',
        destination_city: document.getElementById('bookingDestination').value.split(' (')[0],
        departure_date: document.getElementById('bookingDepartureDate').value,
        passenger_count: passengerCount,
        cabin_class: 'Economy', // Default, could be extracted from Amadeus data
        total_price: parseFloat(document.getElementById('bookingTotalPrice').value.replace(/[^\d.]/g, '')),
        currency: document.getElementById('bookingTotalPrice').value.split(' ')[0],
        payment_method: document.getElementById('bookingPaymentMethod').value,
        stops: 0 // Default, could be extracted from Amadeus data
    };
    
    // Submit booking
    fetch('/api/flight-bookings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Flight booking created successfully! Booking reference: ' + data.booking_reference);
            bootstrap.Modal.getInstance(document.getElementById('flightBookingModal')).hide();
            
            // Optionally redirect to booking confirmation page
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
            }
        } else {
            alert('Error creating booking: ' + data.message);
            console.error('Booking error:', data);
        }
    })
    .catch(error => {
        console.error('Error submitting booking:', error);
        alert('An error occurred while creating your booking. Please try again.');
    });
}

// Other floating button functions
function openLiveChat() {
    // Implement live chat functionality
    alert('Live chat feature coming soon!');
}

function scrollToHero() {
    document.querySelector('.hero-section').scrollIntoView({
        behavior: 'smooth'
    });
}
</script>



@endsection