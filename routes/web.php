<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\SearchFlightController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Travel\BlogController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\GetPriceController;
use App\Http\Controllers\FlightSearchController;
use App\Http\Controllers\HotelSearchController;
use App\Http\Controllers\OrderFlightController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\GDPRController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\UserFlightContolller;
use App\Http\Controllers\AITripPlannerController;
use App\Http\Controllers\AIChatbotController;
use App\Http\Controllers\SmartAutoFillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\GDPRController as AdminGDPRController;
use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Admin\SeatController as AdminSeatController;
use App\Http\Controllers\Admin\ReferralController as AdminReferralController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\AIPricePredictionController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\UserDashboardController;
use App\Http\Controllers\Dashboard\SuperadminDashboardController;
use App\Http\Controllers\Travel\FlightOffersController;
use App\Http\Controllers\Travel\HotelOffersController;
use App\Http\Controllers\Travel\ActivityController as TravelActivityController;
use App\Http\Controllers\Travel\PopularDestinationController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Travel\NewsletterController;
use App\Http\Controllers\Travel\AITripPlannerController as TravelAITripPlannerController;
use App\Http\Controllers\Travel\AIChatbotController as TravelAIChatbotController;
use App\Http\Controllers\Travel\SmartAutoFillController as TravelSmartAutoFillController;
use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\GDPRController as DashboardGDPRController;
use App\Http\Controllers\API\AmadeusController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\HotelBookingController;
use App\Http\Controllers\TourBookingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SEOController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExchangeRateController;


Route::get('/search-flight', function () {
    return view('home'); 
})->name('search-flight');

