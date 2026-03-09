@extends('layouts.main') 



@section('content')

<!-- Flight Deals Section -->
<section class="deals-section-hero position-relative">
    <div class="container h-100">
        <div class="text-center mb-5 position-relative">
            <!-- Animated Background Elements - Flight Theme -->
            <div class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center" style="top: 0; left: 0; pointer-events: none;">
                <div class="floating-icon" style="position: absolute; top: 20%; left: 15%; animation: fly 8s ease-in-out infinite;">
                    <i class="fas fa-paper-plane" style="color: rgba(255, 193, 7, 0.15); font-size: 2.5rem; transform: rotate(-15deg);"></i>
                </div>
                <div class="floating-icon" style="position: absolute; top: 25%; right: 20%; animation: fly 10s ease-in-out infinite reverse;">
                    <i class="fas fa-plane" style="color: rgba(255, 193, 7, 0.15); font-size: 2rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 25%; left: 25%; animation: float 6s ease-in-out infinite;">
                    <i class="fas fa-globe" style="color: rgba(255, 193, 7, 0.15); font-size: 1.8rem;"></i>
                </div>
                <div class="floating-icon" style="position: absolute; bottom: 30%; right: 15%; animation: fly 12s ease-in-out infinite;">
                    <i class="fas fa-map-marked-alt" style="color: rgba(255, 193, 7, 0.15); font-size: 1.5rem;"></i>
                </div>
            </div>
            
            <!-- Main Heading with Enhanced Typography -->
            <div class="position-relative z-index-2">
                <div class="mb-3">
                    <span class="badge px-4 py-2 rounded-pill" style="background: linear-gradient(45deg, #ffc107, #ffeb3b); color: #212529; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">
                        ✈️ EAST AFRICAN DESTINATIONS
                    </span>
                </div>
                
                <h2 class="fw-bold mb-3" style="color: #495057; font-size: 2.5rem; line-height: 1.2; position: relative;">
                    <span class="position-relative">
                        <i class="fas fa-plane me-3" style="color: #ffc107; font-size: 2rem; vertical-align: middle; text-shadow: 0 0 20px rgba(255, 193, 7, 0.3); animation: planeFly 3s ease-in-out infinite;"></i>
                        Featured Flights
                        <!-- Decorative underline -->
                        <div class="position-absolute w-100" style="bottom: -8px; left: 0; height: 3px; background: linear-gradient(90deg, transparent, #ffc107, transparent); border-radius: 3px;"></div>
                    </span>
                </h2>
                
                <p class="mb-4" style="color: #6c757d; font-size: 1.1rem; font-weight: 300; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                    <span class="d-inline-block" style="border-left: 3px solid #ffc107; padding-left: 15px;">
                        Discover our best flight deals across East Africa's top destinations
                        <br>
                        <small style="color: rgba(108, 117, 125, 0.8); font-size: 0.9rem;">
                            Best prices • Flexible booking • Instant confirmation
                        </small>
                    </span>
                </p>
                
                <!-- Flight stats indicator -->
                <div class="d-flex justify-content-center align-items-center gap-3 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bolt text-warning me-2" style="animation: pulse 2s infinite;"></i>
                        <span style="color: #495057; font-size: 0.9rem; font-weight: 500;">
                            Flash Sale - Limited Time
                        </span>
                    </div>
                    <div style="width: 2px; height: 20px; background: rgba(255, 193, 7, 0.3);"></div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-ticket-alt text-warning me-2"></i>
                        <span style="color: rgba(73, 80, 87, 0.8); font-size: 0.9rem;">
                            <span id="flight-offer-count">9</span> Amazing Deals Available
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="flight-offers-container">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Searching for the best deals across East Africa...</p>
            </div>
        </div>
    </div>

    <!-- Flight Offer Template -->
    <template id="flight-offer-template">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-lift h-100">
                <div class="position-relative">
                    <img src="" class="card-img-top destination-image" alt="Destination" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-danger rounded-pill px-3 py-2 discount-badge">20% OFF</span>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="text-white mb-0 destination-city">City Name</h5>
                                <small class="text-white-50 destination-country">Country</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary rounded-pill airline-badge">KQ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-light text-dark highlight-badge"><i class="fas fa-map-marker-alt me-1"></i> Highlight</span>
                        <span class="badge bg-success text-white cabin-badge">Economy</span>
                    </div>
                    
                    <div class="mb-3 travel-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-6 fw-semibold origin-city">Nairobi</span>
                                <i class="fas fa-arrow-right mx-1 text-muted" style="font-size: 12px;"></i>
                                <span class="fs-6 fw-semibold destination-city-small">Mombasa</span>
                            </div>
                            <small class="text-muted departure-date">Aug 15, 2023</small>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <div class="d-flex align-items-center me-3">
                                <i class="fas fa-clock text-muted me-1" style="font-size: 12px;"></i>
                                <small class="text-muted flight-duration">1h 30m</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-plane-arrival text-muted me-1" style="font-size: 12px;"></i>
                                <small class="text-muted stops-info">Direct</small>
                            </div>
                        </div>
                    </div>
                    
                    <p class="card-text text-muted small mb-3 destination-description">Beautiful coastal city with pristine beaches and rich culture.</p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="price fs-4 fw-bold text-warning current-price">KES 17,000</span>
                            <small class="text-muted text-decoration-line-through ms-2 original-price">KES 21,000</small>
                        </div>
                        <a href="#" class="btn btn-warning rounded-pill px-4 book-button">
                            <i class="fas fa-plane me-1"></i>Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- JavaScript for Dynamic Flight Offers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadFlightOffers();
        });
        
        function loadFlightOffers() {
            fetch('/east-african-offers')
                .then(response => response.json())
                .then(data => {
                    displayFlightOffers(data);
                })
                .catch(error => {
                    console.error('Error fetching flight offers:', error);
                    displayDummyOffers();
                });
        }

        // JavaScript for Dynamic Flight Offers
        function displayFlightOffers(offers) {
            const container = document.getElementById('flight-offers-container');
            container.innerHTML = '';
            
            // Update count
            document.getElementById('flight-offer-count').textContent = offers.length;
            
            // Create carousel structure
            const carouselDiv = document.createElement('div');
            carouselDiv.className = 'col-12';
            carouselDiv.innerHTML = `
                <div class="carousel slide" id="dealsCarousel">
                    <div class="carousel-inner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#dealsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#dealsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            `;
            container.appendChild(carouselDiv);
            
            const carouselInner = document.querySelector('#dealsCarousel .carousel-inner');
            
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
                    offerElement.querySelector('.highlight-badge').innerHTML = `<i class="fas fa-map-marker-alt me-1"></i> ${offer.highlight}`;
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
                        
                        // Create form to submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/book-flight';
                        
                        // Add CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                        
                        // Add flight data
                        const flightDataInput = document.createElement('input');
                        flightDataInput.type = 'hidden';
                        flightDataInput.name = 'flight_data';
                        flightDataInput.value = JSON.stringify(offer);
                        form.appendChild(flightDataInput);
                        
                        // Show loading indicator
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
                        this.disabled = true;
                        
                        // Append form to body and submit
                        document.body.appendChild(form);
                        form.submit();
                    });
                    
                    row.appendChild(offerElement);
                });
                
                carouselItem.appendChild(row);
                carouselInner.appendChild(carouselItem);
            });
            
            // Initialize carousel
            new bootstrap.Carousel(document.getElementById('dealsCarousel'), {
                interval: 5000,
                touch: true
            });
        }

        // Update the dummy offers function to use local travel images too
        function displayDummyOffers() {
            // Fallback function if the API fails
            const dummyOffers = [
                {
                    destination: 'MBA',
                    destinationCity: 'Mombasa',
                    destinationCountry: 'Kenya',
                    destinationImage: 'mombasa.jpg',
                    description: 'Coastal city with rich history and beaches',
                    highlight: 'Iconic Ivory Tusks',
                    origin: 'NBO',
                    originCity: 'Nairobi',
                    departureDate: 'Aug 15, 2023',
                    departureTime: '08:30',
                    returnDate: 'Aug 22, 2023',
                    duration: '1h 5m',
                    price: 12000,
                    originalPrice: 15000,
                    currency: 'KES',
                    discountPercent: 20,
                    cabin: 'Economy',
                    airline: 'KQ',
                    stops: 0
                },
                {
                    destination: 'ZNZ',
                    destinationCity: 'Zanzibar',
                    destinationCountry: 'Tanzania',
                    destinationImage: 'zanzibar.jpg',
                    description: 'Crystal clear waters and pristine beaches',
                    highlight: 'Beautiful Beaches',
                    origin: 'NBO',
                    originCity: 'Nairobi',
                    departureDate: 'Aug 18, 2023',
                    departureTime: '10:15',
                    returnDate: 'Aug 25, 2023',
                    duration: '1h 25m',
                    price: 18500,
                    originalPrice: 23125,
                    currency: 'KES',
                    discountPercent: 20,
                    cabin: 'Economy',
                    airline: 'KQ',
                    stops: 0
                },
                {
                    destination: 'EBB',
                    destinationCity: 'Entebbe',
                    destinationCountry: 'Uganda',
                    destinationImage: 'entebbe.jpg',
                    description: 'Scenic city on the shores of Lake Victoria',
                    highlight: 'Gateway to Uganda',
                    origin: 'NBO',
                    originCity: 'Nairobi',
                    departureDate: 'Aug 20, 2023',
                    departureTime: '14:30',
                    returnDate: 'Aug 27, 2023',
                    duration: '1h 15m',
                    price: 14500,
                    originalPrice: 18125,
                    currency: 'KES',
                    discountPercent: 20,
                    cabin: 'Economy',
                    airline: 'KQ',
                    stops: 0
                },
                {
                    destination: 'DAR',
                    destinationCity: 'Dar es Salaam',
                    destinationCountry: 'Tanzania',
                    destinationImage: 'dar-es-salaam.jpg',
                    description: 'Major Tanzanian port with vibrant markets',
                    highlight: 'Cultural Hub',
                    origin: 'NBO',
                    originCity: 'Nairobi',
                    departureDate: 'Aug 22, 2023',
                    departureTime: '09:45',
                    returnDate: 'Aug 29, 2023',
                    duration: '1h 30m',
                    price: 16800,
                    originalPrice: 21000,
                    currency: 'KES',
                    discountPercent: 20,
                    cabin: 'Economy',
                    airline: 'KQ',
                    stops: 0
                },
                {
                    destination: 'JRO',
                    destinationCity: 'Kilimanjaro',
                    destinationCountry: 'Tanzania',
                    destinationImage: 'kilimanjaro.jpg',
                    description: 'Gateway to Africa\'s highest peak',
                    highlight: 'Mountain Adventure',
                    origin: 'NBO',
                    originCity: 'Nairobi',
                    departureDate: 'Aug 25, 2023',
                    departureTime: '07:30',
                    returnDate: 'Sep 1, 2023',
                    duration: '1h 10m',
                    price: 15200,
                    originalPrice: 19000,
                    currency: 'KES',
                    discountPercent: 20,
                    cabin: 'Economy',
                    airline: 'PW',
                    stops: 0
                },
                {
                    destination: 'KGL',
                    destinationCity: 'Kigali',
                    destinationCountry: 'Rwanda',
                    destinationImage: 'kigali.jpg',
                    description: 'Clean, green city with vibrant culture',
                    highlight: 'Modern Africa',
                    origin: 'NBO',
                    originCity: 'Nairobi',
                    departureDate: 'Aug 28, 2023',
                    departureTime: '11:20',
                    returnDate: 'Sep 4, 2023',
                    duration: '1h 20m',
                    price: 17500,
                    originalPrice: 21875,
                    currency: 'KES',
                    discountPercent: 20,
                    cabin: 'Economy',
                    airline: 'RW',
                    stops: 0
                }
            ];
            
            displayFlightOffers(dummyOffers);
        }

        // Define animation for plane icon
        if (!document.getElementById('flight-deals-animations')) {
            const style = document.createElement('style');
            style.id = 'flight-deals-animations';
            style.textContent = `
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
            `;
            document.head.appendChild(style);
        }
    </script>
</section>

@endsection


