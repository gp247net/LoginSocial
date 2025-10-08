{{-- 
    Render social login buttons
    Usage: @include('Plugins/LoginSocial::render', ['guard' => 'customer', 'providers' => ['facebook', 'google', 'github']])
    
    Parameters:
    - guard: Authentication guard (admin, customer, vendor, pmo)
    - providers: Array of providers to show
    - buttonClass: CSS class for buttons
    - title: Show title (boolean)
    - description: Show description (boolean)
    - forceShow: Force show even if user is logged in (boolean)
    
    Note: By default, social login buttons only show when user is NOT logged in for the specified guard.
    Use forceShow=true to always show the buttons.
--}}

@php
    $guard = $guard ?? 'customer';
    $providers = $providers ?? ['facebook', 'google', 'github'];
    $buttonClass = $buttonClass ?? 'btn btn-block mb-2';
    $forceShow = $forceShow ?? false; // Force show even if logged in
    
    // Check if user is already logged in for the specified guard
    $isLoggedIn = auth($guard)->check();
@endphp

@if(!$isLoggedIn || $forceShow)
<div class="social-login-wrapper">
    @if(isset($title) && $title)
        <h4 class="social-login-title">{{ gp247_language_render('Plugins/LoginSocial::lang.social_login_title') }}</h4>
    @endif
    
    @if(isset($description) && $description)
        <p class="social-login-description">{{ gp247_language_render('Plugins/LoginSocial::lang.social_login_description') }}</p>
    @endif
    
    <div class="btn-group">
        @foreach($providers as $provider)
            @if(\App\GP247\Plugins\LoginSocial\Helpers\ProviderConfig::isProviderEnabled($provider))
                <a href="{{ route('social.redirect', ['provider' => $provider, 'guard' => $guard]) }}" 
                   class="{{ $buttonClass }} btn-{{ $provider }}"
                   title="{{ gp247_language_render('Plugins/LoginSocial::lang.connecting_to') }} {{ ucfirst($provider) }}">
                    <i class="fab fa-{{ $provider }}"></i> 
                    {{ gp247_language_render('Plugins/LoginSocial::lang.login_with_' . $provider) ?? 'Login with ' . ucfirst($provider) }}
                </a>
            @endif
        @endforeach
    </div>
</div>

<style>
.social-login-wrapper {
    margin: 20px 0;
    padding: 20px 0;
    border-top: 1px solid #eee;
    position: relative;
}

.social-login-wrapper::before {
    content: '';
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #1877f2, transparent);
}

.social-login-title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.social-login-description {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
    text-align: center;
}

/* Social Login Buttons Container */
.social-login-wrapper .btn-group {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: nowrap;
    align-items: stretch;
    max-width: 600px;
    margin: 0 auto;
}

/* Individual Social Buttons */
.social-login-wrapper .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    min-width: 140px;
    font-size: 14px;
    flex: 1;
    max-width: 180px;
}

.social-login-wrapper .btn i {
    margin-right: 8px;
    font-size: 16px;
}

/* Facebook Button */
.btn-facebook {
    background: #1877f2;
    color: white;
    box-shadow: 0 2px 4px rgba(24, 119, 242, 0.3);
    border: 1px solid #1877f2;
}

.btn-facebook:hover {
    background: #166fe5;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(24, 119, 242, 0.4);
    border-color: #166fe5;
}

/* Google Button */
.btn-google {
    background: white;
    color: #333;
    border: 1px solid #dadce0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-google:hover {
    background: #f8f9fa;
    color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    border-color: #dadce0;
}

/* GitHub Button */
.btn-github {
    background: #333;
    color: white;
    border: 1px solid #333;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.btn-github:hover {
    background: #000;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    border-color: #000;
}

/* Responsive Design */
@media (max-width: 768px) {
    .social-login-wrapper .btn-group {
        flex-direction: column;
        gap: 10px;
    }
    
    .social-login-wrapper .btn {
        width: 100%;
        min-width: auto;
        max-width: none;
        flex: none;
    }
}

@media (max-width: 480px) {
    .social-login-wrapper .btn {
        padding: 10px 12px;
        font-size: 13px;
    }
    
    .social-login-wrapper .btn i {
        font-size: 14px;
        margin-right: 6px;
    }
}

/* Loading State */
.social-login-wrapper .btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Focus State for Accessibility */
.social-login-wrapper .btn:focus {
    outline: 2px solid #1877f2;
    outline-offset: 2px;
}

/* Loading Animation */
.social-login-wrapper .btn.loading {
    position: relative;
    color: transparent;
}

.social-login-wrapper .btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Pulse Animation for Attention */
.social-login-wrapper .btn.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to social login buttons
    const socialButtons = document.querySelectorAll('.social-login-wrapper .btn');
    
    socialButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Add loading class
            this.classList.add('loading');
            
            // Disable button to prevent multiple clicks
            this.style.pointerEvents = 'none';
            
            // Show loading text
            const originalText = this.innerHTML;
            this.setAttribute('data-original-text', originalText);
            
            // Optional: Add timeout to remove loading state
            setTimeout(() => {
                this.classList.remove('loading');
                this.style.pointerEvents = 'auto';
            }, 5000); // 5 seconds timeout
        });
    });
});
</script>

@endif