// =====================================
// PUBLIC ROUTES
// =====================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/search', [SearchFlightController::class, 'index'])->name('search-flights');
Route::get('/travel-insurance', [SupportController::class, 'travelInsurance'])->name('travel-insurance');
Route::get('/about-us', [SupportController::class, 'aboutUs'])->name('aboutus');
Route::get('/special-offers', [SupportController::class, 'specialOffers'])->name('special-offers');
Route::get('/loyalty-points', [SupportController::class, 'loyaltyPoints'])->name('loyalty-points');
Route::get('/vip-membership', [SupportController::class, 'vipMembership'])->name('vip-membership');
Route::get('/contact-us', [SupportController::class, 'index'])->name('contactus');
Route::post('/contact-us', [SupportController::class, 'submit'])->name('contactus.submit');
Route::get('/live-chat', [SupportController::class, 'liveChat'])->name('live-chat');
Route::get('/help-center', [SupportController::class, 'helpCenter'])->name('help-center');
Route::get('/faq', [SupportController::class, 'faqs'])->name('faq');
Route::get('/help', [SupportController::class, 'help'])->name('help');
Route::get('/support', [SupportController::class, 'support'])->name('support');
Route::get('/what-we-do', [SupportController::class, 'whatWeDo'])->name('what-we-do');
Route::get('/faqs', [SupportController::class, 'faqs'])->name('faqs');
Route::get('/customer-stories', [SupportController::class, 'customerStories'])->name('customer-stories');
Route::get('/blog', [SupportController::class, 'blog'])->name('blog');
Route::get('/news', [SupportController::class, 'news'])->name('news');
Route::get('/events', [SupportController::class, 'events'])->name('events');
Route::get('/careers', [SupportController::class, 'careers'])->name('careers');
Route::get('/why-choose-us', [SupportController::class, 'whyChooseUs'])->name('why-choose-us');
Route::get('/achievements', [SupportController::class, 'achievements'])->name('achievements');
Route::get('/referral-program', [SupportController::class, 'referralProgram'])->name('referral-program');
Route::get('/terms-of-service', [SupportController::class, 'termsOfService'])->name('terms-of-service');
Route::get('/privacy-policy', [SupportController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/cookie-policy', [SupportController::class, 'cookiePolicy'])->name('cookie-policy');
Route::get('/refund-policy', [SupportController::class, 'refundPolicy'])->name('refund-policy');
Route::get('/cancellation-policy', [SupportController::class, 'cancellationPolicy'])->name('cancellation-policy');
Route::get('/disclaimer', [SupportController::class, 'disclaimer'])->name('disclaimer');
Route::get('/sitemap', [SupportController::class, 'sitemap'])->name('sitemap');
Route::get('/sitemap.xml', [SupportController::class, 'sitemapXml'])->name('sitemap.xml');
Route::get('/robots.txt', [SupportController::class, 'robotsTxt'])->name('robots.txt');
Route::post('/book-flight', [App\Http\Controllers\Travel\FlightOffersController::class, 'bookDealFlight'])->name('book.deal-flight');

// Deal Routes
// Flight Deal Routes
Route::get('/deals/flights', [App\Http\Controllers\Travel\Deal\FlightDealController::class, 'showResults'])->name('deal.flight.results');
Route::get('/deals/flights/{dealId}', [App\Http\Controllers\Travel\Deal\FlightDealController::class, 'showBooking'])->name('deal.flight.booking');
Route::post('/deals/flights/book', [App\Http\Controllers\Travel\Deal\FlightDealController::class, 'bookDeal'])->name('deal.flight.book');
Route::get('/deals/flights/checkout/{reference}', [App\Http\Controllers\Travel\Deal\FlightDealController::class, 'showCheckout'])->name('deal.flight.checkout');
Route::post('/deals/flights/payment/{reference}', [App\Http\Controllers\Travel\Deal\FlightDealController::class, 'processPayment'])->name('deal.flight.payment');

// Hotel Deal Routes
Route::get('/deals/hotels', [App\Http\Controllers\Travel\Deal\HotelDealController::class, 'showResults'])->name('deal.hotel.results');
Route::get('/deals/hotels/{dealId}', [App\Http\Controllers\Travel\Deal\HotelDealController::class, 'showBooking'])->name('deal.hotel.booking');
Route::post('/deals/hotels/book', [App\Http\Controllers\Travel\Deal\HotelDealController::class, 'bookDeal'])->name('deal.hotel.book');
Route::get('/deals/hotels/checkout/{reference}', [App\Http\Controllers\Travel\Deal\HotelDealController::class, 'showCheckout'])->name('deal.hotel.checkout');
Route::post('/deals/hotels/payment/{reference}', [App\Http\Controllers\Travel\Deal\HotelDealController::class, 'processPayment'])->name('deal.hotel.payment');

// Tour Deal Routes
Route::get('/deals/tours', [App\Http\Controllers\Travel\Deal\TourDealController::class, 'showResults'])->name('deal.tour.results');
Route::get('/deals/tours/{dealId}', [App\Http\Controllers\Travel\Deal\TourDealController::class, 'showBooking'])->name('deal.tour.booking');
Route::post('/deals/tours/book', [App\Http\Controllers\Travel\Deal\TourDealController::class, 'bookDeal'])->name('deal.tour.book');
Route::get('/deals/tours/checkout/{reference}', [App\Http\Controllers\Travel\Deal\TourDealController::class, 'showCheckout'])->name('deal.tour.checkout');
Route::post('/deals/tours/payment/{reference}', [App\Http\Controllers\Travel\Deal\TourDealController::class, 'processPayment'])->name('deal.tour.payment');
// =====================================
// PUBLIC FLIGHT SEARCH & BOOKING ROUTES
// =====================================
// Flight search routes for all users (including non-authenticated)
Route::post('/api/search', [FlightSearchController::class, '__invoke'])->name('flight.search');
Route::get('/flights/search', [FlightSearchController::class, 'showPublicSearch'])->name('flights.public.search');
Route::get('/flights/public-search', [FlightSearchController::class, 'showPublicSearch'])->name('flights.public.search.alt');
Route::get('/flights/results', [FlightSearchController::class, 'showPublicResults'])->name('flights.results');
Route::post('/api/price', [FlightSearchController::class, 'priceItinerary'])->name('flight.price');

// Flight selection and booking for non-authenticated users
Route::post('/flights/select', [OrderFlightController::class, 'selectFlight'])->name('flights.select');
Route::get('/flights/booking/{offerId}', [FlightBookingController::class, 'show'])->name('flights.booking');
Route::post('/flights/book', [FlightBookingController::class, 'store'])->name('flights.book');
Route::get('/booking/checkout', [OrderFlightController::class, 'showCheckout'])->name('booking.checkout');
Route::post('/booking/process', [OrderFlightController::class, 'processCheckout'])->name('booking.process');
Route::get('/booking/success/{reference}', [OrderFlightController::class, 'showSuccess'])->name('booking.success');
Route::get('/booking/eticket/{reference}', [OrderFlightController::class, 'downloadETicket'])->name('booking.eticket');
Route::get('/booking/failure', [OrderFlightController::class, 'bookingFailure'])->name('booking.failure');

// Airport search endpoints for flight search
Route::get('/api/airports/search', [AirportController::class, 'search'])->name('airports.search');
Route::get('/api/airports/popular', [AirportController::class, 'getPopularAirports'])->name('airports.popular');

// =====================================
// PUBLIC HOTEL SEARCH & BOOKING ROUTES
// =====================================
// Hotel search routes for all users (including non-authenticated)
Route::get('/hotels/search', [HotelSearchController::class, 'showSearchForm'])->name('hotels.search.form');
Route::post('/api/hotels/search', [HotelSearchController::class, '__invoke'])->name('hotels.search');
Route::get('/hotels/results', [HotelSearchController::class, 'showResults'])->name('hotels.results');

// Hotel booking routes
Route::get('/hotels/{hotelId}', [HotelSearchController::class, 'showHotel'])->name('hotels.show');
Route::get('/hotels/{hotelId}/book', [HotelSearchController::class, 'bookingForm'])->name('hotels.booking');
Route::post('/hotels/book', [HotelSearchController::class, 'processBooking'])->name('hotels.process-booking');
Route::get('/hotels/checkout/{reference}', [HotelSearchController::class, 'checkout'])->name('hotels.checkout');
Route::post('/hotels/payment/{reference}', [HotelSearchController::class, 'processPayment'])->name('hotels.payment');
Route::get('/hotels/confirmation/{reference}', [HotelSearchController::class, 'confirmation'])->name('hotels.confirmation');


// =====================================
// TOUR ROUTES
// =====================================
Route::prefix('tours')->name('tours.')->group(function () {
    // Public tour routes
    Route::get('/', [TourController::class, 'index'])->name('index');
    Route::get('/search', [TourController::class, 'search'])->name('search');
    Route::get('/{slug}', [TourController::class, 'show'])->name('show');

    // Tour booking routes (require authentication)
    Route::middleware('auth')->group(function () {
        Route::get('/{slug}/book', [TourController::class, 'booking'])->name('booking');
        Route::post('/book', [TourController::class, 'book'])->name('book');
        Route::get('/payment/{reference}', [TravelActivityController::class, 'payment'])->name('payment');
        Route::post('/payment/process', [TravelActivityController::class, 'processPayment'])->name('payment.process');
        Route::get('/confirmation/{reference}', [TravelActivityController::class, 'confirmation'])->name('confirmation');
        Route::get('/my-bookings', [TravelActivityController::class, 'myBookings'])->name('my-bookings');
        Route::post('/cancel/{reference}', [TravelActivityController::class, 'cancel'])->name('cancel');
    });
    
    // API routes for tour data
    Route::get('/api/{tourId}/available-dates', [TravelActivityController::class, 'getAvailableDates'])->name('available-dates');
});

// =====================================
// ACTIVITY/TOUR API ROUTES
// =====================================
Route::get('/east-african-tours', [TravelActivityController::class, 'getEastAfricanTours'])->name('east-african-tours');
Route::get('/east-african-activities', [TravelActivityController::class, 'getEastAfricanActivities'])->name('east-african-activities');

// Tour booking routes
Route::post('/book-tour', [TravelActivityController::class, 'bookTour'])->name('book.tour');
Route::post('/book-activity', [TravelActivityController::class, 'bookActivity'])->name('book.activity');

// Tour payment routes
Route::get('/tour/payment/{reference}', [TravelActivityController::class, 'payment'])->name('tour.payment');
Route::post('/tour/payment/process', [TravelActivityController::class, 'processPayment'])->name('tour.payment.process');
Route::get('/tour/confirmation/{reference}', [TravelActivityController::class, 'confirmation'])->name('tour.confirmation');

// =====================================
// HOMEPAGE FLIGHT & HOTEL BOOKING ROUTES
// =====================================
Route::get('/east-african-offers', [FlightOffersController::class, 'getEastAfricanOffers'])->name('east-african-offers');
Route::post('/book-flight', [FlightOffersController::class, 'bookFlight'])->name('book.flight');
Route::get('/booking/{type}/passenger-details', [FlightOffersController::class, 'showPassengerDetailsForm'])->name('booking.passenger-details');
Route::post('/booking/passenger-details', [FlightOffersController::class, 'savePassengerDetails'])->name('booking.save-passengers');
Route::get('/booking/{type}/payment', [FlightOffersController::class, 'showPaymentPage'])->name('booking.payment');
Route::post('/booking/process-payment', [FlightOffersController::class, 'processPayment'])->name('booking.process-payment');
// Duplicate route removed — booking.success is defined at line 141 using OrderFlightController
// Route::get('/booking/success/{bookingReference}', [FlightOffersController::class, 'bookingSuccess'])->name('booking.success');
Route::get('/east-african-hotels', [HotelOffersController::class, 'getEastAfricanHotels'])->name('east-african-hotels');
Route::post('/book-hotel', [HotelOffersController::class, 'bookHotel'])->name('book.hotel');

// Activities and Destinations
Route::get('/east-african-activities-home', [TravelActivityController::class, 'getEastAfricanActivities']);
Route::get('/booking/confirmation/{reference}', [TravelActivityController::class, 'showConfirmation'])->name('booking.confirmation');
Route::get('/east-african-destinations', [App\Http\Controllers\Travel\PopularDestinationController::class, 'getDestinations'])->name('destinations.east-african');
Route::post('/book-destination', [App\Http\Controllers\Travel\PopularDestinationController::class, 'bookDestination'])->name('book.destination');
Route::get('/destination/results', [App\Http\Controllers\Travel\PopularDestinationController::class, 'showDestinationResults'])->name('popular.destination.results');
Route::post('/destination/booking/process', [App\Http\Controllers\Travel\PopularDestinationController::class, 'processDestinationBooking'])->name('popular.destination.process');

// Newsletter Routes
Route::post('/newsletter/subscribe', [App\Http\Controllers\Travel\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe', [App\Http\Controllers\Travel\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/preferences', [App\Http\Controllers\Travel\NewsletterController::class, 'updatePreferences'])->name('newsletter.preferences');
Route::post('/custom-trip', [App\Http\Controllers\Travel\PopularDestinationController::class, 'storeCustomTrip'])->name('custom.trip.store');

// =====================================
// BLOG ROUTES
// =====================================
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/tags/{tag}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/archive', [BlogController::class, 'archive'])->name('blog.archive');
Route::get('/blog/author/{author}', [BlogController::class, 'author'])->name('blog.author');
Route::get('/blog/featured', [BlogController::class, 'featured'])->name('blog.featured');
Route::get('/blog/latest', [BlogController::class, 'latest'])->name('blog.latest');
Route::get('/blog/popular', [BlogController::class, 'popular'])->name('blog.popular');
Route::get('/blog/archives', [BlogController::class, 'archives'])->name('blog.archives');
Route::get('/blog/tags', [BlogController::class, 'tags'])->name('blog.tags');
Route::get('/blog/categories', [BlogController::class, 'categories'])->name('blog.categories');
Route::get('/blog/related/{id}', [BlogController::class, 'relatedPosts'])->name('blog.related');
Route::get('/blog/popular-posts', [BlogController::class, 'popularPosts'])->name('blog.popular-posts');
Route::get('/blog/featured-posts', [BlogController::class, 'featuredPosts'])->name('blog.featured-posts');

// =====================================
// API ROUTES
// =====================================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =====================================
// AIRPORT API ROUTES
// =====================================
Route::prefix('airports')->group(function () {
    Route::get('/search', [AirportController::class, 'search']);
    Route::get('/search/iata', [AirportController::class, 'searchByIataCode']);
    Route::get('/popular', [AirportController::class, 'getPopularAirports']);
    Route::get('/code/{iataCode}', [AirportController::class, 'getByIataCode']);
    Route::get('/country/{country}', [AirportController::class, 'getByCountry']);
    Route::get('/city/{city}', [AirportController::class, 'getByCity'])->name('airports.city');
    Route::get('/locations/search', [AirportController::class, 'searchLocations'])->name('airports.locations.search');
    Route::get('/search/locations', [AirportController::class, 'searchLocations'])->name('airports.search.locations');
    Route::get('/locations/{locationId}', [AirportController::class, 'getLocationById'])->name('airports.locations.show');
});

// =====================================
// PUBLIC API ROUTES
// =====================================
Route::prefix('api')->group(function () {
    Route::post('/search', [FlightSearchController::class, '__invoke'])->name('flights.search');
    Route::post('/hotels/search', [HotelSearchController::class, '__invoke'])->name('hotels.search');
    Route::prefix('weather')->group(function () {
        Route::get('/search', [WeatherController::class, 'searchCities']);
    });
    Route::prefix('ai')->group(function () {
        Route::post('/price-prediction', [AIPricePredictionController::class, 'generatePrediction']);
    });
});

// =====================================
// FLIGHT SCHEDULE ROUTES
// =====================================
Route::prefix('api/flights')->group(function () {
    Route::get('/schedule', [FlightSearchController::class, 'getFlightSchedule'])->name('flights.schedule');
    Route::get('/search-with-schedule', [FlightSearchController::class, 'searchFlightsWithSchedule'])->name('flights.search-with-schedule');
    Route::get('/unique', [FlightSearchController::class, 'getUniqueFlights'])->name('flights.unique');
    Route::get('/direct-destinations', [FlightSearchController::class, 'getDirectDestinations'])->name('flights.direct-destinations');
    Route::post('/filter-direct', [FlightSearchController::class, 'filterDirectFlights'])->name('flights.filter-direct');
    Route::get('/direct-destinations-with-flights', [FlightSearchController::class, 'getDirectDestinationsWithFlights'])->name('flights.direct-destinations-with-flights');
});

// =====================================
// AUTHENTICATION ROUTES
// =====================================
require __DIR__.'/auth.php';

// =====================================
// AUTHENTICATED USER ROUTES
// =====================================
Route::middleware(['auth'])->group(function () {
    // Two-Factor Authentication
    Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::get('/two-factor/verify', [TwoFactorController::class, 'verifyForm'])->name('two-factor.verify-form');
    Route::get('/two-factor/backup-codes', [TwoFactorController::class, 'generateBackupCodes'])->name('two-factor.backup-codes');
    
    // Email-based 2FA routes
    Route::post('/two-factor/send-email-code', [TwoFactorController::class, 'sendEmailCode'])->name('two-factor.send-email-code');
    Route::post('/two-factor/verify-email-code', [TwoFactorController::class, 'verifyEmailCode'])->name('two-factor.verify-email-code');
    Route::post('/two-factor/send-setup-code', [TwoFactorController::class, 'sendSetupCode'])->name('two-factor.send-setup-code');

    // Dashboard redirection based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard');
    
    // GDPR Routes
    Route::prefix('gdpr')->name('gdpr.')->group(function () {
        Route::post('/delete-account', [GDPRController::class, 'deleteAccount'])->name('delete-account');
    });
    
    // Security Routes
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/activity', [SecurityController::class, 'activity'])->name('activity');
    });
    
    // Seats Routes
    Route::prefix('seats')->name('seats.')->group(function () {
        Route::post('/subscribe-alerts', [SeatController::class, 'subscribeAlerts'])->name('subscribe-alerts');
    });
    
    // Referrals Routes
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/stats', [ReferralController::class, 'stats'])->name('stats');
    });
    
    // Booking Routes
    Route::get('/booking/{type}', [OrderFlightController::class, 'showBooking'])->name('booking.show');
    Route::post('/flights/select', [OrderFlightController::class, 'selectFlight'])->name('flights.select');
    Route::post('/hotels/select', [OrderFlightController::class, 'selectHotel'])->name('hotels.select');
    Route::get('/checkout', [OrderFlightController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout/process', [OrderFlightController::class, 'processCheckout'])->name('checkout.process');
    // These routes are defined elsewhere
    // Route::get('/booking/success/{booking}', [OrderFlightController::class, 'bookingSuccess'])->name('booking.success');
    // Route::get('/booking/failure', [OrderFlightController::class, 'bookingFailure'])->name('booking.failure');
    Route::get('/dashboard/booking', [OrderFlightController::class, 'bookingPage'])->name('dashboard.booking');
    Route::get('/booking/confirmation', [OrderFlightController::class, 'confirmationPage'])->name('booking.confirmation');
    // Payment Routes
    Route::post('/payment/mpesa', [PaymentController::class, 'processMpesa'])->name('payment.mpesa');
    Route::post('/payment/bank', [PaymentController::class, 'processBank'])->name('payment.bank');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::post('/payments/jenga/callback', [OrderFlightController::class, 'handleJengaCallback'])->name('payments.jenga.callback');

    Route::get('/payments/mpesa/check-status/{orderReference}', [OrderFlightController::class, 'checkMpesaOrderStatus'])->name('payments.mpesa.check-status');
    // Weather Routes
    Route::get('/weather/current', [WeatherController::class, 'getCurrentWeather'])->name('weather.current');
    Route::get('/weather/forecast', [WeatherController::class, 'getForecast'])->name('weather.forecast');
    Route::get('/weather/coords', [WeatherController::class, 'getWeatherByCoords'])->name('weather.coords');
    Route::get('/weather/popular', [WeatherController::class, 'getPopularCitiesWeather'])->name('weather.popular');
    Route::get('/weather/search', [WeatherController::class, 'searchCities'])->name('weather.search');
    
    // Dashboard Analytics Routes
    Route::get('/dashboard/business-intelligence', [DashboardController::class, 'businessIntelligence'])->name('dashboard.business-intelligence');
    Route::get('/dashboard/business-intelligence/export', [DashboardController::class, 'exportBusinessIntelligence'])->name('dashboard.business-intelligence.export');
    Route::get('/dashboard/analytics/data', [DashboardController::class, 'getAnalyticsData'])->name('dashboard.analytics.data');
    
    // AI Trip Planner Routes
    Route::prefix('api/ai')->group(function () {
        Route::post('/trip-plan', [AITripPlannerController::class, 'generateTripPlan']);
        Route::post('/layover-guide', [AITripPlannerController::class, 'generateLayoverGuide']);
        Route::post('/packing-list', [AITripPlannerController::class, 'generatePackingList']);
        Route::post('/chat', [AIChatbotController::class, 'sendMessage']);
        Route::get('/chat/history', [AIChatbotController::class, 'getChatHistory']);
        Route::post('/scan-passport', [SmartAutoFillController::class, 'scanPassport']);
        Route::post('/booking-assistant', [AIChatbotController::class, 'bookingAssistant']);
    });
    
    // Smart Auto-Fill Routes
    Route::prefix('api/ai')->group(function () {
        Route::get('/passenger-suggestions', [SmartAutoFillController::class, 'getPassengerSuggestions']);
        Route::post('/process-document', [SmartAutoFillController::class, 'processDocumentUpload']);
        Route::post('/save-passenger-profile', [SmartAutoFillController::class, 'savePassengerProfile']);
        Route::get('/smart-recommendations', [SmartAutoFillController::class, 'getSmartRecommendations']);
    });
    
    // User API Routes
    Route::prefix('api')->group(function () {
        Route::get('/user/profile', [UserController::class, 'getProfile']);
        Route::get('/user/previous-booking', [UserController::class, 'getPreviousBooking']);
        Route::post('/booking/save-draft', [BookingController::class, 'saveDraft']);
    });
});

// =====================================
// USER DASHBOARD ROUTES
// =====================================
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard main pages
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [UserDashboardController::class, 'home'])->name('home');
    Route::get('/dashboard/stats', [UserDashboardController::class, 'getDashboardStats'])->name('dashboard.stats');
    Route::get('/bookings', [UserDashboardController::class, 'allBookings'])->name('user.bookings.all');

    // DASHBOARD FEATURE CARDS ROUTES
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/travel-insights', [UserDashboardController::class, 'travelInsights'])->name('travel.insights');
        Route::get('/weather', [UserDashboardController::class, 'getWeatherForDestinations'])->name('weather');
        Route::get('/recommendations', [UserDashboardController::class, 'getAmadeusRecommendations'])->name('recommendations');
        Route::get('/flight-alerts', [UserDashboardController::class, 'getFlightAlerts'])->name('flight.alerts');
        Route::get('/loyalty', [UserDashboardController::class, 'getLoyaltyProgramData'])->name('loyalty');
        Route::get('/calendar', [UserDashboardController::class, 'getTravelCalendarData'])->name('calendar');
        Route::get('/budget', [UserDashboardController::class, 'getBudgetTracker'])->name('budget');
        Route::get('/saved-searches', [UserDashboardController::class, 'getSavedSearches'])->name('saved.searches');
        Route::get('/trip-planner', [UserDashboardController::class, 'getTripPlannerData'])->name('trip.planner');
        Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    });

    // Flights search routes for authenticated users
    Route::get('/flights/search', [UserFlightContolller::class, 'search'])->name('flights.search');
    Route::post('/flights/search', [UserFlightContolller::class, 'processSearch'])->name('flights.search.process');
    Route::get('/flights/results', [UserFlightContolller::class, 'showResults'])->name('flights.results');
    Route::post('/flights/select', [UserFlightContolller::class, 'selectFlight'])->name('flights.select');
    Route::get('/flights/booking', [UserFlightContolller::class, 'showBooking'])->name('flights.booking');

    // BOOKINGS MANAGEMENT SECTION
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/all', [UserDashboardController::class, 'allBookings'])->name('all');
        Route::get('/my', [UserDashboardController::class, 'myBookings'])->name('my');
        Route::get('/show/{id}', [UserDashboardController::class, 'showBooking'])->name('show');
        Route::get('/download/{id}', [UserDashboardController::class, 'downloadTicket'])->name('download');
    });

    // FLIGHTS MANAGEMENT SECTION
    Route::prefix('flights')->name('flights.')->group(function () {
        Route::get('/search/my-flight', [UserDashboardController::class, 'searchmyflight'])->name('search.my');
        Route::get('/my', [UserDashboardController::class, 'flightBookings'])->name('my');
        Route::get('/bookings', [UserDashboardController::class, 'flightBookings'])->name('bookings');

    });

    // HOTELS MANAGEMENT SECTION
    Route::prefix('hotels')->name('hotels.')->group(function () {
        Route::get('/search/my-hotel', [UserDashboardController::class, 'searchmyhotel'])->name('search.my');
                Route::get('/my', [UserDashboardController::class, 'myHotels'])->name('my');

    });

    // ROLES & PERMISSIONS SECTION
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/permissions/all', [UserDashboardController::class, 'rolesPermissionsAll'])->name('permissions.all');
         Route::get('/view/{id}', [UserDashboardController::class, 'viewMyRole'])->name('view');
        Route::get('/all', [UserDashboardController::class, 'viewAllRolesandPermissions'])->name('all');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/my-permissions/{id}', [UserDashboardController::class, 'viewMyPermissions'])->name('my.view');
    });

    // PAYMENTS MANAGEMENT SECTION
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/my-payments', [UserDashboardController::class, 'myPayments'])->name('my');
    });

    // WISHLIST MANAGEMENT SECTION
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/my-wishlists', [UserDashboardController::class, 'mywishlists'])->name('my');
    });

    // ACTIVITY & LOGS SECTION
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/my-activity', [UserDashboardController::class, 'myActivity'])->name('my');
    });

    // PROFILE MANAGEMENT SECTION
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/all', [UserDashboardController::class, 'profileAll'])->name('all');
         Route::get('/my', [UserDashboardController::class, 'myProfile'])->name('my');
        Route::put('/update', [UserDashboardController::class, 'updateMyProfile'])->name('update');
        Route::get('/edit', [UserDashboardController::class, 'editProfile'])->name('edit');
    });

    // NOTIFICATIONS SECTION
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/my-notifications', [UserDashboardController::class, 'myNotifications'])->name('my');
        Route::get('/clear', [UserDashboardController::class, 'clearNotifications'])->name('clear');
        Route::post('/mark-all-read', [UserDashboardController::class, 'markAllNotificationsRead'])->name('mark-all-read');
        Route::get('/count', [UserDashboardController::class, 'getNotificationCount'])->name('count');
    });

    // REFERRALS SECTION
    Route::get('/referrals', [UserDashboardController::class, 'referrals'])->name('referrals');

    // CALENDAR SECTION
    Route::get('/calendar', [UserDashboardController::class, 'calendar'])->name('calendar');

    // REWARDS & LOYALTIES SECTION
    Route::get('/rewards-loyalties', [UserDashboardController::class, 'rewardsLoyalties'])->name('rewards.loyalties');

    // PERSONAL SECTION
    Route::prefix('my')->name('my.')->group(function () {
        Route::put('/profile', [UserDashboardController::class, 'updateMyProfile'])->name('profile.update');
    });

    // SETTINGS SECTION
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/account', [UserDashboardController::class, 'accountSettings'])->name('account');
        Route::get('/change-password', [UserDashboardController::class, 'changePassword'])->name('change-password');
        Route::post('/update-password', [UserDashboardController::class, 'updatePassword'])->name('update-password');
        Route::get('/terms-conditions', [UserDashboardController::class, 'termsConditions'])->name('terms.conditions');
    });

    // SEARCH
    Route::get('/search', [UserDashboardController::class, 'search'])->name('search');

    // SUPPORT (add this route for user.support)
    Route::get('/support', function() {
        return view('pages.support');
    })->name('support');
});

