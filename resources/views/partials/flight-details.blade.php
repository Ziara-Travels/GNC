<div class="card shadow-lg h-100">
    <div class="card-header" style="background-color: #2b2e3d;">
        <h3 class="mb-0 text-white">
            <i class="fas fa-plane-departure me-2" style="color: #e3a300;"></i>
            Flight Details
        </h3>
    </div>
    <div class="card-body">
        @if(isset($flightData->flightOffers[0]))
            <div class="flight-info">
                <!-- Flight Route -->
                @include('partials.flight-route')

                <!-- Flight Details -->
                @include('partials.flight-info')

                <!-- Proceed to Checkout Button -->
                @include('partials.checkout-button')
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Flight details are not available
            </div>
        @endif
    </div>
</div>

<style>
    .flight-line {
        height: 2px;
        background: #e3a300;
        flex-grow: 1;
        position: relative;
        margin: 0 15px;
    }

    .route-info {
        border: 1px solid rgba(0,0,0,0.1);
    }

    .detail-item {
        background-color: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .card {
        border: none;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(227, 163, 0, 0.3);
    }
</style>