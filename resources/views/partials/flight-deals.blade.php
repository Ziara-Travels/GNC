<div class="flight-deals-section">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="text-center">Special Flight Deals</h2>
        </div>
    </div>
    
    @if(isset($flightDeals) && count($flightDeals) > 0)
        <div class="flight-deals-carousel owl-carousel">
            @foreach($flightDeals as $deal)
                @php
                    $deal->isOfferFlight = true;
                @endphp
                @include('flight.partials.flight-card', ['flight' => $deal, 'isOfferMode' => true])
            @endforeach
        </div>
    @else
        <div class="row" id="deals-placeholder">
            <div class="col-12">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-4" role="status">
                        <span class="visually-hidden">Loading deals...</span>
                    </div>
                    <p>Loading special flight offers...</p>
                </div>
            </div>
        </div>
        
        <!-- Flight deals placeholder for JS loading -->
        <div class="flight-deals-carousel owl-carousel" style="display: none;"></div>
    @endif
</div>

@if(!isset($flightDeals) || count($flightDeals) === 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch flight deals
            fetch('/api/east-african-offers')
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const carousel = document.querySelector('.flight-deals-carousel');
                        const placeholder = document.getElementById('deals-placeholder');
                        
                        // Create flight cards for each deal
                        data.forEach(deal => {
                            const dealCard = createFlightDealCard(deal);
                            carousel.appendChild(dealCard);
                        });
                        
                        // Hide placeholder and show carousel
                        if (placeholder) placeholder.style.display = 'none';
                        carousel.style.display = 'block';
                        
                        // Initialize carousel
                        initializeCarousel();
                    }
                })
                .catch(error => {
                    console.error('Error fetching flight deals:', error);
                });
                
            // Create flight deal card DOM element
            function createFlightDealCard(deal) {
                const template = document.createElement('template');
                template.innerHTML = `
                    <div class="flight-card card mb-4" data-airline="${deal.airline}" data-price="${deal.offerPrice}" data-offer="true">
                        <div class="card-body position-relative">
                            <div class="offer-badge">Special Offer</div>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="airline-logo mb-2">
                                        <img src="/images/airlines/${deal.airline.toLowerCase().replace(/\s+/g, '-')}.png" 
                                             alt="${deal.airline}" 
                                             class="img-fluid" 
                                             onerror="this.src='/images/airlines/default.png'">
                                    </div>
                                    <div class="airline-name">${deal.airline}</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="flight-details">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="departure">
                                                <div class="time fs-4 fw-bold">${deal.departureTime}</div>
                                                <div class="city">${deal.origin}</div>
                                            </div>
                                            
                                            <div class="flight-duration text-center">
                                                <div class="duration-line position-relative">
                                                    <i class="fas fa-plane"></i>
                                                </div>
                                                <div class="small flight-duration">${deal.duration}</div>
                                                <div class="small flight-stops text-muted">${deal.stops === 0 ? 'Direct Flight' : (deal.stops === 1 ? '1 Stop' : deal.stops + ' Stops')}</div>
                                            </div>
                                            
                                            <div class="arrival text-end">
                                                <div class="time fs-4 fw-bold">${deal.arrivalTime}</div>
                                                <div class="city">${deal.destination}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="flight-info mt-2">
                                            <span class="badge bg-light text-dark me-2">Flight ${deal.flightNumber}</span>
                                            <span class="text-muted small">${deal.departureDate}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-end">
                                    <div class="flight-price-wrapper">
                                        <div class="original-price text-decoration-line-through text-muted">
                                            $${(deal.originalPrice).toLocaleString()}
                                        </div>
                                        <div class="flight-price fs-3 fw-bold text-success">
                                            $${(deal.offerPrice).toLocaleString()}
                                        </div>
                                        <div class="discount-badge bg-danger text-white small rounded px-2 py-1 d-inline-block">
                                            Save ${Math.round(((deal.originalPrice - deal.offerPrice) / deal.originalPrice) * 100)}%
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <form action="/book-flight" method="post">
                                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')}">
                                            <input type="hidden" name="flight_id" value="${deal.id}">
                                            <input type="hidden" name="is_offer" value="1">
                                            <button type="submit" class="btn btn-primary btn-block w-100">Book Now</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                return template.content.firstElementChild;
            }
            
            // Initialize Owl Carousel
            function initializeCarousel() {
                $('.flight-deals-carousel').owlCarousel({
                    loop: true,
                    margin: 20,
                    nav: true,
                    dots: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        768: {
                            items: 1
                        },
                        992: {
                            items: 1
                        }
                    },
                    navText: [
                        '<i class="fas fa-chevron-left"></i>',
                        '<i class="fas fa-chevron-right"></i>'
                    ]
                });
            }
        });
    </script>
@else
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Owl Carousel for server-rendered deals
            $('.flight-deals-carousel').owlCarousel({
                loop: true,
                margin: 20,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 1
                    },
                    992: {
                        items: 1
                    }
                },
                navText: [
                    '<i class="fas fa-chevron-left"></i>',
                    '<i class="fas fa-chevron-right"></i>'
                ]
            });
        });
    </script>
@endif

<style>
.flight-deals-section {
    padding: 20px 0;
}

.owl-carousel .owl-nav button.owl-prev,
.owl-carousel .owl-nav button.owl-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.8) !important;
    color: #333 !important;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.owl-carousel .owl-nav button.owl-prev {
    left: -20px;
}

.owl-carousel .owl-nav button.owl-next {
    right: -20px;
}

.owl-carousel .owl-dots {
    margin-top: 15px;
}

.offer-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #ff5722;
    color: white;
    padding: 5px 10px;
    font-size: 0.8rem;
    font-weight: bold;
    border-radius: 0 0 0 10px;
}

.duration-line {
    height: 2px;
    background: #ddd;
    width: 100%;
    margin: 10px 0;
}

.duration-line i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #007bff;
}
</style>