// =====================================
// ADMIN DASHBOARD ROUTES
// =====================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard main pages
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [AdminDashboardController::class, 'home'])->name('home');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'getDashboardStats'])->name('dashboard.stats');

    // USERS MANAGEMENT SECTION
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'allUsers'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'admins'])->name('admin');
        Route::get('/user', [AdminDashboardController::class, 'users'])->name('user');
        Route::get('/', [AdminDashboardController::class, 'users'])->name('index');
    });
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/admins', [AdminDashboardController::class, 'admins'])->name('admins'); // <-- Add this line

    // BOOKINGS MANAGEMENT SECTION
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'bookingsAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'bookingsAdmin'])->name('admin');
        Route::get('/user', [AdminDashboardController::class, 'bookingsUser'])->name('user');
    });

    // FLIGHTS MANAGEMENT SECTION
    Route::prefix('flights')->name('flights.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'flightsAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'flightsAdmin'])->name('admin');
        Route::get('/users', [AdminDashboardController::class, 'flightsUsers'])->name('users');
        Route::get('/my', [AdminDashboardController::class, 'myFlights'])->name('my');
        Route::get('/search/all', [AdminDashboardController::class, 'searchFlightAll'])->name('search.all');
        Route::get('/search/my-flight', [AdminDashboardController::class, 'searchmyflight'])->name('search.my');
        Route::get('/search/admin', [AdminDashboardController::class, 'searchFlightAdmin'])->name('search.admin');
        Route::get('/search/users', [AdminDashboardController::class, 'searchFlightUsers'])->name('search.users');
        Route::get('/completed', [AdminDashboardController::class, 'completedFlights'])->name('completed');
        Route::get('/failed', [AdminDashboardController::class, 'failedFlights'])->name('failed');
        Route::get('/processing', [AdminDashboardController::class, 'processingFlights'])->name('processing');
    });
    Route::get('/search/flight/all', [AdminDashboardController::class, 'searchFlightAll'])->name('search.flight.all');

    // HOTELS MANAGEMENT SECTION
    Route::prefix('hotels')->name('hotels.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'hotelsAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'hotelsAdmin'])->name('admin');
        Route::get('/users', [AdminDashboardController::class, 'hotelsUsers'])->name('users');
        Route::get('/my', [AdminDashboardController::class, 'myHotels'])->name('my');
        Route::get('/search/all', [AdminDashboardController::class, 'searchHotelsAll'])->name('search.all');
        Route::get('/search/my-hotel', [AdminDashboardController::class, 'searchmyhotel'])->name('search.my');
        Route::get('/search/admin', [AdminDashboardController::class, 'searchHotelsAdmin'])->name('search.admin');
        Route::get('/search/users', [AdminDashboardController::class, 'searchHotelsUsers'])->name('search.users');
        Route::get('/completed', [AdminDashboardController::class, 'completedHotels'])->name('completed');
        Route::get('/failed', [AdminDashboardController::class, 'failedHotels'])->name('failed');
        Route::get('/processing', [AdminDashboardController::class, 'processingHotels'])->name('processing');
    });
    Route::get('/search/hotels/all', [AdminDashboardController::class, 'searchHotelsAll'])->name('search.hotels.all');

    // FLIGHT SEARCH FORM ROUTES
    Route::prefix('flights/search')->name('flights.search.')->group(function () {
        Route::get('/admin', [AdminDashboardController::class, 'searchFormAdmin'])->name('admin');
        Route::get('/users', [AdminDashboardController::class, 'searchFormUsers'])->name('users');
        Route::get('/all', [AdminDashboardController::class, 'searchFormAll'])->name('all');
    });

    // HOTEL SEARCH FORM ROUTES
    Route::prefix('hotels/search')->name('hotels.search.')->group(function () {
        Route::get('/admin', [AdminDashboardController::class, 'hotelSearchAdmin'])->name('admin');
        Route::get('/users', [AdminDashboardController::class, 'hotelSearchUsers'])->name('users');
        Route::get('/all', [AdminDashboardController::class, 'hotelSearchAll'])->name('all');
    });

    // ROLES & PERMISSIONS SECTION
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'roles'])->name('index');
        Route::get('/add', [AdminDashboardController::class, 'addRole'])->name('add');
        Route::get('/edit/{id}', [AdminDashboardController::class, 'editRole'])->name('edit');
        Route::get('/view/{id}', [AdminDashboardController::class, 'viewRole'])->name('view');
        Route::get('/my/{id}', [AdminDashboardController::class, 'viewMyRole'])->name('my');
        Route::get('/all', [AdminDashboardController::class, 'viewAllRolesandPermissions'])->name('all');
        Route::get('/permissions/all', [AdminDashboardController::class, 'rolesPermissionsAll'])->name('permissions.all');
    });
    Route::get('/roles/permissions/all', [AdminDashboardController::class, 'rolesPermissionsAll'])->name('roles.permissions.all');

    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'permissions'])->name('index');
        Route::get('/add', [AdminDashboardController::class, 'addPermission'])->name('add');
        Route::get('/edit/{id}', [AdminDashboardController::class, 'editPermission'])->name('edit');
        Route::get('/view/{id}', [AdminDashboardController::class, 'viewPermission'])->name('view');
        Route::get('/my/{id}', [AdminDashboardController::class, 'viewMyPermissions'])->name('my');
        Route::get('/my-permissions/{id}', [AdminDashboardController::class, 'viewMyPermissions'])->name('my.view');
    });

    // PAYMENTS MANAGEMENT SECTION
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'paymentsAll'])->name('all');
        Route::get('/banks', [AdminDashboardController::class, 'paymentsBanks'])->name('banks');
        Route::get('/mpesa', [AdminDashboardController::class, 'paymentsMpesa'])->name('mpesa');
        Route::get('/my', [AdminDashboardController::class, 'myPayments'])->name('my');
    });

    // WISHLIST MANAGEMENT SECTION
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'wishlistAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'wishlistAdmin'])->name('admin');
        Route::get('/user', [AdminDashboardController::class, 'wishlistUser'])->name('user');
        Route::get('/my', [AdminDashboardController::class, 'mywishlists'])->name('my');
    });

    // ACTIVITY & LOGS SECTION
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'activityAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'activityAdmin'])->name('admin');
        Route::get('/user', [AdminDashboardController::class, 'activityUser'])->name('user');
        Route::get('/my', [AdminDashboardController::class, 'myActivity'])->name('my');
    });

    // PROFILE MANAGEMENT SECTION
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'profileAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'profileAdmin'])->name('admin');
        Route::get('/user', [AdminDashboardController::class, 'profileUser'])->name('user');
        Route::get('/my', [AdminDashboardController::class, 'myCurrentProfile'])->name('my');
    });

    // NOTIFICATIONS SECTION
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/all', [AdminDashboardController::class, 'notificationsAll'])->name('all');
        Route::get('/admin', [AdminDashboardController::class, 'notificationsAdmin'])->name('admin');
        Route::get('/user', [AdminDashboardController::class, 'notificationsUser'])->name('user');
        Route::get('/my', [AdminDashboardController::class, 'myNotifications'])->name('my');
        Route::get('/clear', [AdminDashboardController::class, 'clearNotifications'])->name('clear');
        Route::post('/mark-all-read', [AdminDashboardController::class, 'markAllNotificationsRead'])->name('mark-all-read');
        Route::get('/count', [AdminDashboardController::class, 'getNotificationCount'])->name('count');
    });

    // REFERRALS SECTION
    Route::get('/referrals', [AdminDashboardController::class, 'referrals'])->name('referrals');

    // CALENDAR SECTION
    Route::get('/calendar', [AdminDashboardController::class, 'calendar'])->name('calendar');

    // REWARDS & LOYALTIES SECTION
    Route::get('/rewards-loyalties', [AdminDashboardController::class, 'rewardsLoyalties'])->name('rewards.loyalties');

    // PERSONAL SECTION
    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/profile', [AdminDashboardController::class, 'myProfile'])->name('profile');
        Route::put('/profile', [AdminDashboardController::class, 'updateMyProfile'])->name('profile.update');
    });

    // SETTINGS SECTION
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/terms-conditions', [AdminDashboardController::class, 'termsConditions'])->name('terms.conditions');
        Route::get('/account', [AdminDashboardController::class, 'accountSettings'])->name('account');
        Route::get('/change-password', [AdminDashboardController::class, 'changePassword'])->name('change.password');
        Route::get('/connections', [AdminDashboardController::class, 'connections'])->name('connections');
        Route::post('/update-password', [AdminDashboardController::class, 'updatePassword'])->name('update-password');
        Route::get('/privacy-policy', [AdminDashboardController::class, 'privacyPolicy'])->name('privacy.policy');
    });

    // Support route
    Route::get('/support', [AdminDashboardController::class, 'support'])->name('support');

    // =====================================
    // ADVANCED ADMIN DASHBOARD FEATURES
    // =====================================
    
    // Real-Time Dashboard Overview
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/real-time', [AdminDashboardController::class, 'realTimeDashboard'])->name('real.time');
        Route::get('/real-time-data', [AdminDashboardController::class, 'getRealTimeData'])->name('real.time.data');
        Route::get('/analytics', [AdminDashboardController::class, 'analyticsOverview'])->name('analytics');
        Route::get('/performance', [AdminDashboardController::class, 'performanceMetrics'])->name('performance');
        Route::get('/api-health', [AdminDashboardController::class, 'apiHealthStatus'])->name('api.health');
        Route::get('/conversion-funnel', [AdminDashboardController::class, 'conversionFunnel'])->name('conversion.funnel');
    });

    // Advanced User Management System
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/management', [AdminDashboardController::class, 'advancedUserManagement'])->name('management');
        Route::post('/bulk-operations', [AdminDashboardController::class, 'bulkUserOperations'])->name('bulk.operations');
        Route::get('/activity-tracking', [AdminDashboardController::class, 'userActivityTracking'])->name('activity.tracking');
        Route::get('/verification-queue', [AdminDashboardController::class, 'userVerificationQueue'])->name('verification.queue');
        Route::post('/impersonate/{user}', [AdminDashboardController::class, 'impersonateUser'])->name('impersonate');
        Route::post('/stop-impersonation', [AdminDashboardController::class, 'stopImpersonation'])->name('stop.impersonation');
        Route::get('/export', [AdminDashboardController::class, 'exportUsers'])->name('export');
        Route::post('/import', [AdminDashboardController::class, 'importUsers'])->name('import');
    });

    // Comprehensive Booking Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/management', [AdminDashboardController::class, 'comprehensiveBookingManagement'])->name('management');
        Route::get('/real-time-monitor', [AdminDashboardController::class, 'realTimeBookingMonitor'])->name('real.time.monitor');
        Route::post('/modify/{booking}', [AdminDashboardController::class, 'modifyBooking'])->name('modify');
        Route::post('/cancel/{booking}', [AdminDashboardController::class, 'cancelBooking'])->name('cancel');
        Route::get('/failed-investigation', [AdminDashboardController::class, 'failedBookingInvestigation'])->name('failed.investigation');
        Route::get('/analytics', [AdminDashboardController::class, 'bookingAnalytics'])->name('analytics');
        Route::post('/book-for-user', [AdminDashboardController::class, 'bookForUser'])->name('book.for.user');
        Route::get('/payment-reconciliation', [AdminDashboardController::class, 'paymentReconciliation'])->name('payment.reconciliation');
    });

    // Intelligent Notification Center
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/center', [AdminDashboardController::class, 'notificationCenter'])->name('center');
        Route::post('/broadcast', [AdminDashboardController::class, 'broadcastMessage'])->name('broadcast');
        Route::get('/email-campaigns', [AdminDashboardController::class, 'emailCampaigns'])->name('email.campaigns');
        Route::post('/sms-notification', [AdminDashboardController::class, 'sendSmsNotification'])->name('sms.send');
        Route::get('/push-controls', [AdminDashboardController::class, 'pushNotificationControls'])->name('push.controls');
        Route::get('/analytics', [AdminDashboardController::class, 'notificationAnalytics'])->name('analytics');
        Route::get('/delivery-tracking', [AdminDashboardController::class, 'deliveryTracking'])->name('delivery.tracking');
        Route::post('/preferences/{user}', [AdminDashboardController::class, 'updateNotificationPreferences'])->name('preferences.update');
    });

    // Advanced Search & Booking Tools
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/advanced', [AdminDashboardController::class, 'advancedSearchTools'])->name('advanced');
        Route::get('/priority-flight-search', [AdminDashboardController::class, 'priorityFlightSearch'])->name('priority.flight');
        Route::get('/bulk-booking', [AdminDashboardController::class, 'bulkBookingManagement'])->name('bulk.booking');
        Route::get('/corporate-tools', [AdminDashboardController::class, 'corporateBookingTools'])->name('corporate.tools');
        Route::get('/price-monitoring', [AdminDashboardController::class, 'priceMonitoring'])->name('price.monitoring');
        Route::get('/booking-queue', [AdminDashboardController::class, 'bookingQueueManagement'])->name('booking.queue');
        Route::post('/automated-rebooking', [AdminDashboardController::class, 'automatedRebooking'])->name('automated.rebooking');
    });

    // AI-Powered Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/ai', [AdminDashboardController::class, 'aiPoweredAnalytics'])->name('ai');
        Route::get('/predictive-trends', [AdminDashboardController::class, 'predictiveBookingTrends'])->name('predictive.trends');
        Route::get('/behavior-patterns', [AdminDashboardController::class, 'customerBehaviorPatterns'])->name('behavior.patterns');
        Route::get('/revenue-optimization', [AdminDashboardController::class, 'revenueOptimization'])->name('revenue.optimization');
        Route::get('/fraud-detection', [AdminDashboardController::class, 'fraudDetection'])->name('fraud.detection');
        Route::get('/demand-forecasting', [AdminDashboardController::class, 'demandForecasting'])->name('demand.forecasting');
        Route::get('/churn-prediction', [AdminDashboardController::class, 'churnPrediction'])->name('churn.prediction');
    });

    // Financial Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/management', [AdminDashboardController::class, 'financialManagement'])->name('management');
        Route::get('/revenue-analytics', [AdminDashboardController::class, 'revenueAnalytics'])->name('revenue.analytics');
        Route::get('/payment-gateway', [AdminDashboardController::class, 'paymentGatewayManagement'])->name('payment.gateway');
        Route::get('/refund-processing', [AdminDashboardController::class, 'refundProcessing'])->name('refund.processing');
        Route::get('/commission-tracking', [AdminDashboardController::class, 'commissionTracking'])->name('commission.tracking');
        Route::get('/tax-reporting', [AdminDashboardController::class, 'taxReporting'])->name('tax.reporting');
        Route::get('/financial-forecasting', [AdminDashboardController::class, 'financialForecasting'])->name('financial.forecasting');
        Route::get('/audit-trail', [AdminDashboardController::class, 'auditTrail'])->name('audit.trail');
    });

    // System Health & Performance Monitoring
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/health', [AdminDashboardController::class, 'systemHealth'])->name('health');
        Route::get('/performance', [AdminDashboardController::class, 'performanceMonitoring'])->name('performance');
        Route::get('/database-optimization', [AdminDashboardController::class, 'databaseOptimization'])->name('database.optimization');
        Route::get('/api-monitoring', [AdminDashboardController::class, 'apiMonitoring'])->name('api.monitoring');
        Route::get('/security-threats', [AdminDashboardController::class, 'securityThreats'])->name('security.threats');
        Route::get('/backup-recovery', [AdminDashboardController::class, 'backupRecovery'])->name('backup.recovery');
        Route::get('/system-updates', [AdminDashboardController::class, 'systemUpdates'])->name('system.updates');
    });

    // Customer Support Integration
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/hub', [AdminDashboardController::class, 'supportHub'])->name('hub');
        Route::get('/helpdesk', [AdminDashboardController::class, 'integratedHelpdesk'])->name('helpdesk');
        Route::get('/live-chat', [AdminDashboardController::class, 'liveChatMonitoring'])->name('live.chat');
        Route::get('/tickets', [AdminDashboardController::class, 'supportTickets'])->name('tickets');
        Route::get('/feedback-analysis', [AdminDashboardController::class, 'customerFeedbackAnalysis'])->name('feedback.analysis');
        Route::get('/faq-management', [AdminDashboardController::class, 'faqManagement'])->name('faq.management');
        Route::get('/knowledge-base', [AdminDashboardController::class, 'knowledgeBase'])->name('knowledge.base');
        Route::get('/agent-performance', [AdminDashboardController::class, 'supportAgentPerformance'])->name('agent.performance');
    });

    // Security & Compliance
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/compliance', [AdminDashboardController::class, 'securityCompliance'])->name('compliance');
        Route::get('/access-control', [AdminDashboardController::class, 'accessControl'])->name('access.control');
        Route::get('/session-management', [AdminDashboardController::class, 'sessionManagement'])->name('session.management');
        Route::get('/audit-logs', [AdminDashboardController::class, 'securityAuditLogs'])->name('audit.logs');
        Route::get('/threat-detection', [AdminDashboardController::class, 'threatDetection'])->name('threat.detection');
        Route::get('/data-encryption', [AdminDashboardController::class, 'dataEncryption'])->name('data.encryption');
        Route::get('/gdpr-compliance', [AdminDashboardController::class, 'gdprCompliance'])->name('gdpr.compliance');
        Route::get('/data-retention', [AdminDashboardController::class, 'dataRetention'])->name('data.retention');
    });

    // Content Management System
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/management', [AdminDashboardController::class, 'contentManagement'])->name('management');
        Route::get('/landing-pages', [AdminDashboardController::class, 'landingPageCustomization'])->name('landing.pages');
        Route::get('/promotional-banners', [AdminDashboardController::class, 'promotionalBanners'])->name('promotional.banners');
        Route::get('/deals-discounts', [AdminDashboardController::class, 'dealsDiscounts'])->name('deals.discounts');
        Route::get('/content-scheduling', [AdminDashboardController::class, 'contentScheduling'])->name('content.scheduling');
        Route::get('/ab-testing', [AdminDashboardController::class, 'abTesting'])->name('ab.testing');
        Route::get('/seo-optimization', [AdminDashboardController::class, 'seoOptimization'])->name('seo.optimization');
        Route::get('/multi-language', [AdminDashboardController::class, 'multiLanguageContent'])->name('multi.language');
    });

    // Marketing Automation Hub
    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::get('/automation', [AdminDashboardController::class, 'marketingAutomation'])->name('automation');
        Route::get('/email-campaigns', [AdminDashboardController::class, 'emailMarketingCampaigns'])->name('email.campaigns');
        Route::get('/social-media', [AdminDashboardController::class, 'socialMediaIntegration'])->name('social.media');
        Route::get('/influencer-campaigns', [AdminDashboardController::class, 'influencerCampaigns'])->name('influencer.campaigns');
        Route::get('/affiliate-program', [AdminDashboardController::class, 'affiliateProgram'])->name('affiliate.program');
        Route::get('/customer-referrals', [AdminDashboardController::class, 'customerReferrals'])->name('customer.referrals');
        Route::get('/loyalty-administration', [AdminDashboardController::class, 'loyaltyAdministration'])->name('loyalty.administration');
        Route::get('/cross-selling', [AdminDashboardController::class, 'crossSellingTools'])->name('cross.selling');
    });

    // Partner & Vendor Management
    Route::prefix('partners')->name('partners.')->group(function () {
        Route::get('/management', [AdminDashboardController::class, 'partnerManagement'])->name('management');
        Route::get('/airline-partners', [AdminDashboardController::class, 'airlinePartners'])->name('airline.partners');
        Route::get('/hotel-partners', [AdminDashboardController::class, 'hotelPartners'])->name('hotel.partners');
        Route::get('/commission-tracking', [AdminDashboardController::class, 'partnerCommissionTracking'])->name('commission.tracking');
        Route::get('/contract-management', [AdminDashboardController::class, 'contractManagement'])->name('contract.management');
        Route::get('/performance-monitoring', [AdminDashboardController::class, 'partnerPerformance'])->name('performance.monitoring');
        Route::get('/api-integration', [AdminDashboardController::class, 'apiIntegrationManagement'])->name('api.integration');
        Route::get('/vendor-onboarding', [AdminDashboardController::class, 'vendorOnboarding'])->name('vendor.onboarding');
    });
});

