<div class="container py-5" style="background-color: #ffffff; min-height: 80vh;">
    <div class="row g-4">
        <!-- Flight Details Card -->
        <div class="col-lg-7">
            <div class="card shadow-lg h-100">
                <div class="card-header" style="background-color: #2b2e3d;">
                    <h3 class="mb-0 text-white">
                        <i class="fas fa-plane-departure me-2" style="color: #e3a300;"></i>
                        Flight Details
                    </h3>
                </div>
                <div class="card-body">
                    @if(isset($data->flightOffers[0]))
                        <!-- ... existing flight info code ... -->
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Flight details are not available
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Authentication Section -->
        <div class="col-lg-5">
            <div class="card shadow-lg h-100">
                <div class="card-header" style="background-color: #2b2e3d;">
                    <ul class="nav nav-tabs card-header-tabs" id="authTabs">
                        <li class="nav-item">
                            <a class="nav-link active auth-tab" data-bs-toggle="tab" href="#login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link auth-tab" data-bs-toggle="tab" href="#signup">
                                <i class="fas fa-user-plus me-2"></i>Sign Up
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body" style="background-color: #2b2e3d;">
                    <div class="tab-content">
                        <!-- Login Form -->
                        <div class="tab-pane fade show active" id="login">
                            <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #e3a300;">
                                            <i class="fas fa-envelope text-white"></i>
                                        </span>
                                        <input type="email" class="form-control custom-input" 
                                               name="email" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #e3a300;">
                                            <i class="fas fa-lock text-white"></i>
                                        </span>
                                        <input type="password" class="form-control custom-input" 
                                               name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn w-100" style="background-color: #e3a300; color: white;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </form>
                            
                            <!-- Social Login Options -->
                            <div class="text-center my-4">
                                <p class="text-white">Or continue with</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <!-- Google Login -->
                                    <a href="{{ route('social.login', 'google') }}" class="btn btn-light px-3 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-google">
                                            <path d="M22 8v8a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4Z"></path>
                                            <polyline points="22 8 13.5 13.5 12 15 10.5 13.5 2 8"></polyline>
                                        </svg>
                                    </a>
                                    
                                    <!-- Facebook Login -->
                                    <div class="fb-login-button" 
                                        data-width="" 
                                        data-size="large" 
                                        data-button-type="continue_with" 
                                        data-layout="default" 
                                        data-auto-logout-link="false" 
                                        data-use-continue-as="false"
                                        data-scope="public_profile,email"
                                        data-onlogin="checkLoginState()">
                                    </div>
                                    
                                    <!-- Twitter (X) Login -->
                                    <a href="{{ route('social.login', 'twitter') }}" class="btn btn-dark px-3 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter">
                                            <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sign Up Form -->
                        <div class="tab-pane fade" id="signup">
                            <form action="{{ route('register') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #e3a300;">
                                            <i class="fas fa-user text-white"></i>
                                        </span>
                                        <input type="text" class="form-control custom-input" 
                                               name="name" placeholder="Full Name" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #e3a300;">
                                            <i class="fas fa-envelope text-white"></i>
                                        </span>
                                        <input type="email" class="form-control custom-input" 
                                               name="email" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #e3a300;">
                                            <i class="fas fa-lock text-white"></i>
                                        </span>
                                        <input type="password" class="form-control custom-input" 
                                               name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn w-100" style="background-color: #e3a300; color: white;">
                                    <i class="fas fa-user-plus me-2"></i>Sign Up
                                </button>
                            </form>
                            
                            <!-- Social Signup Options -->
                            <div class="text-center my-4">
                                <p class="text-white">Or sign up with</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <!-- Google Signup -->
                                    <a href="{{ route('social.login', 'google') }}" class="btn btn-light px-3 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-google">
                                            <path d="M22 8v8a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4Z"></path>
                                            <polyline points="22 8 13.5 13.5 12 15 10.5 13.5 2 8"></polyline>
                                        </svg>
                                    </a>
                                    
                                    <!-- Facebook Signup -->
                                    <div class="fb-login-button" 
                                        data-width="" 
                                        data-size="large" 
                                        data-button-type="sign_up" 
                                        data-layout="default" 
                                        data-auto-logout-link="false" 
                                        data-use-continue-as="false"
                                        data-scope="public_profile,email"
                                        data-onlogin="checkLoginState()">
                                    </div>
                                    
                                    <!-- Twitter (X) Signup -->
                                    <a href="{{ route('social.login', 'twitter') }}" class="btn btn-dark px-3 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter">
                                            <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-tab {
    color: #ffffff !important;
    border: none;
    padding: 1rem 1.5rem;
}

.auth-tab:hover {
    color: #e3a300 !important;
}

.auth-tab.active {
    color: #e3a300 !important;
    background-color: transparent !important;
    border-bottom: 2px solid #e3a300 !important;
}

.custom-input {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background-color: rgba(255, 255, 255, 0.05);
    color: #ffffff;
}

.custom-input:focus {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: #e3a300;
    color: #ffffff;
    box-shadow: none;
}

.custom-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.input-group-text {
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(227, 163, 0, 0.3);
}
</style>

<!-- Facebook SDK -->
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{{ env('FACEBOOK_APP_ID') }}',
      cookie     : true,
      xfbml      : true,
      version    : 'v17.0'
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
   
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        // User is logged in via Facebook
        var accessToken = response.authResponse.accessToken;
        var userId = response.authResponse.userID;
        
        // Redirect to your Laravel callback route with the token
        window.location.href = '/auth/facebook/callback?token=' + accessToken + '&userId=' + userId;
      }
    });
  }
</script>