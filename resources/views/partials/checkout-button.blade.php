@auth
    <form action="{{ route('booking.process') }}" method="POST" class="mt-4">
        @csrf
        <input type="hidden" name="flight_data" value="{{ json_encode($data) }}">
        <button type="submit" class="btn btn-lg w-100" 
                style="background-color: #e3a300; color: white;">
            <i class="fas fa-shopping-cart me-2"></i>
            Proceed to Checkout
        </button>
    </form>
@else
    <div class="mt-4">
        <a href="{{ route('register') }}" class="btn btn-lg w-100" 
        style="background-color: #e3a300; color: white;">
            <i class="fas fa-user-plus me-2"></i>
            Register to Continue
        </a>
        <div class="text-center mt-3">
            <span class="text-muted">Already have an account?</span>
            <a href="{{ route('login') }}" class="ms-2" style="color: #e3a300;">
                Login here
            </a>
        </div>
    </div>
@endauth