// =====================================
// SUPERADMIN DASHBOARD ROUTES
// =====================================
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard main pages
    Route::get('/', [SuperadminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [SuperadminDashboardController::class, 'home'])->name('home');
    Route::get('/dashboard/stats', [SuperadminDashboardController::class, 'getDashboardStats'])->name('dashboard.stats');
    
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function() {
        Route::get('/', [App\Http\Controllers\Dashboard\UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Dashboard\UserManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Dashboard\UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\Dashboard\UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\Dashboard\UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\Dashboard\UserManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/restore', [App\Http\Controllers\Dashboard\UserManagementController::class, 'restore'])->name('restore');
    });
    
    // Flight search for superadmin
    Route::get('/flights/search', [FlightSearchController::class, 'superadminSearch'])->name('flights.search');
    Route::post('/flights/search', [FlightSearchController::class, 'processSuperadminSearch'])->name('flights.search.process');
    Route::get('/flights/results', [FlightSearchController::class, 'showSuperadminResults'])->name('flights.results');
    
    // Payment management for superadmin
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/flights', [PaymentController::class, 'flightPayments'])->name('payments.flights');
    Route::get('/payments/hotels', [PaymentController::class, 'hotelPayments'])->name('payments.hotels');
    Route::get('/payments/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/process', [PaymentController::class, 'processPayment'])->name('payments.process');
    Route::get('/payments/{payment}/status', [PaymentController::class, 'paymentStatus'])->name('payments.status');
    Route::get('/payments/real-time-updates', [PaymentController::class, 'realTimeUpdates'])->name('payments.real-time');
    Route::get('/payments/export', [PaymentController::class, 'exportPayments'])->name('payments.export');
    Route::get('/payments/restore-session', [PaymentController::class, 'restoreSession'])->name('payments.restore-session');
    Route::post('/payments/{payment}/refresh-status', [PaymentController::class, 'refreshPaymentStatus'])->name('payments.refresh-status');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    
    // Impersonation routes
    Route::post('/impersonate/{user}', [ImpersonationController::class, 'impersonate'])->name('impersonate');
    Route::post('/stop-impersonation', [ImpersonationController::class, 'stopImpersonation'])->name('stop-impersonation');
    
    // USERS MANAGEMENT SECTION
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/superadmins', [SuperadminDashboardController::class, 'superAdmins'])->name('superadmins');
        Route::get('/admins', [SuperadminDashboardController::class, 'admins'])->name('admins');
        Route::get('/users', [SuperadminDashboardController::class, 'users'])->name('users');
        Route::get('/all', [SuperadminDashboardController::class, 'allUsers'])->name('all');
    });

    // BOOKINGS MANAGEMENT SECTION
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/my', [SuperadminDashboardController::class, 'myBookings'])->name('my');
        Route::get('/superadmin', [SuperadminDashboardController::class, 'bookingsSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'bookingsAdmin'])->name('admin');
        Route::get('/user', [SuperadminDashboardController::class, 'bookingsUser'])->name('user');
        Route::get('/all', [SuperadminDashboardController::class, 'bookingsAll'])->name('all');
    });

    // FLIGHTS MANAGEMENT SECTION
    Route::prefix('flights')->name('flights.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'flightsSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'flightsAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'flightsUsers'])->name('users');
        Route::get('/my', [SuperadminDashboardController::class, 'myFlights'])->name('my');
        Route::get('/all', [SuperadminDashboardController::class, 'flightsAll'])->name('all');
    });

    // HOTELS MANAGEMENT SECTION
    Route::prefix('hotels')->name('hotels.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'hotelsSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'hotelsAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'hotelsUsers'])->name('users');
        Route::get('/my', [SuperadminDashboardController::class, 'myHotels'])->name('my');
        Route::get('/all', [SuperadminDashboardController::class, 'hotelsAll'])->name('all');
    });

    // ACTIVITIES MANAGEMENT SECTION
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'activitySuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'activityAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'activityUser'])->name('users');
        Route::get('/my', [SuperadminDashboardController::class, 'myActivity'])->name('my');
        Route::get('/all', [SuperadminDashboardController::class, 'activityAll'])->name('all');
    });

    // CALENDAR MANAGEMENT SECTION
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [SuperadminDashboardController::class, 'calendar'])->name('index');
    });

    // NOTIFICATIONS MANAGEMENT SECTION
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'notificationsSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'notificationsAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'notificationsUser'])->name('users');
        Route::get('/my', [SuperadminDashboardController::class, 'myNotifications'])->name('my');
        Route::get('/all', [SuperadminDashboardController::class, 'notificationsAll'])->name('all');
        Route::post('/mark-all-read', [SuperadminDashboardController::class, 'markAllNotificationsRead'])->name('mark-all-read');
        Route::get('/count', [SuperadminDashboardController::class, 'getNotificationCount'])->name('count');
        Route::get('/', [SuperadminDashboardController::class, 'notificationsIndex'])->name('index');
    });

    // WISHLIST MANAGEMENT SECTION
    Route::prefix('wishlists')->name('wishlists.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'wishlistSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'wishlistAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'wishlistUser'])->name('users');
        Route::get('/my', [SuperadminDashboardController::class, 'mywishlists'])->name('my');
        Route::get('/all', [SuperadminDashboardController::class, 'wishlistAll'])->name('all');
    });

    // PROFILE MANAGEMENT SECTION
    Route::prefix('profiles')->name('profiles.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'profileSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'profileAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'profileUser'])->name('users');
        Route::get('/my', [SuperadminDashboardController::class, 'myCurrentProfile'])->name('my');
        Route::get('/all', [SuperadminDashboardController::class, 'profileAll'])->name('all');
    });

     // Roles & Permissions Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/add', [SuperadminDashboardController::class, 'addRole'])->name('add');
        Route::get('/', [SuperadminDashboardController::class, 'roles'])->name('index');
    });
    
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/add', [SuperadminDashboardController::class, 'addPermission'])->name('add');
        Route::get('/', [SuperadminDashboardController::class, 'permissions'])->name('index');
    });
    
    // Payment Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/my', [SuperadminDashboardController::class, 'myPayments'])->name('my');
        Route::get('/banks', [SuperadminDashboardController::class, 'paymentsBanks'])->name('banks');
        Route::get('/mpesa', [SuperadminDashboardController::class, 'paymentsMpesa'])->name('mpesa');
        Route::get('/all', [SuperadminDashboardController::class, 'paymentsAll'])->name('all');
    });

     // Real-Time Monitoring Routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/real-time', [SuperadminDashboardController::class, 'realTimeDashboard'])->name('real-time');
        Route::get('/real-time-data', [SuperadminDashboardController::class, 'getRealTimeData'])->name('real-time-data');
    });
    
    // Advanced User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/create', [SuperadminDashboardController::class, 'createUser'])->name('create');
        Route::post('/store', [SuperadminDashboardController::class, 'storeUser'])->name('store');
        Route::get('/advanced-management', [SuperadminDashboardController::class, 'advancedUserManagement'])->name('advanced-management');
        Route::get('/activity-tracking', [SuperadminDashboardController::class, 'userActivityTracking'])->name('activity-tracking');
        Route::get('/session-management', [SuperadminDashboardController::class, 'sessionManagement'])->name('session-management');
        Route::get('/bulk-operations', [SuperadminDashboardController::class, 'bulkOperations'])->name('bulk-operations');
        Route::post('/bulk-actions', [SuperadminDashboardController::class, 'performBulkActions'])->name('bulk-actions');
        Route::post('/suspend/{user}', [SuperadminDashboardController::class, 'suspendUser'])->name('suspend');
        Route::post('/activate/{user}', [SuperadminDashboardController::class, 'activateUser'])->name('activate');
        Route::post('/reset-password/{user}', [SuperadminDashboardController::class, 'resetUserPassword'])->name('reset-password');
    });
    
    // Proxy Booking Routes
    Route::prefix('proxy-booking')->name('proxy-booking.')->group(function () {
        Route::get('/flights', [SuperadminDashboardController::class, 'proxyFlightBooking'])->name('flights');
        Route::get('/hotels', [SuperadminDashboardController::class, 'proxyHotelBooking'])->name('hotels');
        Route::get('/tours', [SuperadminDashboardController::class, 'proxyTourBooking'])->name('tours');
        Route::get('/manage', [SuperadminDashboardController::class, 'manageProxyBookings'])->name('manage');
        Route::post('/book-flight', [SuperadminDashboardController::class, 'processProxyFlightBooking'])->name('book-flight');
        Route::post('/book-hotel', [SuperadminDashboardController::class, 'processProxyHotelBooking'])->name('book-hotel');
        Route::post('/book-tour', [SuperadminDashboardController::class, 'processProxyTourBooking'])->name('book-tour');
        Route::post('/modify/{booking}', [SuperadminDashboardController::class, 'modifyProxyBooking'])->name('modify');
        Route::post('/cancel/{booking}', [SuperadminDashboardController::class, 'cancelProxyBooking'])->name('cancel');
    });
    
    // Advanced Analytics Routes
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/revenue', [SuperadminDashboardController::class, 'revenueAnalytics'])->name('revenue');
        Route::get('/user-metrics', [SuperadminDashboardController::class, 'userAcquisitionMetrics'])->name('user-metrics');
        Route::get('/conversion', [SuperadminDashboardController::class, 'conversionRateAnalytics'])->name('conversion');
        Route::get('/conversion-funnel', [SuperadminDashboardController::class, 'conversionFunnel'])->name('conversion-funnel');
        Route::get('/seasonal-trends', [SuperadminDashboardController::class, 'seasonalTrends'])->name('seasonal-trends');
        Route::get('/export-data', [SuperadminDashboardController::class, 'exportAnalyticsData'])->name('export-data');
    });
    
    // System Monitoring Routes
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/api-health', [SuperadminDashboardController::class, 'apiHealthMonitor'])->name('api-health');
        Route::get('/performance', [SuperadminDashboardController::class, 'systemPerformance'])->name('performance');
        Route::get('/maintenance', [SuperadminDashboardController::class, 'maintenanceMode'])->name('maintenance');
        Route::post('/toggle-maintenance', [SuperadminDashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');
    });
    
    // Financial Management Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/gateway-config', [SuperadminDashboardController::class, 'paymentGatewayConfig'])->name('gateway-config');
        Route::post('/update-gateway', [SuperadminDashboardController::class, 'updatePaymentGateway'])->name('update-gateway');
        Route::get('/refund-processing', [SuperadminDashboardController::class, 'refundProcessing'])->name('refund-processing');
        Route::post('/process-refund/{payment}', [SuperadminDashboardController::class, 'processRefund'])->name('process-refund');
        Route::get('/commission-tracking', [SuperadminDashboardController::class, 'commissionTracking'])->name('commission-tracking');
        Route::get('/revenue-reports', [SuperadminDashboardController::class, 'revenueReports'])->name('revenue-reports');
        Route::get('/export-financial-data', [SuperadminDashboardController::class, 'exportFinancialData'])->name('export-financial-data');
    });
    
    // Content Management Routes
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/offers', [SuperadminDashboardController::class, 'manageOffers'])->name('offers');
        Route::post('/offers/store', [SuperadminDashboardController::class, 'storeOffer'])->name('offers.store');
        Route::put('/offers/{offer}', [SuperadminDashboardController::class, 'updateOffer'])->name('offers.update');
        Route::delete('/offers/{offer}', [SuperadminDashboardController::class, 'deleteOffer'])->name('offers.delete');
        Route::get('/destinations', [SuperadminDashboardController::class, 'manageFeaturedDestinations'])->name('destinations');
        Route::get('/email-templates', [SuperadminDashboardController::class, 'manageEmailTemplates'])->name('email-templates');
        Route::get('/policies', [SuperadminDashboardController::class, 'managePolicies'])->name('policies');
    });
    
    // Communication Center Routes
    Route::prefix('communications')->name('communications.')->group(function () {
        Route::get('/broadcast', [SuperadminDashboardController::class, 'broadcastCenter'])->name('broadcast');
        Route::post('/send-broadcast', [SuperadminDashboardController::class, 'sendBroadcast'])->name('send-broadcast');
        Route::get('/email-campaigns', [SuperadminDashboardController::class, 'emailCampaigns'])->name('email-campaigns');
        Route::post('/create-campaign', [SuperadminDashboardController::class, 'createEmailCampaign'])->name('create-campaign');
        Route::get('/sms', [SuperadminDashboardController::class, 'smsCenter'])->name('sms');
        Route::post('/send-sms', [SuperadminDashboardController::class, 'sendSms'])->name('send-sms');
        Route::get('/support-tickets', [SuperadminDashboardController::class, 'supportTickets'])->name('support-tickets');
    });
    
    // Security & Compliance Routes
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/two-factor', [SuperadminDashboardController::class, 'twoFactorManagement'])->name('two-factor');
        Route::post('/toggle-2fa/{user}', [SuperadminDashboardController::class, 'toggle2FA'])->name('toggle-2fa');
        Route::get('/ip-restrictions', [SuperadminDashboardController::class, 'ipRestrictions'])->name('ip-restrictions');
        Route::post('/add-ip-restriction', [SuperadminDashboardController::class, 'addIpRestriction'])->name('add-ip-restriction');
        Route::get('/audit-logs', [SuperadminDashboardController::class, 'securityAuditLogs'])->name('audit-logs');
        Route::get('/data-retention', [SuperadminDashboardController::class, 'dataRetentionPolicy'])->name('data-retention');
    });
    
    // Advanced Search & Reports
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/advanced', [SuperadminDashboardController::class, 'advancedSearch'])->name('advanced');
        Route::post('/execute', [SuperadminDashboardController::class, 'executeAdvancedSearch'])->name('execute');
        Route::get('/saved-searches', [SuperadminDashboardController::class, 'savedSearches'])->name('saved-searches');
        Route::post('/save-search', [SuperadminDashboardController::class, 'saveSearch'])->name('save-search');
    });
    
    // Notification Center Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/center', [SuperadminDashboardController::class, 'notificationCenter'])->name('center');
        Route::get('/real-time-alerts', [SuperadminDashboardController::class, 'realTimeAlerts'])->name('real-time-alerts');
        Route::post('/mark-all-read', [SuperadminDashboardController::class, 'markAllNotificationsRead'])->name('mark-all-read');
        Route::get('/clear-all', [SuperadminDashboardController::class, 'clearAllNotifications'])->name('clear-all');
    });
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/account', [SuperadminDashboardController::class, 'accountSettings'])->name('account');
        Route::get('/change-password', [SuperadminDashboardController::class, 'changePassword'])->name('change.password');
        Route::get('/connections', [SuperadminDashboardController::class, 'connections'])->name('connections');
        Route::get('/privacy-policy', [SuperadminDashboardController::class, 'privacyPolicy'])->name('privacy.policy');
        Route::get('/terms-conditions', [SuperadminDashboardController::class, 'termsConditions'])->name('terms.conditions');
    });
    
    // Flight Search Form Routes
    Route::prefix('flights/search-form')->name('flights.search-form.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'searchFormSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'searchFormAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'searchFormUsers'])->name('users');
    });
    
    // Hotel Search Routes
    Route::prefix('hotels/search')->name('hotels.search.')->group(function () {
        Route::get('/superadmin', [SuperadminDashboardController::class, 'hotelSearchSuperadmin'])->name('superadmin');
        Route::get('/admin', [SuperadminDashboardController::class, 'hotelSearchAdmin'])->name('admin');
        Route::get('/users', [SuperadminDashboardController::class, 'hotelSearchUsers'])->name('users');
        Route::get('/all', [SuperadminDashboardController::class, 'hotelSearchAll'])->name('all');
    });

    // REWARDS MANAGEMENT SECTION
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [SuperadminDashboardController::class, 'rewardsLoyalties'])->name('index');
    });

    // REFERRALS MANAGEMENT SECTION
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [SuperadminDashboardController::class, 'referrals'])->name('index');
    });

        Route::get('/bookings/my', [SuperadminDashboardController::class, 'myBookings'])->name('bookings.my');
       Route::get('/flights/my', [SuperadminDashboardController::class, 'myFlights'])->name('flights.my');
    Route::get('/hotels/my', [SuperadminDashboardController::class, 'myHotels'])->name('hotels.my');
    
    // Profile Routes
    Route::get('/my/profile', [SuperadminDashboardController::class, 'myProfile'])->name('my.profile');
    
    // Rewards & Loyalties
    Route::get('/rewards-loyalties', [SuperadminDashboardController::class, 'rewardsLoyalties'])->name('rewards.index');
    
    // Additional Missing Routes for Complete Functionality
    Route::get('/calendar', [SuperadminDashboardController::class, 'calendar'])->name('calendar');
    Route::get('/search', [SuperadminDashboardController::class, 'advancedSearch'])->name('search');
    Route::get('/support', [SuperadminDashboardController::class, 'support'])->name('support');
    
    // GDPR and Data Management Routes
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/export', [SuperadminDashboardController::class, 'exportData'])->name('export');
        Route::get('/retention-policy', [SuperadminDashboardController::class, 'dataRetentionPolicy'])->name('retention-policy');
        Route::post('/purge-old-data', [SuperadminDashboardController::class, 'purgeOldData'])->name('purge-old-data');
    });
    
    // System Configuration Routes
    Route::prefix('config')->name('config.')->group(function () {
        Route::get('/email-settings', [SuperadminDashboardController::class, 'emailSettings'])->name('email-settings');
        Route::get('/sms-settings', [SuperadminDashboardController::class, 'smsSettings'])->name('sms-settings');
        Route::get('/api-settings', [SuperadminDashboardController::class, 'apiSettings'])->name('api-settings');
        Route::post('/update-settings', [SuperadminDashboardController::class, 'updateSystemSettings'])->name('update-settings');
    });
    
    // Log Management Routes
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/activity', [SuperadminDashboardController::class, 'activityLogs'])->name('activity');
        Route::get('/error', [SuperadminDashboardController::class, 'errorLogs'])->name('error');
        Route::get('/security', [SuperadminDashboardController::class, 'securityLogs'])->name('security');
        Route::get('/api', [SuperadminDashboardController::class, 'apiLogs'])->name('api');
        Route::post('/clear-logs', [SuperadminDashboardController::class, 'clearLogs'])->name('clear');
    });
    
    // Report Generation Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/generate', [SuperadminDashboardController::class, 'generateReports'])->name('generate');
        Route::post('/custom-report', [SuperadminDashboardController::class, 'generateCustomReport'])->name('custom');
        Route::get('/scheduled', [SuperadminDashboardController::class, 'scheduledReports'])->name('scheduled');
        Route::post('/schedule-report', [SuperadminDashboardController::class, 'scheduleReport'])->name('schedule');
    });
});

