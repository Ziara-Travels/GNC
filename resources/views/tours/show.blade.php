<!-- filepath: c:\xampp\htdocs\GNC\resources\views\tours\show.blade.php -->

@extends('layouts.main')

@section('title', $tour->name)

@section('content')
<div class="tour-header" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ $tour->main_image }}') center/cover no-repeat; padding: 120px 0; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">{{ $tour->name }}</h1>
                <div class="d-flex align-items-center mb-4">
                    <span class="me-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $tour->city }}, {{ $tour->country }}
                    </span>
                    <span class="me-3">
                        <i class="fas fa-clock me-2"></i>
                        {{ $tour->duration }}
                    </span>
                    <span class="me-3">
                        <i class="fas fa-star text-warning me-1"></i>
                        {{ number_format($tour->rating, 1) }}
                        <small>({{ $tour->review_count }} reviews)</small>
                    </span>
                </div>
                <div>
                    <span class="badge bg-success px-3 py-2 rounded-pill mb-3">{{ $tour->category }}</span>
                    @if($tour->free_cancellation)
                        <span class="badge bg-info px-3 py-2 rounded-pill mb-3 ms-2">Free Cancellation</span>
                    @endif
                    @if($tour->hotel_pickup)
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 ms-2">Hotel Pickup</span>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="card bg-dark bg-opacity-75 text-white">
                    <div class="card-body p-4">
                        <div class="fs-3 fw-bold mb-2">
                            @if($tour->has_discount)
                                <span class="text-decoration-line-through text-muted me-2" style="font-size: 1.25rem">
                                    {{ $tour->currency }} {{ number_format($tour->original_price, 2) }}
                                </span>
                                <span class="badge bg-danger">{{ $tour->discount_percent }}% OFF</span>
                            @endif
                            <div class="mt-1">
                                <span class="display-6 text-success">{{ $tour->currency }} {{ number_format($tour->price, 2) }}</span>
                                <span class="fs-6">per person</span>
                            </div>
                        </div>
                        <a href="{{ route('tours.booking', $tour->slug) }}" class="btn btn-success btn-lg w-100 mt-3">
                            <i class="fas fa-ticket-alt me-2"></i>Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 pe-lg-5">
            <!-- Tour Gallery -->
            @if($tour->images && count($tour->images) > 1)
            <div class="mb-5">
                <h4 class="mb-4">Gallery</h4>
                <div class="row g-3">
                    @foreach(array_slice($tour->images, 0, 6) as $index => $image)
                        <div class="col-md-4 col-6">
                            <a href="{{ $image }}" data-fancybox="gallery">
                                <img src="{{ $image }}" alt="{{ $tour->name }} - Image {{ $index + 1 }}" class="img-fluid rounded shadow-sm" style="width: 100%; height: 160px; object-fit: cover;">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Tour Description -->
            <div class="mb-5">
                <h4 class="mb-4">Tour Description</h4>
                <div class="tour-description">
                    <p>{{ $tour->full_description ?? $tour->short_description }}</p>
                </div>
            </div>
            
            <!-- Highlights -->
            @if($tour->highlights)
            <div class="mb-5">
                <h4 class="mb-4">Highlights</h4>
                <div class="row">
                    @foreach($tour->highlights as $highlight)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-success mt-1 me-3" style="font-size: 1.2rem;"></i>
                                <span>{{ $highlight }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Inclusions -->
            @if($tour->included_services)
            <div class="mb-5">
                <h4 class="mb-4">What's Included</h4>
                <div class="row">
                    @foreach($tour->included_services as $service)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check text-success mt-1 me-3"></i>
                                <span>{{ $service }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Important Information -->
            <div class="mb-5">
                <h4 class="mb-4">Important Information</h4>
                
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-info-circle text-info me-2"></i>Tour Details
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div style="width: 120px;">
                                        <strong>Duration:</strong>
                                    </div>
                                    <div>{{ $tour->duration }}</div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div style="width: 120px;">
                                        <strong>Difficulty:</strong>
                                    </div>
                                    <div>{{ $tour->difficulty_level }}</div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div style="width: 120px;">
                                        <strong>Group Size:</strong>
                                    </div>
                                    <div>Maximum {{ $tour->max_participants ?? 20 }} people</div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div style="width: 120px;">
                                        <strong>Languages:</strong>
                                    </div>
                                    <div>English</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>Cancellation Policy
                        </h5>
                        <p class="card-text">{{ $tour->cancellation_policy ?? 'Free cancellation up to 24 hours before the tour starts. If you cancel less than 24 hours before the tour, you will not be eligible for a refund.' }}</p>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clipboard-list text-primary me-2"></i>What to Bring
                        </h5>
                        <ul>
                            <li>Comfortable walking shoes</li>
                            <li>Sunscreen and hat</li>
                            <li>Water bottle</li>
                            <li>Camera</li>
                            <li>Weather-appropriate clothing</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="mb-5">
                <h4 class="mb-4">Reviews</h4>
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center border-end">
                                <div class="display-4 fw-bold text-success">{{ number_format($tour->rating, 1) }}</div>
                                <div class="text-muted mb-2">out of 5</div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($tour->rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="mt-2">{{ $tour->review_count }} reviews</div>
                            </div>
                            <div class="col-md-8">
                                <div class="px-md-4">
                                    <!-- 5 Star -->
                                    <div class="d-flex align-items-center mb-2">
                                        <div style="width: 60px;">5 stars</div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-success" style="width: 70%"></div>
                                        </div>
                                        <div style="width: 40px;" class="ps-2">70%</div>
                                    </div>
                                    
                                    <!-- 4 Star -->
                                    <div class="d-flex align-items-center mb-2">
                                        <div style="width: 60px;">4 stars</div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-success" style="width: 20%"></div>
                                        </div>
                                        <div style="width: 40px;" class="ps-2">20%</div>
                                    </div>
                                    
                                    <!-- 3 Star -->
                                    <div class="d-flex align-items-center mb-2">
                                        <div style="width: 60px;">3 stars</div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-warning" style="width: 7%"></div>
                                        </div>
                                        <div style="width: 40px;" class="ps-2">7%</div>
                                    </div>
                                    
                                    <!-- 2 Star -->
                                    <div class="d-flex align-items-center mb-2">
                                        <div style="width: 60px;">2 stars</div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-warning" style="width: 2%"></div>
                                        </div>
                                        <div style="width: 40px;" class="ps-2">2%</div>
                                    </div>
                                    
                                    <!-- 1 Star -->
                                    <div class="d-flex align-items-center">
                                        <div style="width: 60px;">1 star</div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-danger" style="width: 1%"></div>
                                        </div>
                                        <div style="width: 40px;" class="ps-2">1%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Review Examples -->
                <div class="review-list">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <img src="https://ui-avatars.com/api/?name=John+D&background=random" alt="John D." class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-1">John D.</h6>
                                    <div class="text-muted small">Traveled last month</div>
                                </div>
                                <div class="ms-auto">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <h6>"Absolutely amazing experience!"</h6>
                            <p>This tour exceeded all my expectations. The guide was knowledgeable and friendly, and we saw so much wildlife. Definitely the highlight of our trip to East Africa!</p>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <img src="https://ui-avatars.com/api/?name=Sarah+M&background=random" alt="Sarah M." class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-1">Sarah M.</h6>
                                    <div class="text-muted small">Traveled 2 months ago</div>
                                </div>
                                <div class="ms-auto">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <h6>"Great tour, worth the price"</h6>
                            <p>We had a wonderful time on this tour. The only reason for 4 stars instead of 5 is that we felt slightly rushed at some points. But overall, it was a great experience and I would recommend it.</p>
                        </div>
                    </div>
                    
                    <!-- View More Reviews Button -->
                    <div class="text-center">
                        <button class="btn btn-outline-success px-4">
                            <i class="fas fa-comments me-2"></i>View All Reviews
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Booking Card -->
            <div class="card border-0 shadow sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body">
                    <h5 class="card-title mb-4">Book this tour</h5>
                    
                    <div class="price-summary mb-4">
                        <div class="fs-3 fw-bold mb-2">
                            @if($tour->has_discount)
                                <span class="text-decoration-line-through text-muted me-2" style="font-size: 1.25rem">
                                    {{ $tour->currency }} {{ number_format($tour->original_price, 2) }}
                                </span>
                                <span class="badge bg-danger">{{ $tour->discount_percent }}% OFF</span>
                            @endif
                            <div class="mt-1">
                                <span class="text-success">{{ $tour->currency }} {{ number_format($tour->price, 2) }}</span>
                                <span class="fs-6">per person</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('tours.booking', $tour->slug) }}" class="btn btn-success btn-lg mb-3">
                            <i class="fas fa-ticket-alt me-2"></i>Book Now
                        </a>
                    </div>
                    
                    <div class="tour-features mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-success"></i>
                            </div>
                            <div class="ms-3">
                                <strong>Available daily</strong>
                                <div class="text-muted small">Subject to availability</div>
                            </div>
                        </div>
                        
                        @if($tour->instant_confirmation)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bolt text-success"></i>
                            </div>
                            <div class="ms-3">
                                <strong>Instant confirmation</strong>
                                <div class="text-muted small">Receive confirmation immediately</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($tour->free_cancellation)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-times-circle text-success"></i>
                            </div>
                            <div class="ms-3">
                                <strong>Free cancellation</strong>
                                <div class="text-muted small">Up to 24 hours before</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($tour->hotel_pickup)
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-car text-success"></i>
                            </div>
                            <div class="ms-3">
                                <strong>Hotel pickup available</strong>
                                <div class="text-muted small">For most city hotels</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Similar Tours -->
            @if(count($relatedTours) > 0)
            <div class="card border-0 shadow mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">You Might Also Like</h5>
                    
                    @foreach($relatedTours as $relatedTour)
                    <div class="related-tour mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <a href="{{ route('tours.show', $relatedTour->slug) }}" class="text-decoration-none">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="{{ $relatedTour->main_image }}" class="img-fluid rounded" alt="{{ $relatedTour->name }}" style="height: 70px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="col-8 ps-3">
                                    <h6 class="mb-1 text-dark">{{ Str::limit($relatedTour->name, 40) }}</h6>
                                    <div class="text-warning small mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($relatedTour->rating))
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="text-success fw-bold">{{ $relatedTour->currency }} {{ number_format($relatedTour->price, 2) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Fancybox for gallery
        Fancybox.bind("[data-fancybox='gallery']", {
            // Options here
        });
    });
</script>
@endsection