// Shared Admin/Superadmin Routes
Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::get('/dashboard/roles/permissions', [DashboardController::class, 'permissionsIndex'])->name('dashboard.roles.permissions.index');
    Route::get('/dashboard/roles/permissions/{id}/show', [DashboardController::class, 'showPermission'])->name('dashboard.roles.permissions.show');
});

// Error page testing routes (only available in local/development environment)
if (app()->environment(['local', 'development'])) {
    Route::get('/error/404', function () {
        abort(404);
    });
    
    Route::get('/error/500', function () {
        abort(500);
    });
}

// =====================================
// WISHLIST ROUTES
// =====================================
Route::prefix('wishlists')->name('wishlists.')->group(function () {
    Route::post('/add', [WishlistController::class, 'store'])->name('add');
    Route::delete('/remove/{id}', [WishlistController::class, 'destroy'])->name('remove');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::get('/check', [WishlistController::class, 'checkWishlist'])->name('check');
    Route::get('/count', [WishlistController::class, 'getWishlistCount'])->name('count');
    Route::get('/', [WishlistController::class, 'index'])->name('index');
});

// Keep the old routes for backward compatibility
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::post('/add', [WishlistController::class, 'store'])->name('add');
    Route::post('/remove/{id}', [WishlistController::class, 'destroy'])->name('remove');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::get('/check', [WishlistController::class, 'checkWishlist'])->name('check');
    Route::get('/count', [WishlistController::class, 'getWishlistCount'])->name('count');
});

// Check if user is authenticated
Route::get('/check-auth', function () {
    return response()->json([
        'isLoggedIn' => auth()->check()
    ]);
})->name('check-auth');

// =====================================
// SMART AUTO FILL ROUTES
// =====================================
Route::prefix('api/autofill')->middleware('auth')->name('autofill.')->group(function () {
    Route::get('/passenger-suggestions', [SmartAutoFillController::class, 'getPassengerSuggestions'])->name('passenger-suggestions');
    Route::post('/process-document', [SmartAutoFillController::class, 'processDocumentUpload'])->name('process-document');
    Route::post('/save-passenger-profile', [SmartAutoFillController::class, 'savePassengerProfile'])->name('save-passenger-profile');
    Route::get('/smart-recommendations', [SmartAutoFillController::class, 'getSmartRecommendations'])->name('smart-recommendations');
    Route::post('/scan-passport', [SmartAutoFillController::class, 'scanPassport'])->name('scan-passport');
    Route::post('/validate-passport', [SmartAutoFillController::class, 'validatePassportData'])->name('validate-passport');
});

// =====================================
// SEO ROUTES
// =====================================
Route::prefix('seo')->group(function () {
    Route::get('/sitemap.xml', [SEOController::class, 'sitemap'])->name('sitemap');
    Route::get('/sitemap-index.xml', [SEOController::class, 'sitemapIndex'])->name('sitemap.index');
    Route::get('/sitemap-pages.xml', [SEOController::class, 'pagesSitemap'])->name('sitemap.pages');
    Route::get('/sitemap-flights.xml', [SEOController::class, 'flightsSitemap'])->name('sitemap.flights');
    Route::get('/sitemap-hotels.xml', [SEOController::class, 'hotelsSitemap'])->name('sitemap.hotels');
    Route::get('/sitemap-destinations.xml', [SEOController::class, 'destinationsSitemap'])->name('sitemap.destinations');
    Route::get('/robots.txt', [SEOController::class, 'robots'])->name('robots');
    Route::get('/manifest.json', [SEOController::class, 'manifest'])->name('manifest');
    Route::get('/opensearch.xml', [SEOController::class, 'opensearch'])->name('opensearch');
    Route::get('/google-verification', [SEOController::class, 'getGoogleSearchConsoleVerification'])->name('google.verification');
    Route::get('/tracking-scripts', [SEOController::class, 'getAllTrackingScripts'])->name('tracking.scripts');
    Route::get('/performance-metrics', [SEOController::class, 'getPerformanceMetrics'])->name('performance.metrics');
    Route::post('/submit-sitemap', [SEOController::class, 'submitSitemapToSearchEngines'])->name('submit.sitemap');
    Route::get('/{file}', [SEOController::class, 'verification'])->name('verification');
});

// =====================================
// REFERRAL ROUTES
// =====================================
Route::prefix('referrals')->middleware('auth')->name('referrals.')->group(function () {
    Route::get('/', [ReferralController::class, 'index'])->name('index');
    Route::post('/submit', [ReferralController::class, 'submit'])->name('submit');
    Route::get('/stats', [ReferralController::class, 'stats'])->name('stats');
    Route::get('/{referral}', [ReferralController::class, 'show'])->name('show');
    
    // Admin/Superadmin only routes
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::post('/{referral}/generate-code', [ReferralController::class, 'generateCode'])->name('generate-code');
        Route::post('/{referral}/approve', [ReferralController::class, 'approve'])->name('approve');
        Route::post('/{referral}/reject', [ReferralController::class, 'reject'])->name('reject');
        Route::post('/{referral}/complete', [ReferralController::class, 'markCompleted'])->name('complete');
    });
});

// =====================================
// PROFILE ROUTES
// =====================================
Route::prefix('profile')->middleware('auth')->name('profile.')->group(function () {
    // User profile routes
    Route::get('/user', [ProfileController::class, 'userProfile'])->name('user');
    Route::post('/user/update', [ProfileController::class, 'updateUserProfile'])->name('user.update');
    
    // Admin profile routes
    Route::get('/admin', [ProfileController::class, 'adminProfile'])->name('admin');
    Route::get('/admin/users', [ProfileController::class, 'adminUsers'])->name('admin.users');
    
    // Superadmin profile routes
    Route::get('/superadmin', [ProfileController::class, 'superadminProfile'])->name('superadmin');
    Route::get('/superadmin/users', [ProfileController::class, 'superadminUsers'])->name('superadmin.users');
    
    // Common routes
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::get('/user-details/{id}', [ProfileController::class, 'getUserDetails'])->name('user-details');
    Route::post('/user-status/{id}', [ProfileController::class, 'updateUserStatus'])->name('user-status');
    Route::delete('/user/{id}', [ProfileController::class, 'deleteUser'])->name('delete-user');
    Route::get('/export-users', [ProfileController::class, 'exportUsers'])->name('export-users');
});

// =====================================
// GDPR ROUTES
// =====================================
Route::prefix('gdpr')->middleware('auth')->name('gdpr.')->group(function () {
    Route::get('/consent', [GDPRController::class, 'consentForm'])->name('consent');
    Route::post('/update-consent', [GDPRController::class, 'updateConsent'])->name('update-consent');
    Route::get('/export-data', [GDPRController::class, 'exportData'])->name('export-data');
    Route::post('/delete-account', [GDPRController::class, 'deleteAccount'])->name('delete-account');
    Route::get('/privacy-policy', function() {
        return view('gdpr.privacy-policy');
    })->name('privacy-policy');
    Route::get('/cookie-policy', function() {
        return view('gdpr.cookie-policy');
    })->name('cookie-policy');
    Route::get('/data-processing-agreement', function() {
        return view('gdpr.data-processing');
    })->name('data-processing');
});

// =====================================
// EXCHANGE RATE ROUTES
// =====================================
Route::prefix('exchange-rate')->name('exchange-rate.')->group(function () {
    Route::get('/usd-to-kes', [ExchangeRateController::class, 'getUsdToKesRate'])->name('usd-to-kes');
});

// =====================================
// HOTEL SEARCH ROUTES
// =====================================
Route::prefix('hotels')->name('hotels.')->group(function () {
    Route::post('/search', [HotelSearchController::class, '__invoke'])->name('search');
    Route::get('/search', function() { 
        return view('hotels.search'); 
    })->name('search.form');
});

// =====================================
// ACTIVITY ROUTES
// =====================================
Route::prefix('activity')->middleware('auth')->name('activity.')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    Route::get('/notifications', [ActivityController::class, 'getNotifications'])->name('notifications');
    Route::get('/statistics', [ActivityController::class, 'getStatistics'])->name('statistics');
    Route::post('/mark-read', [ActivityController::class, 'markAsRead'])->name('mark-read');
    Route::get('/export', [ActivityController::class, 'export'])->name('export');
});

// =====================================
// AI PRICE PREDICTION ROUTES
// =====================================
Route::prefix('ai/price-prediction')->name('ai.price-prediction.')->group(function () {
    Route::get('/', [AIPricePredictionController::class, 'getPricePrediction'])->name('index');
    Route::get('/route-history', [AIPricePredictionController::class, 'getRouteHistory'])->name('route-history');
    Route::post('/generate', [AIPricePredictionController::class, 'generatePrediction'])->name('generate');
    Route::get('/booking-time', [AIPricePredictionController::class, 'suggestBestBookingTime'])->name('booking-time');
});

// =====================================
// AI TRIP PLANNER ROUTES
// =====================================
Route::prefix('ai/trip-planner')->middleware('auth')->name('ai.trip-planner.')->group(function () {
    Route::post('/generate', [AITripPlannerController::class, 'generateTripPlan'])->name('generate');
    Route::post('/layover', [AITripPlannerController::class, 'generateLayoverGuide'])->name('layover');
    Route::post('/packing-list', [AITripPlannerController::class, 'generatePackingList'])->name('packing-list');
});

// =====================================
// AI CHATBOT ROUTES
// =====================================
Route::prefix('ai/chatbot')->name('ai.chatbot.')->group(function () {
    Route::post('/send', [AIChatbotController::class, 'sendMessage'])->name('send');
    Route::get('/history', [AIChatbotController::class, 'getChatHistory'])->middleware('auth')->name('history');
});

// =====================================
// WISHLIST ROUTES
// =====================================
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    // Public wishlist routes
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add', [WishlistController::class, 'store'])->name('store');
    Route::delete('/{id}', [WishlistController::class, 'destroy'])->name('destroy');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::post('/check', [WishlistController::class, 'checkWishlist'])->name('check');
    Route::get('/count', [WishlistController::class, 'getWishlistCount'])->name('count');
    
    // Guest wishlist routes
    Route::get('/guest/count', [WishlistController::class, 'getGuestWishlistCount'])->name('guest.count');
    Route::post('/guest/process', [WishlistController::class, 'processGuestWishlist'])->name('guest.process');
    Route::delete('/guest/clear', [WishlistController::class, 'clearGuestWishlist'])->name('guest.clear');
});

// =====================================
// WISHLIST DASHBOARD ROUTES
// =====================================
Route::middleware(['auth'])->group(function () {
    Route::get('/superadmin/wishlists', [WishlistController::class, 'superadminIndex'])->middleware('role:superadmin')->name('superadmin.wishlists');
    Route::get('/admin/wishlists', [WishlistController::class, 'adminIndex'])->middleware('role:admin')->name('admin.wishlists');
    Route::get('/user/wishlists', [WishlistController::class, 'userIndex'])->middleware('role:user')->name('user.wishlists');
});

// =====================================
// FLIGHT PRICING ROUTES
// =====================================
Route::prefix('flight-pricing')->name('flight-pricing.')->group(function () {
    Route::post('/', [GetPriceController::class, '__invoke'])->name('index');
    Route::get('/east-african-offers', [GetPriceController::class, 'getEastAfricanOffers'])->name('east-african-offers');
});

// =====================================
// Old User Management Routes - Moved to Superadmin Dashboard Controller


/*
|--------------------------------------------------------------------------
| Amadeus API Routes (imported from amadeus-api.php)
|--------------------------------------------------------------------------
*/

Route::prefix('api/amadeus')->group(function () {
    // Flight routes
    Route::get('/flights/search', [AmadeusController::class, 'searchFlightOffers']);
    Route::get('/flights/{id}', [AmadeusController::class, 'getFlightOffer']);

    // Hotel routes
    Route::get('/hotels/search', [AmadeusController::class, 'searchHotelOffers']);
    Route::get('/hotels/{id}', [AmadeusController::class, 'getHotelOffer']);

    // Activity routes
    Route::get('/activities/search', [AmadeusController::class, 'searchActivities']);
    Route::get('/activities/{id}', [AmadeusController::class, 'getActivity']);
});

// =====================================
// UNIFIED BOOKING ROUTES (OrderFlightController handles all booking types)
// =====================================
Route::middleware(['auth'])->group(function () {
    // Unified Booking Routes
    Route::post('/api/flight-bookings', [OrderFlightController::class, 'storeFlightBooking'])->name('flight.bookings.store');
    Route::post('/api/hotel-bookings', [OrderFlightController::class, 'storeHotelBooking'])->name('hotel.bookings.store');
    Route::post('/api/tour-bookings', [OrderFlightController::class, 'storeTourBooking'])->name('tour.bookings.store');
    
    // Booking Management Routes
    Route::get('/my-bookings', [OrderFlightController::class, 'index'])->name('bookings.index');
    Route::get('/my-bookings/{reference}', [OrderFlightController::class, 'showBooking'])->name('bookings.detail');
    Route::post('/my-bookings/{reference}/cancel', [OrderFlightController::class, 'cancelBooking'])->name('bookings.cancel');
    
    // Unified booking routes
    Route::get('/bookings/{id}', [OrderFlightController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/download', [OrderFlightController::class, 'download'])->name('bookings.download');
    Route::get('/bookings', [UserDashboardController::class, 'allBookings'])->name('user.bookings.all');
    
    // Flight Booking Routes (POST only - GET is handled by the public routes above)
    Route::post('/flights/search', [FlightBookingController::class, 'searchFlights'])->name('flights.search.results');
    Route::post('/flights/book', [FlightBookingController::class, 'bookFlight'])->name('flights.book');
    Route::get('/flights/booking/{booking}/payment', [FlightBookingController::class, 'showPayment'])->name('flights.booking.payment');
    Route::post('/flights/booking/{booking}/payment', [FlightBookingController::class, 'processPayment'])->name('flights.payment.process');
    
    // Hotel Booking Routes (user dashboard - public search is at /hotels/search with name hotels.search.form)
    Route::get('/user/hotels/search', [HotelBookingController::class, 'search'])->name('user.hotels.search');
    Route::post('/user/hotels/search', [HotelBookingController::class, 'searchHotels'])->name('user.hotels.search.results');
    Route::post('/hotels/book', [HotelBookingController::class, 'bookHotel'])->name('hotels.book');
    Route::get('/hotels/booking/{booking}/payment', [HotelBookingController::class, 'showPayment'])->name('hotels.booking.payment');
    Route::post('/hotels/booking/{booking}/payment', [HotelBookingController::class, 'processPayment'])->name('hotels.payment.process');
    
    // Pending Payments
    Route::get('/pending-payments', [FlightBookingController::class, 'pendingPayments'])->name('pending.payments');
    
    // Individual booking type routes for backward compatibility
    Route::get('/flights/book/{offerId}', [FlightBookingController::class, 'show'])->name('flight.bookings.show');
    Route::get('/hotels/book/{offerId}', [HotelBookingController::class, 'show'])->name('hotel.bookings.show');
    Route::get('/tours/book/{offerId}', [TourBookingController::class, 'show'])->name('tour.bookings.show');
    
    // General Booking Confirmation Route
    Route::get('/bookings/confirmation/{type}/{reference}', function($type, $reference) {
        return view('bookings.confirmation', compact('type', 'reference'));
    })->name('bookings.confirmation');
    
    // Admin/Superadmin Booking Routes
    Route::middleware(['role:admin,superadmin'])->group(function () {
        // Admin Flight Booking
        Route::get('/admin/flights/book', function() {
            $users = \App\Models\User::where('id', '!=', auth()->id())->get();
            return view('dashboard.admin.flight-booking', compact('users'));
        })->name('admin.flights.book.form');
        Route::post('/admin/flights/book', [FlightBookingController::class, 'adminBookFlight'])->name('admin.flights.book');
        Route::post('/admin/flights/bulk-book', [FlightBookingController::class, 'bulkBookFlight'])->name('admin.flights.bulk-book');
        
        // Admin Hotel Booking
        Route::get('/admin/hotels/book', function() {
            $users = \App\Models\User::where('id', '!=', auth()->id())->get();
            return view('dashboard.admin.hotel-booking', compact('users'));
        })->name('admin.hotels.book.form');
        Route::post('/admin/hotels/book', [HotelBookingController::class, 'adminBookHotel'])->name('admin.hotels.book');
        Route::post('/admin/hotels/bulk-book', [HotelBookingController::class, 'bulkBookHotel'])->name('admin.hotels.bulk-book');
        
        // Admin booking management
        Route::get('/admin/bookings', [FlightBookingController::class, 'adminBookings'])->name('admin.bookings.index');
    });
});

// Test route for debugging Amadeus API
Route::get('/test-amadeus', function () {
    try {
        $controller = new \App\Http\Controllers\API\AmadeusController();
        $request = new \Illuminate\Http\Request();
        
        // Test flight search
        $flights = $controller->searchFlightOffers($request);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Amadeus API test completed',
            'flights' => $flights->getData()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// East African Travel Search Routes
Route::prefix('api/east-africa')->group(function () {
    Route::get('/popular-routes', [AmadeusController::class, 'getPopularEastAfricanRoutes']);
    Route::get('/destinations', [AmadeusController::class, 'getEastAfricanDestinations']);
    Route::post('/multi-city-search', [AmadeusController::class, 'searchMultiCityRoutes']);
});
